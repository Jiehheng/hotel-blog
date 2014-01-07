<?php
mb_http_output('UTF-8');
require_once('config/db_config.php');
require_once('config/db_action.php');
require_once('lib/function.php');
require_once('lib/Xtemplate.class.php');
require_once('lib/facebook.php');

// Facebook Function
$facebook = new Facebook($FBarray);
session_start();
$db=new db_action;

// See if there is a user from a cookie
$FBuser = $facebook->getUser();

if ($FBuser) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    //echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
    $FBuser = null;
  }
}

// ---------------------------------------- Script ---------------------------------------------------
// 如果登入者跟正在瀏覽的網頁同個帳號的話
if($_SESSION['custno']) if($cDealer == $_SESSION['custno']) $edit_mode = 1;
/*
visaCount($cUrl);
$_SESSION['cust_no'] = $cDealer;
if(!$rsCount = $db->fetch_array($db->Execute("SELECT visa_count FROM store_count WHERE id='".$cUrl."' "))) Fail('該飯店商計數器讀取');
if(!$rsTodayCount = $db->num_rows($db->Execute("SELECT ip FROM store_count_tempip WHERE id='".$cUrl."' and time = '".date(Ymd)."'"))) Fail('該飯店商當日計數器讀取');
$custCount = $rsCount[0];

if(!$rsPost = $db->Execute("SELECT * FROM `storeinfo` WHERE `serial_id` = '".$cDealer."' ")) Fail('該飯店商資料讀取');
$rs_ar=$db->fetch_array($rsPost);
$rs_ar_intro = CgUTF8($rs_ar["intro"]);
$cFunc = $_GET["func"];
*/

