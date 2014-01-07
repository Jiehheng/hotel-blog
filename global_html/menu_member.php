<div class="left_box"<?php if(!$login_state) echo' style="display:none;"';?>>
      <div class="left_bt2">
      <a href="?{cUrl}&modify" class="left_bt4">修改會員資料</a>
      </div>
      <div class="left_bt2"<?php if($FBuser) echo' style="display:none;"';?>>
      <a href="?{cUrl}&modify_password" class="left_bt4">修改密碼</a>
      </div>
      <div class="left_bt2">
      <a href="?{cUrl}&hotel_order" class="left_bt4">訂單查詢</a>
      </div>
  </div>