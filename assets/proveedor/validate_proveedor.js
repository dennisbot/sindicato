$(document).ready(function() {
    $.validator.messages.required = 'Este campo es requerido';
    $.validator.messages.email = 'Ingrese una dirección de email válida';
    $('#form-proveedor').validate(
            {
              "rules":{
                "nombre":[
                  
                ],
                "direccion":[
                  
                ],
                "telefonos":[
                  
                ],
                "ruc":[
                  
                ],
                "ciudad":[
                  
                ]
              }
            }
        );//end of validate
    }//end of function
);//end of ready
