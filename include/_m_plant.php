<?php
//echo $CustUsed;
if(!$rs = $db->Execute("SELECT * FROM `sys_plant` WHERE `cust_sid` = '".$CustUsed."'")) Fail("資料庫連接失敗");
while ($rowsDist=$db->fetch_assoc($rs))
{
	if($rowsDist['type']=='message1') $list["text1"] = htmlspecialchars($rowsDist["content"]);
	if($rowsDist['type']=='message2') $list["text2"] = htmlspecialchars($rowsDist["content"]);
}
?>