$(document).ready(function(){

	$(document).on('click', '#search_statement_btn', function(){

	$("#search_statement_form").validate({
		rules:{
			description: {
				minlength:3
			},
		},

		submitHandler: submitForm
	});

		

		function submitForm(){
			var data = $('#search_statement_form').serialize();
			event.preventDefault();
			$.ajax({
				url: '/search-statement',
				type: 'POST',
				data: data,
				dataType: 'html',
				beforeSend: function(){
					$('#statement-message').html("<div style='color:maroon; text-align:center;'>Please wait...</div>");
					$("#search_statement_btn").prop("disabled", true);
					$("#search_payment_btn").html("<div style='color:maroon; text-align:center;'><i class='fa fa-spinner' aria-hidden='true'></i> Please Wait </div>");
				},
				error: function (jqXhr, textStatus, errorMessage){
					if (jqXhr.status == 401) {
						$("#statement-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized. </div>");
					}

					if(textStatus == 'timeout'){
						$("#statement-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Please try again </div>");
					}

					else
						$("#statement-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was an error. Please try again </div>");

				},
				success: function(data, status, xhr){
					$("#statement-message").html("");
					if(data != 'No record found'){
						$('#statement').html(data);
						var table = $('#statementTable').DataTable({autoWidth: true});
					}
					else
						$('#statement-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> No record found </div>");
				},
				complete: function(jqXhr, status){

					$("#search_statement_btn").prop("disabled", false);
					$("#search_statement_btn").html("Search");
				},
				timeout: 15000
			});
			return false;
		}  
	});


	$(document).on('change', '#statement_search_option', function(){

		var search_option = $('#statement_search_option').val();

		if($('#'+search_option+'Div').css('display') == 'none'){

			$('#'+search_option+'Div').css('display', 'block');
		}
		else{
			$('#'+search_option).val('');
		}

	});


	//Remove Search Criteria
	$(document).on('click', '.hide-statement-search-criteria', function(event){
		$($(this).parents('.hide').children('input')).val('');
		$($(this).parents('.hide')).css('display', 'none');

	});


});