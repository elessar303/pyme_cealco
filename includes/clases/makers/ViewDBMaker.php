<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewDBMaker
 *
 * @author Sebastian
 */
require_once '../BDControlador.php';

class ViewDBMaker {
    private $table;
    private $modulo;
    private $conn;
    
    public function Init($bd,$user,$pass,$table,$modulo) {
        $this->conn = new BDControlador();
        $this->conn->conexionRemota("localhost", $user, $pass, $bd);
        $this->conn->conectar();
        $this->table = $table;
        $this->modulo = $modulo;
    }
    
    public function makeFields(){
        $classNameAux = explode("_", $this->table);
        $className = "";
        foreach ($classNameAux as $value) {
            $className .= strtoupper(substr($value, 0,1)).substr($value, 1);
        }
        
        if(!file_exists("../../js/gui/fields/".$this->table.".js")){
            if($file = fopen("../../js/gui/fields/".$this->table.".js", "a")){
                $this->conn->setQuery("SHOW COLUMNS FROM ".$this->table);
                $result = $this->conn->ejecutarSql();
                fwrite($file, "var fields_".$this->table." = [\n");
                
                $fields = "";
                while ( $item = $this->conn->fetch($result)) {
                    if( strpos($item['Type'], "int") !== FALSE){
                        $fields .= "\t\t{ mapping:'".$item['Field']."', name:'".$item['Field']."', type: 'int'},\n";                        
                    }
                    else if( strpos($item['Type'], "varchar") !== FALSE){
                        $fields .= "\t\t{ mapping:'".$item['Field']."', name:'".$item['Field']."', type: 'string'},\n";
                    }
                    else if( strpos($item['Type'], "decimal") !== FALSE){
                        $fields .= "\t\t{ mapping:'".$item['Field']."', name:'".$item['Field']."', type: 'float'},\n";
                    }
                    else if( strpos($item['Type'], "date") !== FALSE){
                        $fields .= "\t\t{ mapping:'".$item['Field']."', name:'".$item['Field']."', type: 'date', dateFormat: 'Y-m-d'},\n";
                    }
                    else if( strpos($item['Type'], "timestamp") !== FALSE){
                        $fields .= "\t\t{ mapping:'".$item['Field']."', name:'".$item['Field']."', type: 'date',dateFormat:'Y-m-d g:i:s'},\n";
                    }
                }
                $fields = substr($fields, 0,-2);
                fwrite($file, $fields."];");
                fclose($file);
            }
        }        
    }
    
