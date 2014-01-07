<?php
header("Cache-control: private");
$db_host = 'localhost';			// wowbuyhouse.db.8676074.hostedresource.com
$db_user = 'wowbook_hotel';		// wowbuyhouse
$db_psword = 'CPRYr5rHNRbU9ub4';// aA!wowwow8888
								// https://sg2nlsmysqladm1.secureserver.net/grid50/105

//// Database Setup Start ////
$db_name = 'wowbook_hotel';
$domain = 'http://localhost/WOW/house/';
$intranet_ip = '192.168.1.1';  //限定區網
$RealLnk = strtr($_SERVER["SCRIPT_FILENAME"], "\'", "/");
$RealLnk = substr($RealLnk,0,strrpos($RealLnk, "/")+1);
$web_title = '部落格訂房網';
$mail_link = '<a href="http://blog.wowbooking.com" target="_blank">http://blog.wowbooking.com</a>';
$ext_html = 'global_html/';
$ext_file = 'upload_image/';
//// Database Setup End ////

$remoteaddr = $_SERVER['REMOTE_ADDR'];	// 歷史紀錄 IP REMOTE ADDR
$datetime = date("Y-m-d H:i:s");		// 歷史紀錄 Datetime
$DestDIR = "D:/Websites/WOW/hotel-blog/";
$real_DestDIR = "../_documents/";

// FB Auth.
$FBarray = array(
  'appId'  => '150857848420849',
  'secret' => 'e7d1027f540390962b30db4ce24e16b8'
);

// Credit Auth. 台灣里認證
$CRarray = array(
  'mid' => '7197',
  'code_start' => 'b6e074633af18b0dd1673e2b426ee86a',
  'code_end' => '8fc164b0123ed9b27537b27b970461d9',
  'access' => '69639577',
  'submit' => 'https://www.twv.com.tw/openpay/pay.php',
  'submit_debug' => 'VisualPay.php',
  'debug' => 0
);
?>