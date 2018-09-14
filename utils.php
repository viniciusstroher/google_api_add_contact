<?php 
$max_results = 10;

#achar os dados no projeto - configurar a mesma url
$google_client_id = '231408011324-f039c8sr33ktgbipbuc249e74in518gm.apps.googleusercontent.com';
$google_client_secret = 'pRItA1PTgdHcdO7On8pgWGTG';
$google_redirect_uri = 'http://venizao.dlinkddns.com/google-api/retorno.php';

#pegar dados do token no key-retorno.txt
$auth_code 			 = '4/WgAKvyUNTUz9aoFGkA0NV5Tw565J2fOn4poWTdDmAvbdSnIGJL0t58vPprGpnu7pY0yXhQyDyPHHYwm_Hyh_ZZo';

#pegar access_code dentro de auth-retorno.txt
$acess_code = 'ya29.GlsYBotpQxkxHoXWdkpa3fv37h04ODIuWiVRmci36UV-FwKoDMCJDBt-V7VULVF0sYVF5dchgUJTMy-fnCXTnRDj81JL6XzTv3CL0tYurfZb-rus49yUEYo9JPNO';


#pegar id dentro de user-retorno.txt - http://www.google.com/m8/feeds/contacts/viniciusferreirawk%40gmail.com/base/391e6fcf09d3530b -> 391e6fcf09d3530b
$idgmail 	 = '391e6fcf09d3530b';
$groupId 	 = '6';
$userEmail 	 = 'viniciusferreirawk@gmail.com';

#USAR PLAYGROUND PARA LIST O GRUPO My contacts e pegar o ID
#http://www.google.com/m8/feeds/groups/viniciusferreirawk%40gmail.com/base/6
#https://stackoverflow.com/questions/39338209/how-delete-update-create-google-contacts-api-in-php
#https://stackoverflow.com/questions/15566287/google-api-contacts-v-3-php-add-a-contact-to-the-group
