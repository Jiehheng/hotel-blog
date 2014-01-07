<?php
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');
    include_once('../../lib/Xtemplate.class.php');

    $db=new db_action;
    session_start();

	//檢測權限是否有 action 的權限,並且設定權限細項在$_SESSION中
	check_authority('action');		
	
	if (empty($_POST['caption'])) die();
	$caption=filter_xss($_POST['caption']);
	$stid=filter_xss($_POST['stid']);
	$sort=intval($_POST['sort']);
	
	$serial_id=md5(uniqid(rand(),true));
	$sql="insert into `sys_depart_mb` (serial_id,caption,stid,sort) values ('{$serial_id}','{$caption}','{$stid}','{$sort}') ";
	$rs=$db->Execute($sql);
	if ($rs)
	{
		echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>
			<script type="text/javascript">alert("新增成功"); window.location.href="index.php?action=action&which=depart_list"</script>
			</body></html>';
		exit;
	}else
		die('資料庫發生錯誤');
?>	