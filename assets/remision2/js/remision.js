function getDetailFields () {
    fields = {};
    fields.publicacion_id = $('#descripcion').val();
    fields.descripcion = $('#descripcion option:selected').text();
    fields.cantidad = $('#cantidad').val().trim();
    fields.unidadMedida = $('#unidad-medida').val().trim();
    fields.precioUnitarioGuia = $('#precio-unitario-guia').val().trim();
    fields.precioUnitarioCalculado = $('#precio-unitario-calculado').val().trim();
    fields.importe = $('#importe').val().trim();
    return fields;
}
function validar_nuevo_detalle() {
    /* limpiamos nuestra caja de mensajes */
    $('#caja-validaciones').empty();
    var ok = true;
    var fields = getDetailFields();
    /* nos aseguramos que todos los campos estan seteados */
    for (var field in fields) {
        ok = fields[field] != '' && ok;
        /* si ya encontramos un campo que
        esta vacio ya no seguimos buscando y hacemos break */
        if (!ok) break;
    };
    if (!ok) {
        msg = '\
        <div class="alert alert-danger">\
            <button type="button" class="close" data-dismiss="alert">&times;</button>\
            todos los campos son requeridos\
        </div>\
        ';
        $('#caja-validaciones').html(msg);
    }
    return ok;
}
function insertarDetail() {
    fields = getDetailFields();
    $('table#details').append('<tr>\
                    <td>\
                        '+ fields['descripcion'] +'\
                        <input type="hidden" name="detalle[id][]" value="" />\
                        <input type="hidden" name="detalle[descripcion][]" value="'+ fields['descripcion'] +'" />\
                        <input type="hidden" name="detalle[publicacion_id][]" value="'+ fields['publicacion_id'] +'" />\
                    </td>\
                    <td>\
                        '+ fields['cantidad'] +'\
                        <input type="hidden" name="detalle[cantidad][]" value="'+ fields['cantidad'] +'" />\
                    </td>\
                    <td>\
                        '+ fields['unidadMedida'] +'\
                        <input type="hidden" name="detalle[unidadMedida][]" value="'+ fields['unidadMedida'] +'" />\
                    </td>\
                    <td>\
                        '+ fields['precioUnitarioGuia'] +'\
                        <input type="hidden" name="detalle[precioUnitarioGuia][]" value="'+ fields['precioUnitarioGuia'] +'" />\
                    </td>\
                    <td>\
                        '+ fields['precioUnitarioCalculado'] +'\
                        <input type="hidden" name="detalle[precioUnitarioCalculado][]" value="'+ fields['precioUnitarioCalculado'] +'" />\
                    </td>\
                    <td>\
                        '+ fields['importe'] +'\
                        <input type="hidden" name="detalle[importe][]" value="'+ fields['importe'] +'" />\
                    </td>\
                    <td><button class="btn btn-danger cancel-detail">&times;</button></td>\
                </tr>');
}
$(document).ready(function(){
    /* de bot */
    /* inicializamos todos los campos a vacio */
    $('#to-add input').attr('disabled', 'disabled');
    $('#aniadir').on('click', function() {
        var texto = $(this).text().trim();
        if (texto == 'Nuevo') {
            $(this).text('Añadir');
            $(this).addClass('btn-success');
            $('#to-add input').removeAttr('disabled');
        }
        else { /* es añadir, tenemos que validar y procesar los campos */
            var ok = validar_nuevo_detalle();
            /* si no valido terminamos la ejecucion
            y hacemos un display de los errores encontrados */
            if (!ok) return false;
            insertarDetail();
            $(this).text('Nuevo');
            $(this).removeClass('btn-success');
            $('#to-add input').val('');
            $('#to-add input').attr('disabled', 'disabled');
        }
        /* nos aseguramos que tenemos el foco en la descripcion
        por si es que quiere seguir agregando más elementos */
        $('#descripcion').focus();
        return false;
    });
    /* esto lo usaremos para eliminar el row que no necesitemos */
    $('.cancel-detail').live('click', function() {
        $(this).closest('tr').remove();
        return false;
    });
    $('.proveedor').change(function() {
        $('#descripcion>option').remove();
        var proveedor_id = $(this).val();
        $.ajax({
            url: base_url() + 'proveedor/getPublicacionesByProveedor/' + proveedor_id,
            data: {'ajax': true},
            type: 'post',
            dataType: 'json',
            success: function(response) {
                var publicaciones = response["publicaciones"];
                for (var key in publicaciones) {
                    var value = publicaciones[key];
                    var opt = $('<option />');
                    console.log(key, value);
                    opt.val(key);
                    opt.text(value);
                    $('#descripcion').append(opt);
                }
                console.log("exito :)");
                console.log(response);
            },
            error: function(response) {
                console.log("error :(");
                console.log(response);
            }
        });
    });

    /* de rosita */
    $(".proveedor").change(function() {
            Actualizar_Precios();
    });
       $("#fecha_recepcion").change(function() {
            Actualizar_Precios();
    });

    $(".cantidad").live('keyup', function() {
    	var total, multi;
    	$(".cantidad").each(function(index, obj) {
    		total = $(this).val() * $(".precio").eq(index).val();
    		multi = parseFloat(total).toFixed(2);
    		$(".importe").eq(index).val(multi);
    	});
    	var suma = 0.0;
    	$(".importe").each(function(i, obj) {
    		//suma += $(".importe").eq(i).val();
    		suma += parseFloat($(this).val());
    		//alert(suma);
    		$(".total").val(suma.toFixed(2));
    		//$(this).val($(this).val() * $(".precio").val());
    	});
    	//$(".total").val(suma);
    });

   //devoluciones mostrar datos
    $(".proveedor_deva").change(function(){
    	//var proveedor_id = $(this).val();
    	var proveedor_id = $(".proveedor_dev").val();
        $.ajax({
            type: "post",
            //url: base_url() + "remision/get_publicaciones_proveedor/" + pais_id,
            url: base_url() + "remision/get_remisiones/" + proveedor_id,
            dataType: "json",
            success: mostrar_remisiones
        })
    });

    //ingresar nro de devoluciones
    $(".cantidad_devolucion").live('keyup', function(){
    	var recibido, total_pagar, suma_devolucion = 0, suma_total_pagar = 0;
    	this.value = this.value.replace(/[^0-9]/g,'');
    	$(".cantidad_devolucion").each(function(index, obj) {
    		recibido = $(".cantidad").eq(index).val() - $(this).val();
    		//recibido = parseFloat(recibido).toFixed(2);
    		//total_pagar = $(".recibido").eq(index).val() * $(".precio").eq(index).val();
    		total_pagar = recibido * $(".precio").eq(index).val();
    		suma_total_pagar += total_pagar;
    		total_pagar = parseFloat(total_pagar).toFixed(2);
    		$(".recibido").eq(index).val(recibido);
    		$(".importe_neto").eq(index).val(total_pagar);
    		suma_devolucion += parseInt($(this).val());
    	});

    	/*$(".cantidad_devolucion").each(function(i, obj) {
    		//suma += $(".importe").eq(i).val();
    		suma_devolucion += parseFloat($(this).val());
    		$(".total").val(suma.toFixed(2));
    		//$(this).val($(this).val() * $(".precio").val());
    	});*/
    	$(".cantidad_total_devuelta").val(suma_devolucion);
    	$(".total").val(suma_total_pagar.toFixed(2));
    });

        Actualizar_Precios();
});

