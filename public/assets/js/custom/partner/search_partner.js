$(document).ready(function(){

	$(document).on('click', '#search', function(){

	$("#search_partner_form").validate({
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
			var data = $('#search_partner_form').serialize();
			event.preventDefault();
			$.ajax({
				url: '/search-partner',
				type: 'POST',
				data: data,
				dataType: 'html',
				beforeSend: function(){
					$("#search").prop("disabled", true);
					$("#search").html("<div style='color:maroon; text-align:center;'><i class='fa fa-spinner' aria-hidden='true'></i> Please Wait </div>");
				},
				error: function (jqXhr, textStatus, errorMessage){
					if (jqXhr.status == 401) {
						$("#partner-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized. </div>");
					}

					if(textStatus == 'timeout'){
						$("#partner-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Please try again </div>");
					}

					else
						$("#partner-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was an error. Please try again </div>");

					$("#search").html("Submit");
					$("#search").prop('disabled', false);

				},
				success: function(data, status, xhr){
					$("#partner-message").html("");
					if(data != 'No record found'){
						$('#partners').html(data);
						var table = $('#partnerTable').DataTable({
							autoWidth: true,
							dom: 'Bfrtip',
        					buttons: [
			            		'excelHtml5',
			            		{
					            	extend:'pdfHtml5',
					            	orientation: 'landscape',
		                			pageSize: 'LEGAL'
			        			}
        					]
						});
					}
					else
						$('#partner-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> No record found </div>");
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