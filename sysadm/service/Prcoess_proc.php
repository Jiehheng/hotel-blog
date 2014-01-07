<?php
include_once('../../config/db_action.php');
include_once('../../lib/function.php');
session_start();
if(!$_SESSION["session_serial_id"]) echo html_start().Fail('系統閒置過長,請重新登入!');

extract($_POST);
$db=new db_action;
$is_Send_Mail = '1';		// 是否寄出通知信 0為關,1為開

// 回覆問題
if($HTTP_SERVER_VARS[WINDIR]<>"" && $HTTP_ENV_VARS[OSTYPE]=="") 
{
	// USE "WINDOWS";
}
else
{
	// USE "LINUX";
	$ser_respond = addslashes($ser_respond);
}
$ser_respond = htmlspecialchars($ser_respond);

// 抓取留言資料
$result = $db->Execute("SELECT `service_record`.*,`customer`.`id`,`customer`.`caption`,`website`.`demo_link_caption`,`website`.`window` 
FROM `service_record` 
LEFT JOIN `website` ON `service_record`.`cal_number`=`website`.`serial_id` 
LEFT JOIN `customer` ON `website`.`customer`=`customer`.`serial_id` 
WHERE `service_record`.`ser_id`='".$ser_id."'");
$row = $db->fetch_assoc($result);
$ser_question = $row['ser_question'];
	
// 查詢處理者郵件
if($row["accid"]) $ProcMaster = $row["accid"]; else $ProcMaster = $_SESSION["session_serial_id"];
$ServiceResult = $db->Execute("SELECT `email` FROM `account` WHERE `serial_id` = '".$ProcMaster."'");
$ServiceObject = mysql_fetch_object($ServiceResult);
$accemail = $ServiceObject -> email;

if($is_Send_Mail)
{
	// 抓出網站相關人員 email
	$relation_array = unserialize($row['window']);
	if(count($relation_array)=='1' && $relation_array[0]["email"]=='')
	{
		$to = "sammi@kingfor.com.tw,vivian@kingfor.com.tw,alice@kingfor.com.tw";
		$send_all_text="";
		$subject= "客服系統回覆，".$row['id']."_".$row['caption']."(請勿直接回覆本信件)";
		$send_all_text = "本案件 ".$row['caption']." 客戶 ".$relation_array[0]["unit"].$relation_array[0]["name"]."尚未有 Email 資料,因此無法送達 Email 告知客戶！";
	}else
		for($i=0;$i<count($relation_array);$i++)
		{
			if($relation_array["$i"]["email"])
			{
				if(isset($to))$to.=', '; else $to='';
				$to.= $relation_array["$i"]["unit"].' '.$relation_array["$i"]["name"].'<'.$relation_array["$i"]["email"].'>';
			}
		}
	
	// 寫信開始
	$send_all_text="";
	$subject= "客服系統回覆，".$row['id']."_".$row['caption'];
	$send_all_text = "<p><font size=2 face=Arial>親愛的奇豐客戶 您好，<br><br>
	<br><font color=#0066CC><strong>
	您在本案子中所提出的內容如下：</strong></font><br><br>
	『".nl2br($ser_question)."』<br><br><font color=#0066CC><strong>
	在本次問題中我們回覆的內容如下：</strong></font><br><br>
	『".nl2br($ser_respond)."』
	<br><br><br><hr color=#E0E0E0 size=1><br><font color=#666666>
	敬請參考上述回覆，希望能對您有所幫助，若有內文不清楚或需要進一步協助者，為了確保我們能收到您的相關資訊，請不要修改您的郵件主旨並直接回覆本郵件，或請您於上班時間來電奇豐資訊客服中心，服務專線(市話)07-558-3368轉9 
	(服務時間：週一至週五，上午09:00至下午18:00)。<br>
	<br>
	再次感謝您的支持與愛護，同時對我們的服務感到滿意。<br>
	<br>
	<br>
	敬祝：<br>
	<br>
	　　　　　　　　　　順心！<br>
	<br>
	<br>
	奇豐資訊客服中心 (email: <a href=mailto:alice@kingfor.com.tw>alice@kingfor.com.tw</a>)<br>
	客戶服務專線 : (07) 5583368轉9<br>
	客戶服務傳真 : (07) 5585696<br>
	奇豐資訊網址 : <a href=http://www.kingfor.com.tw target=_blank>http://www.kingfor.com.tw</a></font></p>";
	
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=utf-8\r\n";
	$headers .= "From: KingFor 客服系統<alice@kingfor.com.tw>\r\n";
	$headers .= 'Bcc: sammi@kingfor.com.tw,vivian@kingfor.com.tw,ginny@kingfor.com.tw,alice@kingfor.com.tw,'.$accemail  . "\r\n"; // 密件副本
	$success = mail(twu2b($to), $subject, $send_all_text, twu2b($headers));
}

$ser_respondtime = date("Y-m-d H:i:s");
$SQL = "UPDATE `service_record` SET `ser_status` = '已處理',
`ser_tinyint` = 'Y',
`ser_respond` = '$ser_respond',
`ser_respondtime` = '$ser_respondtime',
`accid` = '".$ProcMaster."' ,
`ser_hour` = '$ser_hour',
`ser_min` = '$ser_min'			
WHERE `ser_id` = '$ser_id' ";
$result = $db->Execute($SQL);

echo html_start().'<script type="text/javascript">alert("處理成功 ，按下確認後關閉本視窗!"); 
window.location.href="index.php"; self.close(); 
window.opener.location.href = window.opener.location.href;
</script>'.html_end();
?>