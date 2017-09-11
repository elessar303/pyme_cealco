<?php /* Smarty version 2.6.21, created on 2016-08-30 14:51:32
         compiled from acta_inventario_nuevo.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'acta_inventario_nuevo.tpl', 177, false),array('function', 'html_options', 'acta_inventario_nuevo.tpl', 190, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Humberto Zapata" />
        <title></title>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/inclusiones_reportes.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php echo '

         <script language="JavaScript" type="text/JavaScript">
        function recargar(boton){

                toma1 = $("input[name=\'toma1[]\']").map(function(){return $(this).val();}).get();$("#toma1").val();
                toma2 = $("input[name=\'toma2[]\']").map(function(){return $(this).val();}).get();$("#toma2").val();
                toma3 = $("input[name=\'tomadef[]\']").map(function(){return $(this).val();}).get();$("#toma3").val();
                items = $("input[name=\'codigo_barras[]\']").map(function(){return $(this).val();}).get();$("#codigo_barras").val();
                
                id_mov = $("#id_mov").val();
                cantidad_items = $("#cantidad_items").val();

                    parametros={
                     "opt": "toma_fisica_update",
                     "toma1" : toma1,
                     "toma2" : toma2,
                     "toma3" : toma3,
                     "items" :items,
                     "id_mov" : id_mov,
                     "boton" : boton,
                     "cantidad_items" : cantidad_items

                     };

                    $.ajax({
                     type: "POST",
                     url: "../../libs/php/ajax/ajax.php",
                     data: parametros,
                     dataType: "html",
                     asynchronous: false,
                     beforeSend: function() {
                        $("#datos_carga").empty();
                                $("#datos_carga").html(\'<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>\');
                     },
                     error: function(){
                     alert("error petición ajax");
                     },
                     success: function(data){
                        
                        $("#datos_carga").html(data);
                        falta_campo = $("#falta_campo").val();
                        if(falta_campo=="1"){
                            alert("Error, Complete Todos Los Datos (Solo Numeros)");
                        }
                    
                   
                     }
                     });
                }
        </script>
        '; ?>

        <?php echo '
        <style type="text/css">
            .imgajax{               
                position: absolute;
                top: 50%;
                left: 50%;
                margin-top: 100px; 
            }
            .cargando{
                margin-top: 10px;
                font-size: 18px;
                text-align: center;
            }

        </style>
            <script type="text/javascript">//<![CDATA[
            $(document).ready(function(){
               
                $("#fecha").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths:true,
                    selectOtherMonths: true,
                   
                    dateFormat: "dd-mm-yy",
                    timeFormat: \'HH:mm:ss\',
                    showOn: "both",//button,
                    onClose: function( selectedDate ) {
                   
                        $( "#fecha2" ).datetimepicker("option", "minDate", selectedDate);
                    }
                });
                $("#fecha2").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths:true,
                    selectOtherMonths: true,
                   
                    dateFormat: "dd-mm-yy",
                    timeFormat: \'HH:mm:ss\',
                    showOn: "both",//button,
                    onClose: function( selectedDate ) {
                        $( "#fecha" ).datetimepicker( "option", "maxDate", selectedDate );
                    }
                });

                
                $("#almacen_entrada").change(function(){
                cargarUbicaciones();
                });


                function cargarUbicaciones() {    
                idAlmacen=$("#almacen_entrada").val();     
                if(idAlmacen!=0){
                $.ajax({
                    type: \'POST\',
                    data: \'opt=cargaUbicacion&idAlmacen=\'+idAlmacen,
                    url: \'../../libs/php/ajax/ajax.php\',
                    beforeSend: function() {
                        $("#ubicacion").find("option").remove();
                        $("#ubicacion").append("<option value=\'\'>Cargando..</option>");
                    },
                    success: function(data) {
                        $("#ubicacion").find("option").remove();
                        this.vcampos = eval(data);
                         $("#ubicacion").append("<option value=\'\'>Seleccione..</option>");
                        for (i = 0; i <= this.vcampos.length; i++) {
                            $("#ubicacion").append("<option value=\'"+this.vcampos[i].id+"\'>" + this.vcampos[i].descripcion + "</option>");
                        }
                    }
                });
                }//fin el if
                else{
                     $("#ubicacion").find("option").remove();
                     $("#ubicacion").append("<option value=\'\'>Seleccione..</option>");
                    }
                }




                });

               
        </script>
        '; ?>


        <script type="text/javascript" src="../../libs/js/underscore.js"></script>
        <script type="text/javascript" src="../../libs/js/underscore.string.js"></script>
        <script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida.js"></script>

    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="">
            <div id="datosGral" class="x-hide-display">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
"/>
                <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
                <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
                <input type="hidden" name="cant_fechas" id="cant_fechas" value="2"/>
                <input type="hidden" name="ordenar_por" id="ordenar_por" value="1"/>
                <input type="hidden" name="tiene_filtro" id="tiene_filtro" value="1"/>

                
                    <table style="width:100%; background-color:white;">
                        <thead>
                            <tr>
                                <th colspan="8" class="tb-head" style="text-align:center;">
                                    LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="label">Fecha del Acta:</td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                  <input type="text" name="fecha_apertura" id="fecha_apertura" size="10" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
' readonly class="form-text" />
                                </td>
                                    <!--button id="boton_fecha">...</button-->
                                    
                                <td class="label">Establecimiento:</td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                <input type="text" name="establecimiento" id="establecimiento" class="form-text" value="<?php echo $this->_tpl_vars['paramentros'][0]['nombre_empresa']; ?>
"/>
                                </td>
                                
                                <td class="label">Almacen</td>
                                <td  style="padding-top:2px; padding-bottom: 2px;">
                                <select  name="almacen" id="almacen" class="form-text">
                                <option value="0">Seleccione</option>              
                                <?php echo smarty_function_html_options(array('output' => $this->_tpl_vars['option_output_almacen'],'values' => $this->_tpl_vars['option_values_almacen'],'selected' => $this->_tpl_vars['almacen']), $this);?>

                                </select>
                                </td>

                                <td class="label">Ubicacion:</td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                <select name="ubicacion" id="ubicacion" style="width:200px;" class="form-text">
                                        <option value="0" disabled="disabled" selected="selected">Seleccione</option>
                                        <?php echo smarty_function_html_options(array('output' => $this->_tpl_vars['option_output_producto'],'values' => $this->_tpl_vars['option_values_producto'],'selected' => $this->_tpl_vars['ubicacion']), $this);?>

                                    </select>
                                </td>
                            </tr>                         
                            <tr class="tb-head">
                                <td colspan="8">
                                    <input type="submit" id="enviarajax" name="aceptar" value="Mostrar" />
                                    <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
';" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                
            </div>
        </form>
<?php if ($this->_tpl_vars['aceptar'] == 'Mostrar'): ?>
        <div id="datos_carga"> 
<table   width="100%" border="0" >
    <thead>
        <tr class="tb-head">
            <th >Nombres y Apellidos</th>
            <th >Cedula de Indentidad</th>
            <th>Cargo</th>
            <th>Procedencia</th>
        </tr>
    </thead>

    <tbody>

        
        
        <form name="formulario2" id="formulario2" method="post">
        <input type="hidden" name="tiene_filtro" id="tiene_filtro" value="1"/>
        <input type="hidden" name="ubicacion" id="tiene_filtro" value="<?php echo $this->_tpl_vars['ubicacion']; ?>
"/>
        <input type="hidden" name="fecha_apertura" id="fecha_apertura" value="<?php echo $this->_tpl_vars['fecha_apertura']; ?>
"/>
        <input type="hidden" name="establecimiento" id="establecimiento" value="<?php echo $this->_tpl_vars['establecimiento']; ?>
"/>
        <input type="hidden" name="almacen" id="almacen" value="<?php echo $this->_tpl_vars['almacen']; ?>
"/>
        <input type="hidden" name="ubicacion" id="ubicacion" value="<?php echo $this->_tpl_vars['ubicacion']; ?>
"/>
        <tr>
            <td style="padding-top:2px; padding-bottom: 2px;">
                
                <input type="text" name="nombres0" id="nombres0" class="form-text" size="50" />
            </td>
            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="cedula0" id="cedula0">
            </td>            
            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="cargo0" id="cargo0">
            </td>

            <td style="padding-top:2px; padding-bottom: 2px;">
                 <select class="form-text" type="text" name="procedencia0" >
                    <option value="3">Responsable</option>
                </select>
            </td>
        </tr>
        <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['start'] = (int)1;
$this->_sections['i']['loop'] = is_array($_loop=20) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                
                <input type="text" name="nombres<?php echo $this->_sections['i']['index']; ?>
" id="nombres<?php echo $this->_sections['i']['index']; ?>
" class="form-text" size="50" />
            </td>
            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="cedula<?php echo $this->_sections['i']['index']; ?>
" id="cedula<?php echo $this->_sections['i']['index']; ?>
">
            </td>            
            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="cargo<?php echo $this->_sections['i']['index']; ?>
" id="cargo<?php echo $this->_sections['i']['index']; ?>
">
            </td>

            <td style="padding-top:2px; padding-bottom: 2px;">
                 <select class="form-text" type="text" name="procedencia<?php echo $this->_sections['i']['index']; ?>
" >
                    <option value="1">Local</option>
                    <option value="2">Sede Central</option>
                </select>
            </td>
        </tr>
        <?php endfor; endif; ?>
        <tr class="tb-head">
            <td colspan="8">
                <input type="submit" id="enviarajax" name="guardar" value="Guardar" />
            </td>     
        </tr>

        </form>
       
        
        
    </tbody>
</table>
 </div>
 <?php endif; ?>
    </body>
</html>