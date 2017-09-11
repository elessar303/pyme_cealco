<?php
  $host = "localhost";
  $usuario = "root"; //Aqui se coloca el nombre de usuario
  $contrasena = "selectra"; //Aqui se coloca el password
  $conectar = mysql_connect($host, $usuario, $contrasena); //Aqui se pasan datos conexion bd
  mysql_select_db("sisalud_admon", $conectar); //Aqui se conecta la bd
?>
