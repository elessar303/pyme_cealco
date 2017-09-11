<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {literal}
            <script type="text/javascript">//<![CDATA[
            Ext.ns("tCuentaEditar");
            tCuentaEditar.main= {
                init:function(){
                    this.storeCuContable = new Ext.data.JsonStore({
                        url:'../../libs/php/ajax/ajax.php',
                        baseParams:{
                            opt:'store_cuContable'
                        },
                        root:'data',
                        fields:[
                            {name:'descripcion'},
                            {name:'cuenta'}
                        ]
                    });

                    this.storeTipoCuenta = new Ext.data.JsonStore({
                        url:'../../libs/php/ajax/ajax.php',
                        baseParams:{
                            opt:'store_tipoCuenta'
                        },
                        root:'data',
                        fields:[
                            {name:'descripcion'},
                            {name:'cod_tipo_cuenta_banco'}
                        ]
                    });

                    this.tx_descripcion = new Ext.form.TextField({
                        fieldLabel:'Descripci&oacute;n de la cuenta',
                        name:'descripcion',
                        value:Ext.get("tmpdescripcion").dom.value,
                        allowBlank:false
                    });

                    this.nu_cuenta = new Ext.form.TextField({
                        fieldLabel:'N&uacute;mero de la cuenta',
                        name:'nrocuenta',
                        value:Ext.get("tmpnrocuenta").dom.value,
                        allowBlank:false
                    });

                    this.comision_debito = new Ext.form.NumberField({
                        fieldLabel:'Comisi&oacute;n Tarjeta D&eacute;bito',
                        name:'com_debito',
                        value:Ext.get("comision_debito").dom.value,
                        allowBlank:false
                    });

                    this.comision_credito = new Ext.form.NumberField({
                        fieldLabel:'Comisi&oacute;n Tarjeta Cr&eacute;dito',
                        name:'com_credito',
                        value:Ext.get("comision_credito").dom.value,
                        allowBlank:false
                    });

                    this.comision_impuesto_debito_bancario = new Ext.form.NumberField({
                        fieldLabel:'Comisi&oacute;n Impuesto D&eacute;bito Bancario (IDB)',
                        name:'com_idb',
                        value:Ext.get("comision_idb").dom.value,
                        allowBlank:false
                    });

                    this.retencion_islr = new Ext.form.NumberField({
                        fieldLabel:'Retenci&oacute;n ISLR',
                        name:'com_islr',
                        value:Ext.get("retencion_islr").dom.value,
                        allowBlank:false
                    });

                    this.cmbTipoCuenta = new Ext.form.ComboBox({
                        fieldLabel:'Tipo de Cuenta',
                        resizable:true,
                        store: this.storeTipoCuenta,
                        typeAhead: true,
                        valueField: 'cod_tipo_cuenta_banco',
                        displayField:'descripcion',
                        hiddenName:'cod_tipo_cuenta_banco',
                        forceSelection:true,
                        name: 'cmbTipoCuenta',
                        id: 'idcmbTipoCuenta',
                        triggerAction: 'all',
                        selectOnFocus: true,
                        mode: 'local',
                        width:190,
                        resizable:true,
                        allowBlank:false
                    });

                    this.storeTipoCuenta.load();

                    this.storeTipoCuenta.on("load",function(){
                        tCuentaEditar.main.cmbTipoCuenta.setValue(Ext.get("tmpcod_tipo_cuenta_banco").dom.value)
                    });

                    this.fe_apertura = new Ext.form.DateField({
                         fieldLabel:'Fecha de apertura',
                         name:'fechaapertura',
                         allowBlank:false,
                         value:Ext.get("tmpfecha_apertura").dom.value
                    });

                    this.nu_apertura = new Ext.form.NumberField({
                        fieldLabel:'Monto de apertura',
                        name:'montoapertura',
                        value:Ext.get("tmpmontoapertura").dom.value
                    });

                    this.nu_disponible = new Ext.form.NumberField({
                        fieldLabel:'Monto Disponible',
                        name:'montodisponible',
                        value:Ext.get("tmpmontodisponible").dom.value
                    });

                    this.cmbCuContable = new Ext.form.ComboBox({
                        fieldLabel:'Cuenta Contable',
                        resizable:true,
                        store: this.storeCuContable,
                        typeAhead: true,
                        valueField: 'cuenta',
                        displayField:'descripcion',
                        hiddenName:'cuenta_contable',
                        forceSelection:true,
                        name: 'cmbCuContable',
                        id: 'idcmbCuContable',
                        triggerAction: 'all',
                        selectOnFocus: true,
                        minChars:1,
                        mode: 'remote',
                        width:190,
                        resizable:true,
                        allowBlank:false
                    });

                   this.storeCuContable.load();

                    /*this.storeCuContable.on("load",function(){
                        tCuentaEditar.main.cmbCuContable.setValue(Ext.get("tmpcuentacontable").dom.value);
                    });*/

                    tCuentaEditar.main.cmbCuContable.setValue(Ext.get("tmpcuentacontable").dom.value);

                    this.co_cuenta = new Ext.form.Hidden({
                        name:'codCuenta',
                        value: Ext.get("tmpcodCuenta").dom.value
                    });

                    this.co_banco = new Ext.form.Hidden({
                        name:'codBanco',
                        value:Ext.get("tmpcod_banco").dom.value
                    });

                    this.formpanel_ = new Ext.FormPanel({
                       renderTo:"panel_",
                       standardSubmit: true,
                       url:'',
                       frame:true,
                       title:'Informaci&oacute;n de la cuenta',
                       items:[
                           {
                                xtype:'fieldset',
                                items:[this.tx_descripcion,this.nu_cuenta,this.comision_debito,this.comision_credito,this.comision_impuesto_debito_bancario,this.retencion_islr]
                           },
                           {
                                xtype:'fieldset',
                                items:[this.cmbTipoCuenta]
                           },
                           {
                               xtype:'fieldset',
                               items:[
                                this.co_banco ,
                                this.co_cuenta,
                                this.fe_apertura,
                                this.nu_apertura,
                                this.nu_disponible,
                                this.cmbCuContable
                               ]
                           },
                       ],
                       buttons:[
                           {
                               text:'Guardar',
                               handler:function(){
                                    if(!tCuentaEditar.main.formpanel_.getForm().isValid()){
                                        Ext.Msg.alert("Mensaje","Debe llenar los campos en rojo");
                                        return false;
                                    }
                                    tCuentaEditar.main.formpanel_.getForm().submit();
                               }
                           }
                       ]
                    });
                }
            };
            Ext.onReady(tCuentaEditar.main.init, tCuentaEditar.main);
            //]]></script>
            {/literal}
    </head>
    <body>
        <!--<form name="formulario" id="formulario" method="POST" action="">-->
        <table style="width: 100%;">
            <tr class="row-br">
                <td>
                    <table class="tb-tit" style="width: 100%;">
                        <tbody>
                            <tr>
                                <td width="900"><span style="float:left"><img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" />{$subseccion[0].descripcion}</span></td>
                                <td width="75">
                                    <table style="cursor: pointer;" class="btn_bg" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=viewcuentasByBanco&amp;cod={$smarty.get.cod}'">
                                        <tr>
                                            <td style="padding: 0px; text-align: right;"><img src="../../../includes/imagenes/bt_left.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                            <td class="btn_bg"><img src="../../../includes/imagenes/back.gif" width="16" height="16" /></td>
                                            <td class="btn_bg" style="padding: 0px 1px; white-space: nowrap;">Regresar</td>
                                            <td style="padding: 0px; text-align: left;"><img src="../../../includes/imagenes/bt_right.gif" alt="" width="4" height="21" style="border-width: 0px;" /></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <input type="hidden" value="{$campos_cuentas[0].descripcion}" name="tmpdescripcion" id="tmpdescripcion"/>
        <input type="hidden" value="{$campos_cuentas[0].cod_tesor_bandodet}" name="tmpcodCuenta" id="tmpcodCuenta"/>
        <input type="hidden" value="{$campos_cuentas[0].cod_tipo_cuenta_banco}" name="tmpcod_tipo_cuenta_banco" id="tmpcod_tipo_cuenta_banco"/>
        <input type="hidden" value="{$campos_cuentas[0].cuenta_contable}" name="tmpcuentacontable" id="tmpcuentacontable"/>
        <input type="hidden" value="{$campos_cuentas[0].nro_cuenta}" name="tmpnrocuenta" id="tmpnrocuenta"/>
        <input type="hidden" value="{$campos_cuentas[0].cod_banco}" name="tmpcod_banco" id="tmpcod_banco"/>
        <input type="hidden" value="{$campos_cuentas[0].monto_apertura}" name="tmpmontoapertura" id="tmpmontoapertura"/>
        <input type="hidden" value="{$campos_cuentas[0].monto_disponible}" name="tmpmontodisponible" id="tmpmontodisponible"/>
        <input type="hidden" value="{$campos_cuentas[0].fecha_apertura}" name="tmpfecha_apertura" id="tmpfecha_apertura"/>
        <input type="hidden" value="{$campos_cuentas[0].comision_tarjeta_debito}" name="comision_debito" id="comision_debito"/>
        <input type="hidden" value="{$campos_cuentas[0].comision_tarjeta_credito}" name="comision_credito" id="comision_credito"/>
        <input type="hidden" value="{$campos_cuentas[0].comision_idb}" name="comision_idb" id="comision_idb"/>
        <input type="hidden" value="{$campos_cuentas[0].retencion_islr}" name="retencion_islr" id="retencion_islr"/>
        <div id="panel_"></div>
    </body>
</html>
