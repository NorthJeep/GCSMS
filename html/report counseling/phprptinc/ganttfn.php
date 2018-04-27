<?php

//
// Gantt Chart for PHP Report Maker 11
// (C) 2018 e.World Technology Limited
//

/**
 * Gantt chart classes
 */

//
// Gantt Chart Categories
//
class crGanttCategories {
	var $Title = "";
	var $Interval = EWR_GANTT_INTERVAL_NONE; // 0-5
	var $CategoriesAttrs = array();
	var $CategoryAttrs = array();
	var $StartDate;
	var $EndDate;

	function SetTitle($title) {
		$this->Title = $title;
		$this->Interval = EWR_GANTT_INTERVAL_NONE; // Reset
	}

	function SetInterval($interval) {
		$this->Interval = $interval;
		$this->Title = ""; // Reset
	}
}

//
// Gantt Chart Data Column
//
class crGanttDataColumn {
	var $FieldName = ""; // Field name
	var $Caption = ""; // Header text
	var $ColumnAttrs = array();
	var $TextAttrs = array();
	var $FormatFunction = "";

	// Constructor
	function __construct($fldname, $caption, $formatfunc) {
		$this->FieldName = $fldname;
		$this->Caption = $caption;
		$this->FormatFunction = $formatfunc;
	}
}

//
// Gantt Chart
//
class crGantt extends crChart {
	var $Name = "";
	var $ProcessesHeaderText;
	var $DateFormat = "yyyy/mm/dd";

	// Tables
	var $TaskTable = "";
	var $TaskTableDBID = "";
	var $ProcessTable = ""; // Optional
	var $ProcessTableDBID = ""; // Optional
	var $MilestoneTable = ""; // Optional
	var $MilestoneTableDBID = ""; // Optional
	var $ConnectorTable = ""; // Optional
	var $ConnectorTableDBID = ""; // Optional
	var $TrendlineTable = ""; // Optional
	var $TrendlineTableDBID = ""; // Optional

	// Task Table Fields
	var $TaskIdField = "";
	var $TaskNameField = "";
	var $TaskStartField = "";
	var $TaskEndField = "";
	var $TaskResourceIdField = "";
	var $TaskDurationField = "";
	var $TaskPercentCompleteField = "";
	var $TaskDependenciesField = "";
	var $TaskFromTaskIdField = ""; // Optional
	var $TaskMilestoneDateField = ""; // Optional
	var $TaskFilter = ""; // Table filter
	var $TaskIdFilter = ""; // Task Id filter
	var $TaskNameFilter = ""; // Task Name filter

	// Category
	var $Categories = array(); // Array of crGanttCategories
	var $Intervals = array(); // Array of category intervals
	var $Connectors = array(); // Array of connectors
	var $Trendlines = array(); // Array of trendlines
	var $Milestones = array(); // Array of milestones
	var $StartDate;
	var $EndDate;
	var $FixedStartDate; // Must in 'yyyy-mm-dd' format
	var $FixedEndDate; // Must in 'yyyy-mm-dd' format

	// Data columns
	var $DataColumns = array(); // Array of crGanttDataColumn

	// XML DOMDocument object
	var $XmlDoc;

	// Default styles
	var $HeaderColor = '4567aa';
	var $HeaderFontColor = 'ffffff';
	var $CategoryColor = '';
	var $CategoryFontColor = '';
	var $HeaderIsBold = '1';
	var $TaskColors = array('FF0000', 'FF0080', 'FF00FF', '8000FF', 'FF8000',
		'FF3D3D', '7AFFFF', '0000FF', 'FFFF00', 'FF7A7A', '3DFFFF', '0080FF',
		'80FF00', '00FF00', '00FF80', '00FFFF'); // Task colors
	var $ShowWeekNumber = TRUE;

	// Chart properties
	var $ChartAttrs = array(); // Attributes for <chart>
	var $ProcessesAttrs = array(); // Attributes for <processes>
	var $ProcessAttrs = array(); // Attributes for <process>
	var $TasksAttrs = array(); // Attributes for <tasks>
	var $TaskAttrs = array('alpha'=>75); // Attributes for <task>
	var $ConnectorsAttrs = array(); // Attributes for <connectors>
	var $ConnectorAttrs = array(); // Attributes for <connector>
	var $DataTableAttrs = array(); // Attributes for <datatable>
	var $TrendlineAttrs = array(); // Attributes for <trendline>
	var $MilestoneAttrs = array('radius'=>'6', 'shape'=>'Polygon', 'numSides'=>'4', 'borderColor'=>'333333', 'borderThickness'=>'1'); // Attributes for <milestone>
	var $Connection;

	// Constructor
	function __construct($table, $dbid) {
		global $ReportLanguage;
		$this->TaskTable = $table;
		$this->TaskTableDBID = $dbid;
		$this->Connection = &ReportConn($this->TaskTableDBID);
		$this->XmlDoc = new DOMDocument("1.0", "utf-8");
		$this->XmlDoc->appendChild($this->XmlDoc->createElement("chart"));
		$this->ChartAttrs["extendcategoryBg"] = "0";
		$this->ProcessesHeaderText = $ReportLanguage->Phrase("Tasks");
	}

	// Is gantt chart
	function IsGanttChart($typ = 0) {
		return TRUE;
	}

	// Set XML attribute
	function SetAttribute(&$element, $name, $value) {
		if (!$element)
			return;
		$element->setAttribute($name, ewr_ConvertToUtf8($value));
	}

	// Get inteval as integer
	function GetIntervalValue($interval) {
		$interval = strtoupper($interval);
		if ($interval == "_YEAR") {
			return EWR_GANTT_INTERVAL_YEAR;
		} elseif ($interval == "_QUARTER") {
			return EWR_GANTT_INTERVAL_QUARTER;
		} elseif ($interval == "_MONTH") {
			return EWR_GANTT_INTERVAL_MONTH;
		} elseif ($interval == "_WEEK") {
			return EWR_GANTT_INTERVAL_WEEK;
		} elseif ($interval == "_DAY") {
			return EWR_GANTT_INTERVAL_DAY;
		} else {
			return EWR_GANTT_INTERVAL_NONE;
		}
	}

