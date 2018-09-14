<?php

require_once 'utils.php';


/**This is main script which is used for contact saving in account,variable declare before is passed here.***/

$contactXML = '<?xml version="1.0" encoding="utf-8"?>
<entry gd:etag="contactEtag">
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
file_put_contents('user-group-retorno.txt', var_export($result,true));




// PUT /m8/feeds/contacts/default/full/6976230c08eade30 HTTP/1.1
// Host: www.google.com
// Gdata-version: 3.0
// Content-length: 1285
// Content-type: application/atom+xml
// If-match: *
// Authorization: Bearer ya29.GlsYBlyxmqGXKU3sAw8ygVna95tw8fXhN0zskZKkwWnfams4vAFAnXzXvMAQTmWNol38-Or-ozKCPIw5o1Ot_G5NOyQj3bbJVvoS3UY_-Cq-Uv-THhfAiX9KhTld
// <entry gd:etag="{lastKnownEtag}">
//   <id>http://www.google.com/m8/feeds/contacts/viniciusferreirawk%40gmail.com/base/6976230c08eade30</id>
//   <updated>2018-09-13T23:09:02.303Z</updated>
//   <category scheme="http://schemas.google.com/g/2005#kind"
//     term="http://schemas.google.com/contact/2008#contact"/>
//   <gd:name>
//     <gd:givenName>New</gd:givenName>
//     <gd:familyName>Name</gd:familyName>
//     <gd:fullName>New Name</gd:fullName>
//   </gd:name>
//   <content type="text">Notes</content>
//   <link rel="http://schemas.google.com/contacts/2008/rel#photo" type="image/*"
//     href="https://www.google.com/m8/feeds/photos/media/viniciusferreirawk%40gmail.com/6976230c08eade30"/>
//   <link rel="self" type="application/atom+xml"
//     href="https://www.google.com/m8/feeds/contacts/viniciusferreirawk%40gmail.com/full/6976230c08eade30"/>
//   <link rel="edit" type="application/atom+xml"
//     href="https://www.google.com/m8/feeds/contacts/viniciusferreirawk%40gmail.com/full/6976230c08eade30"/>
//   <gd:phoneNumber rel="http://schemas.google.com/g/2005#other"
//     primary="true">456-123-2133</gd:phoneNumber>
//   <gd:extendedProperty name="pet" value="hamster"/>
//   <gContact:groupMembershipInfo deleted="false"
//     href="http://www.google.com/m8/feeds/groupsviniciusferreirawk%40gmail.com/base/6"/>
// </entry>
