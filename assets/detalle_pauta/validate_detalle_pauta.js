$(document).ready(function() {
    $.validator.messages.required = 'Este campo es requerido';
    $.validator.messages.email = 'Ingrese una dirección de email válida';
    $('#form-detalle_pauta').validate(
            {
              "rules":{
                "vendedor_id":{
                  "required":true
                },
                "publicacion_id":{
                  "required":true
                },
                "pauta_id":{
                  "required":true
                },
                "cantidad":{
                  "required":true
                }
              }
            }
        );//end of validate
    }//end of function
);//end of ready
