<?php
    include_once('../Config.php');
    include_once('../Db_connect.php');
	session_start();

	extract($_POST);
	if($HTTP_SERVER_VARS[WINDIR]<>"" && $HTTP_ENV_VARS[OSTYPE]=="") 
	{
    	// USE "WINDOWS";
	}
	else
	{
	    // USE "LINUX";
    	$ser_respond = addslashes($ser_respond);
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $ser_respond = htmlspecialchars($ser_respond);
    
    $ServiceResult = mysql_query("SELECT * FROM service_record WHERE ser_id = '$_POST[ser_id]'");
    $ServiceObject = mysql_fetch_object($ServiceResult);
    $ser_question = $ServiceObject -> ser_question; // 原始留言
    
    $result = mysql_query("SELECT * FROM case_list,customerbody,customer WHERE case_list.cubid = customerbody.cubid AND cal_number = '$cal_number' AND customer.cusid = customerbody.cusid");
    $object = mysql_fetch_object($result);
        $to = "ginny@kingfor.com.tw";
        $send_all_text="";
        $subject= "客服系統回覆，".$object -> cal_number."_".$object -> cal_name;
        $send_all_text=$send_all_text = "<p><font face="Arial" style="font-size: 12pt" >親愛的奇豐客戶 您好，<br>
<br><font color="#0066CC">
您在本案子中所提出的內容如下：</font><br><br>
『".$ser_question."』<br><br><font color="#0066CC">
在本次問題中我們回覆的內容如下：</font><br><br>
『".$ser_respond."』
<br><br><font color="#666666">
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
奇豐資訊客服中心 (email: <a href=mailto:rita@kingfor.com.tw>rita@kingfor.com.tw</a>)<br>
<br>
客戶服務專線 : (07) 5583368轉9<br>
<br>
客戶服務傳真 : (07) 5585696<br>
<br>
奇豐資訊網址 : <a href=http://www.kingfor.com.tw target=_blank>http://www.kingfor.com.tw</a></font></p>";

    
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=big5\r\n";
    $headers .= "From: "."KingFor 客服系統<rita@kingfor.com.tw>"."\r\n";
    $headers .= 'Bcc: ginny@kingfor.com.tw' . "\r\n"; // 密件副本
    $success = mail($to, $subject, $send_all_text, $headers);    
    

		
?>
