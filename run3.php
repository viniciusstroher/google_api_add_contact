<?php
include_once __DIR__ . '/google-api-php-client-2.2.2_PHP54/src/Google/autoload.php';

$serializado  = file_get_contents('obj-auth.serializado');
$obj 		  = unserialize($serializado);

if(isset($obj->request->refresh_token)){
	require_once 'utils.php';

	$client 	   	   = new Google_Client();
	$client->setClientid($google_client_id);
	$client->setClientSecret($google_client_secret);
	$client->setRedirectUri($google_redirect_uri);
	 
	$client->setDeveloperKey($apiKey);
	$client->setApprovalPrompt('force');
	$client->setAccessType('offline');

	// $access_token_auth = $client->authenticate($obj->request->access_token);
	$retorno 		   = $client->refreshToken($obj->request->refresh_token);
	
	// var_dump('$access_token_auth',$access_token_auth);
	var_dump('$retorno',$retorno);
}

require_once 'create4-getcontact.php';