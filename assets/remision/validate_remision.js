$(document).ready(function() {
    $.validator.messages.required = 'Este campo es requerido';
    $.validator.messages.email = 'Ingrese una dirección de email válida';
    $('#form-remision').validate(
            {
              "rules":{
                "nro_guia":[

                ],
                "razon_social":[

                ],
                "codigo":[

                ],
                "ruc":[

                ],
                "tipo":[

                ],
                "sector":[

                ],
                "observaciones":[

                ],
                "fecha_emision":[

                ],
                "fecha_recepcion":[
                ]
              }
            }
        );//end of validate
    }//end of function
);//end of ready
