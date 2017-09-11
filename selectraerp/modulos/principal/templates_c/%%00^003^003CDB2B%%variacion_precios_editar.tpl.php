<?php /* Smarty version 2.6.21, created on 2017-08-28 16:19:37
         compiled from variacion_precios_editar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'variacion_precios_editar.tpl', 173, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/header_form.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../libs/js/ajax.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>
<?php echo '
<script language="JavaScript">
function validarItem(cod, nom)
{
    //$("#nombre0").html(cod.value);

    if(cod.value!=\'\'){
        $.ajax({
            type: "GET",
            url:  "../../libs/php/ajax/ajax.php",
            data: "opt=ValidarCodigoBarras&v1="+cod.value,
            beforeSend: function(){
                $("#notificacionVCoditem").html(MensajeEspera("<b>Veficando Cod. item..<b>"));
            },
            success: function(data){
                if(data == "NO EXISTE EL PRODUCTO")
                {
                    $("#"+nom).html("<img align=\\"absmiddle\\" src=\\"../../libs/imagenes/ico_note.gif\\"><span style=\\"color:red;\\"><b>"+data+"</b></span>");
                    cod.focus();
                }
                else
                {
                    $("#"+nom).html("");
                    $("#"+nom).html("<img align=\\"absmiddle\\" src=\\"../../libs/imagenes/ok.gif\\"><span style=\\"color:#0c880c;\\"><b>"+data+"</b></span>");
                }
                /*if(resultado=="-1"){
                    //$("#cod_item").val("").focus();
                    $("#notificacionVCoditem").html("<img align=\\"absmiddle\\" src=\\"../../libs/imagenes/ico_note.gif\\"><span style=\\"color:red;\\"><b>Disculpe, este c&oacute;digo ya existe.</b></span>");
                }
                else if(resultado=="1"){//cod de item disponble, originalmente sin "else"
                    $("#notificacionVCoditem").html("<img align=\\"absmiddle\\" src=\\"../../libs/imagenes/ok.gif\\"><span style=\\"color:#0c880c;\\"><b> C&oacute;digo Disponible</b></span>");
                }*/
            }
        });
    }
}

