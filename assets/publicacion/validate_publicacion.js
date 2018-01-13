$(document).ready(function() {
    $.validator.messages.required = 'Este campo es requerido';
    $.validator.messages.email = 'Ingrese una dirección de email válida';
    $('#form-publicacion').validate(
            {
              "rules":{
                "nombre":[
                  
                ],
                "img":[
                  
                ],
                "fecha_aniversario":[
                  
                ],
                "proveedor_id":{
                  "required":true
                }
              }
            }
        );//end of validate
    var fecha = $('#fecha_aniversario').val()
    if (fecha !='') 
    {
        var array = fecha.split("-");
        $('#mes :nth-child('+array[1]+')').prop('selected', true); // To select via index
        $('#dia_publicacion :nth-child('+array[0]+')').prop('selected', true); // To select via index

    };
    }//end of function
);//end of ready

// function Numero_de_Dias()
// {
//     var mes = $('#mes').val();
//     var limite = 31;
//     if (mes == 2 ) {limite =29;}
//     else  { if (mes  == 4 || mes == 6 || mes == 9 || mes == 11)  {limite =30;} }
//     var dias = '';
//     for (var i = 1; i<= limite; i++) {
//         dias+="<option value="+i+">"+i+"</option>";
//     };
//     // $('#dia_publicacion').append(dias);

// }
$('#dia_publicacion').change(function(){
    var fecha_n = $('#dia_publicacion').val()+'-'+$('#mes').val();
    $('#fecha_aniversario').attr('value',fecha_n);
});

$('.fileupload').fileupload()