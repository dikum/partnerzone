
function deletePayment(payment, row_number){
	if(confirm('Are you sure you want to delete this payment?'))
	$.ajax({
		url: '/delete-payment/'+payment,
		type: 'GET',
		dataType: 'json',
		beforeSend: function(){

			$("#partner-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-spinner' aria-hidden='true'></i> Please wait... </div>");
			
		},
		error: function (jqXhr, textStatus, errorMessage){
			if (jqXhr.status == 401) {

				$("#partner-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Sorry, you are not authorized to perfom this action. </div>");
				
			}

			if (jqXhr.status == 404) {

				$("#partner-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Payment not found </div>");

			}

			if(textStatus == 'timeout'){

				$("#partner-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Timeout. Please try again. </div>");
			}

			else
				$("#partner-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i>There was an error. Please try again. </div>");

		},
		success: function(data, status, xhr){
			if(xhr.status == 200){

				$.notify(
							{	
								message: "<i class='fa fa-info-circle' aria-hidden='true'></i> Payment deleted successfully"
							},
							{
								type: 'success'
							}
						);

				document.getElementById("partnerPaymentTable").deleteRow(row_number);
			}

		},
		timeout: 15000
	});

}

