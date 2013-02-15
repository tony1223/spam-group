<div class="span8 navbar offset1" style="width:auto;">
  <div class="navbar-inner">
    <ul class="nav">
      <li <?php if($selector=="check") { ?> class="active" <?php }?>><a href="<?=site_url("/")?>">檢查</a></li>
      <li <?php if($selector=="group") { ?> class="active" <?php }?>><a href="<?=site_url("/groups")?>">社團清單(API)</a></li>
      <li <?php if($selector=="report") { ?> class="active" <?php }?>><a href="<?=site_url("/report")?>">回報新廣告社團</a></li>
      <li class="pull-right"><a target="_blank" href="https://github.com/tony1223/spam-group">專案原始碼(Github)</a></li>
    </ul>
  </div>
</div>