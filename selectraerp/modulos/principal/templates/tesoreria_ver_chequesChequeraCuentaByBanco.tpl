<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title></title>
        {literal}
            <script type="text/javascript">//<![CDATA[
                $(document).ready(function(){
                    $("#buscar").click(function(){
                        $("form").submit();
                    });
                });
                function direccionar(url){
                    window.location.href=url;
                }
                Ext.ns("pDACheque");
                pDACheque.main = {
                    cheque:0,
                    url:'',
                    init:function(){
                    },
                    chequeDanar:function(url,cheque){
                        pDACheque.main.cheque = cheque;
                        pDACheque.main.url = url;
                        this.fecha  = new Ext.form.DateField({
                            fieldLabel:'Fecha Daño',
                            format:'d/m/Y',
                            name:'fecha',
                            allowBlank:false
                        });
                        this.motivo = new Ext.form.TextArea({
                            fieldLabel:'Motivo del daño',
                            name:'motivo',
                            allowBlank:false,
                            width:210
                        });

    this.formPanel_ = new Ext.FormPanel({
       title:'',
       frame:true,
       width:350,
       items:[
           this.fecha,
           this.motivo
       ],
       buttonAlign:'center',
       buttons:[
           {
               text:'Aceptar',
               handler:function(){
                    if(!pDACheque.main.formPanel_.getForm().isValid()){
                        Ext.Msg.alert("Alerta","Debe especificar la fecha y el motivo");
                        return false;
                    }
                    motivo = pDACheque.main.motivo.getValue();
                    fecha = pDACheque.main.fecha.getRawValue();
                    url =  pDACheque.main.url+"&motivo="+motivo+"&fecha="+fecha;
                    window.location.href = url;

               }
           },
           {
               text:'Cancelar',
               handler:function(){
                pDACheque.main.win.hide();
               }
           }
       ]

    });

    this.win = new Ext.Window({
       title:'Dañar Cheque N° '+pDACheque.main.cheque,
       modal:true,
       closable:false,
       constrain: true,
       autoWidth:true,
       autoHeight:true,
       items:[
           this.formPanel_
       ]
    });
    this.win.show();
  },
                    chequeAnular:function(url,cheque){
    pDACheque.main.cheque = cheque;
    pDACheque.main.url = url;
    this.fecha  = new Ext.form.DateField({
      fieldLabel:'Fecha Anulación',
      name:'fecha',
      format:'d/m/Y',
      allowBlank:false
    });

    this.motivo = new Ext.form.TextArea({
      fieldLabel:'Motivo de anulación',
      name:'motivo',
      allowBlank:false,
      width:210
    });

    this.formPanel_ = new Ext.FormPanel({
       title:'',
       frame:true,
       width:350,
       items:[
           this.fecha,
           this.motivo
       ],
       buttonAlign:'center',
       buttons:[
           {
               text:'Aceptar',
               handler:function(){
                    if(!pDACheque.main.formPanel_.getForm().isValid()){
                        Ext.Msg.alert("Alerta","Debe especificar la fecha y el motivo");
                        return false;
                    }
                    motivo = pDACheque.main.motivo.getValue();
                    fecha = pDACheque.main.fecha.getRawValue();
                    url =  pDACheque.main.url+"&motivo="+motivo+"&fecha="+fecha;
                    window.location.href = url;

               }
           },
           {
               text:'Cancelar',
               handler:function(){
                pDACheque.main.win.hide();
               }
           }
       ]

    });

    this.win = new Ext.Window({
       title:'Anular Cheque N° '+pDACheque.main.cheque,
       modal:true,
       closable:false,
       constrain: true,
       autoWidth:true,
       autoHeight:true,
       items:[
           this.formPanel_
       ]
    });
    this.win.show();
  }
                    };
                    Ext.onReady(pDACheque.main.init,pDACheque.main);

                    function fDAcheque(url,opt,cheque){
                        if(opt=="An") pDACheque.main.chequeAnular(url,cheque)
                        if(opt=="Da") pDACheque.main.chequeDanar(url,cheque)
                    }
            //]]>
            </script>
        {/literal}
    </head>
    <body>
        <form name="{$name_form}" action="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}&amp;cod_chequera={$smarty.get.cod_chequera}" method="post">
            <table style="width: 100%;">
                <tr class="row-br">
                    <td>
                        <table class="tb-tit" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width: 900px;"><span style="float:left"><img src="{$campo_seccion[0].img_ruta}" width="22" height="22" class="icon" />{$datos_banco[0].descripcion_banco}, Cuenta {$datos_banco[0].nro_cuenta} - {$subseccion[0].descripcion}</span></td>
                                    <td style="width: 75px;">
                                        <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=listaChequeraCuentaByBanco&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}'">
                                            <tr>
                                                <td style="padding: 0px; text-align:right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                                <td class="btn_bg"><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                                <td class="btn_bg" style="padding: 0px 1px; white-space: nowrap;">Regresar</td>
                                                <td style="padding: 0px; text-align:left;"><img  src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <table class="tb-head" style="width: 100%;">
                <tr>
                    <td><input type="text" name="buscar" value="{$smarty.post.buscar}{$smarty.get.des}" size="20"/></td>
                    <td>
                        <select name="busqueda">
                            {html_options values=$option_values selected=$option_selected output=$option_output}
                        </select>
                    </td>
                    <td>
                        <table style="cursor: pointer;" class="btn_bg" id="buscar">
                            <tr>
                                <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                <td class="btn_bg"><img src="../../../includes/imagenes/search.gif" width="16" height="16" /></td>
                                <td class="btn_bg" style="padding: 0px 4px; white-space: nowrap;">Buscar</td>
                                <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table style="cursor: pointer;" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}'" class="btn_bg">
                            <tr>
                                <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                <td class="btn_bg"><img src="../../../includes/imagenes/list.gif" width="16" height="16" /></td>
                                <td class="btn_bg" style="padding: 0px 4px; white-space: nowrap;">Mostrar todo</td>
                                <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:120px;"><input type="radio" name="palabra" value="exacta" />Palabra exacta</td>
                    <td style="width:140px;"><input type="radio" name="palabra" value="todas" />Todas las palabras</td>
                    <td style="width:150px;"><input type="radio" name="palabra" value="cualquiera" checked/>Cualquier palabra</td>
                    <td style="width:386px;"></td>
                </tr>
            </table>
            <br/>
            <table class="seleccionLista" style="width:100%; text-align:center;">
                <tbody>
                    <tr class="tb-head" >
                        {foreach from=$cabecera key=i item=campos}
                            <td><strong>{$campos}</strong></td>
                        {/foreach}
                        <td colspan="3"><strong>Opciones</strong></td>
                    </tr>
                    {if $cantidadFilas == 0}
                        <tr><td colspan="11">{$mensaje}</td></tr>
                    {else}
                        {foreach from=$registros key=i item=campos}
                            {if $i%2==0}
                                {assign var=color value=""}
                            {else}
                                {assign var=color value="#cacacf"}
                            {/if}
                            <tr bgcolor="{$color}">
                                <td>
                                    {if $campos.situacion eq 'A'}<span style="color:green;"><b>Activa sin Uso</b></span>
                                    {elseif $campos.situacion eq 'Ac'}<span style="color:#c1c2c3;"><b>Activo Usado</b></span>
                                    {elseif $campos.situacion eq 'C'}<span style="color:red;"><b>Consumida</b></span>
                                    {elseif $campos.situacion eq 'D'}<span style="color:#c9c9c9;"><b>Dep&oacute;sito</b></span>
                                    {elseif $campos.situacion eq 'Da'}<span style="color:red;"><b>Da&ntilde;ado</b></span>
                                    {elseif $campos.situacion eq 'Im'}<span style="color:#c0c0c0;">Impreso</span>
                                    {elseif $campos.situacion eq 'An'}<span style="color:red;"><b>Anulado</b></span>
                                    {elseif $campos.situacion eq 'En'}<span style="color:blue;"><b>Entregado</b></span>
                                    {else}<span><i>Desconocido</i></span>
                                    {/if}
                                </td>
                                <td style="text-align: center;">{$campos.nro_cheque}</td>
                                <td>{$campos.ref}</td>
                                <td>{$campos.descripcion_proveedor}</td>
                                <td style="text-align: right; width: 100px; padding-right: 50px;">{$campos.monto|number_format:2:",":"."}</td>
                                <td style="text-align: center;">{$campos.fecha}</td>
                                <td style="text-align: center;">{$campos.fecha_anulacion}</td>
                                <td style="text-align: center;">{$campos.fecha_danado}</td>
                                {if $campos.situacion eq 'D' or $situacion_chequera eq 'C'}
                                    <td><img class="editar" title="No puede Activar" src="../../../includes/imagenes/ico_est6.gif"/></td>
                                    <td><img class="editar" title="No puede Dañar" src="../../../includes/imagenes/ico_est6.gif"/></td>
                                    <td><img class="editar" title="No puede Aular" src="../../../includes/imagenes/ico_est6.gif"/></td>
                                    {else}
                                        {if $campos.situacion eq 'Ac'}
                                        <td><img class="editar" title="No puede Activar" src="../../../includes/imagenes/ico_est6.gif"/></td>
                                        {else}
                                            {if $campos.situacion eq 'A'}
                                            <td><img class="editar" title="El cheque ya esta activo" src="../../../includes/imagenes/ico_est6.gif"/></td>
                                            {else}
                                                {if $campos.situacion eq 'Da' or $campos.situacion eq 'An'  or $campos.situacion eq 'Im' or  $campos.situacion eq 'En'}
                                                <td><img class="editar" title="No puede Activar" src="../../../includes/imagenes/ico_est6.gif"/></td>
                                                {else}
                                                <td><img style="cursor: pointer;" width="16" height="16" class="activar_cheque" onclick="javascript: window.location.href='{$url_query}&amp;cheque={$campos.cheque}&amp;action_cheque=A&amp;cod_cheque={$campos.cod_cheque}'" title="Activar Cheque" src="../../../includes/imagenes/activar.png"/></td>
                                                {/if}
                                            {/if}
                                        {/if}
                                        {if $campos.situacion eq 'Da' or $campos.situacion eq 'An'}
                                            {if $campos.situacion eq 'Da'}
                                            <td><img class="editar" title="Ya el cheque est&acute; da&ntilde;ado" src="../../../includes/imagenes/ico_est6.gif"/></td>
                                            <td><img class="editar" title="No puede anular" src="../../../includes/imagenes/ico_est6.gif"/></td>
                                            {else}
                                            <td><img class="editar" title="No puede da&ntilde;ar" src="../../../includes/imagenes/ico_est6.gif"/></td>
                                            <td><img class="editar" title="Ya el cheque est&aacute; anulado" src="../../../includes/imagenes/ico_est6.gif"/></td>
                                            {/if}
                                        {else}
                                        <td><img style="cursor: pointer;" width="16" height="16" class="danar_cheque" onclick="javascript: fDAcheque('{$url_query}&amp;cheque={$campos.cheque}&amp;action_cheque=Da&amp;cod_cheque={$campos.cod_cheque}','Da','{$campos.nro_cheque}')" title="Dañar Cheque" src="../../../includes/imagenes/cheque_danado.png"/></td>
                                        <td><img style="cursor: pointer;" width="16" height="16" class="anular_cheque" onclick="javascript: fDAcheque('{$url_query}&amp;cheque={$campos.cheque}&amp;action_cheque=An&amp;cod_cheque={$campos.cod_cheque}','An','{$campos.nro_cheque}')" title="Anular Cheque" src="../../../includes/imagenes/cheque_anular.png"/></td>
                                        {/if}
                                    {/if}
                            </tr>
                            {assign var=ultimo_cod_valor value=$campos.cod_cheque}
                        {/foreach}
                    {/if}
                </tbody>
            </table>
            <table class="tb-head" style="width: 100%;">
                <tbody>
                    <tr>
                        <td><span>P&aacute;gina&nbsp;</span></td>
                        <td><a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}&amp;cod_chequera={$smarty.get.cod_chequera}&amp;pagina=1&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}"><img src="../../../includes/imagenes/first.gif" title="Primera" alt="Primera" width="16" height="16" /></a></td>
                        <td><a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}&amp;cod_chequera={$smarty.get.cod_chequera}&amp;pagina={$pagina-1}&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}"><img src="../../../includes/imagenes/prev.gif" alt="Anterior" title="Anterior" width="16" height="16" /></a></td>
                        <td><input type="text" name="numero_pagina" value="{$pagina}" onblur="javascript: paginacion('?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}&amp;cod_chequera={$smarty.get.cod_chequera}',this.value,'&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}')" size="4"></td>
                        <td><a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}&amp;cod_chequera={$smarty.get.cod_chequera}&amp;pagina={$pagina+1}&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}"><img src="../../../includes/imagenes/next.gif" alt="Siguiente" title="Siguiente" width="16" height="16" /></a></td>
                        <td><a href="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion={$smarty.get.opt_subseccion}&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}&amp;cod_chequera={$smarty.get.cod_chequera}&amp;pagina={$num_paginas}&amp;tipo={$tipo}&amp;des={$des}&amp;busqueda={$busqueda}&amp;codigo={$ultimo_cod_valor}"><img src="../../../includes/imagenes/last.gif" alt="&Uacute;ltima" title="&Uacute;ltima" width="16" height="16" /></a></td>
                        <td colspan="14" style="width:100%; text-align:center;">P&aacute;gina {$pagina} de {$num_paginas}</td>
                    </tr>
                </tbody>
            </table>
        </form>
    </body>
</html>