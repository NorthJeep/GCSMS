<?php

// Functions for PHP Report Maker 11
// (C) 2007-2018 e.World Technology Limited
// Auto load class
function ewr_AutoLoad($class) {
	global $EWR_RELATIVE_PATH;
	$file = "";
	if ($class == "GD") {
		$file = "phprptinc/PHPThumb.php";
	} elseif ($class == "Hybrid_Auth") {
		$file = "hybridauth/Hybrid/Auth.php";
	} elseif (preg_match('/^cr\w+_db$/', $class)) {
		$file = "rdbhelper11.php";
	} elseif (ewr_StartsStr("cr", $class)) {
		$file = str_replace("%cls%", substr($class, 2), "%cls%rptinfo.php");
	}
	if ($file <> "" && file_exists($EWR_RELATIVE_PATH . $file))
		include_once $EWR_RELATIVE_PATH . $file;
}

// Create Database helper class
if (!function_exists("ReportDbHelper")) {

	function &ReportDbHelper($dbid = "") {
		$dbclass = "crg26csms_db_db";
		$dbhelper = new $dbclass();
		return $dbhelper;
	}
}
spl_autoload_register("ewr_AutoLoad");

// Get security object
if (!function_exists("Security")) {

	function &Security() {
		return $GLOBALS["Security"];
	}
}

// Get profile object
if (!function_exists("Profile")) {

	function &Profile($name = "", $value = NULL) {
		$profile = $GLOBALS["UserProfile"];
		if (!$profile)
			$profile = new crUserProfile();
		$numargs = func_num_args();
		if ($numargs == 1) { // Get
			$value = $profile->Get($name);
			return $value;
		} elseif ($numargs == 2) { // Set
			$profile->Set($name, $value);
			$profile->Save();
		}
		return $profile;
	}
}

// Get language object
if (!function_exists("Language")) {

	function &Language() {
		return $GLOBALS["ReportLanguage"];
	}
}

// Get breadcrumb object
if (!function_exists("Breadcrumb")) {

	function &Breadcrumb() {
		return $GLOBALS["ReportBreadcrumb"];
	}
}

// Is admin
if (!function_exists("IsAdmin")) {

	function IsAdmin() {
		global $Security;
		return (isset($Security)) ? $Security->IsAdmin() : (@$_SESSION[EWR_SESSION_SYS_ADMIN] == 1);
	}
}

// Get page object
function &Page($tblname = "") {
	if (!$tblname)
		return $GLOBALS["Page"];
	foreach ($GLOBALS as $k => $v) {
		if (is_object($v) && $k == $tblname)
			return $GLOBALS[$k];
	}
	$res = NULL;
	return $res;
}

// Get current language ID
function CurrentLanguageID() {
	return $GLOBALS["grLanguage"];
}

// Get current project ID
function CurrentProjectID() {
	if (isset($GLOBALS["Page"]))
		return $GLOBALS["Page"]->ProjectID;
	return "{234B495E-E8C1-4FF1-B18B-170E747447B8}";
}

// Get current page object
function &CurrentPage() {
	return $GLOBALS["Page"];
}

// Get current main table object
function &CurrentTable() {
	return $GLOBALS["Table"];
}

// Get user table object
function &UserTable() {
	return $GLOBALS["UserTable"];
}
/**
 * Langauge class for reports
 */

class crLanguage {
	var $LanguageId;
	var $Phrases = NULL;
	var $LanguageFolder = EWR_LANGUAGE_FOLDER;
	var $Template = ""; // JsRender template
	var $Method = "prependTo"; // JsRender template method
	var $Target = ".navbar-custom-menu .nav"; // JsRender template target
	var $Type = "LI"; // LI/DROPDOWN (for used with top Navbar) or SELECT/RADIO (NOT for used with top Navbar)

	// Constructor
	function __construct($langfolder = "", $langid = "") {
		global $grLanguage;
		if ($langfolder <> "")
			$this->LanguageFolder = $langfolder;
		$this->LoadFileList(); // Set up file list
		if ($langid <> "") { // Set up language id
			$this->LanguageId = $langid;
			$_SESSION[EWR_SESSION_LANGUAGE_ID] = $this->LanguageId;
		} elseif (@$_POST["language"] <> "") {
			$this->LanguageId = $_POST["language"];
			$_SESSION[EWR_SESSION_LANGUAGE_ID] = $this->LanguageId;
		} elseif (@$_GET["language"] <> "") {
			$this->LanguageId = $_GET["language"];
			$_SESSION[EWR_SESSION_LANGUAGE_ID] = $this->LanguageId;
		} elseif (@$_SESSION[EWR_SESSION_LANGUAGE_ID] <> "") {
			$this->LanguageId = $_SESSION[EWR_SESSION_LANGUAGE_ID];
		} else {
			$this->LanguageId = EWR_LANGUAGE_DEFAULT_ID;
		}
		$grLanguage = $this->LanguageId;
		$this->Load($this->LanguageId);

		// Call Language Load event
		$this->Language_Load();
	}

	// Load language file list
	function LoadFileList() {
		global $EWR_LANGUAGE_FILE;
		if (is_array($EWR_LANGUAGE_FILE)) {
			$cnt = count($EWR_LANGUAGE_FILE);
			for ($i = 0; $i < $cnt; $i++)
				$EWR_LANGUAGE_FILE[$i][1] = $this->LoadFileDesc($this->LanguageFolder . $EWR_LANGUAGE_FILE[$i][2]);
		}
	}

	// Load language file description
	function LoadFileDesc($File) {
		if (EWR_USE_DOM_XML) {
			$this->Phrases = new crXMLDocument();
			if ($this->Phrases->Load($File))
				return $this->GetNodeAtt($this->Phrases->DocumentElement(), "desc");
		} else {
			$ar = ewr_XmlToArray(substr(file_get_contents($File), 0, 512)); // Just read the first part
			return (is_array($ar)) ? @$ar['ew-language']['attr']['desc'] : "";
		}
	}

	// Load language file
	function Load($id) {
		global $EWR_DECIMAL_POINT, $EWR_THOUSANDS_SEP, $EWR_MON_DECIMAL_POINT, $EWR_MON_THOUSANDS_SEP,
			$EWR_CURRENCY_SYMBOL, $EWR_POSITIVE_SIGN, $EWR_NEGATIVE_SIGN, $EWR_FRAC_DIGITS,
			$EWR_P_CS_PRECEDES, $EWR_P_SEP_BY_SPACE, $EWR_N_CS_PRECEDES, $EWR_N_SEP_BY_SPACE,
			$EWR_P_SIGN_POSN, $EWR_N_SIGN_POSN, $EWR_TIME_ZONE,
			$EWR_DATE_SEPARATOR, $EWR_TIME_SEPARATOR, $EWR_DATE_FORMAT, $EWR_DATE_FORMAT_ID;
		$sFileName = $this->GetFileName($id);
		if ($sFileName == "")
			$sFileName = $this->GetFileName(EWR_LANGUAGE_DEFAULT_ID);
		if ($sFileName == "")
			return;
		if (EWR_USE_DOM_XML) {
			$this->Phrases = new crXMLDocument();
			$this->Phrases->Load($sFileName);
		} else {
			if (is_array(@$_SESSION[EWR_PROJECT_NAME . "_" . $sFileName])) {
				$this->Phrases = $_SESSION[EWR_PROJECT_NAME . "_" . $sFileName];
			} else {
				$this->Phrases = ewr_XmlToArray(file_get_contents($sFileName));
			}
		}

		// Set up locale / currency format for language
		extract(ewr_LocaleConv());
		if (!empty($decimal_point)) $EWR_DECIMAL_POINT = $decimal_point;
		if (!empty($thousands_sep)) $EWR_THOUSANDS_SEP = $thousands_sep;
		if (!empty($mon_decimal_point)) $EWR_MON_DECIMAL_POINT = $mon_decimal_point;
		if (!empty($mon_thousands_sep)) $EWR_MON_THOUSANDS_SEP = $mon_thousands_sep;
		if (!empty($currency_symbol)) $EWR_CURRENCY_SYMBOL = $currency_symbol;
		if (isset($positive_sign)) $EWR_POSITIVE_SIGN = $positive_sign; // Note: $positive_sign can be empty.
		if (!empty($negative_sign)) $EWR_NEGATIVE_SIGN = $negative_sign;
		if (isset($frac_digits)) $EWR_FRAC_DIGITS = $frac_digits;
		if (isset($p_cs_precedes)) $EWR_P_CS_PRECEDES = $p_cs_precedes;
		if (isset($p_sep_by_space)) $EWR_P_SEP_BY_SPACE = $p_sep_by_space;
		if (isset($n_cs_precedes)) $EWR_N_CS_PRECEDES = $n_cs_precedes;
		if (isset($n_sep_by_space)) $EWR_N_SEP_BY_SPACE = $n_sep_by_space;
		if (isset($p_sign_posn)) $EWR_P_SIGN_POSN = $p_sign_posn;
		if (isset($n_sign_posn)) $EWR_N_SIGN_POSN = $n_sign_posn;
		if (!empty($date_sep)) $EWR_DATE_SEPARATOR = $date_sep;
		if (!empty($time_sep)) $EWR_TIME_SEPARATOR = $time_sep;
		if (!empty($date_format)) {
			$EWR_DATE_FORMAT = ewr_DateFormat($date_format);
			$EWR_DATE_FORMAT_ID = ewr_DateFormatId($date_format);
		}
		/**
		 * Time zone
		 * Read http://www.php.net/date_default_timezone_set for details
		 * and http://www.php.net/timezones for supported time zones
		*/

		// Set up time zone from language file for multi-language site
		if (!empty($time_zone))
			$EWR_TIME_ZONE = $time_zone;
		if (!empty($EWR_TIME_ZONE))
			date_default_timezone_set($EWR_TIME_ZONE);
	}

	// Get language file name
	function GetFileName($Id) {
		global $EWR_LANGUAGE_FILE;
		if (is_array($EWR_LANGUAGE_FILE)) {
			$cnt = count($EWR_LANGUAGE_FILE);
			for ($i = 0; $i < $cnt; $i++)
				if ($EWR_LANGUAGE_FILE[$i][0] == $Id) {
					return $this->LanguageFolder . $EWR_LANGUAGE_FILE[$i][2];
				}
		}
		return "";
	}

	// Get node attribute
	function GetNodeAtt($Nodes, $Att) {
		$value = ($Nodes) ? $this->Phrases->GetAttribute($Nodes, $Att) : "";

		//return ewr_ConvertFromUtf8($value);
		return $value;
	}

	// Set node attribute
	function SetNodeAtt($Nodes, $Att, $Value) {
		if ($Nodes)
			$this->Phrases->SetAttribute($Nodes, $Att, $Value);
	}

	// Get phrase
	function Phrase($Id, $UseText = FALSE) {
		if (is_object($this->Phrases)) {
			$ImageUrl = $this->GetNodeAtt($this->Phrases->SelectSingleNode("//global/phrase[@id='" . strtolower($Id) . "']"), "imageurl");
			$ImageWidth = $this->GetNodeAtt($this->Phrases->SelectSingleNode("//global/phrase[@id='" . strtolower($Id) . "']"), "imagewidth");
			$ImageHeight = $this->GetNodeAtt($this->Phrases->SelectSingleNode("//global/phrase[@id='" . strtolower($Id) . "']"), "imageheight");
			$ImageClass = $this->GetNodeAtt($this->Phrases->SelectSingleNode("//global/phrase[@id='" . strtolower($Id) . "']"), "class");
			$Text = $this->GetNodeAtt($this->Phrases->SelectSingleNode("//global/phrase[@id='" . strtolower($Id) . "']"), "value");
		} elseif (is_array($this->Phrases)) {
			$ImageUrl = ewr_ConvertFromUtf8(@$this->Phrases['ew-language']['global']['phrase'][strtolower($Id)]['attr']['imageurl']);
			$ImageWidth = ewr_ConvertFromUtf8(@$this->Phrases['ew-language']['global']['phrase'][strtolower($Id)]['attr']['imagewidth']);
			$ImageHeight = ewr_ConvertFromUtf8(@$this->Phrases['ew-language']['global']['phrase'][strtolower($Id)]['attr']['imageheight']);
			$ImageClass = ewr_ConvertFromUtf8(@$this->Phrases['ew-language']['global']['phrase'][strtolower($Id)]['attr']['class']);
			$Text = ewr_ConvertFromUtf8(@$this->Phrases['ew-language']['global']['phrase'][strtolower($Id)]['attr']['value']);
		}
		if (!$UseText && $ImageClass <> "") {
			return "<span data-phrase=\"" . $Id . "\" class=\"" . $ImageClass . "\" data-caption=\"" . ewr_HtmlEncode($Text) . "\"></span>";
		} elseif (!$UseText && $ImageUrl <> "") {
			$style = ($ImageWidth <> "") ? "width: " . $ImageWidth . "px;" : "";
			$style .= ($ImageHeight <> "") ? "height: " . $ImageHeight . "px;" : "";
			return "<img data-phrase=\"" . $Id . "\" src=\"" . ewr_HtmlEncode($ImageUrl) . "\" style=\"" . $style . "\" alt=\"" . ewr_HtmlEncode($Text) . "\" title=\"" . ewr_HtmlEncode($Text) . "\">";
		} else {
			return $Text;
		}
	}

	// Set phrase
	function setPhrase($Id, $Value) {
		$this->setPhraseAttr($Id, "value", $Value);
	}

	// Get project phrase
	function ProjectPhrase($Id) {
		if (is_object($this->Phrases)) {
			return $this->GetNodeAtt($this->Phrases->SelectSingleNode("//project/phrase[@id='" . strtolower($Id) . "']"), "value");
		} elseif (is_array($this->Phrases)) {
			return ewr_ConvertFromUtf8(@$this->Phrases['ew-language']['project']['phrase'][strtolower($Id)]['attr']['value']);
		}
	}

	// Set project phrase
	function setProjectPhrase($Id, $Value) {
		if (is_object($this->Phrases)) {
			$this->SetNodeAtt($this->Phrases->SelectSingleNode("//project/phrase[@id='" . strtolower($Id) . "']"), "value", $Value);
		} elseif (is_array($this->Phrases)) {
			$this->Phrases['ew-language']['project']['phrase'][strtolower($Id)]['attr']['value'] = $Value;
		}
	}

	// Get menu phrase
	function MenuPhrase($MenuId, $Id) {
		if (is_object($this->Phrases)) {
			return $this->GetNodeAtt($this->Phrases->SelectSingleNode("//project/menu[@id='" . $MenuId . "']/phrase[@id='" . strtolower($Id) . "']"), "value");
		} elseif (is_array($this->Phrases)) {
			return ewr_ConvertFromUtf8(@$this->Phrases['ew-language']['project']['menu'][$MenuId]['phrase'][strtolower($Id)]['attr']['value']);
		}
	}

	// Set menu phrase
	function setMenuPhrase($MenuId, $Id, $Value) {
		if (is_object($this->Phrases)) {
			$this->SetNodeAtt($this->Phrases->SelectSingleNode("//project/menu[@id='" . $MenuId . "']/phrase[@id='" . strtolower($Id) . "']"), "value", $Value);
		} elseif (is_array($this->Phrases)) {
			$this->Phrases['ew-language']['project']['menu'][$MenuId]['phrase'][strtolower($Id)]['attr']['value'] = $Value;
		}
	}

	// Get table phrase
	function TablePhrase($TblVar, $Id) {
		if (is_object($this->Phrases)) {
			return $this->GetNodeAtt($this->Phrases->SelectSingleNode("//project/table[@id='" . strtolower($TblVar) . "']/phrase[@id='" . strtolower($Id) . "']"), "value");
		} elseif (is_array($this->Phrases)) {
			return ewr_ConvertFromUtf8(@$this->Phrases['ew-language']['project']['table'][strtolower($TblVar)]['phrase'][strtolower($Id)]['attr']['value']);
		}
	}

	// Set table phrase
	function setTablePhrase($TblVar, $Id, $Value) {
		if (is_object($this->Phrases)) {
			$this->SetNodeAtt($this->Phrases->SelectSingleNode("//project/table[@id='" . strtolower($TblVar) . "']/phrase[@id='" . strtolower($Id) . "']"), "value", $Value);
		} elseif (is_array($this->Phrases)) {
			$this->Phrases['ew-language']['project']['table'][strtolower($TblVar)]['phrase'][strtolower($Id)]['attr']['value'] = $Value;
		}
	}

	// Get chart phrase
	function ChartPhrase($TblVar, $ChtVar, $Id) {
		if (is_object($this->Phrases)) {
			return $this->GetNodeAtt($this->Phrases->SelectSingleNode("//project/table[@id='" . strtolower($TblVar) . "']/chart[@id='" . strtolower($ChtVar) . "']/phrase[@id='" . strtolower($Id) . "']"), "value");
		} elseif (is_array($this->Phrases)) {
			return ewr_ConvertFromUtf8(@$this->Phrases['ew-language']['project']['table'][strtolower($TblVar)]['chart'][strtolower($ChtVar)]['phrase'][strtolower($Id)]['attr']['value']);
		}
	}

	// Set chart phrase
	function setChartPhrase($TblVar, $FldVar, $Id, $Value) {
		if (is_object($this->Phrases)) {
			$this->SetNodeAtt($this->Phrases->SelectSingleNode("//project/table[@id='" . strtolower($TblVar) . "']/chart[@id='" . strtolower($ChtVar) . "']/phrase[@id='" . strtolower($Id) . "']"), "value", $Value);
		} elseif (is_array($this->Phrases)) {
			$this->Phrases['ew-language']['project']['table'][strtolower($TblVar)]['chart'][strtolower($FldVar)]['phrase'][strtolower($Id)]['attr']['value'] = $Value;
		}
	}

	// Get field phrase
	function FieldPhrase($TblVar, $FldVar, $Id) {
		if (is_object($this->Phrases)) {
			return $this->GetNodeAtt($this->Phrases->SelectSingleNode("//project/table[@id='" . strtolower($TblVar) . "']/field[@id='" . strtolower($FldVar) . "']/phrase[@id='" . strtolower($Id) . "']"), "value");
		} elseif (is_array($this->Phrases)) {
			return ewr_ConvertFromUtf8(@$this->Phrases['ew-language']['project']['table'][strtolower($TblVar)]['field'][strtolower($FldVar)]['phrase'][strtolower($Id)]['attr']['value']);
		}
	}

	// Set field phrase
	function setFieldPhrase($TblVar, $FldVar, $Id, $Value) {
		if (is_object($this->Phrases)) {
			$this->SetNodeAtt($this->Phrases->SelectSingleNode("//project/table[@id='" . strtolower($TblVar) . "']/field[@id='" . strtolower($FldVar) . "']/phrase[@id='" . strtolower($Id) . "']"), "value", $Value);
		} elseif (is_array($this->Phrases)) {
			$this->Phrases['ew-language']['project']['table'][strtolower($TblVar)]['field'][strtolower($FldVar)]['phrase'][strtolower($Id)]['attr']['value'] = $Value;
		}
	}

	// Get phrase attribute
	function PhraseAttr($Id, $Name) {
		if (is_object($this->Phrases)) {
			return $this->GetNodeAtt($this->Phrases->SelectSingleNode("//global/phrase[@id='" . strtolower($Id) . "']"), strtolower($Name));
		} elseif (is_array($this->Phrases)) {
			return ewr_ConvertFromUtf8(@$this->Phrases['ew-language']['global']['phrase'][strtolower($Id)]['attr'][strtolower($Name)]);
		}
		return "";
	}

	// Set phrase attribute
	function setPhraseAttr($Id, $Name, $Value) {
		if (is_object($this->Phrases)) {
			$Node = $this->Phrases->SelectSingleNode("//global/phrase[@id='" . strtolower($Id) . "']");
			if (!$Node) { // Create new phrase
				$Node = $this->Phrases->XmlDoc->createElement("phrase");
				$Node->setAttribute("id", $Id);
				$this->Phrases->SelectSingleNode("//global")->appendChild($Node);
			}
			$this->SetNodeAtt($Node, strtolower($Name), $Value);
		} elseif (is_array($this->Phrases)) {
			$this->Phrases['ew-language']['global']['phrase'][strtolower($Id)]['attr'][strtolower($Name)] = $Value;
		}
	}

	// Get phrase class
	function PhraseClass($Id) {
		return $this->PhraseAttr($Id, "class");
	}

	// Set phrase attribute
	function setPhraseClass($Id, $Value) {
		$this->setPhraseAttr($Id, "class", $Value);
	}

	// Output XML as JSON
	function XmlToJson($XPath) {
		$NodeList = $this->Phrases->SelectNodes($XPath);
		$Str = "{";
		foreach ($NodeList as $Node) {
			$Id = $this->GetNodeAtt($Node, "id");
			$Value = $this->GetNodeAtt($Node, "value");
			$Str .= "\"" . ewr_JsEncode2($Id) . "\":\"" . ewr_JsEncode2($Value) . "\",";
		}
		if (substr($Str, -1) == ",") $Str = substr($Str, 0, strlen($Str)-1);
		$Str .= "}";
		return $Str;
	}

	// Output array as JSON
	function ArrayToJson($client) {
		$ar = @$this->Phrases['ew-language']['global']['phrase'];
		$Str = "{";
		if (is_array($ar)) {
			foreach ($ar as $id => $node) {
				$is_client = @$node['attr']['client'] == '1';
				$value = ewr_ConvertFromUtf8(@$node['attr']['value']);
				if (!$client || ($client && $is_client))
					$Str .= "\"" . ewr_JsEncode2($id) . "\":\"" . ewr_JsEncode2($value) . "\",";
			}
		}
		if (substr($Str, -1) == ",") $Str = substr($Str, 0, strlen($Str)-1);
		$Str .= "}";
		return $Str;
	}

	// Output all phrases as JSON
	function AllToJson() {
		if (is_object($this->Phrases)) {
			return "var ewLanguage = new ewr_Language(" . $this->XmlToJson("//global/phrase") . ");";
		} elseif (is_array($this->Phrases)) {
			return "var ewLanguage = new ewr_Language(" . $this->ArrayToJson(FALSE) . ");";
		}
	}

	// Output client phrases as JSON
	function ToJson() {
		ewr_SetClientVar("languages", array("languages" => $this->GetLanguages()));
		if (is_object($this->Phrases)) {
			return "var ewLanguage = new ewr_Language(" . $this->XmlToJson("//global/phrase[@client='1']") . ");";
		} elseif (is_array($this->Phrases)) {
			return "var ewLanguage = new ewr_Language(" . $this->ArrayToJson(TRUE) . ");";
		}
	}

	// Output languages as array
	function GetLanguages() {
		global $EWR_LANGUAGE_FILE, $grLanguage;
		$ar = array();
		if (is_array($EWR_LANGUAGE_FILE)) {
			$cnt = count($EWR_LANGUAGE_FILE);
			if ($cnt > 1) {
				for ($i = 0; $i < $cnt; $i++) {
					$langid = $EWR_LANGUAGE_FILE[$i][0];
					$phrase = $this->Phrase($langid) ?: $EWR_LANGUAGE_FILE[$i][1];
					$ar[] = array("id" => $langid, "desc" => ewr_ConvertFromUtf8($phrase), "selected" => $langid == $grLanguage);
				}
			}
		}
		return $ar;
	}

	// Get template
	function GetTemplate() {
		if ($this->Template == "") {
			if (ewr_SameText($this->Type, "LI")) { // LI template (for used with top Navbar)
				return '{{for languages}}<li{{if selected}} class="active"{{/if}}><a href="#" class="ewTooltip" title="{{>desc}}" onclick="ewr_SetLanguage(this);" data-language="{{:id}}">{{:id}}</a></li>{{/for}}';
			} elseif (ewr_SameText($this->Type, "DROPDOWN")) { // DROPDOWN template (for used with top Navbar)
				return '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-globe ewIcon" aria-hidden="true"></span>&nbsp;<span class="caret"></span></a><ul class="dropdown-menu">{{for languages}}<li{{if selected}} class="active"{{/if}}><a href="#" onclick="ewr_SetLanguage(this);" data-language="{{:id}}">{{>desc}}</a></li>{{/for}}</ul></li>';
			} elseif (ewr_SameText($this->Type, "SELECT")) { // SELECT template (NOT for used with top Navbar)
				return '<div class="ewLanguageOption"><select class="form-control" id="ewLanguage" name="ewLanguage" onchange="ewr_SetLanguage(this);">{{for languages}}<option value="{{:id}}"{{if selected}} selected{{/if}}>{{:desc}}</option>{{/for}}</select></div>';
			} elseif (ewr_SameText($this->Type, "RADIO")) { // RADIO template (NOT for used with top Navbar)
				return '<div class="ewLanguageOption"><div class="btn-group" data-toggle="buttons">{{for languages}}<label class="btn btn-default ewTooltip" data-container="body" data-placement="bottom" title="{{>desc}}"><input type="radio" name="ewLanguage" autocomplete="off" onchange="ewr_SetLanguage(this);{{if selected}} checked{{/if}}" value="{{:id}}">{{:id}}</label>{{/for}}</div></div>';
			}
		}
		return $this->Template;
	}

	// Language Load event
	function Language_Load() {

		// Example:
		//$this->setPhrase("MyID", "MyValue"); // Refer to language file for the actual phrase id
		//$this->setPhraseClass("MyID", "glyphicon glyphicon-xxx ewIcon"); // Refer to http://getbootstrap.com/components/#glyphicons for icon name

	}
}

// Get numeric formatting information
function ewr_LocaleConv() {
	$langid = CurrentLanguageID();
	$localefile = EWR_LOCALE_FOLDER . strtolower($langid) . ".json";
	if (!file_exists($localefile)) // Locale file not found, fall back to English ("en") locale
		$localefile = EWR_LOCALE_FOLDER . "en.json";
	$locale = json_decode(file_get_contents($localefile), TRUE);
	$locale["currency_symbol"] = ewr_ConvertFromUtf8($locale["currency_symbol"]);
	return $locale;
}

// Get internal default date format (e.g. "yyyy/mm/dd"") from date format (int)
// 5 - Ymd (default)
// 6 - mdY
// 7 - dmY
// 9 - YmdHis
// 10 - mdYHis
// 11 - dmYHis
// 12 - ymd
// 13 - mdy
// 14 - dmy
// 15 - ymdHis
// 16 - mdyHis
// 17 - dmyHis
function ewr_DateFormat($dateFormat) {
	global $EWR_DATE_SEPARATOR;
	if (is_numeric($dateFormat)) {
		$dateFormat = intval($dateFormat);
		switch ($dateFormat) {
			case 5:
			case 9:
				return "yyyy" . $EWR_DATE_SEPARATOR . "mm" . $EWR_DATE_SEPARATOR . "dd";
			case 6:
			case 10:
				return "mm" . $EWR_DATE_SEPARATOR . "dd" . $EWR_DATE_SEPARATOR . "yyyy";
			case 7:
			case 11:
				return "dd" . $EWR_DATE_SEPARATOR . "mm" . $EWR_DATE_SEPARATOR . "yyyy";
			case 12:
			case 15:
				return "yy" . $EWR_DATE_SEPARATOR . "mm" . $EWR_DATE_SEPARATOR . "dd";
			case 13:
			case 16:
				return "mm" . $EWR_DATE_SEPARATOR . "dd" . $EWR_DATE_SEPARATOR . "yy";
			case 14:
			case 17:
				return "dd" . $EWR_DATE_SEPARATOR . "mm" . $EWR_DATE_SEPARATOR . "yy";
		}
	} elseif (is_string($dateFormat)) {
		switch (substr($dateFormat, 0, 3)) {
			case "Ymd":
				return "yyyy" . $EWR_DATE_SEPARATOR . "mm" . $EWR_DATE_SEPARATOR . "dd";
			case "mdY":
				return "mm" . $EWR_DATE_SEPARATOR . "dd" . $EWR_DATE_SEPARATOR . "yyyy";
			case "dmY":
				return "dd" . $EWR_DATE_SEPARATOR . "mm" . $EWR_DATE_SEPARATOR . "yyyy";
			case "ymd":
				return "yy" . $EWR_DATE_SEPARATOR . "mm" . $EWR_DATE_SEPARATOR . "dd";
			case "mdy":
				return "mm" . $EWR_DATE_SEPARATOR . "dd" . $EWR_DATE_SEPARATOR . "yy";
			case "dmy":
				return "dd" . $EWR_DATE_SEPARATOR . "mm" . $EWR_DATE_SEPARATOR . "yy";
		}
	}
	return "yyyy" . $EWR_DATE_SEPARATOR . "mm" . $EWR_DATE_SEPARATOR . "dd";
}

// Validate locale file date format
function ewr_DateFormatId($dateFormat) {
	if (is_numeric($dateFormat)) {
		$dateFormat = intval($dateFormat);
		return (in_array($dateFormat, array(5, 6, 7, 9, 10, 11, 12, 13, 14, 15, 16, 17))) ? $dateFormat : 5;
	} elseif (is_string($dateFormat)) {
		switch ($dateFormat) {
			case "Ymd":
				return 5;
			case "mdY":
				return 6;
			case "dmY":
				return 7;
			case "YmdHis":
				return 9;
			case "mdYHis":
				return 10;
			case "dmYHis":
				return 11;
			case "ymd":
				return 12;
			case "mdy":
				return 13;
			case "dmy":
				return 14;
			case "ymdHis":
				return 15;
			case "mdyHis":
				return 16;
			case "dmyHis":
				return 17;
		}
	}
	return 5;
}

// Convert XML to array
function ewr_XmlToArray($contents) {
	if (!$contents) return array(); 
	if (!function_exists('xml_parser_create')) return FALSE;
	$get_attributes = 1; // Always get attributes. DO NOT CHANGE!

	// Get the XML Parser of PHP
	$parser = xml_parser_create();
	xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); // Always return in utf-8
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	xml_parse_into_struct($parser, trim($contents), $xml_values);
	xml_parser_free($parser);
	if (!$xml_values) return;
	$xml_array = array();
	$parents = array();
	$opened_tags = array();
	$arr = array();
	$current = &$xml_array;
	$repeated_tag_index = array(); // Multiple tags with same name will be turned into an array
	foreach ($xml_values as $data) {
		unset($attributes, $value); // Remove existing values

		// Extract these variables into the foreach scope
		// - tag(string), type(string), level(int), attributes(array)

		extract($data);
		$result = array();
		if (isset($value))
			$result['value'] = $value; // Put the value in a assoc array

		// Set the attributes
		if (isset($attributes) and $get_attributes) {
			foreach ($attributes as $attr => $val)
				$result['attr'][$attr] = $val; // Set all the attributes in a array called 'attr'
		} 

		// See tag status and do the needed
		if ($type == "open") { // The starting of the tag '<tag>'
			$parent[$level-1] = &$current;
			if (!is_array($current) || !in_array($tag, array_keys($current))) { // Insert New tag
				if ($tag <> 'ew-language' && @$result['attr']['id'] <> '') { // 
					$last_item_index = $result['attr']['id'];
					$current[$tag][$last_item_index] = $result;
					$repeated_tag_index[$tag.'_'.$level] = 1;
					$current = &$current[$tag][$last_item_index];
				} else {
					$current[$tag] = $result;
					$repeated_tag_index[$tag.'_'.$level] = 0;
					$current = &$current[$tag];
				}
			} else { // Another element with the same tag name
				if ($repeated_tag_index[$tag.'_'.$level] > 0) { // If there is a 0th element it is already an array
					if (@$result['attr']['id'] <> '') {
						$last_item_index = $result['attr']['id'];
					} else {
						$last_item_index = $repeated_tag_index[$tag.'_'.$level];
					}
					$current[$tag][$last_item_index] = $result;
					$repeated_tag_index[$tag.'_'.$level]++;
				} else { // Make the value an array if multiple tags with the same name appear together
					$temp = $current[$tag];
					$current[$tag] = array();
					if (@$temp['attr']['id'] <> '') {
						$current[$tag][$temp['attr']['id']] = $temp;
					} else {
						$current[$tag][] = $temp;
					}
					if (@$result['attr']['id'] <> '') {
						$last_item_index = $result['attr']['id'];
					} else {
						$last_item_index = 1;
					}
					$current[$tag][$last_item_index] = $result;
					$repeated_tag_index[$tag.'_'.$level] = 2;
				} 
				$current = &$current[$tag][$last_item_index];
			}
		} elseif ($type == "complete") { // Tags that ends in one line '<tag />'
			if (!isset($current[$tag])) { // New key
				$current[$tag] = array(); // Always use array for "complete" type
				if (@$result['attr']['id'] <> '') {
					$current[$tag][$result['attr']['id']] = $result;
				} else {
					$current[$tag][] = $result;
				}
				$repeated_tag_index[$tag.'_'.$level] = 1;
			} else { // Existing key
				if (@$result['attr']['id'] <> '') {
					$current[$tag][$result['attr']['id']] = $result;
				} else {
					$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
				}
				$repeated_tag_index[$tag.'_'.$level]++;
			}
		} elseif ($type == 'close') { // End of tag '</tag>'
			$current = &$parent[$level-1];
		}
	}
	return($xml_array);
}
/**
 * XML document class
 */

class crXMLDocument {
	var $Encoding = "utf-8";
	var $RootTagName;
	var $RowTagName;
	var $XmlDoc = FALSE;
	var $XmlTbl;
	var $XmlRow;
	var $NullValue = 'NULL';

	function __construct($encoding = "") {
		if ($encoding <> "")
			$this->Encoding = $encoding;
		if ($this->Encoding <> "") {
			$this->XmlDoc = new DOMDocument("1.0", strval($this->Encoding));
		} else {
			$this->XmlDoc = new DOMDocument("1.0");
		}
	}

	function Load($filename) {
		$filepath = realpath($filename);
		return $this->XmlDoc->load($filepath);
	}

	function &DocumentElement() {
		$de = $this->XmlDoc->documentElement;
		return $de;
	}

	function GetAttribute($element, $name) {
		return ($element) ? ewr_ConvertFromUtf8($element->getAttribute($name)) : "";
	}

	function SetAttribute($element, $name, $value) {
		if ($element)
			$element->setAttribute($name, ewr_ConvertToUtf8($value));
	}

	function SelectSingleNode($query) {
		$elements = $this->SelectNodes($query);
		return ($elements->length > 0) ? $elements->item(0) : NULL;
	}

	function SelectNodes($query) {
		$xpath = new DOMXPath($this->XmlDoc);
		return $xpath->query($query);
	}

	function AddRoot($roottagname = 'table') {
		$this->RootTagName = $roottagname;
		$this->XmlTbl = $this->XmlDoc->createElement($this->RootTagName);
		$this->XmlDoc->appendChild($this->XmlTbl);
	}

	function AddRow($rowtagname = 'row') {
		$this->RowTagName = $rowtagname;
		$this->XmlRow = $this->XmlDoc->createElement($this->RowTagName);
		if ($this->XmlTbl)
			$this->XmlTbl->appendChild($this->XmlRow);
	}

	function AddField($name, $value) {
		if (is_null($value)) $value = $this->NullValue;
		$value = ewr_ConvertToUtf8($value); // Convert to UTF-8
		$xmlfld = $this->XmlDoc->createElement($name);
		$this->XmlRow->appendChild($xmlfld);
		$xmlfld->appendChild($this->XmlDoc->createTextNode($value));
	}

	function XML() {
		return $this->XmlDoc->saveXML();
	}
}

// Select nodes from XML document
function &ewr_SelectNodes(&$xmldoc, $query) {
	if ($xmldoc) {
		$xpath = new DOMXPath($xmldoc);
		return $xpath->query($query);
	}
	return NULL;
}

// Select single node from XML document
function &ewr_SelectSingleNode(&$xmldoc, $query) {
	$elements = ewr_SelectNodes($xmldoc, $query);
	return ($elements && $elements->length > 0) ? $elements->item(0) : NULL;
}

// Debug timer
class crTimer {
	public $StartTime;
	public static $Template = "<div class=\"alert alert-info ewAlert\">Page processing time: {time} seconds</div>";

	// Constructor
	function __construct($start = TRUE) {
		if ($start)
			$this->Start();
	}

	// Get time
	function GetTime() {
		return microtime(TRUE);
	}

	// Get elapsed time
	function GetElapsedTime() {
		$curtime = $this->GetTime();
		if (isset($curtime) && isset($this->StartTime) && $curtime > $this->StartTime)
		return $curtime - $this->StartTime;
	}

	// Get script start time
	function Start() {
		if (EWR_DEBUG_ENABLED)
			$this->StartTime = $this->GetTime();
	}

	// Display elapsed time (in seconds)
	function Stop() {
		if (EWR_DEBUG_ENABLED) {
			$time = $this->GetElapsedTime();
			echo str_replace("{time}", number_format($time, 6), self::$Template);
		}
	}
}
/**
 * Breadcrumb class
 */

class crBreadcrumb {
	var $Links = array();
	var $SessionLinks = array();
	var $Visible = TRUE;

	// Constructor
	function __construct() {
		global $ReportLanguage;
		$this->Links[] = array("home", "HomePage", "index.php", "ewHome", "", FALSE); // Home
	}

	// Check if an item exists
	function Exists($pageid, $table, $pageurl) {
		if (is_array($this->Links)) {
			$cnt = count($this->Links);
			for ($i = 0; $i < $cnt; $i++) {
				@list($id, $title, $url, $tablevar, $cur) = $this->Links[$i];
				if ($pageid == $id && $table == $tablevar && $pageurl == $url)
					return TRUE;
			}
		}
		return FALSE;
	}

	// Add breadcrumb
	function Add($pageid, $pagetitle, $pageurl, $pageurlclass = "", $table = "", $current = FALSE) {

		// Load session links
		$this->LoadSession();

		// Get list of master tables
		$mastertable = array();
		if ($table <> "") {
			$tablevar = $table;
			while (@$_SESSION[EWR_PROJECT_NAME . "_" . $tablevar . "_" . EWR_TABLE_MASTER_TABLE] <> "") {
				$tablevar = $_SESSION[EWR_PROJECT_NAME . "_" . $tablevar . "_" . EWR_TABLE_MASTER_TABLE];
				if (in_array($tablevar, $mastertable))
					break;
				$mastertable[] = $tablevar;
			}
		}

		// Add master links first
		if (is_array($this->SessionLinks)) {
			$cnt = count($this->SessionLinks);
			for ($i = 0; $i < $cnt; $i++) {
				@list($id, $title, $url, $cls, $tbl, $cur) = $this->SessionLinks[$i];
				if (in_array($tbl, $mastertable)) {
					if ($url == $pageurl)
						break;
					if (!$this->Exists($id, $tbl, $url))
						$this->Links[] = array($id, $title, $url, $cls, $tbl, FALSE);
				}
			}
		}

		// Add this link
		if (!$this->Exists($pageid, $table, $pageurl))
			$this->Links[] = array($pageid, $pagetitle, $pageurl, $pageurlclass, $table, $current);

		// Save session links
		$this->SaveSession();
	}

	// Save links to Session
	function SaveSession() {
		$_SESSION[EWR_SESSION_BREADCRUMB] = $this->Links;
	}

	// Load links from Session
	function LoadSession() {
		if (is_array(@$_SESSION[EWR_SESSION_BREADCRUMB]))
			$this->SessionLinks = $_SESSION[EWR_SESSION_BREADCRUMB];
	}

	// Load language phrase
	function LanguagePhrase($title, $table, $current) {
		global $ReportLanguage;
		$wrktitle = ($title == $table) ? $ReportLanguage->TablePhrase($title, "TblCaption") : $ReportLanguage->Phrase($title);
		if ($current)
			$wrktitle = "<span id=\"ewPageCaption\">" . $wrktitle . "</span>";
		return $wrktitle;
	}

	// Render
	function Render() {
		if (!$this->Visible || EWR_PAGE_TITLE_STYLE == "" || EWR_PAGE_TITLE_STYLE == "None")
			return;
		$nav = "<ul class=\"breadcrumb ewBreadcrumbs\">";
		if (is_array($this->Links)) {
			$cnt = count($this->Links);
			if (EWR_PAGE_TITLE_STYLE == "Caption") {

				// Already shown in content header, just ignore
				//list($id, $title, $url, $cls, $table, $cur) = $this->Links[$cnt-1];
				//echo "<div class=\"ewPageTitle\">" . $this->LanguagePhrase($title, $table, $cur) . "</div>";

				return;
			} else {
				for ($i = 0; $i < $cnt; $i++) {
					list($id, $title, $url, $cls, $table, $cur) = $this->Links[$i];
					if ($i < $cnt - 1) {
						$nav .= "<li id=\"ewBreadcrumb" . ($i + 1) . "\">";
					} else {
						$nav .= "<li id=\"ewBreadcrumb" . ($i + 1) . "\" class=\"active\">";
						$url = ""; // No need to show URL for current page
					}
					$text = $this->LanguagePhrase($title, $table, $cur);
					$title = ewr_HtmlTitle($text);
					if ($url <> "") {
						$nav .= "<a href=\"" . ewr_GetUrl($url) . "\"";
						if ($title <> "" && $title <> $text)
							$nav .= " title=\"" . ewr_HtmlEncode($title) . "\"";
						if ($cls <> "")
							$nav .= " class=\"" . $cls . "\"";
						$nav .= ">" . $text . "</a>";
					} else {
						$nav .= $text;
					}
					$nav .= "</li>";
				}
			}
		}
		$nav .= "</ul>";
		echo $nav;
	}
}
/**
 * Table classes
 */

// Common class for table and report
class crTableBase {
	var $TableVar;
	var $TableName;
	var $TableType;
	var $TableReportType;
	var $SourcTableIsCustomView = FALSE;
	var $TableCaption = "";
	var $DBID = "DB"; // Table database id
	var $ShowCurrentFilter = EWR_SHOW_CURRENT_FILTER;
	var $ShowDrillDownFilter = EWR_SHOW_DRILLDOWN_FILTER;
	var $CurrentOrder; // Current order
	var $CurrentOrderType; // Current order type
	var $UseDrillDownPanel = EWR_USE_DRILLDOWN_PANEL; // Use drill down panel
	var $UseCustomTemplate = EWR_USE_CUSTOM_TEMPLATE; // Use custom template in report

	// Connection
	function &Connection() {
		return ReportConn($this->DBID);
	}

	// Set table caption
	function setTableCaption($v) {
		$this->TableCaption = $v;
	}

	// Table caption
	function TableCaption() {
		global $ReportLanguage;
		if ($this->TableCaption <> "")
			return $this->TableCaption;
		else
			return $ReportLanguage->TablePhrase($this->TableVar, "TblCaption");
	}

	// Session Group Per Page
	function getGroupPerPage() {
		return @$_SESSION[EWR_PROJECT_NAME . "_" . $this->TableVar . "_grpperpage"];
	}

	function setGroupPerPage($v) {
		@$_SESSION[EWR_PROJECT_NAME . "_" . $this->TableVar . "_grpperpage"] = $v;
	}

	// Session Start Group
	function getStartGroup() {
		return @$_SESSION[EWR_PROJECT_NAME . "_" . $this->TableVar . "_start"];
	}

	function setStartGroup($v) {
		@$_SESSION[EWR_PROJECT_NAME . "_" . $this->TableVar . "_start"] = $v;
	}

	// Session Order By
	function getOrderBy() {
		return @$_SESSION[EWR_PROJECT_NAME . "_" . $this->TableVar . "_orderby"];
	}

	function setOrderBy($v) {
		@$_SESSION[EWR_PROJECT_NAME . "_" . $this->TableVar . "_orderby"] = $v;
	}

	// Session Order By (for non-grouping fields)
	function getDetailOrderBy() {
		return @$_SESSION[EWR_PROJECT_NAME . "_" . $this->TableVar . "_detailorderby"];
	}

	function setDetailOrderBy($v) {
		@$_SESSION[EWR_PROJECT_NAME . "_" . $this->TableVar . "_detailorderby"] = $v;
	}
	var $fields = array();
	var $Export; // Export
	var $CustomExport; // Custom export
	var $GenOptions = array(); // Generate options
	var $FirstRowData = array(); // First row data
	var $ExportAll;
	var $ExportPageBreakCount = 1; // Export page break count
	var $ExportChartPageBreak = TRUE; // Page break for chart when export
	var $PageBreakContent = EWR_EXPORT_PAGE_BREAK_CONTENT;
	var $UseTokenInUrl = EWR_USE_TOKEN_IN_URL;
	var $RowType; // Row type
	var $RowTotalType; // Row total type
	var $RowTotalSubType; // Row total subtype
	var $RowGroupLevel; // Row group level
	var $RowAttrs = array(); // Row attributes

	// Reset attributes for table object
	function ResetAttrs() {
		$this->RowAttrs = array();
		foreach ($this->fields as $fld) {
			$fld->ResetAttrs();
		}
	}

	// Row attributes
	function RowAttributes() {
		$sAtt = "";
		foreach ($this->RowAttrs as $k => $v) {
			$sAtt .= " " . $k . "=\"" . ewr_HtmlEncode($v) . "\"";
		}
		return $sAtt;
	}

	// Field object by fldname
	function &fields($fldname) {
		return $this->fields[$fldname];
	}

	// Field object by fldparm
	function &FieldByParm($fldparm) {
		foreach ($this->fields as $fld) {
			if (substr($fld->FldVar, 2) == $fldparm)
				return $fld;
		}
		return NULL;
	}

	// URL encode
	function UrlEncode($str) {
		return urlencode($str);
	}

	// Print
	function Raw($str) {
		return $str;
	}
}

// Class for crosstab
class crTableCrosstab extends crTableBase {

	// Column field related
	var $ColumnFieldName;
	var $ColumnDateSelection = FALSE;
	var $ColumnDateType;

	// Summary fields
	var $SummaryFields = array();
	var $SummarySeparatorStyle = "unstyled";

	// Summary cells
	var $SummaryCellAttrs;
	var $SummaryViewAttrs;
	var $SummaryLinkAttrs;
	var $SummaryCurrentValue;
	var $SummaryViewValue;
	var $CurrentIndex = -1;

	// Summary cell attributes
	function SummaryCellAttributes($i) {
		$sAtt = "";
		if (is_array($this->SummaryCellAttrs)) {
			if ($i >= 0 && $i < count($this->SummaryCellAttrs)) {
				$Attrs = $this->SummaryCellAttrs[$i];
				if (is_array($Attrs)) {
					foreach ($Attrs as $k => $v) {
						if (trim($v) <> "")
							$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
					}
				}
			}
		}
		return $sAtt;
	}

	// Summary view attributes
	function SummaryViewAttributes($i) {
		$sAtt = "";
		if (is_array($this->SummaryViewAttrs)) {
			if ($i >= 0 && $i < count($this->SummaryViewAttrs)) {
				$Attrs = $this->SummaryViewAttrs[$i];
				if (is_array($Attrs)) {
					foreach ($Attrs as $k => $v) {
						if (trim($v) <> "")
							$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
					}
				}
			}
		}
		return $sAtt;
	}

	// Summary link attributes
	function SummaryLinkAttributes($i) {
		$sAtt = "";
		if (is_array($this->SummaryLinkAttrs)) {
			if ($i >= 0 && $i < count($this->SummaryLinkAttrs)) {
				$Attrs = $this->SummaryLinkAttrs[$i];
				if (is_array($Attrs)) {
					foreach ($Attrs as $k => $v) {
						if (trim($v) <> "") {
							$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
						}
					}
					if (@$Attrs["onclick"] <> "" && @$Attrs["href"] == "")
						$sAtt .= " href=\"javascript:void(0);\"";
				}
			}
		}
		return $sAtt;
	}

	// Render summary fields
	function RenderSummaryFields($idx) {
		global $gsExport, $gsCustomExport;
		$html = "";
		$cnt = count($this->SummaryFields);
		for ($i = 0; $i < $cnt; $i++) {
			$smry = &$this->SummaryFields[$i];
			$vv = $smry->SummaryViewValue[$idx];
			if (@$smry->SummaryLinkAttrs[$idx]["onclick"] <> "" || @$smry->SummaryLinkAttrs[$idx]["href"] <> "") {
				$vv = "<a" . $smry->SummaryLinkAttributes($idx) . ">" . $vv . "</a>";
			}
			$vv = "<span" . $smry->SummaryViewAttributes($idx) . ">" . $vv . "</span>";
			if ($cnt > 0) {
				if ($gsExport == "" || $gsExport == "print" && $gsCustomExport == "")
					$vv = "<li>" . $vv . "</li>";
				elseif ($gsExport == "excel" && defined('EWR_USE_PHPEXCEL') || $gsExport == "word" && defined('EWR_USE_PHPWORD'))
					$vv = $vv . "    ";
				else
					$vv = $vv . "<br>";
			}
			$html .= $vv;
		}
		if ($cnt > 0 && ($gsExport == "" || $gsExport == "print" && $gsCustomExport == ""))
			$html = "<ul class=\"list-" . $this->SummarySeparatorStyle . " ewCrosstabValues\">" . $html . "</ul>";
		return $html;
	}

	// Render summary types
	function RenderSummaryCaptions($typ = "") {
		global $ReportLanguage, $gsExport, $gsCustomExport;
		$html = "";
		$cnt = count($this->SummaryFields);
		if ($typ == "page") {
			return $ReportLanguage->Phrase("RptPageSummary");
		} elseif ($typ == "grand") {
			return $ReportLanguage->Phrase("RptGrandSummary");
		} else {
			for ($i = 0; $i < $cnt; $i++) {
				$smry = &$this->SummaryFields[$i];
				$st = $smry->SummaryCaption;
				$fld = &$this->fields($smry->FldName);
				$caption = $fld->FldCaption();
				if ($caption <> "") $st = $caption . " (" . $st . ")";
				if ($cnt > 0) {
					if ($gsExport == "" || $gsExport == "print" && $gsCustomExport == "")
						$st = "<li>" . $st . "</li>";
					elseif ($gsExport == "excel" && defined('EWR_USE_PHPEXCEL') || $gsExport == "word" && defined('EWR_USE_PHPWORD'))
						$st = $st . "    ";
					else
						$st = $st . "<br>";
				}
				$html .= $st;
			}
			if ($cnt > 0 && ($gsExport == "" || $gsExport == "print" && $gsCustomExport == ""))
				$html = "<ul class=\"list-" . $this->SummarySeparatorStyle . " ewCrosstabValues\">" . $html . "</ul>";
			return $html;
		}
	}
}

// Crosstab summary field attributes
class crSummaryField {
	var $FldName; // Field name
	var $FldVar; // Field variable name
	var $FldExpression; // Field expression (used in SQL)
	var $SummaryType;
	var $SummaryCaption;
	var $SummaryViewAttrs;
	var $SummaryLinkAttrs;
	var $SummaryCurrentValue;
	var $SummaryViewValue;
	var $SummaryCnt;
	var $SummaryVal;
	var $SummarySmry;
	var $SummaryValCnt;
	var $SummarySmryCnt;
	var $SummaryInitValue;
	var $SummaryRowSmry;
	var $SummaryRowCnt;

	// Constructor
	function __construct($fldvar, $fldname, $fldexpression, $smrytype) {
		$this->FldVar = $fldvar;
		$this->FldName = $fldname;
		$this->FldExpression = $fldexpression;
		$this->SummaryType = $smrytype;
	}

	// Summary view attributes
	function SummaryViewAttributes($i) {
		$sAtt = "";
		if (is_array($this->SummaryViewAttrs)) {
			if ($i >= 0 && $i < count($this->SummaryViewAttrs)) {
				$Attrs = $this->SummaryViewAttrs[$i];
				if (is_array($Attrs)) {
					foreach ($Attrs as $k => $v) {
						if (trim($v) <> "")
							$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
					}
				}
			}
		}
		return $sAtt;
	}

	// Summary link attributes
	function SummaryLinkAttributes($i) {
		$sAtt = "";
		if (is_array($this->SummaryLinkAttrs)) {
			if ($i >= 0 && $i < count($this->SummaryLinkAttrs)) {
				$Attrs = $this->SummaryLinkAttrs[$i];
				if (is_array($Attrs)) {
					foreach ($Attrs as $k => $v) {
						if (trim($v) <> "") {
							$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
						}
					}
					if (@$Attrs["onclick"] <> "" && @$Attrs["href"] == "")
						$sAtt .= " href=\"javascript:void(0);\"";
				}
			}
		}
		return $sAtt;
	}
}
/**
 * Field class
 */

class crField {
	var $TblName; // Table name
	var $TblVar; // Table variable name
	var $FldName; // Field name
	var $FldVar; // Field variable name
	var $FldExpression; // Field expression (used in SQL)
	var $FldIsCustom = FALSE; // Custom field
	var $FldDefaultErrMsg; // Default error message
	var $FldType; // Field type
	var $FldDataType; // PHP Report Maker Field type
	var $FldDateTimeFormat; // Date time format
	var $Count; // Count
	var $SumValue; // Sum
	var $AvgValue; // Average
	var $MinValue; // Minimum
	var $MaxValue; // Maximum
	var $CntValue; // Count
	var $SumViewValue; // Sum
	var $AvgViewValue; // Average
	var $MinViewValue; // Minimum
	var $MaxViewValue; // Maximum
	var $CntViewValue; // Count
	var $OldValue; // Old Value
	var $CurrentValue; // Current value
	var $ViewValue; // View value
	var $HrefValue; // Href value
	var $DrillDownUrl = ""; // Drill down URL
	var $CurrentFilter = ""; // Current filter in use
	var $FormValue; // Form value
	var $QueryStringValue; // QueryString value
	var $DbValue; // Database value
	var $ImageWidth = 0; // Image width
	var $ImageHeight = 0; // Image height
	var $ImageResize = FALSE; // Image resize
	var $IsBlobImage = FALSE; // Is blob image
	var $Sortable = TRUE; // Sortable
	var $GroupingFieldId = 0; // Grouping field id
	var $ShowGroupHeaderAsRow = FALSE; // Show grouping level as row
	var $UploadPath = EWR_UPLOAD_DEST_PATH; // Upload path
	var $HrefPath = EWR_UPLOAD_HREF_PATH; // Href path (for download)
	var $TruncateMemoRemoveHtml = FALSE; // Remove HTML from memo field
	var $DefaultDecimalPrecision = EWR_DEFAULT_DECIMAL_PRECISION;
	var $UseColorbox = EWR_USE_COLORBOX; // Use Colorbox
	var $CellAttrs = array(); // Cell attributes
	var $ViewAttrs = array(); // View attributes
	var $LinkAttrs = array(); // Href attributes
	var $EditAttrs = array(); // Edit attributes
	var $PlaceHolder = "";
	var $DisplayValueSeparator = ", ";
	var $LookupFilters = array();
	var $LookupFilterOptions = array();
	var $FldGroupByType; // Group By Type
	var $FldGroupInt; // Group Interval
	var $FldGroupSql; // Group SQL
	var $GroupDbValues; // Group DB Values
	var $GroupViewValue; // Group View Value
	var $GroupSummaryOldValue; // Group Summary Old Value
	var $GroupSummaryValue; // Group Summary Value
	var $GroupSummaryViewValue; // Group Summary View Value
	var $SqlSelect; // Field SELECT
	var $SqlGroupBy; // Field GROUP BY
	var $SqlOrderBy; // Field ORDER BY
	var $ValueList; // Value List
	var $SelectionList; // Selection List
	var $DefaultSelectionList; // Default Selection List
	var $AdvancedFilters; // Advanced Filters
	var $RangeFrom; // Range From
	var $RangeTo; // Range To
	var $DropDownList; // Dropdown List
	var $DropDownValue; // Dropdown Value
	var $DefaultDropDownValue; // Default Dropdown Value
	var $DateFilter; // Date Filter
	var $SearchValue; // Search Value 1
	var $SearchValue2; // Search Value 2
	var $SearchOperator; // Search Operator 1
	var $SearchOperator2; // Search Operator 2
	var $SearchCondition; // Search Condition
	var $DefaultSearchValue; // Default Search Value 1
	var $DefaultSearchValue2; // Default Search Value 2
	var $DefaultSearchOperator; // Default Search Operator 1
	var $DefaultSearchOperator2; // Default Search Operator 2
	var $DefaultSearchCondition; // Default Search Condition
	var $FldDelimiter = ""; // Field delimiter (e.g. comma) for delimiter separated value
	var $Visible = TRUE; // Visible

	// Constructor
	function __construct($tblvar, $tblname, $fldvar, $fldname, $fldexpression, $fldtype, $flddatatype, $flddtfmt) {
		$this->TblVar = $tblvar;
		$this->TblName = $tblname;
		$this->FldVar = $fldvar;
		$this->FldName = $fldname;
		$this->FldExpression = $fldexpression;
		$this->FldType = $fldtype;
		$this->FldDataType = $flddatatype;
		$this->FldDateTimeFormat = $flddtfmt;
	}
	var $Caption = "";

	// Set field caption
	function setFldCaption($v) {
		$this->Caption = $v;
	}

	// Field caption
	function FldCaption() {
		global $ReportLanguage;
		if ($this->Caption <> "")
			return $this->Caption;
		else
			return $ReportLanguage->FieldPhrase($this->TblVar, substr($this->FldVar, 2), "FldCaption");
	}

	// Field title
	function FldTitle() {
		global $ReportLanguage;
		return $ReportLanguage->FieldPhrase($this->TblVar, substr($this->FldVar, 2), "FldTitle");
	}

	// Field image alt
	function FldAlt() {
		global $ReportLanguage;
		return $ReportLanguage->FieldPhrase($this->TblVar, substr($this->FldVar, 2), "FldAlt");
	}

	// Field error message
	function FldErrMsg() {
		global $ReportLanguage;
		$err = $ReportLanguage->FieldPhrase($this->TblVar, substr($this->FldVar, 2), "FldErrMsg");
		if ($err == "") $err = $this->FldDefaultErrMsg . " - " . $this->FldCaption();
		return $err;
	}

	// Set field visibility
	function SetVisibility() {
		$this->Visible = $GLOBALS[$this->TblVar]->SetFieldVisibility(substr($this->FldVar, 2));
	}

	// Get display field value separator
	// idx (int) display field index (1|2|3)
	function GetDisplayValueSeparator($idx) {
		$sep = $this->DisplayValueSeparator;
		return (is_array($sep)) ? @$sep[$idx - 1] : ($sep ?: ", ");
	}

	// Get display field value separator as attribute value
	function DisplayValueSeparatorAttribute() {
		return ewr_HtmlEncode(is_array($this->DisplayValueSeparator) ? json_encode($this->DisplayValueSeparator) : $this->DisplayValueSeparator);
	}

	// Get display value (for lookup field)
	// $rs (array|recordset)
	function DisplayValue($rs) {
		$ar = is_array($rs) ? $rs : $rs->fields;
		$val = strval(@$ar[1]); // Display field 1
		for ($i = 2; $i <= 4; $i++) { // Display field 2 to 4
			$sep = $this->GetDisplayValueSeparator($i - 1);
			if (is_null($sep)) // No separator, break
				break;
			if (@$ar[$i] <> "")
				$val .= $sep . $ar[$i];
		}
		return $val;
	}

	// Reset attributes for field object
	function ResetAttrs() {
		$this->CellAttrs = array();
		$this->ViewAttrs = array();
	}

	// View Attributes
	function ViewAttributes() {
		$sAtt = "";
		$sStyle = "";
		if (intval($this->ImageWidth) > 0 && (!$this->ImageResize || ($this->ImageResize && intval($this->ImageHeight) <= 0)))
			$sStyle .= "width: " . intval($this->ImageWidth) . "px; ";
		if (intval($this->ImageHeight) > 0 && (!$this->ImageResize || ($this->ImageResize && intval($this->ImageWidth) <= 0)))
			$sStyle .= "height: " . intval($this->ImageHeight) . "px; ";
		$sStyle = trim($sStyle);
		if (@$this->ViewAttrs["style"] <> "")
			$sStyle .= " " . $this->ViewAttrs["style"];
		if (trim($sStyle) <> "")
			$sAtt .= " style=\"" . trim($sStyle) . "\"";
		foreach ($this->ViewAttrs as $k => $v) {
			if ($k <> "style" && trim($v) <> "")
				$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
		}
		return $sAtt;
	}

	// Link Attributes
	function LinkAttributes() {
		$sAtt = "";
		$sHref = trim($this->HrefValue);
		foreach ($this->LinkAttrs as $k => $v) {
			if (trim($v) <> "") {
				if ($k == "href") {
					if ($sHref == "")
						$sHref = $v;
				} else {
					$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
				}
			}
		}
		if ($sHref <> "")
			$sAtt .= " href=\"" . trim($sHref) . "\"";
		elseif (trim(@$this->LinkAttrs["onclick"]) <> "")
			$sAtt .= " href=\"javascript:void(0);\"";
		return $sAtt;
	}

	// Cell attributes
	function CellAttributes() {
		$sAtt = "";
		foreach ($this->CellAttrs as $k => $v) {
			if (trim($v) <> "")
				$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
		}
		return $sAtt;
	}

	// Edit Attributes
	function EditAttributes() {
		$sAtt = "";
		foreach ($this->EditAttrs as $k => $v) {
			if (trim($v) <> "")
				$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
		}
		return $sAtt;
	}

	// Sort
	function getSort() {
		return @$_SESSION[EWR_PROJECT_NAME . "_" . $this->TblVar . "_" . EWR_TABLE_SORT . "_" . $this->FldVar];
	}

	function setSort($v) {
		if (@$_SESSION[EWR_PROJECT_NAME . "_" . $this->TblVar . "_" . EWR_TABLE_SORT . "_" . $this->FldVar] <> $v) {
			$_SESSION[EWR_PROJECT_NAME . "_" . $this->TblVar . "_" . EWR_TABLE_SORT . "_" . $this->FldVar] = $v;
		}
	}

	function ReverseSort() {
		return ($this->getSort() == "ASC") ? "DESC" : "ASC";
	}

	// List view value
	function ListViewValue() {
		$value = trim(strval($this->ViewValue));
		if ($value <> "") {
			$value2 = trim(preg_replace('/<[^img][^>]*>/i', '', strval($value)));
			return ($value2 <> "") ? $this->ViewValue : "&nbsp;";
		} else {
			return "&nbsp;";
		}
	}

	// Form value
	function setFormValue($v) {
		$this->FormValue = $v;
		if (is_array($this->FormValue))
			$this->FormValue = implode(",", $this->FormValue);
		$this->CurrentValue = $this->FormValue;
	}

	// QueryString value
	function setQueryStringValue($v) {
		$this->QueryStringValue = $v;
		$this->CurrentValue = $this->QueryStringValue;
	}

	// Database value
	function setDbValue($v) {
		$this->OldValue = $this->DbValue;
		if (EWR_IS_MSSQL && ($this->FldType == 131 || $this->FldType == 139)) // MS SQL adNumeric/adVarNumeric field
			$this->DbValue = floatval($v);
		else
			$this->DbValue = $v;
		$this->CurrentValue = $this->DbValue;
	}

	// Group value
	function GroupValue() {
		return $this->getGroupValue($this->CurrentValue);
	}

	// Group old value
	function GroupOldValue() {
		return $this->getGroupValue($this->OldValue);
	}

	// Get group value
	function getGroupValue($v) {
		if ($this->GroupingFieldId == 1) {
			return $v;
		} else {
			return $this->getGroupValueBase($v);
		}
	}

	// Get group value base
	function getGroupValueBase($v) {
		if (is_array($this->GroupDbValues)) {
			return @$this->GroupDbValues[$v];
		} elseif ($this->FldGroupByType <> "" && $this->FldGroupByType <> "n") {
			return ewr_GroupValue($this, $v);
		} else {
			return $v;
		}
	}

	// Lookup filter query
	function LookupFilterQuery($isAutoSuggest = FALSE) {
		global $grLanguage;
		$tbl = $GLOBALS[$this->TblVar];
		if ($isAutoSuggest) {
			if (method_exists($tbl, "SetupAutoSuggestFilters"))
				$tbl->SetupAutoSuggestFilters($this);
		} else {
			if (method_exists($tbl, "SetupLookupFilters"))
				$tbl->SetupLookupFilters($this);
		}
		foreach ($this->LookupFilters as $key => &$value) {
			if (preg_match('/^(select|where|orderby)$/', $key)) // Remove
				unset($this->LookupFilters[$key]);
			elseif (preg_match('/^f\d+$|^s$|^dx\d+$|^af$/', $key)) // "f<n>" or "s" or "dx<n>" or "af"
				$value = ewr_Encrypt($value); // Encrypt SQL and filter
		}
		$this->LookupFilters["lang"] = @$grLanguage;
		return http_build_query($this->LookupFilters);
	}

	// Href path
	function HrefPath() {
		$path = ewr_UploadPathEx(FALSE, ($this->HrefPath <> "") ? $this->HrefPath : $this->UploadPath);
		if (preg_match('/^s3:\/\/([^\/]+)/i', $path, $m)) {
			$options = stream_context_get_options(stream_context_get_default());
			$client = @$options["s3"]["client"];
			if ($client) {
				$r = ewr_Random();
				$path = $client->getObjectUrl($m[1], $r);
				return explode($r, $path)[0];
			}
		}
		return $path;
	}

	// Physical upload path
	function PhysicalUploadPath() {
		return ewr_ServerMapPath($this->UploadPath);
	}
}

// JavaScript for drill down
function ewr_DrillDownJs($url, $id, $hdr, $usepanel = TRUE, $objid = "", $event = TRUE) {
	if (trim($url) == "") {
		return "";
	} else {
		if ($usepanel) {
			$obj = ($objid == "") ? "this" : "'" . ewr_JsEncode($objid) . "'";
			if ($event) {
				$wrkurl = preg_replace('/&(?!amp;)/', '&amp;', $url); // Replace & to &amp;
				return "ewr_ShowDrillDown(event, " . $obj . ", '" . ewr_JsEncode($wrkurl) . "', '" . ewr_JsEncode($id) . "', '" . ewr_JsEncode($hdr) . "'); return false;";
			} else {
				return "ewr_ShowDrillDown(null, " . $obj . ", '" . ewr_JsEncode($url) . "', '" . ewr_JsEncode($id) . "', '" . ewr_JsEncode($hdr) . "');";
			}
		} else {
			$wrkurl = str_replace("?d=1&", "?d=2&", $url); // Change d parameter to 2
			return "ewr_Redirect('" . ewr_JsEncode($wrkurl) . "');";
		}
	}
}
/**
 * Chart class
 */

class crChart {
	var $Table; // Table object
	var $TblVar; // Retained for compatibility
	var $TblName; // Retained for compatibility
	var $ChartName; // Chart name
	var $ChartVar; // Chart variable name
	var $ChartXFldName; // Chart X Field name
	var $ChartYFldName; // Chart Y Field name
	var $ChartType; // Chart Type
	var $ChartSFldName; // Chart Series Field name
	var $ChartSeriesType; // Chart Series Type
	var $ChartSeriesRenderAs = ""; // Chart Series renderAs
	var $ChartSeriesYAxis = ""; // Chart Series Y Axis
	var $ChartRunTimeSort = FALSE; // Chart run time sort
	var $ChartSortType = 0; // Chart Sort Type
	var $ChartSortSeq = ""; // Chart Sort Sequence
	var $ChartSummaryType; // Chart Summary Type
	var $ChartWidth; // Chart Width
	var $ChartHeight; // Chart Height
	var $ChartGridHeight = 200; // Chart grid height
	var $ChartGridConfig;
	var $ChartAlign; // Chart Align
	var $ChartDrillDownUrl = ""; // Chart drill down URL
	var $UseDrillDownPanel = EWR_USE_DRILLDOWN_PANEL; // Use drill down panel
	var $ChartDefaultDecimalPrecision = EWR_DEFAULT_DECIMAL_PRECISION;
	var $SqlSelect;
	var $SqlWhere = "";
	var $SqlGroupBy;
	var $SqlOrderBy;
	var $XAxisDateFormat;
	var $NameDateFormat;
	var $SeriesDateType;
	var $SqlSelectSeries;
	var $SqlWhereSeries = "";
	var $SqlGroupBySeries;
	var $SqlOrderBySeries;
	var $UseGridComponent = FALSE;
	var $ChartSeriesSql;
	var $ChartSql;
	var $PageBreak = FALSE;
	var $PageBreakType = "";
	var $PageBreakContent = "";
	var $DrillDownInPanel = FALSE;
	var $ScrollChart = FALSE;
	var $IsCustomTemplate = FALSE;
	var $CompatDataMode = FALSE; // For zoomline chart only
	var $DataSeparator = "|"; // For zoomline chart only
	var $ID;
	var $Parms = array();
	var $Trends;
	var $Data;
	var $ViewData;
	var $Series;
	var $XmlDoc;
	var $XmlRoot;

	// Constructor
	function __construct(&$tbl, $chartvar, $chartname, $xfld, $yfld, $type, $sfld, $stype, $smrytype, $width, $height, $align="") {
		$this->Table = &$tbl;
		$this->TblVar = $tbl->TableVar; // For compatiblity
		$this->TblName = $tbl->TableName; // For compatiblity
		$this->ChartVar = $chartvar;
		$this->ChartName = $chartname;
		$this->ChartXFldName = $xfld;
		$this->ChartYFldName = $yfld;
		$this->ChartType = $type;
		$this->ChartSFldName = $sfld;
		$this->ChartSeriesType = $stype;
		$this->ChartSummaryType = $smrytype;
		$this->ChartWidth = $width;
		$this->ChartHeight = $height;
		$this->ChartAlign = $align;
		$this->ID = NULL;
		$this->Parms = NULL;
		$this->Trends = NULL;
		$this->Data = NULL;
		$this->Series = NULL;
		$this->XmlDoc = new DOMDocument("1.0", "utf-8");
	}
	var $Caption = "";

	// Set chart caption
	function setChartCaption($v) {
		$this->Caption = $v;
	}

	// Chart caption
	function ChartCaption() {
		global $ReportLanguage;
		if ($this->Caption <> "")
			return $this->Caption;
		else
			return $ReportLanguage->ChartPhrase($this->Table->TableVar, $this->ChartVar, "ChartCaption");
	}

	// Function XAxisName
	function ChartXAxisName() {
		global $ReportLanguage;
		return $ReportLanguage->ChartPhrase($this->Table->TableVar, $this->ChartVar, "ChartXAxisName");
	}

	// Function YAxisName
	function ChartYAxisName() {
		global $ReportLanguage;
		return $ReportLanguage->ChartPhrase($this->Table->TableVar, $this->ChartVar, "ChartYAxisName");
	}

	// Function PYAxisName
	function ChartPYAxisName() {
		global $ReportLanguage;
		return $ReportLanguage->ChartPhrase($this->Table->TableVar, $this->ChartVar, "ChartPYAxisName");
	}

	// Function SYAxisName
	function ChartSYAxisName() {
		global $ReportLanguage;
		return $ReportLanguage->ChartPhrase($this->Table->TableVar, $this->ChartVar, "ChartSYAxisName");
	}

	// Sort
	function getSort() {
		return @$_SESSION[EWR_PROJECT_NAME . "_" . $this->Table->TableVar . "_" . EWR_TABLE_SORTCHART . "_" . $this->ChartVar];
	}

	function setSort($v) {
		if (@$_SESSION[EWR_PROJECT_NAME . "_" . $this->Table->TableVar . "_" . EWR_TABLE_SORTCHART . "_" . $this->ChartVar] <> $v) {
			$_SESSION[EWR_PROJECT_NAME . "_" . $this->Table->TableVar . "_" . EWR_TABLE_SORTCHART . "_" . $this->ChartVar] = $v;
		}
	}

	// Set chart parameters
	function SetChartParm($Name, $Value, $Output) {
		$this->Parms[$Name] = array($Name, $Value, $Output);
	}

	// Set chart parameters
	function SetChartParms($parms) {
		if (is_array($parms)) {
			foreach ($parms as $parm) {
				if (!isset($parm[2]))
					$parm[2] = TRUE;
				$this->Parms[$parm[0]] = $parm;
			}
		}
	}

	// Set up default chart parm
	function SetupDefaultChartParm($key, $value) {
		if (is_array($this->Parms)) {
			$parm = $this->LoadParm($key);
			if (is_null($parm)) {
				$this->Parms[$key] = array($key, $value, TRUE);
			} elseif ($parm == "") {
				$this->SaveParm($key, $value);
			}
		}
	}

	// Load chart parm
	function LoadParm($key) {
		if (is_array($this->Parms) && array_key_exists($key, $this->Parms))
			return $this->Parms[$key][1];
		return NULL;
	}

	// Save chart parm
	function SaveParm($key, $value) {
		if (is_array($this->Parms)) {
			if (array_key_exists($key, $this->Parms))
				$this->Parms[$key][1] = $value;
			else
				$this->Parms[$key] = array($key, $value, TRUE);
		}
	}

	// Process chart parms
	function ProcessChartParms(&$Parms) {
		$arParms[] = array("shownames", "showLabels");
		$arParms[] = array("showhovercap", "showToolTip");
		$arParms[] = array("rotateNames", "rotateLabels");
		$arParms[] = array("showColumnShadow", "showShadow");
		$arParms[] = array("showBarShadow", "showShadow");
		$arParms[] = array("hoverCapBgColor", "toolTipBgColor");
		$arParms[] = array("hoverCapBorderColor", "toolTipBorderColor");
		$arParms[] = array("hoverCapSepChar", "toolTipSepChar");
		$arParms[] = array("showAnchors", "drawAnchors");
		if ($this->IsCandlestickChart()) { // Candlestick
			$arParms[] = array("yAxisMaxValue", "pYAxisMaxValue");
			$arParms[] = array("yAxisMinValue", "pYAxisMinValue");
		}

		// Rename chart parm
		foreach ($arParms as $p) {
			list($fromParm, $toParm) = $p;
			if (array_key_exists($fromParm, $Parms) && !array_key_exists($toParm, $Parms)) {
				$Parms[$toParm] = array($toParm, $Parms[$fromParm][1], TRUE);
				unset($Parms[$fromParm]);
			}
		}
	}

	// Load chart parms
	function LoadChartParms() {

		// Initialize default values
		$this->SetupDefaultChartParm("caption", "Chart");

		// Show names/values/hover
		$this->SetupDefaultChartParm("shownames", "1"); // Default show names
		$this->SetupDefaultChartParm("showvalues", "1"); // Default show values

		// Process chart parms
		$this->ProcessChartParms($this->Parms);

		// Get showvalues/showhovercap
		$cht_showValues = (bool)$this->LoadParm("showvalues");
		$cht_showHoverCap = (bool)$this->LoadParm("showhovercap") || (bool)$this->LoadParm("showToolTip"); // v8

		// Tooltip // v8
		if ($cht_showHoverCap && !$this->LoadParm("showToolTip"))
			$this->SaveParm("showToolTip", "1");

		// Format percent for Pie charts
		$cht_showPercentageValues = $this->LoadParm("showPercentageValues");
		$cht_showPercentageInLabel = $this->LoadParm("showPercentageInLabel");
		$cht_type = $this->LoadParm("type");
		if ($cht_type == 1005 || $cht_type == 1105 || $cht_type == 1006 || $cht_type == 1106) { // Pie or Dougnut
			if (($cht_showHoverCap == "1" && $cht_showPercentageValues == "1") ||
			($cht_showValues == "1" && $cht_showPercentageInLabel == "1")) {
				$this->SetupDefaultChartParm("formatNumber", "1");
				$this->SaveParm("formatNumber", "1");
			}
		} elseif ($this->IsCandlestickChart()) { // Candlestick
			$this->SetupDefaultChartParm("bearBorderColor", "E33C3C");
			$this->SetupDefaultChartParm("bearFillColor", "E33C3C");
			$this->SetupDefaultChartParm("showVolumeChart", "0"); // v8
			if ($this->LoadParm("showAsBars"))
				$this->SaveParm("plotPriceAs", "BAR");
		}

		// Hide legend for single series (Column 2D / Line 2D / Area 2D)
		$cht_single_series = $this->ScrollChart && $this->IsSingleSeriesChart() ? 1 : 0;
		if ($cht_single_series == 1) {
			$this->SetupDefaultChartParm("showLegend", "0");
			$this->SaveParm("showLegend", "0");
		}
	}

	// Load view data
	function LoadViewData() {
		$sdt = $this->SeriesDateType;
		$xdt = $this->XAxisDateFormat;
		$ndt = $this->IsCandlestickChart() ? $this->NameDateFormat : ""; // Candlestick
		if ($sdt <> "") $xdt = $sdt;
		$this->ViewData = array();
		if ($sdt == "" && $xdt == "" && $ndt == "") { // No formatting, just copy
			$this->ViewData = $this->Data;
		} elseif (is_array($this->Data)) { // Format data
			$cntData = count($this->Data);
			for ($i = 0; $i < $cntData; $i++) {
				$temp = array();
				$chartrow = $this->Data[$i];
				$cntRow = count($chartrow);
				$temp[0] = $this->XValue($chartrow[0], $xdt); // X value
				$temp[1] = $this->SeriesValue($chartrow[1], $sdt); // Series value
				for ($j = 2; $j < $cntRow; $j++) {
					if ($ndt <> "" && $j == $cntRow-1)
						$temp[$j] = $this->XValue($chartrow[$j], $ndt); // Name value
					else
						$temp[$j] = $chartrow[$j]; // Y values
				}
				$this->ViewData[] = $temp;
			}
		}
	}

	// Set up chart
	function SetupChart() {
		global $gsExport, $Page;

		// Set up chart base SQL
		if ($this->Table->TableReportType == "crosstab") { // Crosstab chart
			$sqlSelect = str_replace("<DistinctColumnFields>", $this->Table->DistinctColumnFields, $this->Table->getSqlSelect());
			$sqlChartSelect = $this->SqlSelect;
		} else {
			$sqlSelect = $this->Table->getSqlSelect();
			$sqlChartSelect = $this->SqlSelect;
		}
		$pageFilter = isset($Page) ? $Page->Filter : "";
		if ($this->Table->SourcTableIsCustomView)
			$sqlChartBase = "(" . ewr_BuildReportSql($sqlSelect, $this->Table->getSqlWhere(), $this->Table->getSqlGroupBy(), $this->Table->getSqlHaving(), (EWR_IS_MSSQL) ? $this->Table->getSqlOrderBy() : "", $pageFilter, "") . ") EW_TMP_TABLE";
		else
			$sqlChartBase = $this->Table->getSqlFrom();

		// Set up chart series
		if (!ewr_EmptyStr($this->ChartSFldName)) {
			if ($this->ChartSeriesType == 1) { // Multiple Y fields
				$ar = explode("|", $this->ChartSFldName);
				$cnt = count($ar);
				$yaxis = explode(",", $this->ChartSeriesYAxis);
				for ($i = 0; $i < $cnt; $i++) {
					$fld = &$this->Table->fields[$ar[$i]];
					if (substr(strval($this->ChartType),0,1) == "4") { // Combination charts
						$series = @$yaxis[$i] == "2" ? "S" : "P";
						$this->Series[] = array($fld->FldCaption(), $series);
					} else {
						$this->Series[] = $fld->FldCaption();
					}
				}
			} elseif ($this->Table->TablReportType == "crosstab" && $this->ChartSFldName == $this->Table->ColumnFieldName && $this->Table->ColumnDateSelection && $this->Table->ColumnDateType == "q") { // Quarter
				for ($i = 1; $i <= 4; $i++)
					$this->Series[] = ewr_QuarterName($i);
			} elseif ($this->Table->TablReportType == "crosstab" && $this->ChartSFldName == $this->Table->ColumnFieldName && $this->Table->ColumnDateSelection && $this->Table->ColumnDateType == "m") { // Month
				for ($i = 1; $i <= 12; $i++)
					$this->Series[] = ewr_MonthName($i);
			} else { // Load chart series from sql directly
				if ($this->Table->SourcTableIsCustomView) {
					$sql = $this->SqlSelectSeries . $sqlChartBase;
					$sql = ewr_BuildReportSql($sql, $this->SqlWhereSeries, $this->SqlGroupBySeries, "", $this->SqlOrderBySeries, "", "");
				} else {
					$sql = $this->SqlSelectSeries . $sqlChartBase;
					$chartFilter = $this->SqlWhereSeries;
					ewr_AddFilter($chartFilter, $this->Table->getSqlWhere());
					$sql = ewr_BuildReportSql($sql, $chartFilter, $this->SqlGroupBySeries, "", $this->SqlOrderBySeries, $pageFilter, "");
				}
				$this->ChartSeriesSql = $sql;
			}
		}

		// Run time sort, update SqlOrderBy
		if ($this->ChartRunTimeSort)
			$this->SqlOrderBy .= ($this->ChartSortType == 2) ? " DESC" : "";

		// Set up ChartSql
		if ($this->Table->SourcTableIsCustomView) {
			$sql = $sqlChartSelect . $sqlChartBase;
			$sql = ewr_BuildReportSql($sql, $this->SqlWhere, $this->SqlGroupBy, "", $this->SqlOrderBy, "", "");
		} else {
			$sql = $sqlChartSelect . $sqlChartBase;
			$chartFilter = $this->SqlWhere;
			ewr_AddFilter($chartFilter, $this->Table->getSqlWhere());
			$sql = ewr_BuildReportSql($sql, $chartFilter, $this->SqlGroupBy, "", $this->SqlOrderBy, $pageFilter, "");
		}
		$this->ChartSql = $sql;
	}
	private $_dataLoaded = FALSE;

	// Load chart data
	function LoadChartData() {

		// Data already loaded, return
		if ($this->_dataLoaded)
			return;

		// Setup chart series data
		if ($this->ChartSeriesSql <> "") {
			$this->LoadSeries();
			if (EWR_DEBUG_ENABLED)
				ewr_SetDebugMsg("(Chart Series SQL): " . $this->ChartSeriesSql);
		}

		// Setup chart data
		if ($this->ChartSql <> "") {
			$this->LoadData();
			if (EWR_DEBUG_ENABLED)
			ewr_SetDebugMsg("(Chart SQL): " . $this->ChartSql);
		}

		// Sort data
		if ($this->ChartSFldName <> "" && $this->ChartSeriesType <> 1)
			$this->SortMultiData();
		else
			$this->SortData();
		$this->_dataLoaded = TRUE;
	}

	// Load Chart Series
	function LoadSeries() {
		$sSql = $this->ChartSeriesSql;
		$cnn = ReportConn($this->Table->DBID);
		$rscht = $cnn->Execute($sSql);
		$sdt = $this->SeriesDateType;
		while ($rscht && !$rscht->EOF) {
			$this->Series[] = $this->SeriesValue($rscht->fields[0], $sdt); // Series value
			$rscht->MoveNext();
		}
		if ($rscht) $rscht->Close();
	}

	// Get Chart Series value
	function SeriesValue($val, $dt) {
		if ($dt == "syq") {
			$ar = explode("|", $val);
			if (count($ar) >= 2)
				return $ar[0] . " " . ewr_QuarterName($ar[1]);
			else
				return $val;
		} elseif ($dt == "sym") {
			$ar = explode("|", $val);
			if (count($ar) >= 2)
				return $ar[0] . " " . ewr_MonthName($ar[1]);
			else
				return $val;
		} elseif ($dt == "sq") {
			return ewr_QuarterName($val);
		} elseif ($dt == "sm") {
			return ewr_MonthName($val);
		} else {
			if (is_string($val))
				return trim($val);
			else
				return $val;
		}
	}

	// Load Chart Data from SQL
	function LoadData() {
		$sSql = $this->ChartSql;
		$cnn = ReportConn($this->Table->DBID);
		$rscht = $cnn->Execute($sSql);
		while ($rscht && !$rscht->EOF) {
			$temp = array();
			for ($i = 0; $i < $rscht->FieldCount(); $i++)
				$temp[$i] = $rscht->fields[$i];
			$this->Data[] = $temp;
			$rscht->MoveNext();
		}
		if ($rscht) $rscht->Close();
	}

	// Get Chart X value
	function XValue($val, $dt) {
		if (is_numeric($dt)) {
			return ewr_FormatDateTime($val, $dt);
		} elseif ($dt == "y") {
			return $val;
		} elseif ($dt == "xyq") {
			$ar = explode("|", $val);
			if (count($ar) >= 2)
				return $ar[0] . " " . ewr_QuarterName($ar[1]);
			else
				return $val;
		} elseif ($dt == "xym") {
			$ar = explode("|", $val);
			if (count($ar) >= 2)
				return $ar[0] . " " . ewr_MonthName($ar[1]);
			else
				return $val;
		} elseif ($dt == "xq") {
			return ewr_QuarterName($val);
		}
		elseif ($dt == "xm") {
			return ewr_MonthName($val);
		} else {
			if (is_string($val))
				return trim($val);
			else
				return $val;
		}
	}

	// Sort chart data
	function SortData() {
		$ar = &$this->Data;
		$opt = $this->ChartSortType;
		$seq = $this->ChartSortSeq;
		if ((($opt < 3 || $opt > 4) && $seq == "") || (($opt < 1 || $opt > 4) && $seq <> ""))
			return;
		if (is_array($ar)) {
			$cntar = count($ar);
			for ($i = 0; $i < $cntar; $i++) {
				for ($j = $i+1; $j < $cntar; $j++) {
					switch ($opt) {
						case 1: // X values ascending
							$bSwap = ewr_CompareValueCustom($ar[$i][0], $ar[$j][0], $seq);
							break;
						case 2: // X values descending
							$bSwap = ewr_CompareValueCustom($ar[$j][0], $ar[$i][0], $seq);
							break;
						case 3: // Y values ascending
							$bSwap = ewr_CompareValueCustom($ar[$i][2], $ar[$j][2], $seq);
							break;
						case 4: // Y values descending
							$bSwap = ewr_CompareValueCustom($ar[$j][2], $ar[$i][2], $seq);
					}
					if ($bSwap) {
						$tmpar = $ar[$i];
						$ar[$i] = $ar[$j];
						$ar[$j] = $tmpar;
					}
				}
			}
		}
	}

	// Sort chart multi series data
	function SortMultiData() {
		$ar = &$this->Data;
		$opt = $this->ChartSortType;
		$seq = $this->ChartSortSeq;
		if (!is_array($ar) || (($opt < 3 || $opt > 4) && $seq == "") || (($opt < 1 || $opt > 4) && $seq <> ""))
			return;

		// Obtain a list of columns
		foreach ($ar as $key => $row) {
			$xvalues[$key] = $row[0];
			$series[$key] = $row[1];
			$yvalues[$key] = $row[2];
			$ysums[$key] = $row[0]; // Store the x-value for the time being
			if (isset($xsums[$row[0]])) {
				$xsums[$row[0]] += $row[2];
			} else {
				$xsums[$row[0]] = $row[2];
			}
		}

		// Set up Y sum
		if ($opt == 3 || $opt == 4) {
			$cnt = count($ysums);
			for ($i=0; $i<$cnt; $i++)
				$ysums[$i] = $xsums[$ysums[$i]];
		}

		// No specific sequence, use array_multisort
		if ($seq == "") {
			switch ($opt) {
				case 1: // X values ascending
					array_multisort($xvalues, SORT_ASC, $ar);
					break;
				case 2: // X values descending
					array_multisort($xvalues, SORT_DESC, $ar);
					break;
				case 3:
				case 4: // Y values
					if ($opt == 3) { // Ascending
						array_multisort($ysums, SORT_ASC, $ar);
					} elseif ($opt == 4) { // Descending
						array_multisort($ysums, SORT_DESC, $ar);
					}
			}

		// Handle specific sequence
		} else {

			// Build key list
			if ($opt == 1 || $opt == 2)
				$vals = array_unique($xvalues);
			else
				$vals = array_unique($ysums);
			foreach ($vals as $key => $val) {
				$keys[] = array($key, $val);
			}

			// Sort key list based on specific sequence
			$cntkey = count($keys);
			for ($i = 0; $i < $cntkey; $i++) {
				for ($j = $i+1; $j < $cntkey; $j++) {
					switch ($opt) {

						// Ascending
						case 1:
						case 3:
							$bSwap = ewr_CompareValueCustom($keys[$i][1], $keys[$j][1], $seq);
							break;

						// Descending
						case 2:
						case 4:
							$bSwap = ewr_CompareValueCustom($keys[$j][1], $keys[$i][1], $seq);
							break;
					}
					if ($bSwap) {
						$tmpkey = $keys[$i];
						$keys[$i] = $keys[$j];
						$keys[$j] = $tmpkey;
					}
				}
			}
			for ($i = 0; $i < $cntkey; $i++) {
				$xsorted[] = $xvalues[$keys[$i][0]];
			}

			// Sort array based on x sequence
			$arwrk = $ar;
			$rowcnt = 0;
			$cntx = intval(count($xsorted));
			for ($i = 0; $i < $cntx; $i++) {
				foreach ($arwrk as $key => $row) {
					if ($row[0] == $xsorted[$i]) {
						$ar[$rowcnt] = $row;
						$rowcnt++;
					}
				}
			}
		}
	}

	// Chart XML
	function ChartXml() {

		// Load chart data
		$this->LoadChartData();
		$this->LoadChartParms();
		$this->LoadViewData();
		$this->Chart_Rendering();
		$cht_type = $this->LoadParm("type");

		// Format line color for Multi-Series Column Dual Y chart
		$cht_lineColor = $this->IsCombinationChart() ? $this->LoadParm("lineColor") : "";
		$chartseries = &$this->Series;
		$chartdata = &$this->ViewData;
		$cht_series = $this->IsSingleSeriesChart() ? 0 : 1; // $cht_series = 1 (Multi series charts)
		$cht_series_type = $this->LoadParm("seriestype");
		$cht_alpha = $this->LoadParm("alpha");

		// Hide legend for single series (Column 2D / Line 2D / Area 2D)
		$cht_single_series = $this->ScrollChart && $this->IsSingleSeriesChart() ? 1 : 0;
		if ($this->IsZoomLineChart()) // Zoom line chart, use compat data mode
			$this->CompatDataMode = TRUE;
		if (is_array($chartdata)) {
			$this->WriteChartHeader(); // Write chart header

			// Candlestick
			if ($this->IsCandlestickChart()) {

				// Write candlestick cat
				if (count($chartdata[0]) >= 7) {
					$cats = $this->XmlDoc->createElement("categories");
					$this->XmlRoot->appendChild($cats);
					$cntcat = count($chartdata);
					for ($i = 0; $i < $cntcat; $i++) {
						$xindex = $i+1;
						$name = $chartdata[$i][6];
						if ($name <> "")
							$this->WriteChartCandlestickCatContent($cats, $xindex, $name);
					}
				}

				// Write candlestick data
				$data = $this->XmlDoc->createElement("dataset");
				$this->XmlRoot->appendChild($data);
				$cntdata = count($chartdata);
				for ($i = 0; $i < $cntdata; $i++) {
					$dt = $chartdata[$i][0];
					$open = is_null($chartdata[$i][2]) ? 0 : (float)$chartdata[$i][2];
					$high = is_null($chartdata[$i][3]) ? 0 : (float)$chartdata[$i][3];
					$low = is_null($chartdata[$i][4]) ? 0 : (float)$chartdata[$i][4];
					$close = is_null($chartdata[$i][5]) ? 0 : (float)$chartdata[$i][5];
					$xindex = $i+1;
					$lnk = $this->GetChartLink($this->ChartDrillDownUrl, $this->Data[$i]);
					$this->WriteChartCandlestickContent($data, $dt, $open, $high, $low, $close, $xindex, $lnk);
				}

			// Multi series
			} else if ($cht_series == 1) {

				// Multi-Y values
				if ($cht_series_type == "1") {

					// Write cat
					if ($this->CompatDataMode) {
						$cntcat = count($chartdata);
						$content = "";
						for ($i = 0; $i < $cntcat; $i++) {
							$name = $this->ChartFormatName($chartdata[$i][0]);
							if ($content <> "") $content .= $this->DataSeparator;
							$content .= $name;
						}
						$cats = $this->XmlDoc->createElement("categories", $content);
						$this->XmlRoot->appendChild($cats);
					} else {
						$cats = $this->XmlDoc->createElement("categories");
						$this->XmlRoot->appendChild($cats);
						$cntcat = count($chartdata);
						for ($i = 0; $i < $cntcat; $i++) {
							$name = $this->ChartFormatName($chartdata[$i][0]);
							$this->WriteChartCatContent($cats, $name);
						}
					}

					// Write series
					$cntdata = count($chartdata);
					$cntseries = count($chartseries);
					if ($cntseries > count($chartdata[0])-2) $cntseries = count($chartdata[0])-2;
					for ($i = 0; $i < $cntseries; $i++) {
						if ($this->CompatDataMode) {
							$content = "";
							$bShowSeries = EWR_CHART_SHOW_BLANK_SERIES;
							for ($j = 0; $j < $cntdata; $j++) {
								$val = $chartdata[$j][$i+2];
								$val = (is_null($val)) ? 0 : (float)$val;
								if ($val <> 0) $bShowSeries = TRUE;
								if ($content <> "") $content .= $this->DataSeparator;
						 		$content .= $val;
							}
							$dataset = $this->XmlDoc->createElement("dataset", $content);
							$series = @$chartseries[$i];
							$seriesname = is_array($series) ? $series[0] : $series;
							if (is_null($seriesname)) {
								$seriesname = $ReportLanguage->Phrase("NullLabel");
							} elseif ($seriesname == "") {
								$seriesname = $ReportLanguage->Phrase("EmptyLabel");
							}
							$this->WriteAtt($dataset, "seriesname", $seriesname);
							if ($bShowSeries)
								$this->XmlRoot->appendChild($dataset);
						} else {
							$color = $this->GetPaletteColor($i);
							$renderAs = $this->GetRenderAs($i);
							$bShowSeries = EWR_CHART_SHOW_BLANK_SERIES;
							$dataset = $this->XmlDoc->createElement("dataset");
							$this->WriteChartSeriesHeader($dataset, $chartseries[$i], $color, $cht_alpha, $cht_lineColor, $renderAs);
							$bWriteSeriesHeader = TRUE;
							for ($j = 0; $j < $cntdata; $j++) {
								$val = $chartdata[$j][$i+2];
								$val = (is_null($val)) ? 0 : (float)$val;
								if ($val <> 0) $bShowSeries = TRUE;
								$lnk = $this->GetChartLink($this->ChartDrillDownUrl, $this->Data[$j]);
								$this->WriteChartSeriesContent($dataset, $val, "", "", $lnk);
							}
							if ($bShowSeries)
								$this->XmlRoot->appendChild($dataset);
						}
					}

				// Series field
				} else {

					// Get series names
					if (is_array($chartseries)) {
						$nSeries = count($chartseries);
					} else {
						$nSeries = 0;
					}

					// Write cat
					$cntdata = count($chartdata);
					$chartcats = array();
					if ($this->CompatDataMode) {
						$content = "";
						for ($i = 0; $i < $cntdata; $i++) {
							$name = $chartdata[$i][0];
							if (!in_array($name, $chartcats)) {
								if ($content <> "") $content .= $this->DataSeparator;
								$content .= $name;
								$chartcats[] = $name;
							}
						}
						$cats = $this->XmlDoc->createElement("categories", $content);
						$this->XmlRoot->appendChild($cats);
					} else {
						$cats = $this->XmlDoc->createElement("categories");
						$this->XmlRoot->appendChild($cats);
						for ($i = 0; $i < $cntdata; $i++) {
							$name = $chartdata[$i][0];
							if (!in_array($name, $chartcats)) {
								$this->WriteChartCatContent($cats, $name);
								$chartcats[] = $name;
							}
						}
					}

					// Write series
					$cntcats = count($chartcats);
					$cntdata = count($chartdata);
					for ($i = 0; $i < $nSeries; $i++) {
						$seriesname = (is_array($chartseries[$i])) ? $chartseries[$i][0] : $chartseries[$i];
						if ($this->CompatDataMode) {
							$content = "";
							$bShowSeries = EWR_CHART_SHOW_BLANK_SERIES;
							for ($j = 0; $j < $cntcats; $j++) {
								$val = 0;
								for ($k = 0; $k < $cntdata; $k++) {
									if ($chartdata[$k][0] == $chartcats[$j] && $chartdata[$k][1] == $seriesname) {
										$val = $chartdata[$k][2];
										$val = (is_null($val)) ? 0 : (float)$val;
										if ($val <> 0) $bShowSeries = TRUE;
										break;
									}
								}
								if ($content <> "") $content .= $this->DataSeparator;
						 		$content .= $val;
							}
							$dataset = $this->XmlDoc->createElement("dataset", $content);
							if (is_null($seriesname)) {
								$seriesname = $ReportLanguage->Phrase("NullLabel");
							} elseif ($seriesname == "") {
								$seriesname = $ReportLanguage->Phrase("EmptyLabel");
							}
							$this->WriteAtt($dataset, "seriesname", $seriesname);
							if ($bShowSeries)
								$this->XmlRoot->appendChild($dataset);
						} else {
							$color = $this->GetPaletteColor($i);
							$renderAs = $this->GetRenderAs($i);
							$bShowSeries = EWR_CHART_SHOW_BLANK_SERIES;
							$dataset = $this->XmlDoc->createElement("dataset");
							$this->WriteChartSeriesHeader($dataset, $chartseries[$i], $color, $cht_alpha, $cht_lineColor, $renderAs);
							for ($j = 0; $j < $cntcats; $j++) {
								$val = 0;
								$lnk = "";
								for ($k = 0; $k < $cntdata; $k++) {
									if ($chartdata[$k][0] == $chartcats[$j] && $chartdata[$k][1] == $seriesname) {
										$val = $chartdata[$k][2];
										$val = (is_null($val)) ? 0 : (float)$val;
										if ($val <> 0) $bShowSeries = TRUE;
										$lnk = $this->GetChartLink($this->ChartDrillDownUrl, $this->Data[$k]);
										break;
									}
								}
								$this->WriteChartSeriesContent($dataset, $val, "", "", $lnk);
							}
							if ($bShowSeries)
								$this->XmlRoot->appendChild($dataset);
						}
					}
				}

			// Show single series
			} elseif ($cht_single_series == 1) {

				// Write multiple cats
				$cats = $this->XmlDoc->createElement("categories");
				$this->XmlRoot->appendChild($cats);
				$cntcat = count($chartdata);
				for ($i = 0; $i < $cntcat; $i++) {
					$name = $this->ChartFormatName($chartdata[$i][0]);
					if ($chartdata[$i][1] <> "") 
						$name .= ", " . $chartdata[$i][1];
					$this->WriteChartCatContent($cats, $name);
				}

				// Write series
				$toolTipSep = $this->LoadParm("toolTipSepChar");
				if ($toolTipSep == "") $toolTipSep = ":";
				$cntdata = count($chartdata);
				$dataset = $this->XmlDoc->createElement("dataset");
				$this->WriteChartSeriesHeader($dataset, "", "", $cht_alpha, $cht_lineColor);
				for ($i = 0; $i < $cntdata; $i++) {
					$name = $this->ChartFormatName($chartdata[$i][0]);
					if ($chartdata[$i][1] <> "") 
						$name .= ", " . $chartdata[$i][1];
					$val = $chartdata[$i][2];
					$val = (is_null($val)) ? 0 : (float)$val;
					$color = $this->GetPaletteColor($i);
					$toolText = $name . $toolTipSep . $this->ChartFormatNumber($val);
					$lnk = $this->GetChartLink($this->ChartDrillDownUrl, $this->Data[$i]);
					$this->WriteChartSeriesContent($dataset, $val, $color, $cht_alpha, $lnk, $toolText);
					$this->XmlRoot->appendChild($dataset);
				}

			// Single series
			} else {
				$cntdata = count($chartdata);
				for ($i = 0; $i < $cntdata; $i++) {
					$name = $this->ChartFormatName($chartdata[$i][0]);
					$color = $this->GetPaletteColor($i);
					if ($chartdata[$i][1] <> "") 
						$name .= ", " . $chartdata[$i][1];
					$val = $chartdata[$i][2];
					$val = (is_null($val)) ? 0 : (float)$val;
					$lnk = $this->GetChartLink($this->ChartDrillDownUrl, $this->Data[$i]);
					$this->WriteChartContent($this->XmlRoot, $name, $val, $color, $cht_alpha, $lnk); // Get chart content
				}
			}

			// Get trend lines
			$this->WriteChartTrendLines();
		}
		$wrk = $this->XmlDoc->saveXML();
		$this->Chart_Rendered($wrk);
		return $this->XmlRoot ? $wrk : "";
	}

	// Get renderAs
	function GetRenderAs($i) {
		$ar = explode(",", $this->ChartSeriesRenderAs);
		if ($i < count($ar))
			return $ar[$i];
		return "";
	}

	// Get color
	function GetPaletteColor($i) {
		$colorpalette = $this->LoadParm("colorpalette");
		$ar_cht_colorpalette = explode("|", $colorpalette);
		if (is_array($ar_cht_colorpalette))
			$cntar = count($ar_cht_colorpalette);
		return $ar_cht_colorpalette[$i % $cntar];
	}

	// Output chart header
	function WriteChartHeader() {
		$cht_parms = $this->Parms;
		$chartElement = "chart";
		$chart = $this->XmlDoc->createElement($chartElement);
		$this->XmlRoot = &$chart;
		$this->XmlDoc->appendChild($chart);
		if (is_array($cht_parms)) {
			foreach ($cht_parms as $parm) {
				if ($parm[2])
					$this->WriteAtt($chart, $parm[0], $parm[1]);
			}
		}
		if ($this->CompatDataMode) {
			$this->WriteAtt($chart, "compactdatamode", "1");
			$this->WriteAtt($chart, "dataseparator", $this->DataSeparator);
		}
	}

	// Output trend lines
	function WriteChartTrendLines() {
		$cht_trends = $this->Trends;
		if (is_array($cht_trends)) {
			foreach ($cht_trends as $trend) {
				$trends = $this->XmlDoc->createElement('trendlines');
				$this->XmlRoot->appendChild($trends);

				// Get all trend lines
				$this->WriteChartTrendLine($trends, $trend);
			}
		}
	}

	// Output trend line
	function WriteChartTrendLine(&$node, $ar) {
		$line = $this->XmlDoc->createElement('line');
		@list($startval, $endval, $color, $dispval, $thickness, $trendzone, $showontop, $alpha, $tooltext, $valueonright, $dashed, $dashlen, $dashgap, $parentyaxis) = $ar;
		$this->WriteAtt($line, "startValue", $startval); // Starting y value
		if ($endval <> 0)
			$this->WriteAtt($line, "endValue", $endval); // Ending y value
		$this->WriteAtt($line, "color", $this->CheckColorCode($color)); // Color
		if ($dispval <> "")
			$this->WriteAtt($line, "displayValue", $dispval); // Display value
		if ($thickness > 0)
			$this->WriteAtt($line, "thickness", $thickness); // Thickness
		$this->WriteAtt($line, "isTrendZone", $trendzone); // Display trend as zone or line
		$this->WriteAtt($line, "showOnTop", $showontop); // Show on top
		if ($alpha > 0)
			$this->WriteAtt($line, "alpha", $alpha); // Alpha
		if ($tooltext <> "")
			$this->WriteAtt($line, "toolText", $tooltext); // Tool text
		if ($valueonright <> "0")
			$this->WriteAtt($line, "valueOnRight", $valueonright); // Value on right
		if ($dashed <> "0") {
			$this->WriteAtt($line, "dashed", $dashed); // Dashed trend line
			$this->WriteAtt($line, "dashLen", $dashlen); // Dashed trend length
			$this->WriteAtt($line, "dashGap", $dashgap); // Dashed line gap
		}
		if ($parentyaxis <> "")
			$this->WriteAtt($line, "parentYAxis", $parentyaxis); // Parent Y Axis
		$node->appendChild($line);
	}

	// Series header/footer XML (multi series)
	function WriteChartSeriesHeader(&$node, $series, $color, $alpha, $linecolor, $renderAs = "") {
		global $ReportLanguage;
		$seriesname = is_array($series) ? $series[0] : $series;
		if (is_null($seriesname)) {
			$seriesname = $ReportLanguage->Phrase("NullLabel");
		} elseif ($seriesname == "") {
			$seriesname = $ReportLanguage->Phrase("EmptyLabel");
		}
		$this->WriteAtt($node, "seriesname", $seriesname);
		if (is_array($series)) {
			if ($series[1] == "S" && $linecolor <> "")
				$this->WriteAtt($node, "color", $linecolor);
			else
				$this->WriteAtt($node, "color", $color);
		} else {
				$this->WriteAtt($node, "color", $color);
		}
		$this->WriteAtt($node, "alpha", $alpha);
		if (is_array($series))
			$this->WriteAtt($node, "parentyaxis", $series[1]);
		if ($renderAs <> "")
			$this->WriteAtt($node, "renderas", $renderAs);
		$this->Chart_DataRendered($node);
	}

	// Series content XML (multi series)
	function WriteChartSeriesContent(&$node, $val, $color = "", $alpha = "", $lnk = "", $toolText = "") {
		$set = $this->XmlDoc->createElement('set');
		if ($this->IsStackedChart() && $val == 0 && !EWR_CHART_SHOW_ZERO_IN_STACK_CHART)
			$this->WriteAtt($set, "value", "");
		else
			$this->WriteAtt($set, "value", $this->ChartFormatNumber($val));
		if ($color <> "")
			$this->WriteAtt($set, "color", $color);
		if ($alpha <> "")
			$this->WriteAtt($set, "alpha", $alpha);
		if ($lnk <> "")
			$this->WriteAtt($set, "link", $lnk);
		if ($toolText <> "")
			$this->WriteAtt($set, "toolText", $toolText);
		$this->Chart_DataRendered($set);
		$node->appendChild($set);
	}

	// Category content XML (Candlestick category)
	function WriteChartCandlestickCatContent(&$node, $xindex, $name) {
		$cat = $this->XmlDoc->createElement("category");
		$this->WriteAtt($cat, "label", $name);
		$this->WriteAtt($cat, "x", $xindex);
		$this->WriteAtt($cat, "showline", "1");
		$this->Chart_DataRendered($cat);
		$node->appendChild($cat);
	}

	// Chart content XML (Candlestick)
	function WriteChartCandlestickContent(&$node, $dt, $open, $high, $low, $close, $xindex, $lnk = "") {
		$set = $this->XmlDoc->createElement("set");
		$this->WriteAtt($set, "date", ewr_FormatDateTime($dt, 5)); // Format as yyyy/mm/dd
		$this->WriteAtt($set, "open", $this->ChartFormatNumber($open));
		$this->WriteAtt($set, "high", $this->ChartFormatNumber($high));
		$this->WriteAtt($set, "low", $this->ChartFormatNumber($low));
		$this->WriteAtt($set, "close", $this->ChartFormatNumber($close));
		if ($xindex <> "")
			$this->WriteAtt($set, "x", $xindex);
		if ($lnk <> "")
			$this->WriteAtt($set, "link", $lnk);
		$this->Chart_DataRendered($set);
		$node->appendChild($set);
	}

	// Format name for chart
	function ChartFormatName($name) {
		global $ReportLanguage;
		if (is_null($name)) {
			return $ReportLanguage->Phrase("NullLabel");
		} elseif ($name == "") {
			return $ReportLanguage->Phrase("EmptyLabel");
		} else {
			return $name;
		}
	}

	// Write attribute
	function WriteAtt(&$node, $name, $val) {
		$val = $this->CheckColorCode(strval($val));
		$val = $this->ChartEncode($val);
		if ($node->hasAttribute($name)) {
			$node->getAttributeNode($name)->value = ewr_XmlEncode(ewr_ConvertToUtf8($val));
		} else {
			$att = $this->XmlDoc->createAttribute($name);
			$att->value = ewr_XmlEncode(ewr_ConvertToUtf8($val));
			$node->appendChild($att);
		}
	}

	// Check color code
	function CheckColorCode($val) {
		return $val;
	}

	// Is single series chart
	function IsSingleSeriesChart($typ = 0) {
		$typ = $typ > 0 ? $typ : $this->ChartType;
		return substr(strval($typ),0,1) == "1";
	}

	// Is zoom line chart
	function IsZoomLineChart($typ = 0) {
		$typ = $typ > 0 ? $typ : $this->ChartType;
		return substr(strval($typ),2) == "92";
	}

	// Is stack chart
	function IsStackedChart($typ = 0) {
		$typ = $typ > 0 ? $typ : $this->ChartType;
		return substr(strval($typ),0,1) == "3";
	}

	// Is combination chart
	function IsCombinationChart($typ = 0) {
		$typ = $typ > 0 ? $typ : $this->ChartType;
		return substr(strval($typ),0,1) == "4";
	}

	// Is candlestick chart
	function IsCandlestickChart($typ = 0) {
		$typ = $typ > 0 ? $typ : $this->ChartType;
		return strval($typ) == "5099";
	}

	// Is gantt chart
	function IsGanttChart($typ = 0) {
		$typ = $typ > 0 ? $typ : $this->ChartType;
		return strval($typ) == "6098";
	}

	// Google chart
	function IsGoogleChart($typ = 0) {
		global $EWR_NON_FUSIONCHARTS;
		$typ = $typ > 0 ? $typ : $this->ChartType;
		return !$EWR_NON_FUSIONCHARTS && ($this->IsCandlestickChart($typ) || $this->IsGanttChart($typ));
	}

	// Encode "+" as "%2B" for FusionChartsFree
	function ChartEncode($val) {
		return $val;
	}

	// Format number for chart
	function ChartFormatNumber($v) {
		$cht_decimalprecision = $this->LoadParm("decimals");
		if (is_null($cht_decimalprecision)) {
			if ($this->ChartDefaultDecimalPrecision >= 0)
				$cht_decimalprecision = $this->ChartDefaultDecimalPrecision; // Use default precision
			else
				$cht_decimalprecision = (($v-(int)$v) == 0) ? 0 : strlen(abs($v-(int)$v))-2; // Use original decimal precision
		}
		return number_format($v, $cht_decimalprecision, '.', '');
	}

	// Category content XML (multi series)
	function WriteChartCatContent(&$node, $name) {
		$cat = $this->XmlDoc->createElement("category");
		$this->WriteAtt($cat, "label", $name);
		$this->Chart_DataRendered($cat);
		$node->appendChild($cat);
	}

	// Chart content XML
	function WriteChartContent(&$node, $name, $val, $color, $alpha, $lnk) {
		$cht_shownames = $this->LoadParm("shownames");
		$set = $this->XmlDoc->createElement("set");
		$this->WriteAtt($set, "label", $name);
		$this->WriteAtt($set, "value", $this->ChartFormatNumber($val));
		$this->WriteAtt($set, "color", $color);
		$this->WriteAtt($set, "alpha", $alpha);
		$this->WriteAtt($set, "link", $lnk);
		if ($cht_shownames == "1")
			$this->WriteAtt($set, "showName", "1");
		$this->Chart_DataRendered($set);
		$node->appendChild($set);
	}

	// Get chart link
	function GetChartLink($src, $row) {
		if ($src <> "" && is_array($row)) {
			$cntrow = count($row);
			$lnk = $src;
			$sdt = $this->SeriesDateType;
			$xdt = $this->XAxisDateFormat;
			$ndt = $this->IsCandlestickChart() ? $this->NameDateFormat : "";
			if ($sdt <> "") $xdt = $sdt;
			if (preg_match("/&t=([^&]+)&/", $lnk, $m))
				$tblcaption = $GLOBALS["ReportLanguage"]->TablePhrase($m[1], 'TblCaption');
			else
				$tblcaption = "";
			for ($i = 0; $i < $cntrow; $i++) { // Link format: %i:Parameter:FieldType%
				if (preg_match("/%" . $i . ":([^%:]*):([\d]+)%/", $lnk, $m)) {
					$fldtype = ewr_FieldDataType($m[2]);
					if ($i == 0) { // Format X SQL
						$lnk = str_replace($m[0], ewr_Encrypt($this->XSQL("@" . $m[1], $fldtype, $row[$i], $xdt)), $lnk);
					} elseif ($i == 1) { // Format Series SQL
						$lnk = str_replace($m[0], ewr_Encrypt($this->SeriesSQL("@" . $m[1], $fldtype, $row[$i], $sdt)), $lnk);
					} else {
						$lnk = str_replace($m[0], ewr_Encrypt("@" . $m[1] . " = " . ewr_QuotedValue($row[$i], $fldtype, $this->Table->DBID)), $lnk);
					}
				}
			}

			// Fusioncharts do not support "-" in drill down link. Need to replace "-" with another non-base64 character that is supported by FusionCharts (now use "$"). To be changed back to "-" before decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$lnk = str_replace("-", "$", $lnk);
			return "javascript:" . ewr_DrillDownJs($lnk, $this->ID, $tblcaption, $this->UseDrillDownPanel, "div_" . $this->ID, FALSE);
		} else {
			return "";
		}
	}

	// Get Chart X SQL
	function XSQL($fldsql, $fldtype, $val, $dt) {
		$dbid = $this->Table->DBID;
		if (is_numeric($dt)) {
			return $fldsql . " = " . ewr_QuotedValue(ewr_UnFormatDateTime($val, $dt), $fldtype, $dbid);
		} elseif ($dt == "y") {
			if (is_numeric($val))
				return ewr_GroupSql($fldsql, "y", 0, $dbid) . " = " . ewr_QuotedValue($val, EWR_DATATYPE_NUMBER, $dbid);
			else
				return $fldsql . " = " . ewr_QuotedValue($val, $fldtype, $dbid);
		} elseif ($dt == "xyq") {
			$ar = explode("|", $val);
			if (count($ar) >= 2 && is_numeric($ar[0]) && is_numeric($ar[1]))
				return ewr_GroupSql($fldsql, "y", 0, $dbid) . " = " . ewr_QuotedValue($ar[0], EWR_DATATYPE_NUMBER, $dbid) . " AND " . ewr_GroupSql($fldsql, "xq", 0, $dbid) . " = " . ewr_QuotedValue($ar[1], EWR_DATATYPE_NUMBER, $dbid);
			else
				return $fldsql . " = " . ewr_QuotedValue($val, $fldtype, $dbid);
		} elseif ($dt == "xym") {
			$ar = explode("|", $val);
			if (count($ar) >= 2 && is_numeric($ar[0]) && is_numeric($ar[1]))
				return ewr_GroupSql($fldsql, "y", 0, $dbid) . " = " . ewr_QuotedValue($ar[0], EWR_DATATYPE_NUMBER, $dbid) . " AND " . ewr_GroupSql($fldsql, "xm", 0, $dbid) . " = " . ewr_QuotedValue($ar[1], EWR_DATATYPE_NUMBER, $dbid);
			else
				return $fldsql . " = " . ewr_QuotedValue($val, $fldtype, $dbid);
		} elseif ($dt == "xq") {
			return ewr_GroupSql($fldsql, "xq", 0, $dbid) . " = " . ewr_QuotedValue($val, EWR_DATATYPE_NUMBER, $dbid);
		} elseif ($dt == "xm") {
			return ewr_GroupSql($fldsql, "xm", 0, $dbid) . " = " . ewr_QuotedValue($val, EWR_DATATYPE_NUMBER, $dbid);
		} else {
			return $fldsql . " = " . ewr_QuotedValue($val, $fldtype, $dbid);
		}
	}

	// Get Chart Series SQL
	function SeriesSQL($fldsql, $fldtype, $val, $dt) {
		$dbid = $this->Table->DBID;
		if ($dt == "syq") {
			$ar = explode("|", $val);
			if (count($ar) >= 2 && is_numeric($ar[0]) && is_numeric($ar[1]))
				return ewr_GroupSql($fldsql, "y", 0, $dbid) . " = " . ewr_QuotedValue($ar[0], EWR_DATATYPE_NUMBER, $dbid) . " AND " . ewr_GroupSql($fldsql, "xq", 0, $dbid) . " = " . ewr_QuotedValue($ar[1], EWR_DATATYPE_NUMBER, $dbid);
			else
				return $fldsql . " = " . ewr_QuotedValue($val, $fldtype, $dbid);
		} elseif ($dt == "sym") {
			$ar = explode("|", $val);
			if (count($ar) >= 2 && is_numeric($ar[0]) && is_numeric($ar[1]))
				return ewr_GroupSql($fldsql, "y", 0, $dbid) . " = " . ewr_QuotedValue($ar[0], EWR_DATATYPE_NUMBER, $dbid) . " AND " . ewr_GroupSql($fldsql, "xm", 0, $dbid) . " = " . ewr_QuotedValue($ar[1], EWR_DATATYPE_NUMBER, $dbid);
			else
				return $fldsql . " = " . ewr_QuotedValue($val, $fldtype, $dbid);
		} elseif ($dt == "sq") {
			return ewr_GroupSql($fldsql, "xq", 0, $dbid) . " = " . ewr_QuotedValue($val, EWR_DATATYPE_NUMBER, $dbid);
		} elseif ($dt == "sm") {
			return ewr_GroupSql($fldsql, "xm", 0, $dbid) . " = " . ewr_QuotedValue($val, EWR_DATATYPE_NUMBER, $dbid);
		} else {
			return $fldsql . " = " . ewr_QuotedValue($val, $fldtype, $dbid);
		}
	}

	// Show chart (Google Charts)
	function ShowGoogleChart($width, $height) {
		global $ReportLanguage;
		$xml = $this->ChartXml();
		$typ = $this->ChartType; // Chart type (nnnn)
		$id = $this->ID; // Chart ID
		$parms = $this->Parms; // "bgcolor=FFFFFF|..."
		$trends = $this->Trends; // Trend lines
		$align = $this->ChartAlign;

		// Candlestick
		if ($this->IsCandlestickChart()) {

			// Get chart data
			$ar = ewr_XmlToArray($xml);

			// Get options
			$options = @$ar["chart"]["options"][0]["value"];
			if ($options <> "") // Decode user options to array
				$options = json_decode($options, TRUE); // Options must be UTF-8 encoded
			if (!is_array($options))
				$options = array();
			$sets = @$ar["chart"]["dataset"]["set"];
			$ar = array();

			// Modify your colors here
			$risingColor = "green"; // Rising color
			$fallingColor = "red"; // Falling color
			$elementColor = "#888888"; // Chart element color
			if (is_array($sets)) {
				foreach ($sets as $set_id => $set) {
					$date = str_replace("/", "-", $set["attr"]["date"]);
					$open = floatval($set["attr"]["open"]);
					$high = floatval($set["attr"]["high"]);
					$low = floatval($set["attr"]["low"]);
					$close = floatval($set["attr"]["close"]);
					$ar[] = array($date, $low, $open, $close, $high);
				}
			}

			// Output JavaScript
			$wrk = "<script type=\"text/javascript\">\r\n";
			$wrk .= "google.charts.load('current', {'packages':['corechart'], 'language': EWR_LANGUAGE_ID});\r\n";
			$wrk .= "google.charts.setOnLoadCallback(function() {\r\n";
			$wrk .= "\tvar data = " . ewr_ConvertFromUtf8(json_encode($ar)) . ";\r\n";
			$wrk .= "\tfor (var i = 0; i < data.length; i++)\r\n";
			$wrk .= "\t\tdata[i][0] = new Date(data[i][0]);\r\n";
			$wrk .= "\tvar d = new google.visualization.arrayToDataTable(data, true); // Treat first row as data as well\r\n";
			$defOptions = array("legend" => "none", "width" => $width, "height" => $height);
			$defOptions["bar"] = array("groupWidth" => 1);
			$defOptions["candlestick"] = array("hollowIsRising" => TRUE,
				"fallingColor" => array("fill" => $fallingColor, "stroke" => $fallingColor, "strokeWidth" => 1),
				"risingColor" => array("fill" => $risingColor, "stroke" => $risingColor, "strokeWidth" => 1),
			);
			$defOptions["colors"] = array($elementColor);
			$defOptions["title"] = $this->ChartCaption();
			$defOptions["fontName"] = EWR_FONT_NAME;
			$defOptions["fontSize"] = EWR_FONT_SIZE;
			$options = array_merge($defOptions, $options);
			$wrk .= "\tvar options = " . ewr_ConvertFromUtf8(json_encode($options)) . ";\r\n";
			$wrk .= "\tvar chart = new google.visualization.CandlestickChart(document.getElementById('div_$id'));\r\n";
			$wrk .= "\tewrExportCharts[ewrExportCharts.length] = { 'id': 'chart_$id', 'chart': chart };\r\n"; // Export chart
			$wrk .= "\tvar args = { 'id': 'chart_$id', 'chart': chart, 'data': d, 'options': options };\r\n";
			$wrk .= "\tjQuery(document).trigger('draw', [args]);\r\n";
			$wrk .= "\tchart.draw(args.data, args.options);\r\n";
			if (EWR_DEBUG_ENABLED)
				$wrk .= "\tconsole.log(args);\r\n";
			$wrk .= "});\r\n";
			$wrk .= "</script>\r\n";
		}
		return $wrk;
	}

	// FusionCharts XT - list of charts
	// http://www.fusioncharts.com/dev/getting-started/list-of-charts.html
	//
	// Format - array(chart_id, array(normal_chart_name, scroll_chart_name))
	// chart_id - abnn
	// **id = chart_id in previous version
	// - a: 1 = Single Series, 2 = Multi Series, 3 = Stacked, 4 = Combination, 5 = Financial, 6 = Other
	// - b: 0 = 2D, 1 = 3D
	// - nn: 01 = Column, 02 = Line, 03 = Area, 04 = Bar, 05 = Pie, 06 = Doughnut, 07 Pareto
	// - nn: 91 = Marimekko, 92 = Zoom-line
	// - nn: 99 = Candlestick, 98 = Gantt

	var $FCCharts = array(

		// Single Series
		array(1001, array("column2d", "scrollcolumn2d")),				// Column 2D (**1)
		array(1101, array("column3d")),									// Column 3D (**5)
		array(1002, array("line", "scrollline2d")),						// Line 2D (**4)
		array(1003, array("area2d", "scrollarea2d")),					// Area 2D (**7)
		array(1004, array("bar2d")),									// Bar 2D (**3)
		array(1104, array("bar3d")), 									// Bar 3D (**104)
		array(1005, array("pie2d")),									// Pie 2D (**2)
		array(1105, array("pie3d")),									// Pie 3D (**6)
		array(1006, array("doughnut2d")),								// Doughnut 2D (**8)
		array(1106, array("doughnut3d")),								// Doughnut 3D (**101)
		array(1007, array("pareto2d")),									// Pareto 2D
		array(1107, array("pareto3d")),									// Pareto 3D

		// Multi Series
		array(2001, array("mscolumn2d", "scrollcolumn2d")),				// Multi-series Column 2D (**9)
		array(2101, array("mscolumn3d")),								// Multi-series Column 3D (**10)
		array(2002, array("msline", "scrollline2d")),					// Multi-series Line 2D (**11)
		array(2003, array("msarea", "scrollarea2d")),					// Multi-series Area 2D (**12)
		array(2004, array("msbar2d")),									// Multi-series Bar 2D (**13)
		array(2104, array("msbar3d")),									// Multi-series Bar 3D (**102)
		array(2091, array("marimekko")),								// Multi-series Marimekko
		array(2092, array("zoomline")),									// Multi-series Zoom-line (CompatDataMode = TRUE)

		// Stacked
		array(3001, array("stackedcolumn2d", "scrollstackedcolumn2d")),	// Stacked Column 2D (**14)
		array(3101, array("stackedcolumn3d")),							// Stacked Column 3D (**15)
		array(3003, array("stackedarea2d")),							// Stacked Area 2D (**16)
		array(3004, array("stackedbar2d")),								// Stacked Bar 2D (**17)
		array(3104, array("stackedbar3d")),								// Stacked Bar 3D (**103)

		// Reserved, NOT supported
		// http://www.fusioncharts.com/dev/chart-attributes.html?chart=msstackedcolumn2d
		//array(3014, array("msstackedcolumn2d")),						// Multi-series Stacked Column 2D
		// Combination

		array(4001, array("mscombi2d", "scrollcombi2d")),				// Multi-series 2D Single Y Combination Chart (Column + Line + Area)
		array(4101, array("mscombi3d")),								// Multi-series 3D Single Y Combination Chart (Column + Line + Area)
		array(4111, array("mscolumnline3d")),							// Multi-series Column 3D + Line - Single Y Axis
		array(4021, array("stackedcolumn2dline")),						// Stacked Column2D + Line single Y Axis
		array(4121, array("stackedcolumn3dline")),						// Stacked Column3D + Line single Y Axis
		array(4031, array("mscombidy2d", "scrollcombidy2d")),			// Multi-series 2D Dual Y Combination Chart (Column + Line + Area) (**18)
		array(4131, array("mscolumn3dlinedy")),							// Multi-series Column 3D + Line - Dual Y Axis (**19)
		array(4141, array("stackedcolumn3dlinedy")),					// Stacked Column 3D + Line Dual Y Axis

		// Reserved, NOT supported
		// http://www.fusioncharts.com/dev/chart-attributes.html?chart=msstackedcolumn2dlinedy
		//array(4051, array("msstackedcolumn2dlinedy")),				// Multi-series Stacked Column 2D + Line Dual Y Axis

		array(4092, array("zoomlinedy")),								// Multi-series Zoom-line Dual Y-Axis (CompatDataMode = TRUE)

		// XY Plot Charts (NOT supported)
		// Scroll Charts
		// - Scroll Column 2D: scrollcolumn2d - 1001/2001
		// - Scroll Line 2D: scrollline2d - 1002/2002
		// - Scroll Area 2D: scrollarea2d - 1003/2003
		// - Scroll Stacked Column 2D: scrollstackedcolumn2d - 3001
		// - Scroll Combination 2D (Single Y): scrollcombi2d - 4001
		// - Scroll Combination 2D (Dual Y): scrollcombidy2d - 4031
		// Financial

		array(5099, array("candlestick")),								// Candlestick (**20)

		// Other
		array(6098, array("gantt"))										// Gantt (**21)
	);

	// Show chart (FusionCharts)
	function ShowFusionChart($width, $height) {
		global $ReportLanguage, $Page;
		$chartxml = $this->ChartXml();
		$scroll = $this->ScrollChart;
		$drilldown = $this->DrillDownInPanel;
		$typ = $this->ChartType; // Chart type (nnnn)
		$id = $this->ID; // Chart ID
		$parms = $this->Parms; // "bgcolor=FFFFFF|..."
		$trends = $this->Trends; // Trend lines
		$data = $this->Data;
		$series = $this->Series;
		$align = $this->ChartAlign;
		if (empty($typ))
			$typ = 1001;
		$showgrid = $this->UseGridComponent;
		if (!$this->IsSingleSeriesChart($typ))
			$showgrid = FALSE;

		// Get chart type
		$charttype = "column2d"; // Default = Column 2D
		foreach ($this->FCCharts as $chart) {
			if ($typ == $chart[0]) {
				$charttype = $scroll ? (count($chart[1]) >= 2 ? $chart[1][1] : $chart[1][0]) : $chart[1][0];
				break;
			}
		}

		// Output JavaScript for FusionCharts
		$chartid = "chart_$id";
		if ($drilldown) $chartid .= "_" . ewr_Random();
		$wrk = "<script type='text/javascript'>\r\n";
		$wrk .= "var chartxml = \"" . ewr_EscapeJs(ewr_ConvertFromUtf8($chartxml)) . "\";\r\n";
		$wrk .= "var chartjson = FusionCharts.transcodeData(chartxml, 'xml', 'json');\r\n";
		$wrk .= "var chartoptions = { 'id': 'chart_$id', 'renderAt': 'div_$id', 'width': $width, 'height': $height, 'id': '$chartid', 'type': '$charttype', 'dataFormat': 'json', 'dataSource': chartjson };\r\n";
		$wrk .= "jQuery(document).trigger('draw', [chartoptions]);\r\n";
		$wrk .= "var cht_$id = new FusionCharts(chartoptions);\r\n";
		$wrk .= "cht_$id.render();\r\n";
		$wrk .= ($drilldown) ? "ewrDrillCharts[ewrDrillCharts.length] = cht_$id.id;\r\n" :
			"ewrExportCharts[ewrExportCharts.length] = cht_$id.id;\r\n"; // Export chart
		if ($this->ChartDrillDownUrl <> "")
			$wrk .= "ewrDrillDownCharts[ewrDrillDownCharts.length] = cht_$id.id;\r\n"; // Chart is drill down

		// Grid component
		if ($showgrid && $chartxml <> "") {

			// Remove clickurl/caption first
			$doc = new DOMDocument();
			$doc->loadXML($chartxml);
			$doc->documentElement->setAttribute("clickurl", "");
			$doc->documentElement->setAttribute("caption", "");
			$chartgridxml = $doc->saveXML();
			$gridid = $id . "_grid";
			$chartid = "chart_$gridid";
			if ($drilldown) $chartid .= "_" . ewr_Random();
			$wrkgridheight = $this->ChartGridHeight;
			$wrk .= "chartxml = \"" . ewr_EscapeJs(ewr_ConvertFromUtf8($chartgridxml)) . "\";\r\n";
			$wrk .= "var chartjson = FusionCharts.transcodeData(chartxml, 'xml', 'json');\r\n";
			$wrk .= "chartoptions = { 'id': 'chart_$gridid', 'renderAt': 'div_$gridid', 'width': $width, 'height': $wrkgridheight, 'id': '$chartid', 'type': 'ssgrid', 'dataFormat': 'json', 'dataSource': chartjson };\r\n";
			$wrk .= "jQuery(document).trigger('draw', [chartoptions]);\r\n";
			$wrk .= "var cht_$gridid = new FusionCharts(chartoptions);\r\n";
			$wrk .= "cht_$gridid.render();\r\n";
			$wrk .= ($drilldown) ? "ewrDrillCharts[ewrDrillCharts.length] = cht_$gridid.id;\r\n" :
				"ewrExportCharts[ewrExportCharts.length] = cht_$gridid.id;\r\n"; // Export chart

			// Set Grid specific parameters
			if ($this->ChartGridConfig)
				$wrk .= "cht_$gridid.configure(" . $this->ChartGridConfig . ");\r\n";
		}

		// Debug mode
		if (EWR_DEBUG_ENABLED) {
			$wrk .= "FusionCharts['debugger'].enable(true, function(message) { console.log(message); });\r\n";
			$wrk .= "console.log(chartoptions);\r\n";
		}
		$wrk .= "</script>\r\n";

		// Show XML for debug
		if (EWR_DEBUG_ENABLED && $chartxml <> "") {
			$doc = new DOMDocument();
			$doc->loadXML($chartxml);
			$doc->formatOutput = TRUE;
			ewr_SetDebugMsg("(Chart XML):<pre>" . ewr_HtmlEncode(ewr_ConvertFromUtf8($doc->saveXML())) . "</pre>");
		}
		return $wrk;
	}

	// Show chart temp image
	function ShowTempImage() {
		global $gsExport;
		$chartid = "chart_" . $this->ID;
		$tmpChartImage = ewr_TmpChartImage($chartid, $this->IsCustomTemplate);
		$tmpGridImage = ewr_TmpChartImage($chartid . "_grid", $this->IsCustomTemplate);
		if ($this->PageBreak)
			$pageBreakTag = " data-page-break=\"" . ($this->PageBreakType == "before" ? "before" : "after") . "\"";
		else
			$pageBreakTag = "";
		$wrk = "";
		if ($tmpChartImage <> "") {
			$wrk = "<img src=\"" . $tmpChartImage . "\" alt=\"\">";
			if ($tmpGridImage <> "")
				$wrk .= "<img src=\"" . $tmpGridImage . "\" alt=\"\">";
			if ($gsExport == "word" && defined('EWR_USE_PHPWORD') || $gsExport == "excel" && defined('EWR_USE_PHPEXCEL'))
				$wrk = "<table class=\"ewChart\"" . $pageBreakTag . "><tr><td>" . $wrk . "</td></tr></table>";
			else
				$wrk = "<div class=\"ewChart\"" . $pageBreakTag . ">" . $wrk . "</div>";
		}
		return $wrk;
	}

	// Check width and height
	function CheckSize(&$width, &$height) {
		if ($width <= 0)
			$width = $this->ChartWidth;
		if ($height <= 0)
			$height = $this->ChartHeight;
		if (!is_numeric($width) || $width <= 0)
			$width = $this->IsGoogleChart() ? "" : EWR_CHART_WIDTH; // Use default google chart width
		if (!is_numeric($height) || $height <= 0)
			$height = EWR_CHART_HEIGHT;
	}

	// Render chart
	function Render($width = -1, $height = -1) {
		global $gsExport, $gsCustomExport, $ReportLanguage, $Page, $EWR_NON_FUSIONCHARTS;

		// Check chart size
		$this->CheckSize($width, $height);
		$wrkwidth = $width;
		$wrkheight = $height;

		// Set up chart first
		$this->SetupChart();

		// Render page break content (before)
		if ($this->PageBreak && $this->PageBreakType == "before")
			echo $this->PageBreakContent;

		// Render chart content
		if ($gsExport == "" || $gsExport == "print" && $gsCustomExport == "" || $gsExport == "email" && @$_POST["contenttype"] == "url") {

			// Output chart html first
			$isDashBoard = CurrentPageID() == "dashboard";
			$chartDivName = $this->Table->TableVar . '_' . $this->ChartVar;
			$chartAnchor = 'cht_' . $chartDivName;
			if (EWR_LOWERCASE_OUTPUT_FILE_NAME)
				$chartAnchor = strtolower($chartAnchor);
			$html = '<a id="' . $chartAnchor . '"></a>' .
				'<div id="div_ctl_' . $chartDivName . '" class="ewChart">';

			// Not dashboard / run time sort / Not export / Not drilldown
			$isDrillDown = isset($Page) ? $Page->DrillDown : FALSE;
			if (!$isDashBoard && $this->ChartRunTimeSort && $gsExport == "" && !$isDrillDown) {
				$html .= '<div class="ewChartSort">' .
					'<form class="ewForm form-horizontal" action="' . ewr_CurrentPage() . '#cht_' . $chartDivName . '">' .
					$ReportLanguage->Phrase("ChartOrder") . '&nbsp;' .
					'<select id="chartordertype" name="chartordertype" class="form-control" onchange="this.form.submit();">' .
					'<option value="1"' . ($this->ChartSortType == '1' ? ' selected' : '') . '>' . $ReportLanguage->Phrase("ChartOrderXAsc") . '</option>' .
					'<option value="2"' . ($this->ChartSortType == '2' ? ' selected' : '') . '>' . $ReportLanguage->Phrase("ChartOrderXDesc") . '</option>' .
					'<option value="3"' . ($this->ChartSortType == "3" ? ' selected' : '') . '>' . $ReportLanguage->Phrase("ChartOrderYAsc") . '</option>' .
					'<option value="4"' . ($this->ChartSortType == "4" ? ' selected' : '') . '>' . $ReportLanguage->Phrase("ChartOrderYDesc") . '</option>' .
					'</select>' .
					'<input type="hidden" id="chartorder" name="chartorder" value="' . $this->ChartVar . '">' .
					'</form>' .
					'</div>';
			}
			$html .= '<div id="div_' . $chartDivName . '" class="ewChartDiv"></div>';
			if ($this->UseGridComponent)
				$html .= '<!-- grid component --><div id="div_' . $chartDivName . '_grid" class="ewChartGrid"></div>';
			$html .= '</div>';
			echo $html;

			// Output JavaScript
			if (!$this->IsGoogleChart() || $EWR_NON_FUSIONCHARTS)
				echo $this->ShowFusionChart($wrkwidth, $wrkheight);
			else
				echo $this->ShowGoogleChart($wrkwidth, $wrkheight);
		} elseif ($gsExport == "pdf" || $gsCustomExport <> "" || $gsExport == "email" || $gsExport == "excel" && defined("EWR_USE_PHPEXCEL") || $gsExport == "word" && defined("EWR_USE_PHPWORD")) { // Show temp image
			echo $this->ShowTempImage();
		}

		// Render page break content (after)
		if ($this->PageBreak && $this->PageBreakType == "after")
			echo $this->PageBreakContent;
	}

	// Chart Rendering event
	function Chart_Rendering() {

		// Example:
		// var_dump($this); // Chart
		// if ($this->ID == "<Report>_<Chart>") {
		//	$this->SaveParm("formatNumber", "1"); // Format number
		//	$this->SaveParm("numberSuffix", "%"); // % as suffix
		// }

	}

	// Chart Data Rendered event
	function Chart_DataRendered(&$node) {

		// Example:
		// var_dump($this); // Chart
		// if ($this->ID == "<Report>_<Chart>") {
		//	if ($node->nodeName == "set") { // Multiply values by 100
		//		$val = $node->getAttribute("value");
		//		$val = $val * 100;
		//		$node->setAttribute("value", $val);
		//	}
		// }

	}

	// Chart Rendered event
	function Chart_Rendered(&$chartxml) {

		// Example:
		// var_dump($this); // Chart
		// if ($this->ID == "<Report>_<Chart>") {
		//	$doc = $this->XmlDoc; // Get the DOMDocument object
		//	//Enter your code to manipulate the DOMDocument object here
		//	$chartxml = $doc->saveXML(); // Output the XML
		// }

	}
}

//
// Column class
//
class crCrosstabColumn {
	var $Caption;
	var $Value;
	var $Visible;

	function __construct($value, $caption, $visible = TRUE) {
		$this->Caption = $caption;
		$this->Value = $value;
		$this->Visible = $visible;
	}
}

//
// Advanced filter class
//
class crAdvancedFilter {
	var $ID;
	var $Name;
	var $FunctionName;
	var $Enabled = TRUE;

	function __construct($filterid, $filtername, $filterfunc) {
		$this->ID = $filterid;
		$this->Name = $filtername;
		$this->FunctionName = $filterfunc;
	}
}
/**
 * Menu class
 */

class crMenu {
	var $Id;
	var $IsRoot;
	var $FollowLink = TRUE; // For sidebar menu
	var $Accordion = TRUE; // For sidebar menu
	var $UseSubmenuForRootHeader;
	var $Items = array();
	var $_nullItem = NULL;

	// Constructor
	function __construct($id, $isRoot = FALSE) {
		global $EWR_USE_SUBMENU_FOR_ROOT_HEADER;
		$this->Id = $id;
		$this->IsRoot = $isRoot;
		$this->UseSubmenuForRootHeader = $EWR_USE_SUBMENU_FOR_ROOT_HEADER;
	}

	// Add a menu item ($src for backward compatibility only)
	function AddMenuItem($id, $name, $text, $url, $parentId = -1, $src = "", $allowed = TRUE, $isHeader = FALSE, $isCustomUrl = FALSE, $icon = "", $label = "") {

		// For backward compatibility only (without $name)
		if (is_int($url) && is_bool($src)) {
			list($text, $url, $parentId, $src, $allowed, $isHeader, $isCustomUrl) = array($name, $text, $url, $parentId, $src, $allowed, $isHeader);
			$name = "mi_" . $id;
		}
		$item = new crMenuItem($id, $name, $text, $url, $parentId, $allowed, $isHeader, $isCustomUrl, $icon, $label);

		// Fire MenuItem_Adding event
		if (function_exists("MenuItem_Adding") && !MenuItem_Adding($item))
			return;
		if ($item->parentId < 0) {
			$this->AddItem($item);
		} else {
			if ($parentMenu = &$this->FindItem($item->parentId))
				$parentMenu->AddItem($item);
		}
	}

	// Add item to internal array
	function AddItem($item) {
		$this->Items[] = $item;
	}

	// Clear all menu items
	function Clear() {
		$this->Items = array();
	}

	// Find item
	function &FindItem($_id) {
		$cnt = count($this->Items);
		for ($i = 0; $i < $cnt; $i++) {
			$item = &$this->Items[$i];
			if ($item->id == $_id) {
				return $item;
			} elseif (!is_null($item->submenu)) {
				if ($subitem = &$item->submenu->FindItem($_id))
					return $subitem;
			}
		}
		$nullItem = $this->_nullItem;
		return $nullItem;
	}

	// Find item by menu text
	function &FindItemByText($txt) {
		$cnt = count($this->Items);
		for ($i = 0; $i < $cnt; $i++) {
			$item = &$this->Items[$i];
			if ($item->text == $txt) {
				return $item;
			} elseif (!is_null($item->submenu)) {
				if ($subitem = &$item->submenu->FindItemByText($txt))
					return $subitem;
			}
		}
		$nullItem = $this->_nullItem;
		return $nullItem;
	}

	// Get menu item count
	function Count() {
		return count($this->Items);
	}

	// Move item to position
	function MoveItem($text, $pos) {
		$cnt = count($this->Items);
		if ($pos < 0) {
			$pos = 0;
		} elseif ($pos >= $cnt) {
			$pos = $cnt - 1;
		}
		$item = NULL;
		$cnt = count($this->Items);
		for ($i = 0; $i < $cnt; $i++) {
			if ($this->Items[$i]->Text == $text) {
				$item = $this->Items[$i];
				break;
			}
		}
		if ($item) {
			unset($this->Items[$i]);
			$this->Items = array_merge(array_slice($this->Items, 0, $pos),
				array($item), array_slice($this->Items, $pos));
		}
	}

	// Check if a menu item should be shown
	function RenderItem($item) {
		if (!is_null($item->submenu)) {
			foreach ($item->submenu->Items as $subitem) {
				if ($item->submenu->RenderItem($subitem))
					return TRUE;
			}
		}
		return ($item->allowed && $item->url <> "");
	}

	// Check if a menu item should be opened
	function IsItemOpened($item) {
		if (!is_null($item->submenu)) {
			foreach ($item->submenu->Items as $subitem) {
				if ($item->submenu->IsItemOpened($subitem))
					return TRUE;
			}
		}
		return $item->active;
	}

	// Check if this menu should be rendered
	function RenderMenu() {
		foreach ($this->Items as $item) {
			if ($this->RenderItem($item))
				return TRUE;
		}
		return FALSE;
	}

	// Check if this menu should be opened
	function IsOpened() {
		foreach ($this->Items as $item) {
			if ($this->IsItemOpened($item))
				return TRUE;
		}
		return FALSE;
	}

	// Render the menu as array of object
	function Render() {
		if ($this->IsRoot && function_exists("Menu_Rendering"))
			Menu_Rendering($this);
		if (!$this->RenderMenu())
			return;
		$menu = array();
		$url = ewr_CurrentUrl();
		$url = substr($url, strrpos($url, "/") + 1);
		foreach ($this->Items as $item) {
			if ($this->RenderItem($item)) {
				if ($item->isHeader && (!$this->IsRoot || !$this->UseSubmenuForRootHeader)) { // Group title (Header)
					$menu[] = $item->Render(FALSE);
					if (!is_null($item->submenu)) {
						foreach ($item->submenu->Items as $subitem) {
							if ($this->RenderItem($subitem)) {
								if (!$subitem->isCustomUrl && ewr_CurrentPage() == ewr_GetPageName($subitem->url) || $subitem->isCustomUrl && $url == $subitem->url) {
									$subitem->active = TRUE;
									$subitem->url = "#";
								}
								$menu[] = $subitem->Render();
							}
						}
					}
				} else {
					if (!$item->isCustomUrl && ewr_CurrentPage() == ewr_GetPageName($item->url) || $item->isCustomUrl && $url == $item->url) {
						$item->active = TRUE;
						$item->url = "#";
					}
					$menu[] = $item->Render();
				}
			}
		}
		if ($this->IsRoot && function_exists("Menu_Rendered"))
			Menu_Rendered($this);
		return count($menu) ? $menu : NULL;
	}

	// Returns the menu as JSON
	function ToJson() {
		return json_encode(array("items" => $this->Render(), "followLink" => $this->FollowLink, "accordion" => $this->Accordion));
	}

	// Returns the menu as script tag
	function ToScript() {
		return '<script type="text/javascript">ewrVar.menu = ' . $this->ToJson() . ';</script>';
	}
}

// Menu item class
class crMenuItem {
	var $id = "";
	var $name = "";
	var $text = "";
	var $url = "";
	var $parentId = -1;
	var $submenu = NULL; // Data type = crMenu
	var $allowed = TRUE;
	var $target = "";
	var $isHeader = FALSE;
	var $isCustomUrl = FALSE;
	var $href = ""; // href attribute
	var $active = FALSE;
	var $icon = "";
	var $attrs = "";
	var $label = ""; // HTML to be placed in pull-right-container

	// Constructor
	function __construct($_id, $_name, $_text, $_url, $_parentId = -1, $_allowed = TRUE, $_isHeader = FALSE, $_isCustomUrl = FALSE, $_icon = "", $_label = "") {
		$this->id = $_id;
		$this->name = $_name;
		$this->text = $_text;
		$this->url = $_url;
		$this->parentId = $_parentId;
		$this->allowed = $_allowed;
		$this->isHeader = $_isHeader;
		$this->isCustomUrl = $_isCustomUrl;
		$this->icon = $_icon;
		$this->label = $_label;
	}

	// Set property case-insensitively (for backward compatibilty) // PHP
	function __set($name, $value) {
		$vars = get_class_vars(get_class($this));
		foreach ($vars as $key => $val) {
			if (ewr_SameText($name, $key)) {
				$this->$key = $value;
				break;
			}
		}
	}

	// Get property case-insensitively (for backward compatibilty) // PHP
	function __get($name) {
		$vars = get_class_vars(get_class($this));
		foreach ($vars as $key => $val) {
			if (ewr_SameText($name, $key)) {
				return $this->$key;
				break;
			}
		}
		return NULL;
	}

	// Add submenu item
	function AddItem($item) {
		if (is_null($this->submenu))
			$this->submenu = new crMenu($this->id);
		$this->submenu->AddItem($item);
	}

	// Render
	function Render($deep = TRUE) {
		$url = ewr_GetUrl($this->url);
		if (ewr_IsMobile() && !$this->isCustomUrl)
			$url = str_replace("#", (ewr_ContainsStr($url, "?") ? "&" : "?") . "hash=", $url);
		if ($url == "")
			$url = "#";
		$this->href = $url;
		$this->attrs = trim($this->attrs);
		if ($this->attrs)
			$this->attrs = " " . $this->attrs;
		$class = trim($this->icon);
		if ($class) {
			$ar = explode(" ", $class);
			foreach ($ar as $name) {
				if (ewr_StartsStr("fa-", $name) && !in_array("fa", $ar))
					$ar[] = "fa";
				elseif (ewr_StartsStr("glyphicon-", $name) && !in_array("glyphicon", $ar))
					$ar[] = "glyphicon";
			}
			$this->icon = implode(" ", $ar);
		}
		$ar = (array)$this;
		unset($ar["url"], $ar["submenu"], $ar["allowed"], $ar["isCustomUrl"]);
		$ar["items"] = ($deep && !is_null($this->submenu)) ? $this->submenu->Render() : NULL;
		$ar["open"] = ($deep && !is_null($this->submenu)) ? $this->submenu->IsOpened() : FALSE;
		return $ar;
	}
}

// MenuItem Adding event
function MenuItem_Adding(&$Item) {

	//var_dump($Item);
	// Return FALSE if menu item not allowed

	return TRUE;
}

// Menu Rendering event
function Menu_Rendering(&$Menu) {

	// Change menu items here
}

// Menu Rendered event
function Menu_Rendered(&$Menu) {

	// Clean up here
}
/**
 * List option collection class
 */

class crListOptions {
	var $Items = array();
	var $CustomItem = "";
	var $Tag = "td";
	var $TagClassName = "";
	var $TableVar = "";
	var $RowCnt = "";
	var $ScriptType = "block";
	var $ScriptId = "";
	var $ScriptClassName = "";
	var $JavaScript = "";
	var $RowSpan = 1;
	var $UseDropDownButton = FALSE;
	var $UseButtonGroup = FALSE;
	var $ButtonClass = "";
	var $GroupOptionName = "button";
	var $DropDownButtonPhrase = "";
	var $UseImageAndText = FALSE;

	// Check visible
	function Visible() {
		foreach ($this->Items as $item) {
			if ($item->Visible)
				return TRUE;
		}
		return FALSE;
	}

	// Check group option visible
	function GroupOptionVisible() {
		$cnt = 0;
		foreach ($this->Items as $item) {
			if ($item->Name <> $this->GroupOptionName && 
				(($item->Visible && $item->ShowInDropDown && $this->UseDropDownButton) ||
				($item->Visible && $item->ShowInButtonGroup && $this->UseButtonGroup))) {
				$cnt += 1;
				if ($this->UseDropDownButton && $cnt > 1)
					return TRUE;
				elseif ($this->UseButtonGroup)
					return TRUE;
			}
		}
		return FALSE;
	}

	// Add and return a new option
	function &Add($Name) {
		$item = new crListOption($Name);
		$item->Parent = &$this;
		$this->Items[$Name] = $item;
		return $item;
	}

	// Load default settings
	function LoadDefault() {
		$this->CustomItem = "";
		foreach ($this->Items as $key => $item)
			$this->Items[$key]->Body = "";
	}

	// Hide all options
	function HideAllOptions($Lists=array()) {
		foreach ($this->Items as $key => $item)
			if (!in_array($key, $Lists))
				$this->Items[$key]->Visible = FALSE;
	}

	// Show all options
	function ShowAllOptions() {
		foreach ($this->Items as $key => $item)
			$this->Items[$key]->Visible = TRUE;
	}

	// Get item by name
	// Predefined names: view/edit/copy/delete/detail_<DetailTable>/userpermission/checkbox
	function &GetItem($Name) {
		$item = array_key_exists($Name, $this->Items) ? $this->Items[$Name] : NULL;
		return $item;
	}

	// Get item position
	function ItemPos($Name) {
		$pos = 0;
		foreach ($this->Items as $item) {
			if ($item->Name == $Name)
				return $pos;
			$pos++;
		}
		return FALSE;
	}

	// Move item to position
	function MoveItem($Name, $Pos) {
		$cnt = count($this->Items);
		if ($Pos < 0) // If negative, count from the end
			$Pos = $cnt + $Pos;
		if ($Pos < 0)
			$Pos = 0;
		if ($Pos >= $cnt)
			$Pos = $cnt - 1;
		$item = $this->GetItem($Name);
		if ($item) {
			unset($this->Items[$Name]);
			$this->Items = array_merge(array_slice($this->Items, 0, $Pos),
				array($Name => $item), array_slice($this->Items, $Pos));
		}
	}

	// Render list options
	function Render($Part, $Pos="", $RowCnt="", $ScriptType="block", $ScriptId="", $ScriptClassName="") {
		if ($this->CustomItem == "" && $groupitem = &$this->GetItem($this->GroupOptionName) && $this->ShowPos($groupitem->OnLeft, $Pos)) {
			if ($this->UseDropDownButton) { // Render dropdown
				$buttonvalue = "";
				$cnt = 0;
				foreach ($this->Items as $item) {
					if ($item->Name <> $this->GroupOptionName && $item->Visible && $item->ShowInDropDown) {
						$buttonvalue .= $item->Body;
						$cnt += 1;
					}
				}
				if ($cnt <= 1) {
					$this->UseDropDownButton = FALSE; // No need to use drop down button
				} else {
					$groupitem->Body = $this->RenderDropDownButton($buttonvalue, $Pos);
					$groupitem->Visible = TRUE;
				}
			}
			if (!$this->UseDropDownButton && $this->UseButtonGroup) { // Render button group
				$visible = FALSE;
				$buttongroups = array();
				foreach ($this->Items as $item) {
					if ($item->Name <> $this->GroupOptionName && $item->Visible && $item->ShowInButtonGroup && $item->Body <> "") {
						$visible = TRUE;
						$buttonvalue = ($this->UseImageAndText) ? $item->GetImageAndText($item->Body) : $item->Body;
						if (!array_key_exists($item->ButtonGroupName, $buttongroups)) $buttongroups[$item->ButtonGroupName] = "";
						$buttongroups[$item->ButtonGroupName] .= $buttonvalue;
					}
				}
				$groupitem->Body = "";
				foreach ($buttongroups as $buttongroup => $buttonvalue)
					$groupitem->Body .= $this->RenderButtonGroup($buttonvalue);
				if ($visible)
				$groupitem->Visible = TRUE;
			}
		}
		$this->RenderEx($Part, $Pos, $RowCnt, $ScriptType, $ScriptId, $ScriptClassName);
	}

	function RenderEx($Part, $Pos="", $RowCnt="", $ScriptType="block", $ScriptId="", $ScriptClassName="") {
		$this->RowCnt = $RowCnt;
		$this->ScriptType = $ScriptType;
		$this->ScriptId = $ScriptId;
		$this->ScriptClassName = $ScriptClassName;
		$this->JavaScript = "";

		//$this->Tag = ($Pos <> "" && $Pos <> "bottom") ? "td" : "span";
		$this->Tag = ($Pos <> "" && $Pos <> "bottom") ? "td" : "div";
		if ($this->CustomItem <> "") {
			$cnt = 0;
			$opt = NULL;
			foreach ($this->Items as &$item) {
				if ($this->ShowItem($item, $ScriptId, $Pos))
					$cnt++;
				if ($item->Name == $this->CustomItem)
					$opt = &$item;
			}
			$bUseButtonGroup = $this->UseButtonGroup; // Backup options
			$bUseImageAndText = $this->UseImageAndText;
			$this->UseButtonGroup = TRUE; // Show button group for custom item
			$this->UseImageAndText = TRUE; // Use image and text for custom item
			if (is_object($opt) && $cnt > 0) {
				if ($ScriptId <> "" || $this->ShowPos($opt->OnLeft, $Pos)) {
					echo $opt->Render($Part, $cnt);
				} else {
					echo $opt->Render("", $cnt);
				}
			}
			$this->UseButtonGroup = $bUseButtonGroup; // Restore options
			$this->UseImageAndText = $bUseImageAndText;
		} else {
			foreach ($this->Items as &$item) {
				if ($this->ShowItem($item, $ScriptId, $Pos))
					echo $item->Render($Part, 1);
			}
		}
	}

	function ShowItem($item, $ScriptId, $Pos) {
		$show = $item->Visible && ($ScriptId <> "" || $this->ShowPos($item->OnLeft, $Pos));
		if ($show)
			if ($this->UseDropDownButton)
				$show = ($item->Name == $this->GroupOptionName || !$item->ShowInDropDown);
			elseif ($this->UseButtonGroup)
				$show = ($item->Name == $this->GroupOptionName || !$item->ShowInButtonGroup);
		return $show;
	}

	function ShowPos($OnLeft, $Pos) {
		return ($OnLeft && $Pos == "left") || (!$OnLeft && $Pos == "right") || ($Pos == "") || ($Pos == "bottom");
	}

	// Concat options and return concatenated HTML
	// - pattern - regular expression pattern for matching the option names, e.g. '/^detail_/'
	function Concat($pattern, $separator = "") {
		$ar = array();
		$keys = array_keys($this->Items);
		foreach ($keys as $key) {
			if (preg_match($pattern, $key) && trim($this->Items[$key]->Body) <> "")
				$ar[] = $this->Items[$key]->Body;
		}
		return implode($separator, $ar);
	}

	// Merge options to the first option and return it
	// - pattern - regular expression pattern for matching the option names, e.g. '/^detail_/'
	function &Merge($pattern, $separator = "") {
		$keys = array_keys($this->Items);
		$first = NULL;
		foreach ($keys as $key) {
			if (preg_match($pattern, $key)) {
				if (!$first) {
					$first = $this->Items[$key];
					$first->Body = $this->Concat($pattern, $separator);
				} else {
					$this->Items[$key]->Visible = FALSE;
				}
			}
		}
		return $first;
	}

	// Get button group link
	function RenderButtonGroup($body) {

		// Get all hidden inputs
		// format: <input type="hidden" ...>
//		$inputs = array();
//		if (preg_match_all('/<input\s+([^>]*)>/i', $body, $inputmatches, PREG_SET_ORDER)) {
//			foreach ($inputmatches as $inputmatch) {
//				$body = str_replace($inputmatch[0], '', $body); 
//				if (preg_match('/\s+type\s*=\s*[\'"]hidden[\'"]/i', $inputmatch[0])) // Match type='hidden'
//					$inputs[] = $inputmatch[0];
//			}
//		}
		// Get all buttons
		// format: <div class="btn-group">...</div>

		$btns = array();
		if (preg_match_all('/<div\s+class\s*=\s*[\'"]btn-group[\'"]([^>]*)>([\s\S]*?)<\/div\s*>/i', $body, $btnmatches, PREG_SET_ORDER)) {
			foreach ($btnmatches as $btnmatch) {
				$body = str_replace($btnmatch[0], '', $body); 
				$btns[] = $btnmatch[0];
			}
		}
		$links = '';

		// Get all links/buttons
		// format: <a ...>...</a> / <button ...>...</button>

		if (preg_match_all('/<(a|button)([^>]*)>([\s\S]*?)<\/(a|button)\s*>/i', $body, $matches, PREG_SET_ORDER)) {
			foreach ($matches as $match) {
				$tag = $match[1];
				if (preg_match('/\s+class\s*=\s*[\'"]([\s\S]*?)[\'"]/i', $match[2], $submatches)) { // Match class='class'
					$class = $submatches[1];
					$attrs = str_replace($submatches[0], '', $match[2]);
				} else {
					$class = '';
					$attrs = $match[2];
				}
				$caption = $match[3];
				if (strpos($class, 'btn btn-default') === FALSE) // Prepend button classes
					ewr_PrependClass($class, 'btn btn-default');
				if ($this->ButtonClass <> "")
					ewr_AppendClass($class, $this->ButtonClass);
				$attrs = ' class="' . $class . '" ' . $attrs;
 				$link ='<' . $tag . $attrs . '>' . $caption . '</' . $tag . '>';
				$links .= $link;
			}
		}
		if ($links <> "")
			$btngroup = '<div class="btn-group ewButtonGroup">' . $links . '</div>';
		else
			$btngroup = "";
		foreach ($btns as $btn)
			$btngroup .= $btn;

		//foreach ($inputs as $input)
		//	$btngroup .= $input;

		return $btngroup;
	}

	// Render drop down button
	function RenderDropDownButton($body, $pos) {

		// Get all hidden inputs
		// format: <input type="hidden" ...>
//		$inputs = array();
//		if (preg_match_all('/<input\s+([^>]*)>/i', $body, $inputmatches, PREG_SET_ORDER)) {
//			foreach ($inputmatches as $inputmatch) {
//				$body = str_replace($inputmatch[0], '', $body); 
//				if (preg_match('/\s+type\s*=\s*[\'"]hidden[\'"]/i', $inputmatch[0])) // Match type='hidden'
//					$inputs[] = $inputmatch[0];
//			}
//		}
		// Remove toggle button first <button ... data-toggle="dropdown">...</button>

		if (preg_match_all('/<button\s+([\s\S]*?)data-toggle\s*=\s*[\'"]dropdown[\'"]\s*>([\s\S]*?)<\/button\s*>/i', $body, $btnmatches, PREG_SET_ORDER)) {
			foreach ($btnmatches as $btnmatch)
				$body = str_replace($btnmatch[0], '', $body);
		}

		// Get all links/buttons <a ...>...</a> / <button ...>...</button>
		if (!preg_match_all('/<(a|button)([^>]*)>([\s\S]*?)<\/(a|button)\s*>/i', $body, $matches, PREG_SET_ORDER))
			return '';
		$links = '';
		$submenu = FALSE;
		$submenulink = "";
		$submenulinks = "";
		foreach ($matches as $match) {
			$tag = $match[1];
			if (preg_match('/\s+data-action\s*=\s*[\'"]([\s\S]*?)[\'"]/i', $match[2], $actionmatches)) { // Match data-action='action'
				$action = $actionmatches[1];
			} else {
				$action = '';
			}
			if (preg_match('/\s+class\s*=\s*[\'"]([\s\S]*?)[\'"]/i', $match[2], $submatches)) { // Match class='class'
				$class = preg_replace('/btn[\S]*\s+/i', '', $submatches[1]);
				$attrs = str_replace($submatches[0], '', $match[2]);
			} else {
				$class = '';
				$attrs = $match[2];
			}
			$attrs = preg_replace('/\s+title\s*=\s*[\'"]([\s\S]*?)[\'"]/i', '', $attrs); // Remove title='title'
			if (preg_match('/\s+data-caption\s*=\s*[\'"]([\s\S]*?)[\'"]/i', $attrs, $submatches)) // Match data-caption='caption'
				$caption = $submatches[1];
			else
				$caption = '';
			$attrs = ' class="' . $class . '" ' . $attrs;
			if (strtolower($tag) == "button") // Add href for button
				$attrs .= ' href="javascript:void(0);"';
			if ($this->UseImageAndText) { // Image and text
				if (preg_match('/<img([^>]*)>/i', $match[3], $submatch)) // <img> tag
					$caption = $submatch[0] . '&nbsp;&nbsp;' . $caption;
				elseif (preg_match('/<span([^>]*)>([\s\S]*?)<\/span\s*>/i', $match[3], $submatch)) // <span class='class'></span> tag
					if (preg_match('/\s+class\s*=\s*[\'"]([\s\S]*?)[\'"]/i', $submatch[1], $submatches)) // Match class='class'
						$caption = $submatch[0] . '&nbsp;&nbsp;' . $caption;
			}
			if ($caption == '')
				$caption = $match[3];
			$link = '<a' . $attrs . '>' . $caption . '</a>';
			if ($action == 'list') { // Start new submenu
				if ($submenu) { // End previous submenu
					if ($submenulinks <> '') { // Set up submenu
						$links .= '<li class="dropdown-submenu">' . $submenulink . '<ul class="dropdown-menu">' . $submenulinks . '</ul></li>';
					} else {
						$links .= '<li>' . $submenulink . '</li>';
					}
				}
				$submenu = TRUE;
				$submenulink = $link;
				$submenulinks = "";
			} else {
				if ($action == '' && $submenu) { // End previous submenu
					if ($submenulinks <> '') { // Set up submenu
						$links .= '<li class="dropdown-submenu">' . $submenulink . '<ul class="dropdown-menu">' . $submenulinks . '</ul></li>';
					} else {
						$links .= '<li>' . $submenulink . '</li>';
					}
					$submenu = FALSE;
				}
				if ($submenu)
					$submenulinks .= '<li>' . $link . '</li>';
				else
					$links .= '<li>' . $link . '</li>';
			}
		}
		if ($links <> "") {
			if ($submenu) { // End previous submenu
				if ($submenulinks <> '') { // Set up submenu
					$links .= '<li class="dropdown-submenu">' . $submenulink . '<ul class="dropdown-menu">' . $submenulinks . '</ul></li>';
				} else {
					$links .= '<li>' . $submenulink . '</li>';
				}
			}
			$buttonclass = "dropdown-toggle btn btn-default";
			if ($this->ButtonClass <> "")
				ewr_AppendClass($buttonclass, $this->ButtonClass);
			$buttontitle = ewr_HtmlTitle($this->DropDownButtonPhrase);
			$buttontitle = ($this->DropDownButtonPhrase <> $buttontitle) ? ' title="' . $buttontitle . '"' : '';
			$button = '<button class="' . $buttonclass . '"' . $buttontitle . ' data-toggle="dropdown">' . $this->DropDownButtonPhrase . '<span class="caret"></span></button><ul class="dropdown-menu ewMenu">' . $links . '</ul>';
			if ($pos == "bottom") // Use dropup
				$btndropdown = '<div class="btn-group dropup ewButtonDropdown">' . $button . '</div>';
			else
				$btndropdown = '<div class="btn-group ewButtonDropdown">' . $button . '</div>';
		} else {
			$btndropdown = "";
		}

		//foreach ($inputs as $input)
			//$btndropdown .= $input;

		return $btndropdown;
	}
}
/**
 * List option class
 */

class crListOption {
	var $Name;
	var $OnLeft;
	var $CssStyle;
	var $CssClass;
	var $Visible = TRUE;
	var $Header;
	var $Body;
	var $Footer;
	var $Parent;
	var $ShowInButtonGroup = TRUE;
	var $ShowInDropDown = TRUE;
	var $ButtonGroupName = "_default";

	function __construct($Name) {
		$this->Name = $Name;
	}

	function MoveTo($Pos) {
		$this->Parent->MoveItem($this->Name, $Pos);
	}

	function Render($Part, $ColSpan = 1) {
		$tagclass = $this->Parent->TagClassName;
		if ($Part == "header") {
			if ($tagclass == "") $tagclass = "ewListOptionHeader";
			$value = $this->Header;
		} elseif ($Part == "body") {
			if ($tagclass == "") $tagclass = "ewListOptionBody";
			if ($this->Parent->Tag <> "td")
				ewr_AppendClass($tagclass, "ewListOptionSeparator");
			$value = $this->Body;
		} elseif ($Part == "footer") {
			if ($tagclass == "") $tagclass = "ewListOptionFooter";
			$value = $this->Footer;
		} else {
			$value = $Part;
		}
		if (strval($value) == "" && $this->Parent->Tag == "span" && $this->Parent->ScriptId == "")
			return "";
		$res = ($value <> "") ? $value : "&nbsp;";
		ewr_AppendClass($tagclass, $this->CssClass);
		$attrs = array("class" => $tagclass, "style" => $this->CssStyle, "data-name" => $this->Name);
		if (strtolower($this->Parent->Tag) == "td" && $this->Parent->RowSpan > 1)
			$attrs["rowspan"] = $this->Parent->RowSpan;
		if (strtolower($this->Parent->Tag) == "td" && $ColSpan > 1)
			$attrs["colspan"] = $ColSpan;
		$name = $this->Parent->TableVar . "_" . $this->Name;
		if ($this->Name <> $this->Parent->GroupOptionName) {
			if (!in_array($this->Name, array('checkbox', 'rowcnt'))) {
				if ($this->Parent->UseImageAndText)
					$res = $this->GetImageAndText($res);
				if ($this->Parent->UseButtonGroup && $this->ShowInButtonGroup) {
					$res = $this->Parent->RenderButtonGroup($res);
					if ($this->OnLeft && strtolower($this->Parent->Tag) == "td" && $ColSpan > 1)
						$res = '<div style="text-align: right">' . $res . '</div>';
				}
			}
			if ($Part == "header")
				$res = "<span id=\"elh_" . $name . "\" class=\"" . $name . "\">" . $res . "</span>";
			else if ($Part == "body")
				$res = "<span id=\"el" . $this->Parent->RowCnt . "_" . $name . "\" class=\"" . $name . "\">" . $res . "</span>";
			else if ($Part == "footer")
				$res = "<span id=\"elf_" . $name . "\" class=\"" . $name . "\">" . $res . "</span>";
		}
		$tag = ($this->Parent->Tag == "td" && $Part == "header") ? "th" : $this->Parent->Tag;
		if ($this->Parent->UseButtonGroup && $this->ShowInButtonGroup)
			$attrs["style"] .= "white-space: nowrap;";
		$res = ewr_HtmlElement($tag, $attrs, $res);
		return $res;
	}

	// Get image and text link
	function GetImageAndText($body) {
		if (!preg_match_all('/<a([^>]*)>([\s\S]*?)<\/a\s*>/i', $body, $matches, PREG_SET_ORDER))
			return $body;
		foreach ($matches as $match) {
			if (preg_match('/\s+data-caption\s*=\s*[\'"]([\s\S]*?)[\'"]/i', $match[1], $submatches)) { // Match data-caption='caption'
				$caption = $submatches[1];
				if (preg_match('/<img([^>]*)>/i', $match[2])) // Image and text
					$body = str_replace($match[2], $match[2] . '&nbsp;&nbsp;' . $caption, $body);
			}
		}
		return $body;
	}
}
/**
 * Advanced Security class
 */

class crAdvancedSecurity {
	var $UserLevel = array(); // All User Levels
	var $UserLevelPriv = array(); // All User Level permissions
	var $UserLevelID = array(); // User Level ID array
	var $UserID = array(); // User ID array
	var $CurrentUserLevelID;
	var $CurrentUserLevel; // Permissions
	var $CurrentUserID;
	var $CurrentParentUserID;

	// Constructor
	function __construct() {

		// Init User Level
		if ($this->IsLoggedIn()) {
			$this->CurrentUserLevelID = $this->SessionUserLevelID();
			if (is_numeric($this->CurrentUserLevelID) && intval($this->CurrentUserLevelID) >= -1) {
				$this->UserLevelID[] = $this->CurrentUserLevelID;
			}
		} else { // Anonymous user
			$this->CurrentUserLevelID = -2;
			$this->UserLevelID[] = $this->CurrentUserLevelID;
		}

		// Init User ID
		$this->CurrentUserID = $this->SessionUserID();
		$this->CurrentParentUserID = $this->SessionParentUserID();

		// Load user level
		$this->LoadUserLevel();
	}

	// Session User ID
	function SessionUserID() {
		return strval(@$_SESSION[EWR_SESSION_USER_ID]);
	}

	function setSessionUserID($v) {
		$_SESSION[EWR_SESSION_USER_ID] = trim(strval($v));
		$this->CurrentUserID = trim(strval($v));
	}

	// Session Parent User ID
	function SessionParentUserID() {
		return strval(@$_SESSION[EWR_SESSION_PARENT_USER_ID]);
	}

	function setSessionParentUserID($v) {
		$_SESSION[EWR_SESSION_PARENT_USER_ID] = trim(strval($v));
		$this->CurrentParentUserID = trim(strval($v));
	}

	// Session User Level ID
	function SessionUserLevelID() {
		return @$_SESSION[EWR_SESSION_USER_LEVEL_ID];
	}

	function setSessionUserLevelID($v) {
		$_SESSION[EWR_SESSION_USER_LEVEL_ID] = $v;
		$this->CurrentUserLevelID = $v;
		if (is_numeric($v) && $v >= -1)
			$this->UserLevelID = array($v);
	}

	// Session User Level value
	function SessionUserLevel() {
		return @$_SESSION[EWR_SESSION_USER_LEVEL];
	}

	function setSessionUserLevel($v) {
		$_SESSION[EWR_SESSION_USER_LEVEL] = $v;
		$this->CurrentUserLevel = $v;
	}

	// Current user name
	function getCurrentUserName() {
		return strval(@$_SESSION[EWR_SESSION_USER_NAME]);
	}

	function setCurrentUserName($v) {
		$_SESSION[EWR_SESSION_USER_NAME] = $v;
	}

	function CurrentUserName() {
		return $this->getCurrentUserName();
	}

	// Current User ID
	function CurrentUserID() {
		return $this->CurrentUserID;
	}

	// Current Parent User ID
	function CurrentParentUserID() {
		return $this->CurrentParentUserID;
	}

	// Current User Level ID
	function CurrentUserLevelID() {
		return $this->CurrentUserLevelID;
	}

	// Current User Level value
	function CurrentUserLevel() {
		return $this->CurrentUserLevel;
	}

	// Can list
	function CanList() {
		return (($this->CurrentUserLevel & EWR_ALLOW_LIST) == EWR_ALLOW_LIST);
	}

	function setCanList($b) {
		if ($b) {
			$this->CurrentUserLevel = ($this->CurrentUserLevel | EWR_ALLOW_LIST);
		} else {
			$this->CurrentUserLevel = ($this->CurrentUserLevel & (~ EWR_ALLOW_LIST));
		}
	}

	// Can report
	function CanReport() {
		return (($this->CurrentUserLevel & EWR_ALLOW_REPORT) == EWR_ALLOW_REPORT);
	}

	function setCanReport($b) {
		if ($b) {
			$this->CurrentUserLevel = ($this->CurrentUserLevel | EWR_ALLOW_REPORT);
		} else {
			$this->CurrentUserLevel = ($this->CurrentUserLevel & (~ EWR_ALLOW_REPORT));
		}
	}

	// Can admin
	function CanAdmin() {
		return (($this->CurrentUserLevel & EWR_ALLOW_ADMIN) == EWR_ALLOW_ADMIN);
	}

	function setCanAdmin($b) {
		if ($b) {
			$this->CurrentUserLevel = ($this->CurrentUserLevel | EWR_ALLOW_ADMIN);
		} else {
			$this->CurrentUserLevel = ($this->CurrentUserLevel & (~ EWR_ALLOW_ADMIN));
		}
	}

	// Last URL
	function LastUrl() {
		if (is_array(@$_COOKIE[EWR_PROJECT_NAME]))
			return @$_COOKIE[EWR_PROJECT_NAME]['LastUrl'];
		return "";
	}

	// Save last URL
	function SaveLastUrl() {
		$s = ewr_ServerVar("SCRIPT_NAME");
		$q = ewr_ServerVar("QUERY_STRING");
		if ($q <> "") $s .= "?" . $q;
		if ($this->LastUrl() == $s) $s = "";
		@setcookie(EWR_PROJECT_NAME . '[LastUrl]', $s);
	}

	// Auto login
	function AutoLogin() {
		$AutoLogin = FALSE;
		if (!$AutoLogin && @$_COOKIE[EWR_PROJECT_NAME]['AutoLogin'] == "autologin") {
			$usr = ewr_Decrypt(@$_COOKIE[EWR_PROJECT_NAME]['Username']);
			$pwd = ewr_Decrypt(@$_COOKIE[EWR_PROJECT_NAME]['Password']);
			$AutoLogin = $this->ValidateUser($usr, $pwd, TRUE, FALSE);
		}
		if (!$AutoLogin && EWR_ALLOW_LOGIN_BY_URL && isset($_GET["username"])) {
			$usr = ewr_RemoveXSS($_GET["username"]);
			$pwd = ewr_RemoveXSS(@$_GET["password"]);
			$enc = !empty($_GET["encrypted"]);
			$AutoLogin = $this->ValidateUser($usr, $pwd, TRUE, $enc);
		}
		if (!$AutoLogin && EWR_ALLOW_LOGIN_BY_SESSION && isset($_SESSION[EWR_PROJECT_NAME . "_Username"])) {
			$usr = $_SESSION[EWR_PROJECT_NAME . "_Username"];
			$pwd = @$_SESSION[EWR_PROJECT_NAME . "_Password"];
			$enc = !empty($_SESSION[EWR_PROJECT_NAME . "_Encrypted"]);
			$AutoLogin = $this->ValidateUser($usr, $pwd, TRUE, $enc);
		}
		return $AutoLogin;
	}

	// Login user
	function LoginUser($userName = NULL, $userID = NULL, $parentUserID = NULL, $userLevel = NULL) {
		$_SESSION[EWR_SESSION_STATUS] = "login";
		if (!is_null($userName))
			$this->setCurrentUserName($userName);
		if (!is_null($userID))
			$this->setSessionUserID($userID);
		if (!is_null($parentUserID))
			$this->setSessionParentUserID($parentUserID);
		if (!is_null($userLevel)) {
			$this->setSessionUserLevelID(intval($userLevel));
			SetUpUserLevel();
		}
	}

	// Validate user
	function ValidateUser(&$usr, &$pwd, $autologin, $encrypted = FALSE, $provider = "") {
		global $ReportLanguage, $UserProfile, $EWR_AUTH_CONFIG;
		$valid = FALSE;
		$customValid = FALSE;
		$providerValid = FALSE;

		// OAuth provider
		if ($provider <> "") {
			$providers = $EWR_AUTH_CONFIG["providers"];
			if (array_key_exists($provider, $providers) && $providers[$provider]["enabled"]) {
				try {
					$UserProfile->Provider = $provider;
					if (!array_key_exists("base_url", $EWR_AUTH_CONFIG))
						$EWR_AUTH_CONFIG["base_url"] = ewr_FullUrl("hybridauth/", "auth"); // Set base URL
					$hybridauth = new Hybrid_Auth($EWR_AUTH_CONFIG);
					$UserProfile->Auth = $hybridauth;
					$adapter = $hybridauth->authenticate($provider); // Authenticate with the selected provider
					$profile = $adapter->getUserProfile();
					$UserProfile->Assign($profile); // Save profile
					$usr = $profile->email;
					$providerValid = TRUE;
				} catch (Exception $e) {
					if (EWR_DEBUG_ENABLED) { // Show debug message
						echo $e->getMessage();
						exit();
					}
					return FALSE;
				}
			} else {
				return FALSE;
			}
		}

		// Call User Custom Validate event
		if (EWR_USE_CUSTOM_LOGIN) {
			$customValid = $this->User_CustomValidate($usr, $pwd);
		}

		// Handle provider login as custom login
		if ($providerValid)
			$customValid = TRUE;
		if ($customValid) {

			//$_SESSION[EWR_SESSION_STATUS] = "login"; // To be setup below
			$this->setCurrentUserName($usr); // Load user name
		}
		if ($customValid) {
			$rs = NULL;
			$customValid = $this->User_Validated($rs) !== FALSE;
		}
		$UserProfile->Save();
		if ($customValid)
			return $customValid;
		if (!$valid)
			$_SESSION[EWR_SESSION_STATUS] = ""; // Clear login status
		return $valid;
	}

	// No User Level security
	function SetUpUserLevel() {}

	// Add user permission
	function AddUserPermission($UserLevelName, $TableName, $UserPermission) {

		// Get User Level ID from user name
		$UserLevelID = "";
		if (is_array($this->UserLevel)) {
			foreach ($this->UserLevel as $row) {
				list($levelid, $name) = $row;
				if (ewr_SameText($UserLevelName, $name)) {
					$UserLevelID = $levelid;
					break;
				}
			}
		}
		if (is_array($this->UserLevelPriv) && $UserLevelID <> "") {
			$cnt = count($this->UserLevelPriv);
			for ($i = 0; $i < $cnt; $i++) {
				list($table, $levelid, $priv) = $this->UserLevelPriv[$i];
				if (ewr_SameText($table, EWR_PROJECT_ID . $TableName) && ewr_SameStr($levelid, $UserLevelID)) {
					$this->UserLevelPriv[$i][2] = $priv | $UserPermission; // Add permission
					break;
				}
			}
		}
	}

	// Delete user permission
	function DeleteUserPermission($UserLevelName, $TableName, $UserPermission) {

		// Get User Level ID from user name
		$UserLevelID = "";
		if (is_array($this->UserLevel)) {
			foreach ($this->UserLevel as $row) {
				list($levelid, $name) = $row;
				if (ewr_SameText($UserLevelName, $name)) {
					$UserLevelID = $levelid;
					break;
				}
			}
		}
		if (is_array($this->UserLevelPriv) && $UserLevelID <> "") {
			$cnt = count($this->UserLevelPriv);
			for ($i = 0; $i < $cnt; $i++) {
				list($table, $levelid, $priv) = $this->UserLevelPriv[$i];
				if (ewr_SameText($table, EWR_PROJECT_ID . $TableName) && ewr_SameStr($levelid, $UserLevelID)) {
					$this->UserLevelPriv[$i][2] = $priv & (127 - $UserPermission); // Remove permission
					break;
				}
			}
		}
	}

	// Load current User Level
	function LoadCurrentUserLevel($Table) {
		$this->LoadUserLevel();
		$this->setSessionUserLevel($this->CurrentUserLevelPriv($Table));
	}

	// Get current user privilege
	function CurrentUserLevelPriv($TableName) {
		if ($this->IsLoggedIn()) {
			$Priv= 0;
			foreach ($this->UserLevelID as $UserLevelID)
				$Priv |= $this->GetUserLevelPrivEx($TableName, $UserLevelID);
			return $Priv;
		} else { // Anonymous
			return $this->GetUserLevelPrivEx($TableName, -2);
		}
	}

	// Get User Level ID by User Level name
	function GetUserLevelID($UserLevelName) {
		global $ReportLanguage;
		if (ewr_SameStr($UserLevelName, "Administrator")) {
			return -1;
		} elseif ($ReportLanguage && ewr_SameStr($UserLevelName, $ReportLanguage->Phrase("UserAdministrator"))) {
			return -1;
		} elseif (ewr_SameStr($UserLevelName, "Default")) {
			return 0;
		} elseif ($ReportLanguage && ewr_SameStr($UserLevelName, $ReportLanguage->Phrase("UserDefault"))) {
			return 0;
		} elseif ($UserLevelName <> "") {
			if (is_array($this->UserLevel)) {
				foreach ($this->UserLevel as $row) {
					list($levelid, $name) = $row;
					if (ewr_SameStr($name, $UserLevelName))
						return $levelid;
				}
			}
		}
		return -2; // Anonymous
	}

	// Add User Level by name
	function AddUserLevel($UserLevelName) {
		if (strval($UserLevelName) == "") return;
		$UserLevelID = $this->GetUserLevelID($UserLevelName);
		$this->AddUserLevelID($UserLevelID);
	}

	// Add User Level by ID
	function AddUserLevelID($UserLevelID) {
		if (!is_numeric($UserLevelID)) return;
		if ($UserLevelID < -1) return;
		if (!in_array($UserLevelID, $this->UserLevelID))
			$this->UserLevelID[] = $UserLevelID;
	}

	// Delete User Level by name
	function DeleteUserLevel($UserLevelName) {
		if (strval($UserLevelName) == "") return;
		$UserLevelID = $this->GetUserLevelID($UserLevelName);
		$this->DeleteUserLevelID($UserLevelID);
	}

	// Delete User Level by ID
	function DeleteUserLevelID($UserLevelID) {
		if (!is_numeric($UserLevelID)) return;
		if ($UserLevelID < -1) return;
		$cnt = count($this->UserLevelID);
		for ($i = 0; $i < $cnt; $i++) {
			if ($this->UserLevelID[$i] == $UserLevelID) {
				unset($this->UserLevelID[$i]);
				break;
			}
		}
	}

	// User Level list
	function UserLevelList() {
		return implode(", ", $this->UserLevelID);
	}

	// User Level name list
	function UserLevelNameList() {
		$list = "";
		foreach ($this->UserLevelID as $UserLevelID) {
			if ($list <> "") $lList .= ", ";
			$list .= ewr_QuotedValue($this->GetUserLevelName($UserLevelID), EWR_DATATYPE_STRING, EWR_USER_LEVEL_DBID);
		}
		return $list;
	}

	// Get user privilege based on table name and User Level
	function GetUserLevelPrivEx($TableName, $UserLevelID) {
		if (strval($UserLevelID) == "-1") { // System Administrator
			return 127; // Use new User Level values (separate View/Search)
		} elseif ($UserLevelID >= 0 || $UserLevelID == -2) {
			if (is_array($this->UserLevelPriv)) {
				foreach ($this->UserLevelPriv as $row) {
					list($table, $levelid, $priv) = $row;
					if (strtolower($table) == strtolower($TableName) && strval($levelid) == strval($UserLevelID)) {
						if (is_null($priv) || !is_numeric($priv)) return 0;
						return intval($priv);
					}
				}
			}
		}
		return 0;
	}

	// Get current User Level name
	function CurrentUserLevelName() {
		return $this->GetUserLevelName($this->CurrentUserLevelID());
	}

	// Get User Level name based on User Level
	function GetUserLevelName($UserLevelID) {
		if (strval($UserLevelID) == "-1") {
			return "Administrator";
		} elseif ($UserLevelID >= 0) {
			if (is_array($this->UserLevel)) {
				foreach ($this->UserLevel as $row) {
					list($levelid, $name) = $row;
					if (strval($levelid) == strval($UserLevelID))
						return $name;
				}
			}
		}
		return "";
	}

	// Display all the User Level settings (for debug only)
	function ShowUserLevelInfo() {
		echo "<pre>";
		print_r($this->UserLevel);
		print_r($this->UserLevelPriv);
		echo "</pre>";
		echo "<p>Current User Level ID = " . $this->CurrentUserLevelID() . "</p>";
		echo "<p>Current User Level ID List = " . $this->UserLevelList() . "</p>";
	}

	// Check privilege for List page (for menu items)
	function AllowList($TableName) {
		return ($this->CurrentUserLevelPriv($TableName) & EWR_ALLOW_LIST);
	}

	// Check if user is logged in
	function IsLoggedIn() {
		return (@$_SESSION[EWR_SESSION_STATUS] == "login");
	}

	// Check if user is system administrator
	function IsSysAdmin() {
		return (@$_SESSION[EWR_SESSION_SYSTEM_ADMIN] == 1);
	}

	// Check if user is administrator
	function IsAdmin() {
		$IsAdmin = $this->IsSysAdmin();
		return $IsAdmin;
	}

	// Save User Level to Session
	function SaveUserLevel() {
		$_SESSION[EWR_SESSION_AR_USER_LEVEL] = $this->UserLevel;
		$_SESSION[EWR_SESSION_AR_USER_LEVEL_PRIV] = $this->UserLevelPriv;
	}

	// Load User Level from Session
	function LoadUserLevel() {
		if (!is_array(@$_SESSION[EWR_SESSION_AR_USER_LEVEL]) || !is_array(@$_SESSION[EWR_SESSION_AR_USER_LEVEL_PRIV])) {
			$this->SetupUserLevel();
			$this->SaveUserLevel();
		} else {
			$this->UserLevel = $_SESSION[EWR_SESSION_AR_USER_LEVEL];
			$this->UserLevelPriv = $_SESSION[EWR_SESSION_AR_USER_LEVEL_PRIV];
		}
	}

	// Get current user info
	function CurrentUserInfo($fldname) {
		global $UserTableConn;
		$info = NULL;
		if (defined("EWR_USER_TABLE") && !$this->IsSysAdmin()) {
			$user = $this->CurrentUserName();
			if (strval($user) <> "")
				return ewr_ExecuteScalar("SELECT " . ewr_QuotedName($fldname, EWR_USER_TABLE_DBID) . " FROM " . EWR_USER_TABLE . " WHERE " .
					str_replace("%u", ewr_AdjustSql($user, EWR_USER_TABLE_DBID), EWR_USER_NAME_FILTER), $UserTableConn);
		}
		return $info;
	}

	// UserID Loading event
	function UserID_Loading() {

		//echo "UserID Loading: " . $this->CurrentUserID() . "<br>";
	}

	// UserID Loaded event
	function UserID_Loaded() {

		//echo "UserID Loaded: " . $this->UserIDList() . "<br>";
	}

	// User Level Loaded event
	function UserLevel_Loaded() {

		//$this->AddUserPermission(<UserLevelName>, <TableName>, <UserPermission>);
		//$this->DeleteUserPermission(<UserLevelName>, <TableName>, <UserPermission>);

	}

	// Table Permission Loading event
	function TablePermission_Loading() {

		//echo "Table Permission Loading: " . $this->CurrentUserLevelID() . "<br>";
	}

	// Table Permission Loaded event
	function TablePermission_Loaded() {

		//echo "Table Permission Loaded: " . $this->CurrentUserLevel . "<br>";
	}

	// User Custom Validate event
	function User_CustomValidate(&$usr, &$pwd) {

		// Enter your custom code to validate user, return TRUE if valid.
		return FALSE;
	}

	// User Validated event
	function User_Validated(&$rs) {

		// Example:
		//$_SESSION['UserEmail'] = $rs['Email'];

	}
}
/**
 * Functions for backward compatibilty
 */

// Get current user name
function CurrentUserName() {
	global $Security;
	return (isset($Security)) ? $Security->CurrentUserName() : strval(@$_SESSION[EWR_SESSION_USER_NAME]);
}

// Get current user ID
function CurrentUserID() {
	global $Security;
	return (isset($Security)) ? $Security->CurrentUserID() : strval(@$_SESSION[EWR_SESSION_USER_ID]);
}

// Get current parent user ID
function CurrentParentUserID() {
	global $Security;
	return (isset($Security)) ? $Security->CurrentParentUserID() : strval(@$_SESSION[EWR_SESSION_PARENT_USER_ID]);
}

// Get current user level
function CurrentUserLevel() {
	global $Security;
	return (isset($Security)) ? $Security->CurrentUserLevelID() : @$_SESSION[EWR_SESSION_USER_LEVEL_ID];
}

// Get current user level list
function CurrentUserLevelList() {
	global $Security;
	return (isset($Security)) ? $Security->UserLevelList() : strval(@$_SESSION[EWR_SESSION_USER_LEVEL_ID]);
}

// Get Current user info
function CurrentUserInfo($fldname) {
	global $Security, $UserTableConn;
	if (isset($Security)) {
		return $Security->CurrentUserInfo($fldname);
	} elseif (defined("EWR_USER_TABLE") && !IsSysAdmin()) {
		$user = CurrentUserName();
		if (strval($user) <> "")
			return ewr_ExecuteScalar("SELECT " . ewr_QuotedName($fldname, EWR_USER_TABLE_DBID) . " FROM " . EWR_USER_TABLE . " WHERE " .
				str_replace("%u", ewr_AdjustSql($user, EWR_USER_TABLE_DBID), EWR_USER_NAME_FILTER), $UserTableConn);
	}
	return NULL;
}

// Is logged in
if (!function_exists('IsLoggedIn')) {

	function IsLoggedIn() {
		global $Security;
		return (isset($Security)) ? $Security->IsLoggedIn() : (@$_SESSION[EWR_SESSION_STATUS] == "login");
	}
}

// Get current page heading
if (!function_exists('CurrentPageHeading')) {

	function CurrentPageHeading() {
		global $ReportLanguage;
		if (EWR_PAGE_TITLE_STYLE <> "Title" && isset($GLOBALS["Page"]) && method_exists($GLOBALS["Page"], "PageHeading")) {
			$heading = $GLOBALS["Page"]->PageHeading();
			if ($heading <> "")
				return $heading;
		}
		return $ReportLanguage->ProjectPhrase("BodyTitle");
	}
}

// Get current page subheading
if (!function_exists('CurrentPageSubheading')) {

	function CurrentPageSubheading() {
		$heading = "";
		if (EWR_PAGE_TITLE_STYLE <> "Title" && isset($GLOBALS["Page"]) && method_exists($GLOBALS["Page"], "PageSubheading"))
			$heading = $GLOBALS["Page"]->PageSubheading();
		return $heading;
	}
}

// Get global login status array
if (!function_exists('LoginStatus')) {

	function LoginStatus() {
		global $ReportLoginStatus;
		return $ReportLoginStatus;
	}
}

// Set up login status
if (!function_exists('SetupLoginStatus')) {

	function SetupLoginStatus() {
		global $ReportLoginStatus, $ReportLanguage;
		$ReportLoginStatus["isLoggedIn"] = IsLoggedIn();
		$ReportLoginStatus["currentUserName"] = CurrentUserName();
		$ReportLoginStatus["logoutUrl"] = ewr_GetUrl("rlogout.php");
		$ReportLoginStatus["logoutText"] = $ReportLanguage->Phrase("Logout");
		$ReportLoginStatus["loginUrl"] = ewr_GetUrl("rlogin.php");
		$ReportLoginStatus["loginText"] = $ReportLanguage->Phrase("Login");
		$ReportLoginStatus["canLogin"] = !IsLoggedIn() && !ewr_EndsStr($ReportLoginStatus["loginUrl"], @$_SERVER["URL"]);
		$ReportLoginStatus["canLogout"] = IsLoggedIn();
	}
}

// Is auto login (login with option "Auto login until I logout explicitly")
function ewr_IsAutoLogin() {
	return (@$_SESSION[EWR_SESSION_USER_LOGIN_TYPE] == "a");
}

// Check if user is system administrator
function IsSysAdmin() {
	return (@$_SESSION[EWR_SESSION_SYSTEM_ADMIN] == 1);
}

// Is Windows authenticated
function IsAuthenticated() {
	return ewr_CurrentWindowsUser() <> "";
}

// Is Export
function IsExport($format = "") {
	global $gsExport;
	if ($format)
		return ewr_SameText($gsExport, $format);
	else
		return ($gsExport <> "");
}

// Get current page ID
function CurrentPageID() {
	if (isset($GLOBALS["Page"])) {
		return $GLOBALS["Page"]->PageID;
	} elseif (defined("EWR_PAGE_ID")) {
		return EWR_PAGE_ID;
	}
	return "";
}

// Allow list
function AllowList($TableName) {
	global $Security;
	return $Security->AllowList($TableName);
}

// Get user IP
function ewr_CurrentUserIP() {
	return ewr_ServerVar("REMOTE_ADDR");
}

// Get current Windows user (for Windows Authentication)
function ewr_CurrentWindowsUser() {
	return ewr_ServerVar("AUTH_USER"); // REMOTE_USER or LOGON_USER or AUTH_USER
}
/**
 * User Profile Class
 */

class crUserProfile {
	var $Profile = array();
	var $Provider = "";
	var $Auth = "";

	// Constructor
	function __construct() {
		$this->Load();
	}

	// Get value
	function GetValue($name) {
		if (array_key_exists($name, $this->Profile))
			return $this->Profile[$name];
		return NULL;
	}

	// Get value (alias)
	function Get($name) {
		return $this->GetValue($name);
	}

	// Set value
	function SetValue($name, $value) {
		$this->Profile[$name] = $value;
	}

	// Set value (alias)
	function Set($name, $value) {
		$this->SetValue($name, $value);
	}

	// Set property // PHP
	function __set($name, $value) {
		$this->SetValue($name, $value);
	}

	// Get property // PHP
	function __get($name) {
		return $this->GetValue($name);
	}

	// Assign properties
	function Assign($input) {
		if (is_object($input)) {
			$this->Assign(get_object_vars($input));
		} elseif (is_array($input)) {
			foreach($input as $key => $value) // Remove integer keys
				if(is_int($key))
					unset($input[$key]);
			$input = array_filter($input, function($val) {
				if (is_bool($val) || is_float($val) || is_int($val) || is_null($val) ||
					is_string($val) && strlen($val) < 256)
					return TRUE;
				return FALSE;
			});
			$this->Profile = array_merge($this->Profile, $input);
		}
	}

	// Load profile from session
	function Load() {
		if (isset($_SESSION[EWR_SESSION_USER_PROFILE]))
			$this->LoadProfile($_SESSION[EWR_SESSION_USER_PROFILE]);
	}

	// Save profile to session
	function Save() {
		$_SESSION[EWR_SESSION_USER_PROFILE] = $this->ProfileToString();
	}

	// Load profile from string
	function LoadProfile($Profile) {
		$ar = unserialize(strval($Profile));
		if (is_array($ar))
			$this->Profile = array_merge($this->Profile, $ar);
	}

	// Write (var_dump) profile
	function WriteProfile() {
		var_dump($this->Profile);
	}

	// Clear profile
	function ClearProfile() {
		$this->Profile = array();
	}

	// Clear profile (alias)
	function Clear() {
		$this->ClearProfile();
	}

	// Profile to string
	function ProfileToString() {
		return serialize($this->Profile);
	}
}

// Load recordset
function &ewr_LoadRecordset($SQL, $c = NULL) {
	if (is_string($c))
		$c = &ReportConn($c);
	$conn = ($c) ? $c : $GLOBALS["conn"];
	$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
	$rs = $conn->Execute($SQL);
	$conn->raiseErrorFn = '';
	return $rs;
}

// Execute UPDATE, INSERT, or DELETE statements
function ewr_Execute($SQL, $fn = NULL, $c = NULL) {
	if (is_null($c) && (is_string($fn) || is_object($fn) && method_exists($fn, "Execute")))
		$c = $fn;
	if (is_string($c))
		$c = &ReportConn($c);
	$conn = ($c) ? $c : $GLOBALS["conn"];
	$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
	$rs = $conn->Execute($SQL);
	$conn->raiseErrorFn = '';
	if (is_callable($fn) && $rs) {
		while (!$rs->EOF) {
			$fn($rs->fields);
			$rs->MoveNext();
		}
		$rs->MoveFirst(); // For MySQL and PostgreSQL only
	}
	return $rs;
}

// Executes the query, and returns the first column of the first row
function ewr_ExecuteScalar($SQL, $c = NULL) {
	$res = FALSE;
	$rs = ewr_LoadRecordset($SQL, $c);
	if ($rs && !$rs->EOF && $rs->FieldCount() > 0) {
		$res = $rs->fields[0];
		$rs->Close();
	}
	return $res;
}

// Executes the query, and returns the first row
function ewr_ExecuteRow($SQL, $c = NULL) {
	$res = FALSE;
	$rs = ewr_LoadRecordset($SQL, $c);
	if ($rs && !$rs->EOF) {
		$res = $rs->fields;
		$rs->Close();
	}
	return $res;
}

// Executes the query, and returns all row
function ewr_ExecuteRows($SQL, $c = NULL) {
	$res = FALSE;
	$rs = ewr_LoadRecordset($SQL, $c);
	if ($rs && !$rs->EOF) {
		$res = $rs->GetRows();
		$rs->Close();
	}
	return $res;
}

// Get result in HTML table
function ewr_ExecuteHtml($SQL, $options = NULL, $c = NULL) {
	$TableClass = "table table-bordered ewDbTable"; // Table CSS class name
	$ar = is_array($options) ? $options : array();
	$horizontal = (array_key_exists("horizontal", $ar) && $ar["horizontal"]);
	$rs = ewr_LoadRecordset($SQL, $c);
	if (!$rs || $rs->EOF || $rs->FieldCount() < 1)
		return "";
	$html = "";
	$class = (array_key_exists("tableclass", $ar) && $ar["tableclass"]) ? $ar["tableclass"] : $TableClass;
	if ($rs->RecordCount() > 1 || $horizontal) { // Horizontal table
		$cnt = $rs->FieldCount();
		$html = "<table class=\"" . $class . "\">";
		$html .= "<thead><tr>";
		$row = &$rs->fields;
		foreach ($row as $key => $value) {
			if (!is_numeric($key))
				$html .= "<th>" . ewr_GetFieldCaption($key, $ar) . "</th>";
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
				$html .= "<td>" . ewr_GetFieldCaption($key, $ar) . "</td>";
				$html .= "<td>" . $value . "</td></tr>";
			}
		}
		$html .= "</tbody></table>";
	}
	return $html;
}

// Get field caption
function ewr_GetFieldCaption($key, $ar) {
	global $ReportLanguage;
	$caption = "";
	if (!is_array($ar))
		return $key;
	$tablename = @$ar["tablename"];
	$usecaption = (array_key_exists("fieldcaption", $ar) && $ar["fieldcaption"]);
	if ($usecaption) {
		if (is_array($ar["fieldcaption"])) {
			$caption = @$ar["fieldcaption"][$key];
		} elseif (isset($ReportLanguage)) {
			if (is_array($tablename)) {
				foreach ($tablename as $tbl) {
					$caption = @$ReportLanguage->FieldPhrase($tbl, $key, "FldCaption");
					if ($caption <> "")
						break;
				}
			} elseif ($tablename <> "") {
				$caption = @$ReportLanguage->FieldPhrase($tablename, $key, "FldCaption");
			}
		}
	}
	return ($caption <> "") ? $caption : $key;
}

// Generate a GUID
function ewr_RandomGuid($trim = TRUE) {

	// Windows
	if (function_exists('com_create_guid')) {
		if ($trim === TRUE)
			return trim(com_create_guid(), '{}');
		else
			return com_create_guid();
	}

	// OSX/Linux
	if (function_exists('openssl_random_pseudo_bytes')) {
		$data = openssl_random_pseudo_bytes(16);
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // Set version to 0100
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // Set bits 6-7 to 10
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}

	// Fallback
	mt_srand((double)microtime() * 10000);
	$charid = strtolower(md5(uniqid(rand(), true)));
	$hyphen = chr(45); // "-"
	$lbrace = $trim ? "" : chr(123); // "{"
	$rbrace = $trim ? "" : chr(125); // "}"
	$guid = $lbrace . substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen .
		substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12) . $rbrace;
	return $guid;
}

// Check if valid operator
function ewr_IsValidOpr($Opr, $FldType) {
	$valid = ($Opr == "=" || $Opr == "<" || $Opr == "<=" ||
		$Opr == ">" || $Opr == ">=" || $Opr == "<>");
	if ($FldType == EWR_DATATYPE_STRING || $FldType == EWR_DATATYPE_MEMO)
		$valid = ($valid || $Opr == "LIKE" || $Opr == "NOT LIKE" || $Opr == "STARTS WITH" || $Opr == "ENDS WITH");
	return $valid;
}

// Quote table/field name based on dbid
function ewr_QuotedName($Name, $DbId = 0) {
	global $EWR_CONN;
	$db = @$EWR_CONN[$DbId];
	if ($db) {
		$qs = $db["qs"];
		$qe = $db["qe"];
		$Name = str_replace($qe, $qe . $qe, $Name);
		return $qs . $Name . $qe;
	} else { // Use default quotes
		$Name = str_replace(EWR_DB_QUOTE_END, EWR_DB_QUOTE_END . EWR_DB_QUOTE_END, $Name);
		return EWR_DB_QUOTE_START . $Name . EWR_DB_QUOTE_END;
	}
}

// Quote field value based on dbid
function ewr_QuotedValue($Value, $FldType, $DbId = 0) {
	if (is_null($Value))
		return "NULL";
	$dbtype = ewr_GetConnectionType($DbId);
	switch ($FldType) {
		case EWR_DATATYPE_STRING:
		case EWR_DATATYPE_MEMO:
			if (EWR_REMOVE_XSS)
				$Value = ewr_RemoveXSS($Value);
			if ($dbtype == "MSSQL")
				return "N'" . ewr_AdjustSql($Value, $DbId) . "'";
			else
				return "'" . ewr_AdjustSql($Value, $DbId) . "'";
		case EWR_DATATYPE_TIME:
			if (EWR_REMOVE_XSS)
				$Value = ewr_RemoveXSS($Value);
			return "'" . ewr_AdjustSql($Value, $DbId) . "'";
		case EWR_DATATYPE_BLOB:
			if ($dbtype == "MYSQL") {
				return "'" . addslashes($Value) . "'";
			} elseif ($dbtype == "POSTGRESQL") {
				return "'" . ReportConn($DbId)->BlobEncode($Value) . "'";
			} else {
				return "0x" . bin2hex($Value);
			}
		case EWR_DATATYPE_DATE:
			if ($dbtype == "ACCESS")
				return "#" . ewr_AdjustSql($Value, $DbId) . "#";
			else
				return "'" . ewr_AdjustSql($Value, $DbId) . "'";
		case EWR_DATATYPE_GUID:
			if ($dbtype == "ACCESS") {
				if (strlen($Value) == 38) {
					return "{guid " . $Value . "}";
				} elseif (strlen($Value) == 36) {
					return "{guid {" . $Value . "}}";
				}
			} else {
				return "'" . $Value . "'";
			}
		case EWR_DATATYPE_BOOLEAN:
			if ($dbtype == "MYSQL" || $dbtype == "POSTGRESQL")
				return "'" . $Value . "'"; // 'Y'|'N' or 'y'|'n' or '1'|'0' or 't'|'f'
			else
				return $Value;
		default:
			return $Value;
	}
}

// Convert value
function ewr_ConvertValue($FldOpr, $val) {
	if (is_null($val)) {
		return EWR_NULL_VALUE;
	} elseif ($val == "") {
		return EWR_EMPTY_VALUE;
	}
	if (is_float($val))
		$val = (float)$val;
	if ($FldOpr == "")
		return $val;
	if ($ar = explode(" ", $val)) {
		$ar = explode("-", $ar[0]);
	} else {
		return $val;
	}
	if (!$ar || count($ar) <> 3)
		return $val;
	list($year, $month, $day) = $ar;
	switch (strtolower($FldOpr)) {
	case "year":
		return $year;
	case "quarter":
		return "$year|" . ceil(intval($month)/3);
	case "month":
		return "$year|$month";
	case "day":
		return "$year|$month|$day";
	case "date":
		return "$year-$month-$day";
	}
}

// Dropdown display values
function ewr_DropDownDisplayValue($v, $t, $fmt) {
	global $ReportLanguage;
	if (ewr_SameStr($v, EWR_NULL_VALUE)) {
		return $ReportLanguage->Phrase("NullLabel");
	} elseif (ewr_SameStr($v, EWR_EMPTY_VALUE)) {
		return $ReportLanguage->Phrase("EmptyLabel");
	} elseif (strtolower($t) == "boolean") {
		return ewr_BooleanName($v);
	}
	if ($t == "")
		return $v;
	$ar = explode("|", strval($v));
	switch (strtolower($t)) {
	case "year":
		return $v;
	case "quarter":
		if (count($ar) >= 2)
			return ewr_QuarterName($ar[1]) . " " . $ar[0];
	case "month":
		if (count($ar) >= 2)
			return ewr_MonthName($ar[1]) . " " . $ar[0];
	case "day":
		if (count($ar) >= 3)
			return ewr_FormatDateTime($ar[0] . "-" . $ar[1] . "-" . $ar[2], $fmt);
	case "date":
		return ewr_FormatDateTime($v, $fmt);
	}
}

// Get filter value for dropdown
function ewr_FilterDropDownValue($fld, $sep = ", ") {
	global $ReportLanguage;
	$value = $fld->DropDownValue;
	$out = "";
	if ($value == EWR_INIT_VALUE || is_null($value)) {
		$out = ($sep == ",") ? "" : $ReportLanguage->Phrase("PleaseSelect"); // Output empty string as value for input tag
	} else {
		if (!is_array($value))
			$value = array($value);
		$cnt = count($value);
		for ($i = 0; $i < $cnt; $i++) {
			$val = $value[$i];
			if (substr($val, 0, 2) == "@@") { // Lookup from AdvancedFilter
				if (is_array($fld->AdvancedFilters)) {
					foreach ($fld->AdvancedFilters as $filter) {
						if ($filter->Enabled && $val == $filter->ID) {
							$val = $filter->Name;
							break;
						}
					}
				}
			} else {
				if ($fld->FldDataType == EWR_DATATYPE_DATE)
					$val = ewr_FormatDateTime($val, $fld->FldDateTimeFormat);
			}
			$out .= ($out <> "" ? $sep : "") . $val;
		}
	}
	return $out;
}

// Get current filter value for modal lookup
function ewr_FilterCurrentValue($fld, $sep = ", ") {
	global $ReportLanguage;
	$value = $fld->DropDownValue;
	if (is_array($value))
		$value = implode($sep, $value);
	if ($value == EWR_INIT_VALUE || is_null($value))
		$value = ($sep == ",") ? "" : $ReportLanguage->Phrase("PleaseSelect"); // Output empty string as value for input tag
	return $value;
}

// Get Boolean Name
// - Treat "T" / "True" / "Y" / "Yes" / "1" As True
function ewr_BooleanName($v) {
	global $ReportLanguage;
	if (is_null($v))
		return $ReportLanguage->Phrase("NullLabel");
	elseif (strtoupper($v) == "T" || strtoupper($v) == "TRUE" || strtoupper($v) == "Y" || strtoupper($v) == "YES" Or strval($v) == "1")
		return $ReportLanguage->Phrase("BooleanYes");
	else
		return $ReportLanguage->Phrase("BooleanNo");
}

// Quarter name
function ewr_QuarterName($q) {
	global $ReportLanguage;
	switch ($q) {
	case 1:
		return $ReportLanguage->Phrase("Qtr1");
	case 2:
		return $ReportLanguage->Phrase("Qtr2");
	case 3:
		return $ReportLanguage->Phrase("Qtr3");
	case 4:
		return $ReportLanguage->Phrase("Qtr4");
	default:
		return $q;
	}
}

// Month name
function ewr_MonthName($m) {
	global $ReportLanguage;
	switch ($m) {
	case 1:
		return $ReportLanguage->Phrase("MonthJan");
	case 2:
		return $ReportLanguage->Phrase("MonthFeb");
	case 3:
		return $ReportLanguage->Phrase("MonthMar");
	case 4:
		return $ReportLanguage->Phrase("MonthApr");
	case 5:
		return $ReportLanguage->Phrase("MonthMay");
	case 6:
		return $ReportLanguage->Phrase("MonthJun");
	case 7:
		return $ReportLanguage->Phrase("MonthJul");
	case 8:
		return $ReportLanguage->Phrase("MonthAug");
	case 9:
		return $ReportLanguage->Phrase("MonthSep");
	case 10:
		return $ReportLanguage->Phrase("MonthOct");
	case 11:
		return $ReportLanguage->Phrase("MonthNov");
	case 12:
		return $ReportLanguage->Phrase("MonthDec");
	default:
		return $m;
	}
}

// Get group count for custom template
function ewr_GrpCnt($ar, $key = array()) {
	if (is_array($ar) && is_array($key)) {
		$lvl = count($key);
		$cnt = 0;
		if ($lvl > 1) { // Get next level
			$wrkkey = array_shift($key);
			$wrkar = @$ar[$wrkkey];
			$cnt += ewr_GrpCnt($wrkar, $key);
		} else {
			$wrkar = ($lvl == 0) ? $ar : @$ar[$key[0]];
			if (is_array($wrkar)) { // Accumulate all values
				$grp = count($wrkar);
				for ($i = 1; $i < $grp; $i++)
					$cnt += ewr_GrpCnt($wrkar, array($i));
			} else {
				$cnt = $wrkar;
			}
		}
		return $cnt;
	} else {
		return 0;
	}
}

// Join array
function ewr_JoinArray($ar, $sep, $ft, $pos=0, $dbid=0) {
	if (!is_array($ar))
		return "";
	$arwrk = array_slice($ar, $pos); // Return array from position pos
	$cntar = count($arwrk);
	for ($i = 0; $i < $cntar; $i++)
		$arwrk[$i] = ewr_QuotedValue($arwrk[$i], $ft, $dbid);
	return implode($sep, $arwrk);
}

// Unformat date time based on format type
function ewr_UnFormatDateTime($dt, $namedformat) {
	global $EWR_DATE_SEPARATOR, $EWR_TIME_SEPARATOR, $EWR_DATE_FORMAT, $EWR_DATE_FORMAT_ID;
	if (preg_match('/^([0-9]{4})-([0][1-9]|[1][0-2])-([0][1-9]|[1|2][0-9]|[3][0|1])( (0[0-9]|1[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9]))?$/', $dt))
		return $dt;
	$dt = trim($dt);
	while (strpos($dt, "  ") !== FALSE)
		$dt = str_replace("  ", " ", $dt);
	$arDateTime = explode(" ", $dt);
	if (count($arDateTime) == 0)
		return $dt;
	if ($namedformat == 0 || $namedformat == 1 || $namedformat == 2 || $namedformat == 8)
		$namedformat = $EWR_DATE_FORMAT_ID;
	$arDatePt = explode($EWR_DATE_SEPARATOR, $arDateTime[0]);
	if (count($arDatePt) == 3) {
		switch ($namedformat) {
			case 5:
			case 9: //yyyymmdd
				if (ewr_CheckDate($arDateTime[0])) {
					list($year, $month, $day) = $arDatePt;
					break;
				} else {
					return $dt;
				}
			case 6:
			case 10: //mmddyyyy
				if (ewr_CheckUSDate($arDateTime[0])) {
					list($month, $day, $year) = $arDatePt;
					break;
				} else {
					return $dt;
				}
			case 7:
			case 11: //ddmmyyyy
				if (ewr_CheckEuroDate($arDateTime[0])) {
					list($day, $month, $year) = $arDatePt;
					break;
				} else {
					return $dt;
				}
			case 12:
			case 15: //yymmdd
				if (ewr_CheckShortDate($arDateTime[0])) {
					list($year, $month, $day) = $arDatePt;
					$year = ewr_UnformatYear($year);
					break;
				} else {
					return $dt;
				}
			case 13:
			case 16: //mmddyy
				if (ewr_CheckShortUSDate($arDateTime[0])) {
					list($month, $day, $year) = $arDatePt;
					$year = ewr_UnformatYear($year);
					break;
				} else {
					return $dt;
				}
			case 14:
			case 17: //ddmmyy
				if (ewr_CheckShortEuroDate($arDateTime[0])) {
					list($day, $month, $year) = $arDatePt;
					$year = ewr_UnformatYear($year);
					break;
				} else {
					return $dt;
				}
			default:
				return $dt;
		}
		return $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-" .
			str_pad($day, 2, "0", STR_PAD_LEFT) .
			((count($arDateTime) > 1) ? " " . str_replace($EWR_TIME_SEPARATOR, ":", $arDateTime[1]) : "");
	} else {
		if ($namedformat == 3 || $namedformat == 4) {
			$dt = str_replace($EWR_TIME_SEPARATOR, ":", $dt);
		}
		return $dt;
	}
}

// ViewValue
// - return &nbsp; if empty
function ewr_ViewValue($value) {
	if ($value <> "")
		return $value;
	else
		return "&nbsp;";
}

// Get current year
function ewr_CurrentYear() {
	return intval(date('Y'));
}

// Get current quarter
function ewr_CurrentQuarter() {
	return ceil(intval(date('n'))/3);
}

// Get current month
function ewr_CurrentMonth() {
	return intval(date('n'));
}

// Get current day
function ewr_CurrentDay() {
	return intval(date('j'));
}

// Format a timestamp, datetime, date or time field
// $namedformat:
// 0 - Default date format
// 1 - Long Date (with time)
// 2 - Short Date (without time)
// 3 - Long Time (hh:mm:ss AM/PM)
// 4 - Short Time (hh:mm:ss)
// 5 - Short Date (yyyy/mm/dd)
// 6 - Short Date (mm/dd/yyyy)
// 7 - Short Date (dd/mm/yyyy)
// 8 - Short Date (Default) + Short Time (if not 00:00:00)
// 9 - Short Date (yyyy/mm/dd) + Short Time (hh:mm:ss)
// 10 - Short Date (mm/dd/yyyy) + Short Time (hh:mm:ss)
// 11 - Short Date (dd/mm/yyyy) + Short Time (hh:mm:ss)
// 12 - Short Date - 2 digit year (yy/mm/dd)
// 13 - Short Date - 2 digit year (mm/dd/yy)
// 14 - Short Date - 2 digit year (dd/mm/yy)
// 15 - Short Date (yy/mm/dd) + Short Time (hh:mm:ss)
// 16 - Short Date (mm/dd/yy) + Short Time (hh:mm:ss)
// 17 - Short Date (dd/mm/yy) + Short Time (hh:mm:ss)
function ewr_FormatDateTime($ts, $namedformat) {
	global $ReportLanguage, $EWR_DATE_SEPARATOR, $EWR_TIME_SEPARATOR, $EWR_DATE_FORMAT, $EWR_DATE_FORMAT_ID;
	if ($namedformat == 0)
		$namedformat = $EWR_DATE_FORMAT_ID;
	if (is_numeric($ts)) // Timestamp
	{
		switch (strlen($ts)) {
			case 14:
				$patt = '/(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/';
				break;
			case 12:
				$patt = '/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/';
				break;
			case 10:
				$patt = '/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/';
				break;
			case 8:
				$patt = '/(\d{4})(\d{2})(\d{2})/';
				break;
			case 6:
				$patt = '/(\d{2})(\d{2})(\d{2})/';
				break;
			case 4:
				$patt = '/(\d{2})(\d{2})/';
				break;
			case 2:
				$patt = '/(\d{2})/';
				break;
			default:
				return $ts;
		}
		if ((isset($patt))&&(preg_match($patt, $ts, $matches)))
		{
			$year = $matches[1];
			$month = @$matches[2];
			$day = @$matches[3];
			$hour = @$matches[4];
			$min = @$matches[5];
			$sec = @$matches[6];
		}
		if (($namedformat==0)&&(strlen($ts)<10)) $namedformat = 2;
	}
	elseif (is_string($ts))
	{
		if (preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $ts, $matches)) // Datetime
		{
			$year = $matches[1];
			$month = $matches[2];
			$day = $matches[3];
			$hour = $matches[4];
			$min = $matches[5];
			$sec = $matches[6];
		}
		elseif (preg_match('/(\d{4})-(\d{2})-(\d{2})/', $ts, $matches)) // Date
		{
			$year = $matches[1];
			$month = $matches[2];
			$day = $matches[3];
			if ($namedformat==0) $namedformat = 2;
		}
		elseif (preg_match('/(^|\s)(\d{2}):(\d{2}):(\d{2})/', $ts, $matches)) // Time
		{
			$hour = $matches[2];
			$min = $matches[3];
			$sec = $matches[4];
			if (($namedformat==0)||($namedformat==1)) $namedformat = 3;
			if ($namedformat==2) $namedformat = 4;
		}
		else
		{
			return $ts;
		}
	}
	else
	{
		return $ts;
	}
	if (!isset($year)) $year = 0; // Dummy value for times
	if (!isset($month)) $month = 1;
	if (!isset($day)) $day = 1;
	if (!isset($hour)) $hour = 0;
	if (!isset($min)) $min = 0;
	if (!isset($sec)) $sec = 0;
	$uts = @mktime($hour, $min, $sec, $month, $day, $year);
	if ($uts < 0 || $uts == FALSE || // Failed to convert
		(intval($year) == 0 && intval($month) == 0 && intval($day) == 0)) {
		$year = substr_replace("0000", $year, -1 * strlen($year));
		$month = substr_replace("00", $month, -1 * strlen($month));
		$day = substr_replace("00", $day, -1 * strlen($day));
		$hour = substr_replace("00", $hour, -1 * strlen($hour));
		$min = substr_replace("00", $min, -1 * strlen($min));
		$sec = substr_replace("00", $sec, -1 * strlen($sec));
		if (ewr_ContainsStr($EWR_DATE_FORMAT, "yyyy"))
			$DefDateFormat = str_replace("yyyy", $year, $EWR_DATE_FORMAT);
		elseif (ewr_ContainsStr($EWR_DATE_FORMAT, "yy"))
			$DefDateFormat = str_replace("yy", substr(strval($year), -2), $EWR_DATE_FORMAT);
		$DefDateFormat = str_replace("mm", $month, $DefDateFormat);
		$DefDateFormat = str_replace("dd", $day, $DefDateFormat);
		switch ($namedformat) {

			//case 0: // Default
			case 1:
				return $DefDateFormat . " " . $hour . $EWR_TIME_SEPARATOR . $min . $EWR_TIME_SEPARATOR . $sec;
				break;

			//case 2: // Default
			case 3:
				if (intval($hour) == 0) {
					if ($min == 0 && $sec == 0)
						return "12 " . $ReportLanguage->Phrase("Midnight");
					else
						return "12" . $EWR_TIME_SEPARATOR . $min . $EWR_TIME_SEPARATOR . $sec . " " . $ReportLanguage->Phrase("AM");
				} elseif (intval($hour) > 0 && intval($hour) < 12) {
					return $hour . $EWR_TIME_SEPARATOR . $min . $EWR_TIME_SEPARATOR . $sec . " " . $ReportLanguage->Phrase("AM");
				} elseif (intval($hour) == 12) {
					if ($min == 0 && $sec == 0)
						return "12 " . $ReportLanguage->Phrase("Noon");
					else
						return $hour . $EWR_TIME_SEPARATOR . $min . $EWR_TIME_SEPARATOR . $sec . " " . $ReportLanguage->Phrase("PM");
				} elseif (intval($hour) > 12 && intval($hour) <= 23) {
					return (intval($hour)-12) . $EWR_TIME_SEPARATOR . $min . $EWR_TIME_SEPARATOR . $sec . " " . $ReportLanguage->Phrase("PM");
				} else {
					return $hour . $EWR_TIME_SEPARATOR . $min . $EWR_TIME_SEPARATOR . $sec;
				}
				break;
			case 4:
				return $hour . $EWR_TIME_SEPARATOR . $min . $EWR_TIME_SEPARATOR . $sec;
				break;
			case 5:
				return $year . $EWR_DATE_SEPARATOR . $month . $EWR_DATE_SEPARATOR . $day;
				break;
			case 6:
				return $month . $EWR_DATE_SEPARATOR . $day . $EWR_DATE_SEPARATOR . $year;
				break;
			case 7:
				return $day . $EWR_DATE_SEPARATOR . $month . $EWR_DATE_SEPARATOR . $year;
				break;
			case 8:
				return $DefDateFormat . (($hour == 0 && $min == 0 && $sec == 0) ? "" : " " . $hour . $EWR_TIME_SEPARATOR . $min . $EWR_TIME_SEPARATOR . $sec);
				break;
			case 9:
				return $year . $EWR_DATE_SEPARATOR . $month . $EWR_DATE_SEPARATOR . $day . " " . $hour . $EWR_TIME_SEPARATOR . $min . $EWR_TIME_SEPARATOR . $sec;
				break;
			case 10:
				return $month . $EWR_DATE_SEPARATOR . $day . $EWR_DATE_SEPARATOR . $year . " " . $hour . $EWR_TIME_SEPARATOR . $min . $EWR_TIME_SEPARATOR . $sec;
				break;
			case 11:
				return $day . $EWR_DATE_SEPARATOR . $month . $EWR_DATE_SEPARATOR . $year . " " . $hour . $EWR_TIME_SEPARATOR . $min . $EWR_TIME_SEPARATOR . $sec;
				break;
			case 12:
				return substr($year,-2) . $EWR_DATE_SEPARATOR . $month . $EWR_DATE_SEPARATOR . $day;
				break;
			case 13:
				return $month . $EWR_DATE_SEPARATOR . $day . $EWR_DATE_SEPARATOR . substr($year,-2);
				break;
			case 14:
				return $day . $EWR_DATE_SEPARATOR . $month . $EWR_DATE_SEPARATOR . substr($year,-2);
				break;
			case 15:
				return substr($year,-2) . $EWR_DATE_SEPARATOR . $month . $EWR_DATE_SEPARATOR . $day . " " . $hour . $EWR_TIME_SEPARATOR . $min . $EWR_TIME_SEPARATOR . $sec;
				break;
			case 16:
				return $month . $EWR_DATE_SEPARATOR . $day . $EWR_DATE_SEPARATOR . substr($year,-2) . " " . $hour . $EWR_TIME_SEPARATOR . $min . $EWR_TIME_SEPARATOR . $sec;
				break;
			case 17:
				return $day . $EWR_DATE_SEPARATOR . $month . $EWR_DATE_SEPARATOR . substr($year,-2) . " " . $hour . $EWR_TIME_SEPARATOR . $min . $EWR_TIME_SEPARATOR . $sec;
				break;
			default:
				return $DefDateFormat;
				break;
		}
	} else {
		if (ewr_ContainsStr($EWR_DATE_FORMAT, "yyyy"))
			$DefDateFormat = str_replace("yyyy", $year, $EWR_DATE_FORMAT);
		elseif (ewr_ContainsStr($EWR_DATE_FORMAT, "yy"))
			$DefDateFormat = str_replace("yy", substr(strval($year), -2), $EWR_DATE_FORMAT);
		$DefDateFormat = str_replace("mm", $month, $DefDateFormat);
		$DefDateFormat = str_replace("dd", $day, $DefDateFormat);
		switch ($namedformat) {

			// case 0: // Default
			case 1:
				return strftime($DefDateFormat . " %H" . $EWR_TIME_SEPARATOR . "%M" . $EWR_TIME_SEPARATOR . "%S", $uts);
				break;

			// case 2: // Default
			case 3:
				if (intval($hour) == 0) {
					if ($min == 0 && $sec == 0)
						return "12 " . $ReportLanguage->Phrase("Midnight");
					else
						return strftime("%I" . $EWR_TIME_SEPARATOR . "%M" . $EWR_TIME_SEPARATOR . "%S", $uts) . " " . $ReportLanguage->Phrase("AM");
				} elseif (intval($hour) > 0 && intval($hour) < 12) {
					return strftime("%I" . $EWR_TIME_SEPARATOR . "%M" . $EWR_TIME_SEPARATOR . "%S", $uts) . " " . $ReportLanguage->Phrase("AM");
				} elseif (intval($hour) == 12) {
					if ($min == 0 && $sec == 0)
						return "12 " . $ReportLanguage->Phrase("Noon");
					else
						return strftime("%I" . $EWR_TIME_SEPARATOR . "%M" . $EWR_TIME_SEPARATOR . "%S", $uts) . " " . $ReportLanguage->Phrase("PM");
				} elseif (intval($hour) > 12 && intval($hour) <= 23) {
					return strftime("%I" . $EWR_TIME_SEPARATOR . "%M" . $EWR_TIME_SEPARATOR . "%S", $uts) . " " . $ReportLanguage->Phrase("PM");
				} else {
					return strftime("%I" . $EWR_TIME_SEPARATOR . "%M" . $EWR_TIME_SEPARATOR . "%S %p", $uts);
				}
				break;
			case 4:
				return strftime("%H" . $EWR_TIME_SEPARATOR . "%M" . $EWR_TIME_SEPARATOR . "%S", $uts);
				break;
			case 5:
				return strftime("%Y" . $EWR_DATE_SEPARATOR . "%m" . $EWR_DATE_SEPARATOR . "%d", $uts);
				break;
			case 6:
				return strftime("%m" . $EWR_DATE_SEPARATOR . "%d" . $EWR_DATE_SEPARATOR . "%Y", $uts);
				break;
			case 7:
				return strftime("%d" . $EWR_DATE_SEPARATOR . "%m" . $EWR_DATE_SEPARATOR . "%Y", $uts);
				break;
			case 8:
				return strftime($DefDateFormat . (($hour == 0 && $min == 0 && $sec == 0) ? "" : " %H" . $EWR_TIME_SEPARATOR . "%M" . $EWR_TIME_SEPARATOR . "%S"), $uts);
				break;
			case 9:
				return strftime("%Y" . $EWR_DATE_SEPARATOR . "%m" . $EWR_DATE_SEPARATOR . "%d %H" . $EWR_TIME_SEPARATOR . "%M" . $EWR_TIME_SEPARATOR . "%S", $uts);
				break;
			case 10:
				return strftime("%m" . $EWR_DATE_SEPARATOR . "%d" . $EWR_DATE_SEPARATOR . "%Y %H" . $EWR_TIME_SEPARATOR . "%M" . $EWR_TIME_SEPARATOR . "%S", $uts);
				break;
			case 11:
				return strftime("%d" . $EWR_DATE_SEPARATOR . "%m" . $EWR_DATE_SEPARATOR . "%Y %H" . $EWR_TIME_SEPARATOR . "%M" . $EWR_TIME_SEPARATOR . "%S", $uts);
				break;
			case 12:
				return strftime("%y" . $EWR_DATE_SEPARATOR . "%m" . $EWR_DATE_SEPARATOR . "%d", $uts);
				break;
			case 13:
				return strftime("%m" . $EWR_DATE_SEPARATOR . "%d" . $EWR_DATE_SEPARATOR . "%y", $uts);
				break;
			case 14:
				return strftime("%d" . $EWR_DATE_SEPARATOR . "%m" . $EWR_DATE_SEPARATOR . "%y", $uts);
				break;
			case 15:
				return strftime("%y" . $EWR_DATE_SEPARATOR . "%m" . $EWR_DATE_SEPARATOR . "%d %H" . $EWR_TIME_SEPARATOR . "%M" . $EWR_TIME_SEPARATOR . "%S", $uts);
				break;
			case 16:
				return strftime("%m" . $EWR_DATE_SEPARATOR . "%d" . $EWR_DATE_SEPARATOR . "%y %H" . $EWR_TIME_SEPARATOR . "%M" . $EWR_TIME_SEPARATOR . "%S", $uts);
				break;
			case 17:
				return strftime("%d" . $EWR_DATE_SEPARATOR . "%m" . $EWR_DATE_SEPARATOR . "%y %H" . $EWR_TIME_SEPARATOR . "%M" . $EWR_TIME_SEPARATOR . "%S", $uts);
				break;
			default:
				return strftime($DefDateFormat, $uts);
				break;
		}
	}
}

// FormatCurrency
// FormatCurrency(Expression[,NumDigitsAfterDecimal [,IncludeLeadingDigit [,UseParensForNegativeNumbers [,GroupDigits]]]])
// NumDigitsAfterDecimal is the numeric value indicating how many places to the right of the decimal are displayed
// -1 Use Default
// The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits arguments have the following settings:
// -1 True
// 0 False
// -2 Use Default
function ewr_FormatCurrency($amount, $NumDigitsAfterDecimal = EWR_DEFAULT_DECIMAL_PRECISION, $IncludeLeadingDigit = -2, $UseParensForNegativeNumbers = -2, $GroupDigits = -2) {
	if (!is_numeric($amount))
		return $amount;
	extract($GLOBALS["EWR_LOCALE"]);

	// Check $NumDigitsAfterDecimal
	if ($NumDigitsAfterDecimal > -1)
		$frac_digits = $NumDigitsAfterDecimal;

	// Check $UseParensForNegativeNumbers
	if ($UseParensForNegativeNumbers == -1) {
		$n_sign_posn = 0;
		if ($p_sign_posn == 0) {
			$p_sign_posn = 3;
		}
	} elseif ($UseParensForNegativeNumbers == 0) {
		if ($n_sign_posn == 0)
			$n_sign_posn = 3;
	}

	// Check $GroupDigits
	if ($GroupDigits == -1) {
	} elseif ($GroupDigits == 0) {
		$mon_thousands_sep = "";
	}

	// Start by formatting the unsigned number
	$number = number_format(abs($amount),
							$frac_digits,
							$mon_decimal_point,
							$mon_thousands_sep);

	// Check $IncludeLeadingDigit
	if ($IncludeLeadingDigit == 0) {
		if (substr($number, 0, 2) == "0.")
			$number = substr($number, 1, strlen($number)-1);
	}
	if ($amount < 0) {
		$sign = $negative_sign;

		// "extracts" the boolean value as an integer
		$n_cs_precedes = intval($n_cs_precedes == true);
		$n_sep_by_space = intval($n_sep_by_space == true);
		$key = $n_cs_precedes . $n_sep_by_space . $n_sign_posn;
	} else {
		$sign = $positive_sign;
		$p_cs_precedes = intval($p_cs_precedes == true);
		$p_sep_by_space = intval($p_sep_by_space == true);
		$key = $p_cs_precedes . $p_sep_by_space . $p_sign_posn;
	}
	$formats = array(

		// Currency symbol is after amount
		// No space between amount and sign

		'000' => '(%s' . $currency_symbol . ')',
		'001' => $sign . '%s ' . $currency_symbol,
		'002' => '%s' . $currency_symbol . $sign,
		'003' => '%s' . $sign . $currency_symbol,
		'004' => '%s' . $sign . $currency_symbol,

		// One space between amount and sign
		'010' => '(%s ' . $currency_symbol . ')',
		'011' => $sign . '%s ' . $currency_symbol,
		'012' => '%s ' . $currency_symbol . $sign,
		'013' => '%s ' . $sign . $currency_symbol,
		'014' => '%s ' . $sign . $currency_symbol,

		// Currency symbol is before amount
		// No space between amount and sign

		'100' => '(' . $currency_symbol . '%s)',
		'101' => $sign . $currency_symbol . '%s',
		'102' => $currency_symbol . '%s' . $sign,
		'103' => $sign . $currency_symbol . '%s',
		'104' => $currency_symbol . $sign . '%s',

		// One space between amount and sign
		'110' => '(' . $currency_symbol . ' %s)',
		'111' => $sign . $currency_symbol . ' %s',
		'112' => $currency_symbol . ' %s' . $sign,
		'113' => $sign . $currency_symbol . ' %s',
		'114' => $currency_symbol . ' ' . $sign . '%s');

	// Lookup the key in the above array
	return sprintf($formats[$key], $number);
}

// FormatNumber
// FormatNumber(Expression[,NumDigitsAfterDecimal [,IncludeLeadingDigit
// 	[,UseParensForNegativeNumbers [,GroupDigits]]]])
// NumDigitsAfterDecimal is the numeric value indicating how many places to the right of the decimal are displayed
// -1 Use Default
// The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits arguments have the following settings:
// -1 True
// 0 False
// -2 Use Default
function ewr_FormatNumber($amount, $NumDigitsAfterDecimal = EWR_DEFAULT_DECIMAL_PRECISION, $IncludeLeadingDigit = -2, $UseParensForNegativeNumbers = -2, $GroupDigits = -2) {
	if (!is_numeric($amount))
		return $amount;
	extract($GLOBALS["EWR_LOCALE"]);

	// Check $NumDigitsAfterDecimal
	if ($NumDigitsAfterDecimal > -1)
		$frac_digits = $NumDigitsAfterDecimal;

	// Check $UseParensForNegativeNumbers
	if ($UseParensForNegativeNumbers == -1) {
		$n_sign_posn = 0;
		if ($p_sign_posn == 0) {
			$p_sign_posn = 3;
		}
	} elseif ($UseParensForNegativeNumbers == 0) {
		if ($n_sign_posn == 0)
			$n_sign_posn = 3;
	}

	// Check $GroupDigits
	if ($GroupDigits == -1) {
	} elseif ($GroupDigits == 0) {
		$mon_thousands_sep = "";
	}

	// Start by formatting the unsigned number
	$number = number_format(abs($amount),
		$frac_digits,
		$mon_decimal_point,
		$mon_thousands_sep);

	// Check $IncludeLeadingDigit
	if ($IncludeLeadingDigit == 0) {
		if (substr($number, 0, 2) == "0.")
			$number = substr($number, 1, strlen($number)-1);
	}
	if ($amount < 0) {
		$sign = $negative_sign;
		$key = $n_sign_posn;
	} else {
		$sign = $positive_sign;
		$key = $p_sign_posn;
	}
	$formats = array(
		'0' => '(%s)',
		'1' => $sign . '%s',
		'2' => $sign . '%s',
		'3' => $sign . '%s',
		'4' => $sign . '%s');

	// Lookup the key in the above array
	return sprintf($formats[$key], $number);
}

// FormatPercent
// FormatPercent(Expression[,NumDigitsAfterDecimal [,IncludeLeadingDigit
// 	[,UseParensForNegativeNumbers [,GroupDigits]]]])
// NumDigitsAfterDecimal is the numeric value indicating how many places to the right of the decimal are displayed
// -1 Use Default
// The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits arguments have the following settings:
// -1 True
// 0 False
// -2 Use Default
function ewr_FormatPercent($amount, $NumDigitsAfterDecimal, $IncludeLeadingDigit = -2, $UseParensForNegativeNumbers = -2, $GroupDigits = -2) {
	if (!is_numeric($amount))
		return $amount;
	extract($GLOBALS["EWR_LOCALE"]);

	// Check $NumDigitsAfterDecimal
	if ($NumDigitsAfterDecimal > -1)
		$frac_digits = $NumDigitsAfterDecimal;

	// Check $UseParensForNegativeNumbers
	if ($UseParensForNegativeNumbers == -1) {
		$n_sign_posn = 0;
		if ($p_sign_posn == 0) {
			$p_sign_posn = 3;
		}
	} elseif ($UseParensForNegativeNumbers == 0) {
		if ($n_sign_posn == 0)
			$n_sign_posn = 3;
	}

	// Check $GroupDigits
	if ($GroupDigits == -1) {
	} elseif ($GroupDigits == 0) {
		$mon_thousands_sep = "";
	}

	// Start by formatting the unsigned number
	$number = number_format(abs($amount)*100,
							$frac_digits,
							$mon_decimal_point,
							$mon_thousands_sep);

	// Check $IncludeLeadingDigit
	if ($IncludeLeadingDigit == 0) {
		if (substr($number, 0, 2) == "0.")
			$number = substr($number, 1, strlen($number)-1);
	}
	if ($amount < 0) {
		$sign = $negative_sign;
		$key = $n_sign_posn;
	} else {
		$sign = $positive_sign;
		$key = $p_sign_posn;
	}
	$formats = array(
		'0' => '(%s%%)',
		'1' => $sign . '%s%%',
		'2' => $sign . '%s%%',
		'3' => $sign . '%s%%',
		'4' => $sign . '%s%%');

	// Lookup the key in the above array
	return sprintf($formats[$key], $number);
}

// Add message
function ewr_AddMessage(&$msg, $msgtoadd, $sep = "<br>") {
	if (strval($msgtoadd) <> "") {
		if (strval($msg) <> "")
			$msg .= $sep;
		$msg .= $msgtoadd;
	}
}

// Add filter
function ewr_AddFilter(&$filter, $newfilter) {
	if (trim($newfilter) == "") return;
	if (trim($filter) <> "") {
		$filter = "(" . $filter . ") AND (" . $newfilter . ")";
	} else {
		$filter = $newfilter;
	}
}

// Addjust SQL based on dbid
function ewr_AdjustSql($val, $dbid = 0) {
	$dbtype = ewr_GetConnectionType($dbid);
	if ($dbtype == "MYSQL") {
		$val = addslashes(trim($val));
	} else {
		$val = str_replace("'", "''", trim($val)); // Adjust for single quote
	}
	return $val;
}

// Build Report SQL
function ewr_BuildReportSql($sSelect, $sWhere, $sGroupBy, $sHaving, $sOrderBy, $sFilter, $sSort) {
	$sDbWhere = $sWhere;
	if ($sDbWhere <> "") $sDbWhere = "(" . $sDbWhere . ")";
	if ($sFilter <> "") {
		if ($sDbWhere <> "") $sDbWhere .= " AND ";
		$sDbWhere .= "(" . $sFilter . ")";
	}
	$sDbOrderBy = ewr_UpdateSortFields($sOrderBy, $sSort, 1);
	$sSql = $sSelect;
	if ($sDbWhere <> "") $sSql .= " WHERE " . $sDbWhere;
	if ($sGroupBy <> "") $sSql .= " GROUP BY " . $sGroupBy;
	if ($sHaving <> "") $sSql .= " HAVING " . $sHaving;
	if ($sDbOrderBy <> "") $sSql .= " ORDER BY " . $sDbOrderBy;
	return $sSql;
}

// Update sort fields
// - opt = 1, merge all sort fields
// - opt = 2, merge sOrderBy fields only
function ewr_UpdateSortFields($sOrderBy, $sSort, $opt) {
	if ($sOrderBy == "") {
		if ($opt == 1)
			return $sSort;
		else
			return "";
	} elseif ($sSort == "") {
		return $sOrderBy;
	} else {

		// Merge sort field list
		$arorderby = ewr_GetSortFields($sOrderBy);
		$cntorderby = count($arorderby);
		$arsort = ewr_GetSortFields($sSort);
		$cntsort = count($arsort);
		for ($i = 0; $i < $cntsort; $i++) {

			// Get sort field
			$sortfld = trim($arsort[$i]);
			if (strtoupper(substr($sortfld,-4)) == " ASC") {
				$sortfld = trim(substr($sortfld,0,-4));
			} elseif (strtoupper(substr($sortfld,-5)) == " DESC") {
				$sortfld = trim(substr($sortfld,0,-4));
			}
			for ($j = 0; $j < $cntorderby; $j++) {

				// Get orderby field
				$orderfld = trim($arorderby[$j]);
				if (strtoupper(substr($orderfld,-4)) == " ASC") {
					$orderfld = trim(substr($orderfld,0,-4));
				} elseif (strtoupper(substr($orderfld,-5)) == " DESC") {
					$orderfld = trim(substr($orderfld,0,-4));
				}

				// Replace field
				if ($orderfld == $sortfld) {
					$arorderby[$j] = $arsort[$i];
					break;
				}
			}

			// Append field
			if ($opt == 1) {
				if ($orderfld <> $sortfld)
					$arorderby[] = $arsort[$i];
			}
		}
		return implode(", ", $arorderby);
	}
}

// Get sort fields
function ewr_GetSortFields($flds) {
	$offset = -1;
	$fldpos = 0;
	$ar = array();
	while ($offset = strpos($flds, ",", $offset + 1)) {
		$orderfld = substr($flds, $fldpos, $offset - $fldpos);
		if (ewr_EndsText(" ASC", $orderfld) || ewr_EndsText(" DESC", $orderfld)) {
			$fldpos = $offset+1;
			$ar[] = $orderfld;
		}
	}
	$ar[] = substr($flds, $fldpos);
	return $ar;
}

// Get reverse sort
function ewr_ReverseSort($sorttype) {
	return ($sorttype == "ASC") ? "DESC" : "ASC";
}

// Construct a crosstab field name
function ewr_CrossTabField($smrytype, $smryfld, $colfld, $datetype, $val, $qc, $alias="", $dbid=0) {
	if (ewr_SameStr($val, EWR_NULL_VALUE)) {
		$wrkval = "NULL";
		$wrkqc = "";
	} elseif (ewr_SameStr($val, EWR_EMPTY_VALUE)) {
		$wrkval = "";
		$wrkqc = $qc;
	} else {
		$wrkval = $val;
		$wrkqc = $qc;
	}
	switch ($smrytype) {
	case "SUM":
		$fld = $smrytype . "(" . $smryfld . "*" . ewr_SqlDistinctFactor($colfld, $datetype, $wrkval, $wrkqc, $dbid) . ")";
		break;
	case "COUNT":
		$fld = "SUM(" . ewr_SqlDistinctFactor($colfld, $datetype, $wrkval, $wrkqc, $dbid) . ")";
		break;
	case "MIN":
	case "MAX":
		$dbtype = ewr_GetConnectionType($dbid);
		$aggwrk = ewr_SqlDistinctFactor($colfld, $datetype, $wrkval, $wrkqc, $dbid);
		$fld = $smrytype . "(IF(" . $aggwrk . "=0,NULL," . $smryfld . "))";
		if ($dbtype == "ACCESS")
			$fld = $smrytype . "(IIf(" . $aggwrk . "=0,NULL," . $smryfld . "))";
		elseif ($dbtype == "MSSQL" || $dbtype == "ORACLE")
			$fld = $smrytype . "(CASE " . $aggwrk . " WHEN 0 THEN NULL ELSE " . $smryfld . " END)";
		elseif ($dbtype == "MYSQL" || $dbtype == "POSTGRESQL")
			$fld = $smrytype . "(IF(" . $aggwrk . "=0,NULL," . $smryfld . "))";
		break;
	case "AVG":
		$sumwrk = "SUM(" . $smryfld . "*" . ewr_SqlDistinctFactor($colfld, $datetype, $wrkval, $wrkqc, $dbid) . ")";
		if ($alias != "")

//			$sumwrk .= " AS SUM_" . $alias;
			$sumwrk .= " AS " . ewr_QuotedName("sum_" . $alias, $dbid);
		$cntwrk = "SUM(" . ewr_SqlDistinctFactor($colfld, $datetype, $wrkval, $wrkqc, $dbid) . ")";
		if ($alias != "")

//			$cntwrk .= " AS CNT_" . $alias;
			$cntwrk .= " AS " . ewr_QuotedName("cnt_" . $alias, $dbid);
		return $sumwrk . ", " . $cntwrk;
	}
	if ($alias != "")
		$fld .= " AS " . ewr_QuotedName($alias, $dbid);
	return $fld;
}

// Construct SQL Distinct factor
// - ACCESS
// y: IIf(Year(FieldName)=1996,1,0)
// q: IIf(DatePart(""q"",FieldName,1,0)=1,1,0))
// m: (IIf(DatePart(""m"",FieldName,1,0)=1,1,0)))
// others: (IIf(FieldName=val,1,0)))
// - MS SQL
// y: (1-ABS(SIGN(Year(FieldName)-1996)))
// q: (1-ABS(SIGN(DatePart(q,FieldName)-1)))
// m: (1-ABS(SIGN(DatePart(m,FieldName)-1)))
// d: (CASE Convert(VarChar(10),FieldName,120) WHEN '1996-1-1' THEN 1 ELSE 0 END)
// - MySQL
// y: IF(YEAR(FieldName)=1996,1,0))
// q: IF(QUARTER(FieldName)=1,1,0))
// m: IF(MONTH(FieldName)=1,1,0))
// - PostgreSql
// y: IF(EXTRACT(YEAR FROM FieldName)=1996,1,0))
// q: IF(EXTRACT(QUARTER FROM FieldName)=1,1,0))
// m: IF(EXTRACT(MONTH FROM FieldName)=1,1,0))
function ewr_SqlDistinctFactor($sFld, $dateType, $val, $qc, $dbid = 0) {
	$dbtype = ewr_GetConnectionType($dbid);
	if ($dbtype == "ACCESS") {
		if ($dateType == "y" && is_numeric($val)) {
			return "IIf(Year(" . $sFld . ")=" . $val . ",1,0)";
		} elseif (($dateType == "q" || $dateType == "m") && is_numeric($val)) {
			return "IIf(DatePart(\"" . $dateType . "\"," . $sFld . ")=" . $val . ",1,0)";
		} else {
			if ($val == "NULL")
				return "IIf(" . $sFld . " IS NULL,1,0)";
			else
				return "IIf(" . $sFld . "=" . $qc . ewr_AdjustSql($val, $dbid) . $qc . ",1,0)";
		}
	} elseif ($dbtype == "MSSQL") {
		if ($dateType == "y" && is_numeric($val)) {
			return "(1-ABS(SIGN(Year(" . $sFld . ")-" . $val . ")))";
		} elseif (($dateType == "q" || $dateType == "m") && is_numeric($val)) {
			return "(1-ABS(SIGN(DatePart(" . $dateType . "," . $sFld . ")-" . $val . ")))";
		} elseif ($dateType == "d") {
			return "(CASE CONVERT(VARCHAR(10)," . $sFld . ",120) WHEN " . $qc . ewr_AdjustSql($val, $dbid) . $qc . " THEN 1 ELSE 0 END)";
		} elseif ($dateType == "dt") {
			return "(CASE CONVERT(VARCHAR," . $sFld . ",120) WHEN " . $qc . ewr_AdjustSql($val, $dbid) . $qc . " THEN 1 ELSE 0 END)";
		} else {
			if ($val == "NULL")
				return "(CASE WHEN " . $sFld . " IS NULL THEN 1 ELSE 0 END)";
			else
				return "(CASE " . $sFld . " WHEN " . $qc . ewr_AdjustSql($val, $dbid) . $qc . " THEN 1 ELSE 0 END)";
		}
	} elseif ($dbtype == "MYSQL") {
		if ($dateType == "y" && is_numeric($val)) {
			return "IF(YEAR(" . $sFld . ")=" . $val . ",1,0)";
		} elseif ($dateType == "q" && is_numeric($val)) {
			return "IF(QUARTER(" . $sFld . ")=" . $val . ",1,0)";
		} elseif ($dateType == "m" && is_numeric($val)) {
			return "IF(MONTH(" . $sFld . ")=" . $val . ",1,0)";
		} else {
			if ($val == "NULL") {
				return "IF(" . $sFld . " IS NULL,1,0)";
			} else {
				return "IF(" . $sFld . "=" . $qc . ewr_AdjustSql($val, $dbid) . $qc . ",1,0)";
			}
		}
	} elseif ($dbtype == "POSTGRESQL") {
		if ($dateType == "y" && is_numeric($val)) {
			return "CASE WHEN TO_CHAR(" . $sFld . ",'YYYY')='" . $val . "' THEN 1 ELSE 0 END";
		} elseif ($dateType == "q" && is_numeric($val)) {
			return "CASE WHEN TO_CHAR(" . $sFld . ",'Q')='" . $val . "' THEN 1 ELSE 0 END";
		} elseif ($dateType == "m" && is_numeric($val)) {
			return "CASE WHEN TO_CHAR(" . $sFld . ",'MM')=LPAD('" . $val . "',2,'0') THEN 1 ELSE 0 END";
		} else {
			if ($val == "NULL") {
				return "CASE WHEN " . $sFld . " IS NULL THEN 1 ELSE 0 END";
			} else {
				return "CASE WHEN " . $sFld . "=" . $qc . ewr_AdjustSql($val, $dbid) . $qc . " THEN 1 ELSE 0 END";
			}
		}
	} elseif ($dbtype == "ORACLE") {
		if ($dateType == "y" && is_numeric($val)) {
			return "DECODE(TO_CHAR(" . $sFld . ",'YYYY'),'" . $val . "',1,0)";
		} elseif ($dateType == "q" && is_numeric($val)) {
			return "DECODE(TO_CHAR(" . $sFld . ",'Q'),'" . $val . "',1,0)";
		} elseif ($dateType == "m" && is_numeric($val)) {
			return "DECODE(TO_CHAR(" . $sFld . ",'MM'),LPAD('" . $val . "',2,'0'),1,0)";
		} elseif ($dateType == "d") {
			return "DECODE(" . $sFld . ",TO_DATE(" . $qc . ewr_AdjustSql($val, $dbid) . $qc . ",'YYYY/MM/DD'),1,0)";
		} elseif ($dateType == "dt") {
			return "DECODE(" . $sFld . ",TO_DATE(" . $qc . ewr_AdjustSql($val, $dbid) . $qc . ",'YYYY/MM/DD HH24:MI:SS'),1,0)";
		} else {
			if ($val == "NULL") {
				return "(CASE WHEN " . $sFld . " IS NULL THEN 1 ELSE 0 END)";
			} else {
				return "DECODE(" . $sFld . "," . $qc . ewr_AdjustSql($val, $dbid) . $qc . ",1,0)";
			}
		}
	}
}

// Evaluate summary value
function ewr_SummaryValue($val1, $val2, $ityp) {
	switch ($ityp) {
	case "SUM":
	case "COUNT":
	case "AVG":
		if (is_null($val2) || !is_numeric($val2)) {
			return $val1;
		} else {
			return ($val1 + $val2);
		}
	case "MIN":
		if (is_null($val2) || !is_numeric($val2)) {
			return $val1; // Skip null and non-numeric
		} elseif (is_null($val1)) {
			return $val2; // Initialize for first valid value
		} elseif ($val1 < $val2) {
			return $val1;
		} else {
			return $val2;
		}
	case "MAX":
		if (is_null($val2) || !is_numeric($val2)) {
			return $val1; // Skip null and non-numeric
		} elseif (is_null($val1)) {
			return $val2; // Initialize for first valid value
		} elseif ($val1 > $val2) {
			return $val1;
		} else {
			return $val2;
		}
	}
}

// Match filter value
function ewr_MatchedFilterValue($ar, $value) {
	if (!is_array($ar)) {
		return (strval($ar) == strval($value));
	} else {
		foreach ($ar as $val) {
			if (strval($val) == strval($value))
				return TRUE;
		}
		return FALSE;
	}
}

// Render repeat column table
// - rowcnt - zero based row count
function ewr_RepeatColumnTable($totcnt, $rowcnt, $repeatcnt, $rendertype) {
	$sWrk = "";
	if ($rendertype == 1) { // Render control start
		if ($rowcnt == 0) $sWrk .= "<table class=\"" . EWR_ITEM_TABLE_CLASSNAME . "\">";
		if ($rowcnt % $repeatcnt == 0) $sWrk .= "<tr>";
		$sWrk .= "<td>";
	} elseif ($rendertype == 2) { // Render control end
		$sWrk .= "</td>";
		if ($rowcnt % $repeatcnt == $repeatcnt - 1) {
			$sWrk .= "</tr>";
		} elseif ($rowcnt == $totcnt - 1) {
			for ($i = ($rowcnt % $repeatcnt) + 1; $i < $repeatcnt; $i++) {
				$sWrk .= "<td>&nbsp;</td>";
			}
			$sWrk .= "</tr>";
		}
		if ($rowcnt == $totcnt - 1) $sWrk .= "</table>";
	}
	return $sWrk;
}

// Check if the value is selected
function ewr_IsSelectedValue(&$ar, $value, $ft) {
	if (!is_array($ar))
		return TRUE;
	$af = (substr($value, 0, 2) == "@@");
	foreach ($ar as $val) {
		if ($af || substr($val, 0, 2) == "@@") { // Advanced filters
			if ($val == $value)
				return TRUE;
		} elseif (ewr_SameStr($value, EWR_NULL_VALUE) && $value == $val) {
				return TRUE;
		} else {
			if (ewr_CompareValue($val, $value, $ft))
				return TRUE;
		}
	}
	return FALSE;
}

// Check if advanced filter value
function ewr_IsAdvancedFilterValue($v) {
	if (is_array($v) && count($v) > 0) {
		foreach ($v as $val) {
			if (substr($val,0,2) <> "@@")
				return FALSE;
		}
		return TRUE;
	} elseif (substr($v,0,2) == "@@") {
		return TRUE;
	}
	return FALSE;
}

// Set up distinct values
// - ar: array for distinct values
// - val: value
// - label: display value
// - dup: check duplicate
function ewr_SetupDistinctValues(&$ar, $val, $label, $dup, $dlm = "") {
	$isarray = is_array($ar);
	if ($dlm <> "") {
		$arval = explode($dlm, $val);
		$arlabel = explode($dlm, $label);
		if (count($arval) <> count($arlabel)) {
			$arval = array($val);
			$arlabel = array($label);
		}
	} else {
		$arval = array($val);
		$arlabel = array($label);
	}
	$cntval = count($arval);
	for ($i = 0; $i < $cntval; $i++) {
		$v = $arval[$i];
		$l = $arlabel[$i];
		if ($dup && $isarray && in_array($v, array_keys($ar)))
			continue;
		if (!$isarray) {
			$ar = array($v => $l);
		} elseif (ewr_SameStr($v, EWR_EMPTY_VALUE) || ewr_SameStr($v, EWR_NULL_VALUE)) { // Null/Empty
			$ar = array_reverse($ar, TRUE);
			$ar[$v] = $l; // Insert at top
			$ar = array_reverse($ar, TRUE);
		} else {
			$ar[$v] = $l; // Default insert at end
		}
	}
}

// Compare values based on field type
function ewr_CompareValue($v1, $v2, $ft) {
	switch ($ft) {

	// Case adBigInt, adInteger, adSmallInt, adTinyInt, adUnsignedTinyInt, adUnsignedSmallInt, adUnsignedInt, adUnsignedBigInt
	case 20:
	case 3:
	case 2:
	case 16:
	case 17:
	case 18:
	case 19:
	case 21:
		if (is_numeric($v1) && is_numeric($v2)) {
			return (intval($v1) == intval($v2));
		}
		break;

	// Case adSingle, adDouble, adNumeric, adCurrency
	case 4:
	case 5:
	case 131:
	case 6:
		if (is_numeric($v1) && is_numeric($v2)) {
			return ((float)$v1 == (float)$v2);
		}
		break;

	//	Case adDate, adDBDate, adDBTime, adDBTimeStamp
	case 7:
	case 133:
	case 134:
	case 135:
		if (is_numeric(strtotime($v1)) && is_numeric(strtotime($v2))) {
			return (strtotime($v1) == strtotime($v2));
		}
		break;
	default:
		return (strcmp($v1, $v2) == 0); // Treat as string
	}
}

// Register filter
function ewr_RegisterFilter(&$fld, $id, $name, $functionName = "") {
	if (!is_array($fld->AdvancedFilters))
		$fld->AdvancedFilters = array();
	$wrkid = (substr($id, 0, 2) == "@@") ? $id : "@@" . $id;
	$key = substr($wrkid, 2);
	$fld->AdvancedFilters[$key] = new crAdvancedFilter($wrkid, $name, $functionName);
}

// Unregister filter
function ewr_UnregisterFilter(&$fld, $id) {
	if (is_array($fld->AdvancedFilters)) {
		$wrkid = (substr($id, 0, 2) == "@@") ? $id : "@@" . $id;
		$key = substr($wrkid, 2);
		foreach ($fld->AdvancedFilters as $filter) {
			if ($filter->ID == $wrkid) {
				unset($fld->AdvancedFilters[$key]);
				break;
			}
		}
	}
}

// Return date value
function ewr_DateVal($FldOpr, $FldVal, $ValType, $dbid = 0) {

	// Compose date string
	switch (strtolower($FldOpr)) {
		case "year":
			if ($ValType == 1) {
				$wrkVal = "$FldVal-01-01";
			} elseif ($ValType == 2) {
				$wrkVal = "$FldVal-12-31";
			}
			break;
		case "quarter":
			list($y, $q) = explode("|", $FldVal);
			if (intval($y) == 0 || intval($q) == 0) {
				$wrkVal = "0000-00-00";
			} else {
				if ($ValType == 1) {
					$m = ($q - 1) * 3 + 1;
					$m = str_pad($m, 2, "0", STR_PAD_LEFT);
					$wrkVal = "$y-$m-01";
				} elseif ($ValType == 2) {
					$m = ($q - 1) * 3 + 3;
					$m = str_pad($m, 2, "0", STR_PAD_LEFT);
					$wrkVal = "$y-$m-" . ewr_DaysInMonth($y, $m);
				}
			}
			break;
		case "month":
			list($y, $m) = explode("|", $FldVal);
			if (intval($y) == 0 || intval($m) == 0) {
				$wrkVal = "0000-00-00";
			} else {
				if ($ValType == 1) {
					$m = str_pad($m, 2, "0", STR_PAD_LEFT);
					$wrkVal = "$y-$m-01";
				} elseif ($ValType == 2) {
					$m = str_pad($m, 2, "0", STR_PAD_LEFT);
					$wrkVal = "$y-$m-" . ewr_DaysInMonth($y, $m);
				}
			}
			break;
		case "day":
		default:
			$wrkVal = str_replace("|", "-", $FldVal);
	}

	// Add time if necessary
	if (preg_match('/(\d{4}|\d{2})-(\d{1,2})-(\d{1,2})/', $wrkVal)) { // Date without time
		if ($ValType == 1) {
			$wrkVal .= " 00:00:00";
		} elseif ($ValType == 2) {
			$wrkVal .= " 23:59:59";
		}
	}

	// Check if datetime
	if (preg_match('/(\d{4}|\d{2})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})/', $wrkVal)) { // DateTime
		$DateVal = $wrkVal;
	} else {
		$DateVal = "";
	}

	// Change date format if necessary
	if (ewr_GetConnectionType($dbid) <> "MYSQL")
		$DateVal = str_replace("-", "/", $DateVal);
	return $DateVal;
}

// "Past"
function ewr_IsPast($FldExpression, $dbid = 0) {
	$dt = date("Y-m-d H:i:s");
	if (ewr_GetConnectionType($dbid) <> "MYSQL")
		$dt = str_replace("-", "/", $dt);
	return ("($FldExpression < " . ewr_QuotedValue($dt, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "Future";
function ewr_IsFuture($FldExpression, $dbid = 0) {
	$dt = date("Y-m-d H:i:s");
	if (ewr_GetConnectionType($dbid) <> "MYSQL")
		$dt = str_replace("-", "/", $dt);
	return ("($FldExpression > " . ewr_QuotedValue($dt, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "Last 30 days"
function ewr_IsLast30Days($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m-d", strtotime("-29 days"));
	$dt2 = date("Y-m-d", strtotime("+1 days"));
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "Last 14 days"
function ewr_IsLast14Days($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m-d", strtotime("-13 days"));
	$dt2 = date("Y-m-d", strtotime("+1 days"));
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "Last 7 days"
function ewr_IsLast7Days($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m-d", strtotime("-6 days"));
	$dt2 = date("Y-m-d", strtotime("+1 days"));
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "Next 30 days"
function ewr_IsNext30Days($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m-d");
	$dt2 = date("Y-m-d", strtotime("+30 days"));
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "Next 14 days"
function ewr_IsNext14Days($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m-d");
	$dt2 = date("Y-m-d", strtotime("+14 days"));
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "Next 7 days"
function ewr_IsNext7Days($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m-d");
	$dt2 = date("Y-m-d", strtotime("+7 days"));
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "Yesterday"
function ewr_IsYesterday($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m-d", strtotime("-1 days"));
	$dt2 = date("Y-m-d");
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "Today"
function ewr_IsToday($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m-d");
	$dt2 = date("Y-m-d", strtotime("+1 days"));
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "Tomorrow"
function ewr_IsTomorrow($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m-d", strtotime("+1 days"));
	$dt2 = date("Y-m-d", strtotime("+2 days"));
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "Last month"
function ewr_IsLastMonth($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m", strtotime("-1 months")) . "-01";
	$dt2 = date("Y-m") . "-01";
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "This month"
function ewr_IsThisMonth($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m") . "-01";
	$dt2 = date("Y-m", strtotime("+1 months")) . "-01";
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "Next month"
function ewr_IsNextMonth($FldExpression, $dbid = 0) {
	$dt1 = date("Y-m", strtotime("+1 months")) . "-01";
	$dt2 = date("Y-m", strtotime("+2 months")) . "-01";
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "Last two weeks"
function ewr_IsLast2Weeks($FldExpression, $dbid = 0) {
	if (strtotime("this Sunday") == strtotime("today")) {
		$dt1 = date("Y-m-d", strtotime("-14 days this Sunday"));
		$dt2 = date("Y-m-d", strtotime("this Sunday"));
	} else {
		$dt1 = date("Y-m-d", strtotime("-14 days last Sunday"));
		$dt2 = date("Y-m-d", strtotime("last Sunday"));
	}
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "Last week"
function ewr_IsLastWeek($FldExpression, $dbid = 0) {
	if (strtotime("this Sunday") == strtotime("today")) {
		$dt1 = date("Y-m-d", strtotime("-7 days this Sunday"));
		$dt2 = date("Y-m-d", strtotime("this Sunday"));
	} else {
		$dt1 = date("Y-m-d", strtotime("-7 days last Sunday"));
		$dt2 = date("Y-m-d", strtotime("last Sunday"));
	}
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "This week"
function ewr_IsThisWeek($FldExpression, $dbid = 0) {
	if (strtotime("this Sunday") == strtotime("today")) {
		$dt1 = date("Y-m-d", strtotime("this Sunday"));
		$dt2 = date("Y-m-d", strtotime("+7 days this Sunday"));
	} else {
		$dt1 = date("Y-m-d", strtotime("last Sunday"));
		$dt2 = date("Y-m-d", strtotime("+7 days last Sunday"));
	}
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "Next week"
function ewr_IsNextWeek($FldExpression, $dbid = 0) {
	if (strtotime("this Sunday") == strtotime("today")) {
		$dt1 = date("Y-m-d", strtotime("+7 days this Sunday"));
		$dt2 = date("Y-m-d", strtotime("+14 days this Sunday"));
	} else {
		$dt1 = date("Y-m-d", strtotime("+7 days last Sunday"));
		$dt2 = date("Y-m-d", strtotime("+14 days last Sunday"));
	}
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "Next two week"
function ewr_IsNext2Weeks($FldExpression, $dbid = 0) {
	if (strtotime("this Sunday") == strtotime("today")) {
		$dt1 = date("Y-m-d", strtotime("+7 days this Sunday"));
		$dt2 = date("Y-m-d", strtotime("+21 days this Sunday"));
	} else {
		$dt1 = date("Y-m-d", strtotime("+7 days last Sunday"));
		$dt2 = date("Y-m-d", strtotime("+21 days last Sunday"));
	}
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "Last year"
function ewr_IsLastYear($FldExpression, $dbid = 0) {
	$dt1 = date("Y", strtotime("-1 years")) . "-01-01";
	$dt2 = date("Y") . "-01-01";
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "This year"
function ewr_IsThisYear($FldExpression, $dbid = 0) {
	$dt1 = date("Y") . "-01-01";
	$dt2 = date("Y", strtotime("+1 years")) . "-01-01";
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// "Next year"
function ewr_IsNextYear($FldExpression, $dbid = 0) {
	$dt1 = date("Y", strtotime("+1 years")) . "-01-01";
	$dt2 = date("Y", strtotime("+2 years")) . "-01-01";
	if (ewr_GetConnectionType($dbid) <> "MYSQL") {
		$dt1 = str_replace("-", "/", $dt1);
		$dt2 = str_replace("-", "/", $dt2);
	}
	return ("($FldExpression >= " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, $dbid) . " AND $FldExpression < " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, $dbid) . ")");
}

// Days in month
function ewr_DaysInMonth($y, $m) {
	if (in_array($m, array(1, 3, 5, 7, 8, 10, 12))) {
		return 31;
	} elseif (in_array($m, array(4, 6, 9, 11))) {
		return 30;
	} elseif ($m == 2) {
		return ($y % 4 == 0) ? 29 : 28;
	}
	return 0;
}

// Function to calculate date difference
function ewr_DateDiff($dateTimeBegin, $dateTimeEnd, $interval = "d") {
	$dateTimeBegin = strtotime($dateTimeBegin);
	if ($dateTimeBegin === -1 || $dateTimeBegin === FALSE)
		return FALSE;
	$dateTimeEnd = strtotime($dateTimeEnd);
	if($dateTimeEnd === -1 || $dateTimeEnd === FALSE)
		return FALSE;
	$dif = $dateTimeEnd - $dateTimeBegin;	
	$arBegin = getdate($dateTimeBegin);
	$dateBegin = mktime(0, 0, 0, $arBegin["mon"], $arBegin["mday"], $arBegin["year"]);
	$arEnd = getdate($dateTimeEnd);
	$dateEnd = mktime(0, 0, 0, $arEnd["mon"], $arEnd["mday"], $arEnd["year"]);
	$difDate = $dateEnd - $dateBegin;
	switch ($interval) {
		case "s": // Seconds
			return $dif;
		case "n": // Minutes
			return ($dif > 0) ? floor($dif/60) : ceil($dif/60);
		case "h": // Hours
			return ($dif > 0) ? floor($dif/3600) : ceil($dif/3600);
		case "d": // Days
			return ($difDate > 0) ? floor($difDate/86400) : ceil($difDate/86400);
		case "w": // Weeks
			return ($difDate > 0) ? floor($difDate/604800) : ceil($difDate/604800);
		case "ww": // Calendar Weeks
			$difWeek = (($dateEnd - $arEnd["wday"]*86400) - ($dateBegin - $arBegin["wday"]*86400))/604800;
			return ($difWeek > 0) ? floor($difWeek) : ceil($difWeek);
		case "m": // Months
			return (($arEnd["year"]*12 + $arEnd["mon"]) -	($arBegin["year"]*12 + $arBegin["mon"]));
		case "yyyy": // Years
			return ($arEnd["year"] - $arBegin["year"]);
	}
}

// Set up distinct values from ext. filter
function ewr_SetupDistinctValuesFromFilter(&$ar, $af) {
	if (is_array($af)) {
		foreach ($af as $filter) {
			if ($filter->Enabled)
				ewr_SetupDistinctValues($ar, $filter->ID, $filter->Name, FALSE);
		}
	}
}

// Get group value
// - Get the group value based on field type, group type and interval
// - ft: field type
// * 1: numeric, 2: date, 3: string
// - gt: group type
// * numeric: i = interval, n = normal
// * date: d = Day, w = Week, m = Month, q = Quarter, y = Year
// * string: f = first nth character, n = normal
// - intv: interval
function ewr_GroupValue(&$fld, $val) {
	$ft = $fld->FldType;
	$grp = $fld->FldGroupByType;
	$intv = $fld->FldGroupInt;
	switch ($ft) {

	// Case adBigInt, adInteger, adSmallInt, adTinyInt, adSingle, adDouble, adNumeric, adCurrency, adUnsignedTinyInt, adUnsignedSmallInt, adUnsignedInt, adUnsignedBigInt (numeric)
	case 20:
	case 3:
	case 2:
	case 16:
	case 4:
	case 5:
	case 131:
	case 6:
	case 17:
	case 18:
	case 19:
	case 21:
		if (!is_numeric($val)) return $val;	
		$wrkIntv = intval($intv);
		if ($wrkIntv <= 0) $wrkIntv = 10;
		switch ($grp) {
			case "i":
				return intval($val/$wrkIntv);
			default:
				return $val;
		}

	// Case adDate, adDBDate, adDBTime, adDBTimeStamp (date)
//	case 7:
//	case 133:
//	case 134:
//	case 135:
	// Case adLongVarChar, adLongVarWChar, adChar, adWChar, adVarChar, adVarWChar (string)

	case 201: // String
	case 203:
	case 129:
	case 130:
	case 200:
	case 202:
		$wrkIntv = intval($intv);
		if ($wrkIntv <= 0) $wrkIntv = 1;
		switch ($grp) {
			case "f":
				return substr($val, 0, $wrkIntv);
			default:
				return $val;
		}
	default:
		return $val; // Ignore
	}
}

// Display group value
function ewr_DisplayGroupValue(&$fld, $val) {
	global $ReportLanguage;
	$ft = $fld->FldType;
	$grp = $fld->FldGroupByType;
	$intv = $fld->FldGroupInt;
	if (is_null($val)) return $ReportLanguage->Phrase("NullLabel");
	if ($val == "") return $ReportLanguage->Phrase("EmptyLabel");
	switch ($ft) {

	// Case adBigInt, adInteger, adSmallInt, adTinyInt, adSingle, adDouble, adNumeric, adCurrency, adUnsignedTinyInt, adUnsignedSmallInt, adUnsignedInt, adUnsignedBigInt (numeric)
	case 20:
	case 3:
	case 2:
	case 16:
	case 4:
	case 5:
	case 131:
	case 6:
	case 17:
	case 18:
	case 19:
	case 21:
		$wrkIntv = intval($intv);
		if ($wrkIntv <= 0) $wrkIntv = 10;
		switch ($grp) {
			case "i":
				return strval($val*$wrkIntv) . " - " . strval(($val+1)*$wrkIntv-1);
			default:
				return $val;
		}
		break;

	// Case adDate, adDBDate, adDBTime, adDBTimeStamp (date)
	case 7:
	case 133:
	case 134:
	case 135:
		$ar = explode("|", $val);
		switch ($grp) {
			Case "y":
				return $ar[0];
			Case "q":
				if (count($ar) < 2) return $val;
				return ewr_FormatQuarter($ar[0], $ar[1]);
			Case "m":
				if (count($ar) < 2) return $val;
				return ewr_FormatMonth($ar[0], $ar[1]);
			Case "w":
				if (count($ar) < 2) return $val;
				return ewr_FormatWeek($ar[0], $ar[1]);
			Case "d":
				if (count($ar) < 3) return $val;
				return ewr_FormatDay($ar[0], $ar[1], $ar[2]);
			Case "h":
				return ewr_FormatHour($ar[0]);
			Case "min":
				return ewr_FormatMinute($ar[0]);
			default:
				return $val;
		}
		break;
	default: // String and others
		return $val; // Ignore
	}
}

function ewr_FormatQuarter($y, $q) {
	return "Q" . $q . "/" . $y;
}

function ewr_FormatMonth($y, $m) {
	return $m . "/" . $y;
}

function ewr_FormatWeek($y, $w) {
	return "WK" . $w . "/" . $y;
}

function ewr_FormatDay($y, $m, $d) {
	return $y . "-" . $m . "-" . $d;
}

function ewr_FormatHour($h) {
	if (intval($h) == 0) {
		return "12 AM";
	} elseif (intval($h) < 12) {
		return $h . " AM";
	} elseif (intval($h) == 12) {
		return "12 PM";
	} else {
		return ($h-12) . " PM";
	}
}

function ewr_FormatMinute($n) {
	return $n . " MIN";
}

// Get JavaScript db in the form of:
// [{k:"key1",v:"value1",s:selected1}, {k:"key2",v:"value2",s:selected2}, ...]
function ewr_GetJsDb(&$fld, $ft) {
	$jsdb = "";
	$arv = $fld->ValueList;
	$ars = $fld->SelectionList;
	if (is_array($arv)) {
		foreach ($arv as $key => $value) {
			$jsselect = (ewr_IsSelectedValue($ars, $key, $ft)) ? "true" : "false";
			if ($jsdb <> "") $jsdb .= ",";
			$jsdb .= "{\"k\":\"" . ewr_EscapeJs($key) . "\",\"v\":\"" . ewr_EscapeJs($value) . "\",\"s\":$jsselect}";
		}
	}
	$jsdb = "[" . $jsdb . "]";
	return $jsdb;
}

// Return detail filter SQL
function ewr_DetailFilterSql(&$fld, $fn, $val, $dbid=0) {
	$ft = $fld->FldDataType;
	if ($fld->FldGroupSql <> "") $ft = EWR_DATATYPE_STRING;
	$sqlwrk = $fn;
	if (is_null($val)) {
		$sqlwrk .= " IS NULL";
	} else {
		$sqlwrk .= " = " . ewr_QuotedValue($val, $ft, $dbid);
	}
	return $sqlwrk;
}

// Return popup filter SQL
function ewr_FilterSql(&$fld, $fn, $ft, $dbid = 0) {
	$ar = $fld->SelectionList;
	$af = $fld->AdvancedFilters;
	$gt = $fld->FldGroupByType;
	$gi = $fld->FldGroupInt;
	$sql = $fld->FldGroupSql;
	$dlm = $fld->FldDelimiter;
	if (!is_array($ar)) {
		return TRUE;
	} else {
		$sqlwrk = "";
		$i = 0;
		foreach ($ar as $value) {
			if (ewr_SameStr($value, EWR_EMPTY_VALUE)) { // Empty string
				$sqlwrk .= "$fn = '' OR ";
			} elseif (ewr_SameStr($value, EWR_NULL_VALUE)) { // Null value
				$sqlwrk .= "$fn IS NULL OR ";
			} elseif (substr($value, 0, 2) == "@@") { // Advanced filter
				if (is_array($af)) {
					$afsql = ewr_AdvancedFilterSql($af, $fn, $value, $dbid); // Process popup filter
					if (!is_null($afsql))
						$sqlwrk .= $afsql . " OR ";
				}
			} elseif ($dlm <> "") {
				$sql = ewr_GetMultiSearchSql($fn, trim($value), $dbid);
				if ($sql <> "")
					$sqlwrk .= $sql . " OR ";
			} elseif ($sql <> "") {
				$sqlwrk .= str_replace("%s", $fn, $sql) . " = '" . $value . "' OR ";
			} else {
				$sqlwrk .= "$fn IN (" . ewr_JoinArray($ar, ", ", $ft, $i, $dbid) . ") OR ";
				break;
			}
			$i++;
		}
	}
	if ($sqlwrk != "")
		$sqlwrk = "(" . substr($sqlwrk, 0, -4) . ")";
	return $sqlwrk;
}

// Cast date/time field for LIKE
function ewr_CastDateFieldForLike($fld, $namedformat, $dbid = 0) {
	global $EWR_DATE_SEPARATOR, $EWR_TIME_SEPARATOR, $EWR_DATE_FORMAT, $EWR_DATE_FORMAT_ID;
	$dbtype = ewr_GetConnectionType($dbid);
	$isDateTime = FALSE; // Date/Time
	if ($namedformat == 0 || $namedformat == 1 || $namedformat == 2 || $namedformat == 8) {
		$isDateTime = ($namedformat == 1 || $namedformat == 8);
		$namedformat = $EWR_DATE_FORMAT_ID;
	}
	$shortYear = ($namedformat >= 12 && $namedformat <= 17);
	$isDateTime = $isDateTime || in_array($namedformat, array(9, 10, 11, 15, 16, 17));
	$dateFormat = "";
	switch ($namedformat) {
		case 3:
			if ($dbtype == "MYSQL") {
				$dateFormat = "%h" . $EWR_TIME_SEPARATOR . "%i" . $EWR_TIME_SEPARATOR . "%s %p";
			} else if ($dbtype == "ACCESS") {
				$dateFormat = "hh" . $EWR_TIME_SEPARATOR . "nn" . $EWR_TIME_SEPARATOR . "ss AM/PM";
			} else if ($dbtype == "MSSQL") {
				$dateFormat = "REPLACE(LTRIM(RIGHT(CONVERT(VARCHAR(19), %s, 0), 7)), ':', '" . $EWR_TIME_SEPARATOR . "')"; // Use hh:miAM (or PM) only or SQL too lengthy
			} else if ($dbtype == "ORACLE") {
				$dateFormat = "HH" . $EWR_TIME_SEPARATOR . "MI" . $EWR_TIME_SEPARATOR . "SS AM";
			}
			break;
		case 4:
			if ($dbtype == "MYSQL") {
				$dateFormat = "%H" . $EWR_TIME_SEPARATOR . "%i" . $EWR_TIME_SEPARATOR . "%s";
			} else if ($dbtype == "ACCESS") {
				$dateFormat = "hh" . $EWR_TIME_SEPARATOR . "nn" . $EWR_TIME_SEPARATOR . "ss";
			} else if ($dbtype == "MSSQL") {
				$dateFormat = "REPLACE(CONVERT(VARCHAR(8), %s, 108), ':', '" . $EWR_TIME_SEPARATOR . "')";
			} else if ($dbtype == "ORACLE") {
				$dateFormat = "HH24" . $EWR_TIME_SEPARATOR . "MI" . $EWR_TIME_SEPARATOR . "SS";
			}
			break;
		case 5: case 9: case 12: case 15:
			if ($dbtype == "MYSQL") {
				$dateFormat = ($shortYear ? "%y" : "%Y") . $EWR_DATE_SEPARATOR . "%m" . $EWR_DATE_SEPARATOR . "%d";
				if ($isDateTime) $dateFormat .= " %H" . $EWR_TIME_SEPARATOR . "%i" . $EWR_TIME_SEPARATOR . "%s";
			} else if ($dbtype == "ACCESS") {
				$dateFormat = ($shortYear ? "yy" : "yyyy") . $EWR_DATE_SEPARATOR . "mm" . $EWR_DATE_SEPARATOR . "dd";
				if ($isDateTime) $dateFormat .= " hh" . $EWR_TIME_SEPARATOR . "nn" . $EWR_TIME_SEPARATOR . "ss";
			} else if ($dbtype == "MSSQL") {
				$dateFormat = "REPLACE(" . ($shortYear ? "CONVERT(VARCHAR(8), %s, 2)" : "CONVERT(VARCHAR(10), %s, 102)") . ", '.', '" . $EWR_DATE_SEPARATOR . "')";
				if ($isDateTime) $dateFormat = "(" . $dateFormat . " + ' ' + REPLACE(CONVERT(VARCHAR(8), %s, 108), ':', '" . $EWR_TIME_SEPARATOR . "'))";
			} else if ($dbtype == "ORACLE") {
				$dateFormat = ($shortYear ? "YY" : "YYYY") . $EWR_DATE_SEPARATOR . "MM" . $EWR_DATE_SEPARATOR . "DD";
				if ($isDateTime) $dateFormat .= " HH24" . $EWR_TIME_SEPARATOR . "MI" . $EWR_TIME_SEPARATOR . "SS";
			}
			break;
		case 6: case 10: case 13: case 16:
			if ($dbtype == "MYSQL") {
				$dateFormat = "%m" . $EWR_DATE_SEPARATOR . "%d" . $EWR_DATE_SEPARATOR . ($shortYear ? "%y" : "%Y");
				if ($isDateTime) $dateFormat .= " %H" . $EWR_TIME_SEPARATOR . "%i" . $EWR_TIME_SEPARATOR . "%s";
			} else if ($dbtype == "ACCESS") {
				$dateFormat = "mm" . $EWR_DATE_SEPARATOR . "dd" . $EWR_DATE_SEPARATOR . ($shortYear ? "yy" : "yyyy");
				if ($isDateTime) $dateFormat .= " hh" . $EWR_TIME_SEPARATOR . "nn" . $EWR_TIME_SEPARATOR . "ss";
			} else if ($dbtype == "MSSQL") {
				$dateFormat = "REPLACE(" . ($shortYear ? "CONVERT(VARCHAR(8), %s, 1)" : "CONVERT(VARCHAR(10), %s, 101)") . ", '/', '" . $EWR_DATE_SEPARATOR . "')";
				if ($isDateTime) $dateFormat = "(" . $dateFormat . " + ' ' + REPLACE(CONVERT(VARCHAR(8), %s, 108), ':', '" . $EWR_TIME_SEPARATOR . "'))";
			} else if ($dbtype == "ORACLE") {
				$dateFormat = "MM" . $EWR_DATE_SEPARATOR . "DD" . $EWR_DATE_SEPARATOR . ($shortYear ? "YY" : "YYYY");
				if ($isDateTime) $dateFormat .= " HH24" . $EWR_TIME_SEPARATOR . "MI" . $EWR_TIME_SEPARATOR . "SS";
			}
			break;
		case 7: case 11: case 14: case 17:
			if ($dbtype == "MYSQL") {
				$dateFormat = "%d" . $EWR_DATE_SEPARATOR . "%m" . $EWR_DATE_SEPARATOR . ($shortYear ? "%y" : "%Y");
				if ($isDateTime) $dateFormat .= " %H" . $EWR_TIME_SEPARATOR . "%i" . $EWR_TIME_SEPARATOR . "%s";
			} else if ($dbtype == "ACCESS") {
				$dateFormat = "dd" . $EWR_DATE_SEPARATOR . "mm" . $EWR_DATE_SEPARATOR . ($shortYear ? "yy" : "yyyy");
				if ($isDateTime) $dateFormat .= " hh" . $EWR_TIME_SEPARATOR . "nn" . $EWR_TIME_SEPARATOR . "ss";
			} else if ($dbtype == "MSSQL") {
				$dateFormat = "REPLACE(" . ($shortYear ? "CONVERT(VARCHAR(8), %s, 3)" : "CONVERT(VARCHAR(10), %s, 103)") . ", '/', '" . $EWR_DATE_SEPARATOR . "')";
				if ($isDateTime) $dateFormat = "(" . $dateFormat . " + ' ' + REPLACE(CONVERT(VARCHAR(8), %s, 108), ':', '" . $EWR_TIME_SEPARATOR . "'))";
			} else if ($dbtype == "ORACLE") {
				$dateFormat = "DD" . $EWR_DATE_SEPARATOR . "MM" . $EWR_DATE_SEPARATOR . ($shortYear ? "YY" : "YYYY");
				if ($isDateTime) $dateFormat .= " HH24" . $EWR_TIME_SEPARATOR . "MI" . $EWR_TIME_SEPARATOR . "SS";
			}
			break;
	}
	if ($dateFormat) {
		if ($dbtype == "MYSQL") {
			return "DATE_FORMAT(" . $fld . ", '" . $dateFormat . "')";
		} else if ($dbtype == "ACCESS") {
			return "FORMAT(" . $fld . ", '" . $dateFormat . "')";
		} else if ($dbtype == "MSSQL") {
			return str_replace("%s", $fld, $dateFormat);
		} else if ($dbtype == "ORACLE") {
			return "TO_CHAR(" . $fld . ", '" . $dateFormat . "')";
		}
	}
	return $fld;
}

// Return multi-value search SQL
function ewr_GetMultiSearchSql($fn, $val, $dbid) {
	if (ewr_SameStr($val, EWR_INIT_VALUE) || ewr_SameStr($val, EWR_ALL_VALUE)) {
		$sSql = "";
	} elseif (ewr_GetConnectionType($dbid) == "MYSQL") {
		$sSql = "FIND_IN_SET('" . ewr_AdjustSql($val, $dbid) . "', " . $fn . ")";
	} else {
		$sSql = $fn . " = '" . ewr_AdjustSql($val, $dbid) . "' OR " . ewr_GetMultiSearchSqlPart($fn, $val, $dbid);
	}
	return $sSql;
}

// Get multi search SQL part
function ewr_GetMultiSearchSqlPart($fn, $val, $dbid) {
	global $EWR_CSV_DELIMITER;
	return $fn . ewr_Like("'" . ewr_AdjustSql($val, $dbid) . $EWR_CSV_DELIMITER . "%'", $dbid) . " OR " .
		$fn . ewr_Like("'%" . $EWR_CSV_DELIMITER . ewr_AdjustSql($val, $dbid) . $EWR_CSV_DELIMITER . "%'", $dbid) . " OR " .
		$fn . ewr_Like("'%" . $EWR_CSV_DELIMITER . ewr_AdjustSql($val, $dbid) . "'", $dbid);
}

// Return Advanced Filter SQL
function ewr_AdvancedFilterSql(&$af, $fn, $val, $dbid = 0) {
	if (!is_array($af)) {
		return NULL;
	} elseif (is_null($val)) {
		return NULL;
	} else {
		foreach ($af as $filter) {
			if (ewr_SameStr($val, $filter->ID) && $filter->Enabled) {
				$func = $filter->FunctionName;
				return $func($fn, $dbid);
			}
		}
		return NULL;
	}
}

// Truncate Memo Field based on specified length, string truncated to nearest space or CrLf
function ewr_TruncateMemo($memostr, $ln, $removehtml) {
	$str = ($removehtml) ? ewr_RemoveHtml($memostr) : $memostr;
	if (strlen($str) > 0 && strlen($str) > $ln) {
		$k = 0;
		while ($k >= 0 && $k < strlen($str)) {
			$i = strpos($str, " ", $k);
			$j = strpos($str, chr(10), $k);
			if ($i === FALSE && $j === FALSE) { // Not able to truncate
				return $str;
			} else {

				// Get nearest space or CrLf
				if ($i > 0 && $j > 0) {
					if ($i < $j) {
						$k = $i;
					} else {
						$k = $j;
					}
				} elseif ($i > 0) {
					$k = $i;
				} elseif ($j > 0) {
					$k = $j;
				}

				// Get truncated text
				if ($k >= $ln) {
					return substr($str, 0, $k) . "...";
				} else {
					$k++;
				}
			}
		}
	} else {
		return $str;
	}
}

// Remove HTML tags from text
function ewr_RemoveHtml($str) {
	return preg_replace('/<[^>]*>/', '', strval($str));
}

// Escape string for JavaScript
function ewr_EscapeJs($str) {
	$str = strval($str);
	$str = str_replace("\\", "\\\\", $str);
	$str = str_replace("\"", "\\\"", $str);
	$str = str_replace("\t", "\\t", $str);
	$str = str_replace("\r", "\\r", $str);
	$str = str_replace("\n", "\\n", $str);
	return $str;
}

// Compare values by custom sequence
function ewr_CompareValueCustom($v1, $v2, $seq) {
	if ($seq == "_number") { // Number
		if (is_numeric($v1) && is_numeric($v2)) {
			return ((float)$v1 > (float)$v2);
		}
	} else if ($seq == "_date") { // Date
		if (is_numeric(strtotime($v1)) && is_numeric(strtotime($v2))) {
			return (strtotime($v1) > strtotime($v2));
		}
	} else if ($seq <> "") { // Custom sequence
		if (is_array($seq))
			$ar = $seq;
		else
			$ar = explode(",", $seq);
		if (in_array($v1, $ar) && in_array($v2, $ar))
			return (array_search($v1, $ar) > array_search($v2, $ar));
		else
			return in_array($v2, $ar);
	}
	return ($v1 > $v2);
}

// Load array from sql
function ewr_LoadArrayFromSql($sql, &$ar) {
	global $conn;
	if (strval($sql) == "")
		return;
	$rswrk = $conn->Execute($sql);
	if ($rswrk) {
		while (!$rswrk->EOF) {
			$v = $rswrk->fields[0];
			if (is_null($v)) {
				$v = EWR_NULL_VALUE;
			} elseif ($v == "") {
				$v = EWR_EMPTY_VALUE;
			}
			if (!is_array($ar))
				$ar = array();
			$ar[] = $v;
			$rswrk->MoveNext();
		}
		$rswrk->Close();
	}
}

// Function to Match array
function ewr_MatchedArray(&$ar1, &$ar2) {
	if (!is_array($ar1) && !is_array($ar2)) {
		return TRUE;
	} elseif (is_array($ar1) && is_array($ar2)) {
		return (count(array_diff($ar1, $ar2)) == 0);
	}
	return FALSE;
}

// Write a value to file for debug
function ewr_Trace($msg) {
	$filename = "debug.txt";
	if (!$handle = fopen($filename, 'a')) exit;
	if (is_writable($filename)) fwrite($handle, $msg . "\n");
	fclose($handle);
}

// Connection/Query error handler
function ewr_ErrorFn($DbType, $ErrorType, $ErrorNo, $ErrorMsg, $Param1, $Param2, $Object) {
	if ($ErrorType == 'CONNECT') {
		if ($DbType == "ado_access" || $DbType == "ado_mssql") {
			$msg = "Failed to connect to database. Error: " . $ErrorMsg;
		} else {
			$msg = "Failed to connect to $Param2 at $Param1. Error: " . $ErrorMsg;
		}
	} elseif ($ErrorType == 'EXECUTE') {
		if (EWR_DEBUG_ENABLED) {
			$msg = "Failed to execute SQL: $Param1. Error: " . $ErrorMsg;
		} else {
			$msg = "Failed to execute SQL. Error: " . $ErrorMsg;
		}
	} 
	ewr_AddMessage($_SESSION[EWR_SESSION_FAILURE_MESSAGE], $msg);
}

// Write HTTP header
function ewr_Header($cache, $charset = EWR_CHARSET, $json = FALSE) {
	$export = @$_GET["export"];
	if ($cache || ewr_IsHttps() && $export <> "" && $export <> "print") { // Allow cache
		header("Cache-Control: private, must-revalidate");
		header("Pragma: public");
	} else { // No cache
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
		header("Cache-Control: private, no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}
	header("X-UA-Compatible: IE=edge");
	$ct = "text/html";
	if ($charset <> "")
		$ct .= "; charset=" . $charset;
	if ($json)
		$ct = "application/json; charset=utf-8";
	header("Content-Type: " . $ct); // Charset
}

// Get content file extension
function ewr_ContentExt($data) {
	$ct = ewr_ContentType(substr($data, 0, 11));
	switch ($ct) {
	case "image/gif": return ".gif"; // Return gif
	case "image/jpeg": return ".jpg"; // Return jpg
	case "image/png": return ".png"; // Return png
	case "image/bmp": return ".bmp"; // Return bmp
	case "application/pdf": return ".pdf"; // Return pdf
	default: return ""; // Unknown extension
	}
}

// Get content type
function ewr_ContentType($data, $fn = "") {
	if (substr($data, 0, 6) == "\x47\x49\x46\x38\x37\x61" || substr($data, 0, 6) == "\x47\x49\x46\x38\x39\x61") { // Check if gif
		return "image/gif";

	//} elseif (substr($data, 0, 4) == "\xFF\xD8\xFF\xE0" && substr($data, 6, 5) == "\x4A\x46\x49\x46\x00") { // Check if jpg
	} elseif (substr($data, 0, 2) == "\xFF\xD8") { // Check if jpg (SOI marker \xFF\xD8)
		return "image/jpeg";
	} elseif (substr($data, 0, 8) == "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A") { // Check if png
		return "image/png";
	} elseif (substr($data, 0, 2) == "\x42\x4D") { // Check if bmp
		return "image/bmp";
	} elseif (substr($data, 0, 4) == "\x25\x50\x44\x46") { // Check if pdf
		return "application/pdf";
	} elseif ($fn <> "") { // Use file extension to get mime type
		$extension = strtolower(substr(strrchr($fn, "."), 1));
		$ct = @$EWR_MIME_TYPES[$extension];
		if ($ct == "") {
			if (file_exists($fn) && function_exists("finfo_file")) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$ct = finfo_file($finfo, $fn);
				finfo_close($finfo);
			} elseif (function_exists("mime_content_type")) {
				$ct = mime_content_type($fn);
			}
		}
		return $ct;
	} else {
		return "images";
	}
}

// Get connection object
function &ReportConn($dbid = 0) {
	$db = &ReportDb($dbid);
	if ($db && is_null($db["conn"]))
		ewr_ConnectDb($db);
	if ($db)
		$conn = &$db["conn"];
	else
		$conn = FALSE;
	return $conn;
}

// Get connection object
if (!function_exists("Conn")) {

	function &Conn($dbid = 0) {
		return ReportConn($dbid);
	}
}

// Get database object
function &ReportDb($dbid = 0) {
	global $EWR_CONN;
	if (ewr_EmptyStr($dbid))
		$dbid = 0;
	if (array_key_exists($dbid, $EWR_CONN))
		$db = &$EWR_CONN[$dbid];
	else
		$db = FALSE;
	return $db;
}

// Get connection type
function ewr_GetConnectionType($dbid = 0) {
	$db = ReportDb($dbid);
	if ($db) {
		return $db["type"];
	} elseif (ewr_SameText($dbid, "MYSQL")) {
		return "MYSQL";
	} elseif (ewr_SameText($dbid, "POSTGRESQL")) {
		return "POSTGRESQL";
	} elseif (ewr_SameText($dbid, "ORACLE")) {
		return "ORACLE";
	} elseif (ewr_SameText($dbid, "ACCESS")) {
		return "ACCESS";
	} elseif (ewr_SameText($dbid, "MSSQL")) {
		return "MSSQL";
	}
	return FALSE;
}

// Connect to database
function &ewr_Connect($dbid = 0) {
	return ReportConn($dbid);
}

// Connect to database
function ewr_ConnectDb(&$info) {
	global $EWR_DATE_FORMAT;
	$GLOBALS["ADODB_FETCH_MODE"] = ADODB_FETCH_BOTH;
	$GLOBALS["ADODB_COUNTRECS"] = FALSE;

	// Database connecting event
	Database_Connecting($info);
	$dbid = @$info["id"];
	$dbtype = @$info["type"];
	if ((!EW_USE_MSSQL_NATIVE && $dbtype == "MSSQL" || $dbtype == "ACCESS") && !class_exists("COM"))
		die("<strong>PHP COM extension required for database type '" . $dbtype . "' is not installed on this server.</strong> Note that Windows server is required for database type '" . $dbtype . "' and as of PHP 5.3.15/5.4.5, the COM extension requires php_com_dotnet.dll to be enabled in php.ini. See <a href='http://php.net/manual/en/com.installation.php'>http://php.net/manual/en/com.installation.php</a> for details.");
	if ($dbtype == "MYSQL") {
		if (EW_USE_ADODB) {
			if (EW_USE_MYSQLI)
				$conn = ADONewConnection('mysqli');
			else
				$conn = ADONewConnection('mysqlt');
		} else {
			$conn = new mysqlt_driver_ADOConnection();
		}
	} elseif ($dbtype == "POSTGRESQL") {
		$conn = ADONewConnection('postgres7');
	} elseif ($dbtype == "MSSQL") {
		if (EW_USE_MSSQL_NATIVE) {
			$conn = ADONewConnection('mssqlnative');
			$conn->connectionInfo = array("ReturnDatesAsStrings" => TRUE);
			if (strtolower(EWR_CHARSET) == "utf-8")
				$conn->connectionInfo["CharacterSet"] = "UTF-8";
			if (is_array(@$info["connectionInfo"]))
				$conn->connectionInfo = array_merge($conn->connectionInfo, $info["connectionInfo"]);
		} else {
			$conn = ADONewConnection('ado_mssql');
		}
	} elseif ($dbtype == "ACCESS") {
		$conn = ADONewConnection('ado_access');
	} elseif ($dbtype == "ORACLE") {
		$conn = ADONewConnection('oci805');
		$conn->NLS_DATE_FORMAT = 'RRRR-MM-DD HH24:MI:SS';
	}
	$conn->debug = EWR_DEBUG_ENABLED;
	$conn->debug_echo = FALSE;
	if ($dbtype == "MYSQL" || $dbtype == "POSTGRESQL" || $dbtype == "ORACLE")
		$conn->port = intval(@$info["port"]);
	if ($dbtype == "ORACLE")
		$conn->charSet = @$info["charset"];
	$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
	if ($dbtype == "MYSQL" || $dbtype == "POSTGRESQL" || $dbtype == "ORACLE") {
		if ($dbtype == "MYSQL")
			$conn->Connect(@$info["host"], @$info["user"], @$info["pass"], @$info["db"], @$info["new"]);
		else
			$conn->Connect(@$info["host"], @$info["user"], @$info["pass"], @$info["db"]);
		if ($dbtype == "MYSQL" && EWR_MYSQL_CHARSET <> "")
			$conn->Execute("SET NAMES '" . EWR_MYSQL_CHARSET . "'");
		if ($dbtype == "ORACLE") {

			// Set schema
			$conn->Execute("ALTER SESSION SET CURRENT_SCHEMA = ". ewr_QuotedName(@$info["schema"], $dbid));
			$conn->Execute("ALTER SESSION SET NLS_TIMESTAMP_FORMAT = 'yyyy-mm-dd hh24:mi:ss'");
			$conn->Execute("ALTER SESSION SET NLS_TIMESTAMP_TZ_FORMAT = 'yyyy-mm-dd hh24:mi:ss'");
		}
		if ($dbtype == "POSTGRESQL") {

			// Set schema
			if (@$info["schema"] <> "public")
				$conn->Execute("SET search_path TO " . ewr_QuotedName($info["schema"], $dbid));
		}
	} elseif ($dbtype == "ACCESS" || $dbtype == "MSSQL") {
		if (EWR_CODEPAGE > 0)
			$conn->charPage = EWR_CODEPAGE;
		if ($dbtype == "ACCESS") {
			$relpath = @$info["relpath"];
			$dbname = @$info["dbname"];
			$provider = @$info["provider"];
			$password = @$info["password"];
			if ($relpath == "")
				$datasource = realpath($GLOBALS["EWR_RELATIVE_PATH"] . $dbname);

			//elseif (substr($relpath, 0, 1) == ".") // Relative path starting with "." or ".." (relative to app root)
				//$datasource = ewr_ServerMapPath($relpath . $dbname);

			elseif (substr($relpath, 0, 2) == "\\\\" || strpos($relpath, ":") !== FALSE) // Physical path
				$datasource = $relpath . $dbname;
			else // Relative to app root
				$datasource = ewr_ServerMapPath($relpath) . $dbname;
			if ($password <> "")
				$connstr = $provider . ";Data Source=" . $datasource . ";Jet OLEDB:Database Password=" . $password . ";";
			elseif (strtoupper(substr($dbname, -6)) == ".ACCDB") // AccDb
				$connstr = $provider . ";Data Source=" . $datasource . ";Persist Security Info=False;";
			else
				$connstr = $provider . ";Data Source=" . $datasource . ";";
			$conn->Connect($connstr, FALSE, FALSE);
		} else {
			if (EW_USE_MSSQL_NATIVE)
				$conn->Connect(@$info["host"], @$info["user"], @$info["pass"], @$info["db"]);
			else
				$conn->Connect(@$info["connectionstring"], FALSE, FALSE);
		}

		// Set date format
		if ($dbtype == "MSSQL" && $EWR_DATE_FORMAT <> "")
			$conn->Execute("SET DATEFORMAT ymd");
	}

	//$conn->raiseErrorFn = '';
	// Database connected event

	Database_Connected($conn);
	$info["conn"] = &$conn;
}

// Close database connections
function ewr_CloseConn() {
	global $conn, $EWR_CONN;
	foreach ($EWR_CONN as $dbid => &$db) {
		if ($db["conn"]) $db["conn"]->Close();
		$db["conn"] = NULL;
	}
	$conn = NULL;
}

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

// Database Connected event
function Database_Connected(&$conn) {

	// Example:
	//$conn->Execute("Your SQL");

}

// Reporting inserting event
function Report_Inserting($rsnew) {

	// Enter your code here
	return TRUE; // Return TRUE to insert, FALSE to skip
}

// Check if boolean value is TRUE
function ewr_ConvertToBool($value) {
	return ($value === TRUE || strval($value) == "1" ||
		strtolower(strval($value)) == "y" || strtolower(strval($value)) == "t");
}

// Check if HTTP POST
function ewr_IsHttpPost() {
	$ct = ewr_ServerVar("CONTENT_TYPE");
	if (empty($ct)) $ct = ewr_ServerVar("HTTP_CONTENT_TYPE");
	return strpos($ct, "application/x-www-form-urlencoded") !== FALSE;
}

// Prepend CSS class name
function ewr_PrependClass(&$attr, $classname) {
	$classname = trim($classname);
	if ($classname <> "") {
		$attr = trim($attr);
		if ($attr <> "")
			$attr = " " . $attr;
		$attr = $classname . $attr;
	}
}

// Append CSS class name
function ewr_AppendClass(&$attr, $classname) {
	$classname = trim($classname);
	if ($classname <> "") {
		$attr = trim($attr);
		if ($attr <> "")
			$attr .= " ";
		$attr .= $classname;
	}
}

// Escape chars for XML
function ewr_XmlEncode($val) {
	return htmlspecialchars(strval($val));
}

// Output SCRIPT tag
function ewr_AddClientScript($src, $attrs = NULL) {
	global $EWR_RELATIVE_PATH;
	if ($EWR_RELATIVE_PATH <> "" && !ewr_StartsStr($EWR_RELATIVE_PATH, $src))
		$src = $EWR_RELATIVE_PATH . $src;
	$atts = array("type" => "text/javascript", "src" => $src);
	if (is_array($attrs))
		$atts = array_merge($atts, $attrs);
	echo ewr_HtmlElement("script", $atts, "") . "\n";
}

// Output LINK tag
function ewr_AddStylesheet($href, $attrs = NULL) {
	global $EWR_RELATIVE_PATH;
	if ($EWR_RELATIVE_PATH <> "" && !ewr_StartsStr($EWR_RELATIVE_PATH, $href))
		$href = $EWR_RELATIVE_PATH . $href;
	$atts = array("rel" => "stylesheet", "type" => "text/css", "href" => $href);
	if (is_array($attrs))
		$atts = array_merge($atts, $attrs);
	echo ewr_HtmlElement("link", $atts, "", FALSE) . "\n";
}

// Build HTML element
function ewr_HtmlElement($tagname, $attrs, $innerhtml = "", $endtag = TRUE) {
	$html = "<" . $tagname;
	if (is_array($attrs)) {
		foreach ($attrs as $name => $attr) {
			if (strval($attr) <> "")
				$html .= " " . $name . "=\"" . ewr_HtmlEncode($attr) . "\"";
		}
	}
	$html .= ">";
	if (strval($innerhtml) <> "")
		$html .= $innerhtml;
	if ($endtag)
		$html .= "</" . $tagname . ">";
	return $html;
}

// Encode html
function ewr_HtmlEncode($exp) {
	return @htmlspecialchars(strval($exp), ENT_COMPAT | ENT_HTML5, EWR_ENCODING);
}

// Get title
function ewr_HtmlTitle($name) {
	if (preg_match('/\s+title\s*=\s*[\'"]([\s\S]*?)[\'"]/i', $name, $matches)) { // Match title='title'
		return $matches[1];
	} elseif (preg_match('/\s+data-caption\s*=\s*[\'"]([\s\S]*?)[\'"]/i', $name, $matches)) { // Match data-caption='caption'
		return $matches[1];
	} else {
		return $name;
	}
}

// View Option Separator
function ewr_ViewOptionSeparator($rowcnt) {
	return ", ";
}
/**
 * Class for TEA encryption/decryption
 */

class crTEA {

	function long2str($v, $w) {
		$len = count($v);
		$s = array();
		for ($i = 0; $i < $len; $i++)
		{
			$s[$i] = pack("V", $v[$i]);
		}
		if ($w) {
			return substr(join('', $s), 0, $v[$len - 1]);
		}	else {
			return join('', $s);
		}
	}

	function str2long($s, $w) {
		$v = unpack("V*", $s. str_repeat("\0", (4 - strlen($s) % 4) & 3));
		$v = array_values($v);
		if ($w) {
			$v[count($v)] = strlen($s);
		}
		return $v;
	}

	// Encrypt
	public function Encrypt($str, $key = EWR_RANDOM_KEY) {
		if ($str == "") {
			return "";
		}
		$v = $this->str2long($str, true);
		$k = $this->str2long($key, false);
		$cntk = count($k);
		if ($cntk < 4) {
			for ($i = $cntk; $i < 4; $i++) {
				$k[$i] = 0;
			}
		}
		$n = count($v) - 1;
		$z = $v[$n];
		$y = $v[0];
		$delta = 0x9E3779B9;
		$q = floor(6 + 52 / ($n + 1));
		$sum = 0;
		while (0 < $q--) {
			$sum = $this->int32($sum + $delta);
			$e = $sum >> 2 & 3;
			for ($p = 0; $p < $n; $p++) {
				$y = $v[$p + 1];
				$mx = $this->int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ $this->int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
				$z = $v[$p] = $this->int32($v[$p] + $mx);
			}
			$y = $v[0];
			$mx = $this->int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ $this->int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
			$z = $v[$n] = $this->int32($v[$n] + $mx);
		}
		return $this->UrlEncode($this->long2str($v, false));
	}

	// Decrypt
	public function Decrypt($str, $key = EWR_RANDOM_KEY) {
		$str = $this->UrlDecode($str);
		if ($str == "") {
			return "";
		}
		$v = $this->str2long($str, false);
		$k = $this->str2long($key, false);
		$cntk = count($k);
		if ($cntk < 4) {
			for ($i = $cntk; $i < 4; $i++) {
				$k[$i] = 0;
			}
		}
		$n = count($v) - 1;
		$z = $v[$n];
		$y = $v[0];
		$delta = 0x9E3779B9;
		$q = floor(6 + 52 / ($n + 1));
		$sum = $this->int32($q * $delta);
		while ($sum != 0) {
			$e = $sum >> 2 & 3;
			for ($p = $n; $p > 0; $p--) {
				$z = $v[$p - 1];
				$mx = $this->int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ $this->int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
				$y = $v[$p] = $this->int32($v[$p] - $mx);
			}
			$z = $v[$n];
			$mx = $this->int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ $this->int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
			$y = $v[0] = $this->int32($v[0] - $mx);
			$sum = $this->int32($sum - $delta);
		}
		return $this->long2str($v, true);
	}

	function int32($n) {
		while ($n >= 2147483648) $n -= 4294967296;
		while ($n <= -2147483649) $n += 4294967296;
		return (int)$n;
	}

	function UrlEncode($string) {
		$data = base64_encode($string);
		return str_replace(array('+','/','='), array('-','_','.'), $data);
	}

	function UrlDecode($string) {
		$data = str_replace(array('-','_','.'), array('+','/','='), $string);
		return base64_decode($data);
	}
}

// Encrypt
function ewr_Encrypt($str, $key = EWR_RANDOM_KEY) {
	$tea = new crTEA;
	return $tea->Encrypt($str, $key);
}

// Decrypt
function ewr_Decrypt($str, $key = EWR_RANDOM_KEY) {
	$tea = new crTEA;
	return $tea->Decrypt($str, $key);
}
/**
 * Pager item class
 */

class crPagerItem {
	var $Start;
	var $Text;
	var $Enabled;
}
/**
 * Numeric pager class
 */

class crNumericPager {
	var $Items = array();
	var $Count, $FromIndex, $ToIndex, $RecordCount, $PageSize, $Range;
	var $FirstButton, $PrevButton, $NextButton, $LastButton;
	var $ButtonCount = 0;
	var $AutoHidePager = TRUE;
	var $Visible = TRUE;

	function __construct($StartRec, $DisplayRecs, $TotalRecs, $RecRange, $AutoHidePager = EWR_AUTO_HIDE_PAGER) {
		$this->AutoHidePager = $AutoHidePager;
		if ($this->AutoHidePager && $StartRec == 1 && $TotalRecs <= $DisplayRecs)
			$this->Visible = FALSE;
		$this->FirstButton = new crPagerItem;
		$this->PrevButton = new crPagerItem;
		$this->NextButton = new crPagerItem;
		$this->LastButton = new crPagerItem;
		$this->FromIndex = intval($StartRec);
		$this->PageSize = intval($DisplayRecs);
		$this->RecordCount = intval($TotalRecs);
		$this->Range = intval($RecRange);
		if ($this->PageSize == 0) return;
		if ($this->FromIndex > $this->RecordCount)
			$this->FromIndex = $this->RecordCount;
		$this->ToIndex = $this->FromIndex + $this->PageSize - 1;
		if ($this->ToIndex > $this->RecordCount)
			$this->ToIndex = $this->RecordCount;

		// Setup
		$this->SetupNumericPager();

		// Update button count
		if ($this->FirstButton->Enabled) $this->ButtonCount++;
		if ($this->PrevButton->Enabled) $this->ButtonCount++;
		if ($this->NextButton->Enabled) $this->ButtonCount++;
		if ($this->LastButton->Enabled) $this->ButtonCount++;
		$this->ButtonCount += count($this->Items);
	}

	// Add pager item
	function AddPagerItem($StartIndex, $Text, $Enabled)
	{
		$Item = new crPagerItem;
		$Item->Start = $StartIndex;
		$Item->Text = $Text;
		$Item->Enabled = $Enabled;
		$this->Items[] = $Item;
	}

	// Setup pager items
	function SetupNumericPager()
	{
		if ($this->RecordCount > $this->PageSize) {
			$Eof = ($this->RecordCount < ($this->FromIndex + $this->PageSize));
			$HasPrev = ($this->FromIndex > 1);

			// First Button
			$TempIndex = 1;
			$this->FirstButton->Start = $TempIndex;
			$this->FirstButton->Enabled = ($this->FromIndex > $TempIndex);

			// Prev Button
			$TempIndex = $this->FromIndex - $this->PageSize;
			if ($TempIndex < 1) $TempIndex = 1;
			$this->PrevButton->Start = $TempIndex;
			$this->PrevButton->Enabled = $HasPrev;

			// Page links
			if ($HasPrev || !$Eof) {
				$x = 1;
				$y = 1;
				$dx1 = intval(($this->FromIndex-1)/($this->PageSize*$this->Range))*$this->PageSize*$this->Range + 1;
				$dy1 = intval(($this->FromIndex-1)/($this->PageSize*$this->Range))*$this->Range + 1;
				if (($dx1+$this->PageSize*$this->Range-1) > $this->RecordCount) {
					$dx2 = intval($this->RecordCount/$this->PageSize)*$this->PageSize + 1;
					$dy2 = intval($this->RecordCount/$this->PageSize) + 1;
				} else {
					$dx2 = $dx1 + $this->PageSize*$this->Range - 1;
					$dy2 = $dy1 + $this->Range - 1;
				}
				while ($x <= $this->RecordCount) {
					if ($x >= $dx1 && $x <= $dx2) {
						$this->AddPagerItem($x, $y, $this->FromIndex<>$x);
						$x += $this->PageSize;
						$y++;
					} elseif ($x >= ($dx1-$this->PageSize*$this->Range) && $x <= ($dx2+$this->PageSize*$this->Range)) {
						if ($x+$this->Range*$this->PageSize < $this->RecordCount) {
							$this->AddPagerItem($x, $y . "-" . ($y+$this->Range-1), TRUE);
						} else {
							$ny = intval(($this->RecordCount-1)/$this->PageSize) + 1;
							if ($ny == $y) {
								$this->AddPagerItem($x, $y, TRUE);
							} else {
								$this->AddPagerItem($x, $y . "-" . $ny, TRUE);
							}
						}
						$x += $this->Range*$this->PageSize;
						$y += $this->Range;
					} else {
						$x += $this->Range*$this->PageSize;
						$y += $this->Range;
					}
				}
			}

			// Next Button
			$TempIndex = $this->FromIndex + $this->PageSize;
			$this->NextButton->Start = $TempIndex;
			$this->NextButton->Enabled = !$Eof;

			// Last Button
			$TempIndex = intval(($this->RecordCount-1)/$this->PageSize)*$this->PageSize + 1;
			$this->LastButton->Start = $TempIndex;
			$this->LastButton->Enabled = ($this->FromIndex < $TempIndex);
		}
	}
}
/**
 * PrevNext pager class
 */

class crPrevNextPager {
	var $FirstButton, $PrevButton, $NextButton, $LastButton;
	var $CurrentPage, $PageCount, $FromIndex, $ToIndex, $RecordCount;
	var $AutoHidePager = TRUE;
	var $Visible = TRUE;

	function __construct($StartRec, $DisplayRecs, $TotalRecs, $AutoHidePager = EWR_AUTO_HIDE_PAGER) {
		$this->AutoHidePager = $AutoHidePager;
		if ($this->AutoHidePager && $StartRec == 1 && $TotalRecs <= $DisplayRecs)
			$this->Visible = FALSE;
		$this->FirstButton = new crPagerItem;
		$this->PrevButton = new crPagerItem;
		$this->NextButton = new crPagerItem;
		$this->LastButton = new crPagerItem;
		$this->FromIndex = intval($StartRec);
		$this->PageSize = intval($DisplayRecs);
		$this->RecordCount = intval($TotalRecs);
		if ($this->PageSize == 0) return;
		$this->CurrentPage = intval(($this->FromIndex-1)/$this->PageSize) + 1;
		$this->PageCount = intval(($this->RecordCount-1)/$this->PageSize) + 1;
		if ($this->FromIndex > $this->RecordCount)
			$this->FromIndex = $this->RecordCount;
		$this->ToIndex = $this->FromIndex + $this->PageSize - 1;
		if ($this->ToIndex > $this->RecordCount)
			$this->ToIndex = $this->RecordCount;

		// First Button
		$TempIndex = 1;
		$this->FirstButton->Start = $TempIndex;
		$this->FirstButton->Enabled = ($TempIndex <> $this->FromIndex);

		// Prev Button
		$TempIndex = $this->FromIndex - $this->PageSize;
		if ($TempIndex < 1) $TempIndex = 1;
		$this->PrevButton->Start = $TempIndex;
		$this->PrevButton->Enabled = ($TempIndex <> $this->FromIndex);

		// Next Button
		$TempIndex = $this->FromIndex + $this->PageSize;
		if ($TempIndex > $this->RecordCount)
			$TempIndex = $this->FromIndex;
		$this->NextButton->Start = $TempIndex;
		$this->NextButton->Enabled = ($TempIndex <> $this->FromIndex);

		// Last Button
		$TempIndex = intval(($this->RecordCount-1)/$this->PageSize)*$this->PageSize + 1;
		$this->LastButton->Start = $TempIndex;
		$this->LastButton->Enabled = ($TempIndex <> $this->FromIndex);
	}
}
/**
 * Email class
 */

class crEmail {

	// Class properties
	var $Sender = ""; // Sender
	var $Recipient = ""; // Recipient
	var $Cc = ""; // Cc
	var $Bcc = ""; // Bcc
	var $Subject = ""; // Subject
	var $Format = ""; // Format
	var $Content = ""; // Content
	var $Attachments = array(); // Attachments
	var $EmbeddedImages = array(); // Embedded image
	var $Charset = ""; // Charset
	var $SendErrDescription; // Send error description
	var $SmtpSecure = EWR_SMTP_SECURE_OPTION; // Send secure option
	var $Prop = array(); // PHPMailer properties

	// Set PHPMailer property
	function __set($name, $value) {
		$this->Prop[$name] = $value;
	}

	// Method to load email from template
	function Load($fn) {
		$fn = ewr_ScriptFolder() . EWR_PATH_DELIMITER . $fn;
		$sWrk = file_get_contents($fn); // Load text file content
		if (substr($sWrk, 0, 3) == "\xEF\xBB\xBF") // UTF-8 BOM
			$sWrk = substr($sWrk, 3);
		if ($sWrk <> "") {

			// Locate Header & Mail Content
			if (EWR_IS_WINDOWS) {
				$i = strpos($sWrk, "\r\n\r\n");
			} else {
				$i = strpos($sWrk, "\n\n");
				if ($i === FALSE) $i = strpos($sWrk, "\r\n\r\n");
			}
			if ($i > 0) {
				$sHeader = substr($sWrk, 0, $i);
				$this->Content = trim(substr($sWrk, $i, strlen($sWrk)));
				if (EWR_IS_WINDOWS) {
					$arrHeader = explode("\r\n", $sHeader);
				} else {
					$arrHeader = explode("\n", $sHeader);
				}
				$cnt = count($arrHeader);
				for ($j = 0; $j < $cnt; $j++) {
					$i = strpos($arrHeader[$j], ":");
					if ($i > 0) {
						$sName = trim(substr($arrHeader[$j], 0, $i));
						$sValue = trim(substr($arrHeader[$j], $i+1, strlen($arrHeader[$j])));
						switch (strtolower($sName))
						{
							case "subject":
								$this->Subject = $sValue;
								break;
							case "from":
								$this->Sender = $sValue;
								break;
							case "to":
								$this->Recipient = $sValue;
								break;
							case "cc":
								$this->Cc = $sValue;
								break;
							case "bcc":
								$this->Bcc = $sValue;
								break;
							case "format":
								$this->Format = $sValue;
								break;
						}
					}
				}
			}
		}
	}

	// Method to replace sender
	function ReplaceSender($ASender) {
		$this->Sender = str_replace('<!--$From-->', $ASender, $this->Sender);
	}

	// Method to replace recipient
	function ReplaceRecipient($ARecipient) {
		$this->Recipient = str_replace('<!--$To-->', $ARecipient, $this->Recipient);
	}

	// Method to add Cc email
	function AddCc($ACc) {
		if ($ACc <> "") {
			if ($this->Cc <> "") $this->Cc .= ";";
			$this->Cc .= $ACc;
		}
	}

	// Method to add Bcc email
	function AddBcc($ABcc) {
		if ($ABcc <> "") {
			if ($this->Bcc <> "") $this->Bcc .= ";";
			$this->Bcc .= $ABcc;
		}
	}

	// Method to replace subject
	function ReplaceSubject($ASubject) {
		$this->Subject = str_replace('<!--$Subject-->', $ASubject, $this->Subject);
	}

	// Method to replace content
	function ReplaceContent($Find, $ReplaceWith) {
		$this->Content = str_replace($Find, $ReplaceWith, $this->Content);
	}

	// Method to add embedded image
	function AddEmbeddedImage($image) {
		if ($image <> "")
			$this->EmbeddedImages[] = $image;
	}

	// Method to add attachment
	function AddAttachment($filename, $content = "") {
		if ($filename <> "")
			$this->Attachments[] = array("filename" => $filename, "content" => $content);
	}

	// Method to send email
	function Send() {
		$result = ewr_SendEmail($this->Sender, $this->Recipient, $this->Cc, $this->Bcc,
			$this->Subject, $this->Content, $this->Format, $this->Charset, $this->SmtpSecure,
			$this->Attachments, $this->EmbeddedImages, $this->Prop);
		if (is_bool($result)) {
			return $result;
		} else { // Error
			$this->SendErrDescription = $result;
			return FALSE;
		}
	}
}

// Include PHPMailer class
include_once($EWR_RELATIVE_PATH . "phpmailer5226/PHPMailerAutoload.php");

// Function to send email
function ewr_SendEmail($sFrEmail, $sToEmail, $sCcEmail, $sBccEmail, $sSubject, $sMail, $sFormat, $sCharset, $sSmtpSecure = "", $arAttachments = array(), $arImages = array(), $arProperties = NULL) {
	global $ReportLanguage;
	$res = FALSE;
	$mail = new PHPMailer();
	$mail->IsSMTP(); 
	$mail->Host = EWR_SMTP_SERVER;
	$mail->SMTPAuth = (EWR_SMTP_SERVER_USERNAME <> "" && EWR_SMTP_SERVER_PASSWORD <> "");
	$mail->Username = EWR_SMTP_SERVER_USERNAME;
	$mail->Password = EWR_SMTP_SERVER_PASSWORD;
	$mail->Port = EWR_SMTP_SERVER_PORT;
	if (EWR_DEBUG_ENABLED) {
		$mail->SMTPDebug = 2;
		$mail->Debugoutput = ewr_SetDebugMsg;
	}
	if ($sSmtpSecure <> "") {
		$mail->SMTPSecure = $sSmtpSecure;
		$mail->SMTPOptions = array("ssl" => array("verify_peer" => FALSE, "verify_peer_name" => FALSE, "allow_self_signed" => TRUE));
	}
	if (preg_match('/^(.+)<([\w.%+-]+@[\w.-]+\.[A-Z]{2,6})>$/i', trim($sFrEmail), $m)) {
		$mail->From = $m[2];
		$mail->FromName = trim($m[1]);
	} else {
		$mail->From = $sFrEmail;
		$mail->FromName = $sFrEmail;
	}
	$mail->Subject = $sSubject;
	$mail->Body = $sMail;
	if ($sCharset <> "" && strtolower($sCharset) <> "iso-8859-1")
		$mail->CharSet = $sCharset;
	$sToEmail = str_replace(";", ",", $sToEmail);
	$arrTo = explode(",", $sToEmail);
	foreach ($arrTo as $sTo) {
		$mail->AddAddress(trim($sTo));
	}
	if ($sCcEmail <> "") {
		$sCcEmail = str_replace(";", ",", $sCcEmail);
		$arrCc = explode(",", $sCcEmail);
		foreach ($arrCc as $sCc) {
			$mail->AddCC(trim($sCc));
		}
	}
	if ($sBccEmail <> "") {
		$sBccEmail = str_replace(";", ",", $sBccEmail);
		$arrBcc = explode(",", $sBccEmail);
		foreach ($arrBcc as $sBcc) {
			$mail->AddBCC(trim($sBcc));
		}
	}
	if (strtolower($sFormat) == "html") {
		$mail->ContentType = "text/html";
	} else {
		$mail->ContentType = "text/plain";
	}
	if (is_array($arAttachments)) {
		foreach ($arAttachments as $attachment) {
			$filename = @$attachment["filename"];
			$content = @$attachment["content"];
			if ($content <> "" && $filename <> "") {
				$mail->AddStringAttachment($content, $filename);
			} else if ($filename <> "") {
				$mail->AddAttachment($filename);
			}
		}
	}
	if (is_array($arImages)) {
		foreach ($arImages as $tmpimage) {
			$file = ewr_UploadPathEx(TRUE, EWR_UPLOAD_DEST_PATH) . $tmpimage;
			$cid = ewr_TmpImageLnk($tmpimage, "cid");
			$mail->AddEmbeddedImage($file, $cid, $tmpimage);
		}
	}
	if (is_array($arProperties)) {
		foreach ($arProperties as $key => $value)
			$mail->set($key, $value);
	}
	$res = $mail->Send();
	if (!$res)
		$res = $mail->ErrorInfo;
	return $res; // TRUE on success, error message on failure
}

// Adjust email content
function ewr_AdjustEmailContent($content) {
	$content = str_replace('class="ewGrid"', "", $content);
	$content = str_replace('class="ewGridMiddlePanel"', "", $content);
	$content = str_replace('class="table-responsive ewGridMiddlePanel"', "", $content);
	$content = str_replace("table ewTable", "ewExportTable", $content);
	$tableStyles = "border-collapse: collapse;";
	$cellStyles = "border: 1px solid #dddddd; padding: 5px;";
	$doc = new DOMDocument();
	@$doc->loadHTML('<?xml encoding="utf-8">' . ewr_ConvertToUtf8($content)); // Convert to utf-8
	$tables = $doc->getElementsByTagName("table");
	foreach ($tables as $table) {
		if (ewr_ContainsText($table->getAttribute("class"), "ewExportTable")) {
			if ($table->hasAttribute("style"))
				$table->setAttribute("style", $table->getAttribute("style") . $tableStyles);
			else
				$table->setAttribute("style", $tableStyles);
			$rows = $table->getElementsByTagName("tr");
			$rowcnt = $rows->length;
			for ($i = 0; $i < $rowcnt; $i++) {
				$row = $rows->item($i);
				$cells = $row->childNodes;
				$cellcnt = $cells->length;
				for ($j = 0; $j < $cellcnt; $j++) {
					$cell = $cells->item($j);
					if ($cell->nodeType <> XML_ELEMENT_NODE || $cell->tagName <> "td")
						continue;
					if ($cell->hasAttribute("style"))
						$cell->setAttribute("style", $cell->getAttribute("style") . $cellStyles);
					else
						$cell->setAttribute("style", $cellStyles);
				}
			}
		}
	}
	$content = $doc->saveHTML();
	$content = ewr_ConvertFromUtf8($content);
	return $content;
}

// Load email count
function ewr_LoadEmailCount() {

	// Read from log
	if (EWR_EMAIL_WRITE_LOG) {
		$ip = ewr_ServerVar("REMOTE_ADDR");

		// Load from database
		if (EWR_EMAIL_WRITE_LOG_TO_DATABASE) {
			$dt1 = date("Y-m-d H:i:s", strtotime("- " . EWR_MAX_EMAIL_SENT_PERIOD . "minute"));
			$dt2 = date("Y-m-d H:i:s");
			$sEmailSql = "SELECT COUNT(*) FROM " . EWR_EMAIL_LOG_TABLE_NAME .
				" WHERE " . ewr_QuotedName(EWR_EMAIL_LOG_FIELD_NAME_DATETIME, EWR_EMAIL_LOG_TABLE_DBID) .
				" BETWEEN " . ewr_QuotedValue($dt1, EWR_DATATYPE_DATE, EWR_EMAIL_LOG_TABLE_DBID) . " AND " . ewr_QuotedValue($dt2, EWR_DATATYPE_DATE, EWR_EMAIL_LOG_TABLE_DBID) .
				" AND " . ewr_QuotedName(EWR_EMAIL_LOG_FIELD_NAME_IP, EWR_EMAIL_LOG_TABLE_DBID) . 
				" = " . ewr_QuotedValue($ip, EWR_DATATYPE_STRING, EWR_EMAIL_LOG_TABLE_DBID);
			$rscnt = ReportConn(EWR_EMAIL_LOG_TABLE_DBID)->Execute($sEmailSql);
			if ($rscnt) {
				$_SESSION[EWR_EXPORT_EMAIL_COUNTER] = ($rscnt->RecordCount()>1) ? $rscnt->RecordCount() : $rscnt->fields[0];
				$rscnt->Close();
			} else {
				$_SESSION[EWR_EXPORT_EMAIL_COUNTER] = 0;
			}

		// Load from log file
		} else {
			$pfx = "email";
			$sTab = "\t";
			$sFolder = EWR_UPLOAD_DEST_PATH;
			$randomkey = ewr_Encrypt(date("Ymd"), EWR_RANDOM_KEY);
			$sFn = $pfx . "_" . date("Ymd") . "_" . $randomkey . ".txt";
			$filename = ewr_UploadPathEx(TRUE, $sFolder) . $sFn;
			if (file_exists($filename)) {
				$arLines = file($filename);
				$cnt = 0;
				foreach ($arLines as $line) {
					if ($line <> "") {
						list($dtwrk, $ipwrk, $senderwrk, $recipientwrk, $subjectwrk, $messagewrk) = explode($sTab, $line);
						$timediff = intval((strtotime("now") - strtotime($dtwrk,0))/60);
						if ($ipwrk == $ip && $timediff < EWR_MAX_EMAIL_SENT_PERIOD) $cnt++;
					}
				}
				$_SESSION[EWR_EXPORT_EMAIL_COUNTER] = $cnt;
			} else {
				$_SESSION[EWR_EXPORT_EMAIL_COUNTER] = 0;
			}
		}
	}
	if (!isset($_SESSION[EWR_EXPORT_EMAIL_COUNTER]))
		$_SESSION[EWR_EXPORT_EMAIL_COUNTER] = 0;
	return intval($_SESSION[EWR_EXPORT_EMAIL_COUNTER]);
}

// Add email log
function ewr_AddEmailLog($sender, $recipient, $subject, $message) {
	if (!isset($_SESSION[EWR_EXPORT_EMAIL_COUNTER]))
		$_SESSION[EWR_EXPORT_EMAIL_COUNTER] = 0;
	$_SESSION[EWR_EXPORT_EMAIL_COUNTER]++;

	// Save to email log
	if (EWR_EMAIL_WRITE_LOG) {
		$dt = date("Y-m-d H:i:s");
		$ip = ewr_ServerVar("REMOTE_ADDR");
		$senderwrk = ewr_TruncateText($sender);
		$recipientwrk = ewr_TruncateText($recipient);
		$subjectwrk = ewr_TruncateText($subject);
		$messagewrk = ewr_TruncateText($message);

		// Save to database
		if (EWR_EMAIL_WRITE_LOG_TO_DATABASE) {
			$sEmailSql = "INSERT INTO " . EWR_EMAIL_LOG_TABLE_NAME .
				" (" . ewr_QuotedName(EWR_EMAIL_LOG_FIELD_NAME_DATETIME, EWR_EMAIL_LOG_TABLE_DBID) . ", " .
				ewr_QuotedName(EWR_EMAIL_LOG_FIELD_NAME_IP, EWR_EMAIL_LOG_TABLE_DBID) . ", " .
				ewr_QuotedName(EWR_EMAIL_LOG_FIELD_NAME_SENDER, EWR_EMAIL_LOG_TABLE_DBID) . ", " .
				ewr_QuotedName(EWR_EMAIL_LOG_FIELD_NAME_RECIPIENT, EWR_EMAIL_LOG_TABLE_DBID) . ", " .
				ewr_QuotedName(EWR_EMAIL_LOG_FIELD_NAME_SUBJECT, EWR_EMAIL_LOG_TABLE_DBID) . ", " .
				ewr_QuotedName(EWR_EMAIL_LOG_FIELD_NAME_MESSAGE, EWR_EMAIL_LOG_TABLE_DBID) . ") VALUES (" .
				ewr_QuotedValue($dt, EWR_DATATYPE_DATE, EWR_EMAIL_LOG_TABLE_DBID) . ", " .
				ewr_QuotedValue($ip, EWR_DATATYPE_STRING, EWR_EMAIL_LOG_TABLE_DBID) . ", " .
				ewr_QuotedValue($senderwrk, EWR_DATATYPE_STRING, EWR_EMAIL_LOG_TABLE_DBID) . ", " .
				ewr_QuotedValue($recipientwrk, EWR_DATATYPE_STRING, EWR_EMAIL_LOG_TABLE_DBID) . ", " .
				ewr_QuotedValue($subjectwrk, EWR_DATATYPE_STRING, EWR_EMAIL_LOG_TABLE_DBID) . ", " .
				ewr_QuotedValue($messagewrk, EWR_DATATYPE_STRING, EWR_EMAIL_LOG_TABLE_DBID) . ")";
			ReportConn(EWR_EMAIL_LOG_TABLE_DBID)->Execute($sEmailSql);

		// Save to log file
		} else {
			$pfx = "email";
			$sTab = "\t";
			$sHeader = "date/time" . $sTab . "ip" . $sTab . "sender" . $sTab . "recipient" . $sTab . "subject" . $sTab . "message";
			$sMsg = $dt . $sTab . $ip . $sTab . $senderwrk . $sTab . $recipientwrk . $sTab . $subjectwrk . $sTab . $messagewrk;
			$sFolder = EWR_UPLOAD_DEST_PATH;
			$randomkey = ewr_Encrypt(date("Ymd"), EWR_RANDOM_KEY);
			$sFn = $pfx . "_" . date("Ymd") . "_" . $randomkey . ".txt";
			$filename = ewr_UploadPathEx(TRUE, $sFolder) . $sFn;
			if (file_exists($filename)) {
				$fileHandler = fopen($filename, "a+b");
			} else {
				$fileHandler = fopen($filename, "a+b");
				fwrite($fileHandler,$sHeader."\r\n");
			}
			fwrite($fileHandler, $sMsg."\r\n");
			fclose($fileHandler);
		}
	}
}

function ewr_TruncateText($v, $maxlen = EWR_EMAIL_LOG_SIZE_LIMIT) {
	$v = preg_replace('/[\f\n\r\t\v]/', " ", $v);
	if (strlen($v) > $maxlen)
		$v = substr($v, 0, $maxlen - 3) . "...";
	return $v;
}

// Write global debug message
function ewr_DebugMsg() {
	global $grDebugMsg, $gsExport;
	$msg = $grDebugMsg;
	$grDebugMsg = "";
	return ($gsExport == "" && $msg <> "") ? '<div class="box box-danger box-solid ewDebug"><div class="box-header with-border"><h3 class="box-title">Debug</h3></div><div class="box-body">' . $msg . '</div></div>' : "";
}

// Set global debug message (2nd argument not used)
function ewr_SetDebugMsg($v, $newline = TRUE) {
	global $grDebugMsg, $grTimer;
	$ar = preg_split('/<(hr|br)>/', trim($v));
	$ar = array_filter($ar, function($s) {
		return trim($s); 
	});
	$v = implode("; ", $ar);
	$grDebugMsg .= "<p><samp>" . (isset($grTimer) ? number_format($grTimer->GetElapsedTime(), 6) . ": " : "") . $v . "</samp></p>";
}

// Get global debug message
function ewr_GetDebugMsg() {
	global $grDebugMsg;
	return $grDebugMsg;
}

// Save global debug message
function ewr_SaveDebugMsg() {
	global $grDebugMsg;
	if (EWR_DEBUG_ENABLED)
		$_SESSION["EWR_DEBUG_MESSAGE"] = $grDebugMsg;
}

// Load global debug message
function ewr_LoadDebugMsg() {
	global $grDebugMsg;
	if (EWR_DEBUG_ENABLED) {
		$grDebugMsg = @$_SESSION["EWR_DEBUG_MESSAGE"];
		$_SESSION["EWR_DEBUG_MESSAGE"] = "";
	}
}

// Permission denied message
function ewr_DeniedMsg() {
	global $ReportLanguage;
	return str_replace("%s", ewr_ScriptName(), $ReportLanguage->Phrase("NoPermission"));
}
/**
 * Functions for converting encoding
 */

function ewr_ConvertToUtf8($str) {
	return ewr_Convert(EWR_ENCODING, "UTF-8", $str);
}

function ewr_ConvertFromUtf8($str) {
	return ewr_Convert("UTF-8", EWR_ENCODING, $str);
}

function ewr_Convert($from, $to, $str) {
	if ($from != "" && $to != "" && strtoupper($from) != strtoupper($to)) {
		if (function_exists("iconv")) {
			return iconv($from, $to, $str);
		} elseif (function_exists("mb_convert_encoding")) {
			return mb_convert_encoding($str, $to, $from);
		} else {
			return $str;
		}
	} else {
		return $str;
	}
}

// Encode value for single-quoted JavaScript string
function ewr_JsEncode($val) {
	$val = strval($val);
	if (EWR_IS_DOUBLE_BYTE)
		$val = ewr_ConvertToUtf8($val);
	$val = str_replace("\\", "\\\\", $val);
	$val = str_replace("'", "\\'", $val);
	$val = str_replace("\r\n", "<br>", $val);
	$val = str_replace("\r", "<br>", $val);
	$val = str_replace("\n", "<br>", $val);
	if (EWR_IS_DOUBLE_BYTE)
		$val = ewr_ConvertFromUtf8($val);
	return $val;
}

// Encode value for double-quoted Javascript string
function ewr_JsEncode2($val) {
	$val = strval($val);
	if (EWR_IS_DOUBLE_BYTE)
		$val = ewr_ConvertToUtf8($val);
	$val = str_replace("\\", "\\\\", $val);
	$val = str_replace("\"", "\\\"", $val);
	$val = str_replace("\t", "\\t", $val);
	$val = str_replace("\r", "\\r", $val);
	$val = str_replace("\n", "\\n", $val);
	if (EWR_IS_DOUBLE_BYTE)
		$val = ewr_ConvertFromUtf8($val);
	return $val;
}

// Display field value separator
// idx (int) display field index (1|2|3)
// fld (object) field object
function ewr_ValueSeparator($idx, &$fld) {
	$sep = ($fld) ? $fld->DisplayValueSeparator : ", ";
	return (is_array($sep)) ? @$sep[$idx - 1] : $sep;
}

// Convert a value to JSON value
// $type: string/boolean
function ewr_VarToJson($val, $type = "") {
	$type = strtolower($type);
	if (is_null($val)) {
		return "null";
	} elseif ($type == "boolean" || is_bool($val)) {
		return (ewr_ConvertToBool($val)) ? "true" : "false";
	} elseif ($type == "string" || is_string($val)) {
		return "\"" . ewr_JsEncode2($val) . "\"";
	}
	return $val;
}

// Convert rows (array) to JSON
function ewr_ArrayToJson($ar, $offset = 0) {
	$arOut = array();
	$array = FALSE;
	if (count($ar) > 0) {
		$keys = array_keys($ar[0]);
		foreach ($keys as $key) {
			if (is_int($key)) {
				$array = TRUE;
				break;
			}
		}
	}
	foreach ($ar as $row) {
		$arwrk = array();
		foreach ($row as $key => $val) {
			if (($array && is_string($key)) || (!$array && is_int($key)))
				continue;
			$key = ($array) ? "" : "\"" . ewr_JsEncode2($key) . "\":";
			$arwrk[] = $key . ewr_VarToJson($val);
		}
		if ($array) { // Array
			$arOut[] = "[" . implode(",", $arwrk) . "]";
		} else { // Object
			$arOut[] = "{" . implode(",", $arwrk) . "}";
		}
	}
	if ($offset > 0)
		$arOut = array_slice($arOut, $offset);
	return "[" . implode(",", $arOut) . "]";
}

// Executes the query, and returns all rows as JSON
// options: header(bool), utf8(bool), array(bool), firstonly(bool)
function ewr_ExecuteJson($SQL, $options = NULL, $c = NULL) {
	$ar = is_array($options) ? $options : array();
	if (is_bool($options)) // First only, backward compatibility
		$ar["firstonly"] = $options;
	if (is_null($c) && is_object($options) && method_exists($options, "Execute")) // ewr_ExecuteJson($SQL, $c)
		$c = $options;
	$res = "false";
	$header = array_key_exists("header", $ar) && $ar["header"]; // Set header for JSON
	$utf8 = $header || array_key_exists("utf8", $ar) && $ar["utf8"]; // Convert to utf-8
	$array = array_key_exists("array", $ar) && $ar["array"];
	$firstonly = array_key_exists("firstonly", $ar) && $ar["firstonly"];
	if ($firstonly)
		$rows = array(ewr_ExecuteRow($SQL, $c));
	else
		$rows = ewr_ExecuteRows($SQL, $c);
	if (is_array($rows)) {
		$arOut = array();
		foreach ($rows as $row) {
			$arwrk = array();
			foreach ($row as $key => $val) {
				if (($array && is_string($key)) || (!$array && is_int($key)))
					continue;
				$key = ($array) ? "" : "\"" . ewr_JsEncode2($key) . "\":";
				$arwrk[] = $key . ewr_VarToJson($val);
			}
			if ($array) { // Array
				$arOut[] = "[" . implode(",", $arwrk) . "]";
			} else { // Object
				$arOut[] = "{" . implode(",", $arwrk) . "}";
			}
		}
		$res = ($firstonly) ? $arOut[0] : "[" . implode(",", $arOut) . "]";
		if ($utf8)
			$res = ewr_ConvertToUtf8($res);
	}
	if ($header)
		header("Content-Type: application/json; charset=utf-8");
	return $res;
}

// Get current page name
function ewr_CurrentPage() {
	return ewr_GetPageName(ewr_ScriptName());
}

// Get page name
function ewr_GetPageName($url) {
	$PageName = "";
	if ($url <> "") {
		$PageName = $url;
		$p = strpos($PageName, "?");
		if ($p !== FALSE)
			$PageName = substr($PageName, 0, $p); // Remove QueryString
		$p = strrpos($PageName, "/");
		if ($p !== FALSE)
			$PageName = substr($PageName, $p+1); // Remove path
	}
	return $PageName;
}

// Adjust text for caption
function ewr_BtnCaption($Caption) {
	$Min = 10;
	if (strlen($Caption) < $Min) {
		$Pad = abs(intval(($Min - strlen($Caption))/2*-1));
		$Caption = str_repeat(" ", $Pad) . $Caption . str_repeat(" ", $Pad);
	}
	return $Caption;
}

// Include mobile_detect.php
if (!class_exists("Mobile_Detect"))
	include_once("phprptinc/mobile_detect.php");

// Check if mobile device
function ewr_IsMobile() {
	global $MobileDetect;
	if (!isset($MobileDetect))
		$MobileDetect = new Mobile_Detect;
	return $MobileDetect->isMobile();
}

// Check if responsive layout
function ewr_IsResponsiveLayout() {
	return $GLOBALS['EWR_USE_RESPONSIVE_LAYOUT'];
}

// Get server variable by name
function ewr_ServerVar($Name) {
	$str = @$_SERVER[$Name];
	if (empty($str)) $str = @$_ENV[$Name];
	return $str;
}

// Get CSS file
function ewr_CssFile($f) {
	global $EWR_CSS_FLIP;
	if ($EWR_CSS_FLIP)
		return preg_replace('/(.css)$/i', "-rtl.css", $f);
	else
		return $f;
}

// Check if HTTPS
function ewr_IsHttps() {
	return ewr_ServerVar("HTTPS") <> "" && ewr_ServerVar("HTTPS") <> "off" ||
		ewr_ServerVar("SERVER_PORT") == 443 ||
		ewr_ServerVar("HTTP_X_FORWARDED_PROTO") <> "" && ewr_ServerVar("HTTP_X_FORWARDED_PROTO") == "https";
}

// Include password.php
include_once $EWR_RELATIVE_PATH . "password.php";

// Encrypt password
function ewr_EncryptPassword($input, $salt = '') {
	if (EWR_PASSWORD_HASH)
		return password_hash($input, PASSWORD_DEFAULT);
	else
		return (strval($salt) <> "") ? md5($input . $salt) . ":" . $salt : md5($input);
}

// Compare password
// Note: If salted, password must be stored in '<hashedstring>:<salt>' or in phpass format
function ewr_ComparePassword($pwd, $input, $encrypted = FALSE) {
	global $EWR_RELATIVE_PATH;
	if ($encrypted)
		return $pwd == $input;
	if (preg_match('/^\$[HP]\$/', $pwd)) { // phpass
		include $EWR_RELATIVE_PATH . "passwordhash.php";
		$ar = json_decode(EWR_PHPASS_ITERATION_COUNT_LOG2);
		if (is_array($ar)) {
			foreach ($ar as $i) {
				$hasher = new PasswordHash($i, TRUE);
				if ($hasher->CheckPassword($input, $pwd))
					return TRUE;
			}
			return FALSE;
		}
	} elseif (strpos($pwd, ':') !== FALSE) { // <hashedstring>:<salt>
		@list($crypt, $salt) = explode(":", $pwd, 2);
		return ($pwd == ewr_EncryptPassword($input, $salt));
	} else {
		if (EWR_CASE_SENSITIVE_PASSWORD) {
			if (EWR_ENCRYPTED_PASSWORD) {
				if (EWR_PASSWORD_HASH)
					return password_verify($input, $pwd);
				else
					return ($pwd == ewr_EncryptPassword($input));
			} else {
				return ($pwd == $input);
			}
		} else {
			if (EWR_ENCRYPTED_PASSWORD) {
				if (EWR_PASSWORD_HASH)
					return password_verify(strtolower($input), $pwd);
				else
					return ($pwd == ewr_EncryptPassword(strtolower($input)));
			} else {
				return (strtolower($pwd) == strtolower($input));
			}
		}
	}
}

// Get domain URL
function ewr_DomainUrl() {
	$sUrl = "http";
	$bSSL = (ewr_ServerVar("HTTPS") <> "" && ewr_ServerVar("HTTPS") <> "off");
	$sPort = strval(ewr_ServerVar("SERVER_PORT"));
	if (ewr_ServerVar("HTTP_X_FORWARDED_PROTO") <> "" && strval(ewr_ServerVar("HTTP_X_FORWARDED_PORT")) <> "")
		$sPort = strval(ewr_ServerVar("HTTP_X_FORWARDED_PORT"));
	$defPort = ($bSSL) ? "443" : "80";
	$sPort = ($sPort == $defPort) ? "" : (":" . $sPort);
	$sUrl .= ($bSSL) ? "s" : "";
	$sUrl .= "://";
	$sUrl .= ewr_ServerVar("SERVER_NAME") . $sPort;
	return $sUrl;
}

// Get base URL
function ewr_BaseUrl() {
	$url = ewr_FullUrl();
	return substr($url, 0, strrpos($url, "/")+1);
}

// Get current URL
function ewr_CurrentUrl() {
	$s = ewr_ScriptName();
	$q = ewr_ServerVar("QUERY_STRING");
	if ($q <> "") $s .= "?" . $q;
	return $s;
}

// Is remote path
function ewr_IsRemote($Path) {
	global $EWR_REMOTE_FILE_PATTERN;
	return preg_match($EWR_REMOTE_FILE_PATTERN, $Path);
}

// Get full URL
function ewr_FullUrl($url = "", $type = "") {
	if (ewr_IsRemote($url))
		return $url;
	global $EWR_FULL_URL_PROTOCOLS;
	$sUrl = ewr_DomainUrl() . ewr_ScriptName();
	if ($url <> "")
		$sUrl = substr($sUrl, 0, strrpos($sUrl, "/") + 1) . $url;
	$protocol = @$EWR_FULL_URL_PROTOCOLS[$type];
	if ($protocol)
		$sUrl = preg_replace('/^\w+(?!:\/\/)/i', $protocol, $sUrl);
	return $sUrl;
}

// Get relative URL
function ewr_GetUrl($url) {
	global $EWR_RELATIVE_PATH;
	if ($url != "" && !ewr_StartsStr("/", $url) && !ewr_ContainsStr($url, "://") && !ewr_ContainsStr($url, "\\") && !ewr_ContainsStr($url, "javascript:")) {
		$path = "";
		if (strrpos($url, "/") !== FALSE) {
			$path = substr($url, 0, strrpos($url, "/"));
			$url = substr($url, strrpos($url, "/")+1);
		}
		$path = ewr_PathCombine($EWR_RELATIVE_PATH, $path, FALSE);
		if ($path <> "") $path = ewr_IncludeTrailingDelimiter($path, FALSE);
		return $path . $url;
	} else {
		return $url;
	}
}

// Get script name
function ewr_ScriptName() {
	$sn = ewr_ServerVar("PHP_SELF");
	if (empty($sn)) $sn = ewr_ServerVar("SCRIPT_NAME");
	if (empty($sn)) $sn = ewr_ServerVar("ORIG_PATH_INFO");
	if (empty($sn)) $sn = ewr_ServerVar("ORIG_SCRIPT_NAME");
	if (empty($sn)) $sn = ewr_ServerVar("REQUEST_URI");
	if (empty($sn)) $sn = ewr_ServerVar("URL");
	if (empty($sn)) $sn = "UNKNOWN";
	return $sn;
}

// Remove XSS
function ewr_RemoveXSS($val) {

	// Remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
	// This prevents some character re-spacing such as <java\0script>
	// Note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs

	$val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val);

	// Straight replacements, the user should never need these since they're normal characters
	// This prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>

	$search = 'abcdefghijklmnopqrstuvwxyz';
	$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$search .= '1234567890!@#$%^&*()';
	$search .= '~`";:?+/={}[]-_|\'\\';
	for ($i = 0; $i < strlen($search); $i++) {

		// ;? matches the ;, which is optional
		// 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
		// &#x0040 @ search for the hex values

		$val = preg_replace('/(&#[x|X]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // With a ;

		// &#00064 @ 0{0,7} matches '0' zero to seven times
		$val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // With a ;
	}

	// Now the only remaining whitespace attacks are \t, \n, and \r 
	$ra = $GLOBALS["EWR_XSS_ARRAY"]; // Note: Customize $EWR_XSS_ARRAY in ewrcfg*.php
	$found = true; // Keep replacing as long as the previous round replaced something
	while ($found == true) {
		$val_before = $val;
		for ($i = 0; $i < sizeof($ra); $i++) {
			$pattern = '/';
			for ($j = 0; $j < strlen($ra[$i]); $j++) { 
				if ($j > 0) {
					$pattern .= '('; 
					$pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?'; 
					$pattern .= '|(&#0{0,8}([9][10][13]);?)?'; 
					$pattern .= ')?'; 
				}
				$pattern .= $ra[$i][$j];
			}
			$pattern .= '/i';
			$replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // Add in <> to nerf the tag
			$val = preg_replace($pattern, $replacement, $val); // Filter out the hex tags
			if ($val_before == $val) {

				// No replacements were made, so exit the loop
				$found = false;
			}
		}
	}
	return $val;
}

// Check token
function ewr_CheckToken($token) {
	return (time() - intval(ewr_Decrypt($token))) < intval(ini_get("session.gc_maxlifetime"));
}

// Create token
function ewr_CreateToken() {
	return ewr_Encrypt(time());
}

// Load selection from a filter clause
function ewr_LoadSelectionFromFilter(&$fld, $filter, &$sel, $af = "") {
	$sel = "";
	if ($af <> "") { // Set up advanced filter first
		$ar = is_array($af) ? $af : array($af);
		$cnt = count($ar);
		for ($i = 0; $i < $cnt; $i++) {
			if (substr($ar[$i], 0, 2) == "@@") {
				if (!is_array($sel))
					$sel = array();
				$sel[] = $ar[$i];
			}
		}
	}
	if ($filter <> "") {
		$sSql = ewr_BuildReportSql($fld->SqlSelect, "", "", "", $fld->SqlOrderBy, $filter, "");
		ewr_LoadArrayFromSql($sSql, $sel);
	}
}

// Load drop down list
function ewr_LoadDropDownList(&$list, $val) {
	if (is_array($val)) {
		$ar = $val;
	} elseif ($val <> EWR_INIT_VALUE && $val <> EWR_ALL_VALUE && $val <> "") {
		$ar = array($val);
	} else {
		$ar = array();
	}
	$list = array();
	foreach ($ar as $v) {
		if ($v <> EWR_INIT_VALUE && $v <> "" && substr($v,0,2) <> "@@")
			$list[] = $v;
	}
}

// Load selection list
function ewr_LoadSelectionList(&$list, $val) {
	if (is_array($val)) {
		$ar = $val;
	} elseif ($val <> EWR_INIT_VALUE && $val <> "") {
		$ar = array($val);
	} else {
		$ar = array();
	}
	$list = array();
	foreach ($ar as $v) {
		if (ewr_SameStr($v, EWR_ALL_VALUE)) {
			$list = EWR_INIT_VALUE;
			return;
		} elseif ($v <> EWR_INIT_VALUE && $v <> "") {
			$list[] = $v;
		}
	}
	if (count($list) == 0)
		$list = EWR_INIT_VALUE;
}

// Get extended filter
function ewr_GetExtendedFilter(&$fld, $Default = FALSE, $dbid = 0) {
	$dbtype = ewr_GetConnectionType($dbid);
	$FldName = $fld->FldName;
	$FldExpression = $fld->FldExpression;
	$FldDataType = $fld->FldDataType;
	$FldDateTimeFormat = $fld->FldDateTimeFormat;
	$FldVal1 = ($Default) ? $fld->DefaultSearchValue : $fld->SearchValue;
	if (ewr_IsFloatFormat($fld->FldType)) $FldVal1 = ewr_StrToFloat($FldVal1);
	$FldOpr1 = ($Default) ? $fld->DefaultSearchOperator : $fld->SearchOperator;
	$FldCond = ($Default) ? $fld->DefaultSearchCondition : $fld->SearchCondition;
	$FldVal2 = ($Default) ? $fld->DefaultSearchValue2 : $fld->SearchValue2;
	if (ewr_IsFloatFormat($fld->FldType)) $FldVal2 = ewr_StrToFloat($FldVal2);
	$FldOpr2 = ($Default) ? $fld->DefaultSearchOperator2 : $fld->SearchOperator2;
	$sWrk = "";
	$FldOpr1 = strtoupper(trim($FldOpr1));
	if ($FldOpr1 == "") $FldOpr1 = "=";
	$FldOpr2 = strtoupper(trim($FldOpr2));
	if ($FldOpr2 == "") $FldOpr2 = "=";
	$wrkFldVal1 = $FldVal1;
	$wrkFldVal2 = $FldVal2;
	if ($FldDataType == EWR_DATATYPE_BOOLEAN) {
		if ($dbtype == "ACCESS") {
			if ($wrkFldVal1 <> "") $wrkFldVal1 = ($wrkFldVal1 == "1") ? "True" : "False";
			if ($wrkFldVal2 <> "") $wrkFldVal2 = ($wrkFldVal2 == "1") ? "True" : "False";
		} else {

			//if ($wrkFldVal1 <> "") $wrkFldVal1 = ($wrkFldVal1 == "1") ? EWR_TRUE_STRING : EWR_FALSE_STRING;
			//if ($wrkFldVal2 <> "") $wrkFldVal2 = ($wrkFldVal2 == "1") ? EWR_TRUE_STRING : EWR_FALSE_STRING;

			if ($wrkFldVal1 <> "") $wrkFldVal1 = ($wrkFldVal1 == "1") ? "1" : "0";
			if ($wrkFldVal2 <> "") $wrkFldVal2 = ($wrkFldVal2 == "1") ? "1" : "0";
		}
	} elseif ($FldDataType == EWR_DATATYPE_DATE) {
		if ($wrkFldVal1 <> "") $wrkFldVal1 = ewr_UnFormatDateTime($wrkFldVal1, $FldDateTimeFormat);
		if ($wrkFldVal2 <> "") $wrkFldVal2 = ewr_UnFormatDateTime($wrkFldVal2, $FldDateTimeFormat);
	}
	if ($FldOpr1 == "BETWEEN") {
		$IsValidValue = ($FldDataType <> EWR_DATATYPE_NUMBER ||
			($FldDataType == EWR_DATATYPE_NUMBER && is_numeric($wrkFldVal1) && is_numeric($wrkFldVal2)));
		if ($wrkFldVal1 <> "" && $wrkFldVal2 <> "" && $IsValidValue)
			$sWrk = $FldExpression . " BETWEEN " . ewr_QuotedValue($wrkFldVal1, $FldDataType, $dbid) .
				" AND " . ewr_QuotedValue($wrkFldVal2, $FldDataType, $dbid);
	} else {

		// Handle first value
		if (ewr_SameStr($FldVal1, EWR_NULL_VALUE) || $FldOpr1 == "IS NULL") {
			$sWrk = $FldExpression . " IS NULL";
		} elseif (ewr_SameStr($FldVal1, EWR_NOT_NULL_VALUE) || $FldOpr1 == "IS NOT NULL") {
			$sWrk = $FldExpression . " IS NOT NULL";
		} else {
			$IsValidValue = ($FldDataType <> EWR_DATATYPE_NUMBER ||
				($FldDataType == EWR_DATATYPE_NUMBER && is_numeric($wrkFldVal1)));
			if ($wrkFldVal1 <> "" && $IsValidValue && ewr_IsValidOpr($FldOpr1, $FldDataType))
				$sWrk = $FldExpression . ewr_FilterString($FldOpr1, $wrkFldVal1, $FldDataType, $dbid);
		}

		// Handle second value
		$sWrk2 = "";
		if (ewr_SameStr($FldVal2, EWR_NULL_VALUE) || $FldOpr2 == "IS NULL") {
			$sWrk2 = $FldExpression . " IS NULL";
		} elseif (ewr_SameStr($FldVal2, EWR_NOT_NULL_VALUE) || $FldOpr2 == "IS NOT NULL") {
			$sWrk2 = $FldExpression . " IS NOT NULL";
		} else {
			$IsValidValue = ($FldDataType <> EWR_DATATYPE_NUMBER ||
				($FldDataType == EWR_DATATYPE_NUMBER && is_numeric($wrkFldVal2)));
			if ($wrkFldVal2 <> "" && $IsValidValue && ewr_IsValidOpr($FldOpr2, $FldDataType))
				$sWrk2 = $FldExpression . ewr_FilterString($FldOpr2, $wrkFldVal2, $FldDataType, $dbid);
		}

		// Combine SQL
		if ($sWrk2 <> "") {
			if ($sWrk <> "")
				$sWrk = "(" . $sWrk . " " . (($FldCond == "OR") ? "OR" : "AND") . " " . $sWrk2 . ")";
			else
				$sWrk = $sWrk2;
		}
	}
	return $sWrk;
}

// Return search string
function ewr_FilterString($FldOpr, $FldVal, $FldType, $dbid = 0) {
	if (ewr_SameStr($FldVal, EWR_NULL_VALUE) || $FldOpr == "IS NULL") {
		return " IS NULL";
	} elseif (ewr_SameStr($FldVal, EWR_NOT_NULL_VALUE) || $FldOpr == "IS NOT NULL") {
		return " IS NOT NULL";
	} elseif ($FldOpr == "LIKE") {
		return ewr_Like(ewr_QuotedValue("%$FldVal%", $FldType, $dbid), $dbid);
	} elseif ($FldOpr == "NOT LIKE") {
		return " NOT " . ewr_Like(ewr_QuotedValue("%$FldVal%", $FldType, $dbid), $dbid);
	} elseif ($FldOpr == "STARTS WITH") {
		return ewr_Like(ewr_QuotedValue("$FldVal%", $FldType, $dbid), $dbid);
	} elseif ($FldOpr == "ENDS WITH") {
		return ewr_Like(ewr_QuotedValue("%$FldVal", $FldType, $dbid), $dbid);
	} else {
		return " $FldOpr " . ewr_QuotedValue($FldVal, $FldType, $dbid);
	}
}

// Append like operator
function ewr_Like($pat, $dbid = 0) {
	$dbtype = ewr_GetConnectionType($dbid);
	if ($dbtype == "POSTGRESQL") {
		return ((EWR_USE_ILIKE_FOR_POSTGRESQL) ? " ILIKE " : " LIKE ") . $pat;
	} elseif ($dbtype == "MYSQL") {
		if (EWR_LIKE_COLLATION_FOR_MYSQL <> "") {
			return " LIKE " . $pat . " COLLATE " . EWR_LIKE_COLLATION_FOR_MYSQL;
		} else {
			return " LIKE " . $pat;
		}
	} elseif ($dbtype == "MSSQL") {
		if (EWR_LIKE_COLLATION_FOR_MSSQL <> "") {
			return " COLLATE " . EWR_LIKE_COLLATION_FOR_MSSQL . " LIKE " . $pat;
		} else {
			return " LIKE " . $pat;
		}
	} else {
		return " LIKE " . $pat;
	}
}

// Return date search string
function ewr_DateFilterString($FldExpr, $FldOpr, $FldVal, $FldType, $dbid = 0) {
	if ($FldOpr == "Year" && $FldVal <> "") { // Year filter
		return ewr_GroupSql($FldExpr, "y", 0, $dbid) . " = " . $FldVal;
	} else {
		$wrkVal1 = ewr_DateVal($FldOpr, $FldVal, 1, $dbid);
		$wrkVal2 = ewr_DateVal($FldOpr, $FldVal, 2, $dbid);
		if ($wrkVal1 <> "" && $wrkVal2 <> "") {
			return $FldExpr . " BETWEEN " . ewr_QuotedValue($wrkVal1, $FldType, $dbid) . " AND " . ewr_QuotedValue($wrkVal2, $FldType, $dbid);
		} else {
			return "";
		}
	}
}

// Group filter
function ewr_GroupSql($FldExpr, $GrpType, $GrpInt = 0, $dbid = 0) {
	$dbtype = ewr_GetConnectionType($dbid);
	switch ($GrpType) {
		case "f": // First n characters
			if ($dbtype == "ACCESS") // Access
				return "MID(" . $FldExpr . ",1," . $GrpInt . ")";
			else if ($dbtype == "MSSQL" || $dbtype == "MYSQL") // MSSQL / MySQL
				return "SUBSTRING(" . $FldExpr . ",1," . $GrpInt . ")";
			else // PostgreSql / Oracle
				return "SUBSTR(" . $FldExpr . ",1," . $GrpInt . ")";
			break;
		case "i": // Interval
			if ($dbtype == "ACCESS") // Access
				return "(" . $FldExpr . "\\" . $GrpInt . ")";
			else if ($dbtype == "MSSQL") // MSSQL
				return "(" . $FldExpr . "/" . $GrpInt . ")";
			else if ($dbtype == "MYSQL") // MySQL
				return "(" . $FldExpr . " DIV " . $GrpInt . ")";
			else if ($dbtype == "POSTGRESQL") // PostgreSql
				return "(" . $FldExpr . "/" . $GrpInt . ")";
			else // Oracle
				return "FLOOR(" . $FldExpr . "/" . $GrpInt . ")";
			break;
		case "y": // Year
			if ($dbtype == "ACCESS" || $dbtype == "MSSQL" || $dbtype == "MYSQL") // Access / MSSQL / MySQL
				return "YEAR(" . $FldExpr . ")";
			else // PostgreSql / Oracle
				return "TO_CHAR(" . $FldExpr . ",'YYYY')";
			break;
		case "xq": // Quarter
			if ($dbtype == "ACCESS") // Access
				return "FORMAT(" . $FldExpr . ", 'q')";
			else if ($dbtype == "MSSQL") // MSSQL
				return "DATEPART(QUARTER," . $FldExpr . ")";
			else if ($dbtype == "MYSQL") // MySQL
				return "QUARTER(" . $FldExpr . ")";
			else // PostgreSql / Oracle
				return "TO_CHAR(" . $FldExpr . ",'Q')";
			break;
		case "q": // Quarter (with year)
			if ($dbtype == "ACCESS") // Access
				return "FORMAT(" . $FldExpr . ", 'yyyy|q')";
			else if ($dbtype == "MSSQL") // MSSQL
				return "(STR(YEAR(" . $FldExpr . "),4) + '|' + STR(DATEPART(QUARTER," . $FldExpr . "),1))";
			else if ($dbtype == "MYSQL") // MySQL
				return "CONCAT(CAST(YEAR(" . $FldExpr . ") AS CHAR(4)), '|', CAST(QUARTER(" . $FldExpr . ") AS CHAR(1)))";
			else // PostgreSql / Oracle
				return "(TO_CHAR(" . $FldExpr . ",'YYYY') || '|' || TO_CHAR(" . $FldExpr . ",'Q'))";
			break;
		case "xm": // Month
			if ($dbtype == "ACCESS") // Access
				return "FORMAT(" . $FldExpr . ", 'mm')";
			else if ($dbtype == "MSSQL" || $dbtype == "MYSQL") // MSSQL / MySQL
				return "MONTH(" . $FldExpr . ")";
			else // PostgreSql / Oracle
				return "TO_CHAR(" . $FldExpr . ",'MM')";
			break;
		case "m": // Month (with year)
			if ($dbtype == "ACCESS") // Access
				return "FORMAT(" . $FldExpr . ", 'yyyy|mm')";
			else if ($dbtype == "MSSQL") // MSSQL
				return "(STR(YEAR(" . $FldExpr . "),4) + '|' + REPLACE(STR(MONTH(" . $FldExpr . "),2,0),' ','0'))";
			else if ($dbtype == "MYSQL") // MySQL
				return "CONCAT(CAST(YEAR(" . $FldExpr . ") AS CHAR(4)), '|', CAST(LPAD(MONTH(" . $FldExpr . "),2,'0') AS CHAR(2)))";
			else // PostgreSql / Oracle
				return "(TO_CHAR(" . $FldExpr . ",'YYYY') || '|' || TO_CHAR(" . $FldExpr . ",'MM'))";
			break;
		case "w":
			if ($dbtype == "ACCESS") // Access
				return "FORMAT(" . $FldExpr . ", 'yyyy|ww')";
			else if ($dbtype == "MSSQL") // MSSQL
				return "(STR(YEAR(" . $FldExpr . "),4) + '|' + REPLACE(STR(DATEPART(WEEK," . $FldExpr . "),2,0),' ','0'))";
			else if ($dbtype == "MYSQL") // MySQL

				//return "CONCAT(CAST(YEAR(" . $FldExpr . ") AS CHAR(4)), '|', CAST(LPAD(WEEKOFYEAR(" . $FldExpr . "),2,'0') AS CHAR(2)))";
				return "CONCAT(CAST(YEAR(" . $FldExpr . ") AS CHAR(4)), '|', CAST(LPAD(WEEK(" . $FldExpr . ",0),2,'0') AS CHAR(2)))";
			else
				return "(TO_CHAR(" . $FldExpr . ",'YYYY') || '|' || TO_CHAR(" . $FldExpr . ",'WW'))";
			break;
		case "d":
			if ($dbtype == "ACCESS") // Access
				return "FORMAT(" . $FldExpr . ", 'yyyy|mm|dd')";
			else if ($dbtype == "MSSQL") // MSSQL
				return "(STR(YEAR(" . $FldExpr . "),4) + '|' + REPLACE(STR(MONTH(" . $FldExpr . "),2,0),' ','0') + '|' + REPLACE(STR(DAY(" . $FldExpr . "),2,0),' ','0'))";
			else if ($dbtype == "MYSQL") // MySQL
				return "CONCAT(CAST(YEAR(" . $FldExpr . ") AS CHAR(4)), '|', CAST(LPAD(MONTH(" . $FldExpr . "),2,'0') AS CHAR(2)), '|', CAST(LPAD(DAY(" . $FldExpr . "),2,'0') AS CHAR(2)))";
			else
				return "(TO_CHAR(" . $FldExpr . ",'YYYY') || '|' || LPAD(TO_CHAR(" . $FldExpr . ",'MM'),2,'0') || '|' || LPAD(TO_CHAR(" . $FldExpr . ",'DD'),2,'0'))";
			break;
		case "h":
			if ($dbtype == "ACCESS" || $dbtype == "MSSQL" || $dbtype == "MYSQL") // Access / MSSQL / MySQL
				return "HOUR(" . $FldExpr . ")";
			else
				return "TO_CHAR(" . $FldExpr . ",'HH24')";
			break;
		case "min":
			if ($dbtype == "ACCESS" || $dbtype == "MSSQL" || $dbtype == "MYSQL") // Access / MSSQL / MySQL
				return "MINUTE(" . $FldExpr . ")";
			else
				return "TO_CHAR(" . $FldExpr . ",'MI')";
			break;
	}
	return "";
}
/**
 * Validation functions
 */

// Check date format
// Format: std/stdshort/us/usshort/euro/euroshort
function ewr_CheckDateEx($value, $format, $sep) {
	if (strval($value) == "" || ewr_StartsStr("@@", $value))
		return TRUE;
	while (strpos($value, "  ") !== FALSE)
		$value = str_replace("  ", " ", $value);
	$value = trim($value);
	$arDT = explode(" ", $value);
	if (count($arDT) > 0) {
		if (preg_match('/^([0-9]{4})-([0][1-9]|[1][0-2])-([0][1-9]|[1|2][0-9]|[3][0|1])$/', $arDT[0], $matches)) { // Accept yyyy-mm-dd
			$sYear = $matches[1];
			$sMonth = $matches[2];
			$sDay = $matches[3];
		} else {
			$wrksep = "\\$sep";
			switch ($format) {
				case "std":
					$pattern = '/^([0-9]{4})' . $wrksep . '([0]?[1-9]|[1][0-2])' . $wrksep . '([0]?[1-9]|[1|2][0-9]|[3][0|1])$/';
					break;
				case "stdshort":
					$pattern = '/^([0-9]{2})' . $wrksep . '([0]?[1-9]|[1][0-2])' . $wrksep . '([0]?[1-9]|[1|2][0-9]|[3][0|1])$/';
					break;
				case "us":
					$pattern = '/^([0]?[1-9]|[1][0-2])' . $wrksep . '([0]?[1-9]|[1|2][0-9]|[3][0|1])' . $wrksep . '([0-9]{4})$/';
					break;
				case "usshort":
					$pattern = '/^([0]?[1-9]|[1][0-2])' . $wrksep . '([0]?[1-9]|[1|2][0-9]|[3][0|1])' . $wrksep . '([0-9]{2})$/';
					break;
				case "euro":
					$pattern = '/^([0]?[1-9]|[1|2][0-9]|[3][0|1])' . $wrksep . '([0]?[1-9]|[1][0-2])' . $wrksep . '([0-9]{4})$/';
					break;
				case "euroshort":
					$pattern = '/^([0]?[1-9]|[1|2][0-9]|[3][0|1])' . $wrksep . '([0]?[1-9]|[1][0-2])' . $wrksep . '([0-9]{2})$/';
					break;
			}
			if (!preg_match($pattern, $arDT[0])) return FALSE;
			$arD = explode($sep, $arDT[0]); // Change EWR_DATE_SEPARATOR to $sep
			switch ($format) {
				case "std":
				case "stdshort":
					$sYear = ewr_UnformatYear($arD[0]);
					$sMonth = $arD[1];
					$sDay = $arD[2];
					break;
				case "us":
				case "usshort":
					$sYear = ewr_UnformatYear($arD[2]);
					$sMonth = $arD[0];
					$sDay = $arD[1];
					break;
				case "euro":
				case "euroshort":
					$sYear = ewr_UnformatYear($arD[2]);
					$sMonth = $arD[1];
					$sDay = $arD[0];
					break;
			}
		}
		if (!ewr_CheckDay($sYear, $sMonth, $sDay)) return FALSE;
	}
	if (count($arDT) > 1 && !ewr_CheckTime($arDT[1])) return FALSE;
	return TRUE;
}

// Unformat 2 digit year to 4 digit year
function ewr_UnformatYear($yr) {
	if (strlen($yr) == 2) {
		if ($yr > EWR_UNFORMAT_YEAR)
			return "19" . $yr;
		else
			return "20" . $yr;
	} else {
		return $yr;
	}
}

// Check Date format (yyyy/mm/dd)
function ewr_CheckDate($value) {
	global $EWR_DATE_SEPARATOR;
	return ewr_CheckDateEx($value, "std", $EWR_DATE_SEPARATOR);
}

// Check Date format (yy/mm/dd)
function ewr_CheckShortDate($value) {
	global $EWR_DATE_SEPARATOR;
	return ewr_CheckDateEx($value, "stdshort", $EWR_DATE_SEPARATOR);
}

// Check US Date format (mm/dd/yyyy)
function ewr_CheckUSDate($value) {
	global $EWR_DATE_SEPARATOR;
	return ewr_CheckDateEx($value, "us", $EWR_DATE_SEPARATOR);
}

// Check US Date format (mm/dd/yy)
function ewr_CheckShortUSDate($value) {
	global $EWR_DATE_SEPARATOR;
	return ewr_CheckDateEx($value, "usshort", $EWR_DATE_SEPARATOR);
}

// Check Euro Date format (dd/mm/yyyy)
function ewr_CheckEuroDate($value) {
	global $EWR_DATE_SEPARATOR;
	return ewr_CheckDateEx($value, "euro", $EWR_DATE_SEPARATOR);
}

// Check Euro Date format (dd/mm/yy)
function ewr_CheckShortEuroDate($value) {
	global $EWR_DATE_SEPARATOR;
	return ewr_CheckDateEx($value, "euroshort", $EWR_DATE_SEPARATOR);
}

// Check default date format
function ewr_CheckDateDef($value) {
	global $EWR_DATE_FORMAT;
	if (preg_match('/^yyyy/', $EWR_DATE_FORMAT))
		return ewr_CheckDate($value);
	else if (preg_match('/^yy/', $EWR_DATE_FORMAT))
		return ewr_CheckShortDate($value);
	else if (preg_match('/^m/', $EWR_DATE_FORMAT) && preg_match('/yyyy$/', $EWR_DATE_FORMAT))
		return ewr_CheckUSDate($value);
	else if (preg_match('/^m/', $EWR_DATE_FORMAT) && preg_match('/yy$/', $EWR_DATE_FORMAT))
		return ewr_CheckShortUSDate($value);
	else if (preg_match('/^d/', $EWR_DATE_FORMAT) && preg_match('/yyyy$/', $EWR_DATE_FORMAT))
		return ewr_CheckEuroDate($value);
	else if (preg_match('/^d/', $EWR_DATE_FORMAT) && preg_match('/yy$/', $EWR_DATE_FORMAT))
		return ewr_CheckShortEuroDate($value);
	return false;
}

// Check day
function ewr_CheckDay($checkYear, $checkMonth, $checkDay) {
	$maxDay = 31;
	if ($checkMonth == 4 || $checkMonth == 6 ||	$checkMonth == 9 || $checkMonth == 11) {
		$maxDay = 30;
	} elseif ($checkMonth == 2)	{
		if ($checkYear % 4 > 0) {
			$maxDay = 28;
		} elseif ($checkYear % 100 == 0 && $checkYear % 400 > 0) {
			$maxDay = 28;
		} else {
			$maxDay = 29;
		}
	}
	return ewr_CheckRange($checkDay, 1, $maxDay);
}

// Check integer
function ewr_CheckInteger($value) {
	global $EWR_DECIMAL_POINT;
	if (strval($value) == "") return TRUE;
	if (strpos($value, $EWR_DECIMAL_POINT) !== FALSE)
		return FALSE;
	return ewr_CheckNumber($value);
}

// Check number
function ewr_CheckNumber($value) {
	global $EWR_THOUSANDS_SEP, $EWR_DECIMAL_POINT;
	if (strval($value) == "") return TRUE;
	$pat = '/^[+-]?(\d{1,3}(' . (($EWR_THOUSANDS_SEP) ? '\\' . $EWR_THOUSANDS_SEP . '?' : '') . '\d{3})*(\\' .
		$EWR_DECIMAL_POINT . '\d+)?|\\' . $EWR_DECIMAL_POINT . '\d+)$/';
	return preg_match($pat, $value);
}

// Check range
function ewr_CheckRange($value, $min, $max) {
	if (strval($value) == "") return TRUE;
	if (is_int($min) || is_float($min) || is_int($max) || is_float($max)) { // Number
		if (ewr_CheckNumber($value))
			$value = floatval(ewr_StrToFloat($value));
	}
	if ((!is_null($min) && $value < $min) || (!is_null($max) && $value > $max))
		return FALSE;
	return TRUE;
}

// Check time
function ewr_CheckTime($value) {
	if (strval($value) == "") return TRUE;
	return preg_match('/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/', $value);
}

// Check US phone number
function ewr_CheckPhone($value) {
	if (strval($value) == "") return TRUE;
	return preg_match('/^\(\d{3}\) ?\d{3}( |-)?\d{4}|^\d{3}( |-)?\d{3}( |-)?\d{4}$/', $value);
}

// Check US zip code
function ewr_CheckZip($value) {
	if (strval($value) == "") return TRUE;
	return preg_match('/^\d{5}$|^\d{5}-\d{4}$/', $value);
}

// Check credit card
function ewr_CheckCreditCard($value, $type="") {
	if (strval($value) == "") return TRUE;
	$creditcard = array("visa" => "/^4\d{3}[ -]?\d{4}[ -]?\d{4}[ -]?\d{4}$/",
		"mastercard" => "/^5[1-5]\d{2}[ -]?\d{4}[ -]?\d{4}[ -]?\d{4}$/",
		"discover" => "/^6011[ -]?\d{4}[ -]?\d{4}[ -]?\d{4}$/",
		"amex" => "/^3[4,7]\d{13}$/",
		"diners" => "/^3[0,6,8]\d{12}$/",
		"bankcard" => "/^5610[ -]?\d{4}[ -]?\d{4}[ -]?\d{4}$/",
		"jcb" => "/^[3088|3096|3112|3158|3337|3528]\d{12}$/",
		"enroute" => "/^[2014|2149]\d{11}$/",
		"switch" => "/^[4903|4911|4936|5641|6333|6759|6334|6767]\d{12}$/");
	if (empty($type))	{
		$match = FALSE;
		foreach ($creditcard as $type => $pattern) {
			if (@preg_match($pattern, $value) == 1) {
				$match = TRUE;
				break;
			}
		}
		return ($match) ? ewr_CheckSum($value) : FALSE;
	}	else {
		if (!preg_match($creditcard[strtolower(trim($type))], $value)) return FALSE;
		return ewr_CheckSum($value);
	}
}

// Check sum
function ewr_CheckSum($value) {
	$value = str_replace(array('-',' '), array('',''), $value);
	$checksum = 0;
	for ($i=(2-(strlen($value) % 2)); $i<=strlen($value); $i+=2)
		$checksum += (int)($value[$i-1]);
	for ($i=(strlen($value)%2)+1; $i <strlen($value); $i+=2) {
		$digit = (int)($value[$i-1]) * 2;
		$checksum += ($digit < 10) ? $digit : ($digit-9);
	}
	return ($checksum % 10 == 0);
}

// Check US social security number
function ewr_CheckSSC($value) {
	if (strval($value) == "") return TRUE;
	return preg_match('/^(?!000)([0-6]\d{2}|7([0-6]\d|7[012]))([ -]?)(?!00)\d\d\3(?!0000)\d{4}$/', $value);
}

// Check emails
function ewr_CheckEmailList($value, $email_cnt) {
	if (strval($value) == "") return TRUE;
	$emailList = str_replace(",", ";", $value);
	$arEmails = explode(";", $emailList);
	$cnt = count($arEmails);
	if ($cnt > $email_cnt && $email_cnt > 0)
		return FALSE;
	foreach ($arEmails as $email) {
		if (!ewr_CheckEmail($email))
			return FALSE;
	}
	return TRUE;
}

// Check email
function ewr_CheckEmail($value) {
	if (strval($value) == "") return TRUE;
	return preg_match('/^[\w.%+-]+@[\w.-]+\.[A-Z]{2,18}$/i', trim($value));
}

// Check GUID
function ewr_CheckGUID($value) {
	if (strval($value) == "") return TRUE;
	$p1 = '/^\{\w{8}-\w{4}-\w{4}-\w{4}-\w{12}\}$/';
	$p2 = '/^\w{8}-\w{4}-\w{4}-\w{4}-\w{12}$/';
	return preg_match($p1, $value) || preg_match($p2, $value);
}

// Check by preg
function ewr_CheckByRegEx($value, $pattern) {
	if (strval($value) == "") return TRUE;
	return preg_match($pattern, $value);
}
/**
 * End Validation functions
 */

// Write the paths for config/debug only
function ewr_WritePaths() {
	global $EWR_ROOT_RELATIVE_PATH, $EWR_RELATIVE_PATH;
	echo 'EWR_RELATIVE_PATH = ' . $EWR_RELATIVE_PATH . "<br>";
	echo 'EW_ROOT_RELATIVE_PATH = ' . $EWR_ROOT_RELATIVE_PATH . "<br>";
	echo "ewr_AppRoot() = " . ewr_AppRoot() . "<br>";
	echo "realpath('.') = " . realpath(".") . "<br>";
	echo "DOCUMENT_ROOT = " . ewr_ServerVar("DOCUMENT_ROOT") . "<br>";
	echo "__FILE__ = " . __FILE__ . "<br>";
}

// Write info for config/debug only
function ewr_Info() {
	global $Security;
	ewr_WritePaths();
	echo "CurrentUserName() = " . CurrentUserName() . "<br>";
	echo "CurrentUserID() = " . CurrentUserID() . "<br>";
	echo "CurrentParentUserID() = " . CurrentParentUserID() . "<br>";
	echo "IsLoggedIn() = " . (IsLoggedIn() ? "TRUE" : "FALSE") . "<br>";
	echo "IsAdmin() = " . (IsAdmin() ? "TRUE" : "FALSE") . "<br>";
	echo "IsSysAdmin() = " . (IsSysAdmin() ? "TRUE" : "FALSE") . "<br>";
	if (isset($Security))
		$Security->ShowUserLevelInfo();
}

// Upload path
// If PhyPath is TRUE(1), return physical path on the server
// If PhyPath is FALSE(0), return relative URL
function ewr_UploadPathEx($PhyPath, $DestPath) {
	global $EWR_ROOT_RELATIVE_PATH;
	if ($PhyPath) {
		$PhyPath = !ewr_IsRemote($DestPath); // Not remote
		if ($PhyPath)
			$DestPath = str_replace("/", EWR_PATH_DELIMITER, $DestPath);
		$Path = ewr_PathCombine(ewr_AppRoot(), $DestPath, $PhyPath);
	} else {
		$Path = ewr_PathCombine($EWR_ROOT_RELATIVE_PATH, $DestPath, FALSE);
	}
	return ewr_IncludeTrailingDelimiter($Path, $PhyPath);
}

// Get a temp folder for temp file
function ewr_TmpFolder() {
	$tmpfolder = NULL;
	$folders = array();
	if (EWR_IS_WINDOWS) {
		$folders[] = ewr_ServerVar("TEMP");
		$folders[] = ewr_ServerVar("TMP");
	} else {
		if (EWR_UPLOAD_TMP_PATH <> "") $folders[] = ewr_AppRoot() . str_replace("/", EWR_PATH_DELIMITER, EWR_UPLOAD_TMP_PATH);
		$folders[] = '/tmp';
	}
	if (ini_get('upload_tmp_dir')) {
		$folders[] = ini_get('upload_tmp_dir');
	}
	foreach ($folders as $folder) {
		if (!$tmpfolder && is_dir($folder)) {
			$tmpfolder = $folder;
		}
	}

	//if ($tmpfolder) $tmpfolder = ewr_IncludeTrailingDelimiter($tmpfolder, TRUE);
	return $tmpfolder;
}

// Field data type
function ewr_FieldDataType($fldtype) {
	switch ($fldtype) {
		case 20:
		case 3:
		case 2:
		case 16:
		case 4:
		case 5:
		case 131:
		case 139:
		case 6:
		case 17:
		case 18:
		case 19:
		case 21: // Numeric
			return EWR_DATATYPE_NUMBER;
		case 7:
		case 133:
		case 135: // Date
		case 146: // DateTiemOffset
			return EWR_DATATYPE_DATE;
		case 134: // Time
		case 145: // Time
			return EWR_DATATYPE_TIME;
		case 201:
		case 203: // Memo
			return EWR_DATATYPE_MEMO;
		case 129:
		case 130:
		case 200:
		case 202: // String
			return EWR_DATATYPE_STRING;
		case 11: // Boolean
			return EWR_DATATYPE_BOOLEAN;
		case 72: // GUID
			return EWR_DATATYPE_GUID;
		case 128:
		case 204:
		case 205: // Binary
			return EWR_DATATYPE_BLOB;
		default:
			return EWR_DATATYPE_OTHER;
	}
}

// Application root
function ewr_AppRoot() {
	global $EWR_ROOT_RELATIVE_PATH;

	// 1. use root relative path
	if ($EWR_ROOT_RELATIVE_PATH <> "") {
		$Path = realpath($EWR_ROOT_RELATIVE_PATH);
		$Path = str_replace("\\\\", EWR_PATH_DELIMITER, $Path);
	} else {
		$Path = realpath(".");
	}

	// 2. if empty, use the document root if available
	if (empty($Path)) $Path = ewr_ServerVar("DOCUMENT_ROOT");

	// 3. if empty, use current folder
	if (empty($Path)) $Path = realpath(".");

	// 4. use custom path, uncomment the following line and enter your path
	// E.g. $Path = 'C:\Inetpub\wwwroot\MyWebRoot'; // Windows
	//$Path = 'enter your path here';

	if (empty($Path)) die("Path of website root unknown.");
	return ewr_IncludeTrailingDelimiter($Path, TRUE);
}

// Get path relative to application root
function ewr_ServerMapPath($path, $isFile = FALSE) {
	$pathinfo = ewr_IsRemote($path) ? array() : pathinfo($path);
	if ($isFile || @$pathinfo["extension"] <> "") // File
		return ewr_UploadPathEx(TRUE, $pathinfo["dirname"]) . $pathinfo["basename"];
	else // Folder
		return ewr_UploadPathEx(TRUE, $path);
}

// Get path relative to a base path
function ewr_PathCombine($BasePath, $RelPath, $PhyPath) {
	if (ewr_IsRemote($RelPath)) // Allow remote file
		return $RelPath;
	$PhyPath = !ewr_IsRemote($BasePath) && $PhyPath;
	$Delimiter = ($PhyPath) ? EWR_PATH_DELIMITER : '/';
	if ($BasePath <> $Delimiter) // If BasePath = root, do not remove delimiter
		$BasePath = ewr_RemoveTrailingDelimiter($BasePath, $PhyPath);
	$RelPath = ($PhyPath) ? str_replace(array('/', '\\'), EWR_PATH_DELIMITER, $RelPath) : str_replace('\\', '/', $RelPath);
	$RelPath = ewr_IncludeTrailingDelimiter($RelPath, $PhyPath);
	$p1 = strpos($RelPath, $Delimiter);
	$Path2 = "";
	while ($p1 !== FALSE) {
		$Path = substr($RelPath, 0, $p1 + 1);
		if ($Path == $Delimiter || $Path == '.' . $Delimiter) {

			// Skip
		} elseif ($Path == '..' . $Delimiter) {
			$p2 = strrpos($BasePath, $Delimiter);
			if ($p2 === 0) // BasePath = "/xxx", cannot move up
				$BasePath = $Delimiter;
			elseif ($p2 !== FALSE && substr($BasePath, -2) <> "..")
				$BasePath = substr($BasePath, 0, $p2);
			elseif ($BasePath <> "" && $BasePath <> "." && $BasePath <> "..")
				$BasePath = "";
			else
				$Path2 .= ".." . $Delimiter;
		} else {
			$Path2 .= $Path;
		}
		$RelPath = substr($RelPath, $p1+1);
		if ($RelPath === FALSE)
			$RelPath = "";
		$p1 = strpos($RelPath, $Delimiter);
	}
	return (($BasePath === "" || $BasePath === ".") ? "" : ewr_IncludeTrailingDelimiter($BasePath, $PhyPath)) . $Path2 . $RelPath;
}

// Remove the last delimiter for a path
function ewr_RemoveTrailingDelimiter($Path, $PhyPath) {
	$Delimiter = ($PhyPath) ? EWR_PATH_DELIMITER : '/';
	while (substr($Path, -1) == $Delimiter)
		$Path = substr($Path, 0, strlen($Path)-1);
	return $Path;
}

// Include the last delimiter for a path
function ewr_IncludeTrailingDelimiter($Path, $PhyPath) {
	$Path = ewr_RemoveTrailingDelimiter($Path, $PhyPath);
	$Delimiter = ($PhyPath) ? EWR_PATH_DELIMITER : '/';
	return $Path . $Delimiter;
}

// Get script physical folder
function ewr_ScriptFolder() {
	$folder = "";
	$path = ewr_ServerVar("SCRIPT_FILENAME");
	$p = strrpos($path, EWR_PATH_DELIMITER);
	if ($p !== FALSE)
		$folder = substr($path, 0, $p);
	return ($folder <> "") ? $folder : realpath(".");
}

// Create folder
function ewr_CreateFolder($dir, $mode = 0777) {
	return (is_dir($dir) || @mkdir($dir, $mode, TRUE));
}

// Save file
function ewr_SaveFile($folder, $fn, $filedata) {
	$res = FALSE;
	if (ewr_CreateFolder($folder)) {
		if ($handle = fopen($folder . $fn, 'w')) { // P6
			$res = fwrite($handle, $filedata);
		fclose($handle);
		}
		if ($res)
			chmod($folder . $fn, EWR_UPLOADED_FILE_MODE);
	}
	return $res;
}

// Init array
function &ewr_InitArray($len, $value) {
	if ($len > 0)
		$ar = array_fill(0, $len, $value);
	else
		$ar = array();
	return $ar;
}

// Init 2D array
function &ewr_Init2DArray($len1, $len2, $value) {
	return ewr_InitArray($len1, ewr_InitArray($len2, $value));
}

// Function to generate random number
function ewr_Random() {
	return mt_rand();
}

// Check if float format
function ewr_IsFloatFormat($FldType) {
	return ($FldType == 4 || $FldType == 5 || $FldType == 131 || $FldType == 6);
}

// Convert string to float
function ewr_StrToFloat($v) {
	global $EWR_THOUSANDS_SEP, $EWR_DECIMAL_POINT;
	$v = str_replace(" ", "", $v);
	$v = str_replace(array($EWR_THOUSANDS_SEP, $EWR_DECIMAL_POINT), array("", "."), $v);
	return $v;
}

// Concat string
function ewr_Concat($str1, $str2, $sep) {
	$str1 = trim($str1);
	$str2 = trim($str2);
	if ($str1 <> "" && $sep <> "" && substr($str1, -1 * strlen($sep)) <> $sep)
		$str1 .= $sep;
	return $str1 . $str2;
}

// Contains a substring (case-sensitive)
function ewr_ContainsStr($haystack, $needle, $offset = 0) {
	return strpos($haystack, $needle, $offset) !== FALSE;
}

// Contains a substring (case-insensitive)
function ewr_ContainsText($haystack, $needle, $offset = 0) {
	return stripos($haystack, $needle, $offset) !== FALSE;
}

// Starts with a substring (case-sensitive)
function ewr_StartsStr($needle, $haystack) {
	return strpos($haystack, $needle) === 0;
}

// Starts with a substring (case-insensitive)
function ewr_StartsText($needle, $haystack) {
	return stripos($haystack, $needle) === 0;
}

// Ends with a substring (case-sensitive)
function ewr_EndsStr($needle, $haystack) {
	return strrpos($haystack, $needle) === strlen($haystack) - strlen($needle);
}

// Ends with a substring (case-insensitive)
function ewr_EndsText($needle, $haystack) {
	return strripos($haystack, $needle) === strlen($haystack) - strlen($needle);
}

// Same trimmed strings (case-sensitive)
function ewr_SameStr($str1, $str2) {
	return strcmp(trim($str1), trim($str2)) === 0;
}

// Same trimmed strings (case-insensitive)
function ewr_SameText($str1, $str2) {
	return strcasecmp(trim($str1), trim($str2)) === 0;
}

// Convert different data type value
function ewr_Conv($v, $t) {
	switch ($t) {
		case 2:
		case 3:
		case 16:
		case 17:
		case 18:
		case 19: // adSmallInt/adInteger/adTinyInt/adUnsignedTinyInt/adUnsignedSmallInt
			return (is_null($v)) ? NULL : intval($v);
		case 4:
		case 5:
		case 6:
		case 131:
		case 139: // adSingle/adDouble/adCurrency/adNumeric/adVarNumeric
			return (is_null($v)) ? NULL : (float)$v;
		default:
			return (is_null($v)) ? NULL : $v;
	}
}

// Convert byte array to binary string
function ewr_BytesToStr($bytes) {
	$str = "";
	foreach ($bytes as $byte)
		$str .= chr($byte);
	return $str;
}

// Create temp image file from binary data
function ewr_TmpImage(&$filedata) {
	global $grTmpImages;
	$export = "";
	if (@$_GET["export"] <> "")
		$export = $_GET["export"];
	elseif (@$_POST["export"] <> "")
		$export = $_POST["export"];
	elseif (@$_POST["customexport"] <> "")
		$export = $_POST["customexport"];
	$folder = ewr_AppRoot() . EWR_UPLOAD_DEST_PATH;
	$f = tempnam($folder, "tmp");
	$handle = fopen($f, 'w+');
	fwrite($handle, $filedata);
	fclose($handle);
	$info = getimagesize($f);
	switch ($info[2]) {
	case 1:
		rename($f, $f .= '.gif'); break;
	case 2:
		rename($f, $f .= '.jpg'); break;
	case 3:
		rename($f, $f .= '.png'); break;
	case 6:
		rename($f, $f .= '.bmp'); break;
	default:
		return "";
	}
	$tmpimage = basename($f);
	$grTmpImages[] = $tmpimage;

	//return ewr_TmpImageLnk($tmpimage);
	return ewr_TmpImageLnk($tmpimage, $export);
}

// Get temp chart image
function ewr_TmpChartImage($id, $custom = FALSE) {
	global $grTmpImages;
	$exportid = "";
	if (@$_GET["exportid"] <> "")
		$exportid = $_GET["exportid"];
	elseif (@$_POST["exportid"] <> "")
		$exportid = $_POST["exportid"];
	$export = "";
	if ($custom)
		$export = "print";
	elseif (@$_GET["export"] <> "")
		$export = $_GET["export"];
	elseif (@$_POST["export"] <> "")
		$export = $_POST["export"];
	if ($exportid <> "") {
		$file = $exportid . "_" . $id . ".png"; // v8
		$folder = ewr_AppRoot() . EWR_UPLOAD_DEST_PATH;
		$f = $folder . $file;
		if (file_exists($f)) {
			$tmpimage = basename($f);
			$grTmpImages[] = $tmpimage;

			//return ewr_TmpImageLnk($tmpimage);
			return ewr_TmpImageLnk($tmpimage, $export);
		}
		return "";
	}
}

// Delete temp images
function ewr_DeleteTmpImages($html = "") {
	global $grTmpImages;
	foreach ($grTmpImages as $tmpimage)
		@unlink(ewr_AppRoot() . EWR_UPLOAD_DEST_PATH . $tmpimage);

	// Check and remove temp images from html content (start with session id)
	if (preg_match_all('/<img([^>]*)>/i', $html, $matches, PREG_SET_ORDER)) {
		foreach ($matches as $match) {
			if (preg_match('/\s+src\s*=\s*[\'"]([\s\S]*?)[\'"]/i', $match[1], $submatches)) { // Match src='src'
				$src = $submatches[1];
				$exportid = session_id();
				$src = basename($src);
				if (substr($src,0,strlen($exportid)) == $exportid || substr($src,0,3) == "tmp") { // Temp image
					@unlink(ewr_AppRoot() . EWR_UPLOAD_DEST_PATH . $src);
				}
			}
		}
	}
}

// Get temp image link
function ewr_TmpImageLnk($file, $lnktype = "") {
	global $EWR_ROOT_RELATIVE_PATH;
	if ($file == "") return "";
	if ($lnktype == "email" || $lnktype == "cid") {
		$ar = explode('.', $file);
		$lnk = implode(".", array_slice($ar, 0, count($ar)-1));
		if ($lnktype == "email") $lnk = "cid:" . $lnk;
		return $lnk;
	} else {
		$fn = EWR_UPLOAD_DEST_PATH . $file;
		if ($EWR_ROOT_RELATIVE_PATH <> ".") $fn = $EWR_ROOT_RELATIVE_PATH . "/" . $fn;
		return $fn;
	}
}

// Check empty string
function ewr_EmptyStr($value) {
	$str = strval($value);
	$str = str_replace("&nbsp;", "", $str);
	return (trim($str) == "");
}

// Get file img tag
function ewr_GetFileImgTag($fld, $fn) {
	$html = "";
	if ($fn <> "") {
		if ($fld->FldDataType <> EWR_DATATYPE_BLOB) {
			$wrkfiles = explode(EWR_MULTIPLE_UPLOAD_SEPARATOR, $fn);
			foreach ($wrkfiles as $wrkfile) {
				if ($wrkfile <> "") {
					if ($html <> "")
						$html .= "<br>";
					$html .= "<img class=\"ewImage\" src=\"" . $wrkfile . "\" alt=\"\">";
				}
			}
		} else {
			$html = "<img class=\"ewImage\" src=\"" . $fn . "\" alt=\"\">";
		}
	}
	return $html;
}

// Get file temp image
function ewr_GetFileTempImage($fld, $val) {
	if ($fld->FldDataType == EWR_DATATYPE_BLOB) {
		$wrkdata = $fld->DbValue;
		if (!empty($wrkdata)) {
			if ($fld->ImageResize) {
				$wrkwidth = $fld->ImageWidth;
				$wrkheight = $fld->ImageHeight;
				ewr_ResizeBinary($wrkdata, $wrkwidth, $wrkheight);
			}
			return ewr_TmpImage($wrkdata);
		}
	} else {
		if (!empty($val)) {
			$files = explode(EWR_MULTIPLE_UPLOAD_SEPARATOR, $val);
			$cnt = count($files);
			$images = "";
			for ($i = 0; $i < $cnt; $i++) {
				if ($files[$i] <> "") {
					$tmpimage = file_get_contents($fld->PhysicalUploadPath() . $files[$i]);
					if ($fld->ImageResize)
						ewr_ResizeBinary($tmpimage, $fld->ImageWidth, $fld->ImageHeight);
					if ($images <> "") $images .= EWR_MULTIPLE_UPLOAD_SEPARATOR;
					$images .= ewr_TmpImage($tmpimage);
				}
			}
			return $images;
		}
	}
}

// Get file upload url
function ewr_GetFileUploadUrl($fld, $val, $resize = FALSE) {
	if (!ewr_EmptyStr($val)) {
		$encrypt = EWR_ENCRYPT_FILE_PATH;
		$path = ($encrypt || $resize) ? $fld->PhysicalUploadPath() : $fld->HrefPath();
		$key = EWR_RANDOM_KEY . session_id();
		if ($encrypt) {
			$fn = EWR_FILE_URL . "?t=" . ewr_Encrypt($fld->TblName, $key) ."&fn=" . ewr_Encrypt($path . $val, $key);
			if ($resize)
				$fn .= "&width=" . $fld->ImageWidth . "&height=" . $fld->ImageHeight;
		} elseif ($resize) {
			$fn = EWR_FILE_URL . "?t=" . rawurlencode($fld->TblName) . "&fn=" . ewr_Encrypt($path . $val, $key) .
				"&width=" . $fld->ImageWidth . "&height=" . $fld->ImageHeight; // Encrypt the physical path
		} else {
			$fn = ewr_IsRemote($path) ? $path : ewr_UrlEncodeFilePath($path);
			$fn .= ewr_UrlEncodeFilePath($val);
		}
		return $fn;
	} else {
		return "";
	}
}

// URL Encode file path
function ewr_UrlEncodeFilePath($path) {
	$ar = explode("/", $path);
	$scheme = parse_url($path, PHP_URL_SCHEME);
	foreach ($ar as &$c) {
		if ($c <> $scheme . ":")
			$c = rawurlencode($c);
	}
	return implode("/", $ar);
}

// Get file view tag
function ewr_GetFileViewTag(&$fld, $val) {
	global $Page, $ReportLanguage;
	if (!ewr_EmptyStr($val)) {
		if ($fld->FldDataType <> EWR_DATATYPE_BLOB)
			$wrkfiles = explode(EWR_MULTIPLE_UPLOAD_SEPARATOR, $val);
		else
			$wrkfiles = array($val);
		$bMultiple = (count($wrkfiles) > 1);
		$href = $fld->HrefValue;
		$images = "";
		foreach ($wrkfiles as $wrkfile) {
			if ($Page && ($Page->Export == "pdf" || $Page->CustomExport == "pdf" || $Page->Export == "word" && defined('EWR_USE_PHPWORD') || $Page->Export == "excel" && defined('EWR_USE_PHPEXCEL')))
				$fn = ewr_GetFileTempImage($fld, $wrkfile);
			elseif ($Page && ($Page->Export == "email" && $GLOBALS["gsEmailContentType"] == "html" || $Page->CustomExport == "email") && $fld->ImageResize)
				$fn = ewr_GetFileTempImage($fld, $wrkfile);
			elseif ($fld->FldDataType == EWR_DATATYPE_BLOB)
				$fn = $val;
			else
				$fn = ewr_GetFileUploadUrl($fld, $wrkfile, $fld->ImageResize);
			if ($Page && $Page->Export == "word" && !defined('EWR_USE_PHPWORD') || $Page->Export == "excel" && !defined('EWR_USE_PHPEXCEL')) {
				$bShowImage = FALSE;
				if (EWR_ENCRYPT_FILE_PATH) // Image not accessible
					$fn = "";
				elseif ($fld->FldDataType == EWR_DATATYPE_BLOB) // Use url
					$fn = ewr_FullUrl($val, "export");
				else // Use original path
					$fn = ewr_FullUrl($fld->PhysicalUploadPath() . $wrkfile, "export");
			} else {
				$bShowImage = ($fld->IsBlobImage || ewr_IsImageFile($wrkfile));
			}
			if ($bShowImage) {
				if ($href == "" && $fld->DrillDownUrl == "" && !$fld->UseColorbox) {
					$image = "<img class=\"ewImage\" alt=\"\" src=\"" . $fn . "\"" . $fld->ViewAttributes() . ">";
				} else {
					if ($fld->FldDataType <> EWR_DATATYPE_BLOB && strpos($href, "%u") !== FALSE)
						$fld->HrefValue = str_replace("%u", ewr_GetFileUploadUrl($fld, $wrkfile), $href);
					$image = "<a" . $fld->LinkAttributes() . "><img class=\"ewImage\" alt=\"\" src=\"" . $fn . "\"" . $fld->ViewAttributes() . "></a>";
				}
			} else {
				if ($fld->FldDataType == EWR_DATATYPE_BLOB) {
					$url = $fn;
					$name = $fld->FldCaption();
				} else {
					$url = ewr_GetFileUploadUrl($fld, $wrkfile, FALSE);
					$name = basename($wrkfile);
				}
				$image = ($url <> "") ? "<a href=\"" . $url . "\">" . $name . "</a>" : $name;
			}
			if ($bMultiple)
				$images .= "<li>" . $image . "</li>";
			else
				$images .= $image;
		}
		if ($bMultiple && $images <> "")
			$images = "<ul class=\"list-inline\">" . $images . "</ul>";
		return $images;
	} else {
		if ($fld->FldDataType == EWR_DATATYPE_BLOB && !is_null($fld->DbValue))
			return $ReportLanguage->Phrase("PrimaryKeyUnspecified");
		else
			return "";
	}
}

// Check if image file
function ewr_IsImageFile($fn) {
	if ($fn <> "") {
		if (substr($fn,0,4) == "cid:") // Embedded image for email
			return TRUE;
		$ar = parse_url($fn);
		if ($ar && array_key_exists('query', $ar)) { // Thumbnail url
 			if ($q = parse_str($ar['query']))
				$fn = $q['fn'];
		}
		$pathinfo = pathinfo($fn);
		$ext = strtolower(@$pathinfo["extension"]);
		return in_array($ext, explode(",", EWR_IMAGE_ALLOWED_FILE_EXT));
	} else {
		return FALSE;
	}
}

// HTTP request by cURL
// Note: cURL must be enabled in PHP
function ewr_ClientUrl($url, $postdata = "", $method = "GET") {
	global $data;
	if (!function_exists("curl_init"))
		die("cURL not installed.");
	$ch = curl_init();
	$method = strtoupper($method);
	if ($method == "POST") {
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	} elseif ($method == "GET") {
		curl_setopt($ch, CURLOPT_URL, $url . "?" . $postdata);
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$res = curl_exec($ch);
	curl_close($ch);
	return $res;
}

// Set client variable
function ewr_SetClientVar($name, $value) {
	global $EWR_CLIENT_VAR;
	if (strval($name) <> "")
		$EWR_CLIENT_VAR[strval($name)] = $value;
}

// Get session timeout time (seconds)
function ewr_SessionTimeoutTime() {
	if (EWR_SESSION_TIMEOUT > 0) // User specified timeout time
		$mlt = EWR_SESSION_TIMEOUT * 60;
	else // Get max life time from php.ini
		$mlt = intval(ini_get("session.gc_maxlifetime"));
	if ($mlt <= 0)
		$mlt = 1440; // PHP default (1440s = 24min)
	return $mlt - 30; // Add some safety margin
}
?>
<?php
/**
 * Functions for image resize
 */

// Resize binary to thumbnail
function ewr_ResizeBinary(&$filedata, &$width, &$height, $quality = EWR_THUMBNAIL_DEFAULT_QUALITY, $plugins = array()) {
	global $EWR_THUMBNAIL_CLASS, $EWR_RESIZE_OPTIONS;
	if ($width <= 0 && $height <= 0)
		return FALSE;
	$f = tempnam(ewr_TmpFolder(), "tmp");
	$handle = @fopen($f, 'wb');
	if ($handle) {
		fwrite($handle, $filedata);
		fclose($handle);
	}
	$format = "";
	if (file_exists($f) && filesize($f) > 0) { // temp file created
		$info = @getimagesize($f);
		@unlink($f);
		if (!$info || !in_array($info[2], array(1, 2, 3))) { // not gif/jpg/png
			return FALSE;
		} elseif ($info[2] == 1) {
			$format = "GIF";
		} elseif ($info[2] == 2) {
			$format = "JPG";
		} elseif ($info[2] == 3) {
			$format = "PNG";
		}
	} else { // temp file not created
		if (substr($filedata, 0, 6) == "\x47\x49\x46\x38\x37\x61" || substr($filedata, 0, 6) == "\x47\x49\x46\x38\x39\x61") {
			$format = "GIF";
		} elseif (substr($filedata, 0, 4) == "\xFF\xD8\xFF\xE0" && substr($filedata, 6, 5) == "\x4A\x46\x49\x46\x00") {
			$format = "JPG";
		} elseif (substr($filedata, 0, 8) == "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A") {
			$format = "PNG";
		} else {
			return FALSE;
		}
	}
	$thumb = new $EWR_THUMBNAIL_CLASS($filedata, $EWR_RESIZE_OPTIONS + array("isDataStream" => TRUE, "format" => $format), $plugins);
	return $thumb->resizeEx($filedata, $width, $height);
}

// Resize file to thumbnail file
function ewr_ResizeFile($fn, $tn, &$width, &$height, $plugins = array()) {
	global $EWR_THUMBNAIL_CLASS, $EWR_RESIZE_OPTIONS;
	$info = @getimagesize($fn);
	if (!$info || !in_array($info[2], array(1, 2, 3)) ||
		($width <= 0 && $height <= 0)) {
		if ($fn <> $tn) copy($fn, $tn);
		return;
	}
	$thumb = new $EWR_THUMBNAIL_CLASS($fn, $EWR_RESIZE_OPTIONS, $plugins);
	$fdata = NULL;
	if (!$thumb->resizeEx($fdata, $width, $height, $tn))
		if ($fn <> $tn) copy($fn, $tn);
}

// Resize file to binary
function ewr_ResizeFileToBinary($fn, &$width, &$height, $plugins = array()) {
	global $EWR_THUMBNAIL_CLASS, $EWR_RESIZE_OPTIONS;
	$info = @getimagesize($fn);
	if (!$info)
		return NULL;
	if (!in_array($info[2], array(1, 2, 3)) ||
		($width <= 0 && $height <= 0)) {
		$fdata = file_get_contents($fn);
	} else {
		$thumb = new $EWR_THUMBNAIL_CLASS($fn, $EWR_RESIZE_OPTIONS, $plugins);
		$fdata = NULL;
		if (!$thumb->resizeEx($fdata, $width, $height))
			$fdata = file_get_contents($fn);
	}
	return $fdata;
}
/**
 * Class Thumbnail (extends GD)
 * Constructor: public function __construct($file, $options = array(), array $plugins = array())
 * @param string $file (file name or file data)
 * @param array $options: 'jpegQuality'(int), resizeUp'(bool), 'keepAspectRatio'(bool), 'isDataStream'(bool), 'format'(string)
 * @param array $plugins: anonymous function with an argument $phpthumb(cThumbnail)
 */

class crThumbnail extends GD {

	// Extended resize method
	function resizeEx(&$fdata, &$width, &$height, $fn = "") {
		try {
			$this->executePlugins()->resize($width, $height); // Execute plugins and resize
			$dimensions = $this->getCurrentDimensions();
			$width = $dimensions["width"];
			$height = $dimensions["height"];
			if ($fn <> "")
				$this->save($fn);
			else
				$fdata = $this->getImageAsString();
			return TRUE;
		} catch (Exception $e) {
			if (EWR_DEBUG_ENABLED)
				throw $e;
			return FALSE;
		}
	}
}
?>
