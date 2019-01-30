<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/*
 *
 * Global variables used throughout the platform.
 * use-case format: $this->config->item('in_miner_start_id')
 *
 */


//Settime zone to PST:
date_default_timezone_set('America/Los_Angeles');


//UI Display:
$config['app_version']              = '0.715'; //Cache buster in URLs for static js/css files
$config['en_per_page']              = 50; //Limits the maximum entities loaded per page


//Intents:
$config['in_mission_name']          = 'expand human potential'; //is the top level intent that will always contain every other intention we cover
$config['in_mission_id']            = 7766; //expand human potential
$config['in_strategy_name']         = 'advance your tech career'; //The broader, more long-term strategic focus of Mench
$config['in_strategy_id']           = 7240; //advance your tech career
$config['in_tactic_id']             = 6903; //The shorter, more immediate focus recommended to Students & Miners as the starting point
$config['in_webhook_prefix']        = 'https://'; //The prefix for completion Webhook URL
$config['in_miner_start_id']        = 7435; //The ID that gets Miner's started


//Entities:
$config['en_start_here_id']         = 3463; //The default matrix entity that is loaded when Entities is clicked
$config['en_default_parent_id']     = 1326; //The entity that would be the parent to all new URLs added via Messages


//App Functionality:
$config['enable_algolia']           = true; //Currently reached our monthly free quota
$config['file_size_max']            = 25; //Server setting is 32MB. see here: mench.com/ses
$config['tr_status_incomplete']     = array(0, 1); //Transactions with these tr_status values are considered in-complete
$config['core_objects']             = array( //The 3 primary objects in the app
    'in' => 'Intent',
    'en' => 'Entity',
    'tr' => 'Transaction'
);


//App Inputs:
$config['in_points_options']        = array(-89, -55, -34, -21, -13, -8, -5, -3, -2, -1, 0, 1, 2, 3, 5, 8, 13, 21, 34, 55, 89, 144, 233, 377, 610);
$config['in_seconds_max']           = 28800; //The maximum seconds allowed per intent. If larger, the miner is asked to break it down into smaller intents
$config['in_outcome_max']           = 89; //Max number of characters allowed in the title of intents
$config['en_name_max']              = 250; //Max number of characters allowed in the title of intents
$config['tr_content_max']           = 610; //Max number of characters allowed in messages. Facebook's cap is 2000 characters/message
$config['message_commands']         = array( //The list of commands supported within a message content
                                        '/firstname', //replaced with first name
                                        '/slice', //slice a part of a YouTube video like this: /slice:180:202 (22 seconds starting from minute 3:00
                                        '/link', //Button link like this: /link:Open Mench:https://mench.com
                                    );

$config['en_convert_4537']          = array( //Used for saving media to Facebook Servers to speed-up delivery over Messenger
    4258 => 'video',
    4259 => 'audio',
    4260 => 'image',
    4261 => 'file',
);

$config['en_mass_actions']          = array( //Various mass actions to be taken on Entity children

    'prefix_add'    => 'Add string as prefix',
    'postfix_add'   => 'Add string as postfix',
    'replace_match'  => 'Replace matching strings',
    'replace_icon'  => 'Update icons',

    //Logic for all items above must be added to Entities/en_miner_ui section
);

//Third-Party Settings:
$config['fb_max_message']           = 2000; //The maximum length of a Message accepted via Messenger API
$config['en_convert_4454']          = array( //Facebook Messenger Notification Levels - This is a manual converter of our internal entities to Facebook API
                                        4456 => 'REGULAR',
                                        4457 => 'SILENT_PUSH',
                                        4458 => 'NO_PUSH',
                                        //@4455 => Unsubscribe NOT listed here since in that case all communication is blocked!
                                    );
$config['en_convert_4537']          = array( //Used for saving media to Facebook Servers to speed-up delivery over Messenger
                                        4258 => 'video',
                                        4259 => 'audio',
                                        4260 => 'image',
                                        4261 => 'file',
                                    );

//Admin notification via Email:
$config['notify_admins']            = array(
                                        array(
                                            'admin_emails' => array('shervin@mench.com'),
                                            'admin_en_ids' => array(1),
                                            'admin_notify' => array(
                                                4246, //Platform Error
                                                4269, //Miner login
                                            ),
                                        ),
                                    );

