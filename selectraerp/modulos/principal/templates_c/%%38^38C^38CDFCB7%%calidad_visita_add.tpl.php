<?php /* Smarty version 2.6.21, created on 2017-09-18 18:22:28
         compiled from calidad_visita_add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'calidad_visita_add.tpl', 151, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css"/>
        <?php echo '
            <script type="text/javascript">
                //<![CDATA[
    $(document).ready(function(){
        
        //funcion para cargar los puntos 
        $("#estado").change(function() {
        //           function cargarUbicaciones() {    
        
        idAlmacen=$("#estado").val();  
        $.ajax({
            type: \'POST\',
            data: \'opt=cargaUbicacion&idAlmacen=\'+idAlmacen,
            url: \'../../libs/php/ajax/ajax.php\',
            beforeSend: function() {
                $("#puntodeventa").find("option").remove();
                $("#puntodeventa").append("<option value=\'\'>Cargando..</option>");
            },
            success: function(data) {
                $("#puntodeventa").find("option").remove();
                this.vcampos = eval(data);
                    $("#puntodeventa").append("<option value=\'\'>Seleccione..</option>");
                for (i = 0; i <= this.vcampos.length; i++) {
                    $("#puntodeventa").append("<option value=\'" + this.vcampos[i].id + "\'>" + this.vcampos[i].descripcion + "</option>");
                            }
                        }
                    });
                });
        

       
        });
        //]]>


    function agregarObservacion()
    {
        cantidad=$("#cantidad_ob").val();
        observaciones=[];
        recomendaciones="";
        
        
        parametros=
        {
            "opt": "calidadObservacion",
            "cantidad" : cantidad,
            "observaciones"   : observaciones,
            "recomendaciones"   : recomendaciones,
        };

        $.ajax({
                type: "POST",
                url: "../../libs/php/ajax/ajax.php",
                data: parametros,
                dataType: "html",
                asynchronous: false,
                beforeSend: function() 
                {
                    $("#pantalla_resultado").empty();
                    $("#pantalla_resultado").html(\'<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>\');
                },
                error: function()
                {
                    alert("error petición ajax");
                },
                success: function(data)
                {
                    $("#pantalla_resultado").html(data);
                }
                });
        }
    
        function validacion() 
        {
            estado = document.getElementById("estado").value;
            puntodeventa = document.getElementById("puntodeventa").value;
            tipo_visita = document.getElementById("tipo_visita").value;
            usuario = document.getElementById("usuario").value;
            fecha_visita = document.getElementById("fecha_visita").value;
            observacion = document.getElementById("observacion").value;

            if( (estado == null || estado==\'\') || (puntodeventa == null || puntodeventa == \'\') || (tipo_visita == null || tipo_visita == \'\') || (usuario == null  || usuario == \'\') || (fecha_visita == null || fecha_visita == \'\') || (observacion == null || observacion == \'\')) 
            {
                    alert ("Error, Debe Rellenar Todos Los Campos");
                    return false;
            }
            else
            {
            return true;
            }
        }
            </script>
        '; ?>

        <script src="../../libs/js/validar_rif.js" type="text/javascript"></script>
    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="" onsubmit="return validacion()">
            <input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
">
            <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
">
            <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
">
            <input type="hidden" name="opt_subseccion" value="<?php echo $_GET['opt_subseccion']; ?>
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
            <table   width="100%" border="0" align="center">
                <tr>
                    <td colspan="5" class="tb-head" align="center" >
                        LOS CAMPOS CON ** SON OBLIGATORIOS&nbsp;
                    </td>
                </tr>
                <tr>
                    <td>
                        <br>
                    </td>
                </tr>
                <tr>    
                    <td colspan="3" class="label">
                            Almacen De Visita**
                    </td>
                            <!--ESTADOS-->
                    <td> 
                        <select name="estado" id="estado" class="form-text" style="width:300px;">
                            <option value="">Seleccione...</option>
                                    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_id_estado'],'output' => $this->_tpl_vars['option_values_nombre_estado'],'selected' => $this->_tpl_vars['estado']), $this);?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="label">
                        Ubicación de Visita**
                    </td>
                             <!-- PUNTOS -->
                    <td>
                        <select name="puntodeventa" id="puntodeventa" class="form-text" style="width:300px;">
                            <option value="">Seleccione...</option>                               
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_punto'],'output' => $this->_tpl_vars['option_output_punto'],'selected' => $this->_tpl_vars['puntodeventa']), $this);?>

                                
                        </select>
                    </td>                     
                </tr>
                

                <tr>
                   
                    <td colspan="3"  class="label" >
                        Tipo Visita**
                    </td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <select name="tipo_visita" id="tipo_visita" class="form-text" style="width:300px;">
                            <option value="">Seleccione...</option>                               
                                <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_tipo'],'output' => $this->_tpl_vars['option_output_tipo2']), $this);?>

                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="3" class="label" >
                        Usuario Creación **
                    </td>
                    <td style="padding-top:2px; padding-bottom: 2px;">
                        <input type="text" name="usuario" size="60" id="usuario" class="form-text" style="width:300px;"  value="<?php echo $this->_tpl_vars['nombre_usuario']; ?>
" readonly>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="3" class="label" >
                        Fecha de Visita **
                    </td>
                    <td>
                        <input type="text" name="fecha_visita" class="form-text" style="width:300px;" value="" id="fecha_visita" size="15" maxlength="12"  placeholder="aaaa-mm-dd" />
                        
                        <?php echo '
                            <script type="text/javascript">
                                //<![CDATA[
                                var cal = Calendar.setup({
                                    onSelect: function(cal) { cal.hide() }
                                });
                                cal.manageFields("fecha_visita", "fecha_visita", "%Y-%m-%d");
                                //]]>
                            </script>
                        '; ?>

                    </td>
                </tr>
                <tr>
                    <td style="padding-top:2px; padding-bottom: 2px;" colspan="3">
                        <table class="btn_bg" onclick="agregarObservacion()" align="right" border="0" height="21" style=" margin-right: -200px;" >
                            <tr style="border-width: 0px; cursor: pointer;">
                                <td>
                                    <img src="../../../includes/imagenes/bt_left.gif" style="border-width: 0px; width: 4px; height: 21px;" />
                                </td>
                                <td>
                                    <img src="../../../includes/imagenes/add.gif" width="16" height="16" />
                                </td>
                                <td style="padding: 0px 10px;">
                                    Agregar Observación
                                </td>
                                <td>
                                    <img src="../../../includes/imagenes/bt_right.gif" style="border-width: 0px; width: 4px; height: 21px;" />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr >
                    <td colspan="3">
                    </td>
                    <td align="center">
                        <div id="pantalla_resultado">
                            <input type="hidden" name="cantidad_ob" id="cantidad_ob"  value="1"/>
                        </div>
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
    </body>
</html>