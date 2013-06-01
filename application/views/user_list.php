<?php include("_site_header.php") ?>

<div class="container">
	<div class="row">
		<div class="span8 offset2">

			<div class="well">
				<a href="<?=site_url("/")?>" ><h1>Facebook 廣告社團檢查器</h1></a>
				<p> 現在就檢查是否被惡意加入社團！ </p>
				<p><div style="height:60px;" class="fb-like" data-href="http://spamgroup.tonyq.org/" data-send="true" data-width="450" data-show-faces="true"></div></p>
			</div>
			<div class="well">
				使用者清單(其他格式 <a href="<?=site_url("/users/json/")?>">JSON</a> 、 <a href="<?=site_url("/users/jsonp/?jsonp=parser")?>">JSONP</a> ），總數 <?=count($fbuids)?>個。

				<table class="table">
					<tr><td>User ID</td><td>名字</td><td>檢舉時好友數量</td><td>被檢舉時間</td></tr>
					<?php foreach($fbuids as $user){ ?>
						<tr>
							<td><?=$user->UID?></td>
							<td><a href="https://www.facebook.com/groups/<?=$user->UID?>" target="_blank"><?=htmlspecialchars($user->Name)?></a></td>
							<td><?=htmlspecialchars($user->FriendCount)?></td>
							<td><?=$user->CreateDate?></td>
						</tr>
					<?php } ?>
				</table>
			</div>
			<div>
				註一：如果這些使用者是你真正的朋友，請來信告知我們。(tonylovejava[at]gmail.com )<br />
				註二：本 App 只使用 "取得社團清單權限" 並不會作為任何其他用途，若有疑慮請勿使用。<br />
				註三：如果剛取消社團，但在本系統查詢還在，這是正常的 、FB API 反應比較慢。<Br />
			</div>
			<?php include("_content_nav.php");?>
		</div>
	</div>
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