/*
Author       : Dreamstechnologies
Template Name: SmartHR - Bootstrap Admin Template
Version      : 4.0
*/

$(document).ready(function() {
	$(document).on('click','.submit-withdraw', function(){
		var URL = $(this).data('url');
		$.ajax({
			url: URL,
			type: "POST",
			data: {_token: csrfToken},
			dataType: 'json',
			success: function(response) {
				$('#delete_model').modal('show');
			},
		});		
	});
});
