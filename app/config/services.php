<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	// 'mailgun' => array(
	// 	'domain' => 'sandboxf91d72ec42d84027904e56073b2c6ebd.mailgun.org',
	// 	'secret' => 'key-b37eb7e472e145b084775b83bf7fbecb',
	// ),

	'mailgun' => array(
		'domain' => 'ranbandokmaisod.com',
		'secret' => 'key-b37eb7e472e145b084775b83bf7fbecb',
	),

	'mandrill' => array(
		'secret' => '',
	),

	'stripe' => array(
		'model'  => 'User',
		'secret' => '',
	),

);
