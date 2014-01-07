// ABOUT
// ==============================================================================
// ajax_zz ver:0.1
// made by zenon blue,December 2006
// http://www.bluezz.com.tw/mybook/content.php?id=458
// service@bluezz.com.tw
// Copyright 2006 by bluezz

// Modify from :
// AUTHER      :  Toshirou Takahashi http://jsgt.org/mt/01/
// SUPPORT URL :  http://jsgt.org/mt/archives/01/000409.html
//===============================================================================

////
// 判斷可執行的瀏覽器
//
// @sample        if(chkAjaBrowser()){ location.href='nonajax.htm' }
// @sample        oj = new chkAjaBrowser();if(oj.bw.safari){ /* Safari code */ }
// @return        只有在可執行的瀏覽器會傳回true  true|false
//
//  Enable list (v038現在)
//   WinIE 5.5+ 
//   Konqueror 3.3+
//   AppleWebKit系(Safari,OmniWeb,Shiira) 124+ 
//   Mozilla系(Firefox,Netscape,Galeon,Epiphany,K-Meleon,Sylera) 20011128+ 
//   Opera 8+ 
//
function chkAjaBrowser()
{
	var a,ua = navigator.userAgent;
	this.bw= { 
	  safari    : ((a=ua.split('AppleWebKit/')[1])?a.split('(')[0]:0)>=124 ,
	  konqueror : ((a=ua.split('Konqueror/')[1])?a.split(';')[0]:0)>=3.3 ,
	  mozes     : ((a=ua.split('Gecko/')[1])?a.split(" ")[0]:0) >= 20011128 ,
	  opera     : (!!window.opera) && ((typeof XMLHttpRequest)=='function') ,
	  msie      : (!!window.ActiveXObject)?(!!createHttpRequest()):false 
	}
	return (this.bw.safari||this.bw.konqueror||this.bw.mozes||this.bw.opera||this.bw.msie)
}

////
// 建立XMLHttpRequest物件
//
// @sample        oj = createHttpRequest()
// @return        XMLHttpRequest物件(實體)
//
function createHttpRequest()
{
	if(window.XMLHttpRequest){
		 // for Win Mac Linux m1,f1,o8 Mac s1 Linux k3 & Win e7
		return new XMLHttpRequest() ;
	} else if(window.ActiveXObject){
		 // for Win e4,e5,e6用
		try {
			return new ActiveXObject("Msxml2.XMLHTTP") ;
		} catch (e) {
			try {
				return new ActiveXObject("Microsoft.XMLHTTP") ;
			} catch (e2) {
				return null ;
			}
		}
	} else  {
		return null ;
	}
}

