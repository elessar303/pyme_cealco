<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
         <script type="text/javascript" src="../../libs/js/jquery.numeric.js"></script>
        <script type="text/javascript" src="../../libs/js/config_items_tabs.js"></script>
        <script type="text/javascript" src="../../libs/js/ajax.js"></script>
         <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />

        {literal}
            <script type="text/javascript">//<![CDATA[

            function soloNumeros(e){
            var key = window.Event ? e.which : e.keyCode
            return (key >= 48 && key <= 57)
            }

            function buscarProducto(id,nombre,empaque,cantidad){
                var miId = document.getElementById(id);

                var miItem = document.getElementById('cod_item');

                cargarUrl('buscarProducto.php?codigo=' + miId.value + '&campo=' + id + '&nombre=' + nombre + '&empaque=' + empaque + '&cantidad=' + cantidad, 'TRANSPARENTE' );

                setTimeout ("sumaTotal()", 3000);
            }

            function ocultarVarios(objeto){
                var aux = document.getElementById(objeto);
                var e = aux.getElementsByTagName("input");

                for(var i=0;i<e.length;i++){
                    e[i].style.visibility= 'hidden';
                }
                var img = aux.getElementsByTagName("img");

                for(var j=0;j<img.length;j++){
                    e[j].style.visibility= 'hidden';
                }
            }

            function agregarItem(codigo){
                var aux = document.getElementById('id0');
                aux.value=codigo;
                aux.onchange();

                var aux = document.getElementById('busqueda');
                aux.value='';

                var aux = document.getElementById('cuadroBusqueda');
                aux.innerHTML='Inserte palabras claves del producto a seleccionar';
            }

            function sumaTotal(){
                var total=0;
                for(var i=1;i<=20;i++){
                    var aux = document.getElementById('sub_total' + i);
                    total = (total * 1) + (aux.value * 1);
                }
                var aux2 = document.getElementById('suma_total');

                aux2.value= redondear(total);

                var aux3 = document.getElementById('costo_actual');
                aux3.value=aux2.value;
                aux3.onchange();
            }

            function buscarProductoLista(cadena){
                cargarUrl('buscarProductoLista.php?cadena=' + cadena , 'cuadroBusqueda' );
            }

            function calcularPrecio(cantidad, precio, sub_total){
                var cant = cantidad.value;
                var monto = document.getElementById(precio).value;
                var sub = document.getElementById(sub_total);
                sub.value= redondear(cant * monto);
                sumaTotal();
            }

            function eliminarProducto(posicion){
                var aux = document.getElementById('item' + posicion);
                while (aux.value != ''){
                    var item = document.getElementById('item' + posicion);
                    var id = document.getElementById('id' + posicion);
                    var nombre = document.getElementById('nombre' + posicion);
                    var empaque = document.getElementById('empaque' + posicion);
                    var cantidad = document.getElementById('cantidad' + posicion);
                    var precio = document.getElementById('precio' + posicion);
                    var sub_total = document.getElementById('sub_total' + posicion);

                    posicion = (posicion * 1) + 1;

                    var item2 = document.getElementById('item' + posicion);
                    var id2 = document.getElementById('id' + posicion);
                    var nombre2 = document.getElementById('nombre' + posicion);
                    var empaque2 = document.getElementById('empaque' + posicion);
                    var cantidad2 = document.getElementById('cantidad' + posicion);
                    var precio2 = document.getElementById('precio' + posicion);
                    var sub_total2 = document.getElementById('sub_total' + posicion);

                    item.value= item2.value;
                    id.value = id2.value;
                    nombre.value = nombre2.value;
                    empaque.value= empaque2.value;
                    cantidad.value = cantidad2.value;
                    precio.value= precio2.value;
                    sub_total.value = sub_total2.value;

                    var aux = document.getElementById('item' + posicion);
                }

                posicion = (posicion * 1) - 1;

                var item2 = document.getElementById('item' + posicion);
                var id2 = document.getElementById('id' + posicion);
                var nombre2 = document.getElementById('nombre' + posicion);
                var empaque2 = document.getElementById('empaque' + posicion);
                var cantidad2 = document.getElementById('cantidad' + posicion);
                var precio2 = document.getElementById('precio' + posicion);
                var sub_total2 = document.getElementById('sub_total' + posicion);
                var eliminar = document.getElementById('eliminar' + posicion);

                item2.style.visibility='hidden';
                id2.style.visibility='hidden';
                nombre2.style.visibility='hidden';
                empaque2.style.visibility='hidden';
                cantidad2.style.visibility='hidden';
                precio2.style.visibility='hidden';
                sub_total2.style.visibility='hidden';
                eliminar.style.visibility='hidden';
            }

            function redondear(cantidad, decimales) {
                var cantidad = parseFloat(cantidad);
                var decimales = parseFloat(decimales);
                decimales = (!decimales ? 2 : decimales);
                return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);
            }

            function calcularMonto(precio, utilidad, campoiva, ocultos){
                 iva=$("#iva").val();
                // precio.value = parseFloat(ocultos.value * (1 + (parseFloat(utilidad.value) / 100)));
                // precio.value = redondear(precio.value,2);
                $("#"+precio).val( parseFloat( $("#"+ocultos).val() * (1 + (parseFloat($("#"+utilidad).val()) / 100))));
                $("#"+precio).val(redondear($("#"+precio).val(),2));
               montoE=$("#monto_exento").val();

                if (montoE == '1'){

                        // Cambiar cero (0) el valor para guardarlo en campo 'iva' de la tabla 'item'
                       $("#iva").val(0);
                        // iva.value = 0;
                        $("#"+campoiva).val($("#"+precio).val());
                        // campoiva.value = precio.value;
                }
                else{

                        // Cambiar al valor configurado IVA para guardarlo en campo 'iva' de la tabla 'item'
                        // iva.value = {/literal}{$parametros_generales[0].porcentaje_impuesto_principal}{literal};
                         iva=$("#iva").val();

                        coniva=redondear($("#"+precio).val() * (1 + (iva/100)), 2);

                        $("#"+campoiva).val(coniva);

                }
            }

            function calcular_todo(){
                var aux = document.getElementById('formulario');
                 calcularMonto("fila_precio1","utilidad1","fila_precio1_iva","ocultos1");
                calcularMonto("fila_precio2","utilidad2","fila_precio2_iva","ocultos2");
                calcularMonto("fila_precio3","utilidad3","fila_precio3_iva","ocultos3");
            }

            $(document).ready(function(){

                $("#cod_item").change(function(){
                    //return false;
                    var vcoditem = $(this).val();
                    if(vcoditem!=''){
                        $.ajax({
                            type: "GET",
                            url:  "../../libs/php/ajax/ajax.php",
                            data: "opt=ValidarCodigoitem&v1="+vcoditem,
                            beforeSend: function(){
                                $("#notificacionVCoditem").html(MensajeEspera("<b>Verificando C&oacute;d. item...<b>"));
                            },
                            success: function(data){
                                resultado = data
                                if(resultado=="-1"){
                                    $("#cod_item").val("").focus();
                                    $("#notificacionVCoditem").html("<img align=\"absmiddle\" src=\"../../libs/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, este c&oacute;digo ya existe.</b></span>");
                                }
                                if(resultado=="1"){//coddeitemdisponble
                                    $("#notificacionVCoditem").html("<img align=\"absmiddle\" src=\"../../libs/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> C&oacute;digo Disponible</b></span>");
                                }
                            }
                        });
                    }
                });

                $("#formulario").submit(function(){
                    if(
                            $("#descripcion1").val()=="" ||
                            $("#cod_barras").val()=="" ||
                            $("#marca").val()=="" ||
                            $("#producto_vencimiento").val()==""

                ){
                        alert("Debe Ingresar los campos obligatorios!");
                        $("#descripcion1").focus();
                        return false;
                    }
                });
                  // agregado el 22/01/14 para regargar el select dependiente de rubro y sub rubro
                        function listarSubrubro(idSelect, tipoSql, idCarga,id){
                            var paramentros="opt=cargarSubrubro&idCarga="+idCarga+"&tipoSql="+tipoSql+"&id="+id;
                            $.ajax({
                                type: "POST",
                                url: "../../libs/php/ajax/ajax.php",
                                data: paramentros,
                                beforeSend: function(datos){
                                    $("#"+idSelect).html('<option value = 0> Cargando... </option>');
                                },
                                success: function(datos){
                                    $("#"+idSelect).html(datos);
                                    id_categoria=$("#cod_grupo").val();
                                    listarSubcategoria("sub_categoria", 0, id_categoria);
                                     document.formulario.sub_categoria.value = id_sub_categoria;
                                },
                                error: function(datos,falla, otroobj){
                                    $("#"+idSelect).html('<option value = 0> Error... </option>');
                                }
                            });
                    };

                    function listarSubcategoria(idSelect, tipoSql, idCarga){
                            var paramentros="opt=cargarSubrubro&idCarga="+idCarga+"&tipoSql="+tipoSql;
                            $.ajax({
                                type: "POST",
                                url: "listarSubcategoria.php",
                                data: paramentros,
                                beforeSend: function(datos){
                                    $("#"+idSelect).html('<option value = 0> Cargando... </option>');
                                },
                                success: function(datos){
                                    $("#"+idSelect).html(datos);
                                     document.formulario.sub_categoria.value = id_sub_categoria;
                                },
                                error: function(datos,falla, otroobj){
                                    $("#"+idSelect).html('<option value = 0> Error... </option>');
                                }
                            });
                    };
                   // fin de la funcion para select dependiente
                   // llamada de la funcion para cargar el select dependiente de subrubro
                        id_rubro=$("#cod_departamento").val();
                        id_prod=$("#id_prod1").val();

                        listarSubrubro("cod_grupo", 1, id_rubro,id_prod);
                    // cuando cambia rubro salta la funcion ajax
                       $("#cod_departamento").change(function(){
                              id_rubro=$("#cod_departamento").val();
                              listarSubrubro("cod_grupo", 1, id_rubro,id_prod);
                               document.formulario.sub_categoria.value = id_sub_categoria;
                       });

                        $("#cod_grupo").change(function(){
                              id_categoria=$("#cod_grupo").val();
                              listarSubcategoria("sub_categoria", 0, id_categoria);
                               document.formulario.sub_categoria.value = id_sub_categoria;
                       });
                   //fin de la llamada

                    $("#cantidad_bulto").change(function(event) {
                        peso_unidad= $("#pesoxunidad").val();
                        cantidad=$("#cantidad_bulto").val();
                        conver=$("#unidadxpeso").val();
                        if(conver==1 || conver==3  ){
                        kiloxbulto=peso_unidad*cantidad;
                        $("#kilos_bulto").val(kiloxbulto);
                        }
                        if(conver==2  ){
                        kiloxbulto=peso_unidad*cantidad*0.001;
                        $("#kilos_bulto").val(kiloxbulto);
                        }
                    });

                    $("#pesoxunidad").change(function(event) {
                        peso_unidad= $("#pesoxunidad").val();
                        cantidad=$("#cantidad_bulto").val();
                        conver=$("#unidadxpeso").val();
                        if(conver==1 || conver==3  ){
                        kiloxbulto=peso_unidad*cantidad;
                        $("#kilos_bulto").val(kiloxbulto);
                        }
                        if(conver==2  ){
                        kiloxbulto=peso_unidad*cantidad*0.001;
                        $("#kilos_bulto").val(kiloxbulto);
                        }
                    });

                    $("#unidadxpeso").change(function(event) {
                        peso_unidad= $("#pesoxunidad").val();
                        cantidad=$("#cantidad_bulto").val();
                        conver=$("#unidadxpeso").val();
                        if(conver==1 || conver==3  ){
                        kiloxbulto=peso_unidad*cantidad;
                        $("#kilos_bulto").val(kiloxbulto);
                        }
                        if(conver==2  || conver==4 || conver==5){
                        kiloxbulto=peso_unidad*cantidad*0.001;
                        $("#kilos_bulto").val(kiloxbulto);
                        }
                    });

                    $("#monto_exento").change(function(event) {
                        tipo= $("#monto_exento").val();
                        if(tipo==1){
                            $("#iva").hide();
                        }
                        if(tipo==0){
                             $("#iva").show();
                        }

                    });
                      tipo= $("#monto_exento").val();
                        if(tipo==1){

                            $(".monto_iva").hide();
                        }
                        if(tipo==0){
                             $(".monto_iva").show();

                        }
            id_sub_categoria=$("#sub_categoria").val();
            //alert(id_sub_categoria);
            document.formulario.sub_categoria.value = id_sub_categoria;
               });




            //]]>



            </script>
            <style type="text/css">
                .tab{
                    text-align:left;
                    background-color:#d0d0d0;
                    padding-left:10px;
                    padding-right:10px;
                    font-size:11px;
                    font-family: arial;
                    color:#a0a0a0;
                    cursor: pointer;
                    width:auto;
                    border-left: 1px solid #8d8f91;
                    border-right: 1px solid #8d8f91;
                    border-top: 1px solid #8d8f91;
                }
                .sobreboton{
                    background-color:#bec0c1;
                }
                .click{
                    background: url('../../libs/imagenes/azul/tb_tit.jpg') repeat-x;
                    color:black;
                    border-left: 1px solid #8d8f91;
                    border-right: 1px solid #8d8f91;
                    border-top: 1px solid #8d8f91;
                }
                input{
                    color:black;
                    readonly:false;
                }
                #productosKit{
                    position:absolute;
                    right:20px;
                    height:300px;
                }
                #contenedorTAB {
                    background-color: #e3ebf1;
                    -moz-border-radius: 5px; padding: 2px;
                    -webkit-border-radius: 5px;
                    border: 1px solid #adafb0;
                    width:700px;
                    overflow: auto;
                }
                #tabs {
                    margin-top:15px;
                }
            </style>
        {/literal}
 <form name="formulario" id="formulario" method="post" enctype="multipart/form-data" action="">
  <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
  <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
  <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
  <input type="hidden" name="id_prod1" id="id_prod1" value="{$smarty.get.cod}"/>
  <table style="width:100%">
