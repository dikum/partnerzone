<?php
return [

	'oauth' => [
		'CLIENT_ID' => 1,
		'CLIENT_SECRET' => '4uwBOX8HAh6fonFPaRrHXx8z76S9WmZOVLIUgq3D',
		'PASSWORD_CLIENT_ID' => 2,
		'PASSWORD_CLIENT_SECRET' => 'yIJ1dGkdpIuzaPTwhdDrVA8u5MNiPMXxQYl15vZl'
	],

	'calendar' => [
		'MONTHS' => array(
		'1' => 'January',
		'2' => 'February',
		'3' => 'March',
		'4' => 'April',
		'5' => 'May',
		'6' => 'June',
		'7' => 'July',
		'8' => 'August',
		'9' => 'September',
		'10' => 'October',
		'11' => 'November',
		'12' =>'December'
		),
	],

	'marital_status' => [
		'SINGLE' => 'single',
		'MARRIED' => 'married',
		'DIVORCED' => 'divorced'
	],

	'genders' => [
		'MALE' => 'male',
		'FEMALE' => 'female'
	],

	'preferred_languages' => [
		'english' => 'english',
		'spanish' => 'spanish',
		'french' => 'french',
		'portuguese' => 'portuguese'
	],

	'branches' => [
		'lagos' => 'Lagos',
		'south africa' => 'south africa',
		'ghana' => 'ghana'
	],

	'api' => 'http://partnersoft.test',

	'message_settings' => [

		'vas2nets_username' => 'dev@emmanuel.tv',
		'vas2nets_password' => 'em@2018_182*1',
		'vas2nets_url' => 'http://www.v2nmobile.com/api/httpsms.php?u=dev@emmanuel.tv&p=em@2018_182*1&m=[MESSAGE]&r=[RECIPIENT]&s=[SENDER]&t=1',

		'bbn_username' => 'dev@emmanuel.tv',
		'bbn_password' => 'EmmTech2018',

		'twilio_account_sid' => 'ACa7130be977833cecfb8b018d4f9fe25b',
		'twilio_account_token' => 'e6cf7b0f1ce4db9995526a4ebc6219ae',
		'sms_sender' => 'Emmanuel TV'
	],

	'mailgun' => [

		'secret' => env('MAILGUN_SECRET'),
		'base_url' => env('MAILGUN_API_BASE_URL'),
		'mail_from' => env('MAIL_FROM_ADDRESS'),
		'domain' => env('MAILGUN_DOMAIN')
	],


];