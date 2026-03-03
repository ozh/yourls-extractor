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
— Connect to DB @param string $context Optional context. Default: ''. @return \YOURLS\Database\YDB

**`yourls_get_db($context = '')`**
— Helper function: return instance of the DB @param string $context Optional context. Default: ''. @return \YOURLS\Database\YDB

**`yourls_set_db($db)`**
— Helper function : set instance of DB, or unset it @param  mixed $db    Either a \YOURLS\Database\YDB instance, or anything. If null, the function will unset $ydb @return void

### `includes\functions-api.php`

**`yourls_api_action_db_stats()`**
— API function wrapper: Just the global counts of shorturls and clicks @return array Result of API call

**`yourls_api_action_expand()`**
— API function wrapper: Expand a short link @return array Result of API call

**`yourls_api_action_shorturl()`**
— API function wrapper: Shorten a URL @return array Result of API call

**`yourls_api_action_stats()`**
— API function wrapper: Stats about links (XX top, bottom, last, rand) @return array Result of API call

**`yourls_api_action_url_stats()`**
— API function wrapper: Stats for a shorturl @return array Result of API call

**`yourls_api_action_version()`**
— API function wrapper: return version numbers @return array Result of API call

**`yourls_api_db_stats()`**
— Return array for counts of shorturls and clicks @return array

**`yourls_api_expand($shorturl)`**
— Expand short url to long url @param string $shorturl  Short URL to expand @return array

**`yourls_api_output($mode, $output, $send_headers = true, $echo = true)`**
— Output and return API result @param  string $mode          Expected output mode ('json', 'jsonp', 'xml', 'simple') @param  array  $output        Array of things to output @param  bool   $send_headers  Optional, default true: Whether a headers (status, content type) should be sent or not @param  bool   $echo          Optional, default true: Whether the output should be outputted or just returned @return string                API output, as an XML / JSON / JSONP / raw text string

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
— Check auth against signature. Sets user if applicable, returns bool @return bool False if signature missing or invalid, true if valid

**`yourls_check_signature_timestamp()`**
— Check auth against signature and timestamp. Sets user if applicable, returns bool @return bool False if signature or timestamp missing or invalid, true if valid

**`yourls_check_timestamp($time)`**
— Check if timestamp is not too old @param int $time  Timestamp to check @return bool      True if timestamp is valid

**`yourls_check_username_password()`**
— Check auth against list of login=>pwd. Sets user if applicable, returns bool @return bool  true if login/pwd pair is valid (and sets user if applicable), false otherwise

**`yourls_cookie_name()`**
— Get YOURLS cookie name @return string  unique cookie name for a given YOURLS site

**`yourls_cookie_value($user)`**
— Get auth cookie value @param string $user     user name @return string          cookie value

**`yourls_create_nonce($action, $user = false)`**
— Create a time limited, action limited and user limited token @param string $action      Action to create nonce for @param false|string $user  Optional user string, false for current user @return string             Nonce token

**`yourls_get_cookie_life()`**
— Get YOURLS_COOKIE_LIFE value (ie the life span of an auth cookie in seconds) @return integer     cookie life span, in seconds

**`yourls_get_nonce_life()`**
— Get YOURLS_NONCE_LIFE value (ie life span of a nonce in seconds) @return integer     nonce life span, in seconds

**`yourls_has_cleartext_passwords()`**
— Check to see if any passwords are stored as cleartext. @return bool true if any passwords are cleartext

**`yourls_has_md5_password($user)`**
— Check if a user has a md5 hashed password @param string $user user login @return bool true if password hashed, false otherwise

**`yourls_has_phpass_password($user)`**
— Check if a user's password is hashed with password_hash @param string $user user login @return bool true if password hashed with password_hash, otherwise false

**`yourls_hash_passwords_now($config_file)`**
— Overwrite plaintext passwords in config file with hashed versions. @param string $config_file Full path to file @return true|string  if overwrite was successful, an error message otherwise

**`yourls_hmac_algo()`**
— Return an available hash_hmac() algorithm @return string  hash_hmac() algorithm

**`yourls_is_user_from_env()`**
— Check if YOURLS_USER comes from environment variables @return bool  true if YOURLS_USER and YOURLS_PASSWORD are defined as environment variables

**`yourls_is_valid_user()`**
— Check for valid user via login form or stored cookie. Returns true or an error message @return bool|string|mixed true if valid user, error message otherwise. Can also call yourls_die() or redirect to login page. Oh my.

**`yourls_maybe_hash_passwords()`**
— Check if we should hash passwords in the config file @return bool

**`yourls_maybe_require_auth()`**
— Show login form if required @return void

**`yourls_nonce_field($action, $name = 'nonce', $user = false, $echo = true)`**
— Echoes or returns a nonce field for inclusion into a form @param string $action      Action to create nonce for @param string $name        Optional name of nonce field -- defaults to 'nonce' @param false|string $user  Optional user string, false if unspecified @param bool $echo          True to echo, false to return nonce field @return string             Nonce field

**`yourls_nonce_url($action, $url = false, $name = 'nonce', $user = false)`**
— Add a nonce to a URL. If URL omitted, adds nonce to current URL @param string $action      Action to create nonce for @param string $url         Optional URL to add nonce to -- defaults to current URL @param string $name        Optional name of nonce field -- defaults to 'nonce' @param false|string $user  Optional user string, false if unspecified @return string             URL with nonce added

**`yourls_phpass_check($password, $hash)`**
— Verify that a password matches a hash @param string $password clear (eg submitted in a form) password @param string $hash hash @return bool true if the hash matches the password, false otherwise

**`yourls_phpass_hash($password)`**
— Create a password hash @param string $password password to hash @return string hashed password

**`yourls_salt($string)`**
— Return hashed string @param string $string   string to salt @return string          hashed string

**`yourls_set_user($user)`**
— Set user name @param string $user  Username @return void

**`yourls_setcookie($name, $value, $expire, $path, $domain, $secure, $httponly)`**
— Replacement for PHP's setcookie(), with support for SameSite cookie attribute @param  string  $name       cookie name @param  string  $value      cookie value @param  int     $expire     time the cookie expires as a Unix timestamp (number of seconds since the epoch) @param  string  $path       path on the server in which the cookie will be available on @param  string  $domain     (sub)domain that the cookie is available to @param  bool    $secure     if cookie should only be transmitted over a secure HTTPS connection @param  bool    $httponly   if cookie will be made accessible only through the HTTP protocol @return bool                setcookie() result : false if output sent before, true otherwise. This does not indicate whether the user accepted the cookie.

**`yourls_skip_password_hashing()`**
— Check if user setting for skipping password hashing is set @return bool

**`yourls_store_cookie($user = '')`**
— Store new cookie. No $user will delete the cookie. @param string $user  User login, or empty string to delete cookie @return void

**`yourls_tick()`**
— Return a time-dependent string for nonce creation @return float

**`yourls_verify_nonce($action, $nonce = false, $user = false, $return = '')`**
— Check validity of a nonce (ie time span, user and action match). @param string $action @param false|string $nonce  Optional, string: nonce value, or false to use $_REQUEST['nonce'] @param false|string $user   Optional, string user, false for current user @param string $return       Optional, string: message to die with if nonce is invalid @return bool|void           True if valid, dies otherwise

### `includes\functions-debug.php`

**`yourls_debug_log($msg)`**
— Add a message to the debug log @param string $msg Message to add to the debug log @return string The message itself

**`yourls_debug_mode($bool)`**
— Debug mode set @param bool $bool Debug on or off @return void

**`yourls_get_debug_log()`**
— Get the debug log @return array

**`yourls_get_debug_mode()`**
— Return YOURLS debug mode @return bool

**`yourls_get_num_queries()`**
— Get number of SQL queries performed @return int

### `includes\functions-formatting.php`

**`yourls_backslashit($string)`**
— Adds backslashes before letters and before a number at the start of a string. Stolen from WP. @param string $string Value to which backslashes will be added. @return string String with backslashes inserted.

**`yourls_check_invalid_utf8($string, $strip = false)`**
— Checks for invalid UTF8 in a string. Stolen from WP @param string $string The text which is to be checked. @param boolean $strip Optional. Whether to attempt to strip out invalid UTF8. Default is false. @return string The checked text.

**`yourls_deep_replace($search, $subject)`**
— Perform a replacement while a string is found, eg $subject = '%0%0%0DDD', $search ='%0D' -> $result ='' @param string|array $search   Needle, or array of needles. @param string       $subject  Haystack. @return string                The string with the replaced values.

**`yourls_esc_attr($text)`**
— Escaping for HTML attributes.  Stolen from WP @param string $text @return string

**`yourls_esc_html($text)`**
— Escaping for HTML blocks. Stolen from WP @param string $text @return string

**`yourls_esc_js($text)`**
— Escape single quotes, htmlspecialchar " < > &, and fix line endings. Stolen from WP. @param string $text The text to be escaped. @return string Escaped text.

**`yourls_esc_textarea($text)`**
— Escaping for textarea values. Stolen from WP. @param string $text @return string

