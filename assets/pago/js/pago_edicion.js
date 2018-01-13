/* variable que se usa para guardar el nuevo elemento
   que será creado dinamicamente
 */
var el;
$('html').keydown(function(e) {
  /* tecla F4 */
  if (e.keyCode == 115) {
    $('body').animate({
      scrollTop: $('#sel_vendedores_chzn').offset().top
    }, 800);
    $("#sel_vendedores_chzn>a").trigger('mousedown');
  }
});

//var startDate = new Date('01/01/2012');
var FromEndDate = new Date();
var ToEndDate = new Date();
ToEndDate.setDate(ToEndDate.getDate() + 365);

$('.calendari').datepicker({
	//setDate: new Date(),
	weekStart: 1,
    language: 'es',
    autoclose: 'true',
    startDate: '',
    endDate: '',
    format: 'dd/mm/yyyy',
    todayBtn: 'linked',
    todayHighlight: 'true'
});

	//startDate: '01/01/2012',
	//endDate: FromEndDate,

$.ajax({
  url: base_url() + 'vendedor/async_vendedor/getAllVendedores',
  data: {ajax: true},
  type: 'post',
  dataType: 'json',
  success: function(response) {
    vendedores = response.result;

    /*$('#caja-publicacion').empty();*/
    el = $('<select data-placeholder="Seleccione vendedor ..." id="sel-vendedores"></select>').append(function() {
      var output = '<option value=""></option>';
      $.each(vendedores, function(key, value) {
        output += '<option value="'+ key +'">' + value + '</option>';
      });
      return output;
    });
    $('#caja-publicacion').html(el);
    el.chosen();
    //el.on('change', function() {
    //$('.calendari').on('click', function() {
    $('.calendari').datepicker().on('changeDate', function(selected){
    	//alert(selected.date.valueOf());

    	/* we get the vendedor_id */
      vendedor_id = el.val();
      //fecha_i = selected.date.valueOf();
      fecha_i = $('.calendari').data('date');
      var coleccion;
      $.ajax({
        url: base_url() + 'pago/async_pago/get_pagos_edicion',
        data: {'ajax': true, 'vendedor_id' : vendedor_id, 'fecha_i' : fecha_i},
        type: 'post',
        dataType: 'json',
        async: false,
        success: function(response) {
          /* deudas */
          // console.log(response);
          /*
            agregamos esta funcion que será usada para renderizar un input o solo texto
            esto es debido a que sólo podemos realizar devoluciones 1 vez, y si hace amortización
            poder completar el resto luego pero sin la posibilidad de realizar más devoluciones
            esto para mantener consistencia en las operaciones.
           */
          var total_pagar = 0;
          $.each(response.result, function(key, value) {
            total_pagar += parseFloat(value.saldo);
            value.sidevuelve = function() {
              //return (this.estado != 'devuelto' && this.abonado == 0 && this.estado_remision == 'pendiente')
              //? '<input type="text" value="' + this.cantidad_devolucion + '">'
              //: '<div style="padding:0 7px">' + this.cantidad_devolucion + '</div>';
            	return '<input type="text" value="' + this.cantidad_devolucion + '">';
            }
          });

          // console.log($('.tb-total'));
          var template = $('#deudastpl').html();
          var html = Mustache.render(template, response);
          $('#resultados').html(html);

          /* seleccionar todo */
          $('#seleccionar-todo').click(function() {
            var el = $(this);
            if (el.hasClass('icon-check-empty')) {
              el.removeClass('icon-check-empty');
              el.addClass('icon-check');
              $('#resultados input[type="checkbox"]').prop('checked', true);
              $('.tb-total').val(calcular_totales());
            }
            else {
              el.removeClass('icon-unchecked');
              el.addClass('icon-check-empty');
              $('#resultados input[type="checkbox"]').prop('checked', false);
              $('.tb-total').val('0');
            }
          });

          $('.tb-total').val(total_pagar.toFixed(3));

          // console.log(total_pagar);
          /* luego de tener el tb total creado, adjuntamos el total */
          // console.log('se ejecuto ajax y mustache con exito');
        },
        error: function(response) {
          console.log('error');
          console.log(response.responseText);
        }
      })
      /* mostramos el id del elemento seleccionado */
      // console.log(this.value);
    })
  },
  error: function(response) {
    console.log('error');
    console.log(response.responseText);
  }
});

