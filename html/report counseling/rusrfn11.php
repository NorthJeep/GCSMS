<?php

// Global user functions
// Filter for 'Last Month' (example)
function GetLastMonthFilter($FldExpression, $dbid) {
	$today = getdate();
	$lastmonth = mktime(0, 0, 0, $today['mon']-1, 1, $today['year']);
	$sVal = date("Y|m", $lastmonth);
	$sWrk = $FldExpression . " BETWEEN " .
		ewr_QuotedValue(ewr_DateVal("month", $sVal, 1, $dbid), EWR_DATATYPE_DATE, $dbid) .
		" AND " .
		ewr_QuotedValue(ewr_DateVal("month", $sVal, 2, $dbid), EWR_DATATYPE_DATE, $dbid);
	return $sWrk;
}

// Filter for 'Starts With A' (example)
function GetStartsWithAFilter($FldExpression, $dbid) {
	return $FldExpression . ewr_Like("'A%'", $dbid);
}

// Page Loading event
function Page_Loading() {

	//echo "Page Loading";
}

// Page Rendering event
function Page_Rendering() {

	//echo "Page Rendering";
}

// Page Unloaded event
function Page_Unloaded() {

	//echo "Page Unloaded";
}
?>
