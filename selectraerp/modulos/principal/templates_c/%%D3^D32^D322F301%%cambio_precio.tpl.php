<?php /* Smarty version 2.6.21, created on 2015-11-02 13:17:48
         compiled from cambio_precio.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'cambio_precio.tpl', 89, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
    		<script src="../../libs/js/cambio_precio.js" type="text/javascript"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/inclusiones_reportes.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        
        
        <?php echo '
<script language="JavaScript">
   function cambiarPrecio()
   {
   	var coeficiente=$("#coeficiente").val();
   	var mto;

		if($("#precio1").is(\':checked\')) {
			$("input[id=\'coniva1\']").each(function(){
				mto=parseFloat(($(this).val())*(coeficiente/100))+parseFloat($(this).val());
				mto=mto.toFixed(2);
				$(this).val(mto);
			});
		}  		
		
		if($("#precio2").is(\':checked\')) {
			$("input[id=\'coniva2\']").each(function(){
				mto=parseFloat(($(this).val())*(coeficiente/100))+parseFloat($(this).val());
				mto=mto.toFixed(2);
				$(this).val(mto);
			});
		}
		
		if($("#precio3").is(\':checked\')) {
			$("input[id=\'coniva3\']").each(function(){
				mto=parseFloat(($(this).val())*(coeficiente/100))+parseFloat($(this).val());
				mto=mto.toFixed(2);
				$(this).val(mto);
			});
		}
		
		if($("#precio4").is(\':checked\')) {
			$("input[id=\'coniva4\']").each(function(){
				mto=parseFloat(($(this).val())*(coeficiente/100))+parseFloat($(this).val());
				mto=mto.toFixed(2);
				$(this).val(mto);
			});
		}
		
		if($("#precio5").is(\':checked\')) {
			$("input[id=\'coniva5\']").each(function(){
				mto=parseFloat(($(this).val())*(coeficiente/100))+parseFloat($(this).val());
				mto=mto.toFixed(2);
				$(this).val(mto);
			});
		}
		
		if($("#precio6").is(\':checked\')) {
			$("input[id=\'coniva6\']").each(function(){
				mto=parseFloat(($(this).val())*(coeficiente/100))+parseFloat($(this).val());
				mto=mto.toFixed(2);
				$(this).val(mto);
			});
		}
	}

</script>
'; ?>

    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="">
            <div id="datosGral">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_boton.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
"/>
                <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
                <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
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
                            		<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_producto'],'output' => $this->_tpl_vars['option_output_producto']), $this);?>

                        			</select>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Articulo Final</td>
                            <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <!--label for="fecha_desde">Desde</label-->
                                <select name="articulo_fin" id="articulo_fin" class="form-text">
                            		<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_producto'],'output' => $this->_tpl_vars['option_output_producto']), $this);?>

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