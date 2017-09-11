<?php
ini_set("memory_limit","1024M");
ini_set("upload_max_filesize","2000M");

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
$correlativos = new Correlativos();
$comun = new Comunes();
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
<script language="JavaScript">

function actualizar_precio(prod, precio, trans)
{
    if(prod.value!=''){
        $.ajax({
            type: "GET",
            url:  "../../libs/php/ajax/ajax.php",
            data: "opt=actualizar_precio_producto&prod="+prod+"&precio="+precio+"&trans="+trans,
            success: function(data){
                if(data == "NO EXISTE EL PRODUCTO");
                $("#"+nom).html(data);
            }
        });
    }
}
</script>
<?php
$pos=POS;
//PATHS
$path_local="/var/www/pyme/selectraerp/uploads/sincronizacion_data_manual/";
$path_local_descomprimido="/var/www/pyme/selectraerp/uploads/sincronizacion_data_manual/descomprimido/";
$path_ventas="/var/www/pyme/selectraerp/uploads/ventas";
$path_inventario="/var/www/pyme/selectraerp/uploads/inventario_central";
$path_kardex="/var/www/pyme/selectraerp/uploads/kardex_central";
//$path_local="c://wamp/www/siscolp_pyme/selectraerp/uploads/temporal_subida_producto/";

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
    
    $string=".zip";
    if(strnatcasecmp($string,substr($_FILES['archivo_productos']['name'],-4))!=0)
    {
        echo"   <script type='text/javascript'>
                alert('Error El Formato De Archivo No Es Valido, Unicamente Extensiones ZIP');
                    history.go(-1);       
                   </script>";exit();
    }

    $nombre=$_FILES['archivo_productos']['name'].".zip";
    if(move_uploaded_file($_FILES['archivo_productos']['tmp_name'],$path_local.$_FILES['archivo_productos']['name'].".zip"))
    {
    }
    else
    {
        echo" <script type='text/javascript'>
         alert('Error Al Cargar Archivo');
         history.go(-1);       
        </script>";
        exit();
    }
    chmod($path_local.$_FILES['archivo_productos']['name'].".zip", 0777);

    //*********************************JUNIOR AYALA**************************************************       
    $directorio=dir("$path_local_descomprimido");
    $login = new Login();
    $cod_usuario=$login->getIdUsuario();

        //echo $nombre;
        $zip = new ZipArchive;
        $res = $zip->open($path_local."/".$nombre);
        if ($res === TRUE) {
        $zip->extractTo($path_local_descomprimido);
        $zip->close();
        //echo 'ok';
        } else {
        //echo 'failed';
        }
        
    while ($archivo = $directorio->read()) {

        if (substr($archivo,0,1)!=".") {

            $kard="_kardex.csv";
            $inve="_inventario.csv";
            $vent="_ventas.csv";
            
            if(strnatcasecmp($kard,substr($archivo,-11))==0){
                //echo "mover kardex".$archivo."<br>";
                rename ($path_local_descomprimido."/".$archivo,$path_kardex."/".$archivo);
            }
            
            if(strnatcasecmp($inve,substr($archivo,-15))==0){
                //echo "mover inventario".$archivo."<br>";
                rename ($path_local_descomprimido."/".$archivo,$path_inventario."/".$archivo);
            }
            
            if(strnatcasecmp($vent,substr($archivo,-11))==0){
                //echo "mover venta".$archivo."<br>";
                rename ($path_local_descomprimido."/".$archivo,$path_ventas."/".$archivo);
            }
        }
            
    }

        unlink($path_local.$nombre);
        echo" <script type='text/javascript'>
                    alert('Exito: Sincronizacion de Inventario y Movimientos Correcta');
                    history.go(-1);       
                    </script>";
                   exit();
    }

?>
