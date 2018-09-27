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
	
	$AccessToken  	  = "";
	$fileRefreshToken = file_get_contents("refreshToken.json");
	$fileRefreshToken = json_decode($fileRefreshToken,true);

	if($fileRefreshToken['auth'] == ""){
		print "ABRA O SITE E COLOQUE ESSE ENDEREÇO LOGO APOS COLE DENTRO DE AUTH NO refreshToken.json"."\n";
		print $client->createAuthUrl();
    }elseif ($fileRefreshToken['auth'] != "" && $fileRefreshToken['token'] == "") {
    	#AUTHCODE - PRECISA DO NUMERO Q FICA NO OAUTH DO GOOGLE SITE
    	print "Gerando acess token do id \n";
    	$client->authenticate($fileRefreshToken['auth']);
		$fileRefreshToken['token'] = $client->getAccessToken();
    	file_put_contents("refreshToken.json", json_encode($fileRefreshToken));
    }else{
    	print "Refresh token \n";
    	$r = $client->refreshToken($fileRefreshToken['token']['access_token']);
    	var_dump($r);
    }

?>