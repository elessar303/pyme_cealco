<?php
ob_start();
require_once("../../libs/php/adodb5/adodb.inc.php");
include("../../../general.config.inc.php");
include("../../../generalp.config.inc.php");
require_once("../../libs/php/configuracion/config.php");
require_once("../../libs/php/clases/ConexionComun.php");
require_once("../../libs/php/configuracion/config.php");
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");
require_once("../../libs/php/clases/login.php");
ob_end_clean();    header("Content-Encoding: None", true);
session_start();
$bd_pyme=DB_SELECTRA_FAC; $bd_pos=POS; $pass=DB_CLAVE; $user=DB_USUARIO; $host=DB_HOST;
$con = mysql_connect($host,$user,$pass);
$nro_cuenta=$_POST['banco'];$monto_dep=$_POST['monto'];
if(isset($_POST['sobrante']))
{
    $sobrante=$_POST['sobrante'];    
}
else
{
    $sobrante=0;
}
$cta_sobrante=$_POST['cta_sobrante'];
//se suma el sobrante y el monto real
$monto_dep+=isset($cta_sobrante) ? $cta_sobrante : 0;
//fechas
$dia=date("d"); $mes=date("m"); $ano=date("Y"); $hora=date("H"); $min=date("i"); $seg=date("s");
$path_ingresos="C:/wamp/www/siscolp_pyme/selectraerp/uploads/control_ingresos";
$fecha=date('Y-m-d H:i:s');
$fecha2=date('Y-m-d');
//se instancia a la clase de conexion
$conn = new ConexionComun(); 
$login = new Login();
//se bussca el maximo id de deposito.
$regs=$conn->ObtenerFilasBySqlSelect("select max(id_deposito)+1 as id_deposito from caja_principal");
$acumulado=$conn->ObtenerFilasBySqlSelect("select monto_acumulado, monto_acumulado_tarjeta, monto_acumulado_deposito, monto_acumulado_tickets, monto_acumulado_cheque, monto_acumulado_credito from caja_principal order by id_deposito desc limit 1");
//correlativo si es primera vez los montos acumulados son 0
if($regs[0]['id_deposito']=='')
{
    $correlativo='000001';
    $acumulado[0]['monto_acumulado']=0;
    $acumulado[0]['monto_acumulado_tarjeta']=0;
    $acumulado[0]['monto_acumulado_deposito']=0;
    $acumulado[0]['monto_acumulado_tickets']=0;
    $acumulado[0]['monto_acumulado_cheque']=0;
    $acumulado[0]['monto_acumulado_credito']=0;
}
//se agrega el monto deposito
$acumulado[0]['monto_acumulado']+=$monto_dep;
if($regs[0]['id_deposito']!='')
{
    $correlativo = sprintf("%06d", $regs[0]['id_deposito']);
}
//codigo siga
$codigo=$conn->ObtenerFilasBySqlSelect("SELECT codigo_siga FROM parametros_generales");
$codigo_siga=$codigo[0]['codigo_siga'];
$codigo=$conn->ObtenerFilasBySqlSelect("SELECT banco FROM cuentas_contables WHERE nro_cuenta='".$nro_cuenta."'");
$banco=$codigo[0]['banco'];
$nro_deposito=$codigo_siga.$banco.$correlativo;
// insert del deposito
$conn->BeginTrans();
$insertar=$conn->ExecuteTrans("insert into caja_principal (nro_deposito, monto, monto_acumulado, fecha_deposito, cod_banco, usuario_creacion)
    VALUES 
    ('".$nro_deposito."', ".$monto_dep.", ".$acumulado[0]['monto_acumulado'].", '".$fecha."', '".$nro_cuenta."', '".$login->getNombreApellidoUSuario()."')");
//guardando en la tabla ingresos
$insert_ingreso=$conn->ExecuteTrans("INSERT INTO ingresos_xenviar (nro_deposito, fecha_deposito, monto_deposito, cuenta_banco, usuario_creacion)
    VALUES
    ('".$nro_deposito."', date('".$fecha."'), ".$monto_dep.",  '".$nro_cuenta."', '".$login->getNombreApellidoUSuario()."')");

foreach ($_POST['arqueos_id'] as $key ) 
{
  $arqueos_id.=$key.",";  
}


$arqueos_id = trim($arqueos_id, ',');
$arqueos_id = str_replace("Array", "",$arqueos_id);
$arqueo=$conn->ExecuteTrans("update arqueo_cajero set id_deposito=".$nro_deposito.", id_deposito2=".($nro_deposito+1)." where id in (".$arqueos_id.")");
//se consulta para ver cuales son los datos de la tranasferencia faltante restante (tickets,deposito y tarjeta, cheque y credito)
/*$electronico=$conn->ObtenerFilasBySqlSelect("Select case when credito>=total_credito_sistema then credito else total_credito_sistema end as credito,  case when cheque>=total_cheque_sistema then cheque else total_cheque_sistema end as cheque, case when tarjeta>=total_tj_sistema then tarjeta else total_tj_sistema end as tarjeta, case when deposito>=total_deposito_sistema then deposito else total_deposito_sistema end as deposito, case when tickets>=total_tickets_sistema then tickets else total_tickets_sistema end as tickets from arqueo_cajero where id in (".$arqueos_id.")");*/
$electronico=$conn->ObtenerFilasBySqlSelect("Select credito as  credito, cheque as cheque, tarjeta as tarjeta,  deposito as deposito,  tickets  as tickets from arqueo_cajero where id in (".$arqueos_id.")");

// monto_credito=".$electronico[0]['credito'].",  monto_acumulado_credito=(monto_acumulado_credito+".($electronico[0]['credito']+$acumulado[0]['monto_acumulado_credito'])."),
$electronico_insert=$conn->ExecuteTrans("update caja_principal set monto_cheque=".$electronico[0]['cheque'].",  monto_acumulado_cheque=(monto_acumulado_cheque+".($electronico[0]['cheque']+$acumulado[0]['monto_acumulado_cheque'])."), monto_tarjeta=".$electronico[0]['tarjeta'].",  monto_acumulado_tarjeta=(monto_acumulado_tarjeta+".($electronico[0]['tarjeta']+$acumulado[0]['monto_acumulado_tarjeta'])."), monto_deposito=".$electronico[0]['deposito'].",  monto_acumulado_deposito=(monto_acumulado_deposito+".($electronico[0]['deposito']+$acumulado[0]['monto_acumulado_deposito'])."), monto_tickets=".$electronico[0]['tickets'].",  monto_acumulado_tickets=(monto_acumulado_tickets+".($electronico[0]['tickets']+$acumulado[0]['monto_acumulado_tickets']).") where nro_deposito='".$nro_deposito."'");

//obtenemos el id de la caja principal para guardar en caja_principal_depositos 
$select_id=$conn->ObtenerFilasBySqlSelect("select sum(b.monto_deposito) as deposito, a.id as arqueo, c.id_deposito as caja_principal from arqueo_cajero as a inner join arqueo_depositos as b on a.id=b.id_arqueo inner join caja_principal as c on a.id_deposito=c.nro_deposito   where a.id_deposito=".$nro_deposito);

//guardamos en caja_principal_depositos solo si se hicieron depositos
if($select_id[0]['deposito']>0)
{
    $caja_princial_arqueo=$conn->ExecuteTrans("insert into caja_principal_depositos (id_caja_principal, monto) (select ".$nro_deposito.", monto_deposito from arqueo_depositos where id_arqueo=".$select_id[0]["arqueo"].") ");
}

$ver= $conn->CommitTrans(1);

echo"<script language='javascript'>window.open('../../reportes/depositofpdf.php?nro_deposito=".$nro_deposito."&nro_cuenta=".$nro_cuenta."');</script>";
echo"<script language='javascript'>window.open('../../reportes/imprimir_arqueo_cajero.php?id=".$arqueos_id."&original=1');</script>";

echo "
<script language='javascript'>
window.history.back();
//window.opener.location.reload();
</script>
";
?>