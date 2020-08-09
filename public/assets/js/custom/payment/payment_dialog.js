$(document).ready(function(){
	var payment_window;
	var dialogIndex;
	var dialogId;
	
	$('#payment').click(function(){
		if(!$("#icon-title-list #payment-icon-title").length){

			payment_window = new Window("Payments", {
				state: WindowState.NORMAL,
				size: {
					width: 1200,
					height: 900
				},
				selected: true,
				icon: "<img src='./assets/images/icons/payment.png'>",
			});

			payment_window.content.id = 'payment_content';

			dialogIndex = dialogIDIndex + 1;
			dialogId = 'window_'+dialogIndex;
			dialogIndexArray[dialogId] = dialogId;
			windowObjectArray[dialogIndex] = payment_window;
			dialogIDIndex++;

			payment_window.on('closed', function(){
				
				$("#payment-icon-title").remove()

			});
			$.ajax({
				url: '/payments',
				type: 'GET',
				dataType: 'html',
				beforeSend: function(){
					payment_window.content.innerHTML = "<div style='color:maroon; text-align:center;'><i class='fa fa-spinner' aria-hidden='true'></i> Please wait...</div>";
				},
				error: function (jqXhr, textStatus, errorMessage){
					if (jqXhr.status == 401) {
						payment_window.content.innerHTML = "<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Sorry, you are not authorized to view this page</div>";
					}

					if(textStatus == 'timeout'){
						payment_window.content.innerHTML = "<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Could not load content</div>";
					}

					else
						payment_window.content.innerHTML = "<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> There was an error. Please try again.</div>";

				},
				success: function(data, status, xhr){
					if(xhr.status == 200){

						//window selected is the class name of the created window
						$('.window_selected').css('zIndex',  "9999");
						payment_window.content.innerHTML = data;

						$("#icon-title-list").append("<li><div class='icon-title' id='payment-icon-title'><img src='./assets/images/payment.png'></div></li>");
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


	$(document).on('click', '#payment-icon-title', function(){
		//payment_window.changeOption('selected', true);
		//$('#window_0').css('zIndex', '999999999999');
		resetDialogZIndexes();
		bringToFront(dialogId);
		payment_window.show();
	});

});