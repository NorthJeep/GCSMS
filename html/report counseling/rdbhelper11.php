<?php
if (!isset($EWR_RELATIVE_PATH)) $EWR_RELATIVE_PATH = "";
if (!isset($EWR_ERROR_FN)) $EWR_ERROR_FN = "ewr_ErrorFn";
if (!defined("EW_USE_ADODB")) define("EW_USE_ADODB", FALSE, TRUE); // Use ADOdb
if (!defined("EW_USE_MYSQLI")) define("EW_USE_MYSQLI", extension_loaded("mysqli"), TRUE); // Use MySQLi
?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php
/**
 * PHP Report Maker 10 database helper class
 */
if (!function_exists('ReportDbHelper')) {

	function &ReportDbHelper($dbid = "") {
		$dbclass = "crg26csms_db_db";
		$dbhelper = new $dbclass();
		return $dbhelper;
	}
}

class crg26csms_db_db extends crDbHelper {

	// Database connection info
	var $Host = '';
	var $Port = 3306;
	var $Username = 'root';
	var $Password = '';
	var $DbName = 'g&csms_db';
	var $CharSet = "";

	// ADODB (Access/SQL Server)
	var $CodePage = 0; // Code page

	// Database
	var $StartQuote = "`";
	var $EndQuote = "`";
	/**
	 * MySQL charset (for SET NAMES statement, not used by default)
	 * Note: Read http://dev.mysql.com/doc/refman/5.0/en/charset-connection.html
	 * before using this setting.
	 */
	var $MySqlCharset = "";

	// Connect to database
	function &Connect($info = NULL) {
		$GLOBALS["ADODB_FETCH_MODE"] = ADODB_FETCH_BOTH;
		$GLOBALS["ADODB_COUNTRECS"] = FALSE;
		if (EW_USE_ADODB) {
			if (EW_USE_MYSQLI)
				$conn = ADONewConnection('mysqli');
			else
				$conn = ADONewConnection('mysqlt');
		} else {
			$conn = new mysqlt_driver_ADOConnection();
		}
		$conn->debug = $this->Debug;
		$conn->debug_echo = FALSE;
		if (!$info) {
			$info = array("host" => $this->Host, "port" => $this->Port, "user" => $this->Username, "pass" => $this->Password, "db" => $this->DbName);
		}
		$conn->port = intval($info["port"]);

		// Database connecting event
		Database_Connecting($info);
		if ($this->Debug)
			$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$conn->Connect($info["host"], $info["user"], $info["pass"], $info["db"]);
		if ($this->MySqlCharset <> "")
			$conn->Execute("SET NAMES '" . $this->MySqlCharset . "'");
		$conn->raiseErrorFn = '';

		// Database connected event
		Database_Connected($conn);
		return $conn;
	}
}
if (!function_exists("Database_Connecting")) {

// Database Connecting event
function Database_Connecting(&$info) {

	// Example:
	//var_dump($info);
	// Assume the scripts are generated with connection info for local PC
	// if (ewr_CurrentUserIP() <> "127.0.0.1") { // not connecting to local PC
	// // connect to the production database
	// $info["host"] = "localhost";
	// $info["user"] = "xxx";
	// $info["pass"] = "yyy";
	// $info["db"] = "production_db";
	// }

}
}
if (!function_exists("Database_Connected")) {

// Database Connected event
function Database_Connected(&$conn) {

	// Example:
	//$conn->Execute("Your SQL");

}
}

// Abstract database helper class
abstract class crDbHelper {

	// Debug
	var $Debug = FALSE;

	// Language
	var $Language;

	// Connection
	var $Connection;

	// Constructor
	function __construct($langfolder = "", $langid = "", $info = NULL) {
		$args = func_get_args();
		if (count($args) == 1 && is_array($args[0])) { // $info only
			$langfolder = "";
			$langid = "";
			$info = $args[0];
		}

		// Debug
		if (defined("EWR_DEBUG_ENABLED"))
			$this->Debug = EWR_DEBUG_ENABLED;

		// Open connection
		if (!isset($this->Connection)) $this->Connection = $this->Connect($info);

		// Set up language object
		if ($langfolder <> "")
			$this->Language = new crLanguage($langfolder, $langid);
		elseif (isset($GLOBALS["ReportLanguage"]))
			$this->Language = &$GLOBALS["ReportLanguage"];
	}

	// Quote name
	function QuotedName($Name) {
		$Name = str_replace($this->EndQuote, $this->EndQuote . $this->EndQuote, $Name);
		return $this->StartQuote . $Name . $this->EndQuote;
	}

	// Executes the query, and returns all rows as JSON (utf-8)
	// options: header(bool), array(bool), firstonly(bool)
	function ExecuteJson($SQL, $options = NULL) {
		$ar = is_array($options) ? $options : array();
		if (is_bool($options)) // First only, backward compatibility
			$ar["firstonly"] = $options;
		$res = "false";
		$header = array_key_exists("header", $ar) && $ar["header"]; // Set header for JSON
		$array = array_key_exists("array", $ar) && $ar["array"];
		$firstonly = array_key_exists("firstonly", $ar) && $ar["firstonly"];
		if ($firstonly)
			$rows = array($this->ExecuteRow($SQL)); // All string data must be UTF-8 encoded
		else
			$rows = $this->ExecuteRows($SQL); // All string data must be UTF-8 encoded
		if (is_array($rows)) {
			$arOut = json_encode($rows);
			$res = ($firstonly) ? json_encode($arOut[0]) : json_encode($arOut);
		}
		if ($header)
			header("Content-Type: application/json; charset=utf-8");
		return $res;
	}

