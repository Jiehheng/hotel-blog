<?php
// 載入模組(房型專案列表)
require_once('global_php/module_onsale.php');

//echo $cUrl_sid;
if(!$rs = $db->Execute("SELECT * FROM `sys_traffic` WHERE `cust_sid` = '".$cUrl_sid."'")) Fail("資料庫連接失敗");
$rs_ar = $db->fetch_array($rs);
$list["content"] = $rs_ar["content"];
?>