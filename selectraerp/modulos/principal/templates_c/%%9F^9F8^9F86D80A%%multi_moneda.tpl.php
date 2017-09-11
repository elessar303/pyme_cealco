<?php /* Smarty version 2.6.21, created on 2017-08-24 11:22:37
         compiled from multi_moneda.tpl */ ?>

<?php echo '

<script>

function mostrarTasa(suiche)
{


var aux= document.getElementById(\'tasacambio\');

if (suiche==1)
{
aux.innerHTML = document.valor;
}
else
{
document.valor = aux.innerHTML;
aux.innerHTML = "<a href=\'?opt_menu=1&opt_seccion=105\'> Ver Matriz Tasas de Cambio </a> ";
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

'; ?>


<?php echo $this->_tpl_vars['javascript1']; ?>


 <?php $_from = $this->_tpl_vars['datos_moneda']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['campos']):
?>
<div class='recuadro1'>



<form action='?opt_menu=1&opt_seccion=103' method='post'>
<table>
<tr> <td colspan=2 >  <br>  <hr color='#ffffff'>  <h1> Moneda Base del Sistema </h1>  </td></tr>
<tr> <td class='col1'>Moneda base del Sistema </td> <td>  <?php echo $this->_tpl_vars['datos_moneda_base'][0]['Nombre']; ?>
</td> </tr>
<tr> <td class='col1'>Abreviatura o Símbolo </td> <td>  <?php echo $this->_tpl_vars['datos_moneda_base'][0]['Abreviatura']; ?>
 </td></tr>


<tr> <td colspan=2 >  <br>  <hr color='#ffffff'>  <h1> Moneda Seleccionada (Actual) </h1>  </td></tr>


<tr> <td class='col1'>Nombre Divisa Actual </td> <td>  <?php echo $this->_tpl_vars['campos']['Nombre']; ?>
</td> </tr>
<tr> <td class='col1'>Abreviatura o Símbolo Actual </td> <td>  <?php echo $this->_tpl_vars['campos']['Abreviatura']; ?>
 </td></tr>
 <tr> <td class='col1'>Factor de Cambio  </td> <td> <div id='tasacambio'> <?php echo $this->_tpl_vars['campos']['Cambio_unico']; ?>
 <?php echo $this->_tpl_vars['campos']['Abreviatura']; ?>
 </div>    </td></tr> 

<tr> <td colspan=2 >  <br>  <hr color='#ffffff'>  <h1> Cambio de Moneda </h1>  </td></tr>


<tr> <td class='col1'>Cambio Único </td> <td> <?php echo $this->_tpl_vars['seccionCambio']; ?>
</td></tr>


<tr> <td class='col1'> Selección Divisa </td> <td>  
 <select name='divisa'>
<?php echo $this->_tpl_vars['monedaActual']; ?>

<?php $_from = $this->_tpl_vars['divisas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['miItem']):
?>
 <option value=<?php echo $this->_tpl_vars['miItem']['id_divisa']; ?>
>
<?php echo $this->_tpl_vars['miItem']['Nombre']; ?>

 </option >
<?php endforeach; endif; unset($_from); ?> 
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

<?php endforeach; endif; unset($_from); ?> 

