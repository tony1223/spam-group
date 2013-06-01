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
		        <div id="chart" style="min-width: 400px; height: 400px; margin: 0 auto" data-group_count="<?=$group_count?>" data-confirm_date="<?=$confirm_avg_date?>" data-info="<?=htmlspecialchars(json_encode($chart_data)) ?>"></div>
			</div>
			<div class="well">
				<h2>回報新廣告社團</h2>
				<div>
					<form class="navbar-form" method="post" onsubmit="return false;" id="check-form">
						請貼上要回報的廣告社團網址<Br />
						<input type="text" class="span6" id="group" value="<?=htmlspecialchars($gurl)?>">
						<button type="button" id="check" class="btn js-user-login js-start" disabled>FB api 載入中</button>
					</form>
					<br>
					網址範例：
					<ul>
						<li>
							https://www.facebook.com/groups/z369w/
						</li>
						<li>
							http://www.facebook.com/groups/279611548826087/
						</li>
					</ul>
					<p>
					註：Facebook 要求要取得授權才能搜尋到大多數社團（非公開、秘密），所以需要登入。
					</p>
					<div id="msg" class="alert" style="display:none;"></div>
					<button type="button" class="btn js-end" disabled="disabled">取消授權並清空查詢</button>
				</div>
			</div>
			<div class="groups well" id="groups" style="display:none;">
				社團查詢紀錄
				<table class="table" >
					<tr>
						<td>社團編號(gid)</td>
						<td>社團名稱</td>
						<td>狀態</td>
						<td>建立者</td>
						<td>檢舉情形</td>
					</tr>
					<tbody id="group-info"></tbody>
				</table>
			</div>
			<div class="well">
				<h2>回報新廣告使用者</h2>
				<div>
					<form class="navbar-form" method="post" onsubmit="return false;" id="check-user-form">
						請貼上要回報的廣告使用者個人動態時報頁<Br />
						<input type="text" class="span6" id="user" value="<?=htmlspecialchars($uurl)?>">
						<button type="button" id="check-user" class="btn js-user-login js-user-start" disabled>FB api 載入中</button>
					</form>
					<br>
					網址範例：
					<ul>
						<li>
							https://www.facebook.com/profile.php?id=100005972521678
						</li>
						<li>
							https://www.facebook.com/profile.php?id=100005973450961
						</li>
					</ul>
					<p>
					註：Facebook 要求要取得授權才能搜尋到大多數社團（非公開、秘密），所以需要登入。
					</p>
					<div id="msg-user" class="alert" style="display:none;"></div>
					<button type="button" class="btn js-end" disabled="disabled">取消授權並清空查詢</button>
				</div>
			</div>
			<div class="users well" id="users" style="display:none;">
				使用者查詢紀錄
				<table class="table" >
					<tr>
						<td>使用者編號(uid)</td>
						<td>使用者名稱</td>
						<td>檢舉情形</td>
					</tr>
					<tbody id="user-info"></tbody>
				</table>
			</div>
			<?php include("_content_nav.php");?>
		</div>
	</div>

</div>

<div id="fb-root"></div>

