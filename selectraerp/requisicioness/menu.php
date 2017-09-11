<?php 
session_start();
ob_start();
$termino= $_SESSION['termino'];
?>
<?php
include("func_bd.php");
include("../lib/common.php");
/*
$Conn = conexion_conf(); //new mysqli($ConnSys["server"], $ConnSys["user"], $ConnSys["pass"], $ConnSys["db"]);
        
$var_sql="select imagen_izq,imagen_der,nomemp from parametros";
$rs = query($var_sql,$Conn);
$row_rs = fetch_array($rs);
$var_imagen_izq=$row_rs['imagen_izq'];
$var_imagen_der=$row_rs['imagen_der'];
$cadena1= substr($var_imagen_izq,3);
$cadena2= substr($var_imagen_der,3);
//$_SESSION[empresa] = $row_rs['nomemp'];

cerrar_conexion($Conn);
*/


$sSql = "SELECT * FROM nomempresa";
$result = sql_ejecutar($sSql);
$fila_empresa=fetch_array($result);

?>
<html class="menu-bg">
<head>
<title>SEIC</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<base target="cont">
<script language="JavaScript" type="text/javascript" src="../lib/prototype/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="../lib/common.js"></script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellpadding="1" cellspacing="1">
<tr>
<td colspan="2" class="menu-separador"></td>
</tr>
<?php  
$sSql = "SELECT * FROM nom_modulos WHERE cod_modulo_padre IS NULL ORDER BY orden";
$result = sql_ejecutar($sSql);      
//msgbox(mysql_num_rows($result));
if (num_rows($result) > 0) 
{   
    while ($row_rs = fetch_array($result))
    {
        if ( (($_SESSION['acce_procesos'] == 1) && ($row_rs['cod_modulo'] == 1)) || (($_SESSION['acce_reportes'] == 1) && ($row_rs['cod_modulo'] == 4)) || (($_SESSION['acce_transacciones']== 1) && ($row_rs['cod_modulo'] == 7)) || (($_SESSION['acce_personal'] == 1) && ($row_rs['cod_modulo'] == 9)) || (($_SESSION['acce_configuracion'] == 1) && ($row_rs['cod_modulo'] == 10)) || (($_SESSION['acce_elegibles'] == 1) && ($row_rs['cod_modulo'] == 60)) || (($_SESSION['acce_consultas'] == 1) && ($row_rs['cod_modulo'] == 61))|| (($_SESSION['acce_prestamos'] == 1) && ($row_rs['cod_modulo'] == 65)) || (($_SESSION['acce_generarordennomina'] == 1) && ($row_rs['cod_modulo'] == 66)) || ($row_rs['cod_modulo'] == 3) )
        {   
        ?>
        <tr  onclick="javascript:parent.<?if($row_rs['cod_modulo']!=2){?>cont.<?}?>location.href='<?php echo $row_rs['archivo']; ?>'" class="menu-fila" onMouseOver="over($(this),'menu-bg-hover');" onMouseOut="out($(this),'menu-bg-hover');">
        <td width="35"><a href="<?php echo $row_rs['archivo']; ?>" ><img src="img_sis/icons/<?php echo $row_rs['cod_modulo'];?>.png" border="0" align="absmiddle"></a></td>
        <td height="40"><a class=""  <?if($row_rs['cod_modulo']==2){?> target="_self"<?}?> href="menu_int.php?cod=<?php
        echo $row_rs['cod_modulo']; ?>"  > <strong><? echo $row_rs['nom_menu'] ?></strong>
        </a></td>
        </tr>
        <tr>
        <td colspan="2" class="menu-separador"></td>
        </tr>
        <?php 
        }
        else
        {
        ?>
        <tr  onclick="javascript:parent.cont.location.href='home.php?cod2=1'" class="menu-fila" onMouseOver="over($(this),'menu-bg-hover');" onMouseOut="out($(this),'menu-bg-hover');">
        <td width="35"><a href="home.php?cod2=1" ><img src="img_sis/icons/<?php echo $row_rs['cod_modulo'];?>.png" border="0" align="absmiddle"></a></td>
        <td height="40"><a class=""  <?if($row_rs['cod_modulo']==2){?> target="_self"<?}?> href="home.php?cod2=1"  > <strong><? echo $row_rs['nom_menu'] ?></strong>
        </a></td>
        </tr>
        <tr>
        <td colspan="2" class="menu-separador"></td>
        </tr>
        <?php 
        }
    } 
}
?>
<TR>
<TD colspan="2"></TD>
</TR>
<!--<TR> 
<TD colspan="2" align="center"><img src="<? echo "../../selectra/".$cadena1;?>" align="middle" width="100" height="106" align="middle" border="0"></TD> 
</TR> 

<TR> 
<TD colspan="2" align="center"><img src="<? echo "../../selectra/".$cadena2;?>" align="middle" width="100" height="70" align="middle" border="0"></TD> 
</TR>--> 
    
</table>

</body>
</html>
