<?php
require_once 'utils.php';

$serializado = file_get_contents('obj-auth.serializado');
$obj 		 = unserialize($serializado);

$response 				  = new StdClass;
$response->access_token   = $obj->request->access_token;
$response->token_type 	  = $obj->request->token_type;

$accesstoken 			  = $response->access_token;
$_SESSION['access_token'] = $accesstoken;
$access_token  			  = $accesstoken;
// if (isset($response->access_token)) {
// 	$accesstoken 			  = $response->access_token;
// 	$_SESSION['access_token'] = $response->access_token;
// }

// if (isset($_GET['code'])) {
// *access_token session is passed here for data persist after refreshing the page**
// 	$accesstoken = $_SESSION['access_token'];
// }
// if (isset($_REQUEST['logout'])) {
// 	unset($_SESSION['access_token']);
// }

$headers = array('Host: www.google.com',
				 'Authorization: '.$response->token_type.' ' . $access_token,
				 'GData-Version: 3.0');

$contactId    = "157aa8188764037";
$contactQuery = "https://www.google.com/m8/feeds/contacts/default/full/$contactId";
$ch 		  = curl_init();

curl_setopt($ch, CURLOPT_URL, $contactQuery);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 400);
curl_setopt($ch, CURLOPT_FAILONERROR, true);

$result 			= curl_exec($ch);
$base_url 			= curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
$http_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

print "<br/>";
var_dump($result,$base_url,$http_response_code);
