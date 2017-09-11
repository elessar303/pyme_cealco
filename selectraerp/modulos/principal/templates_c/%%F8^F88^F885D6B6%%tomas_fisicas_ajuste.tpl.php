<?php /* Smarty version 2.6.21, created on 2017-01-09 19:55:42
         compiled from tomas_fisicas_ajuste.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'tomas_fisicas_ajuste.tpl', 288, false),array('function', 'counter', 'tomas_fisicas_ajuste.tpl', 394, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Humberto Zapata" />
        <title>TOMA FISICA</title>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/inclusiones_reportes.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        
        <?php echo '

         <script language="JavaScript" type="text/JavaScript">
        function recargar(boton)
        {
            formdata = new FormData($("#formulario2")[0]);
            archivo1=0;
            archivo2=0;
            archivo3=0;
            //creamos el objeto formData mediante el formulario
            if(boton==1)
            {
                if($("#archivo_productos1").val()!="")
                {   
                    
                    archivo1=1;
                }

            }
            if(boton==2)
            {
                if($("#archivo_productos2").val()!="")
                {
                    
                    archivo2=1;
                    
                }
            }
            if(boton==3)
            {
                if($("#archivo_productos3").val()!="")
                {
                    
                    archivo3=1;
                    
                }
            }
            
            
            
                
                id_mov = $("#id_mov").val();
                formdata.append(\'id_mov\',id_mov);
                formdata.append(\'boton\',boton);
                
                

                $.ajax({
                    type: "POST",
                    url: "../../libs/php/ajax/ajax.php"+\'?opt=actualizar_toma\',
                    dataType: "html",
                    data: formdata,
                    cache: false,
                    contentType: false,
                    processData: false,
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
                        if(data==3)
                        {
                            alert("Error, el codigo de uno de los productos no existe");
                            location.reload();
                            return false;
                        }
                        if(data==2)
                        {
                            alert("Error, Estructura de archivo no compatible");
                            location.reload();
                            return false;
                        }
                        if(data==1)
                        {
                            alert("Error, Extension incorrecta de archivo (solo .txt)");
                            location.reload();
                            return false;
                        }

                    $("#pantalla_resultado").html(data);
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
                        for (i = 0; i < this.vcampos.length; i++) {
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
    
    function agregarProducto()
    {
    win = new Ext.Window
    ({
        title:\'Toma Fisica Inventario\',
        height:360,
        width:459,
        autoScroll:true,
            
            modal:true,
            bodyStyle:\'padding-right:10px;padding-left:10px;padding-top:5px;\',
            closeAction:\'hide\',
            contentEl:\'incluirproducto\',
            buttons:[
                    {
                    text:\'Incluir\',
                    icon: \'../../libs/imagenes/drop-add.gif\',
                    handler:function()
                    {
                    cantidad=$("#cantidadunitaria").val();
                    ubicacion=$("#ubicacion").val();
                    id_mov=$("#id_mov").val();
                    id_producto=$("#items").val();
                    codigoBarra=$("#codigoBarra").val();
                    

                    if($("#cantidad").val()=="" || $("#cantidad").val()<0)
                    {
                                    Ext.Msg.alert("Alerta","Debe especificar todos los campos.");
                                    return false;
                    }

                    //ajax para insertar
                    $.ajax({
                        type: \'POST\',
                        data: \'opt=toma_fisica_actualizar&id_producto=\'+id_producto+\'&cantidad=\'+cantidad+\'&codigo_barras=\'+codigoBarra+\'&id_mov=\'+id_mov,
                            url: \'../../libs/php/ajax/ajax.php\',
                        beforeSend: function()
                        { 
                            $("#resultado").empty();
                            $("#pantalla_resultado").append("<option value=\'\'>Cargando..</option>");
                        },
                        success: function(data) 
                        {
                            $("#pantalla_resultado").empty();
                            $("#pantalla_resultado").html(data);
                            win.hide();
                            $("#items").val("");
                            $("#items_descripcion").val("");
                            $("#codigoBarra").val("");
                            $("#cantidadunitaria").val("");

                        }
                    });
                    }
                    },
                    {
                        text:\'Cerrar\',
                        icon: \'../../libs/imagenes/cancel.gif\',
                        handler:function()
                        {
                        win.hide();
                        $("#items").val("");
                        $("#items_descripcion").val("");
                        $("#codigoBarra").val("");
                        $("#cantidadunitaria").val("");
                        }
                    },
                    ]
    });

    win.show();
    };
    //fin de ventana emergente e insertar
    </script>
    '; ?>

    <script type="text/javascript" src="../../libs/js/eventform_tomafisica.js"></script>
    <script type="text/javascript" src="../../libs/js/event_toma_fisica.js"></script>
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
                                <td class="label">Fecha Apertura:</td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                  <input type="text" name="fecha_apertura" id="fecha_apertura" size="10" value='<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
' readonly class="form-text" />
                                </td>
                                    <!--button id="boton_fecha">...</button-->
                                    
                                <td class="label">Tipo de Toma:</td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                <?php echo $this->_tpl_vars['nombre_toma']; ?>

                                </td>
                                
                                <td class="label">Almacen</td>
                                <td  style="padding-top:2px; padding-bottom: 2px;">
                                <?php echo $this->_tpl_vars['nombre_almacen']; ?>

                                </td>

                                <td class="label">Ubicacion:</td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                <?php echo $this->_tpl_vars['nombre_ubicacion']; ?>

                                </td>
                            </tr>                         
                        </tbody>
                    </table>
            </div>
        </form>
        <div id="datos_carga"> 


        <?php if ($this->_tpl_vars['aceptar'] == 'Mostrar'): ?>
    <div id="pantalla_resultado">
    <tr>
        <td class="btn" style="float:right; padding-right: 15px;" colspan="1">
        <?php if ($this->_tpl_vars['numero_toma'] != '4'): ?>
        <div id=boton_agregar> 
        <table class="btn_bg" onclick="agregarProducto()" align="center" border="0">
            <tr style="border-width: 0px; cursor: pointer;">
                <td><img src="../../../includes/imagenes/bt_left.gif" style="border-width: 0px; width: 4px; height: 21px;" />
                </td>
                <td><img src="../../../includes/imagenes/add.gif" width="16" height="16" />
                </td>
                <td style="padding: 0px 6px;">Agregar Producto
                </td>
                <td><img src="../../../includes/imagenes/bt_right.gif" style="border-width: 0px; width: 4px; height: 21px;" />
                </td>
            </tr>
        </table>
        </div>
        <?php endif; ?>
        </td>
        </tr>
    <table   width="100%" border="0" >
        <thead align="center">
                <tr class="tb-head">
                <th style="width:220px;" >Codigo de Barras</th>
                <th style="width:200px;">Producto</th>
                <th>Inventario Ini.</th>
                <th>Toma 1</th>
                <th>Toma 2</th>
                <th>Toma Def.</th>
                <th>Mov. Sugerido</th>
                </tr>
        </thead>

        <tbody>
    
        <?php $this->assign('toma1', ""); ?>
        <?php $this->assign('toma2', ""); ?>
        <?php $this->assign('toma3', ""); ?>
        
        <form name="formulario2" id="formulario2" method="post" enctype="multipart/form-data">
        <input type="hidden" name="tiene_filtro" id="tiene_filtro" value="1"/>
        <input type="hidden" name="ubicacion" id="tiene_filtro" value="<?php echo $this->_tpl_vars['ubicacion']; ?>
"/>
        <input type="hidden" name="fecha_apertura" id="fecha_apertura" value="<?php echo $this->_tpl_vars['fecha_apertura']; ?>
"/>
        <input type="hidden" name="tipo_toma" id="tipo_toma" value="<?php echo $this->_tpl_vars['tipo_toma']; ?>
"/>
        <input type="hidden" name="id_mov" id="id_mov" value="<?php echo $this->_tpl_vars['id_mov']; ?>
"/>

        
        <?php if ($this->_tpl_vars['numero_toma'] == '1'): ?>
        <?php $this->assign('toma2_boton', "disabled='disabled'"); ?>
        <?php $this->assign('toma3_boton', "disabled='disabled'"); ?>
        <input type="hidden" name="toma1" id="toma1" value="1" />
        <?php endif; ?>
        <?php if ($this->_tpl_vars['numero_toma'] == '2'): ?>
        <?php $this->assign('toma1_boton', "disabled='disabled'"); ?>
        <?php $this->assign('toma3_boton', "disabled='disabled'"); ?>
        <input type="hidden" name="toma2" id="toma2" value="1" />
        <?php endif; ?>
        <?php if ($this->_tpl_vars['numero_toma'] == '3'): ?>
        <?php $this->assign('toma1_boton', "disabled='disabled'"); ?>
        <?php $this->assign('toma2_boton', "disabled='disabled'"); ?>
        <input type="hidden" name="toma3" id="toma3" value="1" />
        <?php endif; ?>
        <?php if ($this->_tpl_vars['numero_toma'] == '4'): ?>
        <?php $this->assign('toma1_boton', "disabled='disabled'"); ?>
        <?php $this->assign('toma2_boton', "disabled='disabled'"); ?>
        <?php $this->assign('toma3_boton', "disabled='disabled'"); ?>
        
        <?php endif; ?>
        
        <?php $this->assign('counter', '1'); ?>
        <?php $_from = $this->_tpl_vars['consulta']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['outer'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['outer']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['dato']):
        $this->_foreach['outer']['iteration']++;
?>
        
        <tr>
            <td style="padding-top:2px; padding-bottom: 2px;">
                
                <input type="text" name="codigo_barras[]" id="codigo_barras[]" style="float: left;"   class="form-text" value="<?php echo $this->_tpl_vars['dato']['cod_bar']; ?>
" readonly="readonly" />
            </td>
            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="precio<?php echo smarty_function_counter(array(), $this);?>
" size="50" id="precio<?php echo smarty_function_counter(array(), $this);?>
" value="<?php echo $this->_tpl_vars['dato']['nombre_producto']; ?>
" readonly="readonly">
            </td>            
            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="inv_ini[]" size="10" id="inv_ini[]" value="<?php echo $this->_tpl_vars['dato']['inv_sistema']; ?>
" readonly="readonly">
            </td>

            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="toma1[]" size="10" id="toma1[]" value="<?php echo $this->_tpl_vars['dato']['toma1']; ?>
" readonly="readonly" />
            </td>

            <td>
                <input class="form-text" type="text" name="toma2[]" size="10" id="toma2[]" value="<?php echo $this->_tpl_vars['dato']['toma2']; ?>
" readonly="readonly"/>
            </td>
            <td>
                <input class="form-text" type="text" name="tomadef[]" size="10" id="tomadef[]" value="<?php echo $this->_tpl_vars['dato']['tomadef']; ?>
" readonly="readonly" />
            </td>
            <td>
                <input class="form-text" type="text" name="mov_sug[]" size="10" id="mov_sug[]" value="<?php echo $this->_tpl_vars['dato']['mov_sugerido']; ?>
" readonly="readonly"/>
            </td>
        </tr>
        <?php $this->assign('counter', $this->_tpl_vars['counter']++); ?>
        <?php endforeach; endif; unset($_from); ?>
        
        <tr class="tb-head" style="width: 80px;">
            <td colspan="3">
                <input type="hidden" name="cantidad_items" id="cantidad_items" value="<?php echo $this->_tpl_vars['resultado']; ?>
"/>
            </td>

            
        </tr>
        </form>
       
        <?php endif; ?>
        <?php if ($_GET['editar'] != 2): ?>
        <form name="formulario3" id="formulario3" method="post" action="">
        <tr class="tb-head">
                                <td colspan="8">
                                    <input type="submit" id="enviarajax" name="aceptar" value="Ajustar" />
                                    <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu=<?php echo $_GET['opt_menu']; ?>
';" />
                                </td>
        </tr>
        </form>
        <?php endif; ?>
    </tbody>
</table>
</div>
 </div>
 <div id="incluirproducto" class="x-hide-display">
            
            <p>
               <label><b>Codigo de barra</b></label><br/>
               <input type="text" name="codigoBarra" id="codigoBarra" class="form-text">
               <button id="buscarCodigo" name="buscarCodigo">Buscar</button>
            </p>

            <p>
                <label><b>Productos</b></label><br/>
                <input type="hidden" name="items" id="items" class="form-text">
                <input type="text" name="items_descripcion" id="items_descripcion" size="30" readonly class="form-text">
               
            </p>
           
            <p>
                <label><b>Cantidad de la toma</b></label><br/>
                <input type="text" name="cantidadunitaria" id="cantidadunitaria" class="form-text"/>
            </p>
            
        </div>
</body>
</html>