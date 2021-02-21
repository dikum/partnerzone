$(document).ready(function(){
	var partner_window;
	var search_criteria_count = 1;
	var dialogIndex;
	var dialogId;
	
	$('#partner').click(function(){
		if(!$("#icon-title-list #partner-icon-title").length){

			partner_window = new Window("Partners", {
				state: WindowState.NORMAL,
				size: {
					width: 1200,
					height: 900
				},
				selected: true,
				icon: "<img src='./assets/images/icons/partner.png'>",
			});

			partner_window.content.id = 'partner_content';

			dialogIndex = dialogIDIndex + 1;
			dialogId = 'window_'+dialogIndex;
			dialogIndexArray[dialogId] = dialogId;
			windowObjectArray[dialogIndex] = partner_window;
			dialogIDIndex++;

			partner_window.on('closed', function(){
				
				$("#partner-icon-title").remove()

			});

			$.ajax({
				url: '/partners',
				type: 'GET',
				dataType: 'html',
				beforeSend: function(){
					//window selected is the class name of the create window
					$('.window_selected').css('zIndex',  "9999");
					partner_window.content.innerHTML = "<div style='color:maroon; text-align:center;'><i class='fa fa-spinner' aria-hidden='true'></i> Please wait...</div>";
				},
				error: function (jqXhr, textStatus, errorMessage){
					if (jqXhr.status == 401) {
						partner_window.content.innerHTML = "<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized to view this page</div>";
					}

					if(textStatus == 'timeout'){
						partner_window.content.innerHTML = "<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Could not load content</div>";
					}

					else
						partner_window.content.innerHTML = "<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was an error. Please try again.</div>";

				},
				success: function(data, status, xhr){
					if(xhr.status == 200){

						
						partner_window.content.innerHTML = data;
						var table = $('#partnerTable').DataTable({autoWidth: true});

						$("#icon-title-list").append("<li><div class='icon-title' id='partner-icon-title'><img src='./assets/images/partner.png'></div></li>");
					}

				},
				timeout: 15000
			});

		}
		else{
			resetDialogZIndexes();
			bringToFront(dialogId);
		}
		
	});

	$(document).on('click', '#partner-icon-title', function(){
		resetDialogZIndexes();
		bringToFront(dialogId);
		partner_window.show();
	});
		


	//Add Search Criteria
	$(document).on('click', '#add_search_criteria', function(event){

		search_criteria_count++;
		
		$('#criteria_div').append("<div class='row dynamic-search-criteria'>"
							+"<div class='form-group'>"
								+"<select id='search_criteria_select_" + search_criteria_count + "' name='search_criteria_select_" + search_criteria_count + "' class='form-control mr-sm-2 select_criteria'>"
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
						
							+"<div class='form-group dynamic-text-div' style='margin-left: 5px;' id='search_text_div_" + search_criteria_count + "' > <i class='fa fa-minus remove-partner-search-criteria remove-search' aria-hidden='true'></i>  </div>"
						
						+"</div>");

	});

	//Remove Search Criteria
	$(document).on('click', '.remove-partner-search-criteria', function(event){
		$(this).parents('.dynamic-search-criteria').remove();

	});


	//Search option changed
	$(document).on('change', '.select_criteria', function(event){

		var select_option = $(this).val();

		var select_option_name = $(this).attr('name');

		var select_option_count = select_option_name.substring(23, select_option_name.length);

		if(select_option == 'countryOfResidence'){
			setCountries(select_option_count);
			
		}

		else if(select_option == 'stateIdentifier'){
			setStates(select_option_count);

		}
		else if(select_option == 'emailAddress')
			$("#search_text_div_" + select_option_count).html("<input type = 'email' id='search_text_' name='search_text_" + search_criteria_count + "' class='form-control mr-sm-2' type='text' placeholder='Search' aria-label='Search'>");

		else
			$("#search_text_div_" + select_option_count).html("<input id='search_text_' name='search_text_" + search_criteria_count + "' class='form-control mr-sm-2' type='text' placeholder='Search' aria-label='Search'>");


	});


	function setCountries(select_option_count){

		$.ajax({
			url: '/countries',
			type: 'GET',
			dataType: 'json',
			beforeSend: function(){
				$("#search_text_div_" + select_option_count).html("<div style='color:maroon; text-align:center;'>Please wait...</div>");
			},
			error: function (jqXhr, textStatus, errorMessage){
				if (jqXhr.status == 401) {
					$("#search_text_div_" + select_option_count).html("<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, could not load countries</div>");
				}

				if(textStatus == 'timeout'){
					$("#search_text_div_" + select_option_count).html("<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Could not load countries</div>");
				}

				else
					$("#search_text_div_" + select_option_count).html("<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Error in loading countries</div>");

			},
			success: function(data, status, xhr){
				if(xhr.status == 200){

					countries = JSON.parse(data);

					$("#search_text_div_" + select_option_count).html("<select class='form-control mr-sm-2' id='search_text_" + select_option_count + "' name='search_text_"+ select_option_count + "' ></select>");

					for(i = 0; i < countries.data.length; i++){

						$("#search_text_" + select_option_count).append("<option value='" + countries.data[i].countryIdentifier + "'>" + countries.data[i].countryName + "</option>");
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
				$("#search_text_div_" + select_option_count).html("<div style='color:maroon; text-align:center;'>Please wait...</div>");
			},
			error: function (jqXhr, textStatus, errorMessage){
				if (jqXhr.status == 401) {
					$("#search_text_div_" + select_option_count).html("<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, could not load states</div>");
				}

				if(textStatus == 'timeout'){
					$("#search_text_div_" + select_option_count).html("<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Could not load states</div>");
				}

				else
					$("#search_text_div_" + select_option_count).html("<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Error in loading states</div>");

			},
			success: function(data, status, xhr){
				if(xhr.status == 200){

					states = JSON.parse(data);

					$("#search_text_div_" + select_option_count).html("<select class='form-control mr-sm-2' id='search_text_" + select_option_count + "' name='search_text_"+ select_option_count + "' ></select>");

					for(i = 0; i < states.data.length; i++){

						$("#search_text_" + select_option_count).append("<option value='" + states.data[i].stateName + "'>" + states.data[i].stateName + "</option>");
					}

				}

			},
			timeout: 15000
		});

	}
	
});