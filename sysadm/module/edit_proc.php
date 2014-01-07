<?php
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');
    include_once('../../lib/Xtemplate.class.php');
    $xtpl = new XTemplate('../template/index.htm');
	$db= new db_action;
	session_start();
		
	//檢測權限是否有 action 的權限,並且設定權限細項在$_SESSION中
	check_authority('action');	
	
	/// POST參數 start///
	if (empty($_POST['serial_id']))
		die();
	else $serial_id=filter_sql_injection($_POST['serial_id']);
	
	if (!empty($_POST['caption']))
		$caption=filter_xss($_POST['caption']);
	else die('請填入名稱');
	
	if (!empty($_POST['directory']))
		$directory=filter_xss($_POST['directory']);
	else die('請填入資料夾名稱');
	
	if (!empty($_POST['sort']))
	{
		if (!is_numeric($_POST['sort']))
			die('請填入數字');
		else
			$sort=intval($_POST['sort']);
	}
	else
	{
		$sort=0;
	}
	
	if (!empty($_POST['display']))
	{
		$display = filter_xss($_POST['display']);
	}
	
	$sql="update sys_module set caption='{$caption}',directory='{$directory}',sort='{$sort}',display='{$display}' where serial_id='{$serial_id}'";
	$rs=$db->Execute($sql);
	if ($rs)	
	{
		echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>
			<script type="text/javascript">alert("編輯成功"); window.location.href="index.php"</script>
			</body></html>';		
	}else
		die('編輯失敗');
	exit;
?>