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
            data: 'opt=SelectrubroMercadeo&v1=1',
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
        this.cargarRubroMercadeo();
        //this.cargarAlmacenes();       
        this.Limpiar();
        //$("#observacion").hide();
        
    },

    Limpiar: function() {
        $("#cantidadunitaria, #establecimiento, #rubros, #precio, #items, #codigoBarra, #almacen, #descripcionitem, #codigofabricante, #cantidadunitaria, #costounitario, #totalitem_tmp, #cantidaddeberia, #observacion, #cantidad_existente, #fVencimiento, #nlote").val("");
    },

    IncluirRegistros: function(options) {
        var html = "";
        var campos = "";
        $.ajax({
            type: 'GET',
            data: 'opt=DetalleProductoMercadeo&v1=' + options.id_rubro,
            url: '../../libs/php/ajax/ajax.php',
            success: function(data) {
                vcampos = eval(data);
                
                //campos += $.inputHidden("_id_rubro", options.id_rubro, "[]");
                campos += $.inputHidden("_establecimiento", options.establecimiento, "[]");
                campos += $.inputHidden("_producto",options.producto, "[]");
                campos += $.inputHidden("_precio", options.precio, "[]");
                html = "           <tr >";
                //html += "		<td title=\"Haga click aqui para ver el detalle del Item\" class=\"info_detalle\" style=\"cursor:pointer;background-color:#507e95;color:white;\"><a class=\"codigo\" rel=\"facebox\" style=\"color:white;\" href=\"#info\">" + options.producto + "</a></td>";
                html += "       <td>" + options.establecimiento2 + "</td>";
                html += "		<td>" + options.descripcion + "</td>";
                html += "		<td>" + options.precio + "</td>";
                html += "		<td><img style=\"cursor: pointer;\" class=\"eliminar\"  title=\"Eliminar Item\" src=\"../../libs/imagenes/delete.png\">" + campos + "</td>";
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
            $("#estado").val()==0 ||
            $("#establecimiento").val()==0){

            Ext.Msg.alert("Alerta!", "Debe llenar todos los campos antes de registrar el estudio");
          
        }
       
        else{
            $("#formulario").submit();
        }
        return false;
    }
};