**`yourls_esc_url($url, $context = 'display', $protocols = array()`**
— Checks and cleans a URL before printing it. Stolen from WP. @param string $url The URL to be cleaned. @param string $context 'display' or something else. Use yourls_sanitize_url() for database or redirection usage. @param array $protocols Optional. Array of allowed protocols, defaults to global $yourls_allowedprotocols @return string The cleaned $url

**`yourls_get_date_format($format)`**
— Return a date() format for date (no time), filtered @param  string $format  Date format string @return string          Date format string

**`yourls_get_datetime_format($format)`**
— Return a date() format for a full date + time, filtered @param  string $format  Date format string @return string          Date format string

**`yourls_get_time_format($format)`**
— Return a date() format for a time (no date), filtered @param  string $format  Date format string @return string          Date format string

**`yourls_get_time_offset()`**
— Get time offset, as defined in config, filtered @return int       Time offset

**`yourls_get_timestamp($timestamp)`**
— Return a timestamp, plus or minus the time offset if defined @param  string|int $timestamp  a timestamp @return int                    a timestamp, plus or minus offset if defined

**`yourls_int2string($num, $chars = null)`**
— Convert an integer (1337) to a string (3jk). @param int $num       Number to convert @param string $chars  Characters to use for conversion @return string        Converted number

**`yourls_is_rawurlencoded($string)`**
— Check if a string seems to be urlencoded @param string $string @return bool

**`yourls_make_bookmarklet($code)`**
— Converts readable Javascript code into a valid bookmarklet link @param  string $code  Javascript code @return string        Bookmarklet link

**`yourls_normalize_uri($url)`**
— Normalize a URI : lowercase scheme and domain, convert IDN to UTF8 @param string $url URL @return string URL with lowercase scheme and protocol

**`yourls_rawurldecode_while_encoded($string)`**
— rawurldecode a string till it's not encoded anymore @param string $string @return string

**`yourls_remove_backslashes_before_query_fragment(string $url)`**
— Remove backslashes before query string or fragment identifier @param string $url URL @return string URL without backslashes before query string or fragment identifier

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
— Make sure a link keyword (ie "1fv" as in "http://sho.rt/1fv") is acceptable @param  string $keyword                        short URL keyword @param  bool   $restrict_to_shorturl_charset   Optional, default false. True if we want the keyword to comply to short URL charset @return string                                 The sanitized keyword

**`yourls_sanitize_title($unsafe_title, $fallback = '')`**
— Sanitize a page title. No HTML per W3C http://www.w3.org/TR/html401/struct/global.html#h-7.4.2 @param string $unsafe_title  Title, potentially unsafe @param string $fallback      Optional fallback if after sanitization nothing remains @return string               Safe title

**`yourls_sanitize_url($unsafe_url, $protocols = array()`**
— A few sanity checks on the URL. Used for redirection or DB. For redirection when you don't trust the URL ($_SERVER variable, query string), see yourls_sanitize_url_safe() For display purpose, see yourls_esc_url() @param string $unsafe_url unsafe URL @param array $protocols Optional allowed protocols, default to global $yourls_allowedprotocols @return string Safe URL

**`yourls_sanitize_url_safe($unsafe_url, $protocols = array()`**
— A few sanity checks on the URL, including CRLF. Used for redirection when URL to be sanitized is critical and cannot be trusted. @param string $unsafe_url unsafe URL @param array $protocols Optional allowed protocols, default to global $yourls_allowedprotocols @return string Safe URL

**`yourls_sanitize_version($version)`**
— Sanitize a version number (1.4.1-whatever-RC1 -> 1.4.1) @param  string $version  Version number @return string           Sanitized version number

**`yourls_seems_utf8($str)`**
— Check if a string seems to be UTF-8. Stolen from WP. @param string $str  String to check @return bool        Whether string seems valid UTF-8

**`yourls_specialchars($string, $quote_style = ENT_NOQUOTES, $double_encode = false)`**
— Converts a number of special characters into their HTML entities. Stolen from WP. @param string $string The text which is to be encoded. @param mixed $quote_style Optional. Converts double quotes if set to ENT_COMPAT, both single and double if set to ENT_QUOTES or none if set to ENT_NOQUOTES. Also compatible with old values; converting single quotes if set to 'single', double if set to 'double' or both if otherwise set. Default is ENT_NOQUOTES. @param boolean $double_encode Optional. Whether to encode existing html entities. Default is false. @return string The encoded text with HTML entities.

**`yourls_specialchars_decode($string, $quote_style = ENT_NOQUOTES)`**
— Converts a number of HTML entities into their special characters. Stolen from WP. @param string $string The text which is to be decoded. @param mixed $quote_style Optional. Converts double quotes if set to ENT_COMPAT, both single and double if set to ENT_QUOTES or none if set to ENT_NOQUOTES. Also compatible with old _wp_specialchars() values; converting single quotes if set to 'single', double if set to 'double' or both if otherwise set. Default is ENT_NOQUOTES. @return string The decoded text without HTML entities.

**`yourls_string2int($string, $chars = null)`**
— Convert a string (3jk) to an integer (1337) @param string $string  String to convert @param string $chars   Characters to use for conversion @return string         Number (as a string)

**`yourls_supports_pcre_u()`**
— Check for PCRE /u modifier support. Stolen from WP. @return bool whether there's /u support or not

**`yourls_trim_long_string($string, $length = 60, $append = '[...]')`**
— Return trimmed string, optionally append '[...]' if string is too long @param string $string  String to trim @param int $length     Maximum length of string @param string $append  String to append if trimmed @return string         Trimmed string

**`yourls_unique_element_id($prefix = 'yid', $initial_val = 1)`**
— Return a unique string to be used as a valid HTML id @param  string $prefix      Optional prefix @param  int    $initial_val The initial counter value (defaults to one) @return string              The unique string

**`yourls_validate_jsonp_callback($callback)`**
— Validate a JSONP callback name @param string $callback Raw callback value @return string|false Original callback if valid, false otherwise

### `includes\functions-geo.php`

**`yourls_geo_countrycode_to_countryname($code)`**
— Converts a 2 letter country code to long name (ie AU -> Australia) @param string $code 2 letter country code, eg 'FR' @return string Country long name (eg 'France') or an empty string if not found

**`yourls_geo_get_flag($code)`**
— Return flag URL from 2 letter country code @param string $code @return string

**`yourls_geo_ip_to_countrycode($ip = '', $default = '')`**
— Converts an IP to a 2 letter country code, using GeoIP database if available in includes/geo @param string $ip      IP or, if empty string, will be current user IP @param string $default Default string to return if IP doesn't resolve to a country (malformed, private IP...) @return string 2 letter country code (eg 'US') or $default

### `includes\functions-html.php`

**`yourls_add_notice($message, $style = 'notice')`**
— Wrapper function to display admin notices @param string $message Message to display @param string $style    Message style (default: 'notice') @return void

**`yourls_bookmarklet_link($href, $anchor, $echo = true)`**
— Display or return HTML for a bookmarklet link @param string $href    bookmarklet link (presumably minified code with "javascript:" scheme) @param string $anchor  link anchor @param bool   $echo    true to display, false to return the HTML @return string         the HTML for a bookmarklet link

**`yourls_delete_link_modal()`**
— Display hidden modal for link delete confirmation @param void @return void

**`yourls_die($message = '', $title = '', $header_code = 200)`**
— Die die die @param string $message @param string $title @param int $header_code @return void

**`yourls_get_html_context()`**
— Get HTML context (stats, index, infos, ...) @return string

**`yourls_html_addnew($url = '', $keyword = '')`**
— Display "Add new URL" box @param string $url URL to prefill the input with @param string $keyword Keyword to prefill the input with @return void

**`yourls_html_favicon()`**
— Print HTML link for favicon @return mixed|void

**`yourls_html_footer($can_query = true)`**
— Display HTML footer (including closing body & html tags) @param  bool $can_query  If set to false, will not try to send another query to DB server @return void

**`yourls_html_head($context = 'index', $title = '')`**
— Display HTML head and `<body>` tag @param string $context Context of the page (stats, index, infos, ...) @param string $title HTML title of the page @return void

**`yourls_html_language_attributes()`**
— Display the language attributes for the HTML tag. @return void

**`yourls_html_link($href, $anchor = '', $element = '')`**
— Echo HTML tag for a link @param string $href     URL to link to @param string $anchor   Anchor text @param string $element  Element id @return void

**`yourls_html_logo()`**
— Display `<h1>` header and logo @return void

**`yourls_html_menu()`**
— Display the admin menu @return void

**`yourls_html_select($name, $options, $selected = '', $display = false, $label = '')`**
— Return or display a select dropdown field @param  string  $name      HTML 'name' (also use as the HTML 'id') @param  array   $options   array of 'value' => 'Text displayed' @param  string  $selected  optional 'value' from the $options array that will be highlighted @param  boolean $display   false (default) to return, true to echo @param  string  $label     ARIA label of the element @return string HTML content of the select element

**`yourls_html_tfooter($params = array()`**
— Display main table's footer @param array $params Array of all required parameters @return void

**`yourls_l10n_calendar_strings()`**
— Output translated strings used by the Javascript calendar @return void

**`yourls_login_screen($error_msg = '')`**
— Display the login screen. Nothing past this point. @param string $error_msg  Optional error message to display @return void

