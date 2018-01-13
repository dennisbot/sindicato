$(document).ready(function() {

	$('.nombre').hover(function(){
		//alert("hover2");
	});

 $.ajax({
        type: 'post',
        url: base_url() + 'publicacion/get_ids',
        dataType: 'json',
        success: function(result) {
             $.each(result, function(id, publicacion) {
             $(function() {
	            $("#img"+publicacion.id).popover({ placement: 'right', html : true, trigger:'hover',  content: '<img src="' + base_url() + 'assets/img/logos/'+publicacion.img+'"/>' });
	            });
            });
        },
        error: function(result) {
            console.log('error');
        }
    });

$("#proveedor").change(function(){
    if ($("#proveedor").val() =="todos") {
         window.location=BASE_URL+'publicacion/index';
    }
    else
    {
        if ($("#proveedor").val()>=0) {
            window.location=BASE_URL+'publicacion/index/proveedor/'+$("#proveedor").val();
        };
   }
});

});



