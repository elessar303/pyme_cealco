<?php /* Smarty version 2.6.21, created on 2016-11-28 09:59:07
         compiled from flota_modelo_editar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'flota_modelo_editar.tpl', 167, false),)), $this); ?>
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

                //funcion para cargar los puntos 
                    $("#marca").change(function() {
                        estados = $("#marca").val();
                        $.ajax({
                            type: \'GET\',
                            data: \'opt=getTransporteModelo&\'+\'marca=\'+estados,
                            url: \'../../libs/php/ajax/ajax.php\',
                            beforeSend: function() 
                            {
                                $("#modelo").find("option").remove();
                                $("#modelo").append("<option value=\'\'>Cargando..</option>");
                            },
                            success: function(data) 
                            {
                                $("#modelo").find("option").remove();
                                this.vcampos = eval(data);
                                for (i = 0; i < this.vcampos.length; i++) 
                                {
                                    $("#modelo").append("<option value=\'" + this.vcampos[i].id+ "\'>" + this.vcampos[i].descripcion_modelo + "</option>");
                                }
                            }
                        }); 
                    });
                
               
                $("#descripcion").blur(function()
                {
                        valor = $(this).val();
                        if(valor!=\'\')
                        {
                            $.ajax({
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=ValidarDescripcionModelo&v1="+valor,
                                beforeSend: function()
                                {
                                    $("#notificacionDescripcion").html(MensajeEspera("<b>Veficando Marca..</b>"));
                                },
                                success: function(data)
                                {
                                    resultado = data
                                    if(resultado=="-1")
                                    {
                                        $("#descripcion").val("").focus();
                                        $("#notificacionDescripcion").html("<img align=\\"absmiddle\\" src=\\"../../../includes/imagenes/ico_note.gif\\"><span style=\\"color:red;\\"> <b>Disculpe, Modelo Ya Existe.</b></span>");
                                        return false;
                                    }
                                    if(resultado=="1")
                                    {//cod de item disponble
                                        $("#notificacionDescripcion").html("<img align=\\"absmiddle\\" src=\\"../../../includes/imagenes/ok.gif\\"><span style=\\"color:#0c880c;\\"><b> Modelo Disponible</b></span>");
                                        return true;
                                    }
                                }
                            });
                        }
                    });
                });

                function validacion()
                {
                    valor = $(\'#descripcion\').val();
                        if(valor!=\'\' || $(\'#marca\').val()=="")
                        {
                            $.ajax({
                                type: "GET",
                                url:  "../../libs/php/ajax/ajax.php",
                                data: "opt=ValidarDescripcionModelo&v1="+valor,
                                beforeSend: function()
                                {
                                    $("#notificacionDescripcion").html(MensajeEspera("<b>Veficando Marca..</b>"));
                                },
                                success: function(data)
                                {
                                    resultado = data
                                    if(resultado=="-1")
                                    {
                                        $("#descripcion").val("").focus();
                                        $("#notificacionDescripcion").html("<img align=\\"absmiddle\\" src=\\"../../../includes/imagenes/ico_note.gif\\"><span style=\\"color:red;\\"> <b>Disculpe, Marca Ya Existe.</b></span>");
                                        return false;
                                    }
                                    if(resultado=="1")
                                    {//cod de item disponble
                                        $("#notificacionDescripcion").html("<img align=\\"absmiddle\\" src=\\"../../../includes/imagenes/ok.gif\\"><span style=\\"color:#0c880c;\\"><b> Marca Disponible</b></span>");
                                        return true;
                                    }
                                }
                            });
                        }
                        else
                        {   
                            alert(\'Error Campo Vacio\');
                            $("#descripcion").val("").focus();
                            return false;
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
" name="formulario" action="" method="post" onsubmit="return validacion()">
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
                                    <td colspan="3" class="label">Marca **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                        <select name="marca" id="marca" class="form-text" style="width:205px" class="form-text">
                                        <option value="">Seleccione...</option>
                                            <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_marca'],'output' => $this->_tpl_vars['option_output_marca'],'selected' => $this->_tpl_vars['marca']), $this);?>

                                        </select>
                                    </td>
                                </tr>


                                <tr>
                                    <td colspan="3" class="label">Descripcion **</td>
                                    <td style="padding-top:2px; padding-bottom: 2px;">
                                    <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['datos'][0]['id']; ?>
"/>
                                        <input type="text" name="descripcion" placeholder="Descripcion" size="60" id="descripcion" class="form-text" value="<?php echo $this->_tpl_vars['datos'][0]['descripcion_modelo']; ?>
"/>
                                        <div id="notificacionDescripcion"></div>
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