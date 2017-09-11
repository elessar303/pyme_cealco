<?php
// PHPMaker functions for PHPMaker 4+
// (C) 2006 e.World Technology Ltd.

// Common constants

define("DEFAULT_CURRENCY_SYMBOL", "$", true);
define("DEFAULT_MON_DECIMAL_POINT", ".", true);
define("DEFAULT_MON_THOUSANDS_SEP", ",", true);
define("DEFAULT_POSITIVE_SIGN", "", true);
define("DEFAULT_NEGATIVE_SIGN", "-", true);
define("DEFAULT_FRAC_DIGITS", 2, true);
define("DEFAULT_P_CS_PRECEDES", true, true);
define("DEFAULT_P_SEP_BY_SPACE", false, true);
define("DEFAULT_N_CS_PRECEDES", true, true);
define("DEFAULT_N_SEP_BY_SPACE", false, true);
define("DEFAULT_P_SIGN_POSN", 3, true);
define("DEFAULT_N_SIGN_POSN", 3, true);


define("DEFAULT_DATE_FORMAT", "yyyy/mm/dd", true);
define("EW_DATE_SEPARATOR", "/", true);
define("EW_SMTPSERVER", "localhost", true);
define("EW_SMTPSERVER_PORT", "25", true);
define("EW_SMTPSERVER_USERNAME", "", true);
define("EW_SMTPSERVER_PASSWORD", "", true);

define("EW_RootRelativePath", ".", true);

define("EW_RANDOM_KEY", "7de7Q9Cxg2zshUll", true);

$ewPathDelimiter = (strtolower(substr(PHP_OS, 0, 3)) === 'win') ? "\\" : "/";


// Note: If you use non English languages, you may need to set the encoding for
// Ajax features. Make sure your encoding is supported by your PHP and either
// iconv functions or multibyte string functions are enabled. See PHP manual
// for details
// eg. define("ewEncoding", "ISO-8859-1", true);
define("ewEncoding", "ISO-8859-1", true); // enter your encoding here

function ewConvertToUtf8($str)
{
	return ewConvert(ewEncoding, "UTF-8", $str);
}

function ewConvertFromUtf8($str)
{
	return ewConvert("UTF-8", ewEncoding, $str);
}

