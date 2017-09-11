<script>
function validar()
{
	var_tipo=window.document.form1.tipo_orden.value
	if(var_tipo=="Nota de Entrega")
	{
		document.form1.proveedor.disabled=true;
		document.form1.centro.disabled=false;
		document.form1.proveedor.value="TODOS"
	}
	else
	{
		document.form1.proveedor.disabled=false;
		document.form1.centro.disabled=true;
	}
}
</script>
<?
require_once '../lib/config.php';
require_once '../lib/common.php';
include ("../header.php");

$Conn = new mysqli($ConnSys["server"], $ConnSys["user"], $ConnSys["pass"], $ConnSys["db"]);
$var_proveedor=$_POST[proveedor];

if($var_proveedor<>0)
{
$rs = $Conn->query("SELECT cod_proveedor,compania FROM proveedores  where cod_proveedor = $var_proveedor");
while ($row_rs = $rs->fetch_assoc()) 
{
	$var_proveedor=$row_rs['compania'];
}
$rs->close();
}
else
{
	$var_proveedor="TODOS";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:spry="http://ns.adobe.com/spry">
<head>
<title>SEIC</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../lib/common.js"></script>
<script type="text/javascript" src="../lib/Prototype/prototype.js"></script>
</head>
<body>
<table width="100%" class="">
  <tr>
    <td class="row-br"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb-tit">
      <tr>
        <td>Consultas de Requisiciones <span style="float:left"></span>          </td>
        <td><table border="0" align="right" cellpadding="0" cellspacing="0" onclick="javascript:window.location='../menu_int.php?cod=1';" class="btn_bg" style="cursor: pointer;">
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
      <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td>		  <p>&nbsp;</p>
            <form name="form1" id="form1" method="post"  action="buscar_reporte_req.php"><div align="center">
              <table width="499" border="1" align="center">
                  <tr>
                    <th width="489" scope="col" class="tb-head">Consultas de Requisiciones</th>
                  </tr>
                  <tr>
                    <th scope="col" class="tb-head">Centro de Costo </th>
                  </tr>
                  <tr>
                    <th scope="col" class="tb-head"><span>
                      <?php select5("cod_centro", "descripcion", "", "SELECT * FROM centros  ORDER BY cod_centro","centro",3);?>
                    </span></th>
                  </tr>
                  <tr>
                    <th scope="col" class="tb-head">Tipo de Requisici&oacute;n </th>
                    </tr>
                  <tr>
                    <th scope="col" class="tb-head"><?php select5("cod_orden_tipo", "descripcion", "", "SELECT * FROM ordenes_tipos where descripcion <> '$var_descripcion2' and descripcion IS NOT NULL ORDER BY descripcion", "tipo_orden",3,"$var_descripcion2","$var_descripcion2"); ?></th>
                  </tr>
                  <tr>
                    <th scope="col" class="tb-head">Estado</th>
                  </tr>
                  <tr>
                    <th scope="col" class="tb-head"><select name="estado" id="estado">
                      <option>Todas</option>
                      <option>Registrada</option>
                      <option>Revisar</option>
                      <option>Adjudicada</option>
                      <option>Anulado</option>
                                        </select></th>
                  </tr>
                  <tr>
                    <th scope="col" class="tb-head"><input type="submit" name="Submit" value="Buscar" /></th>
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
