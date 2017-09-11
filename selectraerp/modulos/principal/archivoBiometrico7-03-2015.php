<?php
session_start();
$_SESSION['ROOT_PROYECTO1']="/var/www/pyme";
ini_set("display_errors", 1);

require_once("../../libs/php/adodb5/adodb.inc.php");
require_once("../../libs/php/configuracion/config.php");

require_once("../../libs/php/clases/ConexionComun.php");

require_once("../../libs/php/clases/login.php");
#include_once("../../../libs/php/clases/compra.php");
include_once("../../libs/php/clases/correlativos.php");
require_once "../../libs/php/clases/numerosALetras.class.php";
include("../../../menu_sistemas/lib/common.php");
include("../../libs/php/clases/producto.php");
$comunes = new Producto();
// $comunes = new ConexionComun();


 function iComprimir($sFichOrigen, $iNivelComp = 2){
        
        $sFichDetino = $sFichOrigen.".gz";
        
        if ( ! $fOrigen = @fopen($sFichOrigen, "rb"))
            return false;
        $sOriBin = fread($fOrigen, filesize($sFichOrigen));
        fclose($fOrigen);
        
        $sDesGZ = gzencode($sOriBin, $iNivelComp);

        if ( ! $fDestino = @fopen ($sFichDetino, "wb"))
            return false;
        fwrite($fDestino, $sDesGZ);
        fclose($fDestino);

        return true;
        
    } 






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
$pos=POS;
$tipo="E";
$cadena="113";
$sucursal="032";
$dia=date("d");
$mes=date("m");
$ano=date("y");
$hora=date("H");
$min=date("i");
$seg=date("s");
echo  $nomb=$tipo.$cadena.$sucursal.$dia.$mes.$ano.$hora.$min.".txt";
#echo  $nomb="E1130320309141636.txt";
$ultimaFecha = $comunes->ObtenerFilasBySqlSelect("SELECT * from $pos.control_fecha_archivo ORDER BY id DESC LIMIT 1");
$fila=$comunes->getFilas();
if($fila==0){
	echo $fechaF=strftime( "%Y-%m-%d %H:%M:%S", time() );
    echo $fechaI=strftime( "%Y-%m-%d %H:%M:%S",strtotime("-30 minute"));

}else{
	echo $fechaF=strftime( "%Y-%m-%d %H:%M:%S", time());
	$fechaI= strtotime ( '+1 second' , strtotime ( $ultimaFecha[0]["fecha"]." ".$ultimaFecha[0]["hora"].":".$ultimaFecha[0]["minutos"].":".$ultimaFecha[0]["segundos"] ) ) ;
	echo $fechaI=strftime( "%Y-%m-%d %H:%M:%S",$fechaI);
    // echo $fechaI=strftime( "%Y-%m-%d %H:%M:%S",strtotime("-30 minute"));
}


 

