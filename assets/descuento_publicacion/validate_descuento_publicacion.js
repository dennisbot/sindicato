$(document).ready(function() {
    $.validator.messages.required = 'Este campo es requerido';
    $.validator.messages.email = 'Ingrese una dirección de email válida';
    $('#form-descuento_publicacion').validate(
            {
              "rules":{
                "porcentaje_descuento":[
                  
                ],
                "precio_publico":[
                  
                ]
              }
            }
        );//end of validate
    }//end of function
);//end of ready
