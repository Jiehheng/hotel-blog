<?php
$tmpV = strchr($_SERVER['REQUEST_URI'],'?');	//抓出飯店商預設帳號
if(strpos($tmpV,'&')) $cUrl = substr($tmpV,1,strpos($tmpV,'&')-1); else $cUrl = substr($tmpV,1);
?>
	<div class="left_box">
		<a href="?<?=$cUrl?>&onsale" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('促銷活動','','img/button/lbt1_1t.gif',1)"><img src="img/button/lbt1_1.gif" alt="促銷活動" name="促銷活動" width="218" height="39" border="0"></a>
		<a href="?<?=$cUrl?>&room" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('房型介紹','','img/button/lbt1_3t.gif',1)"><img src="img/button/lbt1_3.gif" alt="房型介紹" name="房型介紹" width="218" height="39" border="0"></a>
		<?php if(!$_SESSION["cust_sid"]){?>
		<a href="?<?=$cUrl?>&reservation_step1-1" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('線上訂房','','img/button/lbt1_2t.gif',1)"><img src="img/button/lbt1_2.gif" alt="線上訂房" name="線上訂房" width="218" height="39" border="0"></a>
		<?php }elseif($CustUsed){?>
		<a href="?<?=$cUrl?>&roomctrl" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('控房管理','','img/button/lbt1_10t.gif',1)"><img src="img/button/lbt1_10.gif" alt="控房管理" name="控房管理" width="218" height="39" border="0"></a>
    	<div class="CollapsiblePanelContent"><a href="?<?=$cUrl?>&amp;roomctrl_group">批次控房</a></div>
		<?php }?>
		<a href="?<?=$cUrl?>&plant" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('飯店設施&餐廳介紹','','img/button/lbt1_4t.gif',1)"><img src="img/button/lbt1_4.gif" alt="飯店設施&餐廳介紹" name="飯店設施&餐廳介紹" width="218" height="39" border="0"></a>
		<a href="?<?=$cUrl?>&traffic" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('交通指南','','img/button/lbt1_5t.gif',1)"><img src="img/button/lbt1_5.gif" alt="交通指南" name="交通指南" width="218" height="39" border="0"></a>
		<a href="?<?=$cUrl?>&views" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('鄰近景點','','img/button/lbt1_6t.gif',1)"><img src="img/button/lbt1_6.gif" alt="鄰近景點" name="鄰近景點" width="218" height="39" border="0"></a>
		<a href="?<?=$cUrl?>&orderknow" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('訂房需知','','img/button/lbt1_7t.gif',1)"><img src="img/button/lbt1_7.gif" alt="訂房需知" name="訂房需知" width="218" height="39" border="0"></a>
		<a href="?<?=$cUrl?>&help" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('新手上路','','img/button/lbt1_8t.gif',1)"><img src="img/button/lbt1_8.gif" alt="新手上路" name="新手上路" width="218" height="38" border="0"></a>
	</div>
<div class="left_box">
	  <div class="left_bt2">
		<a href="?<?=$cUrl?>&album_list" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image10','','img/button/lbt2_1t.gif',1)"><img src="img/button/lbt2_1.gif" alt="相簿" name="Image10" width="218" height="38" border="0"></a>
	  </div>
	  <div class="left_bt2">
		<a href="?<?=$cUrl?>&forums" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image11','','img/button/lbt2_2t.gif',1)"><img src="img/button/lbt2_2.gif" alt="留言" name="Image11" width="218" height="38" border="0"></a></div>
  <div class="left_bt2">
    <div id="CollapsiblePanel2" class="CollapsiblePanel">
      <div class="CollapsiblePanelTab" tabindex="0"onfocus="if(this.blur)this.blur()"><a href="?<?=$cUrl?>&amp;blog_list" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image12','','img/button/lbt2_3t.gif',1)"><img src="img/button/lbt2_3.gif" alt="文章&amp;最新活動" name="Image12" width="218" height="38" border="0" id="Image12" /></a></div>
	  <?php if(!$_SESSION["cust_sid"]){ ?>
      <div class="CollapsiblePanelContent">
		<a href="?<?=$cUrl?>&amp;blog_list">全部文章</a>
		<?php
		  if(!$rs_type = $db->Execute("SELECT `serial_id`,`caption` FROM `sys_bulletin_type`")) Fail("連線資料庫失敗 請稍後重新執行");
		  if($db->num_rows($rs_type))
		  {
		  	while ($rowsType=$db->fetch_assoc($rs_type)) echo '<br /><a href="?'.$cUrl.'&amp;blog_list&amp;type='.$rowsType["serial_id"].'">'.$rowsType["caption"].'</a>';
		  }
		?>
	  </div>
	  <?php }?>
    </div>
  </div>
  <a href="?<?=$cUrl?>&index" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image13','','img/button/lbt2_4t.gif',1)"><img src="img/button/lbt2_4.gif" alt="回首頁" name="Image13" width="218" height="38" border="0"></a>
  </div>
	<div class="left_box">
	<img src="img/button/lbt3_1.gif" alt="飯店評鑑" width="219" height="36">
	<div class="left_bt3">
	你覺得飯店好嗎？<br>
	<img src="img/button/fb.jpg" alt="fb讚" width="65" height="36">
	</div>
	</div>