<tbody>
 <tr>
  <td class="tb-tit">
<img src="{$subseccion[0].img_ruta}" width="20" align="absmiddle" height="20"/>&nbsp;&nbsp;<strong>{$subseccion[0].descripcion}</strong>
  </td>
 </tr>
</tbody>
  </table>
  <div id="tabs">
<table style="margin-left:20px;" >
 <tr style="height:25px;">
  <td id="tab1" class="tab">
<!--<img src="../../libs/imagenes/1.png" width="20" align="absmiddle" height="20"/>&nbsp;&nbsp;-->
<b>Datos Generales</b>
</td>
 <!--
<td id="tab2" class="tab">
 <b>Datos Particulares</b>
</td>
<td id="tab3" class="tab">
 <b>Fotos</b>
</td>
<td id="tab4" class="tab">
 <b>Licencia</b>
</td>
<td id="tab5" class="tab">
 <b>Precio</b>
</td> -->
<td id="tab5" class="tab">
 <b>Datos Compra</b>
</td>
  <td>&nbsp;&nbsp;</td>
 </tr>
</table>
  </div>
  <div id='productosKit'></div>
  <div id="contenedorTAB">
<!-- TAB1 -->
<div id="div_tab1">
 <table style="width: 100%;">
  <tr>
<td colspan="4"  class="label" style="text-align: center;"> COMPLETE LOS CAMPOS MARCADOS CON&nbsp;** OBLIGATORIAMENTE</td>
  </tr>
 <tr>
 <td colspan="3" class="label" style="width: 30%;">Foto</td>
 <td>
  <input type="file" name="foto" id="foto"  class="form-text"/>

 </td>
