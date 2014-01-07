<?php
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');

    $db=new db_action;
    session_start();

	//檢測權限是否有 action 的權限,並且設定權限細項在$_SESSION中
	check_authority('action');			
	
	$del_which=filter_xss($_POST['del_which']);
	$del_whichs=explode(',',$del_which);

	for ($i=0;$i<(count($del_whichs));$i++)
	{
		$sql="delete from case_district where serial_id='{$del_whichs[$i]}'";
		$rs=$db->Execute($sql);
		if (!$rs)
			die('資料庫存取發生異常');
	}
	echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>
			<script type="text/javascript">alert("刪除成功"); window.location.href="index.php?action=action&which=district_list"</script>
			</body></html>';
	exit;
?>	