	// Add categories
	function AddCategories($type) {
		if ($type == "")
    	return;
		if (in_array(strtoupper($type), array("_YEAR", "_QUARTER", "_MONTH", "_WEEK", "_DAY"))) { // Interval
			$intv = $this->GetIntervalValue($type);
			if ($intv > EWR_GANTT_INTERVAL_NONE) {
				$this->Intervals[] = $intv;
				$cats = new crGanttCategories();
				$cats->SetInterval($intv);
				$this->Categories[$type] = $cats;
			}
		} else { // Title
			$cats = new crGanttCategories();
			$cats->SetTitle($type);
			$this->Categories[$type] = $cats;
		}
	}

	// Add data column
	function AddDataColumn($fldname, $caption, $formatfunc = "") {
		$this->DataColumns[$fldname] = new crGanttDataColumn($fldname, $caption, $formatfunc);
	}

	// Add connector
	function AddConnector($ar) {
		$this->Connectors[] = $ar;
	}

	// Add trendline
	function AddTrendline($ar) {
		$this->Trendlines[] = $ar;
	}

	// Add milestone
	function AddMilestone($ar) {
		$this->Milestones[] = $ar;
	}

	// Create datetime
	function CreateDateTime($hour, $min, $sec, $month, $day, $year) {
		return mktime($hour, $min, $sec, $month, $day, $year);
	}

	// Get datetime info
	function GetDateTime($ts) {
		return getdate($ts);
	}

	// Get datetime info
	function FormatDate($format, $date) {
		return date($format, $date);
	}

	// Convert Year/Month/Day to string
	function YMDToStr($y, $m, $d) {
		if ($this->DateFormat == 'mm/dd/yyyy') {
			return str_pad($m, 2, '0', STR_PAD_LEFT) . '/' . str_pad($d, 2, '0', STR_PAD_LEFT) . '/' . strval($y);
		} elseif ($this->DateFormat == 'dd/mm/yyyy') {
			return str_pad($d, 2, '0', STR_PAD_LEFT) . '/' . str_pad($m, 2, '0', STR_PAD_LEFT) . '/' . strval($y);
		} else { // 'yyyy/mm/dd'
			return strval($y) . '/' . str_pad($m, 2, '0', STR_PAD_LEFT) . '/' . str_pad($d, 2, '0', STR_PAD_LEFT);
		}
	}

	// Convert date time info (from getdate) to string
	function DateTimeToStr($dt) {
		return $this->YMDToStr($dt["year"], $dt["mon"], $dt["mday"]);
	}

	// Convert date (timestamp) to string
	function DateToStr($d) {
		$dt = $this->GetDateTime($d);
		return $this->DateTimeToStr($dt);
	}

	// Convert database date (yyyy-mm-dd) to yyyy/mm/dd
	function DBDateToStr($str) {
		$date = $this->ParseDate($str);
		return $this->DateToStr($date);
	}

