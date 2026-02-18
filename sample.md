# YOURLS Reference (auto-generated)

> Generated from source. Use as Claude context for YOURLS development.

## Database Schema

Table name constants: `YOURLS_DB_TABLE_URL`, `YOURLS_DB_TABLE_OPTIONS`, `YOURLS_DB_TABLE_LOG`

**`YOURLS_DB_TABLE_URL`**
- `keyword` varchar(100) — NOT NULL
- `url` text — NOT NULL
- `title` text — NULL, DEFAULT NULL
- `timestamp` timestamp — NOT NULL, DEFAULT current_timestamp()
- `ip` varchar(41) — NOT NULL
- `clicks` int(10) — NOT NULL
- PRIMARY KEY (`keyword`)
- KEY `ip` (`ip`)
- KEY `timestamp` (`timestamp`)
- KEY `url_idx` (`url`(30)

**`YOURLS_DB_TABLE_OPTIONS`**
- `option_id` bigint(20) — NOT NULL, AUTO_INCREMENT
- `option_name` varchar(64) — NOT NULL
- `option_value` longtext — NOT NULL
- PRIMARY KEY  (`option_id`
- KEY `option_name` (`option_name`)

**`YOURLS_DB_TABLE_LOG`**
- `click_id` int(11) — NOT NULL, AUTO_INCREMENT
- `click_time` datetime — NOT NULL
- `shorturl` varchar(100) — NOT NULL
- `referrer` varchar(200) — NOT NULL
- `user_agent` varchar(255) — NOT NULL
- `ip_address` varchar(41) — NOT NULL
- `country_code` char(2) — NOT NULL
- PRIMARY KEY  (`click_id`)
- KEY `shorturl` (`shorturl`)

## Functions

### `includes\class-mysql.php`

**`yourls_db_connect($context = '')`**
— Connect to DB @since 1.0 @param string $context Optional context. Default: ''. See yourls_get_db() @return \YOURLS\Database\YDB

**`yourls_get_db($context = '')`**
— Data Source Name (dsn) used to connect the DB DSN with PDO is something like: 'mysql:host=123.4.5.6;dbname=test_db;port=3306' 'sqlite:/opt/databases/mydb.sq3' 'pgsql:host=192.168.13.37;port=5432;dbname=omgwtf' $dsn = sprintf( 'mysql:host=%s;dbname=%s;charset=%s', $dbhost, $dbname, $charset ); $dsn = yourls_apply_filter( 'db_connect_custom_dsn', $dsn, $context ); PDO driver options and attributes The PDO constructor is something like: new PDO( string $dsn, string $username, string $password [, array $options ] ) The driver options are passed to the PDO constructor, eg array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION) The attribute options are then set in a foreach($attr as $k=>$v){$db->setAttribute($k, $v)} loop $driver_options = yourls_apply_filter( 'db_connect_driver_option', [], $context ); // driver options as key-value pairs $attributes = yourls_apply_filter( 'db_connect_attributes', [], $context ); // attributes as key-value pairs $ydb = new \YOURLS\Database\YDB( $dsn, $user, $pass, $driver_options, $attributes ); $ydb->init(); Past this point, we're connected yourls_debug_log( 'Connected to ' . $dsn ); yourls_debug_mode( YOURLS_DEBUG ); return $ydb; } Helper function: return instance of the DB Instead of: global $ydb; $ydb->do_stuff() Prefer : yourls_get_db()->do_stuff() @since  1.7.10 @param string $context Optional context. Default: ''. If not provided, the function will trigger a notice to encourage developers to provide a context while not breaking existing code. A context is a string describing the operation for which the DB is requested. Use a naming schema starting with a prefix describing the operation, followed by a short description: - Prefix should be either "read-" or "write-", as follows: "read-" for operations that only read from the DB (eg get_keyword_infos) "write-" for operations that write to the DB (eg insert_link_in_db) - The description should be lowercase, words separated with underscores, eg "insert_link_in_db". Examples: - read-fetch_keyword - write-insert_link_in_db @return \YOURLS\Database\YDB

**`yourls_set_db($db)`**
— Helper function : set instance of DB, or unset it Instead of: global $ydb; $ydb = stuff Prefer : yourls_set_db( stuff ) (This is mostly used in the test suite) @since 1.7.10 @param  mixed $db    Either a \YOURLS\Database\YDB instance, or anything. If null, the function will unset $ydb @return void

### `includes\functions-api.php`

**`yourls_api_action_db_stats()`**
— API function wrapper: Just the global counts of shorturls and clicks @since 1.6 @return array Result of API call

**`yourls_api_action_expand()`**
— API function wrapper: Expand a short link @since 1.6 @return array Result of API call

**`yourls_api_action_shorturl()`**
— API function wrapper: Shorten a URL @since 1.6 @return array Result of API call

**`yourls_api_action_stats()`**
— API function wrapper: Stats about links (XX top, bottom, last, rand) @since 1.6 @return array Result of API call

**`yourls_api_action_url_stats()`**
— API function wrapper: Stats for a shorturl @since 1.6 @return array Result of API call

**`yourls_api_action_version()`**
— API function wrapper: return version numbers @since 1.6 @return array Result of API call

**`yourls_api_db_stats()`**
— Return array for counts of shorturls and clicks @return array

**`yourls_api_expand($shorturl)`**
— Expand short url to long url @param string $shorturl  Short URL to expand @return array

**`yourls_api_output($mode, $output, $send_headers = true, $echo = true)`**
— Output and return API result This function will echo (or only return if asked) an array as JSON, JSONP or XML. If the array has a 'simple' key, it can also output that key as unformatted text if expected output mode is 'simple' Most likely, script should not do anything after outputting this @since 1.6 @param  string $mode          Expected output mode ('json', 'jsonp', 'xml', 'simple') @param  array  $output        Array of things to output @param  bool   $send_headers  Optional, default true: Whether a headers (status, content type) should be sent or not @param  bool   $echo          Optional, default true: Whether the output should be outputted or just returned @return string                API output, as an XML / JSON / JSONP / raw text string

**`yourls_api_stats($filter = 'top', $limit = 10, $start = 0)`**
— Return array for API stat requests @param string $filter  either "top", "bottom" , "rand" or "last" @param int    $limit   maximum number of links to return @param int    $start   offset @return array

**`yourls_api_url_stats($shorturl)`**
— Return array for API stat requests @param string $shorturl  Short URL to check @return array

### `includes\functions-auth.php`

**`yourls_auth_signature($username = false)`**
— Generate secret signature hash @param false|string $username  Username to generate signature for, or false to use current user @return string                 Signature

**`yourls_check_auth_cookie()`**
— Check auth against encrypted COOKIE data. Sets user if applicable, returns bool @return bool true if authenticated, false otherwise

**`yourls_check_password_hash($user, $submitted_password)`**
— Check a submitted password sent in plain text against stored password which can be a salted hash @param string $user @param string $submitted_password @return bool

**`yourls_check_signature()`**
— Check auth against signature. Sets user if applicable, returns bool @since 1.4.1 @return bool False if signature missing or invalid, true if valid

**`yourls_check_signature_timestamp()`**
— Check auth against signature and timestamp. Sets user if applicable, returns bool Original usage : http://sho.rt/yourls-api.php?timestamp=<timestamp>&signature=<md5 hash>&action=... Since 1.7.7 we allow a `hash` parameter and an arbitrary hashed signature, hashed with the `hash` function. Examples : http://sho.rt/yourls-api.php?timestamp=<timestamp>&signature=<sha512 hash>&hash=sha512&action=... http://sho.rt/yourls-api.php?timestamp=<timestamp>&signature=<crc32 hash>&hash=crc32&action=... @since 1.4.1 @return bool False if signature or timestamp missing or invalid, true if valid

**`yourls_check_timestamp($time)`**
— Check if timestamp is not too old @param int $time  Timestamp to check @return bool      True if timestamp is valid

**`yourls_check_username_password()`**
— Check auth against list of login=>pwd. Sets user if applicable, returns bool @return bool  true if login/pwd pair is valid (and sets user if applicable), false otherwise

**`yourls_cookie_name()`**
— Get YOURLS cookie name The name is unique for each install, to prevent mismatch between sho.rt and very.sho.rt -- see #1673 TODO: when multi user is implemented, the whole cookie stuff should be reworked to allow storing multiple users @since 1.7.1 @return string  unique cookie name for a given YOURLS site

**`yourls_cookie_value($user)`**
— Get auth cookie value @since 1.7.7 @param string $user     user name @return string          cookie value

**`yourls_create_nonce($action, $user = false)`**
— Create a time limited, action limited and user limited token @param string $action      Action to create nonce for @param false|string $user  Optional user string, false for current user @return string             Nonce token

**`yourls_get_cookie_life()`**
— Get YOURLS_COOKIE_LIFE value (ie the life span of an auth cookie in seconds) Use this function instead of directly using the constant. This way, its value can be modified by plugins on a per case basis @since 1.7.7 @see includes/Config/Config.php @return integer     cookie life span, in seconds

**`yourls_get_nonce_life()`**
— Get YOURLS_NONCE_LIFE value (ie life span of a nonce in seconds) Use this function instead of directly using the constant. This way, its value can be modified by plugins on a per case basis @since 1.7.7 @see includes/Config/Config.php @see https://en.wikipedia.org/wiki/Cryptographic_nonce @return integer     nonce life span, in seconds

**`yourls_has_cleartext_passwords()`**
— Check to see if any passwords are stored as cleartext. @since 1.7 @return bool true if any passwords are cleartext

**`yourls_has_md5_password($user)`**
— Check if a user has a md5 hashed password Check if a user password is 'md5:[38 chars]'. TODO: deprecate this when/if we have proper user management with password hashes stored in the DB @since 1.7 @param string $user user login @return bool true if password hashed, false otherwise

**`yourls_has_phpass_password($user)`**
— Check if a user's password is hashed with password_hash Check if a user password is 'phpass:[lots of chars]'. (For historical reason we're using 'phpass' as an identifier.) TODO: deprecate this when/if we have proper user management with password hashes stored in the DB @since 1.7 @param string $user user login @return bool true if password hashed with password_hash, otherwise false

**`yourls_hash_passwords_now($config_file)`**
— Overwrite plaintext passwords in config file with hashed versions. @since 1.7 @param string $config_file Full path to file @return true|string  if overwrite was successful, an error message otherwise

**`yourls_hmac_algo()`**
— Return an available hash_hmac() algorithm @since 1.8.3 @return string  hash_hmac() algorithm

**`yourls_is_user_from_env()`**
— Check if YOURLS_USER comes from environment variables @since 1.8.2 @return bool  true if YOURLS_USER and YOURLS_PASSWORD are defined as environment variables

**`yourls_is_valid_user()`**
— Check for valid user via login form or stored cookie. Returns true or an error message @return bool|string|mixed true if valid user, error message otherwise. Can also call yourls_die() or redirect to login page. Oh my.

**`yourls_maybe_hash_passwords()`**
— Check if we should hash passwords in the config file By default, passwords are hashed. They are not if - there is no password in clear text in the config file (ie everything is already hashed) - the user defined constant YOURLS_NO_HASH_PASSWORD is true, see https://docs.yourls.org/guide/essentials/credentials.html#i-don-t-want-to-encrypt-my-password - YOURLS_USER and YOURLS_PASSWORD are provided by the environment, not the config file @since 1.8.2 @return bool

**`yourls_maybe_require_auth()`**
— Function related to authentication functions and nonces Show login form if required @return void

**`yourls_nonce_field($action, $name = 'nonce', $user = false, $echo = true)`**
— Echoes or returns a nonce field for inclusion into a form @param string $action      Action to create nonce for @param string $name        Optional name of nonce field -- defaults to 'nonce' @param false|string $user  Optional user string, false if unspecified @param bool $echo          True to echo, false to return nonce field @return string             Nonce field

**`yourls_nonce_url($action, $url = false, $name = 'nonce', $user = false)`**
— Add a nonce to a URL. If URL omitted, adds nonce to current URL @param string $action      Action to create nonce for @param string $url         Optional URL to add nonce to -- defaults to current URL @param string $name        Optional name of nonce field -- defaults to 'nonce' @param false|string $user  Optional user string, false if unspecified @return string             URL with nonce added

**`yourls_phpass_check($password, $hash)`**
— Filter for hashing algorithm. See https://www.php.net/manual/en/function.password-hash.php Hashing algos are available if PHP was compiled with it. PASSWORD_BCRYPT is always available. $algo    = yourls_apply_filter('hash_algo', PASSWORD_BCRYPT); Filter for hashing options. See https://www.php.net/manual/en/function.password-hash.php A typical option for PASSWORD_BCRYPT would be ['cost' => <int in range 4-31> ] We're leaving the options at default values, which means a cost of 10 for PASSWORD_BCRYPT. If willing to modify this, be warned about the computing time, as there is a 2^n factor. See https://gist.github.com/ozh/65a75392b7cb254131cc55afd28de99b for examples. $options = yourls_apply_filter('hash_options', [] ); return password_hash($password, $algo, $options); } Verify that a password matches a hash @since 1.7 @param string $password clear (eg submitted in a form) password @param string $hash hash @return bool true if the hash matches the password, false otherwise

**`yourls_phpass_hash($password)`**
— Create a password hash @since 1.7 @param string $password password to hash @return string hashed password

**`yourls_salt($string)`**
— Return hashed string This function is badly named, it's not a salt or a salted string : it's a cryptographic hash. @since 1.4.1 @param string $string   string to salt @return string          hashed string

**`yourls_set_user($user)`**
— Set user name @param string $user  Username @return void

**`yourls_setcookie($name, $value, $expire, $path, $domain, $secure, $httponly)`**
— Replacement for PHP's setcookie(), with support for SameSite cookie attribute @see https://github.com/GoogleChromeLabs/samesite-examples/blob/master/php.md @see https://stackoverflow.com/a/59654832/36850 @see https://www.php.net/manual/en/function.setcookie.php @since  1.7.7 @param  string  $name       cookie name @param  string  $value      cookie value @param  int     $expire     time the cookie expires as a Unix timestamp (number of seconds since the epoch) @param  string  $path       path on the server in which the cookie will be available on @param  string  $domain     (sub)domain that the cookie is available to @param  bool    $secure     if cookie should only be transmitted over a secure HTTPS connection @param  bool    $httponly   if cookie will be made accessible only through the HTTP protocol @return bool                setcookie() result : false if output sent before, true otherwise. This does not indicate whether the user accepted the cookie.

**`yourls_skip_password_hashing()`**
— Check if user setting for skipping password hashing is set @since 1.8.2 @return bool

**`yourls_store_cookie($user = '')`**
— Store new cookie. No $user will delete the cookie. @param string $user  User login, or empty string to delete cookie @return void

**`yourls_tick()`**
— Return a time-dependent string for nonce creation Actually, this returns a float: ceil rounds up a value but is of type float, see https://www.php.net/ceil @return float

**`yourls_verify_nonce($action, $nonce = false, $user = false, $return = '')`**
— Check validity of a nonce (ie time span, user and action match). Returns true if valid, dies otherwise (yourls_die() or die($return) if defined). If $nonce is false or unspecified, it will use $_REQUEST['nonce'] @param string $action @param false|string $nonce  Optional, string: nonce value, or false to use $_REQUEST['nonce'] @param false|string $user   Optional, string user, false for current user @param string $return       Optional, string: message to die with if nonce is invalid @return bool|void           True if valid, dies otherwise

### `includes\functions-compat.php`

**`yourls_array_to_json($array)`**
— json_encode for PHP, should someone run a distro without php-json -- see http://askubuntu.com/questions/361424 if( !function_exists( 'json_encode' ) ) { function json_encode( $array ) { return yourls_array_to_json( $array ); } } Converts an associative array of arbitrary depth and dimension into JSON representation. Used for compatibility with older PHP builds. @param array $array the array to convert. @return mixed The resulting JSON string, or false if the argument was not an array. @author Andy Rusterholz @link http://php.net/json_encode (see comments)

### `includes\functions-debug.php`

**`yourls_debug_log($msg)`**
— Add a message to the debug log When in debug mode (YOURLS_DEBUG == true) the debug log is echoed in yourls_html_footer() Log messages are appended to $ydb->debug_log array, which is instantiated within class Database\YDB @since 1.7 @param string $msg Message to add to the debug log @return string The message itself

**`yourls_debug_mode($bool)`**
— Debug mode set @since 1.7.3 @param bool $bool Debug on or off @return void

**`yourls_get_debug_log()`**
— Get the debug log @since  1.7.3 @return array

**`yourls_get_debug_mode()`**
— Return YOURLS debug mode @since 1.7.7 @return bool

**`yourls_get_num_queries()`**
— Get number of SQL queries performed @return int

### `includes\functions-deprecated.php`

**`yourls_activate_plugin_sandbox($pluginfile)`**
— Deprecated functions from past YOURLS versions. Don't use them, as they may be removed in a later version. Use the newer alternatives instead. Note to devs: when deprecating a function, move it here. Then check all the places in core that might be using it, including core plugins. Usage :  yourls_deprecated_function( 'function_name', 'version', 'replacement' ); Output:  "{function_name} is deprecated since version {version}! Use {replacement} instead." Usage :  yourls_deprecated_function( 'function_name', 'version' ); Output:  "{function_name} is deprecated since version {version} with no alternative available." @see yourls_deprecated_function() @codeCoverageIgnoreStart Plugin activation sandbox @since 1.8.3 @deprecated 1.9.2 @param string $pluginfile Plugin filename (full path) @return string|true  string if error or true if success

**`yourls_apply_filters($hook, $value = '')`**
— Alias for yourls_apply_filter because I never remember if it's _filter or _filters At first I thought it made semantically more sense but thinking about it, I was wrong. It's one filter. There may be several function hooked into it, but it still the same one filter. @since 1.6 @deprecated 1.7.1 @param string $hook the name of the YOURLS element or action @param mixed $value the value of the element before filtering @return mixed

**`yourls_current_admin_page()`**
— Return current admin page, or null if not an admin page. Was not used anywhere. @return mixed string if admin page, null if not an admin page @since 1.6 @deprecated 1.9.1

