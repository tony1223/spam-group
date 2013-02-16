<?php include("_site_header.php") ?>
<div class="container">
	<div class="row">
		<div class="span8 offset2">

			<div class="well">
				<a href="<?=site_url("/")?>"  ><h1>Facebook 廣告社團檢查器</h1></a>
				<p> 現在就檢查是否被惡意加入社團！ </p>
				<p><div style="height:60px;" class="fb-like" data-href="http://spamgroup.tonyq.org/" data-send="true" data-width="450" data-show-faces="true"></div></p>
			</div>
			<div class="well">
				<h2>回報新廣告社團</h2>
				<div>
					<form class="navbar-form" method="post" onsubmit="return false;" id="check-form">
						請貼上要回報的廣告社團網址<Br />
						<input type="text" class="span6" id="group" value="<?=htmlspecialchars($gurl)?>">
						<button type="button" id="check" class="btn js-start" disabled>FB api 載入中</button>
					</form>
					範例：
					<ul>
						<li>
							https://www.facebook.com/groups/z369w/
						</li>
						<li>
							http://www.facebook.com/groups/279611548826087/
						</li>
					</ul>
					<div id="msg" class="alert" style="display:none;"></div>
					<button type="button" id="check" class="btn js-end" disabled="disabled">取消授權並離開</button>
					<br />
					註：Facebook 要求要取得授權才能搜尋到大多數社團（非公開、秘密），所以需要登入。 <br />
				</div>
			</div>
			<div class="groups well" id="groups" style="display:none;">
				查詢紀錄
				<table class="table" >
					<tr>
						<td>社團編號(gid)</td>
						<td>社團名稱</td>
						<td>狀態</td>
						<td>檢舉情形</td>
					</tr>
					<tbody id="group-info"></tbody>
				</table>
			</div>
			<?php include("_content_nav.php");?>
		</div>
	</div>

	<div class="groups span8 offset1 well" id="groups" style="display:none;">
		查詢紀錄
		<table class="table" >
			<tr>
				<td>社團編號(gid)</td>
				<td>社團名稱</td>
				<td>狀態</td>
				<td>檢舉情形</td>
			</tr>
			<tbody id="group-info"></tbody>
		</table>
	</div>
	<div class="span8 offset1">
		本系統作者為 <a target="_blank"  href="https://www.facebook.com/tonylovejava">TonyQ</a>，
			若使用上有任何疑問或建議，歡迎與我聯繫。 (tonylovejava[at]gmail.com)
			<br />
			<br />
	</div>
	<?php include("_content_nav.php");?>
</div>

