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
				if (response.documents_details.length > 0) {
					console.log(response.documents_details);
					
					let doc = response.documents_details[0]; 
					
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
					
					var frontalFile = doc.frontal;
					var filePath = response.forntal_path +'/'+ frontalFile;
					$('#view_frontal').attr("href", filePath);
					
					//$('#view_frontal').attr("href", frontalFile).attr("download", frontalFile.split('/').pop());
					
					$('#view_details').modal('show');
				} else {
					alert('No document found!');
				}
			},
			error: function(xhr) {
				console.log(xhr.responseText); // Log errors
				alert('Something went wrong!');
			}
		});

	});
});