**`yourls_new_core_version_notice($compare_with = null)`**
— Display a notice if there is a newer version of YOURLS available @param string $compare_with Optional, YOURLS version to compare to @return void

**`yourls_notice_box($message, $style = 'notice')`**
— Return a formatted notice @param string $message  Message to display @param string $style    CSS class to use for the notice @return string          HTML of the notice

**`yourls_page($page)`**
— Display a page @param string $page  PHP file to display @return void

**`yourls_set_html_context($context)`**
— Set HTML context (stats, index, infos, ...) @param  string  $context @return void

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
— Check if server can perform HTTPS requests, return bool @return bool whether the server can perform HTTP requests over SSL

**`yourls_check_core_version()`**
— Check api.yourls.org if there's a newer version of YOURLS @return mixed JSON data if api.yourls.org successfully requested, false otherwise

**`yourls_get_version_from_zipball_url($zipurl)`**
— Get version number from Github zipball URL (last part of URL, really) @param string $zipurl eg 'https://api.github.com/repos/YOURLS/YOURLS/zipball/1.2.3' @return string

**`yourls_http_default_options()`**
— Default HTTP requests options for YOURLS @return array Options

**`yourls_http_get($url, $headers = array()`**
— Perform a GET request, return response object or error string message @param string $url     URL to request @param array $headers  HTTP headers to send @param array $data     GET data @param array $options  Options to pass to Requests @return mixed Response object, or error string

**`yourls_http_get_body($url, $headers = array()`**
— Perform a GET request, return body or null if there was an error @param string $url     URL to request @param array $headers  HTTP headers to send @param array $data     GET data @param array $options  Options to pass to Requests @return mixed String (page body) or null if error

**`yourls_http_get_proxy()`**
— Get proxy information @return mixed false if no proxy is defined, or string like '10.0.0.201:3128' or array like ('10.0.0.201:3128', 'username', 'password')

**`yourls_http_get_proxy_bypass_host()`**
— Get list of hosts that should bypass the proxy @return mixed false if no host defined, or string like "example.com, *.mycorp.com"

**`yourls_http_post($url, $headers = array()`**
— Perform a POST request, return response object @param string $url     URL to request @param array $headers  HTTP headers to send @param array $data     POST data @param array $options  Options to pass to Requests @return mixed Response object, or error string

**`yourls_http_post_body($url, $headers = array()`**
— Perform a POST request, return body @param string $url     URL to request @param array $headers  HTTP headers to send @param array $data     POST data @param array $options  Options to pass to Requests @return mixed String (page body) or null if error

**`yourls_http_request($type, $url, $headers, $data, $options)`**
— Perform a HTTP request, return response object @param string $type HTTP request type (GET, POST) @param string $url URL to request @param array $headers Extra headers to send with the request @param array $data Data to send either as a query string for GET requests, or in the body for POST requests @param array $options Options for the request (see /includes/Requests/Requests.php:request()) @return object WpOrg\Requests\Response object

**`yourls_http_user_agent()`**
— Return funky user agent string @return string UA string

**`yourls_is_valid_github_repo_url($url)`**
— Check if URL is from YOURLS/YOURLS repo on github @param string $url  URL to check @return bool

**`yourls_maybe_check_core_version()`**
— Determine if we want to check for a newer YOURLS version (and check if applicable) @return bool true if a check was needed and successfully performed, false otherwise

**`yourls_send_through_proxy($url)`**
— Whether URL should be sent through the proxy server. @param string $url URL to check @return bool true to request through proxy, false to request directly

**`yourls_skip_version_check()`**
— Check if user setting for skipping version check is set @return bool

**`yourls_validate_core_version_response($json)`**
— Make sure response from api.yourls.org is valid @param object $json  JSON object to check @return bool   true if seems legit, false otherwise

**`yourls_validate_core_version_response_keys($json)`**
— Check if object has only expected keys 'latest' and 'zipurl' containing strings @param object $json @return bool

### `includes\functions-infos.php`

**`yourls_array_granularity($array, $grain = 100, $preserve_max = true)`**
— Tweak granularity of array $array: keep only $grain values. @param array $array @param int $grain @param bool $preserve_max @return array

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
— Echoes an image tag of Google Charts line graph from array of values (eg 'number of clicks'). @param array $values  Array of values (eg 'number of clicks') @param string $id     HTML element id @return void

**`yourls_stats_pie($data, $limit = 10, $size = '340x220', $id = null)`**
— Echoes an image tag of Google Charts pie from sorted array of 'data' => 'value' (sort by DESC). Optional $limit = (integer) limit list of X first countries, sorted by most visits @param array $data  Array of 'data' => 'value' @param int $limit   Optional limit list of X first countries @param $size        Optional size of the image @param $id          Optional HTML element ID @return void

### `includes\functions-install.php`

**`yourls_check_PDO()`**
— Check if we have PDO installed, returns bool @return bool

**`yourls_check_database_version()`**
— Check if server has MySQL 5.0+ @return bool

**`yourls_check_php_version()`**
— Check if PHP > 7.2 @return bool

**`yourls_create_htaccess()`**
— Create .htaccess or web.config. Returns boolean @return bool

**`yourls_create_sql_tables()`**
— Create MySQL tables. Return array( 'success' => array of success strings, 'errors' => array of error strings ) @return array  An array like array( 'success' => array of success strings, 'errors' => array of error strings )

**`yourls_get_database_version()`**
— Get DB server version @return string sanitized DB version

**`yourls_initialize_options()`**
— Initializes the option table @return bool

**`yourls_insert_sample_links()`**
— Populates the URL table with a few sample links @return bool

**`yourls_insert_with_markers($filename, $marker, $insertion)`**
— Insert text into a file between BEGIN/END markers, return bool. Stolen from WP @param string $filename @param string $marker @param array  $insertion @return bool True on write success, false on failure.

**`yourls_is_apache()`**
— Check if server is an Apache @return bool

**`yourls_is_iis()`**
— Check if server is running IIS @return bool

**`yourls_maintenance_mode($maintenance = true)`**
— Toggle maintenance mode. Inspired from WP. Returns true for success, false otherwise @param bool $maintenance  True to enable, false to disable @return bool              True on success, false on failure

### `includes\functions-kses.php`

**`_yourls_add_global_attributes($value)`**
— Helper function to add global attributes to a tag in the allowed html list. @param array $value An array of attributes. @return array The array of attributes with global attributes added.

**`_yourls_kses_decode_entities_chr($match)`**
— Regex callback for yourls_kses_decode_entities() @param array $match preg match @return string

**`_yourls_kses_decode_entities_chr_hexdec($match)`**
— Regex callback for yourls_kses_decode_entities() @param array $match preg match @return string

**`yourls_kses_allowed_entities()`**
— Kses global for allowable HTML entities. @return array Allowed entities

**`yourls_kses_allowed_protocols()`**
— Kses global for allowable protocols. @return array Allowed protocols

**`yourls_kses_allowed_tags()`**
— Kses global for default allowable HTML tags. TODO: trim down to necessary only. @return array Allowed tags

**`yourls_kses_allowed_tags_all()`**
— } @return array All tags

**`yourls_kses_array_lc($inarray)`**
— Goes through an array and changes the keys to all lower case. @param array $inarray Unfiltered array @return array Fixed array with all lowercase keys

**`yourls_kses_decode_entities($string)`**
— Convert all entities to their character counterparts. @param string $string Content to change entities @return string Content after decoded entities

**`yourls_kses_init()`**
— Init KSES globals if not already defined (by a plugin) @return void

**`yourls_kses_named_entities($matches)`**
— Callback for yourls_kses_normalize_entities() regular expression. @param array $matches preg_replace_callback() matches array @return string Correctly encoded entity

**`yourls_kses_no_null($string)`**
— Removes any null characters in $string. @param string $string @return string

**`yourls_kses_normalize_entities($string)`**
— Converts and fixes HTML entities. @param string $string Content to normalize entities @return string Content with normalized entities

**`yourls_kses_normalize_entities2($matches)`**
— Callback for yourls_kses_normalize_entities() regular expression. @param array $matches preg_replace_callback() matches array @return string Correctly encoded entity

**`yourls_kses_normalize_entities3($matches)`**
— Callback for yourls_kses_normalize_entities() for regular expression. @param array $matches preg_replace_callback() matches array @return string Correctly encoded entity

**`yourls_valid_unicode($i)`**
— Helper function to determine if a Unicode value is valid. @param int $i Unicode value @return bool True if the value was a valid Unicode number

### `includes\functions-l10n.php`

**`yourls__($text, $domain = 'default')`**
— Retrieves the translation of $text. If there is no translation, or the domain isn't loaded, the original text is returned. @param string $text Text to translate @param string $domain Optional. Domain to retrieve the translated text @return string Translated text

**`yourls_date_i18n($dateformatstring, $timestamp = false)`**
— Return the date in localized format, based on timestamp. @param  string   $dateformatstring   Format to display the date. @param  bool|int $timestamp          Optional, Unix timestamp, default to current timestamp (with offset if applicable) @return string                       The date, translated if locale specifies it.

**`yourls_e($text, $domain = 'default')`**
— Displays the returned translated text from yourls_translate(). @param string $text Text to translate @param string $domain Optional. Domain to retrieve the translated text @return void

