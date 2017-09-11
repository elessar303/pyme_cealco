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

            function buscarProducto(id,nombre,empaque,cantidad)
            {
                var miId = document.getElementById(id);
                var miItem = document.getElementById('cod_item');

                cargarUrl('buscarProducto.php?codigo=' + miId.value + '&campo=' + id + '&nombre=' + nombre + '&empaque=' + empaque + '&cantidad=' + cantidad, 'TRANSPARENTE' );
                setTimeout ("sumaTotal()", 3000);
            }

            function sumaTotal()
            {
                var total = 0;

                for(var i=1;i<=20;i++){
                    var aux = document.getElementById('sub_total' + i);
                    total = (total * 1) + (aux.value * 1);
                }

                var aux2 = document.getElementById('suma_total');
                    aux2.value = redondear(total);
                var aux3 = document.getElementById('costo_actual');
                    aux3.value = aux2.value;
                    aux3.onchange();
            }

            function agregarItem(codigo)
            {
                var aux = document.getElementById('id0');
                    aux.value=codigo;
                    aux.onchange();
                var aux = document.getElementById('busqueda');
                    aux.value='';
                var aux = document.getElementById('cuadroBusqueda');
                    aux.innerHTML='Inserte palabras claves del producto a seleccionar';
            }

            function buscarProductoLista(cadena)
            {
                cargarUrl('buscarProductoLista.php?cadena=' + cadena , 'cuadroBusqueda' );
            }

            function ocultarVarios(objeto)
            {
                var aux = document.getElementById(objeto);
                var e=aux.getElementsByTagName("input");

                for(var i=0;i<e.length;i++){
                    e[i].style.visibility = 'hidden';
                }

                var e=aux.getElementsByTagName("img");

                for(var i=0;i<e.length;i++){
                    e[i].style.visibility = 'hidden';
                }
            }

            function calcularPrecio(cantidad,precio,sub_total)
            {
                var cant = cantidad.value;
                var monto = document.getElementById(precio).value;
                var sub = document.getElementById(sub_total);
                    sub.value = redondear(cant * monto);
                sumaTotal();
            }

            function eliminarProducto(posicion)
            {
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
                /*
                Función modificada para hacer funcionar correctamente
                la configuración de productos exentos o no de IVA.
                */

                // var exonerado = document.getElementById('monto_exento');
                // var iva = document.getElementById('iva');
                iva=$("#iva").val();         
                // precio.value = parseFloat(ocultos.value * (1 + (parseFloat(utilidad.value) / 100)));
                // precio.value = redondear(precio.value,2);
                var utilidad=$("#"+utilidad).val();
                if(utilidad=="")
                    utilidad=0;
                
                $("#"+precio).val( parseFloat( $("#"+ocultos).val() * (1 + (parseFloat(utilidad) / 100))));
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

            function calcular_todo()
            {
                var aux= document.getElementById('formulario');
                // calcularMonto(aux.precio_1,aux.utilidad1,aux.coniva1,aux.ocultos1);
                // calcularMonto(aux.precio_2,aux.utilidad2,aux.coniva2,aux.ocultos2);
                // calcularMonto(aux.precio_3,aux.utilidad3,aux.coniva3,aux.ocultos3);
                calcularMonto("fila_precio1","utilidad1","fila_precio1_iva","ocultos1");
                calcularMonto("fila_precio2","utilidad2","fila_precio2_iva","ocultos2");
                calcularMonto("fila_precio3","utilidad3","fila_precio3_iva","ocultos3");
              
            }

            function agregar_impuesto(){
              var fieldset=document.getElementById("tabla_total");
              var inputs=document.getElementsByName("alternativa");
              //var br=document.createElement("br");
              var newInput=document.createElement("select");
              newInput.className = 'form-text';
              var tr=document.createElement("tr");
              var td=document.createElement("td");

              //var impuestos = '{/literal}{$key_impuestos}{literal}';
            {/literal}
            {foreach from=$lista_impuestos key=index item=impuestos}
                {literal}
                var descripcion = '{/literal}{$impuestos.descripcion}{literal}';
                var option = document.createElement('option');
                option.text = descripcion;
                option.value = '{/literal}{$index}{literal}';
                newInput.add(option, '{/literal}{$index}{literal}');
                {/literal}
            {/foreach}
            {literal}

              td.colSpan = "4";
              //newInput.name="alternativa";
              ////newInput.size=100;
              //newInput.id="alt"+(inputs.length+1);

              var btnRemove=document.createElement("input");
              btnRemove.type="button";
              btnRemove.value="Elmininar";

              tr.appendChild(td);
              tr.className = 'label';
              td.appendChild(newInput);
              td.appendChild(btnRemove);

              var tbody = document.getElementById("tbody");
              tbody.appendChild(tr);

              btnRemove.onclick = function(){
                var elem = this.parentNode;
                elem.parentNode.removeChild(elem);
              };
            }

               


            $(document).ready(function(){
                $("#cod_departamento").focus();
                $("#cod_item").blur(function(){
                    return false;
                    vcoditem = $(this).val();
                    if(vcoditem!=''){
                        $.ajax({
                            type: "GET",
                            url:  "../../libs/php/ajax/ajax.php",
                            data: "opt=ValidarCodigoitem&v1="+vcoditem,
                            beforeSend: function(){
                                $("#notificacionVCoditem").html(MensajeEspera("<b>Veficando Cod. item..<b>"));
                            },
                            success: function(data){
                                resultado = data;
                                if(resultado=="-1"){
                                    //$("#cod_item").val("").focus();
                                    $("#notificacionVCoditem").html("<img align=\"absmiddle\" src=\"../../libs/imagenes/ico_note.gif\"><span style=\"color:red;\"><b>Disculpe, este c&oacute;digo ya existe.</b></span>");
                                }
                                else if(resultado=="1"){//cod de item disponble, originalmente sin "else"
                                    $("#notificacionVCoditem").html("<img align=\"absmiddle\" src=\"../../libs/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> C&oacute;digo Disponible</b></span>");
                                }
                            }
                        });
                        }
                    });
                   
                    $("#formulario").submit(function(){
                      // validacion del formulario producto
                        if( 
                            $("#cod_barras").val()=="" ||
                            $("#descripcion1").val()=="" ||
                            $("#producto_vencimiento").val()==""
                          ){
                            alert("Debe Ingresar los campos obligatorios!.");
                            $("#descripcion1").focus();
                            return false;
                        }
                            if(

                                $("#cod_barras").val()=='0' ||
                                $("#cod_barras").val()=='00' ||
                                $("#cod_barras").val()=='000' ||
                                $("#cod_barras").val()=='0000' ||
                                $("#cod_barras").val()=='00000' ||
                                $("#cod_barras").val()=='000000' ||
                                $("#cod_barras").val()=='0000000' ||
                                $("#cod_barras").val()=='00000000' ||
                                $("#cod_barras").val()=='000000000' ||
                                $("#cod_barras").val()=='0000000000'
                                )
                        {
                        alert("Codigo de Barras debe ser Distinto de 0.");
                        $("#cod_barras").focus();
                        return false;
                        }
                        
                    });

                   // agregado el 22/01/14 para regargar el select dependiente de rubro y sub rubro
                        function listarSubrubro(idSelect, tipoSql, idCarga){
                            var paramentros="opt=cargarSubrubro&idCarga="+idCarga+"&tipoSql="+tipoSql;
                            $.ajax({
                                type: "POST",
                                url: "../../libs/php/ajax/ajax.php",
                                data: paramentros,
                                beforeSend: function(datos){
                                    $("#"+idSelect).html('<option value = 0> Cargando... </option>');
                                },
                                success: function(datos){
                                    $("#"+idSelect).html(datos);
                                },
                                error: function(datos,falla, otroobj){
                                    $("#"+idSelect).html('<option value = 0> Error... </option>');
                                }
                            });
                    };
                   // fin de la funcion para select dependiente
                   // llamada de la funcion para cargar el select dependiente de subrubro
                        id_rubro=$("#cod_departamento").val();                        
                        listarSubrubro("cod_grupo", 0, id_rubro);
                    // cuando cambia rubro salta la funcion ajax
                       $("#cod_departamento").change(function(){
                              id_rubro=$("#cod_departamento").val(); 
                              listarSubrubro("cod_grupo", 0, id_rubro);
                       });
                   //fin de la llamada
                   // $(".camposP").focus(function(event) {
                   //     $(this).val("");
                   // });
                
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
                    border-radius: 5px;
                    padding: 2px;
                    border: 1px solid #adafb0;
                    width:550px;
                }
                #tabs {
                    margin-top:15px;
                }
            </style>
        {/literal}
</head>
<body>
<form name="formulario" id="formulario" method="post" enctype="multipart/form-data" action="">
    <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
    <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
    <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>

<table style="width:100%;">
    <tbody>
        <tr>
            <td class="tb-tit">
            <img src="{$subseccion[0].img_ruta}" width="20" height="20" style="vertical-align: middle;"/><strong>{$subseccion[0].descripcion}</strong>
            </td>
        </tr>
    </tbody>
</table>

<div id="tabs">
    <table style="margin-left:20px;">
        <tr style="height:25px;">
            <td id="tab1" class="tab">
 <!--<img src="../../libs/imagenes/1.png" width="20" height="20" style="vertical-align: middle;"/>&nbsp;<b>Datos Generales</b>-->
            <b>Datos Generales</b>
            </td>
<!--<td id="tab2" class="tab">
  <b>Datos Particulares</b> 
</td>
<td id="tab3" class="tab">
 <b>Fotos</b>
</td>
<td id="tab4" class="tab">
 <b>Licencia</b>
</td>
<td id="tab5" class="tab">
 <b>Precios</b>
</td>
<td id="tab5" class="tab">
 <b>Datos Compra</b>
</td>-->
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
 <td colspan="4" class="label" style="text-align: center;">COMPLETE LOS CAMPOS MARCADOS CON&nbsp;** OBLIGATORIAMENTE</td>
</tr>
<tr hidden="hidden">
 <td colspan="3" class="label" style="width: 30%;">Foto</td>
 <td>
  <input type="file" name="foto" id="foto"  class="form-text"/>
  
 </td>
</tr>
<tr>
 <td colspan="3" width="30%" class="label">Fecha de Creaci&oacute;n</td>
 <td>
  <input type="text" name="fecha_ingreso" id="fecha_ingreso" size="50" value="{$smarty.now|date_format:'%e-%m-%G'}" class="form-text" disabled="disabled" />
  
 </td>
</tr>
<tr hidden="hidden">
 <td colspan="3" class="label" style="width: 30%;">&Uacute;ltimo C&oacute;digo</td>
 <td>
  <input type="text" name="ultimo_codigo" id="ultimo_codigo" size="50" value="{$nro_productoOLD}" disabled class="form-text"/>
  <div id="notificacionVCoditem"></div>
 </td>
</tr>
<tr hidden="hidden">
 <td colspan="3" width="30%" class="label">C&oacute;digo **</td>
 <td>
  <input type="text" name="cod_item" id="cod_item" size="50" value="{$nro_productoNEW}" class="form-text"/>
  <div id="notificacionVCoditem"></div>
 </td>
</tr>
<!--tr>
 <td class="label" colspan="4" align="center" width="180">DATOS DEL item</td>
</tr-->
<tr>
 <td colspan="3" class="label"><!-- {$DatosGenerales[0].string_clasificador_inventario1} -->Rubro</td>
 <td>
  <select name="cod_departamento" id="cod_departamento" class="form-text">
{html_options values=$option_output_departamentos output=$option_values_departamentos}
  </select>
 </td>
</tr>
<tr>
 <td colspan="3" class="label"><!-- {$DatosGenerales[0].string_clasificador_inventario2} -->Sub Rubro</td>
 <td>
  <select name="cod_grupo" id="cod_grupo" class="form-text">
<!-- {html_options values=$option_output_grupo output=$option_values_grupo} -->
  </select>
 </td>
</tr>
<!--  comentado el 3 de nenero 2015 para facilitar la creacion en pdval -->
<!-- <tr>
 <td colspan="3" class="label">{$DatosGenerales[0].string_clasificador_inventario3}</td>

 <td> 
  <select name="cod_linea" id="cod_linea" class="form-text">{html_options values=$option_output_linea output=$option_values_linea}</select>
 </td>
</tr> -->
<tr>
 <td colspan="3" class="label">C&oacute;digo de barras**</td>
 <td>
  <input type="text" name="cod_barras" id="cod_barras" size="50" value="{$campos_item[0].codigo_barras}" class="form-text"/>
 </td>
</tr>
<tr hidden="hidden">
 <td colspan="3" width="30%" class="label">Cod. CPE</td>
 <td>
  <input type="text" name="cod_cpe" id="cod_cpe" size="50" value="" class="form-text"/>
  
 </td>
</tr> 
<tr>
 <td colspan="3" class="label">Descripci&oacute;n 1 **</td>
 <td>
  <input type="text" name="descripcion1" id="descripcion1" size="50" value="{$campos_item[0].descripcion1}" class="form-text"/>
 </td>
</tr>
<!--  comentado el 3 de nenero 2015 para facilitar la creacion en pdval -->
<!-- <tr>
 <td class="label" colspan="3">Descripci&oacute;n 2<td>
  <input type="text" name="descripcion2" id="descripcion2" size="50" value="{$campos_item[0].descripcion2}" class="form-text"/>
 </td>
</tr>
<tr>
 <td class="label" colspan="3">Descripci&oacute;n 3</td>
 <td>
  <input type="text" name="descripcion3" id="descripcion3" size="50" value="{$campos_item[0].descripcion3}" class="form-text"/>
 </td>
</tr>
-->
<tr hidden="hidden"> 
 <td class="label" colspan="3">Referencia</td>
 <td>
  <input type="text" name="referencia" id="referencia" size="50" value="{$campos_item[0].referencia}" class="form-text"/>
 </td>
</tr>
<tr>
 <td class="label" colspan="3">Unidad Venta</td>
 <td>
  
   <!--<input type="text" name="unidad_empaque" id="unidad_empaque" size="5" value="{$campos_item[0].cantidad}" class="form-text"/>
  <input type="text" name="empaque_descripcion" id="empaque_descripcion" size="5" value="{$campos_item[0].unidad_empaque}" readonly style="border-style:none; background:none;" class="form-text"/>-->
  <select name="unidad_venta" id="unidad_venta" class="form-text">
<option value="0">Seleccione Unidad de Venta</option>
{html_options values=$option_values_unidad_venta output=$option_output_unidad_venta}
  </select>
 
  
 </td>
</tr>

<!-- <tr>
 <td class="label" colspan="3">Unidad de Peso**</td>
 <td>
   <select name="unidadxpeso" id="unidadxpeso" class="form-text">
<option>Seleccione Peso por Unidad</option>
{html_options values=$option_values_unidadxpeso output=$option_output_unidadxpeso}
  </select>
 </td>
</tr> 

<tr>
<td  colspan="3">
Peso por Unidad
</td>
<td>
 <input type="text" name="pesoxunidad" id="pesoxunidad" size="10" value="{$campos_item[0].pesoxunidad}" class="form-text" onKeyPress="return soloNumeros(event)"/>
</td>
</tr>


<tr>
 <td class="label" colspan="3">Tipo de Producto</td>
 <td>
  <select name="tipo" id="tipo" class="form-text">
<option>Seleccione el uso del producto</option>
{html_options values=$option_values_tipo output=$option_output_tipo selected=$option_selected_tipo}
  </select>
 </td>
</tr>
 comentado el 3 de nenero 2015 para facilitar la creacion en pdval 
 -->
<tr hidden="hidden">
 <td class="label" colspan="3">Marca</td>
 <td>
   <select name="marca" id="marca" class="form-text">
<option value="0">Seleccione Marca del Producto</option>
{html_options values=$option_values_marca output=$option_output_marca}
  </select>
  <input type="text" name="codigo_fabricante" id="codigo_fabricante" size="50" value="{$campos_item[0].codigo_fabricante}" class="form-text"/>
 </td>
</tr> 
<tr>
 <td class="label" colspan="3">Estatus</td>
 <td>
  <select name="estatus" id="estatus" class="form-text">
<option value="A">Activo</option>
<option value="I">Inactivo</option>
  </select>
 </td>
</tr>

<tr>
<td  colspan="3" class="label">Producto con Fecha de Vencimiento</td>
<td>
 <select name="producto_vencimiento" id="producto_vencimiento" class="form-text">
 <option value="">Indique si el Producto posee Fecha de Vencimiento**</option>
  <option {if $campos_item[0].producto_vencimiento eq "Si" } selected {/if} value="Si">Si</option>
  <option {if $campos_item[0].producto_vencimiento eq "No" } selected {/if} value="No">No</option>
 </select>
</td>
</tr>

<!-- <tr>
 <td class="label" colspan="3">Indices</td>
 <td>
  Regulado&nbsp;&nbsp;<input type="checkbox" name="regulado" id="regulado"  value="1" class="form-text"/> &nbsp;&nbsp;Cestack Basica&nbsp;&nbsp;<input type="checkbox" name="cesta" id="cesta"  value="1" class="form-text"/>&nbsp;&nbsp;&nbsp;BCV&nbsp;&nbsp;&nbsp;<input type="checkbox" name="bcv" id="bcv"  value="1" class="form-text"/>
 </td>
</tr> -->
<tr>
 <td class="label" colspan="3"> Producto con Serial</td>
 <td>
    <input type="checkbox" name="serial" id="serial" size="50" value='1' {$campoSerial} {literal} onclick="if (this.checked) { var aux = 'visible'; var aux2 = 'hidden'; document.getElementById('conFactor').style.visibility = 'hidden';} else {var aux = 'hidden'; var aux2 = 'visible'; document.getElementById('conFactor').style.visibility = 'visible'; } document.getElementById('serialGarantia').style.visibility = aux;  document.getElementById('productoKit').style.visibility = aux2;" class="form-text"/>
 </td>
</tr>
<!--  comentado el 3 de nenero 2015 para facilitar la creacion en pdval -->
<!-- <tr>
 <td colspan="3" style="vertical-align: top; width: 30%;" class="label">Cuenta Contable 1</td>
 <td>
  <select name="cuenta_contable1" id="cuenta_contable1" class="form-text">
{html_options values=$option_values_cuenta output=$option_output_cuenta}
  </select>
 </td>
</tr>
<tr>
 <td colspan="3" style="vertical-align: top; width: 30%;" class="label">Cuenta Contable 2</td>
 <td>
  <select name="cuenta_contable2" id="cuenta_contable2" class="form-text">
{html_options values=$option_values_cuenta output=$option_output_cuenta}
  </select>
 </td>
</tr> -->
<!-- <tr><td colspan="4"><hr/></td></tr> -->
<!--  comentado el 3 de nenero 2015 para facilitar la creacion en pdval -->
<!-- <tr>
 <td class="label" colspan="3"><br/></td>
 <td>
 
  
  <div id='serialGarantia' style='display:inline; visibility:hidden'>
&nbsp;&nbsp;&nbsp;&nbsp;Serial Con Garant&iacute;a.
{/literal} -->
<!--  <input type="checkbox" name="garantia" id="garantia" value='1' {$campoGarantia} class="form-text"/>
</div>
<br/>
<div id='productoKit' style='display:inline;'>
 <div id='productoKit' style='display:inline;'>
  Producto con Kit
  <input type="checkbox" name="producto_kit" id="producto_kit" value='1' {$campos_kit} {literal} onclick="if (this.checked) {cargarUrl('kitproductos.php?codigo=' + this.form.cod_item.value,'productosKit'); document.getElementById('productosKit').style.visibility='visible';} else {document.getElementById('productosKit').style.visibility='hidden'; ocultarVarios('productosKit');}" class="form-text"/>
 </div> -->
 &nbsp;&nbsp;&nbsp;&nbsp;
<!--  {/literal}
  <br/>
  <div style='padding:10px; border:1px; border-style:solid; border-color:green; -moz-border-radius:10px; width:200px;'>
&nbsp;&nbsp;Tipo de Producto
<br/>
Nacional <input type="radio" name="tipo_producto" id="tipo_producto1" value='0' {$campoNacional} onchange="if (this.checked) var aux = 'hidden'; else var aux = 'visible'; document.getElementById('conFactor').style.visibility = aux; " class="form-text"/>
&nbsp;&nbsp; Importado <input type="radio" name="tipo_producto" id="tipo_producto2" {$campoImportado} value='1' onchange="if (this.checked) var aux = 'visible'; else var aux = 'hidden'; document.getElementById('conFactor').style.visibility = aux;" class="form-text"/>
<br/>
<div id='conFactor' style='visibility:hidden;'> -->
<!--  Factor de Cambio
 <input type="text" name="factor_cambios" id="factor_cambios" value="0.00" class="form-text"/>
 <br/>
 &Uacute;ltimo Costo
 <input type="text" value="0.00" name="ultimo_costos" id="ultimo_costos" onchange="this.form.costo_actual.value= redondear(this.value * this.form.factor_cambios.value, 2) ; this.form.costo_actual.onchange();" class="form-text"/>
</div>
  </div>
  <br/>
</td>
  </tr> -->
<!-- <div id='serial1' style='display:inline; visibility:hidden'>
<tr>
<td class="label" colspan="3">Serial</td>
<td>
 <input size="50" type="text" name="serial1" id="serial1" value="{$campos_item[0].serial1}" class="form-text"/>
</td>
  </tr>
  </div> 
<tr>
<td class="label" colspan="3">Costo Actual**</td>
<td>
 <input size="50" type="text" name="costo_actual" id="costo_actual" onchange='this.form.precio_1.value= this.value ; this.form.costo_promedio.value= this.value; this.form.costo_anterior.value= this.value; this.form.ocultos1.value= this.value;  this.form.ocultos2.value= this.value; this.form.ocultos3.value= this.value; calcular_todo();' value="{$campos_item[0].costo_actual}" class="form-text"/>
</td>
</tr>
<tr>
<td class="label" colspan="3">Costo Promedio</td>
<td>
 <input size="50" type="text" name="costo_promedio" id="costo_promedio" value="{$campos_item[0].costo_promedio}" class="form-text"/>
</td>
  </tr>
  <tr>
<td class="label" colspan="3">Costo Anterior</td>
<td>
 <input size="50" type="text" name="costo_anterior" id="costo_anterior" value="{$campos_item[0].costo_anterior}" class="form-text"/>
</td>
  </tr>
  -->
  </tbody>
 </table>
 <!--/td>
 </tr>
 </tbody>
 </table-->
                            
                            
<table style="width: 100%;">
<tr>
 <td>
  <br/>
  <table>
<tr>
 <td colspan="4" class="label" style="text-align:center;">&nbsp;</td>
</tr>
<tr>
 <td class="label" colspan="4" align="center" width="180"></td>
</tr>
<tr>
 <td class="label" colspan="3" style="text-align:right">&nbsp;&nbsp;&nbsp;</td>
 <td>
  <table id="tabla_total" style="border: 1px solid #507e95; background-color: white;">
<thead>
 <tr>
  <th style="text-align:left;">Costos</th>
  <th style="text-align:left;">Utilidad</th>
  <th style="text-align:left;">Con Utilidad</th>
  <th style="text-align:left;">Gravado</th>
 </tr>
</thead>
<tbody id="tbody">
 <tr>
  <td>
<input type='text' name='ocultos1' id='ocultos1' value="{$campos_item[0].p1}" onchange="calcular_todo();" size="9" class="form-text"/>
  </td>
  <td>
<input onchange="calcularMonto('fila_precio1','utilidad1','fila_precio1_iva','ocultos1');" class="campo_decimal form-text" size="3" name="utilidad1" id="utilidad1" type="text" value="{$campos_item[0].utilidad1}" />
<label for="utilidad1">%</label>
  </td>
  <td>
<input onchange='calcular_todo();' class="campo_decimal form-text" title="{$DatosGenerales[0].titulo_precio1}" id="fila_precio1" name="precio_1" value="{$campos_item[0].precio1}" size="10" readonly type="text" />
  </td>
  <td>
<input class="campo_decimal form-text" id="fila_precio1_iva" name="coniva1" size="10" type="text" value="{$campos_item[0].coniva1}"/>
  </td>
 </tr>
 <tr>
  <td><input type='text' id='ocultos2' name='ocultos2' value="{$campos_item[0].p2}" onchange="calcular_todo();" size="9" class="form-text"></td>
  <td>
<input onchange="calcularMonto('fila_precio2','utilidad2','fila_precio2_iva','ocultos2');" class="campo_decimal form-text" size="3" name="utilidad2" id="utilidad2" type="text" value="{$campos_item[0].utilidad2}">
<label for="utilidad2">%</label>
  </td>
  <td><input onchange='this.form.oculto2.value=this.value; calcular_todo();' class="campo_decimal form-text" title="{$DatosGenerales[0].titulo_precio2}" id="fila_precio2" name="precio_2" readonly value="{$campos_item[0].precio2}" size="10" type="text"/></td>
  <td><input class="campo_decimal form-text" id="fila_precio2_iva" value="{$campos_item[0].coniva2}" name="coniva2" size="10" type="text"/></td>
 </tr>
 <tr>
  <td><input type='text' id='ocultos3' name='ocultos3' value="{$campos_item[0].p3}" onchange="calcular_todo();" size="9" class="form-text"/></td>
  <td>
<input onchange="calcularMonto('fila_precio3','utilidad3','fila_precio3_iva','ocultos3');" class="campo_decimal form-text" value="{$campos_item[0].utilidad3}" size="3" name="utilidad3" id="utilidad3" type="text">
<label for="utilidad3">%</label>
  </td>
  <td>
<input onchange='this.form.oculto3.value=this; calcular_todo();' class="campo_decimal form-text" title="{$DatosGenerales[0].titulo_precio3}" id="fila_precio3" name="precio_3" readonly value="{$campos_item[0].precio3}" size="10" type="text"/>
  </td>
  <td>
<input class="campo_decimal form-text" id="fila_precio3_iva"  value="{$campos_item[0].coniva3}" name="coniva3" size="10" type="text"/>
  </td>
 </tr>
 <tr>
  <td colspan="4" class="label" align="center">&nbsp;</td>
 </tr>
 <tr>
  <td valign="top" class="label" colspan="3"><b>Existencia M&iacute;nima del &iacute;tem</b></td>
  <td>
<div class="string_empaque"></div>
<input onkeypress="return validarNumero(event)" name="existencia_min" type="text" value="{$campos_item[0].existencia_min}" class="form-text"/>
  </td>
 </tr>
 <tr>
  <td class="label" colspan="3"><b>Existencia M&aacute;xima del &iacute;tem</b></td>
  <td>
<div class="string_empaque"></div>
<input onkeypress="return validarNumero(event)" name="existencia_max" type="text" value="{$campos_item[0].existencia_max}" class="form-text"/>
  </td>
 </tr>
 <tr>
  <td colspan="4" class="label" align="center">IMPUESTOS</td>
 </tr>
 <tr>
  <td colspan="3" class="label" align="left">Monto Exento</td>
  <td>
<select name="monto_exento" id="monto_exento" onchange="calcular_todo();" class="form-text">
 <option value="0">No</option>
 <!-- <option value="1">Si</option> -->
 <option value="1" {if $valor_iva[0].iva eq 0} selected {/if}>S&iacute;</option>
</select>
  </td>
 </tr>
 <tr class="monto_iva">
  <td colspan="3" class="label" align="left">{$DatosGenerales[0].nombre_impuesto_principal}</td>
  <td>
<!-- <input class="campo_decimal" type="text"  value="{$parametros_generales[0].porcentaje_impuesto_principal}" id="iva" name="iva"> -->
<!--input class="campo_decimal form-text" type="text" value="{$valor_iva[0].iva}" id="iva" name="iva"/-->
<select name="iva" id="iva" class="form-text" onchange="calcular_todo();">
 {html_options values=$option_values_porcentaje_impuesto_principal selected=$option_selected_porcentaje_impuesto_principal output=$option_output_porcentaje_impuesto_principal}
</select>
  </td>
 </tr>
 <!-- Aquí estaban los botones Guardar/Cancelar -->
</tbody>
  </table>
 </td>
</tr>
<!--Trabajando caracteristica-->
<!--tr>
 <td colspan="4">
  <input type="Button" value="Agregar impuesto" onclick="agregar_impuesto()" class="form-text"/>
 </td>
</tr
<tr class="tb-tit" align="right">
 <td colspan="4">
  <input type="submit" name="aceptar" id="aceptar" value="Guardar" class="form-text"/>
  <input type="button" name="cancelar" onclick="javascript: document.location.href='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}'" value="Cancelar" class="form-text"/>
 </td>
</tr>-->
  </table>
 </td>
 <td style="left:100px;">
  <table style='width:200px; margin-left:200px; height:200px;  overflow:auto; background:white; border-radius:20px; border-style:none;'>
<tr>
 <td colspan='2'>Existencia en Almac&eacute;n</td>
</tr>
<tr>
 <td style='background:#ccccdd; color:green; font-size:12px ; height:20px; font-weight:bold;'>Nombre Almac&eacute;n</td>
 <td style='color:green; height:20px; background:#ccccdd; width:30px'>Cantidad Existencia</td>
</tr>
{foreach from=$almacenes2 key=i item=miItem}
 <tr>
  <td style='color:blue; font-size:12px; font-weight:bold;'>{$miItem.descripcion}</td>
  <td style='background:#dddddd; width:30px'>{$miItem.cantidad}</td>
 </tr>
{/foreach}
  </table>
  <!--/div-->
 </td>
</tr>
  </table>
</div>
<!-- /TAB1 -->
<!-- TAB2 -->
<div id="div_tab2">
<table style="width: 100%; height: 100px;">

<tr>
 <td colspan="3" class="label" style="width: 30%;">Proveedor</td>
 <td>
  <!--<input type="text" name="proveedor" id="proveedor" size="50" value="" class="form-text"/>-->
    <select name="proveedor" style="width:200px;" id="proveedor" class="form-text">
  <option value=''>Seleccione</option>
  {html_options values=$option_values_prov output=$option_output_prov selected=$option_selected_prov}
 </select>
 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Origen</td>
 <td>
  <input type="text" name="origen" id="origen" size="50" value="" class="form-text"/>
  
 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Costo Cif</td>
 <td>
  <input type="text" name="costo_cif" id="costo_cif" size="50" value="" class="form-text"/>
  
 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Costo Origen</td>
 <td>
  <input type="text" name="costo_origen" id="costo_origen" size="50" value="" class="form-text"/>
  
 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Temporada</td>
 <td>
  <input type="text" name="temporada" id="temporada" size="50" value="" class="form-text"/>
  
 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Material/Composicion/Clase</td>
 <td>
  <input type="text" name="mate_compo_clase" id="mate_compo_clase" size="50" value="" class="form-text"/>
  
 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Punto pedido</td>
 <td>
  <input type="text" name="punto_pedido" id="punto_pedido" size="50" value="" class="form-text"/>
  
 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Tejido</td>
 <td>
  <input type="text" name="tejido" id="tejido" size="50" value="" class="form-text"/>
  
 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Reg. Sanit.</td>
 <td>
  <input type="text" name="reg_sanit" id="reg_sanit" size="50" value="" class="form-text"/>
  
 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Cod. barra bulto</td>
 <td>
  <input type="text" name="cod_barra_bulto" id="cod_barra_bulto" size="50" value="" class="form-text"/>
  
 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Observaciones</td>
 <td>
  <textarea rows="5" cols="50" name="observacion" >
  
  </textarea>
  
 </td>
</tr>


</table>


</div>
<!-- /TAB2 -->
<div id="div_tab3">


<table style="width: 100%; height: 100px;">
<tr>
 <td colspan="3" class="label" style="width: 30%;">Foto 1</td>
 <td>
  <input type="file" name="foto1" id="foto1"  class="form-text"/>
  
 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Foto 2</td>
 <td>
   <input type="file" name="foto2" id="foto2"  class="form-text"/>
  
 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Foto 3</td>
 <td>
   <input type="file" name="foto3" id="foto3"  class="form-text"/>
  
 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Foto 4</td>
 <td>
   <input type="file" name="foto4" id="foto4"  class="form-text"/>
  
 </td>
</tr>

</table>

</div>           
<div id="div_tab4">
<table style="width: 100%; height: 100px;">
<tr>
 <td colspan="3" class="label" style="width: 30%;">Contrato Licencia Nro</td>
 <td>
  <input type="text" name="cont_licen_nro" id="cont_licen_nro" size="50" value="" class="form-text"/>
  
 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Precio de Contrato</td>
 <td>
  <input type="text" name="precio_cont" id="precio_cont" size="50" value="" class="form-text"/>
  
 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Aprobacion de Arte</td>
 <td>
  <input type="text" name="aprob_arte" id="aprob_arte" size="50" value="" class="form-text"/>
  
 </td>
</tr>

<tr>
 <td colspan="3" width="30%" class="label">Propiedad</td>
 <td>
  <input type="text" name="propiedad" id="propiedad" size="50" value="" class="form-text"/>
  
 </td>
</tr>

</table>
</div>

<!-- tab 5  -->
<!-- <div style="margin:20px 0px 20px 10px" id="div_tab5">
   
    <table style="width: 100%;" class="tab5t">
       
        <tr class="tb-head" style="height: 35px;">
            <td><b>Region</b></td>
            <td><b>Precio Red comercial </b></td>
            <td><b>Precio PAE</b></td>
            <td><b>Pdvalito</b></td>
           
        </tr>
         
            {foreach from=$campos_precio key=i item=campos}
             <tr  style="height: 35px;">
                 <td><b>{$campos.descripcion}</b></td>  
                 <input name="region[]" value="{$campos.id}" type="hidden" >
                 <td><input class="form-text" onkeypress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;" class="camposP" name="precio1[]" type="text"></td>  
                 <td><input class="form-text" onkeypress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;" class="camposP" name="precio2[]" type="text"></td>  
                 <td><input class="form-text" onkeypress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;" class="camposP" name="precio3[]" type="text"></td>  
               
             </tr>   
            {/foreach}                         
      
              
       

        
    </table>
</div>-->

<div id="div_tab5">
<table style="width: 100%;">
<tr>
 <td class="label" colspan="3">Empaque**</td>
 <td>
  <!--<input type="text" name="empaque" id="empaque" size="50" value="{$campos_item[0].unidad_empaque}" onchange='this.form.empaque_descripcion.value = this.value;' class="form-text"/>-->
  <select name="empaque" id="empaque" class="form-text" onchange='this.form.empaque_descripcion.value = this.form.empaque.options[this.form.empaque.selectedIndex].text;'(s)>
<option value="0">Seleccione Unidad de Empaque</option>
{html_options values=$option_values_empaque output=$option_output_empaque selected=$campos_item[0].unidad_empaque}
  </select>
 </td>
</tr>
<tr>
 <td colspan="3" width="30%" class="label">Cod. barra bulto</td>
 <td>
  <input type="text" name="cod_barra_bulto" id="cod_barra_bulto" size="50" value="" class="form-text"/>
  
 </td>
</tr>
<tr>
 <td class="label" colspan="3">Cantidad por Bulto**</td>
 <td>
  <input type="text" name="cantidad_bulto" id="cantidad_bulto" size="50"  class="form-text" value="1" />
 </td>
</tr>

<tr>
 <td class="label" colspan="3">Kilos por Bulto**</td>
 <td>
  <input type="text" name="kilos_bulto" id="kilos_bulto" size="50"  class="form-text" value="1" />
 </td>
</tr>
</table>
</div>

<!-- +++++++++++++++ -->
  </div>
  
  <input type="hidden" name="pg_iva" id="pg_iva" value="{$parametros_generales[0].porcentaje_impuesto_principal}"/>

 {literal}
  <script type="text/javascript">//<![CDATA[
var mikit= document.getElementById('producto_kit');
mikit.onclick();

var aux= document.getElementById('serial');
aux.onclick();

var aux= document.getElementById('tipo_producto1');
aux.onchange();

var aux= document.getElementById('tipo_producto2');
aux.onchange();

  //]]>
  </script>
				
<table style="width: 100%;">         
  <tr class="tb-tit" align="right">
 <td colspan="4">
  <input type="submit" name="aceptar" id="aceptar" value="Guardar" class="form-text"/>
  <input type="button" name="cancelar" onclick="javascript: document.location.href='?opt_menu=3&opt_seccion=61'" value="Cancelar" class="form-text"/>
 </td>
</tr>
  </table>
   </form>
 {/literal}
</body>
  </html>