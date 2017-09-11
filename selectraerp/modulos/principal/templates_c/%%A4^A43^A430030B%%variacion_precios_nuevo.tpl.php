<?php /* Smarty version 2.6.21, created on 2017-08-28 16:17:08
         compiled from variacion_precios_nuevo.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'variacion_precios_nuevo.tpl', 278, false),)), $this); ?>
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
        <link type="text/css" media="screen" rel="stylesheet" href="../../libs/css/nueva_factura.css" />
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
          URL = URL.replace(\'add\',\'add&boton_agregar=\'+valor); 

    window.location = URL;
}

function buscarItem(num){

        //var teclaTabMasP  = 13;
        //var codeCurrent = ev.keyCode;
        //window.alert(num);
        //window.alert(\'motivo\'+num);
        //var value = $(this).val();
        
        //if(teclaTabMasP == codeCurrent){
            //if(_.str.isBlank(value)) {
    pBuscaItem.main.mostrarWin(num);
    return false;
            //}
            //$.filtrarArticulo(value, "filtroItemByRCCB");
            //return false;
        // }

}


$(document).ready(function(){

    $("#aceptar").click(function() {

        /*Seleccion obligatoria del motivo de la variacion
        hz*/
        if(
            $("#motivo").val()=="" ||
            $("#motivo").val()=="0" 
          ){
            Ext.Msg.alert("Alerta","Debe Especificar el Estado.");
            return false;
        };
    });
    /*$("input[name=\'buscar\']").click(function(){

        //var teclaTabMasP  = 13;
        //var codeCurrent = ev.keyCode;
        var value = $(this).val();
        window.alert(value);
        //if(teclaTabMasP == codeCurrent){
            //if(_.str.isBlank(value)) {
                pBuscaItem.main.mostrarWin();
                return false;
            //}
            //$.filtrarArticulo(value, "filtroItemByRCCB");
            //return false;
        // }
    });
    
    /*$("#codigo_barras2").change(function(event) {

        codbarra=$("#codigo_barras2").val();
        
        //cantidad2=$("#costo_iva").val();
        var n = cantidad;
        //var n2 = cantidad2;
        //var mg = n*n2;

        //$("#margen_ganancia").val(mg);

        //var precioventa = parseFloat(n2)+parseFloat(mg);
        $("#codigo_barras").val(n);

    });*


        /*$("#restringido_grupo").click(function() {          
           if($("#restringido_grupo").is(":checked")){
                $("#restringido").show();
           }else{
                $("#cantidad_grupo").val("");
                $("#dias_grupo").val("");
                $("#restringido").hide();
           }
        });*
        /*$("#rubro").click(function() {          
           if($("#rubro").val()==2){
                $("#restringido2").show();
           }else{
                $("#restringido2").hide();
           }
        });*/

});

/*window.onload = function () {
    //document.formularioContacto.nombre.focus();
    document.formulario.addEventListener(\'submit\', validarFormulario);
}
 
function validarFormulario(evObject) {
evObject.preventDefault();
var todoCorrecto = true;
var formulario = document.formulario;
for (var i=0; i<formulario.length; i++) {
                if(formulario[i].motivo{$smarty.section.i.index}.value ==\'\') {
                               if (formulario[i].value == null || formulario[i].value.length == 0 || /^\\s*$/.test(formulario[i].value)){
                               alert (formulario[i].name+ \' no puede estar vacío o contener sólo espacios en blanco\');
                               todoCorrecto=false;
                               }
                }
                }
if (todoCorrecto ==true) {formulario.submit();}
}*/

                
                    function validar() {
                        //obteniendo el valor que se puso en campo text del formulario
                        miCampoTexto = document.getElementById("motivo{$smarty.section.i.index}").value;
                        alert(miCampoTexto);
                        //la condición
                        if (miCampoTexto.length == 0) {
                            Ext.Msg.alert("Alerta","Debe Especificar el Motivo de la variacion.")
                            return false;
                        }
                        return true;
                    }
                


</script>
'; ?>

    <script type="text/javascript" src="../../libs/js/underscore.js"></script>
    <script type="text/javascript" src="../../libs/js/underscore.string.js"></script>
    <script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida2.js"></script>
