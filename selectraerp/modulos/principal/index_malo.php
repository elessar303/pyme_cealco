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

if (isset($_POST["submit"])) {
    if ($login->validarAcceso($_POST['txtUsuario'], $_POST['txtContrasena']) == 1) {
        $cod_usuario=$login->getIdUsuario();        
        // $campos_usuario =$productos->ObtenerFilasBySqlSelect("SELECT a.cod_usuario, b.cod_modulo
        //         FROM modulo_usuario AS b, usuarios AS a
        //         WHERE a.cod_usuario = b.cod_usuario
        //         AND b.cod_modulo =  '106'
        //         AND a.cod_usuario ='".$cod_usuario."'");

        //se consulta si se habilito la sincronizacion
        $campos_generales =$productos->ObtenerFilasBySqlSelect("select sincronizacion_inv from parametros_generales");
       // se consulta la fecha de ayer para compararla con la de la BD
        $fechaAyer=strftime( "%Y-%m-%d",strtotime("-1 day"));
        //se busca la fecha de la ultima sincronizacion
        $campos_generales1 =$productos->ObtenerFilasBySqlSelect("SELECT * FROM control_sincronizacion
            WHERE fecha_cierre_debido = ( 
            SELECT MAX( `fecha_cierre_debido` ) 
            FROM control_sincronizacion )");
       $fechaBD=$campos_generales1[0]["fecha_cierre_debido"]; 
        $noSinc=0;
        // se compara la fecha de la BD con la de ayer 
        if($fechaBD >= $fechaAyer){
            // si es mayor o igual se hizo la sincronizacion
           $noSinc=0;
            //echo "sincronizado";
           
        }else{
            //si es menor no se hizo la sincronizacion
           $noSinc=1;         
           // echo " no sincronizado";  
        }
       
      
        //si el check en configuracion esta seleccionado
     
       
        if($campos_generales[0]["sincronizacion_inv"]==1 && $noSinc==1){
            echo "entro no sincronizado"; 
            header("Location: ../sincronizacion/");
            exit();
           
        }else{
            echo "entro  sincronizado"; 
            $smarty->assign("acceso", 1);
            header("Location: ../principal/?opt_menu=54");
        }
        

      
       
    } else {
        $smarty->assign("acceso", 0);
    }
}

if ($login->getIdSessionActual() != "") {
    header("Location: ../principal/?opt_menu=54");
    exit;
} else {
    $smarty->display("index.tpl");
}
?>
