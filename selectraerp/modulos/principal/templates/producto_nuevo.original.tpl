{literal}
<script src="../../libs/js/config_items_tabs.js"></script>
 <script src="../../libs/js/ajax.js"></script>
<script>

function buscarProducto(id,nombre,empaque,cantidad)
{
var miId = document.getElementById(id);

var miItem = document.getElementById('cod_item');

cargarUrl('buscarProducto.php?codigo=' + miId.value + '&campo=' + id + '&nombre=' + nombre + '&empaque=' + empaque + '&cantidad=' + cantidad, 'TRANSPARENTE' );
setTimeout ("sumaTotal()", 3000); 

}


function sumaTotal()
{

var total=0;

for(var i=1;i<=20;i++)
{
var aux = document.getElementById('sub_total' + i);
total = (total * 1) + (aux.value * 1);

}
var aux2 = document.getElementById('suma_total');

aux2.value= redondear(total);

var aux3 = document.getElementById('costo_actual');
aux3.value=aux2.value;
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

for(var i=0;i<e.length;i++){e[i].style.visibility= 'hidden';}
 var e=aux.getElementsByTagName("img");

for(var i=0;i<e.length;i++){e[i].style.visibility= 'hidden';}

}


function calcularPrecio(cantidad,precio,sub_total)
{

var cant = cantidad.value;
var monto = document.getElementById(precio).value;
var sub = document.getElementById(sub_total);

sub.value= redondear(cant * monto);
sumaTotal();
}

function eliminarProducto(posicion)
{


var aux = document.getElementById('item' + posicion);


while (aux.value != '')
{


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

function calcularMonto(precio,utilidad,campoiva,ocultos)
{



var exonerado = document.getElementById('monto_exento');
var iva = document.getElementById('iva');

precio.value = parseFloat(ocultos.value * (1 + (parseFloat(utilidad.value) / 100))) ;
precio.value = redondear(precio.value,2);

if (exonerado.value == '1') 
campoiva.value = precio.value ;
else
campoiva.value = redondear(precio.value * (1 + (iva.value/100)),2);



}

function  calcular_todo()
{
var aux= document.getElementById('formulario');

calcularMonto(aux.precio_1,aux.utilidad1,aux.coniva1,aux.ocultos1);
calcularMonto(aux.precio_2,aux.utilidad2,aux.coniva2,aux.ocultos2);
calcularMonto(aux.precio_3,aux.utilidad3,aux.coniva3,aux.ocultos3);

}



$(document).ready(function(){

				
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
                    resultado = data
                    if(resultado=="-1"){
                        $("#cod_item").val("").focus();
                        $("#notificacionVCoditem").html("<img align=\"absmiddle\"  src=\"../../libs/imagenes/ico_note.gif\"><span style=\"color:red;\"> <b>Disculpe, este codigo ya existe.</b></span>");
                    }

                    if(resultado=="1"){//coddeitemdisponble
                        $("#notificacionVCoditem").html("<img align=\"absmiddle\"  src=\"../../libs/imagenes/ok.gif\"><span style=\"color:#0c880c;\"><b> Codigo Disponible</b></span>");
                    }

                }
            
            });
            }

        });

 
          $("#formulario").submit(function(){
                if($("#cod_item").val()==""||$("#descripcion1").val()==""){
                    alert("Debe Ingresar los campos obligatorios!.");
                    $("#descripcion1").focus();
                    return false;
                }
        });
    });


</script>
<style>
 

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
        border-right: 1px solid #8d8f91;border-top: 1px solid #8d8f91;
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
	width:550px;

    }

    #tabs {
        margin-top:15px;

    }


</style>
{/literal}
<form name="formulario" id="formulario" method="POST" action="">
<input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}">
<input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}">
<input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}">

<table width="100%" border="0">
<tbody>
<tr>
      <td class="tb-tit">
         <img src="{$subseccion[0].img_ruta}" width="20" align="absmiddle" height="20">  <strong>{$subseccion[0].descripcion}</strong>
      </td>
