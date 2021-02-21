var table;
$(document).ready(function(){
	var search_criteria_count = 1;
	$(document).on('click', '#partner-search-btn', function(){

		$("#search_message_partner_form").validate({
			rules:{
				message_search_text_1: {
					required:true
				},
			},

			messages:{
				message_search_text_1: "Enter or select a value for this search field"
			},

			submitHandler: submitForm
		});

		

		function submitForm(){
			var data = $('#search_message_partner_form').serialize();
			event.preventDefault();
			$.ajax({
				url: '/search-partner',
				type: 'POST',
				data: data,
				dataType: 'html',
				beforeSend: function(){
					$("#partner-search-btn").prop("disabled", true);
					$("#partner-search-btn").html("<div style='color:maroon; text-align:center;'><i class='fa fa-spinner' aria-hidden='true'></i> Please Wait </div>");
				},
				error: function (jqXhr, textStatus, errorMessage){
					if (jqXhr.status == 401) {
						$("#create-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized. </div>");
					}

					if(textStatus == 'timeout'){
						$("#create-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Please try again </div>");
					}

					else
						$("#create-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was an error. Please try again </div>");

				},
				success: function(data, status, xhr){
					$("#create-message").html('');
					if(data != 'No record found'){
						$('#partner-list').html(data);
						table = $('#messagePartnerTable').DataTable({autoWidth: true});
						$('html, body').animate({
        					scrollTop: $("#partner-list").offset().top
    					}, 1000);
					}
					else{
						
						$('#create-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> No record found </div>");
						$('#partner-list').html('');
					}
				},
				complete: function(jqXhr, status){

					$("#partner-search-btn").prop("disabled", false);
					$("#partner-search-btn").html("Search");
				},
				timeout: 15000
			});
			return false;
	    
		}  
	});


	//Add Search Criteria
	$(document).on('click', '#message_add_search_criteria', function(event){

		search_criteria_count++;
		
		$('#message_criteria_div').append("<div class='row dynamic-search-criteria'>"
							+"<div class='form-group'>"
								+"<select id='message_search_criteria_select_" + search_criteria_count + "' name='message_search_criteria_select_" + search_criteria_count + "' class='form-control mr-sm-2 message_select_criteria'>"
									+"<option value='partnerIdentifier'>Partner ID</option>"
									+"<option value='fullname'>Name</option>"
									+"<option value='emailAddress'>Email</option>"
									+"<option value='phoneNumber'>Phone</option>"
									+"<option value='job'>Occupation</option>"
									+"<option value='countryOfResidence'>Country of Residence</option>"
									+"<option value='stateIdentifier'>State</option>"
									+"<option value='userStatus'>Status</option>"
								+"</select>"
							+"</div>"
						
							+"<div class='form-group' style='margin-left: 5px;' id='message_search_text_div_" + search_criteria_count + "' > <i class='fa fa-minus remove-message-partner-search-criteria remove-search' aria-hidden='true'></i>  </div>"
						
						+"</div>");

	});

	//Remove Search Criteria
	$(document).on('click', '.remove-message-partner-search-criteria', function(event){
		$(this).parents('.dynamic-search-criteria').remove();

	});


	//Search option changed
	$(document).on('change', '.message_select_criteria', function(event){
		var select_option = $(this).val();

		var select_option_name = $(this).attr('name');

		var select_option_count = select_option_name.substring(31, select_option_name.length);

		if(select_option == 'countryOfResidence'){
			setCountries(select_option_count);
			
		}

		else if(select_option == 'stateIdentifier'){
			setStates(select_option_count);

		}
		else if(select_option == 'emailAddress')
			$("#message_search_text_div_" + select_option_count).html("<input type = 'email' id='message_search_text_' name='message_search_text_" + search_criteria_count + "' class='form-control mr-sm-2' type='text' placeholder='Email' aria-label='Search'>");

		else
			$("#message_search_text_div_" + select_option_count).html("<input id='message_search_text_' name='message_search_text_" + search_criteria_count + "' class='form-control mr-sm-2' type='text' placeholder='Enter Search Value' aria-label='Search'>");


	});


	function setCountries(select_option_count){

		$.ajax({
			url: '/countries',
			type: 'GET',
			dataType: 'json',
			beforeSend: function(){
				$("#message_search_text_div_" + select_option_count).html("<div style='color:maroon; text-align:center;'>Please wait...</div>");
			},
			error: function (jqXhr, textStatus, errorMessage){
				if (jqXhr.status == 401) {
					$("#message_search_text_div_" + select_option_count).html("<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, could not load countries</div>");
				}

				if(textStatus == 'timeout'){
					$("#message_search_text_div_" + select_option_count).html("<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Could not load countries</div>");
				}

				else
					$("#message_search_text_div_" + select_option_count).html("<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Error in loading countries</div>");

			},
			success: function(data, status, xhr){
				if(xhr.status == 200){

					countries = JSON.parse(data);

					$("#message_search_text_div_" + select_option_count).html("<select class='form-control mr-sm-2' id='message_search_text_" + select_option_count + "' name='message_search_text_"+ select_option_count + "' ></select>");

					for(i = 0; i < countries.data.length; i++){

						$("#message_search_text_" + select_option_count).append("<option value='" + countries.data[i].countryIdentifier + "'>" + countries.data[i].countryName + "</option>");
					}

				}

			},
			timeout: 15000
		});

	}

	function setStates(select_option_count){

		$.ajax({
			url: '/states',
			type: 'GET',
			dataType: 'json',
			beforeSend: function(){
				$("#message_search_text_div_" + select_option_count).html("<div style='color:maroon; text-align:center;'>Please wait...</div>");
			},
			error: function (jqXhr, textStatus, errorMessage){
				if (jqXhr.status == 401) {
					$("#message_search_text_div_" + select_option_count).html("<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, could not load countries</div>");
				}

				if(textStatus == 'timeout'){
					$("#message_search_text_div_" + select_option_count).html("<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Could not load countries</div>");
				}

				else
					$("#message_search_text_div_" + select_option_count).html("<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Error in loading countries</div>");

			},
			success: function(data, status, xhr){
				if(xhr.status == 200){

					states = JSON.parse(data);

					$("#message_search_text_div_" + select_option_count).html("<select class='form-control mr-sm-2' id='message_search_text_" + select_option_count + "' name='message_search_text_"+ select_option_count + "' ></select>");

					for(i = 0; i < states.data.length; i++){

						$("#message_search_text_" + select_option_count).append("<option value='" + states.data[i].stateName + "'>" + states.data[i].stateName + "</option>");
					}

				}

			},
			timeout: 15000
		});

	}


	$(document).on( 'click', '#remove_message_partner_icon', function () {
    	table
        .row( $(this).parents('tr') )
        .remove()
        .draw();
	});

});

function getPartnerMessageTable(){
	return table;
}

function setPartnerMessageTable(){
	table = null;
}
