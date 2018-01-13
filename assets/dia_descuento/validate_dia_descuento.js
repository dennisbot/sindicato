$(document).ready(function() {
    $.validator.messages.required = 'Este campo es requerido';
    $.validator.messages.email = 'Ingrese una dirección de email válida';
    $('#form-dia_descuento').validate(
            {
              "rules":{
                "nombre":[
                  
                ],
                "fecha":[
                  
                ]
              }
            }
        );//end of validate
    }//end of function
);//end of ready
