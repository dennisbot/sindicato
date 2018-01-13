$(document).ready(function() {
    $.validator.messages.required = 'Este campo es requerido';
    $.validator.messages.email = 'Ingrese una dirección de email válida';
    $('#form-operador').validate(
            {
              "rules":{
                "nombre_usuario":{
                  "required":true
                },
                "clave":{
                  "required":true
                },
                "email":{
                  "required":true,
                  "email":true
                }
              }
            }
        );//end of validate
    }//end of function
);//end of ready
