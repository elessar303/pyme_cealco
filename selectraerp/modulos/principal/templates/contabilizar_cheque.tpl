<table width="100%">
  <tr>
    <td class="row-br"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb-tit">
      <tr>
        <td><span style="float:left"><img src="{$CabeceraseccionesByOptMenu[0].img_ruta}" width="22" height="22" class="icon" />{$CabeceraseccionesByOptMenu[0].nom_menu}</span>          </td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td><div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0"  >
        <tr>
          <TD >
	    <table width="100%" border="0" cellspacing="0" cellpadding="0"  >
{assign var = contarTD value=0}
{assign var = contarTR value=0}
{assign var = sw value= 0}
{foreach from=$seccionesByOptMenu key=i item=varArreglo }
{if $varArreglo.nom_menu <> ""}
{if $i%5 eq 0 and $contarTD eq 0}
<tr> <!-- Inicio -->
{/if}
                    <td width="20%">
                        <div class="box">
                            <table width="150" height="90" border="0" cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="javascript:window.location='?opt_menu={$smarty.get.opt_menu}&opt_seccion={$varArreglo.cod_modulo}'">
                                <tr>
                                    <td height="45" valign="bottom"><div align="center"><img width="30" height="30" src="{$varArreglo.img_ruta}" class="icon"/></div></td>
                                </tr>
                                <tr>
                                    <td height="45"><div align="center" class="boton-text">{$varArreglo.nom_menu}

                                       </div></td>
                                </tr>
                            </table>
                        </div>

                    </td>

{assign var = sw value= 1}
{assign var = contarTD value= $contarTD+1}
{if $contarTD%5 eq 0 and $i <> 0 or  $contarTD%5 eq 0 and $i <> 5  }
{assign var = contarTD value= 0}
</tr> <!-- Fin -->
{/if}
{/if}
{/foreach}
{if $contarTD <> 5 and $sw eq 1}
</tr> <!-- Fin -->
{/if}



            </table>
              </div>
	</td>
        </tr>
        </table>
    </div>
    </td>
  </tr>
</table>