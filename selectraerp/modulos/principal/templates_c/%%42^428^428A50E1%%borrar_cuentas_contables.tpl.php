<?php /* Smarty version 2.6.21, created on 2017-05-19 18:31:36
         compiled from borrar_cuentas_contables.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'borrar_cuentas_contables.tpl', 78, false),)), $this); ?>
<?php echo '
<script language="JavaScript">
    $(document).ready(function(){
        $("#descripcion").focus();
        $("#formulario").submit(function(){
                if($("#descripcion").val()==""){
                    $.facebox("Debe especificar el nombre del banco");
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
<input type="hidden" name="pagina" value="<?php echo $_GET['pagina']; ?>
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
&pagina=<?php echo $_GET['pagina']; ?>
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
       Nro Cuenta
    </td>
    <td >
        <input type="text" readonly name="nro_cuenta"  size="60" value="<?php echo $this->_tpl_vars['datos_banco'][0]['nro_cuenta']; ?>
" id="nro_cuenta" >
        <input type="hidden" value="<?php echo $this->_tpl_vars['datos_banco'][0]['id']; ?>
" name="id" />
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Descripción
    </td>
    <td >
        <input type="text" name="descripcion" value="<?php echo $this->_tpl_vars['datos_banco'][0]['descripcion']; ?>
" size="60" id="descripcion" >
    </td>
</tr>
 <tr>
        <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Banco
    </td>
     <td >
        <select name="banco" id="banco" class="form-text">
        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_precio'],'selected' => $this->_tpl_vars['option_selected_cuentas'],'output' => $this->_tpl_vars['option_output_precio']), $this);?>

        </select>
       
    </td>
</tr>
</table>


<table width="100%" border="0">
    <tbody>
    <tr class="tb-tit" align="right">
    <td>
        <input type="submit" name="eliminar" id="aceptar" value="Eliminar Cuenta Contable">
    </td>
    </tr>
    </tbody>
</table>

</form>
