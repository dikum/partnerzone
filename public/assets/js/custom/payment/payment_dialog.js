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
						var table = $('#paymentTable').DataTable({autoWidth: true});

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

	$(document).on('mouseenter', '.payment-row', function(e){

		var description = $(this).find('.payment-description').text();
		var depositor = $(this).find('.payment-depositor').text();
		var email = $(this).find('.payment-email').text();
		var phone = $(this).find('.payment-phone').text();
		//var x = e.pageX - 80;
		//var y = e.pageY - 70;

		var dialog_position = payment_window.getPosition();
		var dialog_size = payment_window.getSize();
		

		$('.payment-content').html("<p><strong>" +email+ " </strong>  <strong>" +phone+ "</strong></p> <p>" +depositor+ "</p> <p>" +description+ "</p>");

		var x = dialog_position.x;
		var y = dialog_size.width - 500;

		$('.full-payment-details').css({position:'fixed', left:x, top:y});
		$('.full-payment-details').css({display:'block'});


		
	});

	$(document).on('mouseleave', '.payment-row', function(){

		$('.full-payment-details').css({display:'none'});
		$('.payment-content').text('');
		
	});



});