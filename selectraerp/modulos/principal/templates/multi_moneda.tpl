
{literal}

<script>

function mostrarTasa(suiche)
{


var aux= document.getElementById('tasacambio');

if (suiche==1)
{
aux.innerHTML = document.valor;
}
else
{
document.valor = aux.innerHTML;
aux.innerHTML = "<a href='?opt_menu=1&opt_seccion=105'> Ver Matriz Tasas de Cambio </a> ";
}


}
</script>


<style>
.recuadro1
{
background:#dddddd;
width:300px;
height:470px;
margin: 30px auto auto auto;
-moz-border-radius:20px;
text-align:center;
}

.col1
{
color:green;
font-size:11px;
font-family: arial;
width:200px;
padding:10px;

}
</style>

{/literal}

{$javascript1}

 {foreach from =$datos_moneda  key = i item = campos}
<div class='recuadro1'>



<form action='?opt_menu=1&opt_seccion=103' method='post'>
<table>
<tr> <td colspan=2 >  <br>  <hr color='#ffffff'>  <h1> Moneda Base del Sistema </h1>  </td></tr>
<tr> <td class='col1'>Moneda base del Sistema </td> <td>  {$datos_moneda_base[0].Nombre}</td> </tr>
<tr> <td class='col1'>Abreviatura o Símbolo </td> <td>  {$datos_moneda_base[0].Abreviatura} </td></tr>


<tr> <td colspan=2 >  <br>  <hr color='#ffffff'>  <h1> Moneda Seleccionada (Actual) </h1>  </td></tr>


<tr> <td class='col1'>Nombre Divisa Actual </td> <td>  {$campos.Nombre}</td> </tr>
<tr> <td class='col1'>Abreviatura o Símbolo Actual </td> <td>  {$campos.Abreviatura} </td></tr>
 <tr> <td class='col1'>Factor de Cambio  </td> <td> <div id='tasacambio'> {$campos.Cambio_unico} {$campos.Abreviatura} </div>    </td></tr> 

<tr> <td colspan=2 >  <br>  <hr color='#ffffff'>  <h1> Cambio de Moneda </h1>  </td></tr>


<tr> <td class='col1'>Cambio Único </td> <td> {$seccionCambio}</td></tr>


<tr> <td class='col1'> Selección Divisa </td> <td>  
 <select name='divisa'>
{$monedaActual}
{foreach from =$divisas  key = i item = miItem}
 <option value={$miItem.id_divisa}>
{$miItem.Nombre}
 </option >
{/foreach} 
</select> 
<br>
<center><a href='?opt_menu=1&opt_seccion=104' style='color:blue'> Editar Divisas </a> </center>
</td></tr>



</table>

<br>
<br>
<input  type='submit'  name='guardarMoneda' value='Guardar Cambios' >
</form>
</div>

{/foreach} 


