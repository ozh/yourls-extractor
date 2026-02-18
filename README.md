# yourls-extractor

A PHP CLI script that parses the [YOURLS](https://yourls.org) source code and generates a Markdown reference file — intended to be used as context when working with an AI assistant (Claude, ChatGPT, etc.) on YOURLS development (plugins, core patches)

## Why

AI assistants have partial, often outdated knowledge of YOURLS internals: function signatures, hook names, arguments, constants, and database schema. Rather than hoping the model guesses correctly, you feed it a generated reference extracted directly from your current source tree.

The output is a compact Markdown file you can drop into a Claude Project (or paste at the start of any conversation) so the model works from accurate, up-to-date information instead of plausible-looking hallucinations.

## What it extracts

- **Functions** — all `yourls_*` functions with their full signature and docblock
- **Filters** — every `yourls_apply_filters()` call with hook name and arguments
- **Actions** — every `yourls_do_action()` call with hook name and arguments
- **Constants** — all `define()` calls with name, value, and docblock
- **Database schema** — tables defined in `$create_tables[YOURLS_DB_TABLE_*]` blocks, with column names, types, and flags

## Requirements

- PHP 8.0+
- Read access to the YOURLS source tree

## Usage

```bash
# Output to stdout
php yourls-extractor.php /path/to/yourls

# Write to a file
php yourls-extractor.php /path/to/yourls --output yourls-reference.md
# or positional:
php yourls-extractor.php /path/to/yourls yourls-reference.md

# Debug mode: shows what the parser finds and how it reassembles SQL
php yourls-extractor.php /path/to/yourls --output yourls-reference.md --debug
```

Then upload `yourls-reference.md` to your AI assistant's project context and keep it updated when you upgrade YOURLS.

## Contributing

Contributions to YOURLS are welcome — bug fixes, edge case handling, support for new YOURLS patterns, output format improvements.

That said: **please don't send pull requests that are raw, unreviewed AI output.** Using an AI assistant to help you write or improve code is completely fine. Submitting something you clearly don't understand and haven't validated is not. PRs where the author can't explain what the code does or why it was written that way will be closed without further discussion.

If you're unsure about an approach, open an issue first.
