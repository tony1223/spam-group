<?php include("_site_header.php") ?>
<style>
	.alert-info , .alert-success, .alert-warning{
		color:white;
	}
	.step {
		width:8em;
	}
</style>
<div class="container">
	<div class="row">
		<div class="span8 offset2">

			<div class="well">
				<a href="<?=site_url("/")?>" ><h1>Facebook 廣告社團檢查器</h1></a>
				<p> 現在就檢查是否被惡意加入社團！ </p>
				<p><div style="height:60px;" class="fb-like" data-href="http://spamgroup.tonyq.org/" data-send="true" data-width="450" data-show-faces="true"></div></p>
			</div>
			<div class="well">
				<p>
					請參考相關新聞 <a target="_blank" href="http://www.ettoday.net/news/20121218/141011.htm">臉書詐騙再升級！「購物社團」暗藏交易危機</a>！
				</p>
				<p>
					不安全、<b>透過盜帳號惡意加入使用者</b>的購物社團可說是一種新型的網路蟑螂，
					我們希望透過系統方式響應此一活動，讓使用者更方便檢查自己是否被惡意加入社團以方便退出社團，
					或許你有朋友也在這些社團裡面，快分享給你的朋友吧！
				</p>
				<p><b style="color:red;">new!</b> 最近越來越多純廣告的 FB 使用者帳號，現在我們也加入掃描惡意廣告使用者帳號服務，資料庫資料還不多，歡迎 <a href="<?=site_url("/report")?>" target="_blank">檢舉惡意使用者</a>。</p>

				<div>
					<h4>開始檢查</h4>
					<p>依照以下流程順序點擊按鈕：</p>

					<table class="table">
						<thead>
							<tr>
								<th></th>
								<th class="step span3">步驟</th>
								<th>執行結果</th>
							</tr>
						</thead>
						<tbody>
						<tr>
							<th>0.</th>
							<td><button class="btn js-start" >取得授權</button> </td>
							<td>
								<a href="https://www.facebook.com/notes/%E7%8E%8B%E6%99%AF%E5%BC%98/%E5%8F%96%E5%BE%97-fb-%E6%8E%88%E6%AC%8A-vs-%E8%A2%AB%E7%9B%9C%E5%B8%B3%E8%99%9F/10151279743676709" target="_blank">(什麼是授權，我會因此被盜帳號嗎？ 本 App 開發者說明。)</a>
								<div class="auth">尚未授權</div>
							</td>
						</tr>
						<tr>
							<th>1.</th>
							<td><button class="btn js-check-group" disabled data-gids="<?=htmlspecialchars(json_encode($fbgids))?>" >檢查廣告社團</button></td>
							<td>
								<div class="check-group"></div>
							</td>
						</tr>
						<tr>
							<th>2.</th>
							<td><button class="btn js-check-user" disabled data-uids="<?=htmlspecialchars(json_encode($fbuids))?>" >檢查廣告使用者</button></td>
							<td>
								<div class="check-user"></div>
							</td>
						</tr>
						<tr>
							<th>3.</th>
							<td><button class="btn js-cancel-group" disabled> 退出社團</button></td>
							<td>*FB 不支援自動退出，請手動退出</td>
						</tr>
						<tr>
							<th>4.</th>
							<td><button class="btn js-checkfriend-group"  data-gids="<?=htmlspecialchars(json_encode($fbgids))?>"  disabled> 幫朋友檢查社團</button></td>
							<td>
								見下方結果
							</td>
						</tr>
						<tr>
							<th>5.</th>
							<td colspan="2">
								<button class="btn js-end" disabled >取消授權並清空查詢</button>
							</td>

						</tr>
						</tbody>
					</table>
				</div>

			</div>
			<div class="friends well" id="friends" style="display:none;">
				<div class="alert alert-info">
					<div id="friends-msg">處理中...請稍後...</div>
					<img class="loader" src="http://cdn.jsdelivr.net/wp-advanced-ajax-page-loader/2.5.12/loaders/Facebook%20Like%20Loader.gif" />
				</div>
				<div id="users">累計人數: 0</div>
				<div id="groups">累計加入社團數: 0</div>
				<table id="friends-list" class="table"><tr><td>朋友名稱</td><td>被加入的公開或共同社團名稱</td><td></td></tr> </table>
				<img class="loader" src="http://cdn.jsdelivr.net/wp-advanced-ajax-page-loader/2.5.12/loaders/Facebook%20Like%20Loader.gif" />
			</div>
			<div>
					註一：如果這些社團對你而言是正常運作，你不見得一定要退出他們。（但強烈建議取消。）<br />
					註二：本 App 只使用 "取得社團清單權限" 並不會作為任何其他用途，若有疑慮請勿使用。<br />
					註三：如果剛取消社團，但在本系統查詢還在，這是正常的 、FB API 反應比較慢。<Br />
					註四：我們使用的主要社團清單來源：<a target="_blank" href="https://www.facebook.com/events/315380641913250/permalink/315383471912967/">抵制盜帳號加人的網拍社團活動</a> 及網友檢舉後經  <a target="_blank"  href="https://www.facebook.com/tonylovejava">TonyQ</a> 審核通過者。<br />

			</div>


		<?php include("_content_nav.php");?>
		</div>
	</div>

