<?php
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');
    include_once('../../lib/Xtemplate.class.php');

    $db=new db_action;
    session_start();

	//檢測權限是否有 action 的權限,並且設定權限細項在$_SESSION中
	check_authority('action');			
	
	if (empty($_POST['caption']) or empty($_POST['serial_id']))
		die();
	$caption=filter_xss($_POST['caption']);
	$stid=filter_xss($_POST['stid']);
	$sort=intval($_POST['sort']);
	$serial_id=filter_sql_injection($_POST['serial_id']);
	
	$sql="update case_district set caption='{$caption}',stid='{$stid}',sort='{$sort}' where serial_id='{$serial_id}'";
	$rs=$db->Execute($sql);
	if ($db->num_rows($rs)>=0)
	{
		echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>
			<script type="text/javascript">alert("更新成功"); window.location.href="index.php?action=action&which=district_list"</script>
			</body></html>';
		exit;
	}else
		die('資料庫發生錯誤');
?>	