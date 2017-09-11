inputHidden  = function(Input,Value,ID){
        return '<input type="hidden" name="'+Input+''+ID+'" value="'+Value+'">';
};

setValoresInput = function(nombreObjetoDestino,nombreObjetoActual){
    $(nombreObjetoDestino).attr("value", $(nombreObjetoActual).val());
};

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
        //return (nums.length > 1)?(nums[0] + "," + nums[1].substring(0,2)):num;
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
                for (i = 0; i < this.vcampos.length; i++) {
                    $("#items").append("<option value='" + this.vcampos[i].id_item + "'>" + this.vcampos[i].descripcion1 + "</option>");
                }
            }
        });
    },
    cargarServicio: function() {
        $.ajax({
            type: 'GET',
            data: 'opt=Selectitem&v1=2',
            url: '../../libs/php/ajax/ajax.php',
            beforeSend: function() {
                $("#items2").find("option").remove();
                $("#items2").append("<option value=''>Cargando..</option>");
            },
            success: function(data) {
                $("#items2").find("option").remove();
                this.vcampos = eval(data);
                for (i = 0; i <= this.vcampos.length; i++) {
                    $("#items2").append("<option value='" + this.vcampos[i].id_item + "'>" + this.vcampos[i].descripcion1 + "</option>");
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
                for (i = 0; i < this.vcampos.length; i++) {
                    $("#almacen").append("<option value='" + this.vcampos[i].cod_almacen + "'>" + this.vcampos[i].descripcion + "</option>");
                }
            }
        });
    },
    init: function() {
        this.cargarProducto();
        this.cargarAlmacenes();
        this.Limpiar();
    },
    init2: function() {
        this.cargarServicio();
        this.Limpiar2();
    },
    Limpiar: function() {
        $("#cantidadunitaria, #items, #almacen, #descripcionitem, #codigofabricante,#cantidadunitaria,#costounitario, #totalitem_tmp").val("");
    },
    Limpiar2: function() {
        $("#cantidadunitaria2, #items2, #descripcionitem2, #cantidadunitaria2,#costounitario2, #totalitem_tmp2").val("");
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
                var_precio = eval(options.precio);
                var_iva = eval(vcampos[0].iva);
                ttotalsiniva = eval(options.cantidad * options.precio);
                vprecio = eval(options.precio);
                vtprecio = eval(options.totalsiniva);
                var iva = 0;
                campos += inputHidden("_id_item", options.id_item, "[]");
                campos += inputHidden("_id_almacen", options.id_almacen, "[]");
                campos += inputHidden("_cantidad", options.cantidad, "[]");
                campos += inputHidden("_precio", var_precio.toFixed(2), "[]");
                campos += inputHidden("_iva", var_iva.toFixed(2), "[]");
                if (vcampos[0].monto_exento == "0") {
                    iva = ttotalsiniva * vcampos[0].iva / 100;
                    iva = eval(iva);
                    campos += inputHidden("_tiva", iva.toFixed(2), "[]");
                }
                else {
                    iva = 0;
                    campos += inputHidden("_tiva", 0, "[]");
                }
                campos += inputHidden("_ttotalsiniva", ttotalsiniva.toFixed(2), "[]");
                campos += inputHidden("_codfabricante", options.codfabricante, "[]");

                html = "<tr>";
                html += "<td title=\"Haga click aqui para ver el detalle del Item\" class=\"info_detalle\" style=\"cursor:pointer;background-color:#507e95;color:white;\"><a class=\"codigo\" rel=\"facebox\" style=\"color:white;\" href=\"#info\">" + options.id_item + "</a></td>";
                html += "<td>" + options.descripcion + "</td>";
                html += "<td>" + options.cantidad + "</td>";
                html += "<td>" + vprecio.toFixed(2) + "</td>";
                html += "<td>" + vtprecio.toFixed(2) + "</td>";
                html += "<td><img style=\"cursor: pointer;\" class=\"eliminar\" title=\"Eliminar Item\" src=\"../../libs/imagenes/delete.png\">" + campos + "</td>";
                html += "</tr>";
                $(".grid table.lista tbody").append(html);
                eventos_form.CargarDisplayMontos();
                $("#totalizar_monto_cancelar").trigger("click");
                //win.hide();
            }
        });
    },
    IncluirRegistros2: function(options) {
        var html = "";
        var campos = "";
        $.ajax({
            type: 'GET',
            data: 'opt=DetalleSelectitem&v1=' + options.id_item,
            url: '../../libs/php/ajax/ajax.php',
            success: function(data) {
                vcampos = eval(data);
                var_precio = eval(options.precio);
                var_iva = eval(vcampos[0].iva);
                ttotalsiniva = eval(options.cantidad * options.precio);
                vprecio = eval(options.precio);
                vtprecio = eval(options.totalsiniva);
                var iva = 0;
                campos += inputHidden("_id_item", options.id_item, "[]");
                campos += inputHidden("_id_almacen", options.id_almacen, "[]");
                campos += inputHidden("_cantidad", options.cantidad, "[]");
                campos += inputHidden("_precio", var_precio.toFixed(2), "[]");
                campos += inputHidden("_iva", var_iva.toFixed(2), "[]");
                if (vcampos[0].monto_exento == "0") {
                    iva = ((ttotalsiniva * vcampos[0].iva) / 100);
                    iva = eval(iva);
                    campos += inputHidden("_tiva", iva.toFixed(2), "[]");
                }
                else {
                    iva = 0;
                    campos += inputHidden("_tiva", 0, "[]");
                }
                campos += inputHidden("_ttotalsiniva", ttotalsiniva.toFixed(2), "[]");
                campos += inputHidden("_codfabricante", options.codfabricante, "[]");

                html = "<tr>";
                html += "<td title=\"Haga click aqui para ver el detalle del Item\" class=\"info_detalle\" style=\"cursor:pointer;background-color:#507e95;color:white;\"><a class=\"codigo\" rel=\"facebox\" style=\"color:white;\" href=\"#info\">" + options.id_item + "</a></td>";
                html += "<td>" + options.descripcion + "</td>";
                html += "<td>" + options.cantidad + "</td>";
                html += "<td>" + vprecio.toFixed(2) + "</td>";
                html += "<td>" + vtprecio.toFixed(2) + "</td>";
                html += "<td><img style=\"cursor: pointer;\" class=\"eliminar\" title=\"Eliminar Item\" src=\"../../libs/imagenes/delete.png\">" + campos + "</td>";
                html += "</tr>";
                $(".grid2 table.lista2 tbody").append(html);
                eventos_form.CargarDisplayMontos2();
                $("#totalizar_monto_cancelar").trigger("click");
                //win2.hide();
            }
        });
    },
    CargarDisplayMontos: function() {
        cantidad_ = $(".grid table.lista tbody").find("tr").length;
        cantidad2_ = $(".grid2 table.lista2 tbody").find("tr").length;
        $(".span_cantidad_items").html("<span style=\"font-size: 10px;\">Cantidad de Items: " + (cantidad_) + "</span>");
        var sum_ttotalsiniva = 0, sum_tiva = 0;
        $(".grid table.lista tbody").find("tr").each(function() {
            sum_ttotalsiniva += eval($(this).find("td").eq(5).find("input[name='_ttotalsiniva[]']").val());
            sum_tiva += eval($(this).find("td").eq(5).find("input[name='_tiva[]']").val());
        });
        $(".grid2 table.lista2 tbody").find("tr").each(function() {
            sum_ttotalsiniva += eval($(this).find("td").eq(5).find("input[name='_ttotalsiniva[]']").val());
            sum_tiva += eval($(this).find("td").eq(5).find("input[name='_tiva[]']").val());
        });
        $("#input_tiva").val(sum_tiva.toFixed(2));
        $("#input_tsiniva").val(sum_ttotalsiniva.toFixed(2));
        acu_input_tciniva = sum_ttotalsiniva + sum_tiva;
        $("#input_tciniva").val(acu_input_tciniva.toFixed(2));
        var stringDisplay = "<span style='color:green'><b>Cantidad Items(" + cantidad_ + ")</b></span> :: <b>Total</b> (sin I.V.A): " + sum_ttotalsiniva.toFixed(2) + " :: <b>Total I.V.A:</b> " + sum_tiva.toFixed(2) + " :: <b>Total</b> (con I.V.A): " + acu_input_tciniva.toFixed(2)
        $("#displaytotal").html(stringDisplay);
        cantidad_ = parseFloat(cantidad_) + parseFloat(cantidad2_);
        $("input[name='input_cantidad_items']").attr("value", cantidad_);
    },
    CargarDisplayMontos2: function() {
        cantidad_ = $(".grid2 table.lista2 tbody").find("tr").length;
        cantidad2_ = $(".grid table.lista tbody").find("tr").length;
        $(".span_cantidad_items2").html("<span style=\"font-size: 10px;\">Cantidad de Items: " + (cantidad_) + "</span>");
        var sum_ttotalsiniva = 0, sum_tiva = 0;
        $(".grid2 table.lista2 tbody").find("tr").each(function() {
            sum_ttotalsiniva += eval($(this).find("td").eq(5).find("input[name='_ttotalsiniva[]']").val());
            sum_tiva += eval($(this).find("td").eq(5).find("input[name='_tiva[]']").val());
        });
        $(".grid table.lista tbody").find("tr").each(function() {
            sum_ttotalsiniva += eval($(this).find("td").eq(5).find("input[name='_ttotalsiniva[]']").val());
            sum_tiva += eval($(this).find("td").eq(5).find("input[name='_tiva[]']").val());
        });
        $("#input_tiva").val(sum_tiva.toFixed(2));
        $("#input_tsiniva").val(sum_ttotalsiniva.toFixed(2));
        acu_input_tciniva = sum_ttotalsiniva + sum_tiva;
        $("#input_tciniva").val(acu_input_tciniva.toFixed(2));
        var stringDisplay = "<span style='color:green'><b>Cantidad Items(" + cantidad_ + ")</b></span> :: <b>Total</b> (sin I.V.A): " + sum_ttotalsiniva.toFixed(2) + " :: <b>Total I.V.A:</b> " + sum_tiva.toFixed(2) + " :: <b>Total</b> (con I.V.A): " + acu_input_tciniva.toFixed(2)
        $("#displaytotal2").html(stringDisplay);
        cantidad_ = parseFloat(cantidad_) + parseFloat(cantidad2_);
        $("input[name='input_cantidad_items']").attr("value", cantidad_);
    },
    GenerarCompraX: function(){//"input[name='totalizar_nro_cheque']"
        /*var responsable = $("input[name='responsable']").val();
        var num_factura = $("input[name='num_factura']").val();
        var num_control_factura = $("input[name='num_cont_factura']").val();

        if (responsable === "") {
            Ext.Msg.alert("Alerta!", "Debe Ingresar el Responsable");
            return false;
        }
        else if (num_factura === "") {
            Ext.Msg.alert("Alerta!", "Debe Ingresar el Nro. de Factura");
            return false;
        }
        else if (num_control_factura === "") {
            Ext.Msg.alert("Alerta!", "Debe Ingresar el Nro. de Control Factura");
            return false;
        }*/

        tsiniva = eval($("#input_tsiniva").val());
        totalgeneral = eval($("#input_tciniva").val());

        if (totalgeneral <= 0) {
            Ext.Msg.alert("Alerta!", "El monto general es cero, probablemente usted deba cargar un item.");
            return false;
        }

        sum0 = $("input[name='totalizar_monto_efectivo']").val();
        //validar si selecciono el modo de pago con cheque
        valor = $("#opt_cheque").val();
        sw = false;
        if (valor == 1) {
            if ($("input[name='totalizar_monto_cheque']").val() == "0") {
                sw = true;
            }
            if ($("input[name='totalizar_nro_cheque']").val() == "0") {
                sw = true;
            }
            if ($("#totalizar_nombre_banco").val() == "0") {
                sw = true;
            }
            if (sw == true) {
                Ext.Msg.alert("Alerta!", "Verifique los datos de instrumento pago (CHEQUE)");
                return false;
            }
            sum1 = $("input[name='totalizar_monto_cheque']").val();
        } else {
            sum1 = 0;
        }
        //validar si selecciono el modo de pago con tarjeta
        valor = $("#opt_tarjeta").val();
        sw = false;
        if (valor == 1) {
            if ($("input[name='totalizar_monto_tarjeta']").val() == "0") {
                sw = true;
            }
            if ($("input[name='totalizar_nro_tarjeta']").val() == "0") {
                sw = true;
            }
            if ($("#totalizar_tipo_tarjeta").val() == "0") {
                sw = true;
            }
            if (sw == true) {
                Ext.Msg.alert("Alerta!", "Verifique los datos de instrumento pago (TARJETA)");
                return false;
            }
            sum2 = $("input[name='totalizar_monto_tarjeta']").val();
        } else {
            sum2 = 0;
        }

        //validar si selecciono el modo de pago con deposito
        valor = $("#opt_deposito").val();
        sw = false;
        if (valor == 1) {
            if ($("input[name='totalizar_monto_deposito']").val() == "0") {
                sw = true;
            }
            if ($("input[name='totalizar_nro_deposito']").val() == "0") {
                sw = true;
            }
            if ($("#totalizar_banco_deposito").val() == "0") {
                sw = true;
            }
            if (sw == true) {
                Ext.Msg.alert("Alerta!", "Verifique los datos de instrumento pago (DEPOSITO)");
                return false;
            }
            sum3 = $("input[name='totalizar_monto_deposito']").val();
        } else {
            sum3 = 0;
        }

        //validar si selecciono el modo de pago otro documento
        valor = $("#opt_otrodocumento").val();
        sw = false;
        if (valor == 1) {

            if ($("#totalizar_tipo_otrodocumento").val() == "0") {
                sw = true;
            }
            if ($("input[name='totalizar_monto_otrodocumento']").val() == "0") {
                sw = true;
            }
            //if($("input[name='totalizar_nro_otrodocumento']").val()=="0"){sw=true;}
            //if($("#totalizar_banco_otrodocumento").val()=="0"){sw=true;}
            if (sw == true) {
                Ext.Msg.alert("Alerta!", "Verifique los datos de instrumento pago (OTRO DOCUMENTO)");
                return false;
            }
            sum4 = $("input[name='totalizar_monto_otrodocumento']").val();
        } else {
            sum4 = 0;
        }

        total_acancelar = parseFloat(sum0) + parseFloat(sum1) + parseFloat(sum2) + parseFloat(sum3) + parseFloat(sum4);
        //alert(total_acancelar+" "+parseFloat($("input[name='totalizar_monto_cancelar']").val()));
        //if(parseFloat(total_acancelar) > parseFloat($("input[name='totalizar_monto_cancelar']").val()) || parseFloat(total_acancelar) < parseFloat($("input[name='totalizar_monto_cancelar']").val()) ){

        //if(parseFloat(total_acancelar) < parseFloat($("input[name='totalizar_monto_cancelar']").val()) ){

        //Ext.Msg.alert("Alerta!","Verifique los montos de instrumento de pago, son distintos al del monto a cancelar.");
        //   return false;
        //}

        femision = $("#fecha_emision").val();
        if (femision == "") {
            Ext.Msg.alert("Alerta!", "Debe Cargar la Fecha de Emision");
            return false;
        }

        setValoresInput("#input_totalizar_sub_total", "#tsiniva");
        setValoresInput("#input_totalizar_monto_iva", "#input_tiva");
        setValoresInput("#input_totalizar_total_general", "#totalgeneral");

        //#FORMA PAGO
        setValoresInput("#input_totalizar_monto_cancelar", "#totalizar_monto_cancelar");
        setValoresInput("#input_totalizar_saldo_pendiente", "#totalizar_saldo_pendiente");
        setValoresInput("#input_totalizar_cambio", "#totalizar_cambio");

        //#INSTRUMENTO DE PAGO
        setValoresInput("#input_totalizar_monto_efectivo", "#totalizar_monto_efectivo");
        setValoresInput("#input_totalizar_monto_cheque", "#totalizar_monto_cheque");
        setValoresInput("#input_totalizar_nro_cheque", "#totalizar_nro_cheque");
        setValoresInput("#input_totalizar_nombre_banco", "#totalizar_nombre_banco");
        setValoresInput("#input_totalizar_monto_tarjeta", "#totalizar_monto_tarjeta");
        setValoresInput("#input_totalizar_nro_tarjeta", "#totalizar_nro_tarjeta");
        setValoresInput("#input_totalizar_tipo_tarjeta", "#totalizar_tipo_tarjeta");
        setValoresInput("#input_totalizar_monto_deposito", "#totalizar_monto_deposito");
        setValoresInput("#input_totalizar_nro_deposito", "#totalizar_nro_deposito");
        setValoresInput("#input_totalizar_banco_deposito", "#totalizar_banco_deposito");

        $("#form-item").submit();
    }
}
