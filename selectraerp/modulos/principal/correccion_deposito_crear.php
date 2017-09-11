<?php
include("../../libs/php/clases/almacen.php");
$almacen = new Almacen();
$login = new Login();
if(isset($_POST["aceptar"]))
{

  if(trim($_POST['nro_deposito_usuario'])=="" || trim($_POST['monto_usuario'])=="" || trim($_POST['observacion'])=="")
  {
    echo
    "
      <script language='javascript' type='text/JavaScript'>
      alert('Campos Vacios, Corregir');
      history.back(-1);
      </script>
    ";
    exit();
  }
  if(!is_numeric($_POST['monto_usuario']))
  {
    echo
    "
      <script language='javascript' type='text/JavaScript'>
      alert('Campo Monto debe Ser Numerico');
      history.back(-1);
      </script>
    ";
    exit();
  }


    $instruccion =
    "
      update cataporte  set nro_cataporte_usuario='".$_POST['nro_deposito_usuario']."', monto_usuario=".$_POST['monto_usuario'].",  usuario_correccion='".$login->getnombreApellidoUSuario()."', observacion='".$_POST['observacion']."', fecha_modificacion=now() where id='".$_POST['cod']."';
    ";
    
    $almacen->ExecuteTrans($instruccion);
    $instruccion=
    "
      INSERT INTO `ingresos_xenviar`(`nro_deposito`, `fecha_deposito`, `monto_deposito`, `cuenta_banco`, `usuario_creacion`, `nro_cataporte`, `fecha_cataporte`, `fecha_retiro`, `usuario_creacion_cataporte`, `nro_cataporte_usuario`, `monto_usuario_cataporte`, `usuario_correccion`, `fecha_correccion`, `observacion`)
      (SELECT a.nro_deposito, a.fecha_deposito, a.monto, a.cod_banco, a.usuario_creacion, a.id_cataporte, b.fecha, b.retirado, b.cod_usuario, b.nro_cataporte_usuario, b.monto_usuario, b.usuario_correccion, b.fecha_modificacion, b.observacion  FROM caja_principal as a inner join cataporte as b on a.id_cataporte=b.nro_cataporte where b.id='".$_POST['cod']."');
    ";
    $almacen->ExecuteTrans($instruccion);
  //}
  //$almacen->Execute2($instruccion);
  header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}
// Cargando region en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM cataporte where id='".$_GET['cod']."'");
$smarty->assign("nro_cataporte", $campos_comunes[0]['nro_cataporte']);
$smarty->assign("monto", $campos_comunes[0]['monto']);
$smarty->assign("cod", $_GET['cod']);

?>
