$(function() {
	$('.ver-pauta').on('click', function() {
		$this =$(this);
		var remision_id = $this.attr('data-remision-id');
        var proveedor = $this.attr('data-proveedor');
		var idproveedor = $this.attr('data-idproveedor');
		var curdate = $this.closest('.remisiones').attr('data-curdate');

		/* nos aseguramos que no existe la remision
		porque si existe entonces tenemos que enviarle a
		la vista de mantenimiento de nuevo
		 */

		var ok = existe_pauta_de_remision({'remision_id': remision_id, 'ajax': true});
		/* "ok" será falso o devolverá un valor positivo (pauta_id) si es que existe */
		var form = document.createElement("form");
		form.setAttribute("method", "post");
		form.style.display ="none";

		if (!ok) {
			form.setAttribute("action", "pauta/generar");
			var input_remision_id = document.createElement('input');
			input_remision_id.setAttribute('type', 'hidden');
			input_remision_id.setAttribute('value', remision_id);
			input_remision_id.setAttribute('name', 'remision_id');

			var input_proveedor = document.createElement('input');
            input_proveedor.setAttribute('type', 'hidden');
            input_proveedor.setAttribute('value', proveedor);
            input_proveedor.setAttribute('name', 'proveedor');

            var input_idproveedor = document.createElement('input');
			input_idproveedor.setAttribute('type', 'hidden');
			input_idproveedor.setAttribute('value', idproveedor);
			input_idproveedor.setAttribute('name', 'idproveedor');


			var input_curdate = document.createElement('input');
			input_curdate.setAttribute('type', 'hidden');
			input_curdate.setAttribute('value', curdate);
			input_curdate.setAttribute('name', 'curdate');

			form.appendChild(input_remision_id);
            form.appendChild(input_proveedor);
			form.appendChild(input_idproveedor);
			form.appendChild(input_curdate);
		}
		else {
			form.setAttribute("action", "listado/pauta_id/" + ok + "/remision_id/" + remision_id);
		}
		this.appendChild(form);
		form.submit();
		return false;
	});
	$('.datepicker').datepicker({
        language: 'es',
        minViewMode: 'days',
        autoclose: true,
        format: 'dd/mm/yyyy',
        endDate: '+2d'
    }).on('changeDate', function(ev) {
        var f = ev.date;
        var selDate = new Date(f.getUTCFullYear(), f.getUTCMonth(), f.getUTCDate());

        var curDate = $('.remisiones').attr('data-curdate') || '';
        var fecha = curDate.split('/');

        curDate  = new Date(fecha[2], fecha[1] - 1, fecha[0]);
        if (curDate.getTime() != selDate.getTime()) {
            $('.remisiones').hide();
        }
        else {
            $('.remisiones').show();
        }

    });
    $('.ver-pauta').tooltip({placement: 'right'});
});
function existe_pauta_de_remision(params) {
	var res = null;
	$.ajax({
		url: base_url() + 'pauta/existe_pauta_de_remision',
		data: params,
		type: 'post',
		dataType: 'json',
		async: false,
		cache: false,
		success: function(response) {
			res = response;
		},
		error: function(response) {
			console.log(response);
			console.log("sucedio un error");
		}
	});
	return res != null ? res.ok : false;
}