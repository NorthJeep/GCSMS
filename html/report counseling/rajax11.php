<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php
ewr_Header(FALSE, 'utf-8', TRUE);
$lookup = new crlookup;
$lookup->Page_Main();

//
// Page class for lookup
//
class crlookup {

	// Page ID
	var $PageID = "lookup";

	// Project ID
	var $ProjectID = "{234B495E-E8C1-4FF1-B18B-170E747447B8}";

	// Page object name
	var $PageObjName = "lookup";

	// Page name
	function PageName() {
		return ewr_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		return ewr_CurrentPage() . "?";
	}

	// Connection
	var $Connection;
	var $DBID;

	// Get record count
	function GetRecordCount($sql) {
		$cnt = -1;
		$rs = NULL;
		$sql = preg_replace('/\/\*BeginOrderBy\*\/[\s\S]+\/\*EndOrderBy\*\//', "", $sql); // Remove ORDER BY clause (MSSQL)
		$pattern = '/^SELECT\s+\*\s+FROM/i'; // SELECT * FROM ...
		if (preg_match($pattern, $sql)) {
			$sqlwrk = "SELECT COUNT(*) FROM" . preg_replace($pattern, "", $sql);
			$rs = $this->Connection->Execute($sqlwrk);
		}
		$pattern = '/^SELECT\s+DISTINCT\s+([\s\S]+)\s*,\s*\1\s+AS\s+[\s\S]+FROM/i'; // SELECT DISTINCT Field1, Field1 AS DispFld, ... FROM ...
		if (!$rs && preg_match($pattern, $sql, $matches) && ewr_GetConnectionType($this->DBID) <> "ACCESS") {
			$sqlwrk = "SELECT COUNT(DISTINCT " . $matches[1] . ") FROM " . preg_replace($pattern, "", $sql);
			$rs = $this->Connection->Execute($sqlwrk);
		}
		if (!$rs) {
			$sqlwrk = "SELECT COUNT(*) FROM (" . $sql . ") EW_COUNT_TABLE";
			$rs = $this->Connection->Execute($sqlwrk);
		}
		if ($rs && !$rs->EOF && $rs->FieldCount() > 0) {
			$cnt = $rs->fields[0];
			$rs->Close();
			return intval($cnt);
		}

		// Unable to get count, get record count directly
		if ($rs = $this->Connection->Execute($sql)) {
			$cnt = $rs->RecordCount();
			$rs->Close();
			return intval($cnt);
		}
		return $cnt;
	}

