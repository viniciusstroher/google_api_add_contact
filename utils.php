<?php 
$max_results = 10;

#achar os dados no projeto - configurar a mesma url
$google_client_id = '231408011324-f039c8sr33ktgbipbuc249e74in518gm.apps.googleusercontent.com';
$google_client_secret = 'pRItA1PTgdHcdO7On8pgWGTG';
$google_redirect_uri = 'http://venizao.dlinkddns.com/google-api/retorno.php';

#pegar dados do token no key-retorno.txt
$auth_code 			 = '4/WgCQHOwQyGVd6qt87ztsBQslooKSiROoQvjOMOwPz3FOJDRqOTVf8zmoMZ0uopLwOsL1A1elZjRKLvPn6dZpEBU';

#pegar access_code dentro de auth-retorno.txt
$acess_code = 'ya29.GlsYBj-fEZvDpdkTsryia7Tu2TqcUYWTKwWTM8s_FkAzgmNeKCWkycJcykkTeUhbH01yPUStApZQQcA_Q8JIP-yK20-9bzTMx2OGksEjMqyvAnlpq8-BPxOs-jFO';


#pegar id dentro de user-retorno.txt - http://www.google.com/m8/feeds/contacts/viniciusferreirawk%40gmail.com/base/391e6fcf09d3530b -> 391e6fcf09d3530b
$idgmail 	 = '6976230c08eade30';
$groupId 	 = '6';
#$userEmail 	 = 'viniciusferreirawk@gmail.com';
$userEmail 	 = 'viniciusferreirawk%40gmail.com';

#USAR PLAYGROUND PARA LIST O GRUPO My contacts e pegar o ID
#http://www.google.com/m8/feeds/groups/viniciusferreirawk%40gmail.com/base/6
#https://stackoverflow.com/questions/39338209/how-delete-update-create-google-contacts-api-in-php
#https://stackoverflow.com/questions/15566287/google-api-contacts-v-3-php-add-a-contact-to-the-group
