<?php
if (!isset($_SESSION))
{ session_start();
ob_start(); 
} 
	require_once '../lib/config.php';
	require_once '../lib/common.php';
	$Conn = conexion();
	$var_sql_con=$_GET['buscar'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:spry="http://ns.adobe.com/spry" class="fondo">
<head>
<title>Reporte de Proveedores</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../lib/common.js"></script>
<script type="text/javascript" src="../lib/Prototype/prototype.js"></script>
</head>
<body>
<table width="100%">
  <tr>
    <td class="row-br"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb-tit">
      <tr>	
        <td>Reporte de Proveedores           </td>
        <td><table border="0" align="right" cellpadding="0" cellspacing="0" onclick="javascript:window.location='/sisalud/selectraerp/modulos/principal/?opt_menu=83';" class="btn_bg" style="cursor: pointer;">
          <tr>
            <td style="padding: 0px;" align="right"><div align="center"><img src="../img_sis/bt_left.gif" alt="" width="15" height="20" style="border-width: 0px;" /></div></td>
            <td class="btn_bg"><div align="center"><img src="../img_sis/ico_up.gif" width="16" height="16" /></div></td>
            <td class="btn_bg" style="padding: 0px 4px;"><div align="center">Regresar</div></td>
            <!--<td style="padding: 0px;" align="left"><div align="center"><img src="../img_sis/bt_right.gif" alt="" width="5" height="20" style="border-width: 0px;" /></div></td>-->
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
          <td>		  <p>&nbsp;</p>
            <form name="form1" id="form1" method="post" action=""><div align="center">
              <table width="200" border="1" class="ewTableHeader">
                  <tr>
                    <th class="tb-head" colspan="3" scope="col">Reporte de Proveedores</th>
                  </tr>
                  <tr>
                    <th class="tb-fila" colspan="3" scope="col">Ordenar por: </th>
                  </tr>
                  <tr>
                    <th class="tb-head" width="56" scope="col">C&oacute;digo</th>
                    <th class="tb-head" width="74" scope="col">Nombre</th>
                    <th class="tb-head" width="74" scope="col">Inactivos</th>
                    
                  </tr>
                  <tr>
                    <th class="tb-fila" scope="col"><p>&nbsp;
                      </p>
                      <p><a  href="reportes_pro_print.php?buscar=cod_proveedor" target="_blank" ><img title="Imprimir" src="../img_sis/ico_print.gif" width="16" height="16" border="0" /></a><span class="ewTableAltRow">
                      </span> </p>                      
                      <p>&nbsp;                      </p></th>
                    <th class="tb-fila" scope="col"><a href="reportes_pro_print.php?buscar=compania" target="_blank" ><img title="Imprimir" src="../img_sis/ico_print.gif" width="16" height="16" border="0" /></a></th>
                    <th class="tb-fila" scope="col"><a href="reportes_pro_print2.php?buscar=estatus" target="_blank" ><img title="Imprimir" src="../img_sis/ico_print.gif" width="16" height="16" border="0" /></a></th>
                  
                  </tr>
                </table>
                </div>
            </form>            </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    </div>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
