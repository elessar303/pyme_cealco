function anularFactura(idFac)
{
	if(confirm("SEGURO DESEA ANULAR ESTE DOCUMENTO???"))
	{
		$.ajax({
			type: 'GET',
			data: 'opt=anularFactura&idFac='+idFac,
			url:  '../../libs/php/ajax/ajax.php',
			beforeSend: function(){
				document.getElementById("loading").style.visibility = 'visible';
	// 			$("#items").find("option").remove();
	// 			$("input[name='anticipo']").val("Cargando..");
			},
			success: function(data){
				location.reload()
				document.getElementById("loading").style.visibility = 'hidden';
			}
		});
	}
}
