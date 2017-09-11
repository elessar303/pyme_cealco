<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>        
        <title></title>
        <script type="text/javascript" src="../../libs/js/md5_crypt.js"></script>
        <script type="text/javascript" src="../../libs/js/cambiar_clave.js"></script>
    </head>
    <body>
        <table style="width:100%" >
            <tr>
                <td style="cursor:pointer; height: 60px; width: 200px; background-color: #A3CCE2; " >
                    <img src="../../libs/imagenes/selectra.png" onclick="javascript: window.location.href='?opt_menu=54';" height="50" width="200"/>
                </td>
                <td>
                    <table style="width:100%" >
                        <tr>
                            <td style="background-image: url('../../libs/imagenes/sup_33.png')">
                                <table style="width:100%">
                                    <tr>
                                        <td><img src="../../libs/imagenes/sup_22.png" width="200" height="52"/></td>
                                        <td>
                                            <table style="width:100%; text-align:right;" >
                                                <tr>
                                                    <td><img src="../../libs/imagenes/asys.png" height="38"/></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: #517D96">
                                <table style="width:100%" >
                                    <tr>
                                        <td>
                                            <table style="width:100%" >
                                                <tr>
                                                    <td><img src="../../libs/imagenes/sup_c22.png" width="20" height="22"/></td>
                                                    <td><span class="stilo_login"><b>{$nombre} ({$stringLoginUsuario}) de {$DatosGenerales[0].nombre_empresa}</b> - Su &uacute;ltima sesión fue el d&iacute;a {$stringUltimaSesionFecha} a las {$stringUltimaSesionHora} - ID: {$idsesion}</span></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table style="width:100%" >
                                                <tr>
                                                    <td style="background-color: #A3CCE2" ></td>
                                                    <td style="width:10px">&nbsp;</td>
                                                    <td style="background-color: #A3CCE2" ></td>
                                                    <td style="width:10px">&nbsp;</td>
                                                    <td style="cursor: pointer;" onclick="javascript: winClave.show();">
                                                        <img src="../../imagenes/generar.png" width="22" height="22" align="absmiddle"/><a style="color:#FFFFFF">Cambiar Contrase&ntilde;a</a>
                                                    </td>
                                                    <td>
                                                        <a href="index.php?logout=off" target="_top" style="color:#FFFFFF"><img src="../../libs/imagenes/exit.png" width="22" height="22" align="absmiddle" class="icon">Salir</a>
                                                    </td>
                                                    <td style="width:10px">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div id="cambiarclave" class="x-hide-display">
            <label>
                <table style="width:100%">
                    <thead>
                        <tr align="center" valign="middle">
                            <th valign="middle" class="tb-tit"><b>Ingrese sus Datos</b></th>
                        </tr>
                    </thead>
                </table>
            </label>
            <table>
                <tr>
                    <td valign="top" colspan="3" width="40%" class="tb-head" >Clave Anterior</td>
                    <td>
                        <input maxlength="90" type="password" name="claveOLD" size="30" id="claveOLD" />
                    </td>
                </tr>
                <tr>
                    <td valign="top" colspan="3" width="40%" class="tb-head" >Nueva Clave</td>
                    <td><input maxlength="90" type="password" name="clave1" size="30" id="clave1" /></td>
                </tr>
                <tr>
                    <td valign="middle" colspan="3" width="40%" class="tb-head" >Confirmar Nueva Clave</td>
                    <td><input maxlength="90" type="password" name="clave2" size="30" id="clave2" /></td>
                </tr>
            </table>
        </div>
    </body>
</html>