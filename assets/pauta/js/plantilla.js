$('#select-proveedores').change(function() {
    console.log('ha cambiado xD');
    $('#form-proveedores').submit();
});
$('.editinplace').editInPlace({
    error_sink: function(idOfEditor, errorString) {
        console.log('hubo un error: ', idOfEditor);
        console.log(errorString);
    },
    callback: function(element_id, update_value, original_value) {
        var diff = update_value - original_value;
        var publicacion_id = element_id.substr(0, element_id.indexOf('-'));
        params = {ajax: 1, 'update_value': update_value, 'element_id': element_id};
        var html_response;
        $.ajax({
            url: base_url() + 'pauta/editar_detalle_plantilla',
            type: 'post',
            data: params,
            dataType: 'html',
            async: false,
            success: function(response) {
                html_response = response;
                var box_total = $('#publicacion-' + publicacion_id);
                var cur_val = parseInt(box_total.html());
                cur_val += diff;
                box_total.html(cur_val);
            },
            error: function(response) {
                console.log('error :(');
                console.log(response);
            }
        });
        return html_response;
    },
    default_text: '0',
    value_required: true,
    // show_buttons: true
});
var g;
jQuery('table tr td input.inplace_field').live('focus', function() {
    $(this).closest('td').css({padding: 0});
    $(this).maskMoney({
        precision: 0,
        defaultZero: false,
        thousands: '',
        decimal: ''
    });
    $(this).keydown(function(event) {
        if (event.which != 9 && event.which != 13) return true;
        /* es tecla tab */
        if (event.which == 9) {
            if (event.shiftKey == false) {
                var cur_td = $(this).closest('td');
                var sgte_td = cur_td.next('td.editinplace');
                if (sgte_td.length) {
                    sgte_td.trigger('click');
                }
                else {
                    var sgte_td = cur_td.closest('tr').next().find('td.editinplace').first();
                    /* buscamos el hermano sgte editinplace */
                    if (sgte_td.length) {
                        sgte_td.trigger('click');
                    }
                    else {
                        $(this).trigger('blur');
                    }
                }
            }
            if (event.shiftKey == true) {
                var cur_td = $(this).closest('td');
                var prev_td = cur_td.prev('td.editinplace');
                if (prev_td.length) {
                    prev_td.trigger('click');
                }
                else {
                    var prev_td = cur_td.closest('tr').prev().find('td.editinplace').last();
                    /* buscamos el hermano sgte editinplace */
                    if (prev_td.length) {
                        $(prev_td).trigger('click');
                    }
                    else {
                        $(this).trigger('blur');
                    }
                }
            }
        }
        else {
            /* es enter */
            /* si no usamos shift avanzamos hacia abajo */
            if (event.shiftKey == false) {
                var cellIndex = $(this).closest('td').index();
                var sgte_td = $(this).closest('tr').next().children().eq(cellIndex);
                if (sgte_td.length) {
                    g = sgte_td;
                    sgte_td.trigger('click');
                }
                else {
                    $(this).trigger('blur');
                }
            }
            /* si usamos shift avanzamos hacia arriba */
            if (event.shiftKey == true) {
                var cellIndex = $(this).closest('td').index();
                var sgte_td = $(this).closest('tr').prev().children().eq(cellIndex);
                if (sgte_td.length) {
                    sgte_td.trigger('click');
                }
                else {
                    $(this).trigger('blur');
                }
            }
        }
        $(this).closest('td').css({padding: '8px'});
        return false;
    })

});

jQuery('table tr td input.inplace_field').live('blur', function() {
    $(this).closest('td').css({padding: '8px'});
});
jQuery('.chosen-select')
.chosen()
.change(function() {
    $('#form-proveedores').submit();
});
$('.tofix').affix({
    offset: {
        top: function() {
            return $(window).width() <= 980 ? 290: 300;
        }
    }
});