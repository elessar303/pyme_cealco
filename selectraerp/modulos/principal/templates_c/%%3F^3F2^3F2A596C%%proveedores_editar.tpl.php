<?php /* Smarty version 2.6.21, created on 2017-09-13 15:51:53
         compiled from proveedores_editar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'proveedores_editar.tpl', 113, false),)), $this); ?>
<?php echo '
<script language="JavaScript">
    $(document).ready(function(){
        $("#cod_proveedor").focus();
        $("#formulario").submit(function(){
                if($("#descripcion").val()=="" ||  $("#rif").value.length !=10 || $("#rif").val()==""){
                    alert("Debe llenar todos los campos obligatorios (**)!");
                    return false;
                }
                if(document.getElementById("rif").value.length < 10){
                alert(\'ingrese un id fiscal de almenos 10 caracteres\');
                return false;
                }  
        });



       $(".validadDecimales").numeric();
       $(".validadDecimales").blur(function(){
            if($(this).val()!=\'\'&&$(this).val()!=\'.\'){
                $(this).val(parseFloat($(this).val()));
            }else{
                $(this).val("0.00");
            }
        });


     $(".validadNumerico").numeric();
       $(".validadNumerico").blur(function(){
            if($(this).val()!=\'\'&&$(this).val()!=\'.\'){
                $(this).val(parseInt($(this).val()));
            }else{
                $(this).val("0");
            }
        });


    });

</script>
'; ?>

<script src="../../libs/js/validar_rif.js" type="text/javascript"></script>
<form name="formulario" id="formulario" method="POST" action="" enctype="multipart/form-data">
<input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
">
<input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
">
<input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
">
<input type="hidden" name="opt_subseccion" value="<?php echo $_GET['opt_subseccion']; ?>
">
<input type="hidden" name="id_proveedor" value="<?php echo $_GET['cod']; ?>
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
          LOS CAMPOS CON ** SON OBLIGATORIOS&nbsp;
      </td>
</tr>
<tr hidden="hidden">
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Codigo Proveedor**
    </td>
    <td >
        <input type="text" name="cod_proveedor" value="<?php echo $this->_tpl_vars['datos_proveedores'][0]['cod_proveedor']; ?>
" readonly id="cod_proveedor" >

    </td>
</tr>


 <tr hidden="hidden">
 <td  colspan="3"style="width:30%; vertical-align: top;" class="tb-head">Foto</td>
 <td>
  <input type="file" name="foto" id="foto"  class="form-text"/>
  
 </td>
</tr>

<tr hidden="hidden">
<td colspan="3" style="width:30%; vertical-align: top;" class="tb-head"></td>
 <td  align="left"> 
<img src="../../imagenes/<?php echo $this->_tpl_vars['datos_proveedores'][0]['foto']; ?>
" width="100" align="absmiddle" height="100"/> 
 </td>
 
</tr> 
<tr>
        <td colspan="4" class="tb-head" align="center">
          &nbsp;
      </td>
</tr>

<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_parametros'],'output' => $this->_tpl_vars['option_output_idfiscal']), $this);?>

    </td>
    <td >
        <input type="text" name="rif" size="60" value="<?php echo $this->_tpl_vars['datos_proveedores'][0]['rif']; ?>
" id="rif" maxlength="10" >
        <span id="rif_error" class="error" name="rif_error"   >Formato Invalido..</span>
    </td>
</tr>


<tr hidden="hidden">
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Compañia
    </td>
    <td >
        <input type="text" name="compania"  size="60" value="<?php echo $this->_tpl_vars['datos_proveedores'][0]['compania']; ?>
" id="compania" >

    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Descripción
    </td>
    <td >
        <input type="text" name="descripcion"  size="60" value="<?php echo $this->_tpl_vars['datos_proveedores'][0]['descripcion']; ?>
" id="descripcion" >

    </td>
</tr>
<tr hidden="hidden">
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Dirección **
    </td>
    <td >
        <input type="text" name="direccion"  value="<?php echo $this->_tpl_vars['datos_proveedores'][0]['direccion']; ?>
" size="60" id="direccion" >
    </td>
</tr>
<tr hidden="hidden">
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Telefonos **
    </td>
    <td >
        <input type="text" name="telefonos" value="<?php echo $this->_tpl_vars['datos_proveedores'][0]['telefonos']; ?>
" size="60" id="telefonos" >
    </td>
</tr>
<tr hidden="hidden">
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       Fax
    </td>
    <td >
        <input type="text" name="fax"  value="<?php echo $this->_tpl_vars['datos_proveedores'][0]['fax']; ?>
" size="60" id="fax" >
    </td>
</tr>
<tr hidden="hidden">
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
       E-Mail
    </td>
    <td >
        <input type="text"  value="<?php echo $this->_tpl_vars['datos_proveedores'][0]['email']; ?>
"  name="email" size="60" id="email" >
    </td>
</tr>

<tr hidden="hidden">
    <td class="tb-head" colspan="3">
        Estatus
    </td>
    <td>
        <select name="estatus" id="estatus">
    <option <?php if ($this->_tpl_vars['datos_proveedores'][0]['estatus'] == 'A'): ?> selected<?php endif; ?> value="A">Activo</option>
            <option <?php if ($this->_tpl_vars['datos_proveedores'][0]['estatus'] == 'I'): ?> selected<?php endif; ?> value="I">Inactivo</option>        
			</select>
    </td>
</tr>


<tr hidden="hidden">
    <td class="tb-head" colspan="3">
        Tipo de Proveedor
    </td>
    <td>
<select name="id_pclasif" id="id_pclasif">
    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_clasi'],'output' => $this->_tpl_vars['option_output_clasi'],'selected' => $this->_tpl_vars['option_selected_clasi']), $this);?>

</select>    
</td>
</tr>

<tr hidden="hidden">
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Especialidad
    </td>
    <td >
<select name="cod_especialidad" id="cod_especialidad">
    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_especialidad'],'output' => $this->_tpl_vars['option_output_especialidad'],'selected' => $this->_tpl_vars['option_selected_especialidad']), $this);?>

</select>
    </td>
</tr>



<!--
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Tipo de Proveedor
    </td>
    <td >
        <select name="cod_tipo_proveedor" id="cod_tipo_proveedor">
             <option <?php if ($this->_tpl_vars['datos_proveedores'][0]['cod_tipo_proveedor'] == 'Normal'): ?> selected<?php endif; ?> value="Normal">Normal</option>
            <option <?php if ($this->_tpl_vars['datos_proveedores'][0]['cod_tipo_proveedor'] == 'No Residenciado'): ?> selected<?php endif; ?> value="No Residenciado">No Residenciado</option> 
	     <option <?php if ($this->_tpl_vars['datos_proveedores'][0]['cod_tipo_proveedor'] == 'No Domiciliado'): ?> selected<?php endif; ?> value="No Domiciliado">No Domiciliado</option>   
        </select>
    </td>
</tr>
-->

<tr hidden="hidden">
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Tipo de Entidad
    </td>
    <td >
<select name="cod_entidad" id="cod_entidad">
    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_entidad'],'output' => $this->_tpl_vars['option_output_entidad'],'selected' => $this->_tpl_vars['option_selected_entidad']), $this);?>

</select>
    </td>
</tr>





<tr hidden="hidden">
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
     Retencion del I.V.A.
    </td>
    <td >
<select name="cod_impuesto_proveedor" id="cod_impuesto_proveedor">
    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_impuesto'],'output' => $this->_tpl_vars['option_output_impuesto'],'selected' => $this->_tpl_vars['option_selected_impuesto']), $this);?>

</select>
    </td>
</tr>

<tr hidden="hidden">
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Mostrar Cobrado y No Cobrado
    </td>
    <td >
<select name="mostrar" id="mostrar">
    <option values="0">No</option>
    <option values="1">Si</option>
</select>
    </td>
</tr>

<tr hidden="hidden">
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Cuenta Contable
    </td>
    <td >
<select name="cuenta_contable" id="cuenta_contable">
    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cuenta'],'output' => $this->_tpl_vars['option_output_cuenta'],'selected' => $this->_tpl_vars['option_selected_cuenta']), $this);?>

</select>
    </td>
</tr>

</table>

<table width="100%" border="0">
    <tbody>
    <tr class="tb-tit" align="right">
    <td>
        <input type="submit" name="aceptar" id="aceptar" value="Guardar Cambios">
    </td>
    </tr>
    </tbody>
</table>

</form>