**`yourls_esc_attr__($text, $domain = 'default')`**
— Retrieves the translation of $text and escapes it for safe use in an attribute. If there is no translation, or the domain isn't loaded, the original text is returned. @param string $text Text to translate @param string $domain Optional. Domain to retrieve the translated text @return string Translated text

**`yourls_esc_attr_e($text, $domain = 'default')`**
— Displays translated text that has been escaped for safe use in an attribute. @param string $text Text to translate @param string $domain Optional. Domain to retrieve the translated text @return void

**`yourls_esc_attr_x($single, $context, $domain = 'default')`**
— Return translated text, with context, that has been escaped for safe use in an attribute @param string   $single @param string   $context @param string   $domain Optional. Domain to retrieve the translated text @return string

**`yourls_esc_html__($text, $domain = 'default')`**
— Retrieves the translation of $text and escapes it for safe use in HTML output. If there is no translation, or the domain isn't loaded, the original text is returned. @param string $text Text to translate @param string $domain Optional. Domain to retrieve the translated text @return string Translated text

**`yourls_esc_html_e($text, $domain = 'default')`**
— Displays translated text that has been escaped for safe use in HTML output. @param string $text Text to translate @param string $domain Optional. Domain to retrieve the translated text @return void

**`yourls_esc_html_x($single, $context, $domain = 'default')`**
— Return translated text, with context, that has been escaped for safe use in HTML output @param string   $single @param string   $context @param string   $domain Optional. Domain to retrieve the translated text @return string

**`yourls_get_available_languages($dir = null)`**
— Get all available languages (*.mo files) in a given directory. The default directory is YOURLS_LANG_DIR. @param string $dir A directory in which to search for language files. The default directory is YOURLS_LANG_DIR. @return array Array of language codes or an empty array if no languages are present. Language codes are formed by stripping the .mo extension from the language file names.

**`yourls_get_locale()`**
— Gets the current locale. @return string The locale of the YOURLS instance

**`yourls_get_translations_for_domain($domain)`**
— Returns the Translations instance for a domain. If there isn't one, returns empty Translations instance. @param string $domain @return NOOPTranslations An NOOPTranslations translation instance

**`yourls_is_rtl()`**
— Checks if current locale is RTL. Stolen from WP. @return bool Whether locale is RTL.

**`yourls_is_textdomain_loaded($domain)`**
— Whether there are translations for the domain @param string $domain @return bool Whether there are translations

**`yourls_l10n_month_abbrev($month = '')`**
— Return translated month abbrevation (3 letters, eg 'Nov' for 'November') @param mixed $month Empty string, a full textual weekday, eg "November", or an integer (1 = January, .., 12 = December) @return mixed Translated month abbrev (eg "Nov"), or array of all translated abbrev months

**`yourls_l10n_months()`**
— Return array of all translated months @return array Array of all translated months

**`yourls_l10n_weekday_abbrev($weekday = '')`**
— Return translated weekday abbreviation (3 letters, eg 'Fri' for 'Friday') @param mixed $weekday A full textual weekday, eg "Friday", or an integer (0 = Sunday, 1 = Monday, .. 6 = Saturday) @return mixed Translated weekday abbreviation, eg "Ven" (abbrev of "Vendredi") for "Friday" or 5, or array of all weekday abbrev

**`yourls_l10n_weekday_initial($weekday = '')`**
— Return translated weekday initial (1 letter, eg 'F' for 'Friday') @param mixed $weekday A full textual weekday, eg "Friday", an integer (0 = Sunday, 1 = Monday, .. 6 = Saturday) or empty string @return mixed Translated weekday initial, eg "V" (initial of "Vendredi") for "Friday" or 5, or array of all weekday initials

**`yourls_load_custom_textdomain($domain, $path)`**
— Loads a custom translation file (for a plugin, a theme, a public interface...) if locale is defined @param string $domain Unique identifier (the "domain") for retrieving translated strings @param string $path Full path to directory containing MO files. @return mixed|void Returns nothing if locale undefined, otherwise return bool: true on success, false on failure

**`yourls_load_default_textdomain()`**
— Loads default translated strings based on locale. @return bool True on success, false on failure

**`yourls_load_textdomain($domain, $mofile)`**
— Loads a MO file into the domain $domain. @param string $domain Unique identifier for retrieving translated strings @param string $mofile Path to the .mo file @return bool True on success, false on failure

**`yourls_n($single, $plural, $number, $domain = 'default')`**
— Retrieve the plural or single form based on the amount. @param string $single The text that will be used if $number is 1 @param string $plural The text that will be used if $number is not 1 @param int $number The number to compare against to use either $single or $plural @param string $domain Optional. The domain identifier the text should be retrieved in @return string Either $single or $plural translated text

**`yourls_n_noop($singular, $plural, $domain = null)`**
— Register plural strings in POT file, but don't translate them. @param string $singular Single form to be i18ned @param string $plural Plural form to be i18ned @param string $domain Optional. The domain identifier the text will be retrieved in @return array array($singular, $plural)

**`yourls_number_format_i18n($number, $decimals = 0)`**
— Return integer number to format based on the locale. @param int $number The number to convert based on locale. @param int $decimals Precision of the number of decimal places. @return string Converted number in string format.

**`yourls_nx($single, $plural, $number, $context, $domain = 'default')`**
— A hybrid of yourls_n() and yourls_x(). It supports contexts and plurals. @param string $single   The text that will be used if $number is 1 @param string $plural   The text that will be used if $number is not 1 @param int $number      The number to compare against to use either $single or $plural @param string $context  Context information for the translators @param string $domain   Optional. The domain identifier the text should be retrieved in @return string          Either $single or $plural translated text

**`yourls_nx_noop($singular, $plural, $context, $domain = null)`**
— Register plural strings with context in POT file, but don't translate them. @param string $singular Single form to be i18ned @param string $plural   Plural form to be i18ned @param string $context  Context information for the translators @param string $domain   Optional. The domain identifier the text will be retrieved in @return array           array($singular, $plural)

**`yourls_s($pattern)`**
— Return a translated sprintf() string (mix yourls__() and sprintf() in one func) @param mixed ...$pattern Text to translate, then $arg1: optional sprintf tokens, and $arg2: translation domain @return string Translated text

**`yourls_se($pattern)`**
— Echo a translated sprintf() string (mix yourls__() and sprintf() in one func) @param string ...$pattern Text to translate, then optional sprintf tokens, and optional translation domain @return void Translated text

**`yourls_translate($text, $domain = 'default')`**
— Retrieves the translation of $text. If there is no translation, or the domain isn't loaded, the original text is returned. @param string $text Text to translate. @param string $domain Domain to retrieve the translated text. @return string Translated text

**`yourls_translate_nooped_plural($nooped_plural, $count, $domain = 'default')`**
— Translate the result of yourls_n_noop() or yourls_nx_noop() @param array $nooped_plural Array with singular, plural and context keys, usually the result of yourls_n_noop() or yourls_nx_noop() @param int $count Number of objects @param string $domain Optional. The domain identifier the text should be retrieved in. If $nooped_plural contains @return string

**`yourls_translate_user_role($name)`**
— Translates role name. Unused. @param string $name The role name @return string Translated role name

**`yourls_translate_with_context($text, $context, $domain = 'default')`**
— Retrieves the translation of $text with a given $context. If there is no translation, or the domain isn't loaded, the original text is returned. @param string $text Text to translate. @param string $context Context. @param string $domain Domain to retrieve the translated text. @return string Translated text

**`yourls_unload_textdomain($domain)`**
— Unloads translations for a domain @param string $domain Textdomain to be unloaded @return bool Whether textdomain was unloaded

**`yourls_x($text, $context, $domain = 'default')`**
— Retrieve translated string with gettext context @param string $text Text to translate @param string $context Context information for the translators @param string $domain Optional. Domain to retrieve the translated text @return string Translated context string

**`yourls_xe($text, $context, $domain = 'default')`**
— Displays translated string with gettext context @param string $text Text to translate @param string $context Context information for the translators @param string $domain Optional. Domain to retrieve the translated text @return void Echoes translated context string

### `includes\functions-links.php`

**`yourls_add_query_arg()`**
— Add a query var to a URL and return URL. Completely stolen from WP. @param string|array $param1 Either newkey or an associative_array. @param string       $param2 Either newvalue or oldquery or URI. @param string       $param3 Optional. Old query or URI. @return string New URL query string.

**`yourls_admin_url($page = '')`**
— Return admin link, with SSL preference if applicable. @param string $page  Page name, eg "index.php" @return string

**`yourls_get_yourls_favicon_url($echo = true)`**
— Auto detect custom favicon in /user directory, fallback to YOURLS favicon, and echo/return its URL @param  bool $echo   true to echo, false to silently return @return string       favicon URL

**`yourls_get_yourls_site()`**
— Get YOURLS_SITE value, trimmed and filtered @return string  YOURLS_SITE, trimmed and filtered

**`yourls_link($keyword = '', $stats = false)`**
— Converts keyword into short link (prepend with YOURLS base URL) or stat link (sho.rt/abc+) @param  string $keyword  Short URL keyword @param  bool   $stats    Optional, true to return a stat link (eg sho.rt/abc+) @return string           Short URL, or keyword stat URL

