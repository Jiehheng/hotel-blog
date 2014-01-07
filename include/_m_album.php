<?php
if(isset($_GET["id"]))
{
	$func_type = $_GET["id"];
	if(!$rsNews = mysql_query("SELECT * FROM `blog_action` WHERE `actioncode` ='".$func_type."'")) Fail("連線資料庫失敗 請稍後重新執行");
	if(mysql_num_rows($rsNews) == '0') $newscode = date(Ymd).'01';	//如果沒偵測到就創新的
	else{ $rs_array = mysql_fetch_array($rsNews);$rs_array_title = $rs_array["title"];}
	if (count($_FILES)) {
		// Handle degraded form uploads here.  Degraded form uploads are POSTed to index.php.  SWFUpload uploads
		// are POSTed to upload.php
	}
	$list["session_id"] = session_id();
	if(mysql_num_rows($rsNews)) $list["Newsid"] = '"'.Newsid.'" : "'.$func_type.'"'; else $list["Newsid"] = '"'.newNews.'" : "'.$newscode.'"';
	$list["id"] = $func_type;
	if(mysql_num_rows($rsNews)) $list["Newsid_input"] = '<input type="hidden" id="Newsid" name="Newsid" value="'.$func_type.'">';else $list["Newsid_input"] = '<input type="hidden" id="newNews" name="newNews" value="'.$newscode.'">';
	if(isset($rs_array_title)) $list["val_1"] = 'value="'.$rs_array_title.'" ';
	if(mysql_num_rows($rsNews)) $list["val_2"] = '<input type="button" value="刪除" onClick="return xoopsFormDeltext_active();">';
	
	if(isset($rs_array["content"])){
		$thedate = $rs_array["content"];
		//$list["list"] .= strip_tags(stripslashes($thedate),$allowedTags);
		$list["val_3"] = trim(stripslashes($thedate));
		//$list["list"] .= htmlspecialchars($thedate);
	}							
	if($_POST["act"] == "up" or $_POST["act"] == "down"){
		$fileDel_name = $_POST["action_delpicid"];
		//暫存所有已改數值
		if ($_POST['all_view'] != null){
			$f_pic=explode("###@@@###",$_POST['all_view']);
			for($i=0;$i<count($f_pic);$i++) {
				$word1 = $_POST['img_url_'.$i];						//實圖路徑
				$word2 = $_POST['img_title_'.$i];					//實圖標題

				$word3 = substr($word1,0,strpos($word1,"."))."a".strrchr($word1,".");
				$word4 = $_POST['img_somen_'.$i];					//實圖說明
				$word = $word1.'|||@@@|||'.$word2.'|||@@@|||'.$word3.'|||@@@|||'.$word4;
				if ($_POST["C".$i] == '1'){if (!isset($all_view)) $all_view = "-ns)".$word; else $all_view = $all_view."###@@@###-ns)".$word;
				}else{if (!isset($all_view)) $all_view = $word; else $all_view = $all_view."###@@@###".$word;}
				if (substr_count($f_pic[$i],$fileDel_name)!=0){	$age=$i; }
			}
		}
		$f_pic="";
		$f_pic=explode("###@@@###",$all_view);
		for($k=0;$k<count($f_pic);$k++) {
			if(isset($g_pic))$g_pic.='###@@@###';
			if($_POST["act"] == "up"){
				if($k==$age-1){$g_pic.=$f_pic[$age]; }elseif($k==$age){$g_pic.=$f_pic[$age-1]; }else{$g_pic.=$f_pic[$k];}	//向前交換
			}else{
				if($k==$age){$g_pic.=$f_pic[($age+1)]; }elseif($k==$age+1){$g_pic.=$f_pic[$age]; }else{$g_pic.=$f_pic[$k];}	//向後交換
			}
		}
		$do_pic = $g_pic;
	}
	else
	{
		$do_pic = $rs_array['a_pic'];
	}
	$list["do_pic"] = $do_pic;
	
	if(isset($do_pic) && ($do_pic!='')){
		//$do_pic=$do_pic;
		$f_pic=explode("###@@@###",$do_pic);
		for($j=0;$j<count($f_pic);$j++){
			$f_pic[$j] = trim($f_pic[$j]); 
			$j_count = $j+1;
			$ic = "$f_pic[$j]";
			if (substr($ic,0,4)=='-ns)')$f_showornot_value[$j]='checked';
			$ic = eregi_replace("-ns)","","$ic");
			$iku = explode("|||@@@|||",$ic);
			$aImageInfo = getimagesize("upload_image/".$iku[0]);
			$list["list"] .= '<tr><td width="25" align="center" valign="top" class="style2" style="text-align:center; vertical-align:top">圖<br />'.$j_count.'</td><td><a href="javascript: ImageWindow(\''.$iku[0].'\','.$aImageInfo[0].','.$aImageInfo[1].')"><img name="imgUpload1" id="ImgUpload1" src="./smallpic.php?pic='.$iku[0].'" align="top" border="0"></a><br /><hr />
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				  <td width="56" class="style2">照片名稱</td>
				  <td><input name="img_title_'.$j.'" type="text" size="65" maxlength="100" value="'.$iku[1].'" />
				  <input name="img_url_'.$j.'" type="hidden" value="'.$iku[0].'" /></td>
				</tr>
				<tr>
				  <td width="56" class="style2">照片描述</td>
				  <td><textarea name="img_somen_'.$j.'" cols="40" rows="3">'.$iku[3].'</textarea></td>
				</tr>
			  </table></td>
			<td align="center" width="40" class="style2"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td height="*%" style="text-align:center; vertical-align:top">';
				if ($j != '0'){$list["list"] .= '<input type="button" value="上移" onclick="return moveFormPicState(\'up\',\''.$iku[0].'\');" style="font-size: 8pt"><br />';}
				if ($j != count($f_pic)-1){$list["list"] .= '<input type="button" value="下移" onclick="return moveFormPicState(\'down\',\''.$iku[0].'\');" style="font-size: 8pt"><br />';}
				$list["list"] .= '<input type="submit" value="刪除" name="action_delpic" onclick="return xoopsFormDelpic_active('; $list["list"] .= "'".$iku[0]."'"; $list["list"] .= ');" style="font-size: 8pt">
				</td>
			  </tr>
			  <tr>
				<td height="40" align="center" class="style2" style="text-align:center; vertical-align:top">隱藏<br /><input type="checkbox" name="C'.$j.'" value="1" '.$f_showornot_value[$j].'></td>
			  </tr>
			</table></td></tr>';
		}
	}else{
		$list["list"] .= '<tr><td colspan="3"><img name="imgUpload1" id="ImgUpload1" src="images/no-product.gif" align="top"></td></tr>';
	}
	
	$list["replace"] = $_SERVER["REQUEST_URI"];
}
else Fail("查無對應ID");
?>