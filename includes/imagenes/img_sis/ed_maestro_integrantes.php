<?php 
session_start();
ob_start();

$termino=$_SESSION['termino'];
include("../header.php");
include("func_bd.php");	

include("../lib/common.php");

$bandera=$_GET['bandera'];

?>
<html>
<head>

<script language="JavaScript" type="text/javascript">
function cargar_foto(){
	var archivo=document.getElementById("mifichero")
	var imagen=document.getElementById("imgFoto")
	var cadena=archivo.value.split("/")
	var cantidad=cadena.length
}
function calcular_antiguedad(fecha){
    hoy=new Date()
    var array_fecha = fecha.split("/")

    if (array_fecha.length!=3)
       return "La fecha introducida es incorrecta"
    var ano
    ano = parseInt(array_fecha[2]);
    if (isNaN(ano))
       return "El año es incorrecto"

    var mes
    mes = parseInt(array_fecha[1]);
    if (isNaN(mes))
       return "El mes es incorrecto"

    var dia
    dia = parseInt(array_fecha[0]);
    if (isNaN(dia))
       return "El dia introducido es incorrecto"

    if (ano<=99){
       ano +=1900
		
	 }

    edad=hoy.getFullYear()- ano - 1; //-1 porque no se si ha cumplido años ya este año
	 var meses

	 if (hoy.getMonth() + 1 - mes > 0 ){
		 meses=hoy.getMonth() + 1 - mes
       edad= edad+1
	 }
		if(hoy.getMonth() + 1 - mes < 0)
		{
			meses=hoy.getMonth() + 1 - mes+12
		}
		if(hoy.getMonth() + 1 - mes == 0){
			meses=0
	 }


			
	var dias
    //entonces es que eran iguales. miro los dias
    //si resto los dias y me da menor que 0 entonces no ha cumplido años. Si da mayor o igual si ha cumplido
    if (hoy.getDate() - dia >= 0){
       dias=hoy.getDate() - dia
		if(meses==0){	
		 edad=edad + 1
		}
	}else{
		dias=hoy.getDate()-dia+30
	}

    var antiguedad=edad+" A&#241;os, "+meses+" Meses y "+dias+" Dias"
	document.getElementById('antiguedad').innerHTML="<strong>"+antiguedad+"</strong>"
} 
function actualizar(valor1,valor2){
	var temp1=document.getElementById(valor1)
	var temp2=document.getElementById(valor2)
	
	var edad=calcular_edad(temp1.value)
	var cadena=edad.toString()
	temp2.innerHTML="<strong>"+cadena+" A&#241;os</strong>"

	

}


function calcular_edad(fecha){

    //calculo la fecha de hoy
    hoy=new Date()
    //alert(hoy)

    //calculo la fecha que recibo
    //La descompongo en un array
    var array_fecha = fecha.split("/")
    //si el array no tiene tres partes, la fecha es incorrecta
    if (array_fecha.length!=3)
       return false

    //compruebo que los ano, mes, dia son correctos
    var ano
    ano = parseInt(array_fecha[2]);
    if (isNaN(ano))
       return false

    var mes
    mes = parseInt(array_fecha[1]);
    if (isNaN(mes))
       return false

    var dia
    dia = parseInt(array_fecha[0]);
    if (isNaN(dia))
       return false


    //si el año de la fecha que recibo solo tiene 2 cifras hay que cambiarlo a 4
    if (ano<=99){
       ano +=1900
		
	 }
		
    //resto los años de las dos fechas
    edad=hoy.getFullYear()- ano - 1; //-1 porque no se si ha cumplido años ya este año

    //si resto los meses y me da menor que 0 entonces no ha cumplido años. Si da mayor si ha cumplido
    if (hoy.getMonth() + 1 - mes < 0) //+ 1 porque los meses empiezan en 0
       return edad
    if (hoy.getMonth() + 1 - mes > 0)
       return edad+1

    //entonces es que eran iguales. miro los dias
    //si resto los dias y me da menor que 0 entonces no ha cumplido años. Si da mayor o igual si ha cumplido
    if (hoy.getUTCDate() - dia >= 0)
       return edad + 1

    return edad
} 


function VerCamposAdicionales(){
AbrirVentana('otrosdatos_integrantes.php?txtficha='+document.frmAgregarIntegrantes.txtficha.value,600,620,0);
}
function VerFamiliares(){
AbrirVentana('familiares.php?txtficha='+document.frmAgregarIntegrantes.txtficha.value,600,720,0);
}
/*
function Actualizar_Foto() { 
		var archivo=document.getElementById("mifichero").value
		var long=archivo.length
		var i=0,cont
		cont=long
		
		while(archivo[cont-1]!='/'){
		i++
		cont--
		}
		//alert("antes"+archivo.substr(cont,i)+"despues")
		
		var cadena_final="fotos/"+archivo.substr(cont,i)
	
		document.getElementById("imgFoto").src=archivo
		document.getElementById("imgFoto").lowsrc=archivo//cadena_final//document.getElementById("mifichero").value;
		
		document.getElementById("txtrutafoto").value=archivo//cadena_final//document.getElementById("mifichero").value;
	
} */
function Enviar(){					
	if (document.frmAgregarIntegrantes.registro_id.value==0){ 
		document.frmAgregarIntegrantes.op_tp.value=1}
	else{ 	
		document.frmAgregarIntegrantes.op_tp.value=2}		
	
	if (document.frmAgregarIntegrantes.txtnombres.value==0){
		document.frmAgregarIntegrantes.op_tp.value=-1
		alert("Debe ingresar un nombre valido. Verifique...");}	
	else if (document.frmAgregarIntegrantes.txtapellidos.value==0){
		document.frmAgregarIntegrantes.op_tp.value=-1
		alert("Debe ingresar un epellido valida. Verifique...");}				
	else if (document.frmAgregarIntegrantes.txtficha.value==0){
		document.frmAgregarIntegrantes.op_tp.value=-1
		alert("Debe ingresar una ficha valida. Verifique...");}				

}
function mostrar_div_periodos(valor){
	
	
	var div_fin=document.getElementById("div_fin_periodo")
	var div_inicio=document.getElementById("div_inicio_periodo")
	if(valor!="Fijo"){
		div_fin.style.visibility="visible"
		div_inicio.style.visibility="visible"
	}else{
		div_fin.style.visibility="hidden"
		div_inicio.style.visibility="hidden"
	}
}
function Verificar(estado){
	
	var valor=document.frmAgregarIntegrantes.cedula.value
	location.href="ed_maestro_integrantes.php?cedula="+valor+"&estado="+estado
}


</script>



<?php 	
$registro_id=$_POST[registro_id];
$op_tp=$_POST[op_tp];
$fecha_actual=date("Y-m-d");
$validacion=0;

