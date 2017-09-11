    <?php

    include("../../libs/php/clases/almacen.php");
    $almacen = new Almacen();
       
   echo $tipoSql = $_POST["tipoSql"];
   echo $id_rubro = $_POST["idCarga"];
    $campos = $almacen->ObtenerFilasBySqlSelect("select * from grupo where id_rubro = ".$id_rubro);
  
              /*SELECT QUE LISTA TODOS LOS REGISTROS*/                  
                foreach ( $campos as $filas){
                ?>
                <option value= "<?php echo $filas['cod_grupo']; ?>"><?php echo $filas['descripcion']; ?> </option>

                <?php }
               
               
   

   