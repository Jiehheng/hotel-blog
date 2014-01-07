<?php
if(isset($_GET["login_id"]))
{
	$result = $db->Execute("SELECT * FROM `sys_members` WHERE `account` = '".$_GET["login_id"]."'");
	$rows = @$db->num_rows($result);
	if($rows){ echo '1'; }else{ echo '0'; }
	exit;
}

for($y=(date(Y)-100);$y<(date(Y)-10);$y++)
{
	$list["birthday_Year"] .= '<option value="'.$y.'">'.$y.'</option>';
}
for($m=1;$m<=12;$m++)
{
	$list["birthday_Month"] .= '<option value="'.$m.'">'.$m.'</option>';
	unset($sel);
}
for($d=1;$d<=31;$d++)
{
	$list["birthday_Day"] .= '<option value="'.$d.'">'.$d.'</option>';
	unset($sel);
}
?>