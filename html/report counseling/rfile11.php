<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php
ewr_Header(FALSE);
$file = new crfile;
$file->Page_Main();

//
// Page class for file viewer
//
class crfile {

	// Page ID
	var $PageID = "file";

	// Project ID
	var $ProjectID = "{234B495E-E8C1-4FF1-B18B-170E747447B8}";

	// Page object name
	var $PageObjName = "file";

	// Page name
	function PageName() {
		return ewr_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		return ewr_CurrentPage() . "?";
	}

	// Main
	// - Uncomment ** for database connectivity / Page_Loading / Page_Unloaded server event
	function Page_Main() {
		global $Security;

		//**global $conn;
		$GLOBALS["Page"] = &$this;

		//**$conn = ewr_Connect();
		// Get fn / table name parameters

		$key = EWR_RANDOM_KEY . session_id();
		$fn = (@$_GET["fn"] <> "") ? $_GET["fn"] : "";
		$fn = ewr_Decrypt($fn, $key); // File path is always encrypted
		$table = (@$_GET["t"] <> "") ? $_GET["t"] : "";
		if ($table <> "" && EWR_ENCRYPT_FILE_PATH)
			$table = ewr_Decrypt($table, $key);

		// Global Page Loading event (in userfn*.php)
		//**Page_Loading();
		// Get resize parameters

		$resize = (@$_GET["resize"] <> "");
		$width = (@$_GET["width"] <> "") ? $_GET["width"] : 0;
		$height = (@$_GET["height"] <> "") ? $_GET["height"] : 0;
		if (@$_GET["width"] == "" && @$_GET["height"] == "") {
			$width = EWR_THUMBNAIL_DEFAULT_WIDTH;
			$height = EWR_THUMBNAIL_DEFAULT_HEIGHT;
		}

		// Resize image from physical file
		if ($fn <> "") {
			$fn = str_replace("\0", "", $fn);
			$info = pathinfo($fn);
			if (file_exists($fn) || @fopen($fn, "rb") !== FALSE) { // Allow remote file
				if (ob_get_length())
					ob_end_clean();
				$ext = strtolower(@$info["extension"]);
				$ct = ewr_ContentType("", $fn);
				if ($ct <> "")
					header("Content-type: " . $ct);
				$download = !isset($_GET["download"]) || $_GET["download"] == "1"; // Download by default
				if ($download)
					header("Content-Disposition: attachment; filename=\"" . $info["basename"] . "\"");
				if (in_array($ext, explode(",", EWR_IMAGE_ALLOWED_FILE_EXT))) {
					$size = @getimagesize($fn);
					if ($size)
						header("Content-type: {$size['mime']}");
					if ($width > 0 || $height > 0)
						echo ewr_ResizeFileToBinary($fn, $width, $height);
					else
						echo file_get_contents($fn);
				} elseif (in_array($ext, explode(",", EWR_DOWNLOAD_ALLOWED_FILE_EXT))) {
					echo file_get_contents($fn);
				}
			}
		}

		// Global Page Unloaded event (in userfn*.php)
		//**Page_Unloaded();
		 // Close connection
		//**ewr_CloseConn();

	}
}
?>
