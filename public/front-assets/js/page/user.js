/*
Author       : Dreamstechnologies
Template Name: SmartHR - Bootstrap Admin Template
Version      : 4.0
*/

$(document).ready(function() {
	$(document).on('click','.edit-data', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		//alert(URL);
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id, _token: csrfToken},
			dataType: 'json',
			success: function(response) {
				// console.log(response.result);
				$('#id').val(response.result.id);
				$('#email').val(response.result.email);
				$('#first_name').val(response.result.first_name);
				$('#last_name').val(response.result.last_name);
				$('#phone_number').val(response.result.phone_number);
				$('#edit_user').modal('show');
			},
		});
	});
	$(document).on('click','.update-user', function(){
		let formData = new FormData($('#frmUserSubmit')[0]);
		formData.append('_token', csrfToken);
		var URL = $('#frmUserSubmit').attr('action');
		//alert(URL);
		$.ajax({
			url: URL,
			type: "POST",
			data: formData,
			processData: false,  // Required for FormData
			contentType: false,
			//dataType: 'json',
			success: function(response) {
				$('#updt_success_msg').modal('show');
				setTimeout(() => {
					window.location.reload();
				}, "1000");
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

	$(document).on('click','.delete-data', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		//alert(id);alert(URL);
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id, _token: csrfToken},
			dataType: 'json',
			success: function(response) {				
				$('.final-delete-submit').attr('data-id', response.result.id);
				$('#delete_modal_name_data').text(response.result.name);
				$('#delete_model').modal('show');
			},
		});
		
	});
	$(document).on('click','.final-delete-submit', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id, _token: csrfToken},
			dataType: 'json',
			success: function(response) {
				if(response.result == 'success'){
					$('#delete-icon').html('<font color="green">Record Deleted Successfully</font>');
				}else{
					$('#delete-icon').html('<font color="red">Record not deleted</font>');
				}
				setTimeout(() => {
					window.location.reload();
				}, "2000");
			},
		});
		
	});
	
	$(document).on('click','.update-status', function(){
		var id= $(this).data('id');
		var URL = $(this).data('url');
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id, _token: csrfToken},
			dataType: 'json',
			success: function(response) {
				//alert(update_status);
				$('#update_status').modal('show');
				setTimeout(() => {
					window.location.reload();
				}, "1000");
			},
		});
	});
});
