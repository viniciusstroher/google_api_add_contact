<?php

require_once 'utils.php';


$doc = new DOMDocument('1.0', 'UTF-8');
$doc->formatOutput = true;

$entry = $doc->createElement('entry');
$entry->setAttribute('gd:etag', $etag);
$doc->appendChild($entry);

$category = $doc->createElement('category');
$category->setAttribute('scheme', 'http://schemas.google.com/g/2005#kind');
$category->setAttribute('term', 'http://schemas.google.com/contact/2008#contact');
$entry->appendChild($category);

$id = $doc->createElement('id', 'http://www.google.com/m8/feeds/contacts/default/base/'.$idgmail);
$entry->appendChild($id);

$updated = $doc->createElement('updated', $update_info);
$entry->appendChild($updated);

// Add group info (My Contacts)

$group = $doc->createElement('gContact:groupMembershipInfo');
$entry->appendChild($group);
$group->setAttribute('deleted', 'false');
$group->setAttribute('href', 'http://www.google.com/m8/feeds/groups/default/base/6');

// Add another group info

$group = $doc->createElement('gContact:groupMembershipInfo');
$entry->appendChild($group);
$group->setAttribute('deleted', 'false');
$group->setAttribute('href', 'http://www.google.com/m8/feeds/groups/default/base/'.$my_group_id);

$group_info = $doc->saveXML();
#And this is my cURL:

$url = 'https://www.google.com/m8/feeds/contacts/default/full/'.$idgmail.'/';

$headers = array(
'Host: www.google.com',
'Gdata-version: 3.0',
'Content-length: '.strlen($group_info),
'Content-type: application/atom+xml',
'If-Match: *',
'Authorization: OAuth '.$access,
);

$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $group_info);
curl_setopt($curl, CURLOPT_FAILONERROR, true);

$resp = curl_exec($curl); 
print_r($resp); // Prints nothing
echo curl_getinfo($curl, CURLINFO_HTTP_CODE); // Gives 400
curl_close($curl);