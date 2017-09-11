<?php 
session_start();
ob_start();
?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
header("Cache-Control: private, no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
?>
<?php // include ("ewconfig.php") ?>
<?php include ("func_bd.php") ?>
<?php //include ("clasificacion_unicainfo.php") ?>
<?php //include ("advsecu.php") ?>
<?php //include ("phpmkrfn.php") ?>
<?php //include ("ewupload.php") ?>
<?php

if (!IsLoggedIn()) {
	ob_end_clean();
	header("Location: login.php");
	exit();
}
?>
<html>
<head>
<link rel="shortcut icon" href="../imagenes/logo.ico" />
<title>.: SELECTRA :.</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="../estilos.css" rel="stylesheet" type="text/css">

</head>

<frameset rows="60,*" cols="*" frameborder="NO" border="0" framespacing="0">
  <frame src="sup.php" name="sup" frameborder="no" scrolling="NO" noresize marginwidth="0" marginheight="0" id="sup" >
  <frameset cols="200,*" frameborder="no" border="0" framespacing="0">
    <frame src="menu.php" name="menu" frameborder="no" noresize marginwidth="0" marginheight="0" id="menu">
    <frame src="home.php" name="cont" frameborder="no" noresize marginwidth="5" marginheight="5" id="cont">
  </frameset>
</frameset>
<noframes><body>
</body></noframes>
</html>
