<div class="contenedor_factura" style="width:99%; float:left;">
    <img style="vertical-align: middle;" src="../../../includes/imagenes/ico_user.gif"/>
    <span style="font-family:'Verdana';"><b>Cliente: </b></span>
    <span style="font-family:'Verdana';">{$datacliente[0].nombre}</span>
    <input type="hidden" name="id_cliente" value="{$datacliente[0].id_cliente}"/>
    <input type="hidden" name="id_fiscal" value="{$datacliente[0].rif}"/>
    <input type="hidden" name="numero_control_factura" value="{$nro_cotizacion}"/>
    <br/>
    <img src="../../../includes/imagenes/ico_user.gif" style="vertical-align: middle;"/>
    <span style="font-family:'Verdana';"><b>Vendedor:</b></span>
    <select name="cod_vendedor" id="cod_vendedor">
        {html_options output=$option_output_vendedor values=$option_values_vendedor selected=$option_selected_vendedor}
    </select>
    <br/>
    <!--img align="absmiddle" src="../../../includes/imagenes/ico_user.gif">
    <span style="font-family:'Verdana';"><b>Estado de Entrega de Materiales</b></span>
    <select name="estado_entrega" id="estado_entrega">
        <option value="Entregado">Entregado</option>
        <option value="Pendiente">Pendiente</option>
    </select>
    </td-->
</div>