// $array_venta = $comunes->ObtenerFilasBySqlSelect(" SELECT people.NAME,
// 	  payments.PAYMENT,
// 	  sum(payments.TOTAL) as TOTAL
// 	FROM $pos.payments INNER JOIN
// 	  $pos.receipts ON receipts.ID = payments.RECEIPT INNER JOIN
// 	  $pos.tickets ON tickets.ID = receipts.ID INNER JOIN
// 	  $pos.people ON tickets.PERSON = people.ID       
// 	WHERE DATENEW BETWEEN '{$inicio}' AND '{$final}'
// 	GROUP BY people.NAME, payments.PAYMENT  
// 	ORDER BY NAME");
  $array_venta = $comunes->ObtenerFilasBySqlSelect("SELECT d.taxid, g.cod_categoria, a.tickettype,
   (b.units*1000) AS units,
   DATE_FORMAT(cast(b.datenew as date), '%Y%m%d') as fecha,
   TIME_FORMAT(cast(b.datenew as time),'%H') as hh,
   TIME_FORMAT(cast(b.datenew as time),'%i') as mm,
   TIME_FORMAT(cast(b.datenew as time),'%S') as ss,
   f.host
   from $pos.tickets as a, $pos.ticketlines as b, $pos.products as c, $pos.customers as d, $pos.receipts as e, $pos.closedcash as f, $pos.categoria_conversor as g
   where a.id=b.ticket and b.product=c.id and c.category=g.id_categoria_pos and a.customer=d.id and a.id=e.id and e.money=f.money
   AND b.DATENEW BETWEEN '".$fechaI."' AND '".$fechaF."'");
$filas=$comunes->getFilas();
if($filas==0){
	$contenido="";
}else{
	foreach ($array_venta as $key => $value) {
		if($value["tickettype"]==0)
			$signo=" ";
		else
			$signo="-";
		 $contenido .=  completarD(str_replace(".","",$value["taxid"]),12).completar($value["cod_categoria"],4).$signo.completar(abs($value["units"]),5).$value["fecha"].$value["hh"].$value["mm"].$value["ss"].$cadena.$sucursal.substr($value["host"],4)."\r\n";
		
		
		
	}
        $pf=fopen("PDVAL"."/".$nomb,"w+");
        fwrite($pf, "$contenido");
        fclose($pf);
        chmod("PDVAL"."/".$nomb,  0777);
}


if($filas>0){
    if (iComprimir ("/var/www/pyme/selectraerp/modulos/principal/PDVAL/".$nomb, 2))
    {
        echo "Fichero comprimido satisfactoriamente !!!";
    }else{
        echo "Se ha producido un error en la compresion del fichero.<br>";
        echo "Asegurese de que la ruta del fichero a comprimir es valida y tiene permisos de escritura";
    } 
};

 $instruccion = "
INSERT INTO ".$pos.".control_fecha_archivo (
`fecha`,
`hora`,
`minutos`,
`segundos`
)
VALUES (
 '".$ano."-".$mes."-".$dia."','".$hora."','".$min."','".$seg."'
);
";
$comunes->Execute2($instruccion);

session_destroy();

//OBTENER INFORMACION DE LOS ARCHIVOS



//
// $pf=fopen("PDVAL"."/".$nomb,"w+");
//        fwrite($pf, "$contenido");
//        fclose($pf);
//        chmod("PDVAL"."/".$nomb,  0777);




if($filas>0){
$connection = ssh2_connect('192.168.8.45', 22);
ssh2_auth_password($connection, 'root', 'pdval2021');

//ssh2_scp_send($connection, "PDVAL"."/".$nomb, "/var/www/pyme/selectraerp/modulos/principal/pdvalcentral/".$nomb, 0777);
//ssh2_scp_send($connection, "PDVAL"."/E1130320309141636.txt", "/var/www/pyme/selectraerp/modulos/principal/pdvalcentral/E1130320309141637.txt", 0777)
if(ssh2_scp_send($connection, "PDVAL"."/".$nomb, "/var/www/pyme/selectraerp/modulos/principal/pdvalcentral/".$nomb, 0777)){
    
    echo "Exitoso";
    
}else{
    
    echo "No Exitoso";
}


}




/*
if($filas>0){

#           PROBANDO CON FTP 
define("SERVER","10.8.0.1"); //IP o Nombre del Servidor
define("PORT",21); //Puerto
define("USER","usuario113"); //Nombre de Usuario
define("PASSWORD","Tx113AMe"); //Contrase?a de acceso
#$define("PASV",true); //Activa modo pasivo

# FUNCIONES

function ConectarFTP(){
//Permite conectarse al Servidor FTP
$id_ftp=ftp_connect(SERVER,PORT); //Obtiene un manejador del Servidor FTP
ftp_login($id_ftp,USER,PASSWORD); //Se loguea al Servidor FTP
ftp_pasv($id_ftp,MODO); //Establece el modo de conexi?n
return $id_ftp; //Devuelve el manejador a la funci?n
}

function SubirArchivo($archivo_local,$archivo_remoto){
//Sube archivo de la maquina Cliente al Servidor (Comando PUT)
$id_ftp=ConectarFTP(); //Obtiene un manejador y se conecta al Servidor FTP
ftp_put($id_ftp,$archivo_remoto,$archivo_local,FTP_BINARY);
//Sube un archivo al Servidor FTP en modo Binario
ftp_quit($id_ftp); //Cierra la conexion FTP
return $id_ftp;
}

function ObtenerRuta(){
//Obriene ruta del directorio del Servidor FTP (Comando PWD)
$id_ftp=ConectarFTP(); //Obtiene un manejador y se conecta al Servidor FTP
$Directorio=ftp_pwd($id_ftp); //Devuelve ruta actual p.e. "/home/willy"
ftp_quit($id_ftp); //Cierra la conexion FTP
return $Directorio; //Devuelve la ruta a la funci?n
}


if(SubirArchivo("PDVAL"."/".$nomb.".gz","carga/".$nomb.".gz")){
    echo "Envio Exitoso";
}else{
    echo "Error al Enviar Archivo";
}



}
*/






?>