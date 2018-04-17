<!doctype html>
<html lang="zh">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>速职后台登录</title>
	<link rel="stylesheet" type="text/css" href="/public_source/www/login/css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="/public_source/www/login/css/default.css">
	<link rel="stylesheet" type="text/css" href="/public_source/www/login/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="/public_source/www/login/css/clouds.css" />
	<link rel="stylesheet" type="text/css" href="/public_source/www/login/css/style.css" />	
	<link rel="stylesheet" type="text/css" href="/public_source/www/css/sweetalert.css">
	<script src="/public_source/www/login/js/jquery-1.7.2.min.js" type="text/javascript"></script>
	<script src="/public_source/www/js/sweetalert.min.js"></script>
</head>
<body>
	<div class="login_div">
		<div class="col-xs-12 login_title">速职后台登录</div>
		<div class="login">
			<div class="nav">
				<div class="nav login_nav">
					<div class="col-xs-4 login_username">用户名:</div>
					<div class="col-xs-6 login_usernameInput">
						<input type="text" name="username" id="name" value="" placeholder="&nbsp;&nbsp;填写用户名"  onblur="javascript:ok_or_errorBylogin(this)" />
					</div>
					<div class="col-xs-1 ok_gou">√</div>
					<div class="col-xs-1 error_cuo">×</div>
				</div>
				<div class="nav login_psdNav">
					<div class="col-xs-4">密&nbsp;&nbsp;&nbsp;码:</div>
					<div class="col-xs-6">
						<input type="password" name="password" id="psd" value="" placeholder="&nbsp;&nbsp;填写密码" onBlur="javascript:ok_or_errorBylogin(this)" />
					</div>
					<div class="col-xs-1 ok_gou">√</div>
					<div class="col-xs-1 error_cuo">×</div>
				</div>
				<div class="col-xs-12 login_btn_div">
					<input type="submit" class="sub_btn" value="登录" id="login" />
				</div>
			</div>
		</div>
		<div class="col-xs-12 barter_btnDiv">
			order by zhaima
		</div>
	</div>
	<div id="far-clouds" class="stage far-clouds"></div>
    <div id="near-clouds" class="stage near-clouds"></div>
	<script type="text/javascript" src="/public_source/www/login/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="/public_source/www/login/js/clouds.js"></script>
    <script type="text/javascript" src="/public_source/www/login/js/app.js"></script>
</body>
</html>
<script type="text/javascript">
	$(function() {
		$('#login').click(function() {
			var name_state = $('#name');
			var psd_state = $('#psd');
			var name = $('#name').val();
			var psd = $('#psd').val();
			if (name == '') {
				name_state.parent().next().next().css("display", "block");
				return false;
			} else if (psd == '') {
				name_state.parent().next().next().css("display", "none");
				psd_state.parent().next().next().css("display", "block");
				return false;
			} else {
				$.ajax({
					async: false,
					url:"/index.php/user_admin/login/login",
					type:"post",
					data: {username:name,password:psd},
					dataType: 'json',
					success:function(data) {
						if(data.message) {
							window.location.replace("<?=$admin_path?>/welcome");
						}else {
							if(data.login_error > 5) {
								name_state.parent().next().next().css("display", "none");
								psd_state.parent().next().next().css("display", "none");
								swal({   
									title: "OMG",   
									text: '<b style="color:red;">登录失败5次，请30分钟后进行操作</b><br/>5秒后自动关闭',   
									imageUrl: "/public_source/www/images/thumbs-up.jpg",
									html: true,
									timer: 5000,   
									showConfirmButton: false
								});
							}else {
								name_state.parent().next().next().css("display", "block");
								psd_state.parent().next().next().css("display", "block");
							}
						}			 
					}
				});						
			}
		});
	})	
	function ok_or_errorBylogin(l) {
		var content = $(l).val();
		if (content != "") {
			$(l).parent().next().next().css("display", "none");
		}
	}
	function barter_btn(bb) {
		$(bb).parent().parent().fadeOut(1000);
		$(bb).parent().parent().siblings().fadeIn(2000);
	}

</script>