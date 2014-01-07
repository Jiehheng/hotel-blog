<?php
//echo $CustUsed;
if(!$rs = $db->Execute("SELECT * FROM `sys_traffic` WHERE `cust_sid` = '".$CustUsed."'")) Fail("資料庫連接失敗");
$rs_ar = $db->fetch_array($rs);
$list = htmlspecialchars($rs_ar["content"]);

?>