<?php
if(!$rs = $db->Execute("SELECT * FROM `sys_customer` WHERE `serial_id` = '".$CustUsed."'")) Fail("資料庫連接失敗");
$rs_ar = $db->fetch_array($rs);
$cust_id = $rs_ar["cust_id"];
foreach ($rs_ar as $k => $v) $list[$k] = $v;

function showPIC($picX)
{
	global $cust_id,$ext_file;
	$pic_file = $ext_file.$picX[1];
	if(filesize($pic_file))
	{
		return '<a href="'.$ext_file.$picX[0].'" rel="lightbox['.$cust_id.']"><img src="'.$pic_file.'" border="0"></a>';
	}
	unset($pic_file);
}

if($list["pic"])
{
	$pic_ar = unserialize($list["pic"]);
	//foreach ($pic_ar as $k => $v) echo "$k=$v<br />";
	$list["pic1_show"] = showPIC($pic_ar[0]);
	$list["pic2_show"] = showPIC($pic_ar[1]);
	$list["pic3_show"] = showPIC($pic_ar[2]);
	$list["pic4_show"] = showPIC($pic_ar[3]);
	$list["pic1"] = htmlentities(serialize($pic_ar[0]));
	$list["pic2"] = htmlentities(serialize($pic_ar[1]));
	$list["pic3"] = htmlentities(serialize($pic_ar[2]));
	$list["pic4"] = htmlentities(serialize($pic_ar[3]));
}

if($list["cust_facility"])
{
	$cust_facility = unserialize($list["cust_facility"]);
	for($x=1;$x<43;$x++)
	{
		if(in_array($x,$cust_facility))	$list["cf$x"] = ' checked="checked"'; else $list["cf$x"] = '';
	}
}
?>