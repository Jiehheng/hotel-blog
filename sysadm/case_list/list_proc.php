<?php
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');
	require_once("../../lib/mail.php");

	$db=new db_action;
    session_start();

	//檢測權限是否有 action 的權限,並且設定權限細項在$_SESSION中
	check_authority('action');

	extract($_POST);
	foreach ($_POST as $k => $v){
		if(is_array($v))$k = $v; else $k = filter_xss($v);
	}
	
	if($edit_service)
	{
		// 抓出求購者
		if (!$rsCase = $db->Execute("SELECT `whos`,`ob_name` FROM `case_list` WHERE `serial_id`='{$action_id}'")) die('資料庫發生錯誤');
		$rowCase = $db->fetch_object($rsCase);
		if($rowCase->whos) $userid = $rowCase->whos;
		if($rowCase->ob_name) $ob_name = $rowCase->ob_name;
	
		$sql="UPDATE `case_list` SET `service_bywho` = '{$edit_service}' WHERE `serial_id` = '{$action_id}'";
		$rs1=$db->Execute($sql);
		if ($rs1)
		{
			MailObjectTran($userid,$ob_name);
			AlertJump('指派完畢!並發出系統通知信...','index.php');
		}
		else die('資料庫發生錯誤');
	} else Fail("Enter Fail!");
?>