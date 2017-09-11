<script language="JavaScript" src="../../libs/js/md5_crypt.js"></script>
<script language="JavaScript" src="../../libs/js/cambiar_clave.js"></script>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td width="200" bgcolor="#A3CCE2" height="60" style="cursor:pointer;" >
            <img src="../../libs/imagenes/selectra.png" onclick="javascript: window.location.href='?opt_menu=54';" height="50" width="200">
        </td>
        <td valign="top">
           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td background="../../libs/imagenes/sup_33.png">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><img src="../../libs/imagenes/sup_22.png" width="260" height="52"></td>
                                <td>
                                    <table border="0" align="right" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td><img src="../../libs/imagenes/asys.png"  height="38"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#517D96">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <table border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td><img src="../../libs/imagenes/sup_c22.png" width="20" height="22"></td>
                                            <td><span class="stilo_login"><b>Usuario:</b> {$stringLoginUsuario} - <b>Empresa: {$DatosGenerales[0].nombre_empresa}</b> - Ultima Sesión: {$stringUltimaSesion} - ID {$idsesion}</span></td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table border="0" align="right" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td bgcolor="#A3CCE2" ></td>
                                            <td width="10">&nbsp;</td>
                                            <td bgcolor="#A3CCE2" > </td>
                                            <td width="10">&nbsp;</td>
                                            <td onclick="javascript: winClave.show();"><img src="../../imagenes/generar.png" width="22" height="22" align="absmiddle"><a style="color:#FFFFFF">Cambiar Contraseña</a></td>
                                            <td><a href="index.php?logout=off" target="_top" style="color:#FFFFFF"><img src="../../libs/imagenes/exit.png" width="22" height="22" align="absmiddle" class="icon">Salir</a></td>
                                            <td width="10">&nbsp;</td>
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
        <table width="100%">
            <thead>
                <tr align="center" valign="middle">
                    <th valign="middle" class="tb-tit"><b>Ingrese sus Datos</b></th>
                </tr>
            </thead>
        </table>
    </label>
    <table>
        <tr>
            <td valign="top"  colspan="3" width="40%" class="tb-head" >
                Clave Anterior
            </td>
            <td >
                <input maxlength="90" type="password"   name="claveOLD" size="30" id="claveOLD" >
            </td>
        </tr>
        <tr>
            <td valign="top"  colspan="3" width="40%" class="tb-head" >
                Nueva Clave
            </td>
            <td >
                <input maxlength="90" type="password" name="clave1" size="30" id="clave1" >
            </td>
        </tr>
        <tr>
            <td valign="middle"  colspan="3" width="40%" class="tb-head" >
                Confirmar Nueva Clave
            </td>
            <td >
                <input maxlength="90" type="password" name="clave2" size="30" id="clave2" >
            </td>
        </tr>
    </table>
</div>