#######################################################################
# 
# KindEditor   �@��  ��w
#
#######################################################################

���n���F��d  �W  

1.       ��W��  �Z  �S���        �@  ���n  �Z�J  ��  ttp://�F  ��  ��/editor/    

2. �@���@  �@  ���n��  ttached  ��w�G��  �ʲ��  �P  �W��777    

3.   �{      �C    �@  ����  �ब���n��  ����    ���TEXTAREA    �e��  �C    �@��    ��    ��  
   �Y  []        �@��      �@    �@        ��n  �s�f
-----------------------------------------------------------------------
<input type="hidden" name="[    EXTAREA      ]" value="[�Y    �y  ��    �C      �@]">
<script type="text/javascript" charset="utf-8" src="[JS�o�@  ]/KindEditor.js"></script>
<script type="text/javascript">
var editor = new KindEditor("editor"); //    �w�y�i    ���@�\  
editor.hiddenName = "[    EXTAREA      ]";
editor.editorWidth = "[�y�i    ���@�S  �Z�J  ��  00px]";
editor.editorHeight = "[�y�i    ��  �S  �Z�J  ��  00px]";
editor.show(); //  �@�A
//    ��  �k����ߺ��RTML�W��    �@    
function KindSubmit() {
	editor.data();
}
</script>
-----------------------------------------------------------------------

4. FORM  �@nsubmit����    �{    indSubmit()  ��`    
<form name="formname" onsubmit="javascript:KindSubmit();">
  �W�⳨  ���ӡ@�m�V  �@�Zonclick����    
<input type="button" value="Submit" onclick="javascript:KindSubmit();">

5. ����KindEditor  ��w  �ߺ�  ��    �@  ���n    ����            skinPath    conPath��        �@��        ���x�r�i

* �F��d  �W    �@��    demo  ��w    

-----------------------------------------------------------------------

�~�����W��  
-----------------------------------------------------------------------
1. siteDomain
���S    ����    ���@�G        �@        �\�@�s����
�d  �@  �P      
�Z�J  wwww.kindsoft.net

2. editorType
simple    ull        �@�d�`�e�٩o��  
�d  �@  �P  full

3. safeMode
true    alse      ���@�d�`true  �k  �D  cript        
  �@  �P  false

4. uploadMode
true    alse    �Z  �@�d�`true  ���b  ��  �Z  �S  �j��
�d  �@  �P  true

5. hiddenName
    ��  ��  �C  �e�c�VPOST        
  �@  �P      

6. editorWidth
�y�i    ���@�S  
�d  �@  �P  700px

7. editorHeight
�y�i    ��  �S  
�d  �@  �P  400px

8. skinPath
�y�i    ����    �s����
  �@  �P  ./skins/default/

9. iconPath
�y�i    ��    �Z�z    �s����
  �@  �P  ./icons/

10. imageAttachPath
�N��  ���n    �@��  �@�s����
  �@  �P  ./attached/

11. imageUploadCgi
���n    �@��  �@GI  ��w�o�@  
�d  �@  �P  ./upload_cgi/upload.php

12. menuBorderColor
���n    �C�W  ��  
�d  �@  �P  #AAAAAA

13. menuBgColor
���n        ��  
�d  �@  �P  #EFEFEF

14. menuTextColor
���n      ���  
�d  �@  �P  #222222

15. menuSelectedColor
���n        ����  
�d  �@  �P  #DDDDDD

16. toolbarBorderColor
�f��Q  �G  �@      
�d  �@  �P  #DDDDDD

17. toolbarBgColor
�f��Q  �G  �@      
�d  �@  �P  #EFEFEF

18. formBorderColor
�y�i  ʵ�ų�ʵ��      
�d  �@  �P  #DDDDDD

19. formBgColor
�y�i  ʵ��  �@      
�d  �@  �P  #FFFFFF

20. buttonColor
        ��  
�d  �@  �P  #AAAAAA

21. cssPath
      CSS  ��w�o�@  
�d  �@  �P  ./common.css
-----------------------------------------------------------------------

���ٲZ�J  ��  
-----------------------------------------------------------------------
<input type="hidden" name="content" value="[�Y    �y  ��    �C      �@]">
<script type="text/javascript" src="./KindEditor.js"></script>
<script type="text/javascript">
var editor = new KindEditor("editor");
editor.siteDomain = "www.kindsoft.net";
editor.safeMode = true; // true     false
editor.uploadMode = true; // true     false
editor.hiddenName = "content"; //���nhidden  �@�s�`
editor.imageUploadCgi = "./upload_cgi/upload.php"; //  �@����n  CGI���  
editor.editorType = "simple"; // simple     full
editor.skinPath = "./skins/fck/"; //�R    ����    �r�i
editor.editorWidth = "700px"; //�e�Ǯ`
editor.editorHeight = "400px"; //��  �`
editor.menuBorderColor = '#696969';
editor.menuBgColor = '#EFEFDE';
editor.menuTextColor = '#000000';
editor.menuSelectedColor = '#C7C78F';
editor.toolbarBorderColor = '#696969';
editor.toolbarBgColor = '#EFEFDE';
editor.formBorderColor = '#696969';
editor.buttonColor = '#C7C78F';
editor.show();
function KindSubmit() {
	editor.data(); //      �C    �@�e�W��    ontent    Form    
}
</script>
-----------------------------------------------------------------------

���n�R���n  CGI
-----------------------------------------------------------------------
KindEditor���n�w榡@�M�F��dupload_cgi    ڬ�@��CGI���  ��  ���i���Y  �y���n    �@��      �Y    �X�C��    
���E�@  ����          �s�    �s�`  �z��  �s�`  ��w      �@        �ʲ��  �G�N��    ��        �@  ���n

1. Form���R
--------------------------------------
<form name="uploadForm" method="post" enctype="multipart/form-data" action="[imageUploadCgi        �@        �S��">
�^�s�    �W����<input type="hidden" name="fileName" value="">
  ��w<input type="file" name="fileData">
    ��<input type="text" name="imgTitle" value="">
�e  input type="text" name="imgWidth" value="">
��  input type="text" name="imgHeight" value="">
�C  input type="text" name="imgBorder" value="">
<select name="imgAlign">
<option value="">�\�k    ��  </option>
</select>
�o�@��<input type="text" name="imgHspace" value="">
�ǻ�<input type="text" name="imgVspace" value="">
<input type="submit" name="button" value="�ơ@  ">
</form>
--------------------------------------

2. ��    �@avaScript  ��`
parent.KindInsertImage("[  �@��URL]","[  �@���e�Ǯ`]","[  �@����  �`]","[  �@���C�W  ]","[ALT        ��]","[  �@���\�k    ��  ]","[  �@��o�@�G�ҭi��]","[  �@��ǻ�ҭi��]");
  议��n          �`    ���@�G  ���R  ��Q    �e  �����n        
* �գ���       ��w��    �Z  �SJavaScript    mageAttachPath  �@      

parent.KindDisableMenu();
        �d����        

parent.KindReloadIframe();
  ��  ��  �@��d����        

3.   ��  �y�i  �W  梮p��    pload_cgi  ���      

-----------------------------------------------------------------------

�t��      ��    �W  
-----------------------------------------------------------------------
1. ��      kins  �@  ���n�w�ǻ�  ���@���r�i�Z�J  ��  skins/myskin/

2.     kins/default/�@�@  ���n      ��w�G��  ���y�e��  ��      �@  ���n
  ����  �@�����n�y���n�y  �ؾ]��  �������W��        �F���s�`  ���          �f��

3.   ��    ��  �C    �@  ��        ����    æ��    �o�@    �]  �C  ��      

��  ��  
editor.skinPath = "./skins/myskin/"; //          ��  ���ɪP  �@  
editor.menuBorderColor = '#696969';
editor.menuBgColor = '#EFEFDE';
editor.menuTextColor = '#000000';
editor.menuSelectedColor = '#C7C78F';
editor.toolbarBorderColor = '#696969';
editor.toolbarBgColor = '#EFEFDE';
editor.formBorderColor = '#696969';
editor.buttonColor = '#C7C78F';
-----------------------------------------------------------------------

-----------------------------------------------------------------------

�@��ڬ�q    �@  
-----------------------------------------------------------------------
1.           B2312�y�i  ���n�b    indEditor��  

����    �@  �C  �d  �@    UTF-8  ��          �W��GB2312  ��  ��  �W��  ڬ�@�j�y�i  ���@��      �@  �C  ��  ltraEdit, EditPlus����      indEditor.js���w  ��  ���@��    B2312���˼B�W�ߤN
  ��b  ����      �Z  �S  ��  upload���  �K�~    �W��GB2312    
      KindEditor.js  ����e��harset    
<script type="text/javascript" charset="utf-8" src="[JS  �@  ]/KindEditor.js"></script>
-----------------------------------------------------------------------

��    ���@      �  �@  梺ơ@    ttp://www.kindsoft.net    