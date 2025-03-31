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
				$('#withdrawable_balance_text').text(response.get_records_amount);
				$('#withdrawable_balance_input').val(response.get_records_amount);
				$('#withdrawable_id').val(response.get_records);
				if(response.get_records_amount <= 0){
					$('#withdraw-submit-section').hide();
					$('.submit-withdraw-request').prop('disabled', true);
				}else{
					$('#withdraw-submit-section').show();
					$('.submit-withdraw-request').prop('disabled', false);
				}
				$('#submit_withdraw_request').modal('show');
			},
		});	
	});
	$(document).on('click','.submit-withdraw-request', function(){
		let withdrawable_balance_input = $('#withdrawable_balance_input').val();
		if(withdrawable_balance_input > 0){
			let formData = new FormData($('#frmWithdrawSubmit')[0]);
			formData.append('_token', csrfToken);
			var URL = $('#frmWithdrawSubmit').attr('action');
			//alert(URL);
			$.ajax({
				url: URL,
				type: "POST",
				data: formData,
				processData: false,  // Required for FormData
				contentType: false,
				dataType: 'json',
				success: function(response) {
					if(response.result == 'success'){
						$('#request_withdraw_msg_modal').modal('show');
						$('#request_withdraw_msg').text('Request sent successfully.');
					}else{
						$('#request_withdraw_msg_modal').modal('show');
						$('#request_withdraw_msg').text('Request not sent.');
					}
					setTimeout(() => {
						window.location.reload();
					}, "1000");
				},
			});
		}
	});
});
