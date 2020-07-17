
function showPartner(partner){

	$.ajax({
		url: '/show-partner/',
		type: 'GET',
		data: {partner},
		dataType: 'html',
		beforeSend: function(){

			$("#message").html("<i class='fa fa-spinner' aria-hidden='true'></i> Loading");
			
		},
		error: function (jqXhr, textStatus, errorMessage){
			if (jqXhr.status == 401) {
				$('#message').html("<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized to view this page</div>");
			}

			if(textStatus == 'timeout'){
				$('#message').html("<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Could not load content</div>");
			}

			else
				$('#message').html("<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was an error. Please try again.</div>");

		},
		success: function(data, status, xhr){
			if(xhr.status == 200){

				$('#message').html('');
				$('#show-partner-section').html(data);
				$('#show-partner').trigger('click');
			}

		},
		timeout: 15000
	});

}

