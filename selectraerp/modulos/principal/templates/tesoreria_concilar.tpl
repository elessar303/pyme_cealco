{literal}
<script language="JavaScript">
Ext.ns("pConciliar");
pConciliar.main = {
    arrayMovSelect: [],
    sumCH:0,
    sumND:0,
    sumNC:0,
    sumDe:0,
    cantCH:0,
    cantND:0,
    cantNC:0,
    cantDe:0,
    formatearMonto:function(valor){
        return Ext.util.Format.number(valor,'0,000.00');
    },
    init: function(){
        this.store_movimientos = this.getStoreMovimientos();


        this.cod_movimiento_banArray= new Ext.form.Hidden({
           name:'cod_movimiento_banArray',
           value:''
        });

        this.mon_tran_cheque = new Ext.form.TextField({
           name:'mon_tran_cheque_',
           flex:2,
           readOnly:true,
           value:Ext.get("monto_cheque_tran").dom.value
        });

        this.cant_tran_cheque = new Ext.form.TextField({
           name:'cant_tran_cheque_',
           flex:1,
           width:50,
           readOnly:true,
           value:Ext.get("cant_cheque_tran").dom.value
        });

        this.compofield1 = new Ext.form.CompositeField({
            fieldLabel:'Cheques',
            items:[
                this.mon_tran_cheque,
                this.cant_tran_cheque
            ]
        });
        
        this.mon_tran_depo = new Ext.form.TextField({
           name:'mon_tran_depo_',
           flex:2,
           readOnly:true,
           value:Ext.get("monto_deposito_tran").dom.value
        });

        this.cant_tran_depo = new Ext.form.TextField({
           name:'cant_tran_depo_',
           flex:1,
           width:50,
           readOnly:true,
           value:Ext.get("cant_deposito_tran").dom.value
        });

        this.compofield2 = new Ext.form.CompositeField({
            fieldLabel:'Depositos',
            items:[
                this.mon_tran_depo,
                this.cant_tran_depo
            ]
        });

        //nota de credito
        this.mon_tran_nc = new Ext.form.TextField({
           flex:2,
           readOnly:true,
           name:'mon_tran_nc_',
           value:Ext.get("monto_nc_tran").dom.value
        });

        this.cant_tran_nc = new Ext.form.TextField({
           name:'cant_tran_nc_',
           flex:1,
           width:50,
           readOnly:true,
           value:Ext.get("cant_nc_tran").dom.value
        });

        this.compofield3 = new Ext.form.CompositeField({
            fieldLabel:'N. de Credito',
            items:[
                this.mon_tran_nc,
                this.cant_tran_nc
            ]
        });

        //nota de debito
        this.mon_tran_nd = new Ext.form.TextField({
           flex:2,
           name:'mon_tran_nd_',
           readOnly:true,
           value:Ext.get("monto_nd_tran").dom.value
        });

        this.cant_tran_nd = new Ext.form.TextField({
           name:'cant_tran_nd_',
           flex:1,
           width:50,
           readOnly:true,
           value: Ext.get("cant_nd_tran").dom.value
        });

        this.compofield4  = new Ext.form.CompositeField({
            fieldLabel:'N. de Debito',
            items:[
                this.mon_tran_nd,
                this.cant_tran_nd
            ]
        });



        this.fielsetMovTran = new Ext.form.FieldSet({
            title:'En transito',
            width:300,
            autoHeight:true,
            items:[

                this.compofield1,
                this.compofield2,
                this.compofield3,
                this.compofield4
            ]
        });



        this.mon_xcon_cheque = new Ext.form.TextField({
           name:'mon_xcon_cheque',
           readOnly:true,
           value:0,
           flex:2

        });
        this.cant_xcon_cheque = new Ext.form.TextField({
           name:'cant_xcon_cheque',
           readOnly:true,
           value:0,
           flex:1,
           width:50
        });

        this.compofield5  = new Ext.form.CompositeField({
           fieldLabel:'Cheques',
           items:[
                this.mon_xcon_cheque,
                this.cant_xcon_cheque
            ]
        });


        this.mon_xcon_depo = new Ext.form.TextField({
           name:'mon_xcon_depo',
           flex:2,
           readOnly:true,
           value:0
        });
        this.cant_xcon_depo = new Ext.form.TextField({
           name:'cant_xcon_depo',
           flex:1,
           width:50,
           readOnly:true,
           value:0
        });

        this.compofield6  = new Ext.form.CompositeField({
           fieldLabel:'Depositos',
           items:[
                this.mon_xcon_depo,
                this.cant_xcon_depo
            ]
        });



        this.mon_xcon_nc = new Ext.form.TextField({
           name:'mon_xcon_nc',
           flex:2,
           readOnly:true,
           value:0
        });
        this.cant_xcon_nc = new Ext.form.TextField({
           name:'cant_xcon_nc',
           flex:1,
           width:50,
           readOnly:true,
           value:0
        });

        this.compofield7  = new Ext.form.CompositeField({
           fieldLabel:'N. de Credito',
           items:[
                this.mon_xcon_nc,
                this.cant_xcon_nc
            ]
        });

        this.mon_xcon_nd = new Ext.form.TextField({
           name:'mon_xcon_nd',
           flex:2,
           readOnly:true,
           value:0
        });
        this.cant_xcon_nd = new Ext.form.TextField({
           name:'cant_xcon_nd',
           flex:1,
           width:50,
           readOnly:true,
           value:0
        });

        this.compofield8  = new Ext.form.CompositeField({
           fieldLabel:'N. de Debito',
           items:[
                this.mon_xcon_nd,
                this.cant_xcon_nd
            ]
        });

        this.monto_total = new Ext.form.NumberField({
           fieldLabel:'Saldo conciliado',
           name:'monto_total',
           value:.0
        });

        this.monto_libro = new Ext.form.NumberField({
           fieldLabel:'Saldo',
           name:'saldo_libros',
           value:.0,
           allowBlank:false
        });

        this.fielsetMovSalLibro = new Ext.form.FieldSet({
            title:'Saldo en libro',
            width:300,
            autoHeight:true,
            items:[
                this.monto_libro
            ]
        });


        this.fielsetMovXConc = new Ext.form.FieldSet({
            title:'Por conciliar',
            width:300,
            autoHeight:true,
            items:[
                this.compofield5,
                this.compofield6,
                this.compofield7,
                this.compofield8,
                this.monto_total
            ]
        });

        this.seleccionmodal = new Ext.grid.CheckboxSelectionModel({
            singleSelect: false,
            listeners: {
                'rowselect': function(selectModal,ri,rd){
                    pConciliar.main.generarCalculosDeConciliacion();
                },
                rowdeselect: function(selectModal,ri,rd){
                    pConciliar.main.generarCalculosDeConciliacion();
                }
            }
        });
        this.columnas = new Ext.grid.ColumnModel([
               new Ext.grid.RowNumberer(),
               this.seleccionmodal,
               {
                   header:'ID',
                   menuDisabled:true,
                   sortable:true,
                   width:50,
                   dataIndex:'cod_movimiento_ban'
               },
               {
                   header:'Cod. tesor banco',
                   menuDisabled:true,
                   sortable:true,
                   hidden:true,
                   dataIndex:'cod_tesor_bancodet'
               },
               {
                   header:'Tipo Movimiento',
                   menuDisabled:true,
                   sortable:true,
                   dataIndex:'tipo_movimiento_desc'
               },
               {
                   header:'Numero',
                   menuDisabled:true,
                   sortable:true,
                   dataIndex:'numero_movimiento'
               },
               {
                   header:'Fecha',
                   menuDisabled:true,
                   sortable:true,
                   renderer: Ext.util.Format.dateRenderer("d/m/Y"),
                   dataIndex:'fecha_movimiento'
               },
               {
                   header:'Concepto',
                   menuDisabled:true,
                   sortable:true,
                   width:390,
                   dataIndex:'concepto'
               },
               {
                   header:'Debe',
                   menuDisabled:true,
                   sortable:true,
                   renderer: pConciliar.main.formatearMonto,
                   dataIndex:'debe'
               },
               {
                   header:'Haber',
                   menuDisabled:true,
                   sortable:true,
                   renderer: pConciliar.main.formatearMonto,
                   dataIndex:'haber'
               },
               {
                   header:'tipo movimiento',
                   menuDisabled:true,
                   sortable:true,
                   hidden:true,
                   dataIndex:'tipo_movimiento'
               }
        ]);

        this.grid_ = new Ext.grid.GridPanel({
            title:'Movimientos',
            store:this.store_movimientos,
            cm:this.columnas,
            align:'center',
            sm:this.seleccionmodal,
            height:230,

            autoScroll:true,
            frame:true,
            stripeRows: true,
            loadMask:true,
            autoScroll:true,
            stateful: true
        });

        this.formPanel_ = new Ext.FormPanel({
            style:'padding-top:5px;',
            width:'100%',
            id:'formulario_',
            autoHeight:true,
            frame:true,
//            standardSubmit:true,
            items:[
                this.cod_movimiento_banArray,
                {
                    contentEl:'hiddens'
                },
                {
                    collapsed:false,
                    collapsible:true,
                    titleCollapse:true,

                    title:'Detalle de información - Conciliacion Periodo <b>'+Ext.get("fecha1_").dom.value+" - "+Ext.get("fecha2_").dom.value+"</b>",
                    layout:'column',
                    defaults:{style:'padding:1px;'},
                    items:[
                        {
                            
                            items:[
                                this.fielsetMovXConc
                            ]
                        },
                        {
                            
                            items:[
                                this.fielsetMovTran
                            ]
                        },
                        {

                            items:[
                                this.fielsetMovSalLibro
                            ]
                        }
                    ]
                },
                this.grid_
            ],
            buttonAlign:'center',
            buttons: [
                {
                    text:'Grabar',
                    name:'grabar',
                    handler:function(){
                        pConciliar.main.cod_movimiento_banArray.setValue(pConciliar.main.arrayMovSelect);
                        pConciliar.main.formPanel_.getForm().submit({
                            method:'POST',
                            waitMsg: 'Enviando datos, por favor espere..',
                            waitTitle:'Enviando',
                            url:Ext.get("string_url").dom.value,
                            failure: function(form, action) {
                                Ext.MessageBox.alert('Error en transacción', action.result.msg);
                            },
                            success:function(form,action){
                                if(action.result.success){
                                    window.location.href='?opt_menu=89&opt_seccion=94';
                                    Ext.Msg.alert("Alerta!",action.result.msg);

                                }else{
                                    Ext.Msg.alert("Alerta!",action.result.msg);
                                }

                            }
                        });
                    }
                }
            ]
        });


        if(Ext.fly("verificarCuentaBanco").dom.value>0){
            this.panelHTML = new Ext.Panel({
                title:'Notificación',
                html:'<span style="color:red;font-size:15px;">Mes Conciliado.</span>',
                frame:true
            });

            this.panelHTML.render("panel_conciliacion");
        }else{
            this.formPanel_.render("panel_conciliacion");
        }

        
        this.store_movimientos.load({
            params:{
                opt:        'movimientos_bancarios_conciliar',
                fecha1_:    Ext.get("fecha1_").dom.value,
                fecha2_:    Ext.get("fecha2_").dom.value,
                cod_cuenta: Ext.get("cod_cuenta").dom.value
            }
        });
    },
    getStoreMovimientos: function(){
    this.store = new Ext.data.JsonStore({
        url:'../../libs/php/ajax/ajax.php',
        root:'data',
        fields:[
                {name: 'cod_movimiento_ban', type:'float'},
                {name: 'cod_tesor_bancodet'},
                {name: 'tipo_movimiento_desc'},
                {name: 'numero_movimiento',type:'float'},
                {name: 'fecha_movimiento'},
                {name: 'concepto'},
                {name: 'debe',type:'float'},
                {name: 'haber',type:'float'},
                {name: 'tipo_movimiento'},
                {name: 'conciliar'}
               ]
    });
    return this.store;
    },
    generarCalculosDeConciliacion:function(){
                pConciliar.main.arrayMovSelect=[];
                pConciliar.main.sumCH=0;
                pConciliar.main.sumND=0;
                pConciliar.main.sumNC=0;
                pConciliar.main.sumDe=0;

                pConciliar.main.cantCH=0;
                pConciliar.main.cantND=0;
                pConciliar.main.cantNC=0;
                pConciliar.main.cantDe=0;

                pConciliar.main.seleccionmodal.each(function(rec){
                    this.tipo_movimiento = rec.get("tipo_movimiento");
                    if(this.tipo_movimiento==1||this.tipo_movimiento==2){// CHEQUE ó DEPOSITO
                        this.monto  =   rec.get("haber");
                        if(this.tipo_movimiento==1){
                           pConciliar.main.cantCH++;
                           pConciliar.main.sumCH += parseFloat(this.monto);
                       }else{
                           pConciliar.main.cantDe++;
                           pConciliar.main.sumDe += parseFloat(this.monto);
                       }
                    }else{
                        if(this.tipo_movimiento==3||this.tipo_movimiento==4){// ND ó NC
                            this.monto  =   rec.get("debe");
                            if(this.tipo_movimiento==3){
                               pConciliar.main.cantNC++;
                               pConciliar.main.sumNC += parseFloat(this.monto);
                           }else{
                               pConciliar.main.cantND++;
                               pConciliar.main.sumND += parseFloat(this.monto);
                           }
                        }
                    }
                    pConciliar.main.arrayMovSelect.push(rec.get("cod_movimiento_ban"));
                });

                pConciliar.main.mon_xcon_cheque.setValue((parseFloat(pConciliar.main.sumCH)).toFixed(2));
                pConciliar.main.cant_xcon_cheque.setValue((parseInt(pConciliar.main.cantCH)).toFixed(0));
                pConciliar.main.mon_tran_cheque.setValue((parseFloat(Ext.get("monto_cheque_tran").dom.value-pConciliar.main.sumCH)).toFixed(2));
                pConciliar.main.cant_tran_cheque.setValue((parseInt(Ext.get("cant_cheque_tran").dom.value-pConciliar.main.cantCH)).toFixed(0));

                pConciliar.main.mon_xcon_depo.setValue((parseFloat(pConciliar.main.sumDe)).toFixed(2));
                pConciliar.main.cant_xcon_depo.setValue((parseInt(pConciliar.main.cantDe)).toFixed(0));
                pConciliar.main.mon_tran_depo.setValue((parseFloat(Ext.get("monto_deposito_tran").dom.value-pConciliar.main.sumDe)).toFixed(2));
                pConciliar.main.cant_tran_depo.setValue((parseInt(Ext.get("cant_deposito_tran").dom.value-pConciliar.main.cantDe)).toFixed(0));

                pConciliar.main.mon_xcon_nc.setValue((parseFloat(pConciliar.main.sumNC)).toFixed(2));
                pConciliar.main.cant_xcon_nc.setValue((parseInt(pConciliar.main.cantNC)).toFixed(0));
                pConciliar.main.mon_tran_nc.setValue((parseFloat(Ext.get("monto_nc_tran").dom.value-pConciliar.main.sumNC)).toFixed(2));
                pConciliar.main.cant_tran_nc.setValue((parseInt(Ext.get("cant_nc_tran").dom.value-pConciliar.main.cantNC)).toFixed(0));


                pConciliar.main.mon_xcon_nd.setValue((parseFloat(pConciliar.main.sumND)).toFixed(2));
                pConciliar.main.cant_xcon_nd.setValue((parseInt(pConciliar.main.cantND)).toFixed(0));
                pConciliar.main.mon_tran_nd.setValue((parseFloat(Ext.get("monto_nd_tran").dom.value-pConciliar.main.sumND)).toFixed(2));
                pConciliar.main.cant_tran_nd.setValue((parseInt(Ext.get("cant_nd_tran").dom.value-pConciliar.main.cantND)).toFixed(0));
                pConciliar.main.monto_total.setValue((parseFloat((pConciliar.main.sumDe+pConciliar.main.sumNC)-(pConciliar.main.sumCH+pConciliar.main.sumND))).toFixed(2));


    }

};
Ext.onReady(pConciliar.main.init,pConciliar.main);
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