$query="select * from nomempresa";
$result=sql_ejecutar($query);	
$row_emp = fetch_array ($result);	
	
	//if ($registro_id==0){ // Si el registro_id es 0 se va a agregar un registro nuevo

echo $HTTP_POST_FILES['mifichero']['name'];
if(isset($_POST['guardar'])){
if($_POST['txtficha']!="" && $_POST['txtcedula']!="" && $_POST['txtnombres']!="" && $_POST['txtapellidos']!="")
{
	$filesize = $_FILES['mifichero']['size'];
	$filetype = $_FILES['mifichero']['type'];
	$archivo=$HTTP_POST_FILES['mifichero']['name'];
	if($archivo!="")
	{
		$nombre_archivo1 = $HTTP_POST_FILES['mifichero']['name'];
		$tipo_archivo = $HTTP_POST_FILES['mifichero']['type'];
		$tamano_archivo = $HTTP_POST_FILES['mifichero']['size'];
		if(copy($HTTP_POST_FILES['mifichero']['tmp_name'],"fotos/".$nombre_archivo1)){//move_uploaded_file($HTTP_POST_FILES['mifichero']['tmp_name'], nomina/paginas/fotos."/".$nombre_archivo1)){
       		echo "<div align='center' style=\"background-color : #84225b; color : #fdfdfd; font-family : 'Arial Black'; font-size : 15px;\">EL archivo fue cargado exitosamente</div>";
				chmod("fotos/".$nombre_archivo1, 0777);			
    	}
	else
	{
       		echo "<div align='center' style=\"background-color : #84225b; color : #fdfdfd; font-family : 'Arial Black'; font-size : 15px;\">Ocurri&oacute; un problema cargando el archivo</div>";
					
    	}
}

			
if ($_POST[cboNivel1]=='')
{
	$cod_nive1=0;
}
else
{
	$cod_nive1=$_POST[cboNivel1];
}
if ($_POST[cboNivel2]=='') {$cod_nive2=0;} else {$cod_nive2=$_POST[cboNivel2];}
if ($_POST[cboNivel3]=='') {$cod_nive3=0;} else {$cod_nive3=$_POST[cboNivel3];}
if ($_POST[cboNivel4]=='') {$cod_nive4=0;} else {$cod_nive4=$_POST[cboNivel4];}
if ($_POST[cboNivel5]=='') {$cod_nive5=0;} else {$cod_nive5=$_POST[cboNivel5];}
if ($_POST[cboNivel6]=='') {$cod_nive6=0;} else {$cod_nive6=$_POST[cboNivel6];}
if ($_POST[cboNivel7]=='') {$cod_nive7=0;} else {$cod_nive7=$_POST[cboNivel7];}
		
$s_monto=$_POST['txtmonto'];
		
$temp1=$_POST['txtFechaNac'];
$temp2=$_POST['txtFechaIngreso'];
$temp3=$_POST['txtFechaInclusion'];
$temp4=$_POST['dfecing'];
$temp5=$_POST['sfecing'];
if($temp1[4]!='-' && $temp1[7]!='-')
{
	$fecha=fecha_sql($_POST['txtFechaNac']);
}
else
{
	$fecha=$_POST['txtFechaNac'];
}
if($temp2[4]!='-' && $temp2[7]!='-')
{
	$fecha1=fecha_sql($_POST['txtFechaIngreso']);
   
}
else
{
	$fecha1=$_POST['txtFechaIngreso'];
}

if($temp3[4]!='-' && $temp3[7]!='-')
{
	$fecha2=fecha_sql($_POST['txtFechaInclusion']);
}
else
{
	$fecha2=$_POST['txtFechaInclusion'];
}
if($temp4[4]!='-' && $temp4[7]!='-')
{
	$fecha3=fecha_sql($_POST['dfecing']);
}
else
{
	$fecha3=$_POST['dfecing'];
}
if($temp5[4]!='-' && $temp5[7]!='-')
{
	$fecha4=fecha_sql($_POST['sfecing']);
}
else
{
	$fecha4=$_POST['sfecing'];
}	
	
//echo $fecha." ".$fecha1." ".$fecha2;
if($nombre_archivo1==""){
$nombre_archivo1=$_POST['txtrutafoto'];
}else{
$nombre_archivo1="fotos/".$nombre_archivo1;
}	
		$anexo='';
		if ($_POST['cbosituacion']=='Jubilado'){
			$anexo=", fechajubipensi='".fecha_sql($_POST['txtJubilado'])."', fechavac='0000-00-00',fechareivac='0000-00-00'";
		}
		if ($_POST['cbosituacion']=='Pensionado'){
			$anexo=", fechajubipensi='".fecha_sql($_POST['txtPensionado'])."', fechavac='0000-00-00',fechareivac='0000-00-00'";
		}
		if ($_POST['cbosituacion']=='Vacaciones'){
			
			$anexo=", fechavac='".fecha_sql($_POST['txtVacaini'])."', fechareivac='".fecha_sql($_POST['txtVacafin'])."'";
		}
		$query="UPDATE nompersonal SET foto='".$nombre_archivo1."',
nacionalidad='".$_POST['optNacionalidad']."',
cedula='".$_POST['txtcedula']."',
nombres='".$_POST['txtnombres']."',
apellidos='".$_POST['txtapellidos']."',
apenom='".$_POST['txtapellidos'].", ".$_POST['txtnombres']."',
sexo='".$_POST['optSexo']."',
estado_civil='".$_POST['cboEstadocivil']."',
fecnac='".$fecha."',
lugarnac='".$_POST['txtlugarNac']."',
codpro='".$_POST['cboProfesion']."',
direccion='".$_POST['txtdireccion']."',
telefonos='".$_POST['txttelefonos']."',
email='".$_POST['txtemail']."',
estado='".$_POST['cbosituacion']."',
fecing='".$fecha1."',
fecharetiro='".$fecha2."',
ficha='".$_POST['txtficha2']."',
tipopres='".$_POST['optPresentacion']."',
forcob='".$_POST['cboTipocobro']."',
codbancob='".$_POST['cboBancos']."',
cuentacob='".$_POST['txtcuenta']."',
codbanlph='".$_POST['cbobancolph']."',
cuentalph='".$_POST['txtcuentaaux']."',
tipemp='".$_POST['optContrato']."',
sueldopro='".$s_monto."',
suesal='".$s_monto."',
tipnom='".$_POST['cboTipoNomina']."',
codcat='".$_POST['cboCategorias']."',
codcargo='".$_POST['cboCargos']."',
codnivel1='".$cod_nive1."',
codnivel2='".$cod_nive2."',
codnivel3='".$cod_nive3."',
codnivel4='".$cod_nive4."',
codnivel5='".$cod_nive5."',
codnivel6='".$cod_nive6."',
codnivel7='".$cod_nive7."',
porjubipensi='".$_POST['txtpor']."',
inicio_periodo='".fecha_sql($_POST['fecha_inicio_contrato'])."',
fin_periodo='".fecha_sql($_POST['fecha_fin_contrato'])."'".$anexo.", dfecing='$fecha3', sfecing='$fecha4', antiguedadap='".$_POST['antiguedadap']."'
WHERE cedula='".$_POST['txtcedula']."' and ficha='".$_POST['txtficha']."'";

$result=sql_ejecutar($query);	
activar_pagina("maestro_personal.php");
}else{

if($_POST['txtficha']==""){
	mensaje("Debe introducir una ficha Válida");

}elseif($_POST['txtcedula']==""){
	mensaje("Debe introducir un numero de cédula");
}elseif($_POST['txtnombres']==""){
	mensaje("Debe introducir un nombre");
}elseif($_POST['txtapellidos']==""){
	mensaje("Debe introducir un apellido");
}



}
}

