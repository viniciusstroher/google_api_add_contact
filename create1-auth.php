<?php



require_once 'utils.php';
#O QUE VEM DO RETORNO .PHP

$fields = array(
	'code' 			=> urlencode($auth_code),
	'client_id' 	=> urlencode($google_client_id),
	'client_secret' => urlencode($google_client_secret),
	'redirect_uri'  => urlencode($google_redirect_uri),
	'grant_type'    => urlencode('authorization_code')
);

$post = '';
foreach ($fields as $key => $value) {
	$post .= $key . '=' . $value . '&';
}

$post = rtrim($post, '&');
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
curl_setopt($curl, CURLOPT_POST, 5);
curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
$result = curl_exec($curl);
curl_close($curl);



$response = json_decode($result);

var_dump($response,$result);
file_put_contents('auth-retorno.txt', var_export($response,true));
exit;

