$(document).ready(function(){

	$(document).on('click', '#import_statement_btn', function(event){

		$("#import_statement_form").validate({
			rules:{
				bank_to_import: 'required',
				bank_statement: 'required'
			},

			submitHandler: submitForm
		});

		function submitForm(){
			//var data = $('#import_statement_form').serialize();
			var form = document.getElementById('import_statement_form');
			var data = new FormData(form);
			event.preventDefault();
			$.ajax({
				url: '/import-statement',
				type: 'POST',
				data: data,
				dataType: 'html',
				contentType:false,
				processData:false,
				beforeSend: function(){
					$("#import-message").html('Please wait...');
					$("#import_statement_btn").prop("disabled", true);
					$("#import_statement_btn").html("<div style='color:maroon; text-align:center;'><i class='fa fa-spinner' aria-hidden='true'></i> Please Wait </div>");
				},
				error: function (jqXhr, textStatus, errorMessage){
					if (jqXhr.status == 401) {
						$("#import-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized. </div>");
					}

					if(textStatus == 'timeout'){
						$("#import-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Please try again </div>");
					}

					else
						$("#import-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was an error. Please try again </div>");

				},
				success: function(data, status, xhr){
					data = JSON.parse(data);
					if(xhr.status == 201){
						$("#import-message").html("<div style='color:#40916C; text-align:center;'><i class='fa fa-check' aria-hidden='true'></i> Bank Statement imported successfully. </div>");
						document.getElementById('import_statement_form').reset();
					}
					
					else
						$('#import-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> " + data.message + "</div>");
				},
				complete: function(jqXhr, status){

					$("#import_statement_btn").prop("disabled", false);
					$("#import_statement_btn").html("Import");
				},
				timeout: 15000
			});
			return false;
	    
		}  
	});
});