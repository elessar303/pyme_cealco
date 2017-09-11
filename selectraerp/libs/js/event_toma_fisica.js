var win;

Ext.onReady(function(){
    $.filtrarArticulo = function(value, tipoFiltro){
            var switchFiltro = tipoFiltro || "filtroItem"; // para el caso de pedido es: filtroItemByRCCB
            pBuscaItem.main.storeProductos.baseParams.opt = switchFiltro;
            pBuscaItem.main.storeProductos.baseParams.limit = pBuscaItem.main.limitePaginacion;
            pBuscaItem.main.storeProductos.baseParams.start = pBuscaItem.main.iniciar;
            pBuscaItem.main.storeProductos.baseParams.cmb_tipo_item = 1;//productos
            pBuscaItem.main.storeProductos.baseParams.codigoProducto = value;
            pBuscaItem.main.storeProductos.baseParams.BuscarBy = true;
            pBuscaItem.main.storeProductos.load({ 
                callback: function(records, operation, success) { 
                    if (success) {
                        if(records.length > 1){
                            pBuscaItem.main.mostrarWin();
                        } else if(records.length == 1){
                            $("#codigoBarra").val(records[0]['data']['codigo_barras']);
                            $("#items").val(records[0]['data']['id_item']);
                            $("#items_descripcion").val(records[0]['data']['descripcion1']);
                            $("cantidadunitaria").focus();
                            $("cantidadunitaria").select();
                        } else {
                            $.mensajeNotificacion({mensaje:'No se encontraron productos asociados'});
                            $.limpiarCamposFiltro();
                            data.productoSeleccionado = {};
                        } 
                    } 
                } 
            });
    };

$("#buscarCodigo").click(function(){
    var value = $('#codigoBarra').val();
    //if(value="") 
    //{
        pBuscaItem.main.mostrarWin(value);
        return false;
    //}

   // return false;
    
    });

});
