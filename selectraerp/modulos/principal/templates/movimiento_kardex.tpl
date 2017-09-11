

<form name="formulario" id="formulario" method="POST" action="">
<input type="hidden" name="Datosproveedor" value="">
<input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}">
<input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}">
<input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}">
<input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}">
  <table width="100%">
        <tr class="row-br">
            <td>
                <table class="tb-tit" cellspacing="0" cellpadding="1" border="0" width="100%">
                    <tbody>
                        <tr>
                        <td width="900"><span style="float:left"><img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" />{$subseccion[0].descripcion}</span></td>
                        <td width="75">
                            <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}'" name="buscar" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 0px;" align="right"><img src="../../libs/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                    <td class="btn_bg"><img src="../../libs/imagenes/back.gif" width="16" height="16" /></td>
                                    <td class="btn_bg" nowrap style="padding: 0px 1px;">Regresar</td>
                                    <td  style="padding: 0px;" align="left"><img  src="../../libs/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                </tr>
                            </table>
                        </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

<!--<Datos del proveedor y vendedor>-->
<br>
    <table>
        <tr>
            <td>
                <img align="absmiddle" width="17" height="17" src="../../libs/imagenes/28.png">
                <span style="font-family:'Verdana';"><b>Autorizado Por: (*):</b></span>
            </td>
            <td>
                <input type="text" maxlength="70" name="autorizado_por" id="autorizado_por" value="{$registros[0].autorizado_por}">
            </td>
       </tr>
       <tr>
            <td>
               <img align="absmiddle" width="17" height="17" src="../../libs/imagenes/8.png">
               <span style="font-family:'Verdana';"><b>Observaciones</b></span>
            </td>
            <td>
                <input type="text" name="observaciones" maxlength="70"  id="observaciones" value="{$registros[0].observaciones_entrega}">
            </td>
       </tr>
       <tr>
            <td>
                <img align="absmiddle" width="17" height="17" src="../../libs/imagenes/8.png">
                <span style="font-family:'Verdana';"><b>Fecha:</b></span>
            </td>
            <td>
                <input type="text" maxlength="70" name="fecha" id="fecha" value='{$smarty.now|date_format:"%d-%m-%Y"}'>
            </td>
       </tr>
       <tr>
            <td>
                <img align="absmiddle" width="17" height="17" src="../../libs/imagenes/8.png">
                <span style="font-family:'Verdana';"><b>Documento :</b></span>
            </td>
            <td>
                <input type="hidden" maxlength="70" name="id_documento" id="id_documento" value='{$registros[0].id_documento}'>
                <input type="text" maxlength="70" name="cod_documento" id="cod_documento" value='{$registros[0].cod_documento}'>
            </td>
       </tr>
    </table>
<br>
    <table width="100%" class="lista">
            <tr >
            <th class="tb-tit" width="10%" >Codigo</th>
            <th class="tb-tit" width="30%" >Descripcion</th>
            <th class="tb-tit" width="15%" >Almacen</th>
            <th class="tb-tit" width="15%" >Cantidad Pedida</th>
            <th class="tb-tit" width="15%" >Cantidad Entregada</th>
            <th class="tb-tit" width="15%" >Cantidad Por Entregar</th>

            </tr>
     </table>
<br>
    <table width="100%">
            <tbody>
                {foreach from = $registros item = campos key = i}
                <tr class="sf_admin_row_1" >
                    <td width="10%" align="left">{$campos.codigo}</td>
                    <td width="30%" align="left">{$campos.descripcion_item}</td>
                    <td width="15%" >{$campos.nombre_almacen} </td>
                    <td width="15%" >{$campos.cantidad_item} </td>
                    <td width="15%" >{$campos.cantidad_entregada} </td>
                    <td width="15%" ><input type="text" name="cantidad_registrada" maxlength="70" value="{$campos.cantidad_item - $campos.cantidad_entregada}" id="cantidad_registrada"></td>
                </tr>
                {/foreach}
            </tbody>
    </table> 


</form>


