<?php include("_site_header.php") ?>

<style>
	.group-confirm .read-user{
		background:gray;
	}
	.group-confirm .confirm-user{
		background:red;
	}

</style>
<div class="container group-confirm">
	<div class="row">
		<div class="span8 offset2">
			<div class="well">
				<a href="<?=site_url("/")?>" ><h1>Facebook 廣告社團檢查器</h1></a>
				<p> 現在就檢查是否被惡意加入社團！ </p>
				<p><div style="height:60px;" class="fb-like" data-href="http://spamgroup.tonyq.org/" data-send="true" data-width="450" data-show-faces="true"></div></p>
			</div>
			<div class="well">
				<button class="js-admin-auth btn">登入管理員（Tony Wang 限定）</button>
				<br />
				<br />
				審查中社團清單 (總數<?=count($fbuids)?>)
				<table class="table">
					<tr>
						<td>Group ID</td>
						<td>名字</td>
						<Td>被使用者封鎖</Td>
						<td>加入時間</td>
						<td>好友數量</td>
						<Td>+1數量</Td>
						<td></td>
					</tr>
					<?php foreach($fbuids as $user){ ?>
						<tr class="<?=(isset($_SESSION["admin"]) && $user->Read)?"read-user":"" ?>">
							<td><?=$user->UID?></td>
							<td><a href="https://www.facebook.com/profile.php?id=<?=htmlspecialchars($user->UID)?>" target="_blank"><?=htmlspecialchars($user->Name)?></a></td>
							<td><?=$user->IsBlocked ? "封鎖": ""?></td>
							<td><?=$user->CreateDate?></td>
							<td><?=$user->FriendCount?></td>
							<td><?=$user->RequestCount?></td>
							<?php if( isset($_SESSION["admin"])  ){?>
							<td>
								<a class="btn js-confirmed"  href="javascript:void 0;" data-gid="<?=htmlspecialchars($user->UID)?>">通過</a>
								<a class="btn js-mark"  href="javascript:void 0;" data-uid="<?=htmlspecialchars($user->UID)?>"  data-read="<?=htmlspecialchars($user->Read ? "1" :"0")?>">
									<?php if($user->Read){?>
										標為未讀
									<?php }else{?>
										標為已讀
									<?php }?>
								</a>
							</td>
							<?php }?>
						</tr>
					<?php } ?>
				</table>
			</div>
			<?php include("_content_nav.php");?>
		</div>
	</div>
</div>
<div id="fb-root"></div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script>
	function fb_init(){
		$(".js-admin-auth").click(function(){
			 FB.login(function(response) {
			   if (response.authResponse) {
				   uid = response.authResponse.userID;
				   $.get("<?=site_url("/group/admin_login")?>?access_token="+FB.getAccessToken(),function(){
					   self.location.reload();
				   });
			   }
			 },{"scope":"user_groups,friends_groups"});
		});

		$(".js-confirmed").click(function(){
			var self = this;
			$.post("<?=site_url("/user/js_confirming")?>",{uid:$(this).data("gid")},function(res){
				var obj = JSON.parse(res);
				if(obj.IsSuccess){
					$(self).text("已審核完成").prop("disabled","disabled");
					$(self).parents("tr").addClass("confirm-group");
				}else{
					$(self).text("審核失敗，再試一次");
				}
			});
		});
		$(".js-mark").click(function(){
			var self = this;
			$.post("<?=site_url("/user/js_mark_as_read")?>",{uid:$(this).data("uid"),read:$(this).data("read")},function(res){
				var obj = JSON.parse(res);
				if(obj.IsSuccess){
					$(self).text(obj.Data.Status);
					$(self).data("read",obj.Data.Read);

					if(obj.Data.Read == 1 ){
						$(self).parents("tr").addClass("read-user");
					}else{
						$(self).parents("tr").removeClass("read-user");
					}

				}else{
					alert(obj.ErrorMessage);
				}
			});
		});
	}
</script>
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
	fb_init();
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