</tr>
</tbody>
</table>
<div id="tabs">
<table style="margin-left:20px;" >
    <tr style="height:25px;">
        <td id="tab1" class="tab">
            <img src="../../libs/imagenes/1.png" width="20" align="absmiddle" height="20">  <b>Datos Generales</b>
        </td>
        <td>&nbsp;&nbsp;</td>
       
    </tr>



</table>
</div>

<div id='productosKit'> </div>
<div id="contenedorTAB">
<!-- TAB1 -->
<div id="div_tab1">
<table   width="100%" border="0" height="100">
    <tr>
        <td colspan="4" class="tb-head" align="center">
          COMPLETLE LOS CAMPOS MARCADOS CON&nbsp;** OBLIGATORIAMENTE
      </td>


<tr>
    <td  colspan="3" width="30%" class="tb-head" >
        Ultimo Codigo
    </td>
    <td >
        <input type="text" disabled="true" name="ultimo_codigo" id="ultimo_codigo" size="60"  value="{$nro_productoOLD}">
        <div id="notificacionVCoditem"></div>
    </td>
</tr>

<tr>
    <td  colspan="3" width="30%" class="tb-head" >
        Codigo **
    </td>
    <td >
        <input type="text" name="cod_item" readonly id="cod_item" size="60"  value="{$nro_productoNEW}">
        <div id="notificacionVCoditem"></div>
    </td>
</tr>
<tr>
      <td class="tb-head" colspan="4" align="center" width="180">
          DATOS DEL item
      </td>
</tr>
<tr>
    <td colspan="3" class="tb-head">
        {$DatosGenerales[0].string_clasificador_inventario1}
    </td>
    <td>
        <select name="cod_departamento" id="cod_departamento">
          {html_options values=$option_output_departamentos output=$option_values_departamentos }
        </select>
    </td>
</tr>
<tr>
    <td colspan="3" class="tb-head">
        {$DatosGenerales[0].string_clasificador_inventario2}
    </td>
    <td>
        <select name="cod_grupo" id="cod_grupo">
          {html_options values=$option_output_grupo output=$option_values_grupo }
        </select>
    </td>
</tr>
<tr>
    <td colspan="3" class="tb-head">
        {$DatosGenerales[0].string_clasificador_inventario3}
    </td>
    <td>
<select name="cod_linea" id="cod_linea">
    {html_options values=$option_output_linea output=$option_values_linea}
</select>
    </td>
</tr>
<tr>
    <td colspan="3" class="tb-head">Código de barras</td>
    <td>
        <input type="text" name="cod_barras" id="cod_barras" size="60"  value="{$campos_item[0].codigo_barras}">
    </td>
</tr>
<tr>
    <td colspan="3" class="tb-head">
        Descripción 1 **
    </td>
    <td>
        <input type="text" name="descripcion1" id="descripcion1" size="60"  value="{$campos_item[0].descripcion1}">
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3"  >
        Descripción 2
    <td>
        <input type="text" name="descripcion2" id="descripcion2" size="60"  value="{$campos_item[0].descripcion2}">
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3">
       Descripción 3
    </td>
    <td>
        <input type="text" name="descripcion3" id="descripcion3" size="60"  value="{$campos_item[0].descripcion3}">
    </td>
</tr>
<tr>


    <td class="tb-head" colspan="3">
        Referencia
    </td>
    <td>
        <input type="text" name="referencia" id="referencia" size="60"  value="{$campos_item[0].referencia}">
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3">
        Empaque
    </td>
    <td>
        <input type="text" name="empaque" id="empaque" size="60" value="{$campos_item[0].unidad_empaque}" onchange='this.form.empaque_descripcion.value = this.value;' >
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3">
        Unidad Empaque
    </td>
    <td>
        <input type="text" name="unidad_empaque" id="unidad_empaque" size="5" value="{$campos_item[0].cantidad}">     <input style='border-style:none; background:none;' readonly type="text" name="empaque_descripcion" id="empaque_descripcion" size="5"  value="{$campos_item[0].unidad_empaque}">
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3">
        Codigo Fabricante
    </td>
    <td>
        <input type="text" name="codigo_fabricante" id="codigo_fabricante" size="60" value="{$campos_item[0].codigo_fabricante}">
    </td>
