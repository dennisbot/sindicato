$(document).ready(function() {
    $.validator.messages.required = 'Este campo es requerido';
    $.validator.messages.email = 'Ingrese una dirección de email válida';
    $('#form-usuario').validate(
            {
              "rules":{
                "nombre_usuario":[
                  
                ],
                "clave":[
                  
                ],
                "email":{
                  "email":true
                }
              }
            }
        );//end of validate
    }//end of function
);//end of ready
