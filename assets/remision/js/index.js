$("td ul.dropdown-menu a").on('click', function() {
	$this = $(this);
	if ($this.text() == "Pagado")  {
		bootbox.confirm("¿Está seguro de cambiar la guia de remision\
				a este estado?, recuerde que no podra modificar ni cambiar ninguna remision,\
				este cambio es permanente e irrevertible?", function(result) {
			  	if (!result) return;
				procesar_estado($this);
			});
	}else{
		procesar_estado($this);
	}
});

function procesar_estado($this) {
	/* para subir li, ul y seleccionar ul */
	var ul = $this.closest('.dropdown-menu');
	var remision_id = ul.attr('data-remision-id');
	
	console.log($.trim($this.text()), remision_id);
	/* vamos a cambiar los estados en sus respectivos
	registros en la base de datos */
	$.ajax({
		url : base_url() + 'remision/cambiar_estado_ajax',
		data : {estado: $.trim($this.text()), "remision_id" : remision_id},
		type: "post",
		dataType: "json",
		success: function(response) {
			console.log(response.query);
			if(response.estado == 'ok'){
			//todo ok
				bootbox.alert('Estado cambiado correctamente.');
			}//console.log(response.estado);
			else
				bootbox.alert(response.estado);
		},
		error: function(response) {
			//console.log("error");
			//alert("No se puede cambiar de estado a la Encuesta");
			bootbox.alert("No se puede cambiar de estado de la Remision, intentelo nuevamente.");
		}
	})
}