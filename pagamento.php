<meta charset="utf-8">
<?php

require_once ('mercadopago.php');
	
require_once ('../conexao9.php');

session_start();

$select_assoc_info = //Select com a tabela que guarda dados do  PAGAMENTO

if(mysql_num_rows($select_assoc_info)>0){// EXITE NA TABELA STARKCLUB_PF

$mp = new MP('DADO__PESSOAL'); 

$dado_user = mysql_fetch_array($select_assoc_info);	

$nome_user = explode(" ",$dado_user['nome']); 
	
$payment_data = array(
    "transaction_amount"   => doubleval($dado_user['valor']), //VALOR DA COMPRA
    "token"                => $_POST['token'], //TOKEN GERADO PELO JAVASCRIPT INDEX.PHP
    "description"          => "Starkclub PF: ".$dado_user['plano'], //DESCRIÇÃO DA COMPRA
    "installments"         => 1, //PARCELAS
    "payment_method_id"    => $_POST['bandeira'], //FORMA DE PAGAMENTO (VISA, MASTERCARD, AMEX)
    "statement_descriptor" => $dado_user['nome'], //NOME NA FATURA DO CARTÃO
	"payment_method_id"	   => $_REQUEST['paymentMethodId'], // MEIO DE PAGAMENTO ESCOLHIDO.
    "statement_descriptor" => "STARKCLUB", // ESTE CAMPO IRÁ NA APARECER NA FATURA DO CARTÃO DO CLIENTE, LIMITADO A 10 CARACTERES.
    "notification_url"=> "http://www.starkclub.com.br/club/notification", //ENDEREÇO EM SEU SISTEMA POR ONDE DESEJA RECEBER AS NOTIFICAÇÕES DE STATUS: https://www.mercadopago.com.br/developers/pt/solutions/payments/custom-checkout/webhooks/
    /*"sponsor_id"=>12345678*/ //SOMENTE PARA DEVS/PLATAFORMAS QUE FOREM ADMINISTRAR MÚLTIPLAS LOJAS, INFORMANDO NESTE CAMPO O ID DE SUA CONTA MERCADO PAGO, TORNARÁ FACILMENTE RASTREAVEL AS VENDAS DE TODOS OS SEUS CLIENTES LOJISTAS.
    "payer"=> array(
        "email" => $dado_user['email'] //E-MAIL DO COMPRADOR
    ),
    "additional_info"=>  array(  // DADOS ESSENCIAIS PARA ANÁLISE ANTI-FRAUDE
        "items"=> array(array( //PARA CADA ITEM QUE ESTÁ SENDO VENDIDO É CRIADO UM ARRAY DENTRO DESTE ARRAY PAI COM AS INFORMAÇÕES DESCRITAS ABAIXO
            
                "id"=> $dado_user['plano'], //CÓDIGO IDENTIFICADOR DO SEU PRODUTO
                "title"=> "Aqui coloca os itens do carrinho", //TÍTULO DO ITEM
                "description"=> "StarkClub PF Cartão Benefício", //DESCRIÇÃO DO ITEM
                "category_id"=> "services", //CATEGORIA A QUAL O ITEM PERTENCE, LISTAGEM DISPONÍVEL EM: https://api.mercadopago.com/item_categories
                "quantity"=> 1, //QUANTIDADE A QUAL ESTA SENDO COMPRADO ESTE ITEM
                "unit_price"=> round((float)$_REQUEST['amount'],2) //VALOR UNITARIO DO ITEM INDEPENDENTE DO QUANTO ESTÁ SENDO COBRADO
            )
        ),
        "payer"=>  array( //INFORMAÇÕES PESSOAIS DO COMPRADOR
            "first_name"=> $nome_user[0],
            "last_name"=> $nome_user [1].$nome_user[2].$nome_user[3].$nome_user[4],
            "registration_date"=> "2014-06-28T16:53:03.176-04:00",
            "phone"=>  array(
                "number"=> $dado_user['telefone']
            ),
            "address"=>  array(
                "zip_code"=> $dado_user['cep'],
                "street_name"=> $dado_use['logradouro_nome'],
                "street_number"=> $dado_user['numero']
            )
        ),
    )
  );


$payment = $mp->post("/v1/payments", $payment_data);

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