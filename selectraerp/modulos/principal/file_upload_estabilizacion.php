<?php
ini_set("memory_limit","1024M");
ini_set("upload_max_filesize","2000M");
set_time_limit(0);

session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);
include("../../../generalp.config.inc.php");
require_once("../../../general.config.inc.php");
require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");

$comunes = new ConexionComun();
$comun = new Comunes();
$correlativos = new Correlativos();
$login = new Login();

function completar($cad,$long){
    $temp=$cad;
    for($i=1;$i<=$long-strlen($cad);$i++){
        $temp=" ".$temp;
    }
    return $temp;

}
function completarD($cad,$long){
    $temp=$cad;
    for($i=1;$i<=$long-strlen($cad);$i++){
        $temp=$temp." ";
    }
    return $temp; 
}
?>
<script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
<script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
<script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/development-bundle/ui/i18n/jquery.ui.datepicker-es.js"></script>
<script type="text/javascript" src="../../../includes/js/jquery-ui-timepicker-addon.js"></script>
<?php
$pyme=DB_SELECTRA_FAC;
$pos=POS;
//PATHS
$sql="SELECT codigo_siga, servidor FROM $pyme.parametros_generales";
$array_sucursal=$comunes->ObtenerFilasBySqlSelect($sql);
foreach ($array_sucursal as $key => $value) {
    $sucursal=$value['codigo_siga']; 
    $servidor=$value['servidor']; 
}

$ruta_master=$_SESSION['ROOT_PROYECTO']."/selectraerp/uploads";

$path_local=$ruta_master."/estabilizacion//";

if ($_FILES['archivo_productos']["error"] > 0)
{
  echo" <script type='text/javascript'>
  alert('Error Al Subir Archivo');
  history.go(-1);       
  </script>";
  exit();
}
if(!empty($_FILES['archivo_productos']))
{
    $string=".json";
    if(strnatcasecmp($string,substr($_FILES['archivo_productos']['name'],-5))!=0){

        echo"   <script type='text/javascript'>
        alert('Error El Formato De Archivo No Es Valido, Unicamente Extensiones JSON');
        history.go(-1);       
        </script>";exit();
    }

    $nombre=$_FILES['archivo_productos']['name'];
    if(move_uploaded_file($_FILES['archivo_productos']['tmp_name'],$path_local.$_FILES['archivo_productos']['name'])){
    }else{
        echo" <script type='text/javascript'>
        alert('Error Al Cargar Archivo');
        history.go(-1);       
        </script>";
        exit();

    }
    chmod($path_local.$_FILES['archivo_productos']['name'], 0777);

//*********************************JUNIOR AYALA**************************************************       
    //Leyendo el archivo JSON
    $data = file_get_contents($path_local.$_FILES['archivo_productos']['name']);
    $data = json_decode($data, true);

    //Sincronizando Rubros
    $comunes->Execute2("TRUNCATE TABLE `departamentos`");
    foreach ($data['rubros'] as $rubro=>$value)
    {
        $sql="INSERT INTO `departamentos`(`cod_departamento`, `descripcion`) VALUES ('".$value['cod_departamento']."','".$value['descripcion']."')";
        $comunes->Execute2($sql);
    }

    //Sincronizando Categorias
    $comunes->Execute2("TRUNCATE TABLE `grupo`");
    foreach ($data['categorias'] as $categoria=>$value)
    {
        $sql="INSERT INTO `grupo`(`cod_grupo`, `descripcion`, `id_rubro`, `restringido`, `cantidad_rest`, `dias_rest`, `grupopos`) VALUES ('".$value['cod_grupo']."', '".$value['descripcion']."', '".$value['id_rubro']."', '".$value['restringido']."', '".$value['cantidad_rest']."', '".$value['dias_rest']."', '".$value['grupopos']."')";
        $comunes->Execute2($sql);
    }

    //Sincronizando Sub Categorias
    $comunes->Execute2("TRUNCATE TABLE `sub_grupo`");
    foreach ($data['sub_categorias'] as $sub_categoria=>$value)
    {
        $sql="INSERT INTO `sub_grupo`(`id_sub_grupo`, `cod_grupo`, `descripcion`) VALUES ('".$value['id_sub_grupo']."','".$value['cod_grupo']."','".$value['descripcion']."')";
        $comunes->Execute2($sql);
    }

    //Sincronizando Productos
    $comunes->Execute2("TRUNCATE TABLE `item`");
    foreach ($data['productos'] as $producto=>$value)
    {
        $sql="INSERT INTO `item`(`id_item` , `codigo_barras` , `descripcion1` ,  `unidad_empaque` ,  `seriales` ,  `cod_departamento` ,  `cod_grupo` , `cantidad_bulto` ,  `kilos_bulto` ,  `id_marca` ,  `unidadxpeso` ,  `unidad_venta` , `pesoxunidad` ,  `producto_vencimiento`, `iva`, `produccion`) VALUES ('".$value['id_item']."','".$value['codigo_barras']."','".$value['descripcion1']."','".$value['unidad_empaque']."', '".$value['seriales']."', '".$value['cod_departamento']."', '".$value['cod_grupo']."', '".$value['cantidad_bulto']."', '".$value['kilos_bulto']."','".$value['id_marca']."','".$value['unidadxpeso']."','".$value['unidad_venta']."','".$value['pesoxunidad']."','".$value['producto_vencimiento']."','".$value['iva']."','".$value['sae']."')";
        $comunes->Execute2($sql);
    }

    //Sincronizando Clientes
    $comunes->Execute2("TRUNCATE TABLE `clientes`");
    foreach ($data['proveedores'] as $cliente=>$value)
    {
        $sql="INSERT INTO `clientes`(`id_cliente`, `cod_cliente`, `nombre`, `direccion`, `telefonos`, `fax`, `email`, `rif`, `estado`) VALUES ('".$value['id_proveedor']."', '".$value['cod_proveedor']."', '".$value['descripcion']."', '".$value['direccion']."', '".$value['telefonos']."', '".$value['fax']."', '".$value['email']."', '".$value['rif']."', 'A')";
        $comunes->Execute2($sql);
    }

    //Sincronizando Instalaciones Clientes
    $comunes->Execute2("TRUNCATE TABLE `instalacion_clientes`");
    foreach ($data['instalaciones'] as $instalacion=>$value)
    {
        $sql="INSERT INTO `instalacion_clientes`(`id`, `id_cliente`, `estado`, `direccion`, `codigo_sica`, `responsable`, `telefono_responsable`, `correo_responsable`) VALUES ('".$value['id']."','".$value['id_proveedor']."','".$value['estado']."','".$value['direccion']."','".$value['codigo_sica']."','".$value['responsable']."','".$value['telefono_responsable']."','".$value['correo_responsable']."')";
        $comunes->Execute2($sql);
    }

    $sql_arreglar_productos="UPDATE item SET unidadxpeso='5' where unidadxpeso='' or unidadxpeso='Seleccione' or unidadxpeso=0;
    UPDATE item SET unidad_venta='1' where unidad_venta='' or unidad_venta='Unidad';
    UPDATE item SET id_marca=1 WHERE id_marca=0 or id_marca='';
    UPDATE item SET cantidad_bulto=1 WHERE cantidad_bulto=0;
    UPDATE item SET kilos_bulto=1 WHERE kilos_bulto=0";
    $comunes->Execute2($sql_arreglar_productos);

    unlink($path_local.$nombre);
    echo" <script type='text/javascript'>
    alert('Exito: Sincronizacion de Data de Sede Central Correcta');
    history.go(-1);       
    </script>";
    exit();
}
?>