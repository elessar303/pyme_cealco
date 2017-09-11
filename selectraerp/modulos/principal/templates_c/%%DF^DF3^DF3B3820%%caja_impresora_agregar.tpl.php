<?php /* Smarty version 2.6.21, created on 2017-05-19 18:16:57
         compiled from caja_impresora_agregar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'caja_impresora_agregar.tpl', 110, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <?php $this->assign('name_form', 'usuarios_nuevo'); ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/header_form.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <!--Para estilo JQuery en botones-->
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../libs/js/md5_crypt.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
       
        <?php echo '
            <script type="text/javascript">//<![CDATA[
               
                $(document).ready(function(){
                    $("#nombre_caja").focus();
                    $("input[name=\'cancelar\']").button();//Coloca estilo JQuery
                    $("input[name=\'aceptar\']").button().click(function(){
                        var usu = document.formulario.nombre_caja.value;
                        var nom = document.formulario.serial.value;
                        
                        //if($("#usuario").val()==="" || $("#nombreyapellido").val()==="" || $("#clave1").val()==="" || $("#clave2").val()===""){
                        if(usu==""||nom==""){
                            //alert("Debe llenar todos los campos");
                            Ext.Msg.alert("Alerta","Debe llenar todos los campos");
                            return false;
                        }
                    });
                    $("#nombre_caja").blur(function(){
                        valor = $(this).val();
                        if(valor!=\'\'){
                            $.ajax({
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=Validarnombre_caja&v1="+valor,
                                beforeSend: function(){
                                    $("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando Nombre de Caja..</b>"));
                                },
                                success: function(data){
                                    resultado = data
                                    if(resultado==-1){
                                        $("#nombre_caja").val("").focus();
                                        $("#notificacionVUsuario").html("<img align=\\"absmiddle\\" src=\\"../../../includes/imagenes/ico_note.gif\\"><span style=\\"color:red;\\"> <b>Disculpe, esta Caja ya existe.</b></span>");
                                    }
                                    if(resultado==1){//cod de item disponble
                                        $("#notificacionVUsuario").html("<img align=\\"absmiddle\\" src=\\"../../../includes/imagenes/ok.gif\\"><span style=\\"color:#0c880c;\\"><b> Nombre de Caja Disponible</b></span>");
                                    }
                                }
                            });
                        }
                    });


            $("#serial").blur(function(){
                        valor = $(this).val();
                        if(valor!=\'\'){
                            $.ajax({
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=Validarserial_caja&v1="+valor,
                                beforeSend: function(){
                                    $("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando serial ..</b>"));
                                },
                                success: function(data){
                                    resultado = data
                                    if(resultado==-1){
                                        $("#serial").val("").focus();
                                        $("#notificacionVUsuario1").html("<img align=\\"absmiddle\\" src=\\"../../../includes/imagenes/ico_note.gif\\"><span style=\\"color:red;\\"> <b>Disculpe, este serial ya existe.</b></span>");
                                    }
                                    if(resultado==1){//cod de item disponble
                                        $("#notificacionVUsuario1").html("<img align=\\"absmiddle\\" src=\\"../../../includes/imagenes/ok.gif\\"><span style=\\"color:#0c880c;\\"><b> Serial Disponible</b></span>");
                                    }
                                }
                            });
                        }
                    });





                });/*end of document.ready*/
            //]]>
            </script>
        '; ?>

    </head>
    <body>
        <form id="form-<?php echo $this->_tpl_vars['name_form']; ?>
" name="formulario" action="" method="post">
            <div id="datosGral" class="x-hide-display">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
             
             
                        <table style="width: 100%">
                            <thead>
                                <tr>
                                    <th colspan="4" class="tb-head">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <tr>
                                <td colspan="3" class="label">Nombre Caja **</td>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                        <?php if ($this->_tpl_vars['tipo_venta'] == '1'): ?>
                        <select name="nombre_caja" id="nombre_caja" style="width:200px;" class="form-text">
                        <option value="0">Seleccione Caja...</option>                               
                        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cajas'],'output' => $this->_tpl_vars['option_output_cajas']), $this);?>

                        </select>
                        <?php else: ?>
                         <input type="text" name="nombre_caja" placeholder="Nombre De Impresora Fiscal" size="60" id="nombre_caja" class="form-text"/>
                         <?php endif; ?>

                    </td>
                    </tr>


                                
                                <tr>
                                    <td colspan="3" class="label">Serial **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="serial" placeholder="Serial De Impresora Fiscal" size="60" id="serial" class="form-text"/>
                                        <div id="notificacionVUsuario1"></div>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">IP **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="ip" placeholder="IP De La Caja" size="60" id="ip" class="form-text"/>
                                       
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">Tipo CAJA **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="tipo_caja" id="tipo_caja" style="width:200px;" class="form-text">
                                        <option value="0">Tipo de Caja...</option>                               
                                        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cajas_tipo'],'output' => $this->_tpl_vars['option_output_cajas_tipo']), $this);?>

                                        </select>
                                      
                                    </td>
                                </tr>







                                                       </table>
                   
                <table style="width:100%;">
                    <tbody>
                        <tr class="tb-tit" style="text-align: right;">
                            <td style="padding-top:2px; padding-right: 2px;">
                                <input type="submit" name="aceptar" id="aceptar" value="Guardar" />
                                <input type="button" name="cancelar" id="cancelar" value="Cancelar" onclick="javascript:window.location='?opt_menu=<?php echo $_GET['opt_menu']; ?>
&amp;opt_seccion=<?php echo $_GET['opt_seccion']; ?>
';" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>