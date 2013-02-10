<?php include("_site_header.php") ?>

<div class="container">
	<div class="well span8">
		<h1>Facebook 廣告社團檢查器</h1>
		<p> 現在就檢查是否被惡意加入社團！ </p>
		<p><div style="height:60px;" class="fb-like" data-send="false" data-width="450" data-show-faces="true"></div></p>
	</div>
	<div class="well span8 row">
		<p>
			請參考 <a target="_blank" href="https://www.facebook.com/events/315380641913250/?ref=22">抵制盜帳號加人的網拍社團</a> 一活動，

			我們希望透過系統方式響應此一活動，讓使用者更方便檢查自己是否被惡意加入社團以方便退出社團，
			或許你有朋友也在這些社團裡面，快分享給你的朋友吧！
		</p>
		<div>
			依照以下流程順序點擊按鈕：<Br />
			<button class="js-start" >取得授權</button>  -&gt;
			<button class="js-check-group" disabled data-gids="<?=htmlspecialchars(json_encode($fbgids))?>" >檢查社團</button> -&gt;
			<button class="js-cancel-group" disabled> 退出社團（FB 尚不支援自動退出，請先手動退出）</button>
			<button class="js-end" disabled >取消授權並結束</button>
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<button class="js-checkfriend-group"  data-gids="<?=htmlspecialchars(json_encode($fbgids))?>"  disabled> 幫朋友檢查(朋友多可能會花上好幾分鐘 )</button>
		</div>
		<br />
		<div>
			目前狀態：<br />
			<div class="auth">尚未授權</div>
			<div class="check-group"></div>
		</div>
		<br />
		<br />
		<br />
	</div>
	<div class="friends span8 row well" id="friends" style="display:none;">
		<div class="alert alert-info">
			<div id="friends-msg">處理中...請稍後...</div>
			<img class="loader" src="http://cdn.jsdelivr.net/wp-advanced-ajax-page-loader/2.5.12/loaders/Facebook%20Like%20Loader.gif" />
		</div>
		<div id="users">累計人數: 0</div>
		<div id="groups">累計加入社團數: 0</div>
		<table id="friends-list" class="table"><tr><td>朋友名稱</td><td>被加入社團名稱</td><td></td></tr> </table>
		<img class="loader" src="http://cdn.jsdelivr.net/wp-advanced-ajax-page-loader/2.5.12/loaders/Facebook%20Like%20Loader.gif" />
	</div>
	<div class="span8 row">
			註一：如果這些社團對你而言是正常運作，你不見得一定要退出他們。（但強烈建議取消。）<br />
			註二：本 App 只使用 "取得社團清單權限" 並不會作為任何其他用途，若有疑慮請勿使用。<br />
			註三：如果剛取消社團，但在本系統查詢還在，這是正常的 、FB API 反應比較慢。<Br />
			註四：我們使用的社團清單來源。<a target="_blank" href="https://www.facebook.com/events/315380641913250/permalink/315383471912967/">https://www.facebook.com/events/315380641913250/permalink/315383471912967/</a>
	</div>
	<div class="span8 row">
		本系統作者為 <a target="_blank"  href="https://www.facebook.com/tonylovejava">TonyQ</a>，
			若使用上有任何疑問或建議，歡迎與我聯繫。 (tonylovejava[at]gmail.com)
			<br /><br /><br />
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
					       // console.log('Good to see you, ' + response.name + '.');
					    $(".auth").text("已取得[ "+response.name+" ]授權").addClass("alert alert-success");
					    $(".js-check-group,.js-end,.js-checkfriend-group").prop("disabled","");

					    if(_gaq){
					    	_gaq.push(['_trackEvent', 'Login', "auth",uid+":"+ response.name]);
					    }

					    //for sync group name
