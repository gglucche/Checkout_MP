<meta charset="utf-8">
<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once ('//DADO_PESSOAL');
	
require_once ('//DADO_PESSOAL');

session_start();

$select_assoc_info = mysql_query("SELECT * FROM `starkclub_pf` INNER JOIN starkclub_associado ON starkclub_associado.id = starkclub_pf.vinc_id WHERE starkclub_pf.vinc_id =".$_SESSION['pfAssoc']);

$dado_user = mysql_fetch_array($select_assoc_info);

$nome_user = explode(" ",$dado_user['nome']);

	
if(mysql_num_rows($select_assoc_info)>0){// exite na tabela starkclub_pf

$mp = new MP('//DADO_PESSOAL'); 

$payment_data = array(

   "transaction_amount"   => doubleval($dado_user['valor']), //valor da compra
    "token"                => $_POST['token'], //token gerado pelo javascript da index.php
    "description"          => "Starkclub PF: ".$dado_user['plano'], //descrição da compra
    "installments"         => 1, //parcelas
    "payment_method_id"    => $_POST['bandeira'], //forma de pagamento (visa, master, amex...)
    "payer"                => array ("email" => $dado_user['email']), //e-mail do comprador
    "statement_descriptor" => $dado_user['nome'], //nome para aparecer na fatura do cartão do cliente
    "notification_url" 	   => "http://www.starkclub.com.br/club/MP/notification.php",

);

print_r($payment_data);
	
$payment = $mp->post("/v1/payments", $payment_data);

print_r($payment);	
	
if ($payment['status']['id'] == 205){ echo "<script> alert('Digite o seu número de cartão.'); window.location.assign('https://www.starkclub.com.br/club/checkout-payment-end'); </script>" ; }
elseif ($payment['status']['id'] == 208){ echo "<script> alert('Escolha um mês.'); window.location.assign('https://www.starkclub.com.br/club/checkout-payment-end'); </script>" ; }
elseif ($payment['status']['id'] == 209){ echo "<script> alert('Escolha um ano.'); window.location.assign('https://www.starkclub.com.br/club/checkout-payment-end'); </script>" ; }
elseif ($payment['status']['id'] == 212){ echo "<script> alert('Insira o seu documento.'); window.location.assign('https://www.starkclub.com.br/club/checkout-payment-end'); </script>" ; }
elseif ($payment['status']['id'] == 213){ echo "<script> alert('Insira o seu documento.'); window.location.assign('https://www.starkclub.com.br/club/checkout-payment-end'); </script>" ; }
elseif ($payment['status']['id'] == 214){ echo "<script> alert('Insira o seu documento.'); window.location.assign('https://www.starkclub.com.br/club/checkout-payment-end'); </script>" ; }
elseif ($payment['status']['id'] == 220){ echo "<script> alert('Digite o seu banco emissor.'); window.location.assign('https://www.starkclub.com.br/club/checkout-payment-end'); </script>" ; }
elseif ($payment['status']['id'] == 221){ echo "<script> alert('Insira o nome e o sobrenome.'); window.location.assign('https://www.starkclub.com.br/club/checkout-payment-end'); </script>" ; }
elseif ($payment['status']['id'] == 224){ echo "<script> alert('Digite o código de segurança.'); window.location.assign('https://www.starkclub.com.br/club/checkout-payment-end'); </script>" ; }
elseif ($payment['status']['id'] == 301){ echo "<script> alert('Há algo errado com este número. Volte a digitá-lo.); window.location.assign('https://www.starkclub.com.br/club/checkout-payment-end'); </script>" ; }
elseif ($payment['status']['id'] == 302){ echo "<script> alert('Revise o código de segurança.'); window.location.assign('https://www.starkclub.com.br/club/checkout-payment-end'); </script>" ; }
elseif ($payment['status']['id'] == 316){ echo "<script> alert('Insira um nome válido.'); window.location.assign('https://www.starkclub.com.br/club/checkout-payment-end'); </script>" ; }
elseif ($payment['status']['id'] == 322){ echo "<script> alert('Revise o seu documento.'); window.location.assign('https://www.starkclub.com.br/club/checkout-payment-end'); </script>" ; }
elseif ($payment['status']['id'] == 323){ echo "<script> alert('Revise o seu documento.'); window.location.assign('https://www.starkclub.com.br/club/checkout-payment-end'); </script>" ; }
elseif ($payment['status']['id'] == 324){ echo "<script> alert('Revise o seu documento.'); window.location.assign('https://www.starkclub.com.br/club/checkout-payment-end'); </script>" ; }
elseif ($payment['status']['id'] == 325){ echo "<script> alert('Revise a data.'); window.location.assign('https://www.starkclub.com.br/club/checkout-payment-end'); </script>" ; }
elseif ($payment['status']['id'] == 326) { echo "<script> alert('Revise a data.'); window.location.assign('https://www.starkclub.com.br/club/checkout-payment-end'); </script>" ; }	
else {	
$update_card_info = "UPDATE `starkclub_pf` SET `id_trans` = '".$payment['response']['id']."', `date_created` = '".$payment['response']['date_created']."', `date_approved` = '".$payment['response']['date_approved']."', `payment_type_id` = '".$payment['response']['payment_type_id']."', `card_status` = '".$payment['response']['status']."', `net_received_amount` = '".$payment['response']['transaction_details']['net_received_amount']."', `card_first_six_digits` = '".$payment['response']['card']['first_six_digits']."', `card_last_four_digits` = '".$payment['response']['card']['last_four_digits']."', `card_expiration_month` = '".$payment['response']['card']['expiration_month']."', `card-expiration_year` = '".$payment['response']['card']['expiration_year']."', `installments` = '".$payment['response']['installments']."' WHERE vinc_id = ".$_SESSION['pfAssoc'];	
	
mysql_query($update_card_info) or die(mysql_error());

if($payment['response']['status'] == "rejected"){
	echo "<script>alert('Infelizmente seu cartão não foi aceito, contate a administradora do cartão ou tente outro meio de pagamento');
	window.location.assign('https://www.starkclub.com.br/club/checkout-payment-end'); </script>";
}	
else if ($payment['response']['status'] == "pending")	{
	echo "<script>alert('Seu pagamento está pendente. Aguarde o e-mail para acessar.');
		window.location.assign('https://www.starkclub.com.br/club/painel_pf'); </script>";
}
else if ($payment['response']['status'] == "in_process")	{
		echo "<script>alert('Seu pagamento está em processo de aprovação, aguarde nosso retorno para acessar o produto');
		window.location.assign('https://www.starkclub.com.br/club/'); </script>";
}
else if ($payment['response']['status'] == "in_mediation")	{
	
		echo "<script>alert('Seu pagamento está em processo de mediação, aguarde nosso retorno para retirar o dinheiro');
		window.location.assign('https://www.starkclub.com.br/club/painel_pf'); </script>";
}
else if ($payment['response']['status'] == "rejected")	{
				echo "<script>alert('Seu pagamento foi aprovado com sucesso! Bem vindo ao clube Starkclub');
		window.location.assign('https://www.starkclub.com.br/club/painel_pf'); </script>";
}
else if ($payment['response']['status'] == "cancelled")	{
				echo "<script>alert('Seu pagamento foi cancelado com sucesso! Faça um novo paagmento para não perder as promoções incríveis');
		window.location.assign('https://www.starkclub.com.br/club/'); </script>";
}
else if ($payment['response']['status'] == "refunded")	{
				echo "<script>alert('Seu pagamento foi estornado para sua conta');
		window.location.assign('https://www.starkclub.com.br/club/'); </script>";
}
else if($payment['response']['status'] == "approved"){
	
	echo "<script>alert('Seu pagamento foi aprovado com sucesso! Bem vindo ao clube Starkclub');
		window.location.assign('https://www.starkclub.com.br/club/painel_pf'); </script>";
}		
	
}
	
} else {// direciona para a tela de escolha dos planos
	
		echo "<script>alert('Para Utilizar o Starkclub Pessoa Física, é preciso fazer o cadastro Starkclub PF ou Logar em uma conta com um plano selecionado');
		window.location.assign('https://www.starkclub.com.br/club/checkout-start'); </script>";
} 
?>