    public function makeForm(){
        $formNameAux = explode("_", $this->table);
        $formName = "";
        $className = "";
        $ind = 0;
        foreach ($formNameAux as $value) {
            if($ind == 0){
                $formName .= strtolower(substr($value, 0,1)).substr($value, 1);
            }
            else{
                $formName .= strtoupper(substr($value, 0,1)).substr($value, 1);
            }
            $className .= strtoupper(substr($value, 0,1)).substr($value, 1);
            $ind++;
        }
        $fileName = "../../js/gui/".$this->modulo."/".$formName."Form.js";
        if(!file_exists($fileName)){
            if($file = fopen($fileName, "a")){
                $this->conn->setQuery("SHOW COLUMNS FROM ".$this->table);
                $result = $this->conn->ejecutarSql();
                fwrite($file, $formName."Form = function(){\n");
                fwrite($file, "\tvar tabForm = null;\n");
                fwrite($file, "return{\n");
                fwrite($file, "\taceptar:function(){\n");
                fwrite($file, "\t\t//@TODO operacion del boton aceptar\n");
                fwrite($file, "\t\ttabForm.form.submit({\n");
                fwrite($file, "\t\t\tparams:{\n");
                fwrite($file, "\t\t\taccion: 'Accion/".$className."/Save',operacion:'add',output: 'json'},\n");
                fwrite($file, "\t\t\twaitMsg:'Enviando Datos...Espere',\n");
                fwrite($file, "\t\t\treset: true,\n");
                fwrite($file, "\t\t\tfailure: function(form_instance, action) {},\n");
                fwrite($file, "\t\t\tsuccess: function(form_instance, action) {}\n\t\t});\n");
                
                fwrite($file, "\t},\n");
                fwrite($file, "\tcancelar:function(){\n");
                fwrite($file, "\t\t//@TODO operacion del boton cancelar\n");
                fwrite($file, "\t},\n");
                fwrite($file, "\tInit:function(){\n");
                $fieldsInForm = "";
                $numFields = $this->conn->numero_filas($result);
                while ( $item = $this->conn->fetch($result)) {
                    $fieldNameAux = explode("_", $item['Field']);
                    $fieldName = "";
                    $ind = 0;
                    foreach ($fieldNameAux as $value) {
                        if($ind == 0){
                            $fieldName .= strtolower(substr($value, 0,1)).substr($value, 1);
                        }
                        else{
                            $fieldName .= strtoupper(substr($value, 0,1)).substr($value, 1);
                        }
                        $ind++;
                    }
                    $fieldsInForm .= "\t\t\t".$fieldName."Field,\n";
                    if( strpos($item['Type'], "int") !== FALSE || strpos($item['Type'], "decimal") !== FALSE) {
                        fwrite($file, "\t\tvar ".$fieldName."Field = new Ext.form.NumberField({\n");
                        fwrite($file, "\t\t\tstyle:{textAlign:'right'},\n");
                        fwrite($file, "\t\t\tname:'".$item['Field']."',\n");
                        fwrite($file, "\t\t\tid:'".$item['Field']."',\n");
                        fwrite($file, "\t\t\tfieldLabel:'".$item['Field']."',\n");
                        fwrite($file, "\t\t\tanchor:'80%',\n");
                        if($item['Default'] == NULL){
                            fwrite($file, "\t\t\tallowBlank:true,\n");
                        }
                        else{
                            fwrite($file, "\t\t\tallowBlank:false,\n");
                        }
                        if($item['Key'] == "PRI"){
                            fwrite($file, "\t\t\treadOnly:true,\n");
                        }
                        else{
                            fwrite($file, "\t\t\treadOnly:false,\n");
                        }
                        if(strpos($item['Type'], "decimal") !== FALSE){
                            $fieldTypeAux = explode(",", $item['Type']);
                            $fieldTypeAux2 = explode(")", $fieldTypeAux[1]);
                            fwrite($file, "\t\t\tallowDecimals:true,\n");
                            fwrite($file, "\t\t\tdecimalPrecision:".$fieldTypeAux2[0]."\n");
                        }
                        else{
                            fwrite($file, "\t\t\tallowDecimals:false\n");
                        }
                        fwrite($file, "\t\t});\n");
                    }
                    else if( strpos($item['Type'], "varchar") !== FALSE){
                        fwrite($file, "\t\tvar ".$fieldName."Field = new Ext.form.TextField({\n");
                        fwrite($file, "\t\t\tname:'".$item['Field']."',\n");
                        fwrite($file, "\t\t\tfieldLabel:'".$item['Field']."',\n");
                        fwrite($file, "\t\t\tid:'".$item['Field']."',\n");
                        fwrite($file, "\t\t\tanchor:'80%',\n");
                        if($item['Default'] == NULL){
                            fwrite($file, "\t\t\tallowBlank:true\n");
                        }
                        else{
                            fwrite($file, "\t\t\tallowBlank:false\n");
                        }
                        fwrite($file, "\t\t});\n");
                    }
                    else if( strpos($item['Type'], "date") !== FALSE || strpos($item['Type'], "timestamp") !== FALSE){
                        fwrite($file, "\t\tvar ".$fieldName."Field = new Ext.form.DateField({\n");
                        fwrite($file, "\t\t\tname:'".$item['Field']."',\n");
                        fwrite($file, "\t\t\tfieldLabel:'".$item['Field']."',\n");
                        fwrite($file, "\t\t\tid:'".$item['Field']."',\n");
                        fwrite($file, "\t\t\tanchor:'40%',\n");
                        fwrite($file, "\t\t\tformat:'Y-m-d',\n");
                        fwrite($file, "\t\t\tallowBlank:false\n");
                        fwrite($file, "\t\t});\n");
                    }
                    else if( strpos($item['Type'], "enum") !== FALSE){
                        //@TODO crear combo
                    }
                }
                fwrite($file, "\t\ttabForm = new Ext.FormPanel({\n");
                fwrite($file, "\t\t\turl:'../../../includes/clases/controladores/ControladorApp.php',\n");
                fwrite($file, "\t\t\tlabelAlign: 'left',\n");
                fwrite($file, "\t\t\tregion: 'center',\n");
                fwrite($file, "\t\t\tbodyStyle:'padding:5px',\n");
                fwrite($file, "\t\t\tid: 'form-crud-".$this->table."',\n");
                fwrite($file, "\t\t\titems:[\n");
                $colWidth = 1;
                if($numFields > 20){
                    $colWidth = 0.5;
                }
                $fieldsInForm = substr($fieldsInForm, 0,-2);
                fwrite($file, "\t\t\t\t{layout:'column',border:false,items:[\n");        
                fwrite($file, "\t\t\t\t{columnWidth:".$colWidth.",layout:'form',border:false,labelAlign:'left',\n");        
                fwrite($file, "\t\t\t\titems:[\n");        
                fwrite($file, "\t\t\t\t".$fieldsInForm."]}\n");
                
                if($numFields > 20){
                    fwrite($file, "\t\t\t\t,{columnWidth:".$colWidth.",layout:'form',border:false,labelAlign:'left',\n");        
                    fwrite($file, "\t\t\t\titems:[\n");        
                    fwrite($file, "\t\t\t\t]}\n");
                }
                fwrite($file, "\t\t\t\t]}],\n");
                
                fwrite($file, "\t\t\t\tbuttons:[\n");        
                fwrite($file, "\t\t\t\t{text: 'Aceptar',id:'btn-aceptar',icon:'../../../includes/imagenes/icons/accept.png',\n");        
                fwrite($file, "\t\t\t\tlisteners :{click : function( el, e ){".$formName."Form.aceptar();}}},\n");
                fwrite($file, "\t\t\t\t{text: 'Cancelar',id:'btn-cancelar',icon:'../../../includes/imagenes/icons/cross.png',\n");        
                fwrite($file, "\t\t\t\tlisteners :{click : function( el, e ){".$formName."Form.cancelar();}}}]\n");
                
                fwrite($file, "\t\t\t\t});\n");
                
                fwrite($file, "\t\t".$formName."Win = new Ext.Window({\n");
                fwrite($file, "\t\t\tcloseAction:'hide',\n");
                fwrite($file, "\t\t\tdraggable: false,\n");
                fwrite($file, "\t\t\tmodal: false,\n");
                fwrite($file, "\t\t\tid: '".$formName."-win',\n");
                fwrite($file, "\t\t\tlayout: 'border',\n");
                fwrite($file, "\t\t\tregion:'center',\n");
                fwrite($file, "\t\t\tmaximizable:false,\n");
                fwrite($file, "\t\t\tminHeight: 250,\n");
                fwrite($file, "\t\t\tminWidth: 500,\n");
                fwrite($file, "\t\t\tplain: false,\n");
                fwrite($file, "\t\t\tresizable: true,\n");
                fwrite($file, "\t\t\titems: [ tabForm],\n");
                fwrite($file, "\t\t\tconstrainHeader:true,\n");
                fwrite($file, "\t\t\ttitle: '<img src=\"../../../includes/imagenes/icons/application.png\"/>&nbsp;Ventana Crud',\n");
                fwrite($file, "\t\t\theight: 700,\n");
                fwrite($file, "\t\t\twidth: 900,\n");
                fwrite($file, "\t\t\tclosable:false,\n");
                fwrite($file, "\t\t\trenderTo: 'container-window'\n");
                fwrite($file, "\t\t\t});\n");
                    
                fwrite($file, $formName."Win.show();\n");
                
                fwrite($file, "}};}();\n");
                fwrite($file, "Ext.onReady(".$formName."Form.Init, ".$formName."Form, true);");
                fclose($file);
            }
        }
    }
    
