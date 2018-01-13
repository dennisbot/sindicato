$(document).ready(function() {
    $.validator.messages.required = 'Este campo es requerido';
    $.validator.messages.email = 'Ingrese una dirección de email válida';
    $('#form-comision_publicacion').validate(
            {
              "rules":{
                "comision":[
                  
                ],
                "fecha":{
                  "required":true
                },
                "publicacion_id":{
                  "required":true
                },
                "operador_id":{
                  "required":true
                }
              }
            }
        );//end of validate

    /* x-editable */
    $.fn.editable.defaults.url = base_url() + 'publicacion/editar_comision';
    $('#tags').editable({
        inputclass: 'input-large',
        select2: {
            tags: ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'],
            tokenSeparators: [",", " "]
        }
    });

});//end of ready
