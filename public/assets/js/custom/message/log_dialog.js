$(document).ready(function(){
	var message_log_window;
	var dialogIndex;
	var dialogId;
	
	$('#show-message-log').click(function(){
		if(!$("#icon-title-list #message-log-icon-title").length){

			message_log_window = new Window("Message Log", {
				state: WindowState.NORMAL,
				size: {
					width: 1200,
					height: 900
				},
				selected: true,
				icon: "<img src='./assets/images/icons/message_log.jpg'>",
			});

			message_log_window.content.id = 'message_log_content';

			dialogIndex = dialogIDIndex + 1;
			dialogId = 'window_'+dialogIndex;
			dialogIndexArray[dialogId] = dialogId;
			windowObjectArray[dialogIndex] = message_log_window;
			dialogIDIndex++;

			message_log_window.on('closed', function(){
				
				$("#message-log-icon-title").remove()

			});
			$.ajax({
				url: '/message-log',
				type: 'GET',
				dataType: 'html',
				beforeSend: function(){
					//window selected is the class name of the create window
					$('.window_selected').css('zIndex',  "9999");
					message_log_window.content.innerHTML = "<div style='color:maroon; text-align:center;'><i class='fa fa-spinner' aria-hidden='true'></i> Please wait...</div>";
				},
				error: function (jqXhr, textStatus, errorMessage){
					if (jqXhr.status == 401) {
						message_log_window.content.innerHTML = "<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized to view this page</div>";
					}

					if(textStatus == 'timeout'){
						message_log_window.content.innerHTML = "<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Could not load content</div>";
					}

					else
						message_log_window.content.innerHTML = "<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was an error. Please try again.</div>";

				},
				success: function(data, status, xhr){
					if(xhr.status == 200){

						message_log_window.content.innerHTML = data;
						var table = $('#messageLogTable').DataTable({autoWidth: true});

						$("#icon-title-list").append("<li><div class='icon-title' id='message-log-icon-title'><img src='./assets/images/message_log.jpg'></div></li>");
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

	$(document).on('click', '#message-log-icon-title', function(){
		resetDialogZIndexes();
		bringToFront(dialogId);
		message_log_window.show();
	});
	
});