<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" content="Lucas Sosa" />
        <title></title>
        {include file="snippets/inclusionesFpdUbicacion.tpl"}
        {literal}
            <script type="text/javascript">//<![CDATA[
            $(document).ready(function(){
                function cargarUbicaciones() {    
        idAlmacen=$("#almacen_entrada").val();     
        if(idAlmacen!=0){
        $.ajax({
            type: 'POST',
            data: 'opt=cargaUbicacion&idAlmacen='+idAlmacen,
            url: '../../libs/php/ajax/ajax.php',
            beforeSend: function() {
                $("#ubicacion").find("option").remove();
                $("#ubicacion").append("<option value=''>Cargando..</option>");
            },
            success: function(data) {
                $("#ubicacion").find("option").remove();
                this.vcampos = eval(data);
                 $("#ubicacion").append("<option value=''>Seleccione..</option>");
                for (i = 0; i <= this.vcampos.length; i++) {
                    $("#ubicacion").append("<option value='"+this.vcampos[i].id+"'>" + this.vcampos[i].descripcion + "</option>");
                }
            }
        });
        }//fin el if
        else{
             $("#ubicacion").find("option").remove();
             $("#ubicacion").append("<option value=''>Seleccione..</option>");
            }
        }
        $("#almacen_entrada").change(function(){
            cargarUbicaciones();
        });
                
            });
            //]]>
            </script>
        {/literal}
    </head>
    <body>
        <form name="formulario" id="formulario" method="get"  target="_blank" action="../../fpdf/realizar_toma_fisica_3.php">
            <div id="datosGral" class="x-hide-display">
                {include file = "snippets/regresar_solo.tpl"}
                <input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}"/>
                <input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}"/>
                <input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}"/>
                <input type="hidden" name="cant_fechas" id="cant_fechas" value="2"/>
                <input type="hidden" name="ordenar_por" id="ordenar_por" value="1"/>
                <input type="hidden" name="tiene_filtro" id="tiene_filtro" value="1"/>
                <table style="width:100%; background-color:white;">
                    <thead>
                        <tr>
                            <th colspan="6" class="tb-head" style="text-align:center;">
                                LOS CAMPOS MARCADOS CON&nbsp;** SON OBLIGATORIOS
                            </th>
                        </tr>
                    </thead>
                    <tbody>                      
                       
                <tr>
                    <td class="label">Almacen</td>
                    <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                    <select  name="almacen_entrada" id="almacen_entrada" class="form-text">
                     <option value="0">Seleccione</option>              
                        {html_options output=$option_output_almacen values=$option_values_almacen}
                    </select>
                    </td>
                </tr>
                <tr>
                     <td class="label">CATEGORIA</td>
                     <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="categoria" id="categoria" style="width:200px;" class="form-text">
                                    <option value="0">Todos</option>
                               
                                {html_options values=$option_values_departamento output=$option_output_departamento}
                                
                                </select>
                       </td>
                             
                       <td class="label">Ubicacion</td>
                        <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <select name="ubicacion" id="ubicacion" style="width:200px;" class="form-text">
                                    <option value="0">Seleccione...</option>              
                                <!--{html_options values=$option_values_id_ubi output=$option_values_nombre_ubi}-->
                                
                            </select>
                           </td>

                </tr>

                <tr>
                            <td class="label">C&Oacute;DIGO DE BARRAS</td>
                              <td >
                                <input type="text" name="codigo_barras" id="codigo_barras" style="float: left;" class="form-text" />
                                <input type="button" id="buscar" name="buscar" value="Buscar" style="float: left;" />
                              </td>
                            <td class="label">Formato Reporte</td>
                            <td colspan="5" style="padding-top:2px; padding-bottom: 2px;">
                                <div id="formato">
                                  <!--   <input type="radio" id="radio1" name="radio" value="0" checked/><label for="radio1">Hoja de C&aacute;lculo</label> -->
                                   
                                    <input type="radio" id="radio2" name="radio" value="1" checked /><label for="radio2">Formato PDF</label>
                                </div>
                            </td>

                </tr>
                        <tr class="tb-tit">
                            <td colspan="6">
                                <input type="submit" id="aceptar" name="aceptar" value="Enviar"  />
                                <input type="button" name="cancelar" value="Cancelar" onclick="javascript:document.location.href='?opt_menu={$smarty.get.opt_menu}';" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </body>
</html>