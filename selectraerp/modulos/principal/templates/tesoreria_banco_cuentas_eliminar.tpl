{literal}
<script language="JavaScript">
Ext.ns("tCuentaEliminar");
tCuentaEliminar.main= {
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
            fieldLabel:'Descripción de la cuenta',
            name:'descripcion',
            disabled:true,
            value:Ext.get("tmpdescripcion").dom.value,
            allowBlank:false
        });

        this.nu_cuenta = new Ext.form.NumberField({
            fieldLabel:'Numero de la cuenta',
            name:'nrocuenta',
            disabled:true,
            value:Ext.get("tmpnrocuenta").dom.value,
            allowBlank:false
        });



        this.cmbTipoCuenta = new Ext.form.ComboBox({
            fieldLabel:'Tipo de Cuenta',
            resizable:true,
            store: this.storeTipoCuenta,
            typeAhead: true,
            disabled:true,
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
            tCuentaEliminar.main.cmbTipoCuenta.setValue(Ext.get("tmpcod_tipo_cuenta_banco").dom.value)
        });


        this.fe_apertura = new Ext.form.DateField({
             fieldLabel:'Fecha de apertura',
             name:'fechaapertura',
             allowBlank:false,
             disabled:true,
             value:Ext.get("tmpfecha_apertura").dom.value
        });

        this.nu_apertura = new Ext.form.NumberField({
            fieldLabel:'Monto de apertura',
            name:'montoapertura',
            disabled:true,
            value:Ext.get("tmpmontoapertura").dom.value
        });

        this.nu_disponible = new Ext.form.NumberField({
            fieldLabel:'Monto Disponible',
            name:'montodisponible',
            disabled:true,
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
            disabled:true,
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
//       this.storeCuContable.on("load",function(){
//            tCuentaEliminar.main.cmbCuContable.setValue(Ext.get("tmpcuentacontable").dom.value);
//       });
        tCuentaEliminar.main.cmbCuContable.setValue(Ext.get("tmpcuentacontable").dom.value);

        this.co_cuenta = new Ext.form.Hidden({
            name:'codCuenta',
            value: Ext.get("tmpcodCuenta").dom.value
        });


        this.co_banco = new Ext.form.Hidden({
            name:'codBanco',
            value:Ext.get("tmpcod_banco").dom.value
        });

         this.beliminar = new Ext.Button({
                   text:'Eliminar',
                   handler:function(){
                        if(!tCuentaEliminar.main.formpanel_.getForm().isValid()){
                            Ext.Msg.alert("Mensaje","Debe llenar los campos en rojo");
                            return false;
                        }
                        tCuentaEliminar.main.formpanel_.getForm().submit();
                   }
        });

        this.formpanel_ = new Ext.FormPanel({
           renderTo:"panel_",
           standardSubmit: true,
           url:'',
           frame:true,
           title:'Información de la cuenta',
           items:[
               {
                    xtype:'fieldset',
                    items:[this.tx_descripcion,this.nu_cuenta]
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
               }
           ],
           buttons:[
               this.beliminar
           ]
        });
        
        this.resultado = Ext.get("peliminar").dom.value;
        if(this.resultado!="si"){
            this.beliminar.setDisabled(true);
            Ext.Msg.alert("Alerta!",Ext.get("mensaje").dom.value)
        }
    }
};
Ext.onReady(tCuentaEliminar.main.init, tCuentaEliminar.main);
</script>
{/literal}
<!--<form name="formulario" id="formulario" method="POST" action="">-->




  <table width="100%">
        <tr class="row-br">
            <td>
                <table class="tb-tit" cellspacing="0" cellpadding="1" border="0" width="100%">
                    <tbody>
                          <tr>
                        <td width="900"><span style="float:left"><img src="{$subseccion[0].img_ruta}" width="22" height="22" class="icon" />{$subseccion[0].descripcion}</span></td>
                        <td width="75">
                            <table style="cursor: pointer;" class="btn_bg" onClick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$smarty.get.opt_seccion}&opt_subseccion=viewcuentasByBanco&cod={$smarty.get.cod}'" name="buscar" border="0" cellpadding="0" cellspacing="0">
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

<input type="hidden" value="{$campos_cuentas[0].descripcion}"       name="tmpdescripcion" id="tmpdescripcion">
<input type="hidden" value="{$campos_cuentas[0].cod_tesor_bandodet}"       name="tmpcodCuenta" id="tmpcodCuenta">


<input type="hidden" value="{$eliminar}" name="peliminar" id="peliminar">
<input type="hidden" value="{$mensaje}" name="mensaje" id="mensaje">

<input type="hidden" value="{$campos_cuentas[0].cod_tipo_cuenta_banco}"    id="tmpcod_tipo_cuenta_banco">
<input type="hidden" value="{$campos_cuentas[0].cuenta_contable}" name="tmpcuentacontable"   id="tmpcuentacontable">
<input type="hidden" value="{$campos_cuentas[0].nro_cuenta}"    id="tmpnrocuenta">
<input type="hidden" value="{$campos_cuentas[0].cod_banco}"    id="tmpcod_banco">
<input type="hidden" readonly value="{$campos_cuentas[0].monto_apertura}"      name="tmpmontoapertura"  id="tmpmontoapertura" >
<input type="hidden" readonly value="{$campos_cuentas[0].monto_disponible}"   name="tmpmontodisponible"  id="tmpmontodisponible">
<input type="hidden" class="" readonly name="tmpfecha_apertura" id="tmpfecha_apertura" value="{$campos_cuentas[0].fecha_apertura}" style="border: 1px solid black; margin-bottom: 5px;" readonly="" size="10">


<div id="panel_"></div>
