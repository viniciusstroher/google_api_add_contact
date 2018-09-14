<?php
var_dump($_REQUEST);
file_put_contents('key-retorno.txt', var_export($_REQUEST,true));

// array(2) { ["code"]=> string(89) "4/WgDQpII6oCvj5YeKvRWSf8CjL-BUnbHDwgI6Vub4Sw9wq8hwLGx6aka1Q3Hymmg0D7mRUayJvHnaT_K_TSViqAw" ["scope"]=> string(31) "https://www.google.com/m8/feeds" }