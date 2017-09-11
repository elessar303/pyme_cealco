<?php /* Smarty version 2.6.21, created on 2017-01-20 15:39:35
         compiled from divisas_agregar2.tpl */ ?>
<?php echo '


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
        background: url(\'../../libs/imagenes/azul/tb_tit.jpg\') repeat-x;
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


'; ?>






<form name="formulario" id="formulario" method="POST" action="">
<input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
">
<input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
">
<input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
">

<h1><blink><?php echo $this->_tpl_vars['mensaje']; ?>
</blink> </br><div style='font-family:15;color:black'> <?php echo $this->_tpl_vars['mensajeDetalle']; ?>
 </div></h1>

<table  width="100%" border="0">
<tbody>
<tr>
      <td  class="tb-tit">
         <img src="../../libs/imagenes/moneda.png"  width="20" align="absmiddle" height="20">  <strong><?php echo $this->_tpl_vars['subseccion'][0]['descripcion']; ?>
</strong>
      </td>
</tr>
</tbody>
</table>
<div id="tabs">
<table style="margin-left:20px;" >
    <tr style="height:25px;">
        <td id="tab1" class="tab">
            <img src="../../libs/imagenes/1.png" width="20" align="absmiddle" height="20">  <b>Guardar Tasa</b>
        </td>
   
    </tr>

</table>
</div>
<div id="contenedorTAB">





<!-- TAB1 -->
<div id="div_tab1">
<table   width="100%" border="0" height="40">
  
<tr>
    <td colspan="3" class="tb-head">
        Fecha
    </td>
    <td>
        <input type="text" name="fecha" id="fecha" size="60" value='<?php echo $this->_tpl_vars['fechaActual']; ?>
'>
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3"  >
        Tasa
    <td>
        <input type="text" name="tasa" id="tasa" size="60" value=''>
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

<?php echo '
    <script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() }
      });
      cal.manageFields("fecha", "fecha", "%d/%m/%Y");
    //]]></script>
'; ?>


<br>


</form>

   <center> <input type="submit" name="aceptar" id="descripcion3" size="200" value='Guardar Tasa'> </center>