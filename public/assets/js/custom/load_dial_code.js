$(document).ready(function(){
	//var dial_code;
	$(document).on('change', '#countryOfResidence', function(event){
		var selectedCountry = $(this).val();

		//countries variable gotten from show partner blade
		for (var i = 0; i<countries.length; i++) {
			if(countries[i]['countryIdentifier'] == selectedCountry){
				dial_code = countries[i]['countryDialingCode'];
				$('#primaryPhoneDialCode').html(dial_code);
			}
		}
	});


	$(document).on('change', '#same-dial-code', function(event){

		if(this.checked){

			$('#secondaryPhoneNumber').val($('#primaryPhoneDialCode').text() + $('#secondaryPhoneNumber').val());
		}
		else{

			var phoneNumber = $('#secondaryPhoneNumber').val();

			$('#secondaryPhoneNumber').val(phoneNumber.substring($('#primaryPhoneDialCode').text().length, phoneNumber.length));
		}

	})

});
