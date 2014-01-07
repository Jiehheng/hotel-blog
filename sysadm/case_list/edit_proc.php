<?php
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');

	$db=new db_action;
    session_start();

	//檢測權限是否有 action 的權限,並且設定權限細項在$_SESSION中
	check_authority('action');

	extract($_POST);
	foreach ($_POST as $k => $v){
		if(is_array($v))$k = $v; else $k = filter_xss($v);
	}
	$contact_time = serialize(array($contact_time,$contact_time_s,$contact_time_e));
	if (is_array($ob_District)) $ob_District=serialize($ob_District);
	if (is_array($ob_Categories)) $ob_Categories=serialize($ob_Categories);
	if (is_array($house_patterns)) $house_patterns=serialize($house_patterns);
	if (is_array($house_Blockto)) $house_Blockto=serialize($house_Blockto);
	if (is_array($lot_requirements)) $lot_requirements=serialize($lot_requirements);
	if (is_array($other_equirements)) $other_equirements=serialize($other_equirements);
	if (is_array($purpose)) $purpose=serialize($purpose);
	if (is_array($move_in_date)) $move_in_date=serialize($move_in_date);

	$sql="UPDATE `case_list` SET `ob_District` = '{$ob_District}', `ob_Categories` = '{$ob_Categories}', `presale` = '{$ob_presale}', `presale_name` = '{$ob_presale_name}', 
	`house_patterns` = '{$house_patterns}', `house_Blockto` = '{$house_Blockto}', `budget` = '{$budget}', `lot_requirements` = '{$lot_requirements}', 
	`other_equirements` = '{$other_equirements}', `purpose` = '{$purpose}', `building_age` = '{$building_age}', `move_in_date` = '{$move_in_date}', `building_evel_round` = '{$building_evel_round}', 
	`land_level_round` = '{$land_level_round}', `contacter` = '{$contacter}', `contacter_mphone` = '{$contacter_mphone}', `contacter_fphone` = '{$contacter_fphone}', 
	`contacter_email` = '{$contacter_email}', `contact_time` = '{$contact_time}', `ps` = '{$ps}', `status` = '{$status}', `service_bywho` = '{$service_bywho}' WHERE `serial_id` = '{$serial_id}';";
	
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