$config['eng_converter']            = array(
                                        //Mining Links
                                        20 => 4250, //Log intent creation
                                        6971 => 4251, //Log entity creation
                                        21 => 4252, //Log intent archived
                                        50 => 4254, //Log intent migration
                                        19 => 4264, //Log intent modification
                                        //0 => 4253, //Entity Archived (Did not have this!)

                                        36 => 4242, //Log intent message update
                                        7727 => 4242, //Log entity link note modification

                                        12 => 4263, //Log entity modification
                                        7001 => 4299, //Log pending image upload sync to cloud

                                        89 => 4241, //Log intent unlinked
                                        7292 => 4241, //Log entity unlinked
                                        35 => 4241, //Log intent message archived
                                        6912 => 4241, //Log entity URL archived

                                        39 => 4262, //Log intent message sorting
                                        22 => 4262, //Log intent children sorted


                                        //Growth links
                                        27 => 4265, //Log user joined
                                        5 => 4266, //Log Messenger optin
                                        4 => 4267, //Log Messenger referral
                                        3 => 4268, //Log Messenger postback
                                        10 => 4269, //Log user sign in
                                        59 => 4271, //Log user password reset


                                        //Personal Assistant links
                                        40 => 4273, //Log console tip read
                                        7703 => 4275, //Log subscription intent search
                                        28 => 4276, //Log user email sent
                                        6 => 4277, //Log message received
                                        1 => 4278, //Log message read
                                        2 => 4279, //Log message delivered
                                        7 => 4280, //Log message sent
                                        55 => 4282, //Log my account access
                                        32 => 4283, //Log action plan access
                                        33 => 4242, //Log Action Plan completion [Link updated]
                                        7718 => 4287, //Log unrecognized message

                                        //Platform Operations Links:
                                        8 => 4246, //Platform Error
                                        72 => 4248, //Log user review
                                    );


//Ledger filters:
$config['ledger_filters']           = array(
                                        'tr_miner_en_id' => 'en',
                                        'tr_type_en_id' => 'en',
                                        'tr_en_child_id'  => 'en',
                                        'tr_en_parent_id' => 'en',
                                        'tr_in_child_id'  => 'in',
                                        'tr_in_parent_id' => 'in',
                                        'tr_tr_parent_id' => 'tr',
                                    );


//3x Table Statuses:
$config['object_statuses']          = array(
                                        'in_status' => array(
                                            -1 => array(
                                                's_name' => 'Removed',
                                                's_desc' => 'Intent removed by Miner',
                                                's_icon' => '<i class="fal fa-minus-square"></i>',
                                            ),
                                            0 => array(
                                                's_name' => 'New',
                                                's_desc' => 'Intent is newly added and is pending review by a Miner',
                                                's_icon' => '<i class="fal fa-square"></i>',
                                            ),
                                            1 => array(
                                                's_name' => 'Working On',
                                                's_desc' => 'Intent is being mined by Miners and not yet ready to be published live',
                                                's_icon' => '<i class="fas fa-spinner fa-spin"></i>',
                                            ),
                                            2 => array(
                                                's_name' => 'Published',
                                                's_desc' => 'Intent is published live and ready to be distributed to Students',
                                                's_icon' => '<i class="fal fa-check-square"></i>',
                                            ),
                                            3 => array(
                                                's_name' => 'Verified',
                                                's_desc' => 'Intent recommended to Students',
                                                's_icon' => '<i class="fas fa-badge-check"></i>',
                                            ),
                                        ),
                                        'en_status' => array(
                                            -1 => array(
                                                's_name' => 'Removed',
                                                's_desc' => 'Entity removed by Miner',
                                                's_icon' => '<i class="fal fa-minus-square"></i>',
                                            ),
                                            0 => array(
                                                's_name' => 'New',
                                                's_desc' => 'Entity is newly added and is pending review by a Miner',
                                                's_icon' => '<i class="fal fa-square"></i>',
                                            ),
                                            1 => array(
                                                's_name' => 'Working On',
                                                's_desc' => 'Entity is being mined by Miners and not yet ready to be published live',
                                                's_icon' => '<i class="fas fa-spinner fa-spin"></i>'
                                            ),
                                            2 => array(
                                                's_name' => 'Published',
                                                's_desc' => 'Entity is published live and ready to be distributed to Students',
                                                's_icon' => '<i class="fal fa-check-square"></i>',
                                            ),
                                            3 => array(
                                                's_name' => 'Verified',
                                                's_desc' => 'Entity references a human which has been claimed by that person',
                                                's_icon' => '<i class="fas fa-badge-check"></i>',
                                            ),
                                        ),
                                        'tr_status' => array(
                                            -1 => array(
                                                's_name' => 'Removed',
                                                's_desc' => 'Transaction removed by Student or Miner',
                                                's_icon' => '<i class="fal fa-minus-square"></i>',
                                            ),
                                            0 => array( //Considered incomplete, see tr_status_incomplete for more details
                                                's_name' => 'New',
                                                's_desc' => 'Newly added transaction pending review by Miner',
                                                's_icon' => '<i class="fal fa-square"></i>',
                                            ),
                                            1 => array( //Considered incomplete, see tr_status_incomplete for more details
                                                's_name' => 'Working On',
                                                's_desc' => 'Transaction is being worked on but is not yet completed',
                                                's_icon' => '<i class="fas fa-spinner fa-spin"></i>',
                                            ),
                                            2 => array(
                                                's_name' => 'Published',
                                                's_desc' => 'Transaction is completed and ready for updates to be synced',
                                                's_icon' => '<i class="fal fa-check-square"></i>',
                                            ),
                                            3 => array(
                                                's_name' => 'Verified',
                                                's_desc' => 'Transaction has been reviewed and verified by a Miner',
                                                's_icon' => '<i class="fas fa-badge-check"></i>',
                                            ),
                                        ),
                                        'in_is_any' => array(
                                            0 => array(
                                                's_name' => 'AND',
                                                's_desc' => 'Intent is complete when all children are marked as complete',
                                                's_icon' => '<i class="fas fa-sitemap"></i>',
                                            ),
                                            1 => array(
                                                's_name' => 'OR',
                                                's_desc' => 'Intent is complete when a single child is marked as complete',
                                                's_icon' => '<i class="fas fa-code-merge"></i>',
                                            ),
                                        ),

                                    );