var precio_vendedor;
var total_a_cobrar;
var saldo_a_cobrar;
var repartido;
var fila;
var error = false;
$('.devolucion>input').live('focus', function() {
    fila = $(this).closest('tr');

    precio_vendedor = parseFloat(fila.attr('data-precio-vendedor'));
    total_a_cobrar = fila.find('.total');
    saldo_a_cobrar = fila.find('.saldo>input');
    repartido = parseInt(fila.find('.repartido').text());

    $(this).maskMoney({
      precision: 0,
      defaultZero: true,
      allowZero: true,
      thousands: '',
      decimal: ''
    });
    $(this).keyup(function(e) {
      var devuelto = parseInt($(this).val());
      /* si se enfoca y se limpia tenemos que setear a 0 */
      if (isNaN(devuelto)) devuelto = 0;
      /* controlamos si esta ingresando un valor válido o no */
      if (repartido < devuelto) {
        error = true;
        fila.find('td.devolucion').addClass('control-group error');
        fila.find('td.saldo').addClass('control-group error');
        fila.find('td.total').addClass('alert-error');
      }
      else {
        error = false;
        fila.find('td.devolucion').removeClass('control-group error');
        fila.find('td.saldo').removeClass('control-group error');
        fila.find('td.total').removeClass('alert-error');
      }

      var para_cobrar = (precio_vendedor * (repartido - devuelto)).toFixed(3);
      saldo_a_cobrar.val(para_cobrar);
      total_a_cobrar.text(para_cobrar);
      calcular_totales();
    });
});
$('.saldo>input').live('focus', function() {
  $(this).maskMoney({
    precision: 3,
    defaultZero: true,
    allowZero: true,
    thousands: '',
    decimal:'.'
  });
  fila = $(this).closest('tr');
  total_a_cobrar = fila.find('.total').text();
  // console.log('total a cobrar: ', total_a_cobrar);
  total_a_cobrar = parseFloat(total_a_cobrar);
  $(this).keyup(function() {
    saldo_a_cobrar = parseFloat($(this).val());
    if (isNaN(saldo_a_cobrar))
      saldo_a_cobrar = 0;
    if (total_a_cobrar < saldo_a_cobrar) {
      error = true;
      fila.find('td.total').addClass('alert-error');
      fila.find('td.saldo').addClass('control-group error');
    }
    else {
      error = false;
      fila.find('td.total').removeClass('alert-error');
      fila.find('td.saldo').removeClass('control-group error');
    }
    calcular_totales();
  })
})
$('.check-cobrar').live('click', function() {
    var saldo = parseFloat($(this).closest('tr').find('.saldo>input').val());
    saldo = isNaN(saldo) ? 0 : saldo;
    var total = parseFloat($('.tb-total').val());
    total = isNaN(total) ? 0 : total;
    if (this.checked)
      total += saldo;
    else
      total -= saldo;
    $('.tb-total').val(total.toFixed(3));
});