**`yourls_current_time($type, $gmt = 0)`**
— Retrieve the current time based on specified type. Stolen from WP. The 'mysql' type will return the time in the format for MySQL DATETIME field. The 'timestamp' type will return the current timestamp. If $gmt is set to either '1' or 'true', then both types will use GMT time. if $gmt is false, the output is adjusted with the GMT offset in the WordPress option. @since 1.6 @deprecated 1.7.10 @param string $type Either 'mysql' or 'timestamp'. @param int|bool $gmt Optional. Whether to use GMT timezone. Default is false. @return int|string String if $type is 'gmt', int if $type is 'timestamp'.

**`yourls_encodeURI($url)`**
— PHP emulation of JS's encodeURI @link https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/encodeURI @deprecated 1.9.1 @param string $url @return string

**`yourls_escape($data)`**
— Escape a string or an array of strings before DB usage. ALWAYS escape before using in a SQL query. Thanks. Deprecated in 1.7.3 because we moved onto using PDO and using built-in escaping functions, instead of rolling our own. @deprecated 1.7.3 @param string|array $data string or array of strings to be escaped @return string|array escaped data

**`yourls_escape_real($string)`**
— "Real" escape. This function should NOT be called directly. Use yourls_escape() instead. This function uses a "real" escape if possible, using PDO, MySQL or MySQLi functions, with a fallback to a "simple" addslashes If you're implementing a custom DB engine or a custom cache system, you can define an escape function using filter 'custom_escape_real' @since 1.7 @deprecated 1.7.3 @param string $a string to be escaped @return string escaped string

**`yourls_ex($text, $context, $domain = 'default')`**
— Displays translated string with gettext context This function has been renamed yourls_xe() for consistency with other *e() functions @see yourls_x() @since 1.6 @deprecated 1.7.1 @param string $text Text to translate @param string $context Context information for the translators @param string $domain Optional. Domain to retrieve the translated text @return string Translated context string without pipe

**`yourls_favicon($echo = true)`**
— Return favicon URL (either default or custom) @deprecated 1.7.10

**`yourls_get_duplicate_keywords($longurl)`**
— Return list of all shorturls associated to the same long URL. Returns NULL or array of keywords.

**`yourls_get_link_stats($url)`**
— Return array of stats for a given keyword @deprecated 1.7.10

**`yourls_get_remote_content($url, $maxlen = 4096, $timeout = 5)`**
— Get remote content via a GET request using best transport available

**`yourls_get_search_text()`**
— Get search text from query string variables search_protocol, search_slashes and search Some servers don't like query strings containing "(ht|f)tp(s)://". A javascript bit explodes the search text into protocol, slashes and the rest (see JS function split_search_text_before_search()) and this function glues pieces back together See issue https://github.com/YOURLS/YOURLS/issues/1576 @since 1.7 @deprecated 1.8.2 @return string Search string

**`yourls_has_interface()`**
— Check if we'll need interface display function (ie not API or redirection)

**`yourls_http_proxy_is_defined()`**
— Check if a proxy is defined for HTTP requests @since 1.7 @deprecated 1.7.1 @return bool true if a proxy is defined, false otherwise

**`yourls_intval($int)`**
— Make sure a integer is safe Note: this function is dumb and dumbly named since it does not intval(). DO NOT USE.

**`yourls_lowercase_scheme_domain($url)`**
— Lowercase scheme and domain of an URI - see issues 591, 1630, 1889 Renamed to yourls_normalize_uri() in 1.7.10 because the function now does more than just lowercasing the scheme and domain. @deprecated 1.7.10

**`yourls_plural($word, $count=1)`**
— Return word or words if more than one

**`yourls_sanitize_string($string, $restrict_to_shorturl_charset = false)`**
— The original string sanitize function @deprecated 1.7.10

**`yourls_string2htmlid($string)`**
— Return a unique(ish) hash for a string to be used as a valid HTML id @deprecated 1.8.3

**`yourls_url_exists($url)`**
— Check if a long URL already exists in the DB. Return NULL (doesn't exist) or an object with URL informations. @since 1.5.1 @deprecated 1.7.10

**`yourls_validate_plugin_file($file)`**
— Check if a file is a plugin file @deprecated 1.8.3

### `includes\functions-formatting.php`

**`yourls_backslashit($string)`**
— Adds backslashes before letters and before a number at the start of a string. Stolen from WP. @since 1.6 @param string $string Value to which backslashes will be added. @return string String with backslashes inserted.

**`yourls_check_invalid_utf8($string, $strip = false)`**
— Checks for invalid UTF8 in a string. Stolen from WP @since 1.6 @param string $string The text which is to be checked. @param boolean $strip Optional. Whether to attempt to strip out invalid UTF8. Default is false. @return string The checked text.

**`yourls_deep_replace($search, $subject)`**
— Perform a replacement while a string is found, eg $subject = '%0%0%0DDD', $search ='%0D' -> $result ='' Stolen from WP's _deep_replace @param string|array $search   Needle, or array of needles. @param string       $subject  Haystack. @return string                The string with the replaced values.

**`yourls_esc_attr($text)`**
— Escaping for HTML attributes.  Stolen from WP @since 1.6 @param string $text @return string

**`yourls_esc_html($text)`**
— Escaping for HTML blocks. Stolen from WP @since 1.6 @param string $text @return string

**`yourls_esc_js($text)`**
— Case 1 : scheme like "stuff:", as opposed to "stuff://" Examples: "mailto:joe@joe.com" or "bitcoin:15p1o8vnWqNkJBJGgwafNgR1GCCd6EGtQR?amount=1&label=Ozh" In this case, we only lowercase the scheme, because depending on it, things after should or should not be lowercased if (substr($scheme, -2, 2) != '//') { $url = str_replace( $scheme, strtolower( $scheme ), $url ); return $url; } Case 2 : scheme like "stuff://" (eg "http://example.com/" or "ssh://joe@joe.com") Here we lowercase the scheme and domain parts $parts = parse_url($url); Most likely malformed stuff, could not parse : we'll just lowercase the scheme and leave the rest untouched if (false == $parts) { $url = str_replace( $scheme, strtolower( $scheme ), $url ); return $url; } URL seems parsable, let's do the best we can $lower = array(); $lower['scheme'] = strtolower( $parts['scheme'] ); if( isset( $parts['host'] ) ) { Convert domain to lowercase, with mb_ to preserve UTF8 $lower['host'] = mb_strtolower($parts['host']); Convert IDN domains to their UTF8 form so that طارق.net and xn--mgbuq0c.net are considered the same. Explicitly mention option and variant to avoid notice on PHP 7.2 and 7.3 $lower['host'] = idn_to_utf8($lower['host'], IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46); } $url = http_build_url($url, $lower); return $url; } Escape single quotes, htmlspecialchar " < > &, and fix line endings. Stolen from WP. Escapes text strings for echoing in JS. It is intended to be used for inline JS (in a tag attribute, for example onclick="..."). Note that the strings have to be in single quotes. The filter 'js_escape' is also applied here. @since 1.6 @param string $text The text to be escaped. @return string Escaped text.

**`yourls_esc_textarea($text)`**
— Escaping for textarea values. Stolen from WP. @since 1.6 @param string $text @return string

**`yourls_esc_url($url, $context = 'display', $protocols = array()`**
— Checks and cleans a URL before printing it. Stolen from WP. A number of characters are removed from the URL. If the URL is for displaying (the default behaviour) ampersands are also replaced. This function by default "escapes" URL for display purpose (param $context = 'display') but can take extra steps in URL sanitization. See yourls_sanitize_url() and yourls_sanitize_url_safe() @since 1.6 @param string $url The URL to be cleaned. @param string $context 'display' or something else. Use yourls_sanitize_url() for database or redirection usage. @param array $protocols Optional. Array of allowed protocols, defaults to global $yourls_allowedprotocols @return string The cleaned $url

**`yourls_get_date_format($format)`**
— Return a date() format for date (no time), filtered @since 1.7.10 @param  string $format  Date format string @return string          Date format string

**`yourls_get_datetime_format($format)`**
— Return a date() format for a full date + time, filtered @since 1.7.10 @param  string $format  Date format string @return string          Date format string

**`yourls_get_time_format($format)`**
— Return a date() format for a time (no date), filtered @since 1.7.10 @param  string $format  Date format string @return string          Date format string

**`yourls_get_time_offset()`**
— Get time offset, as defined in config, filtered @since 1.7.10 @return int       Time offset

**`yourls_get_timestamp($timestamp)`**
— Return a timestamp, plus or minus the time offset if defined @since 1.7.10 @param  string|int $timestamp  a timestamp @return int                    a timestamp, plus or minus offset if defined

**`yourls_int2string($num, $chars = null)`**
— Convert an integer (1337) to a string (3jk). @param int $num       Number to convert @param string $chars  Characters to use for conversion @return string        Converted number

**`yourls_is_rawurlencoded($string)`**
— Check if a string seems to be urlencoded We use rawurlencode instead of urlencode to avoid messing with '+' @since 1.7 @param string $string @return bool

**`yourls_make_bookmarklet($code)`**
— Converts readable Javascript code into a valid bookmarklet link Uses https://github.com/ozh/bookmarkletgen @since 1.7.1 @param  string $code  Javascript code @return string        Bookmarklet link

**`yourls_normalize_uri($url)`**
— Normalize a URI : lowercase scheme and domain, convert IDN to UTF8 All in one example: 'HTTP://XN--mgbuq0c.Com/AbCd' -> 'http://طارق.com/AbCd' See issues 591, 1630, 1889, 2691 This function is trickier than what seems to be needed at first First, we need to handle several URI types: http://example.com, mailto:ozh@ozh.ozh, facetime:user@example.com, and so on, see yourls_kses_allowed_protocols() in functions-kses.php The general rule is that the scheme ("stuff://" or "stuff:") is case insensitive and should be lowercase. But then, depending on the scheme, parts of what follows the scheme may or may not be case sensitive. Second, simply using parse_url() and its opposite http_build_url() is a pretty unsafe process: - parse_url() can easily trip up on malformed or weird URLs - exploding a URL with parse_url(), lowercasing some stuff, and glueing things back with http_build_url() does not handle well "stuff:"-like URI [1] and can result in URLs ending modified [2][3]. We don't want to *validate* URI, we just want to lowercase what is supposed to be lowercased. So, to be conservative, this function: - lowercases the scheme - does not lowercase anything else on "stuff:" URI - tries to lowercase only scheme and domain of "stuff://" URI [1] http_build_url(parse_url("mailto:ozh")) == "mailto:///ozh" [2] http_build_url(parse_url("http://blah#omg")) == "http://blah/#omg" [3] http_build_url(parse_url("http://blah?#")) == "http://blah/" @since 1.7.1 @param string $url URL @return string URL with lowercase scheme and protocol

**`yourls_rawurldecode_while_encoded($string)`**
— rawurldecode a string till it's not encoded anymore Deals with multiple encoding (eg "%2521" => "%21" => "!"). See https://github.com/YOURLS/YOURLS/issues/1303 @since 1.7 @param string $string @return string

**`yourls_remove_backslashes_before_query_fragment(string $url)`**
— Remove backslashes before query string or fragment identifier This function removes backslashes before the first ? or #, if any. If there's no ? or #, all backslashes are removed. See issue #3802 and PR #3998 @since 1.10.3 @param string $url URL @return string URL without backslashes before query string or fragment identifier

**`yourls_sanitize_date($date)`**
— Make sure a date is m(m)/d(d)/yyyy, return false otherwise @param string $date  Date to check @return false|mixed  Date in format m(m)/d(d)/yyyy or false if invalid

**`yourls_sanitize_date_for_sql($date)`**
— Sanitize a date for SQL search. Return false if malformed input. @param string $date   Date @return false|string  String in Y-m-d format for SQL search or false if malformed input

