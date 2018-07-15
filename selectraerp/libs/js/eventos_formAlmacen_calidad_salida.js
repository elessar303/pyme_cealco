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
     
    //   cargarUbicaciones: function() {    
    //     idAlmacen=$("#almacen").val();
    //     $.ajax({
    //         type: 'POST',
    //         data: 'opt=cargaUbicacion&idAlmacen='+idAlmacen,
    //         url: '../../libs/php/ajax/ajax.php',
    //         beforeSend: function() {
    //             $("#ubicacion").find("option").remove();
    //             $("#ubicacion").append("<option value=''>Cargando..</option>");
    //         },
    //         success: function(data) {
    //             $("#ubicacion").find("option").remove();
    //             this.vcampos = eval(data);
    //             for (i = 0; i <= this.vcampos.length; i++) {
    //                 $("#ubicacion").append("<option value='" + this.vcampos[i].id + "'>" + this.vcampos[i].descripcion + "</option>");
    //             }
    //         }
    //     });
    // },

   
    init: function() {
        this.cargarProducto();
        this.cargarAlmacenes();
        this.cargartipo_uso();       
        this.Limpiar();
        $("#observacion").hide();
        $("#fecha_vencimiento").hide();
        
      
    },
    Limpiar: function() {

        $("#cantidadunitaria, #items,#items_descripcion,#codigoBarra, #almacen, #descripcionitem, #codigofabricante,#cantidadunitaria,#costounitario, #totalitem_tmp,#cantidaddeberia,#observacion,#cantidad_existente,#fVencimiento,#nlote,#fecha_vencimiento,#estatus_producto,#observacion1,#tipo_uso,#costo_referencial,#costo_declarado,#marca,#presentacion").val("");
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
                campos += $.inputHidden("_marca", options.marca, "[]");
                campos += $.inputHidden("_presentacion", options.presentacion, "[]");
                campos += $.inputHidden("_costo_declarado", options.costo_declarado, "[]");
                campos += $.inputHidden("_costo_referencial", options.costo_referencial, "[]");
                
                html = "           <tr id ="+options.id_item+">";
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
            Ext.Msg.alert("Alerta!", "Debe Ingresar al Cliente");
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
        }else if($("#id_ticket").val()==""){
            Ext.Msg.alert("Alerta","Debe Seleccionar el ticket del Conductor.");
            $("#id_ticket").css("border-color",$(this).val()===""?"red":"");
            $("#id_ticket").css("border-width",$(this).val()===""?"1px":"");
        }else if( $("input[name='input_cantidad_items']").val()==0){
            Ext.Msg.alert("Alerta!", "Debe Ingresar un Producto antes de registrar el movimiento");
        }else{
            $("#formulario").submit();
        }
        var pruebatab = Ext.getCmp('remove-this-tab');
        tab.show(pruebatab);
        return false;
    }
};