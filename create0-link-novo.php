<?php

session_start();

include_once __DIR__ . '/google-api-php-client-2.2.2_PHP54/src/Google/autoload.php';
#include_once "templates/base.php";


require_once 'utils.php';


$client = new Google_Client();
$client->setApplicationName('My application name');
$client->setClientid($google_client_id);
$client->setClientSecret($google_client_secret);
$client->setRedirectUri($google_redirect_uri);
// $client->setAccessType('online');
 
$client->setAccessType('offline');
 

$client->setScopes('https://www.google.com/m8/feeds');
 
$googleImportUrl = $client->createAuthUrl();

print "<br/><br/><br/><br/><br/>";

print $googleImportUrl;