function ewConvert($from, $to, $str)
{
	if ($from != "" && $to != "" && $from != $to) {
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

function EW_GetValue($Key, $sArr)
{
	for ($i=0; $i< count($sArr); $i++) {
		$kv = explode("=", $sArr[$i]);
		If ($kv[0] == $Key) {
			return ewConvertFromUtf8(EW_Decode($kv[1]));			
		}
	}
	return "";
}

function ewScriptFileName() {


	$sScriptFileName = @$_ENV["PHP_SELF"];
	if (empty($sScriptFileName)) {$sScriptFileName = @$_SERVER["PHP_SELF"];}
	if (empty($sScriptFileName)) {$sScriptFileName = @$_ENV["SCRIPT_NAME"];}
	if (empty($sScriptFileName)) {$sScriptFileName = @$_SERVER["SCRIPT_NAME"];}
	if (empty($sScriptFileName)) {$sScriptFileName = @$_ENV["ORIG_PATH_INFO"];}
	if (empty($sScriptFileName)) {$sScriptFileName = @$_SERVER["ORIG_PATH_INFO"];}
	if (empty($sScriptFileName)) {$sScriptFileName = @$_ENV["ORIG_SCRIPT_NAME"];}
	if (empty($sScriptFileName)) {$sScriptFileName = @$_SERVER["ORIG_SCRIPT_NAME"];}
	if (empty($sScriptFileName)) {$sScriptFileName = @$_ENV["REQUEST_URI"];}
	if (empty($sScriptFileName)) {$sScriptFileName = @$_SERVER["REQUEST_URI"];}
	if (empty($sScriptFileName)) {$sScriptFileName = @$_ENV["URL"];}
	if (empty($sScriptFileName)) {$sScriptFileName = @$_SERVER["URL"];}
	if (empty($sScriptFileName)) {$sScriptFileName = "UNKNOWN";}

	return $sScriptFileName;
}

//-------------------------------------------------------------------------------
// Functions for default date format
// FormatDateTime
/*
Format a timestamp, datetime, date or time field from MySQL
$namedformat:
0 - General Date,
1 - Long Date,
2 - Short Date (Default),
3 - Long Time,
4 - Short Time,
5 - Short Date (yyyy/mm/dd),
6 - Short Date (mm/dd/yyyy),
7 - Short Date (dd/mm/yyyy)
*/

// Convert a date to MySQL format
function ConvertDateToMysqlFormat($dateStr)
{
	@list($datePt, $timePt) = explode(" ", $dateStr);
	$arDatePt = explode(EW_DATE_SEPARATOR, $datePt);
	if (count($arDatePt) == 3) {
		switch (DEFAULT_DATE_FORMAT) {
		case "yyyy" . EW_DATE_SEPARATOR . "mm" . EW_DATE_SEPARATOR . "dd":
			list($year, $month, $day) = $arDatePt;
			break;
		case "mm" . EW_DATE_SEPARATOR . "dd" . EW_DATE_SEPARATOR . "yyyy":
			list($month, $day, $year) = $arDatePt;
			break;
		case "dd" . EW_DATE_SEPARATOR . "mm" . EW_DATE_SEPARATOR . "yyyy":
			list($day, $month, $year) = $arDatePt;
			break;
		}
		return trim($year . "-" . $month . "-" . $day . " " . $timePt);
	} else {
		return $dateStr;
	}
}

function FormatDateTime($ts, $namedformat)
{
	$DefDateFormat = str_replace("yyyy", "%Y", DEFAULT_DATE_FORMAT);
	$DefDateFormat = str_replace("mm", "%m", $DefDateFormat);
	$DefDateFormat = str_replace("dd", "%d", $DefDateFormat);
	if (is_numeric($ts)) // timestamp
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
		if (preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $ts, $matches)) // datetime
		{
			$year = $matches[1];
			$month = $matches[2];
			$day = $matches[3];
			$hour = $matches[4];
			$min = $matches[5];
			$sec = $matches[6];
		}
		elseif (preg_match('/(\d{4})-(\d{2})-(\d{2})/', $ts, $matches)) // date
		{
			$year = $matches[1];
			$month = $matches[2];
			$day = $matches[3];
			if ($namedformat==0) $namedformat = 2;
		}
		elseif (preg_match('/(^|\s)(\d{2}):(\d{2}):(\d{2})/', $ts, $matches)) // time
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
	if (!isset($year)) $year = 0; // dummy value for times
	if (!isset($month)) $month = 1;
	if (!isset($day)) $day = 1;
	if (!isset($hour)) $hour = 0;
	if (!isset($min)) $min = 0;
	if (!isset($sec)) $sec = 0;
	$uts = @mktime($hour, $min, $sec, $month, $day, $year);
	if ($uts < 0) { // failed to convert
		$year = substr_replace("0000", $year, -1 * strlen($year));
		$month = substr_replace("00", $month, -1 * strlen($month));
		$day = substr_replace("00", $day, -1 * strlen($day));
		$hour = substr_replace("00", $hour, -1 * strlen($hour));
		$min = substr_replace("00", $min, -1 * strlen($min));
		$sec = substr_replace("00", $sec, -1 * strlen($sec));
		$DefDateFormat = str_replace("yyyy", $year, DEFAULT_DATE_FORMAT);
		$DefDateFormat = str_replace("mm", $month, $DefDateFormat);
		$DefDateFormat = str_replace("dd", $day, $DefDateFormat);
		switch ($namedformat) {
			case 0:
				return $DefDateFormat." $hour:$min:$sec";
				break;
			case 1://unsupported, return general date
				return $DefDateFormat." $hour:$min:$sec";
				break;
			case 2:
				return $DefDateFormat;
				break;
			case 3:
				if (intval($hour)==0)
					return "12:$min:$sec AM";
				elseif (intval($hour)>0 && intval($hour)<12)
					return "$hour:$min:$sec AM";
				elseif (intval($hour)==12)
					return "$hour:$min:$sec PM";
				elseif (intval($hour)>12 && intval($hour)<=23)
					return (intval($hour)-12).":$min:$sec PM";
				else
					return "$hour:$min:$sec";
				break;
			case 4:
				return "$hour:$min:$sec";
				break;
			case 5:
				return "$year". EW_DATE_SEPARATOR . "$month" . EW_DATE_SEPARATOR . "$day";
				break;
			case 6:
				return "$month". EW_DATE_SEPARATOR ."$day" . EW_DATE_SEPARATOR . "$year";
				break;
			case 7:
				return "$day" . EW_DATE_SEPARATOR ."$month" . EW_DATE_SEPARATOR . "$year";
				break;
		}
	} else {
		switch ($namedformat) {
			case 0:
				return strftime($DefDateFormat." %H:%M:%S", $uts);
				break;
			case 1:
				return strftime("%A, %B %d, %Y", $uts);
				break;
			case 2:
				return strftime($DefDateFormat, $uts);
				break;
			case 3:
				return strftime("%I:%M:%S %p", $uts);
				break;
			case 4:
				return strftime("%H:%M:%S", $uts);
				break;
			case 5:
				return strftime("%Y" . EW_DATE_SEPARATOR . "%m" . EW_DATE_SEPARATOR . "%d", $uts);
				break;
			case 6:
				return strftime("%m" . EW_DATE_SEPARATOR . "%d" . EW_DATE_SEPARATOR . "%Y", $uts);
				break;
			case 7:
				return strftime("%d" . EW_DATE_SEPARATOR . "%m" . EW_DATE_SEPARATOR . "%Y", $uts);
				break;
		}
	}
}

// Function for debug
function ewTrace($msg) {
	$filename = "debug.txt";
	if (!$handle = fopen($filename, 'a')) exit;
	if (is_writable($filename)) fwrite($handle, $msg . "\n");
	fclose($handle);
}

// Function for trim
function ewTrim($str) {
	$temp = trim($str);
	if (strtolower($str) == "null") return "";
	if (substr($temp, 0, 1) == "`" && substr($temp, -1, 1) == "`") {
		$temp = substr($temp, 1, -1);
	} elseif (substr($temp, 0, 1) == "'" && substr($temp, -1, 1) == "'") {
		$temp = substr($temp, 1, -1);
		$temp = (get_magic_quotes_gpc()) ? stripslashes($temp) : $temp;
	}
	return $temp;
}

// FormatCurrency
/*
FormatCurrency(Expression[,NumDigitsAfterDecimal [,IncludeLeadingDigit
 [,UseParensForNegativeNumbers [,GroupDigits]]]])
NumDigitsAfterDecimal is the numeric value indicating how many places to the
right of the decimal are displayed
-1 Use Default
The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits
arguments have the following settings:
-1 True
0 False
-2 Use Default
*/
function FormatCurrency($amount, $NumDigitsAfterDecimal, $IncludeLeadingDigit, $UseParensForNegativeNumbers, $GroupDigits)
{

	// export the values returned by localeconv into the local scope
	if (function_exists("localeconv")) extract(localeconv());

	// set defaults if locale is not set
	if (empty($currency_symbol)) $currency_symbol = DEFAULT_CURRENCY_SYMBOL;
	if (empty($mon_decimal_point)) $mon_decimal_point = DEFAULT_MON_DECIMAL_POINT;
	if (empty($mon_thousands_sep)) $mon_thousands_sep = DEFAULT_MON_THOUSANDS_SEP;
	if (empty($positive_sign)) $positive_sign = DEFAULT_POSITIVE_SIGN;
	if (empty($negative_sign)) $negative_sign = DEFAULT_NEGATIVE_SIGN;
	if (empty($frac_digits) || $frac_digits == CHAR_MAX) $frac_digits = DEFAULT_FRAC_DIGITS;
	if (empty($p_cs_precedes) || $p_cs_precedes == CHAR_MAX) $p_cs_precedes = DEFAULT_P_CS_PRECEDES;
	if (empty($p_sep_by_space) || $p_sep_by_space == CHAR_MAX) $p_sep_by_space = DEFAULT_P_SEP_BY_SPACE;
	if (empty($n_cs_precedes) || $n_cs_precedes == CHAR_MAX) $n_cs_precedes = DEFAULT_N_CS_PRECEDES;
	if (empty($n_sep_by_space) || $n_sep_by_space == CHAR_MAX) $n_sep_by_space = DEFAULT_N_SEP_BY_SPACE;
	if (empty($p_sign_posn) || $p_sign_posn == CHAR_MAX) $p_sign_posn = DEFAULT_P_SIGN_POSN;
	if (empty($n_sign_posn) || $n_sign_posn == CHAR_MAX) $n_sign_posn = DEFAULT_N_SIGN_POSN;

	// check $NumDigitsAfterDecimal
	if ($NumDigitsAfterDecimal > -1)
		$frac_digits = $NumDigitsAfterDecimal;

	// check $UseParensForNegativeNumbers
	if ($UseParensForNegativeNumbers == -1) {
		$n_sign_posn = 0;
		if ($p_sign_posn == 0) {
			if (DEFAULT_P_SIGN_POSN != 0)
				$p_sign_posn = DEFAULT_P_SIGN_POSN;
			else
				$p_sign_posn = 3;
		}
	} elseif ($UseParensForNegativeNumbers == 0) {
		if ($n_sign_posn == 0)
			if (DEFAULT_P_SIGN_POSN != 0)
				$n_sign_posn = DEFAULT_P_SIGN_POSN;
			else
				$n_sign_posn = 3;
	}

	// check $GroupDigits
	if ($GroupDigits == -1) {
		$mon_thousands_sep = DEFAULT_MON_THOUSANDS_SEP;
	} elseif ($GroupDigits == 0) {
		$mon_thousands_sep = "";
	}

	// start by formatting the unsigned number
	$number = number_format(abs($amount),
							$frac_digits,
							$mon_decimal_point,
							$mon_thousands_sep);

	// check $IncludeLeadingDigit
	if ($IncludeLeadingDigit == 0) {
		if (substr($number, 0, 2) == "0.")
			$number = substr($number, 1, strlen($number)-1);
	}
	if ($amount < 0) {
		$sign = $negative_sign;

		// "extracts" the boolean value as an integer
		$n_cs_precedes  = intval($n_cs_precedes  == true);
		$n_sep_by_space = intval($n_sep_by_space == true);
		$key = $n_cs_precedes . $n_sep_by_space . $n_sign_posn;
	} else {
		$sign = $positive_sign;
		$p_cs_precedes  = intval($p_cs_precedes  == true);
		$p_sep_by_space = intval($p_sep_by_space == true);
		$key = $p_cs_precedes . $p_sep_by_space . $p_sign_posn;
	}
	$formats = array(

	  // currency symbol is after amount

	  // no space between amount and sign
	  '000' => '(%s' . $currency_symbol . ')',
	  '001' => $sign . '%s ' . $currency_symbol,
	  '002' => '%s' . $currency_symbol . $sign,
	  '003' => '%s' . $sign . $currency_symbol,
	  '004' => '%s' . $sign . $currency_symbol,

	  // one space between amount and sign
	  '010' => '(%s ' . $currency_symbol . ')',
	  '011' => $sign . '%s ' . $currency_symbol,
	  '012' => '%s ' . $currency_symbol . $sign,
	  '013' => '%s ' . $sign . $currency_symbol,
	  '014' => '%s ' . $sign . $currency_symbol,

	  // currency symbol is before amount

	  // no space between amount and sign
	  '100' => '(' . $currency_symbol . '%s)',
	  '101' => $sign . $currency_symbol . '%s',
	  '102' => $currency_symbol . '%s' . $sign,
	  '103' => $sign . $currency_symbol . '%s',
	  '104' => $currency_symbol . $sign . '%s',

	  // one space between amount and sign
	  '110' => '(' . $currency_symbol . ' %s)',
	  '111' => $sign . $currency_symbol . ' %s',
	  '112' => $currency_symbol . ' %s' . $sign,
	  '113' => $sign . $currency_symbol . ' %s',
	  '114' => $currency_symbol . ' ' . $sign . '%s');

  // lookup the key in the above array
	return sprintf($formats[$key], $number);
}

// FormatNumber
/*
FormatNumber(Expression[,NumDigitsAfterDecimal [,IncludeLeadingDigit
	[,UseParensForNegativeNumbers [,GroupDigits]]]])
NumDigitsAfterDecimal is the numeric value indicating how many places to the
right of the decimal are displayed
-1 Use Default
The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits
arguments have the following settings:
-1 True
0 False
-2 Use Default
*/
function FormatNumber($amount, $NumDigitsAfterDecimal, $IncludeLeadingDigit, $UseParensForNegativeNumbers, $GroupDigits)
{

	// export the values returned by localeconv into the local scope
	if (function_exists("localeconv")) extract(localeconv());

	// set defaults if locale is not set
	if (empty($currency_symbol)) $currency_symbol = DEFAULT_CURRENCY_SYMBOL;
	if (empty($mon_decimal_point)) $mon_decimal_point = DEFAULT_MON_DECIMAL_POINT;
	if (empty($mon_thousands_sep)) $mon_thousands_sep = DEFAULT_MON_THOUSANDS_SEP;
	if (empty($positive_sign)) $positive_sign = DEFAULT_POSITIVE_SIGN;
	if (empty($negative_sign)) $negative_sign = DEFAULT_NEGATIVE_SIGN;
	if (empty($frac_digits) || $frac_digits == CHAR_MAX) $frac_digits = DEFAULT_FRAC_DIGITS;
	if (empty($p_cs_precedes) || $p_cs_precedes == CHAR_MAX) $p_cs_precedes = DEFAULT_P_CS_PRECEDES;
	if (empty($p_sep_by_space) || $p_sep_by_space == CHAR_MAX) $p_sep_by_space = DEFAULT_P_SEP_BY_SPACE;
	if (empty($n_cs_precedes) || $n_cs_precedes == CHAR_MAX) $n_cs_precedes = DEFAULT_N_CS_PRECEDES;
	if (empty($n_sep_by_space) || $n_sep_by_space == CHAR_MAX) $n_sep_by_space = DEFAULT_N_SEP_BY_SPACE;
	if (empty($p_sign_posn) || $p_sign_posn == CHAR_MAX) $p_sign_posn = DEFAULT_P_SIGN_POSN;
	if (empty($n_sign_posn) || $n_sign_posn == CHAR_MAX) $n_sign_posn = DEFAULT_N_SIGN_POSN;

	// check $NumDigitsAfterDecimal
	if ($NumDigitsAfterDecimal > -1)
		$frac_digits = $NumDigitsAfterDecimal;

	// check $UseParensForNegativeNumbers
	if ($UseParensForNegativeNumbers == -1) {
		$n_sign_posn = 0;
		if ($p_sign_posn == 0) {
			if (DEFAULT_P_SIGN_POSN != 0)
				$p_sign_posn = DEFAULT_P_SIGN_POSN;
			else
				$p_sign_posn = 3;
		}
	} elseif ($UseParensForNegativeNumbers == 0) {
		if ($n_sign_posn == 0)
			if (DEFAULT_P_SIGN_POSN != 0)
				$n_sign_posn = DEFAULT_P_SIGN_POSN;
			else
				$n_sign_posn = 3;
	}

	// check $GroupDigits
	if ($GroupDigits == -1) {
		$mon_thousands_sep = DEFAULT_MON_THOUSANDS_SEP;
	} elseif ($GroupDigits == 0) {
		$mon_thousands_sep = "";
	}

	// start by formatting the unsigned number
	$number = number_format(abs($amount),
						  $frac_digits,
						  $mon_decimal_point,
						  $mon_thousands_sep);

	// check $IncludeLeadingDigit
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

	// lookup the key in the above array
	return sprintf($formats[$key], $number);
}

// FormatPercent
/*
FormatPercent(Expression[,NumDigitsAfterDecimal [,IncludeLeadingDigit
	[,UseParensForNegativeNumbers [,GroupDigits]]]])
NumDigitsAfterDecimal is the numeric value indicating how many places to the
right of the decimal are displayed
-1 Use Default
The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits
arguments have the following settings:
-1 True
0 False
-2 Use Default
*/
function FormatPercent($amount, $NumDigitsAfterDecimal, $IncludeLeadingDigit, $UseParensForNegativeNumbers, $GroupDigits)
{

	// export the values returned by localeconv into the local scope
	if (function_exists("localeconv")) extract(localeconv());

	// set defaults if locale is not set
	if (empty($currency_symbol)) $currency_symbol = DEFAULT_CURRENCY_SYMBOL;
	if (empty($mon_decimal_point)) $mon_decimal_point = DEFAULT_MON_DECIMAL_POINT;
	if (empty($mon_thousands_sep)) $mon_thousands_sep = DEFAULT_MON_THOUSANDS_SEP;
	if (empty($positive_sign)) $positive_sign = DEFAULT_POSITIVE_SIGN;
	if (empty($negative_sign)) $negative_sign = DEFAULT_NEGATIVE_SIGN;
	if (empty($frac_digits) || $frac_digits == CHAR_MAX) $frac_digits = DEFAULT_FRAC_DIGITS;
	if (empty($p_cs_precedes) || $p_cs_precedes == CHAR_MAX) $p_cs_precedes = DEFAULT_P_CS_PRECEDES;
	if (empty($p_sep_by_space) || $p_sep_by_space == CHAR_MAX) $p_sep_by_space = DEFAULT_P_SEP_BY_SPACE;
	if (empty($n_cs_precedes) || $n_cs_precedes == CHAR_MAX) $n_cs_precedes = DEFAULT_N_CS_PRECEDES;
	if (empty($n_sep_by_space) || $n_sep_by_space == CHAR_MAX) $n_sep_by_space = DEFAULT_N_SEP_BY_SPACE;
	if (empty($p_sign_posn) || $p_sign_posn == CHAR_MAX) $p_sign_posn = DEFAULT_P_SIGN_POSN;
	if (empty($n_sign_posn) || $n_sign_posn == CHAR_MAX) $n_sign_posn = DEFAULT_N_SIGN_POSN;

	// check $NumDigitsAfterDecimal
	if ($NumDigitsAfterDecimal > -1)
		$frac_digits = $NumDigitsAfterDecimal;

	// check $UseParensForNegativeNumbers
	if ($UseParensForNegativeNumbers == -1) {
		$n_sign_posn = 0;
		if ($p_sign_posn == 0) {
			if (DEFAULT_P_SIGN_POSN != 0)
				$p_sign_posn = DEFAULT_P_SIGN_POSN;
			else
				$p_sign_posn = 3;
		}
	} elseif ($UseParensForNegativeNumbers == 0) {
		if ($n_sign_posn == 0)
			if (DEFAULT_P_SIGN_POSN != 0)
				$n_sign_posn = DEFAULT_P_SIGN_POSN;
			else
				$n_sign_posn = 3;
	}

	// check $GroupDigits
	if ($GroupDigits == -1) {
		$mon_thousands_sep = DEFAULT_MON_THOUSANDS_SEP;
	} elseif ($GroupDigits == 0) {
		$mon_thousands_sep = "";
	}

	// start by formatting the unsigned number
	$number = number_format(abs($amount)*100,
							$frac_digits,
							$mon_decimal_point,
							$mon_thousands_sep);

	// check $IncludeLeadingDigit
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

	// lookup the key in the above array
	return sprintf($formats[$key], $number);
}


// Function to get application root
function ewAppRoot() {

	global $ewPathDelimiter;
	
	// 1. use root relative path (4.0.2)
	if (EW_RootRelativePath != "") {
		$Path = realpath(EW_RootRelativePath);
		$Path = str_replace("\\\\", $ewPathDelimiter, $Path);
	}
	
	// 2. if empty, use the document root if available
	if (empty($Path)) $Path = @$_SERVER["DOCUMENT_ROOT"]; 
	if (empty($Path)) $Path = @$_ENV["DOCUMENT_ROOT"];
	
	// 3. if empty, use current folder
	if (empty($Path)) $Path = realpath(".");
	
	// 4. use custom path, uncomment the following line and enter your path
	// e.g. $Path = "C:\Inetpub\wwwroot\MyWebRoot"; // Windows
	
	//$Path = "enter your path here";
	
	if (empty($Path)) die("Path of website root unknown.");
	return ewIncludeTrailingDelimiter($Path, true);
}

// Function to include the last delimiter for a path
function ewIncludeTrailingDelimiter($Path, $PhyPath) {
	global $ewPathDelimiter;
	if ($PhyPath) {
		if (substr($Path, -1) <> $ewPathDelimiter) $Path .= $ewPathDelimiter;
	} else {
		if (substr($Path, -1) <> "/") $Path .= "/";
	}
	return $Path;
}

// Function to write the paths for config/debug only
function ewWritePaths() {

	print '$HTTP_SERVER_VARS["DOCUMENT_ROOT"]=' . @$HTTP_SERVER_VARS["DOCUMENT_ROOT"] . "<br>";
	print '$HTTP_ENV_VARS["DOCUMENT_ROOT"]=' . @$HTTP_ENV_VARS["DOCUMENT_ROOT"] . "<br>";
	print '$_SERVER["DOCUMENT_ROOT"]=' . @$_SERVER["DOCUMENT_ROOT"] . "<br>";
	print '$_ENV["DOCUMENT_ROOT"]=' . @$_ENV["DOCUMENT_ROOT"] . "<br>";
	print 'EW_RootRelativePath=' . EW_RootRelativePath . "<br>";
	print 'ewAppRoot()=' . ewAppRoot() . "<br>";
	print 'realpath(".")=' . realpath(".") . "<br>";
	print '__FILE__=' . __FILE__ . "<br>";
}

// Function to Load Email Content from input file name
// - Content Loaded to the following variables
// - Subject: sEmailSubject
// - From: sEmailFrom
// - To: sEmailTo
// - Cc: sEmailCc
// - Bcc: sEmailBcc
// - Format: sEmailFormat
// - Content: sEmailContent
//
function LoadEmail($fn)
{
	global $sEmailSubject;
	global $sEmailFrom;
	global $sEmailTo;
	global $sEmailCc;
	global $sEmailBcc;
	global $sEmailFormat;
	global $sEmailContent;

	$sWrk = LoadTxt($fn); // Load text file content
	if ($sWrk <> "") {
		// Locate Header & Mail Content
		if (strtolower(substr(PHP_OS, 0, 3)) === 'win') {
			$i = strpos($sWrk, "\r\n\r\n");
		} else {
			$i = strpos($sWrk, "\n\n");
			if ($i === false) $i = strpos($sWrk, "\r\n\r\n");
		}
		if ($i > 0) {
			$sHeader = substr($sWrk, 0, $i);
			$sEmailContent = trim(substr($sWrk, $i, strlen($sWrk)));
			if (strtolower(substr(PHP_OS, 0, 3)) === 'win') {
				$arrHeader = split("\r\n",$sHeader);
			} else {
				$arrHeader = split("\n",$sHeader);
			}
			for ($j = 0; $j < count($arrHeader); $j++)
			{
				$i = strpos($arrHeader[$j], ":");
				if ($i > 0) {
					$sName = trim(substr($arrHeader[$j], 0, $i));
					$sValue = trim(substr($arrHeader[$j], $i+1, strlen($arrHeader[$j])));
					switch (strtolower($sName))
					{
						case "subject": $sEmailSubject = $sValue;
												break;
						case "from": $sEmailFrom = $sValue;
												break;
						case "to": $sEmailTo = $sValue;
												break;
						case "cc": $sEmailCc = $sValue;
												break;
						case "bcc": $sEmailBcc = $sValue;
												break;
						case "format": $sEmailFormat = $sValue;
												break;
					}
				}
			}
		}
	}

}

// Function to Load a Text File
function LoadTxt($fn)
{	global $ewPathDelimiter;
	
	// Get text file content
	$filepath = realpath(".") . $ewPathDelimiter . $fn;
	
	$fobj = fopen ($filepath , "r");
	return fread ($fobj, filesize ($filepath));
}


//Function to Send out Email
function Send_Email($sFrEmail, $sToEmail, $sCcEmail, $sBccEmail, $sSubject, $sMail, $sFormat)
{
	/* for debug only
	echo "sSubject: " . $sSubject . "<br>";
	echo "sFrEmail: " . $sFrEmail . "<br>";
	echo "sToEmail: " . $sToEmail . "<br>";
	echo "sCcEmail: " . $sCcEmail . "<br>"; 
	echo "sSubject: " . $sSubject . "<br>";
	echo "sMail: " . $sMail . "<br>";
	echo "sFormat: " . $sFormat . "<br>";
	*/
	
	
	
	/* recipients */
	$to  = $sToEmail;

	/* subject */
	$subject = $sSubject;

	$headers = "";

	if (strtolower($sFormat) == "html") {
		$content_type = "text/html";
	} else {
		$content_type = "text/plain";
	}

	$headers = "Content-type: " . $content_type . "\r\n";

	$message = $sMail;

	/* additional headers */
	$headers .= "From: " . str_replace(";", ",", $sFrEmail) . "\r\n";
	if ($sCcEmail <> "") {
		$headers .= "Cc: " . str_replace(";", ",", $sCcEmail) . "\r\n";
	}
	if ($sBccEmail <>"") {
		$headers .= "Bcc: " . str_replace(";", ",", $sBccEmail) . "\r\n";
	}

	/* and now mail it */
	if (strtolower(substr(PHP_OS, 0, 3)) === 'win') {
		ini_set("SMTP",EW_SMTPSERVER);
		ini_set("smtp_port",EW_SMTPSERVER_PORT);
	}
	ini_set("sendmail_from",$sFrEmail);
	if (!mail($to, $subject, $message, $headers)) {
		echo "There has been a mail error sending to " . $sToEmail . "<br>";
		return false;
	} else {
		return true;
	}

}

// Function to generate Value Separator based on current row count
// rowcnt - zero based row count
//
function ValueSeparator($rowcnt)
{
	return ", ";
}

// Function to generate View Option Separator based on current row count (Multi-Select / CheckBox)
// rowcnt - zero based row count
//
function ViewOptionSeparator($rowcnt)
{
	return  ", ";
}

// Function to render repeat column table
// rowcnt - zero based row count
//
function RenderControl($totcnt, $rowcnt, $repeatcnt, $rendertype) {

	$sWrk = "";

	// Render control start
	if ($rendertype == 1) { 

		if ($rowcnt == 0) $sWrk .= "<table class=\"phpmakerlist\">";
		If (($rowcnt % $repeatcnt) == 0) $sWrk .= "<tr>";
		$sWrk .= "<td>";

	// Render control end
	} elseif ($rendertype == 2) {

		$sWrk .= "</td>";
		if (($rowcnt % $repeatcnt) == ($repeatcnt-1)) {
			$sWrk .= "</tr>";
		} elseif ($rowcnt == $totcnt) {
			for ($i=(($rowcnt % $repeatcnt)+1); $i < $repeatcnt; $i++ ) {
				$sWrk .= "<td>&nbsp;</td>";
			}
			$sWrk .= "</tr>";
		}
		if ($rowcnt == $totcnt) $sWrk .= "</table>";

	}

	return $sWrk;

}

// Function to truncate Memo Field based on specified length, string truncated to nearest space or CrLf
//
function TruncateMemo($str, $ln)
{
	if (strlen($str) > 0 && strlen($str) > $ln) {
		$k = 0;
		while ($k >= 0 && $k < strlen($str)) {
			$i = strpos($str, " ", $k);
			$j = strpos($str,chr(10), $k);
			if ($i === false && $j === false) { // Not able to truncate
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
					$k ++;
				}
			}
		}
	} else {
		return $str;
	}
}

