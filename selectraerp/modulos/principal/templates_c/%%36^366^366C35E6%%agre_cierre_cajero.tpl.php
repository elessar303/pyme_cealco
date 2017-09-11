<?php /* Smarty version 2.6.21, created on 2017-08-01 19:45:03
         compiled from agre_cierre_cajero.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'agre_cierre_cajero.tpl', 839, false),)), $this); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="autor" />
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/inclusiones_reportes.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php echo '
        <script language="JavaScript" type="text/JavaScript">
        $( document ).ready(function() 
        {
          //verificar cajas con la cual van a trabajar
          parametros=
          {
            "opt": "abrir_cajeros"
          };

          $.ajax(
          {
            type: "POST",
            url: "../../libs/php/ajax/ajax.php",
            data: parametros,
            dataType: "html",
            asynchronous: false,
            error: function()
            {
              alert("error petición ajax");
            },
            success: function(data)
            {
              if(data!=1)
              {
                verificar_tipo_venta();
              }
            }
          });
        }); //fin del ready de verificar tupo de venta
        
        function procesar_tipo_venta()
        {
          if(document.getElementById("pos_check").checked==true)
          {
            document.getElementById(\'miVentana\').style.display=\'none\';
            var nested=document.getElementById(\'tipo_de_venta_mostrar\');
            var pyme_div=document.getElementById(\'ambas_pyme\');
            nested.parentNode.removeChild(nested);
            pyme_div.parentNode.removeChild(pyme_div);
            document.getElementById(\'ambas_pos\').style.display=\'block\';
            document.getElementById("cajero").disabled=false;  
          }
          else
          {
            document.getElementById(\'miVentana\').style.display=\'none\';
            var nested=document.getElementById(\'tipo_de_venta_mostrar\');
            var pos_div=document.getElementById(\'ambas_pos\');
            nested.parentNode.removeChild(nested);
            pos_div.parentNode.removeChild(pos_div);
            document.getElementById(\'ambas_pyme\').style.display=\'block\';
            document.getElementById("cajero").disabled=false;  
          }
        }//fin de la funcion procesar tipo de venta
        

      function check_tipo(id)
      {
        if(id=="pyme_check")
        {
          document.getElementById("pos_check").checked=false;
        }
        else
        {
          document.getElementById("pyme_check").checked=false;
        }
      }//fin de chequear tipo


      function verificar_tipo_venta()
      {
        parametros=
        {
          "opt": "verificar_tipo_venta"
        };

        $.ajax(
        {
          type: "POST",
          url: "../../libs/php/ajax/ajax.php",
          data: parametros,
          dataType: "html",
          asynchronous: false,
          error: function()
          {
            alert("error petición ajax");
          },
          success: function(data)
          {
            if(data==1)
            {
              var ventana = document.getElementById(\'miVentana\');
              ventana.style.marginTop = \'100px\';
              ventana.style.left = ((document.body.clientWidth-350) / 2) +  \'px\';
              ventana.style.display = \'block\';
              document.getElementById("cajero").disabled=true;                
            }
            else
            {
                $(\'#boton\').css("visibility", "visible");
            }
          }
        });
      }// fin de verificar tipo  de venta
      
      function calcular()
      {
        fecha = $("#fecha").val();
        caja= $("#caja").val();
          
        if(fecha=="")
        {
          alert("Campo Fecha no puede ser Vacio");
        }
        else
        {
          parametros=
          {
            "fecha": fecha, "caja": caja, "opt": "calcular"
          };
          $.ajax(
          {
            type: "POST",
            url: "../../libs/php/ajax/ajax.php",
            data: parametros,
            dataType: "html",
            asynchronous: false,
            error: function()
            {
              alert("error petición ajax");
            },
            success: function(data)
            {
              if(data==-1)
              {
                alert("No hay Datos Para La Fecha indicada");
                location.reload();
                }
                else
                {
                  if(data==-2)
                  {
                    alert("Error,La Fecha Indicada No Concuerda Con el Ultimo Cierre De Caja Registrado!");
                    history.back(-1); exit();
                  }
                  var res = data.split(" ");
                  $("#monto_bruto").val(parseFloat((res[\'1\'])).toFixed(2));
                  $("#monto_exento").val(parseFloat(res[\'2\']).toFixed(2));
                  $("#base_imponible").val(parseFloat(res[\'0\']).toFixed(2));
                  $("#iva").val(parseFloat(res[\'3\']).toFixed(2));
                }
              }
          });
        }
      } //fin del  funcion caluclar


      function guardar_arqueo(parametros)
      {
        $.ajax(
        {
          type: "POST",
          url: "../../libs/php/ajax/ajax.php",
          data: parametros,
          dataType: "html",
          asynchronous: false,
          beforeSend: function() 
          {
            $("#resultado").empty();
            $("#resultado").html(\'<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>\');
          },
          error: function()
          {
            alert("error petición ajax");
          },
          success: function(data)
          {
            data=data.split("_");
            if(data[0]==1)
            {
              alert("Se Ha Arqueado Al Cajero");
              var url = \'../../reportes/imprimir_arqueo_cajero.php\';

              //una vez realizado el arqueo, se realiza la transferencia de dicho arqueo
              tipo_venta_pyme= $("#tipo_venta_pyme").val();
              if(tipo_venta_pyme!=null)
              {
                //pyme
                tipo_venta=0;
              }
              else
              {
                //pos
                tipo_venta=1;
              }
              //comienza la transferencia a caja principal
              parametros2 = 
              { 
                "arreglo" : data[1], 
                "opt" : "calcular_monto_deposito", 
                "venta_pyme" : tipo_venta 
              }
              $.ajax(
              { 
                type: "POST",
                url: "../../libs/php/ajax/ajax.php",
                data: parametros2, 
                dataType: "html", 
                asynchronous: false,
                error: function()
                { 
                  alert("error peticion ajax"); 
                }, 
                success: function(data3)
                { 
                  
                  document.getElementById("resultado_transferencia").innerHTML=data3;
                  alert("Transferencia a Caja Principal...");
                 arqueos="";
                $(\'input[name^="arqueos_id"]\').each(function()
                {
                  arqueos+=$(this).val();
                  
                });
                banco=$(\'#banco\').val();
                monto=$(\'#monto\').val();
                sobrante=$(\'#sobrante\').val();
                cta_sobrante=$(\'#cta_sobrante\').val();
                var form2 = $(\'<form action="generar_deposito_fin2.php" method="post">\' 
                  + \'<input type="text" name="arqueos_id[]" id="arqueos_id[]" value="\' + arqueos + \'" />\' 
                  + \'<input type="text" name="banco" id="banco" value="\' + banco + \'" />\' 
                  + \'<input type="text" name="monto" id="monto" value="\' + monto + \'" />\' 
                  + \'<input type="text" name="sobrante" id="sobrante" value="\' + sobrante + \'" />\' 
                  + \'<input type="text" name="cta_sobrante" id="cta_sobrante" value="\' + cta_sobrante + \'" />\' +
                  \'</form>\');
                  document.body.appendChild(form2[0])
                  $(form2).submit();
                  //generar la planilla de caja principal como la de arqueo. y fin de transferir el arqueo automaticamente al caja principal
                }
              }); 
            }

            else
            {
              if(data==5)
              {
                alert("Error, Se Ha Superado La Cantidad De Cajeros Operativos");
                location.reload();
              }
                else
                {
                  alert("Error Al Insertar Registro (Consulte Al Administrador).");
                  location.reload();
                }
            }
          }
        });
      } //fin de guardar arqueo

      function enviar()
      {
        cajeros_activos= $("#cajeros_activos").val();
        if(cajeros_activos!=null)
        {
          parametros= 
          {
            "cajeros_activos": cajeros_activos,
            "opt": "insertar_cajeros_activos"
          }
          $.ajax(
          {
            type: "POST",
            url: "../../libs/php/ajax/ajax.php",
            data: parametros,
            dataType: "html",
            asynchronous: false,
            beforeSend: function() 
            {
              $("#resultado").empty();
              $("#resultado").html(\'<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>\');
            },
            error: function()
            {
              alert("error petición ajax");
            },
            success: function(data)
            {
              alert("Registro Exitoso");
              $(\'#boton\').css("visibility", "visible");
              $("#resultado").html("");
            }
          }); // fin del ajax

            return true;
        }
        if($("#registro_inicial").val()==1)
        {
          fecha_fin=$("#fecha_fin").val();
          fecha_inicio=$("#fecha_inicio").val();
          cajero=$("#cajero").val();
          tipo_venta_pyme= $("#tipo_venta_pyme").val();
          if(tipo_venta_pyme==null)
          {
            parametros=
            {
              "fecha_inicio": fecha_inicio,
              "fecha_fin": fecha_fin,
              "cajero": cajero,
              "opt": "insertar_registro_inicial"
            };
          }
          else
          {
            parametros=
            {
              "fecha_inicio": fecha_inicio,
              "fecha_fin": fecha_fin,
              "cajero": cajero,
              "tipo_venta_pyme": "1",
              "opt": "insertar_registro_inicial"
            };
          }
          $.ajax(
          {
            type: "POST",
            url: "../../libs/php/ajax/ajax.php",
            data: parametros,
            dataType: "html",
            asynchronous: false,
            beforeSend: function() 
            {
              $("#resultado").empty();
              $("#resultado").html(\'<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>\');
            },
            error: function()
            {
              alert("error petición ajax");
            },
            success: function(data)
            {
              if(data==1)
              {
                cargar_formulario(cajero);
              }
              else
              {
                alert("Error Al Insertar Registro Inicial.");
              }
            }
          });//fin del ajax
        } //fin del if
        else
        {
          //capturamos el string con los billetes validos y verificamos si fueron llenados
          billetes=$("#billetesvalidos").val();
          var billetesarray = billetes.split(",");
          var valoresarray =new Array();
          var bandera=1;
          for (var i=0; i<billetesarray.length; i++) 
          { 
            if($("#"+billetesarray[i]).val()=="")
            {
              bandera=0;//bandera que avisa si faltó algun campo por llenar
            }
            else
            {
              valoresarray[i]=$("#"+billetesarray[i]).val();
            }
          }
          total_monedas=$("#total_monedas").val();
          total_tarjeta=$("#total_tarjeta").val();
          total_credito=0;//$("#total_credito").val();
          total_ticket=$("#total_ticket").val();
          total_deposito=$("#total_deposito").val();
          total_cheque=$("#total_cheque").val();
          cajero=$("#cajero").val();
             
          if(bandera==0 || total_monedas=="" || total_monedas<0 || total_tarjeta=="" || total_tarjeta<0 || total_ticket=="" || total_ticket<0 || total_credito<0 || total_deposito<0 || total_deposito=="" || total_cheque<0 || total_cheque=="")
          {
            alert("Faltan Datos O Existen Campos En Negativo En El Formulario, Por Favor Corregir");
          }
          else
          {
            tipo_venta_pyme= $("#tipo_venta_pyme").val();
            parametros= 
            {
              "billetesarray": billetesarray,
              "valoresarray" : valoresarray,
              "total_monedas": total_monedas,
              "total_tarjeta": total_tarjeta,
              "total_ticket": total_ticket,
              "total_credito": total_credito,
              "total_deposito": total_deposito,
              "total_cheque": total_cheque,
              "cajero": cajero,
              "tipo_venta_pyme": tipo_venta_pyme,
              "opt": "calcular_arqueo"
            };
            $.ajax(
            {
              type: "POST",
              url: "../../libs/php/ajax/ajax.php",
              data: parametros,
              dataType: "html",
              asynchronous: false,
              beforeSend: function() 
              {
                $("#resultado").empty();
                $("#resultado").html(\'<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargandodiv></div>\');
              },
              error: function()
              {
                alert("error petición ajax");
              },
              success: function(data)
              {
                data=data.split("_");
                if(data[0]==0)
                {
                  if(confirm(\'Usted Esta Entregando La Cantidad De \'+data[1]+\', ese es el monto con el cual se registrará en el sistema, Si no esta de acuerdo con esseleccionar el boton cancelar y volver a cargar, en caso contrario aceptar.\'))
                  {
                    if(confirm(\'¿Esta Seguro De Proceder Con La Operacion? (Esta OPodrá Editar)\'))
                    {
                      $(\'#boton\').css("visibility", "hidden");
                      tipo_venta_pyme= $("#tipo_venta_pyme").val();
                      parametros=
                      {
                        "billetesarray": billetesarray,
                        "valoresarray" : valoresarray,
                        "total_monedas": total_monedas,
                        "total_tarjeta": total_tarjeta,
                        "total_credito": total_credito,
                        "total_deposito": total_deposito,
                        "total_cheque": total_cheque,
                        "total_ticket": total_ticket,
                        "cajero": cajero,
                        "sistema": data[2],
                        "resultado": data[3],
                        "total_efectivo_sistema": data[4],
                        "total_tj_sistema": data[5],
                        "total_tickets_sistema": data[6],
                        "total_devolucion": data[7],
                        "total_credito_sistema": data[8],
                        "total_deposito_sistema": data[9],
                        "total_cheque_sistema": data[10],
                        "total_iva1": data[11],
                        "total_iva2": data[12],
                        "total_iva3": data[13],
                        "array_depositos": data[14],
                        "array_cheques": data[15],
                        "tipoarray": data[16],
                        "tipo_venta_pyme": tipo_venta_pyme,
                        "opt": "guardar_arqueo"
                      };
                        guardar_arqueo(parametros);
                        $("#resultado").empty();
                    }
                    else
                    {
                      alert("Arqueo Cancelado");
                      location.reload();
                    }
                  }
                  else
                  {
                    alert("Arqueo Cancelado");
                    location.reload();
                  }
                }//fin del if 0
                if(data[0]==1)
                {
                  if(confirm(\'Usted Esta Entregando La Cantidad De \'+data[1]+\', ese es el monto con el cual se registrará en el sistema, Si no esta de acuerdo con este monto, debe seleccionar el boton cancelar y volver a cargar, en caso contrario aceptar.\'))
                    {
                      if(confirm(\'***¿Esta seguro De Proceder con La operacion? Recuerde que esta operacion no se podrá editar***\'))
                      {
                        tipo_venta_pyme= $("#tipo_venta_pyme").val();
                        parametros=
                        {
                          "billetesarray": billetesarray,
                          "valoresarray" : valoresarray,
                          "total_monedas": total_monedas,
                          "total_tarjeta": total_tarjeta,
                          "total_credito": total_credito,
                          "total_deposito": total_deposito,
                          "total_cheque": total_cheque,
                          "total_ticket": total_ticket,
                          "cajero": cajero,
                          "sistema": data[2],
                          "resultado": data[3],
                          "total_efectivo_sistema": data[4],
                          "total_tj_sistema": data[5],
                          "total_tickets_sistema": data[6],
                          "total_devolucion": data[7],
                          "total_credito_sistema": data[8],
                          "total_deposito_sistema": data[9],
                          "total_cheque_sistema": data[10],
                          "total_iva1": data[11],
                          "total_iva2": data[12],
                          "total_iva3": data[13],
                          "array_depositos": data[14],
                          "array_cheques": data[15],
                          "tipoarray": data[16],
                          "tipo_venta_pyme": tipo_venta_pyme,
                          "opt": "guardar_arqueo"
                          };
                          guardar_arqueo(parametros);
                      }
                      else
                      {
                        alert("Arqueo Cancelado");
                        location.reload();
                      }
                    }
                    else
                    {
                      alert("Arqueo Cancelado");
                      location.reload();
                    }
                  }//fin del if1
                  if(data[0]==2)
                  {
                    if(confirm(\'Usted Esta Entregando La Cantidad De \'+data[1]+\', ese es el monto con el cual se registrará en el sistema, Si no esta de acuerdo con este monto, debe seleccionar el boton cancelar y volver a cargar, en caso contrario aceptar.\'))
                    {
                      if(confirm(\'¿Esta Seguro De Proceder Con La Operacion? (Esta Operacion No Se Podrá Editar)\'))
                      {
                        tipo_venta_pyme= $("#tipo_venta_pyme").val();
                        parametros=
                        {
                          "billetesarray": billetesarray,
                          "valoresarray" : valoresarray,
                          "total_monedas": total_monedas,
                          "total_tarjeta": total_tarjeta,
                          "total_credito": total_credito,
                          "total_deposito": total_deposito,
                          "total_cheque": total_cheque,
                          "total_ticket": total_ticket,
                          "cajero": cajero,
                          "sistema": data[2],
                          "resultado": data[3],
                          "total_efectivo_sistema": data[4],
                          "total_tj_sistema": data[5],
                          "total_tickets_sistema": data[6],
                          "total_devolucion": data[7],
                          "total_credito_sistema": data[8],
                          "total_deposito_sistema": data[9],
                          "total_cheque_sistema": data[10],
                          "total_iva1": data[11],
                          "total_iva2": data[12],
                          "total_iva3": data[13],
                          "array_depositos": data[14],
                          "array_cheques": data[15],
                          "tipoarray": data[16],
                          "tipo_venta_pyme": tipo_venta_pyme,
                          "opt": "guardar_arqueo"
                        };
                        guardar_arqueo(parametros);
                      }
                      else
                      {
                        alert("Arqueo Cancelado");
                        location.reload();
                      }
                    }
                    else
                    {
                      alert("Arqueo Cancelado");
                      location.reload();
                    }
                  }// fin del if2
                  if(data[0]==333)
                  {
                    alert("No Existen Ventas En Ese Periodo");
                    location.reload();
                  }
                  if(data[0]==\'_4\')
                  {
                    alert("Error Con Fecha Final");
                  }
                }//fin del succes
            });
          }
        }//fin del else
      } //fin del funcion enviar


        


      function cargar_formulario(cajero)
      { 
        parametros =
        {
          "cajero": cajero, "opt":\'formulario_arqueo_cajero\'
        };
        $.ajax(
        {
          type: "POST",
          url: "../../libs/php/ajax/ajax.php",
          data: parametros,
          dataType: "html",
          asynchronous: false,
          beforeSend: function() 
          {
            $("#resultado").empty();
            $("#resultado").html(\'<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>\');
          },
          error: function()
          {
            alert("error petición ajax");
          },
          success: function(data)
          {
            $(\'#boton\').css("visibility", "visible");
            $("#resultado").html(data);
          }
        });
      } //fin del funcion cargar formulario

      function registro_inicial(cajero)
      {
        //verificamos  si es pyme
        tipo_venta_pyme= $("#tipo_venta_pyme").val();
        if(tipo_venta_pyme==null)
        {
          parametros =
          {
            "cajero": cajero, "opt": "registro_inicial"
          };
        }
        else
        {
          parametros =
          {
            "cajero": cajero, "tipo_venta_pyme": "1", "opt": "registro_inicial"
          };
        }
        $.ajax(
        {
          type: "POST",
          url: "../../libs/php/ajax/ajax.php",
          data: parametros,
          dataType: "html",
          asynchronous: false,
          beforeSend: function() 
          {
            $("#resultado").empty();
            $("#resultado").html(\'<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>\');
          },
          error: function()
          {
            alert("error petición ajax");
          },
          success: function(data)
          {
            $(\'#boton\').css("visibility", "visible");
            $("#resultado").html(data);
          }
        }); // fin del ajax
      } //fin de la funcion registro inicial

      function verificar_cajero()
      {
         cajero= $("#cajero").val();
        if(cajero!=-1)
        {
          parametros =
          {
            "cajero": cajero, "opt":\'verificar_cajero\'
          };
          $.ajax(
          {
            type: "POST",
            url: "../../libs/php/ajax/ajax.php",
            data: parametros,
            dataType: "html",
            asynchronous: false,
            beforeSend: function() 
            {
              $("#resultado").empty();
              $("#resultado").html(\'<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>\');
            },
            error: function()
            {
              alert("error petición ajax");
            },
            success: function(data)
            {
              if(data==-1)
              {
                if(confirm("Este Cajero No Posee Registros De Ventas Anteriores, ¿Desea Crear Registro?"))
                {
                  registro_inicial(cajero);
                }
              }
              else
              {
                if(data==-2)
                {
                  alert("Este Cajero No Esta Habilitado");
                  $(\'#boton\').css("visibility", "hidden");
                  location.reload();
                }
                else
                {
                  cargar_formulario(cajero); 
                }
              }
            }
          });//fin del ajax    
        }
      }//fin de la funcion verificar cajero
      
      //arquero cajero por pyme *********************************+++
      function verificar_cajero1()
      {
        cajero= $("#cajero").val();
        if(cajero!=-1)
        {
          parametros =
          {
            "cajero": cajero, "opt":\'verificar_cajero_pyme\'
          };
          $.ajax(
          {
            type: "POST",
            url: "../../libs/php/ajax/ajax.php",
            data: parametros,
            dataType: "html",
            asynchronous: false,
            beforeSend: function() 
            {
              $("#resultado").empty();
              $("#resultado").html(\'<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>\');
            },
            error: function()
            {
              alert("error petición ajax");
            },
            success: function(data)
            {
              if(data==-1)
              {
                if(confirm("Este Cajero No Posee Registros De Ventas Anteriores, ¿Desea Crear Registro?"))
                {
                  registro_inicial(cajero);
                }
              }
              else
              {
                if(data==1)
                {
                  cargar_formulario(cajero); 
                }
                else
                {
                  alert("Error");
                }
              }
            }
          });//fin del ajax
        }//fin if
      } //fin de verificar cajero pyme

      function registro_inicial_pyme(cajero)
      {
        parametros =
        {
          "cajero": cajero, "opt": "registro_inicial_pyme"
        };
        $.ajax(
        {
          type: "POST",
          url: "../../libs/php/ajax/ajax.php",
          data: parametros,
          dataType: "html",
          asynchronous: false,
          beforeSend: function() 
          {
            $("#resultado").empty();
            $("#resultado").html(\'<div class="imgajax"><img style="margin-left: 10px" src="../../imagenes/ajax-loader.gif" alt=""><div class="cargando">Cargando...</div></div>\');
          },
          error: function()
          {
            alert("error petición ajax");
          },
          success: function(data)
          {
            $(\'#boton\').css("visibility", "visible");
            $("#resultado").html(data);
          }
        });  
      }//fin de la funcion registro inicial del pyme




        </script>
        '; ?>
        
        <title>Cierre de Ventas</title>
        
    </head>

    <body>
        <form name="formulario" id="formulario">
        <div id="datosGral" class="x-hide-display">
        
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "snippets/regresar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <input type="hidden" name="codigo_empresa" value="<?php echo $this->_tpl_vars['DatosEmpresa'][0]['codigo']; ?>
"/>
                <input type="hidden" name="opt_menu" value="<?php echo $_GET['opt_menu']; ?>
"/>
                <input type="hidden" name="opt_seccion" value="<?php echo $_GET['opt_seccion']; ?>
"/>
                <input type="hidden" name="cant_fechas" id="cant_fechas" value="2"/>
                <input type="hidden" name="ordenar_por" id="ordenar_por" value="1"/>
                <input type="hidden" name="tiene_filtro" id="tiene_filtro" value="1"/>
                <input type="hidden" name="tip_mov" id="tip_mov" value="1"/>

        <table style="width:50%; background-color:white;" cellpadding="1" cellspacing="1" class="seleccionLista" align="center">
                <thead>
                    <tr>
                        <th class="tb-head" style="text-align:center;">Seleccionar El Cajero A Cerrar (Arqueo)</th>
                    </tr>
                    <tr>
                      <td align="center"> 
                        <div id="tipo_de_venta_mostrar">
                      <table>

                    <?php if ($this->_tpl_vars['ubicacion_venta'] == '1'): ?>
                    <tr>

                    <?php if ($this->_tpl_vars['option_values_cajas'] != ""): ?>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                        <select name="cajero" id="cajero" style="width:200px;" class="form-text" onchange="verificar_cajero();">
                        <option value="-1">Seleccione Cajero...</option>                               
                        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cajas'],'output' => $this->_tpl_vars['option_output_cajas']), $this);?>

                        </select>
                    </td>
                    <?php else: ?>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                      <input type="hidden" id="cajero" />
                        <p align="center" > No Existen Cajeros con Ventas el Dia de Hoy </p>
                    </td>
                    <?php endif; ?>
                    </tr>
                    <?php else: ?>
                    <tr>
                    <?php if ($this->_tpl_vars['option_values_cajas_pyme'] != ""): ?>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                        <select name="cajero" id="cajero" style="width:200px;" class="form-text" onchange="verificar_cajero1();">
                        <option value="-1">Seleccione Cajero...</option>                               
                        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cajas_pyme'],'output' => $this->_tpl_vars['option_output_cajas_pyme']), $this);?>

                        </select>
                        <input type="hidden" name="tipo_venta_pyme" id="tipo_venta_pyme" value="0" />
                    </td>
                    <?php else: ?>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                      <input type="hidden" id="cajero" />
                        <p align="center" > No Existen Cajeros con Ventas el Dia de Hoy </p>
                    </td>
                    <?php endif; ?>
                    </tr>
                    <?php endif; ?>
                  </table>
                </div>
              </td>
            </tr>

                    <!-- Otra Opcion -->
                    <tr><td align="center"><div id="ambas_pos"  style="display: none;"> <table><tr>
                    <?php if ($this->_tpl_vars['option_values_cajas'] != ""): ?>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                        <select name="cajero" id="cajero" style="width:200px;" class="form-text" onchange="verificar_cajero();">
                        <option value="-1">Seleccione Cajero...</option>                               
                        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cajas'],'output' => $this->_tpl_vars['option_output_cajas']), $this);?>

                        </select>
                        
                    </td>
                    <?php else: ?>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                      <input type="hidden" id="cajero" />
                        <p align="center" > No Existen Cajeros con Ventas el Dia de Hoy </p>
                    </td>
                    <?php endif; ?>
                    </tr>
                  </table></div> </td></tr>

                  <tr><td align="center"><div id="ambas_pyme"  style="display: none;"> <table><tr>
                    <?php if ($this->_tpl_vars['option_values_cajas_pyme'] != ""): ?>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                        <select name="cajero" id="cajero" style="width:200px;" class="form-text" onchange="verificar_cajero1();">
                        <option value="-1">Seleccione Cajero...</option>                               
                        <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['option_values_cajas_pyme'],'output' => $this->_tpl_vars['option_output_cajas_pyme']), $this);?>

                        </select>
                        <input type="hidden" name="tipo_venta_pyme" id="tipo_venta_pyme" value="0" />
                    </td>
                    <?php else: ?>
                    <td width="200px" style="padding-top:2px; padding-bottom: 2px;">
                      <input type="hidden" id="cajero" />
                        <p align="center" > No Existen Cajeros con Ventas el Dia de Hoy </p>
                    </td>
                    <?php endif; ?>
                    </tr>
                  </table></div></td></tr>
                </thead>
                
        </table>
        </form>
        </div>
        <br>
        <form method='POST' action='agregar_libro_fin.php' name='form1' target="ventanaForm" onsubmit="window.open('', 'ventanaForm', '')">
        <div id="resultado" width='50%' align="center">
         
        </div>
        <div id="boton" style="visibility:hidden;"><tbody>
                    <tr class="tb-head" align="center">
                            <td align='center'>
                                <input type="button" id="aceptar" name="aceptar" value="Crear" onclick="enviar()" />
                                
                            </td>
                    </tr>
                </tbody>
            </div>
        </form>  
    </body>


      <div id='miVentana' style='position: fixed; width: 350px; height: 190px; top: 0; left: 0; font-family:Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; border: #333333 3px solid; background-color: #FAFAFA; color: #000000; display:none;  -moz-opacity:0.8; -webkit-opacity:0.8; -o-opacity:0.9; -ms-opacity:0.9; background-color: #808080; overflow: auto; width: 500px; background: #fff; padding: 30px; -moz-border-radius: 7px; border-radius: 7px; -webkit-box-shadow: 0 3px 20px rgba(0,0,0,1); -moz-box-shadow: 0 3px 20px rgba(0,0,0,1); box-shadow: 0 3px 20px rgba(0,0,0,1); background: -moz-linear-gradient(#fff, #ccc); background: -webkit-gradient(linear, right bottom, right top, color-stop(1, rgb(255,255,255)), color-stop(0.57, rgb(230,230,230)));  
'>
<h1>Seleccionar Ubicacion De Venta Del Libro De Venta</h1>
<table border="4"  align="center" width="200px">
  <tr>
    <td align="center" >
      <b>PYME</b>
    </td>
    <td >
      <input type="checkbox" name="pyme_check" id="pyme_check" onclick="check_tipo(this.id)" style="margin-top: 2px; margin-left:2px;"/>
    </td>
  </tr>
<tr>
  <td align="center">
    <b>POS</b>
  </td>
  <td>
    <input type="checkbox" name="pos_check" id="pos_check" onclick="check_tipo(this.id)" style="margin-top: 2px; margin-left:2px;" />
  </td>
</tr>
</tr>
</table>
  <p><input align="center" type="submit" value="Procesar" style="  margin-right: 220px;" onclick="procesar_tipo_venta()" /></p>
</div>

<div style="opacity: 0;">
  
    <div id="resultado_transferencia" width='50%' align="center">
    </div>
  
</div>
</html>    