    public function makePropertyList(){
        $formNameAux = explode("_", $this->table);
        $formName = "";
        $className = "";
        $objectView = "";
        $ind = 0;
        foreach ($formNameAux as $value) {
            if($ind == 0){
                $formName .= strtolower(substr($value, 0,1)).substr($value, 1);
            }
            else{
                $formName .= strtoupper(substr($value, 0,1)).substr($value, 1);
            }
            $className .= strtoupper(substr($value, 0,1)).substr($value, 1);
            $ind++;
        }
        $fileName = "../../js/gui/".$this->modulo."/".$formName."PropertyList.js";
        if(!file_exists($fileName)){
            if($file = fopen($fileName, "a")){
                $this->conn->setQuery("SHOW COLUMNS FROM ".$this->table);
                $result = $this->conn->ejecutarSql();
                $objectView = $formName."PropertyList";
                fwrite($file,$objectView."  = function(){\n");
                fwrite($file, "\tvar tabForm = null;\n");
                fwrite($file, "\tvar store = null;\n");
                fwrite($file, "return{\n");
                fwrite($file, "\tidWin:function(){return '".$objectView."-win';},\n");
                fwrite($file, "\tmoduleFields: fields_".$this->table.",\n");
                fwrite($file, "\tformload:function(){\n\t\tstore.load();\n\t},\n");
                fwrite($file, "\tmodificar:function(){\n\t\ttabForm.form.submit({\n
                \t\t\tparams:{\n
                    \t\t\t\taccion: 'Accion/".$className."/Save',\n 
                    \t\t\t\t//@TODO Colocar parametros con el siguiente formato\n
                    \t\t\t\t//nombre_campo:row.data.nombre_campo (nombre_campo es el mismo nombre de la bd)\n
                    \t\t\t\toutput: 'json'
                \t\t\t},\n
                \t\t\twaitMsg:'Guardando...',\n
                \t\t\treset: true,\n
                \t\t\tfailure: function(form_instance, action) {\n
                    \t\t\t\tExt.MessageBox.alert('Error', 'Ha ocurrido un error de Procesamiento');\n
                \t\t\t},\n
                \t\t\tsuccess: function(form_instance, action) {\n
                    \t\t\t\tExt.getCmp('grid-list-".$this->table."').store.reload();\n
                \t\t\t}\n
                \t\t});\n\t},\n");
                fwrite($file, "\tagregar:function(){\n\t\ttabForm.form.submit({\n
                \t\t\tparams:{\n
                    \t\t\t\taccion: 'Accion/".$className."/Save',\n 
                    \t\t\t\t//@TODO Colocar parametros con el siguiente formato\n
                    \t\t\t\t//nombre_campo:row.data[0].nombre_campo (nombre_campo es el mismo nombre de la bd)\n
                    \t\t\t\toutput: 'json'
                \t\t\t},\n
                \t\t\twaitMsg:'Guardando...',\n
                \t\t\treset: true,\n
                \t\t\tfailure: function(form_instance, action) {\n
                    \t\t\t\tExt.MessageBox.alert('Error', 'Ha ocurrido un error de Procesamiento');\n
                \t\t\t},\n
                \t\t\tsuccess: function(form_instance, action) {\n
                    \t\t\t\tExt.getCmp('grid-list-".$this->table."').store.reload();\n
                \t\t\t}\n
                \t\t});\n\t},\n");
                
                fwrite($file, "\tinitRecordDef : function(){\n\t\treturn new Ext.data.Record.create(".$objectView.".moduleFields);\n\t},\n");
                fwrite($file, "\tinitColumnModel : function(){\n\t\treturn new Ext.grid.ColumnModel([\n\t\tnew Ext.grid.RowNumberer(),\n");
                
                $columns = "";
                while ( $item = $this->conn->fetch($result)) {
                    $columnNameAux = explode("_", $item['Field']);
                    $columnName = "";
                    foreach ($columnNameAux as $value) {
                        $columnName .= strtoupper(substr($value, 0,1)).substr($value, 1);                        
                    }
                    if( strpos($item['Type'], "int") !== FALSE){
                        $columns .= "\t\t{\n\t\t\tid: 'col_".$item['Field']."',\n\t\t\theader: '".$columnName."',\n\t\t\tdataIndex: '".$item['Field']."',\n";
                        $columns .= "\t\t\teditor: {\n\t\t\t\txtype: 'numberfield',\n\t\t\t\tallowBlank: false,\n\t\t\t\tallowDecimals:false,\n\t\t\t\tselectOnFocus :true\n\t\t\t}},\n";
                    }
                    else if( strpos($item['Type'], "varchar") !== FALSE){
                        $columns .= "\t\t{\n\t\t\tid: 'col_".$item['Field']."',\n\t\t\theader: '".$columnName."',\n\t\t\tdataIndex: '".$item['Field']."',\n";
                        $columns .= "\t\t\teditor: {\n\t\t\t\txtype: 'textfield',\n\t\t\t\tallowBlank: false,\n\t\t\t\tselectOnFocus :true\n\t\t\t}},\n";
                    }
                    else if( strpos($item['Type'], "decimal") !== FALSE){
                        $fieldTypeAux = explode(",", $item['Type']);
                        $fieldTypeAux2 = explode(")", $fieldTypeAux[1]);
                        $columns .= "\t\t{\n\t\t\tid: 'col_".$item['Field']."',\n\t\t\theader: '".$columnName."',\n\t\t\tdataIndex: '".$item['Field']."',\n";
                        $columns .= "\t\t\teditor: {\n\t\t\t\txtype: 'numberfield',\n\t\t\t\tallowBlank: false,\n\t\t\t\tdecimalPrecision:".$fieldTypeAux2[0].",\n\t\t\t\tselectOnFocus :true\n\t\t\t}},\n";
                    }
                    else if( strpos($item['Type'], "date") !== FALSE){
                        $columns .= "\t\t{\n\t\t\tid: 'col_".$item['Field']."',\n\t\t\theader: '".$columnName."',\n\t\t\tdataIndex: '".$item['Field']."',\n";
                        $columns .= "\t\t\teditor: {\n\t\t\t\txtype: 'datefield',\n\t\t\t\tallowBlank: false,\n\t\t\t\tdateFormat: 'Y-m-d',\n\t\t\t\tselectOnFocus :true\n\t\t\t}},\n";
                    }
                    else if( strpos($item['Type'], "timestamp") !== FALSE){
                        $columns .= "\t\t{\n\t\t\tid: 'col_".$item['Field']."',\n\t\t\theader: '".$columnName."',\n\t\t\tdataIndex: '".$item['Field']."',\n";
                        $columns .= "\t\t\teditor: {\n\t\t\t\txtype: 'datefield',\n\t\t\t\tallowBlank: false,\n\t\t\t\tdateFormat: 'Y-m-d g:i:s',\n\t\t\t\tselectOnFocus :true\n\t\t\t}},\n";
                    }
                }
                $columns = substr($columns, 0,-2);
                fwrite($file, $columns."])},\n");
                
                fwrite($file, "\tInit:function(){\n");
                fwrite($file, "\t\tstore = new Ext.data.GroupingStore({\n\t\t\tautoLoad:true,\n\t\t\tproxy:new Ext.data.HttpProxy({url:'../../../includes/clases/controladores/ControladorApp.php'}),\n\t\t\t");
                fwrite($file, "baseParams:{\n\t\t\t\taccion: 'Accion/".$className."/Get_List',\n\t\t\t\touput:'json'\n\t\t\t},\n\t\treader: new Ext.data.JsonReader({\n\t\t\ttotalProperty: 'totalCount',\n\t\t\troot: 'matches'},\n\t\t".$objectView.".initRecordDef())});\n\n");
                
                fwrite($file, "\t\tvar editor = new Ext.ux.grid.RowEditor({saveText: 'Update'});\n\n");
                
                fwrite($file, "\t\tvar grid = new Ext.grid.GridPanel({\n");
                fwrite($file, "\t\t\tstore: store,\n\t\t\tid:'grid-list-".$this->table."',\n\t\t\theight: 680,\n\t\t\twidth: 600,\n\t\t\tregion:'center',\n\t\t\t\n\t\t\tmargins: '0 5 5 5',\n\t\t\tplugins: [editor],\n");
                fwrite($file, "\t\t\tview: new Ext.grid.GroupingView({markDirty: false}),\n");
                fwrite($file, "\t\t\tcm: ".$objectView.".initColumnModel(),\n");
                fwrite($file, "\t\t\ttbar: [{\n\t\t\ticon: 'imagenes/icons/add.png',\n\t\t\ttext: 'Agregar',\n\t\t\thandler: function(){\n\t\t\t\tvar e = new Configuracion({\n");
                
                $result = $this->conn->ejecutarSql();
                $fieldsInObj = "";
                
                while ( $item = $this->conn->fetch($result)) {
                    if( strpos($item['Type'], "int") !== FALSE){
                        $fieldsInObj .= "\t\t\t".$item['Field'].":0,\n";
                    }
                    else if( strpos($item['Type'], "varchar") !== FALSE){
                        $fieldsInObj .= "\t\t\t".$item['Field'].":'',\n";
                    }
                    else if( strpos($item['Type'], "decimal") !== FALSE){
                        $fieldsInObj .= "\t\t\t".$item['Field'].":0.0,\n";
                    }
                    else if( strpos($item['Type'], "date") !== FALSE){
                        $fieldsInObj .= "\t\t\t".$item['Field'].":new Date(),\n";
                    }
                    else if( strpos($item['Type'], "timestamp") !== FALSE){
                        $fieldsInObj .= "\t\t\t".$item['Field'].":new Date(),\n";
                    }
                }
                $fieldsInObj = substr($fieldsInObj, 0,-2);
                
                fwrite($file, $fieldsInObj."\n});\n\n\t\t\teditor.stopEditing();\n\t\t\tstore.insert(0, e);\n\t\t\tgrid.getView().refresh();\n\t\t\tgrid.getSelectionModel().selectRow(0);\n\t\t\teditor.startEditing(0);}}]});\n");
                
                fwrite($file, "\n\t\tstore.on('add', ".$objectView.".agregar, store);\n\t\tstore.on('update', ".$objectView.".modificar, store);");
                
                fwrite($file, "\n\t\ttabForm = new Ext.FormPanel({\n
                    \t\t\tid: 'list-".$this->table."-form',\n
                    \t\t\tbodyStyle:'padding:5px',\n
                    \t\t\theight: 680,\n
                    \t\t\twidth: 600,\n
                    \t\t\tlayout: 'form',\n
                    \t\t\titems: [grid]\n
                \t\t});\n");
                
                fwrite($file, "\t\t".$objectView."Win = new Ext.Window({\n");
                fwrite($file, "\t\t\tcloseAction:'hide',\n");
                fwrite($file, "\t\t\tdraggable: false,\n");
                fwrite($file, "\t\t\tmodal: false,\n");
                fwrite($file, "\t\t\tid: '".$objectView."-win',\n");
                fwrite($file, "\t\t\tlayout: 'fit',\n");
                fwrite($file, "\t\t\tregion:'center',\n");
                fwrite($file, "\t\t\tmaximizable:false,\n");
                fwrite($file, "\t\t\tplain: false,\n");
                fwrite($file, "\t\t\tresizable: true,\n");
                fwrite($file, "\t\t\titems: [ tabForm],\n");
                fwrite($file, "\t\t\tconstrainHeader:true,\n");
                fwrite($file, "\t\t\ttitle: '<img src=\"../../../includes/imagenes/icons/application.png\"/>&nbsp;Ventana Crud',\n");
                fwrite($file, "\t\t\theight: 680,\n");
                fwrite($file, "\t\t\twidth: 600,\n");
                fwrite($file, "\t\t\tclosable:false,\n");
                fwrite($file, "\t\t\trenderTo: 'container-window'\n");
                fwrite($file, "\t\t\t});\n");
                    
                fwrite($file, $objectView."Win.show();\n");
                
                fwrite($file, "}};}();\n");
                fwrite($file, "Ext.onReady(".$objectView.".Init, ".$objectView.", true);");
                fclose($file);
            }
        }
    }
}

$ob = new ViewDBMaker();
$ob->Init("administrativo", "root", "armadillo01", $_GET['table'],$_GET['module']);
$ob->makeFields();
$guiTypes = explode("/",$_GET['guiTypes']);
foreach ($guiTypes as $value) {
    if($value == "Form"){
        $ob->makeForm();
    }
    else if($value == "PropertyList"){
        $ob->makePropertyList();
    }
    else if($value == "CrudList"){
        $ob->makeForm();
    }
}