</tr>

<tr>
    <td class="tb-head" colspan="3">
        Estatus
    </td>
    <td>
        <select name="estatus" id="estatus">
            <option value="A">Activo</option>
            <option value="I">Inactivo</option>
        </select>
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Cuenta Contable 1
    </td>
    <td >
<select name="cuenta_contable1" id="cuenta_contable1">
    {html_options values=$option_values_cuenta output=$option_output_cuenta}
</select>
    </td>
</tr>
<tr>
    <td valign="top"  colspan="3" width="30%" class="tb-head" >
      Cuenta Contable 2
    </td>
    <td >
<select name="cuenta_contable2" id="cuenta_contable2">
    {html_options values=$option_values_cuenta output=$option_output_cuenta}
</select>
    </td>
</tr>

<tr>
    <td colspan="4">

        <hr>
    </td>
</tr>

<tr>
    <td class="tb-head" colspan="3">  
     
<br>
    </td> 

    <td>  Producto con Serial  <input type="checkbox" name="serial" id="serial" size="60" value='1'  {$campoSerial}   {literal} onclick="if (this.checked) { var aux = 'visible'; var aux2 = 'hidden'; document.getElementById('conFactor').style.visibility = 'hidden';} else {var aux = 'hidden'; var aux2 = 'visible'; document.getElementById('conFactor').style.visibility = 'visible'; } document.getElementById('serialGarantia').style.visibility = aux;  document.getElementById('productoKit').style.visibility = aux2;">

        <div id='serialGarantia' style='display:inline; visibility:hidden'>       
	&nbsp;  &nbsp; &nbsp;   &nbsp;Serial Con Garantía. 
  {/literal}
   <input type="checkbox" name="garantia" id="garantia"  value='1' {$campoGarantia} > </div>  <br>
 
        <div id='productoKit' style='display:inline; '>     

   
 <div id='productoKit' style='display:inline; '>     
 Producto con Kit
  



        <input type="checkbox" name="producto_kit" id="producto_kit"  value='1' {$campos_kit}   {literal} onclick="if (this.checked) {cargarUrl('kitproductos.php?codigo=' + this.form.cod_item.value,'productosKit'); document.getElementById('productosKit').style.visibility='visible';} else {document.getElementById('productosKit').style.visibility='hidden'; ocultarVarios('productosKit');}"> </div>
&nbsp;  &nbsp; &nbsp;   &nbsp;

 {/literal} 
 
 <br>

 
    <div style='  padding:10px; border:1px; border-style:solid;  border-color:green; -moz-border-radius:10px; width:200px; '  >
 &nbsp;   &nbsp;Tipo de Producto
 <br>

       Nacional <input type="radio" name="tipo_producto" id="tipo_producto1"  value='0'  {$campoNacional}  onchange="if (this.checked) var aux = 'hidden'; else var aux = 'visible';  document.getElementById('conFactor').style.visibility = aux;  "> 
       &nbsp;   &nbsp; Importado <input type="radio" name="tipo_producto" id="tipo_producto2"  {$campoImportado}   value='1' onchange="if (this.checked) var aux = 'visible'; else var aux = 'hidden';  document.getElementById('conFactor').style.visibility = aux;  " >
 <br>
<div id='conFactor' style='visibility:hidden; '>     
Factor de Cambio 
<input  type="text"  name="factor_cambios"  id="factor_cambios"  value="0.00"> 

