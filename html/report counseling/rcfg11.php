<?php

// PHP Report Maker 11 - configuration
// Relative path

if (!isset($EWR_RELATIVE_PATH)) $EWR_RELATIVE_PATH = "";

// Debug
define("EWR_DEBUG_ENABLED", FALSE, TRUE); // True to debug
if (EWR_DEBUG_ENABLED) {
	@ini_set("display_errors", "1"); // Display errors
	error_reporting(E_ALL ^ E_NOTICE); // Report all errors except E_NOTICE
}
define("EWR_PROJECT_ID", "{234B495E-E8C1-4FF1-B18B-170E747447B8}", TRUE); // Project ID
define("EWR_PROJECT_NAME", "project1", TRUE); // Project Name
define("EWR_CONFIG_FILE_FOLDER", EWR_PROJECT_NAME . "", TRUE); // Config file folder
define("EWR_LOWERCASE_OUTPUT_FILE_NAME", FALSE, TRUE); // Lowercase output file name
define("EWR_IS_WINDOWS", (strtolower(substr(PHP_OS, 0, 3)) === 'win'), TRUE); // Is Windows OS
define("EWR_IS_PHP5", version_compare(PHP_VERSION, "5.4.0") >= 0, TRUE); // Is PHP 5.4 or later
if (!EWR_IS_PHP5) die("This script requires PHP 5.4. You are running " . phpversion() . ".");
define("EWR_PATH_DELIMITER", ((EWR_IS_WINDOWS) ? "\\" : "/"), TRUE); // Path delimiter
define("EWR_FONT_NAME", "Verdana", TRUE);
define("EWR_FONT_SIZE", 14, TRUE);
$EWR_BODY_CLASS = "hold-transition skin-red";
$EWR_RESET_HEIGHT = TRUE; // Reset layout height

// Set up font path
$EWR_FONT_PATH = realpath('./phprptfont');

// External JavaScripts
$EWR_JAVASCRIPT_FILES = array();

// External StyleSheets
$EWR_STYLESHEET_FILES = array();

// Language settings
define("EWR_LANGUAGE_FOLDER", $EWR_RELATIVE_PATH . "phprptlang/", TRUE);
$EWR_LANGUAGE_FILE = array();
$EWR_LANGUAGE_FILE[] = array("en", "", "english.xml");
define("EWR_LANGUAGE_DEFAULT_ID", "en", TRUE);
define("EWR_SESSION_LANGUAGE_ID", EWR_PROJECT_NAME . "_LanguageId", TRUE); // Language ID
define("EWR_LOCALE_FOLDER", $EWR_RELATIVE_PATH . "phprptlocale/", TRUE);
if (!function_exists('xml_parser_create') && !class_exists("DOMDocument")) die("This script requires PHP XML Parser or DOM.");
define('EWR_USE_DOM_XML', ((!function_exists('xml_parser_create') && class_exists("DOMDocument")) || FALSE), TRUE);

// Page Token
define("EWR_TOKEN_NAME", "token", TRUE);
define("EWR_SESSION_TOKEN", EWR_PROJECT_NAME . "_Token", TRUE);

// Authentication configuration for Google/Facebook
$EWR_AUTH_CONFIG = array(
	"providers" => array(
		"Google" => array(
			"enabled" => false,
			"keys" => array("id" => "", "secret" => "")
		),
		"Facebook" => array(
			"enabled" => false,
			"keys" => array("id" => "", "secret" => ""),
			"trustForwarded" => FALSE
		)
	),
	"debug_mode" => FALSE,
	"debug_file" => "" // Path to file writable by the web server. Required if 'debug_mode' is not false
);

// Database connection info
if (!defined("EW_USE_ADODB"))
	define("EW_USE_ADODB", FALSE, TRUE); // Use ADOdb
if (!defined("EW_USE_MYSQLI"))
	define('EW_USE_MYSQLI', extension_loaded("mysqli"), TRUE); // Use MySQLi
if (!defined("EW_USE_MSSQL_NATIVE"))
	define("EW_USE_MSSQL_NATIVE", FALSE, TRUE); // Use ADOdb "mssqlnative" driver for MSSQL
$EWR_CONN["DB"] = array("conn" => NULL, "id" => "DB", "type" => "MYSQL", "host" => "localhost", "port" => 3306, "user" => "root", "pass" => "", "db" => "g&csms_db", "qs" => "`", "qe" => "`");
$EWR_CONN[0] = &$EWR_CONN["DB"];

// Set up database error function
$EWR_ERROR_FN = 'ewr_ErrorFn';

// ADODB (Access/SQL Server)
define("EWR_CODEPAGE", 0, TRUE); // Code page
define("EWR_CHARSET", "", TRUE); // Project charset
define("EWR_DBMSNAME", 'MySQL', TRUE); // DBMS Name
define("EWR_IS_MSACCESS", FALSE, TRUE); // Access
define("EWR_IS_MSSQL", FALSE, TRUE); // SQL Server
define("EWR_IS_MYSQL", TRUE, TRUE); // MySQL
define("EWR_IS_POSTGRESQL", FALSE, TRUE); // PostgreSQL
define("EWR_IS_ORACLE", FALSE, TRUE); // Oracle
if (!EWR_IS_WINDOWS && (EWR_IS_MSACCESS || EWR_IS_MSSQL))
	die("Microsoft Access or SQL Server is supported on Windows server only.");
define("EWR_DB_QUOTE_START", "`", TRUE);
define("EWR_DB_QUOTE_END", "`", TRUE);