$cedula=$_GET['cedula'];
$consulta="select * from nompersonal where cedula='".$cedula."'";
$result=sql_ejecutar($consulta);	

$fila=mysql_fetch_array($result);

?>

<form action="ed_maestro_integrantes.php" method="post" name="frmAgregarIntegrantes" id="frmAgregarIntegrantes" enctype="multipart/form-data">
<input name="op_tp" type="Hidden" id="op_tp" value="-1">
<input name="registro_id" type="Hidden" id="registro_id" value="<?php echo $_POST[registro_id]; ?>">

<table align="left" width="700" border="0">
  <tbody>
<tr><TD colspan="3" class="tb-tit" align="right"><?if ($bandera==1) echo btn("back","maestro_personal.php?bandera=1"); else echo btn("back","maestro_personal.php");?></TD></tr>
<tr><TD colspan="3"><br></TD></tr>
    <tr>
      <td><font size="2" face="Arial, Helvetica, sans-serif"><?echo $termino?>: </font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif">
             <select name="cboTipoNomina" id="select2" style="width:300px">
              <?php
	 	$query="select codtip,descrip from nomtipos_nomina";
		$result=sql_ejecutar($query);
		$tiponomina=$fila['tipnom'];
	 	  //ciclo para mostrar los datos
  		while ($row = fetch_array($result))
  		{ 		
		// option de modificar, se selecciona la situacion del registro a modificar				
  		if ($row[codtip]==$tiponomina){ 
		?>
              <option value="<?php echo $row[codtip];?>" selected="true"> <?php echo $row[descrip];?> </option>
              <?php 
		}
		else // option de agregar
		{ 
		   ?>
              <option value="<?php echo $row[codtip];?>"><?php echo $row[descrip];?></option>
              <?php 
		} 
		}//fin del ciclo while
		?>
            </select>
          </font></td>
      <td rowspan="7"><img src="<?echo $fila['foto']?>" lowsrc="<?echo $fila['foto']?>" border="1" name="imgFoto" id="imgFoto"
	   width="177" height="179" align="middle" class="" style="top:0px"></td>
    </tr>
    <tr>
      <td><font size="2" face="Arial, Helvetica, sans-serif">No de Ficha:</font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif">
<input name="txtficha2" type="text" id="txtficha2" size="10" value="<?php echo $fila['ficha'] ?>" maxlength="20">
<input name="txtficha" type="hidden" id="txtficha" value="<?php echo $fila['ficha'] ?>">
</td>
    </tr>
    <tr>
      <td>Archivo de Foto:</td>
      <td><input type="file" name="mifichero" id="mifichero" onchange="Actualizar_Foto();" style="width:320px" value="" ><input name="txtrutafoto" type="hidden" id="txtrutafoto" value="<?echo $fila['foto']?>" maxlength="30"></td>
    </tr>
<tr>
      <td></td>
      <td></td>
    </tr>
<tr>
      <td></td>
      <td></td>
    </tr>
<tr>
      <td></td>
      <td></td>
    </tr>
<tr>
      <td></td>
      <td></td>
    </tr>
  </tbody>
</table>
<br>
<table align="left" width="700" border="0" class="">

        <tr >
          <td width="200"><font size="2" face="Arial, Helvetica, sans-serif">Apellidos:</font></td>
          <td width="500" ><font size="2" face="Arial, Helvetica, sans-serif">
		<input name="txtapellidos" type="text" id="txtapellidos" style="width:200px" value="<?php echo $fila['apellidos']; ?>" maxlength="30">
          </font></td>
        </tr>
        <tr bgcolor="">
          <td ><font size="2" face="Arial, Helvetica, sans-serif">Nombres</font>:</td>
          <td ><font size="2" face="Arial, Helvetica, sans-serif"><input name="txtnombres" type="text" id="txtnombres" style="width:200px" value="<?php echo $fila['nombres']; ?>" maxlength="30">
          </font></td>
        </tr>
<tr >
          <td ><font size="2" face="Arial, Helvetica, sans-serif">C&eacute;dula:</font></td>
          <td ><font size="2" face="Arial, Helvetica, sans-serif">
            <input name="txtcedula" type="text" id="txtcedula" style="width:100px" value="<?=$fila['cedula']?>" maxlength="10">
          </font></td>
        </tr>
