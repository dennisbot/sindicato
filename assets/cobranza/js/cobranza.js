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
  /* 119 para F8 */
  /*  para control e.ctrlKey */
  if (e.keyCode == 119) {
    if (e.keyCode == 119 && e.ctrlKey) {
      var boton = $('.btn-cobrar-imprimir').get(0);
      procesar_cobrar($('.btn-cobrar-imprimir').get(0));
      console.log('boton: ', boton);
      console.log('guardar e imprimir');
    }
    else {
      var boton = $('.btn-cobrar').get(0);
      procesar_cobrar($('.btn-cobrar').get(0));
      console.log('boton: ', boton);
      console.log('sólo guardar');
    }
  }


});
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
          // console.log(response);
          /*
            agregamos esta funcion que será usada para renderizar un input o solo texto
            esto es debido a que sólo podemos realizar devoluciones 1 vez, y si hace amortización
            poder completar el resto luego pero sin la posibilidad de realizar más devoluciones
            esto para mantener consistencia en las operaciones.
           */
          var total_pagar = 0;
          $.each(response.result, function(key, value) {
            value.vendedor_id = vendedor_id;
            total_pagar += parseFloat(value.saldo);
            value.sidevuelve = function() {
              return (this.estado != 'devuelto' && this.abonado == 0 && this.estado_remision == 'pendiente')
              ? '<input type="text" value="' + this.cantidad_devolucion + '">'
              : '<div style="padding:0 7px">' + this.cantidad_devolucion + '</div>';
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
              el.removeClass('icon-check');
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
          bootbox.alert(response.responseText);
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

$('.btn-cobrar, .btn-cobrar-imprimir').live('click', function() {
  procesar_cobrar(this);
});
$('.btn-cobrar, .btn-cobrar-imprimir').live('hover', function() {
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
function procesar_cobrar(el) {
  var imprimir = parseInt($(el).attr('data-imprimir'));
  console.log('imprimir: ', imprimir);
  console.log('procesar cobrar');
  // return;
  // return false;
    if (error) {
      alert('Tienes errores en las filas rojas, corríjelas primero');
      return;
    }
    var fila = $(el).closest('tbody').find('tr').first();
    var vendedor_id = fila.attr('data-vendedorid');
    // console.log('vendedor_id: ', vendedor_id);
    // console.log('se detuvo');
    // return;
    $(el).closest('tbody').find('tr').each(function(index, value) {

      var row = $(this);
      var checked = row.find('input[type="checkbox"]').is(':checked');

      /* si el row no esta checkeado no realizamos ninguna operacion */
      if (!checked) return;

      var dpid = row.attr('data-dpid');
      var rep = row.find('.repartido').text().trim();
      /* esto será usado para guardar en la tabla de devolucion */
      wada  = row.find('.devolucion>input');
      var dev =row.find('.devolucion>input').val().trim();
      /* esto será usado para guardar en la tabla de pagos */
      var total = row.find('.total').text();
      var abonado = row.find('.abonado').text();
      var saldo = row.find('.saldo>input').val();

      /*
      console.log('dpid: ', dpid);
      console.log('saldo: ', saldo);
      */
      
      if (dev.length > 0) {	  
        $.ajax({
          url: base_url() + 'devolucion/async_devolucion/devolver',
          data: {
            'dpid' : dpid,
            'cant_dev': dev,
            'ajax': true
          },
          type: 'post',
          dataType: 'json',
          async: false,
          success: function(response) {
            /* si hay para devolver, entonces anulamos el td con devolucion */
            row.find('.devolucion').text(dev);
          },
          error: function(response) {
            console.log('error');
            console.log(response.responseText);
          }
        });
        var dev = parseInt(dev);
        var rep = parseInt(rep);
        if (dev == rep)
          row.remove();
      }
      /* esta llamada será hecha para guardar el monto cobrado */
      if (parseFloat(saldo) != 0) {
        // console.log('aqui entra para hacer cobranza (saldo es diferente de cero)');
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
            /*console.log('success :)');
            console.log(response);*/
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
    calcular_totales();
    if (imprimir && vendedor_id !== undefined) {
      var fecha = new Date();
      var day = fecha.getDate();
      var month = fecha.getMonth();
      var year = fecha.getFullYear();
      var d = new Date(year, month, day);
      do_imprimir(vendedor_id, Math.round(d.getTime() / 1000));
    }
    else {
      if (vendedor_id === undefined)
        bootbox.alert('ya no existen registros para cobrar e imprimir');
    }
    /*bootbox.confirm('<center><h3>¿Está seguro que deseas procesar este cobro?</h3></center>', function(result) {
      if (result) {}
    });*/
}
function do_imprimir(vendedor_id, curdate_timestamp) {
    // console.log('curdate_timestamp: ', curdate_timestamp);
    popupwindow(base_url() + 'vendedor/imprimir/' + vendedor_id + '/' + curdate_timestamp,
      'Vista de pagos',
      screen.width,
      screen.height / 2);
}
function popupwindow(url, title, w, h) {
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open(url, title,
    'toolbar=no, location=no, directories=no, status=no, \
    menubar=no, scrollbars=no, resizable=no, copyhistory=no, \
    width='+w+', height='+h+', top='+top+', left='+left);
}