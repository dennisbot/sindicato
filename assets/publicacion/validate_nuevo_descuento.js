$(document).ready(function() {
    $.validator.messages.required = 'Este campo es requerido';
    $('#form-publicacion').validate(
            {
              "rules":{
                "publicacion_id":{
                  "required":true
                },
                "tipo_fecha":{
                  "required":true
                },
                "dia_descuento_id":{
                  "required":true
                }
              }
            }
        );//end of validate
    }//end of function
);//end of ready