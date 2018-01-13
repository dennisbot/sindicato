$(document).ready(function() {
    $.validator.messages.required = 'Este campo es requerido';
    $.validator.messages.email = 'Ingrese una dirección de email válida';
    $('#form-detalle_remision').validate(
            {
              "rules":{
                "descripcion":[
                  
                ],
                "cantidad":[
                  
                ],
                "unidad_medida":[
                  
                ],
                "precio_unitario":[
                  
                ],
                "importe":[
                  
                ],
                "cantidad_devolucion":[
                  
                ],
                "importe_neto":[
                  
                ]
              }
            }
        );//end of validate
    }//end of function
);//end of ready
