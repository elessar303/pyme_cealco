<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {literal}
            <script type="text/javascript">//<![CDATA[
            Ext.ns("tCuentaAgregar");
            tCuentaAgregar.main = {
                init:function(){
                    // Extraer datos para mostrar en Combobox
                    this.storeCuentaContable = new Ext.data.JsonStore({
                        url:'../../libs/php/ajax/ajax.php',// Archivo *.php donde esta el sql
                        baseParams:{
                            opt:'store_cuContable'// Opcion de un Switch en *.php en la que se define la consulta sql
                        },
                        root:'data',// Fuente de datos definida en *.php
                        fields:[// Campos seleccionados en la cosulta sql
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
                   // Definir etiquetas para los campos del formulario
                    this.tx_descripcion = new Ext.form.TextField({
                        fieldLabel:'Descripci&oacute;n de la cuenta',
                        name:'descripcion',
                        allowBlank:false
                    });

                    this.nu_cuenta = new Ext.form.TextField({
                        fieldLabel:'N&uacute;mero de la cuenta',
                        name:'nrocuenta',
                        allowBlank:false
                    });

                    this.comision_debito = new Ext.form.NumberField({
                        fieldLabel:'Comisi&oacute;n Tarjeta D&eacute;bito',
                        name:'com_debito',
                        allowBlank:false
                    });

                    this.comision_credito = new Ext.form.NumberField({
                        fieldLabel:'Comisi&oacute;n Tarjeta Cr&eacute;dito',
                        name:'com_credito',
                        allowBlank:false
                    });

                    this.comision_impuesto_debito_bancario = new Ext.form.NumberField({
                        fieldLabel:'Comisi&oacute;n Impuesto D&eacute;bito Bancario (IDB)',
                        name:'com_idb',
                        allowBlank:false
                    });

                    this.retencion_islr = new Ext.form.NumberField({
                        fieldLabel:'Retenci&oacute;n ISLR',
                        name:'com_islr',
                        allowBlank:false
                    });
                    // Definir Combobox
                    this.cmbTipoCuenta = new Ext.form.ComboBox({
                        fieldLabel:'Tipo de Cuenta',
                        resizable:true,
                        store: this.storeTipoCuenta,// Se asignan los dqtos cargados en storeTipoCuenta a este elemento del formu
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
                        //width:190,
                        resizable:true,
                        allowBlank:false
                    });

                    this.storeTipoCuenta.load();

                    this.fe_apertura = new Ext.form.DateField({
                         fieldLabel:'Fecha de apertura',
                         name:'fechaapertura',
                         allowBlank:false
                    });

                    this.nu_apertura = new Ext.form.NumberField({
                        fieldLabel:'Monto de apertura',
                        name:'montoapertura'
                    });

                    this.nu_disponible = new Ext.form.NumberField({
                        fieldLabel:'Monto Disponible',
                        name:'montodisponible'
                    });

                    this.cmbCuContable = new Ext.form.ComboBox({
                        fieldLabel:'Cuenta Contable',
                        resizable:true,
                        store: this.storeCuentaContable,
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
                        //width:190,
                        resizable:true,
                        allowBlank:false
                    });

                   this.storeCuentaContable.load();

                    this.co_banco = new Ext.form.Hidden({
                        name:'codBanco',
                        value:Ext.get("tmpcodbanco").dom.value
                    });

                    this.formpanel_ = new Ext.FormPanel({
                       renderTo:"panel_",
                       standardSubmit: true,
                       url:'',
                       frame:true,
                       title:'Informaci&oacute;n de la cuenta',
                       items:[// Se asignan los elementos definidos
                           {
                                xtype:'fieldset',
                                items:[
                                    this.tx_descripcion,
                                    this.nu_cuenta,
                                    this.comision_debito,
                                    this.comision_credito,
                                    this.comision_impuesto_debito_bancario,
                                    this.retencion_islr
                                ]
                           },
                           {
                                xtype:'fieldset',
                                items:[this.cmbTipoCuenta]
                           },
                           {
                               xtype:'fieldset',
                               items:[
                                   this.co_banco ,
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
                                    if(!tCuentaAgregar.main.formpanel_.getForm().isValid()){
                                        Ext.Msg.alert("Mensaje","<span style=\"font-family: Gill, Helvetica, sans-serif;\">Debe llenar los campos en rojo</span>");
                                        return false;
                                    }
                                    tCuentaAgregar.main.formpanel_.getForm().submit();
                               }
                           }
                       ]
                    });
                }
            };
            Ext.onReady(tCuentaAgregar.main.init, tCuentaAgregar.main);
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
                                <td style="width: 95%;">
                                    <span style="float:left">
                                        <img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" />
                                        <span>{$subseccion[0].descripcion}</span>
                                    </span>
                                </td>
                                <td style="width: 5%;">
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
        <input type="hidden" value="{$smarty.get.cod}" name="tmpcodbanco" id="tmpcodbanco"/>
        <div id="panel_"></div>
    </body>
</html>