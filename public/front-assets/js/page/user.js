/*
Author       : Dreamstechnologies
Template Name: SmartHR - Bootstrap Admin Template
Version      : 4.0
*/

$(document).ready(function() {
	let currentBalance = 0;
	
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
	
	$(document).on('click','.adjust-balance', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		//alert(URL);
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id, _token: csrfToken},
			dataType: 'json',
			success: function(response) {
				$('#current_balance').text(response.result.users_balances);
				$('#new_amount').text(response.result.users_balances);
				$('#current_balance_val').val(response.result.users_balances);
				$('#adjust_amount_user').val(id);
				$('#adjust_balance_model').modal('show');
				currentBalance = response.result.users_balances;
			},
		});
	});
	$('#adjust_amount').on('input', function() {
      let val = $(this).val();

      // Remove invalid characters (allow digits and one decimal)
      val = val.replace(/[^0-9.]/g, '');

      // Only allow one decimal point
      let parts = val.split('.');
      if (parts.length > 2) {
        val = parts[0] + '.' + parts[1]; // Ignore extra decimals
      }

      $(this).val(val);

      // Update new amount
      let adjustAmount = parseFloat(val);
      if (!isNaN(adjustAmount)) {
        let newAmount = currentBalance + adjustAmount;
        $('#new_amount').text(newAmount.toFixed(2));
      } else {
        $('#new_amount').text(currentBalance.toFixed(2));
      }
    });
	$(document).on('click','.submit-adjust-balance', function(){
		var type = $(this).data('mode');
		
		let formData = new FormData($('#frmAdjustBalance')[0]);
		// formData.append('user_id', $('adjust_amount_user').val());
		formData.append('type', type);
		formData.append('_token', csrfToken);
		var URL = $('#frmAdjustBalance').attr('action');
		//alert(URL);
		$.ajax({
			url: URL,
			type: "POST",
			data: formData,
			processData: false,  // Required for FormData
			contentType: false,
			//dataType: 'json',
			success: function(response) {
				if(type == 'add'){
					$('.adjust_balance_msg').text('User balance added successfully.');
				}else{
					$('.adjust_balance_msg').text('User balance removed successfully.');
				}
				$('#adjust_balance_msg').modal('show');
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
	$(document).on('click','.multi-adjust-balance', function(){
		var employee = [];
		$(".table input[name=chk_id]:checked").each(function() {  
			employee.push($(this).data('emp-id'));
		});
		if(employee.length <=0)  {
			$('#confirmChkSelect').modal("show");	
		}else {
			$('#adjust_multi_user_balance_model').modal('show');
		}
	});
	$('#adjust_percent').on('input', function() {
      let val = $(this).val();

      // Remove invalid characters (allow digits and one decimal)
      val = val.replace(/[^0-9.]/g, '');

      // Only allow one decimal point
      let parts = val.split('.');
      if (parts.length > 2) {
        val = parts[0] + '.' + parts[1]; // Ignore extra decimals
      }

      $(this).val(val);
    });
	$(document).on('click','.submit-multi-adjust-balance', function(){
		var employee = [];
		$(".table input[name=chk_id]:checked").each(function() {  
			employee.push($(this).data('emp-id'));
		});
		if(employee.length <=0) {
			$('#confirmChkSelect').modal("show");	
		}else{
			var selected_values = employee.join(",");
			
			var TYPE_VAL = $(this).data('mode');
			let formData = new FormData($('#frmMultiAdjustBalance')[0]);
			formData.append('_token', csrfToken);
			formData.append('type_val', TYPE_VAL);
			formData.append('users_id', selected_values);
			var URL = $('#frmMultiAdjustBalance').attr('action');
			
			$.ajax({
				url: URL,
				type: "POST",
				data: formData,
				processData: false,  // Required for FormData
				contentType: false,
				dataType: 'json',
				success: function(response) {
					if(TYPE_VAL == 'add'){
						$('.adjust_balance_msg').text('User balance added successfully.');
					}else{
						$('.adjust_balance_msg').text('User balance removed successfully.');
					}
					$('#adjust_balance_msg').modal('show');
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
		}
	});
});
