<!--#include File="upload.inc"-->
<%
Server.ScriptTimeOut=999999
'公開定義
Dim upload_type,upload_ViewType
Dim previewpath,F_Viewname
Dim Forumupload
Dim FormName,FormPath,File_name,FileExt,Filesize,F_Type,rename
Dim upNum,dateupnum
Dim TempSize,ImageWidth,ImageHeight
Dim ImageMode
'________________________________________________

'===========================上傳功能開始判斷==============================
FormPath=checkFolder

'開始上傳圖片
Call upload_0()

'===========================無組件上傳加浮水印，加縮略圖============================
Sub upload_0()
Dim upload,File,UpCount,utype
UpCount=0
set upload=new upload_5xsoft

set file=upload.file("fileData")
        utype=int(upload.Form("utype"))
		FileWidthl = int(upload.Form("imgWidthl"))
        FileHeightl = int(upload.Form("imgHeightl"))
		FileWidth = int(upload.Form("imgWidth"))
        FileHeight = int(upload.Form("imgHeight"))
        FileBorder = int(upload.Form("imgBorder"))
        FileTitle = upload.Form("imgTitle")
        FileAlign = upload.Form("imgAlign")
        FileHspace = int(upload.Form("imgHspace"))
        FileVspace = int(upload.Form("imgVspace"))
        if filewidthl="" or filewidthl=0 or fileheightl="" or fileheightl=0 then
		response.write "非法文件"
		response.End()
		end if
		if FileWidthl=28 and FileHeightl=30 then
		response.write "圖片檔出錯，請重新選擇，並點擊預覽確定你的圖片能顯示出來"
		response.End()
		end if
		FileExt=lcase(File.FileExt)
        If CheckFileExt(FileExt)=false then
        Response.write "檔案格式不正確,或不能為空,只能上傳GIF,JPG,bmp,png,jpeg　[ <a href=# onclick=history.go(-1)>重新上傳</a> ]"
        EXIT SUB
		end if
		if TrueStr(file.filename)=false then
		response.write "圖片檔出錯，請重新選擇，並點擊預覽確定你的圖片能顯示出來"
        response.end
        end if
		If right(FormPath,1)<>"/" then FormPath=FormPath&"/"
		'付值變數
		F_Type		=	CheckFiletype(FileExt)
		File_name	=	CreateName()
		Filename	=	File_name&"."&FileExt&
		rename		=	CreatePath()&Filename&"|"
		Filename	=	FormPath&CreatePath()&Filename
		Filesize	=	File.FileSize
        file.saveas  Server.mappath(FileName)
		filename="/uploadfiles/"&filename
		Response.Write "<script type=""text/javascript"">parent.KindInsertImage(""" & Filename & """,""" & FileWidth & """,""" & FileHeight & """,""" & FileBorder & """,""" & FileTitle & """,""" & FileAlign & """,""" & FileHspace & """,""" & FileVspace & """);</script>"
		F_Viewname=FormPath&CreatePath()&File_name&"_"&FileExt&"_small.jpg"
		if FileWidthl>300 and Fileheightl>300 then
		Call shuiyin(FileName,ImageMode)
	    end if	
        Response.write "1個檔上傳成功!大小為"&round(FileSize/1024,2)&"K"
Set File=Nothing
Set upload=Nothing
End Sub



'判斷檔案類型是否合格
Private Function CheckFileExt(FileExt)
Dim Forumupload,i
	If FileExt="" or IsEmpty(FileExt) Then
		CheckFileExt=false
		Exit Function
	End If
	If FileExt="asa" or FileExt="asp" or FileExt="cer" or FileExt="shtml" or FileExt="php" or FileExt="aspx" or FileExt="bmp" then
	CheckFileExt=false
    Exit Function
	End If
	Forumupload=split(uploadtype,"|")
	For i=0 To ubound(Forumupload)
		If Lcase(FileExt)=Lcase(trim(Forumupload(i))) then
			CheckFileExt=true
			exit Function
		Else
			CheckFileExt=false
		End If
	Next
End Function
Private Function CheckFiletype(FileExt)
Dim upFiletype
Dim FilePic,FileVedio,FileSoft,FileFlash,FileMusic
FileExt=Lcase(replace(FileExt,".",""))
Select Case Lcase(FileExt)
		Case "gif", "jpg", "jpeg","png","bmp","tif","iff"
		CheckFiletype=1
		Case "swf", "swi"
		CheckFiletype=2
		Case "mid", "wav", "mp3","rmi","cda"
		CheckFiletype=3
		Case "avi", "mpg", "mpeg","ra","ram","wov","asf"
		CheckFiletype=4
		Case Else
		CheckFiletype=0
End Select
End Function

function TrueStr(fileTrue)
 str_len=len(fileTrue)
 pos=Instr(fileTrue,chr(0))
 if pos=0 or pos=str_len then
    TrueStr=true
 else
    TrueStr=false
 end if
end function
'___________________________________________________________

'創建預覽圖片:call CreateView(原始檔的路徑,預覽檔案名及路徑)
Sub CreateView(imagename,tempFilename)
'定義變數
Dim PreviewImageFolderName
Dim ogvbox,objFont,xiaotu
Dim Logobox,LogoPath
LogoPath = Server.MapPath("/images") & "\logo.gif"  '//加入圖片所在路徑及檔案名
        Set xiaotu = Server.CreateObject("Persits.Jpeg")
        xiaotu.Open Trim(Server.MapPath(imagename))	

If xiaotu.OriginalWidth / xiaotu.OriginalHeight > 1 Then
    xiaotu.Width = 100 
    xiaotu.Height = int((100/xiaotu.OriginalWidth)*xiaotu.OriginalHeight) 
Else
    xiaotu.Height = 100 
    xiaotu.Width= int(xiaotu.OriginalWidth*(100/xiaotu.OriginalHeight)) 
End If
    xiaotu.Save Server.MapPath(tempFilename)	
Set xiaotu = Nothing
End Sub
'___________________________________________________________


sub shuiyin(imagename,ImageMode)
	Set ogvbox = Server.CreateObject("Persits.Jpeg")
	ogvbox.Open Trim(Server.MapPath(imagename))	
	dim bb
	dim aa
	dim cc
	aa=ogvbox.Binary
			ogvbox.Canvas.Font.Color	= &Hff0000		'// 文字的顏色
			ogvbox.Canvas.Font.Family	= "Arial Black"	'// 文字的字體
			ogvbox.Canvas.Font.BkMode = "Opaque"
			ogvbox.Canvas.Font.Bkcolor=&HffFFFF
			ogvbox.Canvas.Font.Quality = 4
			ogvbox.Canvas.Font.size = 20
			ogvbox.Canvas.Font.ShadowColor = &HFFFF00''文字陰影
			ogvbox.Canvas.Font.ShadowYOffset = 1 
            ogvbox.Canvas.Font.ShadowXOffset = 1 
			ogvbox.Canvas.Font.Bold = false
			ogvbox.Canvas.Print 1, 1, "圖片浮水印資訊"		
			bb=ogvbox.Binary
			Set ogvbox = Server.CreateObject("Persits.Jpeg") 
             ogvbox.OpenBinary aa 
            Set Logo = Server.CreateObject("Persits.Jpeg") 
             Logo.OpenBinary bb 
            ogvbox.DrawImage 0,0, Logo, 0.6 '0.3是透明度 
            cc=ogvbox.Binary 
			ogvbox.Save Server.MapPath(imagename)		'// 生成大圖片，並加浮水印
			
Set Jpeg1 = Server.CreateObject("Persits.Jpeg")
Set Jpeg2 = Server.CreateObject("Persits.Jpeg")
Jpeg1.Open Server.MapPath(imagename)
Jpeg2.Open Server.MapPath("/images/logo.jpg")

iWidth=Jpeg1.OriginalWidth 
iHeight=Jpeg1.OriginalHeight

iiWidth=Jpeg2.OriginalWidth 
iiHeight=Jpeg2.OriginalHeight

iX=iWidth / 2
iY=iHeight / 2

iiX=iiWidth / 2
iiY=iiHeight / 2

iiiX=iX-iiX
iiiY=iY-iiY
Jpeg1.Canvas.DrawImage iiiX, iiiY, Jpeg2,0.3,&HFFFFFF  ' optional arguments omitted
jpeg1.save Server.mappath(imagename)


	set aa=nothing
	set bb=nothing
	set cc=nothing
	set ogvbox=nothing
	set logo=nothing
	set jpeg1=nothing
	set jpeg2=nothing
end sub



'=====================生成文件隨機名=================================
Private Function CreateName()
Dim ranNum
	randomize
	ranNum=int(999*rnd)
	CreateName=year(now)&month(now)&day(now)&hour(now)&minute(now)&second(now)&ranNum
End Function
'___________________________________________________________





'=====================生成圖片所在資料夾=============================
Private Function CreatePath()
Dim objFSO,Fsofolder,uploadpath
uploadpath=year(now)&month(now)&day(now)
On Error Resume Next
Set objFSO = Server.CreateObject("Scripting.FileSystemObject")
	If objFSO.FolderExists(Server.MapPath(FormPath&uploadpath))=False Then
	objFSO.CreateFolder Server.MapPath(FormPath&uploadpath)
	End If
	If Err.Number = 0 Then
	CreatePath=uploadpath&"/"
	Else
	CreatePath=""
	End If
set objFSO = nothing
End Function
'___________________________________________________________





'=====================檢查資料夾=================================
Function checkFolder()
	If uploadto="" Or uploadto="0" Then uploadto="/uploadfiles/"
	checkFolder=uploadto
End Function 
'___________________________________________________________
%>
</body>
</html>
