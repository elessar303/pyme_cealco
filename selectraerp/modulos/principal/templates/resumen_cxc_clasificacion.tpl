{literal}
<script type="text/javascript">

function valida_envia(){
    //valido el codigo
    if (document.formulario.fecha.value.length==0){
       alert("Tiene que seleccionar un tipo para el listado")
       document.formulario.fecha.focus()
       return false;}

	
	   //el formulario se envia
    document.formulario.submit();}
</script>
{/literal}


<form name="formulario" id="formulario" method="POST" onsubmit="return valida_envia()" action="procesar9.php">
<input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}">
<input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}">
<input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}">

<table  width="100%" border="0">
<tbody>
<tr>
<td  class="tb-tit">
<img src="../../libs/imagenes/118.png" width="20" align="absmiddle" height="20">  <b>Datos Del reporte</b>
</td>
</tr>
</tbody>
</table>


<table   width="100%" border="0" height="100">
<tr>
<td colspan="3" class="tb-head" align="center">
	COMPLETE LOS CAMPOS MARCADOS CON&nbsp;** OBLIGATORIAMENTE
</td>

<tr>
<td colspan="" class="tb-head" width="170px">
INGRESE EL TIPO A LISTAR</td>
<td>
<select name="cod_tipo_cliente" id="cod_tipo_cliente">
    {html_options values=$option_values_especialidad output=$option_output_especialidad selected=$option_selected_especialidad}
</select>


<tr class="tb-tit" align="right">
<td align="left" colspan="3">
<!--<input type="radio" name="radio" value="0" /> Formato EXCEL -->
<input type="radio" name="radio" value="1" checked="checked"/> Formato PDF
</td>
<td colspan="3">
<input type="submit" id="aceptar" name="aceptar" value="Enviar" onclick="valida_envia()">
<input type="button" name="cancelar" onclick="javascript:document.location.href='?opt_menu=5';" value="Cancelar">

</td>
</tr>

</tbody>
</table>
</form>