// Remove XSS
define("EWR_REMOVE_XSS", TRUE, TRUE);
$EWR_XSS_ARRAY = array('javascript', 'vbscript', 'expression', '<applet', '<meta', '<xml', '<blink', '<link', '<style', '<script', '<embed', '<object', '<iframe', '<frame', '<frameset', '<ilayer', '<layer', '<bgsound', '<title', '<base',
'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

// Check Token
define("EWR_CHECK_TOKEN", TRUE, TRUE); // Check post token

// Session timeout time
define("EWR_SESSION_TIMEOUT", 0, TRUE); // Session timeout time (minutes)

// Session keep alive interval
define("EWR_SESSION_KEEP_ALIVE_INTERVAL", 0, TRUE); // Session keep alive interval (seconds)
define("EWR_SESSION_TIMEOUT_COUNTDOWN", 60, TRUE); // Session timeout count down interval (seconds)

// General
$EWR_ROOT_RELATIVE_PATH = "."; // Relative path of app root
define("EWR_UNFORMAT_YEAR", 50, TRUE); // Unformat year
define("EWR_RANDOM_KEY", '38Q1slTKjyE3oeuO', TRUE); // Random key for encryption
define("EWR_PROJECT_STYLESHEET_FILENAME", "phprptcss/project1.css", TRUE); // Project stylesheet file name
define("EWR_CHART_WIDTH", 600, TRUE);
define("EWR_CHART_HEIGHT", 500, TRUE);
define("EWR_CHART_SHOW_BLANK_SERIES", FALSE, TRUE); // Show blank series
define("EWR_CHART_SHOW_ZERO_IN_STACK_CHART", FALSE, TRUE); // Show zero in stack chart

// Drill down setting
define("EWR_USE_DRILLDOWN_PANEL", TRUE, TRUE); // Use popup panel for drill down
/**
 * Character encoding
 * Note: If you use non English languages, you need to set character encoding
 * for some features. Make sure either iconv functions or multibyte string
 * functions are enabled and your encoding is supported. See PHP manual for
 * details.
 */
define("EWR_ENCODING", "", TRUE); // Character encoding
define("EWR_IS_DOUBLE_BYTE", in_array(EWR_ENCODING, array("GBK", "BIG5", "SHIFT_JIS")), TRUE); // Double-byte character encoding
/**
 * MySQL charset (for SET NAMES statement, not used by default)
 * Note: Read http://dev.mysql.com/doc/refman/5.0/en/charset-connection.html
 * before using this setting.
 */
define("EWR_MYSQL_CHARSET", "", TRUE);
/**
 * Password (MD5 and case-sensitivity)
 * Note: If you enable MD5 password, make sure that the passwords in your
 * user table are stored as MD5 hash (32-character hexadecimal number) of the
 * clear text password. If you also use case-insensitive password, convert the
 * clear text passwords to lower case first before calculating MD5 hash.
 * Otherwise, existing users will not be able to login.
 */
define("EWR_ENCRYPTED_PASSWORD", FALSE, TRUE); // Use encrypted password
define("EWR_CASE_SENSITIVE_PASSWORD", FALSE, TRUE); // Case-sensitive password

// Use responsive layout
$EWR_USE_RESPONSIVE_LAYOUT = TRUE;

// Use css flip
$EWR_CSS_FLIP = FALSE;
$EWR_RTL_LANGUAGES = array("ar", "fa", "he", "iw", "ug", "ur");
/**
 * Locale settings
 * Note: DO NOT CHANGE THE FOLLOWING $EWR_* VARIABLES!
 * If you want to use custom settings, customize the locale files for ewr_FormatCurrency/Number/Percent functions.
 * Also read http://www.php.net/localeconv for description of the constants
*/
$EWR_DECIMAL_POINT = ".";
$EWR_THOUSANDS_SEP = ",";
$EWR_CURRENCY_SYMBOL = "$";
$EWR_MON_DECIMAL_POINT = ".";
$EWR_MON_THOUSANDS_SEP = ",";
$EWR_POSITIVE_SIGN = "";
$EWR_NEGATIVE_SIGN = "-";
$EWR_FRAC_DIGITS = 2;
$EWR_P_CS_PRECEDES = 1;
$EWR_P_SEP_BY_SPACE = 0;
$EWR_N_CS_PRECEDES = 1;
$EWR_N_SEP_BY_SPACE = 0;
$EWR_P_SIGN_POSN = 1;
$EWR_N_SIGN_POSN = 1;
$EWR_DATE_SEPARATOR = "/";
$EWR_TIME_SEPARATOR = ":";
$EWR_DATE_FORMAT = "yyyy/mm/dd";
$EWR_DATE_FORMAT_ID = 5;
$EWR_TIME_ZONE = "GMT";
$EWR_LOCALE = array("decimal_point" => &$EWR_DECIMAL_POINT,
	"thousands_sep" => &$EWR_THOUSANDS_SEP,
	"currency_symbol" => &$EWR_CURRENCY_SYMBOL,
	"mon_decimal_point" => &$EWR_MON_DECIMAL_POINT,
	"mon_thousands_sep" => &$EWR_MON_THOUSANDS_SEP,
	"positive_sign" => &$EWR_POSITIVE_SIGN,
	"negative_sign" => &$EWR_NEGATIVE_SIGN,
	"frac_digits" => &$EWR_FRAC_DIGITS,
	"p_cs_precedes" => &$EWR_P_CS_PRECEDES,
	"p_sep_by_space" => &$EWR_P_SEP_BY_SPACE,
	"n_cs_precedes" => &$EWR_N_CS_PRECEDES,
	"n_sep_by_space" => &$EWR_N_SEP_BY_SPACE,
	"p_sign_posn" => &$EWR_P_SIGN_POSN,
	"n_sign_posn" => &$EWR_N_SIGN_POSN,
	"date_sep" => &$EWR_DATE_SEPARATOR,
	"time_sep" => &$EWR_TIME_SEPARATOR,
	"date_format" => &$EWR_DATE_FORMAT,
	"time_zone" => &$EWR_TIME_ZONE
);

// Set default time zone
date_default_timezone_set($EWR_TIME_ZONE);

// Filter
define("EWR_SHOW_CURRENT_FILTER", FALSE, TRUE); // True to show current filter
define("EWR_SHOW_DRILLDOWN_FILTER", TRUE, TRUE); // True to show drill down filter

// Session names
define("EWR_SESSION_STATUS", EWR_PROJECT_NAME . "_status", TRUE); // Login Status
define("EWR_SESSION_USER_NAME", EWR_SESSION_STATUS . "_UserName", TRUE); // User Name
define("EWR_SESSION_USER_LOGIN_TYPE", EWR_SESSION_STATUS . "_UserLoginType", TRUE); // User login type
define("EWR_SESSION_USER_ID", EWR_SESSION_STATUS . "_UserID", TRUE); // User ID
define("EWR_SESSION_USER_PROFILE", EWR_SESSION_STATUS . "_UserProfile", TRUE); // User profile
define("EWR_SESSION_USER_LEVEL_ID", EWR_SESSION_STATUS . "_UserLevel", TRUE); // User Level ID
define("EWR_SESSION_USER_LEVEL", EWR_SESSION_STATUS . "_UserLevelValue", TRUE); // User Level
define("EWR_SESSION_PARENT_USER_ID", EWR_SESSION_STATUS . "_ParentUserID", TRUE); // Parent User ID
define("EWR_SESSION_SYSTEM_ADMIN", EWR_PROJECT_NAME . "_SysAdmin", TRUE); // System Admin
define("EWR_SESSION_AR_USER_LEVEL", EWR_PROJECT_NAME . "_arUserLevel", TRUE); // User Level Array
define("EWR_SESSION_AR_USER_LEVEL_PRIV", EWR_PROJECT_NAME . "_arUserLevelPriv", TRUE); // User Level Privilege Array
define("EWR_SESSION_MESSAGE", EWR_PROJECT_NAME . "_Message", TRUE); // System Message
define("EWR_SESSION_FAILURE_MESSAGE", EWR_PROJECT_NAME . "_Failure_Message", TRUE); // System error message
define("EWR_SESSION_SUCCESS_MESSAGE", EWR_PROJECT_NAME . "_Success_Message", TRUE); // System success message
define("EWR_SESSION_WARNING_MESSAGE", EWR_PROJECT_NAME . "_Warning_Message", TRUE); // Warning message

// Hard-coded admin
define("EWR_ADMIN_USER_NAME", "", TRUE);
define("EWR_ADMIN_PASSWORD", "", TRUE);
define("EWR_USE_CUSTOM_LOGIN", TRUE, TRUE); // Use custom login
define("EWR_ALLOW_LOGIN_BY_URL", FALSE, TRUE); // Allow login by URL
define("EWR_ALLOW_LOGIN_BY_SESSION", FALSE, TRUE); // Allow login by session variables
define("EWR_PHPASS_ITERATION_COUNT_LOG2", "[10,8]", TRUE); // Note: Use JSON array syntax
define("EWR_PASSWORD_HASH", FALSE, TRUE); // Use PHP 5.5+ password hashing functions

// User admin
define("EWR_LOGIN_SELECT_SQL", "", TRUE);

// User table filters
// User level constants

define("EWR_ALLOW_LIST", 8, TRUE); // List
define("EWR_ALLOW_REPORT", 8, TRUE); // Report
define("EWR_ALLOW_ADMIN", 16, TRUE); // Admin

// User id constants
define("EWR_USER_ID_IS_HIERARCHICAL", TRUE, TRUE); // Hierarchical user id

// Save report on server for file output
define("EWR_REPORT_SAVE_OUTPUT_ON_SERVER", FALSE, TRUE); // Change to TRUE to save on server

// Table level constants
define("EWR_TABLE_PREFIX", "||PHPReportMaker||", TRUE);
define("EWR_TABLE_PREFIX_OLD", "||PHPReportMaker||", TRUE);
define("EWR_TABLE_GROUP_PER_PAGE", "grpperpage", TRUE);
define("EWR_TABLE_START_GROUP", "start", TRUE);
define("EWR_TABLE_ORDER_BY", "order", TRUE);
define("EWR_TABLE_ORDER_BY_TYPE", "ordertype", TRUE);
define("EWR_TABLE_SORT", "sort", TRUE); // Table sort
define("EWR_TABLE_SORTCHART", "sortc", TRUE); // Table sort chart
define("EWR_TABLE_MASTER_TABLE", "mastertable", TRUE); // Master table
define("EWR_TABLE_PAGE_NO", "pageno", TRUE); // Page number

// Data types
define("EWR_DATATYPE_NONE", 0, TRUE);
define("EWR_DATATYPE_NUMBER", 1, TRUE);
define("EWR_DATATYPE_DATE", 2, TRUE);
define("EWR_DATATYPE_STRING", 3, TRUE);
define("EWR_DATATYPE_BOOLEAN", 4, TRUE);
define("EWR_DATATYPE_MEMO", 5, TRUE);
define("EWR_DATATYPE_BLOB", 6, TRUE);
define("EWR_DATATYPE_TIME", 7, TRUE);
define("EWR_DATATYPE_GUID", 8, TRUE);
define("EWR_DATATYPE_OTHER", 9, TRUE);

// Row types
define("EWR_ROWTYPE_DETAIL", 1, TRUE); // Row type detail
define("EWR_ROWTYPE_TOTAL", 2, TRUE); // Row type group summary

// Row total types
define("EWR_ROWTOTAL_GROUP", 1, TRUE); // Page summary
define("EWR_ROWTOTAL_PAGE", 2, TRUE); // Page summary
define("EWR_ROWTOTAL_GRAND", 3, TRUE); // Grand summary

// Row total sub types
define("EWR_ROWTOTAL_HEADER", 0, TRUE); // Header
define("EWR_ROWTOTAL_FOOTER", 1, TRUE); // Footer
define("EWR_ROWTOTAL_SUM", 2, TRUE); // SUM
define("EWR_ROWTOTAL_AVG", 3, TRUE); // AVG
define("EWR_ROWTOTAL_MIN", 4, TRUE); // MIN
define("EWR_ROWTOTAL_MAX", 5, TRUE); // MAX
define("EWR_ROWTOTAL_CNT", 6, TRUE); // CNT

// Empty/Null/Not Null/Init/all values
define("EWR_EMPTY_VALUE", "##empty##", TRUE);
define("EWR_NULL_VALUE", "##null##", TRUE);
define("EWR_NOT_NULL_VALUE", "##notnull##", TRUE);
define("EWR_INIT_VALUE", "##init##", TRUE);
define("EWR_ALL_VALUE", "##all##", TRUE);

// Boolean values for ENUM('Y'/'N') or ENUM(1/0)
define("EWR_TRUE_STRING", "'Y'", TRUE);
define("EWR_FALSE_STRING", "'N'", TRUE);

// Use token in URL (reserved, not used, do NOT change!)
define("EWR_USE_TOKEN_IN_URL", FALSE, TRUE);

// Auto hide pager
define("EWR_AUTO_HIDE_PAGER", TRUE, TRUE);

// Email
define("EWR_SMTP_SERVER", "localhost", TRUE); // SMTP server
define("EWR_SMTP_SERVER_PORT", 25, TRUE); // SMTP server port
define("EWR_SMTP_SECURE_OPTION", "", TRUE);
define("EWR_SMTP_SERVER_USERNAME", "", TRUE); // SMTP server user name
define("EWR_SMTP_SERVER_PASSWORD", "", TRUE); // SMTP server password
define("EWR_MAX_EMAIL_RECIPIENT", 3, TRUE);
define("EWR_MAX_EMAIL_SENT_COUNT", 3, TRUE);
define("EWR_MAX_EMAIL_SENT_PERIOD", 20, TRUE);
define("EWR_EXPORT_EMAIL_COUNTER", EWR_SESSION_STATUS . "_EmailCounter", TRUE);
define("EWR_EMAIL_CHARSET", EWR_CHARSET, TRUE); // Email charset
define("EWR_EMAIL_WRITE_LOG", TRUE, TRUE); // Write to log file
define("EWR_EMAIL_LOG_SIZE_LIMIT", 255, TRUE); // Email log field size limit
define("EWR_EMAIL_WRITE_LOG_TO_DATABASE", FALSE, TRUE); // Write email log to database
define("EWR_EMAIL_LOG_TABLE_DBID", "DB", TRUE); // Email log table dbid
define("EWR_EMAIL_LOG_TABLE_NAME", "", TRUE); // Email log table name
define("EWR_EMAIL_LOG_FIELD_NAME_DATETIME", "", TRUE); // Email log DateTime field name
define("EWR_EMAIL_LOG_FIELD_NAME_IP", "", TRUE); // Email log IP field name
define("EWR_EMAIL_LOG_FIELD_NAME_SENDER", "", TRUE); // Email log Sender field name
define("EWR_EMAIL_LOG_FIELD_NAME_RECIPIENT", "", TRUE); // Email log Recipient field name
define("EWR_EMAIL_LOG_FIELD_NAME_SUBJECT", "", TRUE); // Email log Subject field name
define("EWR_EMAIL_LOG_FIELD_NAME_MESSAGE", "", TRUE); // Email log Message field name

// Export records
$EWR_EXPORT = array(
	"email" => "ExportEmail",
	"print" => "ExportHtml",
	"html" => "ExportHtml",
	"word" => "ExportWord",
	"excel" => "ExportExcel",
	"pdf" => "ExportPdf"
);
define("EWR_USE_COLORBOX", TRUE, TRUE); // Use Colorbox
define("EWR_MULTIPLE_UPLOAD_SEPARATOR", ",", TRUE); // Multiple upload separator
define("EWR_FILE_URL", "rfile11.php", TRUE); // File accessor URL

// Remote file
$EWR_REMOTE_FILE_PATTERN = '/^((https?\:)?|ftps?\:|s3:)\/\//i';

// Full URL protocols ("http" or "https")
$EWR_FULL_URL_PROTOCOLS = array(
	"href" => "", // field hyperlink
	"export" => "", // export
	"genurl" => "", // generate url
	"chartexport" => "", // chart export handler
	"auth" => "" // OAuth base URL
);

// MIME types
$EWR_MIME_TYPES = array(
	"323" => "text/h323",
	"3g2" => "video/3gpp2",
	"3gp2" => "video/3gpp2",
	"3gp" => "video/3gpp",
	"3gpp" => "video/3gpp",
	"aac" => "audio/aac",
	"aaf" => "application/octet-stream",
	"aca" => "application/octet-stream",
	"accdb" => "application/msaccess",
	"accde" => "application/msaccess",
	"accdt" => "application/msaccess",
	"acx" => "application/internet-property-stream",
	"adt" => "audio/vnd.dlna.adts",
	"adts" => "audio/vnd.dlna.adts",
	"afm" => "application/octet-stream",
	"ai" => "application/postscript",
	"aif" => "audio/x-aiff",
	"aifc" => "audio/aiff",
	"aiff" => "audio/aiff",
	"appcache" => "text/cache-manifest",
	"application" => "application/x-ms-application",
	"art" => "image/x-jg",
	"asd" => "application/octet-stream",
	"asf" => "video/x-ms-asf",
	"asi" => "application/octet-stream",
	"asm" => "text/plain",
	"asr" => "video/x-ms-asf",
	"asx" => "video/x-ms-asf",
	"atom" => "application/atom+xml",
	"au" => "audio/basic",
	"avi" => "video/x-msvideo",
	"axs" => "application/olescript",
	"bas" => "text/plain",
	"bcpio" => "application/x-bcpio",
	"bin" => "application/octet-stream",
	"bmp" => "image/bmp",
	"c" => "text/plain",
	"cab" => "application/vnd.ms-cab-compressed",
	"calx" => "application/vnd.ms-office.calx",
	"cat" => "application/vnd.ms-pki.seccat",
	"cdf" => "application/x-cdf",
	"chm" => "application/octet-stream",
	"class" => "application/x-java-applet",
	"clp" => "application/x-msclip",
	"cmx" => "image/x-cmx",
	"cnf" => "text/plain",
	"cod" => "image/cis-cod",
	"cpio" => "application/x-cpio",
	"cpp" => "text/plain",
	"crd" => "application/x-mscardfile",
	"crl" => "application/pkix-crl",
	"crt" => "application/x-x509-ca-cert",
	"csh" => "application/x-csh",
	"css" => "text/css",
	"csv" => "application/octet-stream",
	"cur" => "application/octet-stream",
	"dcr" => "application/x-director",
	"deploy" => "application/octet-stream",
	"der" => "application/x-x509-ca-cert",
	"dib" => "image/bmp",
	"dir" => "application/x-director",
	"disco" => "text/xml",
	"dlm" => "text/dlm",
	"doc" => "application/msword",
	"docm" => "application/vnd.ms-word.document.macroEnabled.12",
	"docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
	"dot" => "application/msword",
	"dotm" => "application/vnd.ms-word.template.macroEnabled.12",
	"dotx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.template",
	"dsp" => "application/octet-stream",
	"dtd" => "text/xml",
	"dvi" => "application/x-dvi",
	"dvr-ms" => "video/x-ms-dvr",
	"dwf" => "drawing/x-dwf",
	"dwp" => "application/octet-stream",
	"dxr" => "application/x-director",
	"eml" => "message/rfc822",
	"emz" => "application/octet-stream",
	"eot" => "application/vnd.ms-fontobject",
	"eps" => "application/postscript",
	"etx" => "text/x-setext",
	"evy" => "application/envoy",
	"fdf" => "application/vnd.fdf",
	"fif" => "application/fractals",
	"fla" => "application/octet-stream",
	"flr" => "x-world/x-vrml",
	"flv" => "video/x-flv",
	"gif" => "image/gif",
	"gtar" => "application/x-gtar",
	"gz" => "application/x-gzip",
	"h" => "text/plain",
	"hdf" => "application/x-hdf",
	"hdml" => "text/x-hdml",
	"hhc" => "application/x-oleobject",
	"hhk" => "application/octet-stream",
	"hhp" => "application/octet-stream",
	"hlp" => "application/winhlp",
	"hqx" => "application/mac-binhex40",
	"hta" => "application/hta",
	"htc" => "text/x-component",
	"htm" => "text/html",
	"html" => "text/html",
	"htt" => "text/webviewhtml",
	"hxt" => "text/html",
	"ical" => "text/calendar",
	"icalendar" => "text/calendar",
	"ico" => "image/x-icon",
	"ics" => "text/calendar",
	"ief" => "image/ief",
	"ifb" => "text/calendar",
	"iii" => "application/x-iphone",
	"inf" => "application/octet-stream",
	"ins" => "application/x-internet-signup",
	"isp" => "application/x-internet-signup",
	"IVF" => "video/x-ivf",
	"jar" => "application/java-archive",
	"java" => "application/octet-stream",
	"jck" => "application/liquidmotion",
	"jcz" => "application/liquidmotion",
	"jfif" => "image/pjpeg",
	"jpb" => "application/octet-stream",
	"jpe" => "image/jpeg",
	"jpeg" => "image/jpeg",
	"jpg" => "image/jpeg",
	"js" => "application/javascript",
	"json" => "application/json",
	"jsx" => "text/jscript",
	"latex" => "application/x-latex",
	"lit" => "application/x-ms-reader",
	"lpk" => "application/octet-stream",
	"lsf" => "video/x-la-asf",
	"lsx" => "video/x-la-asf",
	"lzh" => "application/octet-stream",
	"m13" => "application/x-msmediaview",
	"m14" => "application/x-msmediaview",
	"m1v" => "video/mpeg",
	"m2ts" => "video/vnd.dlna.mpeg-tts",
	"m3u" => "audio/x-mpegurl",
	"m4a" => "audio/mp4",
	"m4v" => "video/mp4",
	"man" => "application/x-troff-man",
	"manifest" => "application/x-ms-manifest",
	"map" => "text/plain",
	"mdb" => "application/x-msaccess",
	"mdp" => "application/octet-stream",
	"me" => "application/x-troff-me",
	"mht" => "message/rfc822",
	"mhtml" => "message/rfc822",
	"mid" => "audio/mid",
	"midi" => "audio/mid",
	"mix" => "application/octet-stream",
	"mmf" => "application/x-smaf",
	"mno" => "text/xml",
	"mny" => "application/x-msmoney",
	"mov" => "video/quicktime",
	"movie" => "video/x-sgi-movie",
	"mp2" => "video/mpeg",
	"mp3" => "audio/mpeg",
	"mp4" => "video/mp4",
	"mp4v" => "video/mp4",
	"mpa" => "video/mpeg",
	"mpe" => "video/mpeg",
	"mpeg" => "video/mpeg",
	"mpg" => "video/mpeg",
	"mpp" => "application/vnd.ms-project",
	"mpv2" => "video/mpeg",
	"ms" => "application/x-troff-ms",
	"msi" => "application/octet-stream",
	"mso" => "application/octet-stream",
	"mvb" => "application/x-msmediaview",
	"mvc" => "application/x-miva-compiled",
	"nc" => "application/x-netcdf",
	"nsc" => "video/x-ms-asf",
	"nws" => "message/rfc822",
	"ocx" => "application/octet-stream",
	"oda" => "application/oda",
	"odc" => "text/x-ms-odc",
	"ods" => "application/oleobject",
	"oga" => "audio/ogg",
	"ogg" => "video/ogg",
	"ogv" => "video/ogg",
	"ogx" => "application/ogg",
	"one" => "application/onenote",
	"onea" => "application/onenote",
	"onetoc" => "application/onenote",
	"onetoc2" => "application/onenote",
	"onetmp" => "application/onenote",
	"onepkg" => "application/onenote",
	"osdx" => "application/opensearchdescription+xml",
	"otf" => "font/otf",
	"p10" => "application/pkcs10",
	"p12" => "application/x-pkcs12",
	"p7b" => "application/x-pkcs7-certificates",
	"p7c" => "application/pkcs7-mime",
	"p7m" => "application/pkcs7-mime",
	"p7r" => "application/x-pkcs7-certreqresp",
	"p7s" => "application/pkcs7-signature",
	"pbm" => "image/x-portable-bitmap",
	"pcx" => "application/octet-stream",
	"pcz" => "application/octet-stream",
	"pdf" => "application/pdf",
	"pfb" => "application/octet-stream",
	"pfm" => "application/octet-stream",
	"pfx" => "application/x-pkcs12",
	"pgm" => "image/x-portable-graymap",
	"pko" => "application/vnd.ms-pki.pko",
	"pma" => "application/x-perfmon",
	"pmc" => "application/x-perfmon",
	"pml" => "application/x-perfmon",
	"pmr" => "application/x-perfmon",
	"pmw" => "application/x-perfmon",
	"png" => "image/png",
	"pnm" => "image/x-portable-anymap",
	"pnz" => "image/png",
	"pot" => "application/vnd.ms-powerpoint",
	"potm" => "application/vnd.ms-powerpoint.template.macroEnabled.12",
	"potx" => "application/vnd.openxmlformats-officedocument.presentationml.template",
	"ppam" => "application/vnd.ms-powerpoint.addin.macroEnabled.12",
	"ppm" => "image/x-portable-pixmap",
	"pps" => "application/vnd.ms-powerpoint",
	"ppsm" => "application/vnd.ms-powerpoint.slideshow.macroEnabled.12",
	"ppsx" => "application/vnd.openxmlformats-officedocument.presentationml.slideshow",
	"ppt" => "application/vnd.ms-powerpoint",
	"pptm" => "application/vnd.ms-powerpoint.presentation.macroEnabled.12",
	"pptx" => "application/vnd.openxmlformats-officedocument.presentationml.presentation",
	"prf" => "application/pics-rules",
	"prm" => "application/octet-stream",
	"prx" => "application/octet-stream",
	"ps" => "application/postscript",
	"psd" => "application/octet-stream",
	"psm" => "application/octet-stream",
	"psp" => "application/octet-stream",
	"pub" => "application/x-mspublisher",
	"qt" => "video/quicktime",
	"qtl" => "application/x-quicktimeplayer",
	"qxd" => "application/octet-stream",
	"ra" => "audio/x-pn-realaudio",
	"ram" => "audio/x-pn-realaudio",
	"rar" => "application/octet-stream",
	"ras" => "image/x-cmu-raster",
	"rf" => "image/vnd.rn-realflash",
	"rgb" => "image/x-rgb",
	"rm" => "application/vnd.rn-realmedia",
	"rmi" => "audio/mid",
	"roff" => "application/x-troff",
	"rpm" => "audio/x-pn-realaudio-plugin",
	"rtf" => "application/rtf",
	"rtx" => "text/richtext",
	"scd" => "application/x-msschedule",
	"sct" => "text/scriptlet",
	"sea" => "application/octet-stream",
	"setpay" => "application/set-payment-initiation",
	"setreg" => "application/set-registration-initiation",
	"sgml" => "text/sgml",
	"sh" => "application/x-sh",
	"shar" => "application/x-shar",
	"sit" => "application/x-stuffit",
	"sldm" => "application/vnd.ms-powerpoint.slide.macroEnabled.12",
	"sldx" => "application/vnd.openxmlformats-officedocument.presentationml.slide",
	"smd" => "audio/x-smd",
	"smi" => "application/octet-stream",
	"smx" => "audio/x-smd",
	"smz" => "audio/x-smd",
	"snd" => "audio/basic",
	"snp" => "application/octet-stream",
	"spc" => "application/x-pkcs7-certificates",
	"spl" => "application/futuresplash",
	"spx" => "audio/ogg",
	"src" => "application/x-wais-source",
	"ssm" => "application/streamingmedia",
	"sst" => "application/vnd.ms-pki.certstore",
	"stl" => "application/vnd.ms-pki.stl",
	"sv4cpio" => "application/x-sv4cpio",
	"sv4crc" => "application/x-sv4crc",
	"svg" => "image/svg+xml",
	"svgz" => "image/svg+xml",
	"swf" => "application/x-shockwave-flash",
	"t" => "application/x-troff",
	"tar" => "application/x-tar",
	"tcl" => "application/x-tcl",
	"tex" => "application/x-tex",
	"texi" => "application/x-texinfo",
	"texinfo" => "application/x-texinfo",
	"tgz" => "application/x-compressed",
	"thmx" => "application/vnd.ms-officetheme",
	"thn" => "application/octet-stream",
	"tif" => "image/tiff",
	"tiff" => "image/tiff",
	"toc" => "application/octet-stream",
	"tr" => "application/x-troff",
	"trm" => "application/x-msterminal",
	"ts" => "video/vnd.dlna.mpeg-tts",
	"tsv" => "text/tab-separated-values",
	"ttc" => "application/x-font-ttf",
	"ttf" => "application/x-font-ttf",
	"tts" => "video/vnd.dlna.mpeg-tts",
	"txt" => "text/plain",
	"u32" => "application/octet-stream",
	"uls" => "text/iuls",
	"ustar" => "application/x-ustar",
	"vbs" => "text/vbscript",
	"vcf" => "text/x-vcard",
	"vcs" => "text/plain",
	"vdx" => "application/vnd.ms-visio.viewer",
	"vml" => "text/xml",
	"vsd" => "application/vnd.visio",
	"vss" => "application/vnd.visio",
	"vst" => "application/vnd.visio",
	"vsto" => "application/x-ms-vsto",
	"vsw" => "application/vnd.visio",
	"vsx" => "application/vnd.visio",
	"vtx" => "application/vnd.visio",
	"wav" => "audio/wav",
	"wax" => "audio/x-ms-wax",
	"wbmp" => "image/vnd.wap.wbmp",
	"wcm" => "application/vnd.ms-works",
	"wdb" => "application/vnd.ms-works",
	"webm" => "video/webm",
	"webp" => "image/webp",
	"wks" => "application/vnd.ms-works",
	"wm" => "video/x-ms-wm",
	"wma" => "audio/x-ms-wma",
	"wmd" => "application/x-ms-wmd",
	"wmf" => "application/x-msmetafile",
	"wml" => "text/vnd.wap.wml",
	"wmlc" => "application/vnd.wap.wmlc",
	"wmls" => "text/vnd.wap.wmlscript",
	"wmlsc" => "application/vnd.wap.wmlscriptc",
	"wmp" => "video/x-ms-wmp",
	"wmv" => "video/x-ms-wmv",
	"wmx" => "video/x-ms-wmx",
	"wmz" => "application/x-ms-wmz",
	"woff" => "application/font-woff",
	"woff2" => "application/font-woff2",
	"wps" => "application/vnd.ms-works",
	"wri" => "application/x-mswrite",
	"wrl" => "x-world/x-vrml",
	"wrz" => "x-world/x-vrml",
	"wsdl" => "text/xml",
	"wtv" => "video/x-ms-wtv",
	"wvx" => "video/x-ms-wvx",
	"x" => "application/directx",
	"xaf" => "x-world/x-vrml",
	"xaml" => "application/xaml+xml",
	"xap" => "application/x-silverlight-app",
	"xbap" => "application/x-ms-xbap",
	"xbm" => "image/x-xbitmap",
	"xdr" => "text/plain",
	"xht" => "application/xhtml+xml",
	"xhtml" => "application/xhtml+xml",
	"xla" => "application/vnd.ms-excel",
	"xlam" => "application/vnd.ms-excel.addin.macroEnabled.12",
	"xlc" => "application/vnd.ms-excel",
	"xlm" => "application/vnd.ms-excel",
	"xls" => "application/vnd.ms-excel",
	"xlsb" => "application/vnd.ms-excel.sheet.binary.macroEnabled.12",
	"xlsm" => "application/vnd.ms-excel.sheet.macroEnabled.12",
	"xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
	"xlt" => "application/vnd.ms-excel",
	"xltm" => "application/vnd.ms-excel.template.macroEnabled.12",
	"xltx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.template",
	"xlw" => "application/vnd.ms-excel",
	"xml" => "text/xml",
	"xof" => "x-world/x-vrml",
	"xpm" => "image/x-xpixmap",
	"xps" => "application/vnd.ms-xpsdocument",
	"xsd" => "text/xml",
	"xsf" => "text/xml",
	"xsl" => "text/xml",
	"xslt" => "text/xml",
	"xsn" => "application/octet-stream",
	"xtp" => "application/octet-stream",
	"xwd" => "image/x-xwindowdump",
	"z" => "application/x-compress",
	"zip" => "application/x-zip-compressed"
);

// Image resize
define("EWR_UPLOADED_FILE_MODE", 0666, TRUE); // Uploaded file mode
define("EWR_UPLOAD_TMP_PATH", "", TRUE); // User upload temp path (relative to app root) e.g. "tmp/"
define("EWR_UPLOAD_DEST_PATH", "files/", TRUE); // Upload destination path (relative to app root)
define("EWR_UPLOAD_HREF_PATH", "", TRUE); // Upload file href path (for download)
define("EWR_THUMBNAIL_DEFAULT_WIDTH", 0, TRUE); // Thumbnail default width
define("EWR_THUMBNAIL_DEFAULT_HEIGHT", 0, TRUE); // Thumbnail default height
define("EWR_THUMBNAIL_DEFAULT_QUALITY", 100, TRUE); // Thumbnail default qualtity (JPEG)
define("EWR_IMAGE_ALLOWED_FILE_EXT", "gif,jpg,png,bmp", TRUE); // Allowed file extensions for images
define("EWR_DOWNLOAD_ALLOWED_FILE_EXT", "pdf,xls,doc,xlsx,docx", TRUE); // Allowed file extensions for download (non-image)
define("EWR_ENCRYPT_FILE_PATH", TRUE, TRUE); // Encrypt file path
$EWR_THUMBNAIL_CLASS = "crThumbnail";
define("EWR_REDUCE_IMAGE_ONLY", TRUE, TRUE);
define("EWR_KEEP_ASPECT_RATIO", FALSE, TRUE);
$EWR_RESIZE_OPTIONS = array("keepAspectRatio" => EWR_KEEP_ASPECT_RATIO, "resizeUp" => !EWR_REDUCE_IMAGE_ONLY, "jpegQuality" => EWR_THUMBNAIL_DEFAULT_QUALITY);

// Use ILIKE for PostgreSql
define("EWR_USE_ILIKE_FOR_POSTGRESQL", TRUE, TRUE);

// Use collation for MySQL
define("EWR_LIKE_COLLATION_FOR_MYSQL", "", TRUE);

// Use collation for MsSQL
define("EWR_LIKE_COLLATION_FOR_MSSQL", "", TRUE);

// Comma separated values delimiter
$EWR_CSV_DELIMITER = ",";

// Use mobile menu
$EWR_USE_MOBILE_MENU = FALSE;

// Float fields default decimal position
define("EWR_DEFAULT_DECIMAL_PRECISION", 2, TRUE);

// Quick search
define("EWR_BASIC_SEARCH_IGNORE_PATTERN", "/[\?,\.\^\*\(\)\[\]\\\"]/", TRUE); // Ignore special characters

// Validate option
define("EWR_CLIENT_VALIDATE", TRUE, TRUE);
define("EWR_SERVER_VALIDATE", FALSE, TRUE);

// Auto suggest max entries
define("EWR_AUTO_SUGGEST_MAX_ENTRIES", 10, TRUE);

// Lookup filter value separator
define("EWR_LOOKUP_FILTER_VALUE_SEPARATOR", ",", TRUE);

// Checkbox and radio button groups
define("EWR_ITEM_TEMPLATE_CLASSNAME", "ewTemplate", TRUE);
define("EWR_ITEM_TABLE_CLASSNAME", "ewItemTable", TRUE);

// Cookies
define("EWR_COOKIE_EXPIRY_TIME", time() + 365*24*60*60, TRUE); // Change cookie expiry time here

// Use Custom Template in report
define("EWR_USE_CUSTOM_TEMPLATE", TRUE, TRUE);

// Page break content
define("EWR_EXPORT_PAGE_BREAK_CONTENT", "<div class=\"ewPageBreak\">&nbsp;</div>", TRUE);

// Page Title Style
define("EWR_PAGE_TITLE_STYLE", "Breadcrumbs", TRUE);

// Client variables
$EWR_CLIENT_VAR = array();
if (!isset($conn)) {

	// Common objects
	$conn = NULL; // Connection
	$rs = NULL; // Recordset
	$rsgrp = NULL; // Recordset
	$Page = NULL; // Page
	$OldPage = NULL; // Old Page
	$UserTable = NULL; // User table
	$UserTableConn = NULL; // User table connection
	$ReportOptions = array("ReportTypes" => array(), "UserNameList" => array()); // Report options
	$Table = NULL; // Main table
	$Security = NULL; // Security
	$UserProfile = NULL; // User profile

	// Current language
	$grLanguage = "";

	// Token
	$grToken = "";
}
if (!isset($ReportLanguage)) {
	$ReportLanguage = NULL; // Language
}

// Chart
$Chart = NULL;

// Used by header.php, export checking
$gsExport = ""; // *** DO NOT CHANGE, must match with PHPMaker
$gsCustomExport = ""; // *** DO NOT CHANGE, must match with PHPMaker
$gsExportFile = ""; // *** DO NOT CHANGE, must match with PHPMaker
$gsEmailContentType = ""; // *** DO NOT CHANGE, must match with PHPMaker

// Used by header.php/footer.php, skip header/footer checking
$gbSkipHeaderFooter = FALSE; // *** DO NOT CHANGE, must match with PHPMaker
$gbOldSkipHeaderFooter = $gbSkipHeaderFooter; // *** DO NOT CHANGE, must match with PHPMaker

// Dashboard report checking
$grDashboardReport = FALSE;

// Timer
$grTimer = NULL;
$grDrillDownInPanel = FALSE;

// Used by extended filter
$grFormError = "";

// Debug message
$grDebugMsg = "";
if (!isset($ADODB_OUTP)) $ADODB_OUTP = 'ewr_SetDebugMsg';

// Keep temp images name for PDF export for delete
$grTmpImages = array();

// Mobile detect
$MobileDetect = NULL;

// Breadcrumb
$ReportBreadcrumb = NULL;

// Login status
$ReportLoginStatus = array();
define("EWR_SESSION_BREADCRUMB", EWR_PROJECT_NAME . "_Breadcrumb", TRUE);
$EWR_NON_FUSIONCHARTS = FALSE; // For Gannt and Candlestick only
$EWR_NON_FUSIONCHARTS_PATH = "FusionChartsTrial/js/";
$EWR_USE_GOOGLE_CHARTS = FALSE; // Use Google charts
$EWR_USE_TIMELINE = FALSE; // Use Google Timeline instead of Gantt chart (Reserved, not used)

// Gantt charts
define("EWR_GANTT_INTERVAL_YEAR", 5, TRUE);
define("EWR_GANTT_INTERVAL_QUARTER", 4, TRUE);
define("EWR_GANTT_INTERVAL_MONTH", 3, TRUE);
define("EWR_GANTT_INTERVAL_WEEK", 2, TRUE);
define("EWR_GANTT_INTERVAL_DAY", 1, TRUE);
define("EWR_GANTT_INTERVAL_NONE", 0, TRUE);
define("EWR_GANTT_WEEK_START", 1, TRUE); // 0 (for Sunday) through 6 (for Saturday)
?>
<?php
$EWR_USE_SUBMENU_FOR_ROOT_HEADER = FALSE;
?>
<?php
define("EWR_PDF_STYLESHEET_FILENAME", "", TRUE); // Export PDF CSS styles
?>
<?php

// FusionCharts path (trial version)
$EWR_FUSIONCHARTS_PATH = "FusionChartsTrial/js/";
?>
