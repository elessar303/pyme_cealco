<?php
include("Clases/clases.php");
$directorio = "Archivos/";
$nombre='Consolidado'.'.txt';
if($directorio = opendir("Archivos/"))
{
    $ban=0;
    $archivo_consolidado=fopen("Consolidados/".$nombre,"w+"); //Archivo Consolidado
    while ($archivo = readdir($directorio)) //Directorio de los TXT
    {
    if (is_dir($archivo))//si es archivo o directorio
        {
        //echo $archivo."Directorio<br>";
        } 
        else
        {
        //echo $archivo."Archivo<br>";
        $files=fopen("Archivos/".$archivo,"r");
        while(!feof($files))
            {
            
            fwrite($archivo_consolidado, fgets($files));
       	    } //Escritura en archivo consolidado
        fclose($files); //cierro el archivo para abrir el siguiente
        }
    }
    fclose($archivo_consolidado); //Cierro el archivo consolidado
}
//Se abre el archivo de consolidacion para realizar la carga a la base de datos
$archivo_consolidado=fopen("Consolidados/".$nombre,"r");
$loop = 0; // contador de líneas
while (!feof($archivo_consolidado)){ // loop hasta que se llegue al final del archivo
$loop++;
$line = fgets($archivo_consolidado); // guardamos toda la línea en $line como un string
// dividimos $line en celdas, separadas por el caracter ',' e incorporamos la línea a la matriz $field
$field[$loop] = explode (',', $line);
//Asignamos los valores a las variables correspondientes
$cedula=$field[$loop][1];
$fecha=$field[$loop][2];
$codigo_cantv=$field[$loop][3];
$cantidad=$field[$loop][4];
$cadena=$field[$loop][5];
$codigo_punto=$field[$loop][6];
$codigo_estado=$field[$loop][7];

//Insertamos en la base de datos por linea
$db = new MySQL();
$insert = $db->query("INSERT INTO consolidado_ventas(cedula, fecha, codigo_cantv, cantidad, cadena, codigo_punto, codigo_estado) 
VALUES ($cedula,$fecha,$codigo_cantv,$cantidad,$cadena,$codigo_punto,$codigo_estado)");

$desconecar=$db->desconectar();
    
$archivo_consolidado++; // necesitamos llevar el puntero del archivo a la siguiente línea
}
?>