	// Parse string to datetime
	function ParseDate($str) {
		if (preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $str, $matches)) { // DateTime
			$year = $matches[1];
			$month = $matches[2];
			$day = $matches[3];
			$hour = $matches[4];
			$min = $matches[5];
			$sec = $matches[6];
			return $this->CreateDateTime($hour, $min, $sec, $month, $day, $year);
		} elseif (preg_match('/(\d{4})-(\d{2})-(\d{2})/', $str, $matches)) { // Date
			$year = $matches[1];
			$month = $matches[2];
			$day = $matches[3];
			return $this->CreateDateTime(0, 0, 0, $month, $day, $year);
		}
		return $str;
	}

	// Get task color
	function GetTaskColor($i) {
		$color = "";
		if (is_array($this->TaskColors)) {
			$cntar = count($this->TaskColors);
			if ($cntar > 0) {
				$color = $this->TaskColors[$i % $cntar];
				$color = str_replace('#', '', $color);
			}
		}
		return $color;
	}

	// Set up start/end dates
	function SetupStartEnd() {
		$sql = "SELECT MIN(" . ewr_QuotedName($this->TaskStartField, $this->TaskTableDBID) . "), MAX(". ewr_QuotedName($this->TaskEndField, $this->TaskTableDBID) . ") FROM " . $this->TaskTable;
		if ($this->TaskFilter <> "") $sql .= " WHERE " . $this->TaskFilter;
		$rs = $this->Connection->Execute($sql);
		if ($rs && !$rs->EOF) {
			$start = $rs->fields[0];
			$end = $rs->fields[1];
			$rs->Close();
		} else {
			die('Error: Missing tasks.');
		}
		$start = $this->ParseDate($start);
		$end = $this->ParseDate($end);
		$arStart = $this->GetDateTime($start);
		$arEnd = $this->GetDateTime($end);
		$min = $start;
		$max = $end;
		$cnt = 0;
		foreach ($this->Intervals as $interval) {

//			if ($interval == EWR_GANTT_INTERVAL_YEAR) {
//				$start = $this->CreateDateTime(0, 0, 0, 1, 1, intval($arStart["year"]));
//				$end = $this->CreateDateTime(0, 0, 0, 12, 31, intval($arEnd["year"]));
//				$cnt++;
//			} elseif ($interval == EWR_GANTT_INTERVAL_QUARTER) {
//				$qtr = floor(intval($arStart["mon"])/4) + 1;
//				$start = $this->CreateDateTime(0, 0, 0, ($qtr*3-2), 1, intval($arStart["year"]));
//				$yr = intval($arEnd["year"]);
//				$qtr = floor(intval($arEnd["mon"])/4) + 1;
//				$mon = $qtr * 3;
//				$end = $this->CreateDateTime(0, 0, 0, $mon, ewr_DaysInMonth($yr, $mon), $yr);
//				$cnt++;
//			} elseif ($interval == EWR_GANTT_INTERVAL_MONTH) {

			if ($interval == EWR_GANTT_INTERVAL_YEAR ||
				$interval == EWR_GANTT_INTERVAL_QUARTER ||
				$interval == EWR_GANTT_INTERVAL_MONTH) {
				$mon = intval($arStart["mon"]);
				$tempstart = $this->CreateDateTime(0, 0, 0, $mon, 1, intval($arStart["year"]));
				$yr = intval($arEnd["year"]);
				$mon = intval($arEnd["mon"]);
				$tempend = $this->CreateDateTime(0, 0, 0, $mon, ewr_DaysInMonth($yr, $mon), intval($arEnd["year"]));
				$cnt++;
			} elseif ($interval == EWR_GANTT_INTERVAL_WEEK) {
				$wday = $arStart["wday"];
				$diff = ($wday >= EWR_GANTT_WEEK_START) ? ($wday - EWR_GANTT_WEEK_START) : ($wday + 7 - EWR_GANTT_WEEK_START);
				$tempstart = $start - $diff * 86400;
				$wday = $arEnd["wday"];
				$diff = ($wday >= EWR_GANTT_WEEK_START) ? ($wday - EWR_GANTT_WEEK_START) : ($wday + 7 - EWR_GANTT_WEEK_START);
				$tempend = $end + (6 - $diff) * 86400;
				$cnt++;
			}

			// Start date
			if ($tempstart < $min)
				$min = $tempstart;

			// End date
			if ($tempend > $max)
				$max = $tempend;
		}
		if ($cnt == 0) {
			$min -= 86400;
			$max += 86400;
		}
		$this->StartDate = $min;
		$this->EndDate = $max;

		// Check if fixed start date specified
		if (isset($this->FixedStartDate)) {
			$fd = $this->ParseDate($this->FixedStartDate);
			if ($fd !== FALSE)
				$this->StartDate = $fd;
		}

		// Check if fixed end date specified
		if (isset($this->FixedEndDate)) {
			$fd = $this->ParseDate($this->FixedEndDate);
			if ($fd !== FALSE)
				$this->EndDate = $fd;
		}
	}

	// Output table
	function OutputQuery($sql, $dbid, $tagname, $childtagname, $attrs, $childattrs) {
		$conn = &ReportConn($dbid);
		$rs = $conn->Execute($sql);
		$this->OutputArray($rs->GetRows(), $tagname, $childtagname, $attrs, $childattrs);
		if ($rs)
			$rs->Close();
	}

	// Output table
	function OutputArray($rs, $tagname, $childtagname, $attrs, $childattrs) {
		if (is_array($rs)) {
			$elements = $this->XmlDoc->getElementsByTagName($tagname);
			$el = NULL;
			foreach ($elements as $element)
				$el = $element;
			if (!$el) {
				$el = $this->XmlDoc->createElement($tagname);
				foreach ($attrs as $attr => $value)
					$this->SetAttribute($el, $attr, $value);
				$this->Chart_DataRendered($el);
				$this->XmlDoc->documentElement->appendChild($el);
			}
			foreach ($rs as $row) {
				$cat = $this->XmlDoc->createElement($childtagname);
				foreach ($childattrs as $attr => $value)
					$this->SetAttribute($cat, $attr, $value);
				foreach ($row as $name => $value) {
					if (!is_numeric($name)) {
						if (in_array($name, array('start', 'end', 'date'))) // Date attributes
					 		$value = $this->DBDateToStr($value);
						$this->SetAttribute($cat, $name, $value);
					}
				}
				$this->Chart_DataRendered($cat);
				$el->appendChild($cat);
			}
		}
	}

	// Ouptut <categories> node
	function OutputCategories() {
		global $ReportLanguage;
		foreach ($this->Categories as $cats) {
			$el = $this->XmlDoc->createElement("categories");
			if (!isset($cats->CategoriesAttrs['bgColor']))
				$cats->CategoriesAttrs['bgColor'] = $this->CategoryColor;
			if (!isset($cats->CategoriesAttrs['fontColor']))
				$cats->CategoriesAttrs['fontColor'] = $this->CategoryFontColor;
			foreach ($cats->CategoriesAttrs as $attr => $value)
				$this->SetAttribute($el, $attr, $value);
			$this->Chart_DataRendered($el);
			$this->XmlDoc->documentElement->appendChild($el);
			if ($cats->Title <> "") { // Title
				$cat = $this->XmlDoc->createElement("category");
				$this->SetAttribute($cat, 'start', $this->DateToStr($this->StartDate));
				$this->SetAttribute($cat, 'end', $this->DateToStr($this->EndDate));
				$this->SetAttribute($cat, 'name', $cats->Title);
				foreach ($cats->CategoryAttrs as $attr => $value)
					$this->SetAttribute($cat, $attr, $value);
				$this->Chart_DataRendered($cat);
				$el->appendChild($cat);
			} else { // Intervals
				$arStart = $this->GetDateTime($this->StartDate);
				$arEnd = $this->GetDateTime($this->EndDate);
				if ($cats->Interval == EWR_GANTT_INTERVAL_YEAR) {
					$yrs = intval($arStart["year"]);
					$yre = intval($arEnd["year"]);
					for ($y = $yrs; $y <= $yre; $y++) {
						$cat = $this->XmlDoc->createElement("category");
						$start = ($y == $yrs) ? $this->DateTimeToStr($arStart) : $this->YMDToStr($y, 1, 1);
						$end = ($y == $yre) ? $this->DateTimeToStr($arEnd) : $this->YMDToStr($y, 12, 31);
						$this->SetAttribute($cat, 'start', $start);
						$this->SetAttribute($cat, 'end', $end);

						//if ($start == $this->YMDToStr($y, 1, 1) && $end == $this->YMDToStr($y, 12, 31)) // Complete year
							$this->SetAttribute($cat, 'name', $y);
						foreach ($cats->CategoryAttrs as $attr => $value)
							$this->SetAttribute($cat, $attr, $value);
						$this->Chart_DataRendered($el);
						$el->appendChild($cat);
					}
				} elseif ($cats->Interval == EWR_GANTT_INTERVAL_QUARTER) {
					$yrs = intval($arStart["year"]);
					$mons = intval($arStart["mon"]);
					$qtrs = floor(($mons-1)/3) + 1;
					$qs = $yrs * 4 + $qtrs;
					$yre = intval($arEnd["year"]);
					$mone = intval($arEnd["mon"]);
					$qtre = floor(($mone-1)/3) + 1;
					$qe = $yre * 4 + $qtre;
					for ($q = $qs; $q <= $qe; $q++) {
						$cat = $this->XmlDoc->createElement("category");
						$yr = floor($q/4);
						$qtr = $q % 4;
						$yr = ($qtr == 0) ? $yr - 1 : $yr;
						$qtr = ($qtr == 0) ? 4 : $qtr;
						$mos = ($qtr - 1) * 3 + 1;
						$moe = $qtr * 3;
						$dys = $this->CreateDateTime(0, 0, 0, $mos, 1, $yr);
						if ($this->StartDate > $dys)
							$dys = $this->StartDate;
						$dy = ewr_DaysInMonth($yr, $moe);
						$dye = $this->CreateDateTime(0, 0, 0, $moe, $dy, $yr);
						if ($this->EndDate < $dye)
							$dye = $this->EndDate;
						$start = ($q == $qs) ? $this->DateToStr($dys) : $this->YMDToStr($yr, $mos, 1);
						$end = ($q == $qe) ? $this->DateToStr($dye) : $this->YMDToStr($yr, $moe, $dy);
						$this->SetAttribute($cat, 'start', $start);
						$this->SetAttribute($cat, 'end', $end);

						//if ($start == $this->YMDToStr($yr, $mos, 1) && $end == $this->YMDToStr($yr, $moe, $dy)) // Complete quarter
							$this->SetAttribute($cat, 'name', 'Q' . $qtr);
						foreach ($cats->CategoryAttrs as $attr => $value)
							$this->SetAttribute($cat, $attr, $value);
						$this->Chart_DataRendered($cat);
						$el->appendChild($cat);
					}
				} elseif ($cats->Interval == EWR_GANTT_INTERVAL_MONTH) {
					$yrs = intval($arStart["year"]);
					$mons = intval($arStart["mon"]);
					$ms = $yrs * 12 + $mons;
					$yre = intval($arEnd["year"]);
					$mone = intval($arEnd["mon"]);
					$me = $yre * 12 + $mone;
					for ($m = $ms; $m <= $me; $m++) {
						$cat = $this->XmlDoc->createElement("category");
						$yr = floor($m/12);
						$mo = $m % 12;
						$yr = ($mo == 0) ? $yr - 1 : $yr;
						$mo = ($mo == 0) ? 12 : $mo;
						$dy = ewr_DaysInMonth($yr, $mo);
						$start = ($m == $ms) ? $this->DateTimeToStr($arStart) : $this->YMDToStr($yr, $mo, 1);
						$end = ($m == $me) ? $this->DateTimeToStr($arEnd) : $this->YMDToStr($yr, $mo, $dy);
						$this->SetAttribute($cat, 'start', $start);
						$this->SetAttribute($cat, 'end', $end);
						if ($start == $this->YMDToStr($yr, $mo, 1) && $end == $this->YMDToStr($yr, $mo, $dy)) // Complete month
							$this->SetAttribute($cat, 'name', ewr_MonthName($mo)); // Or ewr_FormatMonth
						foreach ($cats->CategoryAttrs as $attr => $value)
							$this->SetAttribute($cat, $attr, $value);
						$this->Chart_DataRendered($cat);
						$el->appendChild($cat);
					}
				} elseif ($cats->Interval == EWR_GANTT_INTERVAL_WEEK) {
					$ds = $this->StartDate;
					$de = $this->EndDate;
					for ($d = $ds; $d < $de; $d += 86400) {
						$dts = $this->GetDateTime($d);

						//$dte = $this->GetDateTime($d + 6*86400);
						$wday = $dts["wday"];
						$diff = ($wday >= EWR_GANTT_WEEK_START) ? ($wday - EWR_GANTT_WEEK_START) : ($wday + 7 - EWR_GANTT_WEEK_START);
						$ws = ($d == $ds) ? $ds : ($d - $diff * 86400);
						$we = ($d == $de) ? $de : ($d + (6 - $diff) * 86400);
						$d = $we;
						$cat = $this->XmlDoc->createElement("category");
						$this->SetAttribute($cat, 'start', $this->DateToStr($ws));
						$this->SetAttribute($cat, 'end', $this->DateToStr($we));
						if ($this->ShowWeekNumber && EWR_GANTT_WEEK_START == 1) { // Week start on Monday
							$this->SetAttribute($cat, 'name', $ReportLanguage->Phrase("Week") . " " . $this->FormatDate("W", $d));
						} else {
							$this->SetAttribute($cat, 'name', $ReportLanguage->Phrase("Week"));
						}
						foreach ($cats->CategoryAttrs as $attr => $value)
							$this->SetAttribute($cat, $attr, $value);
						$this->Chart_DataRendered($cat);
						$el->appendChild($cat);
					}
				} elseif ($cats->Interval == EWR_GANTT_INTERVAL_DAY) {
					$ds = $this->StartDate;
					$de = $this->EndDate;
					for ($d = $ds; $d <= $de; $d += 86400) {
						$dt = $this->GetDateTime($d);
						$md = $dt["mday"];
						$cat = $this->XmlDoc->createElement("category");
						$sdt = $this->DateTimeToStr($dt);
						$this->SetAttribute($cat, 'start', $sdt);
						$this->SetAttribute($cat, 'end', $sdt);
						$this->SetAttribute($cat, 'name', $md);
						foreach ($cats->CategoryAttrs as $attr => $value)
							$this->SetAttribute($cat, $attr, $value);
						$this->Chart_DataRendered($cat);
						$el->appendChild($cat);
					}
				}
			}
		}
	}

	// Output Data Table
	function OutputDataTable() {
		if ($this->ProcessTable == "" || empty($this->DataColumns))
			return;
		$dt = $this->XmlDoc->createElement("dataTable");
		foreach ($this->DataTableAttrs as $attr => $value)
			$this->SetAttribute($dt, $attr, $value);
		$this->Chart_DataRendered($dt);
		$this->XmlDoc->documentElement->appendChild($dt);
		$sql = "SELECT * FROM " . $this->ProcessTable;
		$conn = &ReportConn($this->ProcessTableDBID);
		$rs = $conn->Execute($sql);
		if ($rs && !$rs->EOF) {
			$i = 0;
			foreach ($this->DataColumns as $dc) {
				$col = $this->XmlDoc->createElement("dataColumn");
				if (!isset($dc->ColumnAttrs['headerbgColor']))
					$dc->ColumnAttrs['headerbgColor'] = $this->HeaderColor;
				if (!isset($dc->ColumnAttrs['headerFontColor']))
					$dc->ColumnAttrs['headerFontColor'] = $this->HeaderFontColor;
				if (!isset($dc->ColumnAttrs['bgColor']))
					$dc->ColumnAttrs['bgColor'] = $this->CategoryColor;
				if (!isset($dc->ColumnAttrs['fontColor']))
					$dc->ColumnAttrs['fontColor'] = $this->CategoryFontColor;
				foreach ($dc->ColumnAttrs as $attr => $value)
					$this->SetAttribute($col, $attr, $value);
				$this->SetAttribute($col, 'headerText', $dc->Caption); // Column header
				$this->Chart_DataRendered($col);
				$dt->appendChild($col);
				$rs->MoveFirst();
				while (!$rs->EOF) {
					$txt = $this->XmlDoc->createElement("text");
					foreach ($dc->TextAttrs as $attr => $value)
						$this->SetAttribute($txt, $attr, $value);
					$fldval = $rs->fields[$dc->FieldName];
					$formatfunc = $dc->FormatFunction;
					if ($formatfunc <> "" && function_exists($formatfunc))
						$fldval = $formatfunc($fldval);
					$this->SetAttribute($txt, 'label', $fldval);
					$this->Chart_DataRendered($txt);
					$col->appendChild($txt);
					$rs->MoveNext();
				}
				$i++;
			}
			$rs->Close();
		}
	}

	// Task table order by
	function TaskTableOrderBy() {
		return " ORDER BY " . ewr_QuotedName($this->TaskStartField, $this->TaskTableDBID);
	}

	// Output Tasks
	function OutputTasks() {
		$tasks = $this->XmlDoc->createElement("tasks");
		foreach ($this->TasksAttrs as $attr => $value)
			$this->SetAttribute($tasks, $attr, $value);
		$this->Chart_DataRendered($tasks);
		$this->XmlDoc->documentElement->appendChild($tasks);
		$sql = "SELECT * FROM " . $this->TaskTable;
		if ($this->TaskFilter <> "") $sql .= " WHERE " . $this->TaskFilter;
		$sql .= $this->TaskTableOrderBy();
		$rs = $this->Connection->Execute($sql);
		if ($rs) {
			$arFields = array(strtolower($this->TaskIdField), strtolower($this->TaskNameField), strtolower($this->TaskStartField), strtolower($this->TaskEndField),
				strtolower($this->TaskResourceIdField), strtolower($this->TaskDurationField), strtolower($this->TaskPercentCompleteField), strtolower($this->TaskDependenciesField));
			$cnt = 0;
			while (!$rs->EOF) {
				$task = $this->XmlDoc->createElement("task");
				foreach ($this->TaskAttrs as $attr => $value) {
					if (!in_array(strtolower($attr), $arFields))
						$this->SetAttribute($task, $attr, $value);
				}
				foreach ($rs->fields as $name => $value) {
					if (!is_numeric($name) && !in_array(strtolower($name), $arFields))
						$this->SetAttribute($task, strtolower($name), $value);
				}
				if ($this->ProcessTable == "") // No process table, set up process id from task id
					$this->SetAttribute($task, 'processid', $rs->fields($this->TaskIdField));
				$this->SetAttribute($task, 'id', $rs->fields($this->TaskIdField));
				$this->SetAttribute($task, 'name', $rs->fields($this->TaskNameField));
				$this->SetAttribute($task, 'start', $this->DBDateToStr($rs->fields($this->TaskStartField)));
				$this->SetAttribute($task, 'end', $this->DBDateToStr($rs->fields($this->TaskEndField)));
				if ($this->TaskResourceIdField <> "")
					$this->SetAttribute($task, 'resourceId', $rs->fields($this->TaskResourceIdField));
				if ($this->TaskDurationField <> "")
					$this->SetAttribute($task, 'duration', $rs->fields($this->TaskDurationField));
				if ($this->TaskPercentCompleteField <> "")
					$this->SetAttribute($task, 'percentComplete', $rs->fields($this->TaskPercentCompleteField));
				if ($this->TaskDependenciesField <> "")
					$this->SetAttribute($task, 'dependencies', $rs->fields($this->TaskDependenciesField));
				if ($task->getAttribute('color') == "") {
					$color = $this->GetTaskColor($cnt);
					if ($color <> "")
						$this->SetAttribute($task, 'color', $color);
				}
				$this->Chart_DataRendered($task);
				$tasks->appendChild($task);
				$rs->MoveNext();
				$cnt++;
			}
			$rs->Close();
		}
	}

	// Process table order by
	function ProcessTableOrderBy() {
		return "";
	}

	// Output processes
	function OutputProcesses() {
		if (!isset($this->ProcessesAttrs['bgColor']))
			$this->ProcessesAttrs['bgColor'] = $this->HeaderColor;
		if (!isset($this->ProcessesAttrs['fontColor']))
			$this->ProcessesAttrs['fontColor'] = $this->HeaderFontColor;
		if (!isset($this->ProcessesAttrs['headerBgColor']))
			$this->ProcessesAttrs['headerBgColor'] = $this->HeaderColor;
		if (!isset($this->ProcessesAttrs['headerFontColor']))
			$this->ProcessesAttrs['headerFontColor'] = $this->HeaderFontColor;
		if (!isset($this->ProcessesAttrs['headerText']))
			$this->ProcessesAttrs['headerText'] = $this->ProcessesHeaderText;
		if (!isset($this->ProcessesAttrs['isBold']))
			$this->ProcessesAttrs['isBold'] = $this->HeaderIsBold;
		if ($this->ProcessTable <> "") { // Use process table
			$processes = $this->XmlDoc->createElement("processes");
			foreach ($this->ProcessesAttrs as $attr => $value)
				$this->SetAttribute($processes, $attr, $value);
			$this->Chart_DataRendered($processes);
			$this->XmlDoc->documentElement->appendChild($processes);
			$sql = "SELECT * FROM " . $this->ProcessTable;
			$sql .= $this->ProcessTableOrderBy();
			$conn = &ReportConn($this->ProcessTableDBID);
			$rs = $conn->Execute($sql);
			if ($rs) {
				while (!$rs->EOF) {
					$process = $this->XmlDoc->createElement("process");
					foreach ($this->ProcessAttrs as $attr => $value)
						$this->SetAttribute($process, $attr, $value);
					foreach ($rs->fields as $name => $value) {
						if (!is_numeric($name))
							$this->SetAttribute($process, $name, $value);
					}
					$this->Chart_DataRendered($process);
					$processes->appendChild($process);
					$rs->MoveNext();
				}
				$rs->Close();
			}
		} else { // Use task table as process table
			$fid = $this->TaskIdField;
			$fname = $this->TaskNameField;
			$fstart = $this->TaskStartField;
			$sql = "SELECT DISTINCT " . ewr_QuotedName($fid, $this->TaskTableDBID) . " AS id, " .
				ewr_QuotedName($fname, $this->TaskTableDBID) . " AS name, " . ewr_QuotedName($fstart, $this->TaskTableDBID) . " FROM " . $this->TaskTable;
			if ($this->TaskIdFilter <> "" || $this->TaskNameFilter <> "") {
				$sql .= " WHERE ";
				if ($this->TaskIdFilter <> "")
					$sql .= $this->TaskIdFilter;
				if ($this->TaskNameFilter <> "")
					$sql .= ($this->TaskIdFilter <> "" ? " AND " : "") . $this->TaskNameFilter;
			}
			$sql .= $this->TaskTableOrderBy();
			$this->OutputQuery($sql, $this->TaskTableDBID, 'processes', 'process', $this->ProcessesAttrs, $this->ProcessAttrs);
		}
	}

	// Show Google Chart
	// - does not support Categories / Milestones / Trendlines / Connectors
	function ShowGoogleChart($width, $height) {
		global $EWR_USE_TIMELINE;
		$id = $this->ID;
		$xml = $this->Xml();
		$ar = ewr_XmlToArray($xml);
		$useGanttChart = (!$EWR_USE_TIMELINE || $this->ProcessTable == ""); // Check if use Gantt chart or Timeline chart

		// Get options
		$options = @$ar["chart"]["options"][0]["value"];
		if ($options <> "") // Decode user options to array
			$options = json_decode($options, TRUE); // Options must be UTF-8 encoded
		if (!is_array($options))
			$options = array();

		// Get processes
		$processes = @$ar["chart"]["processes"]["process"];

		// Get tasks
		$tasks = @$ar["chart"]["tasks"]["task"];
		$ar = array();
		if (is_array($tasks)) {
			foreach ($tasks as $task_id => $task) {
				$task_id = strval($task_id);
				$task_name = @$task["attr"]["name"];

				// Get resource
				$resource = @$task["attr"]["resourceId"];
				if ($resource == "") {
					$processid = @$task["attr"]["processid"];
					if ($processid <> "") {
						$resource = $processid;
						if (is_array($processes)) {
							foreach ($processes as $pid => $process) {
								if ($pid == $processid) {
									$resource = @$process["attr"]["name"];

									// $attrs = @$process["attr"];
									// if (is_array($attrs)) {
									// 	foreach ($attrs as $attrid => $attr) {
									// 		if ($attrid <> "id" && $attrid <> "name")
									// 			$resource .= " (" . $attr . ")";
									// 	}
									// }

									break;
								}
							}
						}
					}
				}
				$start_date = str_replace("/", "-", @$task["attr"]["start"]); // ISO 8601 date
				$end_date = str_replace("/", "-", @$task["attr"]["end"]); // ISO 8601 date
				$duration = @$task["attr"]["duration"] ?: NULL;
				$percent_complete = @$task["attr"]["percentComplete"] ?: 0;
				$dependency = @$task["attr"]["dependencies"] ?: @$task["attr"]["fromtaskid"] ?: NULL;
				if ($useGanttChart) { // Use Gantt chart
					$ar[] = array($task_id, $task_name, $resource, $start_date, $end_date, $duration, $percent_complete, $dependency);
				} else { // Use Timeline chart as Gantt chart
					$ar[] = array($resource, $task_name, $start_date, $end_date);
				}
			}
		}
		$wrk = "<div id=\"div_$id\"></div>\r\n";
		$wrk .= "<script type=\"text/javascript\">\r\n";
		$defOptions = array();
		if ($useGanttChart) { // Use Gantt chart
			$wrk .= "google.charts.load('current', {'packages':['gantt'], 'language': EWR_LANGUAGE_ID});\r\n";
			$wrk .= "google.charts.setOnLoadCallback(function() {\r\n";
			$wrk .= "\tvar \$chartdiv = jQuery('#div_$id').addClass('ewGantt');\r\n";
			$wrk .= "\tvar chart = new google.visualization.Gantt(\$chartdiv[0]);\r\n";
			$wrk .= "\tvar d = new google.visualization.DataTable();\r\n";
			$wrk .= "\td.addColumn('string', 'Task ID');\r\n";
			$wrk .= "\td.addColumn('string', 'Task Name');\r\n";
			$wrk .= "\td.addColumn('string', 'Resource');\r\n";
			$wrk .= "\td.addColumn('date', 'Start');\r\n";
			$wrk .= "\td.addColumn('date', 'End');\r\n";
			$wrk .= "\td.addColumn('number', 'Duration');\r\n";
			$wrk .= "\td.addColumn('number', 'Percent Complete');\r\n";
			$wrk .= "\td.addColumn('string', 'Dependencies');\r\n";
			$wrk .= "\tvar data = " . ewr_ConvertFromUtf8(json_encode($ar)) . ";\r\n";
			$wrk .= "\tfor (var i = 0; i < data.length; i++)\r\n";
			$wrk .= "\t\td.addRow([data[i][0], data[i][1], data[i][2], new Date(data[i][3]), new Date(data[i][4]), data[i][5], data[i][6], data[i][7]]);\r\n";
			$defOptions["gantt"]["labelStyle"] = array("fontName" => EWR_FONT_NAME, "fontSize" => EWR_FONT_SIZE);
		} else { // Use Timelines chart as Gantt chart
			$wrk .= "google.charts.load('current', {'packages':['timeline'], 'language': EWR_LANGUAGE_ID});\r\n";
			$wrk .= "google.charts.setOnLoadCallback(function() {\r\n";
			$wrk .= "\tvar \$chartdiv = jQuery('#div_$id').addClass('ewTimeline');\r\n";
			$wrk .= "\tvar chart = new google.visualization.Timeline(\$chartdiv[0]);\r\n";
			$wrk .= "\tvar d = new google.visualization.DataTable();\r\n";
			$wrk .= "\td.addColumn('string', 'Resource');\r\n";
			$wrk .= "\td.addColumn('string', 'Task Name');\r\n";
			$wrk .= "\td.addColumn('date', 'Start');\r\n";
			$wrk .= "\td.addColumn('date', 'End');\r\n";
			$wrk .= "\tvar data = " . ewr_ConvertFromUtf8(json_encode($ar)) . ";\r\n";
			$wrk .= "\tfor (var i = 0; i < data.length; i++)\r\n";
			$wrk .= "\t\td.addRow([data[i][0], data[i][1], new Date(data[i][3]), new Date(data[i][4])]);\r\n";

			//$defOptions = array("fontName" => EWR_FONT_NAME, "fontSize" => EWR_FONT_SIZE); // Does not work
			$defOptions["timeline"]["rowLabelStyle"] = array("fontName" => EWR_FONT_NAME, "fontSize" => EWR_FONT_SIZE);
			$defOptions["timeline"]["barLabelStyle"] = array("fontName" => EWR_FONT_NAME, "fontSize" => EWR_FONT_SIZE);
		}
		if ($width > 0)
			$defOptions["width"] = $width;
		if ($height > 0)
			$defOptions["height"] = $height;
		$options = array_merge($defOptions, $options);
		$wrk .= "\tvar options = " . ewr_ConvertFromUtf8(json_encode($options)) . ";\r\n";
		$chartid = "chart_" . $id;
		$wrk .= "\tewrExportCharts[ewrExportCharts.length] = { 'id': '$chartid', 'chart': chart };\r\n"; // Export chart
		$wrk .= "\tvar args = { 'id': 'chart_$id', 'chart': chart, 'data': d, 'options': options };\r\n";
		$wrk .= "\tjQuery(document).trigger('draw', [args]);\r\n";
		$wrk .= "\tchart.draw(args.data, args.options);\r\n";
		if (EWR_DEBUG_ENABLED)
			$wrk .= "\tconsole.log(args);\r\n";
		$wrk .= "});\r\n";
		$wrk .= "</script>\r\n";
		if (EWR_DEBUG_ENABLED) {
			$doc = new DOMDocument();
			$doc->loadXML($xml);
			$doc->formatOutput = TRUE;
			ewr_SetDebugMsg("(Chart XML):<pre>" . ewr_HtmlEncode(ewr_ConvertFromUtf8($doc->saveXML())) . "</pre>");
			ewr_SetDebugMsg("(Chart JSON):<pre>" . ewr_ConvertFromUtf8(json_encode($ar, JSON_PRETTY_PRINT)) . "</pre>");
		}
		return $wrk;
	}

	// Show FusionChart
	function ShowFusionChart($width, $height) {
		global $Page;
		$id = $this->ID;
		$xml = $this->Xml();
		$wrk = "<div id=\"div_" . $id  . "\"></div>\r\n";
		$wrk .= "<script type='text/javascript'>\r\n";
		$wrk .= "var chartwidth = $width, chartheight = $height, chartid = 'chart_$id', chartdivid = 'div_$id';\r\n";
		$wrk .= "var chartxml = \"" . ewr_EscapeJs(ewr_ConvertFromUtf8($xml)) . "\";\r\n";
		$wrk .= "var chartjson = FusionCharts.transcodeData(chartxml, 'xml', 'json');\r\n";
		$wrk .= "var chartoptions = { 'type': 'gantt', 'id': chartid, 'renderAt': chartdivid, 'width': chartwidth, 'height': chartheight, 'dataFormat': 'json', 'dataSource': chartjson };\r\n";
		$wrk .= "jQuery(document).trigger('draw', [chartoptions]);\r\n";
		$wrk .= "var cht_$id = new FusionCharts(chartoptions);\r\n";
		$wrk .= "cht_$id.render();\r\n";
		$wrk .= "ewrExportCharts[ewrExportCharts.length] = cht_$id.id;\r\n"; // Export chart
		if (EWR_DEBUG_ENABLED) {
			$wrk .= "FusionCharts['debugger'].enable(true, function(message) { console.log(message); });\r\n";
			$wrk .= "console.log(chartoptions);\r\n";
		}
		$wrk .= "</script>\r\n";
		if (EWR_DEBUG_ENABLED) {
			$doc = new DOMDocument();
			$doc->loadXML($xml);
			$doc->formatOutput = TRUE;
			ewr_SetDebugMsg("(Chart XML):<pre>" . ewr_HtmlEncode(ewr_ConvertFromUtf8($doc->saveXML())) . "</pre>");
		}
		return $wrk;
	}

	// Show temp image
	function ShowTempImage() {
		global $gsExport;
		$chartid = "chart_" . $this->ID;
		$tmpChartImage = ewr_TmpChartImage($chartid, FALSE);
		$wrk = "";
		if ($tmpChartImage <> "") {
			$wrk = "<img src=\"" . $tmpChartImage . "\" alt=\"\">";
			if ($gsExport == "word" && defined('EWR_USE_PHPWORD') || $gsExport == "excel" && defined('EWR_USE_PHPEXCEL'))
				$wrk = "<table class=\"ewChart\"><tr><td>" . $wrk . "</td></tr></table>";
			else
				$wrk = "<div class=\"ewChart\">" . $wrk . "</div>";
		}
		return $wrk;
	}

	// Render gantt chart
	function Render($width = -1, $height = -1) {
		global $gsExport, $gsCustomExport, $EWR_NON_FUSIONCHARTS;

		// Check chart size
		$this->CheckSize($width, $height);
		$wrkwidth = $width;
		$wrkheight = $height;
		if ($gsExport == "" || $gsExport == "print" && $gsCustomExport == "" || $gsExport == "email" && @$_POST["contenttype"] == "url") {
			if ($EWR_NON_FUSIONCHARTS)
				echo $this->ShowFusionChart($wrkwidth, $wrkheight);
			else
				echo $this->ShowGoogleChart($wrkwidth, $wrkheight);
		} elseif ($gsExport == "pdf" || $gsCustomExport <> "" || $gsExport == "email" || $gsExport == "excel" && defined("EWR_USE_PHPEXCEL") || $gsExport == "word" && defined("EWR_USE_PHPWORD")) { // Show temp image
			echo $this->ShowTempImage();
		}
	}

	// Output XML
	function Xml() {
		$dbid = $this->Table->DBID;

		// Start/End dates
		$this->SetupStartEnd();

		// Chart_Rendering event
		$this->Chart_Rendering();

		// Chart attributes
		foreach ($this->ChartAttrs as $attr => $value)
			$this->SetAttribute($this->XmlDoc->documentElement, $attr, $value);
		$this->SetAttribute($this->XmlDoc->documentElement, 'dateFormat', $this->DateFormat);
		$this->Chart_DataRendered($this->XmlDoc->documentElement);

		// Categories
		$this->OutputCategories();

		// Processes
		$this->OutputProcesses();

		// DataTable
		$this->OutputDataTable();

		// Tasks
		$this->OutputTasks();

		// Milestones
		if ($this->MilestoneTable <> "") {
			$sql = "SELECT * FROM " . $this->MilestoneTable;
			$this->OutputQuery($sql, $this->MilestoneTableDBID, 'milestones', 'milestone', array(), $this->MilestoneAttrs);
		} elseif ($this->TaskMilestoneDateField <> "") { // Use task table as milestone table
			$sql = "SELECT " . ewr_QuotedName($this->TaskIdField, $this->TaskTableDBID) . " AS ". ewr_QuotedName('taskId', $this->TaskTableDBID) . ", " .
				ewr_QuotedName($this->TaskMilestoneDateField, $this->TaskTableDBID) . " AS " . ewr_QuotedName('date', $this->TaskTableDBID) .
				" FROM " . $this->TaskTable .
				" WHERE " . ewr_QuotedName($this->TaskMilestoneDateField, $this->TaskTableDBID) . " IS NOT NULL";
			$this->OutputQuery($sql, $this->TaskTableDBID, 'milestones', 'milestone', array(), $this->MilestoneAttrs);
		}
		if (count($this->Milestones) > 0)
			$this->OutputArray($this->Milestones, 'milestones', 'milestone', array(), $this->MilestoneAttrs);

		// Trendlines
		if ($this->TrendlineTable <> "") {
			$sql = "SELECT * FROM " . $this->TrendlineTable;
			$this->OutputQuery($sql, $this->TrendlineTableDBID, 'trendlines', 'line', array(), $this->TrendlineAttrs);
		}
		if (count($this->Trendlines) > 0)
			$this->OutputArray($this->Trendlines, 'trendlines', 'line', array(), $this->TrendlineAttrs);

		// Connectors
		if ($this->ConnectorTable <> "") {
			$sql = "SELECT * FROM " . $this->ConnectorTable;
			$this->OutputQuery($sql, $this->ConnectorTableDBID, 'connectors', 'connector', $this->ConnectorsAttrs, $this->ConnectorAttrs);
		} elseif ($this->TaskFromTaskIdField <> "") { // Use task table as connector table
			$sql = "SELECT " . ewr_QuotedName($this->TaskFromTaskIdField, $this->TaskTableDBID) . " AS ". ewr_QuotedName('fromTaskId', $this->TaskTableDBID) . ", " .
				ewr_QuotedName($this->TaskIdField, $this->TaskTableDBID) . " AS ". ewr_QuotedName('toTaskId', $this->TaskTableDBID) . " FROM " . $this->TaskTable;
			$this->OutputQuery($sql, $this->TaskTableDBID, 'connectors', 'connector', $this->ConnectorsAttrs, $this->ConnectorAttrs);
		}
		if (count($this->Connectors) > 0)
			$this->OutputArray($this->Connectors, 'connectors', 'connector', $this->ConnectorsAttrs, $this->ConnectorAttrs);

		// Get the XML
		$xml = $this->XmlDoc->saveXML();

		// Chart_Rendered event
		$this->Chart_Rendered($xml);

		// Output
		return $xml;
	}
}
?>
