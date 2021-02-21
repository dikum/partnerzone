$(document).ready(function(){
	var statement_window;
	var dialogIndex;
	var dialogId;
	
	$('#show-statement').click(function(){
		if(!$("#icon-title-list #statement-icon-title").length){

			statement_window = new Window("Bank Statement", {
				state: WindowState.NORMAL,
				size: {
					width: 1200,
					height: 900
				},
				selected: true,
				icon: "<img src='./assets/images/icons/statement.png'>",
			});

			statement_window.content.id = 'statement_content';

			dialogIndex = dialogIDIndex + 1;
			dialogId = 'window_'+dialogIndex;
			dialogIndexArray[dialogId] = dialogId;
			windowObjectArray[dialogIndex] = statement_window;
			dialogIDIndex++;

			statement_window.on('closed', function(){
				
				$("#statement-icon-title").remove()

			});
			$.ajax({
				url: '/show-statement',
				type: 'GET',
				dataType: 'html',
				beforeSend: function(){
					//window selected is the class name of the create window
					$('.window_selected').css('zIndex',  "9999");
					statement_window.content.innerHTML = "<div style='color:maroon; text-align:center;'><i class='fa fa-spinner' aria-hidden='true'></i> Please wait...</div>";
				},
				error: function (jqXhr, textStatus, errorMessage){
					if (jqXhr.status == 401) {
						statement_window.content.innerHTML = "<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized to view this page</div>";
					}

					if(textStatus == 'timeout'){
						statement_window.content.innerHTML = "<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Could not load content</div>";
					}

					else
						statement_window.content.innerHTML = "<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was an error. Please try again.</div>";

				},
				success: function(data, status, xhr){
					if(xhr.status == 200){

						statement_window.content.innerHTML = data;
						var table = $('#statementTable').DataTable({autoWidth: true});

						$("#icon-title-list").append("<li><div class='icon-title' id='statement-icon-title'><img src='./assets/images/statement.png'></div></li>");
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

	$(document).on('click', '#statement-icon-title', function(){
		resetDialogZIndexes();
		bringToFront(dialogId);
		statement_window.show();
	});

	//getting click event to show modal
    $('#show-import-statement').click(function () {
        $('#importStatementModal').modal();
      
      //appending modal background inside the bigform-content
        $('.modal-backdrop').appendTo('.main-section');
      //removing body classes to able click events
        $('body').removeClass();
    });
	
});