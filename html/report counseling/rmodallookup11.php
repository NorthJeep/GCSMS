<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php

//
// Page class for modal lookup
//
class crmodallookup {

	// Page ID
	var $PageID = "modallookup";

	// Project ID
	var $ProjectID = "{234B495E-E8C1-4FF1-B18B-170E747447B8}";

	// Page object name
	var $PageObjName = "modallookup";

	// Page name
	function PageName() {
		return ewr_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		return ewr_CurrentPage() . "?";
	}
	var $Connection;
	var $DBID;
	var $SQL;
	var $Recordset;
	var $TotalRecs;
	var $RowCnt;
	var $ColSpan = 1;
	var $RecCount;
	var $StartOffset = 0; // 0-based, not $StartRec which is 1-based
	var $LookupTable;
	var $LookupTableCaption;
	var $LinkField;
	var $LinkFieldCaption;
	var $DisplayFields = array();
	var $DisplayFieldCaptions = array();
 	var $DisplayFieldExpressions = array();
	var $ParentFields = array();
	var $Multiple = FALSE;
	var $PageSize = 10;
	var $SearchValue = "";
	var $SearchFilter = "";
	var $SearchType = ""; // Auto ("=" => Exact Match, "AND" => All Keywords, "OR" => Any Keywords)
	var $PostData;
	var $AdvancedFilters = array();

