<?php

require_once 'utils.php';


/**This is main script which is used for contact saving in account,variable declare before is passed here.***/

$contactXML = '<?xml version="1.0" encoding="utf-8"?>
<entry gd:etag="{lastKnownEtag}">
  <id>http://www.google.com/m8/feeds/contacts/{userEmail}/base/{idgmail}</id>
  <gContact:groupMembershipInfo deleted="false"
    href="http://www.google.com/m8/feeds/groups/{userEmail}/base/{groupId}"/>
</entry>';

$headers = array('Host: www.google.com',
'Gdata-version: 3.0',
'Content-length: ' . strlen($contactXML),
'Content-type: application/atom+xml',
'Authorization: OAuth ' . $acess_code);
$contactQuery = 'https://www.google.com/m8/feeds/contacts/default/full/';
$ch = curl_init();
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
$result = curl_exec($ch);

var_dump($result);
file_put_contents('user-retorno.txt', var_export($result,true));

