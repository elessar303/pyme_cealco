<?
require_once '../lib/config.php';
require_once '../lib/common.php';
$Conn = conexion_conf();//new mysqli($ConnSys["server"], $ConnSys["user"], $ConnSys["pass"], $ConnSys["db"]);
$cod = (empty($_REQUEST['cod'])) ? '' : $_REQUEST['cod'];

$rs = query("SELECT * FROM modulos WHERE cod_modulo = $cod ",$Conn);
$row_rs = fetch_array($rs);

if ($row_rs['archivo'] <> '') {
	//header ('location: '.$row_rs['archivo']);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:spry="http://ns.adobe.com/spry" class="fondo">
<head>
<title>SEIC</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../lib/common.js"></script>
<script type="text/javascript" src="../lib/Prototype/prototype.js"></script>
</head>
<body>

<table width="100%">
  <tr>
    <td class=""><table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb-tit">
      <tr>
        <td><span style="float:left"><img src="../img_sis/icons/<?=$row_rs['cod_modulo'];?>.png" width="22" height="22" class="icon"><? echo $row_rs['nom_menu']; ?></span>          </td>
        <td><table border="0" align="right" cellpadding="0" cellspacing="0" onclick="javascript:window.location='../menu_int.php?cod=2';" class="btn_bg" style="cursor: pointer;">
          <tr>
            <td style="padding: 0px;" align="right"><div align="center"><img src="../img_sis/bt_left.gif" alt="" width="5" height="20" style="border-width: 0px;" /></div></td>
            <td class="btn_bg"><div align="center"><img src="../img_sis/ico_up.gif" width="16" height="16" /></div></td>
            <td class="btn_bg" style="padding: 0px 4px;"><div align="center">Regresar</div></td>
            <td style="padding: 0px;" align="left"><div align="center"><img src="../img_sis/bt_right.gif" alt="" width="5" height="20" style="border-width: 0px;" /></div></td>
          </tr>
          <table border="0" cellspacing="0" cellpadding="0" onclick="javascript:window.location='proveedores_add.php';">
            <tr>
              <td>
                <? //btn('add','proveedores_list.php') ?>
              </td>
            </tr>
          </table>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
<? $rss = query("SELECT * FROM modulos WHERE cod_modulo_padre = $cod ORDER BY orden",$Conn);
while ($row_rss = fetch_array($rss)) {;?>
<? boton1($row_rss['cod_modulo'],$row_rss['nom_menu'],$row_rss['archivo']); ?>
<? }; ?>		  </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    </div>
    </td>
  </tr>
</table>
<table width="774" height="126" border="0" align="center">
  <tr>
    <td height="122"><div align="center">
      <p>&nbsp;        </p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>
          
                                                    </p>
    </div></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<? cerrar_conexion($Conn);?>