/*
 |--------------------------------------------------------------------------
 | Base Site URL
 |--------------------------------------------------------------------------
 |
 | URL to your CodeIgniter root. Typically this will be your base URL,
 | WITH a trailing slash:
 |
 |	http://example.com/
 |
 | WARNING: You MUST set this value!
 |
 | If it is not set, then CodeIgniter will try guess the protocol and path
 | your installation, but due to security concerns the hostname will be set
 | to $_SERVER['SERVER_ADDR'] if available, or localhost otherwise.
 | The auto-detection mechanism exists only for convenience during
 | development and MUST NOT be used in production!
 |
 | If you need to allow multiple domains, remember that this file is still
 | a PHP script and you can easily do that on your own.
 |
 */
$config['base_url'] = '';

/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
$config['index_page'] = 'index.php';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of 'REQUEST_URI' works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'REQUEST_URI'    Uses $_SERVER['REQUEST_URI']
| 'QUERY_STRING'   Uses $_SERVER['QUERY_STRING']
| 'PATH_INFO'      Uses $_SERVER['PATH_INFO']
|
| WARNING: If you set this to 'PATH_INFO', URIs will always be URL-decoded!
*/
$config['uri_protocol'] = 'REQUEST_URI';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by CodeIgniter.
| For more information please see the user guide:
|
| https://codeigniter.com/user_guide/general/urls.html
*/
$config['url_suffix'] = '';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language'] = 'english';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
| See http://php.net/htmlspecialchars for a list of supported charsets.
|
*/
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the 'hooks' feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/
$config['enable_hooks'] = FALSE;

/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| https://codeigniter.com/user_guide/general/core_classes.html
| https://codeigniter.com/user_guide/general/creating_libraries.html
|
*/
$config['subclass_prefix'] = 'MY_';

/*
|--------------------------------------------------------------------------
| Composer auto-loading
|--------------------------------------------------------------------------
|
| Enabling this setting will tell CodeIgniter to look for a Composer
| package auto-loader script in application/vendor/autoload.php.
|
|	$config['composer_autoload'] = TRUE;
|
| Or if you have your vendor/ directory located somewhere else, you
| can opt to set a specific path as well:
|
|	$config['composer_autoload'] = '/path/to/vendor/autoload.php';
|
| For more information about Composer, please visit http://getcomposer.org/
|
| Note: This will NOT disable or override the CodeIgniter-specific
|	autoloading (application/config/autoload.php)
*/
$config['composer_autoload'] = FALSE;

