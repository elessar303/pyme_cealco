{"filter":false,"title":"entradapaleta_nuevo.tpl","tooltip":"/selectraerp/modulos/principal/templates/entradapaleta_nuevo.tpl","undoManager":{"mark":100,"position":100,"stack":[[{"start":{"row":329,"column":29},"end":{"row":329,"column":30},"action":"insert","lines":["d"],"id":5448}],[{"start":{"row":329,"column":30},"end":{"row":329,"column":31},"action":"insert","lines":["i"],"id":5449}],[{"start":{"row":329,"column":31},"end":{"row":329,"column":32},"action":"insert","lines":["v"],"id":5450}],[{"start":{"row":329,"column":32},"end":{"row":329,"column":39},"action":"insert","lines":["></div>"],"id":5451}],[{"start":{"row":329,"column":33},"end":{"row":331,"column":28},"action":"insert","lines":["","                                ","                            "],"id":5452}],[{"start":{"row":330,"column":32},"end":{"row":330,"column":33},"action":"insert","lines":["<"],"id":5453}],[{"start":{"row":330,"column":32},"end":{"row":330,"column":33},"action":"remove","lines":["<"],"id":5454}],[{"start":{"row":329,"column":32},"end":{"row":329,"column":33},"action":"insert","lines":[" "],"id":5455}],[{"start":{"row":329,"column":33},"end":{"row":329,"column":34},"action":"insert","lines":["i"],"id":5456}],[{"start":{"row":329,"column":34},"end":{"row":329,"column":35},"action":"insert","lines":["d"],"id":5457}],[{"start":{"row":329,"column":35},"end":{"row":329,"column":36},"action":"insert","lines":["="],"id":5458}],[{"start":{"row":329,"column":36},"end":{"row":329,"column":38},"action":"insert","lines":["\"\""],"id":5459}],[{"start":{"row":329,"column":37},"end":{"row":329,"column":55},"action":"insert","lines":["notificacionticket"],"id":5460}],[{"start":{"row":217,"column":24},"end":{"row":242,"column":25},"action":"remove","lines":["if(valor!='')","                        {","                            $.ajax(","                            {","                                type: \"GET\",","                                url:  \"../../libs/php/ajax/ajax.php\",","                                data: \"opt=Validarserial_caja&v1=\"+valor,","                                beforeSend: function()","                                {","                                    $(\"#notificacionVCodCliente\").html(MensajeEspera(\"<b>Veficando serial ..</b>\"));","                                },","                                success: function(data)","                                {","                                    resultado = data","                                    if(resultado==-1)","                                    {","                                        $(\"#serial\").val(\"\").focus();","                                        $(\"#notificacionVUsuario1\").html(\"<img align=\\\"absmiddle\\\" src=\\\"../../../includes/imagenes/ico_note.gif\\\"><span style=\\\"color:red;\\\"> <b>Disculpe, este serial ya existe.</b></span>\");","                                    }","                                    if(resultado==1)","                                    {//cod de item disponble","                                        $(\"#notificacionVUsuario1\").html(\"<img align=\\\"absmiddle\\\" src=\\\"../../../includes/imagenes/ok.gif\\\"><span style=\\\"color:#0c880c;\\\"><b> Serial Disponible</b></span>\");","                                    }","                                }","                            });","                        }"],"id":5462}],[{"start":{"row":75,"column":25},"end":{"row":75,"column":26},"action":"insert","lines":[")"],"id":5464}],[{"start":{"row":75,"column":26},"end":{"row":75,"column":27},"action":"insert","lines":[";"],"id":5465}],[{"start":{"row":153,"column":36},"end":{"row":153,"column":37},"action":"insert","lines":["/"],"id":5466}],[{"start":{"row":153,"column":37},"end":{"row":153,"column":38},"action":"insert","lines":["/"],"id":5467}],[{"start":{"row":114,"column":24},"end":{"row":114,"column":25},"action":"insert","lines":["e"],"id":5468}],[{"start":{"row":114,"column":25},"end":{"row":114,"column":26},"action":"insert","lines":["l"],"id":5469}],[{"start":{"row":114,"column":26},"end":{"row":114,"column":27},"action":"insert","lines":["s"],"id":5470}],[{"start":{"row":114,"column":27},"end":{"row":114,"column":28},"action":"insert","lines":["e"],"id":5471}],[{"start":{"row":114,"column":24},"end":{"row":114,"column":28},"action":"remove","lines":["else"],"id":5472},{"start":{"row":114,"column":24},"end":{"row":114,"column":28},"action":"insert","lines":["else"]}],[{"start":{"row":114,"column":24},"end":{"row":114,"column":28},"action":"remove","lines":["else"],"id":5473},{"start":{"row":114,"column":24},"end":{"row":114,"column":28},"action":"insert","lines":["else"]}],[{"start":{"row":114,"column":28},"end":{"row":115,"column":0},"action":"insert","lines":["",""],"id":5474},{"start":{"row":115,"column":0},"end":{"row":115,"column":24},"action":"insert","lines":["                        "]}],[{"start":{"row":115,"column":20},"end":{"row":115,"column":24},"action":"remove","lines":["    "],"id":5475}],[{"start":{"row":115,"column":20},"end":{"row":115,"column":24},"action":"insert","lines":["    "],"id":5476}],[{"start":{"row":115,"column":24},"end":{"row":115,"column":25},"action":"insert","lines":["{"],"id":5477}],[{"start":{"row":115,"column":25},"end":{"row":115,"column":26},"action":"insert","lines":["}"],"id":5478}],[{"start":{"row":115,"column":26},"end":{"row":116,"column":0},"action":"insert","lines":["",""],"id":5479},{"start":{"row":116,"column":0},"end":{"row":116,"column":24},"action":"insert","lines":["                        "]}],[{"start":{"row":115,"column":25},"end":{"row":117,"column":24},"action":"insert","lines":["","                            ","                        "],"id":5480}],[{"start":{"row":116,"column":28},"end":{"row":116,"column":29},"action":"insert","lines":["t"],"id":5481}],[{"start":{"row":116,"column":29},"end":{"row":116,"column":30},"action":"insert","lines":["i"],"id":5482}],[{"start":{"row":116,"column":30},"end":{"row":116,"column":31},"action":"insert","lines":["c"],"id":5483}],[{"start":{"row":116,"column":31},"end":{"row":116,"column":32},"action":"insert","lines":["k"],"id":5484}],[{"start":{"row":116,"column":32},"end":{"row":116,"column":33},"action":"insert","lines":["e"],"id":5485}],[{"start":{"row":116,"column":33},"end":{"row":116,"column":34},"action":"insert","lines":["t"],"id":5486}],[{"start":{"row":116,"column":34},"end":{"row":116,"column":35},"action":"insert","lines":["="],"id":5487}],[{"start":{"row":116,"column":35},"end":{"row":116,"column":37},"action":"insert","lines":["\"\""],"id":5488}],[{"start":{"row":116,"column":37},"end":{"row":116,"column":38},"action":"insert","lines":[";"],"id":5489}],[{"start":{"row":135,"column":46},"end":{"row":136,"column":0},"action":"insert","lines":["",""],"id":5490},{"start":{"row":136,"column":0},"end":{"row":136,"column":24},"action":"insert","lines":["                        "]}],[{"start":{"row":136,"column":24},"end":{"row":136,"column":26},"action":"insert","lines":["''"],"id":5491}],[{"start":{"row":136,"column":25},"end":{"row":136,"column":26},"action":"insert","lines":["t"],"id":5492}],[{"start":{"row":136,"column":26},"end":{"row":136,"column":27},"action":"insert","lines":["i"],"id":5493}],[{"start":{"row":136,"column":27},"end":{"row":136,"column":28},"action":"insert","lines":["c"],"id":5494}],[{"start":{"row":136,"column":28},"end":{"row":136,"column":29},"action":"insert","lines":["k"],"id":5495}],[{"start":{"row":136,"column":29},"end":{"row":136,"column":30},"action":"insert","lines":["e"],"id":5496}],[{"start":{"row":136,"column":30},"end":{"row":136,"column":31},"action":"insert","lines":["t"],"id":5497}],[{"start":{"row":136,"column":32},"end":{"row":136,"column":33},"action":"insert","lines":[" "],"id":5498}],[{"start":{"row":136,"column":33},"end":{"row":136,"column":34},"action":"insert","lines":[":"],"id":5499}],[{"start":{"row":136,"column":34},"end":{"row":136,"column":35},"action":"insert","lines":[" "],"id":5500}],[{"start":{"row":136,"column":35},"end":{"row":136,"column":36},"action":"insert","lines":["t"],"id":5501}],[{"start":{"row":136,"column":36},"end":{"row":136,"column":37},"action":"insert","lines":["i"],"id":5502}],[{"start":{"row":136,"column":37},"end":{"row":136,"column":38},"action":"insert","lines":["c"],"id":5503}],[{"start":{"row":136,"column":38},"end":{"row":136,"column":39},"action":"insert","lines":["k"],"id":5504}],[{"start":{"row":136,"column":39},"end":{"row":136,"column":40},"action":"insert","lines":["e"],"id":5505}],[{"start":{"row":136,"column":40},"end":{"row":136,"column":41},"action":"insert","lines":["t"],"id":5506}],[{"start":{"row":136,"column":41},"end":{"row":136,"column":42},"action":"insert","lines":[","],"id":5507}],[{"start":{"row":66,"column":194},"end":{"row":66,"column":211},"action":"remove","lines":["Usuario ya existe"],"id":5508},{"start":{"row":66,"column":194},"end":{"row":66,"column":195},"action":"insert","lines":["T"]}],[{"start":{"row":66,"column":195},"end":{"row":66,"column":196},"action":"insert","lines":["i"],"id":5509}],[{"start":{"row":66,"column":196},"end":{"row":66,"column":197},"action":"insert","lines":["c"],"id":5510}],[{"start":{"row":66,"column":197},"end":{"row":66,"column":198},"action":"insert","lines":["k"],"id":5511}],[{"start":{"row":66,"column":198},"end":{"row":66,"column":199},"action":"insert","lines":["e"],"id":5512}],[{"start":{"row":66,"column":199},"end":{"row":66,"column":200},"action":"insert","lines":["t"],"id":5513}],[{"start":{"row":66,"column":200},"end":{"row":66,"column":201},"action":"insert","lines":[" "],"id":5514}],[{"start":{"row":66,"column":201},"end":{"row":66,"column":202},"action":"insert","lines":["Y"],"id":5515}],[{"start":{"row":66,"column":202},"end":{"row":66,"column":203},"action":"insert","lines":["a"],"id":5516}],[{"start":{"row":66,"column":203},"end":{"row":66,"column":204},"action":"insert","lines":[" "],"id":5517}],[{"start":{"row":66,"column":204},"end":{"row":66,"column":205},"action":"insert","lines":["f"],"id":5518}],[{"start":{"row":66,"column":205},"end":{"row":66,"column":206},"action":"insert","lines":["u"],"id":5519}],[{"start":{"row":66,"column":206},"end":{"row":66,"column":207},"action":"insert","lines":["e"],"id":5520}],[{"start":{"row":66,"column":207},"end":{"row":66,"column":208},"action":"insert","lines":[" "],"id":5521}],[{"start":{"row":66,"column":208},"end":{"row":66,"column":209},"action":"insert","lines":["u"],"id":5522}],[{"start":{"row":66,"column":209},"end":{"row":66,"column":210},"action":"insert","lines":["s"],"id":5523}],[{"start":{"row":66,"column":210},"end":{"row":66,"column":211},"action":"insert","lines":["a"],"id":5524}],[{"start":{"row":66,"column":211},"end":{"row":66,"column":212},"action":"insert","lines":["d"],"id":5525}],[{"start":{"row":66,"column":212},"end":{"row":66,"column":213},"action":"insert","lines":["o"],"id":5526}],[{"start":{"row":70,"column":193},"end":{"row":70,"column":194},"action":"remove","lines":["o"],"id":5527}],[{"start":{"row":70,"column":192},"end":{"row":70,"column":193},"action":"remove","lines":["i"],"id":5528}],[{"start":{"row":70,"column":191},"end":{"row":70,"column":192},"action":"remove","lines":["r"],"id":5529}],[{"start":{"row":70,"column":190},"end":{"row":70,"column":191},"action":"remove","lines":["a"],"id":5530}],[{"start":{"row":70,"column":189},"end":{"row":70,"column":190},"action":"remove","lines":["u"],"id":5531},{"start":{"row":70,"column":188},"end":{"row":70,"column":189},"action":"remove","lines":["s"]}],[{"start":{"row":70,"column":187},"end":{"row":70,"column":188},"action":"remove","lines":["u"],"id":5532}],[{"start":{"row":70,"column":186},"end":{"row":70,"column":187},"action":"remove","lines":[" "],"id":5533}],[{"start":{"row":70,"column":185},"end":{"row":70,"column":186},"action":"remove","lines":["e"],"id":5534}],[{"start":{"row":70,"column":184},"end":{"row":70,"column":185},"action":"remove","lines":["d"],"id":5535}],[{"start":{"row":70,"column":183},"end":{"row":70,"column":184},"action":"remove","lines":[" "],"id":5536}],[{"start":{"row":70,"column":182},"end":{"row":70,"column":183},"action":"remove","lines":["e"],"id":5537}],[{"start":{"row":70,"column":181},"end":{"row":70,"column":182},"action":"remove","lines":["r"],"id":5538}],[{"start":{"row":70,"column":180},"end":{"row":70,"column":181},"action":"remove","lines":["b"],"id":5539}],[{"start":{"row":70,"column":179},"end":{"row":70,"column":180},"action":"remove","lines":["m"],"id":5540}],[{"start":{"row":70,"column":178},"end":{"row":70,"column":179},"action":"remove","lines":["o"],"id":5541}],[{"start":{"row":70,"column":177},"end":{"row":70,"column":178},"action":"remove","lines":["N"],"id":5542}],[{"start":{"row":70,"column":176},"end":{"row":70,"column":177},"action":"remove","lines":[" "],"id":5543}],[{"start":{"row":70,"column":176},"end":{"row":70,"column":177},"action":"insert","lines":["T"],"id":5544}],[{"start":{"row":70,"column":177},"end":{"row":70,"column":178},"action":"insert","lines":["i"],"id":5545}],[{"start":{"row":70,"column":178},"end":{"row":70,"column":179},"action":"insert","lines":["c"],"id":5546}],[{"start":{"row":70,"column":179},"end":{"row":70,"column":180},"action":"insert","lines":["k"],"id":5547}],[{"start":{"row":70,"column":180},"end":{"row":70,"column":181},"action":"insert","lines":["e"],"id":5548}],[{"start":{"row":70,"column":181},"end":{"row":70,"column":182},"action":"insert","lines":["t"],"id":5549}],[{"start":{"row":70,"column":176},"end":{"row":70,"column":177},"action":"insert","lines":[" "],"id":5550}]]},"ace":{"folds":[],"scrolltop":2664,"scrollleft":52.0025,"selection":{"start":{"row":259,"column":28},"end":{"row":259,"column":28},"isBackwards":false},"options":{"guessTabSize":true,"useWrapMode":false,"wrapToView":true},"firstLineState":{"row":99,"state":"js-start","mode":"ace/mode/handlebars"}},"timestamp":1508427188518,"hash":"bc63f748ee63f7c8e395abaf55f3ba7df280a9c4"}