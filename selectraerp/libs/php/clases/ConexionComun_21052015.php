<?php

class ConexionComun {

    protected $rCampos = "";
    protected $db;
    protected $rs;
    protected $instruccion;
    public $errorTransaccion = 1;

    function __construct() {
        $this->db = NewADOConnection(GESTOR_DATABASE);
        $this->db->Connect(SERVIDOR, USUARIO, CLAVE, BASEDEDATOS);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->db->debug = false;
    }

    function getInsertID() {
        return $this->db->Insert_ID();
    }

    /**
     * Inicia una transacción.
     */
    function BeginTrans() {
        $this->db->Execute("SET AUTOCOMMIT=0");
        $this->db->Execute("START TRANSACTION");
    }

    /**
     * Captura el Query en la transacción.
     */
    function ExecuteTrans($sql) {
        if ($this->db->Execute($sql) == false) {
            $this->errorTransaccion = 0;
            echo $this->db->ErrorMsg() . "<br>" . $sql;
            exit;
        }
    }

    /**
     * <p>Ejecuta los Query's de la transacción.
     * Si todas las operaciones resultaron exitosas
     * entonces se realiza el <b>COMMIT</b> de lo contrario <b>ROLLBACK</b>
     * y finalmente se hace SET AUTOCOMMIT = 1
     * </p>
     */
    function CommitTrans($ok) {
        if ($ok != 1 && $ok != 0) {
            echo "Error de parametro en la función Commitrans";
            exit;
        }

        if ($ok == 1) {
            $this->db->Execute("COMMIT");
        } else {
            $this->db->Execute("ROLLBACK");
        }
        $this->db->Execute("SET AUTOCOMMIT=1");
    }

    /**
     * Retorna la cantidad de filas que genero la consulta sql.
     * @return int
     */
    function getFilas() {
        if (!$this->rs) {
            return 0;
        } else {
            return $this->rs->RecordCount();
        }
    }

    /**
     * Función para realizar consultas sql a la base de datos.
     * @param string $sql Consulta SQL que se desea veriticar. 
     * @return string
     */
    function ObtenerFilasBySqlSelect($sql) {
         $this->instruccion = $sql;
        $this->rs = $this->db->Execute($this->instruccion);
        if (!$this->rs) {
            //return echo "Error: ".$this->db->ErrorMsg();
            //$this->rCampos = -1;
            return "";
        } else {
            
            $this->rCampos = $this->rs->GetRows();
        }
        return $this->rCampos;
    }

    function Execute2($sql) {
        if ($this->db->Execute($sql) == false) {

            $pagina = "";

            $error = $this->db->ErrorMsg();
            $instruccion = "
  SELECT
            opt_menu.cod_modulo as id_optmenu,
            opt_seccion.cod_modulo as id_optseccion,
            opt_menu.nom_menu as descripcion_optmenu,
            opt_seccion.nom_menu as descripcion_optseccion,
            opt_seccion.archivo_php,
            opt_seccion.archivo_tpl,
            opt_seccion.orden orden2,
            opt_seccion.img_ruta,
	    subseccion.descripcion,
             subseccion.opt_subseccion ,
		subseccion.archivo_php as archivo_php_subseccion,
		subseccion.archivo_tpl as archivo_tpl_subseccion
            FROM modulos opt_menu inner join modulos opt_seccion
             on opt_menu.cod_modulo = opt_seccion.cod_modulo_padre inner join
             subseccion on subseccion.cod_seccion = opt_seccion.cod_modulo
               where opt_menu.cod_modulo = '" . $_GET["opt_menu"] . "' and
               opt_seccion.cod_modulo = '" . $_GET["opt_seccion"] . "' and
                   subseccion.opt_subseccion = '" . $_GET["opt_subseccion"] . "'
    ";
            $campos = $this->ObtenerFilasBySqlSelect($instruccion);

            $pagina .= "<html>";
            $pagina .= "<body style=\"background-color:#f8f8f8\">";
            $pagina .= "<div  style=\"background-color:#dcdedb; border: 1px solid black;-moz-border-radius: 8px;padding:5px; margin-left: 20%;margin-right: 20%;margin-top:5%;   font-size: 13px; \">";
            $pagina .= "<img src=\"../../libs/imagenes/configuracion.png\"> <b>Disculpe esta operacion esta permitida:</b> <br>
        <span style='color:red;padding-left:30px;'><img src=\"../../libs/imagenes/ico_note.gif\"> " . $error . "</span><br>
        <span style='color:red;padding-left:30px;'><img src=\"../../libs/imagenes/ico_note.gif\"> Consulta SQL: " . $sql . "</span><br>
            ";
            $pagina .= "<hr><span style=\"color:#1e6602\">Para mas información contacte al administrador.</span>";
            if (count($campos) > 0)
                $pagina .= "<br><span style=\"color:red\"><img style=\"border:none;\" src=\"../../libs/imagenes/ico_list.gif\"> Detalle del error:</span><br><b style=\"padding-left:30px;\"><img src=\"../../libs/imagenes/ico_search.gif\"> Modulo:</b> " . $campos[0]["descripcion_optmenu"] . " - <b>Sección:</b> " . $campos[0]["descripcion_optseccion"] . " >> <b>" . $campos[0]["opt_subseccion"] . ":</b> " . $campos[0]["descripcion"];
            $pagina .= "<hr><br><br><a style=\"text-decoration:none;\" href='?opt_menu=" . $_GET["opt_menu"] . "&opt_seccion=" . $_GET["opt_seccion"] . "'><img style=\"border:none;\" src=\"../../libs/imagenes/ico_back.gif\"> Volver</a>";
            $pagina .= "</div>";
            $pagina .= "</body>";
            $pagina .= "</html>";
            
            echo $pagina;
            exit;
        }else {
            return 1;
        }
    }

    /*
     * Function para mostar consulta sql que se esta procesando para la linea
     * en cuestion.
     */

    function MostarSQL() {
        echo $this->instruccion . "<br>";
    }
    
    
    public function fetch($resultado){
		//$resultado->fetch_row();
		//MYSQLI_NUM
		//MYSQLI_BOTH
		//return $this->resultSet->fetch_array(MYSQLI_ASSOC);
            $Row = null;
            if($this->driver == "mysql")
                $Row = $resultado->fetch_array(MYSQLI_ASSOC);
                        
            return $Row;
	}
        
        
        
        
        
        
        
        
        
        
        
        public function conexionRemota_central($host,$user,$pass,$bd){
           
		$this->db = NewADOConnection(GESTOR_DATABASE);
                $this->db->Connect($host, $user, $pass, $bd,true);
                $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
                $this->db->debug = false;
                
		//$this->conectar();	
		
	}
        
        public function cerrar(){
            
            $this->db->Disconnect();
        }
        
        
        

}




?>