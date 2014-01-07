//==============================================================================
//  SYSTEM      :  暫定版跨瀏覽器Ajax用程式庫
//  PROGRAM     :  以XMLHttpRequest進行資料來回傳輸
//  FILE NAME   :  jslb_ajaxXXX.js
//  CALL FROM   :  Ajax 客戶端
//  AUTHER      :  Toshirou Takahashi http://jsgt.org/mt/01/
//  SUPPORT URL :  http://jsgt.org/mt/archives/01/000409.html
//  CREATE      :  2005.6.26
//  UPDATE      :  v0.50  2006.02.17 新增sendRequest.prototype.README
//  UPDATE      :  v0.50  2006.02.17 支援Win e7原生XMLHttpRequest物件
//  UPDATE      :  v0.50  2006.02.17 引數data可指定object,array
//  UPDATE      :  v0.39  2005.11.17 引數callback可指定object,array
//  UPDATE      :  v0.38  2005.10.18 新增chkAjaBrowser()
//  UPDATE      :  v0.372 2005.10.14 修改uriEncode
//  UPDATE      :  v0.371 2005.10.7  修改GET與sload時加註?的方式
//  UPDATE      :  v0.37 2005.10.5.1 放棄使用修正版BSD授權。
//                       使用本函式庫時不需要著作權標示。
//                       可使用於商業用途、歡迎自由改造。不需知會作者。
//  UPDATE      :  v0.37 2005.10.5 修改請求表頭裡設定enctype的方式
//                       新增 setEncHeader、uriEncode
//                       @see http://jsgt.org/ajax/ref/test/enctype/test1.htm
//                       改採BSD授權
//  UPDATE      :  v0.36 2005.7.20 (oj.setRequestHeader)在winie會傳回unknown
//                       已修正（明明是unknown但會運作）
//  UPDATE      :  v0.35 2005.7.19 將POST的Content-Type設定支援Opera8.01
//  UPDATE      :  v0.34 2005.7.16 在sendRequest()新增user,password引數
//  UPDATE      :  v0.33 2005.7.3  將Query Component(GET)的&與=之外的內容
//                                 全部以encodeURIComponent跳脫之。
//  TEST-URL    :  表頭 http://jsgt.org/ajax/ref/lib/test_head.htm
//  TEST-URL    :  認證 http://jsgt.org/mt/archives/01/000428.html
//  TEST-URL    :  非同步
//        http://allabout.co.jp/career/javascript/closeup/CU20050615A/index.htm
//  TEST-URL    :  SQL     http://jsgt.org/mt/archives/01/000392.html
//------------------------------------------------------------------------------
// 最新資訊   : http://jsgt.org/mt/archives/01/000409.html 
// 無著作權標示義務。本程式庫可自由改造或做商業用途。不需聯絡。
//
//

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

