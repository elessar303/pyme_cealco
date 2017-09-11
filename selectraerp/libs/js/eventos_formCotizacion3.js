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
    
    cargarRubroMercadeo: function() {
        $.ajax({
            type: 'GET',
            data: 'opt=SelectproductoCotizacion&v1=1',
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
                    $("#items").append("<option value='" + this.vcampos[i].id_rubro + "'>" + this.vcampos[i].nombre_rubro + " " + this.vcampos[i].id_rubro + "</option>");
                }
            }
        });
    },
   
    init: function() {
        //this.cargarRubroMercadeo();
        //this.cargarAlmacenes();       
        this.Limpiar();
        //$("#observacion").hide();
        
    },

    Limpiar: function() {
        //$("#cantidadunitaria, #porcentaje_ganancia, #precio, #items, #precio_unitario, #precio_sugerido, #pvp, #operatividad, #margen_ganancia, #costo_operativo, #ivaproduct, #codigoBarra, #almacen, #descripcionitem, #codigofabricante, #cantidadunitaria, #costounitario, #totalitem_tmp, #cantidaddeberia, #observacion, #nlote").val("");
        $("#rubros,#precio_unitario, #troquel, #estatus_producto, #porcentaje_operatividad, #operatividad, #costo_sin_iva, #ivaproduct, #precio_sugerido, #porcentaje_ganancia, #margen_ganancia, #pvp").val("");
        
        if ($("#rubros").val()=="" ||
            $("#troquel").val()=="" ||
            $("#estatus_producto").val()=="" ||
            $("#precio_sugerido").val()=="" ||
            $("#porcentaje_ganancia").val()=="" ||
            $("#margen_ganancia").val()==""
        ) {
            $("#rubros").val("0");
            $("#troquel").val("0");
            $("#estatus_producto").val("0");
            $("#precio_sugerido").val("0");
            $("#porcentaje_ganancia").val("0");
            $("#margen_ganancia").val("0");
        };
    },

    IncluirRegistros: function(options) {
        var html = "";
        var campos = "";
        $.ajax({
            type: 'GET',
            data: 'opt=DetalleProductoCotizacion&v1=' + options.producto,
            url: '../../libs/php/ajax/ajax.php',
            success: function(data) {
                vcampos = eval(data);
                
                //campos += $.inputHidden("_id_rubro", options.id_rubro, "[]");
                campos += $.inputHidden("_producto",options.producto, "[]");
                campos += $.inputHidden("_precio", options.costo_sin_iva, "[]");
                campos += $.inputHidden("_ivaproduct", options.ivaproduct, "[]");
                campos += $.inputHidden("_precio_sugerido", options.precio_sugerido, "[]");
                campos += $.inputHidden("_margen_ganancia", options.margen_ganancia, "[]");
                campos += $.inputHidden("_pvp", options.pvp, "[]");
                campos += $.inputHidden("_precio_unitario", options.precio_unitario, "[]");
                campos += $.inputHidden("_porcentaje_operatividad", options.porcentaje_operatividad, "[]");
                campos += $.inputHidden("_operatividad", options.operatividad, "[]");
                campos += $.inputHidden("_porcentaje_ganancia", options.porcentaje_ganancia, "[]");
                campos += $.inputHidden("_troquel", options.troquel, "[]");
                html = "           <tr >";
                //html += "     <td title=\"Haga click aqui para ver el detalle del Item\" class=\"info_detalle\" style=\"cursor:pointer;background-color:#507e95;color:white;\"><a class=\"codigo\" rel=\"facebox\" style=\"color:white;\" href=\"#info\">" + options.producto + "</a></td>";
                html += "       <td>" + options.descripcion + "</td>";
                html += "       <td>" + options.costo_sin_iva + "</td>";
                html += "       <td>" + options.ivaproduct + "</td>";
                html += "       <td>" + options.precio_sugerido + "</td>";
                html += "       <td>" + options.margen_ganancia + "</td>";
                html += "       <td>" + options.pvp + "</td>";
                html += "       <td>En Estudio</td>";
                html += "       <td>" + options.describe_troquel + "</td>";
                html += "       <td><img style=\"cursor: pointer;\" class=\"eliminar\"  title=\"Eliminar Item\" src=\"../../libs/imagenes/delete.png\">" + campos + "</td>";
                html += "           </tr>";
                $(".grid table.lista tbody").append(html);
                eventos_form.CargarDisplayMontos();
                win.hide();
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
       
        else if($("input[name='input_cantidad_items']").val()==0 ||
            $("#observaciones").val()=="" ||
            $("#tipo_transporte").val()=="0" ||
            $("#proveedores").val()=="0"){

            Ext.Msg.alert("Alerta!", "Debe llenar todos los campos antes de registrar la cotización");
          
        }
       
        else{
            $("#formulario").submit();
        }
        return false;
    }
};
