<?php
	include_once __DIR__ . '/google-api-php-client-2.2.2_PHP54/src/Google/autoload.php';
	

	#ACESSAR !!! PARA GERAR A CHAVE USAR OUTRO
	##https://console.developers.google.com/apis/credentials/oauthclient -> outro
	##https://console.developers.google.com/apis/credentials/oauthclient -> outro
	if(!file_exists("client_secret.json")){
		print "BAIXE O JSON DO SITE E RENOMEIE PARA client_secret.json <a href='https://console.developers.google.com/apis/credentials'/>Link</a>";
		exit;
	}

	if(!file_exists("refreshToken.json")){
		file_put_contents("refreshToken.json",json_encode(array('auth' => '', 'token' => '')));
	}

	if(isset($_POST['auth'])){
		$fileRefreshToken['auth']  = $_POST['auth'];
		$fileRefreshToken['token'] = "";
		$fileRefreshToken['email'] = $_POST['email'];
		
		file_put_contents("refreshToken.json", json_encode($fileRefreshToken));
	}

	function printForm(){
    	print "<form method='post'><input type='text' name='auth' placeholder='digite o id do site do google aquio' /><br/><input type='text' name='email' placeholder='digite o seu email' /><br/><button>Sakvar!</button></form>";
    }

	function auth(){
		$client = new Google_Client();
		$client->setApplicationName('RCX');

		// $client->setClientid($google_client_id);
		// $client->setClientSecret($google_client_secret);
		// $client->setRedirectUri($google_redirect_uri);
		// $client->setDeveloperKey('AIzaSyAtH-MNXzh6YfNlBM2NYAtudsKCPYxBf2E');
		$client->setScopes(['https://www.googleapis.com/auth/userinfo.profile', 
						'https://www.googleapis.com/auth/contacts', 
						'https://www.googleapis.com/auth/contacts.readonly',
							Google_Service_Oauth2::USERINFO_EMAIL,
							'https://www.googleapis.com/auth/plus.profile.emails.read',
							'https://www.googleapis.com/auth/plus.login', 
							'https://www.googleapis.com/auth/userinfo.email']);
		//NOVO AUTH
		$clinetScretFile = file_get_contents("client_secret.json");
		$clinetScretFile = json_decode($clinetScretFile,true);

		$client->setClientId($clinetScretFile['installed']['client_id']);
		$client->setClientSecret($clinetScretFile['installed']['client_secret']);
		$client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
		

		$AccessToken  	  = "";
		$fileRefreshToken = file_get_contents("refreshToken.json");
		$fileRefreshToken = json_decode($fileRefreshToken,true);

		if($fileRefreshToken['auth'] == ""){
			print "ABRA O SITE E COLOQUE ESSE ENDEREÇO LOGO APOS COLE DENTRO DE AUTH NO refreshToken.json"."<br/>\n\n";
			print "<a href='".$client->createAuthUrl()."'>Acesse aqui</a>";
			printForm();

	    }elseif ($fileRefreshToken['auth'] != "" && $fileRefreshToken['token'] == "") {
	    	#AUTHCODE - PRECISA DO NUMERO Q FICA NO OAUTH DO GOOGLE SITE
	    	print "<pre> Gerando acess token do id \n";
	    	$client->authenticate($fileRefreshToken['auth']);
			$fileRefreshToken['token'] = $client->getAccessToken();
	    	file_put_contents("refreshToken.json", json_encode($fileRefreshToken));
	    }else{
	    	
	    	$r = $client->refreshToken($fileRefreshToken['token']['access_token']);
	    	var_dump($r);
	    	if(isset($r['error'])){
	    		#limap expirado
	    		$fileRefreshToken['auth']  = "";
	    		$fileRefreshToken['token'] = "";
	    		$fileRefreshToken['email'] = "";
	    		
	    		file_put_contents("refreshToken.json", json_encode($fileRefreshToken));
	    		exit;
	    	}
			
			

	    	print "<pre> Refresh token ".$r['id_token']." \n";
	    	##############################
			# COLOCAR ESSES DADOS VINDOS DO SISTEMA"
			##############################
			
			$nomeContato 	 = "RCX - ".rand()." Contato";
			$enderecoContato = "POA, Felizardo Furtado,8";
			$familyName 	 = "RCX";
			$numeroTelefone  = "515959959595";
			$emailNewContacs = 'viniciusferreirawk@gmail.com';

			$accesstoken     = $r['access_token'];
			

			//ADICIONAR RETORNO DO SISTEMA AQUI !!!!
			$idUser = createUser($accesstoken,$nomeContato,$familyName,$enderecoContato,$emailNewContacs,$numeroTelefone);
	    	
			$groupId = getGroupId($accesstoken,$fileRefreshToken['email']);
			var_dump('groupId',$groupId);
	    	addToGroup($accesstoken,$fileRefreshToken['email'],$idUser,$groupId,$nomeContato,$numeroTelefone);

	    	$contact = getContact($accesstoken,$idUser);
	    	var_dump($contact);
	    	print "GOOGLE ID: ".$idUser;
	    }
    }


    function createUser($accesstoken,$nomeContato,$familyName,$enderecoContato,$emailNewContacs,$numeroTelefone){
    	
		$access_token = $accesstoken;
		$contactXML = '<?xml version="1.0" encoding="utf-8"?> '
		. '<atom:entry xmlns:atom="http://www.w3.org/2005/Atom" xmlns:gd="http://schemas .google.com/g/2005">'
		. ' <atom:category scheme="http://schemas.google.com/g/2005#kind" term="http://schemas.google.com/contact/2008#contact"/> '
		. '<gd:name> <gd:givenName>' . $nomeContato . '</gd:givenName>'
		. ' </gd:name> '
		. ' <gd:phoneNumber rel="http://schemas.google.com/g/2005#home" primary="true">' . $numeroTelefone . '</gd:phoneNumber> </atom:entry>';

		$headers = array('Host: www.google.com',
		'Gdata-version: 3.0',
		'Content-length: ' . strlen($contactXML),
		'Content-type: application/atom+xml',
		'Authorization: Bearer ' . $access_token);

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
		
		$result 			= curl_exec($ch);
		$base_url 			= curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		$http_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		#var_dump($result,$base_url,$http_response_code);
    	$xml = simplexml_load_string($result);
    	// var_dump($xml);
    	return str_replace("http://www.google.com/m8/feeds/contacts/viniciusferreirawk%40gmail.com/base/", "", $xml->id);

    }

    function addToGroup($access_token,$email,$idGoogle,$groupId,$name,$phone){
    	
    	$contactXML = '<?xml version="1.0" encoding="utf-8"?>
						<entry gd:etag="{lastKnownEtag}">
					  <id>'.$idGoogle.'</id>
					    <gd:name>
    						<gd:givenName>'.$name.'</gd:givenName>
    					</gd:name>
    					<gd:phoneNumber rel="http://schemas.google.com/g/2005#other" primary="true">'.$phone.'</gd:phoneNumber>
					  <gContact:groupMembershipInfo deleted="false"
					    href="http://www.google.com/m8/feeds/groups/'.urlencode($email).'/base/'.$groupId.'"/>
					</entry>';

		
		$headers = array('Host: www.google.com',
		'Gdata-version: 3.0',
		'Content-length: ' . strlen($contactXML),
		'Content-type: application/atom+xml',
		'If-match: *',
		'Authorization: Bearer ' . $access_token
		 
		);
						
		$contactQuery = 'https://www.google.com/m8/feeds/contacts/default/full/'.$idGoogle;
		$ch 		  = curl_init();

		curl_setopt($ch, CURLOPT_URL, $contactQuery);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $contactXML);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 400);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);

		$result 			= curl_exec($ch);
		$base_url 			= curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		$http_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    	// var_dump($result,$base_url,$http_response_code);
    }

    function getContact($access_token,$contactId){
    	$headers = array('Host: www.google.com',
				 'Authorization: Bearer ' . $access_token,
				 'GData-Version: 3.0');

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

		$xml = simplexml_load_string($result);
		return $xml;
    }

    function getGroupId($access_token,$email){
    	$headers = array('Host: www.google.com',
				 'Authorization: Bearer ' . $access_token,
				 'GData-Version: 3.0');

		$contactQuery = "https://www.google.com/m8/feeds/groups/default/full/";
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

		$xml = simplexml_load_string($result);

		try{
			return str_replace("http://www.google.com/m8/feeds/groups/".urlencode($email)."/base/", 
								"", 
								$xml->entry[0]->id[0]);
		}catch(Exception $ex){
			return 0;
		}
		
    }

    auth();
?>