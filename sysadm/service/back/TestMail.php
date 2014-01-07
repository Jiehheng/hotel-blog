<?php
        $to = 'shengeih@kingfor.com.tw';
        $send_all_text="";
        $subject= "客服系統連絡通知，".$object -> cal_number."_".$object -> cal_name."";
        $send_all_text=$send_all_text = "<p>親愛的奇豐客戶 您好，<br>
<br>
您在本案子中問題回覆如下：<br><br>
<br><br>

<br><br>
敬請參考上述回覆，希望能對您有所幫助，若有內文不清楚或需要進一步協助者，為了確保我們能收到您的相關資訊，請不要修改您的郵件主旨並直接回覆本郵件，或請您於上班時間來電奇豐資訊客服中心，服務專線(市話)07-558-3368轉9 
(服務時間：週一至週五，上午09:00至下午18:00)。<br>
<br>
再次感謝您的支持與愛護，同時對我們的服務感到滿意。<br>
<br>
<br>
<br>
敬祝：<br>
<br>
　　　　　　　　　　順心！<br>
<br>
<br>
<br>
奇豐資訊客服中心 (email: <a href=mailto:rita@kingfor.com.tw>rita@kingfor.com.tw</a>)<br>
<br>
客戶服務專線 : (07) 5583368轉9<br>
<br>
客戶服務傳真 : (07) 5585696<br>
<br>
奇豐資訊網址 : <a href=http://www.kingfor.com.tw target=_blank>http://www.kingfor.com.tw</a></p>";

    
    
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=big5\r\n";
    $headers .= "From: "."KingFor 客服系統<rita@kingfor.com.tw>"."\r\n";
    $headers .= 'Bcc: civet.studio@gmail.com' . "\r\n"; // 密件副本
    $success = mail($to, $subject, $send_all_text, $headers);    
?>