<br>
Ultimo Costo        
<input  type="text" value="0.00" name="ultimo_costos"  id="ultimo_costos" onchange="this.form.costo_actual.value= redondear(this.value * this.form.factor_cambios.value, 2) ; this.form.costo_actual.onchange(); ">
</div>

</div>

<br> 
    </td>
</tr>




<tr>
    <td class="tb-head" colspan="3">
    Costo Actual
    </td>
    <td>
        <input size="60" type="text" name="costo_actual"  id="costo_actual" onchange='this.form.precio_1.value= this.value ; this.form.costo_promedio.value= this.value; this.form.costo_anterior.value= this.value; this.form.ocultos1.value= this.value;  this.form.ocultos2.value= this.value; this.form.ocultos3.value= this.value; calcular_todo();' value="{$campos_item[0].costo_actual}">
    </td>
</tr>

<tr>
    <td class="tb-head" colspan="3">
        Costo Promedio
    </td>
    <td>
        <input size="60" type="text"  name="costo_promedio"  id="costo_promedio" onchange='' value="{$campos_item[0].costo_promedio}">
    </td>
</tr>

<tr>
    <td class="tb-head" colspan="3">
       Costo Anterior
    </td>
    <td>
        <input size="60" type="text"  name="costo_anterior"  id="costo_anterior" onchange='' value="{$campos_item[0].costo_anterior}">
    </td>
</tr>

  </tbody>
        </table>
    </td>
</tr>
</tbody>
</table>




</div>
<!-- /TAB1 -->
<!--
***************************************************************************************************************************
***************************************************************************************************************************
-->
<!-- TAB2 -->
<div id="div_tab2">

</div>

<!-- /TAB2 -->
</div>



<table   width="100%" border="0">

    <tr>  <td  > 









 <br>

<table  border="0">

    <tr>
        <td colspan="4" class="tb-head" align="center">
          &nbsp;
      </td>
</tr>
<tr>
      <td class="tb-head" colspan="4" align="center" width="180">

      </td>
</tr>
<tr>
    <td class="tb-head" colspan="3" align="right">
    	&nbsp;&nbsp;&nbsp;
    </td>
    <td>
         <table id="tabla_total" style="border: 1px solid #507e95;" bgcolor="white">
            <thead>
                <tr>
                    
		    <th align="left">Costos</th>	
		    <th align="left">Utilidad</th>
		    <th align="left">Con Utilidad</th>
                    <th align="left">Con Iva</th>
          			</tr>
            </thead>
            <tbody>
                <tr> <td> <input type='text' name='ocultos1' id='ocultos1'  value="{$campos_item[0].p1}" onchange=" calcular_todo();" size="9"> </td>
                   <td><input onchange="calcularMonto(this.form.precio_1,this,this.form.coniva1,this.form.ocultos1);" class="campo_decimal"   size="3" name="utilidad1"  id="utilidad1" name="utilidad1" type="text"  value="{$campos_item[0].utilidad1}">%</td> 
<td>   <input onchange='calcular_todo();'  class="campo_decimal" title="{$DatosGenerales[0].titulo_precio1}" id="fila_precio1" name="precio_1" value="{$campos_item[0].precio1}" size="10" readonly type="text"></td>
                    
                    <td><input class="campo_decimal" id="fila_precio1_iva"  name="coniva1" size="10" type="text" value="{$campos_item[0].coniva1}"></td>
                </tr>
              <tr>


  	 <td> <input  type='text' name='ocultos2' value="{$campos_item[0].p2}"  onchange=" calcular_todo();" size="9"> </td> 
                    <td><input  onchange="calcularMonto(this.form.precio_2,this,this.form.coniva2,this.form.ocultos2);" class="campo_decimal" size="3" name="utilidad2" id="utilidad2" type="text" value="{$campos_item[0].utilidad2}">%</td>
 <td>  <input  onchange='this.form.oculto2.value=this.value; calcular_todo();' class="campo_decimal" title="{$DatosGenerales[0].titulo_precio2}" id="fila_precio2" name="precio_2" readonly value="{$campos_item[0].precio2}" size="10" type="text"></td>
                   
                    <td><input class="campo_decimal" id="fila_precio2_iva" value="{$campos_item[0].coniva2}" name="coniva2" size="10" type="text" ></td>
                </tr>
                     <tr>
	<td><input  type='text' name='ocultos3' value="{$campos_item[0].p3}"  onchange=" calcular_todo();" size="9">  </td>
                   <td><input  onchange="calcularMonto(this.form.precio_3,this,this.form.coniva3,this.form.ocultos3);" class="campo_decimal" value="{$campos_item[0].utilidad3}" size="3"  name="utilidad3" id="utilidad3" type="text">%</td>  
