<%
'�ݭn�w��ABC�ե�
'�U���a�}�Ghttp://www.websupergoo.com/abcupload-1.htm

'���O�s�ؿ����|
Dim SavePath
SavePath = "../attached/"

'���O�s�ؿ�URL
Dim SaveUrl
SaveUrl = "../attached/"

'�w�q���\�W�Ǫ�����X�i�W
Dim ExtArr(3)
ExtArr(0) = "gif"
ExtArr(1) = "jpg"
ExtArr(2) = "png"
ExtArr(3) = "bmp"

'�̤j���j�p
Dim MaxSize
MaxSize = 1000000

Dim Msg1, Msg2, Msg3
Msg1 = "�W�Ǥ��j�p�W�L����C"
Msg2 = "�W�Ǥ���X�i�W�O�����\���X�i�W�C"
Msg3 = "�W�Ǥ�󥢱ѡC"

'��ABC�ե�
Dim theForm
Set theForm = Server.CreateObject("ABCUpload4.XForm")

'�o��POST�Ѽ�
Dim FileName, FileWidth, FileHeight, FileBorder, FileTitle, FileAlign, FileHspace, FileVspace
FileName = theForm("fileName")
FileWidth = theForm("imgWidth")
FileHeight = theForm("imgHeight")
FileBorder = theForm("imgBorder")
FileTitle = theForm("imgTitle")
FileAlign = theForm("imgAlign")
FileHspace = theForm("imgHspace")
FileVspace = theForm("imgVspace")

Dim FilePath, FileUrl
FilePath = SavePath & FileName
FileUrl = SaveUrl & FileName

'�W�Ǩ�۹�a�}
theForm.AbsolutePath = False

'�o��W�ǤG�i����
Dim theField
Set theField = theForm("fileData")(1)

'�W�ǳB�z
Dim oFileSize, oFileType
If theField.FileExists Then
    oFileSize = theField.Length
    oFileType = theField.FileType
	if oFileSize > MaxSize Then
		Alert(Msg1)
	ElseIf oFileType <> ExtArr(0) AND oFileType <> ExtArr(1) AND oFileType <> ExtArr(2) AND oFileType <> ExtArr(3)  Then
		Alert(Msg2)
    else
		theField.Save FilePath
	End If
	Response.Write "<html>"
	Response.Write "<head>"
	Response.Write "<title>error</title>"
	Response.Write "<meta http-equiv=""content-type"" content=""text/html; charset=big5"">"
	Response.Write "</head>"
	Response.Write "<body>"
	Response.Write "<script type=""text/javascript"">parent.KindInsertImage(""" & FileUrl & """,""" & FileWidth & """,""" & FileHeight & """,""" & FileBorder & """,""" & FileTitle & """,""" & FileAlign & """,""" & FileHspace & """,""" & FileVspace & """);</script>"
	Response.Write "</body>"
	Response.Write "</html>"
Else
	Alert(Msg3)
End if

'���ܡA�����h
Function Alert(msg)
	Response.Write "<html>"
	Response.Write "<head>"
	Response.Write "<title>error</title>"
	Response.Write "<meta http-equiv=""content-type"" content=""text/html; charset=big5"">"
	Response.Write "</head>"
	Response.Write "<body>"
	Response.Write "<script type=""text/javascript"">alert(""" & msg & """);parent.KindDisableMenu();parent.KindReloadIframe();</script>"
	Response.Write "</body>"
	Response.Write "</html>"
End Function

%>