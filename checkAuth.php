<?php
	include_once __DIR__ . '/google-api-php-client-2.2.2_PHP54/src/Google/autoload.php';
	
	#MUDAR O EMAIL
	
	if(!file_exists("refreshToken.json")){
		file_put_contents("refreshToken.json",json_encode(array('auth' => '', 'token' => '')));
	}

	if(isset($_POST['auth'])){
		$fileRefreshToken['auth']  = $_POST['auth'];
		$fileRefreshToken['token'] = "";
		$fileRefreshToken['email'] = $_POST['email'];
		$fileRefreshToken['groupid'] = $_POST['groupid'];
		
		file_put_contents("refreshToken.json", json_encode($fileRefreshToken));
	}

	function printForm(){
    	print "<form method='post'><input type='text' name='auth' placeholder='digite o id do site do google aquio' /><br/><input type='text' name='email' placeholder='digite o seu email' /><br/><input type='text' name='groupid' placeholder='digite o id do group - pesquisar no ouath playground' /><br/><button>Sakvar!</button></form>";
    }

	function auth(){
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
			print "ABRA O SITE E COLOQUE ESSE ENDEREÇO LOGO APOS COLE DENTRO DE AUTH NO refreshToken.json"."<br/>\n\n";
			print $client->createAuthUrl();
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
	    		$fileRefreshToken['groupid'] = "";
	    		
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
			$idUser = createUser($accesstoken,$nomeContato,$familyName,$enderecoContato,$emailNewContacs,$numeroTelefone);
	    	addToGroup($accesstoken,$fileRefreshToken['email'],$idUser,$fileRefreshToken['groupid'],$nomeContato,$numeroTelefone);

	    	print "GOOGLE ID: ".$idUser;
	    }
    }


    function createUser($accesstoken,$nomeContato,$familyName,$enderecoContato,$emailNewContacs,$numeroTelefone){
    	
		$access_token = $accesstoken;
		$contactXML = '<?xml version="1.0" encoding="utf-8"?> '
		. '<atom:entry xmlns:atom="http://www.w3.org/2005/Atom" xmlns:gd="http://schemas.google.com/g/2005">'
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
    	var_dump($result,$base_url,$http_response_code);
    }
    auth();
?>