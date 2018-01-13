$(document).ready(function() {
    $.validator.messages.required = 'Este campo es requerido';
    $.validator.messages.email = 'Ingrese una dirección de email válida';
    $('#form-vendedor').validate(
            {
              "rules":{
                "nombres":[
                  
                ],
                "apellidos":[
                  
                ],
                "nickname":[
                  
                ],
                "telefono":[
                  
                ],
                "dni":[
                  
                ],
                "direccion_casa":[
                  
                ],
                "direccion_tienda":[
                  
                ],
                "fecha_nacimiento":[
                  
                ],
                "email":{
                  "email":true
                }
              }
            }
        );//end of validate


    var fecha = $('#fecha_nacimiento').val()

    if (fecha !='') 
    {
      
        var array = fecha.split("-");
        $('#mes :nth-child('+array[1]+')').prop('selected', true); // To select via index
        $('#dia :nth-child('+array[0]+')').prop('selected', true); // To select via index
        $('#anio option:eq('+array[2]+')').prop('selected', true);

    };

    Set_fecha_Nacimiento();
    
    }//end of function
);//end of ready
function Set_fecha_Nacimiento()
{
    console.log($('#dia2').val());
    var fecha_n = $('#anio').val()+$('#mes').val()+$('#dia2').val();
    $('#fecha_nacimiento').attr('value',fecha_n);
    // alert($('#fecha_nacimiento').val());
}

$('#dia2').change(function(){

    Set_fecha_Nacimiento();

});
$('#mes').change(function(){

    Set_fecha_Nacimiento();

});
$('#anio').change(function(){

  Set_fecha_Nacimiento();

});