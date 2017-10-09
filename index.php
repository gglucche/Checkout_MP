<? 

session_start();

require_once('../conexao9.php');

$query = $select_assoc_info = //Select com a tabela que guarda dados do  PAGAMENTO

$fetch = mysql_fetch_array($query);

?>
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
<script>Mercadopago.setPublishableKey("DADO__PESSOAL");</script>

<div class="container">

<form action="pagamento.php" method="post" id="pay" name="pay" >

    <fieldset>
                  <div class="form-group col-md-12"> 
                    <img id="visa" src="https://img.mlstatic.com/org-img/MP3/API/logos/visa.gif" emaille="opacity: 0.3">
                    <img id="master" src="https://img.mlstatic.com/org-img/MP3/API/logos/master.gif" style="opacity: 0.3">
                    <img id="elo" src="https://img.mlstatic.com/org-img/MP3/API/logos/elo.gif" style="opacity: 0.3">
                    <img id="diners" src="https://img.mlstatic.com/org-img/MP3/API/logos/diners.gif" style="opacity: 0.3">
                    <img id="hipercard" src="https://img.mlstatic.com/org-img/MP3/API/logos/hipercard.gif" style="opacity: 0.3">
                    <img id="amex" src="https://img.mlstatic.com/org-img/MP3/API/logos/amex.gif" style="opacity: 0.3">
                    <img id="melicard" src="https://img.mlstatic.com/org-img/MP3/API/logos/melicard.gif" style="opacity: 0.3">
                    <input id="paymentMethodId" type="hidden" data-checkout="paymentMethodId"/>
                    <input name="bandeira" type="hidden" id="bandeira" value="" />
    			   </div>
                <div class="form-group col-md-4">
                <label for="email">Email</label>
                <input  class="form-control" id="email" name="email" value="test_user_19653727@testuser.com" type="email" placeholder="your email"/>
                </div>
                <div class="form-group col-md-4">
                <label for="cardNumber">Número do Cartão:</label>
                <input  class="form-control" type="text" id="cardNumber" data-checkout="cardNumber" placeholder="4509 9535 6623 3704" value="4509 9535 6623 3704"  onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off/>
                </div>
                <div class="form-group col-md-4">
                <label for="securityCode">Código de Segurança:</label>
                <input style="width:60px;" class="form-control" type="text" id="securityCode" data-checkout="securityCode" value="123" placeholder="123"  onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off/>
                </div>
                <div class="form-group col-md-4">
                <label for="cardExpirationMonth">Validade - Mês/Ano:</label><br>
                <div class="col-md-3" style="float: left">
                <input class="form-control" style="padding-left: 0 !important;"    type="text" id="cardExpirationMonth" data-checkout="cardExpirationMonth" value="12" placeholder="12"  onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off/>
                </div>
                <div class="col-md-3">
                <input class="form-control"  type="text" id="cardExpirationYear" data-checkout="cardExpirationYear" value="2024" placeholder="2024"  onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off/>
                </div>
                </div>
                <div class="form-group col-md-4">          
                <label for="cardholderName">Nome no Cartão:</label>
                <input  class="form-control" type="text" id="cardholderName" data-checkout="cardholderName" placeholder="APRO" value="APRO"  onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off/>
                    
                </div>

                
                <input type="hidden" id="amount" name="amount" value="<?= $fetch['valor'] ?>" />
                <input class="form-control" id="installments" name="installments" type="hidden" value="1"  onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off/>
                <!--<select id="issuer" name="issuer"></select>-->
           

                <input data-checkout="docType" type="hidden" value="CPF"/>
                <div class="form-group col-md-4">
                <label for="docNumber">CPF:</label>
                <input  class="form-control" type="text" id="docNumber" data-checkout="docNumber" value="19119119100" placeholder="12345678912" />
                </div>
                <div align="center" class="form-group col-md-12">
                <button style="float: right; background-color:#4fa69d; font-size: 20pt; width: 200px; border-radius: 5px !important;  margin: 20px; color: white" class="btn btn-default" type="submit" >PAGAR ></button>
                </div>
    </fieldset>
</form>

</div>
<script src="funcoesMP.js"></script>