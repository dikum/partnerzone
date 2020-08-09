
function showPartnerPayments(partner, partner_name){
	$.ajax({
		url: '/partner-payments/'+partner,
		type: 'GET',
		dataType: 'html',
		beforeSend: function(){

			$("#partner-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-spinner' aria-hidden='true'></i> Loading </div>");
			
		},
		error: function (jqXhr, textStatus, errorMessage){
			if (jqXhr.status == 401) {

				$("#partner-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized to perfom this action. </div>");
			}

			if (jqXhr.status == 404) {

				$("#partner-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Payments not found </div>");

			}

			if(textStatus == 'timeout'){

				$("#partner-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Please try again. </div>");
			
			}

			else{

				$("#partner-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was an error. Please try again. </div>");
			}

		},
		success: function(data, status, xhr){
			if(xhr.status == 200){

				$('#partner-message').html('');
				$('#partner-payments-section').html(data);
				$('#partner-name').html(partner_name);
				$('#show-payments').trigger('click');
			}

		},
		timeout: 15000
	});

}

