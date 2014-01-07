<?php
// 載入模組(房型專案列表)
require_once('global_php/module_onsale.php');

//echo $cUrl_sid;
if(!$rs = $db->Execute("SELECT * FROM `sys_plant` WHERE `cust_sid` = '".$cUrl_sid."'")) Fail("資料庫連接失敗");
while ($rowsDist=$db->fetch_assoc($rs))
{
	if($rowsDist['type']=='message1') $list["text1"] = $rowsDist["content"];
	if($rowsDist['type']=='message2') $list["text2"] = $rowsDist["content"];
}
?>