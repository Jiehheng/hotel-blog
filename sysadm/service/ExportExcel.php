<?php
  	include_once('../../config/db_action.php');
	include_once('../../lib/function.php');
	include_once("../../lib/excelwriter.inc.php");
    
    session_start();

    //// 判別是否有登入 start ////
    if($_SESSION['accid'] == "")
    {
         header("Location: ./index.php");
    }
    //// 判別是否有登入 End ////

    //// 權限判斷 Start ////
    $MenuResult = mysql_Query("SELECT * FROM secondmenu WHERE accid = '$_SESSION[accid]' AND secname = '客服系統'");
    $MenuRows = mysql_fetch_assoc($MenuResult);
    if($MenuRows['del'] == '1')
        $DeleteButton = '<input type="submit" value="刪除" name="B1" class="lvtCol2">';
    else
        $DeleteButton = '<input type="submit" value="刪除" name="B1" class="lvtCol2" disabled>';
    //// 權限判斷 End ////

    $WHERE = "";
    if($_POST['cal_number']) {
   	    $WHERE .= " AND service_record.cal_number = '$_POST[cal_number]'";
    }
    if($_POST['ser_type']) {
   	    $WHERE .= " AND service_record.ser_type = '$_POST[ser_type]'";
    }	
    if($_POST['ser_status']) {
   	    $WHERE .= " AND service_record.ser_status = '$_POST[ser_status]'";
	}

    $SQL = " select * from account ";
    $Result = mysql_query($SQL);
    $accid_name = array ();
	while ($Rows = mysql_fetch_assoc($Result)) {
        $accid_name[$Rows["accid"]] = $Rows["acctwname"];
    }
    
    $SQL = " select * from case_list where cal_number = '".$_POST["cal_number"]."' ";
    $Result = mysql_query($SQL);
	if ($Rows = mysql_fetch_assoc($Result)) {
        $case_name = $Rows['cal_number']."_".$Rows["cal_name"];
    }


    
    $FileName = "./Files/".date("Ymhhis").".xls";
    $excel=new ExcelWriter($FileName);	
    if($excel==false)	
        echo $excel->error;
        
    $myArr=array("系統檢測報告");
    $excel->writeLine($myArr);
    
    $myArr=array("案件名稱：",$case_name);
    $excel->writeLine($myArr);
    
    $myArr=array("日期：",date("Y-m-d H:i:s"));
    $excel->writeLine($myArr);
    
    $myArr=array("編號","問題分類","問題內容","發問時間","回覆內容","回覆時間","回覆人員","回覆狀況","備註");
    $excel->writeLine($myArr);

        
    
    $SQL = 
    "
        SELECT service_record.*,case_list.cal_name,case_list.cal_number 
        FROM service_record,case_list 
        WHERE service_record.cal_number = case_list.cal_number    		
        $WHERE order by ser_id asc 
    "; 

    $Result = mysql_query($SQL);
	while ($Rows = mysql_fetch_assoc($Result)) {
    
    
    
	    $myArr=array(++$i,$Rows["ser_type"],nl2br($Rows["ser_question"]),$Rows["ser_datetime"],nl2br($Rows["ser_respond"]),$Rows["ser_respondtime"],$accid_name[$Rows["accid"]],$Rows["ser_status"],'');
	    $excel->writeLine($myArr);
        
        
        
    }
    
	$excel->close();
	
	header("Location:".$FileName); # Redirect browser
?>