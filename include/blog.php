<?php
if($_SESSION["cust_sid"])
{
	$list["area1"] = '<input name="" type="button" value="新增文章" /><hr>';
	$list["area2"] = '<div style="text-align:center"><input name="Sout" value="修改" type="button" /></div>';
	$list["text1"] = '
	<textarea name="TE" cols="65" rows="30">
	<div class="right_top2"> 
  <span class="blue2">雙人房優惠活動</span>&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;  &nbsp;  &nbsp;  &nbsp;&nbsp;  &nbsp;  &nbsp;  &nbsp;&nbsp;  &nbsp;  &nbsp;  &nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; <span class="brown">February 19,2013</span></div>
	<div class="right_text">
		<p>自從10月下花蓮整理巷宿至今，已經在花蓮生活5個月了，這兒的人情味的濃厚也感受非常深刻。<br>
		  巷宿這兩天都飄出濃濃的果香甜味，因為我們正熬煮著果醬呢。<br>
		  這次的果醬主題為芭蕉。<br>
		  芭蕉是巷宿的好鄰居林老師送給我們的，林老師對我們很好唷，經常與我們分享水果等等生活中的美好。上週收下芭蕉就決定要把它們變成果醬，但是想要為芭蕉另外找個伙伴搭配。於是乎選定了士多啤梨(草莓)搭配做為第一種果醬，恰巧週末我們也終於空出時間到花蓮鐵道文化<br>
		  <br>
		  <span class="blue"><a href="?{cUrl}&amp;blog_article">觀看全文...</a></span></div>
	<div class="right_footer"><span class="gray2">留言 (0) | 引用 (0) | 人氣 (308)</span></div>
	</textarea><hr>
	<div style="text-align:center"><input name="Sout" value="修改" type="button" /></div>';
	$list["text2"] = '
	<textarea name="TE" cols="65" rows="30">
	<div class="right_top2"> 
  <span class="blue2">雙人房優惠活動</span>&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;  &nbsp;  &nbsp;  &nbsp;&nbsp;  &nbsp;  &nbsp;  &nbsp;&nbsp;  &nbsp;  &nbsp;  &nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; <span class="brown">February 19,2013</span></div>
	<div class="right_text">
		<p>自從10月下花蓮整理巷宿至今，已經在花蓮生活5個月了，這兒的人情味的濃厚也感受非常深刻。<br>
巷宿這兩天都飄出濃濃的果香甜味，因為我們正熬煮著果醬呢。<br>
這次的果醬主題為芭蕉。<br>
芭蕉是巷宿的好鄰居林老師送給我們的，林老師對我們很好唷，經常與我們分享水果等等生活中的美好。上週收下芭蕉就決定要把它們變成果醬，但是想要為芭蕉另外找個伙伴搭配。於是乎選定了士多啤梨(草莓)搭配做為第一種果醬，恰巧週末我們也終於空出時間到花蓮鐵道文化<br><br>

		<span class="blue"><a href="?blog_article">觀看全文...</a></span></div>
	<div class="right_footer"><span class="gray2">留言 (0) | 引用 (0) | 人氣 (308)</span></div>    
	</textarea><hr>
	<div style="text-align:center"><input name="Sout" value="修改" type="button" /></div>';
}
else
{
	$list["text1"] = '
	<div class="right_top2"> 
  <span class="blue2">雙人房優惠活動</span>&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;  &nbsp;  &nbsp;  &nbsp;&nbsp;  &nbsp;  &nbsp;  &nbsp;&nbsp;  &nbsp;  &nbsp;  &nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; <span class="brown">February 19,2013</span></div>
	<div class="right_text">
		<p>自從10月下花蓮整理巷宿至今，已經在花蓮生活5個月了，這兒的人情味的濃厚也感受非常深刻。<br>
		  巷宿這兩天都飄出濃濃的果香甜味，因為我們正熬煮著果醬呢。<br>
		  這次的果醬主題為芭蕉。<br>
		  芭蕉是巷宿的好鄰居林老師送給我們的，林老師對我們很好唷，經常與我們分享水果等等生活中的美好。上週收下芭蕉就決定要把它們變成果醬，但是想要為芭蕉另外找個伙伴搭配。於是乎選定了士多啤梨(草莓)搭配做為第一種果醬，恰巧週末我們也終於空出時間到花蓮鐵道文化<br>
		  <br>
		  <span class="blue"><a href="?{cUrl}&amp;blog_article">觀看全文...</a></span></div>
	<div class="right_footer"><span class="gray2">留言 (0) | 引用 (0) | 人氣 (308)</span></div>
	';
	$list["text2"] = '
	<div class="right_top2"> 
  <span class="blue2">雙人房優惠活動</span>&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;  &nbsp;  &nbsp;  &nbsp;&nbsp;  &nbsp;  &nbsp;  &nbsp;&nbsp;  &nbsp;  &nbsp;  &nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; <span class="brown">February 19,2013</span></div>
	<div class="right_text">
		<p>自從10月下花蓮整理巷宿至今，已經在花蓮生活5個月了，這兒的人情味的濃厚也感受非常深刻。<br>
巷宿這兩天都飄出濃濃的果香甜味，因為我們正熬煮著果醬呢。<br>
這次的果醬主題為芭蕉。<br>
芭蕉是巷宿的好鄰居林老師送給我們的，林老師對我們很好唷，經常與我們分享水果等等生活中的美好。上週收下芭蕉就決定要把它們變成果醬，但是想要為芭蕉另外找個伙伴搭配。於是乎選定了士多啤梨(草莓)搭配做為第一種果醬，恰巧週末我們也終於空出時間到花蓮鐵道文化<br><br>

		<span class="blue"><a href="?blog_article">觀看全文...</a></span></div>
	<div class="right_footer"><span class="gray2">留言 (0) | 引用 (0) | 人氣 (308)</span></div>    
	';
}
?>