// 標記登入中....
if($_SESSION["cust_sid"]) $CustUsed = $_SESSION["cust_sid"];								// 經銷登入無誤.
if($_SESSION["login_id"] || $_SESSION["fb_$FBarray[appId]_user_id"]) $login_state = 1;		// 會員登入無誤.
//if($login_state) echo'會員登入狀態';
//if($_SESSION["cust_sid"]) echo'經銷登入狀態';
$CutoutUrl = array();
if($_GET)
{
	// 拆解個別用戶 過濾導入主頁以外功能頁
	$tmpV = strchr($_SERVER['REQUEST_URI'],'?');	//抓出飯店商預設帳號
	if(substr_count($tmpV,'&index') || !substr_count($tmpV,'&')) $ISindex=1;	// 首頁標記
	if(strpos($tmpV,'&')) $cUrl = substr($tmpV,1,strpos($tmpV,'&')-1); else $cUrl = substr($tmpV,1);

	$Main_Banner = 'img/hotel_login_banner.jpg';
	// 不分登入或有選取飯店獨立秀出該功能頁
	array_push($CutoutUrl,'hotel_login','join','join_apply');
	if(!in_array($cUrl,$CutoutUrl))
	{
		if(!$rs = $db->Execute("SELECT * FROM `sys_customer` WHERE `cust_id` = '".$cUrl."'")) Fail("資料庫連接失敗");
		if($db->num_rows($rs)==0) errorPage("查無相關部落格，請重新點選");
		$rs_ar = $db->fetch_array($rs);
		$cUrl_sid = $rs_ar["serial_id"];
		$_SESSION["Site_sid"] = $cUrl_sid;
		$cUrl_Name = $rs_ar["cust_name"];
		$list["Intro"] = $rs_ar["cust_intro"];
		$list["Address"] = $rs_ar["cust_address"];
		$list["Tel"] = $rs_ar["cust_tel"];
		$list["Fax"] = $rs_ar["cust_fax"];
		$list["Ordertel"] = $rs_ar["cust_ordertel"];
		if($rs_ar["cheep_onsale"]>0) $list["CheepCost"] = $rs_ar["cheep_onsale"];
		if($rs_ar["cheep_room"]>0 && $rs_ar["cheep_room"] < $rs_ar["cheep_onsale"]) $list["CheepCost"] = $rs_ar["cheep_room"];
		if($rs_ar["pic"])
		{
			$cUrl_pic = unserialize($rs_ar["pic"]);
			$Main_Banner = $ext_file.$cUrl_pic[0][0];
			$list["Sub1_Pict"] = $ext_file.$cUrl_pic[1][0];
			$list["SubBanner_a"] = '"'.$ext_file.$cUrl_pic[1][0].'","'.$ext_file.$cUrl_pic[2][0].'","'.$ext_file.$cUrl_pic[3][0].'"';
			if($ext_file.$cUrl_pic[1][0]) $list["SubBanner_b"] .= '&nbsp;<span style="cursor:pointer;" onmouseover="changeimage(gsimages[0],this.href)">◎</span>&nbsp;';
			if($ext_file.$cUrl_pic[2][0]) $list["SubBanner_b"] .= '&nbsp;<span style="cursor:pointer;" onmouseover="changeimage(gsimages[1],this.href)">◎</span>&nbsp;';
			if($ext_file.$cUrl_pic[3][0]) $list["SubBanner_b"] .= '&nbsp;<span style="cursor:pointer;" onmouseover="changeimage(gsimages[2],this.href)">◎</span>&nbsp;';
		}
		if($rs_ar["cust_facility"])
		{
			$cust_facility = unserialize($rs_ar["cust_facility"]);
			for($x=1;$x<43;$x++)
			{
				if(in_array($x,$cust_facility))	$list["cf$x"] = ''; else $list["cf$x"] = 'display:none;';
			}
		}
		$htmllnk = 'index';
	}
	
	// 抓取資料夾內所有.html
	$func_kword = array('index');
	$d = dir($RealLnk);
	while($entry=$d->read())
	{
		 if(substr_count($entry,".html"))
		 {
			$entry = str_replace(".html","",$entry);
			if(!in_array($entry, $func_kword)) array_push($func_kword,$entry);
		 }
	}
	$d->close();

	foreach ($_GET as $Tak => $Via)
	{
		if (in_array($Tak, $func_kword))			// 訂房者檔案
		{
			if($login_state)
			{
				if($Tak == 'member_login' || $Tak == 'member_register') $htmllnk = 'modify';
				elseif($Tak == 'buyhouse') $htmllnk = 'objects';
				else $htmllnk = $Tak;
			}
			else
			{
				if($Tak == 'member' || $Tak == 'modify' || $Tak == 'modify_password' || $Tak == 'hotel_order') $htmllnk = 'member_login';
				else $htmllnk = $Tak;
			}
		}
		elseif (in_array("_m_".$Tak, $func_kword))	// 有飯店用檔案但無訂房用檔案
		{
			$htmllnk = $Tak;
		}
	}

	// 飯店文章
	$pgif = '?'.$cUrl.'&blog_article';
	$rs=$db->Execute("SELECT * FROM `sys_bulletin` WHERE `cust_sid` = '".$cUrl_sid."' AND `news_typ` = '557f1e4df8b060a6d7af879569fe1e40' ORDER BY `news_time` DESC");
	if(!$rs) Fail("連線資料庫失敗[sys_bulletin] 請稍後重新執行");
	for($x=0;$x<2;$x++)
	{
		if($rs_array = $db->fetch_array($rs))
		{
			if($rs_array["hyplnk"]) $lnkis = '&nbsp;-&nbsp;<a href="http://'.$rs_array["hyplnk"].'" target="_blank">外部連結</a>'; else $lnkis = '';
			if(isset($rs_array["serial_id"])) $t_index_sid = $rs_array["serial_id"];
			if(isset($rs_array["title"])) $t_index_title = $rs_array["title"];
			$list["index_list"] .= '
			<div id="hotel_photo" class="photo4"><img src="img/hotel_photo.jpg" width="180" height="140" alt="hotel_photo"></div>
			<span class="style1"><a href="javascript:void(0)" onClick="javascript:window.open(\'./'.$pgif.'&id='.$rs_array["serial_id"].'\',\'_self\')" title="發佈時間:&nbsp;'.$rs_array["news_code"].'">'.$t_index_title.'&nbsp;'.$lnkis.'</a></span><br />
			'.cut_content($rs_array["content"],200).'
			<hr />';
		}
	}
	
/*	if(isset($_GET[$func_kword[0]]) && $login_state) $ErrorMesg = '目前狀態為登入中.';
	elseif(isset($_GET[$func_kword[2]]) && !$login_state && $_GET["message"]=='new') $ErrorMesg = '該服務需登入使用.';
	elseif(isset($_GET[$func_kword[3]]) && !$login_state) $ErrorMesg = '該服務需登入使用.';
	elseif(isset($_GET[$func_kword[4]]) && !$login_state) $ErrorMesg = '該服務需登入使用.';
	elseif(isset($_GET[$func_kword[5]]) && !$login_state) $ErrorMesg = '該服務需登入使用.';
	if($ErrorMesg){ html_start();AlertJump($ErrorMesg,'./?userlogin'); }
*/
}

