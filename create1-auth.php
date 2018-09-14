<?php



require_once 'utils.php';
#O QUE VEM DO RETORNO .PHP
print "<pre>";

$serializado = file_get_contents('obj-retorno.serializado');
$obj 		 = unserialize($serializado);
$auth_code   = $obj->request['code']; 

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


$obj 		  = new StdClass;
$obj->request = $response;
$serializado  = serialize($obj);
// store $s somewhere where page2.php can find it.
file_put_contents('obj-auth.serializado', $serializado);


print "RODAR run2.php - SETAR OS NOMES A ENVIAR ANTES!";