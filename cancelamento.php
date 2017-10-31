<meta charset="utf-8">
<?php

require_once ('mercadopago.php');
	
require_once ('../conexao9.php');

session_start();

$select_assoc_info = mysql_query("SELECT * FROM `starkclub_pf` INNER JOIN starkclub_associado ON starkclub_associado.id = starkclub_pf.vinc_id WHERE vinc_id =".$_SESSION['idAssoc']);

if(mysql_num_rows($select_assoc_info)>0){// exite na tabela starkclub_pf

$mp = new MP('APP...'); 

$dado_user = mysql_fetch_array($select_assoc_info);	
	
$payment_data = array(
    "transaction_amount"   => doubleval($dado_user['valor']), //valor da compra intval($dado_user['valor'])
    "token"                => $_POST['token'], //token gerado pelo javascript da index.php
    "description"          => "Starkclub PF: ".$dado_user['plano'], //descrição da compra
    "installments"         => intval($_POST['installments']), //parcelas
    "payment_method_id"    => $_POST['bandeira'], //forma de pagamento (visa, master, amex...)
    "payer"                => array ("email" => $dado_user['email']), //e-mail do comprador
    "statement_descriptor" => $dado_user['nome'], //nome para aparecer na fatura do cartão do cliente
    "notification_url" 	   => "http://c74f0aad.ngrok.io/hml/webhooks.php",  
);

$payment = $mp->post("/v1/payments", $payment_data);


$update_card_info = "UPDATE `starkclub_pf` SET `id_trans` = '".$payment['response']['id']."', `date_created` = '".$payment['response']['date_created']."', `date_approved` = '".$payment['response']['date_approved']."', `payment_type_id` = '".$payment['response']['payment_type_id']."', `card_status` = '".$payment['response']['status']."', `net_received_amount` = '".$payment['response']['transaction_details']['net_received_amount']."', `card_first_six_digits` = '".$payment['response']['card']['first_six_digits']."', `card_last_four_digits` = '".$payment['response']['card']['last_four_digits']."', `card_expiration_month` = '".$payment['response']['card']['expiration_month']."', `card-expiration_year` = '".$payment['response']['card']['expiration_year']."', `installments` = '".$payment['response']['installments']."' WHERE vinc_id = ".$_SESSION['idAssoc'];	
	
mysql_query($update_card_info) or die(mysql_error());	

echo "<pre>";	
	
print_r($payment);

} else {// direciona para a tela de escolha dos planos
	
		echo "<script>alert('Para Utilizar o Starkclub Pessoa Física, é preciso fazer o cadastro Starkclub PF ou Logar em uma conta com um plano selecionado');
		window.location.assign('https://www.starkclub.com.br/club/checkout-start'); </script>";
	header("location: https://www.starkclub.com.br/club/checkout-start");
}
?>