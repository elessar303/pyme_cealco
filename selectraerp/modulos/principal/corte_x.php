<?php

if ($_GET["generar"] == "si") {
    include_once ("../../../generalp.config.inc.php");
    #$p = new ParametrosGenerales();
    #$parametros_impresora_fiscal = $p->ObtenerFilasBySqlSelect("SELECT tipo_facturacion, swterceroimp, impresora_marca FROM parametros_generales;");
    #$link = new mysqli(DB_HOST, DB_USUARIO, DB_CLAVE, $_SESSION['EmpresaFacturacion']);
    ##$link = mysqli_connect(DB_HOST, DB_USUARIO, DB_CLAVE, $_SESSION['EmpresaFacturacion']);
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
    #$resultado = $link->query($link,$query);
    ##$resultado = mysqli_query($link, $query);
    $resultado = mysql_query($query);
    if (!$resultado) {
        echo "Could not successfully run query ($sql) from DB: " . mysql_error();
        exit;
    }
    /* if (mysqli_connect_errno()) {
      echo("Conexión fallida: " . mysqli_connect_error());
      exit();
      } */
    #while ($parametros_impresora_fiscal = $resultado->fetch_assoc());
    ##while ($parametros_impresora_fiscal = mysqli_fetch_assoc($resultado));
    while ($parametros_impresora_fiscal = mysql_fetch_assoc($resultado)) {
        $tipo_facturacion = $parametros_impresora_fiscal["tipo_facturacion"];
        $swterceroimp = $parametros_impresora_fiscal["swterceroimp"];
        $impresora_marca = $parametros_impresora_fiscal["impresora_marca"];
    }
    mysql_free_result($resultado);
    /* $DB_HOST = DB_HOST;
      $DB_USUARIO = DB_USUARIO;
      echo "" . DB_HOST . "<br/>" . DB_USUARIO . "<br/>" . DB_CLAVE . "<br/>" . $_SESSION['EmpresaFacturacion'] . "<br/>swterceroimp:{$swterceroimp}, tipo_facturacion:{$tipo_facturacion}, impresora_marca:{$impresora_marca}";
      exit; */
    if ($tipo_facturacion/* $parametros_impresora_fiscal["tipo_facturacion"] */ == 1) {
        if ($swterceroimp/* $parametros_impresora_fiscal["swterceroimp"] */ == 1) {
            $directorio = "C:\FACTURAS\\";
            $nombre_archivo_spooler = "Selectra.001";
            $ruta = $directorio . $nombre_archivo_spooler;
            $archivo_spooler = fopen($ruta, "w");
            chmod($directorio, 0777);
            chmod($ruta, 0777);
            fwrite($archivo_spooler, "TIPO>X</TIPO");
            fclose($archivo_spooler);
        } elseif ($swterceroimp/* $parametros_impresora_fiscal["swterceroimp"] */ == 0) {
            switch ($impresora_marca/* $parametros_impresora_fiscal["impresora_marca"] */) {
                case "hasar":
                    /*$archivo_spooler = fopen("C:\Tools\x.txt", "w");
                    fwrite($archivo_spooler, "swterceroimp:{$swterceroimp}, tipo_facturacion:{$tipo_facturacion}, impresora_marca:{$impresora_marca}");
                    fclose($archivo_spooler);*/
                    include ("../../libs/php/clases/hasar/HasarPHP.php");
                    $objHasar = new HasarPHP();
                    $objHasar->setPort("p3");
                    $objHasar->partialCash();
                    break;
                case "dascon":
                case "hka112":
                case "bixolon":
                    include ("../../libs/php/clases/tfhka/TfhkaPHP.php");
                    $itObj = new Tfhka();
                    $itObj->SenCmd("I0X");
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
                    $cabecera = "<REPORTE_X>";
                  
                    fwrite($archivo, $cabecera);
                    fclose($archivo);
                    $comando="Comando.bat";
                    exec($comando);
                    break;
            }
        }
    }

    header("Location: index.php?opt_menu=106");
}
?>
