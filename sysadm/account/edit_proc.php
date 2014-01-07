<?php
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');

	$db=new db_action;
    session_start();

	//檢測權限是否有 action 的權限,並且設定權限細項在$_SESSION中
	check_authority('action');

	$serial_id=filter_xss($_POST['serial_id']);
	$unit=filter_xss($_POST['unit']);
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
	foreach($_POST['job'] as $jobs)
		$job_array[]=filter_xss($jobs);
	$job=serialize($job_array);
	$PS=filter_xss($_POST['PS']);

	if ($orig_account != $account)
	{
		//帳號變更,檢查帳號
		$sql="select * from sys_account where account='{$account}'";
		$rs=$db->Execute($sql);
		if ($rs)
		{
			if ($db->num_rows($rs)>0)
			{
				echo '<script type="text/javascript">alert("帳號重覆"); window.location.href="../main.php?path=account&which=add"</script>';
				exit;
			}
		}
	}
	if (!empty($_POST['password']))
	{
		//密碼變更
		$password=" ,password='{$password}' ";
	}else
		$password='';

	$sql="update sys_account set account='{$account}',unit='{$unit}',authority='{$authority}',
	name='{$name}',identity_id='{$identity_id}',birthday='{$birthday}',telephone='{$telephone}',
	cellphone='{$cellphone}',email='{$email}',fix_address='{$fix_address}',com_address='{$com_address}',
	take_office_day='{$take_office_day}',Contact_time='{$Contact_time}',job='{$job}',PS='{$PS}' {$password} where serial_id='{$serial_id}'";
	$rs1=$db->Execute($sql);
	if ($rs1)
	{
		/*
		// 從未到職過
		$rs2 = $db->Execute("SELECT `year` FROM `account_leave` WHERE `account_sid` = '{$serial_id}'");
		if(!mysql_num_rows($rs2))
		{
			$cnt=1;
			$FirstYear = explode ("-", $take_office_day);
			$basic = date("z", mktime (0,0,0,$FirstYear[1],$FirstYear[2],$FirstYear[0]));
			// echo $basic.'<br>';
			for($y=$FirstYear[0];$y<=date("Y");$y++)
			{
				if($cnt==1) $NowYeartimes = 56 - round($basic*56/365);	// 第一年剩餘時數換算
				elseif($cnt<=3) $NowYeartimes = 7*8;
				elseif($cnt==4) $NowYeartimes = round($basic*56/365)+(80-round($basic*80/365));
				elseif($cnt==5) $NowYeartimes = 10*8;
				elseif($cnt==6) $NowYeartimes = round($basic*80/365)+(112-round($basic*112/365));
				elseif($cnt<=10) $NowYeartimes = 14*8;
				elseif($cnt<=26) $NowYeartimes = round($basic*(($cnt+3)*8)/365)+((($cnt+4)*8)-round($basic*(($cnt+4)*8)/365));
				else $NowYeartimes = 240;
				// echo '第'.$cnt.'年:'.$NowYeartimes.'<br>';
				$rs3 = $db->Execute("SELECT `year` FROM `account_leave` WHERE `account_sid` = '{$serial_id}' AND `year` = '{$y}'");
				if(!mysql_num_rows($rs3)) $db->Execute("INSERT INTO `account_leave` (`account_sid`, `year`, `default`) VALUES ('{$serial_id}', '{$y}', '{$NowYeartimes}')");
				unset($rs3);
				$cnt++;
			}
		}*/
		echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>
			<script type="text/javascript">alert("修改成功"); window.location.href="index.php"</script>
			</body></html>';
		exit;
	}else
		die('資料庫發生錯誤');

?>