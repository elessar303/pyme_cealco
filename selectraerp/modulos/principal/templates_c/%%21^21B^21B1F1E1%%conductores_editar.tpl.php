<?php /* Smarty version 2.6.21, created on 2017-02-21 15:38:29
         compiled from conductores_editar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'conductores_editar.tpl', 161, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/development-bundle/ui/i18n/jquery.ui.datepicker-es.js"></script>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
        

        <?php $this->assign('name_form', 'vendedor_nuevo'); ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/header_form.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php echo '
            <script type="text/javascript">//<![CDATA[
                $(document).ready(function(){

                $("#cedula").blur(function()
                {
                        valor = $(this).val();
                        if(valor!=\'\')
                        {
                            $.ajax({
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=ValidarCedulaConductor&v1="+valor,
                                beforeSend: function()
                                {
                                    $("#notificacionVCodCliente").html(MensajeEspera("<b>Veficando Nro de Documento..</b>"));
                                },
                                success: function(data)
                                {
                                    resultado = data
                                    if(resultado=="-1")
                                    {
                                        $("#cedula").val("").focus();
                                        $("#notificacionVUsuario").html("<img align=\\"absmiddle\\" src=\\"../../../includes/imagenes/ico_note.gif\\"><span style=\\"color:red;\\"> <b>Disculpe, este Nro. de Cedula Ya Existe.</b></span>");
                                    }
                                    if(resultado=="1")
                                    {//cod de item disponble
                                        $("#notificacionVUsuario").html("<img align=\\"absmiddle\\" src=\\"../../../includes/imagenes/ok.gif\\"><span style=\\"color:#0c880c;\\"><b> Nro. de Cedula Disponible</b></span>");
                                    }
                                }
                            });
                        }
                    });


                    valor=$("#posee_vehiculo").val();
                    if(valor==1)
                    {
                        document.getElementById(\'lista\').style.display=\'block\';
                        document.getElementById(\'lista1\').style.display=\'block\';
                    }
                    $("input[name=\'cancelar\']").button();//Coloca estilo JQuery
                    $("input[name=\'aceptar\']").button().click(function(){
                        if($("#nombres").val()==\'\' || $("#apellidos").val()==\'\' || $("#cedula").val()==\'\' || $("#telefono").val()==\'\' || $("#flota_asignada_conductor").val()==\'\')
                        {
                            Ext.Msg.alert("Alerta","Debe llenar los campos obligatorios");
                            $("#nombre").focus();
                            return false;
                        }
                    });
                    
                });

                function mostrarLista()
                {
                    valor=$("#posee_vehiculo").val();
                    if(valor==0)
                    {
                        document.getElementById(\'lista\').style.display=\'none\';
                        document.getElementById(\'lista1\').style.display=\'none\';
                    }
                    else
                    {
                        document.getElementById(\'lista\').style.display=\'block\';
                        document.getElementById(\'lista1\').style.display=\'block\';
                    }
                }
                //Panel
                Ext.ns(\'Selectra.pyme.vendedores\');
                Selectra.pyme.vendedores.TabPanelVendedores = {
                    init: function(){
                        var panelDatos = new Ext.Panel({
                            contentEl:\'div_tab1\',
                            title: \'Datos Generales\'
                        });
                        this.tabs = new Ext.TabPanel({
                            renderTo:\'contenedorTAB\',
                            activeTab:0,
                            plain:true,
                            defaults:{
                                autoHeight: true
                            },
                            items:[
                                panelDatos
                            ]
                        });
                    }
                }
                Ext.onReady(Selectra.pyme.vendedores.TabPanelVendedores.init, Selectra.pyme.vendedores.TabPanelVendedores);
            //]]>
            </script>
        '; ?>

    </head>
    <body>
        <form id="form-<?php echo $this->_tpl_vars['name_form']; ?>
" name="formulario" action="" method="post">
            <div id="datosGral">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar_boton.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <input type="hidden" name="cod_empresa" value="<?php echo $this->_tpl_vars['DatosGenerales'][0]['cod_empresa']; ?>
"/>
                <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
                <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
                <input type="hidden" name="opt_subseccion" value="<?php echo $_GET['opt_subseccion']; ?>
"/>
                <div id="contenedorTAB">
                    <div id="div_tab1" class="x-hide-display">
                        <table style="width:100%">
                            <thead>
                                <tr>
                                    <th colspan="4" class="tb-head" style="text-align:center;">
                                        COMPLETLE LOS CAMPOS MARCADOS CON&nbsp;** OBLIGATORIAMENTE
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3" class="label">Cedula **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="cedula" placeholder="Cedula del Conductor" size="60" id="cedula" class="form-text" value="<?php echo $this->_tpl_vars['datos'][0]['cedula']; ?>
"/>
                                        <div id="notificacionVUsuario"></div>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">Nombres **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="nombres" placeholder="Nombre Del Conductor" size="60" id="nombres" class="form-text" value="<?php echo $this->_tpl_vars['datos'][0]['nombres']; ?>
"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">Apellidos **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="apellidos" placeholder="Apellido Del Conductor" size="60" id="apellidos" class="form-text" value="<?php echo $this->_tpl_vars['datos'][0]['apellidos']; ?>
"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">Telefonos **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <input type="text" name="telefono" placeholder="Telefonos Del Conductor" size="60" id="Telefono" class="form-text" value="<?php echo $this->_tpl_vars['datos'][0]['telefono']; ?>
"/>
                                        <input type="hidden" name="id" size="60" id="id" class="form-text" value="<?php echo $this->_tpl_vars['id']; ?>
"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">Flota Asiganada **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="flota_asignada_conductor" id="flota_asignada_conductor" class="form-text" style="width:205px" class="form-text">
                                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_estado'],'output' => $this->_tpl_vars['option_output_estado'],'selected' => $this->_tpl_vars['estado']), $this);?>

                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="label">Posee Vehiculo **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="posee_vehiculo" id="posee_vehiculo" class="form-text" style="width:205px" class="form-text" onchange="mostrarLista();">
                                            <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_pvehiculo'],'output' => $this->_tpl_vars['option_output_pvehiculo'],'selected' => $this->_tpl_vars['respuesta']), $this);?>

                                        </select>
                                    </td>
                                </tr>
                                
                                        <tr>
                                        <td colspan="3" class="label">
                                            <div id='lista' style="display:none;">
                                                Seleccione Vehiculo **
                                            </div>
                                        </td>
                                        <td style="padding-top:2px; padding-bottom: 2px;">
                                            <div id='lista1' style="display:none;">
                                                <select name="vehiculos" id="vehiculos" class="form-text" style="width:205px" class="form-text" >
                                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_flota'],'output' => $this->_tpl_vars['option_output_flota'],'selected' => $this->_tpl_vars['id_camion']), $this);?>

                                                </select>
                                            </div>
                                        </td>

                                    </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <table style="width: 100%;">
                    <tbody>
                        <tr class="tb-tit" style="text-align: right;">
                            <td style="padding-top:2px; padding-right: 2px;">
                                <input type="submit" id="aceptar" name="aceptar" value="Guardar"/>
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