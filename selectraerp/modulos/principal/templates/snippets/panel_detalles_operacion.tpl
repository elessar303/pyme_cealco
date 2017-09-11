<div style="clear:left;" class="contenedor_factura">
    <div style="float: left; margin-right: 20px;">
        <input type="hidden" name="input_fechaFactura" id="input_fechaFactura" value='{$smarty.now|date_format:"%Y-%m-%d"}'/>
    </div>
    <!--div style=" margin-right: 20px;">
        Cotización Numero
        <div style="font-size:15px;color:red;" id="numFactura">{$nro_cotizacion}</div>
    </div-->
    <div id="lick_detalle" style="cursor:pointer; width:150px;"><img style="vertical-align: middle;" src="../../../includes/imagenes/drop-add2.gif"/> Ocultar detalles</div>
    <div id="detalle_factura_">
        <br/>
        <div class="resumen">
            Sub-Total
            <div style="font-size:20px; color:#2e931a;" id="subTotal">0.00 {$DatosGenerales[0].moneda}</div>
            <input type="hidden" name="input_subtotal" id="input_subtotal" value=""/>
        </div>
        <div class="resumen">
            Descuento
            <input type="hidden" name="input_descuentosItemFactura" id="input_descuentosItemFactura" value=""/>
            <div style="font-size:20px; color:red; text-decoration:line-through;" id="descuentosItemFactura">0.00 {$DatosGenerales[0].moneda}</div>
        </div>
        <div class="resumen">
            Monto Items
            <input type="hidden" name="input_montoItemsFactura" id="input_montoItemsFactura" value=""/>
            <div style="font-size:20px; color:#2e931a;" id="montoItemsFactura">0.00 {$DatosGenerales[0].moneda}</div>
        </div>
        <div class="resumen">
            I.V.A
            <input type="hidden" name="input_ivaTotalFactura" id="input_ivaTotalFactura" value=""/>
            <div style="font-size:20px; color:#2e931a;" id="ivaTotalFactura">0.00 {$DatosGenerales[0].moneda}</div>
        </div>
        <div class="resumen">
            Total
            <input type="hidden" name="input_TotalTotalFactura" id="input_TotalTotalFactura" value=""/>
            <div style="font-size:20px; color:#00005e;" id="TotalTotalFactura">0.00 {$DatosGenerales[0].moneda}</div>
        </div>
        <br/><br/><br/>
        <input type="hidden" name="input_cantidad_items" id="input_cantidad_items" value=""/>
        <div class="span_cantidad_items">
            <span style="font-size: 15px; font-style: italic;">Cantidad de Items: 0</span>
        </div>
    </div>
</div>