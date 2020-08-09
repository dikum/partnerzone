
$(document).ready(function(){

	$(document).on('change', '#countryOfBirth', function(){
		var country = $('#countryOfBirth option:selected').text();
		if(country == 'Nigeria')
			document.getElementById('stateIdentifier').disabled = false;
		else
			document.getElementById('stateIdentifier').disabled = true;
	});

	$(document).on('click', '#update_btn', function(event){
		$("#update_partner_form").validate({
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
				},
				isVerified: 'required',
				userBranch: 'required'

			},

			submitHandler: submitForm
		});

		function submitForm(){
			var data = $('#update_partner_form').serialize();
			event.preventDefault();
			$.ajax({
				url: '/update-partner',
				type: 'POST',
				data: data,
				dataType: 'json',
				beforeSend: function(){
					$.notify(
						{	
							message: 'Please wait'
						},
						{
							type: 'warning'
						}
					);

					$("#update_btn").prop("disabled", false);
					$("#update_btn").html("<i class='fa fa-spinner' aria-hidden='true'></i>");
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

					if (jqXhr.status == 404) {
						$.notify(
							{
								message:"<i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Could not find partner information"
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
					if(data.message == 'success')
						$.notify(
							{	
								message: "<i class='fa fa-info-circle' aria-hidden='true'></i> Record updated successfully."
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
								message: "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Update failed"
							},
							{
								type: 'danger'
							}
						);
				},
				complete: function(jqXhr, status){

					$("#update_btn").prop("disabled", false);
					$("#update_btn").html("Update");
				},
				timeout: 15000
			});
			return false;

		}
	});  

});

