<?php session_start();

    $titulo="Reporte Productos PERT NO PERT";
    header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
    header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");  
    header ("Cache-Control: no-cache, must-revalidate");  
    header ("Pragma: no-cache");  
    header ("Content-type: application/x-msexcel");  
    header ("Content-Disposition: attachment; filename=\"".$titulo.".xls\"" );

             
    echo $_SESSION['basicaPerNoPer'];