</head>

<!-- <form name="formulario" id="formulario" method="POST" action="" onsubmit="return validar()"> -->
<form name="formulario" id="formulario" method="POST" action="" >
<input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
">
<input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
">
<input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
">
<input type="hidden" name="opt_subseccion" value="<?php echo $_GET['opt_subseccion']; ?>
">
<input type="hidden" name="num_campos" value="50">

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
'" name="find" border="0" cellpadding="0" cellspacing="0">
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
                <input id="numero_veces" value="<?php echo $this->_tpl_vars['numero_veces']; ?>
"  name="numero" hidden="hidden" />
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
            <th style="width:200px;">Nombre</th>
            <th>Precio Sin IVA</th>
            <th>Estado</th>
            <th>Motivo de la variaci&oacute;n</th>
            <th>Observacion</th>
        </tr>
    </thead>

    <tbody>
        <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['start'] = (int)0;
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['numero_veces']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
if ($this->_sections['i']['start'] < 0)
    $this->_sections['i']['start'] = max($this->_sections['i']['step'] > 0 ? 0 : -1, $this->_sections['i']['loop'] + $this->_sections['i']['start']);
else
    $this->_sections['i']['start'] = min($this->_sections['i']['start'], $this->_sections['i']['step'] > 0 ? $this->_sections['i']['loop'] : $this->_sections['i']['loop']-1);
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = min(ceil(($this->_sections['i']['step'] > 0 ? $this->_sections['i']['loop'] - $this->_sections['i']['start'] : $this->_sections['i']['start']+1)/abs($this->_sections['i']['step'])), $this->_sections['i']['max']);
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>

        <tr>
            <td style="padding-top:2px; padding-bottom: 2px;">
                
                <!--<input type="text" name="codigo_barras<?php echo $this->_sections['i']['index']; ?>
" id="codigo_barras<?php echo $this->_sections['i']['index']; ?>
" onblur="validarItem(this, 'nombre<?php echo $this->_sections['i']['index']; ?>
')" class="form-text" size="30" style="float: left;">-->
                <input type="text" name="codigo_barras<?php echo $this->_sections['i']['index']; ?>
" id="codigo_barras<?php echo $this->_sections['i']['index']; ?>
" style="float: left;" onblur="validarItem(this, 'nombre<?php echo $this->_sections['i']['index']; ?>
')" class="form-text" />
                <!--<input type="text" name="codigo_barras" id="codigo_barras" style="float: left;" class="form-text" onblur="validarItem(this, 'nombre<?php echo $this->_sections['i']['index']; ?>
')" />-->

                <input type="button" id="buscar<?php echo $this->_sections['i']['index']; ?>
" name="buscar" value="Buscar" style="float: left;" onClick="buscarItem(<?php echo $this->_sections['i']['index']; ?>
)" />
            </td>
            <td><div id="nombre<?php echo $this->_sections['i']['index']; ?>
"></div></td>
            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="precio<?php echo $this->_sections['i']['index']; ?>
" size="10" id="precio<?php echo $this->_sections['i']['index']; ?>
" value="0.00">
            </td>

            <td style="padding-top:2px; padding-bottom: 2px;">
                <select class="form-text" type="text" name="estado<?php echo $this->_sections['i']['index']; ?>
" >
                    <!--<option value="0">Todos</option>-->
                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_estados'],'output' => $this->_tpl_vars['option_output_estados']), $this);?>

                </select>
            </td>

            <td>
                <select class="form-text" type="text" name="motivo<?php echo $this->_sections['i']['index']; ?>
" id="motivo<?php echo $this->_sections['i']['index']; ?>
" >
                    <option value="">--Seleccione el motivo--</option>
                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_motivo'],'output' => $this->_tpl_vars['option_output_motivo']), $this);?>

                </select>
            </td>
            <td>
                <input type="text" name="observacion<?php echo $this->_sections['i']['index']; ?>
" id="observacion<?php echo $this->_sections['i']['index']; ?>
" class="form-text" size="25" >
            </td>
        </tr>

        <?php endfor; endif; ?>


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
</html>
