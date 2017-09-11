<?
function encabezadopdf($var_encabezado1,$var_encabezado2,$var_encabezado3,$var_encabezado4,$var_imagen_izq,$var_imagen_der){

     
$encabezado= '

     $this->SetFont("Arial","B",12);
     $this->Image("../selectra/imagenes/".$var_imagen_izq,10,8,33);
     $this->Ln(20);
     $this->Cell(45);
     $this->Cell(100,8,$var_encabezado1,0,0,"C");
     $this->Image("../selectra/imagenes/".$var_imagen_der,170,15,33);
     $this->Ln(10);
     $this->Cell(35);
     $this->Cell(120,8,$var_encabezado2,0,0,"C");
     $this->Ln(10);
     $this->Cell(10);
     $this->Cell(170,8,$var_encabezado3,0,0,"C");
     $this->Ln(10);
     $this->Cell(10);
     $this->Cell(170,8,$var_encabezado4,0,0,"C");

';

return $encabezado;
}

function pie_inzuvipdf()
{
$pie= '

<div  style="align:center;margin:2.5em;">
<br><br>
 <table height="100" width="575" border="1" ">
  <tbody>
	<tr  >
		<td align="center" width="125"  >Unidad Solicitante<br><br><br><br><br><br>Firma y Sello</td>
      <td align="center" width="125">Administraci&oacute;n<br><br><br><br><br><br>Firma y Sello</td>
		<td align="center" width="125">Compras y Suministros<br><br><br><br><br><br>Firma y Sello</td>
		<td align="center" width="125">Autorizado Por:<br><br><br><br><br><br>Direcci&oacute;n General</td>
    </tr>
  </tbody>
</table>
<table height="100" width="700" border="0" >
  <tbody>
    <tr>
      <td align="left">Elaborado Por:&nbsp;'.$_SESSION['nombre'].'</td>
    </tr>

  </tbody>
</table>
</div>
';
return $pie;
}

function iconoNuevopdf($url,$titulo,$enlace){
echo "<td ><a href="."../fpdf/".$url." target=\"_blank\"><IMG  title=\"$titulo\" src=\"../imagenes/$enlace\" width=\"16\" height=\"16\" align=\"left\" border=\"0\"></a></td>
";
}

?>