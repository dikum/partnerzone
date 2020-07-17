$(document).ready(function(){
	
	$("#login_form").validate({
		rules:{
			email: {
				required:true,
				email:true
			},
			password: {
				required:true
			},
		},

		messages:{
			email: "Please enter a valid email address",
			password: "Please provide a password"
		},

		submitHandler: submitForm
    
	});

	function submitForm(){
		var data = $('#login_form').serialize();
		event.preventDefault();
		$.ajax({
			url: '/login-action',
			type: 'POST',
			data: data,
			dataType: 'json',
			beforeSend: function(){
				$("#submit").prop("disabled", true);
				$("#submit").html("<i class='fa fa-spinner' aria-hidden='true'></i>");
			},
			error: function (jqXhr, textStatus, errorMessage){

				if (jqXhr.status == 401) {
					$("#message").html("<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Invalid login details");
				}

				else
				if(textStatus == 'timeout'){
					$("#message").html("<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Please try again");
				}

				else
					$("#message").html("<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was an error. Please try again");

				$("#submit").html("Submit");
				$("#submit").prop('disabled', false);

			},
			success: function(data, status, xhr){
				if(data.message == 'success'){

					window.location = "/";
				}
				else{
					$("#message").html("<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> " + data.message);
					$("#submit").html("Submit");
					$("#submit").prop('disabled', false);
				}

			},
			timeout: 15000
		});
		return false;
    
	}
});