// Function to check if the MySQL server supports subquery
/* $conn must be set */
function isSubquerySupported()
{
	global $conn;
	$sSql = "SHOW VARIABLES LIKE 'version'";
	$rs = phpmkr_query($sSql,$conn) or die("Fallo al ejecutar la consulta en la línea" . __LINE__ . ": " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
	if ($field = phpmkr_fetch_array($rs)) $version = $field["Value"];
	$match = explode('.', @$version);
	return (int)sprintf('%d%02d%02d', $match[0], @$match[1], intval(@$match[2])) >= 40100;
}

// Function to check if keyword is accepted
function isValidOpr($arOpr)
{
	$arkw = array("=", "<>", "<", "<=", ">", ">=", "LIKE", "NOT LIKE", "BETWEEN", "'", "'%", "%'");
	$valid = true;
	foreach ($arOpr as $value) {
		if (trim($value) != "") {
			if (!in_array(trim($value), $arkw)) {
				$valid = false;
				break;
			}
		}
	}
	return $valid;
} 

// Function for writing audit trail
function ewWriteAuditTrail($pfx, $curDate, $curTime, $id, $user, $action, $table, $field, $keyvalue, $oldvalue, $newvalue)
{

	global $ewPathDelimiter;
	
	$sFolder = "";
	$sFolder = str_replace("/", $ewPathDelimiter, $sFolder);
	$ewFilePath = ewAppRoot() . $sFolder;
	
	$sTab = "\t";
	
	$sHeader = "date" . $sTab . "time" . $sTab . "id" . 
				$sTab .	"user" . $sTab . "action" . $sTab . "table" . 
				$sTab . "field" . $sTab . "key value" . $sTab . "old value" . 
				$sTab . "new value";
	$sMsg = $curDate . $sTab . $curTime . $sTab . 
			$id . $sTab . $user . $sTab . 
			$action . $sTab . $table . $sTab . 
			$field . $sTab . $keyvalue . $sTab . 
			$oldvalue . $sTab . $newvalue;
	$sFn = $pfx . "_" . date("Ymd") . ".txt";
	
	$filename = $ewFilePath . $sFn;
	
	if (file_exists($filename)) {
		$fileHandler = fopen($filename, "a+b");
	} else {
		$fileHandler = fopen($filename, "a+b");
		fwrite($fileHandler,$sHeader."\r\n");
	}
	
	fwrite($fileHandler, $sMsg."\r\n");
	fclose($fileHandler);
	
}

// Function for URL encode
function EW_Encode($str)
{
	return rawurlencode($str);
}

// Function for URL decode
function EW_Decode($str)
{
	return rawurldecode($str);
}

function long2str($v, $w)
{
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

function str2long($s, $w)
{
	$v = unpack("V*", $s. str_repeat("\0", (4 - strlen($s) % 4) & 3));
	$v = array_values($v);
	if ($w) {
		$v[count($v)] = strlen($s);
	}
	return $v;
}

// Function for TEA encrypt
function TEAencrypt($str, $key)
{
	if ($str == "") {
		return "";
	}
	$v = str2long($str, true);
	$k = str2long($key, false);
	if (count($k) < 4)
	{
		for ($i = count($k); $i < 4; $i++) {
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
		$sum = int32($sum + $delta);
		$e = $sum >> 2 & 3;
		for ($p = 0; $p < $n; $p++) {
			$y = $v[$p + 1];
			$mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
			$z = $v[$p] = int32($v[$p] + $mx);
		}
		$y = $v[0];
		$mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
		$z = $v[$n] = int32($v[$n] + $mx);
	}
	return urlencode(long2str($v, false));
}

// Function for TEA decrypt
function TEAdecrypt($str, $key)
{
	$str = urldecode($str);
	if ($str == "") {
		return "";
	}
	$v = str2long($str, false);
	$k = str2long($key, false);
	if (count($k) < 4)
	{
		for ($i = count($k); $i < 4; $i++) {
			$k[$i] = 0;
		}
	}
	$n = count($v) - 1;
	
	$z = $v[$n];
	$y = $v[0];
	$delta = 0x9E3779B9;
	$q = floor(6 + 52 / ($n + 1));
	$sum = int32($q * $delta);
	while ($sum != 0) {
		$e = $sum >> 2 & 3;
		for ($p = $n; $p > 0; $p--) {
			$z = $v[$p - 1];
			$mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
			$y = $v[$p] = int32($v[$p] - $mx);
		}
		$z = $v[$n];
		$mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
		$y = $v[0] = int32($v[0] - $mx);
		$sum = int32($sum - $delta);
	}
	return long2str($v, true);
}

function int32($n) {
	while ($n >= 2147483648) $n -= 4294967296;
	while ($n <= -2147483649) $n += 4294967296; 
	return (int)$n;
}

?>
