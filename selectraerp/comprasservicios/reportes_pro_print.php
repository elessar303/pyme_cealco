<?php 
if (!isset($_SESSION))
{ session_start();
ob_start(); 
} 
	
include ("../header.php");
require_once '../lib/config.php';
require_once '../lib/common.php';

$Conn=conexion_conf();
 //= new mysqli($ConnSys["server"], $ConnSys["user"], $ConnSys["pass"], $ConnSys["db"]);
	$var_sql="select encabezado1,encabezado2,encabezado3,encabezado4,imagen_izq from parametros";
	$rs = query($var_sql,$Conn);
	$row_rs = fetch_array($rs);
	$var_encabezado1=$row_rs['encabezado1'];
	$var_encabezado2=$row_rs['encabezado2'];
	$var_encabezado3=$row_rs['encabezado3'];
	$var_encabezado4=$row_rs['encabezado4'];
	$var_imagen_izq=$row_rs['imagen_izq'];
	$var_imagen_der=$row_rs['imagen_der'];		
	//$rs->close();
	?>


<div style="text-align: center;">
  <p align="center">&nbsp;</p>
  <div align="center">
<div align="right"><INPUT type="button" name="imprimir" value="Imprimir" onclick="javascript:imprimir('impresion')"></div>
<div id="impresion">
<?
$encabezado=encabezado($var_encabezado1,$var_encabezado2,$var_encabezado3,$var_encabezado4,$var_imagen_izq);
echo $encabezado;

$pagina=1;
$date1=date('d/m/Y');
$date2=date('h:i a');
$datos2="<TABLE width='743' align='center' border='0'>
		<TR>
			<TD align='right'><strong>Fecha: </strong>$date1</TD>
		</TR>
		<TR>
			<TD align='right'><strong>Hora: </strong>$date2</TD>
		</TR>
		<TR>
			<TD align='right'><strong>P&#225;g.: &nbsp;$pagina</strong></TD>
		</TR>
	</TABLE>";
	echo $datos2;


?>
<h3 align="center">LISTADO DE PROVEEDORES</h3>
  <div style="text-align: left;">
    <div align="center">
	<?php
	  $conex=conexion();
	  $var_sql_con=$_GET['buscar'];
	  //echo $var_sql_con;
	  $var_sql_con="select * from proveedores where compania <> '' AND estatus='A' Order by ".$var_sql_con ." "; 
	  $contador=0;

	  $rs = query($var_sql_con,$conex);
 /*  	  while ($row_rs = fetch_array($rs)) 
	  { 
	  	$contador++;
	  }
	  //$rs->close();
	  if ($contador<>0)
	 { */?>

      <table width="700" border="1" align="center" cellpadding="2" cellspacing="2" class="">
        <tr class="">
          <td width="50"><div align="left"><strong>C&oacute;digo</strong></div></td>
          <td width="350"><div align="left"><strong>Compa&ntilde;ia</strong></div></td>
           <!--<td width="100"><div align="left"><strong>Tipo</strong></div></td>-->
          <td width="60"><div align="left"><strong>R.I.F</strong></div></td>
          <td width="100"><div align="left"><strong>Teléfonos</strong></div></td>
        </tr>
        <?php 
	  $rs = query($var_sql_con,$conex);
   	  while ($row_rs = fetch_array($rs)) 
	  { 
		$contador++; 
	  ?>
        <tr class="tb-bg-in">
          <form method="post" id="form<? echo $row_rs['id_foto'] ?>" name="form<? echo $row_rs['id_foto'] ?>" action="<? echo $filename ?>?rsac=edit&amp;id=<? echo 		$row_rs['id_foto'] ?>">
            <td><div align="left"><? echo $row_rs['cod_proveedor'] ?>
                </div></td><td><div align="left"><? echo $row_rs['compania'] ?>
                  </div></td>
            
              <!--
            <td><? $tipo= $row_rs['tipo_compania']; if($tipo=='P'){echo "Proveedor";}elseif($tipo=='C'){echo "Contratista";}elseif($tipo=='I'){echo 'Cooperativa';}elseif($tipo=='F'){echo 'Fundación';}elseif($tipo=='O'){echo 'Otros';} ?></td>-->
            <td><div align="left"><? echo $row_rs['rif']; ?></div></td><td><div align="left"><? echo $row_rs['telefonos']; ?>
              </div></td>
        </form>
	</tr>
        <? 

if($contador >= 27)
	{
	

	echo "<br  style='page-break-before : avoid;'>";

		echo "</table> <br><br><br><br><br><br><br><br><br><br><br><br><br>";
		
		echo $encabezado;
		$date1=date('d/m/Y');
		$date2=date('h:i a');	
		$datos="<TABLE width='743' align='center' border='0'>
		<TR>
			<TD align='right'><strong>Fecha: </strong>$date1</TD>
		</TR>
		<TR>
			<TD align='right'><strong>Hora: </strong>$date2</TD>
		</TR>
		<TR>
			<TD align='right'><strong>P&#225;g.: &nbsp;".++$pagina."</strong></TD>
		</TR>
		</TABLE>";
		echo $datos;	
		$contador=1;
		?>	
<h3 align="center">LISTADO DE PROVEEDORES</h3>
	<table width="743" border="1" align="center" cellpadding="2" cellspacing="2" class="">
        <tr class="">
          <td width="50"><div align="left"><strong>C&oacute;digo</strong></div></td>
          <td width="350"><div align="left"><strong>Compa&ntilde;ia</strong></div></td>
        
          <!--  <td width="100"><div align="left"><strong>Tipo</strong></div></td> -->
          <td width="60"><div align="left"><strong>R.I.F</strong></div></td>
          <td width="100"><div align="left"><strong>Telefonos</strong></div></td>
        </tr>
	<?
	}

	} // cierra el if 
	?>
        <?  
	cerrar_conexion($Conn);//$rs->close();
	?>
      	</table>
	<?php// } ?>
    	</div>
    	<div align="center">    </div>
    	<p align="center"><br>
      	<span style="font-family: serif;"></span></p>
</div>
<div align="center"><span style="font-family: serif;"> </span></div>
</div>
</body>
</html>