**`yourls_sanitize_filename($file)`**
— Sanitize a filename (no Win32 stuff) @param string $file  File name @return string|null  Sanitized file name (or null if it's just backslashes, ok...)

**`yourls_sanitize_int($int)`**
— Make sure an integer is a valid integer (PHP's intval() limits to too small numbers) @param int $int  Integer to check @return string   Integer as a string

**`yourls_sanitize_ip($ip)`**
— Sanitize an IP address No check on validity, just return a sanitized string @param string $ip  IP address @return string     IP address

**`yourls_sanitize_keyword($keyword, $restrict_to_shorturl_charset = false)`**
— Make sure a link keyword (ie "1fv" as in "http://sho.rt/1fv") is acceptable If we are ADDING or EDITING a short URL, the keyword must comply to the short URL charset: every character that doesn't belong to it will be removed. But otherwise we must have a more conservative approach: we could be checking for a keyword that was once valid but now the short URL charset has changed. In such a case, we are treating the keyword for what it is: just a part of a URL, hence sanitize it as a URL. @param  string $keyword                        short URL keyword @param  bool   $restrict_to_shorturl_charset   Optional, default false. True if we want the keyword to comply to short URL charset @return string                                 The sanitized keyword

**`yourls_sanitize_title($unsafe_title, $fallback = '')`**
— Sanitize a page title. No HTML per W3C http://www.w3.org/TR/html401/struct/global.html#h-7.4.2 @since 1.5 @param string $unsafe_title  Title, potentially unsafe @param string $fallback      Optional fallback if after sanitization nothing remains @return string               Safe title

**`yourls_sanitize_url($unsafe_url, $protocols = array()`**
— A few sanity checks on the URL. Used for redirection or DB. For redirection when you don't trust the URL ($_SERVER variable, query string), see yourls_sanitize_url_safe() For display purpose, see yourls_esc_url() @param string $unsafe_url unsafe URL @param array $protocols Optional allowed protocols, default to global $yourls_allowedprotocols @return string Safe URL

**`yourls_sanitize_url_safe($unsafe_url, $protocols = array()`**
— A few sanity checks on the URL, including CRLF. Used for redirection when URL to be sanitized is critical and cannot be trusted. Use when critical URL comes from user input or environment variable. In such a case, this function will sanitize it like yourls_sanitize_url() but will also remove %0A and %0D to prevent CRLF injection. Still, some legit URLs contain %0A or %0D (see issue 2056, and for extra fun 1694, 1707, 2030, and maybe others) so we're not using this function unless it's used for internal redirection when the target location isn't hardcoded, to avoid XSS via CRLF @since 1.7.2 @param string $unsafe_url unsafe URL @param array $protocols Optional allowed protocols, default to global $yourls_allowedprotocols @return string Safe URL

**`yourls_sanitize_version($version)`**
— Sanitize a version number (1.4.1-whatever-RC1 -> 1.4.1) The regexp searches for the first digits, then a period, then more digits and periods, and discards all the rest. Examples: 'omgmysql-5.5-ubuntu-4.20' => '5.5' 'mysql5.5-ubuntu-4.20'     => '5.5' '5.5-ubuntu-4.20'          => '5.5' '5.5-beta2'                => '5.5' '5.5'                      => '5.5' @since 1.4.1 @param  string $version  Version number @return string           Sanitized version number

**`yourls_seems_utf8($str)`**
— Check if a string seems to be UTF-8. Stolen from WP. @param string $str  String to check @return bool        Whether string seems valid UTF-8

**`yourls_specialchars($string, $quote_style = ENT_NOQUOTES, $double_encode = false)`**
— Converts a number of special characters into their HTML entities. Stolen from WP. Specifically deals with: &, <, >, ", and '. $quote_style can be set to ENT_COMPAT to encode " to &quot;, or ENT_QUOTES to do both. Default is ENT_NOQUOTES where no quotes are encoded. @since 1.6 @param string $string The text which is to be encoded. @param mixed $quote_style Optional. Converts double quotes if set to ENT_COMPAT, both single and double if set to ENT_QUOTES or none if set to ENT_NOQUOTES. Also compatible with old values; converting single quotes if set to 'single', double if set to 'double' or both if otherwise set. Default is ENT_NOQUOTES. @param boolean $double_encode Optional. Whether to encode existing html entities. Default is false. @return string The encoded text with HTML entities.

**`yourls_specialchars_decode($string, $quote_style = ENT_NOQUOTES)`**
— Converts a number of HTML entities into their special characters. Stolen from WP. Specifically deals with: &, <, >, ", and '. $quote_style can be set to ENT_COMPAT to decode " entities, or ENT_QUOTES to do both " and '. Default is ENT_NOQUOTES where no quotes are decoded. @since 1.6 @param string $string The text which is to be decoded. @param mixed $quote_style Optional. Converts double quotes if set to ENT_COMPAT, both single and double if set to ENT_QUOTES or none if set to ENT_NOQUOTES. Also compatible with old _wp_specialchars() values; converting single quotes if set to 'single', double if set to 'double' or both if otherwise set. Default is ENT_NOQUOTES. @return string The decoded text without HTML entities.

**`yourls_string2int($string, $chars = null)`**
— Convert a string (3jk) to an integer (1337) @param string $string  String to convert @param string $chars   Characters to use for conversion @return string         Number (as a string)

**`yourls_supports_pcre_u()`**
— Check for PCRE /u modifier support. Stolen from WP. Just in case "PCRE is not compiled with PCRE_UTF8" which seems to happen on some distros @since 1.7.1 @return bool whether there's /u support or not

**`yourls_trim_long_string($string, $length = 60, $append = '[...]')`**
— Return trimmed string, optionally append '[...]' if string is too long @param string $string  String to trim @param int $length     Maximum length of string @param string $append  String to append if trimmed @return string         Trimmed string

**`yourls_unique_element_id($prefix = 'yid', $initial_val = 1)`**
— Return a unique string to be used as a valid HTML id @since   1.8.3 @param  string $prefix      Optional prefix @param  int    $initial_val The initial counter value (defaults to one) @return string              The unique string

**`yourls_validate_jsonp_callback($callback)`**
— Validate a JSONP callback name Check if the callback contains only safe characters: [a-zA-Z0-9_$.] Returns the original callback if valid, or false if invalid. Examples: - 'myCallback' => 'myCallback' - 'alert(1)'   => false See tests/tests/format/JsonpCallbackTest.php for various cases covered @since 1.10.3 @param string $callback Raw callback value @return string|false Original callback if valid, false otherwise

### `includes\functions-geo.php`

**`yourls_geo_countrycode_to_countryname($code)`**
— Converts a 2 letter country code to long name (ie AU -> Australia) This associative array is the one used by MaxMind internal functions, it may differ from other lists (eg "A1" does not universally stand for "Anon proxy") @since 1.4 @param string $code 2 letter country code, eg 'FR' @return string Country long name (eg 'France') or an empty string if not found

**`yourls_geo_get_flag($code)`**
— Return flag URL from 2 letter country code @param string $code @return string

**`yourls_geo_ip_to_countrycode($ip = '', $default = '')`**
— Function relative to the geolocation functions (ip <-> country <-> flags), currently tied to Maxmind's GeoIP but this should evolve to become more pluggable Converts an IP to a 2 letter country code, using GeoIP database if available in includes/geo @since 1.4 @param string $ip      IP or, if empty string, will be current user IP @param string $default Default string to return if IP doesn't resolve to a country (malformed, private IP...) @return string 2 letter country code (eg 'US') or $default

### `includes\functions-html.php`

**`yourls_add_notice($message, $style = 'notice')`**
— Wrapper function to display admin notices @param string $message Message to display @param string $style    Message style (default: 'notice') @return void

**`yourls_bookmarklet_link($href, $anchor, $echo = true)`**
— Display or return HTML for a bookmarklet link @since 1.7.1 @param string $href    bookmarklet link (presumably minified code with "javascript:" scheme) @param string $anchor  link anchor @param bool   $echo    true to display, false to return the HTML @return string         the HTML for a bookmarklet link

**`yourls_delete_link_modal()`**
— Display hidden modal for link delete confirmation @since 1.10.3 @param void @return void

**`yourls_die($message = '', $title = '', $header_code = 200)`**
— Die die die @see https://www.youtube.com/watch?v=zSiKETBjARk @param string $message @param string $title @param int $header_code @return void

**`yourls_get_html_context()`**
— Get HTML context (stats, index, infos, ...) @since  1.7.3 @return string

**`yourls_html_addnew($url = '', $keyword = '')`**
— Display "Add new URL" box @param string $url URL to prefill the input with @param string $keyword Keyword to prefill the input with @return void

**`yourls_html_favicon()`**
— Print HTML link for favicon @since 1.7.10 @return mixed|void

**`yourls_html_footer($can_query = true)`**
— Display HTML footer (including closing body & html tags) Function yourls_die() will call this function with the optional param set to false: most likely, if we're using yourls_die(), there's a problem, so don't maybe add to it by sending another SQL query @param  bool $can_query  If set to false, will not try to send another query to DB server @return void

**`yourls_html_head($context = 'index', $title = '')`**
— Display HTML head and <body> tag @param string $context Context of the page (stats, index, infos, ...) @param string $title HTML title of the page @return void

**`yourls_html_language_attributes()`**
— Display the language attributes for the HTML tag. Builds up a set of html attributes containing the text direction and language information for the page. Stolen from WP. @since 1.6 @return void

**`yourls_html_link($href, $anchor = '', $element = '')`**
— Echo HTML tag for a link @param string $href     URL to link to @param string $anchor   Anchor text @param string $element  Element id @return void

**`yourls_html_logo()`**
— Display <h1> header and logo @return void

**`yourls_html_menu()`**
— Display the admin menu @return void

**`yourls_html_select($name, $options, $selected = '', $display = false, $label = '')`**
— Return or display a select dropdown field @since 1.6 @param  string  $name      HTML 'name' (also use as the HTML 'id') @param  array   $options   array of 'value' => 'Text displayed' @param  string  $selected  optional 'value' from the $options array that will be highlighted @param  boolean $display   false (default) to return, true to echo @param  string  $label     ARIA label of the element @return string HTML content of the select element

**`yourls_html_tfooter($params = array()`**
— Display main table's footer The $param array is defined in /admin/index.php, check the yourls_html_tfooter() call @param array $params Array of all required parameters @return void

**`yourls_l10n_calendar_strings()`**
— Output translated strings used by the Javascript calendar @since 1.6 @return void

**`yourls_login_screen($error_msg = '')`**
— Display the login screen. Nothing past this point. @param string $error_msg  Optional error message to display @return void

**`yourls_new_core_version_notice($compare_with = null)`**
— Display a notice if there is a newer version of YOURLS available @since 1.7 @param string $compare_with Optional, YOURLS version to compare to @return void

**`yourls_notice_box($message, $style = 'notice')`**
— Return a formatted notice @param string $message  Message to display @param string $style    CSS class to use for the notice @return string          HTML of the notice

**`yourls_page($page)`**
— Display a page Includes content of a PHP file from the YOURLS_PAGEDIR directory, as if it were a standard short URL (ie http://sho.rt/$page) @since 1.0 @param string $page  PHP file to display @return void

**`yourls_set_html_context($context)`**
— Set HTML context (stats, index, infos, ...) @since  1.7.3 @param  string  $context @return void

**`yourls_share_box($longurl, $shorturl, $title = '', $text='', $shortlink_title = '', $share_title = '', $hidden = false)`**
— Display the Quick Share box @param string $longurl          Long URL @param string $shorturl         Short URL @param string $title            Title @param string $text             Text to display @param string $shortlink_title  Optional replacement for 'Your short link' @param string $share_title      Optional replacement for 'Quick Share' @param bool   $hidden           Optional. Hide the box by default (with css "display:none") @return void

**`yourls_table_add_row($keyword, $url, $title, $ip, $clicks, $timestamp, $row_id = 1)`**
— Return an "Add" row for the main table @param string $keyword     Keyword (short URL) @param string $url         URL (long URL) @param string $title       Title @param string $ip          IP @param string|int $clicks  Number of clicks @param string $timestamp   Timestamp @param int    $row_id      Numeric value used to form row IDs, defaults to one @return string             HTML of the row

**`yourls_table_edit_row($keyword, $id)`**
— Return an "Edit" row for the main table @param string $keyword Keyword to edit @param string $id @return string HTML of the edit row

**`yourls_table_end()`**
— Echo the table start tag @return void

**`yourls_table_head()`**
— Echo the main table head @return void

**`yourls_table_tbody_end()`**
— Echo the tbody end tag @return void

**`yourls_table_tbody_start()`**
— Echo the tbody start tag @return void

### `includes\functions-http.php`

**`yourls_can_http_over_ssl()`**
— Check if server can perform HTTPS requests, return bool @since 1.7.1 @return bool whether the server can perform HTTP requests over SSL

**`yourls_check_core_version()`**
— Check api.yourls.org if there's a newer version of YOURLS This function collects various stats to help us improve YOURLS. See the blog post about it: http://blog.yourls.org/2014/01/on-yourls-1-7-and-api-yourls-org Results of requests sent to api.yourls.org are stored in option 'core_version_checks' and is an object with the following properties: - failed_attempts : number of consecutive failed attempts - last_attempt    : time() of last attempt - last_result     : content retrieved from api.yourls.org during previous check - version_checked : installed YOURLS version that was last checked @since 1.7 @return mixed JSON data if api.yourls.org successfully requested, false otherwise

**`yourls_get_version_from_zipball_url($zipurl)`**
— Get version number from Github zipball URL (last part of URL, really) @since 1.8.3 @param string $zipurl eg 'https://api.github.com/repos/YOURLS/YOURLS/zipball/1.2.3' @return string

**`yourls_http_default_options()`**
— Default HTTP requests options for YOURLS For a list of all available options, see function request() in /includes/Requests/Requests.php @since 1.7 @return array Options

**`yourls_http_get($url, $headers = array()`**
— Functions that relate to HTTP requests On functions using the 3rd party library Requests: Their goal here is to provide convenient wrapper functions to the Requests library. There are 2 types of functions for each METHOD, where METHOD is 'get' or 'post' (implement more as needed) - yourls_http_METHOD() : Return a complete Response object (with ->body, ->headers, ->status_code, etc...) or a simple string (error message) - yourls_http_METHOD_body() : Return a string (response body) or null if there was an error @since 1.7 use WpOrg\Requests\Requests; Perform a GET request, return response object or error string message Notable object properties: body, headers, status_code @since 1.7 @see yourls_http_request @param string $url     URL to request @param array $headers  HTTP headers to send @param array $data     GET data @param array $options  Options to pass to Requests @return mixed Response object, or error string

**`yourls_http_get_body($url, $headers = array()`**
— Perform a GET request, return body or null if there was an error @since 1.7 @see yourls_http_request @param string $url     URL to request @param array $headers  HTTP headers to send @param array $data     GET data @param array $options  Options to pass to Requests @return mixed String (page body) or null if error

**`yourls_http_get_proxy()`**
— Get proxy information @since 1.7.1 @return mixed false if no proxy is defined, or string like '10.0.0.201:3128' or array like ('10.0.0.201:3128', 'username', 'password')

**`yourls_http_get_proxy_bypass_host()`**
— Get list of hosts that should bypass the proxy @since 1.7.1 @return mixed false if no host defined, or string like "example.com, *.mycorp.com"

**`yourls_http_post($url, $headers = array()`**
— Perform a POST request, return response object Notable object properties: body, headers, status_code @since 1.7 @see yourls_http_request @param string $url     URL to request @param array $headers  HTTP headers to send @param array $data     POST data @param array $options  Options to pass to Requests @return mixed Response object, or error string

**`yourls_http_post_body($url, $headers = array()`**
— Perform a POST request, return body Wrapper for yourls_http_request() @since 1.7 @see yourls_http_request @param string $url     URL to request @param array $headers  HTTP headers to send @param array $data     POST data @param array $options  Options to pass to Requests @return mixed String (page body) or null if error

**`yourls_http_request($type, $url, $headers, $data, $options)`**
— Perform a HTTP request, return response object @since 1.7 @param string $type HTTP request type (GET, POST) @param string $url URL to request @param array $headers Extra headers to send with the request @param array $data Data to send either as a query string for GET requests, or in the body for POST requests @param array $options Options for the request (see /includes/Requests/Requests.php:request()) @return object WpOrg\Requests\Response object

**`yourls_http_user_agent()`**
— Return funky user agent string @since 1.5 @return string UA string

**`yourls_is_valid_github_repo_url($url)`**
— Check if URL is from YOURLS/YOURLS repo on github @since 1.8.3 @param string $url  URL to check @return bool

**`yourls_maybe_check_core_version()`**
— Determine if we want to check for a newer YOURLS version (and check if applicable) Currently checks are performed every 24h and only when someone is visiting an admin page. In the future (1.8?) maybe check with cronjob emulation instead. @since 1.7 @return bool true if a check was needed and successfully performed, false otherwise

**`yourls_send_through_proxy($url)`**
— Whether URL should be sent through the proxy server. Concept stolen from WordPress. The idea is to allow some URLs, including localhost and the YOURLS install itself, to be requested directly and bypassing any defined proxy. @since 1.7 @param string $url URL to check @return bool true to request through proxy, false to request directly

**`yourls_skip_version_check()`**
— Check if user setting for skipping version check is set @since 1.8.2 @return bool

**`yourls_validate_core_version_response($json)`**
— Make sure response from api.yourls.org is valid 1) we should get a json object with two following properties: 'latest' => a string representing a YOURLS version number, eg '1.2.3' 'zipurl' => a string for a zip package URL, from github, eg 'https://api.github.com/repos/YOURLS/YOURLS/zipball/1.2.3' 2) 'latest' and version extracted from 'zipurl' should match 3) the object should not contain any other key @since 1.7.7 @param object $json  JSON object to check @return bool   true if seems legit, false otherwise

**`yourls_validate_core_version_response_keys($json)`**
— Check if object has only expected keys 'latest' and 'zipurl' containing strings @since 1.8.3 @param object $json @return bool

### `includes\functions-infos.php`

**`yourls_array_granularity($array, $grain = 100, $preserve_max = true)`**
— Tweak granularity of array $array: keep only $grain values. This make less accurate but less messy graphs when too much values. See https://developers.google.com/chart/image/docs/gallery/line_charts?hl=en#data-granularity @param array $array @param int $grain @param bool $preserve_max @return array

**`yourls_build_list_of_days($dates)`**
— Build a list of all daily values between d1/m1/y1 to d2/m2/y2. @param array $dates @return array[]  Array of arrays of days, months, years

**`yourls_days_in_month($month, $year)`**
— Return the number of days in a month. From php.net. @param int $month @param int $year @return int

**`yourls_get_domain($url, $include_scheme = false)`**
— Return domain of a URL @param string $url @param bool $include_scheme @return string

**`yourls_get_favicon_url($url)`**
— Return favicon URL @param string $url @return string

**`yourls_google_array_to_data_table($data)`**
— Transform data array to data table for Google API @param array $data @return string

**`yourls_google_viz_code($graph_type, $data, $options, $id)`**
— Return javascript code that will display the Google Chart @param string $graph_type @param string $data @param var $options @param string $id @return string

**`yourls_scale_data($data)`**
— Scale array of data from 0 to 100 max @param array $data @return array

**`yourls_stats_countries_map($countries, $id = null)`**
— Echoes an image tag of Google Charts map from sorted array of 'country_code' => 'number of visits' (sort by DESC) @param array $countries  Array of 'country_code' => 'number of visits' @param string $id        Optional HTML element ID @return void

**`yourls_stats_get_best_day($list_of_days)`**
— Get max value from date array of 'Aug 12, 2012' = '1337' @param array $list_of_days @return array

**`yourls_stats_line($values, $id = null)`**
— Echoes an image tag of Google Charts line graph from array of values (eg 'number of clicks'). $legend1_list & legend2_list are values used for the 2 x-axis labels. $id is an HTML/JS id @param array $values  Array of values (eg 'number of clicks') @param string $id     HTML element id @return void

**`yourls_stats_pie($data, $limit = 10, $size = '340x220', $id = null)`**
— Echoes an image tag of Google Charts pie from sorted array of 'data' => 'value' (sort by DESC). Optional $limit = (integer) limit list of X first countries, sorted by most visits @param array $data  Array of 'data' => 'value' @param int $limit   Optional limit list of X first countries @param $size        Optional size of the image @param $id          Optional HTML element ID @return void

### `includes\functions-install.php`

**`yourls_check_PDO()`**
— Check if we have PDO installed, returns bool @since 1.7.3 @return bool

**`yourls_check_database_version()`**
— Check if server has MySQL 5.0+ @return bool

**`yourls_check_php_version()`**
— Check if PHP > 7.2 As of 1.8 we advertise YOURLS as being 7.4+ but it should work on 7.2 (although untested) so we don't want to strictly enforce a limitation that may not be necessary. @return bool

**`yourls_create_htaccess()`**
— Create .htaccess or web.config. Returns boolean @return bool

**`yourls_create_sql_tables()`**
— Create MySQL tables. Return array( 'success' => array of success strings, 'errors' => array of error strings ) @since 1.3 @return array  An array like array( 'success' => array of success strings, 'errors' => array of error strings )

**`yourls_get_database_version()`**
— Get DB server version @since 1.7 @return string sanitized DB version

**`yourls_initialize_options()`**
— Initializes the option table Each yourls_update_option() returns either true on success (option updated) or false on failure (new value == old value, or for some reason it could not save to DB). Since true & true & true = 1, we cast it to boolean type to return true (or false) @since 1.7 @return bool

**`yourls_insert_sample_links()`**
— Populates the URL table with a few sample links @since 1.7 @return bool

**`yourls_insert_with_markers($filename, $marker, $insertion)`**
— Insert text into a file between BEGIN/END markers, return bool. Stolen from WP Inserts an array of strings into a file (eg .htaccess ), placing it between BEGIN and END markers. Replaces existing marked info. Retains surrounding data. Creates file if none exists. @since 1.3 @param string $filename @param string $marker @param array  $insertion @return bool True on write success, false on failure.

**`yourls_is_apache()`**
— Check if server is an Apache @return bool

**`yourls_is_iis()`**
— Check if server is running IIS @return bool

**`yourls_maintenance_mode($maintenance = true)`**
— Toggle maintenance mode. Inspired from WP. Returns true for success, false otherwise @param bool $maintenance  True to enable, false to disable @return bool              True on success, false on failure

### `includes\functions-kses.php`

**`yourls_kses_allowed_entities()`**
— Kses global for allowable HTML entities. @since 1.6 @return array Allowed entities

**`yourls_kses_allowed_protocols()`**
— Kses global for allowable protocols. @since 1.6 @return array Allowed protocols

**`yourls_kses_allowed_tags()`**
— Kses global for default allowable HTML tags. TODO: trim down to necessary only. Short list of HTML tags used in YOURLS core for display @since 1.6 @return array Allowed tags

**`yourls_kses_allowed_tags_all()`**
— See NOTE ABOUT GLOBALS if( ! $yourls_allowedtags_all ) { $yourls_allowedtags_all = yourls_kses_allowed_tags_all(); $yourls_allowedtags_all = array_map( '_yourls_add_global_attributes', $yourls_allowedtags_all ); $yourls_allowedtags_all = yourls_apply_filter( 'kses_allowed_tags_all', $yourls_allowedtags_all ); } else { User defined: let's sanitize $yourls_allowedtags_all = yourls_kses_array_lc( $yourls_allowedtags_all ); } if( ! $yourls_allowedtags ) { $yourls_allowedtags = yourls_kses_allowed_tags(); $yourls_allowedtags = array_map( '_yourls_add_global_attributes', $yourls_allowedtags ); $yourls_allowedtags = yourls_apply_filter( 'kses_allowed_tags', $yourls_allowedtags ); } else { User defined: let's sanitize $yourls_allowedtags = yourls_kses_array_lc( $yourls_allowedtags ); } } Kses global for all allowable HTML tags. Complete (?) list of HTML tags. Keep this function available for any plugin or future feature that will want to display lots of HTML. @since 1.6 @return array All tags

**`yourls_kses_array_lc($inarray)`**
— Goes through an array and changes the keys to all lower case. @since 1.6 @param array $inarray Unfiltered array @return array Fixed array with all lowercase keys

