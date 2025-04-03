/*
Author       : Dreamstechnologies
Template Name: SmartHR - Bootstrap Admin Template
Version      : 4.0
*/

$(document).ready(function() {
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
	
	$(document).on('click','.payout-status', function(){
		var id= $(this).data('id');
		$('#status_user').val(id);
		$('#payout_single_status_model').modal('show');
	});
	$(document).on('click','.multi-payout-status', function(){
		var employee = [];
		$(".table input[name=chk_id]:checked").each(function() {  
			employee.push($(this).data('emp-id'));
		});
		if(employee.length <=0)  {
			$('#confirmChkSelect').modal("show");	
		}else {
			$('#payout_multi_status_model').modal('show');
		}
	});
	
	$(document).on('click','.update-status', function(){
		var TYPE_VAL = $(this).data('mode');
		let formData = new FormData($('#frmSingleStatusPayout')[0]);
		formData.append('_token', csrfToken);
		formData.append('type_val', TYPE_VAL);
		var URL = $('#frmSingleStatusPayout').attr('action');
		
		$.ajax({
			url: URL,
			type: "POST",
			data: formData,
			processData: false,  // Required for FormData
			contentType: false,
			dataType: 'json',
			success: function(response) {
				//alert(response);
				setTimeout(() => {
					window.location.reload();
				}, "1000");
			},
		});
	});
	$(document).on('click','.multi-update-status', function(){
		var employee = [];
		$(".table input[name=chk_id]:checked").each(function() {  
			employee.push($(this).data('emp-id'));
		});
		if(employee.length <=0)  {
			$('#confirmChkSelect').modal("show");	
		}else {
			var selected_values = employee.join(",");
			
			var TYPE_VAL = $(this).data('mode');
			let formData = new FormData($('#frmMultiStatusPayout')[0]);
			formData.append('_token', csrfToken);
			formData.append('type_val', TYPE_VAL);
			formData.append('users_id', selected_values);
			var URL = $('#frmMultiStatusPayout').attr('action');
			
			$.ajax({
				url: URL,
				type: "POST",
				data: formData,
				processData: false,  // Required for FormData
				contentType: false,
				dataType: 'json',
				success: function(response) {
					//alert(response);
					setTimeout(() => {
						window.location.reload();
					}, "1000");
				},
			});
		}
	});
});
