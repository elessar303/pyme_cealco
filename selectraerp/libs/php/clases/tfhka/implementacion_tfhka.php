<?php

function getStatusInformativo($cmd = "") {
    while (!file_exists("C:\IntTFHKA\Status.txt"));

    $stream = fopen("C:\IntTFHKA\Status.txt", "rb");
    $chunk = "";
    while (!feof($stream)) {
        $chunk .= fread($stream, 8192);
    }
    #while ($chunk=fgets($stream)!==false);
    /* $nombre_fichero = "C:\IntTFHKA\Status.txt";
      if (file_exists($nombre_fichero)) {
      echo "El fichero $nombre_fichero existe";
      } else {
      echo "El fichero $nombre_fichero no existe";
      }exit; */

    //$chunk = fread($stream, 128);
    //$length = strlen($chunk);
    //$chunk = stream_get_contents($stream);
    //return $chunk;

    switch ($cmd) {
        case 'S1':
            #$chunk = substr(stream_get_contents($stream), 21, 8);
            $chunk = substr($chunk, 21, 8);
            break;
        case 'S2':
            $chunk = substr(stream_get_contents($stream), 21, 8);
            //$chunk = substr($chunk, 21, 8);
            break;
        default:
            //$chunk = stream_get_contents($stream);
            #$chunk = fread($stream, 8192);
            $chunk = $chunk;
            break;
    }

    fclose($stream);
    return $chunk;
}

function parseStatus($content) {
    $info = array();
    $info["id_factura"] = array_slice($content, 0, 7);

    return $info;
}

/*
  ob_flush();

  fclose($fp);

  echo $html_str;  //or whatever.
 */
?>