<?php
	include_once __DIR__ . '/google-api-php-client-2.2.2_PHP54/src/Google/autoload.php';
	#include_once "templates/base.php";
	require_once 'utils.php';
	$client = new Google_Client();
	$client->setApplicationName('My application name');

	// $client->setClientid($google_client_id);
	// $client->setClientSecret($google_client_secret);
	// $client->setRedirectUri($google_redirect_uri);
	// $client->setDeveloperKey('AIzaSyAtH-MNXzh6YfNlBM2NYAtudsKCPYxBf2E');
	$client->setScopes(['https://www.googleapis.com/auth/userinfo.profile', 
					'https://www.googleapis.com/auth/contacts', 
					'https://www.googleapis.com/auth/contacts.readonly']);
	//NOVO AUTH
	$client->setClientId('231408011324-qmsva7kdink9apksa1arrpvcd66hi372.apps.googleusercontent.com');
	$client->setClientSecret('O3NKI8csGhuGD29H-vx9RPlZ');
	$client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
	
	
	$authCode = "4/aADe92SS-yo86Gv7SEhknqmBQQIBxEaPegdW431Mte1Hf9_SsJazv-w";
	$r 		  = $client->authenticate($authCode);

	var_dump($r);
?>