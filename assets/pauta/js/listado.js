$(function() {
    if (cargar_edit_in_place)
        $('.editinplace').editInPlace({
            error_sink: function(idOfEditor, errorString) {
                console.log('hubo un error: ', idOfEditor);
                console.log(errorString);
            },
            callback: function(element_id, update_value, original_value) {
                var diff = update_value - original_value;
                var sep = element_id.split('-');
                var publicacion_id = sep[0];
                params = {ajax: 1, 'save_this': update_value, 'detalle_pauta_id': sep[1]};
                /*console.log(params);
                console.log('paso paso');*/
                var html_response;
                $.ajax({
                    url: base_url() + 'pauta/editar_detalle_pauta',
                    type: 'post',
                    data: params,
                    dataType: 'html',
                    async: false,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        if (!response.edited) {
                            html_response = original_value;
                            bootbox.alert('Ya se realizo la revolución de la remisión correspondiente\
                             a esta pauta, ya no se puede hacer\
                                ninguna modificación');
                            return false;
                        }

                        html_response = response.cantidad;
                        var box_total = $('#publicacion-detalle-' + publicacion_id);
                        var cur_val = parseInt(box_total.html());
                        cur_val += diff;
                        box_total.html(cur_val);
                        var total_detalle = $('#publicacion-remision-' + publicacion_id);

                        if (box_total.html().trim() == total_detalle.html().trim()) {
                            box_total.addClass('alert-success');
                            box_total.removeClass('alert-danger');
                        }
                        else {
                            box_total.addClass('alert-danger');
                            box_total.removeClass('alert-success');
                        }
                    },
                    error: function(response) {
                        console.log('error :(');
                        console.log(response.responseText);
                    }
                });
                return html_response;
            },
            default_text: '0',
            value_required: true,
            // show_buttons: true
        });
});
jQuery('table tr td input.inplace_field').live('focus', function() {
    $(this).closest('td').css({padding: 0});
    $(this).closest('div').css('margin-bottom', '15px');
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
                var sgte_td = cur_td.next('.editar').find('.editinplace');
                if (sgte_td.length) {
                    sgte_td.trigger('click');
                }
                else {
                    var sgte_td = cur_td.closest('tr').next().find('.editar').first().find('.editinplace');
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
                var prev_td = cur_td.prev('.editar').find('.editinplace');
                if (prev_td.length) {
                    prev_td.trigger('click');
                }
                else {
                    var prev_td = cur_td.closest('tr').prev().find('.editar').last().find('.editinplace');
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
                var sgte_td = $(this).closest('tr').next().children().eq(cellIndex).find('.editinplace');
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
                var sgte_td = $(this).closest('tr').prev().children().eq(cellIndex).find('.editinplace');
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
    $(this).closest('div').css('margin-bottom', '0');
});
jQuery('.tofix').affix({
    offset: {
        top: function() {
            return $(window).width() <= 980 ? 290: 160;
        }
    }
})
jQuery('#anular').on('click', function() {
    var current = $(this);
    var pauta_id = current.attr('data-pauta-id');
    bootbox.confirm('<h3 class="text-center">¿Esta seguro de anular esta Pauta?</h3>', "No", "Si", function(result) {
        if (result)
          window.location.href = current.attr('data-href') + '/' + pauta_id;
    })
})