//if(!$htmllnk)$htmllnk = 'index';

// Login or logout url will be needed depending on current user state.
if ($login_state || $FBuser) // 登入中
{
  if(!$user_profile["name"])$user_profile["name"] = userinfo($_SESSION["login_id"])->name;
  if(userinfo($_SESSION["login_id"])->serviceis){ $membertype = '服務商';$memberISservice=1; }else $membertype = '會員';
  $user_profile["mtype"] = $membertype;
  $Hrefout = 'funcs.php?act=logout';
  
  if($FBuser)
  {
     $logoutUrl = $facebook->getLogoutUrl();
	 $Hrefout = 'javascript:fb_logout()';
	 $user_profile["Imgurl"]='<img src="https://graph.facebook.com/'.$FBuser.'/picture">';
  }
  $logtable = '<a href="?'.$cUrl.'&modify">'.$user_profile["name"].' &nbsp;'.$membertype.'</a>　您好!歡迎您的光臨!　|　<span id="fb-root"></span><a href="'.$Hrefout.'">登出</a>　|　<a href="?'.$cUrl.'">回首頁</a>　';
}
else
{ // 未登入
  $loginUrl = $facebook->getLoginUrl(array("scope" => "email,user_events,user_birthday,user_notes,user_about_me,user_photos,sms"));
  $logtable = '<a href="?'.$cUrl.'&member_login">登入</a>　|　<fb:login-button></fb:login-button><span id="fb-root"></span>　|　<a href="?'.$cUrl.'&member_register">註冊</a>　|　<a href="?'.$cUrl.'">回首頁</a>　';
  $_SESSION["fbloginurl"] = $loginUrl;
}

// 處理有FB登入卻沒有系統紀錄
if($_SESSION["fb_$FBarray[appId]_user_id"])
{
	$fb_uid = $_SESSION["fb_$FBarray[appId]_user_id"];
	$FBemail = $user_profile["email"];
	$FBname = $user_profile["name"];
	$FBwork = $user_profile["work"][0]["employer"]["name"];
	//echo "select * from `sys_members` where `account`='{$FBemail}'";exit;
	if(!$rs=$db->Execute("SELECT * FROM `sys_members` WHERE `account`='{$FBemail}'")) Fail("SQL通訊錯誤!");
	if($db->num_rows($rs))	// 如果FB信箱已存在
	{
		$row = $db->fetch_assoc($rs);
		$serial_id = $row["serial_id"];
		if($row["fbid"]!=$fb_uid)	// 判斷是否為FB帳號
		{
			// 進行綁定
			echo'<script language="JavaScript" charset="utf-8">alert("系統已自動綁定您所使用的 FB 帳號..");</script>';
			if(!$db->Execute("UPDATE `sys_members` SET `fbid` = '{$fb_uid}', `name` = '{$FBname}', `email` = '{$FBemail}', `job` = '{$FBwork}' WHERE `serial_id` = '{$serial_id}'")) Fail("新增失敗! 請重新檢查或聯繫管理員");
		}
	}
	else
	{
		$serial_id = md5(uniqid(rand(),true));
		if(!$db->Execute("INSERT INTO `sys_members` (`serial_id`, `account`, `password`, `fbid`, `name`, `email`, `job`, `create_time`) VALUES ('{$serial_id}', '{$FBemail}', '{$fb_uid}', '{$fb_uid}', '{$FBname}', '{$FBemail}', '{$FBjob}', NOW())")) Fail("新增失敗! 請重新檢查或聯繫管理員");
	}
	$_SESSION["login_id"] = $serial_id;
}
	
