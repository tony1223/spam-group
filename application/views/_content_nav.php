      <div>
        <br />
        <p>本系統作者為 <a target="_blank"  href="https://www.facebook.com/tonylovejava">TonyQ</a>( Software Architect of <a href="http://5945.tw" target="_blank">5945.tw </a>)，
          若使用上有任何疑問或建議，歡迎與我聯繫。 (tonylovejava[at]gmail.com)
        </p>
        <br />
      </div>
<div class="navbar">
  <div class="navbar-inner">
    <ul class="nav">
      <li <?php if($selector=="check") { ?> class="active" <?php }?>><a href="<?=site_url("/")?>">檢查</a></li>
      <li <?php if($selector=="group") { ?> class="active" <?php }?>><a href="<?=site_url("/groups")?>">社團清單(API)</a></li>
      <li <?php if($selector=="report") { ?> class="active" <?php }?>><a href="<?=site_url("/report")?>">回報新廣告社團</a></li>
    </ul>

  	<a class="pull-right btn btn-inverse" target="_blank" href="https://github.com/tony1223/spam-group">
  		專案原始碼(Github)
      	<i class="icon-share-alt icon-white"></i>
  	</a>
  </div>
</div>
