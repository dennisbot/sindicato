$(document).ready(function() {
    $.validator.messages.required = 'Este campo es requerido';
    $.validator.messages.email = 'Ingrese una dirección de email válida';
    $('#form-descripcion_tipo_plantilla').validate(
            {
              "rules":{
                "descripcion":[
                  
                ]
              }
            }
        );//end of validate
    }//end of function
);//end of ready
