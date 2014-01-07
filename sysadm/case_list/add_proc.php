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
	$ob_name = date("ymd").strtoupper(dechex(date("His").rand(10,99)));
	
	$serial_id=md5(uniqid(rand(),true));
	$sql="INSERT INTO `case_list` (`serial_id`, `ob_name`, `ob_District`, `ob_Categories`, `presale`, `house_patterns`, `house_Blockto`, `budget`, `lot_requirements`, `other_equirements`, `purpose`, `building_age`, `move_in_date`, `building_evel_round`, `land_level_round`, `contacter`, `contacter_mphone`, `contacter_fphone`, `contacter_email`, `contact_time`, `ps`, `status`, `service_staff`, `creatime`) 
	VALUES ('{$serial_id}', '{$ob_name}', '{$ob_District}', '{$ob_Categories}', '{$presale}', '{$house_patterns}', '{$house_Blockto}', '{$budget}', '{$lot_requirements}', '{$other_equirements}', '{$purpose}', '{$building_age}', '{$move_in_date}', '{$building_evel_round}', '{$land_level_round}', '{$contacter}', '{$contacter_mphone}', '{$contacter_fphone}', '{$contacter_email}', '{$contact_time}', '{$ps}', '{$status}', '{$service_staff}', NOW());";

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
?>