////
// 收發訊函式
//
// @sample                 sendRequest(onloaded,'&prog=1','POST','./about2.php',true,true)
// @sample                 sendRequest(onloaded,{name:taro,id:123,sel:1},','POST','./about3.php',true,true)
// @sample                 sendRequest({onload:loaded,onbeforsetheader:sethead},'',','POST','./about3.php',true,true)
// @param {string} callback 收到訊息時要執行的函式名（回呼函式）
// @param {object} callback 收到訊息時要執行的函式名與設定表頭的函式名 {onload:函式名,onbeforsetheader:函式名} 
// @param {array}  callback 收到訊息時要執行的函式名與設定表頭的函式名 ary["onload"]=函式名;ary["onbeforsetheader"]=函式名
// @see                    http://jsgt.org/ajax/ref/head_test/header/Range/004/sample.htm
// @param {string} data	   要傳送的資料 string形式: (&名稱1=值1&名稱2=值2...)
// @param {object} data	   要傳送的資料 object形式: {名稱1:值1,名稱2:值2,...}
// @param {array}  data	   要傳送的資料 array(雜湊陣列)形式: ary["名稱1"]=值1;ary["名稱2"]=值2
// @param method           "POST" or "GET"
// @param url              要請求之URL
// @param async	         true:非同步 / false:同步
// @param sload	         強制載入 true:強制 / 省略或false:預設值
// @param user	            認證頁面用使用者名稱
// @param password         認證頁面用密碼
//
function sendRequest(callback,data,method,url,async,sload,user,password)
{
	sendRequest.prototype.README	 = {
		url		: "http://jsgt.org/mt/archives/01/000409.html",
		name	: "sendRequest", 
		version	: 0.50, 
		license	: "Public Domain",
		author	: "Toshiro Takahashi http://jsgt.org/mt/01/",memo:""}

	// 建立XMLHttpRequest物件
	var oj = createHttpRequest();
	if( oj == null ) return null;
	
	// 設定強制載入
	var sload = (!!sendRequest.arguments[5])?sload:false;
	if(sload || method.toUpperCase() == 'GET')url += "?";
	if(sload)url=url+"t="+(new Date()).getTime();
	
	// 瀏覽器設定
	var bwoj = new chkAjaBrowser();
	var opera	  = bwoj.bw.opera;
	var safari	  = bwoj.bw.safari;
	var konqueror = bwoj.bw.konqueror;
	var mozes	  = bwoj.bw.mozes ;
			
	// 分解 callback
	// {onload:xxxx,onbeforsetheader:xxx}
	if(typeof callback=='object'){
		var callback_onload = callback.onload
		var callback_onbeforsetheader = callback.onbeforsetheader
	} else {
		var callback_onload = callback;
		var callback_onbeforsetheader = null;
	}

	// 收訊處理
	// opera會有onreadystatechange重複發生錯誤，改用onload較安全
	// Moz,FireFox在oj.readyState==3也能讀取資料，故採onload也比較安全
	// Win ie下onload不能跑
	// Konqueror的onload不太穩
	// 參考http://jsgt.org/ajax/ref/test/response/responsetext/try1.php
	if(opera || safari || mozes){
		oj.onload = function () { callback_onload(oj); }
	} else {
	
		oj.onreadystatechange =function () 
		{
			if ( oj.readyState == 4 ){
				//alert(oj.status+"--"+oj.getAllResponseHeaders());
				callback_onload(oj);
			}
		}
	}

	// URL編碼
	data = uriEncode(data,url)
	if(method.toUpperCase() == 'GET') {
		url += data
	}
	
	// open 方法
	oj.open(method,url,async,user,password);

	
	// 用來自訂請求表頭的回呼函式
	// 使用時，請在呼叫端HTML裡定義window物件底下的全域函式setHeaders
	// 並在該函式裡設定setRequestHeader()
	// @sample function setHeaders(oj){oj.setRequestHeader('Content-Type',contentTypeUrlenc)}
	//
	if(!!callback_onbeforsetheader)callback_onbeforsetheader(oj)

	// 設定表頭為 application/x-www-form-urlencoded
	setEncHeader(oj)
	
	// 偵錯用
	//alert("////jslb_ajaxxx.js//// \n data:"+data+" \n method:"+method+" \n url:"+url+" \n async:"+async);
	
	// send 方法
	oj.send(data);

	// 設定URI編碼表頭
	function setEncHeader(oj){

		// 將表頭設定為application/x-www-form-urlencoded
		// @see  http://www.asahi-net.or.jp/~sd5a-ucd/rec-html401j/interact/forms.html#h-17.13.3
		// @see  #h-17.3
		//   ( enctype 的預設值是 "application/x-www-form-urlencoded")
		//   根據 h-17.3，不管是POST/GET都要設定
		//   若想要在POST傳送"multipart/form-data"資料時請自行改寫這個函式
		//
		//  這個函式在Win Opera8.0無法正常執行，故獨立處理(8.01則OK)
		var contentTypeUrlenc = 'application/x-www-form-urlencoded; charset=UTF-8';
		if(!window.opera){
			oj.setRequestHeader('Content-Type',contentTypeUrlenc);
		} else {
			if((typeof oj.setRequestHeader) == 'function')
				oj.setRequestHeader('Content-Type',contentTypeUrlenc);
		}	
		return oj
	}

	// URI編碼
	// 引數data可以string或object的形式傳遞
	function uriEncode(data,url){
		var encdata =(url.indexOf('?')==-1)?'?dmy':'';
		if(typeof data=='object'){
			for(var i in data)
				encdata+='&'+encodeURIComponent(i)+'='+encodeURIComponent(data[i]);
		} else if(typeof data=='string'){
			if(data=="")return "";
			// 先分解&與=再encode
			var encdata = '';
			var datas = data.split('&');
			for(i=1;i<datas.length;i++)
			{
				var dataq = datas[i].split('=');
				encdata += '&'+encodeURIComponent(dataq[0])+'='+encodeURIComponent(dataq[1]);
			}
		} 
		return encdata;
	}

	return oj
}