/*// 判斷是否已為服務商
if($_SESSION["login_id"])
{
	if (!$rsSS = $db->Execute("SELECT * FROM `sys_members` WHERE `serial_id`='".$_SESSION["login_id"]."'")) die('資料庫發生錯誤');
	$rowServiceIS = $db->fetch_object($rsSS);
	if($rowServiceIS->serviceis) $SSSIS = 1;
}
*/

// 處理首頁功能
if($ISindex) require_once('global_php/module_onsale.php');

// 載入模組:飯店登入狀態中 那些檔案不需被帶入_m_
array_push($CutoutUrl,'reservation_step1-1','reservation_step1-2','reservation_step2','reservation_step3','forums','member_register','join','member');
if(!$htmllnk) $htmllnk = 'index_global';
else
{
	//if($htmllnk != 'index')
	if($CustUsed && !in_array($htmllnk,$CutoutUrl)) $htmllnk = '_m_'.$htmllnk;
}
if(file_exists('include/'.$htmllnk.'.php')) require_once('include/'.$htmllnk.'.php');

// 設計公用頁面 ( global_html )
$d = dir($RealLnk.$ext_html);
while($entry=$d->read())
{
	if($entry!='.' && $entry !='..')
	{
		$entryH = substr($entry,0,strpos($entry,'.'));
		ob_start();
		include $ext_html.'/'.$entry;
		$include = ob_get_contents();
		ob_end_clean();
		$Html["$entryH"] = $include;
	}
}
$d->close();
// ---------------------------------------- XTemplate ---------------------------------------------------
$xtpl = new XTemplate($htmllnk.'.html');
$refurl = $_SERVER['HTTP_REFERER'];
$xtpl->assign('refurl',$refurl);
$xtpl->assign('FBloginURL',$loginUrl);
$xtpl->assign('FBlogState',$logtable);
$xtpl->assign('FB_profile',$user_profile);
$xtpl->assign('content_list', $list);
$xtpl->assign('HTML', $Html);
$xtpl->assign('cUrl', $cUrl);

switch($Tab_assign)	// 後臺文章
{
	case "_m_blog_list":
	$xtpl->assign('add',$edit);
	$xtpl->assign('name',$_SESSION['session_name']);
	$xtpl->assign('type_listid',$_GET["type"]);
	$xtpl->assign('type_list',$typlist);
	$xtpl->assign('authority_list',$list_blog);
	$xtpl->assign('authority_pg',$list_pg);
	$xtpl->assign('content_list',$view);
	$xtpl->assign('RecCount',$Countrs);
	$xtpl->assign('input_EditID',$input_EditID);
	if($view) $xtpl->parse('main.table.editarea');
	$xtpl->parse('main.table');
	break;
	
	default:
	break;	
}

if($SHOW_main_message)
{
	$xtpl->assign('content_msg', $msg);
	if($login_state || $CustUsed) $xtpl->parse('main.message.reMsg');
	$xtpl->parse('main.message');
}
else $xtpl->parse('main.msg_list');

if($SSSIS) $xtpl->parse('main.tableS'); else $xtpl->parse('main.tableC');

if($ISONSALE && $arr_list)
foreach($arr_list as $val)
{
	$xtpl -> assign('content_circle', $val);
	$xtpl -> parse('main.Circle');
}

if(!$login_state) $xtpl -> parse('main.LoginTable'); else $xtpl -> parse('main.LogonTable');

$xtpl->parse('main');
$xtpl->out('main');
?>