<?php
//載入剛剛下載的 SDK 檔案
//require_once('php-sdk/facebook.php');
require_once('lib/function.php');
require_once('lib/facebook.php');
  
  //APP的 ID 與 secret
  $config = array(
    'appId' => '150857848420849',
    'secret' => 'e7d1027f540390962b30db4ce24e16b8',
  );
  
  //Facebook 連線
  $facebook = new Facebook($config);
  $user_id = $facebook->getUser();
?>
<html>
  <head></head>
  <body>
  <?php
    //假設已登入 $user_id 會取得 uid
    if($user_id) {
      try {
        //呼叫api函式並印出 user 的 ID
        $user_profile = $facebook->api('/me','GET');
        echo "Name: " . $user_profile['name'];
        
        //若驗證過程中發生錯誤
      } catch(FacebookApiException $e) {
        $login_url = $facebook->getLoginUrl(); 
        echo 'Please <a href="' . $login_url . '">login.</a>';
      }   
    } else { //未登入 uid 是 0，則開始登入 Facebook
      $login_url = $facebook->getLoginUrl();
      echo 'Please <a href="' . $login_url . '">login.</a>';
    }
  ?>
  </body>
</html>