**`yourls_kses_decode_entities($string)`**
— Convert all entities to their character counterparts. This function decodes numeric HTML entities (&#65; and &#x41;). It doesn't do anything with other entities like &auml;, but we don't need them in the URL protocol whitelisting system anyway. @since 1.6 @param string $string Content to change entities @return string Content after decoded entities

**`yourls_kses_init()`**
— YOURLS modification of a small subset from WordPress' KSES implementation. Straight from the Let's Not Reinvent The Wheel department. kses 0.2.2 - HTML/XHTML filter that only allows some elements and attributes Copyright (C) 2002, 2003, 2005  Ulf Harnhammar This program is free software and open source software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version. This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA http://www.gnu.org/licenses/gpl.html [kses strips evil scripts!] @version 0.2.2 @copyright (C) 2002, 2003, 2005 @author Ulf Harnhammar <http://advogato.org/person/metaur/> @package External @subpackage KSES NOTE ABOUT GLOBALS Two globals are defined: $yourls_allowedentitynames and $yourls_allowedprotocols - $yourls_allowedentitynames is used internally in KSES functions to sanitize HTML entities - $yourls_allowedprotocols is used in various parts of YOURLS, not just in KSES, albeit being defined here Two globals are not defined and unused at this moment: $yourls_allowedtags_all and $yourls_allowedtags The code for these vars is here and ready for any future use Populate after plugins have loaded to allow user defined values yourls_add_action( 'plugins_loaded', 'yourls_kses_init' ); Init KSES globals if not already defined (by a plugin) @since 1.6 @return void

**`yourls_kses_named_entities($matches)`**
— Callback for yourls_kses_normalize_entities() regular expression. This function only accepts valid named entity references, which are finite, case-sensitive, and highly scrutinized by HTML and XML validators. @since 1.6 @param array $matches preg_replace_callback() matches array @return string Correctly encoded entity

**`yourls_kses_no_null($string)`**
— Regex callback for yourls_kses_decode_entities() @since 1.6 @param array $match preg match @return string function _yourls_kses_decode_entities_chr( $match ) { return chr( $match[1] ); } Regex callback for yourls_kses_decode_entities() @since 1.6 @param array $match preg match @return string function _yourls_kses_decode_entities_chr_hexdec( $match ) { return chr( hexdec( $match[1] ) ); } Removes any null characters in $string. @since 1.6 @param string $string @return string

**`yourls_kses_normalize_entities($string)`**
— Converts and fixes HTML entities. This function normalizes HTML entities. It will convert "AT&T" to the correct "AT&amp;T", "&#00058;" to "&#58;", "&#XYZZY;" to "&amp;#XYZZY;" and so on. @since 1.6 @param string $string Content to normalize entities @return string Content with normalized entities

**`yourls_kses_normalize_entities2($matches)`**
— Callback for yourls_kses_normalize_entities() regular expression. This function helps yourls_kses_normalize_entities() to only accept 16-bit values and nothing more for &#number; entities. @access private @since 1.6 @param array $matches preg_replace_callback() matches array @return string Correctly encoded entity

**`yourls_kses_normalize_entities3($matches)`**
— Callback for yourls_kses_normalize_entities() for regular expression. This function helps yourls_kses_normalize_entities() to only accept valid Unicode numeric entities in hex form. @access private @since 1.6 @param array $matches preg_replace_callback() matches array @return string Correctly encoded entity

**`yourls_valid_unicode($i)`**
— Helper function to add global attributes to a tag in the allowed html list. @since 1.6 @access private @param array $value An array of attributes. @return array The array of attributes with global attributes added. function _yourls_add_global_attributes( $value ) { $global_attributes = array( 'class' => true, 'id' => true, 'style' => true, 'title' => true, ); if ( true === $value ) $value = array(); if ( is_array( $value ) ) return array_merge( $value, $global_attributes ); return $value; } Helper function to determine if a Unicode value is valid. @since 1.6 @param int $i Unicode value @return bool True if the value was a valid Unicode number

### `includes\functions-l10n.php`

**`yourls__($text, $domain = 'default')`**
— Retrieves the translation of $text. If there is no translation, or the domain isn't loaded, the original text is returned. @see yourls_translate() An alias of yourls_translate() @since 1.6 @param string $text Text to translate @param string $domain Optional. Domain to retrieve the translated text @return string Translated text

**`yourls_date_i18n($dateformatstring, $timestamp = false)`**
— Return the date in localized format, based on timestamp. If the locale specifies the locale month and weekday, then the locale will take over the format for the date. If it isn't, then the date format string will be used instead. @since 1.6 @param  string   $dateformatstring   Format to display the date. @param  bool|int $timestamp          Optional, Unix timestamp, default to current timestamp (with offset if applicable) @return string                       The date, translated if locale specifies it.

**`yourls_e($text, $domain = 'default')`**
— Displays the returned translated text from yourls_translate(). @see yourls_translate() Echoes returned yourls_translate() string @since 1.6 @param string $text Text to translate @param string $domain Optional. Domain to retrieve the translated text @return void

**`yourls_esc_attr__($text, $domain = 'default')`**
— Retrieves the translation of $text and escapes it for safe use in an attribute. If there is no translation, or the domain isn't loaded, the original text is returned. @see yourls_translate() An alias of yourls_translate() @see yourls_esc_attr() @since 1.6 @param string $text Text to translate @param string $domain Optional. Domain to retrieve the translated text @return string Translated text

**`yourls_esc_attr_e($text, $domain = 'default')`**
— Displays translated text that has been escaped for safe use in an attribute. @see yourls_translate() Echoes returned yourls_translate() string @see yourls_esc_attr() @since 1.6 @param string $text Text to translate @param string $domain Optional. Domain to retrieve the translated text @return void

**`yourls_esc_attr_x($single, $context, $domain = 'default')`**
— Return translated text, with context, that has been escaped for safe use in an attribute @see yourls_translate() Return returned yourls_translate() string @see yourls_esc_attr() @see yourls_x() @since 1.6 @param string   $single @param string   $context @param string   $domain Optional. Domain to retrieve the translated text @return string

**`yourls_esc_html__($text, $domain = 'default')`**
— Retrieves the translation of $text and escapes it for safe use in HTML output. If there is no translation, or the domain isn't loaded, the original text is returned. @see yourls_translate() An alias of yourls_translate() @see yourls_esc_html() @since 1.6 @param string $text Text to translate @param string $domain Optional. Domain to retrieve the translated text @return string Translated text

**`yourls_esc_html_e($text, $domain = 'default')`**
— Displays translated text that has been escaped for safe use in HTML output. @see yourls_translate() Echoes returned yourls_translate() string @see yourls_esc_html() @since 1.6 @param string $text Text to translate @param string $domain Optional. Domain to retrieve the translated text @return void

**`yourls_esc_html_x($single, $context, $domain = 'default')`**
— Return translated text, with context, that has been escaped for safe use in HTML output @see yourls_translate() Return returned yourls_translate() string @see yourls_esc_attr() @see yourls_x() @since 1.6 @param string   $single @param string   $context @param string   $domain Optional. Domain to retrieve the translated text @return string

**`yourls_get_available_languages($dir = null)`**
— Get all available languages (*.mo files) in a given directory. The default directory is YOURLS_LANG_DIR. @since 1.6 @param string $dir A directory in which to search for language files. The default directory is YOURLS_LANG_DIR. @return array Array of language codes or an empty array if no languages are present. Language codes are formed by stripping the .mo extension from the language file names.

**`yourls_get_locale()`**
— YOURLS Translation API YOURLS modification of a small subset from WordPress' Translation API implementation. GPL License @package POMO @subpackage i18n Load POMO files required to run library use POMO\MO; use POMO\Translations\NOOPTranslations; Gets the current locale. If the locale is set, then it will filter the locale in the 'get_locale' filter hook and return the value. If the locale is not set already, then the YOURLS_LANG constant is used if it is defined. Then it is filtered through the 'get_locale' filter hook and the value for the locale global set and the locale is returned. The process to get the locale should only be done once, but the locale will always be filtered using the 'get_locale' hook. @since 1.6 @return string The locale of the YOURLS instance

**`yourls_get_translations_for_domain($domain)`**
— Returns the Translations instance for a domain. If there isn't one, returns empty Translations instance. @param string $domain @return NOOPTranslations An NOOPTranslations translation instance

**`yourls_is_rtl()`**
— Checks if current locale is RTL. Stolen from WP. @since 1.6 @return bool Whether locale is RTL.

**`yourls_is_textdomain_loaded($domain)`**
— Whether there are translations for the domain @since 1.6 @param string $domain @return bool Whether there are translations

**`yourls_l10n_month_abbrev($month = '')`**
— Return translated month abbrevation (3 letters, eg 'Nov' for 'November') The $month var can be a textual string ('November'), a integer (1 to 12), a two digits strings ('01' to '12), or an empty string If $month is an empty string, the function returns an array of all translated abbrev months ('January' => 'Jan', ...) @since 1.6 @param mixed $month Empty string, a full textual weekday, eg "November", or an integer (1 = January, .., 12 = December) @return mixed Translated month abbrev (eg "Nov"), or array of all translated abbrev months

**`yourls_l10n_months()`**
— Return array of all translated months @since 1.6 @return array Array of all translated months

**`yourls_l10n_weekday_abbrev($weekday = '')`**
— Return translated weekday abbreviation (3 letters, eg 'Fri' for 'Friday') The $weekday var can be a textual string ('Friday'), a integer (0 to 6) or an empty string If $weekday is an empty string, the function returns an array of all translated weekday abbrev @since 1.6 @param mixed $weekday A full textual weekday, eg "Friday", or an integer (0 = Sunday, 1 = Monday, .. 6 = Saturday) @return mixed Translated weekday abbreviation, eg "Ven" (abbrev of "Vendredi") for "Friday" or 5, or array of all weekday abbrev

**`yourls_l10n_weekday_initial($weekday = '')`**
— Return translated weekday initial (1 letter, eg 'F' for 'Friday') The $weekday var can be a textual string ('Friday'), a integer (0 to 6) or an empty string If $weekday is an empty string, the function returns an array of all translated weekday initials @since 1.6 @param mixed $weekday A full textual weekday, eg "Friday", an integer (0 = Sunday, 1 = Monday, .. 6 = Saturday) or empty string @return mixed Translated weekday initial, eg "V" (initial of "Vendredi") for "Friday" or 5, or array of all weekday initials

**`yourls_load_custom_textdomain($domain, $path)`**
— @var YOURLS_Locale_Formats $yourls_locale_formats global $yourls_locale_formats; if( !isset( $yourls_locale_formats ) ) $yourls_locale_formats = new YOURLS_Locale_Formats(); if ( false === $timestamp ) { $timestamp = yourls_get_timestamp( time() ); } store original value for language with untypical grammars $req_format = $dateformatstring; Replace the date format characters with their translatation, if found Example: 'l d F Y' gets replaced with '\L\u\n\d\i d \M\a\i Y' in French We deliberately don't deal with 'I', 'O', 'P', 'T', 'Z' and 'e' in date format (timezones) if ( ( !empty( $yourls_locale_formats->month ) ) && ( !empty( $yourls_locale_formats->weekday ) ) ) { $datemonth            = $yourls_locale_formats->get_month( date( 'm', $timestamp ) ); $datemonth_abbrev     = $yourls_locale_formats->get_month_abbrev( $datemonth ); $dateweekday          = $yourls_locale_formats->get_weekday( date( 'w', $timestamp ) ); $dateweekday_abbrev   = $yourls_locale_formats->get_weekday_abbrev( $dateweekday ); $datemeridiem         = $yourls_locale_formats->get_meridiem( date( 'a', $timestamp ) ); $datemeridiem_capital = $yourls_locale_formats->get_meridiem( date( 'A', $timestamp ) ); $dateformatstring = ' '.$dateformatstring; $dateformatstring = preg_replace( "/([^\\\])D/", "\\1" . yourls_backslashit( $dateweekday_abbrev ), $dateformatstring ); $dateformatstring = preg_replace( "/([^\\\])F/", "\\1" . yourls_backslashit( $datemonth ), $dateformatstring ); $dateformatstring = preg_replace( "/([^\\\])l/", "\\1" . yourls_backslashit( $dateweekday ), $dateformatstring ); $dateformatstring = preg_replace( "/([^\\\])M/", "\\1" . yourls_backslashit( $datemonth_abbrev ), $dateformatstring ); $dateformatstring = preg_replace( "/([^\\\])a/", "\\1" . yourls_backslashit( $datemeridiem ), $dateformatstring ); $dateformatstring = preg_replace( "/([^\\\])A/", "\\1" . yourls_backslashit( $datemeridiem_capital ), $dateformatstring ); $dateformatstring = substr( $dateformatstring, 1, strlen( $dateformatstring ) -1 ); } $date = date( $dateformatstring, $timestamp ); Allow plugins to redo this entirely for languages with untypical grammars return yourls_apply_filter('date_i18n', $date, $req_format, $timestamp); } Class that loads the calendar locale. @since 1.6 class YOURLS_Locale_Formats { Stores the translated strings for the full weekday names. @since 1.6 @var array @access private var $weekday; Stores the translated strings for the one character weekday names. There is a hack to make sure that Tuesday and Thursday, as well as Sunday and Saturday, don't conflict. See init() method for more. @see YOURLS_Locale_Formats::init() for how to handle the hack. @since 1.6 @var array @access private var $weekday_initial; Stores the translated strings for the abbreviated weekday names. @since 1.6 @var array @access private var $weekday_abbrev; Stores the translated strings for the full month names. @since 1.6 @var array @access private var $month; Stores the translated strings for the abbreviated month names. @since 1.6 @var array @access private var $month_abbrev; Stores the translated strings for 'am' and 'pm'. Also the capitalized versions. @since 1.6 @var array @access private var $meridiem; Stores the translated number format @since 1.6 @var array @access private var $number_format; The text direction of the locale language. Default is left to right 'ltr'. @since 1.6 @var string @access private var $text_direction = 'ltr'; Sets up the translated strings and object properties. The method creates the translatable strings for various calendar elements. Which allows for specifying locale specific calendar names and text direction. @since 1.6 @access private @return void function init() { The Weekdays $this->weekday[0] = /* //translators: weekday */ yourls__( 'Sunday' ); $this->weekday[1] = /* //translators: weekday */ yourls__( 'Monday' ); $this->weekday[2] = /* //translators: weekday */ yourls__( 'Tuesday' ); $this->weekday[3] = /* //translators: weekday */ yourls__( 'Wednesday' ); $this->weekday[4] = /* //translators: weekday */ yourls__( 'Thursday' ); $this->weekday[5] = /* //translators: weekday */ yourls__( 'Friday' ); $this->weekday[6] = /* //translators: weekday */ yourls__( 'Saturday' ); The first letter of each day. The _%day%_initial suffix is a hack to make sure the day initials are unique. $this->weekday_initial[yourls__( 'Sunday' )]    = /* //translators: one-letter abbreviation of the weekday */ yourls__( 'S_Sunday_initial' ); $this->weekday_initial[yourls__( 'Monday' )]    = /* //translators: one-letter abbreviation of the weekday */ yourls__( 'M_Monday_initial' ); $this->weekday_initial[yourls__( 'Tuesday' )]   = /* //translators: one-letter abbreviation of the weekday */ yourls__( 'T_Tuesday_initial' ); $this->weekday_initial[yourls__( 'Wednesday' )] = /* //translators: one-letter abbreviation of the weekday */ yourls__( 'W_Wednesday_initial' ); $this->weekday_initial[yourls__( 'Thursday' )]  = /* //translators: one-letter abbreviation of the weekday */ yourls__( 'T_Thursday_initial' ); $this->weekday_initial[yourls__( 'Friday' )]    = /* //translators: one-letter abbreviation of the weekday */ yourls__( 'F_Friday_initial' ); $this->weekday_initial[yourls__( 'Saturday' )]  = /* //translators: one-letter abbreviation of the weekday */ yourls__( 'S_Saturday_initial' ); foreach ($this->weekday_initial as $weekday_ => $weekday_initial_) { $this->weekday_initial[$weekday_] = preg_replace('/_.+_initial$/', '', $weekday_initial_); } Abbreviations for each day. $this->weekday_abbrev[ yourls__( 'Sunday' ) ]    = /* //translators: three-letter abbreviation of the weekday */ yourls__( 'Sun' ); $this->weekday_abbrev[ yourls__( 'Monday' ) ]    = /* //translators: three-letter abbreviation of the weekday */ yourls__( 'Mon' ); $this->weekday_abbrev[ yourls__( 'Tuesday' ) ]   = /* //translators: three-letter abbreviation of the weekday */ yourls__( 'Tue' ); $this->weekday_abbrev[ yourls__( 'Wednesday' ) ] = /* //translators: three-letter abbreviation of the weekday */ yourls__( 'Wed' ); $this->weekday_abbrev[ yourls__( 'Thursday' ) ]  = /* //translators: three-letter abbreviation of the weekday */ yourls__( 'Thu' ); $this->weekday_abbrev[ yourls__( 'Friday' ) ]    = /* //translators: three-letter abbreviation of the weekday */ yourls__( 'Fri' ); $this->weekday_abbrev[ yourls__( 'Saturday' ) ]  = /* //translators: three-letter abbreviation of the weekday */ yourls__( 'Sat' ); The Months $this->month['01'] = /* //translators: month name */ yourls__( 'January' ); $this->month['02'] = /* //translators: month name */ yourls__( 'February' ); $this->month['03'] = /* //translators: month name */ yourls__( 'March' ); $this->month['04'] = /* //translators: month name */ yourls__( 'April' ); $this->month['05'] = /* //translators: month name */ yourls__( 'May' ); $this->month['06'] = /* //translators: month name */ yourls__( 'June' ); $this->month['07'] = /* //translators: month name */ yourls__( 'July' ); $this->month['08'] = /* //translators: month name */ yourls__( 'August' ); $this->month['09'] = /* //translators: month name */ yourls__( 'September' ); $this->month['10'] = /* //translators: month name */ yourls__( 'October' ); $this->month['11'] = /* //translators: month name */ yourls__( 'November' ); $this->month['12'] = /* //translators: month name */ yourls__( 'December' ); Abbreviations for each month. Uses the same hack as above to get around the 'May' duplication. $this->month_abbrev[ yourls__( 'January' ) ]   = /* //translators: three-letter abbreviation of the month */ yourls__( 'Jan_January_abbreviation' ); $this->month_abbrev[ yourls__( 'February' ) ]  = /* //translators: three-letter abbreviation of the month */ yourls__( 'Feb_February_abbreviation' ); $this->month_abbrev[ yourls__( 'March' ) ]     = /* //translators: three-letter abbreviation of the month */ yourls__( 'Mar_March_abbreviation' ); $this->month_abbrev[ yourls__( 'April' ) ]     = /* //translators: three-letter abbreviation of the month */ yourls__( 'Apr_April_abbreviation' ); $this->month_abbrev[ yourls__( 'May' ) ]       = /* //translators: three-letter abbreviation of the month */ yourls__( 'May_May_abbreviation' ); $this->month_abbrev[ yourls__( 'June' ) ]      = /* //translators: three-letter abbreviation of the month */ yourls__( 'Jun_June_abbreviation' ); $this->month_abbrev[ yourls__( 'July' ) ]      = /* //translators: three-letter abbreviation of the month */ yourls__( 'Jul_July_abbreviation' ); $this->month_abbrev[ yourls__( 'August' ) ]    = /* //translators: three-letter abbreviation of the month */ yourls__( 'Aug_August_abbreviation' ); $this->month_abbrev[ yourls__( 'September' ) ] = /* //translators: three-letter abbreviation of the month */ yourls__( 'Sep_September_abbreviation' ); $this->month_abbrev[ yourls__( 'October' ) ]   = /* //translators: three-letter abbreviation of the month */ yourls__( 'Oct_October_abbreviation' ); $this->month_abbrev[ yourls__( 'November' ) ]  = /* //translators: three-letter abbreviation of the month */ yourls__( 'Nov_November_abbreviation' ); $this->month_abbrev[ yourls__( 'December' ) ]  = /* //translators: three-letter abbreviation of the month */ yourls__( 'Dec_December_abbreviation' ); foreach ($this->month_abbrev as $month_ => $month_abbrev_) { $this->month_abbrev[$month_] = preg_replace('/_.+_abbreviation$/', '', $month_abbrev_); } The Meridiems $this->meridiem['am'] = yourls__( 'am' ); $this->meridiem['pm'] = yourls__( 'pm' ); $this->meridiem['AM'] = yourls__( 'AM' ); $this->meridiem['PM'] = yourls__( 'PM' ); Numbers formatting See http://php.net/number_format translators: $thousands_sep argument for http://php.net/number_format, default is , $trans = yourls__( 'number_format_thousands_sep' ); $this->number_format['thousands_sep'] = ('number_format_thousands_sep' == $trans) ? ',' : $trans; translators: $dec_point argument for http://php.net/number_format, default is . $trans = yourls__( 'number_format_decimal_point' ); $this->number_format['decimal_point'] = ('number_format_decimal_point' == $trans) ? '.' : $trans; Set text direction. if ( isset( $GLOBALS['text_direction'] ) ) $this->text_direction = $GLOBALS['text_direction']; translators: 'rtl' or 'ltr'. This sets the text direction for YOURLS. elseif ( 'rtl' == yourls_x( 'ltr', 'text direction' ) ) $this->text_direction = 'rtl'; } Retrieve the full translated weekday word. Week starts on translated Sunday and can be fetched by using 0 (zero). So the week starts with 0 (zero) and ends on Saturday with is fetched by using 6 (six). @since 1.6 @access public @param int|string $weekday_number 0 for Sunday through 6 Saturday @return string Full translated weekday function get_weekday( $weekday_number ) { return $this->weekday[ $weekday_number ]; } Retrieve the translated weekday initial. The weekday initial is retrieved by the translated full weekday word. When translating the weekday initial pay attention to make sure that the starting letter does not conflict. @since 1.6 @access public @param string $weekday_name @return string function get_weekday_initial( $weekday_name ) { return $this->weekday_initial[ $weekday_name ]; } Retrieve the translated weekday abbreviation. The weekday abbreviation is retrieved by the translated full weekday word. @since 1.6 @access public @param string $weekday_name Full translated weekday word @return string Translated weekday abbreviation function get_weekday_abbrev( $weekday_name ) { return $this->weekday_abbrev[ $weekday_name ]; } Retrieve the full translated month by month number. The $month_number parameter has to be a string because it must have the '0' in front of any number that is less than 10. Starts from '01' and ends at '12'. You can use an integer instead and it will add the '0' before the numbers less than 10 for you. @since 1.6 @access public @param string|int $month_number '01' through '12' @return string Translated full month name function get_month( $month_number ) { return $this->month[ sprintf( '%02s', $month_number ) ]; } Retrieve translated version of month abbreviation string. The $month_name parameter is expected to be the translated or translatable version of the month. @since 1.6 @access public @param string $month_name Translated month to get abbreviated version @return string Translated abbreviated month function get_month_abbrev( $month_name ) { return $this->month_abbrev[ $month_name ]; } Retrieve translated version of meridiem string. The $meridiem parameter is expected to not be translated. @since 1.6 @access public @param string $meridiem Either 'am', 'pm', 'AM', or 'PM'. Not translated version. @return string Translated version function get_meridiem( $meridiem ) { return $this->meridiem[ $meridiem ]; } Global variables are deprecated. For backwards compatibility only. @deprecated For backwards compatibility only. @access private @since 1.6 @return void function register_globals() { $GLOBALS['weekday']         = $this->weekday; $GLOBALS['weekday_initial'] = $this->weekday_initial; $GLOBALS['weekday_abbrev']  = $this->weekday_abbrev; $GLOBALS['month']           = $this->month; $GLOBALS['month_abbrev']    = $this->month_abbrev; } Constructor which calls helper methods to set up object variables @since 1.6 function __construct() { $this->init(); $this->register_globals(); } Checks if current locale is RTL. @since 1.6 @return bool Whether locale is RTL. function is_rtl() { return 'rtl' == $this->text_direction; } } Loads a custom translation file (for a plugin, a theme, a public interface...) if locale is defined The .mo file should be named based on the domain with a dash, and then the locale exactly, eg 'myplugin-pt_BR.mo' @since 1.6 @param string $domain Unique identifier (the "domain") for retrieving translated strings @param string $path Full path to directory containing MO files. @return mixed|void Returns nothing if locale undefined, otherwise return bool: true on success, false on failure

**`yourls_load_default_textdomain()`**
— Loads default translated strings based on locale. Loads the .mo file in YOURLS_LANG_DIR constant path from YOURLS root. The translated (.mo) file is named based on the locale. @since 1.6 @return bool True on success, false on failure

**`yourls_load_textdomain($domain, $mofile)`**
— Loads a MO file into the domain $domain. If the domain already exists, the translations will be merged. If both sets have the same string, the translation from the original value will be taken. On success, the .mo file will be placed in the $yourls_l10n global by $domain and will be a MO object. @since 1.6 @param string $domain Unique identifier for retrieving translated strings @param string $mofile Path to the .mo file @return bool True on success, false on failure

**`yourls_n($single, $plural, $number, $domain = 'default')`**
— Retrieve the plural or single form based on the amount. If the domain is not set in the $yourls_l10n list, then a comparison will be made and either $plural or $single parameters returned. If the domain does exist, then the parameters $single, $plural, and $number will first be passed to the domain's ngettext method. Then it will be passed to the 'translate_n' filter hook along with the same parameters. The expected type will be a string. @since 1.6 @param string $single The text that will be used if $number is 1 @param string $plural The text that will be used if $number is not 1 @param int $number The number to compare against to use either $single or $plural @param string $domain Optional. The domain identifier the text should be retrieved in @return string Either $single or $plural translated text

**`yourls_n_noop($singular, $plural, $domain = null)`**
— Register plural strings in POT file, but don't translate them. Used when you want to keep structures with translatable plural strings and use them later. Example: $messages = array( 'post' => yourls_n_noop('%s post', '%s posts'), 'page' => yourls_n_noop('%s pages', '%s pages') ); ... $message = $messages[$type]; $usable_text = sprintf( yourls_translate_nooped_plural( $message, $count ), $count ); @since 1.6 @param string $singular Single form to be i18ned @param string $plural Plural form to be i18ned @param string $domain Optional. The domain identifier the text will be retrieved in @return array array($singular, $plural)

**`yourls_number_format_i18n($number, $decimals = 0)`**
— Return integer number to format based on the locale. @since 1.6 @param int $number The number to convert based on locale. @param int $decimals Precision of the number of decimal places. @return string Converted number in string format.

**`yourls_nx($single, $plural, $number, $context, $domain = 'default')`**
— A hybrid of yourls_n() and yourls_x(). It supports contexts and plurals. @since 1.6 @see yourls_n() @see yourls_x() @param string $single   The text that will be used if $number is 1 @param string $plural   The text that will be used if $number is not 1 @param int $number      The number to compare against to use either $single or $plural @param string $context  Context information for the translators @param string $domain   Optional. The domain identifier the text should be retrieved in @return string          Either $single or $plural translated text

**`yourls_nx_noop($singular, $plural, $context, $domain = null)`**
— Register plural strings with context in POT file, but don't translate them. @since 1.6 @see yourls_n_noop() @param string $singular Single form to be i18ned @param string $plural   Plural form to be i18ned @param string $context  Context information for the translators @param string $domain   Optional. The domain identifier the text will be retrieved in @return array           array($singular, $plural)

**`yourls_s($pattern)`**
— Return a translated sprintf() string (mix yourls__() and sprintf() in one func) Instead of doing sprintf( yourls__( 'string %s' ), $arg ) you can simply use: yourls_s( 'string %s', $arg ) This function accepts an arbitrary number of arguments: - first one will be the string to translate, eg "hello %s my name is %s" - following ones will be the sprintf arguments, eg "world" and "Ozh" - if there are more arguments passed than needed, the last one will be used as the translation domain @see sprintf() @since 1.6 @param mixed ...$pattern Text to translate, then $arg1: optional sprintf tokens, and $arg2: translation domain @return string Translated text

**`yourls_se($pattern)`**
— Echo a translated sprintf() string (mix yourls__() and sprintf() in one func) Instead of doing printf( yourls__( 'string %s' ), $arg ) you can simply use: yourls_se( 'string %s', $arg ) This function accepts an arbitrary number of arguments: - first one will be the string to translate, eg "hello %s my name is %s" - following ones will be the sprintf arguments, eg "world" and "Ozh" - if there are more arguments passed than needed, the last one will be used as the translation domain @see yourls_s() @see sprintf() @since 1.6 @param string ...$pattern Text to translate, then optional sprintf tokens, and optional translation domain @return void Translated text

**`yourls_translate($text, $domain = 'default')`**
— Retrieves the translation of $text. If there is no translation, or the domain isn't loaded, the original text is returned. @see yourls__() Don't use yourls_translate() directly, use yourls__() @since 1.6 @param string $text Text to translate. @param string $domain Domain to retrieve the translated text. @return string Translated text

**`yourls_translate_nooped_plural($nooped_plural, $count, $domain = 'default')`**
— Translate the result of yourls_n_noop() or yourls_nx_noop() @since 1.6 @param array $nooped_plural Array with singular, plural and context keys, usually the result of yourls_n_noop() or yourls_nx_noop() @param int $count Number of objects @param string $domain Optional. The domain identifier the text should be retrieved in. If $nooped_plural contains a domain passed to yourls_n_noop() or yourls_nx_noop(), it will override this value. @return string

**`yourls_translate_user_role($name)`**
— Translates role name. Unused. Unused function for the moment, we'll see when there are roles. From the WP source: Since the role names are in the database and not in the source there are dummy gettext calls to get them into the POT file and this function properly translates them back. @since 1.6 @param string $name The role name @return string Translated role name

**`yourls_translate_with_context($text, $context, $domain = 'default')`**
— Retrieves the translation of $text with a given $context. If there is no translation, or the domain isn't loaded, the original text is returned. Quite a few times, there will be collisions with similar translatable text found in more than two places but with different translated context. By including the context in the pot file translators can translate the two strings differently. @since 1.6 @param string $text Text to translate. @param string $context Context. @param string $domain Domain to retrieve the translated text. @return string Translated text

**`yourls_unload_textdomain($domain)`**
— Unloads translations for a domain @since 1.6 @param string $domain Textdomain to be unloaded @return bool Whether textdomain was unloaded

**`yourls_x($text, $context, $domain = 'default')`**
— Retrieve translated string with gettext context Quite a few times, there will be collisions with similar translatable text found in more than two places but with different translated context. By including the context in the pot file translators can translate the two strings differently. @since 1.6 @param string $text Text to translate @param string $context Context information for the translators @param string $domain Optional. Domain to retrieve the translated text @return string Translated context string

**`yourls_xe($text, $context, $domain = 'default')`**
— Displays translated string with gettext context @see yourls_x() @since 1.7.1 @param string $text Text to translate @param string $context Context information for the translators @param string $domain Optional. Domain to retrieve the translated text @return void Echoes translated context string

### `includes\functions-links.php`

**`yourls_add_query_arg()`**
— Add a query var to a URL and return URL. Completely stolen from WP. Works with one of these parameter patterns: array( 'var' => 'value' ) array( 'var' => 'value' ), $url 'var', 'value' 'var', 'value', $url If $url omitted, uses $_SERVER['REQUEST_URI'] The result of this function call is a URL : it should be escaped before being printed as HTML @since 1.5 @param string|array $param1 Either newkey or an associative_array. @param string       $param2 Either newvalue or oldquery or URI. @param string       $param3 Optional. Old query or URI. @return string New URL query string.

**`yourls_admin_url($page = '')`**
— Return admin link, with SSL preference if applicable. @param string $page  Page name, eg "index.php" @return string

**`yourls_get_yourls_favicon_url($echo = true)`**
— Auto detect custom favicon in /user directory, fallback to YOURLS favicon, and echo/return its URL This function supersedes function yourls_favicon(), deprecated in 1.7.10, with a better naming. @since 1.7.10 @param  bool $echo   true to echo, false to silently return @return string       favicon URL

**`yourls_get_yourls_site()`**
— Get YOURLS_SITE value, trimmed and filtered In addition of being filtered for plugins to hack this, this function is mostly here to help people entering "sho.rt/" instead of "sho.rt" in their config @since 1.7.7 @return string  YOURLS_SITE, trimmed and filtered

**`yourls_link($keyword = '', $stats = false)`**
— Converts keyword into short link (prepend with YOURLS base URL) or stat link (sho.rt/abc+) This function does not check for a valid keyword. The resulting link is normalized to allow for IDN translation to UTF8 @param  string $keyword  Short URL keyword @param  bool   $stats    Optional, true to return a stat link (eg sho.rt/abc+) @return string           Short URL, or keyword stat URL

**`yourls_match_current_protocol($url, $normal = 'http://', $ssl = 'https://')`**
— Change protocol of a URL to HTTPS if we are currently on HTTPS This function is used to avoid insert 'http://' images or scripts in a page when it's served through HTTPS, to avoid "mixed content" errors. So: - if you are on http://sho.rt/, 'http://something' and 'https://something' are left untouched. - if you are on https:/sho.rt/, 'http://something' is changed to 'https://something' So, arguably, this function is poorly named. It should be something like yourls_match_current_protocol_if_we_re_on_https @since 1.5.1 @param string $url        a URL @param string $normal     Optional, the standard scheme (defaults to 'http://') @param string $ssl        Optional, the SSL scheme (defaults to 'https://') @return string            the modified URL, if applicable

**`yourls_remove_query_arg($key, $query = false)`**
— Remove arg from query. Opposite of yourls_add_query_arg. Stolen from WP. The result of this function call is a URL : it should be escaped before being printed as HTML @since 1.5 @param string|array $key   Query key or keys to remove. @param bool|string  $query Optional. When false uses the $_SERVER value. Default false. @return string New URL query string.

**`yourls_site_url($echo = true, $url = '')`**
— Return YOURLS_SITE or URL under YOURLS setup, with SSL preference @param bool $echo   Echo if true, or return if false @param string $url @return string

**`yourls_statlink($keyword = '')`**
— Converts keyword into stat link (prepend with YOURLS base URL, append +) This function does not make sure the keyword matches an actual short URL @param  string $keyword  Short URL keyword @return string           Short URL stat link

**`yourls_urlencode_deep($value)`**
— Navigates through an array and encodes the values to be used in a URL. Stolen from WP, used in yourls_add_query_arg() @param array|string $value The array or string to be encoded. @return array|string

### `includes\functions-options.php`

**`yourls_add_option($name, $value = '')`**
— Add an option to the DB Pretty much stolen from WordPress @since 1.4 @param string $name Name of option to add. Expected to not be SQL-escaped. @param mixed $value Optional option value. Must be serializable if non-scalar. Expected to not be SQL-escaped. @return bool False if option was not added and true otherwise.

**`yourls_delete_option($name)`**
— Delete an option from the DB Pretty much stolen from WordPress @since 1.4 @param string $name Option name to delete. Expected to not be SQL-escaped. @return bool True, if option is successfully deleted. False on failure.

**`yourls_get_all_options()`**
— Read all options from DB at once The goal is to read all options at once and then populate array $ydb->option, to prevent further SQL queries if we need to read an option value later. It's also a simple check whether YOURLS is installed or not (no option = assuming not installed) after a check for DB server reachability has been performed @since 1.4 @return void

**`yourls_get_option($option_name, $default = false)`**
— Read an option from DB (or from cache if available). Return value or $default if not found Pretty much stolen from WordPress @since 1.4 @param string $option_name Option name. Expected to not be SQL-escaped. @param mixed $default Optional value to return if option doesn't exist. Default false. @return mixed Value set for the option.

**`yourls_is_serialized($data, $strict = true)`**
— Check value to find if it was serialized. Stolen from WordPress @since 1.4 @param mixed $data Value to check to see if was serialized. @param bool $strict Optional. Whether to be strict about the end of the string. Defaults true. @return bool False if not serialized and true if it was.

**`yourls_maybe_serialize($data)`**
— Serialize data if needed. Stolen from WordPress @since 1.4 @param mixed $data Data that might be serialized. @return mixed A scalar data

**`yourls_maybe_unserialize($original)`**
— Unserialize value only if it was serialized. Stolen from WP @since 1.4 @param string $original Maybe unserialized original, if is needed. @return mixed Unserialized data can be any type.

**`yourls_update_option($option_name, $newvalue)`**
— Update (add if doesn't exist) an option to DB Pretty much stolen from WordPress @since 1.4 @param string $option_name Option name. Expected to not be SQL-escaped. @param mixed $newvalue Option value. Must be serializable if non-scalar. Expected to not be SQL-escaped. @return bool False if value was not updated, true otherwise.

### `includes\functions-plugins.php`

**`yourls_activate_plugin($plugin)`**
— Activate a plugin @since 1.5 @param string $plugin Plugin filename (full or relative to plugins directory) @return string|true  string if error or true if success

**`yourls_add_action($hook, $function_name, $priority = 10, $accepted_args = 1)`**
— Hooks a function on to a specific action. Actions are the hooks that YOURLS launches at specific points during execution, or when specific events occur. Plugins can specify that one or more of its PHP functions are executed at these points, using the Action API. Typical use: yourls_add_action('some_hook', 'function_handler_for_hook'); @link  https://docs.yourls.org/development/plugins.html @param string   $hook           The name of the action to which the $function_to_add is hooked. @param callable $function_name  The name of the function you wish to be called. @param int      $priority       Optional. Used to specify the order in which the functions associated with a particular action are executed (default: 10). Lower numbers correspond with earlier execution, and functions with the same priority are executed in the order in which they were added to the action. @param int      $accepted_args  Optional. The number of arguments the function accept (default 1). @return void

**`yourls_add_filter($hook, $function_name, $priority = 10, $accepted_args = NULL, $type = 'filter')`**
— The filter/plugin API is located in this file, which allows for creating filters and hooking functions, and methods. The functions or methods will be run when the filter is called. Any of the syntaxes explained in the PHP documentation for the {@link https://www.php.net/manual/en/language.types.callable.php 'callback'} type are valid. This API is heavily inspired by the one I implemented in Zenphoto 1.3, which was heavily inspired by the one used in WordPress. @author Ozh @since 1.5 This global var will collect filters with the following structure: $yourls_filters['hook']['array of priorities']['serialized function names']['array of ['array (functions, accepted_args, filter or action)]'] Real life example : print_r($yourls_filters) : Array ( [plugins_loaded] => Array ( [10] => Array ( [yourls_kses_init] => Array ( [function] => yourls_kses_init [accepted_args] => 1 [type] => action ) [yourls_tzp_config] => Array ( [function] => yourls_tzp_config [accepted_args] => 1 [type] => action ) ) ) [admin_menu] => Array ( [10] => Array ( [ozh_show_db] => Array ( [function] => ozh_show_db [accepted_args] => [type] => filter ) ) ) ) @var array $yourls_filters if ( !isset( $yourls_filters ) ) { $yourls_filters = []; } This global var will collect 'done' actions with the following structure: $yourls_actions['hook'] => number of time this action was done @var array $yourls_actions if ( !isset( $yourls_actions ) ) { $yourls_actions = []; } Registers a filtering function Typical use: yourls_add_filter('some_hook', 'function_handler_for_hook'); @link  https://docs.yourls.org/development/plugins.html @param string   $hook           the name of the YOURLS element to be filtered or YOURLS action to be triggered @param callable $function_name  the name of the function that is to be called. @param int      $priority       optional. Used to specify the order in which the functions associated with a particular action are executed (default=10, lower=earlier execution, and functions with the same priority are executed in the order in which they were added to the filter) @param int      $accepted_args  optional. The number of arguments the function accept (default is the number provided). @param string   $type @return void

**`yourls_apply_filter($hook, $value = '', $is_action = false)`**
— Performs a filtering operation on a value or an event. Typical use: 1) Modify a variable if a function is attached to hook 'yourls_hook' $yourls_var = "default value"; $yourls_var = yourls_apply_filter( 'yourls_hook', $yourls_var ); 2) Trigger functions is attached to event 'yourls_event' yourls_apply_filter( 'yourls_event' ); (see yourls_do_action() ) Returns a value which may have been modified by a filter. @param string $hook the name of the YOURLS element or action @param mixed $value the value of the element before filtering @param true|mixed $is_action true if the function is called by yourls_do_action() - otherwise may be the second parameter of an arbitrary number of parameters @return mixed

**`yourls_call_all_hooks($type, $hook, ...$args)`**
— Execute the 'all' hook, with all of the arguments or parameters that were used for the hook Internal function used by yourls_do_action() and yourls_apply_filter() - not meant to be used from outside these functions. This is mostly a debugging function to understand the flow of events. See https://docs.yourls.org/development/debugging.html to learn how to use the 'all' hook @link   https://docs.yourls.org/development/debugging.html @since  1.8.1 @param  string $type Either 'action' or 'filter' @param  string $hook The hook name, eg 'plugins_loaded' @param  mixed  $args Variable-length argument lists that were passed to the action or filter @return void

**`yourls_deactivate_plugin($plugin)`**
— Deactivate a plugin @since 1.5 @param string $plugin Plugin filename (full relative to plugins directory) @return string|true  string if error or true if success

**`yourls_did_action($hook)`**
— Retrieve the number times an action is fired. @param string $hook Name of the action hook. @return int The number of times action hook <tt>$hook</tt> is fired

**`yourls_do_action($hook, $arg = '')`**
— Performs an action triggered by a YOURLS event. @param string $hook the name of the YOURLS action @param mixed $arg action arguments @return void

**`yourls_filter_unique_id($function)`**
— Build Unique ID for storage and retrieval. Simply using a function name is not enough, as several functions can have the same name when they are enclosed in classes. Possible ways to attach a function to a hook (filter or action): - strings: yourls_add_filter('my_hook_test', 'my_callback_function'); yourls_add_filter('my_hook_test', 'My_Class::my_callback_function'); - arrays: yourls_add_filter('my_hook_test', array('My_Class','my_callback_function')); yourls_add_filter('my_hook_test', array($class_instance, 'my_callback_function')); - objects: yourls_add_filter('my_hook_test', $class_instance_with_invoke_method); yourls_add_filter('my_hook_test', $my_callback_function); @link https://docs.yourls.org/development/hooks.html @param  string|array|object $function  The callable used in a filter or action. @return string  unique ID for usage as array key

**`yourls_get_actions($hook)`**
— Return actions for a specific hook. @since 1.8.3 @param string $hook The hook to retrieve actions for @return array

**`yourls_get_filters($hook)`**
— Return filters for a specific hook. If hook has filters (or actions, see yourls_has_action()), this will return an array priorities => callbacks. See the structure of yourls_filters on top of this file for details. @since 1.8.3 @param string $hook The hook to retrieve filters for @return array

**`yourls_get_plugin_data($file)`**
— Parse a plugin header The plugin header has the following form: Plugin Name: <plugin name> Plugin URI: <plugin home page> Description: <plugin description> Version: <plugin version number> Author: <author name> Author URI: <author home page> Or in the form of a phpdoc block Plugin Name: <plugin name> Plugin URI: <plugin home page> Description: <plugin description> Version: <plugin version number> Author: <author name> Author URI: <author home page> @since 1.5 @param string $file Physical path to plugin file @return array Array of 'Field'=>'Value' from plugin comment header lines of the form "Field: Value"

**`yourls_get_plugins()`**
— List plugins in /user/plugins @return array Array of [/plugindir/plugin.php]=>array('Name'=>'Ozh', 'Title'=>'Hello', )

**`yourls_has_action($hook, $function_to_check = false)`**
— Check if any action has been registered for a hook. @since 1.5 @param string         $hook @param callable|false $function_to_check @return bool|int

**`yourls_has_active_plugins()`**
— Return number of active plugins @return int Number of activated plugins

**`yourls_has_filter($hook, $function_to_check = false)`**
— Check if any filter has been registered for a hook. @since 1.5 @param string         $hook              The name of the filter hook. @param callable|false $function_to_check optional. If specified, return the priority of that function on this hook or false if not attached. @return int|bool Optionally returns the priority on that hook for the specified function.

**`yourls_is_a_plugin_file($file)`**
— Check if a file is a plugin file This doesn't check if the file is a valid PHP file, only that it's correctly named. @since 1.5 @param string $file Full pathname to a file @return bool

**`yourls_is_active_plugin($plugin)`**
— Check if a plugin is active @param string $plugin Physical path to plugin file @return bool

**`yourls_list_plugin_admin_pages()`**
— Build list of links to plugin admin pages, if any @since 1.5 @return array  Array of arrays of URL and anchor of plugin admin pages, or empty array if no plugin page

**`yourls_load_plugins()`**
— Include active plugins This function includes every 'YOURLS_PLUGINDIR/plugin_name/plugin.php' found in option 'active_plugins' It will return a diagnosis array with the following keys: (bool)'loaded' : true if plugin(s) loaded, false otherwise (string)'info' : extra information @since 1.5 @return array    Array('loaded' => bool, 'info' => string)

**`yourls_plugin_admin_page($plugin_page)`**
— Handle plugin administration page @since 1.5 @param string $plugin_page @return void

**`yourls_plugin_basename($file)`**
— Return the path of a plugin file, relative to the plugins directory @since 1.5 @param string $file @return string

**`yourls_plugin_url($file)`**
— Return the URL of the directory a plugin @since 1.5 @param string $file @return string

**`yourls_plugins_sort_callback($plugin_a, $plugin_b)`**
— Callback function: Sort plugins @link http://php.net/uasort @codeCoverageIgnore @since 1.5 @param array $plugin_a @param array $plugin_b @return int 0, 1 or -1, see uasort()

**`yourls_register_plugin_page($slug, $title, $function)`**
— Register a plugin administration page @since 1.5 @param string   $slug @param string   $title @param callable $function @return void

**`yourls_remove_action($hook, $function_to_remove, $priority = 10)`**
— Removes a function from a specified action hook. @see yourls_remove_filter() @since 1.7.1 @param string   $hook               The action hook to which the function to be removed is hooked. @param callable $function_to_remove The name of the function which should be removed. @param int      $priority           optional. The priority of the function (default: 10). @return bool                        Whether the function was registered as an action before it was removed.

**`yourls_remove_all_actions($hook, $priority = false)`**
— Removes all functions from a specified action hook. @see   yourls_remove_all_filters() @since 1.7.1 @param string    $hook     The action to remove hooks from @param int|false $priority optional. The priority of the functions to remove @return bool true when it's finished

**`yourls_remove_all_filters($hook, $priority = false)`**
— Removes all functions from a specified filter hook. @since 1.7.1 @param string    $hook     The filter to remove hooks from @param int|false $priority optional. The priority of the functions to remove @return bool true when it's finished

**`yourls_remove_filter($hook, $function_to_remove, $priority = 10)`**
— Note that we don't return a value here, regardless of $type being an action (obviously) but also a filter. Indeed it would not make sense to actually "filter" and return values when we're feeding the same function every single hook in YOURLS, no matter their parameters. } } while ( next($yourls_filters['all']) !== false ); } Removes a function from a specified filter hook. This function removes a function attached to a specified filter hook. This method can be used to remove default functions attached to a specific filter hook and possibly replace them with a substitute. To remove a hook, the $function_to_remove and $priority arguments must match when the hook was added. @param string $hook The filter hook to which the function to be removed is hooked. @param callable $function_to_remove The name of the function which should be removed. @param int $priority optional. The priority of the function (default: 10). @return bool Whether the function was registered as a filter before it was removed.

