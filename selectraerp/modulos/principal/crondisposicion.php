
<?php
ini_set("memory_limit","256M");
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);

require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/clases/login.php");
require_once("../../libs/php/clases/clase_db.inc.php");
include("../../libs/php/clases/correlativos.php");
include("../../libs/php/clases/factura.php");
include("../../libs/php/clases/almacen.php");

$conex1=$conn = new ConexionComun();
$correlativos = new Correlativos();
$factura = new Factura();
$almacen = new Almacen();
$login = new Login();

    
$fechaactual=date('Y-m-d 00:00:00');
$sql="select b.id from disposicion as a  inner join ubicacion as b on a.idubicacion=b.id where b.ocupado=2 and a.fecha_fin<='".$fechaactual."'";
$resultado=$almacen->ObtenerFilasBySqlSelect($sql);
if($resultado!=null)
{
    $almacen->BeginTrans();
    foreach ($resultado as $key => $ubicaciones) 
    {
        echo "asdasd";
        $sql="update ubicacion set ocupado=0 where id=".$ubicaciones['id'];
        $almacen->ExecuteTrans($sql);
            
    } //fin del foreach
}
if ($almacen->errorTransaccion == 1)
{    
    $almacen->CommitTrans($almacen->errorTransaccion);
    echo 
    "
        <script type='text/javascript'>
        alert('Exitoso');
        window.opener.location = window.opener.location;
        window.close();
        </script>
    ";
    exit();
}
elseif($almacen->errorTransaccion == 0)
{
    echo 
    "
        <script type='text/javascript'>
        alert('Error ');
        window.opener.location = window.opener.location;
        window.close();
        </script>
    ";
    exit();
}
    
?>