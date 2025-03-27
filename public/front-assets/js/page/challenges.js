/*
Author       : Dreamstechnologies
Template Name: SmartHR - Bootstrap Admin Template
Version      : 4.0
*/

$(document).ready(function() {
	$(document).on('click','.save-challenge-email', function(){
		let traderEmail = $('#trader_email').val().trim();
		//let createdDate = $('#created_date').val().trim();
		let isValid = true;
		$('.invalid-feedback').hide();
		$('.form-control').removeClass('is-invalid');
		if (traderEmail === '')
		{
			$('#trader_email').addClass('is-invalid');
			$('#trader_email').next('.invalid-feedback').show();
			isValid = false;
		}
		if (isValid) {		
			var URL = $('#frmTraderEmail').attr('action');
			//alert(URL);
			$.ajax({
				url: URL,
				type: "POST",
				data: {trader_email:traderEmail, _token: csrfToken},
				//dataType: 'json',
				success: function(response) {
					// console.log(response);
					if(response.success) {
						$('#step-one').hide();
						$('#step-two').show();
						$('#traders_email').val(traderEmail);
						$('#trader_first_name').val(response.data.first_name);
						$('#trader_last_name').val(response.data.last_name);
						$('#trader_phone_number').val(response.data.phone_number);
					}
				},
				error: function (xhr) {
					if (xhr.status === 422) {
						// alert(xhr.status);
						const errors = xhr.responseJSON.errors;
						$('.invalid-feedback').hide();
						$('.form-control').removeClass('is-invalid');
						
						$.each(errors, function(key, value) {
							// Check the key received from the server
							let fieldName = key.replace(/\./g, '\\.').replace(/\*/g, '');
							let field = $('[name="' + fieldName + '"]');
							
							if (field.length > 0) {
								field.addClass('is-invalid');
								if (field.is('select')) {
									//field.closest('.form-group').find('.invalid-feedback').show().text(value[0]);
									
									field.closest('.input-block').find('.invalid-feedback').show().text(value[0]);
									//alert(value[0]);
								} else {
									field.next('.invalid-feedback').show().text(value[0]);
								}
							} else {
								var fieldNames = key.split('.')[0]; // Get the base field name (e.g., product_sale_price)
								var index = key.split('.').pop();
								var inputField = $('input[name="' + fieldNames + '[]"]').eq(index);
								inputField.addClass('is-invalid');
								inputField.next('.invalid-feedback').show().text(value[0]);
							}
						});
					}else{
						
					}
				}
			});
		}
	});	
	$(document).on('click','.save-challenge', function(){
		let formData = new FormData($('#frmChallenge')[0]);
		formData.append('_token', csrfToken);
		var URL = $('#frmChallenge').attr('action');
		//alert(URL);
		$.ajax({
			url: URL,
			type: "POST",
			data: formData,
			processData: false,  // Required for FormData
			contentType: false,
			//dataType: 'json',
			success: function(response) {
				if (response.success) {
					$('#success_msg').modal('show');
					setTimeout(() => {
						window.location.reload();
					}, "2000");
				}
			},
			error: function (xhr) {
				if (xhr.status === 422) {
					// alert(xhr.status);
					const errors = xhr.responseJSON.errors;
					$('.invalid-feedback').hide();
					$('.form-control').removeClass('is-invalid');
					
					$.each(errors, function(key, value) {
						// Check the key received from the server
						let fieldName = key.replace(/\./g, '\\.').replace(/\*/g, '');
						let field = $('[name="' + fieldName + '"]');
						
						if (field.length > 0) {
							field.addClass('is-invalid');
							if (field.is('select')) {
								//field.closest('.form-group').find('.invalid-feedback').show().text(value[0]);
								
								field.closest('.input-block').find('.invalid-feedback').show().text(value[0]);
								//alert(value[0]);
							} else {
								field.next('.invalid-feedback').show().text(value[0]);
							}
						} else {
							var fieldNames = key.split('.')[0]; // Get the base field name (e.g., product_sale_price)
							var index = key.split('.').pop();
							var inputField = $('input[name="' + fieldNames + '[]"]').eq(index);
							inputField.addClass('is-invalid');
							inputField.next('.invalid-feedback').show().text(value[0]);
						}
					});
				}else{
					
				}
			}
		});
	});
	
	/*$(document).on('click','.edit-customer', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		//alert(URL);
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id, _token: csrfToken},
			dataType: 'json',
			success: function(response) {
				
			},
		});
	}); 

	$(document).on('click','.delete-customer', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		//alert(id);alert(URL);
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id, _token: csrfToken},
			dataType: 'json',
			success: function(response) {
				$('#delete_model').modal('show');
			},
		});
		
	});*/
});
