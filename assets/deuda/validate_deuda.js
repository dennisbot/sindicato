$(document).ready(function() {
    $.validator.messages.required = 'Este campo es requerido';
    $.validator.messages.email = 'Ingrese una dirección de email válida';
    $('#form-deuda').validate(
            {
              "rules":{
                "monto_deuda":[
                  
                ]
              }
            }
        );//end of validate
    }//end of function
);//end of ready