	// Execute UPDATE, INSERT, or DELETE statements
	function Execute($SQL, $fn = NULL) {
		$conn = &$this->Connection;
		if ($this->Debug)
			$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rs = $conn->Execute($SQL);
		$conn->raiseErrorFn = '';
		if (is_callable($fn) && $rs) {
			while (!$rs->EOF) {
				$fn($rs->fields);
				$rs->MoveNext();
			}
			$rs->MoveFirst();
		}
		return $rs;
	}

	// Executes the query, and returns the first column of the first row
	function ExecuteScalar($SQL) {
		$res = FALSE;
		$rs = $this->LoadRecordset($SQL);
		if ($rs && !$rs->EOF && $rs->FieldCount() > 0) {
			$res = $rs->fields[0];
			$rs->Close();
		}
		return $res;
	}

	// Executes the query, and returns the first row
	function ExecuteRow($SQL) {
		$res = FALSE;
		$rs = $this->LoadRecordset($SQL);
		if ($rs && !$rs->EOF) {
			$res = $rs->fields;
			$rs->Close();
		}
		return $res;
	}

	// Executes the query, and returns all row
	function ExecuteRows($SQL) {
		$res = FALSE;
		$rs = $this->LoadRecordset($SQL);
		if ($rs && !$rs->EOF) {
			$res = $rs->GetRows();
			$rs->Close();
		}
		return $res;
	}

	// Load recordset
	function &LoadRecordset($SQL) {
		$conn = &$this->Connection;
		if ($this->Debug)
			$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rs = $conn->Execute($SQL);
		$conn->raiseErrorFn = '';
		return $rs;
	}

	// Table CSS class name
	var $TableClass = "table table-bordered table-striped ewDbTable";

	// Get result in HTML table
	// options: fieldcaption(bool|array), horizontal(bool), tablename(string|array), tableclass(string)
	function ExecuteHtml($SQL, $options = NULL) {
		$ar = is_array($options) ? $options : array();
		$horizontal = (array_key_exists("horizontal", $ar) && $ar["horizontal"]);
		$rs = $this->LoadRecordset($SQL);
		if (!$rs || $rs->EOF || $rs->FieldCount() < 1)
			return "";
		$html = "";
		$class = (array_key_exists("tableclass", $ar) && $ar["tableclass"]) ? $ar["tableclass"] : $this->TableClass;
		if ($rs->RecordCount() > 1 || $horizontal) { // Horizontal table
			$cnt = $rs->FieldCount();
			$html = "<table class=\"" . $class . "\">";
			$html .= "<thead><tr>";
			$row = &$rs->fields;
			foreach ($row as $key => $value) {
				if (!is_numeric($key))
					$html .= "<th>" . $this->GetFieldCaption($key, $ar) . "</th>";
			}
			$html .= "</tr></thead>";
			$html .= "<tbody>";
			$rowcnt = 0;
			while (!$rs->EOF) {
				$html .= "<tr>";
				$row = &$rs->fields;
				foreach ($row as $key => $value) {
					if (!is_numeric($key))
						$html .= "<td>" . $value . "</td>";
				}
				$html .= "</tr>";
				$rs->MoveNext();
			}
			$html .= "</tbody></table>";
		} else { // Single row, vertical table
			$html = "<table class=\"" . $class . "\"><tbody>";
			$row = &$rs->fields;
			foreach ($row as $key => $value) {
				if (!is_numeric($key)) {
					$html .= "<tr>";
					$html .= "<td>" . $this->GetFieldCaption($key, $ar) . "</td>";
					$html .= "<td>" . $value . "</td></tr>";
				}
			}
			$html .= "</tbody></table>";
		}
		return $html;
	}

	function GetFieldCaption($key, $ar) {
		$caption = "";
		if (!is_array($ar))
			return $key;
		$tablename = @$ar["tablename"];
		$usecaption = (array_key_exists("fieldcaption", $ar) && $ar["fieldcaption"]);
		if ($usecaption) {
			if (is_array($ar["fieldcaption"])) {
				$caption = @$ar["fieldcaption"][$key];
			} elseif (isset($this->Language)) {
				if (is_array($tablename)) {
					foreach ($tablename as $tbl) {
						$caption = @$this->Language->FieldPhrase($tbl, $key, "FldCaption");
						if ($caption <> "")
							break;
					}
				} elseif ($tablename <> "") {
					$caption = @$this->Language->FieldPhrase($tablename, $key, "FldCaption");
				}
			}
		}
		return ($caption <> "") ? $caption : $key;
	}
}

// Connection/Query error handler
if (!function_exists("ewr_ErrorFn")) {

	// Connection/Query error handler
	function ewr_ErrorFn($DbType, $ErrorType, $ErrorNo, $ErrorMsg, $Param1, $Param2, $Object) {
		if ($ErrorType == 'CONNECT') {
			if ($DbType == "ado_access" || $DbType == "ado_mssql") {
				$msg = "Failed to connect to database. Error: " . $ErrorMsg;
			} else {
				$msg = "Failed to connect to $Param2 at $Param1. Error: " . $ErrorMsg;
			}
		} elseif ($ErrorType == 'EXECUTE') {
			if (defined("EWR_DEBUG_ENABLED") && EWR_DEBUG_ENABLED) {
				$msg = "Failed to execute SQL: $Param1. Error: " . $ErrorMsg;
			} else {
				$msg = "Failed to execute SQL. Error: " . $ErrorMsg;
			}
		}
		if (function_exists("ewr_AddMessage") && defined("EWR_SESSION_FAILURE_MESSAGE"))
			ewr_AddMessage($_SESSION[EWR_SESSION_FAILURE_MESSAGE], $msg);
		else
			echo "<div class=\"alert alert-danger ewError\">" . $msg . "</div>";
	}
}
?>
