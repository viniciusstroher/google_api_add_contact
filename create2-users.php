<?php

require_once 'utils.php';
/***Accessing Access_Token and setting the session for access token so that after refreshing the page it will still persist in page***/

$id1 = "Vinicius RXC2";
$id2 = "POA RS2";
$id3 = "RCX IT2";
$id4 = "9541245988882";


$response = new StdClass;
$response->access_token = $acess_code;


if (isset($response->access_token)) {
	$accesstoken = $response->access_token;
$_SESSION['access_token'] = $response->access_token;
}

if (isset($_GET['code'])) {
/**access_token session is passed here for data persist after refreshing the page***/
	$accesstoken = $_SESSION['access_token'];
}
if (isset($_REQUEST['logout'])) {
	unset($_SESSION['access_token']);
}

$max_results = 10;

$url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results=' . $max_results . '&oauth_token=' . $accesstoken;
$xmlresponse = file_get_contents($url);
if ((strlen(stristr($xmlresponse, 'Authorization required')) > 0) && (strlen(stristr($xmlresponse, 'Error ')) > 0)) { //At times you get Authorization error from Google.
	echo "<h2>OOPS !! Something went wrong. Please try reloading the page.</h2>";
	exit();
}
/**This is main script which is used for contact saving in account,variable declare before is passed here.***/
$access_token = $_SESSION['access_token'];
$contactXML = '<?xml version="1.0" encoding="utf-8"?> '
. '<atom:entry xmlns:atom="http://www.w3.org/2005/Atom" xmlns:gd="http://schemas.google.com/g/2005">'
. ' <atom:category scheme="http://schemas.google.com/g/2005#kind" term="http://schemas.google.com/contact/2008#contact"/> '
. '<gd:name> <gd:givenName>' . $id1 . '</gd:givenName> <gd:fullName></gd:fullName> <gd:familyName>' . $id3 . '</gd:familyName>'
. ' </gd:name> <gd:email rel="http://schemas.google.com/g/2005#home" address="' . $id2 . '"/> '
. '<gd:im address="hitman@gmail.com" protocol="http://schemas.google.com/g/2005#GOOGLE_TALK" primary="true" rel="http://schemas.google.com/g/2005#home"/>'
. ' <gd:phoneNumber rel="http://schemas.google.com/g/2005#home" primary="true">' . $id4 . '</gd:phoneNumber> </atom:entry>';
$headers = array('Host: www.google.com',
'Gdata-version: 3.0',
'Content-length: ' . strlen($contactXML),
'Content-type: application/atom+xml',
'Authorization: OAuth ' . $access_token);
$contactQuery = 'https://www.google.com/m8/feeds/contacts/default/full/';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $contactQuery);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $contactXML);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 400);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
$result = curl_exec($ch);

var_dump($result);
file_put_contents('user-retorno.txt', var_export($result,true));