**`yourls_match_current_protocol($url, $normal = 'http: if( yourls_is_ssl()`**
— Change protocol of a URL to HTTPS if we are currently on HTTPS @param string $url        a URL @param string $normal     Optional, the standard scheme (defaults to 'http://') @param string $ssl        Optional, the SSL scheme (defaults to 'https://') @return string            the modified URL, if applicable

**`yourls_remove_query_arg($key, $query = false)`**
— Remove arg from query. Opposite of yourls_add_query_arg. Stolen from WP. @param string|array $key   Query key or keys to remove. @param bool|string  $query Optional. When false uses the $_SERVER value. Default false. @return string New URL query string.

**`yourls_site_url($echo = true, $url = '')`**
— Return YOURLS_SITE or URL under YOURLS setup, with SSL preference @param bool $echo   Echo if true, or return if false @param string $url @return string

**`yourls_statlink($keyword = '')`**
— Converts keyword into stat link (prepend with YOURLS base URL, append +) @param  string $keyword  Short URL keyword @return string           Short URL stat link

**`yourls_urlencode_deep($value)`**
— Navigates through an array and encodes the values to be used in a URL. Stolen from WP, used in yourls_add_query_arg() @param array|string $value The array or string to be encoded. @return array|string

### `includes\functions-options.php`

**`yourls_add_option($name, $value = '')`**
— Add an option to the DB @param string $name Name of option to add. Expected to not be SQL-escaped. @param mixed $value Optional option value. Must be serializable if non-scalar. Expected to not be SQL-escaped. @return bool False if option was not added and true otherwise.

**`yourls_delete_option($name)`**
— Delete an option from the DB @param string $name Option name to delete. Expected to not be SQL-escaped. @return bool True, if option is successfully deleted. False on failure.

**`yourls_get_all_options()`**
— Read all options from DB at once @return void

**`yourls_get_option($option_name, $default = false)`**
— Read an option from DB (or from cache if available). Return value or $default if not found @param string $option_name Option name. Expected to not be SQL-escaped. @param mixed $default Optional value to return if option doesn't exist. Default false. @return mixed Value set for the option.

**`yourls_is_serialized($data, $strict = true)`**
— Check value to find if it was serialized. Stolen from WordPress @param mixed $data Value to check to see if was serialized. @param bool $strict Optional. Whether to be strict about the end of the string. Defaults true. @return bool False if not serialized and true if it was.

**`yourls_maybe_serialize($data)`**
— Serialize data if needed. Stolen from WordPress @param mixed $data Data that might be serialized. @return mixed A scalar data

**`yourls_maybe_unserialize($original)`**
— Unserialize value only if it was serialized. Stolen from WP @param string $original Maybe unserialized original, if is needed. @return mixed Unserialized data can be any type.

