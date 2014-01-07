<?php
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');

	$db=new db_action;
    session_start();

	// 檢測權限是否有 action 的權限,並且設定權限細項在$_SESSION中
	check_authority('action');

	$serial_id=filter_xss($_POST['serial_id']);
	$authority=filter_xss($_POST['authority']);
	$account=filter_xss($_POST['account']);
	$orig_account=filter_xss($_POST['orig_account']);
	$password=md5(filter_xss($_POST['password']));
	$orig_password=filter_xss($_POST['orig_password']);
	$name=filter_xss($_POST['name']);
	$identity_id=filter_xss($_POST['identity_id']);
	$birthday=intval($_POST['year']).'-'.intval($_POST['month']).'-'.intval($_POST['day']);
	$telephone=filter_xss($_POST['telephone']);
	$cellphone=filter_xss($_POST['cellphone']);
	$email=filter_xss($_POST['email']);
	$fix_address=filter_xss($_POST['fix_address']);
	$com_address=filter_xss($_POST['com_address']);
	$pay_account=filter_xss($_POST['pay_account']);
	$take_office_day=filter_xss($_POST['take_office_day']);
	$Contact_time=filter_xss($_POST['Contact_time']);
	$PS=filter_xss($_POST['PS']);

	if ($orig_account != $account)
	{
		// 帳號變更,檢查帳號
		$sql = "select * from sys_members where account='{$account}'";
		$rs = $db->Execute($sql);
		if (!$rs) die('資料庫發生錯誤');
		if ($db->num_rows($rs)>0)
		{
			echo '<script type="text/javascript">alert("帳號重覆"); window.location.href="../main.php?path=account&which=add"</script>';
			exit;
		}
	}
	
	// 密碼變更
	if (!empty($_POST['password'])) $password = ", password='{$password}'";
	
	// 判定為服務商
	$rs = $db->Execute("SELECT `stid` FROM `sys_depart_mb` WHERE `serial_id`='{$authority}'");
	if (!$rs) die('資料庫發生錯誤');
	$row = $db->fetch_object($rs);
	if($row->stid==$member_serV)
	{
		require_once("../../lib/mail.php");
		MailWantbeServiceAccept($serial_id);
		$sql_lnk .= ", `serviceis` = '1'";
		$temp_msg = '，並寄出系統通知信完畢!';
	}
	else $sql_lnk .= ",  `serviceis` = NULL";

	$sql = "update sys_members set account='{$account}',unit='{$authority}',name='{$name}',
	identity_id='{$identity_id}',birthday='{$birthday}',telephone='{$telephone}',cellphone='{$cellphone}',
	email='{$email}',fix_address='{$fix_address}',com_address='{$com_address}',take_office_day='{$take_office_day}',
	Contact_time='{$Contact_time}',PS='{$PS}' {$sql_lnk} where serial_id='{$serial_id}'";

	if (!$db->Execute($sql)) die('資料庫發生錯誤');
	echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>
		<script type="text/javascript">alert("修改成功'.$temp_msg.'"); window.location.href="index.php"</script>
		</body></html>';
	exit;
		

?>