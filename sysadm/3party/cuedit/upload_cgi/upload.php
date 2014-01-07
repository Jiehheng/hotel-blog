<?php
//檔保存目錄路徑
$save_path = '../../../upload_image/';
//檔保存目錄URL
$save_url = '../upload_image/';
//定義允許上傳的檔副檔名
$ext_arr = array('gif','jpg','png','bmp');
//最大文件大小
$max_size = 1000000;
//更改目錄許可權
@mkdir($save_path, 0777);
//檔的全部路徑
$file_path = $save_path.$_POST['fileName'];
//文件URL
$file_url = $save_url.$_POST['fileName'];

//有上傳文件時
if (empty($_FILES) === false) {
	//原檔案名
	$file_name = $_FILES['fileData']['name'];
	//伺服器上暫存檔案名
	$tmp_name = $_FILES['fileData']['tmp_name'];
	//文件大小
	$file_size = $_FILES['fileData']['size'];
	//檢查目錄
	if (@is_dir($save_path) === false) {
		alert("上傳目錄不存在。");
	}
	//檢查目錄寫許可權
	if (@is_writable($save_path) === false) {
		alert("上傳目錄沒有寫許可權。");
	}
	//檢查是否已上傳
	if (@is_uploaded_file($tmp_name) === false) {
		alert("暫存檔案可能不是上傳檔。");
	}
	//檢查檔大小
	if ($file_size > $max_size) {
		alert("上傳檔大小超過限制。");
	}
	//獲得文件副檔名
	$temp_arr = explode(".", $_POST['fileName']);
	$file_ext = array_pop($temp_arr);
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
	//檢查副檔名
	if (in_array($file_ext, $ext_arr) === false) {
		alert("上傳檔副檔名是不允許的副檔名。");
	}
	//移動文件
	if (move_uploaded_file($tmp_name, $file_path) === false) {
		alert("上傳檔失敗。");
	}
	//插入圖片，關閉層
	echo '<html>';
	echo '<head>';
	echo '<title>Insert Image</title>';
	echo '<meta http-equiv="content-type" content="text/html; charset=utf-8">';
	echo '</head>';
	echo '<body>';
	echo '<script type="text/javascript">parent.KindInsertImage("'.$file_url.'","'.$_POST['imgWidth'].'","'.$_POST['imgHeight'].'","'.$_POST['imgBorder'].'","'.$_POST['imgTitle'].'","'.$_POST['imgAlign'].'","'.$_POST['imgHspace'].'","'.$_POST['imgVspace'].'");</script>';
	echo '</body>';
	echo '</html>';
}

//提示，關閉層
function alert($msg)
{
	echo '<html>';
	echo '<head>';
	echo '<title>error</title>';
	echo '<meta http-equiv="content-type" content="text/html; charset=utf-8">';
	echo '</head>';
	echo '<body>';
	echo '<script type="text/javascript">alert("'.$msg.'");parent.KindDisableMenu();parent.KindReloadIframe();</script>';
	echo '</body>';
	echo '</html>';
	exit;
}
?>