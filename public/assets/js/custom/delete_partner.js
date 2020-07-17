
function deletePartner(partner, fullname, row_number){
	if(confirm('Are you sure you want to delete ' + fullname + '?'))
	$.ajax({
		url: '/delete-partner/'+partner,
		type: 'GET',
		dataType: 'json',
		beforeSend: function(){

			$.notify(
					{	
						message: 'Please wait'
					},
					{
							type: 'warning'
					}
					);
			
		},
		error: function (jqXhr, textStatus, errorMessage){
			if (jqXhr.status == 401) {
				$.notify(
							{
								message:"<i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Sorry, you are not authorized to perfom this action."
							},
							{
								type: 'danger'
							}
						);
			}

			if (jqXhr.status == 404) {
				$.notify(
							{
								message:"<i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Partner not found"
							},
							{
								type: 'danger'
							}
						);
			}

			if(textStatus == 'timeout'){
				$.notify(
							{
								message:"<i class='fa fa-exclamation-triangle' aria-hidden='true'></i>Timeout. Please try again."
							},
							{
								type: 'danger'
							}
						);
			}

			else
				$.notify(
							{
								message: "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i>There was an error. Please try again."
							},
							{
								type: 'danger'
							}
						);

		},
		success: function(data, status, xhr){
			if(xhr.status == 200){

				$.notify(
							{	
								message: "<i class='fa fa-info-circle' aria-hidden='true'></i> Partner deleted successfully"
							},
							{
								type: 'success'
							}
						);

				document.getElementById("partnerTable").deleteRow(row_number);
			}

		},
		timeout: 15000
	});

}