</tr>
<!-- <tr>
 <td colspan="5" align="center">
<img src="../../imagenes/{$campos_item[0].foto}" width="100" align="absmiddle" height="100"/>
 </td>

</tr> -->

<tr>
 <td colspan="3" width="30%" class="label">Fecha de Creaci&oacute;n</td>
 <td>
  <input type="text" name="fecha_ingreso" id="fecha_ingreso" size="50" value="{$campos_item[0].fecha_creacion}" class="form-text" disabled="disabled" />

 </td>
</tr>

<tr hidden="hidden">
<td colspan="3" width="30%"  >C&oacute;digo **</td>
<td>
 <input type="text" name="cod_item" id="cod_item" size="50" value="{$campos_item[0].cod_item}" class="form-text" readonly/>
 <!--input type="hidden" name="ultimo_codigo" id="ultimo_codigo" value="{$nro_productoOLD}" disabled />
 <div id="notificacionVCoditem"></div-->
 <div id="notificacionVCoditem"></div>
</td>
</tr>
  <tr>
<td  colspan="4" align="center" width="180">DATOS DEL PRODUCTO</td>
  </tr>
  <tr>
<td colspan="3" class="label"><!-- {$DatosGenerales[0].string_clasificador_inventario1} -->Rubro</td>
<td>
 <select name="cod_departamento" id="cod_departamento" class="form-text">
  {html_options values=$option_output_departamentos output=$option_values_departamentos selected=$option_selected_departamentos}
 </select>
</td>
  </tr>
  <tr>
<td colspan="3" class="label"><!-- {$DatosGenerales[0].string_clasificador_inventario2} -->Categor&iacute;a</td>
<td>
 <select name="cod_grupo" id="cod_grupo" class="form-text">
  {html_options values=$option_values_subrubro output=$option_output_subrubro selected=$option_selected_subrubro}
 </select>
