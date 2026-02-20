#!/usr/bin/env php
<?php
/**
 * YOURLS source extractor — generates a Markdown reference for Claude context.
 * Extracts: functions (with docblocks), hooks (filters/actions + args), constants, DB schema.
 *
 * Usage:
 *   php yourls-extractor.php /path/to/yourls
 *   php yourls-extractor.php /path/to/yourls --output yourls-reference.md
 *   php yourls-extractor.php /path/to/yourls --output yourls-reference.md --debug
 */

// ---------------------------------------------------------------------------
// Config
// ---------------------------------------------------------------------------

$EXCLUDE_DIRS = ['.git*', '.idea', '.vs', 'coverage', 'node_modules', '.phpunit*',
                 'css', 'images', 'js',
                 'vendor', 'user', 'tests',
                 ];

// Glob patterns matched against the filename only (basename), not the full path.
// Supports * and ? wildcards.
$EXCLUDE_FILES = ['test*.php', 'bench*.php'];

// ---------------------------------------------------------------------------
// Data structures (plain arrays)
// ---------------------------------------------------------------------------

function make_function(string $name, string $signature, string $docblock, string $file, int $line): array {
    return compact('name', 'signature', 'docblock', 'file', 'line');
}

function make_hook(string $kind, string $name, array $args, string $file, int $line): array {
    return compact('kind', 'name', 'args', 'file', 'line');
}

function make_constant(string $name, string $value, string $docblock, string $file, int $line): array {
    return compact('name', 'value', 'docblock', 'file', 'line');
}

function make_table(string $constant, array $columns, string $file, int $line): array {
    return compact('constant', 'columns', 'file', 'line');
}

// ---------------------------------------------------------------------------
// Parsing helpers
// ---------------------------------------------------------------------------

function clean_docblock(string $raw): string {
    if ($raw === '') return '';
    $lines = explode("\n", $raw);
    $cleaned = [];
    foreach ($lines as $line) {
        $line = trim($line, " \t\r\n/*");
        if ($line !== '') {
            $cleaned[] = $line;
        }
    }
    return implode(' ', $cleaned);
}

function parse_hook_args(string $raw): array {
    preg_match_all('/\$\w+/', $raw, $matches);
    return $matches[0] ?? [];
}

function line_number(string $content, int $pos): int {
    return substr_count($content, "\n", 0, $pos) + 1;
}

// ---------------------------------------------------------------------------
// SQL schema parser
// ---------------------------------------------------------------------------

/**
 * Parse CREATE TABLE statements from a $create_tables[CONST] = 'CREATE TABLE ...' pattern.
 * Handles PHP string concatenation spread across multiple lines.
 */