<td><input   onchange='this.form.oculto3.value=this; calcular_todo();' class="campo_decimal" title="{$DatosGenerales[0].titulo_precio3}" id="fila_precio3" name="precio_3" readonly  value="{$campos_item[0].precio3}" size="10" type="text"></td>
                   
                    <td><input class="campo_decimal" id="fila_precio3_iva"  value="{$campos_item[0].coniva3}" name="coniva3" size="10" type="text"></td>
                </tr>
<tr>
        <td colspan="4" class="tb-head" align="center">
         &nbsp;
      </td>
</tr>
<tr>
    <td valign="top" class="tb-head" colspan="3"><b>Existencia Minima del item</b></td>
    <td><div class="string_empaque"></div>
        <input onkeypress="return validarNumero(event)" name="existencia_min"  type="text"  value="{$campos_item[0].existencia_min}">
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3"><b>Existencia Maxima del item</b></td>
    <td><div class="string_empaque"></div>
        <input onkeypress="return validarNumero(event)"  name="existencia_max" type="text" value="{$campos_item[0].existencia_max}">
    </td>
</tr>
<tr>
        <td colspan="4" class="tb-head" align="center">
        IMPUESTOS
      </td>
</tr>
  <tr>
        <td colspan="3" class="tb-head" align="left">
         Monto Exento
      </td>
       <td>
           <select name="monto_exento" id="monto_exento" onchange=" calcular_todo();">
               <option value="0">No</option>
               <option value="1">Si</option>
           </select>
      </td>
</tr>
<tr class="monto_iva">
      <td colspan="3" class="tb-head" align="left">
         I.V.A
      </td>
       <td>
           <input class="campo_decimal" type="text"  value="{$parametros_generales[0].porcentaje_impuesto_principal}" id="iva" name="iva">
      </td>
</tr>
<tr class="tb-tit" align="right">
    <td colspan="4">
                <input type="submit" id="aceptar" name="aceptar" value="Guardar">
        <input type="button" name="cancelar" onclick="javascript:  document.location.href='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}'" value="Cancelar">

    </td>
    </tr>

            </tbody>
    </table>
</table>




</td>

 <td style='left:100px;'>

<table style='width:200px; margin-left:200px; height:200px;  overflow:auto; background:white; -moz-border-radius:20px; border-style:none;' >
 <tr>
 <td colspan='2'> Existencia en Almacen  </td>
 </tr>
 <tr>
 <td style='background:#ccccdd; color:green; font-size:12px ; height:20px; font-weight:bold;'> Nombre Almacén </td> <td style=' color:green; height:20px; background:#ccccdd; width:30px'> Cantidad Existencia</td>
 </tr> 
{foreach from =$almacenes2 key = i item = miItem}
 <tr>
 <td style='color:blue; font-size:12px ; font-weight:bold;'> {$miItem.descripcion}  </td> <td style='background:#dddddd; width:30px'> {$miItem.cantidad}  </td>
 </tr>
{/foreach} 
</table> 

</div>

 </td>

    </tr>
  
</table>

<input type="hidden" name="pg_iva" id="pg_iva" value="{$parametros_generales[0].porcentaje_impuesto_principal}">

</form>
<script>
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
</script>
