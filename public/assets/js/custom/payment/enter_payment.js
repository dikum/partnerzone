$(document).ready(function(){
	var paymentType = '';
	$(document).on('change', 'input[type=radio][name=paymentType]', function() {
		paymentType  = this.value;
		
		if(paymentType == 'strong_room_payment')
			strong_room_selected();
		else
			bank_statement_selected();

	});

	$(document).on('click', '#enter_payment_btn', function(event){
		
		if(paymentType == 'strong_room_payment')
			save_strong_room_payment();
		if(paymentType == 'bank_statement_payment')
			save_bank_statement_payment();
	});


	function strong_room_selected(){

		$('#bankStatementIdentifier').prop('disabled', true);
		$('#bankIdentifier').prop('disabled', true);
		$('#datePaid').prop('disabled', false);
		$('#amountPaid').prop('disabled', false);
		$('#currencyIdentifier').prop('disabled', false);
		$('#paymentDescription').prop('disabled', false);

		var select = "<select class='form-control' name='currencyIdentifier' id='currencyIdentifier'></select>";
		$('#currencyContainer').html(select);
		
		//var currencies gotten from partner_payments blade
		for (var i = 0; i<currencies.length; i++) {
			$('#currencyIdentifier').append("<option value='" + currencies[i]['currencyIdentifier'] + "''>" + currencies[i]['currencyShortName'] +'</option')
		}
	}

	function bank_statement_selected(){

		$('#bankStatementIdentifier').prop('disabled', false);
		$('#datePaid').prop('disabled', true);
		$('#bankIdentifier').prop('disabled', true);
		$('#amountPaid').prop('disabled', true);
		$('#currencyIdentifier').prop('disabled', true);
		$('#paymentDescription').prop('disabled', true);

		$('#currencyContainer').html("<input disabled class='form-control' type='text' name='currencyIdentifier' id='currencyIdentifier'>");
	}


	function save_strong_room_payment(){
		
		$("#enter_payment_form").validate({
			rules:{
				datePaid: 'required',
				currencyIdentifier: 'required',
				amountPaid: {
								required:true,
								number: true
				},
				paymentDepositor:'required'

			},

			submitHandler: submitForm
		});

		function submitForm(){
			var data = $('#enter_payment_form').serialize();
			event.preventDefault();
			$.ajax({
				url: '/enter-sr-payment',
				type: 'POST',
				data: data,
				dataType: 'json',
				beforeSend: function(){
					$("#enter-payment-message").html('Please wait');
					$("#enter_payment_btn").prop("disabled", true);
					$("#enter_payment_btn").html("<div style='color:maroon; text-align:center;'><i class='fa fa-spinner' aria-hidden='true'></i> Please Wait </div>");
				},
				error: function (jqXhr, textStatus, errorMessage){
					if (jqXhr.status == 401) {
						$("#enter-payment-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized. </div>");
					}

					if(textStatus == 'timeout'){
						$("#enter-payment-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Please try again </div>");
					}

					else
						$("#enter-payment-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was an error. Please try again </div>");

				},
				success: function(data, status, xhr){
					data = JSON.parse(data);
					if(xhr.status == 201){
						$("#enter-payment-message").html("<div style='color:#40916C; text-align:center;'><i class='fa fa-check' aria-hidden='true'></i> Payment saved successfully. </div>");
						document.getElementById('import_statement_form').reset();
					}
					
					else
						$('#enter-payment-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> " + data.message + "</div>");
				},
				complete: function(jqXhr, status){

					$("#enter_payment_btn").prop("disabled", false);
					$("#enter_payment_btn").html("Save");
				},
				timeout: 15000
			});
			return false;
		}
		
	}
});