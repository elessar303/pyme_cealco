<!DOCTYPE html>
<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Charli Vivenes" />
        <title></title>
        <link type="text/css" href="../../../includes/js/jquery-ui-1.10.0/css/redmond/jquery-ui-1.10.0.custom.min.css" rel="Stylesheet"/>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-1.9.0.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/js/jquery-ui-1.10.0.custom.min.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-1.10.0/development-bundle/ui/i18n/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript" src="../../../includes/js/jquery-ui-timepicker-addon.js"></script>
        
        <link type="text/css" rel="stylesheet" href="../../../includes/css/estilos_basicos.css" />
        {*if $campo_seccion neq NULL}
            {assign var=nom_menu value=$campo_seccion[0].nom_menu}
        {else}
            {assign var=nom_menu value=$cabeceraSeccionesByOptMenu[0].nom_menu}
        {/if}
        {if $cabeceraSeccionesByOptMenu[0].cod_modulo eq 54}
            {assign var=valcolap value=0}
        {else}
            {assign var=valcolap value=1}
        {/if*}
        {literal}
            <script type="text/javascript">//<![CDATA[
            Ext.onReady(function()
            {
                var formpanel = new Ext.Panel({
                    title:' <img src='+$("#imagen").val()+' width="22" height="22" class="icon" /> {/literal}{$campo_seccion[0].nom_menu}{literal}',
                    autoHeight: 600,
                    width: '100%',
                    collapsible: true,
                    titleCollapse: true,
                    contentEl:'datosGral',
                    frame:true
                });
                formpanel.render("formulario");
                $("input[name='aceptar'], input[name='cancelar']").button();
                $("#formato").buttonset();
            });
            function valida_envia(rpt1, rpt2){
                if (document.formulario.fecha.value.length == 0){
                   alert("Debe seleccionar una fecha para el documento.");
                   document.formulario.fecha.focus();
                   return false;
                }
                var inputs =document.getElementsByTagName("input");
                var flag_fecha = false;
                for(var i=0;i<inputs.length;i++){
                    if(inputs[i].getAttribute("name") == "cant_fechas"){
                        var v = inputs[i].getAttribute("value");
                            i = inputs.length;
                            flag_fecha = true;
                    }
                }
                var flag_filtro = false;
                for(var i=0;i<inputs.length;i++){
                    if(inputs[i].getAttribute("name") == "tiene_filtro"){
                        var filtro = inputs[i].getAttribute("value");
                            i = inputs.length;
                            flag_filtro = true;
                    }
                }
                var ini = document.formulario.fecha.value;
                if(document.formulario.siga)
                var siga =document.formulario.siga.value;
                var tipo_mov = '0';
                var tipo_mov = document.getElementById("tipo_mov");
                tipo_mov=tipo_mov.value;
                var flag = 0;
                var producto1 = '';
               
                var filtro_codigo = document.getElementById("filtro_codigo");
                filtro_codigo=filtro_codigo.value;

                        

                if(producto1!=""){
                    producto1="&producto="+producto1;}
                    else{
                        producto1="&producto=null";
                    }
                if(siga!="0"){
                siga="&siga="+siga;
                    }else{
                    siga="&siga=null";
                    }

                if(tipo_mov!="0"){
                tipo_mov="&tip_mov="+tipo_mov;
                    }else{
                    tipo_mov="&tipo_mov=null";
                    }

                    if(filtro_codigo!=""){
                filtro_codigo="&filtro_codigo="+filtro_codigo;
                    }else{
                    filtro_codigo="&filtro_codigo=0";
                    }

                var fin = flag_fecha ? ( v == "2" ? document.formulario.fecha2.value : null) : null;
                var report = document.formulario.radio[1].checked ? rpt1 : rpt2;
                var params = !fin ? '?fecha='+ini : '?fecha='+ini+'&fecha2='+fin;
                    params = flag_filtro ? params + '&filtrado_por='+filtro : params;
                window.open('../../reportes/'+report+params+siga+producto1+tipo_mov+filtro_codigo);

            }
            //]]>
            </script>
        {/literal}
    
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Charli Vivenes" />
        <title></title>
        {include file="snippets/inclusiones_reportes2.tpl"}
        {literal}
            <script type="text/javascript">//<![CDATA[

            function validacion()
                {
                
                    document.formulario.action = "../../reportes/reporte_conductor_total.php";
                    document.formulario.submit();

                }
            function activarButton()
            {
                var value = $('#codigoBarra').val();
                if(value=="") 
                {
                    pBuscaItem.main.mostrarWin();
                 
                }
            }
            //]]>
            </script>
        {/literal}
    </head>
    <body>
        <form name="formulario" id="formulario" method="post"  target="_black" onsubmit="validacion()"  >
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar_boton.tpl"}
                <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="cant_fechas" id="cant_fechas" value="2"/>
                <input type="hidden" name="ordenar_por" id="ordenar_por" value="1"/>
                <input type="hidden" name="tiene_filtro" id="tiene_filtro" value="3"/>
                <table style="width:100%; background-color:white;" align="center" border="0">
                    <thead>
                        <tr>
                            <th colspan="6" class="tb-head" style="text-align:center;">
                                LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr>
                            <td class="label">¿Posee Vehiculo?</td>
                            <td colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="posee" id="posee" style="width:200px;" class="form-text">
                                    <option value="999">TODOS </option>
                                    <option value="1">SI</option>
                                    <option value="0">NO</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Flota</td>
                            <td colspan="3" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="estado" id="estado" style="width:200px;" class="form-text">
                                    {html_options values=$option_values_estado output=$option_output_estado}
                                </select>
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="label"></td>
                            <td colspan="4" style="padding-top:2px; padding-bottom: 2px;">
                                <div id="formato">
                                    <input type="radio" id="radio2" name="radio" value="1" checked /><label for="radio2">Formato PDF</label>
                                </div>

                            </td>

                        </tr>
                        <tr class="tb-tit">
                            <td colspan="6">
                                <input type="submit" id="aceptar" name="aceptar" value="Enviar"  />
                                <input type="button" name="cancelar" value="Cancelar"  />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>