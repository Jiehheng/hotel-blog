<?php
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');
	$db= new db_action;
	session_start();
	
	// #1. 檢測權限是否有 action 的權限,並且設定權限細項在$_SESSION中
	check_authority('action');
	if(!isset($_POST["act"])){ echo html_start();Fail("接口錯誤!");echo html_end();}
	$cAct = $_POST["act"];
	
	/// POST參數 start///
	// 所有發佈訊息修改與新增判斷
	if($cAct=="postNews")
	{
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
		if($_POST["t_cht_n"]!='' && $_POST["Adv"]!='')	// 新增,修改
		{
			$rtype = $_POST["Type"];
			if($_POST["hyplnk_switch"] && $_POST["hyplnk"] )
			{ 
				$hyplnk = str_replace ("http://", "", $_POST["hyplnk"]);
				$hyplnk_update = '\''.$hyplnk.'\'';$hyplnk_add = '\''.$hyplnk.'\'';
			}
			else{ $hyplnk_update = 'NULL';$hyplnk_add = 'NULL'; }
			$titl = filter_xss(stripslashes($_POST["t_cht_n"]));	//HTML跳脫(為了安全將<改為&lt;)
			$somecontent = filter_xss($_POST["Adv"]);
			if(isset($_POST["EditNewsid"])){
				if(isset($_POST["news_time"]))$ntime = $_POST["news_time"];else $ntime = '';
				if(!$rsPost = $db->Execute("UPDATE `sys_bulletin` SET news_typ = '".$rtype."', title = '".$titl."', content = '".$somecontent."', `hyplnk` = ".$hyplnk_update.", `news_time` = '".$ntime."' WHERE serial_id = '".$chk2."'")) Fail("修改失敗! 請重新檢查");
			}else{
				$serial_id = md5(uniqid(rand(),true));
				$news_time = date("Y").'-'.date("m").'-'.date("d");
				if(!$rsPost = $db->Execute("INSERT INTO `sys_bulletin` (serial_id,news_typ,title,content,hyplnk,author,news_code,news_time) VALUES ('".$serial_id."','".$rtype."','".$titl."','".$somecontent."',".$hyplnk_add.",'".$_SESSION['session_account']."',NOW(),'".$news_time."')")) Fail("新增失敗! 請重新檢查");
				// else{ $rs = $db->Execute("SELECT MAX(serial_id) FROM `bulletin`");$rsPost = mysql_fetch_array($rs);$chk2 = $rsPost[0];}
				// echo $doid;exit;
			}
		}

		echo '<html><head><meta http-equiv="Refresh" content="0;url=./?type='.$rtype.'"></head><body></body></html>';
		exit;
	}
	/// POST參數 end///
?>