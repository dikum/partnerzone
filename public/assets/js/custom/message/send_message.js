$(document).ready(function(){
	var create_message_window;
	var search_criteria_count = 1;
	var dialogIndex;
	var dialogId;
	var messageEditor;
	
	$('#show-send-message').click(function(){
		if(!$("#icon-title-list #create-message-icon-title").length){

			create_message_window = new Window("Send Message", {
				state: WindowState.NORMAL,
				size: {
					width: 1200,
					height: 900
				},
				selected: true,
				icon: "<img src='./assets/images/icons/message.png'>",
			});

			create_message_window.content.id = 'create_message_content';

			dialogIndex = dialogIDIndex + 1;
			dialogId = 'window_'+dialogIndex;
			dialogIndexArray[dialogId] = dialogId;
			windowObjectArray[dialogIndex] = create_message_window;
			dialogIDIndex++;

			create_message_window.on('closed', function(){
				
				$("#create-message-icon-title").remove()

			});
			$.ajax({
				url: '/create-message',
				type: 'GET',
				dataType: 'html',
				beforeSend: function(){
					create_message_window.content.innerHTML = "<div style='color:maroon; text-align:center;'><i class='fa fa-spinner' aria-hidden='true'></i> Please wait...</div>";
				},
				error: function (jqXhr, textStatus, errorMessage){
					if (jqXhr.status == 401) {
						create_message_window.content.innerHTML = "<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized to view this page</div>";
					}

					if(textStatus == 'timeout'){
						create_message_window.content.innerHTML = "<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Could not load content</div>";
					}

					else
						create_message_window.content.innerHTML = "<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was an error. Please try again.</div>";

				},
				success: function(data, status, xhr){
					if(xhr.status == 200){

						//window selected is the class name of the create window
						$('.window_selected').css('zIndex',  "9999");
						create_message_window.content.innerHTML = data;

						messageEditor = new Jodit("#messageEditor", {
					  		"uploader": {
					    	"insertImageAsBase64URI": true
					  		},
					  		"theme": "dark"
						});

						$("#icon-title-list").append("<li><div class='icon-title' id='create-message-icon-title'><img src='./assets/images/message.png'></div></li>");
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


	$(document).on('click', '#send-message-btn', function(){

		$("#send-message-form").validate({
			rules:{
				subject: {
					required: '#send_as_email:checked'
				},
				sender: {
					required: '#send_as_email:checked'
				},
				messageEditor: 'required',
				send_as_email: {
					required: '#send_as_sms :not(:checked)'
				}
			},

			submitHandler: submitForm			
		});

		function submitForm(){

			var data = $('#send-message-form').serializeArray();
			var mesasgePartnerTable = getPartnerMessageTable();
			if(mesasgePartnerTable != null && mesasgePartnerTable != 'undefined'){
				var tableRows = mesasgePartnerTable.rows().data().toArray();
				data.push({name: 'tableData', value: tableRows});

			}
			event.preventDefault();
			$.ajax({
				url: '/send-message',
				type: 'POST',
				//data: $.param(data),
				data: data,
				dataType: 'json',
				beforeSend: function(){
					$('#create-message').html("<div style='color:maroon; text-align:center;'> Please wait... </div>");

					$("#send-message-btn").prop("disabled", false);
					$("#send-message-btn").html("<i class='fa fa-spinner' aria-hidden='true'></i>");
				},
				error: function (jqXhr, textStatus, errorMessage){
					if(jqXhr.status == 401)
						$('#create-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized.</div>");

					else if(jqXhr.status == 404)
						$('#create-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Could not find message information</div>");

					else if(jqXhr.status == 403){
						$('#create-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Unauthorized access</div>");
					}

					else if(jqXhr.status == 422){
						$('#create_message_window-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Unauthorized access</div>");
					}
					
					else if(textStatus == 'timeout')
						$('#create-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Please try again </div>");

					else
						$('#create-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was a problem. Please try again.</div>");

				},
				success: function(data, status, xhr){
					if(data.message == 'success'){
						$.notify(
							{	
								message: "<i class='fa fa-info-circle' aria-hidden='true'></i> Message Sending..."
							},
							{
								type: 'success'
							}
						);

						$('#create-message').html('');

						setPartnerMessageTable(); //Resets the table after success
					}
					else
						$('#create-message').html("<div style='color:maroon; text-align:center;'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> " + data.message + "</div>");
				},
				complete: function(jqXhr, status){

					$("#send-message-btn").prop("disabled", false);
					$("#send-message-btn").html("Send");

					//$('#template-message').html('');

				},
				timeout: 15000
			});
			return false;

		}
	});

	$(document).on('click', '#create-message-icon-title', function(){
		resetDialogZIndexes();
		bringToFront(dialogId);
		create_message_window.show();
	});

	//Set placeholder
	$(document).on('click', '#add-message-placeholder', function(event){

		var placeholder = $('#message-placeholder').val();
		messageEditor.value = (messageEditor.getEditorValue().substring(0, messageEditor.getEditorValue().length) + placeholder);

	});

	//Set template
	$(document).on('click', '#add-message-template', function(event){

		var template = $('#message-template').val();
		if(template != '')
			$.ajax({
					url: '/get-template/'+template,
					type: 'GET',
					dataType: 'json',
					error: function (jqXhr, textStatus, errorMessage){
						$('#create-message').html("<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Could not get template</div>");
					},
					success: function(data, status, xhr){
						if(xhr.status == 200){
							messageEditor.value = data.messageTemplate;
						}

					},
					timeout: 15000
				});
		
	});;

});

