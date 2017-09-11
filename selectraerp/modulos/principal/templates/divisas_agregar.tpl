{literal}


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

    #contenedorTAB {
        background-color: #e3ebf1;
            -moz-border-radius: 5px; padding: 2px;
	    -webkit-border-radius: 5px;
	border: 1px solid #adafb0;

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

<table  width="100%" border="0">
<tbody>
<tr>
      <td  class="tb-tit">
         <img src="../../libs/imagenes/moneda.png" width="20" align="absmiddle" height="20">  <strong>{$subseccion[0].descripcion}</strong>
      </td>
</tr>
</tbody>
</table>
<div id="tabs">
<table style="margin-left:20px;" >
    <tr style="height:25px;">
        <td id="tab1" class="tab">
            <img src="../../libs/imagenes/1.png" width="20" align="absmiddle" height="20">  <b>Nueva Moneda</b>
        </td>
   
    </tr>

</table>
</div>
<div id="contenedorTAB">
<!-- TAB1 -->
<div id="div_tab1">
<table   width="100%" border="0" height="60">
  
<tr>
    <td colspan="3" class="tb-head">
        Nombre Nueva Moneda
    </td>
    <td>
        <input type="text" name="nombre" id="descripcion1" size="60" value=''>
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3"  >
        Abreviatura o Símbolo
    <td>
        <input type="text" name="abreviatura" id="descripcion2" size="60" value=''>
    </td>
</tr>
<tr {$oculto}>
    <td class="tb-head" colspan="3">
       Tasa de cambio (en relación a la moneda base)
    </td>
    <td>
        <input type="text" name="tasa" id="descripcion3" size="60" value='' {$oculto} >
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

<br>


</form>

   <center> <input type="submit" name="aceptar" id="descripcion3" size="200" value='Crear Divisa'> </center>
