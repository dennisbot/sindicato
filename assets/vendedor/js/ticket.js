var idvendedor;
$(function() {

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
  $('.calendari').datepicker().on('changeDate', function(selected) {
    toprint();
  });
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
    el.on('change', function(e) {
      idvendedor = this.value;
      toprint();
    });
  },
  error: function(response) {
    console.log('error');
    console.log(response.responseText);
  }
});
function toprint() {
    if (idvendedor !== undefined && $('#fecha_inicio').val().trim() != '') {
      var fecha = $('#fecha_inicio').val().split('/');
      var d = new Date(fecha[2], fecha[1] - 1, fecha[0]);
      do_imprimir(idvendedor, Math.round(d.getTime() / 1000));
    }
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