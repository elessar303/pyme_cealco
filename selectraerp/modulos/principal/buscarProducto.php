<?php

require_once("../../config.ini.php");
include("../../libs/php/clases/banco.php");
$banco = new Banco();




$campos = $banco->ObtenerFilasBySqlSelect("select * from item where cod_item='".$_GET['codigo']."'");


echo "

for (var i =1 ; i<= 30 ; i++)
{

var id = document.getElementById('id' + i);
	
      	if ('".$_GET['codigo']."' == id.value) {alert ('Producto ya seleccionado'); break; }
	if (id.value=='') 
	{
        
	if ('".$campos[0]['id_item']."' != '')
        {
	id.value = '".$_GET['codigo']."';
	id.style.visibility= 'visible';

	var nombre = document.getElementById('nombre' + i);
	nombre.value = '".$campos[0]['descripcion1']."';
	nombre.style.visibility= 'visible';

	var empaque = document.getElementById('empaque' + i);
	empaque.value = '".$campos[0]['unidad_empaque']."';
	empaque.style.visibility= 'visible';

	var item= document.getElementById('item' + i);
	item.value = '".$campos[0]['id_item']."';
	

	var cantidad = document.getElementById('cantidad' + i);
	cantidad.style.visibility= 'visible';




	var subtotal = document.getElementById('sub_total' + i);
	subtotal.value = '';
	subtotal.style.visibility= 'visible';




	var precio = document.getElementById('precio' + i);
	precio.value = '".$campos[0]['precio1']."';
	precio.style.visibility= 'visible';

	var eliminar= document.getElementById('eliminar' + i);

	eliminar.style.visibility= 'visible';
        break;
	}
	else
	{
	alert ('No existe el Producto ".$_GET['codigo']."'); break;
	}

	}

}

	var nombre = document.getElementById('".$_GET['campo']."');
	nombre.value = '';




";


?>