//					    FB.api({method:'fql.query' , query:"select gid,name,description,privacy,website from group where gid in ('288453261255015','502847343061097','486117138105564','303829493050978','544349895580368','454348407956905','487339627955944','294469563997821','197406740394839','549341308427237','51846938151947','149485061856765','281355618634164','368979096447620','135324916620926','580304585328398','130069680490180','151108021708844','428137350592355','192874664188070','322078994561043','130879377068225','226593457473705','235827556549566','490087924347080','447660248630976','441465562568460','405578422847509','379319895487789','541592049202053','130686313755715','301780486597996','475681209145232','379409165437519','16194977395156','455146834522091','552583854757548','174465326010669','394116947337943','431840593544115','272908029478705','106655629505918','171363333002844','388643757891013','123707981117961','315155521933854','158403270971445','140806159405825','309625119151799','369434166486956','389083631178208','111144072390811','317442125028259','123430334489173','521715534520209','311879082256934','421477871258747','400596413349026','153222354825174','538413139521417','473184829391903','307377356037441','299369086847347','111144072390811','477010119006988','399837533418350','359566620806152','562193740463686','500021783365712','127005547465277','318009358300514','401239963293921','555118037832362','536768793011133','187811578027793','118804804956167','323914101057128','279611548826087','112171405611041','545213742175314','568017226548520','134101623417139','526898750664878','313265392124306','475742619128030','480184282028569','394727773954259','272519399541007','124937971000982','451059504945072','356724847769142','249290215197246','464184063640846','333185553460713','458755644190360','588147971201728','492073050845032','332231833556790','452823894757985','291307964314857','412883918794430','482310918492217','295859567203786','275483432578929','448411455212153','309625119151799','366014400164638','465972536785064','576441372382751','121957201294853','1670260756445826','100324593458287','201744453231386','127214820703794','133806883447509','389491097800654','310375788976107','363165567114424') " },function(response){
//						    var out =[];
//						    $.each(response,function(){
//							    out.push("update spamgroup set Name = '"+this.name+"' where GID = '"+this.gid+"';");
//						    });
//					    	console.log(out.join("\n"));
//					    });
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
					$(".check-group").text("恭喜你，沒有加入任何已知惡意廣告社團");
					if(_gaq){
						_gaq.push(['_trackEvent', 'Group', "not_found"]);
					}
					return true;
				}

				var group_label = [];
				var found = ["糟糕了！發現你已加入以下疑似惡意廣告社團，趕快手動取消並檢舉吧 :(  <Br />"];
				$.each(response,function(){
					group_label.push(this.gid+","+this.name+";;");
					found.push("&nbsp;<a style='font-size:120%;' target='_blank' href='https://www.facebook.com/groups/"+this.gid+"'>"+this.name+"</a> <Br />");
				});
				$(".check-group").html(found.join(""));
				if(_gaq){
					_gaq.push(['_trackEvent', 'Group', "found",uid+"::"+group_label.join("")]);
				}
			});
		});

		$(".js-end").click(function(){
			FB.api('/me/permissions', 'delete', function(response) {
				$(".auth").text("已取消授權").removeClass("alert alert-success");
				 $(".js-check-group,.js-end,.js-checkfriend-group").prop("disabled","disabled");
				 if(_gaq) {
					 _gaq.push(['_trackEvent', 'Logout', "success",uid]);
				 }
			});
		});

		$(".js-checkfriend-group").click(function(){
			$(this).prop("disabled","disabled");
			$(".loader").show();
			$("#friends").show();
			var gids = $(this).data("gids");
			var groups = {};
			var rule_gid = [];
			var effect_user_count = 0, effect_group_count = 0;
			if(gids){
				for(var i = 0; i < gids.length;i++){
					groups[gids[i].GID] = gids[i].Name;
					rule_gid.push("'"+gids[i].GID+"'");
				}
			}

			var friendlistReq = $.Deferred();
			$("#friends-msg").text("取得朋友清單中...");
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
				var amount = 50;
				var parseReq = $.Deferred();
				var user_ids = [];
				var indexEnd = index ;
				for(var i = index; i < list.length && i < index + amount;++i,++indexEnd){
					user_ids.push("'"+list[i].uid+"'");
				}
				$("#friends-msg").append("檢查第 "+ (index +1)+" 到 "+ (indexEnd) +" 位朋友...");
				FB.api({method:"fql.query",
						query:"select uid,gid from group_member where gid in ("+rule_gid.join(",")+") and uid in ("+user_ids.join(",")+") order by uid desc "},
						function(response){
					if(response.length >0 ){
						var out = [];
						var last_uid = null,
							last_groups = [];;

						for(var i = 0 ; i < response.length;++i){
							if(last_uid == null){
								last_uid = response[i].uid;
								last_groups = [response[i].gid];
								effect_user_count++;
								effect_group_count++;
							}else if(last_uid != response[i].uid){
								effect_user_count++;
								if(last_groups.length){
									out.push("<tr><td><a target='_blank' href='https://www.facebook.com/"+last_uid+"'>"+users[last_uid]+"</a></td><td>");
									$.each(last_groups,function(ind,item){
										out.push("<a href=''>"+groups[item]+"</a><Br />");
									});
									out.push("<td><a class='btn' target='_blank' href='https://www.facebook.com/messages/"+last_uid+"'>傳訊息告訴他</a></td></tr>");
								}
								last_uid = response[i].uid;
								last_groups = [response[i].gid];
								effect_group_count++;
							}else{
								effect_group_count++;
								last_groups.push(response[i].gid);
							}
						}
						if(last_uid != null){
							if(last_groups.length){
								out.push("<tr><td><a target='_blank' href='https://www.facebook.com/"+last_uid+"'>"+users[last_uid]+"</a></td><td>");
								$.each(last_groups,function(ind,item){
									out.push("<a href=''>"+groups[item]+"</a><Br />");
								});
								out.push("<td><a class='btn' target='_blank' href='https://www.facebook.com/messages/"+last_uid+"'>傳訊息告訴他</a></td></tr>");
							}
						}
						$("#friends-list").append(out.join(""));

					}
					$("#users").text("累計人數:"+effect_user_count +"/"+ list.length +", 感染率:" + parseInt((effect_user_count / list.length) * 100,10) +"%"  );
					$("#groups").text("累計加入社團數:"+effect_group_count);
					$("#friends-msg").append("完成 ("+ parseInt((indexEnd / list.length) *100,10)+"%) <Br />");
					if(indexEnd < list.length){
						parseReq.resolve(indexEnd,true);
					}else{
						parseReq.resolve(indexEnd,false);
					}
				});
				return parseReq;
			}
			friendlistReq.then(function(users,list){
				$("#friends-msg").append("已取得朋友清單，朋友有 "+list.length+" 名..." +"<Br />");
				function go(index){
					var def = parse(users,list,index);
					def.then(function(indexEnd,keep){
						console.log(arguments);
						if(keep){
							go(indexEnd);
						}else{
							$("#friends-msg").append("朋友分析結束 <br />");
							$(".loader").hide();
							$(this).prop("disabled","");
							if(_gaq) {
								 _gaq.push(['_trackEvent', 'Friend', "finish",uid+"::"+effect_user_count+"/"+list.length+" ("+parseInt((effect_user_count / list.length) * 100,10) +"%"+"),group_count:"+effect_group_count]);
							}
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
     js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
     ref.parentNode.insertBefore(js, ref);
   }(document, /*debug*/ false));

</script>
<?php include("_site_footer.php"); ?>