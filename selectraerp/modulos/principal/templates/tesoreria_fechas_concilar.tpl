{literal}
<script language="JavaScript">
Ext.ns("prFechas");
prFechas.main = {
    init: function(){

        this.fecha1_ = new Ext.form.DateField({
            fieldLabel:'Fecha Inicio',
            name:'fecha1_',
            allowBlank:false
        });

        this.fecha2_ = new Ext.form.DateField({
            fieldLabel:'Fecha Fin',
            name:'fecha2_',
            allowBlank:false
        });

        this.formPanel_ = new Ext.FormPanel({
            style:'padding-top:5px;',
            width:'100%',
            url:Ext.get("string_url").dom.value,
            autoHeight:true,
            frame:true,
            standardSubmit:true,
            items:[
                {
                  xtype:'fieldset',
                  title:'Ultimo Periodo conciliado',
                  html:"Periodo: "+Ext.fly("periodo").dom.value
                },
                this.fecha1_,
                this.fecha2_
            ],
            buttonAlign:'left',
            buttons:[
                {
                    text:'Buscar',
                    handler:function(){
                        prFechas.main.formPanel_.getForm().submit();
                    }
                },
                {
                    text:'Limpiar',
                    handler:function(){
                        prFechas.main.formPanel_.getForm().reset();
                    }
                }
            ]

        });

        this.formPanel_.render("panel_fechas");

    }

};
Ext.onReady(prFechas.main.init,prFechas.main);
</script>
{/literal}


<table width="100%">
        <tr class="row-br">
            <td>
                <table class="tb-tit" cellspacing="0" cellpadding="1" border="0" width="100%">
                    <tbody>
                        <tr>
                        <td width="900"><span style="float:left"><img src="{$campo_seccion[0].img_ruta}" width="22" height="22" class="icon" />{$datos_banco[0].descripcion} | Cuenta {$registros[0].nro_cuenta} - {$subseccion[0].descripcion}</span></td>

                        <td width="75">

                        </td>

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
<input type="hidden" name="string_url" id="string_url"  value="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=tesoreria_conciliar&cod={$smarty.get.cod}&cod_cuenta={$smarty.get.cod_cuenta}">
<input type="hidden" name="periodo" id="periodo"  value="{$periodo}">

<div id="panel_fechas"></div>



