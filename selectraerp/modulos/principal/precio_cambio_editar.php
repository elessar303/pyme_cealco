<?php
session_start();
include('../../../menu_sistemas/lib/common.php');
require_once("../../../generalp.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/comunes.php");
require_once("../../libs/php/clases/login.php");
$comunes = new Comunes();
//$comunes2 = new ConexionComun();
$array_parametros_generales = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM parametros, parametros_generales");         
$pos=POS;
$message = $_GET["cod"];

if (isset($_GET["cod"])) {
//echo "SELECT * FROM $pos.taxes order by name";exit();
    
    //PRIMERO BUSCAMOS LOS IMPUESTOS

//BUSCAMOS EL TIPO DE IMPUESTO DEL PRODUCTO
$tax = $comunes->ObtenerFilasBySqlSelect("SELECT taxcat,category FROM  $pos.products where code='".$_GET['cod']."'");
$campos = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM $pos.taxes order by name");
$smarty->assign("tax",$tax);

foreach ($campos as $key => $taxes) {
   $arraySelectOption_taxes[] = $taxes["CATEGORY"];
    $arraySelectOutPut_taxes[] = $taxes["NAME"];
}
$smarty->assign("option_values_item_taxes", $arraySelectOption_taxes);
$smarty->assign("option_output_item_taxes", $arraySelectOutPut_taxes);

//BUSCAMOS LAS CATEGORIAS 
$campos = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM $pos.categories order by name");
foreach ($campos as $key => $cat) {
   $arraySelectOption_cat[] = $cat["ID"];
    $arraySelectOutPut_cat[] = $cat["NAME"];
}
$smarty->assign("option_values_item_cat", $arraySelectOption_cat);
$smarty->assign("option_output_item_cat", $arraySelectOutPut_cat);



$campos= $comunes->ObtenerFilasBySqlSelect("select b.name from $pos.products as a, $pos.taxes as b where a.code='".$_GET["cod"]."' and b.id=a.category");
$producto=array(nombre=>$_GET["nombre"],cod=>$_GET["cod"]);
$smarty->assign("producto_c",$producto);
//$smarty->assign("nombre_c",$_GET["nombre"]);
//$campos = $comunes->ObtenerFilasBySqlSelect("SELECT * FROM $pos.days_id WHERE DAY = " . $_GET["cod"]);
    
    
    //$smarty->assign("days_id", $campos);
}
//AQUI COMIENZA LA CARGA DE DATOS DEL FORMULARIO
if (isset($_POST["aceptar"]))
    {
  
    if(isset($_POST["nombre_producto"]) && isset($_POST["precio_producto"]) && $_POST["categoria"]!="" && $_POST["impuesto"]!="") 
        {
    
    //echo "select iva, id_item from item where codigo_barras='".$_GET["cod"]."'";exit();
    
    //VERIFICAMOS EL IMPUESTO CON RATE
    $campos= $comunes->ObtenerFilasBySqlSelect("select rate from $pos.taxes where category='".$_POST["impuesto"]."'");
//ECHO "select rate from $pos.products where category='".$_POST["impuesto"]."'";EXIT();    
//SI ES DIFERENTE DE 0 CALCULAMOS EL IVA
    $rate=$campos[0]["rate"]*100;
    //echo $campos[0]["rate"]; exit();
    if($rate>0){
       if(!empty($_POST["precio_producto"])){
           $precio=((($_POST["precio_producto"]*$rate)/100)+$_POST["precio_producto"]);
           
       }
       
       
    }
    else{
        if(!empty($_POST["precio_producto"])){
        $precio=$_POST["precio_producto"];
        }
    }
    //primero insertamos en pyme
 
    //AHORA BUSCAMOS LA CATEGORIA DEL PRODUCTO
    $campos= $comunes->ObtenerFilasBySqlSelect("select cod_grupo from grupo where grupopos='".$_POST["categoria"]."'");
    $categoria_producto=$campos[0]["cod_grupo"];
    $campos= $comunes->ObtenerFilasBySqlSelect("select iva, id_item from item where codigo_barras='".$_GET["cod"]."'");
    //realizamos los cambios
    $instruccion = "update item set 
        descripcion1='".$_POST['nombre_producto']."', cod_grupo='".$categoria_producto."', precio1 = '" .$precio. "',
            coniva1 = '" .$precio. "', precio2='".$precio."', coniva2='".$precio."',
                precio3='".$precio."', coniva3='".$precio."', iva='".$rate."'
                    where  id_item=".$campos[0]["id_item"]." and codigo_barras='".$_GET['cod']."'";    
    //$instruccion = "update $pos.days_id set MIN = '" . $_POST["minimo"] . "', MAX = '" . $_POST["maximo"] . "' where DAY = " . $_POST["day"];    
    
    $comunes->Execute2($instruccion);
    //fin de realizar cambios del pyme
   
    
    
    //ACTUALIZAR EL POS PRIMERO
    if(!empty($_POST["precio_producto"])){
        //echo "select id from $pos.products where code='".$_GET["cod"]."'"; exit();
        $campospos= $comunes->ObtenerFilasBySqlSelect("select id from $pos.products where code='".$_GET["cod"]."'");
        //echo $_POST['impuesto'];exit();
        if(!empty($campospos[0]["id"])){
            $instruccion = "update $pos.products set pricebuy = '" .$_POST["precio_producto"]. "', pricesell = '" .$_POST["precio_producto"]."', NAME='".$_POST['nombre_producto']."', CATEGORY='".$_POST['categoria']."', TAXCAT='".$_POST['impuesto']."' where  id='".$campospos[0]["id"]."' and code='".$_GET['cod']."'";    
        //echo "update $pos.products set pricebuy = '" .$_POST["precio_producto"]. "', pricesell = '" .$_POST["precio_producto"]."' where  id='".$campospos[0]["id"]."' and code='".$_GET['cod']."'"; exit();
            $comunes->Execute2($instruccion);
            
        }
    }
    
    //FIN DE MODIFICAR EL POS
    echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Cambio Exitoso')
     history.back(1)
    </SCRIPT>");
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}else{
    echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Error, Faltan Algunos De Los Campos')
    history.back(1)
    </SCRIPT>");exit();
}

    }







?>
