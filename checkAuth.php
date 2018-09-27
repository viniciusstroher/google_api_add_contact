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
	
	//TOKEN GERADO SEMPRE PELO LINK GERADO
	$authCode 	  = "4/aADsuTeDvdDf6Upuz0IJzyYBBlVmlfPblQJ9VfRXfsRfqwlBkLF5PBQ";
	
	$AccessToken  = "";

	if($AccessToken == ""){
		$googleImportUrl = $client->createAuthUrl();
    }else{
    	#AUTHCODE - PRECISA DO NUMERO Q FICA NO OAUTH DO GOOGLE SITE
    	$client->authenticate($authCode);
		$AccessToken = $client->getAccessToken();
    }
	
	
	var_dump($r1,$r2);
?>