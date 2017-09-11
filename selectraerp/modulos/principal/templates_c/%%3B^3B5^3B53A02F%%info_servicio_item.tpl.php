<?php /* Smarty version 2.6.21, created on 2014-05-14 10:57:26
         compiled from info_servicio_item.tpl */ ?>
<?php if ($this->_tpl_vars['campos_item'][0]['descripcion_item_forma'] == 'Productos'): ?>
<!-- INFO PRODUCTO -->
<div id="contenedorTAB">
  <!-- TAB1 -->
  <div id="div_tab1">
    <table   width="100%" border="0" height="100">
      <tr>
        <td colspan="4" class="tb-head" align="center">
         Tipo de Item: <?php echo $this->_tpl_vars['campos_item'][0]['descripcion_item_forma']; ?>
 - Cantidad Total Existente (<?php echo $this->_tpl_vars['campos_item'][0]['existencia_total']; ?>
)
       </td>
     </tr>
     <tr>
        <td colspan="4" class="tb-head" align="center">
          <?php if ($this->_tpl_vars['campos_item'][0]['foto'] == ''): ?>
            No posee Imagen para mostrar
          <?php else: ?>
            <img src="../../imagenes/<?php echo $this->_tpl_vars['campos_item'][0]['foto']; ?>
" width="100" align="absmiddle" height="100"/>
          <?php endif; ?>
       </td>
     </tr>
     <tr>
      <td  colspan="3" width="30%" class="tb-head" >
        Codigo **
      </td>
      <td >
        <?php echo $this->_tpl_vars['campos_item'][0]['cod_item']; ?>

      </td>
    </tr>
    <tr>
      <td class="tb-head" colspan="4" align="center" width="180">
        DATOS DEL item
      </td>
    </tr>
    <tr>
      <td colspan="3" class="tb-head">
        <?php echo $this->_tpl_vars['DatosGenerales'][0]['string_clasificador_inventario1']; ?>

      </td>
      <td>
        <?php echo $this->_tpl_vars['campos_item'][0]['departamento']; ?>

      </td>
    </tr>
    <tr>
      <td colspan="3" class="tb-head">
        <?php echo $this->_tpl_vars['DatosGenerales'][0]['string_clasificador_inventario2']; ?>

      </td>
      <td>
        <?php echo $this->_tpl_vars['campos_item'][0]['grupo']; ?>

      </td>
    </tr>
    <tr>
      <td colspan="3" class="tb-head">
        <?php echo $this->_tpl_vars['DatosGenerales'][0]['string_clasificador_inventario3']; ?>

      </td>
      <td>
        <?php echo $this->_tpl_vars['campos_item'][0]['linea']; ?>

      </td>
    </tr>

    <tr>
      <td colspan="3" class="tb-head">
        Descripción 1 **
      </td>
      <td>
       <?php echo $this->_tpl_vars['campos_item'][0]['descripcion1']; ?>

     </td>
   </tr>
   <tr>
    <td class="tb-head" colspan="3"  >
      Descripción 2
      <td>
        <?php echo $this->_tpl_vars['campos_item'][0]['descripcion2']; ?>

      </td>
    </tr>
    <tr>
      <td class="tb-head" colspan="3">
       Descripción 3
     </td>
     <td>
       <?php echo $this->_tpl_vars['campos_item'][0]['descripcion3']; ?>

     </td>
   </tr>
   <tr>
    <td class="tb-head" colspan="3">
      Referencia
    </td>
    <td>
      <?php echo $this->_tpl_vars['campos_item'][0]['referencia']; ?>

    </td>
  </tr>
  <tr>
    <td class="tb-head" colspan="3">
      Codigo Fabricante
    </td>
    <td>
      <?php echo $this->_tpl_vars['campos_item'][0]['codigo_fabricante']; ?>

    </td>
  </tr>
  <tr>
    <td class="tb-head" colspan="3">
      Estatus
    </td>
    <td>

      <?php if ($this->_tpl_vars['campos_item'][0]['estatus'] == 'A'): ?>Activo <?php endif; ?>
      <?php if ($this->_tpl_vars['campos_item'][0]['estatus'] == 'I'): ?>Inactivo <?php endif; ?>

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
  <table   width="100%" border="0">
    <tr>
      <td colspan="4" class="tb-head" align="center">
        &nbsp;
      </td>
    </tr>

    <tr>
      <td class="tb-head" colspan="3" align="right">
        &nbsp; &nbsp;
      </td>
      <td>
       <table id="tabla_total" style="border: 1px solid #507e95;" bgcolor="white">
        <thead>
          <tr>

            <th align="left">Precios</th>
            <th align="left">Utilidad</th>
            <th align="left">Con Iva</th>

          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo $this->_tpl_vars['campos_item'][0]['precio1']; ?>
