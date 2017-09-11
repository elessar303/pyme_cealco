var eventos_form = {
    vcampos: '',
    formatearNumero: function(objeto) {
        var num = objeto.numero;
        var n = num.toString();
        var nums = n.split('.');
        var newNum = "";
        if (nums.length > 1)
        {
            var dec = nums[1].substring(0, 2);
            newNum = nums[0] + "," + dec;
        }
        else
        {
            newNum = num;
        }
        return newNum;
    },
    cargarProducto: function() {
        $.ajax({
            type: 'GET',
            data: 'opt=Selectitem&v1=1',
            url: '../../libs/php/ajax/ajax.php',
            beforeSend: function() {
                $("#items").find("option").remove();
                $("#items").append("<option value=''>Cargando..</option>");
            },
            success: function(data) {
                $("#items").find("option").remove();
                
                this.vcampos = eval(data);
					$("#items").append("<option value=''>Seleccione..</option>");
                for (i = 0; i <= this.vcampos.length; i++) {
                    $("#items").append("<option value='" + this.vcampos[i].id_item + "'>" + this.vcampos[i].descripcion1 + " " + this.vcampos[i].id_item + " " + this.vcampos[i].cod_item + "</option>");
                }
            }
        });
    },
    cargarAlmacenes: function() {
        $.ajax({
            type: 'GET',
            data: 'opt=getAlmacen',
            url: '../../libs/php/ajax/ajax.php',
            beforeSend: function() {
                $("#almacen").find("option").remove();
                $("#almacen").append("<option value=''>Cargando..</option>");
            },
            success: function(data) {
                $("#almacen").find("option").remove();
                this.vcampos = eval(data);
                     $("#almacen").append("<option value='0'>Seleccione...</option>");
                for (i = 0; i <= this.vcampos.length; i++) {
                    $("#almacen").append("<option value='" + this.vcampos[i].cod_almacen + "'>" + this.vcampos[i].descripcion + "</option>");
                }
            }
        });
    },
    cargartipo_uso: function() {
        $.ajax({
            type: 'GET',
            data: 'opt=gettipo_uso',
            url: '../../libs/php/ajax/ajax.php',
            beforeSend: function() {
                $("#tipo_uso1").find("option").remove();
                $("#tipo_uso1").append("<option value=''>Cargando..</option>");
            },
            success: function(data) {
                $("#tipo_uso1").find("option").remove();
                this.vcampos = eval(data);
                     $("#tipo_uso1").append("<option value='0'>Seleccione...</option>");
                for (i = 0; i <= this.vcampos.length; i++) {
                    $("#tipo_uso1").append("<option value='" + this.vcampos[i].id + "'>" + this.vcampos[i].tipo + "</option>");
                }
            }
        });
    },
     
    
    init: function() {
        this.cargarProducto();
        this.cargarAlmacenes();
        this.cargartipo_uso();       
        
        this.Limpiar();
        $("#observacion").hide();
        $("#fecha_vencimiento").hide();
        
      
    },
    Limpiar: function() {

        $("#cantidadunitaria, #items,#items_descripcion,#codigoBarra, #almacen, #descripcionitem, #codigofabricante,#cantidadunitaria,#costounitario, #totalitem_tmp,#cantidaddeberia,#observacion,#cantidad_existente,#fVencimiento,#nlote,#fecha_vencimiento,#estatus_producto,#observacion1,#tipo_uso").val("");
    },
    IncluirRegistros: function(options) {
        
        var html = "";
        var campos = "";
        $.ajax({
            type: 'GET',
            data: 'opt=DetalleSelectitem&v1=' + options.id_item,
            url: '../../libs/php/ajax/ajax.php',
            success: function(data) {
                vcampos = eval(data);

                campos += $.inputHidden("_id_item", options.id_item, "[]");
                campos += $.inputHidden("_id_almacen", options.id_almacen, "[]");
                campos += $.inputHidden("_cantidad", options.cantidad, "[]");
                campos += $.inputHidden("_ubicacion", options.id_ubicacion, "[]");
                campos += $.inputHidden("_vencimineto", options.vencimiento, "[]");
                campos += $.inputHidden("_lote", options.lote, "[]");
                campos += $.inputHidden("_c_esperada", options.c_esperada, "[]");
                campos += $.inputHidden("_estatus_producto", options.estatus, "[]");
                campos += $.inputHidden("_observacion1", options.observacion, "[]");
                campos += $.inputHidden("_tipo_uso", options.tipo_uso, "[]");
                
                html = "        <tr id ="+options.id_item+">";
                html += "		<td title=\"Haga click aqui para ver el detalle del Item\" class=\"info_detalle\" style=\"cursor:pointer;background-color:#507e95;color:white;\"><a class=\"codigo\" rel=\"facebox\" style=\"color:white;\" href=\"#info\">" + options.id_item + "</a></td>";
                html += "		<td>" + options.descripcion + "</td>";
                html += "		<td>" + options.cantidad + "</td>";
                html += "		<td class=\"eliminar_serial\"><a  rel=\"borrar_serial\" style=\"color:white;\" href=\"#info\"><img style=\"cursor: pointer; float: center;\" class=\"eliminar\"  title=\"Eliminar Item\" src=\"../../libs/imagenes/delete.png\">"+ options.id_item + "</a>" + campos + "</td>";
                html += "           </tr>";
                $(".grid table.lista tbody").append(html);
                eventos_form.CargarDisplayMontos();
                win.hide();
            }
        });
    },
    IncluirSeriales: function(options) {
        var html = "";
        var campos = "";
        $.ajax({
            type: 'GET',
            data: 'opt=agregarSeriales&formulario=' + options.formulario,
            url: '../../libs/php/ajax/ajax.php',
            success: function(data) {
                //vcampos = eval(data);
               //win_tmp.hide();
               //alert(data);
            }
        });
    },
    CargarDisplayMontos: function() {
        cantidad_ = $(".grid table.lista tbody").find("tr").length;
        //alert(cantidad_)
        $(".span_cantidad_items").html("<span style=\"font-size: 10px;\">Cantidad de Items: " + (cantidad_) + "</span>");
        $("input[name='input_cantidad_items']").attr("value", cantidad_);
        var stringDisplay = "<span style='color:green'><b>Cantidad Items(" + cantidad_ + ")</b></span>";
        $("#displaytotal, #displaytotal2").html(stringDisplay);

    },
    GenerarCompraX: function() {
        if ($("#autorizado_por").val() === "") {
            $("#autorizado_por").css("border-color",$(this).val()===""?"red":"");
            $("#autorizado_por").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe Ingresar el Responsable");
        }
        else if ($("#id_proveedor").val() === "") {
            $("#id_proveedor").css("border-color",$(this).val()===""?"red":"");
            $("#id_proveedor").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe Ingresar el Proveedor");
        }
        else if ($("#nro_documento").val() === "") {
            $("#nro_documento").css("border-color",$(this).val()===""?"red":"");
            $("#nro_documento").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe Ingresar el Nro de Documento");
        }

        else if ($("#estado").val() === "") {
            $("#estado").css("border-color",$(this).val()===""?"red":"");
            $("#estado").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe Agregar Estado De Procedencia");
        }
        else if ($("#puntodeventa").val() === "") {
            $("#puntodeventa").css("border-color",$(this).val()===""?"red":"");
            $("#puntodeventa").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe Agregar Un Punto De Procedencia");
        }
        else if ($("#ubicacion_entrada").val() === "") {
            $("#ubicacion_entrada").css("border-color",$(this).val()===""?"red":"");
            $("#ubicacion_entrada").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe Ingresar la ubicacion de entrada");
        }
        else if ($("#empresa_transporte").val() === "") {
            $("#empresa_transporte").css("border-color",$(this).val()===""?"red":"");
            $("#empresa_transporte").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe Ingresar la Empresa de Transporte");
        }
        else if ($("#nacionalidad_conductor").val() === "") {
            $("#nacionalidad_conductor").css("border-color",$(this).val()===""?"red":"");
            $("#nacionalidad_conductor").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Indique la Nacionalidad del Conductor");
        }
        else if ($("#cedula_conductor").val() === "") {
            $("#cedula_conductor").css("border-color",$(this).val()===""?"red":"");
            $("#cedula_conductor").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe Ingresar la Cédula del Conductor");
        }
        else if ($("#conductor").val() === "") {
            $("#conductor").css("border-color",$(this).val()===""?"red":"");
            $("#conductor").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe Ingresar el Nombre del Conductor");
        }
        else if ($("#placa").val() === "") {
            $("#placa").css("border-color",$(this).val()===""?"red":"");
            $("#placa").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe Ingresar la Placa del Vehiculo");
        }
        else if ($("#codigo_sica").val() === "") {
            $("#codigo_sica").css("border-color",$(this).val()===""?"red":"");
            $("#codigo_sica").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe Ingresar el Nro de Guia SUNAGRO ");
        }
        else if ($("#orden_despacho").val() === "") {
            $("#orden_despacho").css("border-color",$(this).val()===""?"red":"");
            $("#orden_despacho").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe Ingresar la Orden de Despacho ");
        }
        else if ($("#nro_control").val() === "") {
            $("#nro_control").css("border-color",$(this).val()===""?"red":"");
            $("#nro_control").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe Ingresar el Nro. Control Factura");
        }
         else if ($("#nro_factura").val() === "") {
            $("#nro_factura").css("border-color",$(this).val()===""?"red":"");
            $("#nro_factura").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe Ingresar el Nro. de Factura");
        }
        else if( $("input[name='input_cantidad_items']").val()==0){
          Ext.Msg.alert("Alerta!", "Debe Ingresar un Producto antes de registrar el movimiento");
          
        }
       
        else{
            $("#formulario").submit();
        }
        return false;
    }
};

 win = new Ext.Window({
        title:'Seleccionar Producto',
        height:400,
        width:459,
        autoScroll:true,
        tbar:[
        {
            text:'Actualizar lista de Productos',
            icon: '../../libs/imagenes/ico_search.gif',
            handler: function(){
                eventos_form.cargarProducto();
            }
        },
        {
            text:'Actualizar lista de Almacenes',
            icon: '../../libs/imagenes/ico_search.gif',
            handler: function(){
                eventos_form.cargarAlmacenes();

            }
        },
        {
            text:'Limpiar',
            icon: '../../libs/imagenes/back.gif',
            handler: function(){
                eventos_form.Limpiar();
            }
        }
        ],
        modal:true,
        bodyStyle:'padding-right:10px;padding-left:10px;padding-top:5px;',
        closeAction:'hide',
        contentEl:'incluirproducto',
        buttons:[
        {
            text:'Incluir',
            icon: '../../libs/imagenes/drop-add.gif',
            handler:function(){

                if($("#items").val()==""||
                    $("#almacen").val()==""||
                    $("#almacen").val()=="0"||
                    $("#ubicacion").val()==""||                    
                    $("#cantidadunitaria").val()==""||
                    (($("#fVencimiento").val()=="") && ($("#fecha_vencimiento").val()=="Si")) ||
                    $("#nlote").val()==""||
                    $("#observacion1").val()==""
                ){
                    Ext.Msg.alert("Alerta","Debe especificar todos los campos.");
                    return false;
                }
                if($("#estatus").val()==0){                 
                    if($.trim($("#causa").val()) ==''){
                           Ext.Msg.alert("Alerta","Debe especificar la causa de la no aprobacion.");
                           return false;
                    }
                }
                eventos_form.IncluirRegistros({
                    //cod_item:           $("#items").val(),/////// Añadido
                    id_item:            $("#items").val(),
                    descripcion:        $("#items :selected").text()=="" ? $("#items_descripcion").val() : $("#items :selected").text(),
                    id_almacen:         $("#almacen").val(),
                    tipo_uso:           $("#tipo_uso1").val(),
                    id_ubicacion:       $("#ubicacion").val(),
                    cantidad:           $("#cantidadunitaria").val(),
                    vencimiento:        $("#fVencimiento").val(),
                    lote:               $("#nlote").val(),
                    c_esperada:         $("#cantidaddeberia").val(),
                    observacion:        $("#observacion1").val(),
                    estatus:            $("#estatus_producto").val()
                    
                    
                });
                
                var cod = $("#items").val();
                var cant = $("#cantidadunitaria").val();
                    var mask = new Ext.LoadMask(Ext.get("Contenido"), {
                        msg:'Cargando..',
                        removeMask:false
                    });
                    $.ajax({
                        type: 'GET',
                        data: 'opt=cantidadSeriales&cod='+cod+'&cant='+cant,
                        url:  '../../libs/php/ajax/ajax.php',
                        beforeSend: function(){
                            //mask.show();
                        },
                        success: function(data){
                            resultado = data
                            //if(resultado=="-1"){exit;}
                            if(resultado!=-1){
                            var win_tmp = new Ext.Window({
                                title:'Registro de Seriales',
                                height: 400,
                                width: 350,
                                frame:true,
                                closable:false,
                                autoScroll:true,
                                modal:true,
                                html: data,
                                buttons:[{
                                    text:'Guardar',
                                    handler: function(){
                                        //VALIDAR SERIALES NO REPETIDOS (DANIEL)
                                        cant=0;
                                        $(".serialSec").each(function(index,value){
                                            cant++;
                                            $(this).css("border-color","#CCCCCC");
                                            
                                        });                              

                                        
                                        var bandera=0;
                                        var igual=0;
                                        for (var i = 0; i < cant ; i++) {                                           
                                            valor=$("#serial"+i).val();
                                            $( ".serialSec" ).each(function( index ) {
                                                if($(this).attr('id')!="serial"+i){
                                                     if($(this).val()==valor){
                                                        igual=1;
                                                        $(this).css("border-color","#DF0101");
                                                     }  
                                                }
                                            }); 
                                        };
                                        $( ".serialSec" ).each(function( index ) {
                                            if($(this).val()==""){
                                                bandera=1;    
                                            }

                                        }); 

                           
                                        if(bandera==0 && igual==0){
                                            formulario=$("#seriales").serialize();  

                                            $.ajax({                                            
                                                type: 'GET',
                                                data: "opt=VerificarSerial&cant="+cant+"&"+formulario,
                                                dataType: "json",
                                                url: '../../libs/php/ajax/ajax.php',
                                                beforeSend: function() {
                                                  
                                                },
                                                success: function(data) {                                                   
                                                    console.log(data[0].idSerial);
                                                    if(data[0].idSerial==0){
                                                        eventos_form.IncluirSeriales({
                                                            formulario: JSON.stringify($("#seriales").serializeArray())
                                                        });
                                                        win_tmp.close(); 
                                                    }else{
                                                        $.each(data,function(index,value){
                                                             $("#"+value.idSerial).css("border-color","#DF0101");                                                         
                                                        
                                                        });  
                                                        alert("los Seriales marcados ya han sido cargado");                                                 
                                                        return false;
                                                    }
                                                                                                 

                                                  

                                                }
                                            });//fin del ajax de validar seriales


                                           
                                        }else{
                                            alert("debe ingresar todos los seriales o estan repetidos los seriales");
                                            return false;
                                        }
                                   
                                    }
                                }]                                  
                            });
                            win_tmp.show(this);
                            //mask.hide();
                            //agregando las funcionalidades al los botones de generar seriales
                                $("#gSerial").click(function(event) {                                  
                                  serialG= $("#iSerialGen").val();
                                  if(serialG==""){
                                    alert("Ingrese un serial para la serie");
                                  }else{
                                      serialG= $("#iSerialGen").val();
                                      serialG=parseInt(serialG);  
                                      valor=0;
                                    $( ".serialSec" ).each(function( index ) {
                                        id= $(this).attr("id");                                       
                                       sum= serialG + valor;
                                        valor= valor + 1;
                                         $( "#"+id ).val(sum);
                                    });
                                  }
                                });

                          }
                        }
                    });
            }
        },
        {
            text:'Cerrar',
            icon: '../../libs/imagenes/cancel.gif',
            handler:function(){
                win.hide();
            }
        },
        ]
    });