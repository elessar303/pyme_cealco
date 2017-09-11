<?php

// Advanced User Level Security for PHPMaker 4+
// User levels

define("ewAllowAdd", 1, true);
define("ewAllowDelete", 2, true);
define("ewAllowEdit", 4, true);
define("ewAllowView", 8, true);
define("ewAllowList", 8, true);
define("ewAllowReport", 8, true);
define("ewAllowSearch", 8, true);
define("ewAllowAdmin", 16, true);
$arUserLevel = NULL;
$arUserLevelPriv = NULL;
$ewCurLvl = CurrentUserLevel();
$dTableSecurity = NULL;
$dUserLevelPriv = NULL;
function SetUpUserLevel()
{

// User Level not used
}

// Get current user privilege
function CurrentUserLevelPriv($TableName)
{
	return GetUserLevelPrivEx($TableName, CurrentUserLevel());
}

// Get anonymous user privilege
function GetAnonymousPriv($TableName)
{
	return GetUserLevelPrivEx($TableName, 0);
}

// Get user privilege based on table name and user level
function GetUserLevelPrivEx($TableName, $UserLevel)
{
	global $arUserLevelPriv;
	$userLevelPrivEx = 0;
	if (strval($UserLevel) == "-1") {
		return 31;
	} elseif ($UserLevel >=0) {
		if (is_array($arUserLevelPriv)) {
			foreach ($arUserLevelPriv as $row) {
				if ((strtolower($row[0]) == strtolower($TableName)) And (strval($row[1]) == strval($UserLevel))) {
					$userLevelPrivEx = $row[2];
				if ((is_null($userLevelPrivEx))) $userLevelPrivEx = 0;
				if (!is_numeric($userLevelPrivEx)) $userLevelPrivEx = 0;
				return (int)($userLevelPrivEx);
				}
			}
		}
	}	
}

// Get current user level name
function CurrentUserLevelName()
{
	return GetUserLevelName(CurrentUserLevel());
}

// Get user level name based on user level
function GetUserLevelName($UserLevel)
{
	global $arUserLevel;
	if (strval($UserLevel) == "-1") {
		return "Administrator";
	} elseif ($UserLevel >= 0) {
		if (is_array($arUserLevel)) {
			foreach ($arUserLevel as $row) {
				if (strval($row[0]) == strval($UserLevel)) {
					return $row[1];
				}
			}
		}
	}
}

// Function to display all the User Level settings (for debug only)
function ShowUserLevelInfo()
{
	if (is_array($GLOBALS["arUserLevel"])) {
		print "User Levels:<br>";
		print "UserLevelID, UserLevelName<br>";
		$rows = $GLOBALS["arUserLevel"];
		for ($i=0;$i<count($rows);$i++) {
			print "&nbsp;&nbsp;".$rows[$i][0].",".$rows[$i][1]."<br>";
		}
	}	else {
		print "No User Level definitions."."<br>";
	}
	if (is_array($GLOBALS["arUserLevelPriv"])) {
		print "User Levels Privs:<br>";
		print "TableName, UserLevelID, UserLevelPriv<br>";
		$rows = $GLOBALS["arUserLevelPriv"];
		for ($i=0; $i<count($rows); $i++) {
			print "&nbsp;&nbsp;".$rows[$i][0].",".$rows[$i][1].",".$rows[$i][2]."<br>";
		}
	}	else {
		print "No User Level privilege settings."."<br>";
	}
	print "CurrentUserLevel = " . CurrentUserLevel()."<br>";
}

// Function to check privilege for List page (for menu items)
function AllowList($TableName)
{
	return (CurrentUserLevelPriv($TableName) & ewAllowList);
}

// Get current user name from session
function CurrentUserName()
{
	return @$_SESSION[ewSessionUserName];
}

// Get current user id from session
function CurrentUserID()
{
	return @$_SESSION[ewSessionUserID];
}

// Get current parent user id from session
function CurrentParentUserID()
{
	return @$_SESSION[ewSessionParentUserID];
}

// Get current user level from session
function CurrentUserLevel()
{
	if (IsLoggedIn()) {
		return @$_SESSION[ewSessionUserLevel];
	} else {
		return 0; //Anonymous if not logged in
	}
}

// Check if user is logged in
function IsLoggedIn()
{
	return (@$_SESSION[ewSessionStatus] == "login");
}

// Check if user is system administrator
function IsSysAdmin()
{
	return (@$_SESSION[ewSessionSysAdmin] == 1);
}

// Save user level to session
function SaveUserLevel()
{
	$_SESSION[ewSessionArUserLevel] = $GLOBALS["arUserLevel"];
	$_SESSION[ewSessionArUserLevelPriv] = $GLOBALS["arUserLevelPriv"];
}

// Load user level from session
function LoadUserLevel()
{
	if (!is_array(@$_SESSION[ewSessionArUserLevel])) {
		SetupUserLevel();
		SaveUserLevel();
	}
	$GLOBALS["arUserLevel"] = @$_SESSION[ewSessionArUserLevel];
	$GLOBALS["arUserLevelPriv"] = @$_SESSION[ewSessionArUserLevelPriv];
}
?>
