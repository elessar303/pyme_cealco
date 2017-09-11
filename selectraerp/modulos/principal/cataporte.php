<?php
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/clientes.php");
$producto = new Producto();
$bd_pyme=DB_SELECTRA_FAC;

//Cajas
$i=0;
//$sql="SELECT * FROM $bd_pyme.deposito WHERE fecha_deposito!='0000-00-00 00:00:00' AND id_cataporte='' and nro_deposito not like '%-%' ";
$sql="Select * from $bd_pyme.caja_principal where fecha_deposito!='0000-00-00 00:00:00' order by id_deposito desc limit 1";
//print_r($sql); exit();
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

$campos = $producto->cerrar();
        //echo "</table>";
?>