/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify which characters are permitted within your URLs.
| When someone tries to submit a URL with disallowed characters they will
| get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| The configured value is actually a regular expression character group
| and it will be executed as: ! preg_match('/^[<permitted_uri_chars>]+$/i
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';

/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| By default CodeIgniter enables access to the $_GET array.  If for some
| reason you would like to disable it, set 'allow_get_array' to FALSE.
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string 'words' that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/
$config['allow_get_array'] = TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'in';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
|
| You can also pass an array with threshold levels to show individual error types
|
| 	array(2) = Debug Messages, without Error Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold'] = 1;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/logs/ directory. Use a full server path with trailing slash.
|
*/
$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| Log File Extension
|--------------------------------------------------------------------------
|
| The default filename extension for log files. The default 'php' allows for
| protecting the log files via basic scripting, when they are to be stored
| under a publicly accessible directory.
|
| Note: Leaving it blank will default to 'php'.
|
*/
$config['log_file_extension'] = '';

/*
|--------------------------------------------------------------------------
| Log File Permissions
|--------------------------------------------------------------------------
|
| The file system permissions to be applied on newly created log files.
|
| IMPORTANT: This MUST be an integer (no quotes) and you MUST use octal
|            integer notation (i.e. 0700, 0644, etc.)
*/
$config['log_file_permissions'] = 0644;

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Error Views Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/views/errors/ directory.  Use a full server path with trailing slash.
|
*/
$config['error_views_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/cache/ directory.  Use a full server path with trailing slash.
|
*/
$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Include Query String
|--------------------------------------------------------------------------
|
| Whether to take the URL query string into consideration when generating
| output cache files. Valid options are:
|
|	FALSE      = Disabled
|	TRUE       = Enabled, take all query parameters into account.
|	             Please be aware that this may result in numerous cache
|	             files generated for the same page over and over again.
|	array('q') = Enabled, but only take into account the specified list
|	             of query parameters.
|
*/
$config['cache_query_string'] = FALSE;

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class, you must set an encryption key.
| See the user guide for more info.
|
| https://codeigniter.com/user_guide/libraries/encryption.html
|
*/
$config['encryption_key'] = '';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_driver'
|
|	The storage driver to use: files, database, redis, memcached
|
| 'sess_cookie_name'
|
|	The session cookie name, must contain only [0-9a-z_-] characters
|
| 'sess_expiration'
|
|	The number of SECONDS you want the session to last.
|	Setting to 0 (zero) means expire when the browser is closed.
|
| 'sess_save_path'
|
|	The location to save sessions to, driver dependent.
|
|	For the 'files' driver, it's a path to a writable directory.
|	WARNING: Only absolute paths are supported!
|
|	For the 'database' driver, it's a table name.
|	Please read up the manual for the format with other session drivers.
|
|	IMPORTANT: You are REQUIRED to set a valid save path!
|
| 'sess_match_ip'
|
|	Whether to match the user's IP address when reading the session data.
|
|	WARNING: If you're using the database driver, don't forget to update
|	         your session table's PRIMARY KEY when changing this setting.
|
| 'sess_time_to_update'
|
|	How many seconds between CI regenerating the session ID.
|
| 'sess_regenerate_destroy'
|
|	Whether to destroy session data associated with the old session ID
|	when auto-regenerating the session ID. When set to FALSE, the data
|	will be later deleted by the garbage collector.
|
| Other session cookie settings are shared with the rest of the application,
| except for 'cookie_prefix' and 'cookie_httponly', which are ignored here.
|
*/
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = NULL;
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix'   = Set a cookie name prefix if you need to avoid collisions
| 'cookie_domain'   = Set to .your-domain.com for site-wide cookies
| 'cookie_path'     = Typically will be a forward slash
| 'cookie_secure'   = Cookie will only be set if a secure HTTPS connection exists.
| 'cookie_httponly' = Cookie will only be accessible via HTTP(S) (no javascript)
|
| Note: These settings (with the exception of 'cookie_prefix' and
|       'cookie_httponly') will also affect sessions.
|
*/
$config['cookie_prefix'] = '';
$config['cookie_domain'] = '';
$config['cookie_path'] = '/';
$config['cookie_secure'] = FALSE;
$config['cookie_httponly'] = FALSE;

/*
|--------------------------------------------------------------------------
| Standardize newlines
|--------------------------------------------------------------------------
|
| Determines whether to standardize newline characters in input data,
| meaning to replace \r\n, \r, \n occurrences with the PHP_EOL value.
|
| This is particularly useful for portability between UNIX-based OSes,
| (usually \n) and Windows (\r\n).
|
*/
$config['standardize_newlines'] = FALSE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
| WARNING: This feature is DEPRECATED and currently available only
|          for backwards compatibility purposes!
|
*/
$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name' = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_expire' = The number in seconds the token should expire.
| 'csrf_regenerate' = Regenerate token on every submission
| 'csrf_exclude_uris' = Array of URIs which ignore CSRF checks
*/
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| Only used if zlib.output_compression is turned off in your php.ini.
| Please do not use it together with httpd-level output compression.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not 'echo' any values with compression enabled.
|
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Student Time Reference
|--------------------------------------------------------------------------
|
| Options are 'local' or any PHP supported timezone. This preference tells
| the system whether to use your server's local time as the master 'now'
| reference, or convert it to the configured one timezone. See the 'date
| helper' page of the user guide for information regarding date handling.
|
*/
$config['time_reference'] = 'local';

/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
| Note: You need to have eval() enabled for this to work.
|
*/
$config['rewrite_short_tags'] = FALSE;

/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy
| IP addresses from which CodeIgniter should trust headers such as
| HTTP_X_FORWARDED_FOR and HTTP_CLIENT_IP in order to properly identify
| the visitor's IP address.
|
| You can use both an array or a comma-separated list of proxy addresses,
| as well as specifying whole subnets. Here are a few examples:
|
| Comma-separated:	'10.0.1.200,192.168.5.0/24'
| Array:		array('10.0.1.200', '192.168.5.0/24')
*/
$config['proxy_ips'] = '';
