<?php
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');

	$db= new db_action;
	session_start();
		
	//檢測權限是否有 action 的權限,並且設定權限細項在$_SESSION中
	check_authority('action');	
	
	if (empty($_POST['delete_which']))
		die();
	$delete_which=filter_sql_injection($_POST['delete_which']);
	$sql="delete from sys_module where serial_id='{$delete_which}'";	
	$rs=$db->Execute($sql);
	if ($rs)
	{
		echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>
			<script type="text/javascript">alert("刪除成功"); window.location.href="index.php"</script>
			</body></html>';
	}else
		die('資料庫發生錯誤');
	exit;
?>