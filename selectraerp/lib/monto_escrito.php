<?php
include("../header.php");
include('numerosALetras.class.php');

function convertir_a_letras($numero)
{

	$n = new numerosALetras();
	$num_letras=$n->convertir($numero);
	return $num_letras;
}
?>