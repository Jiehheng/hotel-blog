<?php
// 載入模組(房型專案列表)
require_once('global_php/module_onsale.php');

if(!$rs = $db->Execute("SELECT * FROM `sys_room` WHERE `cust_sid` = '".$cUrl_sid."' ORDER BY `type`,`auto_add`")) Fail("資料庫連接失敗");
while ($rows=$db->fetch_assoc($rs))
{
	$detail = unserialize($rows["detail"]);
	if($detail[6]) $sela='有'; else $sela='無';
	$list["area1"] .= '<li class="TabbedPanelsTab" tabindex="0" onfocus="if(this.blur)this.blur()">'.$rows["subject"].'</li>';
	$list["area2"] .= '<div class="TabbedPanelsContent">
	定　　價：&nbsp;'.$detail[1].'&nbsp;元<br />
	參考價格：&nbsp;'.$detail[2].'&nbsp;元起<br />
	總房間數：&nbsp;'.$detail[3].'&nbsp;間<br />
	住房基本人數：&nbsp;'.$detail[4].'&nbsp;人<br />
	床　　型：&nbsp;'.$detail[5].'<br />
	房間坪數：約&nbsp;'.$detail[0].'&nbsp;坪<br />
	加床服務：&nbsp;'.($detail[6]==1?'有':'無').'&nbsp;<br />
	加人服務：&nbsp;'.($detail[7]==1?'有':'無').'&nbsp;<br />
	地　　板：&nbsp;'.$detail[9].'&nbsp;<br />
	浴　　室：&nbsp;'.$detail[10].'&nbsp;<br />
	窗　　戶：&nbsp;'.($detail[8]==1?'有':'無').'&nbsp;
	<div style="border:#CCCCCC 1px dashed;padding:5px;">'.$rows['content'].'</div>
	</div>';
}
?>