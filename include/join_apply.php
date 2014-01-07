<?php
// 地區
$rs=$db->Execute("select * FROM `sys_depart_mb` order by `sort` asc");
if ($rs)
{
	while ($rows=$db->fetch_assoc($rs))
	{
		$list["area"] .= '<option value="'.$rows['serial_id'].'">'.$rows['caption'].'</option>'."\n  ";
	}
}else
	die('資料庫發生錯誤');
?>