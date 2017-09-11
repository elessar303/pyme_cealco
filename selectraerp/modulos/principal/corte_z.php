<?php

if ($_GET["generar"] == "si") {
    include_once ("../../../generalp.config.inc.php");
    $link = mysql_connect(DB_HOST, DB_USUARIO, DB_CLAVE);
    if (!$link) {
        echo "Unable to connect to DB: " . mysql_error();
        exit;
    }

    if (!mysql_select_db($_SESSION['EmpresaFacturacion'])) {
        echo "Unable to select db name: " . mysql_error();
        exit;
    }
    $query = "SELECT tipo_facturacion, swterceroimp, impresora_marca FROM parametros_generales";
    $resultado = mysql_query($query);
    if (!$resultado) {
        echo "Could not successfully run query ($sql) from DB: " . mysql_error();
        exit;
    }


    $select="select id_factura from factura where fecha_creacion between (select fecha_inicio from closedcash_pyme where fecha_fin is null and serial_caja='".impresora_serial."' order by fecha_inicio desc  limit 1) and now() limit 1";
     $total=mysql_num_rows(mysql_query($select));
        if($total==0){
            echo "<script type=\"text/javascript\">alert(\"No Existen Ventas, Corte Z Denegado.\"); window.location='index.php?opt_menu=106';</script>";  exit();

        }

    while ($parametros_impresora_fiscal = mysql_fetch_assoc($resultado)) {
        $tipo_facturacion = $parametros_impresora_fiscal["tipo_facturacion"];
        $swterceroimp = $parametros_impresora_fiscal["swterceroimp"];
        $impresora_marca = $parametros_impresora_fiscal["impresora_marca"];
    }
    mysql_free_result($resultado);
    if ($tipo_facturacion == 1) {
        if ($swterceroimp == 1) {
            $directorio = "C:\FACTURAS\\";
            $nombre_archivo_spooler = "Selectra.001";
            $ruta = $directorio . $nombre_archivo_spooler;
            $archivo_spooler = fopen($ruta, "w");
            chmod($directorio, 0777);
            chmod($ruta, 0777);
            fwrite($archivo_spooler, "TIPO>Z</TIPO");
            fclose($archivo_spooler);
        } elseif ($swterceroimp == 0) {

            switch ($impresora_marca/* $parametros_impresora_fiscal["impresora_marca"] */) {
                case "hasar":
                    include ("../../libs/php/clases/hasar/HasarPHP.php");
                    $objHasar = new HasarPHP();
                    $objHasar->setPort("p3");
                    $objHasar->closhCash();
                    break;
                case "dascon":
                case "hka112":
                case "bixolon":
                    include ("../../libs/php/clases/tfhka/TfhkaPHP.php");
                    $itObj = new Tfhka();
                    $itObj->SenCmd("I0Z");
                    break;
                 case "vmax":
                    $directorio = "C:\Elepos\Spooler\\";                   
                    $nombre_archivo_spooler = "files\selectrapos.txt";
                                     
                    //$directorio = "C:\Program Files\Elepos\Spooler\\";                   
                    //$nombre_archivo_spooler = "files\selectra.txt";
                    $ruta = $directorio. $nombre_archivo_spooler;
                    $archivo = fopen($ruta, "w");                   
                    chmod($directorio, 0777);
                    chmod($ruta, 0777);
                    $cabecera = "<REPORTE_Z>";
                  
                    fwrite($archivo, $cabecera);
                    fclose($archivo);
                    $comando="Comando.bat";
                    exec($comando); 
                    break;
            }
        }
    

            //debemos insertar el cierre de caja
  

    $query = "SELECT id, secuencia from closedcash_pyme where serial_caja='".impresora_serial."' and fecha_fin is null order by fecha_inicio desc limit 1";
    $resultado = mysql_query($query);
    $id_cierre=mysql_fetch_array($resultado);
    if($id_cierre==NULL){
    // buscando caja

    }else{

        $sql="select * from caja_impresora where serial_impresora='".impresora_serial."'";
        $procesando_query=mysql_query($sql);
        $nombre_caja=mysql_fetch_array($procesando_query);
        $update="update closedcash_pyme set fecha_fin=now() where id=".$id_cierre['id'];
        
        if(mysql_query($update)){

        $insert="INSERT INTO `closedcash_pyme`(nombre_caja, serial_caja, money, fecha_inicio, fecha_fin, secuencia) VALUES ('".$nombre_caja['caja_host']."', '".impresora_serial."','".impresora_serial.$nombre_caja['ip'].date('Y-m-d_H:i:s')."', now(), null, ".($id_cierre['secuencia']+1).")"; 


        if(!mysql_query($insert)){
            echo "Error ($insert) from DB: " . mysql_error(); exit();


        }else{
         // $insert="INSERT INTO `cierre_caja_control_pyme`(nombre_cajas, serial_cajas, secuencia, estatus_cierre) VALUES ('".$nombre_caja['caja_host']."', '".impresora_serial."', ".($id_cierre['secuencia']).", 1)";
         // mysql_query($insert);
        }

        }else{

             echo "Error ($insert) from DB: " . mysql_error();
        }

    }
 

    if (!$resultado) {
        echo "Could not successfully run query ($sql) from DB: " . mysql_error();
        exit;
    }



    }
    header("Location: index.php?opt_menu=106");
}
?>
