<?php
include 'config/db_config.php';
include 'config/db_action.php';
include 'lib/function.php';
echo'<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

if(isset($_POST["act"]))	$cAct = $_POST["act"];
elseif(isset($_GET["act"]))	$cAct = $_GET["act"];
else Fail("[ System corresponding ]");

//foreach ($_POST as $k => $v) echo "$k=$v<br />";exit;
// 記錄前一頁
$ComeFromPage = $_SERVER["HTTP_REFERER"];

session_start();
$db = new db_action;
// －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－
// 飯店註冊
if($cAct=='hotel_join')
{
	extract($_POST);
	//require_once("./lib/mail.php");
	$serial_id = md5(uniqid(rand(),true));
	if(!$db->Execute("INSERT INTO `sys_customer_temp` (`serial_id`, `city`, `name`, `addr`, `tele`, `Contacter`, `email`, `License`, `Subject`, `Content`, `creatime`) VALUES 
	('{$serial_id}', '{$City}', '{$Name}', '{$Addr}', '{$Tele}', '{$Contacter}', '{$Email}', '{$License}', '{$Subject}', '{$Content}', NOW())")) Fail("新增失敗! 請重新檢查或聯繫管理員");
	//MailWantbeMember($serial_id);
	AlertJump('飯店登記完成,將有專人與您聯繫!',$ComeFromPage);
	exit;
}

// －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－
// 登出
if($cAct=='logout')	// 判斷帳號密碼登出
{
	extract($_GET);
	session_unset();
	session_destroy();
	if($fblogouturl) $R_rul = urldecode($fblogouturl); else $R_rul = $ComeFromPage;
	echo '<meta http-equiv="refresh" content="0;url='. $R_rul.'" />';
	/*echo'<html><head></head><script language="JavaScript" type="text/JavaScript">location.replace("'.$R_rul.'");</script></head></html>';*/
	exit;
}

// 登入
if($cAct=='login')	// 判斷帳號密碼登入
{
	$post_account = filter_xss($_POST['account']);
	$post_password = md5($_POST['password']);
	
	// 帳密驗証
	$sql = "SELECT * FROM `sys_members` where `account` = '{$post_account}' ";
	$rs = $db->Execute($sql);
	if ($rs)
	{
		$row = $db->fetch_assoc($rs);
		// 驗証密碼
		if ( $row['password'] != $post_password )
		{
			echo html_start();
			echo '<script>alert("帳號或密碼錯誤!.");window.location.href="'.$ComeFromPage.'";</script>';
			echo html_end();
			exit;
		}
		else
		{	// 已有FB綁定 以FB登入為主.
			$_SESSION['login_id'] = $row['serial_id'];
			$_SESSION['name'] = $row['name'];
			if($row['fbid'])
			{
				$tmpfbloginurl = $_SESSION["fbloginurl"];
				echo html_start();
				echo '<script>window.location.href="'.$tmpfbloginurl.'";</script>';
				echo html_end();
				exit;
			}
			
			// 更新登入時間
			$sql = "update `sys_members` set `login_time` = NOW() WHERE `serial_id`='{$row['serial_id']}'";
			$rs = $db->Execute($sql);
			
			// 導入主畫面
			if(substr_count($ComeFromPage,'userlogin')) $ComeFromPage ='?index';
			AlertJump('登入成功!',$ComeFromPage);
			exit;
		}
	}
}

// 註冊
if($cAct=="regist")
{
	extract($_POST);
	$rs=$db->Execute("select * from `sys_members` where `account`='{$login_id}'");
	if ($rs)
	{
		//require_once("./lib/mail.php");
		if ($db->num_rows($rs)>0) Fail("帳號重覆");
		$serial_id = md5(uniqid(rand(),true));
		$pwd_in = md5($pwd);
		$birthday = $bir_y.'-'.$bir_m.'-'.$bir_d;
		if(!$db->Execute("INSERT INTO `wowbook_hotel`.`sys_members` (`serial_id`, `account`, `password`, `name`, `sex`, `birthday`, 
		`telephone`, `cellphone`, `email`, `com_address`, `create_time`) VALUES ('{$serial_id}', '{$login_id}', '{$pwd_in}', 
		'{$name}', '{$sex}', '{$birthday}', '{$tel}', '{$mobile}', '{$email}', '{$address}', NOW())")) Fail("新增失敗! 請重新檢查或聯繫管理員");
		$_SESSION["login_id"] = $serial_id;
		//MailWantbeMember($serial_id);
		AlertJump('註冊成功!',$ComeFromPage);
		exit;
	}
}

// 會員資料自行修改
if($cAct=="member_edit")
{
	extract($_POST);
	foreach ($_POST as $k => $v)
	{
		if(is_array($v))$v = $v; else $v = filter_xss($v);
	}
	$user_id = $_SESSION["login_id"];
	$sql = "SELECT * FROM `sys_members` WHERE `serial_id` = '".$user_id."'";
	if(!$rs = $db->Execute($sql)) Fail('資料庫連線錯誤!');
	if($db->num_rows($rs))	// 需先判斷是否為求購者本人
	{
		$birthday = $bir_y.'-'.$bir_m.'-'.$bir_d;
		$sql="UPDATE `sys_members` SET `name` = '{$name}', `sex` = '{$sex}', `birthday` = '{$birthday}', `telephone` = '{$tel}', `cellphone` = '{$mobile}', 
		`email` = '{$email}', `com_address` = '{$address}' WHERE `serial_id` = '".$user_id."';";
	
		$rs1=$db->Execute($sql);
		if ($rs1)AlertJump('修改成功!',$ComeFromPage); else die('資料庫發生錯誤');
	
	}
	else Fail('無法判斷所屬資料為本人');
}
if($cAct=="member_password")
{
	extract($_POST);
	$user_id = $_SESSION["login_id"];
	
	$sql = "SELECT `password` FROM `sys_members` WHERE `serial_id` = '".$user_id."'";
	if(!$rs = $db->Execute($sql)) Fail('資料庫連線錯誤!');
	if($db->num_rows($rs))	// 需先判斷是否為求購者本人
	{
		$rs_User = $db->fetch_array($rs);
		if($rs_User["password"]!=md5($pwd_old)) Fail('舊密碼不符,請重新確認.');
		elseif($pwd!=$pwd_chk) Fail('新密碼與確認密碼不符,請重新確認.');
		else
		{
			$n_pwd = md5($pwd);
			$rs1=$db->Execute("UPDATE `sys_members` SET `password` = '{$n_pwd}' WHERE `serial_id` = '".$user_id."';");
			if ($rs1)AlertJump('修改成功!',$ComeFromPage); else die('資料庫發生錯誤');
		}
	
	}
	else Fail('無法判斷所屬資料為本人');
}

// －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－
// 飯店端登入
if($cAct=='assign')
{
	extract($_POST);
	// 前端送出 $txtLogin, $txtPassword, $storelogin
	$txtAccount = filter_sql_injection($txtLogin);
	$txtPasswrd = md5(filter_sql_injection($txtPassword));
	$rs = $db->Execute("SELECT * FROM `sys_customer` WHERE `account`='".$txtAccount."'");
	
	// 檢驗帳號密碼
	if($db->num_rows($rs))
	{
		$rs_array = $db->fetch_array($rs);
		if($txtPasswrd<>$rs_array["password"]) $WrongUser = true;
	}
	else $WrongUser = true;
	if($WrongUser)
	{
		session_destroy();
		Fail("[ 帳號或密碼認證錯誤 ]");
	}
	$_SESSION["cust_sid"] = $rs_array["serial_id"];
	AlertJump('','./?'.$rs_array["cust_id"]);
	exit;
}

// －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－
// 飯店登出
if($cAct=='fdlogout'){	// 判斷帳號密碼登出
	
	// 前端送出 $storeid
	unset($_SESSION["cust_sid"]);
	if(strpos($ComeFromPage,'&roomctrl')) $ComeFromPage = substr($ComeFromPage,0,strpos($ComeFromPage,'&roomctrl'));
	echo'<html><head></head><script language="JavaScript" type="text/JavaScript">location.replace("'.$ComeFromPage.'");</script></head></html>';
	exit;
}

// －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－
// 飯店基本設定
if($cAct=='postSetup')
if(Valid())
{
	extract($_POST);
	$CustUsed = $_SESSION["cust_sid"];
	//echo $_SESSION["cust_sid"].'<br>';
	//foreach ($_POST as $k => $v) echo "$k=$v<br />";
	//foreach ($cust_facility as $u => $i) echo "$u=$i<br />";
	
	//上傳圖片
	for($i=1;$i<=4;$i++)
	{
		if($_FILES["pic$i"]['name']!='')	// 新增圖片
		{
			//　刪除原本圖
			if($_POST["pic".$i."_soc"] != '')
			{
				$want_del_file = unserialize($_POST["pic".$i."_soc"]);
				$want_del_fila = $ext_file.$want_del_file[0];
				$want_del_filb = $ext_file.$want_del_file[1];
				if(filesize($want_del_fila))unlink($want_del_fila);
				if(filesize($want_del_filb))unlink($want_del_filb);
				unset($want_del_file);unset($want_del_fila);unset($want_del_filb);
			}
			$fileUpload_name = $_FILES["pic$i"]['name'];
			$file_Sname = substr($fileUpload_name, strrpos($fileUpload_name , ".")+1);	//擷取副檔名
			$daa = gettimeofday();														//取時間變數
			$dab = $daa[usec];															//取毫秒
			$dac = date("ymdHis");														//取日期變數:「年月日時分秒」
			$file_Mname = "$dac$dab";													//以目前時間的「年月日時分秒毫秒」來產生新的主檔名
			$file_name = $file_Mname . "." . strtolower($file_Sname);					//合成並顯示新的檔名主檔名
			$file_namea = $file_Mname . "a." . strtolower($file_Sname);					//合成並顯示新的檔名(縮圖)
			$file_name_tmp = $file_Mname . "_tmp." . strtolower($file_Sname);			//合成並顯示新的檔名(縮
			
			$files_link = $ext_file.$file_name_tmp;
			move_uploaded_file($_FILES["pic$i"]['tmp_name'], $files_link);
			chmod($files_link, 0644);
			if(strtolower($file_Sname)=='jpg') $src = imagecreatefromjpeg($files_link);
			elseif(strtolower($file_Sname)=='gif') $src = imagecreatefromgif($files_link);
			else Fail('副檔名不支援');
			
			// get the source image's widht and hight
			$src_w = imagesx($src);
			$src_h = imagesy($src);
				
			// assign thumbnail's widht and hight
			if($i==1){ $default_w = 1000; $default_h = 800; }else{ $default_w = 363; $default_h = 272; }
			
			if(($src_w/$src_h)>1.25){
				if($src_w > $default_w){
					$thuma_w = $default_w;
					$thuma_h = intval($src_h / $src_w * $default_w);
				}else{
					$thuma_w = $src_w;
					$thuma_h = $src_h;
				}
			}else{
				if($src_h > $default_h){
					$thuma_h = $default_h;
					$thuma_w = intval($src_w / $src_h * $default_h);
				}else{
					$thuma_h = $src_h;
					$thuma_w = $src_w;
				}
			}
		
			// assign thumbnail's widht and hight
			$default_x = 200; $default_y = 160;
			if($i==1){ $default_x = 670; $default_y = $default_x/1.25; }
			if($src_h > $default_y){ $thumb_h = $default_y; $thumb_w = intval(($src_w / $src_h) * $default_y);$thumb_w_tmp = $thumb_w; }else{$thumb_h = $src_h;$thumb_w = $src_w;$thumb_w_tmp = $thumb_w;}
			if($thumb_w > $default_x){ $thumb_w = $default_x; $thumb_h = round(($thumb_h / $thumb_w_tmp) * $default_x); unset($thumb_w_tmp);}
			
			// if you are using GD 1.6.x, please use imagecreate()
			$thuma = imagecreatetruecolor($thuma_w, $thuma_h);
			$whita = imagecolorallocate($thuma, 255, 255, 255);
			imagefilledrectangle($thuma, 0, 0, $thuma_w, $thuma_h, $whita);
			$thumb = imagecreatetruecolor($thumb_w, $thumb_h);
			$whitb = imagecolorallocate($thumb, 255, 255, 255);
			imagefilledrectangle($thumb, 0, 0, $thumb_w, $thumb_h, $whitb);
			
			// start resize
			imagecopyresized($thuma, $src, 0, 0, 0, 0, $thuma_w, $thuma_h, $src_w, $src_h);
			imagecopyresized($thumb, $src, 0, 0, 0, 0, $thumb_w, $thumb_h, $src_w, $src_h);
			
			// save thumbnail
			if(strtolower($file_Sname)=='jpg'){
				imagejpeg($thuma, $ext_file.$file_name);
				imagejpeg($thumb, $ext_file.$file_namea);
			}elseif(strtolower($file_Sname)=='gif'){
				imagegif($thuma, $ext_file.$file_name);
				imagegif($thumb, $ext_file.$file_namea);
			}
			$tmp_picval[] = array($file_name,$file_namea);
			unlink($files_link);
		}
		else $tmp_picval[] = unserialize(stripcslashes(${"pic".$i."_soc"}));
	}
	if(is_array($tmp_picval)){ $pic = serialize($tmp_picval); $sql_ext .= ', `pic` = \''.$pic.'\''; }
	if(is_array($cust_facility)){ $cust_facility = serialize($cust_facility); $sql_ext .= ', `cust_facility` = \''.$cust_facility.'\'';}
	
	if(!$rsPost = $db->Execute("UPDATE `sys_customer` SET `cust_intro` = '".$cust_intro."', `cust_tel` = '".$cust_tel."', `cust_fax` = '".$cust_fax."', `cust_address` = '".$cust_address."', `cust_ordertel` = '".$cust_ordertel."'".$sql_ext." WHERE `serial_id` = '".$CustUsed."'")) Fail("修改失敗! 請重新檢查");
	echo'<html><head></head><script language="JavaScript" type="text/JavaScript">location.replace("'.$ComeFromPage.'");</script></head></html>';
	exit;
}

// －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－
// 促銷活動
if($cAct=='postOnsale')
if(Valid())
{
	extract($_POST);
	$price_cut = array('price1','price2','price3','price4','price5','price6','price7');
	$CustUsed = $_SESSION["cust_sid"];
	
	if($action=='new')	// 新增
	{
		$serial_id = md5(uniqid(rand(),true));
		foreach($_POST as $k => $v)
		{
			if (in_array($k, $price_cut)) $price[] = $v;
		}
		$detail = serialize($detail);
		$price = serialize($price);
		if(!$rsPost = $db->Execute("INSERT INTO `sys_onsale` (`serial_id`,`cust_sid`,`type`,`subject`,`subject2`,`manydays`,`sprice`,`uprice`,`price`,`room_match`,`room_open`,`f_date1`,`f_date2`,`content`,`creatime`) VALUES ('".$serial_id."','".$CustUsed."','".$order."','".$subject."','".$subject2."','".$manydays."','".$sprice."','".$uprice."','".$price."','".$room_match."','".$room_open."','".$f_date1."','".$f_date2."','".$content."',NOW())")) Fail("新增失敗! 請重新檢查");
		if(substr_count($ComeFromPage,"&new")) $ComeFromPage = str_replace('&new','',$ComeFromPage);
	}
	else	// 批次修改
	{
		$AReditval = explode (",", $EditVal);
		foreach($AReditval as $v)
		{
			foreach($price_cut as $e) $priceAll[] = ${$e."_".$v};
			$priceAll = serialize($priceAll);
			
			if(${"status_".$v} == 'd')	// 判斷刪除
			{
				if(!$rs = $db->Execute("SELECT `serial_id` FROM `sys_onsale` WHERE `cust_sid` = '".$CustUsed."' AND `auto_add` = '".$v."'")) Fail("資料庫連接失敗");
				if($db->num_rows($rs))
				{
					$rs_sid = $db->fetch_array($rs);	// 取出 room_sid
					if($rsPost = $db->Execute("DELETE FROM `sys_onsale` WHERE `cust_sid` = '".$CustUsed."' AND `auto_add` = '".$v."'"))
					{
						$db->Execute("DELETE FROM `sys_room_state` WHERE `room_sid` = '".$rs_sid[0]."'");
					}
					else Fail(${"subject_".$v}." 刪除失敗 請稍候再試");
				}
				else Fail("查詢失敗!");
			}
			else	// 其餘修改
			{
				if(!$rsPost = $db->Execute("UPDATE `sys_onsale` SET `type` = '".${"order_".$v}."', `subject` = '".${"subject_".$v}."', `subject2` = '".${"subject2_".$v}."', `manydays` = '".${"manydays_".$v}."', `sprice` = '".${"sprice_".$v}."', `uprice` = '".${"uprice_".$v}."', `price` = '".$priceAll."', `room_match` = '".${"room_match_".$v}."', `room_open` = '".${"room_open_".$v}."', `f_date1` = '".${"f_date1_".$v}."', `f_date2` = '".${"f_date2_".$v}."', `content` = '".${"content_".$v}."', `state` = '".${"status_".$v}."' WHERE `cust_sid` = '".$CustUsed."' AND `auto_add` = '".$v."'")) Fail("修改失敗! 請重新檢查");
			}
			unset($detail,$priceAll);
		}
	}
	
	echo'<html><head></head><script language="JavaScript" type="text/JavaScript">location.replace("'.$ComeFromPage.'");</script></head></html>';
	exit;
}

// －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－
// 房型介紹
if($cAct=='postRoom')
if(Valid())
{
	extract($_POST);
	$detail_cut = array('pinswo','sprice','uprice','counts','people','bedtype','bedsvc','peosvc','windows','deban','yaush');
	$price_cut = array('price1','price2','price3','price4','price5','price6','price7');
	$CustUsed = $_SESSION["cust_sid"];
	
	if($action=='new')	// 新增
	{
		$serial_id = md5(uniqid(rand(),true));
		foreach($_POST as $k => $v)
		{
			if (in_array($k, $detail_cut)) $detail[] = $v;
			if (in_array($k, $price_cut)) $price[] = $v;
		}
		$detail = serialize($detail);
		$price = serialize($price);
		if(!$rsPost = $db->Execute("INSERT INTO `sys_room` (`serial_id`,`cust_sid`,`type`,`subject`,`detail`,`price`,`order_percent`,`content`,`creatime`) VALUES ('".$serial_id."','".$CustUsed."','".$order."','".$subject."','".$detail."','".$price."','".$percent."','".$content."',NOW())")) Fail("新增失敗! 請重新檢查");
		if(substr_count($ComeFromPage,"&new")) $ComeFromPage = str_replace('&new','',$ComeFromPage);
	}
	else	// 批次修改
	{
		$AReditval = explode (",", $EditVal);
		foreach($AReditval as $v)
		{
			foreach($detail_cut as $d) $detail[] = ${$d."_".$v};
			$detail = serialize($detail);
			foreach($price_cut as $e) $priceAll[] = ${$e."_".$v};
			$priceAll = serialize($priceAll);
			
			if(${"status_".$v} == 'd')	// 判斷刪除
			{
				if(!$rs = $db->Execute("SELECT `serial_id` FROM `sys_room` WHERE `cust_sid` = '".$CustUsed."' AND `auto_add` = '".$v."'")) Fail("資料庫連接失敗");
				if($db->num_rows($rs))
				{
					$rs_sid = $db->fetch_array($rs);	// 取出 room_sid
					if($rsPost = $db->Execute("DELETE FROM `sys_room` WHERE `cust_sid` = '".$CustUsed."' AND `auto_add` = '".$v."'"))
					{
						$db->Execute("DELETE FROM `sys_room_state` WHERE `room_sid` = '".$rs_sid[0]."'");
					}
					else Fail(${"subject_".$v}." 刪除失敗 請稍候再試");
				}
				else Fail("查詢失敗!");
			}
			else	// 其餘修改
			{
				if(!$rsPost = $db->Execute("UPDATE `sys_room` SET `type` = '".${"order_".$v}."', `subject` = '".${"subject_".$v}."', `detail` = '".$detail."', `price` = '".$priceAll."', `order_percent` = '".${"percent_".$v}."', `content` = '".${"content_".$v}."', `state` = '".${"status_".$v}."' WHERE `cust_sid` = '".$CustUsed."' AND `auto_add` = '".$v."'")) Fail("修改失敗! 請重新檢查");
			}
			unset($detail,$priceAll);
		}
	}
	
	echo'<html><head></head><script language="JavaScript" type="text/JavaScript">location.replace("'.$ComeFromPage.'");</script></head></html>';
	exit;
}

// －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－
// 控房修改
if($cAct=='postRoomTable')
if(Valid())
{
	extract($_POST);
	if(!$roomstatesid) Fail("配對錯誤,編輯來源不明!");
	if(!$rs = $db->Execute("SELECT * FROM `sys_room_state` WHERE `serial_id` = '".$roomstatesid."'")) Fail("資料庫連接失敗");
	if($db->num_rows($rs))
	{
		$rs_array = $db->fetch_array($rs);
		$RoomId = $rs_array["room_sid"];
		$RoomInfo = unserialize($rs_array["Rtable"]);
//echo $rs_array["Rtable"].'<hr>';
		for($i=0;$i<count($Exe_day);$i++)
		{
			if($changeamount) $RoomInfo[$Exe_day[$i]][0] = $changeamount;
			if($changecash) $RoomInfo[$Exe_day[$i]][1] = $changecash;
			if($rstate!=9) $RoomInfo[$Exe_day[$i]][2] = $rstate;
			//echo $Exe_day[$i].',';
		}
		$Quene = serialize($RoomInfo);
//echo '<hr>'.$Quene.'<hr>';
	}
	else Fail("系統查無配對資料!");
	
	if(!$rsPost = $db->Execute("UPDATE `sys_room_state` SET `Rtable` = '".$Quene."' WHERE `serial_id` = '".$roomstatesid."'")) Fail("修改失敗! 請重新檢查");
//	foreach ($_POST as $k => $v){
//      echo "$k   =   $v<br />";
//	}
//	exit;
	if(!substr_count($ComeFromPage,"Product")) $ComeFromPage .= '&Product='.$RoomId;
	echo'<html><head></head><script language="JavaScript" type="text/JavaScript">location.replace("'.$ComeFromPage.'");</script></head></html>';
	exit;
}

// 控房修改(批量)
if($cAct=='postRoomTableGroup')
if(Valid())
{
	extract($_POST);
	function RoomOn($id,$m)
	{
		global $db,$year;
		if($m=='2') $Month_range='28';elseif($m=='4' || $m=='6' || $m=='9' || $m=='11') $Month_range='30';else $Month_range='31';
		if(!$rs = $db->Execute("SELECT `detail`,`price` FROM `sys_room` WHERE `serial_id` = '".$id."'")) Fail("資料庫連接失敗");		// 基本房型
		if($db->num_rows($rs))
		{
			$rs_ar = $db->fetch_array($rs);
			$detail = unserialize($rs_ar["detail"]);
			$DefauleRoom = $detail[3];
		}
		else
		{
			if(!$rs = $db->Execute("SELECT `room_open`,`price` FROM `sys_onsale` WHERE `serial_id` = '".$id."'")) Fail("資料庫連接失敗");	// 專案房型
			$rs_ar = $db->fetch_array($rs);
			$DefauleRoom = $rs_ar["room_open"];
		}
		$priceO = unserialize($rs_ar["price"]);
		for($i=0;$i<$Month_range;$i++)
		{
			if(!isset($Quene)) $Quene = array(($i+1) => array($DefauleRoom,$priceO[$exc_day],1)); else array_push($Quene , array($DefauleRoom,$priceO[$exc_day],1));
			if($exc_day<6) $exc_day++; else $exc_day=0;
		}
		
		$serial_id = md5(uniqid(rand(),true));
		$Quene = serialize($Quene);
		if(!$rs = $db->Execute("SELECT * FROM `sys_room_state` WHERE `room_sid` = '".$id."' AND `Ryear` = '".$year."' AND `Rmonth` = '".$m."'")) Fail("資料庫連接失敗");
		if($db->num_rows($rs)==0)
		{
			if(!$rsPost = $db->Execute("INSERT INTO `sys_room_state` (`serial_id`,`room_sid`,`Ryear`,`Rmonth`,`Rtable`) VALUES ('".$serial_id."','".$id."','".$year."','".$m."','".$Quene."')")) Fail("新增失敗! 請重新檢查");
		}
	}
	foreach ($month as $v)
	{
		$pieces = explode (",", $v);
		RoomOn($pieces[0],$pieces[1]);
	}

	echo'<html><head></head><script language="JavaScript" type="text/JavaScript">location.replace("'.$ComeFromPage.'");</script></head></html>';
	exit;
}

// －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－
// 飯店設施.餐廳介紹
if($cAct=='postPlant')
if(Valid())
{
	extract($_POST);
	$CustUsed = $_SESSION["cust_sid"];
	
	$ar_cnt = array('message1','message2');
	foreach ($ar_cnt as $k => $v)
	{
		if($$v)
		{
			// 判斷第一次還是修改
			if(!$rs = $db->Execute("SELECT * FROM `sys_plant` WHERE `cust_sid` = '".$CustUsed."' AND `type` = '".$v."'")) Fail("資料庫連接失敗");
			if($db->num_rows($rs)) // 已存在,進行更新
			{
				if(!$rsPost = $db->Execute("UPDATE `sys_plant` SET `content` = '".$$v."' WHERE `cust_sid` = '".$CustUsed."' AND `type` = '".$v."'")) Fail("修改失敗! 請重新檢查");
			}
			else	// 進行新增
			{
				$serial_id = md5(uniqid(rand(),true));
				if(!$rsPost = $db->Execute("INSERT INTO `sys_plant` (`serial_id`,`cust_sid`,`type`,`content`) VALUES ('".$serial_id."','".$CustUsed."','".$v."','".$$v."')")) Fail("新增失敗! 請重新檢查");
			}
		}
	}
	echo'<html><head></head><script language="JavaScript" type="text/JavaScript">location.replace("'.$ComeFromPage.'");</script></head></html>';
	exit;
}

// －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－
// 交通指南
if($cAct=='postTraffic')
if(Valid())
{
	extract($_POST);
	$CustUsed = $_SESSION["cust_sid"];
	
	if($content)
	{
		// 判斷第一次還是修改
		if(!$rs = $db->Execute("SELECT * FROM `sys_traffic` WHERE `cust_sid` = '".$CustUsed."'")) Fail("資料庫連接失敗");
		if($db->num_rows($rs)) // 已存在,進行更新
		{
			if(!$rsPost = $db->Execute("UPDATE `sys_traffic` SET `content` = '".$content."' WHERE `cust_sid` = '".$CustUsed."'")) Fail("修改失敗! 請重新檢查");
		}
		else	// 進行新增
		{
			$serial_id = md5(uniqid(rand(),true));
			if(!$rsPost = $db->Execute("INSERT INTO `sys_traffic` (`serial_id`,`cust_sid`,`content`) VALUES ('".$serial_id."','".$CustUsed."','".$content."')")) Fail("新增失敗! 請重新檢查");
		}
	}
	echo'<html><head></head><script language="JavaScript" type="text/JavaScript">location.replace("'.$ComeFromPage.'");</script></head></html>';
	exit;
}

// －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－
// 鄰近景點
if($cAct=='postViews')
if(Valid())
{
	extract($_POST);
	$CustUsed = $_SESSION["cust_sid"];
	
	if($content)
	{
		// 判斷第一次還是修改
		if(!$rs = $db->Execute("SELECT * FROM `sys_views` WHERE `cust_sid` = '".$CustUsed."'")) Fail("資料庫連接失敗");
		if($db->num_rows($rs)) // 已存在,進行更新
		{
			if(!$rsPost = $db->Execute("UPDATE `sys_views` SET `content` = '".$content."' WHERE `cust_sid` = '".$CustUsed."'")) Fail("修改失敗! 請重新檢查");
		}
		else	// 進行新增
		{
			$serial_id = md5(uniqid(rand(),true));
			if(!$rsPost = $db->Execute("INSERT INTO `sys_views` (`serial_id`,`cust_sid`,`content`) VALUES ('".$serial_id."','".$CustUsed."','".$content."')")) Fail("新增失敗! 請重新檢查");
		}
	}
	echo'<html><head></head><script language="JavaScript" type="text/JavaScript">location.replace("'.$ComeFromPage.'");</script></head></html>';
	exit;
}

// －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－
// 訂房需知
if($cAct=='postOrderknow')
if(Valid())
{
	extract($_POST);
	$CustUsed = $_SESSION["cust_sid"];
	
	if($content)
	{
		// 判斷第一次還是修改
		if(!$rs = $db->Execute("SELECT * FROM `sys_orderknow` WHERE `cust_sid` = '".$CustUsed."'")) Fail("資料庫連接失敗");
		if($db->num_rows($rs)) // 已存在,進行更新
		{
			if(!$rsPost = $db->Execute("UPDATE `sys_orderknow` SET `content` = '".$content."' WHERE `cust_sid` = '".$CustUsed."'")) Fail("修改失敗! 請重新檢查");
		}
		else	// 進行新增
		{
			$serial_id = md5(uniqid(rand(),true));
			if(!$rsPost = $db->Execute("INSERT INTO `sys_orderknow` (`serial_id`,`cust_sid`,`content`) VALUES ('".$serial_id."','".$CustUsed."','".$content."')")) Fail("新增失敗! 請重新檢查");
		}
	}
	echo'<html><head></head><script language="JavaScript" type="text/JavaScript">location.replace("'.$ComeFromPage.'");</script></head></html>';
	exit;
}

// －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－
// 新手上路
if($cAct=='postHelp')
if(Valid())
{
	extract($_POST);
	$CustUsed = $_SESSION["cust_sid"];
	
	if($content)
	{
		// 判斷第一次還是修改
		if(!$rs = $db->Execute("SELECT * FROM `sys_help` WHERE `cust_sid` = '".$CustUsed."'")) Fail("資料庫連接失敗");
		if($db->num_rows($rs)) // 已存在,進行更新
		{
			if(!$rsPost = $db->Execute("UPDATE `sys_help` SET `content` = '".$content."' WHERE `cust_sid` = '".$CustUsed."'")) Fail("修改失敗! 請重新檢查");
		}
		else	// 進行新增
		{
			$serial_id = md5(uniqid(rand(),true));
			if(!$rsPost = $db->Execute("INSERT INTO `sys_help` (`serial_id`,`cust_sid`,`content`) VALUES ('".$serial_id."','".$CustUsed."','".$content."')")) Fail("新增失敗! 請重新檢查");
		}
	}
	echo'<html><head></head><script language="JavaScript" type="text/JavaScript">location.replace("'.$ComeFromPage.'");</script></head></html>';
	exit;
}

// －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－
// 相簿處理
if($cAct=='postAlbum')
if(Valid())
{
	extract($_POST);
	if(isset($newNews)) $news_code = $newNews; else $news_code = $Newsid;
	$titl = stripslashes($edtTitle);
	$data = trim(stripslashes($edtNews));
	
	// 新增圖片
	if (isset($PHPSESSID)) $_SESSION["PHPSESSID"] = $PHPSESSID;
	if (isset($_FILES['Filedata']['name'])){
		if (!is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
			header("HTTP/1.1 500 File Upload Error");
			if (isset($_FILES["Filedata"])) {
				echo $_FILES["Filedata"]["error"];
			}
			exit(0);
		}
		$fileUpload_name = $_FILES['Filedata']['name'];		
		$file_Sname = substr($fileUpload_name, strrpos($fileUpload_name , ".")+1);	//擷取副檔名
		$daa = gettimeofday();														//取時間變數
		$dab = $daa[usec];															//取毫秒
		$dac = date("ymdHis");														//取日期變數:「年月日時分秒」
		$file_Mname = "$dac$dab";													//以目前時間的「年月日時分秒毫秒」來產生新的主檔名
		$file_name = $file_Mname . "." . strtolower($file_Sname);					//合成並顯示新的檔名主檔名
		$file_namea = $file_Mname . "a." . strtolower($file_Sname);					//合成並顯示新的檔名(縮圖)
		$file_name_tmp = $file_Mname . "_tmp." . strtolower($file_Sname);			//合成並顯示新的檔名(縮圖)
		
		$rs = $db->Execute("SELECT `a_pic` FROM `blog_action` WHERE `actioncode` = '".$news_code."'");
		$rs_array = $db->fetch_array($rs);
		if ($rs_array["a_pic"] == null){
			$new_pic = $file_name."|||@@@|||".$fileUpload_name."|||@@@|||".$file_namea."|||@@@|||".$file_Mname;
		}else{
			$new_pic = $rs_array["a_pic"]."###@@@###".$file_name."|||@@@|||".$fileUpload_name."|||@@@|||".$file_namea."|||@@@|||".$file_Mname;
		}
		
		$files_link = $ext_file.$file_name_tmp;
		move_uploaded_file($_FILES['Filedata']['tmp_name'], $files_link);
		chmod($files_link, 0644);
		
		if(strtolower($file_Sname)=='jpg') $src = imagecreatefromjpeg($files_link);
		elseif(strtolower($file_Sname)=='gif') $src = imagecreatefromgif($files_link);
		elseif(strtolower($file_Sname)=='png') $src = imagecreatefrompng($files_link);
		else echo '錯誤！副檔名不支援';
		
		// get the source image's widht and hight
		$src_w = imagesx($src);
		$src_h = imagesy($src);
		
		// assign thumbnail's widht and hight
		if(($src_w/$src_h)>1.25){
			if($src_w > 600){
				$thuma_w = 600;
				$thuma_h = intval($src_h / $src_w * 600);
			}else{
				$thuma_w = $src_w;
				$thuma_h = $src_h;
			}
		}else{
			if($src_h > 480){
				$thuma_h = 480;
				$thuma_w = intval($src_w / $src_h * 480);
			}else{
				$thuma_h = $src_h;
				$thuma_w = $src_w;
			}
		}
	
		// assign thumbnail's widht and hight
		if($src_w > $src_h){
			if($src_w < 150){
				$thumb_w = $src_w;
				$thumb_h = $src_h;
			}else{
				$thumb_w = 150;
				$thumb_h = intval($src_h / $src_w * 150);
			}
		}else{
			$thumb_h = 100;
			$thumb_w = intval($src_w / $src_h * 100);
		}
		
		// if you are using GD 1.6.x, please use imagecreate()
		$thuma = imagecreatetruecolor($thuma_w, $thuma_h);
		$whita = imagecolorallocate($thuma, 255, 255, 255);
		imagefilledrectangle($thuma, 0, 0, $thuma_w, $thuma_h, $whita);
		$thumb = imagecreatetruecolor($thumb_w, $thumb_h);
		$whitb = imagecolorallocate($thumb, 255, 255, 255);
		imagefilledrectangle($thumb, 0, 0, $thumb_w, $thumb_h, $whitb);
		
		// start resize
		imagecopyresized($thuma, $src, 0, 0, 0, 0, $thuma_w, $thuma_h, $src_w, $src_h);
		imagecopyresized($thumb, $src, 0, 0, 0, 0, $thumb_w, $thumb_h, $src_w, $src_h);
		
		// save thumbnail
		if(strtolower($file_Sname)=='jpg'){
			imagejpeg($thuma, $ext_file.$file_name);
			imagejpeg($thumb, $ext_file.$file_namea);
		}elseif(strtolower($file_Sname)=='gif'){
			imagegif($thuma, $ext_file.$file_name);
			imagegif($thumb, $ext_file.$file_namea);
		}elseif(strtolower($file_Sname)=='png'){
			imagepng($thuma, $ext_file.$file_name);
			imagepng($thumb, $ext_file.$file_namea);
		}
		
		$db->Execute("UPDATE `blog_action` SET `a_pic` = '".$new_pic."' WHERE `actioncode` = '".$news_code."'");
		unlink($ext_file.$files_link);
		exit(0);
	}

	if(isset($newNews))	// 新增
	{
		$cust_sid = $_SESSION["cust_sid"];
		$rs = $db->Execute("SELECT MAX(`actioncode`) FROM `blog_action` WHERE `actioncode` LIKE '".date(Ymd)."%'");
		$Today = $db->fetch_array($rs);
		if(isset($Today[0])) $news_code = $Today[0]+1; else $news_code = $newNews;
		if(!$rsPost = $db->Execute("INSERT INTO `blog_action` (`actioncode`,`actiondate`,`cust_sid`,`title`,`content`) VALUES ('".$news_code."',now(),'".$cust_sid."','".$titl."','".$data."')")) Fail("新增失敗! 請重新檢查");
	}
	elseif($action_delpicid == $Newsid)	// 刪除
	{
		$cNewscode = $action_delpicid;	// 檢查有無照片, 若有, 一併刪除
		$rs = $db->Execute("SELECT * FROM `blog_action` WHERE `actioncode` = '".$cNewscode."' ");
		$rs_array = $db->fetch_array($rs);
		if($rs_array["a_pic"]!=''){	// 有照片, 刪
			$f_pic=explode("###@@@###",$rs_array['a_pic']);
			for($j=0;$j<count($f_pic);$j++) {
				$f_pic[$j] = eregi_replace("-ns)","",trim($f_pic[$j]));		//將a_pic抓進陣,去除所有有-標註
				$g_pic=explode("|||@@@|||",$f_pic[$j]);
				unlink($ext_file.$g_pic[0]);
				unlink($ext_file.$g_pic[2]);
			}
		}
		if(!$rsPost = $db->Execute("DELETE FROM `blog_action` WHERE `actioncode` = '".$news_code."'")) Fail("刪除失敗! 請重新檢查");
	}
	elseif(isset($edtTitle))	// 更新
	{
		if(!$rsPost = $db->Execute("UPDATE `blog_action` SET `title` = '".$titl."', `content` = '".$data."' WHERE `actioncode` = '".$news_code."'")) Fail("更新失敗! 請重新檢查");
		if($all_view != null){
			$f_pic = explode("###@@@###",$all_view);
			for($j=0;$j<count($f_pic);$j++)
			{
				$word1 = ${"img_url_".$j};						//實圖路徑
				$word2 = ${"img_title_".$j};					//實圖標題
				$word3 = substr($word1,0,strpos($word1,"."))."a".strrchr($word1,".");
				$word4 = ${"img_somen_".$j};					//實圖說明
				$word = $word1.'|||@@@|||'.$word2.'|||@@@|||'.$word3.'|||@@@|||'.$word4;
				if (${"C".$j} == '1'){if (!isset($new_pic)) $new_pic = "-ns)".$word; else $new_pic = $new_pic."###@@@###-ns)".$word;
				}else{if (!isset($new_pic)) $new_pic = $word; else $new_pic = $new_pic."###@@@###".$word;}
			}
			if(!$rsPost = $db->Execute("UPDATE `blog_action` SET `a_pic`='".$new_pic."' WHERE `actioncode` = '".$news_code."'")) Fail("更新失敗! 請重新檢查");
		}
	}
	if(substr_count($ComeFromPage,"=new")) $ComeFromPage = ereg_replace("=new","=$Newsid",$ComeFromPage);
	echo'<html><head></head><script language="JavaScript" type="text/JavaScript">location.replace("'.$ComeFromPage.'");</script></head></html>';
	exit;
}

// 相簿之刪除照片
if($cAct=="action_delpic")
{
	$news_code = $_POST['Newsid'];
	$fileDel_name = $_POST['action_delpicid'];
	$all_view = $_POST['all_view'];
	$fileDel_namea = substr($fileDel_name,0,strpos($fileDel_name,"."))."a".strrchr($fileDel_name,".");
	//$rs = mssql_query("SELECT a_pic FROM blog_action WHERE actioncode='".$newscode."'");
	//$rs_array = mssql_fetch_array($rs);
	//$f_pic=explode(",",$rs_array['a_pic']);
	$f_pic=explode("###@@@###",$all_view);
	for($j=0;$j<count($f_pic);$j++)
	{
		//echo $f_pic[$j].'<br>';
		if (substr_count($f_pic[$j],$fileDel_name)==0)
		{
			if (isset($g_pic))$g_pic.='###@@@###';
			$g_pic.=$f_pic[$j];
		}
	}

	$new_pic = eregi_replace("###@@@###$fileDel_name###@@@###","###@@@###",$g_pic);
	$new_pic = eregi_replace("$fileDel_name###@@@###","",$new_pic);
	$new_pic = eregi_replace("###@@@###$fileDel_name","",$new_pic);
	$new_pic = eregi_replace("$fileDel_name","",$new_pic);
	//echo $new_pic;
	//exit;
	if(!$rs = $db->Execute("UPDATE `blog_action` SET `a_pic`='".$new_pic."' WHERE `actioncode`='".$news_code."' ")) Fail("資料庫連接失敗");
	unlink($ext_file.$fileDel_name);
	unlink($ext_file.$fileDel_namea);
	echo'<html><head></head><script language="JavaScript" type="text/JavaScript">location.replace("'.$ComeFromPage.'");</script></head></html>';
	exit;
}

// ---------------------------------------------------------------------
// 討論區發新貼
if($cAct=="POmsg")
{
	extract($_POST);
	$Site_sid = $_SESSION["Site_sid"];
	$rs = $db->Execute("INSERT INTO `msgboard` (`serial_id`,`msgdate`,`cust_sid`,`author`,`title`,`question`) VALUES ('".md5(uniqid(rand(),true))."','".date("Y-m-d H:i:s")."','".$Site_sid."','".$Author."','".$txtTitle."','".$edtMessage."')");
	if(substr_count($ComeFromPage,"&message=new")) $ComeFromPage = str_replace('&message=new','',$ComeFromPage);
	if($rs) AlertJump('留言成功',''.$ComeFromPage.''); else die('資料庫發生錯誤');
}

// 討論區回覆
if($cAct=="Replymsg")
{
	extract($_POST);
	if($_SESSION["login_id"]) $reMsger = $_SESSION["login_id"]; else $reMsger = $_SESSION["cust_sid"];
	if(!$rs = $db->Execute("SELECT `answer` FROM `msgboard` WHERE `serial_id` = '".$txt_id."'")) Fail('資料庫連線錯誤!');
	if($db->num_rows($rs))
	{
		$row = $db->fetch_assoc($rs);
		if($row["answer"])
		{	// 堆疊陣列
			$arr = unserialize($row["answer"]);
			$arr[] = array(date("Y-m-d H:i:s"),$reMsger,$edtMessage);
			$msg_array = serialize($arr);
		}
		else
		{	// 尚未存在值
			$arr[] = array(date("Y-m-d H:i:s"),$reMsger,$edtMessage);
			$msg_array = serialize($arr);
		}
		$rs1 = $db->Execute("UPDATE `msgboard` SET `answer` = '".$msg_array."' WHERE `serial_id` = '".$txt_id."'");
		if($rs1) AlertJump('',''.$ComeFromPage.''); else die('資料庫發生錯誤');
	}
	else Fail("查無相關主題,無法新增回復文章內容 ");
}

// －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－

// 所有發佈訊息修改與新增判斷
if($cAct=="postNews")
if(Valid())
{
	$cust_sid = $_SESSION["cust_sid"];
	$RecCount = $_POST["RecCount"];
	for($x=0;$x<$RecCount;$x++){
		$now_v0 = $_POST["t_index_$x"];
		$now_v1 = $_POST["t_state_$x"];
		$now_v2 = $_POST["post_type_$x"];
		//判斷刪除
		if($now_v1 == 'd'){
			if(!$rsPost = $db->Execute("DELETE FROM `sys_bulletin` WHERE `serial_id` = '".$now_v0."'")) Fail($new_v0." 刪除失敗 請稍候再試");
			if(isset($chk_script))$chk_script .= ','.$now_v0; else $chk_script = $now_v0;
		//其餘修改
		}else{if(!$rsPost = $db->Execute("UPDATE `sys_bulletin` SET `s_state` = '".$now_v1."' WHERE `serial_id` = '".$now_v0."'")) Fail("更新失敗! 請重新檢查");}
	}
	if(isset($chk_script)) $chk1 = $chk_script; else $chk1 = ',';
	if(isset($_POST["EditNewsid"])) $chk2 = $_POST["EditNewsid"]; else $chk2 = ' ';
	if(substr_count($chk1,$chk2)==0)	//如果有刪除動作則不執行新增修改程序
	if($_POST["t_cht_n"]!='' && $_POST["content"]!='')	// 新增,修改
	{
		$rtype = $_POST["Type"];
		if($_POST["hyplnk_switch"] && $_POST["hyplnk"] )
		{ 
			$hyplnk = str_replace ("http://", "", $_POST["hyplnk"]);
			$hyplnk_update = '\''.$hyplnk.'\'';$hyplnk_add = '\''.$hyplnk.'\'';
		}
		else{ $hyplnk_update = 'NULL';$hyplnk_add = 'NULL'; }
		$titl = filter_xss(stripslashes($_POST["t_cht_n"]));	//HTML跳脫(為了安全將<改為&lt;)
		$somecontent = filter_xss($_POST["content"]);
		if(isset($_POST["EditNewsid"])){
			if(isset($_POST["news_time"]))$ntime = $_POST["news_time"];else $ntime = '';
			if(!$rsPost = $db->Execute("UPDATE `sys_bulletin` SET `news_typ` = '".$rtype."', `title` = '".$titl."', `content` = '".$somecontent."', `hyplnk` = ".$hyplnk_update.", `news_time` = '".$ntime."' WHERE `serial_id` = '".$chk2."'")) Fail("修改失敗! 請重新檢查");
		}else{
			$serial_id = md5(uniqid(rand(),true));
			$news_time = date("Y").'-'.date("m").'-'.date("d");
			if(!$rsPost = $db->Execute("INSERT INTO `sys_bulletin` (serial_id,cust_sid,news_typ,title,content,hyplnk,news_code,news_time) VALUES ('".$serial_id."','".$cust_sid."','".$rtype."','".$titl."','".$somecontent."',".$hyplnk_add.",NOW(),'".$news_time."')")) Fail("新增失敗! 請重新檢查");
			// else{ $rs = $db->Execute("SELECT MAX(serial_id) FROM `bulletin`");$rsPost = mysql_fetch_array($rs);$chk2 = $rsPost[0];}
			// echo $doid;exit;
		}
	}

	if($rsPost) AlertJump('',''.$ComeFromPage.''); else die('資料庫發生錯誤');
}

// －－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－－
// 判斷SESSION時間物件
function Valid(){
	if(!isset($_SESSION["cust_sid"])){
		session_unset();
		echo '<html><head><meta http-equiv="Refresh" content="0;url=./"></head><body></html>';
		exit;
	}else return true;
}
$serial_id = md5(uniqid(rand(),true));
echo $serial_id.'<br>';
?>