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
    $ser_question = $ServiceObject -> ser_question; // ��l�d��
    
    $result = mysql_query("SELECT * FROM case_list,customerbody,customer WHERE case_list.cubid = customerbody.cubid AND cal_number = '$cal_number' AND customer.cusid = customerbody.cusid");
    $object = mysql_fetch_object($result);
        $to = "ginny@kingfor.com.tw";
        $send_all_text="";
        $subject= "�ȪA�t�Φ^�СA".$object -> cal_number."_".$object -> cal_name;
        $send_all_text=$send_all_text = "<p><font face="Arial" style="font-size: 12pt" >�˷R���_�׫Ȥ� �z�n�A<br>
<br><font color="#0066CC">
�z�b���פl���Ҵ��X�����e�p�U�G</font><br><br>
�y".$ser_question."�z<br><br><font color="#0066CC">
�b�������D���ڭ̦^�Ъ����e�p�U�G</font><br><br>
�y".$ser_respond."�z
<br><br><font color="#666666">
�q�аѦҤW�z�^�СA�Ʊ���z�������U�A�Y�����夣�M���λݭn�i�@�B��U�̡A���F�T�O�ڭ̯ব��z��������T�A�Ф��n�ק�z���l��D���ê����^�Х��l��A�νбz��W�Z�ɶ��ӹq�_�׸�T�ȪA���ߡA�A�ȱM�u(����)07-558-3368��9 
(�A�Ȯɶ��G�g�@�ܶg���A�W��09:00�ܤU��18:00)�C<br>
<br>
�A���P�±z������P�R�@�A�P�ɹ�ڭ̪��A�ȷP�캡�N�C<br>
<br>
<br>
�q���G<br>
<br>
�@�@�@�@�@�@�@�@�@�@���ߡI<br>
<br>
<br>
�_�׸�T�ȪA���� (email: <a href=mailto:rita@kingfor.com.tw>rita@kingfor.com.tw</a>)<br>
<br>
�Ȥ�A�ȱM�u : (07) 5583368��9<br>
<br>
�Ȥ�A�ȶǯu : (07) 5585696<br>
<br>
�_�׸�T���} : <a href=http://www.kingfor.com.tw target=_blank>http://www.kingfor.com.tw</a></font></p>";

    
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=big5\r\n";
    $headers .= "From: "."KingFor �ȪA�t��<rita@kingfor.com.tw>"."\r\n";
    $headers .= 'Bcc: ginny@kingfor.com.tw' . "\r\n"; // �K��ƥ�
    $success = mail($to, $subject, $send_all_text, $headers);    
    

		
?>