function mostrar_publicaciones(publicaciones) {
    var publicacion;
    var $tabla = $("#pub_seleccionadas");
    $tabla.find("tr:gt(0)").remove();
    for (var filas in publicaciones)
    {
    	publicacion = publicaciones[filas];
       var  descuento_aplicado = publicacion.porcentaje_descuento_dia_normal;
       // convertir de json a texto plano
       var fecha_recepcion = $("#fecha_recepcion").val();
       var fecha_d = fecha_recepcion.split('/');
       var fecha = new Date(fecha_d[2]+'-'+fecha_d[1]+'-'+fecha_d[0] );
       var fecha_final ="error";
       fecha = getDayName(fecha);
       if (publicacion.porcentaje_descuento_especial > descuento_aplicado) {
        descuento_aplicado = publicacion.porcentaje_descuento_especial;
        fecha = publicacion.fecha_especial_nombre;
       }
    	 $("#pub_seleccionadas").append(
            "<tr><td class='id'><input type='hidden' name='publicacion[]' class='publicacion' value='"+ publicacion.publicacion_id + "'/></td><td >"+fecha+" </td><td >"+publicacion.precio_publico+"</td><td >"+descuento_aplicado+"%</td>  <td >"+publicacion.comision+
            "%</td><td class='nombre'>" + publicacion.publicacion_nombre + "</td><td> <input type='text' name='cantidad[]' class='cantidad currency' value='0' /> </td><td> <input type='text' name='precio[]' readonly class='precio' value='"+publicacion.costo_unitario_final+"' /> </td><td> <input type='text' class='importe' readonly name='importe[]' value='0.00' /></tr>");
    }

      $('.currency').maskMoney({
                    precision: 0,
                    defaultZero: false,
                    thousands:'',
                    decimal:'.'
                });
}

