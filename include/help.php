<?php
// 載入模組(房型專案列表)
require_once('global_php/module_onsale.php');

if($_SESSION["cust_sid"])
{
	$list["content"] = '<textarea name="TE" cols="65" rows="30">
	<h3>沐戀商旅後驛館<br>
	  住址：80757高雄市三民區博愛一路338號<br>
	  四方通行客服聯絡電話：07-9682715</h3>
	  <br>
	
	<h2>訂房流程說明：</h2>
	1.訂單聯絡 <br>
	2.住房費用 <br>
	3.預付訂房費用 <br>
	4.匯款期限 <br>
	5.付款方式 <br>
	6.入住﹝Check in﹞手續 <br>
	7.離店﹝CHECK OUT﹞手續 <br>
	8.訂單異動 <br>
	9.保留住宿權益（保留住房） <br>
	10.付款後取消訂單 <br>
	11. 天候因素 <br>
	12.申辦方式及客服時間 <br>
	13.旅行業代收轉付收據 <br>
	14.其他 <br>
	<br>
	</textarea>
	<div style="padding:3px;text-align:center"><input name="Sout" value="修改" type="button" /></div>
	';
}
else{
	$list["content"] = '
	<h3>沐戀商旅後驛館<br>
	  住址：80757高雄市三民區博愛一路338號<br>
	  四方通行客服聯絡電話：07-9682715</h3>
	  <br>
	
	<h2>訂房流程說明：</h2>
	1.訂單聯絡 <br>
	2.住房費用 <br>
	3.預付訂房費用 <br>
	4.匯款期限 <br>
	5.付款方式 <br>
	6.入住﹝Check in﹞手續 <br>
	7.離店﹝CHECK OUT﹞手續 <br>
	8.訂單異動 <br>
	9.保留住宿權益（保留住房） <br>
	10.付款後取消訂單 <br>
	11. 天候因素 <br>
	12.申辦方式及客服時間 <br>
	13.旅行業代收轉付收據 <br>
	14.其他 <br>
	<br>
	';
} ?>