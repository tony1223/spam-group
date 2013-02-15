<?php include("_site_header.php") ?>

<div class="container">
	<div class="well span8 offset1">
		<a href="<?=site_url("/")?>" style="color:black;"><h1>Facebook 廣告社團檢查器</h1></a>
		<p> 現在就檢查是否被惡意加入社團！ </p>
		<p><div style="height:60px;" class="fb-like" data-href="http://spamgroup.tonyq.org/" data-send="true" data-width="450" data-show-faces="true"></div></p>
	</div>
	<div class="well span8 offset1">
		社團清單(其他格式 <a href="<?=site_url("/groups/json/")?>">JSON</a> 、 <a href="<?=site_url("/groups/jsonp/?jsonp=parser")?>">JSONP</a> 板
		<table class="table">
			<tr><td>Group ID</td><td>名字</td><td>加入時間</td></tr>
			<?php foreach($fbgids as $group){ ?>
				<tr>
					<td><?=$group->GID?></td>
					<td><a href="https://www.facebook.com/groups/<?=$group->GID?>" target="_blank"><?=htmlspecialchars($group->Name)?></a></td>
					<td><?=$group->CreateDate?></td>
				</tr>
			<?php } ?>
		</table>
	</div>
	<div class="span8 offset1">
			註一：如果這些社團對你而言是正常運作，你不見得一定要退出他們。（但強烈建議取消。）<br />
			註二：本 App 只使用 "取得社團清單權限" 並不會作為任何其他用途，若有疑慮請勿使用。<br />
			註三：如果剛取消社團，但在本系統查詢還在，這是正常的 、FB API 反應比較慢。<Br />
			註四：我們使用的社團清單來源。<a target="_blank" href="https://www.facebook.com/events/315380641913250/permalink/315383471912967/">https://www.facebook.com/events/315380641913250/permalink/315383471912967/</a>
	</div>
	<div class="span8 offset1">
		本系統作者為 <a target="_blank"  href="https://www.facebook.com/tonylovejava">TonyQ</a>，
			若使用上有任何疑問或建議，歡迎與我聯繫。 (tonylovejava[at]gmail.com)
			<br /><br /><br />
	</div>
	<?php include("_content_nav.php");?>
</div>
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '135395566627129', // App ID from the App Dashboard
      channelUrl : '//spam.tonyq.org/channel.html', // Channel File for x-domain communication
      status     : true, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : true  // parse XFBML tags on this page?
    });

  };

  // Load the SDK's source Asynchronously
  // Note that the debug version is being actively developed and might
  // contain some type checks that are overly strict.
  // Please report such bugs using the bugs tool.
  (function(d, debug){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/zh_TW/all" + (debug ? "/debug" : "") + ".js";
     ref.parentNode.insertBefore(js, ref);
   }(document, /*debug*/ false));

</script>
<?php include("_site_footer.php"); ?>