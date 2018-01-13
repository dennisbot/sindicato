function calcular_ganancia_sindicato() {
    var ganancia_total_sindicato = 0;
    $(".precio_vendedor").each(function(index, obj) {
        var ganancia = ($(this).val() - $(".precio_guia").eq(index).val())*$(".cantidad").eq(index,obj).val();
        //console.log('ganancia',$(this).val());
        ganancia = ganancia.toFixed(3);
        ganancia_total_sindicato +=parseFloat(ganancia);
        $(".ganancia_sindicato").eq(index).val(ganancia);
    });

    $(".ganancia_total_sindicato").val(ganancia_total_sindicato.toFixed(3));
}
$(document).ready(function() {
  var total_calculado = 0;

  verifica_cantidades();

      $(".importe").each(function(index, obj) {
        total_calculado += parseFloat($(this).val());
      });

    //suma total al editar
    $('.total').attr('value', total_calculado.toFixed(3));

    $(".proveedor").change(function() {

            Actualizar_Precios();
    });
       $("#fecha_recepcion").change(function() {
            Actualizar_Precios();
    });

    $(".cantidad").live('focusout focusin blur', function() {
    	$(".cantidad").each(function(index, obj) {
        	//if($(this).val().length === 0)
    		if($(this).val() === '')
        		$(this).val('0');
    	});
    });

    $(".cantidad").live('keyup blur', function() {
    	var total, multi, cantidad_recibida = 0.000;
    	var correcto = false;
        $(".cantidad").each(function(index, obj) {
            correcto |= ($(this).val() > 0) ? true : false;
            total = $(this).val() * $(".precio_guia").eq(index).val();
            multi = parseFloat(total).toFixed(3);
            //suma de cantidades recibidas
            cantidad_recibida += parseInt($(this).val());
            $(".cantidad_total").val(cantidad_recibida);
            $(".importe").eq(index).val(multi);
            $('#existen_detalles_remision').attr('value', correcto);
        });
        //$('#existen_detalles_remision').attr('value', correcto);
        var suma = 0.0;
        $(".importe").each(function(i, obj) {
            //suma += $(".importe").eq(i).val();
            suma += parseFloat($(this).val());
            $(".total").val(suma.toFixed(3));
        });
        calcular_ganancia_sindicato();
    });

    //calculo al colocar el nuevo precio
    $(".precio_guia").live('keyup blur', function() {
    	var multi = 0.0, precio_vendedor = 0;

      $(".precio_guia").each(function(index, obj) {
	        total = $(this).val() * $(".cantidad").eq(index).val();
	        multi = parseFloat(total).toFixed(3);

	        if($(".precio_guia").eq(index).val() != 0) {
	        	var ganancia = ($(".precio_vendedor").eq(index).val() - $(".precio_guia").eq(index).val())*$(".cantidad").eq(index,obj).val();
	        	ganancia = ganancia.toFixed(3);
	        	precio_vendedor = parseFloat($(".precio_guia").eq(index).val()) + parseFloat($(".comision").eq(index).val() / 100 * $(".precio_publico").eq(index).val());
	        }
	        else {
	        	ganancia = 0.000;
	        }
	        //console.log(precio_vendedor);

	        $(".importe").eq(index).val(multi);
	        $(".ganancia_sindicato").eq(index).val(ganancia);
	        $(".precio_vendedor").eq(index).val("");
	        precio_vendedor = parseFloat(precio_vendedor).toFixed(3);
	        $(".precio_vendedor").eq(index).val(precio_vendedor);
       });
        calcular_ganancia_sindicato();
        var suma = 0.0;
        $(".importe").each(function(i, obj) {
        //suma += $(".importe").eq(i).val();
        suma += parseFloat($(this).val());
            $(".total").val(suma.toFixed(3));
        });
    });

    $(".precio_guia").live('focusout focusin blur clickoutside', function(){
    	$(".precio_guia").each(function(index, obj) {
        	//if($(this).val().length === 0)
    		if($(this).val() === '')
        		$(this).val('0.000');
    	});
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
    	$(".total").val(suma_total_pagar.toFixed(3));
    });

        // Actualizar_Precios();
});
function mostrar_publicaciones(publicaciones) {

    var publicacion, i = 1;
    var $tabla = $("#pub_seleccionadas");
    $tabla.find("tr:gt(0)").remove();
    for (var filas in publicaciones)
    {
    	publicacion = publicaciones[filas];
    	var  descuento_aplicado = publicacion.porcentaje_descuento_dia_normal;
    	//convertir de json a texto plano
    	var fecha_recepcion = $("#fecha_recepcion").val();
    	var fecha_d = fecha_recepcion.split('/');
    	var fecha = new Date(fecha_d[2]+'-'+fecha_d[1]+'-'+fecha_d[0] );
    	var fecha_final ="error";
    	fecha = getDayName(fecha);
    	if (publicacion.porcentaje_descuento_especial > descuento_aplicado) {
    		descuento_aplicado = publicacion.porcentaje_descuento_especial;
    		fecha = publicacion.fecha_especial_nombre;
    	}
    	//precio vendedor y ganancia sindicato
    	var precio_vendedor = parseFloat(publicacion.costo_unitario_final) + (( parseFloat(publicacion.comision)/100) * parseFloat(publicacion.precio_publico));
		$("#pub_seleccionadas").append("\
        <tr>\
            <td class='id'>"+i+"<input type='hidden' name='detalle[id][]' class='id' value='-1'/>\
                <input type='hidden' name='detalle[publicacion_id][]' class='publicacion' value='"+ publicacion.publicacion_id + "'/>\
            </td>\
            <td>\
                <input type='hidden' name='detalle[fecha][]' class='fecha' value='"+fecha+"'/>"+fecha+"\
            </td>\
            <td>\
                <input type='hidden' name='detalle[precioPublico][]' class='precio_publico' value='"+publicacion.precio_publico+"'/>"+publicacion.precio_publico+"\
            </td>\
            <td>\
                <input type='hidden' name='detalle[descuentoAplicado][]' class='publicacion' value='"+descuento_aplicado+"'/>" +descuento_aplicado+"%\
            </td>\
            <td>\
                <input type='hidden' name='detalle[comision][]' class='comision' value='"+publicacion.comision+"'/>"+publicacion.comision+"%\
            </td>\
            <td class='nombre'>\
                <input type='hidden' name='detalle[nombrePublicacion][]' class='publicacion' value='"+publicacion.publicacion_nombre+"'/>" + publicacion.publicacion_nombre + "\
            </td>\
            <td>\
                <input type='text' name='detalle[precio_vendedor][]' class='precio_vendedor currency' value='"+parseFloat(precio_vendedor).toFixed(3)+"' readonly='readonly' />\
            </td>\
            <td>\
                <input type='text' name='detalle[cantidad][]' class='cantidad currency_cantidad' value='0' />\
            </td>\
            <td>\
                <input type='text' name='detalle[precioUnitarioGuia][]'  class='precio_guia currency' value='0.000' style='background-color:rgba(185, 74, 72,0.3);' />\
            </td>\
            <td>\
                <input type='text' name='detalle[precioUnitarioCalculado][]' readonly class='precio' value='"+parseFloat(publicacion.costo_unitario_final).toFixed(3)+"' style='background-color:rgba(112, 179, 105, 8);color:#FFF;' />\
            </td>\
            <td>\
                <input type='text' name='detalle[ganancia_sindicato][]' class='ganancia_sindicato currency' value='0.000' readonly />\
            </td>\
            <td>\
                <input type='text' class='importe currency' readonly name='detalle[importe][]' value='0.000' />\
            </td>\
        </tr>");
		i += 1;
    }

      $('.currency').maskMoney({
                    precision: 3,
                    defaultZero: false,
                    allowZero: true,
                    thousands:'',
                    decimal:'.'
                });
       $('.currency_cantidad').maskMoney({
                    precision: 0,
                    defaultZero: false,
                    allowZero: true,
                    thousands:'',
                    decimal:'.'
                });

}

