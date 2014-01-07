<?php
if(!isset($_GET["id"]) || !$_GET["id"]) Fail("引數錯誤");

$rs = $db->Execute("SELECT serial_id,news_typ,title,content,hyplnk,news_time FROM `sys_bulletin` WHERE `serial_id` = '".$_GET["id"]."'");
if($db->num_rows($rs))
{
	$rsPost = $db->fetch_array($rs);
	$list["title"] = $rsPost["title"];
	$list["Datetime"] = date("F d, Y",strtotime($rsPost["news_time"]));
	$list["text"] = htmlspecialchars_decode($rsPost["content"]);
	
	$rs_type = $db->Execute("SELECT `serial_id`,`caption` FROM `sys_bulletin_type` WHERE `serial_id` = '".$rsPost["news_typ"]."'");
	if($db->num_rows($rs_type))
	{
		$Data_type = $db->fetch_array($rs_type);
		$list["typeurl"] = '&type='.$Data_type[0];
		$list["type"] = $Data_type[1];
	}
	else $list["type"] = '全部文章';
}
else Fail("查無相關文章");
?>