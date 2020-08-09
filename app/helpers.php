<?php
function isLoggedInUserAdmin(){

	return session('user')['type'] === 'admin';
}

function getCurrencyCodeFromCollection($currencies, $currency_id){
	
	foreach($currencies as $currency){

		if($currency['currencyIdentifier'] == $currency_id)
			return $currency['currencyShortName'];
	}

	return 'Unknown';

}

function getBankNameFromCollection($banks, $bank_id){

	foreach($banks as $bank){

		if($bank['bankIdentifier'] == $bank_id)
			return $bank['bankName'];

	}

	return 'Unknown';

}

function getCountryNameFromCollection($countries, $country_id){

	foreach($countries as $country){

		if($country['countryIdentifier'] == $country_id)
			return $country['countryName'];

	}

	return 'Unknown';

}

function getTemplateMessageFromCollection($templates, $template){
	
}