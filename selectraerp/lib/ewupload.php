<?php
// File upload functions for PHPMaker 4+
// (C) 2006 e.World Technology Ltd.

define("EW_UploadDestPath", "", true);
define("EW_UploadAllowedFileExt", "gif,jpg,jpeg,bmp,png,doc,xls,pdf,zip", true);
define("EW_UploadedFileMode", 0666, true);

function ewCreateFolder($dir, $mode = 0777)
{
  if (is_dir($dir) || @mkdir($dir, $mode)) return true;
  if (!ewCreateFolder(dirname($dir), $mode)) return false;
  return @mkdir($dir, $mode);
}

// Function to return path of the uploaded file
// Parameter: If PhyPath is true(1), return physical path on the server;
// If PhyPath is false(0), return relative URL
function ewUploadPathEx($PhyPath, $DestPath)
{
	global $ewPathDelimiter;
	if ($PhyPath) {
		$Path = ewAppRoot();
		$Path .= str_replace("/", $ewPathDelimiter, $DestPath);
	} else {
		$Path = EW_RootRelativePath;
		$Path = str_replace("\\\\", "/", $Path);
		$Path = str_replace("\\", "/", $Path);
		$Path = ewIncludeTrailingDelimiter($Path, false) . $DestPath;
	}
	return ewIncludeTrailingDelimiter($Path, $PhyPath);
}

function ewUploadFileName($sFileName)
{
	// Amend your logic here
	$sOutFileName = $sFileName;

	// Return computed output file name
	return $sOutFileName;
}

function ewUploadFileNameEx($folder, $sFileName)
{
	// Amend your logic here
	$sOutFileName = ewUniqueFilename($folder, $sFileName);

	// Return computed output file name
	return $sOutFileName;
}

// Function to generate an unique file name (filename(n).ext)
function ewUniqueFilename($folder, $oriFilename)
{
	if ($oriFilename == "") $oriFilename = ewDefaultFileName();

	$oriFilename = strtolower(basename($oriFilename));
	$destFullPath = $folder.$oriFilename;
	$newFilename = $oriFilename;
	$i = 1;

	if (!file_exists($folder)) {
		if (!ewCreateFolder($folder)) {
			die("Folder does not exist: " . $folder);
		}
	}
	while (file_exists($destFullPath)) {
		$file_extension  = strtolower(strrchr($oriFilename, "."));
		$file_name = basename($oriFilename, $file_extension);
		$newFilename = $file_name . "($i)" . $file_extension;
		$destFullPath = $folder . $newFilename;
		$i++;
  }
	return $newFilename;
}

// Function to create a default file name(yyyymmddhhmmss.bin)
function ewDefaultFileName()
{
	return date("YmdHis").".bin";
}

// Function to check the file type of the upload file
function ewUploadAllowedFileExt($Filename)
{
	$extension = substr(strtolower(strrchr($Filename, ".")), 1);
	$allowExt = explode(",", strtolower(EW_UploadAllowedFileExt));
	return in_array($extension, $allowExt);
}
?>
