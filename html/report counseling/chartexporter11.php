<?php

// Chart exporter for PHP Report Maker 11+
// (C) 2007-2018 e.World Technology Limited

include_once "rcfg11.php";
include_once "rphpfn11.php";
$CheckTokenFn = "ewr_CheckToken";

// Send 500 server error
function ServerError($msg) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', TRUE, 500);
	die($msg);
}

// Valid Post
function ValidPost() {
	global $CheckTokenFn;
	if (!EWR_CHECK_TOKEN || !ewr_IsHttpPost())
		return TRUE;
	if (!isset($_POST[EWR_TOKEN_NAME]))
		return FALSE;
	if (is_callable($CheckTokenFn))
		return $CheckTokenFn($_POST[EWR_TOKEN_NAME]);
	return FALSE;
}

// Get image from fusioncharts.com
function GetImageFromFusionCharts() {
	if (function_exists("curl_init")) { // Use cURL if available
		$postdata = file_get_contents("php://input"); // Get POST data
		$img = ewr_ClientUrl("export.api3.fusioncharts.com", $postdata, "POST"); // Get the chart from fusioncharts.com
		return $img;
	} else {
		return FALSE;
	}
}

// Get image from Imagick
function GetImageFromImagick() {
	if (class_exists("Imagick")) { // Use Imagick if available
		try {
			$img = new Imagick();
			$svg = '<?xml version="1.0" encoding="utf-8" standalone="no"?>' . @$_POST["stream"]; // Get SVG string

			// Replace, for example, fill="url('#10-270-rgba_255_0_0_1_-rgba_255_255_255_1_')" by fill="rgb(255, 0, 0)" 
			//$svg = preg_replace('/fill="url\(\'#[\w-]+rgba_(\d+)_(\d+)_(\d+)_(\d+)_-[\w-]+\'\)"/', 'fill="rgb($1, $2, $3)"', $svg);

			$svg = preg_replace('/fill="url\(\'\#[\w-]+rgba_(\d+)_(\d+)_(\d+)_(\d+\.?\d?)_-[\w-\.?]+\'\)"/', 'fill="rgb($1, $2, $3)"', $svg);
			$img->readImageBlob($svg);
			$img->setImageBackgroundColor(new ImagickPixel("transparent"));
			$img->setImageFormat("png24");
			return $img;
		} catch (Exception $e) {
			ServerError($e->getMessage());
			return FALSE;
		}
	} else {
		return FALSE;
	}
}

// Check token
if (!ValidPost())
	ServerError("Invalid post request.");

// Google Charts base64
if (@$_POST["stream_type"] == "base64") {
	try {
		$img = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', @$_POST["stream"]));
	} catch (Exception $e) {
		ServerError($e->getMessage());
	}
} else { // SVG / FusionCharts

	 // FusionCharts, requires cURL or Imagick
	if (@$_POST["chart_engine"] == "fusioncharts") {
	 	if (!function_exists("curl_init") && !class_exists("Imagick"))
			ServerError("Both Imagick and cURL not installed on this server.");
	} else { // Others, requires Imagick
	 	if (!class_exists("Imagick"))
			ServerError("Imagick not installed on this server.");
	}

	// Convert SVG string to image
	if (@$_POST["chart_engine"] == "fusioncharts") { // FusionCharts
		$img = GetImageFromFusionCharts(); // Get from fusioncharts.com first
		if ($img === FALSE)
			$img = GetImageFromImagick();
	} else { // Others, get from Imagick
		$img = GetImageFromImagick();
	}
}
if ($img === FALSE)
	ServerError("Unable to load image for chart engine '" . @$_POST["chart_engine"] . "' and stream type '" . @$_POST["stream_type"] . "'");

// Save the file
$params = @$_POST["parameters"];
$filename = "";
if (preg_match('/exportfilename=(\w+\.png)\|/', $params, $matches)) // Must be .png for security
	$filename = $matches[1];
if ($filename == "")
	ServerError("Missing file name.");
$path = ewr_ServerMapPath(EWR_UPLOAD_DEST_PATH);
$realpath = realpath($path);
if (!file_exists($realpath))
	ServerError("Upload folder does not exist.");
if (!is_writable($realpath))
	ServerError("Upload folder is not writable.");
$filepath = realpath($path) . EWR_PATH_DELIMITER . $filename;
file_put_contents($filepath, $img);
?>
