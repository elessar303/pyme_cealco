<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Reporte Transmision Puntos (Rango).xls");

include('../../generalp.config.inc.php');
include('../../general.config.inc.php');
include('config_reportes.php');

/*AGREGADOS PARA PRUEBAS
$punto = $_GET["punto"];
$estado = $_GET["estados"];
//FIN DE LOS AGREGADOS*/

$desde = $_GET["fecha1"];
$hasta = $_GET["fecha2"];
$dias = $_GET["dias"];
$BD_central=DB_REPORTE_CENTRAL;
$BD_selectrapyme=DB_SELECTRA_FAC;


$sql = "SELECT a.codigo_siga_punto, a.nombre_punto, b.nombre_estado FROM $BD_central.puntos_venta a, $BD_central.estados b WHERE a.codigo_estado_punto=b.codigo_estado AND a.estatus='A'";

if($_GET['punto']!="0"){
  $sql.=" and a.codigo_siga_punto='".$_GET['punto']."'";
}      
if($_GET['estado']!="0"){
  $sql.=" and b.codigo_estado='".$_GET['estado']."'";
} 
$sql.=" ORDER BY b.nombre_estado, a.codigo_siga_punto";
$conn = new ConexionComun();
$puntos=$conn->ObtenerFilasBySqlSelect($sql);
?>
<table  width="100%" border="1" align="center" cellpadding="1" cellspacing="0">
                        <tr bgcolor="#5084A9">
                            <td colspan="11" align="center"><b><font color="white">REPORTE DE TRANSMISION DESDE: <?php echo $desde." HASTA: ".$hasta  ?></font></b></td>    
                        </tr>
                        <tr bgcolor="#5084A9">
                            <td width="3%" align="center"><b><font color="white">N°</font></b></td>
                            <td width="5%" align="center"><b><font color="white">ESTADO</font></b></td>
                            <td width="5%" align="center"><b><font color="white">CODIGO SIGA</font></b></td>
                            <td width="15%" align="center"><b><font color="white">PUNTO</font></b></td>

                            <?php
                            $desde_while=$desde;
                            while ( $desde_while<=$hasta) {
                            ?>
                            <td width="5%" align="center"><b><font color="white"><?php echo $desde_while ?></font></b></td>
                            <?php
                            $desde_while = strtotime ( '+1 day' , strtotime ( $desde_while ) ) ;
                            $desde_while = date ( 'Y-m-d' , $desde_while );
                            }
                            ?>
                            <td width="5%" align="center"><b><font color="white">% de Transmisi&oacute;n</font></b></td>
                            
                        </tr>
                        <?php
                    $j=1;
                    $cont_total=0;
                    foreach ($puntos as $lista ) {
                    ?>
                    <tr>
                    <td align="center"><?php echo $j; ?></td>
                    <td align="center"><?php echo $lista["nombre_estado"]; ?></td>
                    <td align="center"><?php echo $lista["codigo_siga_punto"]; ?></td>
                    <td align="center"><?php echo $lista["nombre_punto"]; ?></td>

                    <?php
                    $desde_while=$desde;
                    $cont=0;
                    while ( $desde_while<=$hasta) {
                    $cont_total=$cont_total+1;
                    $sql_ver="SELECT codigo_siga FROM $BD_central.reporte_ventas_historico WHERE codigo_siga='".$lista["codigo_siga_punto"]."' AND fecha='".$desde_while."'";
                    $puntos_ver = $conn->ObtenerFilasBySqlSelect($sql_ver);

                    if(count($puntos_ver)==0){
                    ?>

                    <td align="center">X</td>

                    <?php
                    }else{
                        $cont=$cont+1;
                    ?>

                    <td align="center">&#10004;</td>

                    <?php
                    }
                    $desde_while = strtotime ( '+1 day' , strtotime ( $desde_while ) ) ;
                    $desde_while = date ( 'Y-m-d' , $desde_while );
                    }

                    $port_rep=($cont*100)/($dias+1);
                    ?>
                    <td align="center"  width="5%"><?php echo number_format($port_rep,0, '.', '')."%" ?></td>

                    <?php
                    $cont=0;
                    $j=$j+1;
                    }
                    ?>
                    </tr>

                    <!-- TOTALES DE LOS DÍAS REPORTADOS -->
                    <tr>
                        <td colspan="4" align="center"><b>TOTALES</b></td>
                            <?php
                                $desde_while2=$desde;
                                $total=0;
                                while ( $desde_while2<=$hasta) {
                                $sql="SELECT count(*) as total FROM $BD_central.puntos_venta a,
                                $BD_central.estados b,
                                $BD_central.reporte_ventas_historico c
                                WHERE a.codigo_siga_punto=c.codigo_siga
                                and a.codigo_estado_punto=b.codigo_estado
                                and c.fecha='$desde_while2'
                                ";
                                if($_GET['punto']!="0"){
                                  $sql.=" and a.codigo_siga_punto='".$_GET['punto']."'";
                                }      
                                if($_GET['estado']!="0"){
                                  $sql.=" and b.codigo_estado='".$_GET['estado']."'";
                                }
                                /*if($punto!="0"){
                                    $sql.=" and a.codigo_siga_punto='$punto'";
                                }      
                                if($estado!="0"){
                                    $sql.=" and b.codigo_estado='$estado'";
                                }*/

                                $sql.=" ORDER BY b.nombre_estado, a.codigo_siga_punto";

                                $puntos_total = $conn->ObtenerFilasBySqlSelect($sql);
                                $total=$total+$puntos_total[0]["total"];
                            ?>
                        
                        <td align="center"><?php echo $puntos_total[0]["total"]; ?></td>
                            <?php
                                $desde_while2 = strtotime ( '+1 day' , strtotime ( $desde_while2 ) ) ;
                                $desde_while2 = date ( 'Y-m-d' , $desde_while2 );
                            }
                                $port_rep=($total*100)/($cont_total);
                            ?>
                            <td align="center"><?php echo number_format($port_rep,0, '.', '')."%" ?></td>
                    </tr>

                    </table>
<?php
exit();
?>