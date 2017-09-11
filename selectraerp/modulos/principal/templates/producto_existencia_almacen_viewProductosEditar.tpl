{literal}
<script language="JavaScript">
    $(document).ready(function(){
			
       $("input").numeric();
	   $("input").blur(function(){
			if($(this).val()!="") $(this).val(parseInt($(this).val()));
	   });
	   
	   $("#addcantidad").blur(function(){
		  if ($(this).val() != "") {
		  	cantidadActual = parseFloat($("#cantidad").val());
		  	totalcantidad = parseFloat(cantidadActual) + parseFloat($("#addcantidad").val());
		  	$("#totalcantidad").val(totalcantidad);
		  }
		  
	   });
	   
	   
        $("#addcantidad").focus();
        $("#formulario").submit(function(){
                if($("#addcantidad").val()==""){
                    alert("Debe Ingresar la cantidad!.");
                    $("#addcantidad").focus();
                    return false;
                }
            $("#addcantidad").trigger("blur");
			
        });
    });
</script>
{/literal}

<form name="formulario" id="formulario" method="POST" action="">

<input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}">
<input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}">
<input type="hidden" name="opt_subseccion" value="{$opt_subseccion}">

  <table width="100%">
        <tr class="row-br">
            <td>
                <table class="tb-tit" cellspacing="0" cellpadding="1" border="0" width="100%">
                    <tbody>
                        <tr>
                        <td width="900"><span style="float:left"><img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" />{$subseccion[0].descripcion}</span></td>
                        <td width="75">
                            <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=viewProductos&cod={$smarty.get.cod}'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/back.gif" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Regresar</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>

                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

<table   width="100%" border="0" >
<tr>
        <td colspan="4" class="tb-head" align="center">
          &nbsp;
      </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Almacen {$rs_almacen[0].descripcion}
    </td>
    <td>
       <input type="text" name="cod_almacen" readonly="readonly" value="{$rs_almacen[0].cod_almacen}" id="cod_almacen" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Producto
    </td>
    <td>
        <select name="cod_producto" id="cod_producto">
          {html_options values=$option_output_producto output=$option_values_producto selected= $option_selected_producto}
        </select>
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Cantidad Actual
    </td>
    <td >
        <input type="text" name="cantidad" size="60"  value="{$rs_almacen[0].cantidad}"  readonly="readonly" id="cantidad" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Agregar Cantidad
    </td>
    <td >
        <input type="text" name="addcantidad" size="60"  value=""  id="addcantidad" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Total Cantidad
    </td>
    <td >
        <input type="text" name="totalcantidad" size="60"  value=""  readonly="readonly" id="totalcantidad" >
    </td>
</tr>
</table>



   
<table width="100%" border="0">
    <tbody>
    <tr class="tb-tit" align="right">
    <td>
        <input type="submit" name="aceptar" id="aceptar" value="Guardar">
    </td>
    </tr>
    </tbody>
</table>

</form>
