/*
Author       : Dreamstechnologies
Template Name: SmartHR - Bootstrap Admin Template
Version      : 4.0
*/

$(document).ready(function() {
	$(document).on('click','.edit-customer', function(){
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
		
	});
	
	$(document).on('click','.kyc-documents-data', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		$.ajax({
			url: URL,
			type: "POST",
			data: { id: id, _token: csrfToken },
			dataType: 'json',
			success: function(response) {
					//console.log(response.documents_details);
					let doc = response.documents_details; 
					
					$('#email').html(doc.get_client.email);
					$('#trader_id').html(doc.id);
					$('#full_name').html(doc.get_client.first_name +' '+ doc.get_client.last_name);
					var created_date = doc.created_at;
					var formatted_date = dayjs(doc.created_at).format("DD MMM YY");
					$('#created_date').html(formatted_date);
					if(doc.status==1)
					{
						$('#accept').hide();
						$('#reject').hide();
						$('#pending').show();
					}
					
					if(doc.status==0)
					{
						$('#accept').hide();
						$('#reject').show();
						$('#pending').hide();
					}
					if(doc.status==2)
					{
						$('#accept').show();
						$('#reject').hide();
						$('#pending').hide();
					}
					
					var frontalFile = doc.frontal ?? null;
					if(frontalFile)
					{
						var frontalFilePath = response.forntal_path +'/'+ frontalFile;
						$('#view_frontal').attr("href", frontalFilePath).attr("download", frontalFile.split('/').pop());
					}
					
					var backFile = doc.back ?? null;
					if(backFile)
					{
						var backFilePath = response.back_path +'/'+ backFile;
						$('#view_back').attr("href", backFilePath).attr("download", backFile.split('/').pop());
					}
					
					var residenceFile = doc.residence ?? null;
					if(residenceFile)
					{
						var residenceFilePath = response.residence_path +'/'+ residenceFile;
						$('#view_residence').attr("href", residenceFilePath).attr("download", residenceFile.split('/').pop());
					}
					
					$('#reject_client_id').attr('data-id', doc.id);
					$('#accept_client_id').attr('data-id', doc.id);
					$('#view_details').modal('show');
			},
			error: function(xhr) {
				console.log(xhr.responseText); // Log errors
				alert('Something went wrong!');
			}
		});
	});
	
	$(document).on('click','#reject_client_id', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		var status_typ = $(this).data('mode');
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id,status_typ:status_typ, _token: csrfToken},
			dataType: 'json',
			success: function(response) {
				if(response.message)
				{
					$("#message-section").html('<div class="alert alert-danger">' + response.message + '</div>');
				}
				setTimeout(() => {
					window.location.reload();
				}, "2000");
			},
		});
	});
	$(document).on('click','#accept_client_id', function(){
		var id = $(this).data('id');
		var URL = $(this).data('url');
		var status_typ = $(this).data('mode');
		$.ajax({
			url: URL,
			type: "POST",
			data: {id:id,status_typ:status_typ, _token: csrfToken},
			dataType: 'json',
			success: function(response) {
				if(response.message)
				{
					 $("#message-section").html('<div class="alert alert-success">' + response.message + '</div>');
				}
				setTimeout(() => {
					window.location.reload();
				}, "2000");
			},
		});
	});
});
