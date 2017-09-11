<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <script type="text/javascript" src="../../libs/js/tesoreria_hacerCheque.js"></script>
        <script type="text/javascript" src="../../libs/js/tesoreria_hacerChequeTerceros.js"></script>
        <script type="text/javascript" src="../../libs/js/tesoreria_hacerChequeVarios.js"></script>
        {literal}
            <style type="text/css">
                .icon-regresar{background: url('../../../includes/imagenes/back.png') 0px 0px  no-repeat !important;}
                .icon-cheque{background: url('../../../includes/imagenes/cancelar.png') 0px 0px no-repeat !important;}
                .icon-beneficiario{background: url('../../../includes/imagenes/ico_user.gif') 0px 0px no-repeat !important;}
                .icon-buscarCxP{background-image: url('../../../includes/imagenes/search_f2.png')  !important;}
                .icon-buscarTercero{background-image: url('../../../includes/imagenes/search_f2.png')  !important;}
                .icon-deshacer{background-image: url('../../../includes/imagenes/delete_f2.png')  !important;}
                .icon-guardar{background-image: url('../../../includes/imagenes/save_f2.png')  !important;}
                .icon-vistapreviacheque{background-image: url('../../../includes/imagenes/preview_f2.png')  !important;}
                .icon-regresar{background-image: url('../../../includes/imagenes/restore_f2.png')  !important;}
            </style>
            <script type="text/javascript">//<![CDATA[
            Ext.ns("FormCheque");
            FormCheque.main = {
                init:function(){
                    this.btnRegresar = new Ext.Button({
                        name:'regresar',
                        itemId:'regresar',
                        text:'Regresar',
                        scale: 'large',
                        iconCls: 'icon-regresar',
                        iconAlign: 'top',
                        handler:function(obj){
                            url = Ext.get("url0").getValue();
                            window.location = url;
                        }
                    });
                    /*Contenedor desahabilitado. El boton Regresar cambio al contenedor 2*/
                    this.contenedor1 = new Ext.ButtonGroup({
                        column: 3,
                        title:'<b>Acciones</b>',
                        items: [
                           //this.btnRegresar
                        ]
                    });

                    this.btnCxP = new Ext.Button({
                        text:'CxP (Pendientes)',
                        scale: 'large',
                        iconCls: 'icon-buscarCxP',
                        iconAlign: 'top',
                        handler:function(){
                            storeCXPpendientes.load();
                            winCXPpendientes.show();
                        }
                    });

                    this.btnTerceros = new Ext.Button({
                        text:'Terceros',
                        scale: 'large',
                        iconCls: 'icon-buscarTercero',
                        iconAlign: 'top',
                        handler:function(){
                            storeProveedores.load();
                            winT.show();
                        }
                    });

                    this.btnCxPVarias = new Ext.Button({
                        text:'CxP-Varias (Pendientes)',
                        scale: 'large',
                        iconCls: 'icon-buscarTercero',
                        iconAlign: 'top',
                        handler:function(){
                            pChequeVarios.main.fvoid();
                        }
                    });

                    this.btnDeshacer = new Ext.Button({
                        text:'Deshacer',
                        scale: 'large',
                        iconCls: 'icon-deshacer',
                        iconAlign: 'top',
                        handler: function(){
                            FormCheque.main.mainPanelCXP.getForm().reset();
                        }
                    });

                    this.contenedor2 = new Ext.ButtonGroup({
                        column: 3,
                        title:'<b>Acciones</b>',
                        items: [
                                this.btnCxP,
                                this.btnTerceros,
                                this.btnCxPVarias,
                                //this.btnDeshacer
                               ]
                    });

                    this.btnGuardar = new Ext.Button({
                        id:'guardar',
                        text:'Guardar',
                        name:'guardar',
                        scale: 'large',
                        iconCls: 'icon-guardar',
                        iconAlign: 'top',
                        handler:function(){
                            if(Ext.get("cam_monto_pago").dom.value==0){
                                Ext.Msg.alert("Alerta","El monto a pagar no es v&aacute;lido.");
                                return false;
                            }
                            FormCheque.main.mainPanelCXP.getForm().submit({
                                waitTitle: "Validando acceso",
                                method:'post',
                                url: Ext.get("query_string").getValue(),
                                waitMsg : "Espere un momento por favor...",
                                failure: function(sender,action){
                                    Ext.MessageBox.alert('Error en transacci&oacute;n', action.result.msg+"\nTransacci&oacute;n:"+action.result.sql);
                                },
                                success: function(sender,action) {
                                    if(action.result.success){
                                         Ext.MessageBox.show({
                                             title: 'Mensaje',
                                             msg: action.result.msg,
                                             closable: false,
                                             icon: Ext.MessageBox.INFO,
                                             resizable: false
                                             //buttons: Ext.MessageBox.OK
                                         });
                                         setTimeout(function(){
                                              location.href=Ext.get("url0").getValue();//'?opt_menu=89';
                                         },30);
                                    }
                                }
                            });
                        }
                    });

                    this.contenedor3 = new Ext.ButtonGroup({
                        column: 3,
                        title:'<b>Acciones</b>',
                        items: [
                            /* En última actualizacion se agregaron los botones
                               Regresar y Deshacer a este contenedor*/
                            this.btnRegresar,
                            this.btnDeshacer,
                            this.btnGuardar
                        ]
                    });
                    /*
                     * <FormPanel PRINCIPAL>.
                     * Este panel es el que se encarga de montar el panel principal de cuuentas por pagar
                     */
                    this.mainPanelCXP = new Ext.FormPanel({
                            title: 'Emitir Cheque - Emisi&oacute;n de cheques | ' + Ext.get("stringCuentaBanco").getValue(),
                            iconCls: 'icon-cheque',
                            bodyStyle:'padding: 10px',
                            autoScroll:true,
                            autoWidth:true,
                            tbar :[
                                //this.contenedor1,
                                this.contenedor2,
                                this.contenedor3
                            ],
                            items:[
                            {
                                xtype:'fieldset',
                                title: 'Informaci&oacute;n del Beneficiario',
                                collapsible: true,
                                style: 'magin-rigth:20px;',
                                width: 500,
                                autoHeight:true,
                                defaults: {width: 210},
                                items :[
                                    {
                                        xtype:'hidden',
                                        name:'opt',
                                        id:'opt',
                                        value:'guardarCheque'
                                    },
                                     {
                                        id:'cam_beneficiario',
                                        xtype:'textfield',
                                        fieldLabel:'<b>Beneficiario</b>',
                                        readOnly:true,
                                        name:'cam_beneficiario'
                                    },{
                                        id:'cam_rif',
                                        xtype:'textfield',
                                        fieldLabel:'<b>R.I.F</b>',
                                        readOnly:true,
                                        name:'cam_rif'
                                    }
                                       ]
                             },
                             {
                                xtype:'fieldset',
                                title: 'Informaci&oacute;n del Pago',
                                collapsible: true,
                                width: 500,
                                autoHeight:true,
                                defaults: {width: 210},
                                items :[
                                        {
                                            xtype:'hidden',
                                            id:'codigo_banco',
                                            name:'codigo_banco',
                                            value:Ext.get("cod_banco").getValue()
                                        },
                                        {
                                            xtype:'hidden',
                                            id:'codigo_cheque',
                                            name:'codigo_cheque',
                                            value:Ext.get("cod_cheque").getValue()
                                        },
                                        {
                                            id:'chequera',
                                            xtype:'textfield',
                                            fieldLabel:'Chequera',
                                            readOnly:true,
                                            value: Ext.get("num_chequera").getValue(),
                                            name:'chequera'
                                        },
                                        {
                                            id:'cheque',
                                            xtype:'textfield',
                                            labelWidth: 75, // label settings here cascade unless overridden
                                            fieldLabel:'Cheque',
                                            value: Ext.get("num_cheque").getValue(),
                                            //readOnly:true,
                                            name:'cheque'
                                        },
                                        {
                                            id:'cam_cod_edocuenta',
                                            xtype:'textfield',
                                            labelWidth: 75, // label settings here cascade unless overridden
                                            fieldLabel:'Nro. CxP',
                                            value: '0',
                                            readOnly:true,
                                            name:'cam_cod_edocuenta'
                                        },
                                        {
                                            id:'cam_id_proveedor',
                                            value:'0',
                                            xtype:'hidden',
                                            name:'cam_id_proveedor'
                                        },
                                        {
                                            id:'cam_numero_compra',
                                            xtype:'textfield',
                                            labelWidth: 75, // label settings here cascade unless overridden
                                            fieldLabel:'Numero',
                                            value: '0',
                                            readOnly:true,
                                            name:'cam_numero_compra'
                                        },
                                        {
                                            id:'cam_fechapago',
                                            xtype:'textfield',
                                            labelWidth: 75, // label settings here cascade unless overridden
                                            fieldLabel:'Fecha del Pago',
                                            name:'cam_fechapago',
                                            value: new Date().format("d/m/Y"),
                                            readOnly:true,
                                            allowBlank:false
                                        },
                                        {
                                            id:'cam_monto_pago',
                                            xtype:'numberfield',
                                            labelWidth: 75, // label settings here cascade unless overridden
                                            fieldLabel:'Monto a Pagar',
                                            name:'cam_monto_pago',
                                            allowBlank:false,
                                            readOnly:true,
                                            value: '0.00'
                                        },
                                        {
                                            id:'cam_monto_leltra',
                                            xtype:'textarea',
                                            width:'100%',
                                            labelWidth: 75, // label settings here cascade unless overridden
                                            fieldLabel:'Monto en letras',
                                            name:'cam_monto_leltra',
                                            readOnly:true
                                        },
                                         {
                                            xtype:'textarea',
                                            id:'cam_concepto_cheque',
                                            name:'cam_concepto_cheque',
                                            width:"100%",
                                            readOnly:true,
                                            labelWidth: 75, // label settings here cascade unless overridden
                                            fieldLabel:'Concepto'
                                        }
                                       ]
                             }
                            ]//fin del items formpanel principal
                    });
                    /**
                     * Renderizamos nuestro objeto <mainPanel> en el Divisor <panelCheque>.
                     */
                    this.mainPanelCXP.render("panelCheque");
                }
            };
            Ext.onReady(FormCheque.main.init, FormCheque.main);
            //Ext.onReady(pChequeVarios.main.init, pChequeVarios.main);
            //]]>
            </script>
        {/literal}
    </head>
    <body>
        <div id="panelCheque"></div>
        <input type="hidden" id="query_string" name="query_string" value="?{$query_string}"/>
        <input type="hidden" id="url0" name="url0" value="?opt_menu={$smarty.get.opt_menu}&amp;opt_seccion={$smarty.get.opt_seccion}&amp;opt_subseccion=SeleccionlistaChequeraCuentaByBanco&amp;cod={$smarty.get.cod}&amp;cod_cuenta={$smarty.get.cod_cuenta}" />
        <input type="hidden" id="stringCuentaBanco" name="stringCuentaBanco" value="{$datos_banco[0].descripcion_banco}, Cuenta {$datos_banco[0].nro_cuenta}"/>
        <input type="hidden" id="num_cheque" name="num_cheque" value="{$datos_chequera[0].num_proximo_cheque}"/>
        <input type="hidden" id="cod_cheque" name="cod_cheque" value="{$datos_chequera[0].cod_cheque}"/>
        <input type="hidden" id="cod_banco" name="cod_banco" value="{$datos_banco[0].cod_banco}"/>
        <input type="hidden" id="num_chequera" name="num_chequera" value="{$datos_chequera[0].cod_chequera}"/>
        <div id="formparametros"></div>
    </body>
</html>
