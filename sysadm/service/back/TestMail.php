<?php
        $to = 'shengeih@kingfor.com.tw';
        $send_all_text="";
        $subject= "�ȪA�t�γs���q���A".$object -> cal_number."_".$object -> cal_name."";
        $send_all_text=$send_all_text = "<p>�˷R���_�׫Ȥ� �z�n�A<br>
<br>
�z�b���פl�����D�^�Цp�U�G<br><br>
<br><br>

<br><br>
�q�аѦҤW�z�^�СA�Ʊ���z�������U�A�Y�����夣�M���λݭn�i�@�B��U�̡A���F�T�O�ڭ̯ব��z��������T�A�Ф��n�ק�z���l��D���ê����^�Х��l��A�νбz��W�Z�ɶ��ӹq�_�׸�T�ȪA���ߡA�A�ȱM�u(����)07-558-3368��9 
(�A�Ȯɶ��G�g�@�ܶg���A�W��09:00�ܤU��18:00)�C<br>
<br>
�A���P�±z������P�R�@�A�P�ɹ�ڭ̪��A�ȷP�캡�N�C<br>
<br>
<br>
<br>
�q���G<br>
<br>
�@�@�@�@�@�@�@�@�@�@���ߡI<br>
<br>
<br>
<br>
�_�׸�T�ȪA���� (email: <a href=mailto:rita@kingfor.com.tw>rita@kingfor.com.tw</a>)<br>
<br>
�Ȥ�A�ȱM�u : (07) 5583368��9<br>
<br>
�Ȥ�A�ȶǯu : (07) 5585696<br>
<br>
�_�׸�T���} : <a href=http://www.kingfor.com.tw target=_blank>http://www.kingfor.com.tw</a></p>";

    
    
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=big5\r\n";
    $headers .= "From: "."KingFor �ȪA�t��<rita@kingfor.com.tw>"."\r\n";
    $headers .= 'Bcc: civet.studio@gmail.com' . "\r\n"; // �K��ƥ�
    $success = mail($to, $subject, $send_all_text, $headers);    
?>