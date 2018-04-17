<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li>
					<i class="icon-home home-icon"></i>
					首页
				</li>
			</ul>
		</div>
		<div class="page-content">
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 首页 Home</h1>
			</div>
			<div class="alert alert-info">
				<b><i class="icon-comment-alt"></i> 欢迎使用速职后台管理系统,轻量级好用的后台管理系统模版。</b>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-3 center">
					<div>
						<span class="profile-picture">
							<img id="avatar" class="editable img-responsive" alt="Alex's Avatar" src="/public_source/www/assets/avatars/profile-pic.jpg" />
						</span>
						<div class="space-4"></div>
						<div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
							<div class="inline position-relative">
								<a href="#" class="user-title-label dropdown-toggle" data-toggle="dropdown">
									<i class="icon-circle light-green middle"></i>
									&nbsp;
									<span class="white"><?=$info['admin']['role_name']?>&nbsp;&nbsp;<?=$info['admin']['username'];?></span>
								</a>
							</div>
						</div>
					</div>	
				</div>
				<div class="col-xs-12 col-sm-9">
					<div class="profile-user-info profile-user-info-striped">
						<div class="profile-info-row">
							<div class="profile-info-name"> 当前登录帐号 </div>
	
							<div class="profile-info-value">
								<span class="editable"><b><?=$info['admin']['username'];?></b></span>
							</div>
						</div>
						<div class="profile-info-row">
							<div class="profile-info-name"> 帐号权限级别 </div>
	
							<div class="profile-info-value">
								<span class="editable"><b><?=$info['admin']['role_name'];?></b></span>
							</div>
						</div>
						<div class="profile-info-row">
							<div class="profile-info-name"> 最后登录地点 </div>
	
							<div class="profile-info-value">
								<i class="icon-map-marker light-orange bigger-110"></i>
								<span class="editable"><b><?=$info['admin']['last_address']?></b></span>
							</div>
						</div>
						<div class="profile-info-row">
							<div class="profile-info-name">最后登录IP </div>
	
							<div class="profile-info-value">
								<span class="editable"><b><?=$info['admin']['last_ip']?></b></span>
							</div>
						</div>
						<div class="profile-info-row">
							<div class="profile-info-name"> 最后登录时间 </div>
	
							<div class="profile-info-value">
								<span class="editable" id="signup"><b><?=date("Y-m-d H:i:s",$info['admin']['last_time']);?></b></span>
							</div>
						</div>
						<div class="profile-info-row">
							<div class="profile-info-name"> 登录总次数</div>
	
							<div class="profile-info-value">
								<span class="editable" id="signup"><b><?=$info['admin']['login_num'];?>次</b></span>
							</div>
						</div>
						<div class="profile-info-row">
							<div class="profile-info-name"> 帐号加入时间</div>
	
							<div class="profile-info-value">
								<span class="editable" id="signup"><b><?=date("Y-m-d H:i:s",$info['admin']['create_time']);?></b></span>
							</div>
						</div>
						<div class="profile-info-row">
							<div class="profile-info-name"> 登录特别说明 </div>
	
							<div class="profile-info-value">
								<span class="editable" id="about"><b class="red">如上述信息不符合，说明帐号密码可能已泄漏，请及时修改密码。</b></span>
							</div>
						</div>
					</div>
				</div>
			</div>		
		</div>														
	</div>
<?php include_once('footer.php');?>	