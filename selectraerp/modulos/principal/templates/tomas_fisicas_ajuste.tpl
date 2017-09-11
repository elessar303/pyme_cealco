<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Humberto Zapata" />
        <title>TOMA FISICA</title>
        {include file="snippets/inclusiones_reportes.tpl"}
        
        {literal}

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
                formdata.append('id_mov',id_mov);
                formdata.append('boton',boton);
                
                

                $.ajax({
                    type: "POST",
                    url: "../../libs/php/ajax/ajax.php"+'?opt=actualizar_toma',
                    dataType: "html",
                    data: formdata,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() 
                    {

                        

                        $("#pantalla_resultado").empty();
                        $("#pantalla_resultado").html('<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>');
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
        {/literal}
        {literal}
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
            //función que verifica el codigo seleccionado
            function procesarCodigo()
            {   
                var codigo = $("#codigo_seguridad").val();
                $.ajax({
                            type: 'GET',
                            data: 'opt=getverificacionCodigo&'+'codigo='+codigo,
                            url: '../../libs/php/ajax/ajax.php',
                            success: function(data) 
                            {
                                if(data==1)
                                {
                                    document.getElementById('miVentana').style.display='none';
                                    $("#codigo_kardex").val(codigo);
                                    document.getElementById('enviarajax').style.display='block';
                                    
                                }
                                else
                                {
                                    if(data==-4)
                                    {
                                        alert("Clave Vencida");
                                        return false;
                                    }
                                    if(data==-1)
                                    {
                                        alert("Codigo ya se uso");
                                        return false;
                                    }
                                    if(data==-2)
                                    {
                                        alert("No puede dejar el codigo vacio");
                                        return false;
                                    }
                                    if(data==-3)
                                    {
                                        alert("Este Codigo no Corresponde a la tienda");
                                        return false;
                                    }
                                    if(data!=-4 && data!=-1 && data!=-2 && data!=-3)
                                    {
                                        alert("Error, Verifique el formato de la clave");
                                        return false;
                                    }
                                }
                                
                            }
                        }); 

            }
            //fin del verificar codigo
            $(document).ready(function()
            {

                //se habilita ventana modal para solicitar clave secreta (si esta activa)
                $.ajax({
                            type: 'GET',
                            data: 'opt=getCodigoKardex&',
                            url: '../../libs/php/ajax/ajax.php',
                            success: function(data) 
                            {
                                if(data==1)
                                {
                                    var ventana = document.getElementById('miVentana');
                                    ventana.style.marginTop = '100px';
                                    ventana.style.left = ((document.body.clientWidth-350) / 2) +  'px';
                                    ventana.style.display = 'block';
                                    //document.getElementById("enviarajax").disabled=true;
                                    document.getElementById('enviarajax').style.display='none';
                                }
                            }
                        }); 
            

            //función que verifica el codigo seleccionado

            
               
                $("#fecha").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths:true,
                    selectOtherMonths: true,
                   
                    dateFormat: "dd-mm-yy",
                    timeFormat: 'HH:mm:ss',
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
                    timeFormat: 'HH:mm:ss',
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
                    type: 'POST',
                    data: 'opt=cargaUbicacion&idAlmacen='+idAlmacen,
                    url: '../../libs/php/ajax/ajax.php',
                    beforeSend: function() {
                        $("#ubicacion").find("option").remove();
                        $("#ubicacion").append("<option value=''>Cargando..</option>");
                    },
                    success: function(data) {
                        $("#ubicacion").find("option").remove();
                        this.vcampos = eval(data);
                         $("#ubicacion").append("<option value=''>Seleccione..</option>");
                        for (i = 0; i < this.vcampos.length; i++) {
                            $("#ubicacion").append("<option value='"+this.vcampos[i].id+"'>" + this.vcampos[i].descripcion + "</option>");
                        }
                    }
                });
                }//fin el if
                else{
                     $("#ubicacion").find("option").remove();
                     $("#ubicacion").append("<option value=''>Seleccione..</option>");
                    }
                }
                });
    
    function agregarProducto()
    {
    win = new Ext.Window
    ({
        title:'Toma Fisica Inventario',
        height:360,
        width:459,
        autoScroll:true,
            
            modal:true,
            bodyStyle:'padding-right:10px;padding-left:10px;padding-top:5px;',
            closeAction:'hide',
            contentEl:'incluirproducto',
            buttons:[
                    {
                    text:'Incluir',
                    icon: '../../libs/imagenes/drop-add.gif',
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
                        type: 'POST',
                        data: 'opt=toma_fisica_actualizar&id_producto='+id_producto+'&cantidad='+cantidad+'&codigo_barras='+codigoBarra+'&id_mov='+id_mov,
                            url: '../../libs/php/ajax/ajax.php',
                        beforeSend: function()
                        { 
                            $("#resultado").empty();
                            $("#pantalla_resultado").append("<option value=''>Cargando..</option>");
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
                        text:'Cerrar',
                        icon: '../../libs/imagenes/cancel.gif',
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
    {/literal}
    <script type="text/javascript" src="../../libs/js/eventform_tomafisica.js"></script>
    <script type="text/javascript" src="../../libs/js/event_toma_fisica.js"></script>
    <script type="text/javascript" src="../../libs/js/buscar_productos_servicio_factura_rapida.js"></script>
        
    </head>
    <body>
        <form name="formulario" id="formulario" method="post" action="">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar.tpl"}
                <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
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
                                  <input type="text" name="fecha_apertura" id="fecha_apertura" size="10" value='{$smarty.now|date_format:"%Y-%m-%d"}' readonly class="form-text" />
                                </td>
                                    <!--button id="boton_fecha">...</button-->
                                    
                                <td class="label">Tipo de Toma:</td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                {$nombre_toma}
                                </td>
                                
                                <td class="label">Almacen</td>
                                <td  style="padding-top:2px; padding-bottom: 2px;">
                                {$nombre_almacen}
                                </td>

                                <td class="label">Ubicacion:</td>
                                <td style="padding-top:2px; padding-bottom: 2px;">
                                {$nombre_ubicacion}
                                </td>
                            </tr>                         
                        </tbody>
                    </table>
            </div>
        </form>
        <div id="datos_carga"> 


        {if $aceptar eq "Mostrar"}
    <div id="pantalla_resultado">
    <tr>
        <td class="btn" style="float:right; padding-right: 15px;" colspan="1">
        {if $numero_toma neq "4"}
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
        {/if}
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
    
        {assign var="toma1" value=""}
        {assign var="toma2" value=""}
        {assign var="toma3" value=""}
        
        <form name="formulario2" id="formulario2" method="post" enctype="multipart/form-data">
        <input type="hidden" name="tiene_filtro" id="tiene_filtro" value="1"/>
        <input type="hidden" name="ubicacion" id="tiene_filtro" value="{$ubicacion}"/>
        <input type="hidden" name="fecha_apertura" id="fecha_apertura" value="{$fecha_apertura}"/>
        <input type="hidden" name="tipo_toma" id="tipo_toma" value="{$tipo_toma}"/>
        <input type="hidden" name="id_mov" id="id_mov" value="{$id_mov}"/>

        
        {if $numero_toma eq "1"}
        {assign var="toma2_boton" value="disabled='disabled'"}
        {assign var="toma3_boton" value="disabled='disabled'"}
        <input type="hidden" name="toma1" id="toma1" value="1" />
        {/if}
        {if $numero_toma eq "2"}
        {assign var="toma1_boton" value="disabled='disabled'"}
        {assign var="toma3_boton" value="disabled='disabled'"}
        <input type="hidden" name="toma2" id="toma2" value="1" />
        {/if}
        {if $numero_toma eq "3"}
        {assign var="toma1_boton" value="disabled='disabled'"}
        {assign var="toma2_boton" value="disabled='disabled'"}
        <input type="hidden" name="toma3" id="toma3" value="1" />
        {/if}
        {if $numero_toma eq "4"}
        {assign var="toma1_boton" value="disabled='disabled'"}
        {assign var="toma2_boton" value="disabled='disabled'"}
        {assign var="toma3_boton" value="disabled='disabled'"}
        
        {/if}
        
        {assign var="counter" value="1"}
        {foreach key=key name=outer item=dato from=$consulta}
        
        <tr>
            <td style="padding-top:2px; padding-bottom: 2px;">
                
                <input type="text" name="codigo_barras[]" id="codigo_barras[]" style="float: left;"   class="form-text" value="{$dato.cod_bar}" readonly="readonly" />
            </td>
            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="precio{counter}" size="50" id="precio{counter}" value="{$dato.nombre_producto}" readonly="readonly">
            </td>            
            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="inv_ini[]" size="10" id="inv_ini[]" value="{$dato.inv_sistema}" readonly="readonly">
            </td>

            <td style="padding-top:2px; padding-bottom: 2px;">
                <input class="form-text" type="text" name="toma1[]" size="10" id="toma1[]" value="{$dato.toma1}" readonly="readonly" />
            </td>

            <td>
                <input class="form-text" type="text" name="toma2[]" size="10" id="toma2[]" value="{$dato.toma2}" readonly="readonly"/>
            </td>
            <td>
                <input class="form-text" type="text" name="tomadef[]" size="10" id="tomadef[]" value="{$dato.tomadef}" readonly="readonly" />
            </td>
            <td>
                <input class="form-text" type="text" name="mov_sug[]" size="10" id="mov_sug[]" value="{$dato.mov_sugerido}" readonly="readonly"/>
            </td>
        </tr>
        {assign var="counter" value=$counter++}
        {/foreach}
        
        <tr class="tb-head" style="width: 80px;">
            <td colspan="3">
                <input type="hidden" name="cantidad_items" id="cantidad_items" value="{$resultado}"/>
            </td>

            
        </tr>
        </form>
       
        {/if}
        {if $smarty.get.editar neq 2}
        <form name="formulario3" id="formulario3" method="post" action="">
        <tr class="tb-head">
                                <td colspan="8">
                                    <input class="form-text" type="hidden" maxlength="100"  size="30" name="codigo_kardex" id="codigo_kardex"/>
                                    <input type="submit" id="enviarajax" name="aceptar" value="Ajustar" />
                                    <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu={$smarty.get.opt_menu}';" />
                                </td>
        </tr>
        </form>
        {/if}
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
<div id='miVentana' style='position: fixed; width: 350px; height: 190px; top: 0; left: 0; font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; border: #333333 3px solid; background-color: #FAFAFA; color: #000000; display:none;  -moz-opacity:0.8; -webkit-opacity:0.8; -o-opacity:0.9; -ms-opacity:0.9; background-color: #808080; overflow: auto; width: 500px; background: #fff; padding: 30px; -moz-border-radius: 7px; border-radius: 7px; -webkit-box-shadow: 0 3px 20px rgba(0,0,0,1); -moz-box-shadow: 0 3px 20px rgba(0,0,0,1); box-shadow: 0 3px 20px rgba(0,0,0,1); background: -moz-linear-gradient(#fff, #ccc); background: -webkit-gradient(linear, right bottom, right top, color-stop(1, rgb(255,255,255)), color-stop(0.57, rgb(230,230,230)));  
'>
    <h1>Agregue el Codigo de seguridad asignado para la salida</h1>
    <table border="0"  align="center" width="200px">
        <tr>
            <td align="center" >
                <b>Codigo</b>
            </td>
            <td>
                <input type="text" name="codigo_seguridad" id="codigo_seguridad" class='form-text' />
            </td>
        </tr>
    </table>
    <p>
        <input align="center" type="submit" value="Procesar" style="  margin-right: 220px;" onclick="procesarCodigo()" />
    </p>
</div>
</body>
</html>