function getDayName(date)
{
    var days= ["Lunes","Martes","Miércoles","Jueves","Viernes","Sábado","Domingo"];
    return days[date.getDay()];

}
function Actualizar_Precios()
{
    if ($('#fecha_recepcion').val()!="" && $(".proveedor").val() !="") {
        if (ID == -1) {
            var $tabla = $("#pub_seleccionadas");
            $tabla.find("tr:gt(0)").remove();
            var proveedor_id = $(".proveedor").val();
            var fecha_recepcion = $("#fecha_recepcion").val();
            var aux = fecha_recepcion.split('/');
            fecha_recepcion =aux[0]+'-'+aux[1]+'-'+aux[2];
            if (proveedor_id == "") proveedor_id = "0";
            $.ajax({
                type: "post",
                //url: base_url() + "remision/get_publicaciones_proveedor/" + pais_id,
                url: base_url() + "remision/get_publicaciones_proveedor/" + proveedor_id+"/"+fecha_recepcion,
                dataType: "json",
                success: mostrar_publicaciones,
                error: function(response) {
                    console.log('error');
                    console.log(response);
                }
            })
        }
    }
    if (ID >-1) {
        var $tabla = $("#pub_seleccionadas");
        $tabla.find("tr:gt(0)").remove();
        var proveedor_id = $(".proveedor").val();
        var fecha_recepcion = $("#fecha_recepcion").val();
        var aux = fecha_recepcion.split('/');
        fecha_recepcion =aux[0]+'-'+aux[1]+'-'+aux[2];
        if (proveedor_id == "") proveedor_id = "0";

        $.ajax({
            type: "post",
            //url: base_url() + "remision/get_publicaciones_proveedor/" + pais_id,
            url: base_url() + "remision/get_publicaciones_proveedor_edicion/" +ID+"/",
            dataType: "json",
            success: mostrar_publicaciones_edicion,
            error: function(response) {
                console.log('error :(');
                console.log(response);
            }
        })

    }

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

        $("#pub_seleccionadas").append("\
        <tr>\
            <td class='id'>\
                <input type='hidden' name='publicacion[]' class='publicacion' value='"+ detalle_remision.publicacion_id + "'/>\
            </td>\
            <td>" + fecha + "</td>\
            <td>" + publicacion.precio_publico + "</td>\
            <td>" + descuento_aplicado + "%</td>\
            <td>" + publicacion.comision + "%</td>\
            <td class='nombre'>" + detalle_remision.descripcion + "</td>\
            <td>\
                <input type='text' name='cantidad[]' class='cantidad currency' value='"+detalle_remision.cantidad+"' />\
            </td>\
            <td>\
                <input type='text' name='precio[]' readonly class='precio' value='"+detalle_remision.precio_unitario_guia+"' />\
            </td>\
            <td>\
                <input type='text' class='importe' readonly name='importe[]' value='"+detalle_remision.importe+"' />\
            </td>\
            </tr>");
        importe_total += parseFloat(detalle_remision.importe);
    }

    $('.total').maskMoney({
        precision: 2,
        defaultZero: false,
        thousands:'',
        decimal:'.'
    });
    $('.total').attr('value', importe_total);
    $('.currency').maskMoney({
        precision: 0,
        defaultZero: false,
        thousands:'',
        decimal:'.'
    });
}

function obtener_descuentos_publicacion(publicacion_id,fecha_recepcion,publicacion)
{
      $.ajax({
            type: 'post',
            url: base_url() + "remision/get_comisiones_detalles_remision/" +publicacion_id+"/"+fecha_recepcion,
            dataType: 'json',
            success: function(publicacion) {
                console.log('exito :)');
                console.log(publicacion);
            },
            error: function(publicacion) {
                console.log('error :(');
                console.log(publicacion);
            }
        });
}

function verifica_cantidades() {

	var correcto = false;
	$(".cantidad").each(function(index, obj) {
        correcto |= ($(this).val() > 0) ? true : false;
        $('#existen_detalles_remision').attr('value', correcto);
    });
}



