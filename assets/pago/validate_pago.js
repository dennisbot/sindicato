$(document).ready(function() {
    $.validator.messages.required = 'Este campo es requerido';
    $.validator.messages.email = 'Ingrese una dirección de email válida';
    $('#form-pago').validate(
            {
              "rules":{
                "monto_pago":[
                  
                ],
                "fecha":[
                  
                ]
              }
            }
        );//end of validate
    }//end of function
);//end of ready