</td>
  </tr>
  <tr>
 <td colspan="3" class="label"><!-- {$DatosGenerales[0].string_clasificador_inventario2} -->Sub Categor&iacute;a</td>
 <td>
  <select name="sub_categoria" id="sub_categoria" class="form-text" style="width:230px">
{html_options values=$option_output_grupo output=$option_values_grupo selected=$option_selected_sub_categoria}
  </select>
 </td>
</tr>
  <!--  comentado el 3 de febrero 2015 para facilitar la creacion en pdval -->

<!--   <tr>
<td colspan="3" >
 {$DatosGenerales[0].string_clasificador_inventario3}
</td>
<td>
 <select name="cod_linea" id="cod_linea" class="form-text">
  {html_options values=$option_output_linea output=$option_values_linea selected=$option_selected_linea}
 </select>
</td>
  </tr> -->
  <tr>
<td colspan="3" class="label">C&oacute;digo de barras**</td>
<td>
 <input type="text" name="cod_barras" id="cod_barras" size="50" value="{$campos_item[0].codigo_barras}" class="form-text"/>
</td>
  </tr>

  <tr>
 <td colspan="3" width="30%" class="label">Cod. CPE</td>
 <td>
  <input type="text" name="cod_cpe" id="cod_cpe" size="50" value="{$campos_item[0].codigo_cpe}" class="form-text"/>

 </td>
</tr>
<tr>
 <td colspan="3" width="30%" class="label">Registro Sanitario</td>
 <td>
  <input type="text" name="reg_sanit" id="reg_sanit" size="50" value="{$campos_item[0].reg_sanit}" class="form-text"/>
 </td>
</tr>
  <!--tr>
<td  colspan="3">
 Linea
</td>
<td>
 <select name="cod_linea" id="cod_linea">
<option  {if $campos_item[0].cod_linea eq "3" }selected {/if} value="3">Papeleria y Oficina</option>
  <option  {if $campos_item[0].cod_linea eq "4" }selected {/if}value="4">Insumos Medicos</option>
  <option  {if $campos_item[0].cod_linea eq "5" }selected {/if}value="5">Medicamentos</option>
  <option  {if $campos_item[0].cod_linea eq "6" }selected {/if}value="6">Eq. Medicos</option>
  </select>
</td>
  </tr>
  <tr-->
  <tr>
<td colspan="3" class="label">Descripci&oacute;n 1 **</td>
<td>
 <input type="text" name="descripcion1" id="descripcion1" size="50" value="{$campos_item[0].descripcion1}" class="form-text"/>
</td>
  </tr>
  <tr>
 <td class="label" colspan="3">Tipo de Almacenamiento</td>
 <td>
  <select name="tipo_almacenamiento" id="tipo_almacenamiento" class="form-text" style="width:230px">
<option value="">Seleccione Tipo de Almacenamiento</option>
<option {if $campos_item[0].tipo_almacenamiento eq "SECO" } selected {/if}value="SECO">SECO</option>
<option {if $campos_item[0].tipo_almacenamiento eq "FRIO" } selected {/if}value="FRIO">FRIO</option>
  </select>
 </td>
</tr>
  <!--  comentado el 3 de enero 2015 para facilitar la creacion en pdval -->

<!--   <tr>
<td  colspan="3">
 Descripción 2
<td>
 <input type="text" name="descripcion2" id="descripcion2" size="60" value="{$campos_item[0].descripcion2}" class="form-text"/>
</td>
  </tr>
  <tr>
<td  colspan="3">Descripci&oacute;n 3</td>
<td>
 <input type="text" name="descripcion3" id="descripcion3" size="60" value="{$campos_item[0].descripcion3}" class="form-text"/>
</td>
  </tr> -->
  <tr>
<td  colspan="3" class="label">
 SKU
</td>
<td>
 <input type="text" name="referencia" id="referencia" size="50" value="{$campos_item[0].referencia}" class="form-text" readonly/>
</td>
  </tr>

  <tr>
 <td class="label" colspan="3">Unidad Venta</td>
 <td>

   <!--<input type="text" name="unidad_empaque" id="unidad_empaque" size="5" value="{$campos_item[0].cantidad}" class="form-text"/>
  <input type="text" name="empaque_descripcion" id="empaque_descripcion" size="5" value="{$campos_item[0].unidad_empaque}" readonly style="border-style:none; background:none;" class="form-text"/>-->
  <select name="unidad_venta" id="unidad_venta" class="form-text" style="width:200px">
<option value="Unidad">Seleccione Unidad de Venta</option>
{html_options values=$option_values_unidad_venta output=$option_output_unidad_venta selected=$option_selected_unidad_venta}
  </select>


 </td>
</tr>

<tr>
 <td class="label" colspan="3">Unidad de Peso**</td>
 <td>
   <select name="unidadxpeso" id="unidadxpeso" class="form-text" style="width:200px">
<option>Seleccione Unidad de Peso</option>
{html_options values=$option_values_unidadxpeso output=$option_output_unidadxpeso selected=$option_selected_unidadxpeso}
  </select>
 </td>
</tr>

<tr>
<td  colspan="3" class="label">
Peso por Unidad
</td>
<td>
 <input type="text" name="pesoxunidad" id="pesoxunidad" size="10" value="{$campos_item[0].pesoxunidad}" class="form-text" onKeyPress="return soloNumeros(event)"/>
</td>
  </tr>

<tr>
 <td class="label" colspan="3">Tipo de Producto</td>
 <td>
  <select name="tipo" id="tipo" class="form-text" style="width:225px">
<option>Seleccione el uso del producto</option>
{html_options values=$option_values_tipo output=$option_output_tipo selected=$option_selected_tipo}
  </select>
 </td>
</tr>

<tr>
 <td class="label" colspan="3">Marca</td>
 <td>
   <select name="marca" id="marca" class="form-text" style="width:225px">
<option value="">Seleccione Marca del Producto</option>
{html_options values=$option_values_marca output=$option_output_marca selected=$option_selected_marca}
  </select>
  <!--<input type="text" name="codigo_fabricante" id="codigo_fabricante" size="50" value="{$campos_item[0].codigo_fabricante}" class="form-text"/>-->
 </td>
