<?php
$xml = file_get_contents("http://interop.minpal.gob.ve:8088/homologados/empresas");
echo json_encode($xml);
?>