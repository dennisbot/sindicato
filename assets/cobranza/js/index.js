/*
  this will be use to avoid retrieving the same action
  if it's selected the same again
*/
var last_chosen = "";
var subitems = $('.subitems');
var buscar_por = '';
/* variable que se usa para guardar el nuevo elemento
   que será creado dinamicamente
 */
var el;
subitems.hide();
$('input[type=radio]').on('click', function() {
  var checked = $('#proveedor').is(':checked');
  if (!checked)
    subitems.hide();
  else
    subitems.show();
  //console.log('asdf ' + Math.floor((Math.random() * 10) + 1));
  buscar_por = this.value;
  switch(buscar_por) {
    case 'vendedor':
      /* si esta accediendo otra vez a la misma acción
        no volver a cargar lo mismo otra vez
       */
      if (last_chosen == 'vendedor') break;
      last_chosen = 'vendedor';

      $.ajax({
        url: base_url() + 'vendedor/async_vendedor/getAllVendedores',
        data: {ajax: true},
        type: 'post',
        dataType: 'json',
        success: function(response) {
          vendedores = response.result;

          /*$('#caja-publicacion').empty();*/
          el = $('<select data-placeholder="Seleccione vendedor ..."></select>').append(function() {
            var output = '<option value=""></option>';
            $.each(vendedores, function(key, value) {
              output += '<option value="'+ key +'">' + value + '</option>';
            });
            return output;
          });
          $('#caja-publicacion').html(el);
          el.chosen();
          el.on('change', function() {
            /* we get the vendedor_id */
            vendedor_id = this.value;
            var coleccion;
            $.ajax({
              url: base_url() + 'vendedor/async_vendedor/getDeudasVendedor',
              data: {'ajax': true, 'vendedor_id' : vendedor_id},
              type: 'post',
              dataType: 'json',
              async: false,
              success: function(response) {
                /* deudas */
                console.log(response);
                /*
                  agregamos esta funcion que será usada para renderizar un input o solo texto
                  esto es debido a que sólo podemos realizar devoluciones 1 vez, y si hace amortización
                  poder completar el resto luego pero sin la posibilidad de realizar más devoluciones
                  esto para mantener consistencia en las operaciones.
                 */
                $.each(response.result, function(key, value) {
                  value.sidevuelve = function() {
                    return (this.estado != 'devuelto' && this.abonado == 0 && this.estado_remision == 'pendiente')
                    ? '<input type="text" value="' + this.cantidad_devolucion + '">'
                    : this.cantidad_devolucion;
                  }
                })
                var template = $('#deudastpl').html();
                var html = Mustache.render(template, response);
                $('#resultados').html(html);
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
      break;
    case 'publicacion':
    case 'periodico':
      /* si esta accediendo otra vez a la misma acción
        no volver a cargar lo mismo otra vez
       */
      if (last_chosen == 'publicacion' || last_chosen == 'periodico') break;
      last_chosen = buscar_por;

      $.ajax({
        url: base_url() + 'publicacion/async_publicacion/getAllPublicaciones',
        data: {ajax: true, tipo_publicacion: 'periodico'},
        type: 'post',
        dataType: 'json',
        success: function(response) {
          periodicos = response.result;
          el = $('<select data-placeholder="Seleccione Periódico ..."></select>').append(function() {
            var output = '<option value=""></option>';
            $.each(periodicos, function(key, value) {
              output += '<option value="'+ key +'">'+ value + '</option>';
            });
            return output;
          });
          $('#caja-publicacion').html(el);
          el.chosen();
        },
        error: function(response) {
          console.log('error');
          console.log(response.responseText);
        }
      });
      break;
    case 'revista':
      /* si esta accediendo otra vez a la misma acción
        no volver a cargar lo mismo otra vez
       */
      if (last_chosen == 'revista') break;
      last_chosen = 'revista';
      $.ajax({
        url: base_url() + 'publicacion/async_publicacion/getAllPublicaciones',
        data: {ajax: true, tipo_publicacion: 'revista'},
        type: 'post',
        dataType: 'json',
        success: function(response) {
          revistas = response.result;
          el = $('<select data-placeholder="Seleccione Revista/agregado ..."></select>').append(function() {
            var output = '<option value=""></option>';
            $.each(revistas, function(key, value) {
              output += '<option value="'+ key +'">' + value + '</option>';
            })
            return output;
          });
          $('#caja-publicacion').html(el);
          el.chosen();
        },
        error: function(response) {
          console.log('error');
          console.log(response.responseText);
        }
      })
  }
});
$('.btn-cobrar').live('click', function(e) {
  e.preventDefault();
  e.stopPropagation();

  /* nos aseguramos que si hay error no vamos a enviar nada */
  if (error) return;

  // console.log('no pasa nada ' + (Math.floor(Math.random() * 20) + 1));
  var row = $(this).closest('tr');
  var dpid = row.attr('data-dpid');
  /* esto será usado para guardar en la tabla de devolucion */
  var dev =row.find('.devolucion>input');
  /* esto será usado para guardar en la tabla de pagos */
  var total = row.find('.total').text();
  var abonado = row.find('.abonado').text();
  var saldo = row.find('.saldo>input').val();
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
    row.find('.saldo>input').focus();
  }

  return false;
})
$('.btn-cobrar').live('hover', function() {
  $(this).addClass('btn-success');
}).live('mouseout', function() {
  $(this).removeClass('btn-success');
})
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
  })
})