</tr>
<!--
<tr>
 <td class="label" colspan="3">Estatus</td>
 <td>
  <select name="estatus" id="estatus" class="form-text">
<option value="A">Activo</option>
<option value="I">Inactivo</option>
  </select>
 </td>
</tr> -->

  <!--<tr>
<td  colspan="3">Empaque**</td>

 <td>
  <input type="text" name="empaque" id="empaque" size="50" value="{$campos_item[0].unidad_empaque}" onchange='this.form.empaque_descripcion.value = this.value;' class="form-text"/>
  <select name="empaque" id="empaque" class="form-text" onchange='this.form.empaque_descripcion.value =this.value;'>
<option>Seleccione Unidad de Empaque</option>
{html_options values=$option_values_empaque output=$option_output_empaque selected=$campos_item[0].unidad_empaque}
  </select>


 </td>
  </tr>
  <tr>
<td  colspan="3">Unidad Empaque**</td>
<td>
 <input type="text" name="unidad_empaque" id="unidad_empaque" size="5" value="{$campos_item[0].cantidad}" class="form-text">     <input style='border-style:none; background:none;' readonly type="text" name="empaque_descripcion" id="empaque_descripcion" size="5" class="form-text"  value="{$campos_item[0].unidad_empaque}"/>
</td>
  </tr>

<tr>
 <td class="label" colspan="3">Cantidad por Bulto**</td>
 <td>
  <input type="text" name="cantidad_bulto" id="cantidad_bulto" value="{$campos_item[0].cantidad_bulto}" size="50"  class="form-text"/>
 </td>
</tr>

<tr>
 <td class="label" colspan="3">Kilos por Bulto**</td>
 <td>
  <input type="text" name="kilos_bulto" id="kilos_bulto" size="50" value="{$campos_item[0].kilos_bulto}" class="form-text"/>
 </td>
</tr>-->
   <!--  comentado el 3 de enero 2015 para facilitar la creacion en pdval -->

<!--   <tr>
<td  colspan="3">C&oacute;digo Fabricante</td>
<td>
 <input type="text" name="codigo_fabricante" id="codigo_fabricante" size="60" value="{$campos_item[0].codigo_fabricante}" class="form-text"/>
</td>
  </tr> -->
<tr>
<td  colspan="3" class="label">Estatus</td>
<td>
 <select name="estatus" id="estatus" class="form-text" style="width:100px">
  <option {if $campos_item[0].estatus eq "A" } selected {/if} value="A">Activo</option>
  <option {if $campos_item[0].estatus eq "I" } selected {/if} value="I">Inactivo</option>
 </select>
</td>
</tr>
<tr>
<td  colspan="3" class="label">Producto con Fecha de Vencimiento**</td>
<td>
 <select name="producto_vencimiento" id="producto_vencimiento" class="form-text">
 <option value="">Indique si el Producto posee Fecha de Vencimiento</option>
  <option {if $campos_item[0].producto_vencimiento eq "Si" } selected {/if} value="Si">Si</option>
  <option {if $campos_item[0].producto_vencimiento eq "No" } selected {/if} value="No">No</option>
 </select>
</td>
</tr>
  <tr>
 <td class="label" colspan="3">Indices</td>
 <td>
  Regulado<input {if $campos_item[0].regulado eq "1" } checked {/if}type="checkbox" name="regulado" id="regulado" size="50" value="1" class="form-text"/>
  Cesta Basica<input {if $campos_item[0].cestack_basica eq "1" } checked {/if}type="checkbox" name="cestack_basica" id="cestack_basica" size="50" value="1" class="form-text"/>
  BCV<input {if $campos_item[0].bcv eq "1" } checked {/if}type="checkbox" name="bcv" id="bcv" size="50" value="1" class="form-text"/>&nbsp;&nbsp;&nbsp;SAE&nbsp;&nbsp;&nbsp;<input type="checkbox" {if $campos_item[0].sae eq "1" } checked {/if} name="sae" id="sae"  value="1" class="form-text"/>
 </td>
</tr>
 <tr>
 <td class="label" colspan="3">Producto con Serial</td>
 <td>
     <input type="checkbox" name="serial" id="serial" size="60" class="form-text" value='1'  {$campoSerial}   {literal} onclick="if (this.checked) { var aux = 'visible'; var aux2 = 'hidden'; document.getElementById('conFactor').style.visibility = 'hidden'; } else {var aux = 'hidden'; var aux2 = 'visible'; document.getElementById('conFactor').style.visibility = 'visible'; } document.getElementById('serialGarantia').style.visibility = aux;  document.getElementById('productoKit').style.visibility = aux2;  ">
 </td>
</tr>

<!--  comentado el 3 de febrero 2015 para facilitar la creacion en pdval -->

 <!--  <tr>
<td valign="top" colspan="3" width="30%" >Cuenta Contable 1</td>
<td>
 <select name="cuenta_contable1" style="width:200px;" id="cuenta_contable1" class="form-text">
  {html_options values=$option_values_cuenta output=$option_output_cuenta selected=$option_selected_cuenta1}
 </select>
</td>
  </tr> -->
  <!--  comentado el 3 de febrero 2015 para facilitar la creacion en pdval -->

<!--   <tr>
<td valign="top"  colspan="3" width="30%"  >Cuenta Contable 2</td>
<td>
 <select name="cuenta_contable2" style="width:200px;" id="cuenta_contable2" class="form-text">
  {html_options values=$option_values_cuenta output=$option_output_cuenta selected=$option_selected_cuenta2}
 </select>
</td>
  </tr> -->
  <!-- <tr>
<td colspan="4">
 <hr/>
</td>
  </tr>
  <tr>
<td  colspan="3">
 <br>
</td>
<td>
 <div id='serialGarantia' style='display:inline; visibility:hidden'>
  &nbsp;  &nbsp; &nbsp;   &nbsp;Serial Con Garant&iacute;a.
  {/literal}
