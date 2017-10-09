<meta charset="utf-8">
<?php

require_once ('mercadopago.php');

session_start();

$mp = new MP ("DADO__PESSOAL");
	
$dado_user = mysql_fetch_array($select_assoc_info);	

$filters = array (
    "email" => $dado_user['email']
);

$customer = $mp->get ("/v1/customers/search", $filters);

print_r ($customer);
	

?>	
