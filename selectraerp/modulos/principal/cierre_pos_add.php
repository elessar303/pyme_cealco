<?php
include("../../../generalp.config.inc.php");
include("../../libs/php/clases/usuarios.php");
require_once("../../libs/php/clases/ConexionComun.php");

$usuarios = new Usuarios();
$arrayCodModulo = array();
$arrayNomModulo = array();
$conn = new ConexionComun();

$bancos= $usuarios->ObtenerFilasBySqlSelect("Select * from banco");
//print_r($cajas); exit();
$siga=$usuarios->ObtenerFilasBySqlSelect("select codigo_siga from parametros_generales");
$smarty->assign("siga", $siga[0]['codigo_siga']);
foreach ($bancos as $key => $item) {
    $arraySelectOption[] = $item["cod_banco"];
    $arraySelectOutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_banco", $arraySelectOption);
$smarty->assign("option_output_banco", $arraySelectOutPut);

$arraySelectOption_tipo=array(0,1);
$arraySelectOutPut_tipo=array('PYME','POS');


if (isset($_POST["aceptar"])) 
{
  $login = new Login();
  $sql="Select monto_acumulado_tarjeta, monto_acumulado_deposito from caja_principal where fecha_deposito!='0000-00-00 00:00:00'  order by fecha_deposito desc limit 1";
  $montos_sistema=$usuarios->ObtenerFilasBySqlSelect($sql);
  $instruccion = "
   INSERT INTO `cierre_pos`(`siga`, `fecha`, `banco`,`cuenta`, `afiliacion_credito`, `terminal_credito`, `lote_credito`, `visa`, `master`, `total_credito`, `afiliacion_debito`, `terminal_debito`, `lote_debito`, `debito`, `alimentacion`, `total_debito`, `total_deposito`, `monto_acumulado_sistema`, `usuario`)
    VALUES ('" .$_POST["siga"]. "', '" . $_POST["fecha"] . "','" . $_POST["banco"] . "','" .$_POST["cuenta"] ."'
    ,'" .$_POST["afiliacion_credito"] ."','" .$_POST["terminal_credito"] ."','" .$_POST["lote_credito"] ."'
    ,'" .$_POST["monto_visa"] ."','" .$_POST["monto_master"] ."','" .$_POST["total_credito"] ."'
    ,'" .$_POST["afiliacion_debito"] ."','" .$_POST["terminal_debito"] ."','" .$_POST["lote_debito"] ."'
    ,'" .$_POST["monto_debito"] ."','" .$_POST["monto_alimentacion"] ."','" .$_POST["total_debito"] ."', '" .$_POST["total_deposito"] ."', '".($montos_sistema[0]['monto_acumulado_tarjeta']+$montos_sistema[0]['monto_acumulado_deposito'])."', '".$login->getIdUsuario()."'
    );";


    $usuarios->Execute2($instruccion);
    $id_transaccion = $usuarios->getInsertID();
    $instruccion = "
   INSERT INTO `cierre_pos_xenviar`(`id_original`, `siga`, `fecha`, `banco`,`cuenta`, `afiliacion_credito`, `terminal_credito`, `lote_credito`, `visa`, `master`, `total_credito`, `afiliacion_debito`, `terminal_debito`, `lote_debito`, `debito`, `alimentacion`, `total_debito`, `total_deposito`, `monto_acumulado_sistema`,`usuario`)
    VALUES (".$id_transaccion.", '" .$_POST["siga"]. "', '" . $_POST["fecha"] . "','" . $_POST["banco"] . "','" .$_POST["cuenta"] ."'
    ,'" .$_POST["afiliacion_credito"] ."','" .$_POST["terminal_credito"] ."','" .$_POST["lote_credito"] ."'
    ,'" .$_POST["monto_visa"] ."','" .$_POST["monto_master"] ."','" .$_POST["total_credito"] ."'
    ,'" .$_POST["afiliacion_debito"] ."','" .$_POST["terminal_debito"] ."','" .$_POST["lote_debito"] ."'
    ,'" .$_POST["monto_debito"] ."','" .$_POST["monto_alimentacion"] ."','" .$_POST["total_debito"] ."','" .$_POST["total_deposito"] ."', '".($montos_sistema[0]['monto_acumulado_tarjeta']+$montos_sistema[0]['monto_acumulado_deposito'])."', '".$login->getIdUsuario()."'
    );";

    //agregando en cataporte*******************************************+
    $nro_cataporte=$_POST['nro_referencia'];
    $fecha=date('Y-m-d H:i:s');
    $i=0;
    $num=$conn->ObtenerFilasBySqlSelect("Select count(*) as contar from caja_principal where id_cataporte='".$nro_cataporte."'");
    if($num[0]['contar']==0)
    {
      // insert del deposito
      $conn->BeginTrans();
      //insertar en comprobante
      if($_POST['total_credito']>0)
      {
        $insertC=$conn->ExecuteTrans("insert into comprobante (caja, banco, id_usuario, tipo_mov) values ('".$_POST['total_credito']."', '".$_POST['total_credito']."', '".$login->getIdUsuario()."', 'TRPC')");
        $insertar2=$conn->ExecuteTrans(
            "INSERT INTO 
            cataporte (nro_cataporte, tipo_cataporte, fecha, cod_usuario, monto_usuario, observacion, cuenta) 
            VALUES 
            ('".$nro_cataporte."', '2', '".$fecha."',".$login->getIdUsuario().", ".$_POST['total_credito'].", '', '".$_POST['cuenta']."')
            ");
      }
      if($_POST['total_debito']>0)
      {
        $insertC=$conn->ExecuteTrans("insert into comprobante (caja, banco, id_usuario, tipo_mov) values ('".$_POST['total_debito']."', '".$_POST['total_debito']."', '".$login->getIdUsuario()."', 'TRPD')");
        $insertar2=$conn->ExecuteTrans(
            "INSERT INTO 
            cataporte (nro_cataporte, tipo_cataporte, fecha, cod_usuario, monto_usuario, observacion, cuenta) 
            VALUES 
            ('".($nro_cataporte+1)."', '2', '".$fecha."',".$login->getIdUsuario().", ".$_POST['total_debito'].", '', '".$_POST['cuenta']."')
            ");
      }
      
      
      
      //echo "Select nro_deposito from caja_principal where fecha_deposito!='00:00:00 00:00:00'  order by id_deposito desc limit 1"; exit();
      $_POST['nro_deposito'] = $conn->ObtenerFilasBySqlSelect("Select nro_deposito from caja_principal where fecha_deposito!='00:00:00 00:00:00'  order by id_deposito desc limit 1");
      $monto_usuario=$_POST['total_debito']+$_POST['total_credito'];
      foreach ($_POST['nro_deposito'] as $key => $value ) 
      {
        $num1=$conn->ObtenerFilasBySqlSelect("Select monto_acumulado_tarjeta from caja_principal where nro_deposito='".$value['nro_deposito']."'");
        
        $acumuladototal=$num1[0]['monto_acumulado_tarjeta']-$monto_usuario;
        if($monto_usuario==0)
        {
          $monto_usuario=$acumuladototal;
          $acumuladototal=0;
        }

        $codigo=$conn->ObtenerFilasBySqlSelect("SELECT codigo_siga FROM parametros_generales");
        $codigo_siga=$codigo[0]['codigo_siga'];
        $regs=$conn->ObtenerFilasBySqlSelect("select max(id_deposito)+1 as id_deposito from caja_principal");
        $regs_deposito=$conn->ObtenerFilasBySqlSelect("select nro_deposito as id_deposito  from caja_principal order by fecha_deposito desc limit 1");
        
        if($regs[0]['id_deposito']=='')
        {
          $correlativo='000001';
        }
        if($regs[0]['id_deposito']!='')
        {
          $correlativo = sprintf("%06d", $regs[0]['id_deposito']);
        }
        $correlativo=$codigo_siga.$correlativo;
        
        //insertamos
        $insertar3=$conn->ExecuteTrans(
            "INSERT INTO caja_principal
            (id_deposito, nro_deposito, monto, monto_acumulado, id_cataporte, fecha_deposito, cod_banco, usuario_creacion, monto_tarjeta, monto_acumulado_tarjeta, monto_deposito, monto_acumulado_deposito, monto_tickets, monto_acumulado_tickets, monto_cheque, monto_acumulado_cheque, monto_credito, monto_acumulado_credito) 
            (select (id_deposito+1), '".$correlativo."', 0, monto_acumulado, '".$nro_cataporte."', now(), cod_banco, '".$login->getNombreApellidoUSuario()."', ".$monto_usuario.", ".$acumuladototal.", 0, monto_acumulado_deposito, 0, monto_acumulado_tickets,  0, monto_acumulado_cheque, 0, monto_acumulado_credito  from caja_principal where nro_deposito='".$value['nro_deposito']."')
            ");

               //se modifica depositos de esas cajas principales
        $select_depositos=$conn->ObtenerFilasBySqlSelect("select * from caja_principal_depositos where id_caja_principal='".$regs_deposito[0]['id_deposito']."'");
        if(isset($select_depositos) && $select_depositos!='')
        {
            foreach ($select_depositos as $key => $value) 
            {
                $conn->ExecuteTrans("update caja_principal_depositos set id_caja_principal='".$correlativo."' where id=".$value['id']);
            }
            
        }
        
        $verificar_deposito=$conn->ObtenerFilasBySqlSelect("Select count(id) as existe  from ingresos_xenviar where nro_deposito='".$correlativo."'");
       
        //preguntamos si el deposito existe en la tabla ingreso_xenviar
        if($verificar_deposito[0]["existe"]>0)
        {   //si el usuario no modifico el monto, se realiza el proceso normal
            if($monto_usuario==NULL)
            {
                $ingre_update=$conn->ExecuteTrans("update ingresos_xenviar set nro_cataporte='".$nro_cataporte."', fecha_cataporte='".$fecha."', usuario_creacion_cataporte='".$login->getNombreApellidoUSuario()."' where nro_deposito='".$correlativo."'");
            }
            else
            {
                $ingre_update=$conn->ExecuteTrans("update ingresos_xenviar set nro_cataporte='".$nro_cataporte."', fecha_cataporte='".$fecha."', usuario_creacion_cataporte='".$login->getNombreApellidoUSuario()."', monto_usuario_cataporte='".$monto_usuario."' where nro_deposito='".$correlativo."'");
            }
            
        }
        else
        {
            $deposito1=$conn->ObtenerFilasBySqlSelect("Select * from caja_principal where nro_deposito='".$correlativo."'");
            $ingre_insert=$conn->ExecuteTrans("insert into ingresos_xenviar 
              (nro_deposito, fecha_deposito, monto_deposito, cuenta_banco, usuario_creacion, nro_cataporte, fecha_cataporte, usuario_creacion_cataporte)
                VALUES
              ('".$correlativo."', '".$deposito1[0]['fecha_deposito']."', '".$monto_usuario."', '".$deposito1[0]['cod_banco']."', '".$deposito1[0]['usuario_creacion']."', '".$nro_cataporte."', '".$fecha."', '".$login->getNombreApellidoUSuario()."')");
        }
    }
      
  }
  $ver= $conn->CommitTrans(1);
  echo"<script language='javascript' type='text/JavaScript'>window.open('../../reportes/comprobante_contable_banco.php');</script>";
  $usuarios->Execute2($instruccion);
  echo "<script type=\"text/javascript\">
            alert('Registro Guardado');
            history.go(-1);
          </script>";
    exit;
    
}
?>
