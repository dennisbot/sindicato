$(document).ready(function() {
    $.validator.messages.required = 'Este campo es requerido';
    $.validator.messages.email = 'Ingrese una dirección de email válida';
    $('#form-devolucion').validate(
            {
              "rules":{
                "cantidad_devolucion":[
                  
                ],
                "fecha":[
                  
                ]
              }
            }
        );//end of validate
    }//end of function
);//end of ready