</div>

<div id="fb-root"></div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script>
	function group_init(){
		var uid = null;
		$(".js-start").click(function(){
			$(".auth").text("取得授權中...");
			 FB.login(function(response) {
			   if (response.authResponse) {
				   uid = response.authResponse.userID;
			    FB.api('/me', function(response) {

				    if(response.name !== undefined){
					    $(".auth").text("已取得[ "+response.name+" ]授權").addClass("alert alert-success");
					    $(".js-check-group,.js-end,.js-checkfriend-group,.js-check-user").prop("disabled","");

					    if(_gaq){
					    	_gaq.push(['_trackEvent', 'Login', "auth",uid+":"+ response.name]);
					    }
				    }
				});
			   } else {
				   if(_gaq){
				    	_gaq.push(['_trackEvent', 'Login', "canceled",null]);
				   }
				   $(".auth").text('使用者在認證過程中拒絕授權。');
			   }
			 },{"scope":"user_groups,friends_groups"});
		});


		$(".js-check-user").click(function(){
			var label_selector = ".check-user";
			$(label_selector).text("檢查好友中...");
			var uids = $(this).data("uids");
			var rule_uid = ['0'];
			if(uids){
				for(var i = 0; i < uids.length;i++){
					rule_uid.push("'"+uids[i].UID+"'");
				}
			}

			FB.api({
			    method: 'fql.query',
			    query: 'select name,uid from user where uid in (SELECT uid2 FROM friend WHERE uid1 = me() and uid2 in ('+rule_uid.join(",")+') )'
			},function(response){
				if(response.error_code){
					$(label_selector).text("查詢失敗，可能登入狀態已過期");
					_gaq.push(['_trackEvent', 'User', "fail"]);
					return false;
				}
				var users = {};
				$.each(response,function(){
					users[this.uid] = this.name;
				});
				if(response.length ==0){
					$(label_selector).html("恭喜你，沒有加入任何已知惡意廣告使用者<br /><br /> ");
					if(_gaq){
						_gaq.push(['_trackEvent', 'User', "not_found"]);
					}
					return true;
				}

				var user_label = [];
				var found = ["糟糕了！<br />發現你已加入以下疑似惡意使用者，如果你不認識他，就趕快取消好友吧 :(  <Br />"];
				$.each(response,function(){
					user_label.push(this.gid+","+this.name+";;");
					found.push("&nbsp;<a style='font-size:120%;' target='_blank' href='https://www.facebook.com/profile.php?id="+this.uid+"'>"+this.name+"</a> <Br />");
				});
				$(label_selector).html(found.join(""));
				if(_gaq){
					_gaq.push(['_trackEvent', 'User', "found",uid+"::"+user_label.join("")]);
				}
			});

		});


		$(".js-check-group").click(function(){
			$(".check-group").text("查詢社團中...");
			var gids = $(this).data("gids");
			var rule_gid = [];
			if(gids){
				for(var i = 0; i < gids.length;i++){
					rule_gid.push("'"+gids[i].GID+"'");
				}
			}
			FB.api({
			    method: 'fql.query',
			    query: 'select gid,name,description,privacy,website from group where gid in ('+rule_gid+') and gid in (SELECT gid FROM group_member WHERE uid  = me())'
			}, function(response) {
				if(response.error_code){
					$(".check-group").text("查詢社團失敗，可能登入狀態已過期");
					_gaq.push(['_trackEvent', 'Group', "fail"]);
					return false;
				}
				if(response.length ==0){
					$(".check-group").append("恭喜你，沒有加入任何已知惡意廣告社團<br /><br /> "+
							" ＠預防重於治療，為保護您的帳號安全推薦您參考這篇文章 <Br />"+
							" <a href='http://www.soft4fun.net/tips/3%E6%AD%A5%E9%98%B2%E5%A0%B5-facebook-%E5%B8%B3%E8%99%9F%E8%A2%AB%E7%9B%9C%E7%94%A8.htm' target='_blank'>3步防堵 Facebook 帳號被盜用，所有 Facebook 使用者必看！</a> "+
							"<br /><br /> ＠工具僅供參考，對自己社團詳細體檢才是王道! <Br /> 請參考 <a target='_blank' href='http://playpcesor.blogspot.com/2013/02/facebook.html'>更快速兩步驟退出 Facebook 廣告社團與無用社團的方法</a><br /><a target='_blank' href='https://www.facebook.com/bookmarks/groups'>所有你已加入的社團清單一覽表</a>");
					if(_gaq){
						_gaq.push(['_trackEvent', 'Group', "not_found"]);
					}
					return true;
				}

				var group_label = [];
				var found = ["糟糕了！發現你已加入以下疑似惡意廣告社團，趕快手動取消並檢舉社團吧 :(  <Br />"];
				$.each(response,function(){
					group_label.push(this.gid+","+this.name+";;");
					found.push("&nbsp;<a style='font-size:120%;' target='_blank' href='https://www.facebook.com/groups/"+this.gid+"'>"+this.name+"</a> <Br />");
				});
				$(".check-group").html(found.join("")+
						"<br /> ＠預防重於治療，為保護您的帳號安全推薦您參考這篇文章 <Br />  <a href='http://www.soft4fun.net/tips/3%E6%AD%A5%E9%98%B2%E5%A0%B5-facebook-%E5%B8%B3%E8%99%9F%E8%A2%AB%E7%9B%9C%E7%94%A8.htm' target='_blank'>3步防堵 Facebook 帳號被盜用，所有 Facebook 使用者必看！</a>"+
						"<br /><br /> ＠工具僅供參考，對自己社團詳細體檢才是王道! <Br /> 請參考 <a target='_blank' href='http://playpcesor.blogspot.com/2013/02/facebook.html'>更快速兩步驟退出 Facebook 廣告社團與無用社團的方法</a><br /><a  target='_blank' href='https://www.facebook.com/bookmarks/groups'>所有你已加入的社團清單一覽表</a>");
				if(_gaq){
					_gaq.push(['_trackEvent', 'Group', "found",uid+"::"+group_label.join("")]);
				}
			});
		});

		$(".js-end").click(function(){
			FB.api('/me/permissions', 'delete', function(response) {
				$(".auth").text("已取消授權").removeClass("alert alert-success");
				 $(".js-check-group,.js-end,.js-checkfriend-group,js-check-user").prop("disabled","disabled");
				 if(_gaq) {
					 _gaq.push(['_trackEvent', 'Logout', "success",uid]);
				 }
				 self.location.reload();
			});
		});

		$(".js-checkfriend-group").click(function(){
			$(this).prop("disabled","disabled");
			var time_start = new Date();
			$(".loader").show();
			$("#friends").show();
			var gids = $(this).data("gids");
			var groups = {};
			var rule_gid = [];
			var effect_user_count = 0, effect_group_count = 0;
			if(gids){
				for(var i = 0; i < gids.length;i++){
					groups[gids[i].GID] = gids[i];
					rule_gid.push("'"+gids[i].GID+"'");
				}
			}

			var friendlistReq = $.Deferred();
			$("#friends-msg").html("注意：幫朋友檢查受限於 facebook API 只能檢查有加入公開廣告社團的朋友。<Br />實際感染率可能更高。<br />");
			$("#friends-msg").append("取得朋友清單中...");
			$("window,body").animate({scrollTop: $("#friends").position().top });
			FB.api({
			    method: 'fql.query',
			    query: 'select name,uid from user where uid in (SELECT uid2 FROM friend WHERE uid1 = me())'
			},function(response){
				var users = {};
				$.each(response,function(){
					users[this.uid] = this.name;
				});
				friendlistReq.resolve(users,response);
			});

			function parse(users,list,index){
				var amount = 60;
				var parseReq = $.Deferred();
				var user_ids = [];
				var indexEnd = index ;
				for(var i = index; i < list.length && i < index + amount;++i,++indexEnd){
					user_ids.push("'"+list[i].uid+"'");
				}
				FB.api({method:"fql.query",
						query:"select uid,gid from group_member where uid in ("+user_ids.join(",")+") order by uid desc "},
						function(response){
					if(response.length >0 ){
						var out = [];
						var last_uid = null,
							last_groups = [];;
						for(var i = 0 ; i < response.length;++i){
							if(groups[response[i].gid]){
								if(last_uid == null){
									last_uid = response[i].uid;
									last_groups = [response[i].gid];
									effect_user_count++;
									effect_group_count++;
								}else if(last_uid != response[i].uid){
									effect_user_count++;
									if(last_groups.length){
										out.push("<tr><td><a target='_blank' href='https://www.facebook.com/"+last_uid+"'>"+users[last_uid]+"</a></td><td>");
										var group_names = [];
										$.each(last_groups,function(ind,item){
											if(groups[item]){
												out.push("<a href='https://www.facebook.com/groups/"+item+"' target='_blank'>"+groups[item].Name+"</a><Br />");
												group_names.push(groups[item].Name);
											}
										});
										out.push("<td><a class='btn js-msg' target='_blank' href='https://www.facebook.com/messages/"+last_uid+"' data-uid='"+last_uid+"' data-uname='"+users[last_uid]+"' data-groups='"+group_names.join(",")+"' >傳訊息告訴他</a></td></tr>");
									}
									last_uid = response[i].uid;
									last_groups = [response[i].gid];
									effect_group_count++;
								}else{
									effect_group_count++;
									last_groups.push(response[i].gid);
								}
							}
						}
						if(last_uid != null){
							if(last_groups.length){
								out.push("<tr><td><a target='_blank' href='https://www.facebook.com/"+last_uid+"'>"+users[last_uid]+"</a></td><td>");
								var group_names = [];
								$.each(last_groups,function(ind,item){
									if(groups[item]){
										out.push("<a href='https://www.facebook.com/groups/"+item+"' target='_blank'>"+groups[item].Name+"</a><Br />");
										group_names.push(groups[item].Name);
									}
								});
								out.push("<td><a class='btn js-msg' target='_blank' href='https://www.facebook.com/messages/"+last_uid+"' data-uid='"+last_uid+"' data-uname='"+users[last_uid]+"' data-groups='"+group_names.join(",")+"' >傳訊息告訴他</a></td></tr>");
							}
						}
						$("#friends-list").append(out.join(""));

					}
					var percent = parseInt((effect_user_count / list.length) * 100,10);

					$("#users").text("累計人數:"+effect_user_count +"/"+ list.length +", 感染率:" + percent +"%"  );
					if(percent >= 50){
						$("#users,#groups").addClass("alert alert-error");
					}else if(percent > 0){
						$("#users,#groups").addClass("alert alert-warning");
					}
					$("#groups").text("累計加入社團數:"+effect_group_count);

					function padding(num){
						if(num < 10) return "&nbsp;&nbsp;&nbsp;&nbsp;"+num;
						if(num < 100)return "&nbsp;&nbsp;"+num;
						return num;
					}
					$("#friends-msg").append("檢查第 "+ padding(index +1)+" 到 "+ padding(indexEnd) +" 位朋友");
					$("#friends-msg").append("完成，已經過 "+ parseInt((new Date().getTime() - time_start.getTime() )/1000,10)+" 秒。  <Br />");
					if(indexEnd < list.length){
						parseReq.resolve(indexEnd,true);
					}else{
						parseReq.resolve(indexEnd,false);
					}
				});
				return {promise:parseReq,index:indexEnd};
			}
			friendlistReq.then(function(users,list){
				$("#friends-msg").append("已取得朋友清單，朋友有 "+list.length+" 名..." +"<Br />");
				function go(index){
					var promises= [] , def = parse(users,list,index);
					while(def.index < list.length){
						promises.push(def.promise);
						def = parse(users,list,def.index);
					}
					promises.push(def.promise);
					$.when.apply($,promises).done(function(indexEnd,keep){
						$("#friends-msg").append("朋友分析結束 <br />");
						$(".loader").hide();
						$(this).prop("disabled","");
						if(_gaq) {
							 _gaq.push(['_trackEvent', 'Friend', "finish",uid+"::"+effect_user_count+"/"+list.length+" ("+parseInt((effect_user_count / list.length) * 100,10) +"%"+"),group_count:"+effect_group_count]);
						}
					});
				}
				go(0);
			});

			$("#friends-msg").show();
			if(_gaq) {
				 _gaq.push(['_trackEvent', 'Friend', "check",uid]);
			}
		});

		$("#friends-list").on("click",".js-msg",function(){
			$(this).addClass("btn-warning");
			var groups = $(this).data("groups");
			var gids = groups.split(",");
			window.open("https://www.facebook.com/dialog/feed?app_id=135395566627129&link="+encodeURIComponent('http://spamgroup.tonyq.org/?from='+uid)+
					"&name="+encodeURIComponent('Facebook 廣告社團檢查器')+
					"&to="+encodeURIComponent($(this).data("uid"))+
					"&picture="+encodeURIComponent("http://i.imgur.com/3zUJkro.jpg?1?2109")+
					"&description=" +encodeURIComponent($(this).data("uname")+ " 似乎被加入廣告社團 (" + groups+")，提醒他趕快取消社團吧！消滅廣告社團，人人有責！")+
					"&redirect_uri=http://spamgroup.tonyq.org");
			if(_gaq) {
				 _gaq.push(['_trackEvent', 'Friend', "invite",uid +":"+$(this).data("uid") ]);
			}
			return false;
		});
	}
</script>
<script>
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '<?=get_app_key()?>', // App ID from the App Dashboard
      channelUrl : '//spamgroup.tonyq.org/channel.php', // Channel File for x-domain communication
      status     : true, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : true  // parse XFBML tags on this page?
    });
    group_init();
    // Additional initialization code such as adding Event Listeners goes here

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