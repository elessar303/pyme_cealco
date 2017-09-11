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
     
   

   
    init: function() {
        this.Limpiar();
        $("#observacion").hide();
        $("#fecha_vencimiento").hide();
        
      
    },
    Limpiar: function() {
        $("#cantidadunitaria, #instalacion, #observacion_detalle, #input_fecha_planificacion, #input_fecha_planificacion_fin, #items,#items_descripcion,#codigoBarra, #almacen, #descripcionitem, #codigofabricante,#cantidadunitaria,#costounitario, #totalitem_tmp,#cantidaddeberia,#observacion,#cantidad_existente,#fVencimiento,#nlote,#fecha_vencimiento").val("");
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
                campos += $.inputHidden("_cantidad", options.cantidad, "[]");
                campos += $.inputHidden("instalacion", options.instalacion, "[]");
                campos += $.inputHidden("input_fechaplanificacion_inicio", options.input_fechaplanificacion_inicio, "[]");
                campos += $.inputHidden("input_fechaplanificacion_fin", options.input_fechaplanificacion_fin, "[]");
                campos += $.inputHidden("observacion_detalle", options.observacion_detalle, "[]");
                campos += $.inputHidden("punto_origen", options.punto_origen, "[]");
                campos += $.inputHidden("punto_destino", options.punto_destino, "[]");
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
              
            }
        });
    },
    CargarDisplayMontos: function() {
        cantidad_ = $(".grid table.lista tbody").find("tr").length;
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
        else if ($("#input_fechaplanificacion").val() === "") {
            $("#input_fechaplanificacion").css("border-color",$(this).val()===""?"red":"");
            $("#input_fechaplanificacion").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe Ingresar fecha planificacion inicial");
        }
        else if ($("#input_fechaplanificacion_fin").val() === "") {
            $("#input_fechaplanificacion_fin").css("border-color",$(this).val()===""?"red":"");
            $("#input_fechaplanificacion_fin").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe Ingresar fecha planificacion Final");
        }
        else if ($("#nro_documento").val() === "" && typeof $("#tipo_entrada")  == 'undefined' ) {
            $("#nro_documento").css("border-color",$(this).val()===""?"red":"");
            $("#nro_documento").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe ingresar el Nro. de la Orden De Compra");
        }

        else if ($("#instalacion").val() === "") {
            $("#instalacion").css("border-color",$(this).val()===""?"red":"");
            $("#instalacion").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe ingresar la Instalación del proveedor");
        }
        else if ($("#transporte").val() === "") {
            $("#transporte").css("border-color",$(this).val()===""?"red":"");
            $("#transporte").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe ingresar el Transporte");
        }

        else if ($("#observaciones").val() === "") {
            $("#observaciones").css("border-color",$(this).val()===""?"red":"");
            $("#observaciones").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe ingresar las Observaciones");
        }
        else if ($("#input_fechaplanificacion").val() === "") {
            $("#input_fechaplanificacion").css("border-color",$(this).val()===""?"red":"");
            $("#input_fechaplanificacion").css("border-width",$(this).val()===""?"1px":"");
            Ext.Msg.alert("Alerta!", "Debe ingresar Fecha Planificación");
        }
        else
        {
            fecha1 = $('#input_fechaplanificacion_inicio').val();
            fecha2 = $('#input_fechaplanificacion_fin').val();
            var x = fecha1.split("/");
            var z = fecha2.split("/");
            fecha1 = x[1] + "/" + x[0] + "/" + x[2];
            fecha2 = z[1] + "/" + z[0] + "/" + z[2];
            if(Date.parse(fecha1) > Date.parse(fecha2))
            {
                $("#input_fechaplanificacion").css("border-color",$(this).val()===""?"red":"");
                $("#input_fechaplanificacion").css("border-width",$(this).val()===""?"1px":"");
                Ext.Msg.alert("Alerta!", "Fecha Inicial No puede ser mayor a Fecha Final");
                return false;
            }

            $("#formulario").submit();
        }
        return false;
    }
};