</td>
            <td><?php echo $this->_tpl_vars['campos_item'][0]['utilidad1']; ?>
</td>
            <td><?php echo $this->_tpl_vars['campos_item'][0]['coniva1']; ?>
</td>
          </tr>
          <tr>
            <td><?php echo $this->_tpl_vars['campos_item'][0]['precio2']; ?>
</td>
            <td><?php echo $this->_tpl_vars['campos_item'][0]['utilidad2']; ?>
</td>
            <td><?php echo $this->_tpl_vars['campos_item'][0]['coniva2']; ?>
</td>
          </tr>
          <tr>
            <td><?php echo $this->_tpl_vars['campos_item'][0]['precio3']; ?>
</td>
            <td><?php echo $this->_tpl_vars['campos_item'][0]['utilidad3']; ?>
</td>
            <td><?php echo $this->_tpl_vars['campos_item'][0]['coniva3']; ?>
</td>
          </tr>
        </tbody>
      </table>
    </td>
  </tr>

  <tr>
    <td valign="top" class="tb-head" colspan="3"><b>Existencia Minima del item</b></td>
    <td><div class="string_empaque"></div>
      <?php echo $this->_tpl_vars['campos_item'][0]['existencia_min']; ?>

    </td>
  </tr>
  <tr>
    <td class="tb-head" colspan="3"><b>Existencia Maxima del item</b></td>
    <td><div class="string_empaque"></div>
      <?php echo $this->_tpl_vars['campos_item'][0]['existencia_max']; ?>

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

    <?php if ($this->_tpl_vars['campos_item'][0]['monto_exento'] == '0'): ?>No <?php endif; ?>
    <?php if ($this->_tpl_vars['campos_item'][0]['monto_exento'] == '1'): ?>Si <?php endif; ?>

  </td>
</tr>
<tr class="monto_iva">
  <td colspan="3" class="tb-head" align="left">
   I.V.A
 </td>
 <td>
   <?php echo $this->_tpl_vars['campos_item'][0]['iva']; ?>

 </td>
</tr>
</table>
</div>


<div id="div_tab3">
  <table   width="100%" border="0">
    <tr>
      <td colspan="4" class="tb-head" align="center">
        &nbsp;
      </td>
    </tr>

    <tr>
      <td valign="top" class="tb-head" colspan="3" align="right">
       <b>Existencia por Almacen<b>
       </td>
       <td>
         <table id="tabla_total" style="border: 1px solid #507e95;" bgcolor="white">
          <thead>
            <tr>
              <th align="left">Almacen</th>
              <th align="left">Cantidad</th>
            </tr>
          </thead>
          <tbody>
           <?php $this->assign('variable', 0); ?>
           <?php $_from = $this->_tpl_vars['campos_item']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['campos']):
?>
           <tr>
            <td><?php echo $this->_tpl_vars['campos']['nom_almacen']; ?>
</td>
            <td align="right">
              <?php if ($this->_tpl_vars['campos']['cantidad_almacen'] < '1'): ?>
              <span style="color:red"> <?php echo $this->_tpl_vars['campos']['cantidad_almacen']; ?>
</span>
              <?php endif; ?>


              <?php if ($this->_tpl_vars['campos']['cantidad_almacen'] > 0): ?>
              <span style="color:#00bc09"> <?php echo $this->_tpl_vars['campos']['cantidad_almacen']; ?>

               <?php $this->assign('variable', $this->_tpl_vars['variable']+$this->_tpl_vars['campos']['cantidad_almacen']); ?>
             </span>
             <?php endif; ?>
           </td>
         </tr>
         <tr>
          <?php endforeach; endif; unset($_from); ?>
          <tr style="background-color:navy;color:white;">
            <td>Total Existente</td>
            <td align="right">
              <?php echo $this->_tpl_vars['variable']; ?>

            </td>
          </tr>
          <tr>
          </tbody>
        </table>
      </td>
    </tr>

  </table>
</div>

