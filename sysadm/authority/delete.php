<?php
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');

    $db=new db_action;
    session_start();
		
	//檢測權限是否有 action 的權限,並且設定權限細項在$_SESSION中
	check_authority('action');	
	
	$delete_which=filter_xss($_POST['delete_which']);
	$del_whichs=explode(',',$delete_which);

	for ($i=0;$i<(count($del_whichs));$i++)
	{
		$sql="delete from sys_authority where serial_id='{$del_whichs[$i]}'";
		$rs=$db->Execute($sql);
		if (!$rs)
			die('資料庫連接異常');
	}
	echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>
			<script type="text/javascript">alert("刪除成功"); window.location.href="index.php"</script>
			</body></html>';		
	exit;
?>	