function mostrar_remisiones1(remisiones) {
    var remision, cantidad_total = 0, total_importe = 0;
    var $tabla = $("#pub_seleccionadas");
    $tabla.find("tr:gt(0)").remove();
    for (var filas in remisiones)
    {
    	remision = remisiones[filas];
    	$("#pub_seleccionadas").append(
    	"<tr><td class='id'><input type='text' name='publicacion[]' class='publicacion' value='"+ remision.publicacion_id + "' /></td>" +
    	"<td class='nombre'>" + remision.nombre + "</td>" +
    	"<td> <input type='text' name='cantidad[]' readonly class='cantidad' value='" + remision.cantidad + "' /> </td>" +
        "<td> <input type='text' name='precio[]' readonly class='precio' value='" + remision.precio_unitario + "' /> </td>" +
        "<td> <input type='text' class='importe' readonly name='importe[]' value='" + remision.importe + "' /></td>" +
        "<td> <input type='text' class='cantidad_devolucion' name='cantidad_devolucion[]' value='0' /></td>" +
        "<td> <input type='text' class='recibido' name='recibido[]' value='0' /></td>" +
        "<td> <input type='text' class='importe_neto' name='importe_neto[]' value='0' /></td>" +
        "</tr>");
    	cantidad_total += parseInt(remision.cantidad);
    	total_importe += parseFloat(remision.importe);
    }
    $(".cantidad_total_recibida").val(cantidad_total);
    $(".total_importe").val(total_importe.toFixed(2));
}
function getDayName(date)
{
    var days= ["Lunes","Martes","Miércoles","Jueves","Viernes","Sábado","Domingo"];
    return days[date.getDay()];

}
function Actualizar_Precios()
{
    if ($('#fecha_recepcion').val()!="" && $(".proveedor").val() !="")
    {
        if (ID==-1) {

            var $tabla = $("#pub_seleccionadas");
            $tabla.find("tr:gt(0)").remove();
            var proveedor_id = $(".proveedor").val();
            var fecha_recepcion = $("#fecha_recepcion").val();
            var aux = fecha_recepcion.split('/');
            fecha_recepcion =aux[0]+'-'+aux[1]+'-'+aux[2];
            if (proveedor_id == "") proveedor_id = "0";
            //alert(proveedor_id);
            $.ajax({
                type: "post",
                //url: base_url() + "remision/get_publicaciones_proveedor/" + pais_id,
                url: base_url() + "remision/get_publicaciones_proveedor/" + proveedor_id+"/"+fecha_recepcion,
                dataType: "json",
                success: mostrar_publicaciones
            })
        }
        else // modificacion
        {

            var $tabla = $("#pub_seleccionadas");
            $tabla.find("tr:gt(0)").remove();
            var proveedor_id = $(".proveedor").val();
            var fecha_recepcion = $("#fecha_recepcion").val();
            var aux = fecha_recepcion.split('/');
            fecha_recepcion =aux[0]+'-'+aux[1]+'-'+aux[2];
            if (proveedor_id == "") proveedor_id = "0";
            //alert(proveedor_id);
            $.ajax({
                type: "post",
                //url: base_url() + "remision/get_publicaciones_proveedor/" + pais_id,
                url: base_url() + "remision/get_publicaciones_proveedor_edicion/" +ID+"/",
                dataType: "json",
                success: mostrar_publicaciones_edicion
            })

        }
    }
}
function obtener_descuentos_publicacion(publicacion_id,fecha_recepcion,publicacion)
{
    var pub =[];
    console.log("pub vacio");
      $.ajax({
            type: 'post',
            url: base_url() + "remision/get_comisiones_detalles_remision/" +publicacion_id+"/"+fecha_recepcion,
            dataType: 'json',
            success: function(publicacion) {
                pub = publicacion;
                console.log('pub asignacion');
            },
            error: function(publicacion) {
                console.log("erro pub")

            }
        });
   console.log("pub final");
    return pub;

}
function mostrar_publicaciones_edicion(detalles_remision) {
    var detalle_remision;
    var $tabla = $("#pub_seleccionadas");
    $tabla.find("tr:gt(0)").remove();
    var fecha_recepcion = $("#fecha_recepcion").val();
   var fecha_d = fecha_recepcion.split('/');
   var fecha = new Date(fecha_d[2]+'-'+fecha_d[1]+'-'+fecha_d[0] );
   var fecha_final ="error";
   fecha = getDayName(fecha);

    var fecha_recepcion = $("#fecha_recepcion").val();
    var aux = fecha_recepcion.split('/');
    fecha_recepcion =aux[0]+'-'+aux[1]+'-'+aux[2];
    var importe_total = 0;
    for (var filas in detalles_remision)
    {
         detalle_remision = detalles_remision[filas];

         var publicacion = obtener_descuentos_publicacion(detalle_remision.publicacion_id,fecha_recepcion);

            var  descuento_aplicado = publicacion.porcentaje_descuento_dia_normal;
                if (publicacion.porcentaje_descuento_especial > descuento_aplicado) {
                descuento_aplicado = publicacion.porcentaje_descuento_especial;
                fecha = publicacion.fecha_especial_nombre;
            }

        $("#pub_seleccionadas").append("<tr><td class='id'><input type='hidden' name='publicacion[]' class='publicacion' value='"+ detalle_remision.publicacion_id + "'/></td><td >"+fecha+" </td><td >"+publicacion.precio_publico+"</td><td >"+descuento_aplicado+"%</td>  <td >"+publicacion.comision+"%</td><td class='nombre'>" + detalle_remision.descripcion + "</td><td> <input type='text' name='cantidad[]' class='cantidad currency' value='"+detalle_remision.cantidad+"' /> </td><td> <input type='text' name='precio[]' readonly class='precio' value='"+detalle_remision.precio_unitario_guia+"' /> </td><td> <input type='text' class='importe' readonly name='importe[]' value='"+detalle_remision.importe+"' /></tr>");
        importe_total += parseFloat(detalle_remision.importe);

    }

      $('.total').maskMoney({
                    precision: 2,
                    defaultZero: false,
                    thousands:'',
                    decimal:'.'
                });
        $('.total').attr('value',importe_total)  ;
      $('.currency').maskMoney({
                    precision: 0,
                    defaultZero: false,
                    thousands:'',
                    decimal:'.'
                });
}


