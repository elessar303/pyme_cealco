<?php /* Smarty version 2.6.21, created on 2015-10-21 16:36:21
         compiled from formulacion_impuestos_editar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'formulacion_impuestos_editar.tpl', 70, false),)), $this); ?>
<?php echo '
<script language="JavaScript">
    $(document).ready(function(){
        $("#descripcion").focus();
        $("#formulario").submit(function(){
                if($("#descripcion").val()==""){
                    $.facebox("Debe especificar la descripcion de la forma de pago");
                    $("#descripcion").focus();
                    return false;
                }

        });
    });
</script>
'; ?>


<form name="formulario" id="formulario" method="POST" action="">

<input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
">
<input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
">
<input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
">
<input type="hidden" name="opt_subseccion" value="<?php echo $_GET['opt_subseccion']; ?>
">
<input type="hidden" name="codBanco" value="<?php echo $_GET['cod']; ?>
">
<input type="hidden" name="codCuenta" value="<?php echo $_GET['cod_cuenta']; ?>
">
  <table width="100%">
        <tr class="row-br">
            <td>
                <table class="tb-tit" cellspacing="0" cellpadding="1" border="0" width="100%">
                    <tbody>
                        <tr>
                        <td width="900"><span style="float:left"><img src="<?php echo $this->_tpl_vars['subseccion'][0]['img_ruta']; ?>
" width="22" height="22" class="icon" /><?php echo $this->_tpl_vars['subseccion'][0]['descripcion']; ?>
</span></td>
                        <td width="75">
                            <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&opt_seccion=<?php echo $_GET['opt_seccion']; ?>
'" name="buscar" border="0" cellpadding="0" cellspacing="0">
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
       Codigo
    </td>
    <td >
        <input type="text" name="cod_formula" value="<?php echo $this->_tpl_vars['datos_formula'][0]['cod_formula']; ?>
" id="cod_formula" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Entidad
    </td>
    <td >
<select name="cod_entidad" id="cod_entidad">
<?php echo smarty_function_html_options(array('output' => $this->_tpl_vars['option_output_funcionentidad'],'values' => $this->_tpl_vars['option_values_funcionentidad'],'selected' => $this->_tpl_vars['option_selected_entidad']), $this);?>

</select>

    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Impuesto Aplicado
    </td>
    <td >
<select name="cod_tipo_impuesto" id="cod_tipo_impuesto">
    
<?php echo smarty_function_html_options(array('output' => $this->_tpl_vars['option_output_funciontipoimpuesto'],'values' => $this->_tpl_vars['option_values_funciontipoimpuesto'],'selected' => $this->_tpl_vars['datos_formula'][0]['cod_tipo_impuesto']), $this);?>

    
</select>

    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Fecha de Aplicacion
    </td>
    <td >
<input type="text" name="fecha_aplicacion"  value="<?php echo $this->_tpl_vars['datos_formula'][0]['fecha_aplicacion']; ?>
" id="fecha_aplicacion" size="15" maxlength="12">&nbsp;Ej.: 0000-00-00

    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Descripcion
    </td>
    <td >
        <input type="text" name="descripcion" size="60"  value="<?php echo $this->_tpl_vars['datos_formula'][0]['descripcion']; ?>
" id="descripcion" >
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Formula
    </td>
    <td >
        <textarea cols="58" rows="10" id="formula" name="formula"><?php echo $this->_tpl_vars['datos_formula'][0]['formula']; ?>
</textarea>
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