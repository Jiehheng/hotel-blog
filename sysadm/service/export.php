<?php
include_once('../../config/db_action.php');
include_once('../../lib/function.php');
$rows = array();
session_start();
$db=new db_action;

// Function -------------------------------------------------------------------------
function unGetCustID($custID)
{
	$rs = mysql_query("SELECT id FROM customer WHERE serial_id = '".$custID."'");//
	if(mysql_num_rows($rs)){
		$val_array = mysql_fetch_array($rs);
		return $val_array[0];
	}else return 'NULL';
}
// Function -------------------------------------------------------------------------

$Hour_count = "<input class='lvtCol2' LANGUAGE=javascript onclick='return window.open(\"Hour_count.php?cal_number=".$_POST['cal_number']."&ser_type=".$_POST['ser_type']."\",\"test\",\"width=800,height=400,resizable=no,scrollbars=1, toolbar=no, titlebar=no, left=200, top=226, screenX=100, screenY=126\");' type='button' value='維護時數統計' style='font-family: Arial'>";
if($_POST['cal_number'] == '') // 下拉是挑選案件
	$WHERE = "";
else
	$WHERE = " AND service_record.cal_number = '{$_POST['cal_number']}'";
if($_POST['ser_type'] != '') // 下拉是挑選問題種類
{
	$WHERE .= " AND service_record.ser_type = '{$_POST['ser_type']}'";
}
if($_POST['ser_status'] != '') // 下拉是挑選狀態種類
{
	switch($_POST['ser_status'])
	{
		case "未處理":
			$WHERE .= " AND service_record.ser_status = '{$_POST['ser_status']}'";
			break;
		case "已處理":
			$WHERE .= " AND service_record.ser_status = '{$_POST['ser_status']}'";
			break;
	}
}else{
	$WHERE .= " AND service_record.ser_status = '未處理'";
}

//// 讀取案件  Start ////
$result = $db->Execute("SELECT case_list.caption,case_list.id FROM service_record,case_list WHERE service_record.cal_number = case_list.id GROUP BY service_record.cal_number ORDER BY service_record.cal_number DESC"); //SQL
while($rows = $db->fetch_assoc($result))
{
	if($_POST['cal_number'] == $rows['cal_number'])
		$selected = "selected";
	else
		$selected = null;

	$CaseList .= "<option value=".$rows['cal_number']." ".$selected.">".$rows['cal_number']."_".$rows['cal_name']."</option>";
}
//// 讀取案件  End ////

$rows = array();
//$PagesSQL = "SELECT service_record.*,case_list.caption,case_list.id
//		FROM service_record,case_list
//		WHERE service_record.cal_number = case_list.id
//		{$WHERE}
//		ORDER BY ser_id DESC";
$PagesSQL = "SELECT service_record.*,website.customer,website.caption FROM service_record,website WHERE service_record.cal_number = website.serial_id{$WHERE} ORDER BY ser_id DESC";
$result = $db->Execute($PagesSQL);
$field_num = mysql_num_fields($result); // 共有多少欄位
$a = 1;
$content = '"執行廠商編號 / 網站名稱","問題","回覆","問題分類","狀況","最後回覆","寄信狀況","維護時數"'."\n";
$export=intval($_POST['export1']);
while($row = $db->fetch_assoc($result))
{
	$field_name = array();
	$field_var = array();
	$combine_var = array();
	for($k=0;$k<$field_num;$k++)
	{
		array_push($field_name,mysql_field_name($result, $k));
		array_push($field_var,$row[mysql_field_name($result, $k)]);
		$combine_var = array_combine($field_name,$field_var);
		$rows[$a]=$combine_var;
	}
	$a++;
} // while($row = fetch_assoc($result)) end
$rowsize = count($rows); // count times
for ($i=1; $i<=$rowsize; $i++)
{
	$rows[$i]['cal_number'] = unGetCustID($rows[$i]['customer']);
	$rows[$i]['ser_question_all']=$rows[$i]['ser_question'];
	$rows[$i]['ser_question'] = substr($rows[$i]['ser_question'],0,30)."...";
	if($rows[$i]['ser_respond'] != '')
	{
		$rows[$i]['ser_respond_all'] =$rows[$i]['ser_respond'];
		$rows[$i]['ser_respond'] = substr($rows[$i]['ser_respond'],0,50)."...";
	}
	if($rows[$i]['accid'])
	{
		$FindResult = $db->Execute("SELECT * FROM account WHERE serial_id = '". $rows[$i]['accid']."'");
		$object = mysql_fetch_object($FindResult);
		$rows[$i]['acctwname'] = $object -> name."(".$object -> account.")";
	}else{
		$rows[$i]['acctwname'] = 'null';
	}
	if($rows[$i]['ser_respondtime'] == '0000-00-00 00:00:00')
		$rows[$i]['time'] = '00:00:00';
	else
		$rows[$i]['time'] = date('h:i:s', (strtotime($rows[$i]['ser_respondtime']) - strtotime($rows[$i]['ser_datetime']))/60); // 解決時間
	if($rows[$i]['ser_respond'] == "")
	{
		$rows[$i]['ser_respond'] = '目前尚無回覆說明';
		$rows[$i]['ser_respond_all'] = '目前尚無回覆說明';
	}else{
		$rows[$i]['ser_respond'] = nl2br($rows[$i]['ser_respond']);
		$rows[$i]['ser_respond_all'] = nl2br($rows[$i]['ser_respond_all']);
	}
	switch($rows[$i]['ser_status'])
	{
		case "未處理":
			$rows[$i]['ser_status'] = "未處理";
			break;
		case "已處理":
			$rows[$i]['ser_status'] = "已處理";
			break;
	}
	
	$rows[$i]['ser_question'] = nl2br($rows[$i]['ser_question']);
	$content .= '"'.$rows[$i]['cal_number'].'_'.$rows[$i]['caption'].'","'.
					$rows[$i]['ser_datetime'].chr(13).chr(10).$rows[$i]['ser_question_all'].'","'.
					$rows[$i]['ser_respondtime'].chr(13).chr(10).$rows[$i]['ser_respond_all'].'","'.
					$rows[$i]['ser_type'].'","'.
					$rows[$i]['ser_status'].'","'.
					$rows[$i]['acctwname'].'","'.
					$rows[$i]['ser_mail'].'","'.
					$rows[$i]['ser_hour'].'時'.$rows[$i]['ser_min'].'分"'."\n";
}
//// 任務列表 End ////
ob_clean();
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
header('Content-Disposition: attachment; filename="custom_export.csv"');
header('Content-Transfer-Encoding: binary');
header('Content-Type: application/xml; name="custom_export.csv"');
echo twu2b($content);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>