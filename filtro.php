<meta charset="utf-8">
<?php

require_once ('mercadopago.php');
	
require_once ('../conexao9.php');

session_start();

$select_assoc_info = mysql_query("SELECT * FROM `starkclub_pf` INNER JOIN starkclub_associado ON starkclub_associado.id = starkclub_pf.vinc_id WHERE vinc_id =".$_SESSION['idAssoc']);

if(mysql_num_rows($select_assoc_info)>0){// exite na tabela starkclub_pf

$mp = new MP ("TEST...");
	
$dado_user = mysql_fetch_array($select_assoc_info);	

$filters = array (
    "email" => $dado_user['email']
);

$customer = $mp->get ("/v1/customers/search", $filters);

print_r ($customer);
	
} else {// direciona para a tela de escolha dos planos
	
		echo "<script>alert('Para Utilizar o Starkclub Pessoa Física, é preciso fazer o cadastro Starkclub PF ou Logar em uma conta com um plano selecionado');
		window.location.assign('https://www.starkclub.com.br/club/checkout-start'); </script>";
}
?>	
