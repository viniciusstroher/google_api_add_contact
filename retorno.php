<?php
var_dump($_REQUEST);
file_put_contents('key-retorno.txt', var_export($_REQUEST,true));

// array(2) { ["code"]=> string(89) "4/WgDQpII6oCvj5YeKvRWSf8CjL-BUnbHDwgI6Vub4Sw9wq8hwLGx6aka1Q3Hymmg0D7mRUayJvHnaT_K_TSViqAw" ["scope"]=> string(31) "https://www.google.com/m8/feeds" }


$obj 		  = new StdClass;
$obj->request = $_REQUEST;
$serializado  = serialize($obj);
// store $s somewhere where page2.php can find it.
file_put_contents('obj-retorno.serializado', $serializado);
print "<br/><br/><br/><br/> <pre>";

require_once 'create1-auth.php';