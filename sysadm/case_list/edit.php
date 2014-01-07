<?php
header("Cache-control: private");
if ($check != 'kingfor')
	exit;

	$serial_id=filter_xss($_POST['edit_which']);
	$sql="select * from case_list where serial_id='{$serial_id}'";
	$rs=$db->Execute($sql);
	if ($rs)
	{
		$row=$db->fetch_assoc($rs);
	}else
		die('資料庫發生錯誤');

/*	//門市SID轉中文
	$rsdid = $db->Execute("select serial_id,caption from sys_depart_mb");
	while ($rowsdid=mysql_fetch_assoc($rsdid))
		$depart_sid[$rowsdid['serial_id']]=$rowsdid['caption'];

	if (!empty($row['birthday']))
		$birthday=explode('-',$row['birthday']);
	//生日-西元
	for ($i=date('Y')-65;$i<date('Y')-18;$i++)
	{
		if (is_array($birthday) and $birthday[0]==$i)
			$year .= '<option value='.$i.' selected>'.$i.'</option>';
		else
			$year .= '<option value='.$i.'>'.$i.'</option>';
	}
	//生日-月份
	for ($i=1;$i<13;$i++)
	{
		if (is_array($birthday) and $birthday[1]==$i)
			$month .= '<option value="'.sprintf("%02d",$i).'" selected>'.sprintf("%02d",$i).'</option>';
		else
			$month .= '<option value="'.sprintf("%02d",$i).'">'.sprintf("%02d",$i).'</option>';
	}
	//生日-日
	for ($i=1;$i<32;$i++)
	{
		if (is_array($birthday) and $birthday[2]==$i)
			$day .= '<option value="'.sprintf("%02d",$i).'" selected>'.sprintf("%02d",$i).'</option>';
		else
			$day .= '<option value="'.sprintf("%02d",$i).'">'.sprintf("%02d",$i).'</option>';
	}
*/

	// 區域抽離
	$sql_dist=$db->Execute("SELECT `caption`,`stid` FROM `case_district`");
	while ($rowsDist=$db->fetch_assoc($sql_dist)) $GetDistCaption[$rowsDist['stid']]=$rowsDist['caption'];
	$ob_District_ar = unserialize($row['ob_District']);
	function chk_indr($x){
		global $ob_District_ar;
		if(is_array($ob_District_ar)) if(in_array($x,$ob_District_ar)) return 'checked="checked"';
	}
	$sql_dist=$db->Execute("SELECT `caption`,`stid` FROM `case_district` ORDER BY `sort`");
	$x=1;
	$row["ob_District"]='　<input type="checkbox" name="ob_District[]" id="ob_District[]" value="a" '.chk_indr("a").'/>不　拘';
	while ($rowsDist=$db->fetch_assoc($sql_dist)){
		if($x%10==0)$row["ob_District"].='<br/>　';
		$row["ob_District"].='<input type="checkbox" name="ob_District[]" id="ob_District[]" value="'.$rowsDist['stid'].'" '.chk_indr($rowsDist['stid']).'/>'.$rowsDist['caption'].'&nbsp;';
		$x++;
	}

	// 物件類別
	$ob_Categories_ar = unserialize($row['ob_Categories']);
	function chk_inoc($x){
		global $ob_Categories_ar;
		if(is_array($ob_Categories_ar)) if(in_array($x,$ob_Categories_ar)) return 'checked="checked"';
	}
	$row["ob_Categories"] = '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:9.5pt;">
	  <tr>
		<td valign="top" width="25%" style="border:1px dashed #CCCCCC"><strong>住家類 :</strong><br />
		 　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="10" '.chk_inoc("10").'/>
		大樓豪宅<br />
		　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="11" '.chk_inoc("11").'/>
		透天豪宅<br />
		　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="12" '.chk_inoc("12").'/>
		電梯大樓華廈<br />
		　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="13" '.chk_inoc("13").'/>
		公寓住家<br />
		　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="14" '.chk_inoc("14").'/>
		透天住家<br />
		　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="15" '.chk_inoc("15").'/>
		別墅<br />
		　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="16" '.chk_inoc("16").'/>
		預售屋<br />
		　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="17" '.chk_inoc("17").'/>
		樓中樓<br />
		　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="18" '.chk_inoc("18").'/>
		套房<br />
		　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="19" '.chk_inoc("19").'/>
		雅房<br />
		　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="1a" '.chk_inoc("1a").'/>
		平房<br />
		　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="1b" '.chk_inoc("1b").'/>
		附租約物件<br />
		　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="1e" '.chk_inoc("1c").'/>
		其他</td>
		<td valign="top" width="25%" style="border:1px dashed #CCCCCC"><p><strong>商用店面類:</strong><br />
		  
		  　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="20" '.chk_inoc("20").'/>
		  店面住辦<br />
		  　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="21" '.chk_inoc("21").'/>
		  大樓店面&nbsp;
		  <br />
		  　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="22" '.chk_inoc("22").'/>
		  透天店面　　&nbsp;
		  <br />
		  　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="23" '.chk_inoc("23").'/>
		  三角窗店面&nbsp;
		  <br />
		  　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="24" '.chk_inoc("24").'/>
		  廠房　　　&nbsp;
		  <br />
		  　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="25" '.chk_inoc("25").'/>
		  倉庫　　&nbsp;
		  <br />
		  　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="26" '.chk_inoc("26").'/>
		  附租約物件&nbsp;
		  <br />
		　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="27" '.chk_inoc("27").'/>
		整棟大樓<br />
		　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="28" '.chk_inoc("28").'/>
		飯店<br />
		  　<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="29" '.chk_inoc("29").'/>
		  其他&nbsp;</p>
		</td>
		<td valign="top" style="border:1px dashed #CCCCCC"><strong>土地類: </strong><br />　
		  <input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="30" />
			商業區建地&nbsp;
			<br />
			　
			<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="31" '.chk_inoc("31").'/>
			住宅區建地&nbsp;
			<br />
			　
			<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="32" '.chk_inoc("32").'/>
			農地&nbsp;
			<br />
			　
			 <input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="33" '.chk_inoc("33").'/>
			道路用地公共設施保留地&nbsp;
			<br />
			　
			<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="34" '.chk_inoc("34").'/>
			甲種工業用地&nbsp;
			<br />
			　
			<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="35" '.chk_inoc("35").'/>
			乙種工業用地&nbsp;
			<br />
			　
			<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="36" '.chk_inoc("36").'/>
			農舍&nbsp;<br /> 　
			<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="37" '.chk_inoc("37").'/>
			附租約物件&nbsp;
			<br />
			　
			<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="38" '.chk_inoc("38").'/>
		其他</td>
			<td valign="top" width="25%" style="border:1px dashed #CCCCCC"><strong>商用辦公類: </strong><br />
			　
			  <input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="40" '.chk_inoc("40").'/>
			住辦&nbsp;<br />　
			<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="41" '.chk_inoc("41").'/>
			純辦&nbsp;<br />　
			<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="42" '.chk_inoc("42").'/>
			店面住辦&nbsp;<br />　
			<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="43" '.chk_inoc("43").'/>
			大樓店面&nbsp;<br />　
			<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="44" '.chk_inoc("44").'/>
			附租約物件&nbsp;<br />
			　
			<input type="checkbox" id="ob_Categories[]" name="ob_Categories[]" value="45" '.chk_inoc("45").'/>
		其他</td>
	  </tr>
	</table>';
	
	// 預售屋求購
	$ob_presale_V = $row['presale'];
	function chk_inps($x){
		global $ob_presale_V;
		if($ob_presale_V == $x) return 'checked="checked"';
	}
	$row["presale"] = '<input type="radio" name="ob_presale" id="ob_presale" value="a" '.chk_inps("a").'/> 不拘
								  <input type="radio" name="ob_presale" id="ob_presale" value="1" '.chk_inps("1").'/> 案場名稱 &nbsp; <input type="text" name="ob_presale_name" id="ob_presale_name" value="'.$row["presale_name"].'" />';
	// 房屋格局
	$house_patterns_V = $row['house_patterns'];
	function chk_inhp($x){
		global $house_patterns_V;
		if($house_patterns_V == $x) return 'selected="selected"';
	}
	$row["house_patterns"] = '<select name="house_patterns" id="house_patterns">
									<option value="a" '.chk_inhp("a").'>不拘</option>
									<option value="1" '.chk_inhp("1").'>一房一廳</option>
									<option value="2" '.chk_inhp("2").'>一房兩廳</option>
									<option value="3" '.chk_inhp("3").'>兩房兩廳</option>
									<option value="4" '.chk_inhp("4").'>三房兩廳</option>
									<option value="5" '.chk_inhp("5").'>四房兩廳</option>
									<option value="6" '.chk_inhp("6").'>五房</option>
									<option value="7" '.chk_inhp("7").'>六房</option>
								  </select>';

	// 座向
	$house_Blockto_V = $row['house_Blockto'];
	function chk_inhb($x){
		global $house_Blockto_V;
		if($house_Blockto_V == $x) return ' selected="selected"';
	}
	$row["house_Blockto"] = '<select name="house_Blockto" id="house_Blockto">
								  <option value="a"'.chk_inhb("a").'>不拘</option>
								  <option value="1"'.chk_inhb("1").'>坐南朝北</option>
								  <option value="2"'.chk_inhb("2").'>坐西朝東</option>
								  <option value="3"'.chk_inhb("3").'>坐北朝南</option>
								  <option value="4"'.chk_inhb("4").'>坐東朝西</option>
								  </select>';

	// 預算
	$Budget_V = $row['budget'];
	function chk_inbg($x){
		global $Budget_V;
		if($Budget_V == $x) return ' selected="selected"';
	}
	$row["budget"] = '<select name="budget" id="budget">
									<option value="a"'.chk_inbg("a").'>不拘</option>
									<option value="1"'.chk_inbg("1").'>299以下萬</option>
									<option value="2"'.chk_inbg("2").'>300-600萬</option>
									<option value="3"'.chk_inbg("3").'>600-900萬</option>
									<option value="4"'.chk_inbg("4").'>900-1200萬</option>
									<option value="5"'.chk_inbg("5").'>1200-2000萬</option>
									<option value="6"'.chk_inbg("6").'>2000萬以上</option>
									<option value="7"'.chk_inbg("7").'>其他</option>
									</select>';

	// 地段要求
	$lot_requirements_ar = unserialize($row['lot_requirements']);
	function chk_inlr($x){
		global $lot_requirements_ar;
		if(is_array($lot_requirements_ar)) if(in_array($x,$lot_requirements_ar)) return 'checked="checked"';
	}
	$row["lot_requirements"] = '<input type="checkbox" id="lot_requirements[]" name="lot_requirements[]" value="1" '.chk_inlr("a").'/>不拘　
									<input type="checkbox" id="lot_requirements[]" name="lot_requirements[]" value="1" '.chk_inlr("1").'/>便利店　
									<input type="checkbox" id="lot_requirements[]" name="lot_requirements[]" value="2" '.chk_inlr("2").'/>近國小　
									<input type="checkbox" id="lot_requirements[]" name="lot_requirements[]" value="3" '.chk_inlr("3").'/>近國中　
									<input type="checkbox" id="lot_requirements[]" name="lot_requirements[]" value="4" '.chk_inlr("4").'/>近公園　
									<input type="checkbox" id="lot_requirements[]" name="lot_requirements[]" value="5" '.chk_inlr("5").'/>近夜市　
									<input type="checkbox" id="lot_requirements[]" name="lot_requirements[]" value="6" '.chk_inlr("6").'/>近捷運　
									<input type="checkbox" id="lot_requirements[]" name="lot_requirements[]" value="7" '.chk_inlr("7").'/>近公車　
									<input type="checkbox" id="lot_requirements[]" name="lot_requirements[]" value="8" '.chk_inlr("8").'/>有景觀 
									<input type="checkbox" id="lot_requirements[]" name="lot_requirements[]" value="9" '.chk_inlr("9").'/>近百貨大型賣場　
									<input type="checkbox" id="lot_requirements[]" name="lot_requirements[]" value="10" '.chk_inlr("10").'/>傳統市場　
									<input type="checkbox" id="lot_requirements[]" name="lot_requirements[]" value="11" '.chk_inlr("11").'/>近醫療機構　　
									<input type="checkbox" id="lot_requirements[]" name="lot_requirements[]" value="12" '.chk_inlr("12").'/>其他　';

	// 其他地段要求
	$other_equirements_ar = unserialize($row['other_equirements']);
	function chk_inor($x){
		global $other_equirements_ar;
		if(is_array($other_equirements_ar)) if(in_array($x,$other_equirements_ar)) return 'checked="checked"';
	}
	$row["other_equirements"] = '<input type="checkbox" id="other_equirements[]" name="other_equirements[]" value="1" '.chk_inor("1").'/>露臺　
									<input type="checkbox" id="other_equirements[]" name="other_equirements[]" value="2" '.chk_inor("2").'/>單車位　
									<input type="checkbox" id="other_equirements[]" name="other_equirements[]" value="3" '.chk_inor("3").'/>雙車位　
									<input type="checkbox" id="other_equirements[]" name="other_equirements[]" value="4" '.chk_inor("4").'/>高樓層　
									<input type="checkbox" id="other_equirements[]" name="other_equirements[]" value="5" '.chk_inor("5").'/>毛坯屋　
									<input type="checkbox" id="other_equirements[]" name="other_equirements[]" value="6" '.chk_inor("6").'/>精裝修　
									<input type="checkbox" id="other_equirements[]" name="other_equirements[]" value="7" '.chk_inor("7").'/>一般裝修　
									<input type="checkbox" id="other_equirements[]" name="other_equirements[]" value="8" '.chk_inor("8").'/>附家電　
									<input type="checkbox" id="other_equirements[]" name="other_equirements[]" value="9" '.chk_inor("9").'/>公寓頂樓加蓋　
									<input type="checkbox" id="other_equirements[]" name="other_equirements[]" value="10" '.chk_inor("10").'/>高額貸款　
									<input type="checkbox" id="other_equirements[]" name="other_equirements[]" value="11" '.chk_inor("11").'/>其他';
	
	// 其他地段要求
	$purpose_ar = unserialize($row['purpose']);
	function chk_inpp($x){
		global $purpose_ar;
		if(is_array($purpose_ar)) if(in_array($x,$purpose_ar)) return 'checked="checked"';
	}
	$row["purpose"] = '<input type="checkbox" id="purpose[]" name="purpose[]" value="1" '.chk_inpp("1").'/>投資　
									<input type="checkbox" id="purpose[]" name="purpose[]" value="2" '.chk_inpp("2").'/>首購　
									<input type="checkbox" id="purpose[]" name="purpose[]" value="3" '.chk_inpp("3").'/>自住　
									<input type="checkbox" id="purpose[]" name="purpose[]" value="4" '.chk_inpp("4").'/>贈與　
									<input type="checkbox" id="purpose[]" name="purpose[]" value="5" '.chk_inpp("5").'/>自營 
									<input type="checkbox" id="purpose[]" name="purpose[]" value="a" '.chk_inpp("a").'/>其他　';

	// 屋齡
	$building_age_V = $row['building_age'];
	function chk_inba($x){
		global $building_age_V;
		if($building_age_V == $x) return ' selected="selected"';
	}
	$row["building_age"] = '<select name="building_age" id="building_age">
									<option value="a"'.chk_inba("a").'>不拘</option>
									<option value="1"'.chk_inba("1").'>1-5年</option>
									<option value="2"'.chk_inba("2").'>5-10年</option>
									<option value="3"'.chk_inba("3").'>10-20年</option>
									<option value="4"'.chk_inba("4").'>20-30年</option>
									<option value="5"'.chk_inba("5").'>30-40年</option>
									<option value="6"'.chk_inba("6").'>40年以上</option>
									</select>';

	// 入住時間
	$move_in_date_V = $row['move_in_date'];
	function chk_inmi($x){
		global $move_in_date_V;
		if($move_in_date_V == $x) return ' selected="selected"';
	}
	$row["move_in_date"] = '<select name="move_in_date" id="move_in_date">
									<option value="a"'.chk_inmi("a").'>不急</option>
									<option value="1"'.chk_inmi("1").'>3個月內</option>
									<option value="2"'.chk_inmi("2").'>6個月內</option>
									</select>';

	// 建地坪數
	$building_evel_round_V = $row['building_evel_round'];
	function chk_inbe($x){
		global $building_evel_round_V;
		if($building_evel_round_V == $x) return 'checked';
	}
	$row["building_evel_round"] = '<input name="building_evel_round" type="radio" value="a" '.chk_inbe("a").'/>不拘
								  <input name="building_evel_round" type="radio" value="1" '.chk_inbe("1").'/>20坪以下　
								  <input name="building_evel_round" type="radio" value="2" '.chk_inbe("2").'/>20-30坪　
								  <input name="building_evel_round" type="radio" value="3" '.chk_inbe("3").'/>30-40坪　
								  <input name="building_evel_round" type="radio" value="4" '.chk_inbe("4").'/>40-60坪　
								  <input name="building_evel_round" type="radio" value="5" '.chk_inbe("5").'/>60-100坪　
								  <input name="building_evel_round" type="radio" value="6" '.chk_inbe("6").'/>100坪以上　';
								  ;
	
	// 土地坪數
	$land_level_round_V = $row['land_level_round'];
	function chk_inll($x){
		global $land_level_round_V;
		if($land_level_round_V == $x) return 'checked';
	}
	$row["land_level_round"] = '<input name="land_level_round" type="radio" value="a" '.chk_inll("a").'/>不拘
								  <input name="land_level_round" type="radio" value="1" '.chk_inll("1").'/>20坪以下　
								  <input name="land_level_round" type="radio" value="2" '.chk_inll("2").'/>20-30坪　
								  <input name="land_level_round" type="radio" value="3" '.chk_inll("3").'/>30-40坪　
								  <input name="land_level_round" type="radio" value="4" '.chk_inll("4").'/>40-60坪　
								  <input name="land_level_round" type="radio" value="5" '.chk_inll("5").'/>60-100坪　
								  <input name="land_level_round" type="radio" value="6" '.chk_inll("6").'/>100坪以上　';

	// 聯絡時間
	$contact_time_ar = unserialize($row['contact_time']);
	function chk_inct($x){
		global $contact_time_ar;
		if($contact_time_ar[0] == $x) return 'checked="checked"';
	}
	$row["contact_time"] = '<input type="radio" name="contact_time" id="contact_time" value="1" '.chk_inct("1").'/>
									<input name="contact_time_s" type="text" id="contact_time_s" value="'.$contact_time_ar[1].'" size="2" />&nbsp;點&nbsp;~&nbsp;
									<input name="contact_time_e" type="text" id="contact_time_e" value="'.$contact_time_ar[2].'" size="2" />&nbsp;點
									<br />
									<input type="radio" name="contact_time" id="contact_time" value="a" '.chk_inct("a").'/>&nbsp;隨時';

	// 配對人員列表
	$service_bywho_V = $row['service_bywho'];
	function chk_insb($x){
		global $service_bywho_V;
		if($service_bywho_V == $x) return ' selected="selected"';
	}
	$sql_dist=$db->Execute("SELECT `serial_id`,`name` FROM `sys_members` WHERE `serviceis` = '1' ORDER BY `create_time`");
	while ($rowsMbs=$db->fetch_assoc($sql_dist))
	{
		$row["service_bywho"] .= '<option value="'.$rowsMbs["serial_id"].'"'.chk_insb($rowsMbs["serial_id"]).'>'.$rowsMbs["name"].'</option>';
	}
	$row["service_bywho"] = '<select name="service_bywho" id="service_bywho"><option value="0">未指派</option>'.$row["service_bywho"].'</select>';
	
// ---------------------------------------------------------------------------------------------------------
$xtpl->assign('job_list', $job_list);
$xtpl->assign('extra_job', $extra_job);
$xtpl->assign('year', $year);
$xtpl->assign('month', $month);
$xtpl->assign('DATA', $row);
$xtpl->assign('name',$_SESSION['session_name']);
$xtpl->parse('table');
$xtpl->parse('main.table');

?>