<!-- 檢查相關 -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script>
	var escapeHTML = (function () {
	    'use strict';
	    var chr = { '"': '&quot;', '&': '&amp;', '<': '&lt;', '>': '&gt;' };
	    return function (text) {
		    if(text == null){
			    return "";
		    }
	        return text.replace(/[\"&<>]/g, function (a) { return chr[a]; });
	    };
	}());
	var PRIVACYS = { "CLOSED":"不公開","SECRET":"秘密","OPEN":"公開" };
	var groups = {};
	function fb_init(){
		var uid = null , uname = null;
		var checked = false;
		$(".js-start").on("click.auth",function(){
			if(checked){
				return true;
			}
			 FB.login(function(response) {
			   if (response.authResponse) {
				   uid = response.authResponse.userID;
			    FB.api('/me', function(response) {
				    if(response.name !== undefined){
				    	uname = response.name;
				    	checked = true;
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

		$(".js-user-start").on("click.auth",function(){
			if(checked){
				return true;
			}
			 FB.login(function(response) {
			   if (response.authResponse) {
				   uid = response.authResponse.userID;
			    FB.api('/me', function(response) {
				    if(response.name !== undefined){
				    	uname = response.name;
				    	checked = true;
					    $("#check-user").trigger("click");
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
			if(!checked){
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

		/* --------------- check group ---------------- */
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
			    query: 'select gid,name,description,privacy,website,creator from group where gid = \''+gid+'\''
			}, function(response) {
				if(!response.length){
					checkgid.reject(gid);
					return false;
				}

				FB.api("/"+gid, function(graph_group){
					var group = response[0];
					if(graph_group && graph_group.owner){
						group.creator = graph_group.owner.id;
						group.creatorName = graph_group.owner.name;
					}

					checkgid.resolve(group);
				});
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
					out.push("<td>")
					if(group.creator){
						out.push("<a target='_blank' href='https://www.facebook.com/profile.php?id="+escapeHTML(group.creator)+"'>"+escapeHTML(group.creatorName)+"</a>")
					}
					out.push("</td>");
					if(server_info == null){
						out.push("<td class='status-"+group.gid+"' >尚未有人檢舉 <a href='javascript:void 0;' class='js-report btn' data-group='"+escapeHTML(group.gid)+"'>馬上檢舉</a></td>");
					}else if(server_info.Enabled == "1"){
						out.push("<td>已於 "+server_info.ModifyDate+" 列入廣告社團清單</td>");
					}else{
						out.push("<td class='status-"+group.gid+"' >已於 " + server_info.CreateDate +" 檢舉，尚在審核中。<a href='javascript:void 0;' class='js-report-again btn' data-group='"+escapeHTML(group.gid)+"'>檢舉 +1 </a></td>");
					}
					groups[group.gid]= group;
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
				checked = false;
				self.location.reload();
			});
		});

		$("#groups").on("click",".js-report",function(){
			var groupid = $(this).data("group"),
				group = groups[groupid];
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
			var groupid = $(this).data("group"),
			group = groups[groupid];
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


		/*------------------ check user -------------------------------*/

		function check_user(){
			if(!checked){
				return true;
			}
			$("#msg").removeClass("alert-warning").hide();

			//1.從網址取得  gid 或 identify
			var url = $("#user").val()+"/";// "/" just in case that not end with "/"
			var matchs = url.match(/\/profile\.php\?id=([0-9]+).*/);
			if(!(matchs && matchs[1] )){
				$("#msg-user").text("網址無法解析").addClass("alert-warning").show();
				return false;
			}

			var uid = matchs[1];
			$("#check-user").trigger("checkuser",uid);

		}

		/* --------------- check group ---------------- */
		$("#check-user-form").on("submit",check_user);
		$("#check-user").on("click",check_user);
		$("#check-user").on("checkuser",function(e,uid){
			var checkuid = $.Deferred();
			//檢查是不是 gid
			FB.api({
			    method: 'fql.query',
			    query: 'select uid,first_name,last_name,is_blocked,friend_count,friend_request_count from user where uid = \''+uid+'\''
			}, function(response) {
				if(!response.length){
					checkuid.reject(uid);
					return false;
				}

				checkuid.resolve(response[0]);
			});

			//2.1如果是 gid 的情況
			checkuid.done(function(user){
				//CLOSED = 不公開
				//Open = 公開
				//Secret = 秘密
				$("#msg").removeClass("alert-warning").hide();
				$.get("<?=site_url("/user/js_report_uid/")?>",{uid:user.uid},function(res){
					var server_info = JSON.parse(res);
					$("#users").show();

					var out = [];
					out.push("<tr>");
					out.push("<td>"+escapeHTML(user.uid)+"</td>");
					out.push("<td><a target='_blank' href='https://www.facebook.com/profile.php?id="+escapeHTML(user.uid)+"'>"+escapeHTML(user.last_name +" "+user.first_name)+"</a>")
					if(server_info == null){
						out.push("<td class='status-user-"+user.uid+"' >尚未有人檢舉 <a href='javascript:void 0;' class='js-report-user btn' data-user='"+escapeHTML(user.uid)+"'>馬上檢舉</a></td>");
					}else if(server_info.Enabled == "1"){
						out.push("<td>已於 "+server_info.ModifyDate+" 列入廣告使用者清單</td>");
					}else{
						out.push("<td class='status-"+user.uid+"' >已於 " + server_info.CreateDate +" 檢舉，尚在審核中。");

								//"<a href='javascript:void 0;' class='js-report-user-again btn' data-user='"+escapeHTML(user.uid)+"'>檢舉 +1 </a></td>");
					}
					users[user.uid]= user;
					out.push("</tr>");
					$("#user-info").prepend(out.join(""));
				});
			});


			//2.2 如果不是 gid 的情況
//			checkgid.fail(function(gid){
//		        FB.api("/search?q="+encodeURIComponent(gid)+"&type=group", function(response){
//			        if(!(response && response.data && response.data.length)){
//			        	$("#msg").text("查無任何資料").addClass("alert-warning").show();
//			        	return true;
//			        }
//		        	$("#msg").removeClass("alert-warning").hide();
//			        var gid = response.data[0].id;
//			        $("#check").trigger("checkgroup",gid);
//		        });
//			});
		});
		$("#users").on("click",".js-report-user",function(){
			var uid = $(this).data("user"),
				user = users[uid];
			if(user == null){
				alert("未知的例外情形，user 不存在。");
				return false;
			}
			//post attributes: gid, name, privacy
			$.post("<?=site_url("/user/js_insert_user/")?>",$.extend({report_uid:uid,report_uname:uname},user),function(res){
				var info = JSON.parse(res);

				if(info.IsSuccess){
					$(".status-user-"+user.uid).text("檢舉成功，管理員將會儘快進行審核。");
				}else{
					if(info && info.ErrorMessage){
						alert(info.ErrorMessage);
					}else{
						alert("檢舉時發生錯誤");
					}
				}
			});
		});
		$("#users").on("click",".js-report-user-again",function(){
			var uid = $(this).data("user"),
			user = users[uid];
			if(user == null){
				alert("未知的例外情形，user 不存在。");
				return false;
			}
			//post attributes: gid, name, privacy
			$.post("<?=site_url("/user/js_report_user/")?>",$.extend({report_uid:uid,report_uname:uname},user),function(res){
				var info = JSON.parse(res);

				if(info.IsSuccess){
					$(".status-"+user.uid).text("謝謝您的意見，我們將會作為審核參考。");
				}else{
					if(info && info.ErrorMessage){
						alert(info.ErrorMessage);
					}else{
						alert("檢舉時發生錯誤");
					}
				}
			});
		});

		$("#check,#check-user").text("取得授權後檢查").prop("disabled","");
	}
</script>


<!-- Chart info -->
<script src="<?=base_url("js/highcharts.js")?>" ></script>
<script>
$(function () {
	var chart,
		info = $("#chart").data("info"),
		avg_date = $("#chart").data("confirm_date"),
		group_count = $("#chart").data("group_count");

	var series = [];

	var columns = [], colobj ={}, infoObj = {};
	for(var key in info){
		infoObj[key] = infoObj[key] ||{};
		for(var i = 0 ,len = info[key].length; i < len ; ++i){
			if (colobj[info[key][i].report_date] == null){
				columns.push(info[key][i].report_date);
				colobj[info[key][i].report_date] = 1;
			}
			infoObj[key][info[key][i].report_date] = info[key][i];
		}
	}
	columns.sort(function(obj1,obj2){
		return new Date(obj1) > new Date(obj2);
	});
	for(var key in info){
		var values = [];
		for(var i = 0 ,len = columns.length; i < len ; ++i){
			if (infoObj[key][columns[i]]){
				values.push(parseInt(infoObj[key][columns[i]].group_count,10));
			}else{
				values.push(0);
			}
		}
		series.push({
			name: key,
			data: values
		});
	}

	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'chart',
			type: 'line',
			marginRight: 130,
			marginBottom: 25
		},
		title: {
			text: '廣告社團回報情形與審核通過情形一覽 ',
			x: -20 //center
		},
		subtitle: {
			text: '平均審核日數 '+avg_date+' 天，總社團 ' + group_count+' 個',
			x: -20
		},
		xAxis: {
			categories: columns,
			labels: {
				rotation: -60,
				formatter:function(){
					if(/01$/.test(this.value)){
							return this.value.substring(5);
					}else{
						//return this.value.substring(5);
					}
				},
				style: {
					height:"100px"
				}
			}
		},
		yAxis: {
			title: {
				text: '社團數'
			},
			plotLines: [{
				value: 0,
				width: 1,
				color: '#808080'
			}]
		},
		tooltip: {
			formatter: function() {
				return '<b>'+ this.series.name +'</b><br/>'+
					this.x +': '+ this.y +'個';
			}
		},
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'top',
			x: -10,
			y: 100,
			borderWidth: 0
		},
		series: series
	});

});
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