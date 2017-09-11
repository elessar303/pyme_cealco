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
$path_local="/var/www/pyme/selectraerp/uploads/sincronizacion_data/";
$path_local_descomprimido="/var/www/pyme/selectraerp/uploads/sincronizacion_data/descomprimido/";
$path_ventas="/var/www/pyme/selectraerp/uploads/ventas";
$path_ventas_pyme="/var/www/pyme/selectraerp/uploads/ventas_pyme";
$path_inventario="/var/www/pyme/selectraerp/uploads/inventario_central";
$path_kardex="/var/www/pyme/selectraerp/uploads/kardex_central";
$path_ingresos="/var/www/pyme/selectraerp/uploads/control_ingresos_central";
$path_libros="/var/www/pyme/selectraerp/uploads/libro_venta_central";
$path_comprobantes_cab="/var/www/pyme/selectraerp/uploads/comprobantes";
$path_comprobantes_det="/var/www/pyme/selectraerp/uploads/comprobantes_detalle";
$path_comprobantes_ing="/var/www/pyme/selectraerp/uploads/comprobantes_ingresos";
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
    
    $string="_data";
    if(strnatcasecmp($string,substr($_FILES['archivo_productos']['name'],-5))!=0)
    {
        echo"   <script type='text/javascript'>
                alert('Error El Formato De Archivo No Es Valido, Unicamente Extensiones _data');
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

            $vent="_ventas.csv";
            $kard="_kardex.csv";
            $inve="_inventario.csv";
            $pyme="_pyme.csv";
            $ing="_ingresos.csv";
            $comp_cab="_comprobante_cabecera.csv";
            $comp_det="_comprobante_detalle.csv";
            $comp_ing="_ingresos_detalle.csv";
            $libro="_libro.csv";
            if(strnatcasecmp($vent,substr($archivo,-11))==0){
                //echo "mover venta".$archivo."<br>";
                rename ($path_local_descomprimido."/".$archivo,$path_ventas."/".$archivo);
            }
            
            if(strnatcasecmp($kard,substr($archivo,-11))==0){
                //echo "mover kardex".$archivo."<br>";
                rename ($path_local_descomprimido."/".$archivo,$path_kardex."/".$archivo);
            }
            
            if(strnatcasecmp($inve,substr($archivo,-15))==0){
                //echo "mover inventario".$archivo."<br>";
                rename ($path_local_descomprimido."/".$archivo,$path_inventario."/".$archivo);
            }

            if(strnatcasecmp($pyme,substr($archivo,-9))==0){
                //echo "mover ventas pyme".$archivo."<br>";
                rename ($path_local_descomprimido."/".$archivo,$path_ventas_pyme."/".$archivo);
            }
            if(strnatcasecmp($ing,substr($archivo,-13))==0){
                //echo "mover ingresos".$archivo."<br>";
                rename ($path_local_descomprimido."/".$archivo,$path_ingresos."/".$archivo);
            }
            if(strnatcasecmp($comp_cab,substr($archivo,-25))==0){
                //echo "mover ingresos".$archivo."<br>";
                rename ($path_local_descomprimido."/".$archivo,$path_comprobantes_cab."/".$archivo);
            }
            if(strnatcasecmp($comp_det,substr($archivo,-24))==0){
                //echo "mover comprobante detalle".$archivo."<br>";
                rename ($path_local_descomprimido."/".$archivo,$path_comprobantes_det."/".$archivo);
            }
            if(strnatcasecmp($comp_ing,substr($archivo,-21))==0){
                //echo "mover comprobante ingresos".$archivo."<br>";
                rename ($path_local_descomprimido."/".$archivo,$path_comprobantes_ing."/".$archivo);
            }
            if(strnatcasecmp($libro,substr($archivo,-10))==0){
                //echo "mover ingresos".$archivo."<br>";
                rename ($path_local_descomprimido."/".$archivo,$path_libros."/".$archivo);
            }
        }
            
    }

        unlink($path_local.$nombre);
        echo" <script type='text/javascript'>
                    alert('Exito: Sincronizacion de Data Correcta');
                    history.go(-1);       
                    </script>";
                   exit();
    }

?>
