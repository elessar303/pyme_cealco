<?php

include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/almacen.php");
include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/clientes.php");
//$ruta="/var/www/pyme/selectraerp/uploads/temporal_subida_punto";
$ruta="c:/wamp/www/siscolp-pdval/pyme/selectraerp/uploads/temporal_subida_punto";
$almacen = new Almacen();
if(isset($_POST["aceptar"])){
    if ($_FILES['archivo1']["error"] > 0)  {

        echo "Error: " . $_FILES['archivo']['error'] . "<br>";
    }else {
      $tmp_name = $_FILES["archivo1"]["tmp_name"];
     echo   $name = $_FILES["archivo1"]["name"];exit;
     move_uploaded_file($tmp_name, $ruta."/".$name);
     chmod($ruta."/".$name,  0777);  
     // descomprimir archivo
     // //comentado miestras se ve si se descomprime o no
//    $zip = new ZipArchive;
//    if ($zip->open($name) === TRUE) {
//        $zip->extractTo($ruta,$name);
//        $zip->close();
//        echo 'ok';
//    } else {
//        echo 'failed';
//    }
     
     $pos=POS;
     $almacen->Execute2("delete from  $pos.external_sales");
     echo $sql="LOAD DATA LOCAL INFILE '".$ruta."/".$name."' INTO TABLE  ".$pos.".external_sales FIELDS TERMINATED BY ','  LINES TERMINATED BY '\n'";
     echo "listo";exit;
     system("mysql -u root -h localhost --password=admin.2040 --local_infile=1 -e \"$sql\" $pos");
    }
   
   
}

?>
