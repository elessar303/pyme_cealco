<script type="text/javascript" src="../../libs/js/pendientes.js"></script>
<script type="text/javascript" src="../../libs/js/tesoreria_hacerTransfTerceros.js"></script>
<script type="text/javascript" src="../../libs/js/tesoreria_hacerTransfVarios.js"></script>
{literal}
<style text="text/css">
.icon-regresar{background: url('../../libs/imagenes/back.png') 0px 0px  no-repeat !important;}
.icon-cheque{background: url('../../libs/imagenes/cancelar.png') 0px 0px no-repeat !important;}
.icon-beneficiario{background: url('../../libs/imagenes/ico_user.gif') 0px 0px no-repeat !important;}
.icon-buscarCxP{background-image: url('../../libs/imagenes/search_f2.png')  !important;}
.icon-buscarTercero{background-image: url('../../libs/imagenes/search_f2.png')  !important;}
.icon-deshacer{background-image: url('../../libs/imagenes/delete_f2.png')  !important;}
.icon-guardar{background-image: url('../../libs/imagenes/save_f2.png')  !important;}
.icon-vistapreviacheque{background-image: url('../../libs/imagenes/preview_f2.png')  !important;}
.icon-regresar{background-image: url('../../libs/imagenes/restore_f2.png')  !important;}
</style>
<script type="text/javascript">
Ext.ns("FormTransf"); /*Alias para Ext.namespace(); Crea un espacio de nombres*/
FormTransf.main = {
    init:function(){
        this.btnRegresar = new Ext.Button({
            name:'regresar',
            itemId:'regresar',
            text:'Regresar',
            scale: 'large',
            iconCls: 'icon-regresar',
            iconAlign: 'top',
            handler:function(obj){
                url = Ext.get("url").getValue();
                window.location = url;
            }
        });
        this.contenedor1 = new Ext.ButtonGroup({
            column: 3,
            items: [
               this.btnRegresar
            ]
        });
        this.btnCxP = new Ext.Button({
            text:'Pedidos)',
            scale: 'large',
            iconCls: 'icon-buscarCxP',
            iconAlign: 'top',
            handler:function(){
                storeCXPpendientes.load();
                winCXPpendientes.show();
            }
        });        
        /*this.btnTerceros = new Ext.Button({
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
        });*/
        this.btnDeshacer = new Ext.Button({
            text:'Deshacer',
            scale: 'large',
            iconCls: 'icon-deshacer',
            iconAlign: 'top',
            handler: function(){
                FormTransf.main.mainPanelCXP.getForm().reset();
            }
        });
        this.contenedor2 = new Ext.ButtonGroup({
            column: 3,
            items: [
                    this.btnCxP,
                    /*this.btnTerceros,
                    this.btnCxPVarias,*/
                    this.btnDeshacer
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
                    Ext.Msg.alert("Alerta","El monto a pagar debe ser mayor a 0.");
                    return false;
                }
                FormTransf.main.mainPanelCXP.getForm().submit({
                    waitTitle: "Procesando!!!",
                    method:'post',
                    url: Ext.get("query_string").getValue(),
                    waitMsg : "Espere un momento por favor......",
                    failure: function(sender,action){
                        Ext.MessageBox.alert('Error en transacción', action.result.msg+"\nTransacción:"+action.result.sql);
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
                                  location.href='?opt_menu=89';
                             },30);
                        }
                    }
                });
            }
        });
        this.contenedor3 = new Ext.ButtonGroup({
            column: 3,
            items: [
                this.btnGuardar
            ]
        });
        /*
         * <FormPanel PRINCIPAL>.
         * Este panel es el que se encarga de montar el panel principal de cuuentas por pagar
         */
        this.mainPanelCXP = new Ext.FormPanel({
                //title: 'Emitir Transacción | ' + Ext.get("stringCuentaBanco").getValue(),
                title: 'Facturacion',
                iconCls: 'icon-cheque',
                bodyStyle:'padding: 10px',
                autoScroll:true,
                autoWidth:true,
                tbar :[
                    this.contenedor1,
                    this.contenedor2,
                    this.contenedor3
                ],
                items:[
                {
                    xtype:'fieldset',
                    title: 'Información del Cliente',
                    collapsible: true,
                    style: 'magin-rigth:20px;',
                    width: 500,
                    autoHeight:true,
                    defaults: {width: 210},
                    items :[
                        /*{
                            xtype:'hidden',
                            name:'opt',
                            id:'opt',
                            value:'guardarTransferencia'
                        },*/
                         {
                            id:'cam_beneficiario',
                            xtype:'textfield',
                            fieldLabel:'<b>Cliente</b>',
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
                    title: 'Información de la Transacción',
                    collapsible: false,
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
				xtype: 'combo',
				fieldLabel:'Tipo transacción',
				id:'tipo_transac',
				name:'tipo_transac',
				allowBlank: false,
				editable: false,
				triggerAction: 'all',
// 				typeAhead: false,
				mode: 'local',
// 				width:120,
// 				listWidth: 120,
// 				hiddenName: 'tipo_transac',
				store: new Ext.data.SimpleStore({
					fields: ['ids','nombre'],
					data: [['1', 'Transferencia Proveedores'], ['2', 'Cheque de Gerencia']]
					}),
				displayField: 'nombre',
        			valueField: 'ids',
				value:'1'
// 				readOnly: true
//                                 value:Ext.get("cod_cheque").getValue()
                            },
                            {
                                id:'cam_transferencia_numero',
                                xtype:'textfield',
                                fieldLabel:'Transacción Numero',
                                readOnly:true,
                                value: '0',
                                name:'cam_transferencia_numero'
                            },
                            {
                                id:'cam_referencia',
                                xtype:'textfield',
                                labelWidth: 75, // label settings here cascade unless overridden
                                fieldLabel:'Referencia',
//                                 value: Ext.get("num_cheque").getValue(),
                                readOnly:true,
                                name:'cam_referencia'
                            },
                            {
                                id:'cam_cod_edocuenta',
                                xtype:'textfield',
                                labelWidth: 75, // label settings here cascade unless overridden
                                fieldLabel:'Nro. CxP:',
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
         * Renderizamos nuestro objeto <mainPanel> en el Divisor <panelTransf>.
         */
        this.mainPanelCXP.render("panelTransf");
    }
};
Ext.onReady(FormTransf.main.init, FormTransf.main);
//Ext.onReady(pChequeVarios.main.init, pChequeVarios.main);
</script>
{/literal}
<div id="panelTransf"></div>
<input type="hidden" id="query_string" name="query_string" value="?{$query_string}">
<input type="hidden" id="url" name="url" value="?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}" >
<input type="hidden" id="id_cliente" name="id_cliente" value="{$datacliente[0].id_cliente}">
<input type="hidden" id="nombre" name="nombre" value="{$datacliente[0].nombre}">
<input type="hidden" id="cod_pedido" name="cod_pedido" value="{$datacliente[0].cod_pedido}">
<input type="hidden" id="stringCuentaBanco" name="stringCuentaBanco" value="{$datos_banco[0].descripcion_banco}, Cuenta {$datos_banco[0].nro_cuenta}">
<!--<input type="hidden" id="num_cheque" name="num_cheque" value="0">
<input type="hidden" id="cod_cheque" name="cod_cheque" value="0">-->
<input type="hidden" id="cod_banco" name="cod_banco" value="{$datos_banco[0].cod_banco}">
<!--<input type="hidden" id="num_chequera" name="num_chequera" value="0">-->
<div id="formparametros">
</div>