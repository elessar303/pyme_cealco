<!DOCTYPE html>
<html>
    <head>
    		<script src="../../libs/js/cambio_precio.js" type="text/javascript"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        {include file="snippets/inclusiones_reportes.tpl"}
        
        
        {literal}
<script language="JavaScript">
   function cambiarPrecio()
   {
   	var coeficiente=$("#coeficiente").val();
   	var mto;

		if($("#precio1").is(':checked')) {
			$("input[id='coniva1']").each(function(){
				mto=parseFloat(($(this).val())*(coeficiente/100))+parseFloat($(this).val());
				mto=mto.toFixed(2);
				$(this).val(mto);
			});
		}  		
		
		if($("#precio2").is(':checked')) {
			$("input[id='coniva2']").each(function(){
				mto=parseFloat(($(this).val())*(coeficiente/100))+parseFloat($(this).val());
				mto=mto.toFixed(2);
				$(this).val(mto);
			});
		}
		
		if($("#precio3").is(':checked')) {
			$("input[id='coniva3']").each(function(){
				mto=parseFloat(($(this).val())*(coeficiente/100))+parseFloat($(this).val());
				mto=mto.toFixed(2);
				$(this).val(mto);
			});
		}
		
		if($("#precio4").is(':checked')) {
			$("input[id='coniva4']").each(function(){
				mto=parseFloat(($(this).val())*(coeficiente/100))+parseFloat($(this).val());
				mto=mto.toFixed(2);
				$(this).val(mto);
			});
		}
		
		if($("#precio5").is(':checked')) {
			$("input[id='coniva5']").each(function(){
				mto=parseFloat(($(this).val())*(coeficiente/100))+parseFloat($(this).val());
				mto=mto.toFixed(2);
				$(this).val(mto);
			});
		}
		
		if($("#precio6").is(':checked')) {
			$("input[id='coniva6']").each(function(){
				mto=parseFloat(($(this).val())*(coeficiente/100))+parseFloat($(this).val());
				mto=mto.toFixed(2);
				$(this).val(mto);
			});
		}
	}

</script>
{/literal}
    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="">
            <div id="datosGral">
                {include file = "snippets/regresar_boton.tpl"}
                <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="cant_fechas" value="2"/>
                <table style="width:100%; background-color: white;">
                    <thead>
                        <tr>
                            <th colspan="6" class="tb-head" style="text-align:center;">LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="label">Articulo Inicial</td>
                            <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <!--label for="fecha_desde">Desde</label-->
                                <select name="articulo_ini" id="articulo_ini" class="form-text">
                            		{html_options values=$option_values_producto output=$option_output_producto }
                        			</select>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Articulo Final</td>
                            <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <!--label for="fecha_desde">Desde</label-->
                                <select name="articulo_fin" id="articulo_fin" class="form-text">
                            		{html_options values=$option_values_producto output=$option_output_producto }
                        			</select>
                            </td>
                        </tr>
                        
								  <tr>
                            <!--<td class="label"></td>-->
                            <td colspan="6" align="left" style="padding-top:2px; padding-bottom: 2px;">      
            					<div id="items" ></div>                
				                            
                            
                            
                            </td>
                        </tr>                  
                        
                        <tr>
                            <!--<td class="label"></td>-->
                            <td colspan="6" align="right" style="padding-top:2px; padding-bottom: 2px; align:right;">
                             
                                Coeficiente&nbsp;&nbsp;<input type="text" name="coeficiente" size="6" id="coeficiente" value="0.00" class="form-text"/>
                                &nbsp;&nbsp;&nbsp; <input type="button"  name="aplicar" value="Aplicar" onclick="cambiarPrecio();" />
									
                            </td>
                        </tr>
                        <tr class="tb-tit">
                            <!--td colspan="3" style="text-align:left">
                                <input type="radio" name="radio" value="0" /> Hoja de C&aacute;lculo
                                <input type="radio" name="radio" value="1" checked /> Formato PDF
                            </td-->
                            <td colspan="6">
                                <input type="submit" id="aceptar" name="aceptar" value="Procesar" />
                                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu=3';" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>