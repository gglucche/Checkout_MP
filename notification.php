<? 
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
require_once "../conexao9.php";
require_once "mercadopago.php";

$mp = new MP("APP...");

$json_event = file_get_contents('php://input', true);
$event = json_decode($json_event);

if (!isset($event->type, $event->data) || !ctype_digit($event->data->id)) {
	http_response_code(400);
	return;
}

if ($event->type == 'payment'){
    $payment_info = $mp->get('/v1/payments/'.$event->data->id);

    if ($payment_info["status"] == 200) {
        print_r($payment_info["response"]);
        $myfile = fopen("notifications.txt", "w") or die("falha ao gerar arquivo!");
		$txt = "payment_info:\n";
		fwrite($myfile, $txt);
		$txt = serialize($payment_info);
		fwrite($myfile, $txt);
		fclose($myfile);
		$updt = mysql_query("UPDATE `starkclub_pf` SET `payment_info` = '".$payment_info["response"]."' WHERE `starkclub_pf`.`id_trans` = '".$_GET['id']."'") or die(mysql_error());
    }
}
	
?>