**`yourls_return_empty_array()`**
— Returns an empty array. Useful for returning an empty array to filters easily. @since 1.7.1 @return array Empty array.

**`yourls_return_empty_string()`**
— Returns an empty string. Useful for returning an empty string to filters easily. @since 1.7.1 @return string Empty string.

**`yourls_return_false()`**
— Returns false. Useful for returning false to filters easily. @since 1.7.1 @return bool False.

**`yourls_return_null()`**
— Returns null. Useful for returning null to filters easily. @since 1.7.1 @return null Null value.

**`yourls_return_true()`**
— Returns true. Useful for returning true to filters easily. @since 1.7.1 @return bool True.

**`yourls_return_zero()`**
— Returns 0. Useful for returning 0 to filters easily. @since 1.7.1 @return int 0.

**`yourls_shunt_default()`**
— Default value used to check for 'shunt_*' filters. Before 1.10.4 we were checking for false, but that's not efficient as filtered functions can legitimately return false. @since 1.10.4 @return string

**`yourls_shutdown()`**
— Shutdown function, runs just before PHP shuts down execution. Stolen from WP This function is automatically tied to the script execution end at startup time, see var $actions->register_shutdown in includes/Config/Init.php You can use this function to fire one or several actions when the PHP execution ends. Example of use: yourls_add_action('shutdown', 'my_plugin_action_this'); yourls_add_action('shutdown', 'my_plugin_action_that'); functions my_plugin_action_this() and my_plugin_action_that() will be triggered after YOURLS is completely executed @codeCoverageIgnore @since 1.5.1 @return void

