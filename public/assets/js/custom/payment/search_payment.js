$(document).ready(function(){

	$(document).on('click', '#search_payment_btn', function(){

	$("#search_payment_form").validate({
		rules:{
			description: {
				minlength:3
			},
		},

		submitHandler: submitForm
	});

		

		function submitForm(){
			var data = $('#search_payment_form').serialize();
			event.preventDefault();
			$.ajax({
				url: '/search-payments',
				type: 'POST',
				data: data,
				dataType: 'html',
				beforeSend: function(){
					$("#search_payment_btn").prop("disabled", true);
					$("#search_payment_btn").html("<div style='color:maroon; text-align:center;'><i class='fa fa-spinner' aria-hidden='true'></i> Please Wait </div>");
				},
				error: function (jqXhr, textStatus, errorMessage){
					if (jqXhr.status == 401) {
						$("#payment-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized. </div>");
					}

					if(textStatus == 'timeout'){
						$("#payment-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Please try again </div>");
					}

					else
						$("#payment-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was an error. Please try again </div>");

				},
				success: function(data, status, xhr){
					$("#payment-message").html("");
					if(data != 'No record found'){
						$('#payments').html(data);
						var table = $('#paymentTable').DataTable({autoWidth: true});
					}
					else
						$('#payment-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> No record found </div>");
				},
				complete: function(jqXhr, status){

					$("#search_payment_btn").prop("disabled", false);
					$("#search_payment_btn").html("Search");
				},
				timeout: 15000
			});
			return false;
	    
		}  
	});

});