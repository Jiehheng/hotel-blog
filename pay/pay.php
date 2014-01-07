<?php
header("Content-Type:text/html; charset=utf-8");
$code_start = 'b6e074633af18b0dd1673e2b426ee86a';
$code_end = '8fc164b0123ed9b27537b27b970461d9';
$code_access = '69639577';
$code_mid = '7197';
srand((double)microtime()*1000000);
$rand_number = rand();
$code_txid = date("YmdHis").$rand_number;
$code_amount = '100';
$verify = md5($code_start.'|'.$code_mid.'|'.$code_txid.'|'.$code_amount.'|'.$code_end);
echo $verify;
$cname = '陳玠亨';
$caddress = '高雄市岡山區嘉興路250號';
$ctel = '0922034573';
$cemail = 'jiehheng@yahoo.com.tw';
?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<body>
<form name="pay" method="post" action="https://www.twv.com.tw/openpay/pay.php">
 <input type="hidden" name="version" value="2.1">
 <input type="hidden" name="mid" value="<?=$code_mid?>">
 <input type="hidden" name="txid" value="<?=$code_txid?>">
 <input type="hidden" name="mode" value="1">
 <input type="hidden" name="select_paymethod" value="1">
 <!--<input type="hidden" name="prefer_paymethod" value="1">-->
 <input type="hidden" name="iid" value="0">
 <input type="hidden" name="amount" value="<?=$code_amount?>">
 <input type="hidden" name="verify" value="<?=$verify?>">
 <input type="hidden" name="access_key" value="<?=$code_access?>">
 <input type="hidden" name="return_url" value="http://localhost/WOW/hotel-blog/pay/pay.php">
 <input type="hidden" name="cname" value="<?=$cname?>">
 <input type="hidden" name="caddress" value="<?=$caddress?>">
 <input type="hidden" name="ctel" value="<?=$ctel?>">
 <input type="hidden" name="cemail" value="<?=$cemail?>">
 <input type="hidden" name="language" value="tchinese">
 <input type="hidden" name="encoding" value="utf-8">
 <input type="hidden" name="charset" value="utf-8">
 <input name="py" type="submit">
</form>
</body>
</html>