### `includes\functions-shorturls.php`

**`yourls_add_new_link($url, $keyword = '', $title = '', $row_id = 1)`**
— Add a new link in the DB, either with custom keyword, or find one The return array will contain at least the following keys: status: string, 'success' or 'fail' message: string, a descriptive localized message of what happened in any case code: string, a short descriptivish and untranslated message describing what happened errorCode: string, a HTTP status code statusCode: string, a HTTP status code Depending on the operation, it will contain any of the following keys: url: array, the short URL creation information, with keys: 'keyword', 'url', 'title', 'date', 'ip', 'clicks' title: string, the URL title shorturl: string, the proper short URL in full (eg 'http://sho.rt/abc') html: string, the HTML part used by the ajax to update the page display if any For compatibility with early consumers and third parties, when people asked for various data and data formats before the internal API was really structured, the return array now collects several redundant information. @param  string $url      URL to shorten @param  string $keyword  optional "keyword" @param  string $title    option title @param  int    $row_id   used to form unique IDs in the generated HTML @return array            array with error/success state and short URL information

**`yourls_delete_link_by_keyword($keyword)`**
— Delete a link in the DB @param  string $keyword   Short URL keyword @return int               Number of links deleted

**`yourls_edit_link($url, $keyword, $newkeyword='', $title='')`**
— Edit a link @param string $url @param string $keyword @param string $newkeyword @param string $title @return array Result of the edit and link information if successful

**`yourls_edit_link_title($keyword, $title)`**
— Update a title link (no checks for duplicates etc..) @param string $keyword @param string $title @return int number of rows updated

**`yourls_get_keyword_IP($keyword, $notfound = false)`**
— Return IP that added a keyword. Optional $notfound = string default message if nothing found @param string $keyword          Short URL keyword @param false|string $notfound   Optional string to return if keyword not found @return mixed|string

**`yourls_get_keyword_clicks($keyword, $notfound = false)`**
— Return number of clicks on a keyword. Optional $notfound = string default message if nothing found @param string $keyword          Short URL keyword @param false|string $notfound   Optional string to return if keyword not found @return mixed|string

**`yourls_get_keyword_info($keyword, $field, $notfound = false)`**
— Return information associated with a keyword (eg clicks, URL, title...). Optional $notfound = string default message if nothing found @param string $keyword          Short URL keyword @param string $field            Field to return (eg 'url', 'title', 'ip', 'clicks', 'timestamp', 'keyword') @param false|string $notfound   Optional string to return if keyword not found @return mixed|string

**`yourls_get_keyword_infos($keyword, $use_cache = true)`**
— Return array of all information associated with keyword. Returns false if keyword not found. Set optional $use_cache to false to force fetching from DB Sincere apologies to native English speakers, we are aware that the plural of 'info' is actually 'info', not 'infos'. This function yourls_get_keyword_infos() returns all information, while function yourls_get_keyword_info() (no 's') return only one information. Blame YOURLS contributors whose mother tongue is not English :) @since 1.4 @param  string $keyword    Short URL keyword @param  bool   $use_cache  Default true, set to false to force fetching from DB @return false|object       false if not found, object with URL properties if found

**`yourls_get_keyword_longurl($keyword, $notfound = false)`**
— Return long URL associated with keyword. Optional $notfound = string default message if nothing found @param string $keyword          Short URL keyword @param false|string $notfound   Optional string to return if keyword not found @return mixed|string

**`yourls_get_keyword_stats($shorturl)`**
— Return array of stats for a given keyword This function supersedes function yourls_get_link_stats(), deprecated in 1.7.10, with a better naming. @since 1.7.10 @param  string $shorturl short URL keyword @return array            stats

**`yourls_get_keyword_timestamp($keyword, $notfound = false)`**
— Return timestamp associated with a keyword. Optional $notfound = string default message if nothing found @param string $keyword          Short URL keyword @param false|string $notfound   Optional string to return if keyword not found @return mixed|string

**`yourls_get_keyword_title($keyword, $notfound = false)`**
— Return title associated with keyword. Optional $notfound = string default message if nothing found @param string $keyword          Short URL keyword @param false|string $notfound   Optional string to return if keyword not found @return mixed|string

**`yourls_get_longurl_keywords($longurl, $order = 'ASC')`**
— Return array of keywords that redirect to the submitted long URL @since 1.7 @param string $longurl long url @param string $order Optional SORT order (can be 'ASC' or 'DESC') @return array array of keywords

**`yourls_get_reserved_URL()`**
— Get the list of reserved keywords for URLs. @return array             Array of reserved keywords

