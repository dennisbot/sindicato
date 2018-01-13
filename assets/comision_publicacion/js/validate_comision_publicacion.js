$('.edit_comision').editInPlace({
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
            url: base_url() + 'publicacion/editar_comision',
            type: 'post',
            data: params,
            dataType: 'html',
            async: false,
            success: function(response){
                html_response = response;
                return html_response;
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
    show_buttons: false
});