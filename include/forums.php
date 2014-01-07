<?php
if(isset($_GET["message"]) && $_GET["message"] =='new')
{
	$htmllnk = 'forums_newpo';
	if(!$_SESSION["login_id"] && !$_SESSION["cust_sid"]) Fail("請先登入會員");
	if($_SESSION["cust_sid"])
		$list["Author"] = $_SESSION["cust_sid"];
	else
		$list["Author"] = $_SESSION["login_id"];
}
else
{
	// 以 User_id 帶出使用這資訊
	if(!$rsUser = $db->Execute("SELECT * FROM `sys_members`")) Fail("連線資料庫失敗 請稍後重新執行");
	for($i=0;$i<$db->num_rows($rsUser);$i++)
	{
		$rs_array_User = $db->fetch_array($rsUser);
		foreach ($rs_array_User as $k => $v) $UserInfo_Detail[$k]=$v;
		$UserInfo[$UserInfo_Detail["serial_id"]] = $UserInfo_Detail;
	}
	if(!$rsUser = $db->Execute("SELECT `serial_id`,`cust_name` FROM `sys_customer`")) Fail("連線資料庫失敗 請稍後重新執行");
	// 加入飯店的sid跟NAME
	while ($rowsDist=$db->fetch_assoc($rsUser)) $UserInfo[$rowsDist['serial_id']] = array("serial_id" => $rowsDist['serial_id'], "name" => $rowsDist['cust_name']);
	
	if(!isset($_GET["page"])) $nPage = 1; else $nPage = $_GET["page"];
	$seach_sql = "WHERE `cust_sid` = '".$cUrl_sid."'";
	if(isset($_GET["seach_kword"]) && $_GET["seach_kword"] != '') $seach_sql .= "AND `title` LIKE '%".$_GET["seach_kword"]."%' ";
	if(!$rs = $db->Execute("SELECT * FROM `msgboard` ".$seach_sql."ORDER BY `msgdate` DESC")) Fail("連線資料庫失敗 請稍後重新執行");
	$RecCount = $db->num_rows($rs);
	if (($RecCount % 10)==0) $TotalPage = intval($RecCount/10); else $TotalPage = intval($RecCount/10) + 1;
	$nRow = ($nPage-1)*10;
	if($RecCount) $db->data_seek($rs, $nRow);
	
	// 單一列留言
	if($RecCount>=11)$tpp_act=11;elseif($RecCount==0) $list["area1"] .= '<tr><td class="style2" align="center">查無任何留言資料&nbsp;<span style="cursor:pointer;" onClick="javascript:window.open(\'./?forums&message=new\',\'_self\')" /></span></td></tr>'; else $tpp_act=$RecCount;
	for($j=0;$j<$tpp_act;$j++)
	{
		if($rs_array = $db->fetch_array($rs))
		{
			if(isset($_GET["message"])&&$_GET["message"]==$rs_array["serial_id"]) $normal_style='link2'; else $normal_style='link1';
			$rs_array_title = $rs_array["title"];
			$rs_array_name = $UserInfo[$rs_array["author"]]["name"];
			$rs_array_msgdate = $rs_array["msgdate"];
			$rs_array_anscount = unserialize($rs_array["answer"]);
			if(is_array($rs_array_anscount))$rs_array_anscount = count($rs_array_anscount); else $rs_array_anscount = 0;
			
			if(isset($_GET["seach_kword"]) && $_GET["seach_kword"] != '') $lnkadd = '&seach_kword='.$_GET["seach_kword"];
			if(isset($_GET["page"]) && $_GET["page"] != '') $lnkadd .= '&page='.$_GET["page"];
			
			$list["area1"] .= '
			<tr onmouseover="OMOver(this);" onmouseout="OMOut(this);">
			  <td colspan="5" height="25">
			  	<span style="float:left;width:25px; vertical-align:top;">&nbsp;&nbsp;<img src="images/f15.gif" width="9" height="9" border="0";></span>
			  	<span style="float:left;width:425px;" class="'.$normal_style.'"><a href="./?'.$cUrl.'&forums&message='.$rs_array["serial_id"].$lnkadd.'""><span class="'.$normal_style.'">'.$rs_array_title.'</span></a></span>
				<span style="float:left;width:100px;" class="'.$normal_style.'">'.$rs_array_name.'</span>
				<span style="float:left;width:100px;" class="'.$normal_style.'">'.substr($rs_array_msgdate,0,10).'</span>
				<span style="float:left;width:10px;" class="'.$normal_style.'">'.$rs_array_anscount.'</span>
			  </td>
			</tr>
			<tr>
			  <td colspan="5" height="1" bgcolor="#CCCCCC"></td>
			</tr>';
			
			//$nPage						//目前筆數
			$allPage=$TotalPage;			//總筆數
			
			$Target = $nPage % 10;			//0
			$Targeta = floor($nPage / 10);	//1
			if($Target=='0')$Targeta-=1;	//1->0
			$Targetb = $Targeta * 10 +1;	//11
			$Targetc = ($Targeta+1) * 10;	//20
			if($Targetc>=$allPage)$Targetc=$allPage;	//78
			$scriptpage='?'.$cUrl.'&forums&page=';
			if($nPage<='10') $list["area2"] = 'style="display:none"';
			$list["area2_url"] = $scriptpage.$Targetb-1;
			if($nPage<='1') $list["area3"] = 'style="display:none"';
			$list["area3_url"] = $scriptpage.$nPage-1;
			for($x=$Targetb;$x<=$Targetc;$x++){
				if($x==$nPage) $list["area4"] = '&nbsp;<a href="'.$scriptpage.$x.'" class="style12"><u><b>'.$x.'</b></u></a>';
				else  $list["area4"] = '&nbsp;<a href="'.$scriptpage.$x.'"><span class="style16">'.$x.'</span></a>';
			}
			if($nPage>=$allPage) $list["area5"] = 'style="display:none"';
			$list["area5_url"] = $scriptpage.$nPage+1;
			if($Targetc>=$allPage) $list["area6"] = 'style="display:none"';
			$list["area6_url"] = $scriptpage.$Targetc+1;
			for($x=$Targetb;$x<=$Targetc;$x++){$list["area7"] = '<option value="'.$scriptpage.$x.'">'.$x.'</option>';}
		}
	} 

	if(isset($_GET["message"]))
	{
		if(!$rs_ac = $db->Execute("SELECT * FROM `msgboard` WHERE `serial_id` = '".$_GET["message"]."'")) Fail("連線資料庫失敗 請稍後重新執行");
		$rs_array_ac = $db->fetch_array($rs_ac);
		$rs_array_ac_author = $rs_array_ac["author"];
		$rs_array_ac_title = $rs_array_ac["title"];
		$rs_array_ac_question = $rs_array_ac["question"];
		$rs_array_ac_answer = $rs_array_ac["answer"];
		if(!empty($rs_array_ac_title))
		{
			$SHOW_main_message=1;
			$msg["txt_id"] = $_GET["message"];
			$msg["title"] = $rs_array_ac_title;
			$msg["ac_name"] = $UserInfo[$rs_array_ac_author]["name"];
			$msg["ac_question"] = nl2br($rs_array_ac_question);
			$msg["msgdate"] = $rs_array_ac["msgdate"];
			
			if($rs_array_ac_answer)
			{
				$ans_array = unserialize($rs_array_ac_answer);	// $msg["ac_answer"]
				if(is_array($ans_array))
				foreach ($ans_array as $k => $v)
				{
					$msg["ac_answer"] .= '<div style="padding:7px;">第&nbsp;'.($k+1).'&nbsp;樓：';
					foreach ($v as $l => $m)
					{
						if($l==1)$v[1]=$UserInfo[$m]["name"];
						//echo "$l = $m<br />";
					}
					$msg["ac_answer"] .= '<span style="letter-spacing: 1.5pt;line-height:180%">'.$v[1].'<br />'.$v[2].'</span><br><div align="right" style="border-bottom:1px #999999 dashed;padding:7px;">回覆日期：'.$v[0].'</div></div>';
				}
			}else $msg["ac_answer"] = '目前尚未加入任何討論';
			$db->Execute("UPDATE `msgboard` SET `view_count` = `view_count`+1 WHERE `serial_id` = '".$_GET["message"]."'");
		}
	}
}
if($SHOW_main_message) $SHOW_main_msglist = 0; else $SHOW_main_msglist = 1;
?>