$(document).ready(function(){

	$(document).on('click', '#show-partner-registration', function(){

		$.ajax({

			url: 'partner-create',
			type: 'GET',
			dataType: 'html',

			beforeSend: function(){
				$('#show-partner-registration').prop('disabled', true);

				$("#partner-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-spinner' aria-hidden='true'></i> Please wait... </div>");

			},
			error: function (jqXhr, textStatus, errorMessage){
				$("#partner-message").html("<i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Could not load page");

			},

			success: function(data, status, xhr){
				if(xhr.status == 200){
					$('#partner-message').html('');
					$('#register-payments-section').html(data);
					$('#register-partner').trigger('click');
				}

			},

			complete: function(jqXhr, status){
				$('#show-partner-registration').prop('disabled', false);
				
			},
			timeout: 15000
		});
		return false;
	});



	$(document).on('click', '#register_btn', function(event){
		$("#register_partner_form").validate({
			rules:{
				titleIdentifier: 'required',
				fullname: 'required',
				gender: 'required',
				birthDate: 'required',
				countryOfResidence:'required',
				countryOfBirth: 'required',
				//stateIdentifier:'required',
				emailAddress: 'email',
				secondaryEmailAddress: 'email',
				phoneNumber: 'required',
				residentialAddress: 'required',
				maritalStatus: 'required',
				job: 'required',
				preferredLanguage: 'required',
				currencyIdentifier: 'required',
				donationAmount: {
								required:true,
								number: true
				}

			},

			submitHandler: submitForm
		});

		function submitForm(){
			var data = $('#register_partner_form').serialize();
			event.preventDefault();
			$.ajax({
				url: '/register-partner',
				type: 'POST',
				data: data,
				dataType: 'json',
				beforeSend: function(){
					$.notify(
						{	
							message: 'Please wait...'
						},
						{
							type: 'info'
						}
					);

					$("#register_btn").prop("disabled", false);
					$("#register_btn").html("<i class='fa fa-spinner' aria-hidden='true'></i>");
				},
				error: function (jqXhr, textStatus, errorMessage){
					if (jqXhr.status == 401) {
						$.notify(
							{
								message:"<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized."
							},
							{
								type: 'danger'
							}
						);
					}

					if(textStatus == 'timeout'){
						$.notify(
							{	
								message: "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Please try again"
							},
							{
								type: 'danger'
							}
						);
					}

					else
						$.notify(
							{	
								message: "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was a problem. Please try again."
							},
							{
								type: 'danger'
							}
						);

				},
				success: function(data, status, xhr){
					console.log(data);
					if(data.message == 'success')
						$.notify(
							{	
								message: "<i class='fa fa-info-circle' aria-hidden='true'></i> Record saved successfully."
							},
							{
								type: 'success'
							}
						);
					else
						if(data.code == 422){
							//console.log(Object.values(data.error));
							var errors = Object.values(data.error);
							for(var i = 0; i<errors.length; i++){
								$.notify(
								{	
									message: "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> " + errors[i]
								},
								{
									type: 'danger'
								}
								);
							}
						}
					
					else
						$.notify(
							{	
								message: "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Could not save record"
							},
							{
								type: 'danger'
							}
						);
				},
				complete: function(jqXhr, status){
					console.log(status);
					$("#register_btn").prop("disabled", false);
					$("#register_btn").html("Save");
				},
				timeout: 15000
			});
			return false;

		}
	});
});