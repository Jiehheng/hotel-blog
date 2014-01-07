<?php
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');

	session_start();
	if (!in_array('action',$_SESSION['session_authority_list'])){ html_start(); Fail('認證錯誤!');}
	$db = new db_action;
	$rs=$db->Execute("DELETE FROM service_record WHERE ser_id = '".$_POST['ser_id']."'");
	if($rs) echo html_start().'<script type="text/javascript" charset="utf-8" language="javascript">alert("刪除成功!");window.location="./";</script>'.html_end();	
	else die('資料庫發生錯誤');
?>