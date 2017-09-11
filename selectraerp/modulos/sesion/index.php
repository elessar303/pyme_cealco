<?php

ini_set("display_errors", 1);
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}

require_once("../../config.ini.php");
require_once("../../../generalp.config.inc.php");
require_once(RAIZ_PROYECTO . "/libs/php/clases/producto.php");

$productos = new Producto();
$campos_almacen = $productos->ObtenerFilasBySqlSelect("SELECT * FROM usuarios");

$login = new Login();
$smarty->assign("acceso", -1);
$smarty->assign("acceso2", -1);

if (isset($_POST["submit"])) {
    if ($login->validarAcceso($_POST['txtUsuario'], $_POST['txtContrasena']) == 1) {
        $cod_usuario=$login->getIdUsuario();
        $estatus=$login->getStatusUsuario();


        if($estatus==1){
        //se consulta si se habilito la sincronizacion
        $campos_generales =$productos->ObtenerFilasBySqlSelect("select sincronizacion_inv from parametros_generales");
       // se consulta la fecha de ayer para compararla con la de la BD
        $fechaAyer=strftime( "%Y-%m-%d",strtotime("-1 day"));
        $fecha_hoy=date('Y-m-d');
        //se busca la fecha de la ultima sincronizacion
        $campos_generales1 =$productos->ObtenerFilasBySqlSelect("SELECT * FROM apertura_tienda  WHERE apertura_date ='$fecha_hoy'");
        $filas=$productos->getFilas();

     $noSinc=0;
        // se compara la fecha de la BD con la de ayer 
        if($filas!=0){
            // si es mayor o igual se hizo la sincronizacion
           $noSinc=0;
            //echo "sincronizado";
           
        }else{
            //si es menor no se hizo la sincronizacion
           $noSinc=1;         
           // echo " no sincronizado";  
        }
       
        //si el check en configuracion esta seleccionado
       
        if($noSinc==1 ){
            echo "entro no sincronizado"; 
            header("Location: ../sincronizacion/");
            exit();
           
        }else{
            echo "entro  sincronizado"; 
            $smarty->assign("acceso", 1);
            header("Location: ../principal/?opt_menu=54");
        }
    }else{$smarty->assign("acceso2", 0);}
       
    } else {
        $smarty->assign("acceso", 0);
    }
}

if ($login->getIdSessionActual() != "" && $estatus==1) {
    header("Location: ../principal/?opt_menu=54");
    exit;
} else {
    $smarty->display("index.tpl");
}
?>