**`yourls_get_shorturl_charset()`**
— The result array. $return = [ Always present : 'status' => '', 'code'   => '', 'message' => '', 'errorCode' => '', 'statusCode' => '', ]; Sanitize URL $url = yourls_sanitize_url( $url ); if ( !$url || $url == 'http://' || $url == 'https://' ) { $return['status']    = 'fail'; $return['code']      = 'error:nourl'; $return['message']   = yourls__( 'Missing or malformed URL' ); $return['errorCode'] = $return['statusCode'] = '400'; // 400 Bad Request return yourls_apply_filter( 'add_new_link_fail_nourl', $return, $url, $keyword, $title ); } Prevent DB flood $ip = yourls_get_IP(); yourls_check_IP_flood( $ip ); Prevent internal redirection loops: cannot shorten a shortened URL if (yourls_is_shorturl($url)) { $return['status']    = 'fail'; $return['code']      = 'error:noloop'; $return['message']   = yourls__( 'URL is a short URL' ); $return['errorCode'] = $return['statusCode'] = '400'; // 400 Bad Request return yourls_apply_filter( 'add_new_link_fail_noloop', $return, $url, $keyword, $title ); } yourls_do_action( 'pre_add_new_link', $url, $keyword, $title ); Check if URL was already stored and we don't accept duplicates if ( !yourls_allow_duplicate_longurls() && ($url_exists = yourls_long_url_exists( $url )) ) { yourls_do_action( 'add_new_link_already_stored', $url, $keyword, $title ); $return['status']   = 'fail'; $return['code']     = 'error:url'; $return['url']      = array( 'keyword' => $url_exists->keyword, 'url' => $url, 'title' => $url_exists->title, 'date' => $url_exists->timestamp, 'ip' => $url_exists->ip, 'clicks' => $url_exists->clicks ); $return['message']  = /* //translators: eg "http://someurl/ already exists (short URL: sho.rt/abc)" */ yourls_s('%s already exists in database (short URL: %s)', yourls_trim_long_string($url), preg_replace('!https?://!', '',  yourls_get_yourls_site()) . '/'. $url_exists->keyword ); $return['title']    = $url_exists->title; $return['shorturl'] = yourls_link($url_exists->keyword); $return['errorCode'] = $return['statusCode'] = '400'; // 400 Bad Request return yourls_apply_filter( 'add_new_link_already_stored_filter', $return, $url, $keyword, $title ); } Sanitize provided title, or fetch one if( isset( $title ) && !empty( $title ) ) { $title = yourls_sanitize_title( $title ); } else { $title = yourls_get_remote_title( $url ); } $title = yourls_apply_filter( 'add_new_title', $title, $url, $keyword ); Custom keyword provided : sanitize and make sure it's free if ($keyword) { yourls_do_action( 'add_new_link_custom_keyword', $url, $keyword, $title ); $keyword = yourls_sanitize_keyword( $keyword, true ); $keyword = yourls_apply_filter( 'custom_keyword', $keyword, $url, $title ); if ( !yourls_keyword_is_free( $keyword ) ) { This shorturl either reserved or taken already $return['status']  = 'fail'; $return['code']    = 'error:keyword'; $return['message'] = yourls_s( 'Short URL %s already exists in database or is reserved', $keyword ); $return['errorCode'] = $return['statusCode'] = '400'; // 400 Bad Request return yourls_apply_filter( 'add_new_link_keyword_exists', $return, $url, $keyword, $title ); } Create random keyword } else { yourls_do_action( 'add_new_link_create_keyword', $url, $keyword, $title ); $id = yourls_get_next_decimal(); do { $keyword = yourls_int2string( $id ); $keyword = yourls_apply_filter( 'random_keyword', $keyword, $url, $title ); $id++; } while ( !yourls_keyword_is_free($keyword) ); yourls_update_next_decimal($id); } We should be all set now. Store the short URL ! $timestamp = date( 'Y-m-d H:i:s' ); try { if (yourls_insert_link_in_db( $url, $keyword, $title )){ everything ok, populate needed vars $return['url']      = array('keyword' => $keyword, 'url' => $url, 'title' => $title, 'date' => $timestamp, 'ip' => $ip ); $return['status']   = 'success'; $return['message']  = /* //translators: eg "http://someurl/ added to DB" */ yourls_s( '%s added to database', yourls_trim_long_string( $url ) ); $return['title']    = $title; $return['html']     = yourls_table_add_row( $keyword, $url, $title, $ip, 0, time(), $row_id ); $return['shorturl'] = yourls_link($keyword); $return['statusCode'] = '200'; // 200 OK } else { unknown database error, couldn't store result $return['status']   = 'fail'; $return['code']     = 'error:db'; $return['message']  = yourls_s( 'Error saving url to database' ); $return['errorCode'] = $return['statusCode'] = '500'; // 500 Internal Server Error } } catch (Exception $e) { Keyword supposed to be free but the INSERT caused an exception: most likely we're facing a concurrency problem. See Issue 2538. $return['status']  = 'fail'; $return['code']    = 'error:concurrency'; $return['message'] = $e->getMessage(); $return['errorCode'] = $return['statusCode'] = '503'; // 503 Service Unavailable } yourls_do_action( 'post_add_new_link', $url, $keyword, $title, $return ); return yourls_apply_filter( 'add_new_link', $return, $url, $keyword, $title ); } Determine the allowed character set in short URLs @return string    Acceptable charset for short URLS keywords

**`yourls_insert_link_in_db($url, $keyword, $title = '')`**
— SQL query to insert a new link in the DB. Returns boolean for success or failure of the inserting @param string $url @param string $keyword @param string $title @return bool true if insert succeeded, false if failed

**`yourls_is_page($keyword)`**
— Check if a keyword matches a "page" @see https://docs.yourls.org/guide/extend/pages.html @since 1.7.10 @param  string $keyword  Short URL $keyword @return bool             true if is page, false otherwise

**`yourls_is_shorturl($shorturl)`**
— Is a URL a short URL? Accept either 'http://sho.rt/abc' or 'abc' @param  string $shorturl   short URL @return bool               true if registered short URL, false otherwise

**`yourls_keyword_is_free($keyword)`**
— Check if keyword id is free (ie not already taken, and not reserved). Return bool. @param  string $keyword    short URL keyword @return bool               true if keyword is taken (ie there is a short URL for it), false otherwise

**`yourls_keyword_is_reserved($keyword)`**
— Check to see if a given keyword is reserved (ie reserved URL or an existing page). Returns bool @param  string $keyword   Short URL keyword @return bool              True if keyword reserved, false if free to be used

**`yourls_keyword_is_taken($keyword, $use_cache = true)`**
— Check if a keyword is taken (ie there is already a short URL with this id). Return bool. Check if a keyword is taken (ie there is already a short URL with this id). Return bool. @param  string $keyword    short URL keyword @param  bool   $use_cache  optional, default true: do we want to use what is cached in memory, if any, or force a new SQL query @return bool               true if keyword is taken (ie there is a short URL for it), false otherwise

**`yourls_long_url_exists($url)`**
— Check if a long URL already exists in the DB. Return NULL (doesn't exist) or an object with URL informations. This function supersedes function yourls_url_exists(), deprecated in 1.7.10, with a better naming. @since 1.7.10 @param  string $url  URL to check if already shortened @return mixed        NULL if does not already exist in DB, or object with URL information as properties (eg keyword, url, title, ...)

### `includes\functions-upgrade.php`

**`yourls_alter_url_table_to_14()`**
— Alter table structure, part 1 (change schema, drop index)

**`yourls_alter_url_table_to_141()`**
— Alter table URL to 1.4.1

**`yourls_alter_url_table_to_14_part_two()`**
— Alter table structure, part 2 (recreate indexes after the table is up to date)

**`yourls_clean_htaccess_for_14()`**
— Clean .htaccess as it existed before 1.4. Returns boolean

**`yourls_create_tables_for_14()`**
— Create new tables for YOURLS 1.4: options & log

**`yourls_update_options_to_14()`**
— Update options to reflect new version

**`yourls_update_table_to_14()`**
— Convert each link from 1.3 (id) to 1.4 (keyword) structure

**`yourls_upgrade($step, $oldver, $newver, $oldsql, $newsql)`**
— Upgrade YOURLS and DB schema Note to devs : prefer update function names using the SQL version, eg yourls_update_to_506(), rather than using the YOURLS version number, eg yourls_update_to_18(). This is to allow having multiple SQL update during the dev cycle of the same YOURLS version @param string|int $step @param string $oldver @param string $newver @param string|int $oldsql @param string|int $newsql @return void

**`yourls_upgrade_482()`**
— 1.5 -> 1.6 Upgrade r482

**`yourls_upgrade_505_to_506()`**
— Update to 506, just the fix for people who had updated to master on 1.7.10

**`yourls_upgrade_to_14($step)`**
— 1.3 -> 1.4 Main func for upgrade from 1.3-RC1 to 1.4

**`yourls_upgrade_to_141()`**
— 1.4 -> 1.4.1 Main func for upgrade from 1.4 to 1.4.1

**`yourls_upgrade_to_143()`**
— 1.4.1 -> 1.4.3 Main func for upgrade from 1.4.1 to 1.4.3

**`yourls_upgrade_to_15()`**
— 1.4.3 -> 1.5 Main func for upgrade from 1.4.3 to 1.5

**`yourls_upgrade_to_506()`**
— Update to 506

**`yourls_upgrade_to_507()`**
— Sanitize input. Two notes : - they should already be sanitized in the caller, eg admin/upgrade.php (but hey, let's make sure) - some vars may not be used at the moment (and this is ok, they are here in case a future upgrade procedure needs them) $step   = intval($step); $oldsql = intval($oldsql); $newsql = intval($newsql); $oldver = yourls_sanitize_version($oldver); $newver = yourls_sanitize_version($newver); yourls_maintenance_mode(true); special case for 1.3: the upgrade is a multi step procedure if( $oldsql == 100 ) { yourls_upgrade_to_14( $step ); } other upgrades which are done in a single pass switch( $step ) { case 1: case 2: if( $oldsql < 210 ) yourls_upgrade_to_141(); if( $oldsql < 220 ) yourls_upgrade_to_143(); if( $oldsql < 250 ) yourls_upgrade_to_15(); if( $oldsql < 482 ) yourls_upgrade_482(); // that was somewhere 1.5 and 1.5.1 ... if( $oldsql < 506 ) { 505 was the botched update with the wrong collation, see #2766 506 is the updated collation. We want : people on 505 to update to 506 people before 505 to update to the FIXED complete upgrade if( $oldsql == 505 ) { yourls_upgrade_505_to_506(); } else { yourls_upgrade_to_506(); } } if( $oldsql < 507 ) { yourls_upgrade_to_507(); } yourls_redirect_javascript( yourls_admin_url( "upgrade.php?step=3" ) ); break; case 3: Update options to reflect latest version yourls_update_option( 'version', YOURLS_VERSION ); yourls_update_option( 'db_version', YOURLS_DB_VERSION ); yourls_maintenance_mode(false); break; } } 1.6 -> 1.8 Add sort index for fast URL lookups. DB version 507.

### `includes\functions.php`

**`yourls_allow_duplicate_longurls()`**
— Allow several short URLs for the same long URL ? @return bool

**`yourls_check_IP_flood($ip = '')`**
— Check if an IP shortens URL too fast to prevent DB flood. Return true, or die. @param string $ip @return bool|mixed|string

**`yourls_check_maintenance_mode()`**
— Check for maintenance mode. If yes, die. See yourls_maintenance_mode(). Stolen from WP. @return void

**`yourls_content_type_header($type)`**
— Send a filterable content type header @since 1.7 @param string $type content type ('text/html', 'application/json', ...) @return bool whether header was sent

**`yourls_deprecated_function($function, $version, $replacement = null)`**
— Marks a function as deprecated and informs that it has been used. Stolen from WP. There is a hook deprecated_function that will be called that can be used to get the backtrace up to what file and function called the deprecated function. The current behavior is to trigger a user error if YOURLS_DEBUG is true. This function is to be used in every function that is deprecated. @since 1.6 @param string $function The function that was called @param string $version The version of WordPress that deprecated the function @param string $replacement Optional. The function that should have been called @return void

**`yourls_do_log_redirect()`**
— Check if we want to not log redirects (for stats) @return bool

**`yourls_fix_request_uri()`**
— Fix $_SERVER['REQUEST_URI'] variable for various setups. Stolen from WP. We also strip $_COOKIE from $_REQUEST to allow our lazy using $_REQUEST without 3rd party cookie interfering. See #3383 for explanation. @since 1.5.1 @return void

**`yourls_get_HTTP_status($code)`**
— Return an HTTP status code @param int $code @return string

**`yourls_get_IP()`**
— Get client IP Address. Returns a DB safe string. @return string

**`yourls_get_current_version_from_sql()`**
— Get current version & db version as stored in the options DB. Prior to 1.4 there's no option table. @return array

**`yourls_get_db_stats($where = [ 'sql' => '', 'binds' => [] ])`**
— Get total number of URLs and sum of clicks. Input: optional "AND WHERE" clause. Returns array The $where parameter will contain additional SQL arguments: $where['sql'] will concatenate SQL clauses: $where['sql'] = ' AND something = :value AND otherthing < :othervalue'; $where['binds'] will hold the (name => value) placeholder pairs: $where['binds'] = array('value' => $value, 'othervalue' => $value2) @param  array $where See comment above @return array

**`yourls_get_next_decimal()`**
— Get next id a new link will have if no custom keyword provided @since 1.0 @return int            id of next link

**`yourls_get_protocol($url)`**
— Get protocol from a URL (eg mailto:, http:// ...) What we liberally call a "protocol" in YOURLS is the scheme name + colon + double slashes if present of a URI. Examples: "something://blah" -> "something://" "something:blah"   -> "something:" "something:/blah"  -> "something:" Unit Tests for this function are located in tests/format/urls.php @since 1.6 @param string $url URL to be check @return string Protocol, with slash slash if applicable. Empty string if no protocol

**`yourls_get_protocol_slashes_and_rest($url, $array = [ 'protocol', 'slashes', 'rest' ])`**
— Explode a URL in an array of ( 'protocol' , 'slashes if any', 'rest of the URL' ) Some hosts trip up when a query string contains 'http://' - see http://git.io/j1FlJg The idea is that instead of passing the whole URL to a bookmarklet, eg index.php?u=http://blah.com, we pass it by pieces to fool the server, eg index.php?proto=http:&slashes=//&rest=blah.com Known limitation: this won't work if the rest of the URL itself contains 'http://', for example if rest = blah.com/file.php?url=http://foo.com Sample returns: with 'mailto:jsmith@example.com?subject=hey' : array( 'protocol' => 'mailto:', 'slashes' => '', 'rest' => 'jsmith@example.com?subject=hey' ) with 'http://example.com/blah.html' : array( 'protocol' => 'http:', 'slashes' => '//', 'rest' => 'example.com/blah.html' ) @since 1.7 @param string $url URL to be parsed @param array $array Optional, array of key names to be used in returned array @return array|false false if no protocol found, array of ('protocol' , 'slashes', 'rest') otherwise

**`yourls_get_referrer()`**
— Returns the sanitized referrer submitted by the browser. @return string               HTTP Referrer or 'direct' if no referrer was provided

**`yourls_get_relative_url($url, $strict = true)`**
— Get relative URL (eg 'abc' from 'http://sho.rt/abc') Treat indifferently http & https. If a URL isn't relative to the YOURLS install, return it as is or return empty string if $strict is true @since 1.6 @param string $url URL to relativize @param bool $strict if true and if URL isn't relative to YOURLS install, return empty string @return string URL

**`yourls_get_remote_title($url)`**
— Get a remote page title This function returns a string: either the page title as defined in HTML, or the URL if not found The function tries to convert funky characters found in titles to UTF8, from the detected charset. Charset in use is guessed from HTML meta tag, or if not found, from server's 'content-type' response. @param string $url URL @return string Title (sanitized) or the URL if no title found

**`yourls_get_request($yourls_site = '', $uri = '')`**
— Get request in YOURLS base (eg in 'http://sho.rt/yourls/abcd' get 'abdc') With no parameter passed, this function will guess current page and consider it is the requested page. For testing purposes, parameters can be passed. @since 1.5 @param string $yourls_site   Optional, YOURLS installation URL (default to constant YOURLS_SITE) @param string $uri           Optional, page requested (default to $_SERVER['REQUEST_URI'] eg '/yourls/abcd' ) @return string               request relative to YOURLS base (eg 'abdc')

**`yourls_get_stats($filter = 'top', $limit = 10, $start = 0)`**
— Return array of stats. (string)$filter is 'bottom', 'last', 'rand' or 'top'. (int)$limit is the number of links to return @param string $filter  'bottom', 'last', 'rand' or 'top' @param int $limit      Number of links to return @param int $start      Offset to start from @return array          Array of links

**`yourls_get_user_agent()`**
— Returns a sanitized a user agent string. Given what I found on http://www.user-agents.org/ it should be OK. @return string

**`yourls_include_file_sandbox($file)`**
— File include sandbox Attempt to include a PHP file, fail with an error message if the file isn't valid PHP code. This function does not check first if the file exists : depending on use case, you may check first. @since 1.9.2 @param string $file filename (full path) @return string|bool  string if error, true if success

**`yourls_is_API()`**
— Check if we're in API mode. @return bool

**`yourls_is_Ajax()`**
— Check if we're in Ajax mode. @return bool

**`yourls_is_GO()`**
— Check if we're in GO mode (yourls-go.php). @return bool

**`yourls_is_admin()`**
— Check if we're in the admin area. Returns bool. Does not relate with user rights. @return bool

**`yourls_is_allowed_protocol($url, $protocols = [])`**
— Check if a URL protocol is allowed Checks a URL against a list of whitelisted protocols. Protocols must be defined with their complete scheme name, ie 'stuff:' or 'stuff://' (for instance, 'mailto:' is a valid protocol, 'mailto://' isn't, and 'http:' with no double slashed isn't either @since 1.6 @see yourls_get_protocol() @param string $url URL to be check @param array $protocols Optional. Array of protocols, defaults to global $yourls_allowedprotocols @return bool true if protocol allowed, false otherwise

**`yourls_is_infos()`**
— Check if we're displaying stats infos (yourls-infos.php). Returns bool @return bool

**`yourls_is_installed()`**
— Check if YOURLS is installed Checks property $ydb->installed that is created by yourls_get_all_options() See inline comment for updating from 1.3 or prior. @return bool

**`yourls_is_installing()`**
— Check if YOURLS is installing @since 1.6 @return bool

**`yourls_is_mobile_device()`**
— Quick UA check for mobile devices. @return bool

**`yourls_is_private()`**
— Determine if the current page is private @return bool

**`yourls_is_ssl()`**
— Check if SSL is used. Stolen from WP. @return bool

**`yourls_is_upgrading()`**
— Check if YOURLS is upgrading @since 1.6 @return bool

**`yourls_is_valid_charset($charset)`**
— Is supported charset encoding for conversion. @return bool

**`yourls_is_windows()`**
— Check if the server seems to be running on Windows. Not exactly sure how reliable this is. @return bool

**`yourls_log_redirect($keyword)`**
— Log a redirect (for stats) This function does not check for the existence of a valid keyword, in order to save a query. Make sure the keyword exists before calling it. @since 1.4 @param string $keyword short URL keyword @return mixed Result of the INSERT query (1 on success)

**`yourls_make_regexp_pattern($string)`**
— Make an optimized regexp pattern from a string of characters @param string $string @return string

**`yourls_needs_ssl()`**
— Check if SSL is required. @return bool

**`yourls_no_cache_headers()`**
— Send headers to explicitly tell browser not to cache content or redirection @since 1.7.10 @return void

**`yourls_no_frame_header()`**
— Send header to prevent display within a frame from another site (avoid clickjacking) This header makes it impossible for an external site to display YOURLS admin within a frame, which allows for clickjacking. See https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options This said, the whole function is shuntable : legit uses of iframes should be still possible. @since 1.8.1 @return void|mixed

**`yourls_redirect($location, $code = 301)`**
— Redirect to another page YOURLS redirection, either to internal or external URLs. If headers have not been sent, redirection is achieved with PHP's header(). If headers have been sent already and we're not in a command line client, redirection occurs with Javascript. Note: yourls_redirect() does not exit automatically, and should almost always be followed by a call to exit() to prevent the script from continuing. @since 1.4 @param string $location      URL to redirect to @param int    $code          HTTP status code to send @return int                  1 for header redirection, 2 for js redirection, 3 otherwise (CLI)

**`yourls_redirect_javascript($location, $dontwait = true)`**
— Redirect to another page using Javascript. Set optional (bool)$dontwait to false to force manual redirection (make sure a message has been read by user) @param string $location @param bool   $dontwait @return void

**`yourls_redirect_shorturl($url, $keyword)`**
— Redirect to an existing short URL Redirect client to an existing short URL (no check performed) and execute misc tasks: update clicks for short URL, update logs, and send an X-Robots-Tag header to control indexing of a page. @since  1.7.3 @param  string $url @param  string $keyword @return void

**`yourls_rnd_string($length = 5, $type = 0, $charlist = '')`**
— Generate random string of (int)$length length and type $type (see function for details) @param int    $length @param int    $type @param string $charlist @return mixed|string

**`yourls_robots_tag_header()`**
— Send an X-Robots-Tag header. See #3486 @since 1.9.2 @return void

**`yourls_set_installed($bool)`**
— Set installed state @since  1.7.3 @param bool $bool whether YOURLS is installed or not @return void

**`yourls_set_url_scheme($url, $scheme = '')`**
— Set URL scheme (HTTP or HTTPS) to a URL @since 1.7.1 @param string $url    URL @param string $scheme scheme, either 'http' or 'https' @return string URL with chosen scheme

**`yourls_status_header($code = 200)`**
— Set HTTP status header @since 1.4 @param int $code  status header code @return bool      whether header was sent

**`yourls_tell_if_new_version()`**
— Tell if there is a new YOURLS version This function checks, if needed, if there's a new version of YOURLS and, if applicable, displays an update notice. @since 1.7.3 @return void

**`yourls_update_clicks($keyword, $clicks = false)`**
— Update click count on a short URL. Return 0/1 for error/success. @param string $keyword @param false|int $clicks @return int 0 or 1 for error/success

**`yourls_update_next_decimal($int = 0)`**
— Update id for next link with no custom keyword Note: this function relies upon yourls_update_option(), which will return either true or false depending upon if there has been an actual MySQL query updating the DB. In other words, this function may return false yet this would not mean it has functionally failed In other words I'm not sure if we really need this function to return something :face_with_eyes_looking_up: See issue 2621 for more on this. @since 1.0 @param integer $int id for next link @return bool        true or false depending on if there has been an actual MySQL query. See note above.

**`yourls_upgrade_is_needed()`**
— Check if an upgrade is needed @return bool

**`yourls_xml_encode($array)`**
— Return XML output. @param array $array @return string

## Filters (`yourls_apply_filters`)

| Hook | Args | File |
|------|------|------|

## Actions (`yourls_do_action`)

| Hook | Args | File |
|------|------|------|
| `activated_` | `$plugin` | `includes\functions-plugins.php:667` |
| `activated_plugin` | `$plugin` | `includes\functions-plugins.php:666` |
| `add_new_link_already_stored` | `$url, $keyword, $title` | `includes\functions-shorturls.php:79` |
| `add_new_link_create_keyword` | `$url, $keyword, $title` | `includes\functions-shorturls.php:120` |
| `add_new_link_custom_keyword` | `$url, $keyword, $title` | `includes\functions-shorturls.php:103` |
| `add_option` | `$name, $_value` | `includes\Database\Options.php:220` |
| `admin_headers` | `$context, $title` | `includes\functions-html.php:69` |
| `admin_init` | `—` | `includes\Config\Init.php:122` |
| `admin_menu` | `—` | `includes\functions-html.php:889` |
| `admin_notice` | `—` | `includes\functions-html.php:892` |
| `admin_notices` | `—` | `includes\functions-html.php:891` |
| `admin_page_after_table` | `—` | `admin\index.php:327` |
| `admin_page_before_content` | `—` | `admin\index.php:244` |
| `admin_page_before_form` | `—` | `admin\index.php:263` |
| `admin_page_before_table` | `—` | `admin\index.php:277` |
| `api` | `$action` | `yourls-api.php:16` |
| `api_output` | `$mode, $output, $send_headers, $echo` | `includes\functions-api.php:164` |
| `auth_successful` | `—` | `includes\auth.php:28` |
| `bookmarklet` | `—` | `admin\index.php:111` |
| `check_ip_flood` | `$ip` | `includes\functions.php:679` |
| `content_type_header` | `$type` | `includes\functions.php:377` |
| `deactivated_` | `$plugin` | `includes\functions-plugins.php:712` |
| `deactivated_plugin` | `$plugin` | `includes\functions-plugins.php:711` |
| `debug_log` | `$msg` | `includes\functions-debug.php:17` |
| `delete_link` | `$keyword, $delete` | `includes\functions-shorturls.php:261` |
| `delete_option` | `$name` | `includes\Database\Options.php:249` |
| `deprecated_function` | `$function, $replacement, $version` | `includes\functions.php:1259` |
| `get_all_options` | `$options` | `includes\Database\Options.php:78` |
| `get_db_action` | `$context` | `includes\class-mysql.php:115` |
| `get_keyword_not_cached` | `$keyword` | `includes\functions-shorturls.php:515` |
| `html_addnew` | `—` | `includes\functions-html.php:204` |
| `html_footer` | `—` | `includes\functions-html.php:170` |
| `html_head` | `$context` | `includes\functions-html.php:132` |
| `html_head_meta` | `$context` | `includes\functions-html.php:93` |
| `html_logo` | `—` | `includes\functions-html.php:18` |
| `html_tfooter` | `—` | `includes\functions-html.php:389` |
| `infos_keyword_not_found` | `—` | `yourls-infos.php:28` |
| `infos_no_keyword` | `—` | `yourls-infos.php:9` |
| `init` | `—` | `includes\Config\Init.php:77` |
| `insert_link` | `—` | `includes\functions-shorturls.php:297` |
| `ip_flood` | `$ip, $now, $then` | `includes\functions.php:688` |
| `load-` | `$plugin_page` | `includes\functions-plugins.php:794` |
| `load_template_go` | `$keyword` | `yourls-loader.php:50` |
| `load_template_infos` | `$keyword` | `yourls-loader.php:58` |
| `load_template_redirect_admin` | `$url` | `yourls-loader.php:37` |
| `load_textdomain` | `$domain, $mofile` | `includes\functions-l10n.php:455` |
| `loader_failed` | `$request` | `yourls-loader.php:67` |
| `login` | `—` | `includes\functions-auth.php:97` |
| `login_failed` | `—` | `includes\functions-auth.php:116` |
| `login_form_bottom` | `—` | `includes\functions-html.php:800` |
| `login_form_end` | `—` | `includes\functions-html.php:807` |
| `login_form_top` | `—` | `includes\functions-html.php:789` |
| `logout` | `—` | `includes\functions-auth.php:40` |
| `new_core_version_notice` | `$latest` | `includes\functions-html.php:1015` |
| `plugins_loaded` | `—` | `includes\Config\Init.php:105` |
| `post_add_new_link` | `$url, $keyword, $title, $return` | `includes\functions-shorturls.php:163` |
| `post_page` | `$page` | `includes\functions-html.php:948` |
| `post_redirect_javascript` | `$location` | `includes\functions.php:434` |
| `post_yourls_info_location` | `$keyword` | `yourls-infos.php:475` |
| `post_yourls_info_sources` | `$keyword` | `yourls-infos.php:542` |
| `post_yourls_info_stats` | `$keyword` | `yourls-infos.php:438` |
| `pre_add_new_link` | `$url, $keyword, $title` | `includes\functions-shorturls.php:75` |
| `pre_api_output` | `$mode, $output, $send_headers, $echo` | `includes\functions-api.php:107` |
| `pre_check_ip_flood` | `$ip` | `includes\functions.php:651` |
| `pre_edit_link` | `$url, $keyword, $newkeyword, $new_url_already_there, $keyword_is_ok` | `includes\functions-shorturls.php:375` |
| `pre_get_keyword` | `$keyword, $use_cache` | `includes\functions-shorturls.php:509` |
| `pre_get_request` | `$yourls_site, $uri` | `includes\functions.php:1033` |
| `pre_html_head` | `$context, $title` | `includes\functions-html.php:30` |
| `pre_html_logo` | `—` | `includes\functions-html.php:9` |
| `pre_load_template` | `$request` | `yourls-loader.php:25` |
| `pre_login` | `—` | `includes\functions-auth.php:47` |
| `pre_login_cookie` | `—` | `includes\functions-auth.php:88` |
| `pre_login_signature` | `—` | `includes\functions-auth.php:70` |
| `pre_login_signature_timestamp` | `—` | `includes\functions-auth.php:58` |
| `pre_login_username_password` | `—` | `includes\functions-auth.php:79` |
| `pre_page` | `$page` | `includes\functions-html.php:943` |
| `pre_redirect` | `$location, $code` | `includes\Http\Redirection.php:17` |
| `pre_redirect_bookmarklet` | `$url` | `yourls-loader.php:38` |
| `pre_redirect_javascript` | `$location, $dontwait` | `includes\functions.php:420` |
| `pre_setcookie` | `$user, $time, $path, $domain, $secure, $httponly` | `includes\functions-auth.php:472` |
| `pre_share_redirect` | `—` | `admin\index.php:154` |
| `pre_stats_countries_map` | `—` | `includes\functions-infos.php:12` |
| `pre_stats_line` | `—` | `includes\functions-infos.php:187` |
| `pre_stats_pie` | `—` | `includes\functions-infos.php:47` |
| `pre_yourls_die` | `$message, $title, $header_code` | `includes\functions-html.php:515` |
| `pre_yourls_info_location` | `$keyword` | `yourls-infos.php:449` |
| `pre_yourls_info_sources` | `$keyword` | `yourls-infos.php:486` |
| `pre_yourls_info_stats` | `$keyword` | `yourls-infos.php:262` |
| `pre_yourls_infos` | `$keyword` | `yourls-infos.php:33` |
| `redirect_keyword_not_found` | `$keyword` | `yourls-go.php:26` |
| `redirect_no_keyword` | `—` | `yourls-go.php:7` |
| `redirect_shorturl` | `$url, $keyword` | `includes\functions.php:295` |
| `require_auth` | `—` | `includes\functions-auth.php:14` |
| `require_no_auth` | `—` | `includes\functions-auth.php:17` |
| `set_DB_driver` | `—` | `includes\class-mysql.php:27` |
| `setcookie_failed` | `$user` | `includes\functions-auth.php:478` |
| `share_links` | `$longurl, $shorturl, $title, $text` | `includes\functions-html.php:492` |
| `share_redirect_` | `$_GET, $return` | `admin\index.php:191` |
| `shareboxes_after` | `$longurl, $shorturl, $title, $text` | `includes\functions-html.php:498` |
| `shareboxes_before` | `$longurl, $shorturl, $title, $text` | `includes\functions-html.php:467` |
| `shareboxes_middle` | `$longurl, $shorturl, $title, $text` | `includes\functions-html.php:480` |
| `shutdown` | `—` | `includes\functions-plugins.php:852` |
| `social_bookmarklet_buttons_after` | `—` | `admin\tools.php:281` |
| `status_header` | `$code` | `includes\functions.php:394` |
| `unload_textdomain` | `$domain` | `includes\functions-l10n.php:492` |
| `update_clicks` | `$keyword, $result, $clicks` | `includes\functions.php:134` |
| `update_next_decimal` | `$int, $update` | `includes\functions.php:69` |
| `update_option` | `$name, $oldvalue, $newvalue` | `includes\Database\Options.php:175` |
| `yourls_ajax_` | `$action` | `admin\admin-ajax.php:44` |
| `yourls_die` | `—` | `includes\functions-html.php:526` |

## Constants

| Name | Value | Description | File |
|------|-------|-------------|------|
| `YOURLS_ABSPATH` | `$this->root` |  | `includes\Config\Config.php:122` |
| `YOURLS_ADMIN` | `true` |  | `admin\admin-ajax.php:2` |
| `YOURLS_ADMIN` | `true` |  | `admin\index.php:2` |
| `YOURLS_ADMIN` | `true` |  | `admin\install.php:2` |
| `YOURLS_ADMIN` | `true` |  | `admin\plugins.php:2` |
| `YOURLS_ADMIN` | `true` |  | `admin\tools.php:2` |
| `YOURLS_ADMIN` | `true` |  | `admin\upgrade.php:2` |
| `YOURLS_ADMIN_SSL` | `false` |  | `includes\Config\Config.php:202` |
| `YOURLS_AJAX` | `true` |  | `admin\admin-ajax.php:3` |
| `YOURLS_API` | `true` |  | `yourls-api.php:10` |
| `YOURLS_ASSETDIR` | `YOURLS_ABSPATH.'/assets'` |  | `includes\Config\Config.php:138` |
| `YOURLS_ASSETURL` | `trim(YOURLS_SITE, '/'` |  | `includes\Config\Config.php:142` |
| `YOURLS_CONFIGFILE` | `$config->find_config(` |  | `includes\load-yourls.php:18` |
| `YOURLS_COOKIE_LIFE` | `60*60*24*7` |  | `includes\Config\Config.php:190` |
| `YOURLS_DB_TABLE_LOG` | `YOURLS_DB_PREFIX.'log'` |  | `includes\Config\Config.php:178` |
| `YOURLS_DB_TABLE_OPTIONS` | `YOURLS_DB_PREFIX.'options'` |  | `includes\Config\Config.php:174` |
| `YOURLS_DB_TABLE_URL` | `YOURLS_DB_PREFIX.'url'` |  | `includes\Config\Config.php:170` |
| `YOURLS_DB_VERSION` | `'507'` | YOURLS DB version. Increments when changes are made to the DB schema, to trigger… | `includes\version.php:14` |
| `YOURLS_DEBUG` | `false` |  | `includes\Config\Config.php:206` |
| `YOURLS_DEBUG` | `false` |  | `test.php:7` |
| `YOURLS_FAST_INIT` | `true` |  | `test.php:8` |
| `YOURLS_FLOOD_DELAY_SECONDS` | `15` |  | `includes\Config\Config.php:182` |
| `YOURLS_FLOOD_IP_WHITELIST` | `''` |  | `includes\Config\Config.php:186` |
| `YOURLS_GO` | `true` |  | `yourls-go.php:2` |
| `YOURLS_INC` | `YOURLS_ABSPATH.'/includes'` |  | `includes\Config\Config.php:126` |
| `YOURLS_INFOS` | `true` |  | `yourls-infos.php:3` |
| `YOURLS_INSTALLING` | `true` |  | `admin\install.php:3` |
| `YOURLS_INSTALLING` | `false` |  | `test.php:5` |
| `YOURLS_LANG` | `'fr_FR'` |  | `test.php:10` |
| `YOURLS_LANG_DIR` | `YOURLS_USERDIR.'/languages'` |  | `includes\Config\Config.php:146` |
| `YOURLS_NONCE_LIFE` | `43200` |  | `includes\Config\Config.php:194` |
| `YOURLS_NOSTATS` | `false` |  | `includes\Config\Config.php:198` |
| `YOURLS_NO_VERSION_CHECK` | `true` |  | `test.php:6` |
| `YOURLS_PAGEDIR` | `YOURLS_USERDIR.'/pages'` |  | `includes\Config\Config.php:166` |
| `YOURLS_PLUGINDIR` | `YOURLS_USERDIR.'/plugins'` |  | `includes\Config\Config.php:150` |
| `YOURLS_PLUGINURL` | `YOURLS_USERURL.'/plugins'` |  | `includes\Config\Config.php:154` |
| `YOURLS_PRIVATE` | `false` |  | `test.php:9` |
| `YOURLS_THEMEDIR` | `YOURLS_USERDIR.'/themes'` |  | `includes\Config\Config.php:158` |
| `YOURLS_THEMEURL` | `YOURLS_USERURL.'/themes'` |  | `includes\Config\Config.php:162` |
| `YOURLS_UNINSTALL_PLUGIN` | `true` |  | `includes\functions-plugins.php:698` |
| `YOURLS_UPGRADING` | `true` |  | `admin\upgrade.php:3` |
| `YOURLS_USER` | `$user` |  | `includes\functions-auth.php:521` |
| `YOURLS_USERDIR` | `YOURLS_ABSPATH.'/user'` |  | `includes\Config\Config.php:130` |
| `YOURLS_USERURL` | `trim(YOURLS_SITE, '/'` |  | `includes\Config\Config.php:134` |
| `YOURLS_VERSION` | `'1.10.4-dev'` | YOURLS version Must be one of the following : MAJOR.MINOR (eg 1.8) MAJOR.MINOR.P… | `includes\version.php:2` |
