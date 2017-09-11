<?php
class DB_Class02 {	

	// identificador de conexion y de consultas
	var $Conexion_ID = 0;	
	var $Query_ID= 0;
        var $configDb = array();
        var $Error = '';

        function DB_Class02 ($pDB = 'dbsiga',$pPath = '/var/www/pyme/selectraerp/libs/php/configuracion/'  ){            
  if(!empty($pPath))
            {
                $pPath ='/var/www/pyme/selectraerp/libs/php/configuracion/';
            }

            if (file_exists ( $pPath.$pDB.'.yml' )){
                $this-> configDb = leer_configdb ($pPath.$pDB.'.yml');            
            }else{
                
                $this->Error = 'No Consigio el archivo yml \''.$pPath.$pDB.'.yml\', favor corregir';
                error_log($this->Error, 0);
                echo "Error en Conexión";
                exit;
            }
            
        }

        // Método Constructor: Cada vez que creemos una instancia
	// de esta clase, se debe ejecutar esta función	
	function DB_Init(){            
           $result = $this->DB_Conectar();
           return $result;
	}

        function DB_Iniciar_Transaccion(){
            $result = @pg_query($this->Conexion_ID,"BEGIN");
            if (!$result) {
                    throw new SQLException('Could not begin transaction', pg_last_error($this->Conexion_ID));
            }            
            return $result;
	}

        function DB_Confirmar_Transaccion(){            
            return pg_query($this->Conexion_ID,"COMMIT");
	}
        
        function DB_Cancelar_Transaccion(){            
            return pg_query($this->Conexion_ID,"ROLLBACK");
	}
        
	// Conexión a la base de datos
	function DB_Conectar(){
            
            $this->Conexion_ID = pg_pconnect('host='.$this->configDb["servidor"].' user='.$this->configDb["username"].' password='.$this->configDb["password"].' port='.$this->configDb["puerto"].' dbname='.$this->configDb["db"]);
            //realizar la conexion
            if(!$this->Conexion_ID){
                echo "Error en Conexión. Datos de la Conexión: ".'host='.$this->configDb["servidor"].' user='.$this->configDb["username"].' password='.$this->configDb["password"].' port='.$this->configDb["puerto"].' dbname='.$this->configDb["db"];
                exit;
            }
            return $this->Conexion_ID; //devolver el identificador
            
	}
	
	function DB_Desconectar($conId = '' ){
            
            if ($conId == ''){
                $conId = $this->Conexion_ID;
            }
            
            return pg_close($conId);
	}
	
	// Ejecutar un query
	function DB_Consulta($sql = ""){
            $query="";
            //echo $sql;
            $this->Error = 0;
            if ($sql == "") {
                $this->Error = "No ha especificado una consulta SQL";
                return 0;
            }else
                $query=$sql;

            //ejecutamos la consulta

            try {
              $this->Query_ID = @pg_query($this->Conexion_ID, $query);
              if(!$this->Query_ID){
                      $this->Error = pg_last_error ($this->Conexion_ID);
              }
            throw new Exception($this->Error); //registro el error en el objeto
            }catch (Exception $e) {
              $mensaje=$e->getMessage();
            }

            return $this->Query_ID;
	}

   	function DB_Insertar($tabla, $into, $values){

		$this->Error = 0;
		if ($tabla == "") {
		  $this->Error = "No ha especificado la tabla";
		  return 0;
		}
		if ($into == "") {
		  $this->Error = "No ha especificado los campos";
		  return 0;
		}
		if ($values == "") {
		  $this->Error = "No ha especificado los valores";
		  return 0;
		}
   
	  	$query= "INSERT INTO $tabla ($into) VALUES (".$values.")";

                try {
                    $this->Query_ID = @pg_query($this->Conexion_ID, $query);
                    if(!$this->Query_ID){
                        $this->Error = pg_last_error ($this->Conexion_ID);
                    }
                    throw new Exception($this->Error); //registro el error en el objeto
                }catch (Exception $e) {
                $mensaje=$e->getMessage();
                }

      	return $this->Query_ID;
   	}
	
   	function DB_Modificar($tabla, $set, $where){
            $this->Error = 0;
            if ($tabla == "") {
              $this->Error = "No ha especificado la tabla";
              return 0;
            }
            
            if ($set == "") {
              $this->Error = "No ha especificado las modificaciones";
              return 0;
            }
            
            if ($where == "") {
              $this->Error = "No ha especificado el registro a modificar";
              return 0;
            }
        
            $query= "UPDATE $tabla SET ".$set." WHERE $where";
		
            try {
                $this->Query_ID = @pg_query($this->Conexion_ID, $query);
                if(!$this->Query_ID){
                      $this->Error = pg_last_error ($this->Conexion_ID);
                }
                throw new Exception($this->Error); //registro el error en el objeto
            }catch (Exception $e) {
                $mensaje=$e->getMessage();
            }

            return $this->Query_ID;
   	}

	function DB_num_rows($rs_conn="") {
            if($rs_conn=="") $rs_conn=$this->Query_ID;
                
            $num_rows = pg_num_rows($rs_conn);
                
            return $num_rows;
	}

	function DB_fetch_array($rs_conn=""){
            
            if($rs_conn==""){
                $this->Error = 'no hay un query id favor corregir';
                error_log($this->Error, 0);
            }
            
            return pg_fetch_array($rs_conn);
	}
	
        function DB_Freeres() {
            $this->Conexion_ID = @pg_free_result($this->Query_ID);		
            return $this->Conexion_ID;
        }
}

function leer_configdb($path){    

    

    //$a = getcwd();

    

    $fp = fopen($path,"r");



    $config = array(); 

    while ($linea= fgets($fp,1024)){  

        if (stripos($linea, "servidor:" ) !== false) {

            $config["servidor"] = trim(substr($linea , strlen("servidor:") + 1 ));

        }



        if (stripos($linea, "db:" ) !== false) {      

            $config["db"] = trim(substr($linea , strlen("db:") + 1 ));

        }



        if (stripos($linea, "username:" ) !== false) {      

            $config["username"] = trim(substr($linea , strlen("username:") + 1 ));

        }



        if (stripos($linea, "password:" ) !== false) {

            $config["password"] = trim(substr($linea , strlen("password:") + 1 ));

        }



        if (stripos($linea, "schema:" ) !== false) {      

            $config["schema"] = trim(substr($linea , strlen("schema:") + 1 ));

        }     



        if (stripos($linea, "puerto:" ) !== false){       

            $config["puerto"] = trim(substr($linea , strlen("puerto:") + 1 ));

        }      

    }

    return $config;

}

?>
