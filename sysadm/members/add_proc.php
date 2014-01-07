<?php
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');

	$db=new db_action;
    session_start();

	//檢測權限是否有 action 的權限,並且設定權限細項在$_SESSION中
	check_authority('action');

	$unit=filter_xss($_POST['unit']);
	$account=filter_xss($_POST['account']);
	$password=md5(filter_xss($_POST['password']));
	$name=filter_xss($_POST['name']);
	$identity_id=filter_xss($_POST['identity_id']);
	$birthday=intval($_POST['year']).'-'.intval($_POST['month']).'-'.intval($_POST['day']);
	$telephone=filter_xss($_POST['telephone']);
	$cellphone=filter_xss($_POST['cellphone']);
	$email=filter_xss($_POST['email']);
	$fix_address=filter_xss($_POST['fix_address']);
	$com_address=filter_xss($_POST['com_address']);
	$take_office_day=filter_xss($_POST['take_office_day']);
	$Contact_time=filter_xss($_POST['Contact_time']);

	//檢查帳號
	$sql="select * from sys_account where account='{$account}'";
	$rs=$db->Execute($sql);
	if ($rs)
	{
		if ($db->num_rows($rs)>0)
		{
			echo '<script type="text/javascript">alert("帳號重覆"); window.location.href="../main.php?path=account&which=add"</script>';
			exit;
		}
		$serial_id=md5(uniqid(rand(),true));
		$sql="insert into sys_members
		(serial_id,account,password,unit,name,identity_id,birthday,telephone,cellphone,email,fix_address,com_address,take_office_day,Contact_time,create_time)
		values
		('{$serial_id}','{$account}','{$password}','{$unit}','{$name}','{$identity_id}','{$birthday}','{$telephone}','{$cellphone}','{$email}','{$fix_address}','{$com_address}','{$take_office_day}','{$Contact_time}',NOW())";
		$rs1=$db->Execute($sql);
		if ($rs1)
		{
			//特休
			/*$serial_id1=md5(uniqid(rand(),true));
			$sql="insert into special_leave_hour (serial_id,account,hour) values ('{$serial_id1}','{$serial_id}','0')";
			$rs2=$db->Execute($sql);
			if (!$rs2)
				die('資料庫發生錯誤');*/
			echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>
			<script type="text/javascript">alert("新增成功"); window.location.href="index.php"</script>
			</body></html>';
			exit;
		}else
			die('資料庫發生錯誤');
	}else
		die('資料庫發生錯誤');
?>