$(document).ready(function() {
    $.validator.messages.required = 'Este campo es requerido';
    $.validator.messages.email = 'Ingrese una dirección de email válida';
    $('#form-comision_sindicato').validate(
            {
              "rules":{
                "comision_sindicato":[
                  
                ]
              }
            }
        );//end of validate
    }//end of function
);//end of ready
