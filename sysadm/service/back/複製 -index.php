<?php
    include ("../HeadHtml.php"); // HTML 標頭
    include_once('../Config.php');
    include_once('../Db_connect.php');
    include_once('../lib/DateFindWeek.php');  	
    include_once('../Xtemplate.class.php'); // load Xtemplate //
    $xtpl = new XTemplate('index.htm'); // Load Template File //
    $rows = array();
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

    if($_POST['cal_number'] == '') // 下拉是挑選案件
   	    $WHERE = "";
    else
   	    $WHERE = " AND service_record.cal_number = '$_POST[cal_number]'";

    if($_POST['ser_type'] != '') // 下拉是挑選問題種類
    {
   	    $WHERE .= " AND service_record.ser_type = '$_POST[ser_type]'";
   	    switch($_POST['ser_type'])
   	    {
   		    case "業務問題":
   			    $xtpl->assign('ser_type_selected_1', 'selected');
   			    break;
   		    case "頁面問題":
   			    $xtpl->assign('ser_type_selected_2', 'selected');
   			    break;
   		    case "程式問題":
   			    $xtpl->assign('ser_type_selected_3', 'selected');
   			    break;
   	    }
    }	
   	    
    if($_POST['ser_status'] != '') // 下拉是挑選狀態種類
    {
   	    $WHERE .= " AND service_record.ser_status = '$_POST[ser_status]'";
   	    switch($_POST['ser_status'])
   	    {
    	    case "未處理":
   			    $xtpl->assign('ser_status_selected_1', 'selected');
    		    break;
    	    case "已處理":
    		    $xtpl->assign('ser_status_selected_2', 'selected');
    		    break;    			
   	    }
    }

   	    
    //// 讀取案件  Start //// 
    $result = mysql_query("SELECT case_list.cal_name,case_list.cal_number FROM service_record,case_list WHERE service_record.cal_number = case_list.cal_number GROUP BY service_record.cal_number ORDER BY service_record.cal_number DESC"); //SQL
    while($rows = mysql_fetch_assoc($result))
    {
        if($_POST['cal_number'] == $rows['cal_number'])
    	    $selected = "selected";
        else
    	    $selected = null;
            
	    $CaseList .= "<option value=".$rows['cal_number']." ".$selected.">".$rows['cal_number']."_".$rows['cal_name']."</option>";
    }       
    //// 讀取案件  End ////

    $rows = array();

    $PagesSQL = "SELECT service_record.*,case_list.cal_name,case_list.cal_number 
    	    FROM service_record,case_list 
    	    WHERE service_record.cal_number = case_list.cal_number    		
    	    $WHERE 
    	    ORDER BY ser_id DESC";       
             
    $result = mysql_query($PagesSQL);
    $field_num = mysql_num_fields($result); // 共有多少欄位
    $a = 1;
    while($row = mysql_fetch_assoc($result))
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
        
        $rows[$i][ser_question] = substr($rows[$i][ser_question],0,30)."...";
        if($rows[$i][ser_respond] != '')
        {
    	    $rows[$i][ser_respond] = substr($rows[$i][ser_respond],0,50)."...";
        }
        
        if($rows[$i][accid] != 0)
        {
    	    $temp = $rows[$i][accid];
    	    $FindResult = mysql_query("SELECT * FROM account WHERE accid = '$temp'");
    	    $object = mysql_fetch_object($FindResult);
    	    $rows[$i][acctwname] = $object -> acctwname."(".$object -> accname.")";
        }
        else
        {
    	    $rows[$i][acctwname] = 'null';
        }
    	    
        
        if($rows[$i][ser_respondtime] == '0000-00-00 00:00:00')
    	    $rows[$i][time] = '00:00:00';
        else
    	    $rows[$i][time] = date('h:i:s', (strtotime($rows[$i][ser_respondtime]) - strtotime($rows[$i][ser_datetime]))/60); // 解決時間

        if($rows[$i][ser_respond] == "")
    	    $rows[$i][ser_respond] = '目前尚無回覆說明';
        else
    	    $rows[$i][ser_respond] = nl2br($rows[$i][ser_respond]);
        
        switch($rows[$i][ser_status])
        {
    	    case "未處理":
    		    $rows[$i][ser_status] = "<font color=red><b>未處理</b></font>";
    		    break;
    	    case "已處理":
    		    $rows[$i][ser_status] = "<font color=green><b>已處理</b></font>";
    		    break;    			
        }
        
        //// 刪除鈕控制 Start ////
        $rows[$i][DeleteButton] = $DeleteButton;
        //// 刪除鈕控制 End ////        
            
        $rows[$i][ser_question] = nl2br($rows[$i][ser_question]);
	    $xtpl->assign('DATA', $rows[$i]);
        $xtpl->parse('main.table.TaskList');
    }	
    //// 任務列表 End ////

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

    $xtpl->assign('CaseList', $CaseList); // 案件列表 Option    
    $xtpl->parse('main.table');
    $xtpl->parse('main');
    $xtpl->out('main');
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>