	//
	// Page main
	//
	function Page_Main() {
		global $ReportLanguage;
		$this->PostData = $_POST;
		if (count($this->PostData) == 0)
			$this->Page_Error("Missing post data.");
		$ReportLanguage = new crLanguage("", @$this->PostData["lang"]);
		$GLOBALS["Page"] = &$this;

		// Load form data
		$sql = @$this->PostData["s"];
		$sql = ewr_Decrypt($sql);
		if ($sql == "")
			$this->Page_Error("Missing SQL.");
		$filter = @$this->PostData["f0"];
		$filter = ewr_Decrypt($filter);
		$this->DBID = @$this->PostData["d"] ?: "DB";
		$this->Multiple = @$this->PostData["m"] == "1";
		$this->PageSize = @$this->PostData["n"] ?: 10;
		$this->Action = @$this->PostData["action"];
		$this->StartOffset = @$this->PostData["start"] ?: 0;

		// Get Advanced Filters
		$af = @$this->PostData["af"];
		$af = @json_decode(ewr_Decrypt($af), TRUE);
		if (is_array($af)) {
			foreach ($af as $filter) {
				if ($filter["Enabled"])
					$this->AdvancedFilters[] = array($filter["ID"], $filter["Name"], "", "", "");
			}
		}

		// Load lookup table/field names
		$this->LookupTable = @$this->PostData["lt"];
		if ($this->LookupTable == "")
			$this->Page_Error("Missing lookup table.");
		$this->LookupTableCaption = $ReportLanguage->TablePhrase($this->LookupTable, "TblCaption");
		$this->LinkField = @$this->PostData["lf"];
		if ($this->LinkField == "")
			$this->Page_Error("Missing link field.");
		$this->LinkFieldCaption = $ReportLanguage->FieldPhrase($this->LookupTable, $this->LinkField, "FldCaption");
		$ar = preg_grep('/^ldf\d+$/', array_keys($this->PostData));
		foreach ($ar as $key) {
			$i = preg_replace('/^ldf/', '', $key);
			$fldvar = $this->PostData[$key];
			if ($fldvar <> "") {
				$fldcaption = $ReportLanguage->FieldPhrase($this->LookupTable, $fldvar, "FldCaption");
				if ($fldcaption == "")
					$fldcaption = $fldvar;
				$this->DisplayFields[$i] = $fldvar;
				$this->DisplayFieldCaptions[$i] = $fldcaption;
				$this->DisplayFieldExpressions[$i] = ewr_Decrypt(@$this->PostData["dx" . $i]);
				$this->ColSpan++;
			}
		}

		// Load search filter / selected key values
		$fldtype = intval(@$this->PostData["t0"]);
		$flddatatype = ewr_FieldDataType($fldtype);
		if (isset($_POST["sv"])) {
			$this->SearchValue = $this->PostData["sv"];
			$this->SearchFilter = $this->GetSearchFilter();
			$filter = "";
		} elseif (isset($_POST["keys"])) {
			$arKeys = @$this->PostData["keys"];
			if (is_array($arKeys) && count($arKeys) > 0) {
				$filterwrk = "";
				$cnt = count($arKeys);
				for ($i = 0; $i < $cnt; $i++) {
					$arKeys[$i] = ewr_QuotedValue($arKeys[$i], $flddatatype, $this->DBID);
					$filterwrk .= (($filterwrk <> "") ? " OR " : "") . str_replace("{filter_value}", $arKeys[$i], $filter);
				}
				$filter = $filterwrk;
				$this->PageSize = -1;
			} else {
				$filter = "1=0";
			}
		} else {
			$filter = "";
		}

		// Check parent filters
		$filters = "";
		if (ewr_ContainsStr($sql, "{filter}")) {
			$ar = preg_grep('/^f\d+$/', array_keys(@$this->PostData));
			foreach ($ar as $key) {

				// Get the filter values (for "IN")
				$filter2 = ewr_Decrypt(@$this->PostData[$key]);
				if ($filter2 <> "") {
					$i = preg_replace('/^f/', '', $key);
					$value = @$this->PostData["v" . $i];
					if ($value == "") {
						if ($i > 0) // Empty parent field

							//continue; // Allow
							ewr_AddFilter($filters, "1=0"); // Disallow
						continue;
					}
					$this->ParentFields[$i] = $i;
					$arValue = explode(EWR_LOOKUP_FILTER_VALUE_SEPARATOR, $value);
					$fldtype = intval(@$this->PostData["t" . $i]);
					$flddatatype = ewr_FieldDataType($fldtype);
					$bValidData = TRUE;
					for ($j = 0, $cnt = count($arValue); $j < $cnt; $j++) {
						if ($flddatatype == EWR_DATATYPE_NUMBER && !is_numeric($arValue[$j])) {
							$bValidData = FALSE;
							break;
						} else {
							$arValue[$j] = ewr_QuotedValue($arValue[$j], $flddatatype, $this->DBID);
						}
					}
					if ($bValidData)
						$filter2 = str_replace("{filter_value}", implode(",", $arValue), $filter2);
					else
						$filter2 = "1=0";
					$fn = @$this->PostData["fn" . $i];
					if ($fn == "" || !function_exists($fn)) $fn = "ewr_AddFilter";
					$fn($filters, $filter2);
				}
			}
		}
		$where = ""; // Initialize
		if ($this->SearchFilter <> "" && $this->SearchValue <> "")
			ewr_AddFilter($where, $this->SearchFilter);
		if ($filter <> "")
			ewr_AddFilter($where, $filter);
		if ($filters <> "")
			ewr_AddFilter($where, $filters);
		$sql = str_replace("{filter}", ($where <> "") ? $where : "1=1", $sql);
		$this->SQL = $sql;

		//$this->Page_Error($sql); // Show SQL for debugging
		// Get records

		$this->Connection = &ReportConn($this->DBID);
		$this->TotalRecs = $this->GetRecordCount($sql);
		if ($this->PageSize > 0)
			$this->Recordset = $this->Connection->SelectLimit($sql, $this->PageSize, $this->StartOffset);
		if (!$this->Recordset)
			$this->Recordset = $this->Connection->Execute($sql);

		// Return JSON
		$this->Page_Response();
	}

