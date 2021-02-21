

function cancel_message_sending(job_identifier){

	if (confirm('Are you sure you want cancel?'))
		$.ajax({
			url: '/cancel_message_sending',
			type: 'GET',
			dataType: 'json',
			beforeSend: function(){
				$('#'+job_identifier).prop("disabled", true);
			},
			error: function (jqXhr, textStatus, errorMessage){
				$('#'+job_identifier).prop("disabled", false);

				if(textStatus == 'timeout'){
					$('#bulk_message_notification .message-error').html("<div style='color:maroon; text-align:center;><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Timeout. Could not cancel message</div>");
				}

				else
					$('#bulk_message_notification .message-error').html("#bulk_message_notification .message-error").html("i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Could not cancell message");

			},
			success: function(data, status, xhr){
				if(data.message == 'success'){
					$("#bulk_message_notification #"+job_identifier).html("<label class='label label-success'>Cancelled</label>");
					
				}

			}
		});
}