<input type="checkbox" name="garantia" id="garantia" class="form-text" value='1' {$campoGarantia}/>
  </div>
  <br/>
  <div id='productoKit' style='display:inline;'>
<div id='productoKit' style='display:inline;'>
 Producto con Kit
 <input type="checkbox" name="producto_kit" id="producto_kit" class="form-text" value='1' {$campos_kit}   {literal} onclick="if (this.checked) {cargarUrl('kitproductos.php?codigo=' + this.form.cod_item.value,'productosKit'); document.getElementById('productosKit').style.visibility='visible';} else {document.getElementById('productosKit').style.visibility='hidden';ocultarVarios('productosKit');}"/>
</div>
&nbsp;&nbsp;&nbsp;&nbsp;
{/literal}
 <br/>
 <div style='  padding:10px; border:1px; border-style:solid; border-color:green; -moz-border-radius:10px; width:200px; '  >
  &nbsp;&nbsp;Tipo de Producto
  <br/>
  Nacional <input type="radio" name="tipo_producto" id="tipo_producto1" class="form-text" value='0' {$campoNacional} onchange="if (this.checked) var aux = 'hidden'; else var aux = 'visible';  document.getElementById('conFactor').style.visibility = aux;"/>
  &nbsp;   &nbsp; Importado <input type="radio" name="tipo_producto" id="tipo_producto2"  {$campoImportado}   value='1' onchange="if (this.checked) var aux = 'visible'; else var aux = 'hidden';  document.getElementById('conFactor').style.visibility = aux;  " >
  <br/>
  <div id='conFactor' style='visibility:hidden;'>
Factor de Cambio
<input type="text"  name="factor_cambios" id="factor_cambios" value="0.00"/>
<br/>
&Uacute;ltimo Costo
<input type="text" value="0.00" name="ultimo_costos" id="ultimo_costos" onchange="this.form.costo_actual.value= redondear(this.value * this.form.factor_cambios.value, 2) ; this.form.costo_actual.onchange(); "/>
  </div>
 </div>
 <br/>
  </td>
 </tr>

<div id='serial1' style='display:inline; visibility:hidden'>
<tr>
<td  colspan="3">Serial</td>
<td>
 <input size="60" type="text" name="serial1" id="serial1" value="{$campos_item[0].serial1}" class="form-text"/>
</td>
  </tr>
  </div>    -->


 <tr>
  <td  colspan="3">
Costo Actual
  </td>
  <td>
<input size="60" type="text" name="costo_actual"  id="costo_actual" onchange='this.form.precio_1.value= this.value ; this.form.costo_promedio.value= this.value; this.form.costo_anterior.value= this.value; this.form.ocultos1.value= this.value;  this.form.ocultos2.value= this.value; this.form.ocultos3.value= this.value; calcular_todo();' value="{$campos_item[0].costo_actual}" class="form-text"/>
  </td>
 </tr>
 <tr>
  <td  colspan="3">Costo Promedio</td>
  <td>
<input size="60" type="text" name="costo_promedio" id="costo_promedio" onchange='' value="{$campos_item[0].costo_promedio}" class="form-text"/>
  </td>
 </tr>
 <tr>
  <td  colspan="3">Costo Anterior</td>
  <td>
<input size="60" type="text" name="costo_anterior" id="costo_anterior" onchange='' value="{$campos_item[0].costo_anterior}" class="form-text"/>
  </td>
 </tr>
 </tbody>
</table>
</td>
</tr>
</tbody>
</table>

 <table width="100%">
  <tr>
<td>
 <br/>
 <table>
  <tr>
<td colspan="4"  align="center">&nbsp;</td>
  </tr>
  <tr>
<td  colspan="4" align="center" width="180"></td>
  </tr>
  <tr>
<td  colspan="3" align="right">&nbsp;&nbsp;&nbsp;</td>
<td>
 <table id="tabla_total" style="border: 1px solid #507e95;" bgcolor="white">
  <thead>
<tr>
 <th align="left">Costos</th>
 <th align="left">Utilidad %</th>
 <th align="left">Con Utilidad</th>
 <th align="left">Gravado</th>
</tr>
  </thead>
  <tbody>
<tr>
 <td>
  <input type='text' name='ocultos1' id='ocultos1' value="{$campos_item[0].p1}" onchange=" calcular_todo();" size="9" class="form-text"/>
 </td>
 <td>
  <input type="text" onchange="calcularMonto(this.form.precio_1,this,this.form.coniva1,this.form.ocultos1);" class="campo_decimal" size="3" name="utilidad1" id="utilidad1" name="utilidad1" value="{$campos_item[0].utilidad1}" class="form-text"/>%</td>
 <td>
  <input type="text" onchange='calcular_todo();' class="campo_decimal" title="{$DatosGenerales[0].titulo_precio1}" id="fila_precio1" name="precio_1" value="{$campos_item[0].precio1}" size="10" readonly  class="form-text"/>
 </td>
 <td><input type="text" class="campo_decimal" id="fila_precio1_iva" name="coniva1" size="10" value="{$campos_item[0].coniva1}" class="form-text"/></td>
</tr>
<tr>
 <td><input type='text' id="ocultos2" name='ocultos2' value="{$campos_item[0].p2}" onchange=" calcular_todo();" size="9" class="form-text"/></td>
 <td><input type="text" onchange="calcularMonto(this.form.precio_2,this,this.form.coniva2,this.form.ocultos2);" class="campo_decimal" size="3"  name="utilidad2" id="utilidad2" value="{$campos_item[0].utilidad2}" class="form-text"/>%</td>
 <td><input type="text" onchange='this.form.oculto2.value=this.value; calcular_todo();' class="campo_decimal" title="{$DatosGenerales[0].titulo_precio2}" id="fila_precio2" name="precio_2" readonly value="{$campos_item[0].precio2}" size="10" class="form-text"/></td>
 <td><input type="text" class="campo_decimal" id="fila_precio2_iva" value="{$campos_item[0].coniva2}" name="coniva2" size="10" class="form-text"/></td>
