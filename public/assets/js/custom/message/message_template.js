$(document).ready(function(){
	var message_template_window;
	var dialogIndex;
	var dialogId;
	var editor;
	

	//Load dialog
	$('#message_template').click(function(){
		if(!$("#icon-title-list #message_template-icon-title").length){
			message_template_window = new Window("Message Template", {
				state: WindowState.NORMAL,
				size: {
					width: 1200,
					height: 900
				},
				selected: true,
				icon: "<img src='./assets/images/icons/message_template.png'>",
			});

			message_template_window.content.id = 'message_template_content';

			dialogIndex = dialogIDIndex + 1;
			dialogId = 'window_'+dialogIndex;
			dialogIndexArray[dialogId] = dialogId;
			windowObjectArray[dialogIndex] = message_template_window;
			dialogIDIndex++;


			message_template_window.on('closed', function(){
				
				$("#message_template-icon-title").remove()

			});
			$.ajax({
				url: '/message-templates',
				type: 'GET',
				dataType: 'html',
				beforeSend: function(){
					//window selected is the class name of the create window
					$('.window_selected').css('zIndex',  "9999");
					message_template_window.content.innerHTML = "<div style='color:maroon; text-align:center;'><i class='fa fa-spinner' aria-hidden='true'></i> Please wait...</div>";
				},
				error: function (jqXhr, textStatus, errorMessage){
					if (jqXhr.status == 401) {
						message_template_window.content.innerHTML = "<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized to view this page</div>";
					}

					if(textStatus == 'timeout'){
						message_template_window.content.innerHTML = "<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Could not load content</div>";
					}

					else
						message_template_window.content.innerHTML = "<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was an error. Please try again.</div>";

				},
				success: function(data, status, xhr){
					if(xhr.status == 200){

						message_template_window.content.innerHTML = data;
						var table = $('#messageTemplateTable').DataTable({autoWidth: true});

						editor = new Jodit("#messageTemplate", {
					  		"uploader": {
					    	"insertImageAsBase64URI": true
					  		},
					  		"theme": "dark"
						});

						$('#messageTemplateTable tbody').on( 'click', 'tr', function (){
    						var sn =  table.row(this).data()[0];
    						var title = table.row(this).data()[1];
    						var message = $('#' + sn).text();
    						var templateId = $('#templateId' + sn).text();

    						$('#messageTemplateIdentifier').val(templateId);
    						$('#messageTitle').val(title);
    						//$('#messageTemplate').html(message);
    						editor.setEditorValue(message);
						});


						$("#icon-title-list").append("<li><div class='icon-title' id='message_template-icon-title'><img src='./assets/images/message_template.svg'></div></li>");

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

	$(document).on('click', '#message_template-icon-title', function(){
		resetDialogZIndexes();
		bringToFront(dialogId);
		message_template_window.show();
	});


	//Save or update message template
	$(document).on('click', '#create_message_template_btn', function(){

		$("#create_message_template_form").validate({
			rules:{
				messageTitle: 'required',
				messageTemplate: 'required'
			},

			submitHandler: submitForm
		});

		function submitForm(){

			var data = $('#create_message_template_form').serialize();
			event.preventDefault();
			$.ajax({
				url: '/save-message-template',
				type: 'POST',
				data: data,
				dataType: 'json',
				beforeSend: function(){
					$('#template-message').html("<div style='color:maroon; text-align:center;'> Please wait... </div>");

					$("#create_message_template_btn").prop("disabled", false);
					$("#create_message_template_btn").html("<i class='fa fa-spinner' aria-hidden='true'></i>");
				},
				error: function (jqXhr, textStatus, errorMessage){
					if(jqXhr.status == 401)
						$('#template-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized.</div>");

					else if(jqXhr.status == 404)
						$('#template-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Could not find message information</div>");

					else if(jqXhr.status == 403){
						$('#template-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Unauthorized access</div>");
					}

					else if(jqXhr.status == 422){
						$('#template-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Unauthorized access</div>");
					}
					
					else if(textStatus == 'timeout')
						$('#template-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Please try again </div>");

					else
						$('#template-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was a problem. Please try again.</div>");

				},
				success: function(data, status, xhr){
					if(data.message == 'success'){
						$.notify(
							{	
								message: "<i class='fa fa-info-circle' aria-hidden='true'></i> Message Template Saved Successfully."
							},
							{
								type: 'success'
							}
						);

						$('#template-message').html('');
					}
					else if(data.code == 422){
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
					else if(data.code == 403){
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

					$("#create_message_template_btn").prop("disabled", false);
					$("#create_message_template_btn").html("Save");

					//$('#template-message').html('');

				},
				timeout: 15000
			});
			return false;

		}

	});


	//Set placeholder
	$(document).on('click', '#add-placeholder', function(event){

		var placeholder = $('#placeholder').val();
		editor.value = (editor.getEditorValue().substring(0, editor.getEditorValue().length) + placeholder);

	});


});

function deleteMessageTemplate(message, title, row_number){

	if(confirm('Are you sure you want to delete ' + title + '?'))
	$.ajax({
		url: '/delete-message-template/'+message,
		type: 'GET',
		dataType: 'json',
		beforeSend: function(){

			$("#template-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-spinner' aria-hidden='true'></i> Deleting Template... </div>");
			
		},
		error: function (jqXhr, textStatus, errorMessage){
			if (jqXhr.status == 401) {

				$("#template-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Sorry, you are not authorized to perfom this action.</div>");
		
			}

			if (jqXhr.status == 404) {

				$("#template-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Message Template not found </div>");
				
			}

			if(textStatus == 'timeout'){

				$("#template-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Timeout. Please try again. </div>");
			
			}

			else
				$("#template-message").html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i>There was an error. Please try again. </div>");

		},
		success: function(data, status, xhr){
			if(xhr.status == 200){

				document.getElementById("messageTemplateTable").deleteRow(row_number);

				$("#template-message").html('');

				$.notify(
							{	
								message: "<i class='fa fa-info-circle' aria-hidden='true'></i> Message Template deleted successfully"
							},
							{
								type: 'success'
							}
						);

			}

		},
		timeout: 15000
	});
}