**`yourls_update_option($option_name, $newvalue)`**
— Update (add if doesn't exist) an option to DB @param string $option_name Option name. Expected to not be SQL-escaped. @param mixed $newvalue Option value. Must be serializable if non-scalar. Expected to not be SQL-escaped. @return bool False if value was not updated, true otherwise.

### `includes\functions-plugins.php`

**`yourls_activate_plugin($plugin)`**
— Activate a plugin @param string $plugin Plugin filename (full or relative to plugins directory) @return string|true  string if error or true if success

**`yourls_add_action($hook, $function_name, $priority = 10, $accepted_args = 1)`**
— Hooks a function on to a specific action. @param string   $hook           The name of the action to which the $function_to_add is hooked. @param callable $function_name  The name of the function you wish to be called. @param int      $priority       Optional. Used to specify the order in which the functions associated with a particular action @param int      $accepted_args  Optional. The number of arguments the function accept (default 1). @return void

**`yourls_add_filter($hook, $function_name, $priority = 10, $accepted_args = NULL, $type = 'filter')`**
— Registers a filtering function @param string   $hook           the name of the YOURLS element to be filtered or YOURLS action to be triggered @param callable $function_name  the name of the function that is to be called. @param int      $priority       optional. Used to specify the order in which the functions associated with a @param int      $accepted_args  optional. The number of arguments the function accept (default is the number @param string   $type @return void

**`yourls_apply_filter($hook, $value = '', $is_action = false)`**
— Performs a filtering operation on a value or an event. @param string $hook the name of the YOURLS element or action @param mixed $value the value of the element before filtering @param true|mixed $is_action true if the function is called by yourls_do_action() - otherwise may be the second parameter of an arbitrary number of parameters @return mixed

**`yourls_call_all_hooks($type, $hook, ...$args)`**
— Execute the 'all' hook, with all of the arguments or parameters that were used for the hook @param  string $type Either 'action' or 'filter' @param  string $hook The hook name, eg 'plugins_loaded' @param  mixed  $args Variable-length argument lists that were passed to the action or filter @return void

**`yourls_deactivate_plugin($plugin)`**
— Deactivate a plugin @param string $plugin Plugin filename (full relative to plugins directory) @return string|true  string if error or true if success

**`yourls_did_action($hook)`**
— Retrieve the number times an action is fired. @param string $hook Name of the action hook. @return int The number of times action hook `<tt>`$hook`</tt>` is fired

**`yourls_do_action($hook, $arg = '')`**
— Performs an action triggered by a YOURLS event. @param string $hook the name of the YOURLS action @param mixed $arg action arguments @return void

**`yourls_filter_unique_id($function)`**
— Build Unique ID for storage and retrieval. @param  string|array|object $function  The callable used in a filter or action. @return string  unique ID for usage as array key

**`yourls_get_actions($hook)`**
— Return actions for a specific hook. @param string $hook The hook to retrieve actions for @return array

**`yourls_get_filters($hook)`**
— Return filters for a specific hook. @param string $hook The hook to retrieve filters for @return array

**`yourls_get_plugin_data($file)`**
— Parse a plugin header @param string $file Physical path to plugin file @return array Array of 'Field'=>'Value' from plugin comment header lines of the form "Field: Value"

**`yourls_get_plugins()`**
— List plugins in /user/plugins @return array Array of [/plugindir/plugin.php]=>array('Name'=>'Ozh', 'Title'=>'Hello', )

**`yourls_has_action($hook, $function_to_check = false)`**
— Check if any action has been registered for a hook. @param string         $hook @param callable|false $function_to_check @return bool|int

**`yourls_has_active_plugins()`**
— Return number of active plugins @return int Number of activated plugins

**`yourls_has_filter($hook, $function_to_check = false)`**
— Check if any filter has been registered for a hook. @param string         $hook              The name of the filter hook. @param callable|false $function_to_check optional. If specified, return the priority of that function on this hook or false if not attached. @return int|bool Optionally returns the priority on that hook for the specified function.

**`yourls_is_a_plugin_file($file)`**
— Check if a file is a plugin file @param string $file Full pathname to a file @return bool

**`yourls_is_active_plugin($plugin)`**
— Check if a plugin is active @param string $plugin Physical path to plugin file @return bool

**`yourls_list_plugin_admin_pages()`**
— Build list of links to plugin admin pages, if any @return array  Array of arrays of URL and anchor of plugin admin pages, or empty array if no plugin page

**`yourls_load_plugins()`**
— Include active plugins @return array    Array('loaded' => bool, 'info' => string)

**`yourls_plugin_admin_page($plugin_page)`**
— Handle plugin administration page @param string $plugin_page @return void

**`yourls_plugin_basename($file)`**
— Return the path of a plugin file, relative to the plugins directory @param string $file @return string

**`yourls_plugin_url($file)`**
— Return the URL of the directory a plugin @param string $file @return string

**`yourls_plugins_sort_callback($plugin_a, $plugin_b)`**
— Callback function: Sort plugins @param array $plugin_a @param array $plugin_b @return int 0, 1 or -1, see uasort()

**`yourls_register_plugin_page($slug, $title, $function)`**
— Register a plugin administration page @param string   $slug @param string   $title @param callable $function @return void

**`yourls_remove_action($hook, $function_to_remove, $priority = 10)`**
— Removes a function from a specified action hook. @param string   $hook               The action hook to which the function to be removed is hooked. @param callable $function_to_remove The name of the function which should be removed. @param int      $priority           optional. The priority of the function (default: 10). @return bool                        Whether the function was registered as an action before it was removed.

**`yourls_remove_all_actions($hook, $priority = false)`**
— Removes all functions from a specified action hook. @param string    $hook     The action to remove hooks from @param int|false $priority optional. The priority of the functions to remove @return bool true when it's finished

**`yourls_remove_all_filters($hook, $priority = false)`**
— Removes all functions from a specified filter hook. @param string    $hook     The filter to remove hooks from @param int|false $priority optional. The priority of the functions to remove @return bool true when it's finished

**`yourls_remove_filter($hook, $function_to_remove, $priority = 10)`**
— Removes a function from a specified filter hook. @param string $hook The filter hook to which the function to be removed is hooked. @param callable $function_to_remove The name of the function which should be removed. @param int $priority optional. The priority of the function (default: 10). @return bool Whether the function was registered as a filter before it was removed.

**`yourls_return_empty_array()`**
— Returns an empty array. @return array Empty array.

**`yourls_return_empty_string()`**
— Returns an empty string. @return string Empty string.

**`yourls_return_false()`**
— Returns false. @return bool False.

**`yourls_return_null()`**
— Returns null. @return null Null value.

**`yourls_return_true()`**
— Returns true. @return bool True.

**`yourls_return_zero()`**
— Returns 0. @return int 0.

**`yourls_shunt_default()`**
— Default value used to check for 'shunt_*' filters. Before 1.10.4 we were checking for false, but that's not efficient as filtered functions can legitimately return false. @return string

**`yourls_shutdown()`**
— Shutdown function, runs just before PHP shuts down execution. Stolen from WP @return void

### `includes\functions-shorturls.php`

**`yourls_add_new_link($url, $keyword = '', $title = '', $row_id = 1)`**
— Add a new link in the DB, either with custom keyword, or find one @param  string $url      URL to shorten @param  string $keyword  optional "keyword" @param  string $title    option title @param  int    $row_id   used to form unique IDs in the generated HTML @return array            array with error/success state and short URL information

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
— Return array of all information associated with keyword. Returns false if keyword not found. Set optional $use_cache to false to force fetching from DB @param  string $keyword    Short URL keyword @param  bool   $use_cache  Default true, set to false to force fetching from DB @return false|object       false if not found, object with URL properties if found

**`yourls_get_keyword_longurl($keyword, $notfound = false)`**
— Return long URL associated with keyword. Optional $notfound = string default message if nothing found @param string $keyword          Short URL keyword @param false|string $notfound   Optional string to return if keyword not found @return mixed|string

**`yourls_get_keyword_stats($shorturl)`**
— Return array of stats for a given keyword @param  string $shorturl short URL keyword @return array            stats

**`yourls_get_keyword_timestamp($keyword, $notfound = false)`**
— Return timestamp associated with a keyword. Optional $notfound = string default message if nothing found @param string $keyword          Short URL keyword @param false|string $notfound   Optional string to return if keyword not found @return mixed|string

**`yourls_get_keyword_title($keyword, $notfound = false)`**
— Return title associated with keyword. Optional $notfound = string default message if nothing found @param string $keyword          Short URL keyword @param false|string $notfound   Optional string to return if keyword not found @return mixed|string

**`yourls_get_longurl_keywords($longurl, $order = 'ASC')`**
— Return array of keywords that redirect to the submitted long URL @param string $longurl long url @param string $order Optional SORT order (can be 'ASC' or 'DESC') @return array array of keywords

**`yourls_get_reserved_URL()`**
— Get the list of reserved keywords for URLs. @return array             Array of reserved keywords

**`yourls_get_shorturl_charset()`**
— Determine the allowed character set in short URLs @return string    Acceptable charset for short URLS keywords

**`yourls_insert_link_in_db($url, $keyword, $title = '')`**
— SQL query to insert a new link in the DB. Returns boolean for success or failure of the inserting @param string $url @param string $keyword @param string $title @return bool true if insert succeeded, false if failed

**`yourls_is_page($keyword)`**
— Check if a keyword matches a "page" @param  string $keyword  Short URL $keyword @return bool             true if is page, false otherwise

**`yourls_is_shorturl($shorturl)`**
— Is a URL a short URL? Accept either 'http://sho.rt/abc' or 'abc' @param  string $shorturl   short URL @return bool               true if registered short URL, false otherwise

**`yourls_keyword_is_free($keyword)`**
— Check if keyword id is free (ie not already taken, and not reserved). Return bool. @param  string $keyword    short URL keyword @return bool               true if keyword is taken (ie there is a short URL for it), false otherwise

**`yourls_keyword_is_reserved($keyword)`**
— Check to see if a given keyword is reserved (ie reserved URL or an existing page). Returns bool @param  string $keyword   Short URL keyword @return bool              True if keyword reserved, false if free to be used

**`yourls_keyword_is_taken($keyword, $use_cache = true)`**
— Check if a keyword is taken (ie there is already a short URL with this id). Return bool. @param  string $keyword    short URL keyword @param  bool   $use_cache  optional, default true: do we want to use what is cached in memory, if any, or force a new SQL query @return bool               true if keyword is taken (ie there is a short URL for it), false otherwise

**`yourls_long_url_exists($url)`**
— Check if a long URL already exists in the DB. Return NULL (doesn't exist) or an object with URL informations. @param  string $url  URL to check if already shortened @return mixed        NULL if does not already exist in DB, or object with URL information as properties (eg keyword, url, title, ...)

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
— Upgrade YOURLS and DB schema @param string|int $step @param string $oldver @param string $newver @param string|int $oldsql @param string|int $newsql @return void

**`yourls_upgrade_482()`**
— Upgrade r482

**`yourls_upgrade_505_to_506()`**
— Update to 506, just the fix for people who had updated to master on 1.7.10

**`yourls_upgrade_to_14($step)`**
— Main func for upgrade from 1.3-RC1 to 1.4

**`yourls_upgrade_to_141()`**
— Main func for upgrade from 1.4 to 1.4.1

**`yourls_upgrade_to_143()`**
— Main func for upgrade from 1.4.1 to 1.4.3

**`yourls_upgrade_to_15()`**
— Main func for upgrade from 1.4.3 to 1.5

**`yourls_upgrade_to_506()`**
— Update to 506

**`yourls_upgrade_to_507()`**
— Add sort index for fast URL lookups. DB version 507.

### `includes\functions.php`

**`yourls_allow_duplicate_longurls()`**
— Allow several short URLs for the same long URL ? @return bool

**`yourls_check_IP_flood($ip = '')`**
— Check if an IP shortens URL too fast to prevent DB flood. Return true, or die. @param string $ip @return bool|mixed|string

**`yourls_check_maintenance_mode()`**
— Check for maintenance mode. If yes, die. See yourls_maintenance_mode(). Stolen from WP. @return void

**`yourls_content_type_header($type)`**
— Send a filterable content type header @param string $type content type ('text/html', 'application/json', ...) @return bool whether header was sent

**`yourls_deprecated_function($function, $version, $replacement = null)`**
— Marks a function as deprecated and informs that it has been used. Stolen from WP. @param string $function The function that was called @param string $version The version of WordPress that deprecated the function @param string $replacement Optional. The function that should have been called @return void

**`yourls_do_log_redirect()`**
— Check if we want to not log redirects (for stats) @return bool

**`yourls_fix_request_uri()`**
— Fix $_SERVER['REQUEST_URI'] variable for various setups. Stolen from WP. @return void

**`yourls_get_HTTP_status($code)`**
— Return an HTTP status code @param int $code @return string

**`yourls_get_IP()`**
— Get client IP Address. Returns a DB safe string. @return string

**`yourls_get_current_version_from_sql()`**
— Get current version & db version as stored in the options DB. Prior to 1.4 there's no option table. @return array

**`yourls_get_db_stats($where = [ 'sql' => '', 'binds' => [] ])`**
— Get total number of URLs and sum of clicks. Input: optional "AND WHERE" clause. Returns array @param  array $where See comment above @return array

**`yourls_get_next_decimal()`**
— Get next id a new link will have if no custom keyword provided @return int            id of next link

**`yourls_get_protocol($url)`**
— Get protocol from a URL (eg mailto:, http:// ...) @param string $url URL to be check @return string Protocol, with slash slash if applicable. Empty string if no protocol

**`yourls_get_protocol_slashes_and_rest($url, $array = [ 'protocol', 'slashes', 'rest' ])`**
— Explode a URL in an array of ( 'protocol' , 'slashes if any', 'rest of the URL' ) @param string $url URL to be parsed @param array $array Optional, array of key names to be used in returned array @return array|false false if no protocol found, array of ('protocol' , 'slashes', 'rest') otherwise

**`yourls_get_referrer()`**
— Returns the sanitized referrer submitted by the browser. @return string               HTTP Referrer or 'direct' if no referrer was provided

**`yourls_get_relative_url($url, $strict = true)`**
— Get relative URL (eg 'abc' from 'http://sho.rt/abc') @param string $url URL to relativize @param bool $strict if true and if URL isn't relative to YOURLS install, return empty string @return string URL

**`yourls_get_remote_title($url)`**
— Get a remote page title @param string $url URL @return string Title (sanitized) or the URL if no title found

**`yourls_get_request($yourls_site = '', $uri = '')`**
— Get request in YOURLS base (eg in 'http://sho.rt/yourls/abcd' get 'abdc') @param string $yourls_site   Optional, YOURLS installation URL (default to constant YOURLS_SITE) @param string $uri           Optional, page requested (default to $_SERVER['REQUEST_URI'] eg '/yourls/abcd' ) @return string               request relative to YOURLS base (eg 'abdc')

**`yourls_get_stats($filter = 'top', $limit = 10, $start = 0)`**
— Return array of stats. (string)$filter is 'bottom', 'last', 'rand' or 'top'. (int)$limit is the number of links to return @param string $filter  'bottom', 'last', 'rand' or 'top' @param int $limit      Number of links to return @param int $start      Offset to start from @return array          Array of links

**`yourls_get_user_agent()`**
— Returns a sanitized a user agent string. Given what I found on http://www.user-agents.org/ it should be OK. @return string

**`yourls_include_file_sandbox($file)`**
— File include sandbox @param string $file filename (full path) @return string|bool  string if error, true if success

**`yourls_is_API()`**
— Check if we're in API mode. @return bool

**`yourls_is_Ajax()`**
— Check if we're in Ajax mode. @return bool

**`yourls_is_GO()`**
— Check if we're in GO mode (yourls-go.php). @return bool

**`yourls_is_admin()`**
— Check if we're in the admin area. Returns bool. Does not relate with user rights. @return bool

**`yourls_is_allowed_protocol($url, $protocols = [])`**
— Check if a URL protocol is allowed @param string $url URL to be check @param array $protocols Optional. Array of protocols, defaults to global $yourls_allowedprotocols @return bool true if protocol allowed, false otherwise

**`yourls_is_infos()`**
— Check if we're displaying stats infos (yourls-infos.php). Returns bool @return bool

**`yourls_is_installed()`**
— Check if YOURLS is installed @return bool

**`yourls_is_installing()`**
— Check if YOURLS is installing @return bool

**`yourls_is_mobile_device()`**
— Quick UA check for mobile devices. @return bool

**`yourls_is_private()`**
— Determine if the current page is private @return bool

**`yourls_is_ssl()`**
— Check if SSL is used. Stolen from WP. @return bool

**`yourls_is_upgrading()`**
— Check if YOURLS is upgrading @return bool

**`yourls_is_valid_charset($charset)`**
— Is supported charset encoding for conversion. @return bool

**`yourls_is_windows()`**
— Check if the server seems to be running on Windows. Not exactly sure how reliable this is. @return bool

**`yourls_log_redirect($keyword)`**
— Log a redirect (for stats) @param string $keyword short URL keyword @return mixed Result of the INSERT query (1 on success)

**`yourls_make_regexp_pattern($string)`**
— Make an optimized regexp pattern from a string of characters @param string $string @return string

**`yourls_needs_ssl()`**
— Check if SSL is required. @return bool

**`yourls_no_cache_headers()`**
— Send headers to explicitly tell browser not to cache content or redirection @return void

**`yourls_no_frame_header()`**
— Send header to prevent display within a frame from another site (avoid clickjacking) @return void|mixed

**`yourls_redirect($location, $code = 301)`**
— Redirect to another page @param string $location      URL to redirect to @param int    $code          HTTP status code to send @return int                  1 for header redirection, 2 for js redirection, 3 otherwise (CLI)

**`yourls_redirect_javascript($location, $dontwait = true)`**
— Redirect to another page using Javascript. Set optional (bool)$dontwait to false to force manual redirection (make sure a message has been read by user) @param string $location @param bool   $dontwait @return void

**`yourls_redirect_shorturl($url, $keyword)`**
— Redirect to an existing short URL @param  string $url @param  string $keyword @return void

**`yourls_rnd_string($length = 5, $type = 0, $charlist = '')`**
— Generate random string of (int)$length length and type $type (see function for details) @param int    $length @param int    $type @param string $charlist @return mixed|string

**`yourls_robots_tag_header()`**
— Send an X-Robots-Tag header. See #3486 @return void

**`yourls_set_installed($bool)`**
— Set installed state @param bool $bool whether YOURLS is installed or not @return void

**`yourls_set_url_scheme($url, $scheme = '')`**
— Set URL scheme (HTTP or HTTPS) to a URL @param string $url    URL @param string $scheme scheme, either 'http' or 'https' @return string URL with chosen scheme

**`yourls_status_header($code = 200)`**
— Set HTTP status header @param int $code  status header code @return bool      whether header was sent

**`yourls_tell_if_new_version()`**
— Tell if there is a new YOURLS version @return void

**`yourls_update_clicks($keyword, $clicks = false)`**
— Update click count on a short URL. Return 0/1 for error/success. @param string $keyword @param false|int $clicks @return int 0 or 1 for error/success

**`yourls_update_next_decimal($int = 0)`**
— Update id for next link with no custom keyword @param integer $int id for next link @return bool        true or false depending on if there has been an actual MySQL query. See note above.

**`yourls_upgrade_is_needed()`**
— Check if an upgrade is needed @return bool

**`yourls_xml_encode($array)`**
— Return XML output. @param array $array @return string

## Class Methods

### `includes\Config\Config.php` — class `Config`

**`public define_core_constants()`**
— Define core constants that have not been user defined in config.php @return void

**`public find_config()`**
— Find config.php, either user defined or from standard location @return string         path to found config file

**`public fix_win32_path($path)`**
— Convert antislashes to slashes @param  string  $path @return string  path with \ converted to

**`public set_config($config)`**
— @param  string $config   path to config file @return void

**`public set_root($root)`**
— @param  string $root   path to YOURLS root directory @return void

### `includes\Config\Init.php` — class `Init`

**`public include_cache_files()`**
— Include custom extension file. @return void

**`public include_core_functions()`**
— @return void

**`public include_db_files()`**
— @return void

**`public redirect_ssl_if_needed()`**
— @return void

### `includes\Database\Logger.php` — class `Logger`

**`public getMessages()`**
— Returns the logged messages. @return array

**`public log($level, string|\Stringable $message, array $context = [])`**
— Logs a message. @param string  $level    The log level (ie type of message) @param string  $message  The log message. @param array   $context  Data to interpolate into the message. @return void

**`public pretty_format($statement, array $values = array()`**
— Format PDO statement with bind/values replacement @param  string $statement  SQL query with PDO style named placeholders @param  array  $values     Optional array of values corresponding to placeholders @return string             Readable SQL query with placeholders replaced

### `includes\Database\Options.php` — class `Options`

**`public add($name, $value)`**
— Add an option to the DB @param  string $name   Name of option to add. Expected to not be SQL-escaped. @param  mixed  $value  Option value. Must be serializable if non-scalar. Expected to not be SQL-escaped. @return bool           False if option was not added (eg already exists), true otherwise.

**`public delete($name)`**
— Delete option from DB @param  string $name  Option name to delete. Expected to not be SQL-escaped. @return bool          False if option was not deleted (eg not found), true otherwise.

**`public get($name, $default)`**
— Get option value from DB (or from cache if available). Return value or $default if not found @param  string $name     Option name @param  string $default  Value to return if option doesn't exist @return mixed            Value set for the option

**`public get_all_options()`**
— Read all options from DB at once, return bool @return bool    True on success, false on failure (eg table missing or empty)

**`public update($name, $newvalue)`**
— Update (add if doesn't exist) an option to DB @param  string $name      Option name. Expected to not be SQL-escaped. @param  mixed  $newvalue  Option value. @return bool              False if value was not updated, true otherwise.

### `includes\Database\Profiler.php` — class `Profiler`

**`public finish(?string $statement = null, array $values = [])`**
— Finishes and logs a profile entry. @param string $statement The statement being profiled, if any. @param array $values The values bound to the statement, if any. @return void

### `includes\Database\YDB.php` — class `YDB`

**`public add_plugin($plugin)`**
— @param string $plugin  plugin filename @return void

**`public add_plugin_page($slug, $title, $function)`**
— @param string   $slug @param string   $title @param callable $function @return void

**`public connect_to_DB()`**
— Initiate real connection to DB server @return void

**`public dead_or_error(\Exception $exception)`**
— Die with an error message @param \Exception $exception @return void

**`public delete_infos($keyword)`**
— @param string $keyword @return void

**`public delete_option($name)`**
— @param string $name @return void

**`public get_emulate_state()`**
— Get emulate status @return bool

**`public get_html_context()`**
— @return string

**`public get_infos($keyword)`**
— @param  string $keyword @return array

**`public get_num_queries()`**
— Return count of SQL queries performed @return int

**`public get_option($name)`**
— @param  string $name @return string

**`public get_plugin_pages()`**
— @return array

**`public get_plugins()`**
— @return array

**`public get_queries()`**
— Return SQL queries performed @return array

**`public has_infos($keyword)`**
— @param  string $keyword @return bool

**`public has_option($name)`**
— @param  string $name @return bool

**`public init()`**
— Init everything needed @return void

**`public is_installed()`**
— Get YOURLS installed state @return bool

**`public mysql_version()`**
— Return MySQL version @return string

**`public remove_plugin($plugin)`**
— @param string $plugin  plugin filename @return void

**`public remove_plugin_page($slug)`**
— @param string $slug @return void

**`public set_emulate_state()`**
— Check if we emulate prepare statements, and set bool flag accordingly @return void

**`public set_html_context($context)`**
— @param string $context @return void

**`public set_infos($keyword, $infos)`**
— @param string $keyword @param mixed  $infos @return void

**`public set_installed($bool)`**
— Set YOURLS installed state @param  bool $bool @return void

**`public set_option($name, $value)`**
— @param string $name @param mixed  $value @return void

**`public set_plugin_pages(array $pages)`**
— @param array $pages @return void

**`public set_plugins(array $plugins)`**
— @param array $plugins @return void

**`public start_profiler()`**
— Start a Message Logger @return void

**`public update_infos_if_exists($keyword, $infos)`**
— @param string $keyword @param mixed  $infos @return void

### `includes\Http\Redirection.php` — class `Redirection`

**`public redirect($location, $code)`**

**`public redirect_javascript($url)`**

### `includes\Views\AdminParams.php` — class `AdminParams`

**`public get_click_filter()`**
— Get the click "more or less than" @return mixed

**`public get_click_limit()`**
— Get the click threshold @return int|string

**`public get_date_params()`**
— Get the date parameters : the date "filter" and the two dates @return array

**`public get_page()`**
— Get the current page number to be displayed @return int

**`public get_param_long_name(string $param)`**
— Get the correct phrasing associated to a search or sort parameter (ie 'all' -> 'All fields' for instance) @param string $param @return string

**`public get_per_page(int $default)`**
— Get the number of links to display per page @param int $default default number of links to display @return int

**`public get_search()`**
— Get search text (the 'Search for') from query string variables search_protocol, search_slashes and search @return string

**`public get_search_in()`**
— Get the 'Search In' parameter (one of 'all', 'keyword', 'url', 'title', 'ip') @return string

**`public get_sort_by()`**
— Get the 'Sort by' parameter @return string

**`public get_sort_order()`**
— Get the sort order (asc or desc) @return mixed

### `includes\functions-l10n.php` — class `YOURLS_Locale_Formats`

**`public get_meridiem($meridiem)`**
— Retrieve translated version of meridiem string. @param string $meridiem Either 'am', 'pm', 'AM', or 'PM'. Not translated version. @return string Translated version

**`public get_month($month_number)`**
— Retrieve the full translated month by month number. @param string|int $month_number '01' through '12' @return string Translated full month name

**`public get_month_abbrev($month_name)`**
— Retrieve translated version of month abbreviation string. @param string $month_name Translated month to get abbreviated version @return string Translated abbreviated month

**`public get_weekday($weekday_number)`**
— Retrieve the full translated weekday word. @param int|string $weekday_number 0 for Sunday through 6 Saturday @return string Full translated weekday

**`public get_weekday_abbrev($weekday_name)`**
— Retrieve the translated weekday abbreviation. @param string $weekday_name Full translated weekday word @return string Translated weekday abbreviation

**`public get_weekday_initial($weekday_name)`**
— Retrieve the translated weekday initial. @param string $weekday_name @return string

**`public init()`**
— Sets up the translated strings and object properties. @return void

**`public is_rtl()`**
— Checks if current locale is RTL. @return bool Whether locale is RTL.

**`public register_globals()`**
— Global variables are deprecated. For backwards compatibility only. @return void

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
| `api` | `$action` | `yourls-api.php:16` |
| `api_output` | `$mode, $output, $send_headers, $echo` | `includes\functions-api.php:164` |
| `auth_successful` | `—` | `includes\auth.php:28` |
| `check_ip_flood` | `$ip` | `includes\functions.php:679` |
| `content_type_header` | `$type` | `includes\functions.php:377` |
| `deactivated_` | `$plugin` | `includes\functions-plugins.php:712` |
| `deactivated_plugin` | `$plugin` | `includes\functions-plugins.php:711` |
| `debug_log` | `$msg` | `includes\functions-debug.php:17` |
| `delete_link` | `$keyword, $delete` | `includes\functions-shorturls.php:261` |
| `delete_option` | `$name` | `includes\Database\Options.php:249` |
| `deprecated_function` | `$function, $replacement, $version` | `includes\functions.php:1259` |
| `get_all_options` | `$options` | `includes\Database\Options.php:78` |
| `get_db_action` | `$context` | `includes\class-mysql.php:119` |
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
| `shareboxes_after` | `$longurl, $shorturl, $title, $text` | `includes\functions-html.php:498` |
| `shareboxes_before` | `$longurl, $shorturl, $title, $text` | `includes\functions-html.php:467` |
| `shareboxes_middle` | `$longurl, $shorturl, $title, $text` | `includes\functions-html.php:480` |
| `shutdown` | `—` | `includes\functions-plugins.php:852` |
| `status_header` | `$code` | `includes\functions.php:394` |
| `unload_textdomain` | `$domain` | `includes\functions-l10n.php:492` |
| `update_clicks` | `$keyword, $result, $clicks` | `includes\functions.php:134` |
| `update_next_decimal` | `$int, $update` | `includes\functions.php:69` |
| `update_option` | `$name, $oldvalue, $newvalue` | `includes\Database\Options.php:175` |
| `yourls_die` | `—` | `includes\functions-html.php:526` |

## Constants

| Name | Value | Description | File |
|------|-------|-------------|------|
| `YOURLS_ABSPATH` | `$this->root` |  | `includes\Config\Config.php:122` |
| `YOURLS_ADMIN_SSL` | `false` |  | `includes\Config\Config.php:202` |
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
| `YOURLS_FLOOD_DELAY_SECONDS` | `15` |  | `includes\Config\Config.php:182` |
| `YOURLS_FLOOD_IP_WHITELIST` | `''` |  | `includes\Config\Config.php:186` |
| `YOURLS_GO` | `true` |  | `yourls-go.php:2` |
| `YOURLS_INC` | `YOURLS_ABSPATH.'/includes'` |  | `includes\Config\Config.php:126` |
| `YOURLS_INFOS` | `true` |  | `yourls-infos.php:3` |
| `YOURLS_LANG_DIR` | `YOURLS_USERDIR.'/languages'` |  | `includes\Config\Config.php:146` |
| `YOURLS_NONCE_LIFE` | `43200` |  | `includes\Config\Config.php:194` |
| `YOURLS_NOSTATS` | `false` |  | `includes\Config\Config.php:198` |
| `YOURLS_PAGEDIR` | `YOURLS_USERDIR.'/pages'` |  | `includes\Config\Config.php:166` |
| `YOURLS_PLUGINDIR` | `YOURLS_USERDIR.'/plugins'` |  | `includes\Config\Config.php:150` |
| `YOURLS_PLUGINURL` | `YOURLS_USERURL.'/plugins'` |  | `includes\Config\Config.php:154` |
| `YOURLS_THEMEDIR` | `YOURLS_USERDIR.'/themes'` |  | `includes\Config\Config.php:158` |
| `YOURLS_THEMEURL` | `YOURLS_USERURL.'/themes'` |  | `includes\Config\Config.php:162` |
| `YOURLS_UNINSTALL_PLUGIN` | `true` |  | `includes\functions-plugins.php:698` |
| `YOURLS_USER` | `$user` |  | `includes\functions-auth.php:521` |
| `YOURLS_USERDIR` | `YOURLS_ABSPATH.'/user'` |  | `includes\Config\Config.php:130` |
| `YOURLS_USERURL` | `trim(YOURLS_SITE, '/'` |  | `includes\Config\Config.php:134` |
| `YOURLS_VERSION` | `'1.10.4-dev'` | YOURLS version | `includes\version.php:2` |

## Implicit Constants

> Referenced in code but never explicitly `define()`d. Intended to be set by the user in `config.php`.

| Name | First seen in |
|------|--------------|
| `YOURLS_ADMIN` | `includes\functions.php:837` |
| `YOURLS_AJAX` | `includes\functions.php:810` |
| `YOURLS_COOKIEKEY` | `includes\functions-auth.php:599` |
| `YOURLS_DB_DRIVER` | `includes\functions-http.php:310` |
| `YOURLS_DB_HOST` | `includes\class-mysql.php:16` |
| `YOURLS_DB_NAME` | `includes\class-mysql.php:15` |
| `YOURLS_DB_PASS` | `includes\class-mysql.php:14` |
| `YOURLS_DB_PREFIX` | `includes\Config\Config.php:170` |
| `YOURLS_DB_TABLE_NEXTDEC` | `includes\functions-upgrade.php:311` |
| `YOURLS_DB_USER` | `includes\class-mysql.php:13` |
| `YOURLS_FAST_INIT` | `includes\Config\Init.php:61` |
| `YOURLS_HOURS_OFFSET` | `includes\functions-formatting.php:844` |
| `YOURLS_INSTALLING` | `includes\functions.php:703` |
| `YOURLS_LANG` | `includes\functions-l10n.php:40` |
| `YOURLS_NO_HASH_PASSWORD` | `includes\functions-auth.php:746` |
| `YOURLS_NO_VERSION_CHECK` | `includes\functions-http.php:504` |
| `YOURLS_PASSWORD` | `includes\functions-auth.php:708` |
| `YOURLS_PRIVATE` | `includes\functions-http.php:317` |
| `YOURLS_PRIVATE_API` | `includes\functions.php:610` |
| `YOURLS_PRIVATE_INFOS` | `includes\functions.php:614` |
| `YOURLS_PROXY` | `includes\functions-http.php:97` |
| `YOURLS_PROXY_BYPASS_HOSTS` | `includes\functions-http.php:114` |
| `YOURLS_PROXY_PASSWORD` | `includes\functions-http.php:99` |
| `YOURLS_PROXY_USERNAME` | `includes\functions-http.php:99` |
| `YOURLS_SITE` | `includes\functions-http.php:303` |
| `YOURLS_UNIQUE_URLS` | `includes\functions-http.php:318` |
| `YOURLS_UPGRADING` | `includes\functions.php:713` |
| `YOURLS_URL_CONVERT` | `includes\functions-http.php:319` |