</div>
<!-- /INFO PRODUCTO -->
<?php endif; ?>
<!--
***********************
***********************
-->
<?php if ($this->_tpl_vars['campos_item'][0]['descripcion_item_forma'] == 'Servicios' || $this->_tpl_vars['campos_item'][0]['descripcion_item_forma'] == 'Boleto'): ?>
<!-- INFO SERVICIO -->
<div id="contenedorTAB">
  <!-- TAB1 -->
  <div id="div_tab1">
    <table   width="100%" border="0" height="100">
      <tr>
        <td colspan="4" class="tb-head" align="center">
          Tipo de Item: <?php echo $this->_tpl_vars['campos_item'][0]['descripcion_item_forma']; ?>

        </td>
      </tr>
      <tr>
        <td  colspan="3" width="30%" class="tb-head" >
          Codigo **
        </td>
        <td >
         <?php echo $this->_tpl_vars['campos_item'][0]['cod_item']; ?>

       </td>
     </tr>
     <tr>
      <td class="tb-head" colspan="4" align="center" width="180">
        DATOS DEL item
      </td>
    </tr>
    <tr>
      <td colspan="3" class="tb-head">
        <?php echo $this->_tpl_vars['DatosGenerales'][0]['string_clasificador_inventario1']; ?>

      </td>
      <td>
       <?php echo $this->_tpl_vars['campos_item'][0]['departamento']; ?>

     </td>
   </tr>
   <tr>
    <td colspan="3" class="tb-head">
      <?php echo $this->_tpl_vars['DatosGenerales'][0]['string_clasificador_inventario2']; ?>

    </td>
    <td>
      <?php echo $this->_tpl_vars['campos_item'][0]['grupo']; ?>

    </td>
  </tr>
  <tr>
    <td colspan="3" class="tb-head">
      <?php echo $this->_tpl_vars['DatosGenerales'][0]['string_clasificador_inventario3']; ?>

    </td>
    <td>
      <?php echo $this->_tpl_vars['campos_item'][0]['linea']; ?>

    </td>
  </tr>

  <tr>
    <td colspan="3" class="tb-head">
      Descripción 1 **
    </td>
    <td>
      <?php echo $this->_tpl_vars['campos_item'][0]['descripcion1']; ?>

    </td>
  </tr>
  <tr>
    <td class="tb-head" colspan="3"  >
      Descripción 2
      <td>
        <?php echo $this->_tpl_vars['campos_item'][0]['descripcion2']; ?>

      </td>
    </tr>
    <tr>
      <td class="tb-head" colspan="3">
       Descripción 3
     </td>
     <td>
       <?php echo $this->_tpl_vars['campos_item'][0]['descripcion3']; ?>

     </td>
   </tr>
   <tr>
    <td class="tb-head" colspan="3">
      Referencia
    </td>
    <td>
      <?php echo $this->_tpl_vars['campos_item'][0]['referencia']; ?>

    </td>
  </tr>
  <tr>
    <td class="tb-head" colspan="3">
      Estatus
    </td>
    <td>

      <?php if ($this->_tpl_vars['campos_item'][0]['estatus'] == 'A'): ?>Activo<?php endif; ?>
      <?php if ($this->_tpl_vars['campos_item'][0]['estatus'] == 'I'): ?>Inactivo<?php endif; ?>

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

<!-- TAB3 -->
<div id="div_tab3">
  <table   width="100%" border="0">
    <tr>
      <td colspan="4" class="tb-head" align="center">

      </td>
    </tr>
    <tr>
      <td colspan="4" class="tb-head" align="center">
        PRECIOS
      </td>
    </tr>
    <tr>
      <td colspan="3" valign="top" class="tb-head" align="left">

      </td>
      <td >
       <table id="tabla_total" style="border: 1px solid #507e95;" bgcolor="white">
        <thead>
          <tr>
            <th align="left">Precios</th>
            <th align="left">Utilidad</th>
            <th align="left">Con Iva</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo $this->_tpl_vars['campos_item'][0]['precio1']; ?>
</td>
            <td><?php echo $this->_tpl_vars['campos_item'][0]['utilidad1']; ?>
</td>
            <td><?php echo $this->_tpl_vars['campos_item'][0]['coniva1']; ?>
</td>
          </tr>
          <tr>
            <td><?php echo $this->_tpl_vars['campos_item'][0]['precio2']; ?>
</td>
            <td><?php echo $this->_tpl_vars['campos_item'][0]['utilidad2']; ?>
</td>
            <td><?php echo $this->_tpl_vars['campos_item'][0]['coniva2']; ?>
</td>
          </tr>
          <tr>
            <td><?php echo $this->_tpl_vars['campos_item'][0]['precio3']; ?>
</td>
            <td><?php echo $this->_tpl_vars['campos_item'][0]['utilidad3']; ?>
</td>
            <td><?php echo $this->_tpl_vars['campos_item'][0]['coniva3']; ?>
</td>
          </tr>
        </tbody>
      </table>
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
     <?php if ($this->_tpl_vars['campos_item'][0]['monto_exento'] == '0'): ?>No <?php endif; ?>
     <?php if ($this->_tpl_vars['campos_item'][0]['monto_exento'] == '1'): ?>Si <?php endif; ?>

   </td>
 </tr>
 <tr class="monto_iva">
  <td colspan="3" class="tb-head" align="left">
   I.V.A
 </td>
 <td>
   <?php echo $this->_tpl_vars['campos_item'][0]['iva']; ?>

 </td>
</tr>
</table>
</div>
<!-- /TAB3 -->
</div>
<!-- /INFO SERVICIO -->
<?php endif; ?>

