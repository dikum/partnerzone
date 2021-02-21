$(document).ready(function(){

	setInterval(function(){
		var notification_count = 0;
		try{
			notification_count = parseInt($('#notification-count').val());
		}
		catch(err){}

		$.ajax({
			url: '/get-new-notifications',
			type: 'GET',
			dataType: 'json',
			error: function (jqXhr, textStatus, errorMessage){
				
				notification_count++;
				var time = new Date().getTime();
				$('#notification-list').prepend("<li>"
									+ "<div class='media'>"
									+	"<div class='media-body'>"
									+	"<h5 class='notification-user'>Error</h5>"
									+	"<p class='notification-msg'>Error Retrieving Notifications</p>"
									+	"<span class='notification-time'>" + time +  "</span>"
									+	"</div>"
									+	"</div>"
									+	"</li>");

				$('#notification-count').val(notification_count);
			},
			success: function(data, status, xhr){
				if(xhr.status == 200 &&  data['notifications'].length != 0){
					notification_count++;
					console.log(data);
					for(notification in data){

						console.log(notification);

						var job_id = data[notification]['job_id'];

						$('#notification-list').prepend("<li id='+" + job_id + "'>"
									+ "<div class='media'>"
									+	"<div class='media-body'>"
									+	"<h5 class='notification-user'>" + data[notification]['title'] + "</h5>"
									+	"<p class='notification-msg'>" + data[notification]['notification'] + "</p>"
									+	"<span class='notification-time'>" + data[notification]['created_at'] +  "</span>"
									+	"</div>"
									+	"</div>"
									+	"</li>");

						$('#notification-count').val(notification_count);
					}
				}

			},
			timeout: 15000
	});

	}, 30000);
	
});