</tr>
<tr>
 <td><input type='text' id="ocultos3" name='ocultos3' value="{$campos_item[0].p3}" onchange=" calcular_todo();" size="9" class="form-text"/></td>
 <td><input type="text" onchange="calcularMonto(this.form.precio_3,this,this.form.coniva3,this.form.ocultos3);" class="campo_decimal" value="{$campos_item[0].utilidad3}" size="3" name="utilidad3" id="utilidad3" class="form-text"/>%</td>
 <td><input type="text" onchange='this.form.oculto3.value=this; calcular_todo();' class="campo_decimal" title="{$DatosGenerales[0].titulo_precio3}" id="fila_precio3" name="precio_3" readonly  value="{$campos_item[0].precio3}" size="10" class="form-text"/></td>
 <td><input type="text" class="campo_decimal" id="fila_precio3_iva"  value="{$campos_item[0].coniva3}" name="coniva3" size="10" class="form-text"/></td>
</tr>
<tr>
 <td colspan="4"  align="center">
  &nbsp;
 </td>
</tr>
<tr>
 <td valign="top"  colspan="3"><b>Existencia M&iacute;nima</b></td>
 <td>
  <div class="string_empaque"></div>
  <input type="text" onkeypress="return validarNumero(event)" name="existencia_min" value="{$campos_item[0].existencia_min}" class="form-text"/>
 </td>
</tr>
<tr>
 <td  colspan="3"><b>Existencia M&aacute;xima</b></td>
 <td>
  <div class="string_empaque"></div>
  <input type="text" onkeypress="return validarNumero(event)" name="existencia_max" value="{$campos_item[0].existencia_max}" class="form-text"/>
 </td>
</tr>
<tr>
 <td colspan="4"  align="center">IMPUESTOS</td>
</tr>
<tr>
 <td colspan="3"  align="left">Monto Exento</td>
 <td>
  <select name="monto_exento" id="monto_exento" onchange="calcular_todo();" class="form-text">
<option value="0">No</option>
<option value="1" {if $campos_item[0].monto_exento eq 1} selected {/if}>S&iacute;</option>
<!--{html_options values=$option_values_item_exento selected=$option_selected_item_exento output=$option_output_item_exento}-->
  </select>
 </td>
</tr>
<tr class="monto_iva">
 <td colspan="3"  align="left">{$DatosGenerales[0].nombre_impuesto_principal}</td>
 <td>
  <!-- <input class="campo_decimal" type="text" value="{*$parametros_generales[0].porcentaje_impuesto_principal*}" id="iva" name="iva"/> -->
  <!--input class="campo_decimal" type="text" value="{*$valor_iva[0].iva*}" id="iva" name="iva"/-->
  <select name="iva" id="iva" class="form-text" onchange="calcular_todo();" class="form-text">
{html_options values=$option_values_porcentaje_impuesto_principal selected=$option_selected_porcentaje_impuesto_principal output=$option_output_porcentaje_impuesto_principal}
  </select>
 </td>
</tr>

  </tbody>
 </table>
 </table>
</td>
<td style='left:100px;'>
 <table style='width:200px; margin-left:200px; height:200px; overflow:auto; background:white; -moz-border-radius:20px; border-style:none;'>
  <tr>
<td colspan='2'> Existencia en Almac&eacute;n  </td>
  </tr>
  <tr>
<td style='background:#ccccdd; color:green; font-size:12px ; height:20px; font-weight:bold;'> Nombre Almac&eacute;n </td>
<td style='background:#ccccdd; color:green; font-size:12px ; height:20px; font-weight:bold;'> Ubicacion</td>
<td style=' color:green; height:20px; background:#ccccdd; width:30px'> Cantidad Existencia</td>
  </tr>
  {foreach from =$almacenes2 key = i item = miItem}
<tr>
 <td style='color:blue; font-size:12px ; font-weight:bold;'>{$miItem.descripcion}</td>
 <td style='color:blue; font-size:12px ; font-weight:bold;'>{$miItem.ubicacion}</td>
 <td style='background:#dddddd; width:30px'>{$miItem.cantidad}</td>
</tr>
  {/foreach}
 </table>
 </div>
</td>
  </tr>
 </table>


  </div>
  <!-- /TAB1 -->
  <!--
  ***************************************************************************************************************************
  ***************************************************************************************************************************
  -->
<div id="div_tab2">
<table style="width: 100%; height: 100px;">

<tr>
 <td colspan="3" class="label" style="width: 30%;">Proveedor</td>
 <td>
  <!--<input type="text" name="proveedor" id="proveedor" value="{$campos_item[0].proveedor}" size="50"  class="form-text"/>-->
  <select name="proveedor" style="width:200px;" id="proveedor" class="form-text">
  <option value=''>Seleccione</option>
  {html_options values=$option_values_prov output=$option_output_prov selected=$option_selected_prov}
 </select>
 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Fecha Ingreso</td>
 <td>
  <input type="text" name="fecha_ingreso" id="fecha_ingreso" size="50" value="{$campos_item[0].fecha_ingreso}"  class="form-text"/>

 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Origen</td>
 <td>
  <input type="text" name="origen" id="origen" size="50" value="{$campos_item[0].origen}" class="form-text"/>

 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Costo Cif</td>
 <td>
  <input type="text" name="costo_cif" id="costo_cif" size="50" value="{$campos_item[0].costo_cif}" class="form-text"/>

 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Costo Origen</td>
 <td>
  <input type="text" name="costo_origen" id="costo_origen" size="50" value="{$campos_item[0].costo_origen}" class="form-text"/>

 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Temporada</td>
 <td>
  <input type="text" name="temporada" id="temporada" size="50" value="{$campos_item[0].temporada}" class="form-text"/>

 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Material/Composicion/Clase</td>
 <td>
  <input type="text" name="mate_compo_clase" id="mate_compo_clase" size="50" value="{$campos_item[0].mate_compo_clase}" class="form-text"/>

 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Punto pedido</td>
 <td>
  <input type="text" name="punto_pedido" id="punto_pedido" size="50" value="{$campos_item[0].punto_pedido}" class="form-text"/>

 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Tejido</td>
 <td>
  <input type="text" name="tejido" id="tejido" size="50" value="{$campos_item[0].tejido}" class="form-text"/>

 </td>