	// Get search filter
	function GetSearchFilter() {
		if (trim($this->SearchValue) == "")
			return "";
		$sSearchStr = "";
		$sSearch = trim($this->SearchValue);
		$sSearchType = $this->SearchType;
		if ($sSearchType <> "=") {
			$ar = array();

			// Match quoted keywords (i.e.: "...")
			if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
				foreach ($matches as $match) {
					$p = strpos($sSearch, $match[0]);
					$str = substr($sSearch, 0, $p);
					$sSearch = substr($sSearch, $p + strlen($match[0]));
					if (strlen(trim($str)) > 0)
						$ar = array_merge($ar, explode(" ", trim($str)));
					$ar[] = $match[1]; // Save quoted keyword
				}
			}

			// Match individual keywords
			if (strlen(trim($sSearch)) > 0)
				$ar = array_merge($ar, explode(" ", trim($sSearch)));

			// Search keyword in any fields
			if ($sSearchType == "OR" || $sSearchType == "AND") {
				foreach ($ar as $sKeyword) {
					if ($sKeyword <> "") {
						$sSearchFilter = $this->GetSearchSQL(array($sKeyword));
						if ($sSearchFilter <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $sSearchFilter . ")";
						}
					}
				}
			} else {
				$sSearchStr = $this->GetSearchSQL($ar);
			}
		} else {
			$sSearchStr = $this->GetSearchSQL(array($sSearch));
		}
		return $sSearchStr;
	}

	// Get search SQL
	function GetSearchSQL($arKeywords) {
		$sWhere = "";
		foreach ($this->DisplayFieldExpressions as $sql) {
			if ($sql <> "") {
				$this->BuildSearchSQL($sWhere, $sql, $arKeywords);
			}
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSQL(&$Where, $FldExpr, $arKeywords) {
		$sSearchType = $this->SearchType;
		$sDefCond = ($sSearchType == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EWR_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EWR_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $sSearchType == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} else {
						$sWrk = $FldExpr . ewr_Like(ewr_QuotedValue("%" . $Keyword . "%", EWR_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .= "(" . $sSql . ")";
		}
	}

	// Get record count
	function GetRecordCount($sSql) {
		$cnt = -1;
		$rs = NULL;
		$sql = preg_replace('/\/\*BeginOrderBy\*\/[\s\S]+\/\*EndOrderBy\*\//', "", $sSql); // Remove ORDER BY clause (MSSQL)
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

	// Show page response
	function Page_Response() {
		if (!is_object($this->Recordset)) {
			$result = array("Result" => "ERROR", "Message" => "Failed to execute SQL");
			if (EWR_DEBUG_ENABLED)
				$result["Message"] .= ": " . $this->SQL; // To be viewed in browser Network panel for debugging
			echo json_encode($result);
			exit();
		}
		$rowcnt = $this->Recordset->RecordCount();
		$fldcnt = count($this->DisplayFields);
		$rsarr = $this->Recordset->GetRows();
		$this->Recordset->Close();
		ewr_CloseConn();

		// Clean output buffer
		if (ob_get_length())
			ob_clean();

		// Format date
		$bIsDate = (ewr_FieldDataType(@$_POST["t0"]) == EWR_DATATYPE_DATE);
		$ardt = array();
		for ($i = 0; $i <= $fldcnt; $i++) {
			$ardt[$i] = @$_POST["df" . $i]; // Get date formats
			if ($ardt[$i] == "" && $bIsDate)
				$ardt[$i] = "0"; // Format as default date
		}

		// Output
		$arr = $this->AdvancedFilters;
		if (is_array($rsarr) && $rowcnt > 0) {
			foreach ($rsarr as &$row) {
				if ($bIsDate) // Unformat date
					$row[0] = ewr_ConvertValue("date", $row[0]);
				$ar = array($this->LinkField => $row[0]);
				for ($i = 1; $i <= $fldcnt; $i++) {
					$str = ewr_ConvertToUtf8(strval($row[$i]));
					if ($ardt[$i] != "" && intval($ardt[$i]) >= 0) // Format date
						$str = ewr_FormatDateTime($str, $ardt[$i]);
					$str = str_replace(array("\r", "\n", "\t"), isset($post["keepCRLF"]) ? array("\\r", "\\n", "\\t") : array(" ", " ", " "), $str);
					$row[$i] = $str;
				}
				$arr[] = $row;
			}
		}
		echo '{"Result": "OK", "Records": ' . ewr_ArrayToJson($arr) . ', "TotalRecordCount": ' . $this->TotalRecs . '}';
		exit();
	}

	// Show page error
	function Page_Error($msg) {
		$result = array("Result" => "ERROR", "Message" => $msg); 
		echo json_encode($result);
		exit();
	}
}
ewr_Header(FALSE, 'utf-8', TRUE);
$modallookup = new crmodallookup;
$modallookup->Page_Main();
?>
