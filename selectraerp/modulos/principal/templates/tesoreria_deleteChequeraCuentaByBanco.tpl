{literal}
<script language="JavaScript">
    Ext.onReady(function(){

        

        $("#inicio, #cantidad").attr("readonly", "readonly");

    $("#montoapertura, #montodisponible").blur(function(){
            valor = $(this).val();
                if(valor<0){
                    Ext.Msg.alert("Alerta!","El monto debe ser Mayor a cero (0)");
                }

        });

        new Ext.Panel({
            title:'Cantidad de Cheques',
            contentEl: 'formpanel1',

            renderTo:'formulario',
            bodyStyle:'padding:10px;'
        });

        new Ext.Panel({

            title:'Número del Primer Cheque',
            contentEl: 'formpanel2',
            renderTo:'formulario',
            bodyStyle:'padding:10px;',
             buttons:[
               
                {
                    text:'Eliminar',
                    id:'guardar_',

                    listeners:{
                        click:function(){

                            if($("#cantidad").val()==''||$("#cantidad").val()=="0"){
                                Ext.Msg.alert("Alerta","Debe incicar la cantidad de cheques.");
                                return false;
                                }
                            if($("#inicio").val()==''||$("#inicio").val()=="0"){
                                Ext.Msg.alert("Alerta","Debe indicar la cantidad el Nro. del primer Cheque.");
                                return false;
                            }


                            if($("#cod_tipo_cuenta_banco").val()==''){
                                Ext.Msg.alert("Alerta","Debe seleccionar el tipo de Cuenta");
                                return false;
                            }
                    Ext.fly("formulario").dom.submit();
                        }
                    }
                }
            ]
        });




});
</script>
{/literal}

<form name="formulario" id="formulario" method="POST" action="">
<input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}">
<input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}">
<input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}">
<input type="hidden" name="opt_subseccion" value="{$smarty.get.opt_subseccion}">
<input type="hidden" name="codBanco" value="{$smarty.get.cod}">
<input type="hidden" name="codCuenta" value="{$smarty.get.cod_cuenta}">
<input type="hidden" name="codChequera" value="{$smarty.get.cod_chequera}">
  <table width="100%">
        <tr class="row-br">
            <td>
                <table class="tb-tit" cellspacing="0" cellpadding="1" border="0" width="100%">
                    <tbody>
                        <tr>
                        <td width="900"><span style="float:left"><img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" />{$subseccion[0].descripcion} - {$datos_banco[0].descripcion_banco},  Cuenta: {$datos_banco[0].nro_cuenta}</span></td>
                        <td width="75">
                            <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=listaChequeraCuentaByBanco&cod={$smarty.get.cod}&cod_cuenta={$smarty.get.cod_cuenta}'" name="buscar" border="0" cellpadding="0" cellspacing="0">
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


<div  id="formpanel1" class="x-hide-display" >

<input type="text" maxlength="70" value="{$datos_chequera[0].cantidad}" name="cantidad" id="cantidad">

</div>

<div  id="formpanel2" class="x-hide-display" >
<input type="text" maxlength="70" value="{$datos_chequera[0].inicio}" name="inicio" id="inicio">
</div>






</form>