<div id="hiddens">
<input type="hidden" value="{$smarty.post.fecha1_}" name="fecha1_" id="fecha1_">
<input type="hidden" value="{$smarty.post.fecha2_}" name="fecha2_" id="fecha2_">

<input type="hidden" value="{$smarty.get.cod_cuenta}" name="cod_cuenta" id="cod_cuenta">

<input type="hidden" value="{$data_transito[0].monto}" name="monto_cheque_tran" id="monto_cheque_tran">
<input type="hidden" value="{$data_transito[0].cantidad}" name="cant_cheque_tran" id="cant_cheque_tran">

<input type="hidden" value="{$data_transito[1].monto}" name="monto_deposito_tran" id="monto_deposito_tran">
<input type="hidden" value="{$data_transito[1].cantidad}" name="cant_deposito_tran" id="cant_deposito_tran">

<input type="hidden" value="{$data_transito[2].monto}" name="monto_nc_tran" id="monto_nc_tran">
<input type="hidden" value="{$data_transito[2].cantidad}" name="cant_nc_tran" id="cant_nc_tran">

<input type="hidden" value="{$data_transito[3].monto}" name="monto_nd_tran" id="monto_nd_tran">
<input type="hidden" value="{$data_transito[3].cantidad}" name="cant_nd_tran" id="cant_nd_tran">
</div>

<div id="panel_conciliacion"></div>


<input type="hidden" value="{$verificarCuentaBanco}" name="verificarCuentaBanco" id="verificarCuentaBanco">