<div id="fb-root"></div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script>
	var escapeHTML = (function () {
	    'use strict';
	    var chr = { '"': '&quot;', '&': '&amp;', '<': '&lt;', '>': '&gt;' };
	    return function (text) {
	        return text.replace(/[\"&<>]/g, function (a) { return chr[a]; });
	    };
	}());
	var PRIVACYS = { "CLOSED":"不公開","SECRET":"秘密","OPEN":"公開" };
	function fb_init(){
		var uid = null , uname = null;
		$(".js-start").on("click.auth",function(){
			if($("#check").data("checked") != null){
				return true;
			}
			 FB.login(function(response) {
			   if (response.authResponse) {
				   uid = response.authResponse.userID;
			    FB.api('/me', function(response) {
				    if(response.name !== undefined){
				    	uname = response.name;
					    $("#check").data("checked","1");
					    $("#check").trigger("click");
					    $(".js-end").prop("disabled","");
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

		function check(){
			if($("#check").data("checked") == null){
				return true;
			}
			$("#msg").removeClass("alert-warning").hide();

			//1.從網址取得  gid 或 identify
			var url = $("#group").val()+"/";// "/" just in case that not end with "/"
			var matchs = url.match(/\/groups\/(.*?)\//);
			if(!(matchs && matchs[1] )){
				$("#msg").text("網址無法解析").addClass("alert-warning").show();
				return false;
			}

			var gid = matchs[1];
			$("#check").trigger("checkgroup",gid);

		}

		$("#check-form").on("submit",function(){
			$("#check").trigger("click");
			return false;
		});
		$("#check").on("click",check);
		$("#check").on("checkgroup",function(e,gid){
			var checkgid = $.Deferred();
			//檢查是不是 gid
			FB.api({
			    method: 'fql.query',
			    query: 'select gid,name,description,privacy,website from group where gid = \''+gid+'\''
			}, function(response) {
				if(!response.length){
					checkgid.reject(gid);
					return false;
				}
				checkgid.resolve(response[0]);
			});

			//2.1如果是 gid 的情況
			checkgid.done(function(group){
				//CLOSED = 不公開
				//Open = 公開
				//Secret = 秘密
				$("#msg").removeClass("alert-warning").hide();
				$.get("<?=site_url("/group/js_report_gid/")?>",{gid:group.gid},function(res){
					var server_info = JSON.parse(res);
					$("#groups").show();

					var out = [];
					out.push("<tr>");
					out.push("<td>"+escapeHTML(group.gid)+"</td>");
					out.push("<td>"+escapeHTML(group.name)+"</td>");
					out.push("<td>"+escapeHTML(PRIVACYS[group.privacy])+"</td>");
					if(server_info == null){
						out.push("<td class='status-"+group.gid+"' >尚未有人檢舉 <a href='javascript:void 0;' class='js-report btn' data-group='"+escapeHTML(JSON.stringify(group))+"'>馬上檢舉</a></td>");
					}else if(server_info.Enabled == "1"){
						out.push("<td>已於 "+server_info.ModifyDate+" 列入廣告社團清單</td>");
					}else{
						out.push("<td class='status-"+group.gid+"' >已於 " + server_info.CreateDate +" 檢舉，尚在審核中。<a href='javascript:void 0;' class='js-report-again btn' data-group='"+escapeHTML(JSON.stringify(group))+"'>檢舉 +1 </a></td>");
					}

					out.push("</tr>");
					$("#group-info").prepend(out.join(""));
				});
			});


			//2.2 如果不是 gid 的情況
			checkgid.fail(function(gid){
		        FB.api("/search?q="+encodeURIComponent(gid)+"&type=group", function(response){
			        if(!(response && response.data && response.data.length)){
			        	$("#msg").text("查無任何資料").addClass("alert-warning").show();
			        	return true;
			        }
		        	$("#msg").removeClass("alert-warning").hide();
			        var gid = response.data[0].id;
			        $("#check").trigger("checkgroup",gid);
		        });
			});
		});
		$(".js-end").click(function(){
			FB.api('/me/permissions', 'delete', function(response) {
				if(_gaq) {
					 _gaq.push(['_trackEvent', 'Logout', "success",uid]);
				}
				self.location.reload();
			});
		});

		$("#groups").on("click",".js-report",function(){
			var group = $(this).data("group");
			if(group == null){
				alert("未知的例外情形，group 不存在。");
				return false;
			}
			//post attributes: gid, name, privacy
			$.post("<?=site_url("/group/js_insert_group/")?>",$.extend({uid:uid,uname:uname},group),function(res){
				var info = JSON.parse(res);

				if(info.IsSuccess){
					$(".status-"+group.gid).text("檢舉成功，管理員將會儘快進行審核。");
				}else{
					if(info && info.ErrorMessage){
						alert(info.ErrorMessage);
					}else{
						alert("檢舉時發生錯誤");
					}
				}
			});
		});
		$("#groups").on("click",".js-report-again",function(){
			var group = $(this).data("group");
			if(group == null){
				alert("未知的例外情形，group 不存在。");
				return false;
			}
			//post attributes: gid, name, privacy
			$.post("<?=site_url("/group/js_report_group/")?>",$.extend({uid:uid,uname:uname},group),function(res){
				var info = JSON.parse(res);

				if(info.IsSuccess){
					$(".status-"+group.gid).text("謝謝您的意見，我們將會作為審核參考。");
				}else{
					if(info && info.ErrorMessage){
						alert(info.ErrorMessage);
					}else{
						alert("檢舉時發生錯誤");
					}
				}
			});
		});

		$("#check").text("取得授權後檢查").prop("disabled","");
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
    setTimeout(function(){
    	fb_init();
    },2000);
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