<?php
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');

	$db= new db_action;
	session_start();

	//檢測權限是否有 action 的權限,並且設定權限細項在$_SESSION中
	// check_authority('action');

	/// POST參數 start///
	$serial_id=filter_xss($_POST['serial_id']);

	if (!empty($_POST['caption']))
		$caption=filter_xss($_POST['caption']);
	else
		die('請填入群組名稱');
	foreach($_POST as $column => $value)
		$post_array[]=$column; //post欄位

	$sql="select * from sys_module order by sort asc";
	$rs=$db->Execute($sql);
	if ($rs)
	{
		while ($rows=$db->fetch_assoc($rs))
		{
			if (in_array($rows['serial_id'].'_list',$post_array))
				$authority_list[$rows['serial_id']][]='list';
			if (in_array($rows['serial_id'].'_action',$post_array))
				$authority_list[$rows['serial_id']][]='action';
			if (in_array($rows['serial_id'].'_manage',$post_array))
				$authority_list[$rows['serial_id']][]='manage';
		}
	}else{
		die('資料庫發生錯誤');
	}

	$authority=serialize($authority_list);
	if (!empty($_POST['sort']))
	{
		if (!is_numeric($_POST['sort']))
			die('請填入數字');
		else
			$sort=intval($_POST['sort']);
	}else{
		$sort=0;
	}
	/// POST參數 end///


	$sql="update sys_authority set caption='{$caption}',authority='{$authority}',sort='{$sort}' where serial_id='{$serial_id}'";
	$rs=$db->Execute($sql);
	if ($rs)
	{
		echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>
			<script type="text/javascript">alert("修改成功"); window.location.href="index.php"</script>
			</body></html>';
		exit;
	}else
		die('新增失敗');

?>