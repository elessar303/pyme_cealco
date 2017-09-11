<?php
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/clientes.php");
$producto = new Producto();
$bd_pyme=DB_SELECTRA_FAC;
$pos=POS;

//Cajas
$i=0;
$sql="";
/*$sql="SELECT * FROM $bd_pyme.arqueo_cajero 
LEFT JOIN $pos.people
ON arqueo_cajero.id_cajero=people.ID
WHERE id_deposito='-1'  order by fecha_arqueo";
*/

$campos = $producto->ObtenerFilasBySqlSelect("select venta_pyme from parametros_generales");
$smarty->assign('venta_pyme', $campos[0]['venta_pyme']);
	if($campos[0]['venta_pyme']==1)
	{
	//cuando es pos nada mas
	$sql=
		"SELECT * FROM $bd_pyme.arqueo_cajero inner JOIN $pos.people 
		ON arqueo_cajero.id_cajero=people.ID WHERE id_deposito='-1' order by fecha_arqueo";
	}
	if($campos[0]['venta_pyme']==0)
	{
	//cuando es pyme nada mas
	$sql="
		SELECT a.*, b.cod_usuario, b.nombreyapellido as NAME FROM $bd_pyme.arqueo_cajero as a inner JOIN 
		$bd_pyme.usuarios as b ON a.id_cajero=b.cod_usuario WHERE 
		a.id_deposito='-1' order by a.fecha_arqueo";
	}

	if($campos[0]['venta_pyme']==2)
	{
	// cuando es ambas
	$sql="
		(select a.*, b.id as id_cajero, b.NAME FROM $bd_pyme.arqueo_cajero as a inner JOIN $pos.people as b ON a.id_cajero=b.ID WHERE a.id_deposito='-1')
		union
		(select a.*, b.cod_usuario, b.nombreyapellido as NAME FROM $bd_pyme.arqueo_cajero as a inner JOIN $bd_pyme.usuarios as b ON a.id_cajero=b.cod_usuario WHERE a.id_deposito='-1' )
	
		order by fecha_arqueo";
	}


//echo $sql; exit();

$campos = $producto->ObtenerFilasBySqlSelect($sql);
$resultado=$producto->getFilas($campos);
while($i<$resultado){
$datos[$i]=$campos[$i]; //se guardan los datos en un arreglo
$i++;
}

$sql="SELECT * FROM $bd_pyme.parametros_generales";
$parametros = $producto->ObtenerFilasBySqlSelect($sql);

        /*Para poder mostrar los datos en el TPL debemos mandarlos por ciertas variables por el PHP y poder mostrar las consultas
        entre otras cosas*/
		$aceptar=$_POST['aceptar']; //es lo que viene por el POST del formulario
        $smarty->assign('aceptar', $aceptar);
        $smarty->assign('consulta', $datos); // la variable en la que guardamos el array de la consulta
        $smarty->assign('parametros', $parametros);
        $smarty->assign('bancos', $bancos);

$campos = $producto->cerrar();
        //echo "</table>";
?>
                
