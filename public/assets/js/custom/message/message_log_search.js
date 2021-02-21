$(document).ready(function(){
	$(document).on('click', '#message-log-search', function(){

	$("#search_message_log_form").validate({
		rules:{
			/*search_message_log_option: {
				required: '#fromDate:blank'
			},
			*/

			fromDate: {
      			required: function(element){
      				var search_option = $('#search_message_log_option').val();
            		if(search_option == '')
            			return true;
            		else
            			return false;
            	}
      		},
      		toDate: {
      			required: function(element){
      				var fromDate = $('#fromDate').val();
            		if(fromDate !== '')
            			return true;
            		else
            			return false;	
        		}
      		},
      		search_message_log_value: {
      			required: function(element){
      				var search_option = $('#search_message_log_option').val();
            		if(search_option !== '')
            			return true;
            		else
            			return false;	
        		}
      		}
		},

		messages:{
			search_message_log_value: "Provide a search value",
			fromDate: 'You can provide a date range',
			toDate: 'Provide search end date'
		},

		submitHandler: submitForm
	});

		

		function submitForm(){
			var data = $('#search_message_log_form').serialize();
			event.preventDefault();
			$.ajax({
				url: '/search-message-log',
				type: 'POST',
				data: data,
				dataType: 'html',
				beforeSend: function(){
					$("#message-log-search").prop("disabled", true);
					$("#message-log-search").html("<div style='color:maroon; text-align:center;'><i class='fa fa-spinner' aria-hidden='true'></i> Please Wait </div>");
				},
				error: function (jqXhr, textStatus, errorMessage){
					if (jqXhr.status == 401) {
						$("#log-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized. </div>");
					}

					if(textStatus == 'timeout'){
						$("#log-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Please try again </div>");
					}

					else
						$("#log-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was an error. Please try again </div>");

					$("#message-log-search").html("Submit");
					$("#message-log-search").prop('disabled', false);

				},
				success: function(data, status, xhr){
					$("#log-message").html("");
					if(data != 'No record found'){
						$('#messageLog').html(data);
						var table = $('#messageLogTable').DataTable({autoWidth: true});
					}
					else
						$('#log-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> No record found </div>");
				},
				complete: function(jqXhr, status){

					$("#message-log-search").prop("disabled", false);
					$("#message-log-search").html("Search");
				},
				timeout: 15000
			});
			return false;
	    
		}  
	});

});