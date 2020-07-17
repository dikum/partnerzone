
function showPartnerPayments(partner){

	$.ajax({
		url: '/partner-payments/'+partner,
		type: 'GET',
		dataType: 'html',
		beforeSend: function(){

			$.notify(
					{	
						message: 'Please wait'
					},
					{
							type: 'warning'
					}
					);
			
		},
		error: function (jqXhr, textStatus, errorMessage){
			if (jqXhr.status == 401) {
				$.notify(
							{
								message:"<i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Sorry, you are not authorized to perfom this action."
							},
							{
								type: 'danger'
							}
						);
			}

			if (jqXhr.status == 404) {
				$.notify(
							{
								message:"<i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Payments not found"
							},
							{
								type: 'danger'
							}
						);
			}

			if(textStatus == 'timeout'){
				$.notify(
							{
								message:"<i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Timeout. Please try again."
							},
							{
								type: 'danger'
							}
						);
			}

			else
				$.notify(
							{
								message:errorMessage+ "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i>There was an error. Please try again."
							},
							{
								type: 'danger'
							}
						);

		},
		success: function(data, status, xhr){
			if(xhr.status == 200){

				$('#message').html('');
				$('#partner-payments-section').html(data);
				$('#show-payments').trigger('click');
			}

		},
		timeout: 15000
	});

}

