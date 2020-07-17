$(document).ready(function(){

	$(document).on('click', '#search', function(){

	$("#search_form").validate({
		rules:{
			search_text_1: {
				required:true
			},
		},

		messages:{
			search_text_1: "Enter or select a value for this search field"
		},

		submitHandler: submitForm
	});

		

		function submitForm(){
			var data = $('#search_form').serialize();
			event.preventDefault();
			$.ajax({
				url: '/search-partner',
				type: 'POST',
				data: data,
				dataType: 'html',
				beforeSend: function(){
					$("#search").prop("disabled", true);
					$("#search").html("<i class='fa fa-spinner' aria-hidden='true'></i> Please Wait");
				},
				error: function (jqXhr, textStatus, errorMessage){
					if (jqXhr.status == 401) {
						$("#message").html("<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized.");
					}

					if(textStatus == 'timeout'){
						$("#message").html("<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Please try again");
					}

					else
						$("#message").html("<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was an error. Please try again");

					$("#search").html("Submit");
					$("#search").prop('disabled', false);

				},
				success: function(data, status, xhr){
					if(data != 'No record found')
						$('#partners').html(data);
					else
						$('#message').html("<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> No record found");
				},
				complete: function(jqXhr, status){

					$("#search").prop("disabled", false);
					$("#search").html("Search");
				},
				timeout: 15000
			});
			return false;
	    
		}  
	});

});