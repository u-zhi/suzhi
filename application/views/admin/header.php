<!DOCTYPE html>
<?php header('Cache-control: private,must-revalidate');?>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>速职后台管理</title>
		<meta name="keywords" content="速职后台管理" />
		<meta name="description" content="速职后台管理" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link href="/public_source/www/assets/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="/public_source/www/assets/css/font-awesome.min.css" />
		<!--[if IE 7]>
		  <link rel="stylesheet" href="/public_source/www/assets/css/font-awesome-ie7.min.css" />
		<![endif]-->
		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="/public_source/www/assets/css/ace.min.css" />
		<link rel="stylesheet" href="/public_source/www/assets/css/ace-rtl.min.css" />
		<link rel="stylesheet" href="/public_source/www/assets/css/ace-skins.min.css" />		
		<script src="/public_source/www/assets/js/jquery-1.8.3.min.js"></script>
		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="/public_source/www/assets/css/ace-ie.min.css" />
		<![endif]-->
		<script src="/public_source/www/assets/js/ace-extra.min.js"></script>
		<!--[if lt IE 9]>
		<script src="/public_source/www/assets/js/html5shiv.js"></script>
		<script src="/public_source/www/assets/js/respond.min.js"></script>
		<![endif]-->
		<!-- basic scripts -->
		<!--[if !IE]> -->
		<!-- <![endif]-->
		<!--[if IE]>
		<![endif]-->
		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='/public_source/www/assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>
		<!-- <![endif]-->
		<!--[if IE]>
		<script type="text/javascript">
		 	window.jQuery || document.write("<script src='/public_source/www/assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
		</script>
		<![endif]-->
		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='/public_source/www/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="/public_source/www/assets/js/bootstrap.min.js"></script>
		<script src="/public_source/www/assets/js/typeahead-bs2.min.js"></script>
		<script src="/public_source/www/assets/js/jquery-confirm.js"></script>		
		<link  rel="stylesheet" href="/public_source/www/assets/css/jquery-confirm.css" />
		<link  rel="stylesheet" href="/public_source/www/css/confirm.css" />
		<script src="/public_source/www/diyUpload/js/jquery.js"></script>

		<link rel="stylesheet" type="text/css" href="/public_source/www/diyUpload/css/webuploader.css">
		<link rel="stylesheet" type="text/css" href="/public_source/www/diyUpload/css/diyUpload.css">
		<script type="text/javascript" src="/public_source/www/diyUpload/js/webuploader.html5only.min.js"></script>
		<script type="text/javascript" src="/public_source/www/diyUpload/js/diyUpload.js"></script>
        <link  rel="stylesheet" href="/public_source/www/css/jquery-confirm.min.css" />
        <script src="/public_source/www/js/jquery-confirm.min.js"></script>
		<style>
			a:link{text-decoration:none;}
		</style>
		
	</head>
	</head>
	<body class="navbar-fixed">	
		<script type="text/javascript">
			//获取浏览器页面可见高度和宽度
			var _PageHeight = document.documentElement.clientHeight,
			  _PageWidth = document.documentElement.clientWidth;
			//计算loading框距离顶部和左部的距离（loading框的宽度为215px，高度为61px）
			var _LoadingTop = _PageHeight > 61 ? (_PageHeight - 61) / 2 : 0,
			  _LoadingLeft = _PageWidth > 215 ? (_PageWidth - 215) / 2 : 0;
			//在页面未加载完毕之前显示的loading Html自定义内容
			var _LoadingHtml = '<div id="loadingDiv" style="position:absolute;left:0;width:100%;height:' + _PageHeight + 'px;top:0;background:#f3f8ff;opacity:0.8;filter:alpha(opacity=80);z-index:10000;"><div style="position: absolute; cursor1: wait; left: ' + _LoadingLeft + 'px; top:' + _LoadingTop + 'px; width: auto; height: 170px; line-height: 170px; padding-left: 150px; padding-right: 20px; background: #fff url(/public_source/www/images/1.gif) no-repeat; border: 2px solid #95B8E7; color: #696969; font-family:\'Microsoft YaHei\';">页面加载中，请等待...</div></div>';
			//呈现loading效果
			document.write(_LoadingHtml);
			//window.onload = function () {
			//  var loadingMask = document.getElementById('loadingDiv');
			//  loadingMask.parentNode.removeChild(loadingMask);
			//};
			//监听加载状态改变
			document.onreadystatechange = completeLoading;
			//加载状态为complete时移除loading效果
			function completeLoading() {
			  if (document.readyState == "complete") {
			 	var loadingMask = document.getElementById('loadingDiv');
			 	loadingMask.parentNode.removeChild(loadingMask);
			  }
			}
		</script>
		<div class="navbar navbar-default navbar-fixed-top" id="navbar">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>
			<div class="navbar-container" id="navbar-container">
				<div class="navbar-header pull-left">					
					<a href="#" class="navbar-brand">
						<img src="/public_source/www/images/icon.png" height="27px;"/>
						<small>
							速职后台管理
							<div id="xs"></div>
						</small>
					</a><!-- /.brand -->
				</div><!-- /.navbar-header -->
				<div class="navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">							
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="/public_source/www/assets/avatars/bguser.jpg" alt="Allen's Photo" />
								<span class="user-info">
									<small>您好,</small>
									<?php echo $info['admin']['username'];?>
								</span>
								<i class="icon-caret-down"></i>
							</a>
							<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="<?=$admin_path;?>/login/logout">
										<i class="icon-off"></i>退出登录
									</a>
									<a href="<?=$admin_path;?>/password/index">
										<i class="icon-cog"></i>修改密码
									</a>
								</li>
							</ul>
						</li>  
					</ul>
				</div> 
			</div>
		</div>
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>
			<div class="main-container-inner">
				<a class="menu-toggler" id="menu-toggler" href="#">
					<span class="menu-text"></span>
				</a>
				<div class="sidebar" id="sidebar">
					<script type="text/javascript">
						try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
					</script>
					<!-- 栏目列表 -->
					<ul class="nav nav-list">										
						<?php 
							$mulu_array = $this->lang->language;
							$mulu_array = $mulu_array['mulu'];
							foreach($mulu_array as $key => $value) {
								if(array_intersect($value['controller'],$parent_access)  || $info['admin']['rid'] == 1) {
									if(in_array($controller,$value['controller'])) {
										echo '<li class="active open">';
									}else {
										echo '<li>';
									}
									echo '<a href="#" class="dropdown-toggle"><i class="'.$value['style'].'"></i><span class="menu-text">'.$value['name'].'</span><b class="arrow icon-angle-down"></b></a><ul class="submenu">';
									$m = 0;
									foreach($value['second'] as $k => $v) {
										if(in_array($k,$parent_access) || $info['admin']['rid'] == 1) {											
											if(isset($v['method'])) {
												if($controller == 'audit' && $method == 'look_page') {
													$method = 'audit_list';
												}
												if($method == $v['method']) {
													echo '<li class="active open">';
												}else {
													echo '<li>';
												}												
											}else {
												if($controller == $k) {
													echo '<li class="active open">';
												}else {
													echo '<li>';
												}												
											}
											$temp_array = array_intersect($value['controller'],$parent_access);
											if(isset($value['special'])) {											
												unset($temp_array[$value['special']]);
											}
											$n = count($temp_array);
											if($info['admin']['rid'] == 1) {
												$n	= count($value['second']);
											}																												
											if($m+1 >= $n) {
												echo '<a href="'.$admin_path.''.$v['link'].'"><i class="icon-double-angle-right" ></i>'.$v['name'].'</a></li></ul></li>';
											}else {
												echo '<a href="'.$admin_path.''.$v['link'].'"><i class="icon-double-angle-right" ></i>'.$v['name'].'</a></li>';
											}						
										}
										$m++;
									}
								}
							}
						?>			
					</ul>
					<!-- 栏目列表 END-->					
					<div class="sidebar-collapse" id="sidebar-collapse">
						<i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
					</div>
					<script type="text/javascript">
						try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
					</script>
				</div>