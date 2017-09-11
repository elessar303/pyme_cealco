


var eventos_form = {
    vcampos: '',
    formatearNumero: function(objeto){
        var num = objeto.numero;
        var n = num.toString();
        var nums = n.split('.');
        var newNum = "";
        if (nums.length > 1)
        {
            var dec = nums[1].substring(0,2);
            newNum = nums[0] + "," + dec;
        }
        else
        {
        newNum = num;
        }
        return newNum;
    },
    cargarProducto: function(){

         $.ajax({
                    type: 'GET',
                    data: 'opt=Selectitem&v1=1',
                    url:  '../../libs/php/ajax/ajax.php',
                    beforeSend: function(){
                            $("#items").find("option").remove();
                            $("#items").append("<option value=''>Cargando..</option>");
                        },
                    success: function(data){
                        $("#items").find("option").remove();
                        this.vcampos = eval(data);
                        for(i=0;i<=this.vcampos.length;i++){
                            $("#items").append("<option value='"+this.vcampos[i].id_item+"'>"+this.vcampos[i].descripcion1+"</option>");
                        }
                    }
            });
    },
    cargarAlmacenes: function(){

         $.ajax({
                    type: 'GET',
                    data: 'opt=getAlmacen',
                    url:  '../../libs/php/ajax/ajax.php',
                    beforeSend: function(){
                            $("#almacen").find("option").remove();
                            $("#almacen").append("<option value=''>Cargando..</option>");
                        },
                    success: function(data){
                        $("#almacen").find("option").remove();
                        this.vcampos  = eval(data);
                        for(i=0;i<=this.vcampos.length;i++){
                            $("#almacen").append("<option value='"+this.vcampos[i].cod_almacen+"'>"+this.vcampos[i].descripcion+"</option>");
                        }

                    }
         });
    },
    init: function(){
        this.cargarProducto();
        this.cargarAlmacenes();
        this.Limpiar();
    },
    Limpiar: function(){
        $("#cantidadunitaria, #items, #almacen, #descripcionitem, #codigofabricante,#cantidadunitaria,#costounitario, #totalitem_tmp").val("");
    },
    IncluirRegistros: function(options){
                var html="";
                var campos = "";
                $.ajax({
                    type: 'GET',
                    data: 'opt=DetalleSelectitem&v1='+options.id_item,
                    url:  '../../libs/php/ajax/ajax.php',
                    success: function(data){

                            vcampos  = eval(data);
                            campos += $.inputHidden("_id_item", options.id_item, "[]");
                            campos += $.inputHidden("_id_almacen", options.id_almacen, "[]");
                            campos += $.inputHidden("_cantidad", options.cantidad, "[]");

                            html  = "           <tr >";
                            html += "		<td title=\"Haga click aqui para ver el detalle del Item\" class=\"info_detalle\" style=\"cursor:pointer;background-color:#507e95;color:white;\"><a class=\"codigo\" rel=\"facebox\" style=\"color:white;\" href=\"#info\">"+options.id_item+"</a></td>";
                            html += "		<td>"+options.descripcion+"</td>";
                            html += "		<td>"+options.cantidad+"</td>";
                            html += "		<td><img style=\"cursor: pointer;\" class=\"eliminar\"  title=\"Eliminar Item\" src=\"../../libs/imagenes/delete.png\">"+campos+"</td>";
                            html += "           </tr>";
                            $(".grid table.lista tbody").append(html);
                            eventos_form.CargarDisplayMontos();
                            win.hide();



                        }
                    });



    },
     CargarDisplayMontos: function(){
                    cantidad_ = $(".grid table.lista tbody").find("tr").length;
                    //alert(cantidad_)
                    $(".span_cantidad_items").html("<span style=\"font-size: 10px;\">Cantidad de Items: "+(cantidad_)+"</span>");
                    $("input[name='input_cantidad_items']").attr("value",cantidad_);
                    var stringDisplay = "<span style='color:green'><b>Cantidad Items("+cantidad_+")</b></span>"
                    $("#displaytotal, #displaytotal2").html(stringDisplay);

    },
    GenerarCompraX: function(){

    respon = $("#autorizado_por").val();
    if(respon==""){
        Ext.Msg.alert("Alerta!","Debe cargar el responsable");

        return false;
    }



    $("#formulario").submit();

    }
}



