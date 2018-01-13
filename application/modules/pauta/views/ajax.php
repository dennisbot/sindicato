<script src="<?php echo public_url() ?>jquery/jquery-1.9.1.js"></script>
<script>
	$(function() {
		$('#load-data').click(function (e) {

			e.preventDefault();
			$.ajax({
				url: '/pauta/test',
				type: 'get',
				success: function(response) {
					console.log(response);
				},
				error: function (response) {
					console.log(response);
				}
			})

		});
	})
</script>
<div id="content"></div>
<a href="#" id="load-data">retrieve ajax data</a>