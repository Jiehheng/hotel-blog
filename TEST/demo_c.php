<?php
	$htmlData1 = '';
	if (!empty($_POST['message1'])) {
		if (get_magic_quotes_gpc()) {
			$htmlData1 = stripslashes($_POST['message1']);
		} else {
			$htmlData1 = $_POST['message1'];
		}
	}
	$htmlData2 = '';
	if (!empty($_POST['message2'])) {
		if (get_magic_quotes_gpc()) {
			$htmlData2 = stripslashes($_POST['message2']);
		} else {
			$htmlData2 = $_POST['message2'];
		}
	}
	$htmlData3 = '';
	if (!empty($_POST['message3'])) {
		if (get_magic_quotes_gpc()) {
			$htmlData3 = stripslashes($_POST['message3']);
		} else {
			$htmlData3 = $_POST['message3'];
		}
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>KindEditor PHP</title>
<link rel="stylesheet" href="./kindeditor-4.1.7/themes/default/default.css" />
<link rel="stylesheet" href="./kindeditor-4.1.7/plugins/code/prettify.css" />
<script charset="utf-8" src="./kindeditor-4.1.7/kindeditor.js"></script>
<script charset="utf-8" src="./kindeditor-4.1.7/lang/zh_TW.js"></script>
<script charset="utf-8" src="./kindeditor-4.1.7/plugins/code/prettify.js"></script>
<script>
KindEditor.ready(function(K) {
	LoadEditor('message1', 'example');   
	LoadEditor('message2', 'example');   
	LoadEditor('message3', 'example');   
});   
function LoadEditor(TextName, FormName) {
	var editor1 = KindEditor.create('textarea[name="' + TextName + '"]', {
		cssPath : './kindeditor-4.1.7/plugins/code/prettify.css',
		uploadJson : './kindeditor-4.1.7/php/upload_json.php',
		fileManagerJson : './kindeditor-4.1.7/php/file_manager_json.php',
		allowFileManager : true,
		afterCreate : function() {
			var self = this;
			KindEditor.ctrl(document, 13, function() {
				self.sync();
				KindEditor('form[name=' + FormName + ']')[0].submit();
			});
			KindEditor.ctrl(self.edit.doc, 13, function() {
				self.sync();
				KindEditor('form[name=' + FormName + ']')[0].submit();
			});
		}
	});
	return editor1;
}
</script>
</head>
<body>
<?php echo $htmlData1.'<hr>'.$htmlData2.'<hr>'.$htmlData3; ?>
<form name="example" method="post" action="demo_c.php">  
  <textarea name="message1" style="width:800px;height:400px;visibility:hidden;"><?php echo htmlspecialchars($htmlData1); ?></textarea>
  <textarea name="message2" style="width:800px;height:400px;visibility:hidden;"><?php echo htmlspecialchars($htmlData2); ?></textarea>
  <textarea name="message3" style="width:800px;height:400px;visibility:hidden;"><?php echo htmlspecialchars($htmlData3); ?></textarea>
	<input type="submit" name="button" value="提交内容" /> (提交快捷键: Ctrl + Enter)
</form>
</body>
</html>