</tr>

<!--<tr>
 <td colspan="3" width="30%" class="label">Reg. Sanit.</td>
 <td>
  <input type="text" name="reg_sanit" id="reg_sanit" size="50" value="{$campos_item[0].reg_sanit}" class="form-text"/>

 </td>
</tr>-->

<tr>
 <td colspan="3" width="30%" class="label">Cod. barra bulto</td>
 <td>
  <input type="text" name="cod_barra_bulto" id="cod_barra_bulto" size="50" value="{$campos_item[0].cod_barra_bulto}" class="form-text"/>

 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Observaciones</td>
 <td>
  <textarea rows="5" cols="50" name="observacion" >
  {$campos_item[0].observacion}
  </textarea>

 </td>
</tr>


</table>


</div>
<!-- /TAB2 -->
<div id="div_tab3">


<table style="width: 100%; height: 100px;">
<tr>
 <td  class="label" >Foto 1</td>
 <td>
  <input type="file" name="foto1" id="foto1"  class="form-text"/>

 </td>
   <td  align="center">
<img src="../../imagenes/{$campos_item[0].foto1}" width="100" align="absmiddle" height="100"/>
 </td>
</tr>

<tr>
 <td  width="30%" class="label">Foto 2</td>
 <td>
   <input type="file" name="foto2" id="foto2"  class="form-text"/>
 </td>
  <td  align="center">
<img src="../../imagenes/{$campos_item[0].foto2}" width="100" align="absmiddle" height="100"/>
 </td>
</tr>

<tr>
 <td  width="30%" class="label">Foto 3</td>
 <td>
   <input type="file" name="foto3" id="foto3"  class="form-text"/>

 </td>
   <td  align="center">
<img src="../../imagenes/{$campos_item[0].foto3}" width="100" align="absmiddle" height="100"/>
 </td>
</tr>

<tr>
 <td  width="30%" class="label">Foto 4</td>
 <td>
   <input type="file" name="foto4" id="foto4"  class="form-text"/>

 </td>
   <td  align="center">
<img src="../../imagenes/{$campos_item[0].foto4}" width="100" align="absmiddle" height="100"/>
 </td>
</tr>

</table>

</div>
<div id="div_tab4">
<table style="width: 100%; height: 100px;">
<tr>
 <td colspan="3" class="label" style="width: 30%;">Contrato Licencia Nro</td>
 <td>
  <input type="text" name="cont_licen_nro" id="cont_licen_nro" size="50" value="{$campos_item[0].cont_licen_nro}" class="form-text"/>

 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Precio de Contrato</td>
 <td>
  <input type="text" name="precio_cont" id="precio_cont" size="50" value="{$campos_item[0].precio_cont}" class="form-text"/>

 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Aprobacion de Arte</td>
 <td>
  <input type="text" name="aprob_arte" id="aprob_arte" size="50" value="{$campos_item[0].aprob_arte}" class="form-text"/>

 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Propiedad</td>
 <td>
  <input type="text" name="propiedad" id="propiedad" size="50" value="{$campos_item[0].propiedad}" class="form-text"/>

 </td>
</tr>

</table>
</div>

<!-- tab 5  -->
<div id="div_tab5">
<table style="width: 100%;">
<tr>
 <td class="label" colspan="3">Empaque**</td>
 <td>
  <!--<input type="text" name="empaque" id="empaque" size="50" value="{$campos_item[0].unidad_empaque}" onchange='this.form.empaque_descripcion.value = this.value;' class="form-text"/>-->
  <select name="empaque" id="empaque" class="form-text" onchange='this.form.empaque_descripcion.value = this.form.empaque.options[this.form.empaque.selectedIndex].text;'(s)>
<option>Seleccione Unidad de Empaque</option>
{html_options values=$option_values_empaque output=$option_output_empaque selected=$campos_item[0].unidad_empaque}
  </select>
 </td>
</tr>
<tr>
 <td colspan="3" width="30%" class="label">Cod. barra bulto</td>
 <td>
  <input type="text" name="cod_barra_bulto" id="cod_barra_bulto" size="50" class="form-text" value="{$campos_item[0].cod_barra_bulto}"/>

 </td>
</tr>
<tr>
 <td class="label" colspan="3">Cantidad por Bulto**</td>
 <td>
  <input type="text" name="cantidad_bulto" id="cantidad_bulto" size="50"  class="form-text" value="{$campos_item[0].cantidad_bulto}"/>
 </td>
</tr>

<tr>
 <td class="label" colspan="3">Kilos por Bulto**</td>
 <td>
  <input type="text" name="kilos_bulto" id="kilos_bulto" size="50"  class="form-text" value="{$campos_item[0].kilos_bulto}" disabled="disabled" />
 </td>
</tr>
</table>
</div>


</div>
<!-- +++++++++++++++ -->
 </div>

 <input type="hidden" name="pg_iva" id="pg_iva" value="{$parametros_generales[0].porcentaje_impuesto_principal}"/>

<table style="width: 100%;">
<tr class="tb-tit" align="right">
 <td colspan="4">
  <input type="submit" id="aceptar" name="aceptar" value="Guardar"/>
  <input type="button" name="cancelar" onclick="javascript: document.location.href='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}';" value="Cancelar"/>
 </td>
</tr>
 </table>
</form>



<script type="text/javascript">//<![CDATA[
 {literal}
var mikit= document.getElementById('producto_kit');
mikit.onclick();
var aux= document.getElementById('serial');
aux.onclick();
var aux= document.getElementById('tipo_producto1');
aux.onchange();
var aux= document.getElementById('tipo_producto2');
aux.onchange();
 {/literal}
 //]]>
</script>
  </body>
 </html>