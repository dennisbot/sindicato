$(document).ready(function() {
    $.validator.messages.required = 'Este campo es requerido';
    $.validator.messages.email = 'Ingrese una dirección de email válida';
    $('#form-precio_publicacion').validate(
            {
              "rules":{
                "precio":[
                  
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
    }//end of function
);//end of ready
