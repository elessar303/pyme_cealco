<?php 
session_start();
ob_start();
?>
<?php
include("../header.php") ;
include("func_bd.php") ;

$cod = (empty($_REQUEST['cod'])) ? '' : $_REQUEST['cod'];


$sSql = "SELECT * FROM nom_modulos WHERE cod_modulo = $cod";  
$result = sql_ejecutar($sSql);

//$rs = $Conn->query("SELECT * FROM modulos WHERE cod_modulo = $cod ");
//$row_rs = $rs->fetch_assoc();

$row_rs = mysql_fetch_array($result);

if ($row_rs['archivo'] <> '') {
	//header ('location: '.$row_rs['archivo']);
	activar_pagina($row_rs['archivo']);
	
}

?>


<table width="100%">
  <tr>
    <td class="row-br"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb-tit">
      <tr>
        <td><span style="float:left"><img src="img_sis/icons/<?= $row_rs['cod_modulo']?>.png" width="22" height="22" class="icon" /><? echo $row_rs['nom_menu'] ?></span></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td><div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
<? 

$sSql = "SELECT * FROM nom_modulos WHERE cod_modulo_padre = $cod ORDER BY orden";
//$rss = $Conn->query("SELECT * FROM modulos WHERE cod_modulo_padre = $cod ORDER BY orden");

$result = sql_ejecutar($sSql);


while ($row_rss = mysql_fetch_array($result)) {;?>
<? boton($row_rss[cod_modulo],$row_rss[nom_menu],$row_rss[archivo]) ?>
<? } //$rss->close();?>		  </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    </div>
    </td>
  </tr>
</table>
</body>
</html>
<? // $rs->close();?>
<? // $Conn->close();?>