<tr>
          <td><font size="2" face="Arial, Helvetica, sans-serif">Nacionalidad:</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">
            <input name="optNacionalidad" type="radio" value="0"
	    <?php if ($fila[nacionalidad]==0){?> checked= "true" <?php }?>>
            <?if($termino!="Planilla"){?>Venezolano<?}else{?>Paname&#241;o<?}?>
            <input name="optNacionalidad" type="radio" value="1"
		<?php if ($fila[nacionalidad]==1){?> checked= "true" <?php }?>>
            Extranjero</font> <font size="2" face="Arial, Helvetica, sans-serif">

            </font></td>
          </tr>
        <tr>
          <td ><font size="2" face="Arial, Helvetica, sans-serif">Sexo: </font></td>
          <td ><span ><font size="2" face="Arial, Helvetica, sans-serif">
            <input name="optSexo" type="radio" value="Masculino" <?if($fila['sexo']=="Masculino"){?> checked="true"<?}?>>Masculino<input name="optSexo" type="radio" value="Femenino" <?if($fila['sexo']=="Femenino"){?> checked="true"<?}?>>
            Femenino</font></span></td>
        </tr>

	<tr bgcolor="">
          <td ><font size="2" face="Arial, Helvetica, sans-serif">Estado civil</font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font><font color="#000000" size="2" face="Arial, Helvetica, sans-serif">:</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">
            <select name="cboEstadocivil" style="width:200px">
		<option value="<? echo $fila['estado_civil']?>"><? echo $fila['estado_civil']?></option>
		<option value="Soltero/a">Soltero/a</option>
		<option value="Casado/a">Casado/a</option>
		<option value="Viudo/a">Viudo/a</option>
		<option value="Divorciado/a">Divorciado/a</option>
		<option value="Otro">Otro</option>              
            </select>
          </font></td>
        </tr>

        <tr>
          <td><font size="2" face="Arial, Helvetica, sans-serif">Fecha de Nacimiento:</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">
            <input name="txtFechaNac" type="text" id="txtFechaNac" style="width:100px" value="<?php echo fecha($fila['fecnac']) ?>" maxlength="60" onblur="javascript:actualizar('txtFechaNac','fila_edad');">
          <input name="image2" type="image" id="d_fechanac" src="../lib/jscalendar/cal.gif" />
  <script type="text/javascript">Calendar.setup({inputField:"txtFechaNac",ifFormat:"%d/%m/%Y",button:"d_fechanac"});</script>
          </font></td>
        </tr>
        <tr >
          <td  >Edad:</td>
          <td id="fila_edad" ><font size="2" face="Arial, Helvetica, sans-serif"><strong>
            A&ntilde;os de Edad
          </strong></font></td>
        </tr>
        <tr >
          <td ><font size="2" face="Arial, Helvetica, sans-serif">Lugar de Nacimiento: </font></td>
          <td ><font size="2" face="Arial, Helvetica, sans-serif">
				
            <input name="txtlugarNac" type="text" id="txtlugarNac" style="width:200px" value="<?php echo $fila['lugarnac']; ?>" maxlength="20">
          </font></td>
        </tr>
        <tr bgcolor="">
          <td ><font size="2" face="Arial, Helvetica, sans-serif">Profesi&oacute;n</font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font><font color="#000000" size="2" face="Arial, Helvetica, sans-serif">:</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">
     <select name="cboProfesion" style="width:200px">

<option>Seleccione una Profesi&oacute;n</option>
              <?php
		  
	 	$query="select codorg,descrip from nomprofesiones where codorg";
		$result=sql_ejecutar($query);
		
	 	  //ciclo para mostrar los datos
  		while ($row = mysql_fetch_array($result))
  		{
		   ?>
              <option value="<?php echo $row['codorg'];?>" <?if($fila['codpro']==$row['codorg']){?> selected="true"<?}?>><?php echo $row['descrip'];?></option>
              <?php  
		}//fin del ciclo while
		?>
            </select>
          </font></td>
        </tr>
        <tr bgcolor="">
          <td ><font size="2" face="Arial, Helvetica, sans-serif">Direcci&oacute;n</font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font><font color="#000000" size="2" face="Arial, Helvetica, sans-serif">:</font></td>
          <td ><font size="2" face="Arial, Helvetica, sans-serif">
            <textarea cols="50" rows="5" name="txtdireccion" id="txtdireccion"><?php echo $fila['direccion']; ?></textarea>
          </font></td>

        </tr>
        <tr >
          <td ><font size="2" face="Arial, Helvetica, sans-serif">Tel&eacute;fono:</font></td>
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">
            <input name="txttelefonos" type="text" id="txttelefonos" style="width:200px" value="<?php echo $fila['telefonos']; ?>" maxlength="20">
          </font></td>

        </tr>
        <tr bgcolor="">
          <td ><font size="2" face="Arial, Helvetica, sans-serif">E-Mail:</font></td>
          <td ><font size="2" face="Arial, Helvetica, sans-serif">
            <input name="txtemail" type="text" id="txtemail" style="width:200px"  value="<?php echo $fila['email']; ?>" maxlength="40" >
          </font></td>
        </tr>
        <tr bgcolor="">
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">Situaci&oacute;n:</font></td>
          <td ><font size="2" face="Arial, Helvetica, sans-serif">
	   
		
		<input name="cedula"  type="hidden" id="cedula" style="width:100px"  value="<?php echo $cedula;?>" maxlength="60">
		
           <select name="cbosituacion" id="cbosituacion" style="width:200px">
              <?php
	 	$query="select situacion from nomsituaciones";
		$result=sql_ejecutar($query);
	 	  //ciclo para mostrar los datos
		if (isset($_GET['estado'])){
			$va=$_GET['estado'];
			while ($row = fetch_array($result))
			{ 		
			// option de modificar, se selecciona la situacion del registro a modificar	
			
					
			if ($row['situacion']==$va){ ?>
		<option selected="true" value="<?php echo $row['situacion'];?>" onclick="javascript:Verificar(this.value);" > <?php echo $row['situacion'];?> </option>
			<?php 
			}
			else // option de agregar
			{ 
			?><option value="<?php echo $row[situacion];?>" onclick="javascript:Verificar(this.value);" ><?php echo $row[situacion];?></option><?php 
			} 
			}//fin del ciclo while
		}else{
			while ($row = fetch_array($result))
			{ 		
			// option de modificar, se selecciona la situacion del registro a modificar	
			
					
			if ($row['situacion']==$fila['estado']){ ?>
		<option selected="true" value="<?php echo $row['situacion'];?>" onclick="javascript:Verificar(this.value);" > <?php echo $row['situacion'];?> </option>
			<?php 
			}
			else // option de agregar
			{ 
			?><option value="<?php echo $row[situacion];?>" onclick="javascript:Verificar(this.value);" ><?php echo $row[situacion];?></option><?php 
			} 
			}//fin del ciclo while
		}
		?>
            </select>
		<?php 
			 if (!isset($_GET['estado'])){ 
			if ($fila['estado']=='Jubilado'){ ?>
				<!--Jubilado -->
				<font size="2" face="Arial, Helvetica, sans-serif"> Fecha Jubilado(a): </font>
	      			<input  name="txtJubilado" type="text" id="txtJubilado" style="width:100px" value="<?php echo fecha($fila['fechajubipensi']) ?>" maxlength="60"    >
				<!-- onblur="javascript:actualizar('txtJuvilacion','fila_edad');"-->
              			<input name="d_fechajubilado" type="image" id="d_fechajubilado" src="../lib/jscalendar/cal.gif" />
  				<script type="text/javascript">Calendar.setup({inputField:"txtJubilado",ifFormat:"%d/%m/%Y",button:"d_fechajubilado"});</script>	
			<?php } 
			if ($fila['estado']=='Pensionado'){ ?>
		
				<!-- Pensionado-->
				<font size="2" face="Arial, Helvetica, sans-serif"> Fecha Pensionado(a): </font>
				<input name="txtPensionado" type="text" id="txtPensionado" style="width:100px" value="<?php echo fecha($fila['fechajubipensi']) ?>" maxlength="60" >
					<!-- onblur="javascript:actualizar('txtJuvilacion','fila_edad');"-->
				<input name="d_fechapensionado" type="image" id="d_fechapensionado" src="../lib/jscalendar/cal.gif" />
				<script type="text/javascript">Calendar.setup({inputField:"txtPensionado",ifFormat:"%d/%m/%Y",button:"d_fechapensionado"});</script>
			<?php }
			if ($fila['estado']=='Vacaciones'){ ?>
				<!-- Vacaciones -->
				<font size="2" face="Arial, Helvetica, sans-serif">Inicio: </font>
				<input name="txtVacaini" type="text" id="txtVacaini" style="width:100px" value="<?php echo fecha($fila['fechavac']) ?>" maxlength="60" >
				<!-- onblur="javascript:actualizar('txtJuvilacion','fila_edad');"-->
				<input name="d_fechavacaini" type="image" id="d_fechavacaini" src="../lib/jscalendar/cal.gif" />
  				<script type="text/javascript">Calendar.setup({inputField:"txtVacaini",ifFormat:"%d/%m/%Y",button:"d_fechavacaini"});</script>
				<font size="2" face="Arial, Helvetica, sans-serif">Fin: </font>
				<input name="txtVacafin" type="text" id="txtVacafin" style="width:100px" value="<?php echo fecha($fila['fechareivac']) ?>" maxlength="60" >
				<!-- onblur="javascript:actualizar('txtJuvilacion','fila_edad');"-->
              			<input name="d_fechavacafin" type="image" id="d_fechavacafin" src="../lib/jscalendar/cal.gif" />
 				 <script type="text/javascript">Calendar.setup({inputField:"txtVacafin",ifFormat:"%d/%m/%Y",button:"d_fechavacafin"});</script>
		         <?php } }
				else{
					$situa=$_GET['estado'];
					if ($situa=='Jubilado'){ ?>
						<!--Jubilado -->
						<font size="2" face="Arial, Helvetica, sans-serif"> Fecha Jubilado(a): </font>
						<input  name="txtJubilado" type="text" id="txtJubilado" style="width:100px" value="<?php echo fecha($fila['fechajubipensi']) ?>" maxlength="60"    >
						<!-- onblur="javascript:actualizar('txtJuvilacion','fila_edad');"-->
						<input name="d_fechajubilado" type="image" id="d_fechajubilado" src="../lib/jscalendar/cal.gif" />
						<script type="text/javascript">Calendar.setup({inputField:"txtJubilado",ifFormat:"%d/%m/%Y",button:"d_fechajubilado"});</script>	
					<?php } 
					if ($situa=='Pensionado'){ ?>
				
						<!-- Pensionado-->
						<font size="2" face="Arial, Helvetica, sans-serif"> Fecha Pensionado(a): </font>
						<input name="txtPensionado" type="text" id="txtPensionado" style="width:100px" value="<?php echo fecha($fila['fechajubipensi']) ?>" maxlength="60" >
							<!-- onblur="javascript:actualizar('txtJuvilacion','fila_edad');"-->
						<input name="d_fechapensionado" type="image" id="d_fechapensionado" src="../lib/jscalendar/cal.gif" />
						<script type="text/javascript">Calendar.setup({inputField:"txtPensionado",ifFormat:"%d/%m/%Y",button:"d_fechapensionado"});</script>
					<?php }
					if ($situa=='Vacaciones'){ ?>
						<!-- Vacaciones -->
						<font size="2" face="Arial, Helvetica, sans-serif">Inicio: </font>
						<input name="txtVacaini" type="text" id="txtVacaini" style="width:100px" value="<?php echo fecha($fila['fechavac']) ?>" maxlength="60" >
						<!-- onblur="javascript:actualizar('txtJuvilacion','fila_edad');"-->
						<input name="d_fechavacaini" type="image" id="d_fechavacaini" src="../lib/jscalendar/cal.gif" />
						<script type="text/javascript">Calendar.setup({inputField:"txtVacaini",ifFormat:"%d/%m/%Y",button:"d_fechavacaini"});</script>
						<font size="2" face="Arial, Helvetica, sans-serif">Fin: </font>
						<input name="txtVacafin" type="text" id="txtVacafin" style="width:100px" value="<?php echo fecha($fila['fechareivac']) ?>" maxlength="60" >
						<!-- onblur="javascript:actualizar('txtJuvilacion','fila_edad');"-->
						<input name="d_fechavacafin" type="image" id="d_fechavacafin" src="../lib/jscalendar/cal.gif" />
						<script type="text/javascript">Calendar.setup({inputField:"txtVacafin",ifFormat:"%d/%m/%Y",button:"d_fechavacafin"});</script>
				<?php } } ?>
          </font></td>
        </tr>
        <tr bgcolor="">
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">Fecha de Ingreso:</font></td>
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">
            <input name="txtFechaIngreso" type="text" id="txtFechaIngreso" style="width:100px" value="<?echo fecha($fila['fecing'])?>" onblur="javascript:calcular_antiguedad(this.value);" maxlength="60">

          <input name="image3" type="image" id="d_fechaingreso" src="../lib/jscalendar/cal.gif" />
		   	<script type="text/javascript"> 
   			Calendar.setup({inputField:"txtFechaIngreso",ifFormat:"%d/%m/%Y",button:"d_fechaingreso"}); 
			</script>
          </font></td>
        </tr>
        <tr bgcolor="">
          <td><font size="2" face="Arial, Helvetica, sans-serif">Antiguedad</font></td>
          <td  id="antiguedad"><font size="2" face="Arial, Helvetica, sans-serif"><strong> A&#241;os
          </strong></font></td>
        </tr>
	<tr bgcolor="">
          <td><font size="2" face="Arial, Helvetica, sans-serif">Antiguedad administraci&oacute;n P&uacute;blica:</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif"><input type="text" maxlength="2" name="antiguedadap" id="antiguedadap" value="<?php echo $fila['antiguedadap'] ?>"></font></td>
        </tr>
        <tr bgcolor="">
          <td ><font size="2" face="Arial, Helvetica, sans-serif">Fecha de Inclusi&oacute;n en N&oacute;mina:</font></td>
          <td ><font size="2" face="Arial, Helvetica, sans-serif">
            <input name="txtFechaInclusion" type="text" id="txtFechaInclusion" style="width:100px" value="<?php echo fecha($fila['fecharetiro']); ?>" maxlength="60">
            <input name="image3" type="image" id="d_fechainclusion" src="../lib/jscalendar/cal.gif" />
	<script type="text/javascript"> 
   	Calendar.setup({inputField:"txtFechaInclusion",ifFormat:"%d/%m/%Y",button:"d_fechainclusion"}); 
	</script>

          </font></td>
        </tr>

	<tr bgcolor="">
          <td ><font size="2" face="Arial, Helvetica, sans-serif">Fecha de Ingreso en caja de ahorro:</font></td>
          <td ><font size="2" face="Arial, Helvetica, sans-serif">
            <input name="dfecing" type="text" id="dfecing" style="width:100px" value="<?php echo fecha($fila['dfecing']); ?>" maxlength="60">
            <input name="image3" type="image" id="d_fec" src="../lib/jscalendar/cal.gif" />
	<script type="text/javascript"> 
   	Calendar.setup({inputField:"dfecing",ifFormat:"%d/%m/%Y",button:"d_fec"}); 
	</script>

          </font></td>
        </tr>

	<tr bgcolor="">
          <td ><font size="2" face="Arial, Helvetica, sans-serif">Fecha de Ingreso en LPH:</font></td>
          <td ><font size="2" face="Arial, Helvetica, sans-serif">
            <input name="sfecing" type="text" id="sfecing" style="width:100px" value="<?php echo fecha($fila['sfecing']); ?>" maxlength="60">
            <input name="image3" type="image" id="s_fec" src="../lib/jscalendar/cal.gif" />
	<script type="text/javascript"> 
   	Calendar.setup({inputField:"sfecing",ifFormat:"%d/%m/%Y",button:"s_fec"}); 
	</script>

          </font></td>
        </tr> /
        
        <tr bgcolor="">
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">Prestaciones</font><font color="#000000" size="2" face="Arial, Helvetica, sans-serif">:</font></td>
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">
              <input name="optPresentacion" type="radio" value="1" <?if($fila['tipopres']=='1'){?> checked="true"<?}?>>
            Fideicomiso
            <input name="optPresentacion" type="radio" value="2" <?if($fila['tipopres']=='2'){?> checked="true"<?}?>>
            Fondo
            <input name="optPresentacion" type="radio" value="3" <?if($fila['tipopres']=='3'){?> checked="true"<?}?>>
            Contabilidad &nbsp;</font></td>
          
          </tr>
        <tr bgcolor="">
          <td ><font size="2" face="Arial, Helvetica, sans-serif">Tipo de Cobro: </font></td>
   
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">
            <select name="cboTipocobro" id="select" style="width:200px">
              <option value="Efectivo" <?if($fila['forcob']=='Efectivo'){?> selected="true"<?}?>>
	  Efectivo</option>
              <option value="Cheque" <?if($fila['forcob']=='Cheque'){?> selected="true"<?}?>>
	  Cheque</option>
              <option value="Deposito Ahorro" <?if($fila['forcob']=='Deposito Ahorro'){?> selected="true"<?}?>>
	  Deposito Ahorro</option>
              <option value="Deposito Cta.Corriente" <?if($fila['forcob']=='Deposito Cta.Corriente'){?> selected="true"<?}?>>
	  Deposito Cta.Corriente</option>
              <option value="Deposito F.A.L." <?if($fila['forcob']=='Deposito F.A.L.'){?> selected="true"<?}?>>
	  Deposito F.A.L.</option>
            </select>
          </font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          
          </tr>
        <tr bgcolor="">
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">Banco:</font></td>
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">
            <select name="cboBancos" id="select3" style="width:200px">

              <?php
	 	$query="select cod_ban,des_ban from nombancos";
		$result=sql_ejecutar($query);
	 	  //ciclo para mostrar los datos
		  
  		while ($row = fetch_array($result))
  		{ 		
		// option de modificar, se selecciona la situacion del registro a modificar		
  		if ($row[cod_ban]==$fila['codbancob']){ ?>
              <option value="<?php echo $row[cod_ban];?>" selected="true"> <?php echo $row[des_ban];?> </option>
              <?php 
		}
		else // option de agregar
		{ 
		   ?>
              <option value="<?php echo $row[cod_ban];?>"><?php echo $row[des_ban];?></option>
              <?php 
		} 
		}//fin del ciclo while
		?>
            </select>
          </font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>

          </tr>
        <tr bgcolor="">
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">Cuenta:</font></td>
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">
            <input name="txtcuenta" type="text" id="txtcuenta" style="width:200px" value="<?echo $fila['cuentacob']?>" maxlength="20">
          </font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          
          </tr>

          
        <tr bgcolor="">
          <td  ><font size="2" face="Arial, Helvetica, sans-serif"><?if($termino!="Planilla"){?>Banco L.P.H.:<?}?> </font></td>
		
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">
            <? 
		if($termino!="Planilla"){?><select name="cbobancolph" id="cbobancolph" style="width:200px">

              <?php
		
	 	$query="select cod_ban,des_ban from nombancos";
		$result=sql_ejecutar($query);
	 	  //ciclo para mostrar los datos
		  
  		while ($row = fetch_array($result))
  		{ 		
		// option de modificar, se selecciona la situacion del registro a modificar		
  		if ($row[cod_ban]==$fila['codbanlph']){ ?>
              <option  selected="selected" value="<?php echo $row[cod_ban];?>"> <?php echo $row[des_ban];?> </option>
              <?php 
		}
		else // option de agregar
		{ 
		   ?>
              <option value="<?php echo $row[cod_ban];?>"><?php echo $row[des_ban];?></option>
              <?php 
		} 
		}//fin del ciclo while
		?>
            </select><?}?>
          </font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          
          </tr>
<?//todo mas fino hasta aqui?>
        <tr bgcolor="">
          <td  ><font size="2" face="Arial, Helvetica, sans-serif"><?if($termino!="Planilla"){?>Cuenta L.P.H.:<?}?></font></td>
          <td ><font size="2" face="Arial, Helvetica, sans-serif">
            <?if($termino!="Planilla"){?><input name="txtcuentaaux" type="text" id="txtcuentaaux" style="width:200px" value="<?echo $fila['cuentalph']?>" maxlength="20"><?}?>
          </font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
          </tr>
        <tr bgcolor="">
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">Tipo de Contrato:</font></td>
          <td ><table>
<TR>
<TD colspan="2">

<font size="2" face="Arial, Helvetica, sans-serif">
            <input name="optContrato" type="radio" value="Fijo" <?if($fila['tipemp']=="Fijo"){?> checked="true"<?}?> onclick="javascript:mostrar_div_periodos(this.value)">
            Fijo
            <input name="optContrato" type="radio" value="Temporal" <?if($fila['tipemp']=="Temporal"){?> checked="true"<?}?> onclick="javascript:mostrar_div_periodos(this.value)">
            Temporal
            <input name="optContrato" type="radio" value="Contratado" <?if($fila['tipemp']=="Contratado"){?> checked="true"<?}?> onclick="javascript:mostrar_div_periodos(this.value)">
            Contratado
            <input name="optContrato" type="radio" value="Pasante" <?if($fila['tipemp']=="Pasante"){?> checked="true"<?}?> onclick="javascript:mostrar_div_periodos(this.value)">
            Pasante</font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></TD>
</TR>
<TR>
<TD><div id="div_inicio_periodo"  style="visibility : hidden;"><strong>Inicio Periodo</strong>&nbsp;
<input name="fecha_inicio_contrato" type="text" id="fecha_inicio_contrato" style="width:100px" value="<?php echo fecha($fila['inicio_periodo']); ?>" maxlength="60">
            <input name="image3" type="image" id="d_fecha_inicio_contrato" src="../lib/jscalendar/cal.gif" />
	<script type="text/javascript"> 
   	Calendar.setup({inputField:"fecha_inicio_contrato",ifFormat:"%d/%m/%Y",button:"d_fecha_inicio_contrato"}); 
	</script>
</div></TD><TD><div id="div_fin_periodo"  style="visibility : hidden;"><strong>&nbsp;&nbsp;Fin Periodo</strong>&nbsp;<input name="fecha_fin_contrato" type="text" id="fecha_fin_contrato" style="width:100px" value="<?php echo fecha($fila['fin_periodo']); ?>" maxlength="60">
            <input name="image3" type="image" id="d_fecha_fin_contrato" src="../lib/jscalendar/cal.gif" />
	<script type="text/javascript"> 
   	Calendar.setup({inputField:"fecha_fin_contrato",ifFormat:"%d/%m/%Y",button:"d_fecha_fin_contrato"}); 
	</script></div></TD></TR></table><!--<font size="2" face="Arial, Helvetica, sans-serif">
            <input name="optContrato" type="radio" value="Fijo" <?if($fila['tipemp']=="Fijo"){?> checked="true"<?}?>>
            Fijo
            <input name="optContrato" type="radio" value="Temporal" <?if($fila['tipemp']=="Temporal"){?> checked="true"<?}?>>
            Temporal
            <input name="optContrato" type="radio" value="Contratado" <?if($fila['tipemp']=="Contratado"){?> checked="true"<?}?>>
            Contratado
            <input name="optContrato" type="radio" value="Pasante" <?if($fila['tipemp']=="Pasante"){?> checked="true"<?}?>>
            Pasante</font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font>--></td>
        </tr>
	<? 
	if($bandera!=1)
	{
	?>
        <tr bgcolor="">
          <td ><font size="2" face="Arial, Helvetica, sans-serif">Sueldo:</font></td>
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">
            <input align="left" name="txtmonto" type="text" id="txtmonto" style="width:100px" value="<?echo $fila['suesal'];?>" maxlength="20"></font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="2" face="Arial, Helvetica, sans-serif"></font>
		<!-- % en jubilado y pensionado -->
	   <?php if ($fila['tipnom']==5 || $fila['tipnom']==6 ){ ?>
		<font size="2" face="Arial, Helvetica, sans-serif"> <?php if ($fila['tipnom']==5){ ?>(%) Jubilado: <?php } else { ?> (%) Pensionado: <?php } ?></font>
		<input  name="txtpor" type="text" id="txtpor" style="width:100px" value="<?echo $fila['porjubipensi']?>" maxlength="20"></font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="2" face="Arial, Helvetica, sans-serif"></font>
		
	  <?php } if ($fila['tipnom']==3 || $fila['tipnom']==1 || $fila['tipnom']==2 ){ ?>
		<font size="2" face="Arial, Helvetica, sans-serif"> PRS(%): </font>
		<input  name="txtpor" type="text" id="txtpor" style="width:100px" value="<?echo $fila['porjubipensi']?>" maxlength="20"></font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="2" face="Arial, Helvetica, sans-serif"></font>
	  <?php } ?>
	</td>
	</tr>
	<?}?>
	
        
        <tr bgcolor="">
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">Categoria</font></td>
          <td ><font size="2" face="Arial, Helvetica, sans-serif">
            <select name="cboCategorias" id="cboCategorias" style="width:300px">
              <?php
	 	$query="select * from nomcategorias";
		$result=sql_ejecutar($query);
	 	  //ciclo para mostrar los datos
		  
  		while ($row = fetch_array($result))
  		{ 		
		// option de modificar, se selecciona la situacion del registro a modificar				
  		if ($row[codorg]==$fila['codcat']){ 
		?>
              <option value="<?php echo $row[codorg];?>" selected="true" > <?php echo $row[descrip];?> </option>
              <?php 
		}
		else // option de agregar
		{ 
		   ?>
              <option value="<?php echo $row[codorg];?>"><?php echo $row[descrip];?></option>
              <?php 
		} 
		}//fin del ciclo while
		?>
            </select>
          </font></td>
        </tr>
        <tr bgcolor="">
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">Cargo:</font></td>
          <td><font size="2" face="Arial, Helvetica, sans-serif">
           <select name="cboCargos" id="cboCargos" style="width:300px">

              <?php
	 	$query="select cod_car,des_car from nomcargos order by des_car";
		$result=sql_ejecutar($query);
	 	  //ciclo para mostrar los datos
		  
  		while ($row = fetch_array($result))
  		{ 		
		// option de modificar, se selecciona la situacion del registro a modificar				
  		if ($row['cod_car']==$fila['codcargo']){ 
		?>
              <option value="<?php echo $row[cod_car];?>" selected="true" ><?php echo $row[cod_car];?>   <?php echo $row[des_car];?> </option>
              <?php 
		}
		else // option de agregar
		{ 
		   ?>
              <option value="<?php echo $row[cod_car];?>"><?php echo $row[cod_car];?>   <?php echo $row[des_car];?></option>
              <?php 
		} 
		}//fin del ciclo while
		?>
            </select>
          </font></td>
        </tr>
<?if ($row_emp[nivel1]==1) {?>
<tr bgcolor="">
          <td >
		  <?php echo $row_emp[nomniv1]; ?>:</td>
          <td ><font size="2" face="Arial, Helvetica, sans-serif">
            <select name="cboNivel1" id="select7" style="width:230px">

			<?php
			
			$query="select * from nomnivel1";
			$result=sql_ejecutar($query);
			 //ciclo para mostrar los datos
			 
			while ($row = fetch_array($result)){				
			// option de modificar, se selecciona la situacion del registro a modificar					
			if ($row[codorg]==$fila['codnivel1']){ ?>			
				  <option value="<?php echo $row[codorg];?>" selected="true"> <?php echo $row[codorg]." ".$row[descrip];?> </option><?php 
			}
			else {// option de agregar			 
			   ?><option value="<?php echo $row[codorg];?>"><?php echo $row[codorg]." ".$row[descrip];?></option><?php 
			} 
			}//fin del ciclo while
			?>
            </select>
          </font></td>
		
          </tr>
  <?php 
		  }
		  ?>

<?if ($row_emp[nivel2]==1){?>
        <tr bgcolor="">
     
          <td  >
		  <?php echo $row_emp[nomniv2];?>:</td>
		   
          <td ><font size="2" face="Arial, Helvetica, sans-serif">
            <select name="cboNivel2" id="select8" style="width:230px">

            <?php
			$query="select * from nomnivel2";
			$result=sql_ejecutar($query);
			 //ciclo para mostrar los datos
			while ($row = fetch_array($result)){				
			// option de modificar, se selecciona la situacion del registro a modificar					
			if ($row[codorg]==$fila['codnivel2']){ ?>			
				  <option value="<?php echo $row[codorg];?>" selected="true"> <?php echo $row[codorg]." ".$row[descrip];?> </option><?php 
			}
			else {// option de agregar			 
			   ?><option value="<?php echo $row[codorg];?>"><?php echo $row[codorg]." ".$row[descrip];?></option><?php 
			} 
			}//fin del ciclo while
			?>
            </select>
          </font></td>
		  
          </tr><?php }?>
<?if ($row_emp[nivel3]==1){?>
<tr>

<td  >
		  <?php echo $row_emp[nomniv3];?>:</td>
          <td ><font size="2" face="Arial, Helvetica, sans-serif">
            <select name="cboNivel3" id="select9" style="width:230px">

			<?php
			$query="select * from nomnivel3";
			$result=sql_ejecutar($query);
			 //ciclo para mostrar los datos
			while ($row = fetch_array($result)){				
			// option de modificar, se selecciona la situacion del registro a modificar					
			if ($row[codorg]==$fila['codnivel3']){ ?>			
				  <option value="<?php echo $row[codorg];?>" selected="true"> <?php echo $row[codorg]." ".$row[descrip];?> </option><?php 
			}
			else {// option de agregar			 
			   ?><option value="<?php echo $row[codorg];?>"><?php echo $row[codorg]." ".$row[descrip];?></option><?php 
			} 
			}//fin del ciclo while
			?>
            </select>
          </font></td>
		  
</tr>
<?php }?>
<?if ($row_emp[nivel4]==1){?>
<tr><td  >
		  <?php echo $row_emp[nomniv4];?>:</td>
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">
            <select name="cboNivel4" id="select10" style="width:230px">
				
			<?php
			$query="select * from nomnivel4 order by descrip";
			$result=sql_ejecutar($query);
			 //ciclo para mostrar los datos
			while ($row = fetch_array($result)){				
			// option de modificar, se selecciona la situacion del registro a modificar					
			if ($row[codorg]==$fila['codnivel4']){ ?>			
				  <option value="<?php echo $row[codorg];?>" selected > <?php echo $row[codorg]." ".$row[descrip];?> </option><?php 
			}
			else {// option de agregar			 
			   ?><option value="<?php echo $row[codorg];?>"><?php echo $row[codorg]." ".$row[descrip];?></option><?php 
			} 
			}//fin del ciclo while
			?>
            </select>
          </font></td>
		  </tr><?php }?>
<? if ($row_emp[nivel5]==1){?>
<tr><td >
		  <?php echo $row_emp[nomniv5];?>:</td>
          <td ><font size="2" face="Arial, Helvetica, sans-serif">
            <select name="cboNivel5" id="select11" style="width:230px">
<option>Seleccione un valor</option>
			<?php
			$query="select * from nomnivel5 order by descrip";
			$result=sql_ejecutar($query);
			 //ciclo para mostrar los datos
			while ($row = fetch_array($result)){				
			// option de modificar, se selecciona la situacion del registro a modificar					
			if ($row[codorg]==$fila['codnivel5']){ ?>			
				  <option value="<?php echo $row[codorg];?>" selected > <?php echo $row[codorg]." ".$row[descrip];?> </option><?php 
			}
			else {// option de agregar			 
			   ?><option value="<?php echo $row[codorg];?>"><?php echo $row[codorg]." ".$row[descrip];?></option><?php 
			} 
			}//fin del ciclo while
			?>
            </select>
          </font></td>
		  </tr>
<?php }?>
<? if ($row_emp[nivel6]==1){?>
<tr><td  >
		  <?echo $row_emp[nomniv6];?>:</td>
          <td ><font size="2" face="Arial, Helvetica, sans-serif">
            <select name="cboNivel6" id="select12" style="width:230px">

			<?php
			$query="select * from nomnivel6 order by descrip";
			$result=sql_ejecutar($query);
			 //ciclo para mostrar los datos
			while ($row = fetch_array($result)){				
			// option de modificar, se selecciona la situacion del registro a modificar					
			if ($row[codorg]==$fila['codnivel6']){ ?>			
				  <option value="<?php echo $row[codorg];?>" selected > <?php echo $row[codorg]." ".$row[descrip];?> </option><?php 
			}
			else {// option de agregar			 
			   ?><option value="<?php echo $row[codorg];?>"><?php echo $row[codorg]." ".$row[descrip];?></option><?php 
			} 
			}//fin del ciclo while
			?>
            </select>
          </font></td>
		  </tr>
<?php }?>
<?php if ($row_emp[nivel7]==1){?>
<tr><td >
		  <?echo $row_emp[nomniv7];?>:</td>
          <td ><font size="2" face="Arial, Helvetica, sans-serif">
            <select name="cboNivel7" id="select13" style="width:230px">

			<?php
			$query="select * from nomnivel7 order by descrip";
			$result=sql_ejecutar($query);
			 //ciclo para mostrar los datos
			while ($row = fetch_array($result)){				
			// option de modificar, se selecciona la situacion del registro a modificar					
			if ($row[codorg]==$fila['codnivel7']){ ?>			
				  <option value="<?php echo $row[codorg];?>" selected > <?php echo $row[codorg]." ".$row[descrip];?> </option><?php 
			}
			else {// option de agregar			 
			   ?><option value="<?php echo $row[codorg];?>"><?php echo $row[codorg]." ".$row[descrip];?></option><?php 
			} 
			}//fin del ciclo while
			?>
            </select>
          </font></td>
		  </tr>
<?php }?>
<tr>
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">C&oacute;digo RAC:</font></td>
          <td  ><font size="2" face="Arial, Helvetica, sans-serif">
            <input name="txtcodigorac" type="text" id="txtcodigorac"  style="text-align:right;width:200px" value=""> </td>
        </tr>
<? 
if($bandera!=1)
{
?>
<tr><td colspan="2" height="50" align="center" class="tb-tit" ><INPUT type="submit" name="guardar" value="Guardar"></td></tr>
</tr>
<?}?>
</table>

</form>
<script language="JavaScript" type="text/javascript">

mostrar_div_periodos('<?echo $fila['tipemp']?>')

actualizar('txtFechaNac','fila_edad')

calcular_antiguedad('<?echo fecha($fila['fecing'])?>')
</script>


</body>
</html>