function agregarProducto()
{
    valor=$("#numero_veces").val();
    URL = document.URL;
    if(URL.indexOf(\'&boton_agregar=\') != -1)
           URL = URL.replace(\'&boton_agregar=\'+(valor-1),\'&boton_agregar=\'+valor);
    else
          URL = URL.replace(\'edit\',\'edit&boton_agregar=\'+valor); 

    window.location = URL;
}
   /* $(document).ready(function(){
        


        $("#restringido_grupo").click(function() {          
           if($("#restringido_grupo").is(":checked")){
                $("#restringido").show();
           }else{
                $("#cantidad_grupo").val("");
                $("#dias_grupo").val("");
                $("#restringido").hide();
           }
        });
        /*$("#rubro").click(function() {          
           if($("#rubro").val()==2){
                $("#restringido2").show();
           }else{
                $("#restringido2").hide();
           }
        });*/

   /* });*/
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
<input type="hidden" name="num_campos" value="20">
<input type="hidden" name="id_var_precio_cab" value="<?php echo $_GET['cod']; ?>
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

<table width="100%" border="0">
    <tbody>
    <tr class="tb-tit" align="right">
    <td>
        <input type="submit" name="aceptar" id="aceptar" value="Guardar">
    </td>
    </tr>
    </tbody>
</table>
<div id=boton_agregar> 
        <table class="btn_bg" onclick="agregarProducto()" align="center" border="0">
            <tr style="border-width: 0px; cursor: pointer;">
                <td><img src="../../../includes/imagenes/bt_left.gif" style="border-width: 0px; width: 4px; height: 21px;" />
                </td>
                <td><img src="../../../includes/imagenes/add.gif" width="16" height="16" />
                </td>
                <td style="padding: 0px 6px;">Agregar Celda Nueva 
                <input type="text" id="numero_veces" value="<?php echo $this->_tpl_vars['numero_veces']; ?>
"  name="numero" hidden="hidden" />
                <input type="text" id="filas" value="<?php echo $this->_tpl_vars['filas']; ?>
"  name="filas"  hidden="hidden" />
                </td>
                <td><img src="../../../includes/imagenes/bt_right.gif" style="border-width: 0px; width: 4px; height: 21px;" />
                </td>
            </tr>
        </table>
</div>

<table   width="100%" border="0" >
    <thead>
        <tr class="tb-head">
            <th style="width:220px;" >Codigo de Barras</th>
            <th style="width:400px;">Nombre</th>
            <th>Precio</th>
            <th>Estado</th>
            <th>Motivo de la variaci&oacute;n</th>
            <th>Observacion</th>
        </tr>
    </thead>

    <tbody>

        <?php $_from = $this->_tpl_vars['registros']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
        <tr>
            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="codigo_barras<?php echo $this->_tpl_vars['i']; ?>
" onblur="validarItem(this, 'nombre<?php echo $this->_tpl_vars['i']; ?>
')" size="30" id="codigo_barras<?php echo $this->_tpl_vars['i']; ?>
" value="<?php echo $this->_tpl_vars['campos']['codigo_barra']; ?>
" autofocus>
            </td>
            <td><div id="nombre<?php echo $this->_tpl_vars['i']; ?>
"> <?php echo $this->_tpl_vars['campos']['descripcion1']; ?>
</div>
                <!--<input class="form-text" type="text" name="nombre<?php echo $this->_tpl_vars['i']; ?>
" size="45" id="nombre<?php echo $this->_tpl_vars['i']; ?>
" value="<?php echo $this->_tpl_vars['campos']['descripcion1']; ?>
" readonly>-->
            </td>
            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="precio<?php echo $this->_tpl_vars['i']; ?>
" size="10" id="precio<?php echo $this->_tpl_vars['i']; ?>
" value="<?php echo $this->_tpl_vars['campos']['precio_sin_iva']; ?>
">
            </td>

            <td style="padding-top:2px; padding-bottom: 2px;">
                <select class="form-text" type="text" name="estado<?php echo $this->_tpl_vars['i']; ?>
" >
                    <!--<option value="">Todos</option>-->
                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_estados'],'output' => $this->_tpl_vars['option_output_estados'],'selected' => $this->_tpl_vars['campos']['id_estado']), $this);?>

                </select>
            </td>

            <td>
                <select class="form-text" type="text" name="motivo<?php echo $this->_tpl_vars['i']; ?>
" id="motivo<?php echo $this->_tpl_vars['i']; ?>
" >
                    <option value="">--Seleccione el motivo--</option>
                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_motivo'],'output' => $this->_tpl_vars['option_output_motivo'],'selected' => $this->_tpl_vars['campos']['id_motivo']), $this);?>

                </select>
            </td>
            <td>
                <input type="text" name="observacion<?php echo $this->_tpl_vars['i']; ?>
" id="observacion<?php echo $this->_tpl_vars['i']; ?>
" class="form-text" size="25" value="<?php echo $this->_tpl_vars['campos']['observacion']; ?>
">
            </td>
        </tr>

        <?php endforeach; endif; unset($_from); ?>
        <?php $this->assign('i', $this->_tpl_vars['i']+1); ?>
        <?php if ($_GET['boton_agregar'] != ''): ?> 
        <?php unset($this->_sections['k']);
$this->_sections['k']['name'] = 'k';
$this->_sections['k']['start'] = (int)$this->_tpl_vars['i'];
$this->_sections['k']['loop'] = is_array($_loop=$this->_tpl_vars['i']+$_GET['boton_agregar']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['k']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['k']['show'] = true;
$this->_sections['k']['max'] = $this->_sections['k']['loop'];
if ($this->_sections['k']['start'] < 0)
    $this->_sections['k']['start'] = max($this->_sections['k']['step'] > 0 ? 0 : -1, $this->_sections['k']['loop'] + $this->_sections['k']['start']);
else
    $this->_sections['k']['start'] = min($this->_sections['k']['start'], $this->_sections['k']['step'] > 0 ? $this->_sections['k']['loop'] : $this->_sections['k']['loop']-1);
if ($this->_sections['k']['show']) {
    $this->_sections['k']['total'] = min(ceil(($this->_sections['k']['step'] > 0 ? $this->_sections['k']['loop'] - $this->_sections['k']['start'] : $this->_sections['k']['start']+1)/abs($this->_sections['k']['step'])), $this->_sections['k']['max']);
    if ($this->_sections['k']['total'] == 0)
        $this->_sections['k']['show'] = false;
} else
    $this->_sections['k']['total'] = 0;
if ($this->_sections['k']['show']):

            for ($this->_sections['k']['index'] = $this->_sections['k']['start'], $this->_sections['k']['iteration'] = 1;
                 $this->_sections['k']['iteration'] <= $this->_sections['k']['total'];
                 $this->_sections['k']['index'] += $this->_sections['k']['step'], $this->_sections['k']['iteration']++):
$this->_sections['k']['rownum'] = $this->_sections['k']['iteration'];
$this->_sections['k']['index_prev'] = $this->_sections['k']['index'] - $this->_sections['k']['step'];
$this->_sections['k']['index_next'] = $this->_sections['k']['index'] + $this->_sections['k']['step'];
$this->_sections['k']['first']      = ($this->_sections['k']['iteration'] == 1);
$this->_sections['k']['last']       = ($this->_sections['k']['iteration'] == $this->_sections['k']['total']);
?>
        <tr>
            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="codigo_barras<?php echo $this->_sections['k']['index']; ?>
" onblur="validarItem(this, 'nombre<?php echo $this->_sections['k']['index']; ?>
')" size="30" id="codigo_barras<?php echo $this->_sections['k']['index']; ?>
" >
            </td>
            <td><div id="nombre<?php echo $this->_sections['k']['index']; ?>
"></div></td>
            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="precio<?php echo $this->_sections['k']['index']; ?>
" size="10" id="precio<?php echo $this->_sections['k']['index']; ?>
" value="0.00">
            </td>

            <td style="padding-top:2px; padding-bottom: 2px;">
                <select class="form-text" type="text" name="estado<?php echo $this->_sections['k']['index']; ?>
" >
                    <option value="">Todos</option>                               
                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_estados'],'output' => $this->_tpl_vars['option_output_estados']), $this);?>

                </select>
            </td>

            <td>
                <select class="form-text" type="text" name="motivo<?php echo $this->_sections['k']['index']; ?>
" id="motivo<?php echo $this->_sections['k']['index']; ?>
" >
                    <option value="">--Seleccione el motivo--</option>
                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_motivo'],'output' => $this->_tpl_vars['option_output_motivo']), $this);?>

                </select>
            </td>
            <td>
                <input type="text" name="observacion<?php echo $this->_sections['k']['index']; ?>
" id="observacion<?php echo $this->_sections['k']['index']; ?>
" class="form-text" size="25" >
            </td>
        </tr>

        <?php endfor; endif; ?>
        <?php endif; ?>
    </tbody>
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