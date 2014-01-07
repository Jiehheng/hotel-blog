<?php
extract($_POST);

// 預設都通過
$tid = $rand_number = date("YmdHis");
$pay_type = 1;
$status = 1;
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>台灣里 OpenPay 線上付款 - 返回商家網站完成付款流程</title>
</head>

<body class="body">
<form id="return_form" name="process" method="post" action="<?=$return_url?>" class="form" onSubmit="isCloseCheck = false;" autocomplete="off">
<input type="hidden"  name="txid"       value="<?=$txid?>">
<input type="hidden"  name="amount"     value="<?=$amount?>">
<input type="hidden"  name="pay_type"   value="<?=$pay_type?>">
<input type="hidden"  name="status"     value="<?=$status?>">
<input type="hidden"  name="tid"        value="<?=$tid?>">
<input type="hidden"  name="verify"     value="<?=$verify?>">
<input type="hidden"  name="cname"      value="<?=$cname?>">
<input type="hidden"  name="caddress"   value="<?=$caddress?>">
<input type="hidden"  name="ctel"       value="<?=$ctel?>">
<input type="hidden"  name="cemail"     value="<?=$cemail?>">
<input type="hidden"  name="xname"      value="">
<input type="hidden"  name="xaddress"   value="">
<input type="hidden"  name="xtel"       value="">
<input type="hidden"  name="language"   value="tchinese">
<input type="hidden"  name="error_code" value="">
<input type="hidden"  name="error_desc" value="">
<input type="hidden"  name="auth_code"  value="068971">
<input type="hidden"  name="account_no" value="-">
<input type="hidden"  name="bill_url"   value="">
<input type="hidden"  name="cs_bill_url" value="">
<table id="paytable" class="layout_table" cellpadding="0" cellspacing="0">
  <tr class="zone_submit">
	<td style="color: red;">
	  模擬付款成功介面
	  <br /><br />
	  系統已確認您的付款, 請直接點選下方 [下一步] 按鈕完成交易流程.<br />如果隨後畫面出現安全性警訊視窗係屬正常現象, 請按繼續鍵進行流程.<br />(請勿直接關閉本視窗, 以免交易流程因中斷而無效)<br />
	  <br />
	  <input id="submit_button" type="submit" value="下一步" class="submit"><br /><br />
	  <span id="countdown_text"><span id="countdown_secs" class="countdown_secs"></span></span><br />
	</td>
  </tr>
</table>
</form>
</body>
</html>