$('.btn-cobrar1').live('click', function() {
	if (error) {
		    alert('Tienes errores en las filas rojas, corríjelas primero');
		    return;
	}	
	$(this).closest('tbody').find('tr').each(function(index, value) {
	    
		var row = $(this);
		var checked = row.find('input[type="checkbox"]').is(':checked');
	    /* si el row no esta checkeado no realizamos ninguna operacion */
	    if (!checked) return;		
	    
	    var dpid = row.attr('data-dpid');
	    /* esto será usado para guardar en la tabla de devolucion */
	    var dev = row.find('.devolucion>input').val().trim();
	    /* esto será usado para guardar en la tabla de pagos */
	    var total = row.find('.total').text();
	    var abonado = row.find('.abonado').text();
	    var saldo = row.find('.saldo>input').val();
	    var checked = row.find('input[type="checkbox"]').is(':checked');		
		
		//edicion de la devolucion con los respectivos ids
	    if (dev.length > 0 && dev != '') {
	        $.ajax({
	          url: base_url() + 'devolucion/async_devolucion/devolver_editar',
	          data: {
	            'dpid' : dpid,
	            'cant_dev': dev,
	            'ajax': true
	          },
	          type: 'post',
	          dataType: 'json',
	          success: function(response) {
	            /* si hay para devolver, entonces anulamos el td con devolucion */
	            row.find('.devolucion').text(dev);
	          },
	          error: function(response) {
	            console.log('error');
	            console.log(response.responseText);
	          }
	        });
	    }
		
	});	
	
});

$('.btn-cobrar').live('click', function() {
  if (error) {
    alert('Tienes errores en las filas rojas, corríjelas primero');
    return;
  }

  $(this).closest('tbody').find('tr').each(function(index, value) {
    var row = $(this);
    var dpid = row.attr('data-dpid');
    /* esto será usado para guardar en la tabla de devolucion */
    var dev =row.find('.devolucion>input');
    /* esto será usado para guardar en la tabla de pagos */
    var total = row.find('.total').text();
    var abonado = row.find('.abonado').text();
    var saldo = row.find('.saldo>input').val();
    var checked = row.find('input[type="checkbox"]').is(':checked');

    /* si el row no esta checkeado no realizamos ninguna operacion */
    if (!checked) return;

    /*
    console.log('dpid: ', dpid);
    console.log('saldo: ', saldo);
    */
    if (dev.length > 0 && dev.val().trim() != '') {
      $.ajax({
        url: base_url() + 'devolucion/async_devolucion/devolver',
        data: {
          'dpid' : dpid,
          'cant_dev': dev.val().trim(),
          'ajax': true
        },
        type: 'post',
        dataType: 'json',
        success: function(response) {
          /* si hay para devolver, entonces anulamos el td con devolucion */
          row.find('.devolucion').text(dev.val().trim());
        },
        error: function(response) {
          console.log('error');
          console.log(response.responseText);
        }
      });
    }
    /* esta llamada será hecha para guardar el monto cobrado */
    if (parseFloat(saldo) != 0) {
      $.ajax({
        url: base_url() + 'pago/async_pago/cobrar',
        data: {
          'dpid': dpid,
          'abonado': abonado,
          'total': total,
          'monto_pago': saldo,
          'ajax': true
        },
        type: 'post',
        dataType: 'json',
        async: false,
        success: function(response) {
          // console.log('success :)');
          // console.log(response);
          if (response.cancelado)
            row.remove();
          else {
            /* todavia debe */
            saldo = parseFloat(saldo);

            abonado = parseFloat(abonado);
            total = parseFloat(total);

            /* el nuevo monto abonado */
            abonado = saldo + abonado;

            row.find('.abonado').text(abonado.toFixed(3));
            row.find('.saldo>input').val((total - abonado).toFixed(3));
          }
        },
        error: function(response) {
          console.log('error :(');
          console.log(response.responseText);
        }
      });
    }
    else {
      row.find('.saldo>input').val(total).focus();
    }
  });
  /*$("#myModal").modal();
  return;*/
  calcular_totales();
});
$('.btn-cobrar').live('hover', function() {
  $(this).addClass('btn-success');
}).live('mouseout', function() {
  $(this).removeClass('btn-success');
})

function calcular_totales() {
  var total = 0;
  $('#resultados>table>tbody').find('tr').each(function(index, value) {
    var row = $(this);
    var checked = row.find('input[type="checkbox"]').is(':checked');
    if (checked) {
      var currentAmount = parseFloat(row.find('.saldo>input').val().trim());
      currentAmount = isNaN(currentAmount) ? 0 : currentAmount;
      total += currentAmount;
    }
  });
  $('.tb-total').val(total.toFixed(3));
  return total.toFixed(3);
}