function parse_sql_schema(string $content, string $filepath, bool $debug = false): array {
    $tables = [];

    // Step 1: find each $create_tables[YOURLS_DB_TABLE_*] = assignment
    $re = '/\$create_tables\[(?P<const>YOURLS_DB_TABLE_\w+)\]\s*=/s';
    preg_match_all($re, $content, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

    if ($debug && str_contains($content, 'create_tables')) {
        fwrite(STDERR, "[debug] {$filepath}: found " . count($matches) . " \$create_tables assignment(s)\n");
    }

    foreach ($matches as $m) {
        $constant = $m['const'][0];
        $line     = line_number($content, $m[0][1]);

        // Step 2: grab a generous chunk after the '=' (2500 chars covers any table def)
        $start = $m[0][1] + strlen($m[0][0]);
        $chunk = substr($content, $start, 2500);

        // Step 3: strip PHP string concatenation across lines:  '  .  \n    '
        $sql = preg_replace("/'\s*\.\s*\n\s*'/s", '', $chunk);
        // Also inline concatenation:  '.'
        $sql = preg_replace("/'\s*\.\s*'/", '', $sql);

        // Remove backslash-escaped single quotes inside SQL strings
        $sql = str_replace("\\'", '', $sql);

        // Step 4: cut at the closing '; of the PHP assignment
        if (preg_match("/^(.*?)'\s*;/s", $sql, $end_m)) {
            $sql = $end_m[1];
        }

        // Step 5: strip leading quote/whitespace
        $sql = ltrim($sql, " \t\n\r'");

        if ($debug) {
            fwrite(STDERR, "[debug] Reassembled SQL for {$constant}:\n" . substr($sql, 0, 400) . "\n---\n");
        }

        // Step 6: extract everything inside the outer parens of CREATE TABLE ( ... )
        if (!preg_match('/CREATE\s+TABLE\b[^(]+\((.+)\)\s*(?:DEFAULT|AUTO_INCREMENT)/si', $sql, $body)) {
            if ($debug) fwrite(STDERR, "[debug] Could not match CREATE TABLE body for {$constant}\n");
            continue;
        }

        $columns  = parse_create_table_body($body[1]);
        $tables[] = make_table($constant, $columns, $filepath, $line);
    }

    return $tables;
}

/**
 * Parse the body of a CREATE TABLE ( ... ) into an array of column descriptors.
 */
function parse_create_table_body(string $body): array {
    $columns = [];

    $parts = explode(',', $body);

    foreach ($parts as $part) {
        $part = trim($part);
        if ($part === '') continue;

        // Column definition: `name` type ...
        if (preg_match('/^`(?P<col>\w+)`\s+(?P<type>\S+(?:\(\d+\))?)\s*(?P<rest>.*)$/si', $part, $cm)) {
            $col  = $cm['col'];
            $type = strtolower($cm['type']);
            $rest = trim($cm['rest']);

            $flags = [];
            if (stripos($rest, 'NOT NULL') !== false)         $flags[] = 'NOT NULL';
            elseif (stripos($rest, 'DEFAULT NULL') !== false) $flags[] = 'NULL';
            if (stripos($rest, 'auto_increment') !== false)   $flags[] = 'AUTO_INCREMENT';
            if (preg_match("/DEFAULT\s+'?([^',\s]+)'?/i", $rest, $dm)) $flags[] = "DEFAULT {$dm[1]}";

            $columns[] = "`{$col}` {$type}" . ($flags ? ' — ' . implode(', ', $flags) : '');

        // PRIMARY KEY
        } elseif (stripos($part, 'PRIMARY KEY') !== false) {
            $columns[] = trim($part);

        // KEY / INDEX
        } elseif (preg_match('/KEY\s+`?(\w+)`?\s*\(([^)]+)\)/i', $part, $km)) {
            $columns[] = "KEY `{$km[1]}` ({$km[2]})";
        }
    }

    return $columns;
}

// ---------------------------------------------------------------------------
// File parser
// ---------------------------------------------------------------------------

function parse_file(string $filepath, bool $debug = false): array {
    $functions = [];
    $hooks     = [];
    $constants = [];
    $tables    = [];

    $content = @file_get_contents($filepath);
    if ($content === false) return [$functions, $hooks, $constants, $tables];

    // ---- Functions ----
    $fn_re = '/(?P<doc>\/\*\*.*?\*\/\s*)?function\s+(?P<n>yourls_\w+)\s*\((?P<params>[^)]*)\)/s';
    preg_match_all($fn_re, $content, $fn_matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
    foreach ($fn_matches as $m) {
        $name        = $m['n'][0];
        $params      = preg_replace('/\s+/', ' ', trim($m['params'][0]));
        $sig         = "{$name}({$params})";
        $doc         = clean_docblock($m['doc'][0] ?? '');
        $line        = line_number($content, $m[0][1]);
        $functions[] = make_function($name, $sig, $doc, $filepath, $line);
    }

    // ---- Filters ----
    $filter_re = "/yourls_apply_filters\s*\(\s*'(?P<n>[^']+)'(?P<args>.*?)\)/s";
    preg_match_all($filter_re, $content, $filter_matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
    foreach ($filter_matches as $m) {
        $hooks[] = make_hook('filter', $m['n'][0], parse_hook_args($m['args'][0]), $filepath, line_number($content, $m[0][1]));
    }

    // ---- Actions ----
    $action_re = "/yourls_do_action\s*\(\s*'(?P<n>[^']+)'(?P<args>.*?)\)/s";
    preg_match_all($action_re, $content, $action_matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
    foreach ($action_matches as $m) {
        $hooks[] = make_hook('action', $m['n'][0], parse_hook_args($m['args'][0]), $filepath, line_number($content, $m[0][1]));
    }

    // ---- Constants ----
    $const_re = "/(?P<doc>\/\*\*.*?\*\/\s*)?define\s*\(\s*'(?P<n>[A-Z_]{4,})'\s*,\s*(?P<value>[^)]+)\)/s";
    preg_match_all($const_re, $content, $const_matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
    foreach ($const_matches as $m) {
        $name        = $m['n'][0];
        $value       = rtrim(trim($m['value'][0]), ')');
        $doc         = clean_docblock($m['doc'][0] ?? '');
        $line        = line_number($content, $m[0][1]);
        $constants[] = make_constant($name, $value, $doc, $filepath, $line);
    }

    // ---- Implicit constants (YOURLS_* referenced but not defined here) ----
    // Two passes: defined('YOURLS_FOO') calls, then bare YOURLS_FOO usage.
    $defined_names = array_column($constants, 'name');
    $impl_names    = [];
    $pass1_re = '/defined\s*\(\s*[\'"](?P<n>YOURLS_[A-Z0-9_]{3,})[\'"]\s*\)/';
    $pass2_re = '/(?<![\'\"(])\b(?P<n>YOURLS_[A-Z0-9_]{3,})\b(?![\'\"(])/';
    foreach ([$pass1_re, $pass2_re] as $impl_re) {
        preg_match_all($impl_re, $content, $impl_matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        foreach ($impl_matches as $m) {
            $name = $m['n'][0];
            if (in_array($name, $defined_names, true)) continue;
            if (in_array($name, $impl_names, true))    continue;
            $line          = line_number($content, $m[0][1]);
            $constants[]   = make_constant($name, '(implicit)', '', $filepath, $line);
            $defined_names[] = $name;
            $impl_names[]    = $name;
        }
    }

    // ---- SQL Schema ----
    $tables = parse_sql_schema($content, $filepath, $debug);

    return [$functions, $hooks, $constants, $tables];
}

// ---------------------------------------------------------------------------
// Directory walker
// ---------------------------------------------------------------------------

function file_is_excluded(string $filename, array $exclude_files): bool {
    foreach ($exclude_files as $pattern) {
        if (fnmatch($pattern, $filename)) return true;
    }
    return false;
}

function walk_yourls(string $root, array $exclude_dirs, array $exclude_files): array {
    $php_files = [];
    $iterator  = new RecursiveIteratorIterator(
        new RecursiveCallbackFilterIterator(
            new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
            function ($current) use ($exclude_dirs, $exclude_files) {
                if ($current->isDir()) {
                    return !file_is_excluded($current->getFilename(), $exclude_dirs);
                }
                if ($current->getExtension() !== 'php') return false;
                return !file_is_excluded($current->getFilename(), $exclude_files);
            }
        )
    );
    foreach ($iterator as $file) {
        if ($file->getExtension() === 'php') {
            $php_files[] = $file->getPathname();
        }
    }
    sort($php_files);
    return $php_files;
}

// ---------------------------------------------------------------------------
// Markdown renderer
// ---------------------------------------------------------------------------

function shorten_file(string $path, string $root): string {
    $root = rtrim($root, '/\\') . DIRECTORY_SEPARATOR;
    return str_starts_with($path, $root) ? substr($path, strlen($root)) : $path;
}

function render_markdown(array $functions, array $hooks, array $constants, array $tables, string $root): string {
    $out = [];
    $l   = function (string $line = '') use (&$out) { $out[] = $line; };

    $l('# YOURLS Reference (auto-generated)');
    $l();
    $l('> Generated from source. Use as Claude context for YOURLS development.');
    $l();

    // ---- DB Schema ----
    if ($tables) {
        $l('## Database Schema');
        $l();
        $l('Table name constants: ' . implode(', ', array_map(fn($t) => '`' . $t['constant'] . '`', $tables)));
        $l();
        foreach ($tables as $t) {
            $l("**`{$t['constant']}`**");
            foreach ($t['columns'] as $col) {
                $l("- {$col}");
            }
            $l();
        }
    }

    // ---- Functions ----
    $l('## Functions');
    $l();

    usort($functions, fn($a, $b) => [$a['file'], $a['name']] <=> [$b['file'], $b['name']]);
    $by_file = [];
    foreach ($functions as $fn) {
        $key = shorten_file($fn['file'], $root);
        $by_file[$key][] = $fn;
    }
    ksort($by_file);

    foreach ($by_file as $fname => $fns) {
        $l("### `{$fname}`");
        $l();
        foreach ($fns as $fn) {
            $l("**`{$fn['signature']}`**");
            if ($fn['docblock']) {
                $l("— {$fn['docblock']}");
            }
            $l();
        }
    }

    // ---- Filters & Actions ----
    $filters = array_filter($hooks, fn($h) => $h['kind'] === 'filter');
    $actions = array_filter($hooks, fn($h) => $h['kind'] === 'action');

    $dedup = function (array $list): array {
        $seen   = [];
        $result = [];
        foreach ($list as $h) {
            if (!isset($seen[$h['name']])) {
                $seen[$h['name']] = true;
                $result[]         = $h;
            }
        }
        usort($result, fn($a, $b) => $a['name'] <=> $b['name']);
        return $result;
    };

    $filters = $dedup($filters);
    $actions = $dedup($actions);

    $l('## Filters (`yourls_apply_filters`)');
    $l();
    $l('| Hook | Args | File |');
    $l('|------|------|------|');
    foreach ($filters as $h) {
        $args_str = $h['args'] ? implode(', ', $h['args']) : '—';
        $f        = shorten_file($h['file'], $root);
        $l("| `{$h['name']}` | `{$args_str}` | `{$f}:{$h['line']}` |");
    }
    $l();

    $l('## Actions (`yourls_do_action`)');
    $l();
    $l('| Hook | Args | File |');
    $l('|------|------|------|');
    foreach ($actions as $h) {
        $args_str = $h['args'] ? implode(', ', $h['args']) : '—';
        $f        = shorten_file($h['file'], $root);
        $l("| `{$h['name']}` | `{$args_str}` | `{$f}:{$h['line']}` |");
    }
    $l();

    // ---- Constants ----
    // Deduplicate: explicit define() wins over implicit references; keep first file occurrence.
    $seen_consts    = [];
    $deduped_consts = [];
    // Sort so explicit (non-implicit) come first — they win deduplication
    usort($constants, fn($a, $b) => ($a['value'] === '(implicit)' ? 1 : 0) <=> ($b['value'] === '(implicit)' ? 1 : 0));
    foreach ($constants as $c) {
        if (!isset($seen_consts[$c['name']])) {
            $seen_consts[$c['name']] = true;
            $deduped_consts[]        = $c;
        }
    }
    usort($deduped_consts, fn($a, $b) => $a['name'] <=> $b['name']);

    $explicit = array_values(array_filter($deduped_consts, fn($c) => $c['value'] !== '(implicit)'));
    $implicit = array_values(array_filter($deduped_consts, fn($c) => $c['value'] === '(implicit)'));

    $l('## Constants');
    $l();
    $l('| Name | Value | Description | File |');
    $l('|------|-------|-------------|------|');
    foreach ($explicit as $c) {
        $f    = shorten_file($c['file'], $root);
        $desc = mb_strlen($c['docblock']) > 80 ? mb_substr($c['docblock'], 0, 80) . '…' : $c['docblock'];
        $l("| `{$c['name']}` | `{$c['value']}` | {$desc} | `{$f}:{$c['line']}` |");
    }
    $l();

    if ($implicit) {
        $l('## Implicit Constants');
        $l();
        $l('> Referenced in code but never explicitly `define()`d. Intended to be set by the user in `config.php`.');
        $l();
        $l('| Name | First seen in |');
        $l('|------|--------------|');
        foreach ($implicit as $c) {
            $f = shorten_file($c['file'], $root);
            $l("| `{$c['name']}` | `{$f}:{$c['line']}` |");
        }
        $l();
    }

    return implode("\n", $out);
}

// ---------------------------------------------------------------------------
// Entry point
// ---------------------------------------------------------------------------

$args   = array_slice($argv, 1);
$root   = null;
$output = null;
$debug  = false;

for ($i = 0; $i < count($args); $i++) {
    if ($args[$i] === '--output' || $args[$i] === '-o') {
        $output = $args[++$i] ?? null;
    } elseif ($args[$i] === '--debug') {
        $debug = true;
    } elseif ($root === null && !str_starts_with($args[$i], '-')) {
        $root = $args[$i];
    } elseif ($output === null && !str_starts_with($args[$i], '-')) {
        $output = $args[$i];
    }
}

if ($root === null) {
    fwrite(STDERR, "Usage: php yourls-extractor.php /path/to/yourls [--output file.md] [--debug]\n");
    exit(1);
}

$root = realpath($root);
if (!$root || !is_dir($root)) {
    fwrite(STDERR, "Error: not a valid directory\n");
    exit(1);
}

$php_files = walk_yourls($root, $EXCLUDE_DIRS, $EXCLUDE_FILES);
if (!$php_files) {
    fwrite(STDERR, "Error: no PHP files found in {$root}\n");
    exit(1);
}

$all_functions = [];
$all_hooks     = [];
$all_constants = [];
$all_tables    = [];

foreach ($php_files as $fpath) {
    [$fns, $hooks, $consts, $tables] = parse_file($fpath, $debug);
    array_push($all_functions, ...$fns);
    array_push($all_hooks,     ...$hooks);
    array_push($all_constants, ...$consts);
    array_push($all_tables,    ...$tables);
}

$md = render_markdown($all_functions, $all_hooks, $all_constants, $all_tables, $root);

if ($output) {
    file_put_contents($output, $md);
    fwrite(STDERR, "Written to {$output}\n");
} else {
    echo $md;
}
