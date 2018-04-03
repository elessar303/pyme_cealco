{if $campos_item[0].descripcion_item_forma eq "Productos"}
<!-- INFO PRODUCTO -->
<div id="contenedorTAB">
  <!-- TAB1 -->
  <div id="div_tab1">
    <table   width="100%" border="0" height="100">
      <tr>
        <td colspan="4" class="tb-head" align="center">
         Tipo de Item: {$campos_item[0].descripcion_item_forma} - Cantidad Total Existente ({$campos_item[0].existencia_total})
       </td>
     </tr>
     <tr>
        <td colspan="4" class="tb-head" align="center">
          {if $campos_item[0].foto eq ''}
            No posee Imagen para mostrar
          {else}
            <img src="../../imagenes/{$campos_item[0].foto}" width="100" align="absmiddle" height="100"/>
          {/if}
       </td>
     </tr>
     <tr>
      <td  colspan="3" width="30%" class="tb-head" >
        Codigo **
      </td>
      <td >
        {$campos_item[0].cod_item}
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
        {$campos_item[0].departamento}
      </td>
    </tr>
    <tr>
      <td colspan="3" class="tb-head">
        {$DatosGenerales[0].string_clasificador_inventario2}
      </td>
      <td>
        {$campos_item[0].grupo}
      </td>
    </tr>
    <tr>
      <td colspan="3" class="tb-head">
        {$DatosGenerales[0].string_clasificador_inventario3}
      </td>
      <td>
        {$campos_item[0].linea}
      </td>
    </tr>

    <tr>
      <td colspan="3" class="tb-head">
        Descripción 1 **
      </td>
      <td>
       {$campos_item[0].descripcion1}
     </td>
   </tr>
   <tr>
    <td class="tb-head" colspan="3"  >
      Descripción 2
      <td>
        {$campos_item[0].descripcion2}
      </td>
    </tr>
    <tr>
      <td class="tb-head" colspan="3">
       Descripción 3
     </td>
     <td>
       {$campos_item[0].descripcion3}
     </td>
   </tr>
   <tr>
    <td class="tb-head" colspan="3">
      Referencia
    </td>
    <td>
      {$campos_item[0].referencia}
    </td>
  </tr>
  <tr>
    <td class="tb-head" colspan="3">
      Codigo Fabricante
    </td>
    <td>
      {$campos_item[0].codigo_fabricante}
    </td>
  </tr>
  <tr>
    <td class="tb-head" colspan="3">
      Estatus
    </td>
    <td>

      {if $campos_item[0].estatus eq "A" }Activo {/if}
      {if $campos_item[0].estatus eq "I" }Inactivo {/if}

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
            <td>{$campos_item[0].precio1}</td>
            <td>{$campos_item[0].utilidad1}</td>
            <td>{$campos_item[0].coniva1}</td>
          </tr>
          <tr>
            <td>{$campos_item[0].precio2}</td>
            <td>{$campos_item[0].utilidad2}</td>
            <td>{$campos_item[0].coniva2}</td>
          </tr>
          <tr>
            <td>{$campos_item[0].precio3}</td>
            <td>{$campos_item[0].utilidad3}</td>
            <td>{$campos_item[0].coniva3}</td>
          </tr>
        </tbody>
      </table>
    </td>
  </tr>

  <tr>
    <td valign="top" class="tb-head" colspan="3"><b>Existencia Minima del item</b></td>
    <td><div class="string_empaque"></div>
      {$campos_item[0].existencia_min}
    </td>
  </tr>
  <tr>
    <td class="tb-head" colspan="3"><b>Existencia Maxima del item</b></td>
    <td><div class="string_empaque"></div>
      {$campos_item[0].existencia_max}
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

    {if $campos_item[0].monto_exento eq "0" }No {/if}
    {if $campos_item[0].monto_exento eq "1" }Si {/if}

  </td>
