<?php
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');
    include_once('../../lib/Xtemplate.class.php');
    $xtpl = new XTemplate('Process.htm');
	$file_path="http://www.kingfor.com.tw:8080/Service/coustomer_image/";
    session_start();
	$db=new db_action;
	extract($_POST);

	// Function START-------------------------------------------------------------------------
	function unGetCustID($custID)
	{
		$rs = mysql_query("SELECT id FROM customer WHERE serial_id = '".$custID."'");//
		if(mysql_num_rows($rs)){
			$val_array = mysql_fetch_array($rs);
			return $val_array[0];
		}else return 'NULL';
	}
	// Function END---------------------------------------------------------------------------
	$rows=array();
    $DataGridSQL = "SELECT service_record.*,website.customer,website.caption FROM service_record,website WHERE service_record.cal_number = website.serial_id AND service_record.ser_id = '$_GET[ser_id]'";
    $result = $db->Execute($DataGridSQL);
    $field_num = mysql_num_fields($result); // 共有多少欄位
    $a = 1;

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
    } // while($row = mysql_fetch_assoc($result)) end

    $rowsize = count($rows); // count times
    for ($i=1; $i<=$rowsize; $i++)
    {
    	$rows[$i][cal_number] = unGetCustID($rows[$i][customer]);
		$rows[$i][ser_question] = nl2br($rows[$i][ser_question]);
    	
    	if($rows[$i][ser_respond] == ""){
    		$rows[$i][ser_respond] = '目前尚無回覆說明';
		}else{
    		$rows[$i][ser_respond] = $rows[$i][ser_respond];
		}
		
        if ($rows[$i][files]) {
            $filerow = explode(";",$rows[$i][files]);
            for ($j=0; $j<count($filerow); $j++) {
                list ($filedesc,$file) = explode(",",$filerow[$j]);
                $xtpl->assign("filedesc",$filedesc);
                $xtpl->assign("file",$file_path.$file);
                $xtpl->parse("main.table.file.row");
            }
            $xtpl->parse("main.table.file");
        }
		
    	$xtpl->assign('DATA', $rows[$i]);
        $xtpl->parse('main.table.row');
    }
	
    $JavaScript = '
    <script>
    function checkForm()
    {
		if((document.form1.ser_hour.value.length == 0) && (document.form1.ser_min.value.length == 0) ){
			alert("需輸入維護時數");
			return false;
		}
    }
    </script>';
    
$xtpl->assign('JavaScript', $JavaScript); // 註冊   JavaScript    	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////    
    
$xtpl->assign('Datetime', date("Y-m-d H:i:s"));
$xtpl->parse('main.table');
$xtpl->parse('main');
$xtpl->out('main');
?>