<?php
require_once('../config/db_action.php');
require_once('../lib/function.php');
$db = new db_action;

// 登入前不應該有SESSION
session_start();
unset($SESSION);

$post_account=filter_xss($_POST['account']);
$post_password=md5($_POST['password']);

// 帳密驗証
$sql=" select * FROM sys_account where account = '{$post_account}' ";
$rs=$db->Execute($sql);
if ($rs)
{
	$row = $db->fetch_assoc($rs);
	// 驗証密碼
	if ( $row['password'] != $post_password )
	{
		echo html_start();
		echo '
			<script>
				alert("帳號或密碼錯誤!.");
				window.location.href="index.php";
			</script>';
		echo html_end();
		exit;
	}
	else
	{
		$_SESSION['session_serial_id'] = $row['serial_id'];
		$_SESSION['session_account'] = $post_account;
		$_SESSION['session_name'] = $row['name'];
		
		// 更新登入時間
		$sql = "update sys_account set login_time=NOW() where serial_id='{$row['serial_id']}'";
		$rs = $db->Execute($sql);
		
		// 導入主畫面
		header("Location: main.php");
		exit;
	}
}
else die('資料庫存取發生異常');
?>