</tr>
<tr class="monto_iva">
  <td colspan="3" class="tb-head" align="left">
   I.V.A
 </td>
 <td>
   {$campos_item[0].iva}
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
              <th align="left">Ubicacion</th>
              <th align="left">Cantidad</th>
            </tr>
          </thead>
          <tbody>
           {assign var = variable value=0}
           {foreach from = $campos_item  item =campos}
           <tr>
            <td>{$campos.nom_almacen}</td>
            <td>{$campos.nom_ubicacion}</td>
            <td align="right">
              {if $campos.cantidad_almacen < "1"}
              <span style="color:red"> {$campos.cantidad_almacen}</span>
              {/if}


              {if $campos.cantidad_almacen > 0}
              <span style="color:#00bc09"> {$campos.cantidad_almacen}
               {assign var = variable value= $variable+$campos.cantidad_almacen}
             </span>
             {/if}
           </td>
         </tr>
         <tr>
          {/foreach}
          <tr style="background-color:navy;color:white;">
            <td>Total Existente</td>
            <td align="right">
              {$variable}
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
{/if}
<!--
***********************
***********************
-->
{if $campos_item[0].descripcion_item_forma eq "Servicios" or $campos_item[0].descripcion_item_forma eq "Boleto"}
<!-- INFO SERVICIO -->
<div id="contenedorTAB">
  <!-- TAB1 -->
  <div id="div_tab1">
    <table   width="100%" border="0" height="100">
      <tr>
        <td colspan="4" class="tb-head" align="center">
          Tipo de Item: {$campos_item[0].descripcion_item_forma}
        </td>
      </tr>
      <tr>
        <td  colspan="3" width="30%" class="tb-head" >
          Codigo **
        </td>
        <td >
         {$campos_item[0].cod_item}
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
       {$campos_item[0].departamento}
     </td>
   </tr>
   <tr>
    <td colspan="3" class="tb-head">
      {$DatosGenerales[0].string_clasificador_inventario2}
    </td>
    <td>
      {$campos_item[0].grupo}
    </td>
  </tr>
  <tr>
    <td colspan="3" class="tb-head">
      {$DatosGenerales[0].string_clasificador_inventario3}
    </td>
    <td>
      {$campos_item[0].linea}
    </td>
  </tr>

  <tr>
    <td colspan="3" class="tb-head">
      Descripción 1 **
    </td>
    <td>
      {$campos_item[0].descripcion1}
    </td>
  </tr>
  <tr>
    <td class="tb-head" colspan="3"  >
      Descripción 2
      <td>
        {$campos_item[0].descripcion2}
      </td>
    </tr>
    <tr>
      <td class="tb-head" colspan="3">
       Descripción 3
     </td>
     <td>
       {$campos_item[0].descripcion3}
     </td>
   </tr>
   <tr>
    <td class="tb-head" colspan="3">
      Referencia
    </td>
    <td>
      {$campos_item[0].referencia}
    </td>
  </tr>
  <tr>
    <td class="tb-head" colspan="3">
      Estatus
    </td>
    <td>

      {if $campos_item[0].estatus eq "A" }Activo{/if}
      {if $campos_item[0].estatus eq "I" }Inactivo{/if}

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
            <td>{$campos_item[0].precio1}</td>
            <td>{$campos_item[0].utilidad1}</td>
            <td>{$campos_item[0].coniva1}</td>
          </tr>
          <tr>
            <td>{$campos_item[0].precio2}</td>
            <td>{$campos_item[0].utilidad2}</td>
            <td>{$campos_item[0].coniva2}</td>
          </tr>
          <tr>
            <td>{$campos_item[0].precio3}</td>
            <td>{$campos_item[0].utilidad3}</td>
            <td>{$campos_item[0].coniva3}</td>
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
     {if $campos_item[0].monto_exento eq "0" }No {/if}
     {if $campos_item[0].monto_exento eq "1" }Si {/if}

   </td>
 </tr>
 <tr class="monto_iva">
  <td colspan="3" class="tb-head" align="left">
   I.V.A
 </td>
 <td>
   {$campos_item[0].iva}
 </td>
</tr>
</table>
</div>
<!-- /TAB3 -->
</div>
<!-- /INFO SERVICIO -->
{/if}


