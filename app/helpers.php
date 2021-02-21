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

function getTitleNameFromCollection($titles, $title_id){
	
	foreach($titles as $title){

		if($title['titleIdentifier'] == $title_id)
			return $title['titleName'];
	}

	return 'Unknown';

}

function getCurrencyIDFromCollection($currencies, $currency_code){
	
	foreach($currencies as $currency){

		if($currency['currencyShortName'] == $currency_code)
			return $currency['currencyIdentifier'];
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

function generateRandomCode(){
	$permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	return substr(str_shuffle($permitted_chars), 0, 10);
}