<form name="formulario" method="POST" action="">
<input type="hidden" name="codigo_empresa" value="{$DatosEmpresa[0].codigo}">
<input type="hidden" name="opt_menu" value="{$smarty.get.opt_menu}">
<input type="hidden" name="opt_seccion" value="{$smarty.get.opt_seccion}">

<table  width="100%" border="0" height="100">
<tbody>
<tr>
      <td colspan="4" height="30" class="tb-tit">
          <strong>Editar Registro de Datos de la Empresa</strong>
      </td>
</tr>
<tr>
      <td class="tb-head" colspan="4" align="center">
          COMPLETLE LOS CAMPOS MARCADOS CON&nbsp;** OBLIGATORIAMENTE
      </td>
</tr>
<tr>
    <td  colspan="3" width="30%" class="tb-head" >
        Codigo **
    </td>
    <td >
       <input type="text" name="codigo" size="60"  value="{$DatosEmpresa[0].codigo}">
    </td>
</tr>
<tr>
      <td class="tb-head" colspan="4" align="center" width="180">
          DATOS DE LA EMPRESA
      </td>
</tr>
<tr>
    <td colspan="3" class="tb-head">
        Nombre**
    </td>
    <td>
        <input type="text" name="nomemp" size="60" value='{$DatosEmpresa[0].nomemp}'>
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3"  >
        R.I.F.&nbsp
    <td>
        <input type="text" name="rif" size="60" value='{$DatosEmpresa[0].rif}'>
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3">
        N.I.T.
    </td>
    <td>
        <input type="text" name="nit" size="60" value='{$DatosEmpresa[0].nit}'>
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3">
        Presidente
    </td>
    <td>
        <input type="text" name="presidente" size="60" value='{$DatosEmpresa[0].presidente}'>
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3">
        Período
    </td>
    <td>
        <input type="text" name="periodo" size="60" value='{$DatosEmpresa[0].periodo}'>
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3">
        Departamento
    </td>
    <td>
        <input type="text" name="departamento" size="60" value='{$DatosEmpresa[0].departamento}'>
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3">
        Cargo
    </td>
    <td>
        <input type="text" name="cargo" size="60" value='{$DatosEmpresa[0].cargo}'>
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3">
        Dirección
    </td>
    <td>
        <input type="text" name="direccion" size="60" value='{$DatosEmpresa[0].direccion}'>
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3">
        Estado
    </td>
    <td>
        <input type="text" name="estado" size="60" value='{$DatosEmpresa[0].estado}'>
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3">
        Ciudad
    </td>
    <td>
        <input type="text" name="ciudad" size="60" value='{$DatosEmpresa[0].ciudad}'>
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3">
        Teléfono Fax
    </td>
    <td>
        <input type="text" name="telefonofax" size="60" value='{$DatosEmpresa[0].telefonofax}'>
    </td>
</tr>
<tr>
    <td class="tb-head"  colspan="3">
        Teléfonos
    </td>
    <td>
        <input type="text" name="telefono" size="60" value='{$DatosEmpresa[0].telefono}'>
    </td>
</tr>
<tr>
    <td class="tb-head" colspan="3">
        Encargado Administración
    </td>
    <td>
        <input type="text" name="pers_adm" size="60" value='{$DatosEmpresa[0].pers_adm}'>
    </td>
</tr>
<tr class="tb-tit" align="right">
    <td colspan="4">
        <input type="submit" name="aceptar" value="Aceptar">&nbsp;
        <input type="button" name="cancelar" onclick="javascript: document.location.href='?opt_menu={$smarty.get.opt_menu}'" value="Cancelar">
    </td>
</tr>
</tbody>
</table>
</form>