	// Main
	function Page_Main() {
		global $ReportLanguage;
		$post = $_POST;
		if (count($post) == 0)
			die('{"Result": "Missing post data."}');
		$req = array_merge($_GET, $post);
		$ReportLanguage = new crLanguage("", @$post["lang"]);
		$GLOBALS["Page"] = &$this;

		// Get Advanced Filter Name
		$af = @$post["af"];
		$af = @json_decode(ewr_Decrypt($af), TRUE);
		if (is_array($af)) {
			$value = @$post["v0"];
			foreach ($af as $filter) {
				if ($filter["Enabled"] && $filter["ID"] == $value) {
					$arr = array(array($filter["ID"], $filter["Name"]));
					echo ewr_ArrayToJson($arr);
					exit();
				}
			}
		}

		//$sql = $qs->getValue("s");
		$sql = @$post["s"];
		$sql = ewr_Decrypt($sql);
		if ($sql == "")
			die('{"Result": "Missing SQL."}');
		$this->DBID = @$post["d"] ?: "DB";
		$this->Connection = ewr_Connect($this->DBID);

		// Global Page Loading event (in userfn*.php)
		Page_Loading();
		ob_clean(); // Clear output
		if (ewr_ContainsStr($sql, "{filter}")) {
			$filters = "";
			$ar = preg_grep('/^f\d+$/', array_keys($post));
			foreach ($ar as $key) {

				// Get the filter values (for "IN")
				$filter = ewr_Decrypt(@$post[$key]);
				if ($filter <> "") {
					$i = preg_replace('/^f/', '', $key);
					$value = @$post["v" . $i];
					if ($value == "") {
						if ($i > 0) // Empty parent field

							//continue; // Allow
							ewr_AddFilter($filters, "1=0"); // Disallow
						continue;
					}
					$arValue = explode(EWR_LOOKUP_FILTER_VALUE_SEPARATOR, $value);
					$fldtype = intval(@$post["t" . $i]);
					$wrkfilter = "";
					for ($j = 0, $cnt = count($arValue); $j < $cnt; $j++) {
						if ($wrkfilter <> "") $wrkfilter .= " OR ";
						$val = $arValue[$j];
						if (ewr_SameStr($val, EWR_NULL_VALUE))
							$wrkfilter .= str_replace(" = {filter_value}", " IS NULL", $filter);
						elseif (ewr_SameStr($val, EWR_NOT_NULL_VALUE))
							$wrkfilter .= str_replace(" = {filter_value}", " IS NOT NULL", $filter);
						elseif (ewr_SameStr($val, EWR_EMPTY_VALUE))
							$wrkfilter .= str_replace(" = {filter_value}", " = ''", $filter);
						else
							$wrkfilter .= str_replace("{filter_value}", ewr_QuotedValue($val, ewr_FieldDataType($fldtype), $this->DBID), $filter);
					}
					ewr_AddFilter($filters, $wrkfilter);
				}
			}
			$sql = str_replace("{filter}", ($filters <> "") ? $filters : "1=1", $sql);
		}

		// Get the query value (for "LIKE" or "=")
		$value = ewr_AdjustSql(@$req["q"], $this->DBID); // Get the query value from querystring
		if ($value == "") $value = ewr_AdjustSql(@$post["q"], $this->DBID); // Get the value from post
		if ($value <> "") {
			$sql = preg_replace('/LIKE \'(%)?\{query_value\}%\'/', ewr_Like('\'$1{query_value}%\'', $this->DBID), $sql);
			$sql = str_replace("{query_value}", $value, $sql);
		}

		// Replace {query_value_n}
		preg_match_all('/\{query_value_(\d+)\}/', $sql, $out);
		$cnt = count($out[0]);
		for ($i = 0; $i < $cnt; $i++) {
			$j = $out[1][$i];
			$v = ewr_AdjustSql(@$post["q" . $j], $this->DBID);
			$sql = str_replace("{query_value_" . $j . "}", $v, $sql);
		}

		// Page size
		$max = intval(@$req["n"]);
		$isAutoSuggest = ewr_SameText(@$post["ajax"], "autosuggest");
		if ($isAutoSuggest && $max < 1)
			$max = EWR_AUTO_SUGGEST_MAX_ENTRIES;
		if ($max < 1)
			$max = -1;

		// Offset
		$offset = -1;
		if (isset($req["start"])) { // Get start from GET/POST
			$start = intval($req["start"]); 
			if ($start > -1)
				$offset = $start;
		} elseif (isset($req["page"])) { // Get page number from GET/POST
			$page = intval($req["page"]); 
			if ($page > 0 && $max > 0)
				$offset = ($page - 1) * $max;
		}

		// Record count for AutoSuggest
		$cnt = ($isAutoSuggest) ? $this->GetRecordCount($sql) : -1;
		$dbtype = ewr_GetConnectionType($this->DBID);
		if ($dbtype == "MSSQL") {
			$inputarr = array("_hasOrderBy" => preg_match('/\/\*BeginOrderBy\*\/[\s\S]+\/\*EndOrderBy\*\//', $sql) == 1); // Has ORDER BY clause (MSSQL)
			$rs = $this->Connection->SelectLimit($sql, $max, $offset, $inputarr);
		} else {
			$rs = $this->Connection->SelectLimit($sql, $max, $offset);
		}
		if (!$rs)
			die(json_encode(array("Result" => "Failed to execute SQL: " . ewr_ConvertToUtf8($sql))));
		$fldcnt = $rs->FieldCount();

		// Field delimiter
		$dlm = @$post["dlm"];
		$dlm = ewr_Decrypt($dlm);
		$ds = @$post["ds"]; // Date search type
		$df = @$post["df"]; // Date format

		// Lookup for date field, format as date
		if ($ds == "" && $df == "" && ewr_FieldDataType(@$post["t0"]) == EWR_DATATYPE_DATE) {
			$ds = "date";
			$df = "0";
		}

		// Get result
		$key = array();
		$rsarr = array();
		while (!$rs->EOF) {
			$row = $rs->fields;

			// Handle field delimiter
			if ($dlm <> "") {
				$valcnt = 0;
				for ($j = 0; $j < $fldcnt; $j++) {
					if (strpos(strval($row[$j]), $dlm) !== FALSE) {
						$row[$j] = explode($dlm, $row[$j]);
						if (count($row[$j]) > $valcnt)
							$valcnt = count($row[$j]);
					} else {
						if ($valcnt < 1)
							$valcnt = 1;
					}
				}
			} else {
				$valcnt = 1;
			}
			for ($k = 0; $k < $valcnt; $k++) {
				$val0 = "";
				$str0 = "";
				$rec = array();
				for ($j = 0; $j < $fldcnt; $j++) {
					if ($dlm <> "" && is_array($row[$j])) {
						if (count($row[$j]) > $k)
							$val = $row[$j][$k];
						else
							$val = $row[$j][0];
					} else {
						$val = $row[$j];
					}
					if ($j == 0) {
						$str = ewr_ConvertValue($ds, $val);
						$val0 = $val;
						$str0 = $str;
					} elseif ($j == 1 && is_null($val0)) {
						$str = $ReportLanguage->Phrase("NullLabel");
					} elseif ($j == 1 && strval($val0) == "") {
						$str = $ReportLanguage->Phrase("EmptyLabel");
					} elseif ($j == 1) {
						$str = ewr_DropDownDisplayValue(ewr_ConvertValue($ds, $val), $ds, $df);
					} else {
						$str = strval($val);
					}
					$str = ewr_ConvertToUtf8($str);
					$rec[$j] = $str;
				}
				if (!in_array($str0, $key)) {
					$rsarr[] = $rec;
					$key[] = $str0;
				}
			}
			$rs->MoveNext();
		}
		$rs->Close();
		$result = ewr_ArrayToJson($rsarr);
		if ($isAutoSuggest) {
			$result = '{"Result": "OK", "Records": ' . $result . ', "TotalRecordCount": ' . $cnt;
			if (EWR_DEBUG_ENABLED)
				$result .= ', "SQL": "' . str_replace("\"",  "\\\"", ewr_ConvertToUtf8($sql)) . '"';
			$result .= '}';
		}

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Close connection
		ewr_CloseConn();

		// Clear output
		if (ob_get_length())
			ob_clean();

		// Output
		echo $result;
	}
}
?>
