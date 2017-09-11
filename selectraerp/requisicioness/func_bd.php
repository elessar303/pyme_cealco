<?
require_once($_SERVER['DOCUMENT_ROOT'].'/sisalud/general.config.inc.php');
?>

<script language="JavaScript" type="text/javascript">
function trim(cadena) 
{ 
    for(i=0; i<cadena.length; ) 
    { 
        if(cadena.charAt(i)==" ") 
            cadena=cadena.substring(i+1, cadena.length); 
        else 
            break; 
    } 

    for(i=cadena.length-1; i>=0; i=cadena.length-1) 
    { 
        if(cadena.charAt(i)==" ") 
            cadena=cadena.substring(0,i); 
        else 
            break; 
    }
	return cadena;
} 

</script>
<?php

define("Servidor", "DB_HOST" );
include("funcionesgenerales.php") ;
function fecha($value) { // fecha de YYYY/MM/DD a DD/MM/YYYY
 if ( ! empty($value) ) return substr($value,8,2) ."/". substr($value,5,2) ."/". substr($value,0,4);
}

function mensaje($texto){
echo"<SCRIPT language=\"JavaScript\" type=\"text/javascript\">
	alert(\"".$texto."\")

</SCRIPT>";
}



function AgregarCodigo($tabla,$campo,$condicion="")
{

	if($condicion!=""){
	$query="select MAX($campo)+1 from ".$tabla." ".$condicion;
	
	}else{
		
		$query="select MAX($campo)+1 from $tabla";

	}
		$result=sql_ejecutar($query);	
		$row = mysql_fetch_array ($result);		
		
		if ($row[0]==''){ // si la tabla esta vacia devuelve 1
		$codigo_nuevo= 1;			
		}
		else{
		$codigo_nuevo= $row[0];			
		}
		
		return ($codigo_nuevo);
	
}



function db_error($conn)
{
	return mysql_error($conn);
}



function conectar($host,$nombrebd,$puerto,$usuario,$pwd) 
{ 
	$s = @mysql_connect($host,   $usuario,  $pwd) 
	or die("No se puedo conectar a SQL Server en $host") ;  



	$d = @mysql_select_db( $nombrebd,  $s) or 
	die( "No se pudo conectar a la base de datos $nombrebd") ; 
	
	return $s; 
} 

// funciones del fichero db.hp del login.php del menu de contabilidad
function ewBuildSql($sSelect, $sWhere, $sGroupBy, $sHaving, $sOrderBy, $sFilter, $sSort) {
	$sDbWhere = $sWhere;
	if ($sDbWhere <> "") {
		$sDbWhere = "(" . $sDbWhere . ")";
	}
	if ($sFilter <> "") {
		if ($sDbWhere <> "") $sDbWhere .= " AND ";
		$sDbWhere .= "(" . $sFilter . ")";
	}
	$sDbOrderBy = $sOrderBy;
	if ($sSort <> "") {
		$sDbOrderBy = $sSort;
	}
	$sSql = $sSelect;
	if ($sDbWhere <> "") {
		$sSql .= " WHERE " . $sDbWhere;
	}
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	If ($sDbOrderBy <> "") {
		$sSql .= " ORDER BY " . $sDbOrderBy;
	}
	return $sSql;
}


function AdjustSql($str) {
	$sWrk = trim($str);
	$sWrk = addslashes($sWrk);
	return $sWrk;
}

// Check if user is logged in
function IsLoggedIn()
{
	return (@$_SESSION[ewSessionStatus] == "login");
}
//////////////////////////////


// función para cerrar conexión
function desconectar($link)
{
	mysql_close ($link);
}

function sql_ejecutar($sentencia)
{
	
	$coon=conectar(DB_HOST,$_SESSION['bd'],3306,DB_USUARIO,DB_CLAVE);		
	
	
	$resultado = mysql_query($sentencia,$coon)  or die($sentencia." ".mysql_error()); 
		
	desconectar ( $coon);
	return $resultado;
}

function EXEFNSQL($sentencia,$par1='',$par2='',$par3='',$par4='',$par5='',$par6='',$par7='')
{
	
	$coon=conectar('localhost','selectra',3306,'root','');			
	
	if ($par1<>'')
	{$parametros = "$par1";}
	
	if ($par2<>'')
	{$parametros = "$parametros,$par2";}
	
	if ($par3<>'')
	{$parametros = "$parametros,$par3";}
	
	if ($par4<>'')
	{$parametros = "$parametros,$par4";}
	
	if ($par5<>'')
	{$parametros = "$parametros,$par5";}
	
	if ($par6<>'')
	{$parametros = "$parametros,$par6";}
	
	if ($par7<>'')
	{$parametros = "$parametros,$par7";}
	
	$parametros = "($parametros)";	
	$sentencia = "$sentencia $parametros";
	
	msgbox ($sentencia);
	
	$resultado = mysql_query("SELECT $sentencia",$coon); 
	
	
	$row = mysql_fetch_array($resultado);
	return $row[0];
}

function activar_submit($nombre_pagina)
{
	$reg=$_POST[registro_id];?>
	<input name="registro_id" type="Hidden" id="registro_id" value="<?php echo $_POST[registro_id]; ?>">
	<?php
    echo "<script languaje=javascript> window.location.href='$nombre_pagina'; </script> ";
//	echo "<script languaje=javascript> lista_recaudos.action='$nombre_pagina';
//                    lista_recaudos.submit();  
}

function activar_pagina($nombre_pagina)
{
	echo "<script languaje=javascript> window.location.href='$nombre_pagina';  </script>";
}

function condicional($opcion,$condicion,$cadena,$query)
{
	/*
	if ($condicion=="Contenga")
	{
		$query="$query  where UPPER($opcion) ";
		$query="$query like (UPPER('%$cadena%'))";
	}
	if ($condicion=="Sea Igual a")
	{
		$query="$query  where $opcion ";
		$query="$query ='$cadena' ";
		//echo "condicional=$query";
	}
	*/
	
	if ($condicion=="Contenga")
		{
		$query="$query  where UPPER(cedula) like (UPPER('%$cadena%')) OR ";
		$query="$query  UPPER(nombres) like (UPPER('%$cadena%')) OR ";
		$query="$query  UPPER(apellidos) like (UPPER('%$cadena%'))";
		}
	elseif ($condicion=="Sea Igual a")
		{
		$query="$query  where cedula ='$cadena' OR ";
		$query="$query  nombres ='$cadena' OR ";
		$query="$query  apellidos='$cadena'";			
		}
	return $query;
	
}
/* RESPALDO DE ESTA FUNCION
function condicional($opcion,$condicion,$cadena,$query)
{
	if ($condicion=="Contenga")
	{
		$query="$query  where UPPER($opcion) ";
		$query="$query like (UPPER('%$cadena%'))";
	}
	if ($condicion=="Sea Igual a")
	{
		$query="$query  where $opcion ";
		$query="$query ='$cadena' ";
		//echo "condicional=$query";
	}
	return $query;
	
}
*/


function filtrado($condicion,$cadena,$query,
$campo1="",$campo2="",$campo3="",$campo4="",$campo5="",
$campo6="",$campo7="",$campo8="",$campo9="",$campo10="",$TipoNomina="",$campo_tipo_nomina="")
{
	if (condicion=="")
	{
		return $query;
	}
	else
	{
		if ($condicion=="Contenga"){
		
			if ($TipoNomina<>""){
			$query = "$query where $campo_tipo_nomina = $TipoNomina AND (";
			if ($campo1<>""){$query="$query UPPER($campo1) like (UPPER('%$cadena%')) ";}			
			}
			else{
			if ($campo1<>""){$query="$query where UPPER($campo1) like (UPPER('%$cadena%')) ";}			
			}
		
			if ($campo2<>""){$query="$query OR ";$query="$query UPPER($campo2) like (UPPER('%$cadena%')) ";}			
			if ($campo3<>""){$query="$query OR ";$query="$query UPPER($campo3) like (UPPER('%$cadena%')) ";}			
			if ($campo4<>""){$query="$query OR ";$query="$query UPPER($campo4) like (UPPER('%$cadena%')) ";}			
			if ($campo5<>""){$query="$query OR ";$query="$query UPPER($campo5) like (UPPER('%$cadena%')) ";}			
			if ($campo6<>""){$query="$query OR ";$query="$query UPPER($campo6) like (UPPER('%$cadena%')) ";}			
			if ($campo7<>""){$query="$query OR ";$query="$query UPPER($campo7) like (UPPER('%$cadena%')) ";}			
			if ($campo8<>""){$query="$query OR ";$query="$query UPPER($campo8) like (UPPER('%$cadena%')) ";}			
			if ($campo9<>""){$query="$query OR ";$query="$query UPPER($campo9) like (UPPER('%$cadena%')) ";}			
			if ($campo10<>""){$query="$query OR ";$query="$query UPPER($campo10) like (UPPER('%$cadena%')) ";}	
		}
		elseif ($condicion=="Sea Igual a"){		
			if ($TipoNomina<>""){
			$query = "$query where $campo_tipo_nomina = $TipoNomina AND (";
			if ($campo1<>""){$query="$query $campo1 ='$cadena' ";}			
			}
			else{
			if ($campo1<>""){$query="$query  where $campo1 ='$cadena' ";}			
			}
		
			if ($campo2<>""){$query="$query OR ";$query="$query $campo2 ='$cadena' ";}			
			if ($campo3<>""){$query="$query OR ";$query="$query $campo3 ='$cadena' ";}			
			if ($campo4<>""){$query="$query OR ";$query="$query $campo4 ='$cadena' ";}			
			if ($campo5<>""){$query="$query OR ";$query="$query $campo5 ='$cadena' ";}			
			if ($campo6<>""){$query="$query OR ";$query="$query $campo6 ='$cadena' ";}			
			if ($campo7<>""){$query="$query OR ";$query="$query $campo7 ='$cadena' ";}			
			if ($campo8<>""){$query="$query OR ";$query="$query $campo8 ='$cadena' ";}			
			if ($campo9<>""){$query="$query OR ";$query="$query $campo9 ='$cadena' ";}			
			if ($campo10<>""){$query="$query OR ";$query="$query $campo10 ='$cadena' ";}
		}
	}
	
	if ($TipoNomina<>""){return $query.")";}else{return $query;}	
}

function encabezado_general($nomina_id,$pagina=1,$titulo="nada"){
if($nomina_id!=""){
     
			$query="select * from nom_nominas_pago where codnom = '$nomina_id'";		
			$result2=sql_ejecutar($query);	
			$fila2 = mysql_fetch_array($result2);
			}
?>


<table width="743" align="center">
  <tbody>
    <tr>
      <td width="600" colspan="0" rowspan="0" align="left" valign="middle">GOBERNACION DEL ESTADO ZULIA</td>
      <td width="143" align="left"><strong>Fecha:</strong> <?echo date("d/m/Y")?></td>
    </tr>
    <tr>
      <td width="600" colspan="0" rowspan="0" align="left" valign="middle">S.A.A.E.Z.</td>
      <td width="143" align="left"><strong>Pag N&#176;:</strong> <?echo $pagina?></td>
    </tr>
    <tr>
      <td width="600" colspan="0" rowspan="0" align="left" valign="middle">OFICINA DE RECURSOS HUMANOS-Dpto. N&oacute;mina</td>
      <td width="143" align="left"><strong>Hora:</strong> <?echo date("h:i:s A")?></td>
    </tr>
  </tbody>
</table>
<br>
<br>
<?if($nomina_id!=""){?>
<table align="center">
  <tbody>
    <tr>
      <td align="center"><u>Reporte&nbsp;de&nbsp;N&oacute;mina&nbsp;<?echo $_SESSION['nomina']?></u></td>
    </tr>
    <tr>
      <td align="center"><h2><strong><u><?echo $titulo?></u></strong></h2></td>
    </tr>
    <tr>
      <td align="center"><u>PERIODO PROCESADO - DESDE:<?echo fecha($fila2['periodo_ini'])?> HASTA: <?echo fecha($fila2['periodo_fin'])?></u></td>
    </tr>
  </tbody>
</table>
<?}?>
<br><br>
<?
}



function encabezado_particular($nomina_id,$pagina=1){

     
			$query="select * from nom_nominas_pago where codnom = '$nomina_id'";		
			$result2=sql_ejecutar($query);	
			$fila2 = mysql_fetch_array($result2);
			
?>


<table width="743" align="center">
  <tbody>
    <tr>
      <td width="600" colspan="0" rowspan="0" align="left" valign="middle">GOBERNACION DEL ESTADO ZULIA</td>
      <td width="143" align="left"><strong>Fecha:</strong> <?echo date("d/m/Y")?></td>
    </tr>
    <tr>
      <td width="600" colspan="0" rowspan="0" align="left" valign="middle">S.A.A.E.Z.</td>
      <td width="143" align="left"><strong>Pag N&#176;:</strong> <?echo $pagina?></td>
    </tr>
    <tr>
      <td width="600" colspan="0" rowspan="0" align="left" valign="middle">OFICINA DE RECURSOS HUMANOS-Dpto. N&oacute;mina</td>
      <td width="143" align="left"><strong>Hora:</strong> <?echo date("h:i:s A")?></td>
    </tr>
  </tbody>
</table>
<br>
<br>
<table align="center">
  <tbody>
<tr>
      <td align="center"><u>Reporte&nbsp;de&nbsp;N&oacute;mina&nbsp;<?echo $_SESSION['nomina']?></u></td>
    </tr>
    <tr>
      <td align="center"><u>PERIODO PROCESADO - DESDE:<?echo fecha($fila2['periodo_ini'])?> HASTA: <?echo fecha($fila2['periodo_fin'])?></u></td>
    </tr>
  </tbody>
</table>
<br><br>
<?
}


function encabezado_configuracion($pagina=1,$nombre){

     

?>


<table width="743" align="center">
  <tbody>
    <tr>
      <td width="600" colspan="0" rowspan="0" align="left" valign="middle">GOBERNACION DEL ESTADO ZULIA</td>
      <td width="143" align="left"><strong>Fecha:</strong> <?echo date("d/m/Y")?></td>
    </tr>
    <tr>
      <td width="600" colspan="0" rowspan="0" align="left" valign="middle">S.A.A.E.Z.</td>
      <td width="143" align="left"><strong>Pag N&#176;:</strong> <?echo $pagina?></td>
    </tr>
    <tr>
      <td width="600" colspan="0" rowspan="0" align="left" valign="middle">OFICINA DE RECURSOS HUMANOS-Dpto. N&oacute;mina</td>
      <td width="143" align="left"><strong>Hora:</strong> <?echo date("h:i:s A")?></td>
    </tr>
  </tbody>
</table>
<br>
<br>

<table align="center">
  <tbody>
      <tr>
      <td align="center"><h2><strong><u><?echo $nombre?></u></strong></h2></td>
    </tr>

  </tbody>
</table>

<br><br>
<?
}
