<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li><a href="/user_admin/admin/admin_list">管理员管理</a></li>
				<li><a href="/user_admin/admin/admin_list">管理员列表</a></li>
				<li>增加信息</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$admin_path?>/admin/admin_list">
		<div class="page-content" style="margin-top:18px;">							
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 添加信息</h1>
			</div>	
			<div class="row">
				<div class="col-xs-12">			
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="/user_admin/admin/add"  onsubmit="return admin_add_check()">

						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">账号</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="username" name="username" value="">
							</div>
						</div>						
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">姓名</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="realname" name="realname" value="">
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">密码</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="n_password" name="n_password" value="">
							</div>
						</div>						
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">角色</label>
							<div class="col-sm-2">
								<select class="width-100" id="rid" name="rid">
									<?php
										$list = ''; 
										foreach($role_list as $k => $v) {
											$list .= '<option value="'.$v['role_id'].'">'.$v['role_name'].'</option>';											
										}
										echo $list;
									?>
								</select>
							</div>
						</div>				
						<div class="page-header"></div>
					 	<div class="form-group">
					   		<div class="col-sm-offset-1 col-sm-10">
								<button type="submit" id="submit" class="btn btn-sm btn-primary">确认保存</button>
								<a href="javascript:history.go(-1)" class="btn btn-sm btn-warning">返回上页</a>
							</div>
					  	</div>
					</form>	
				</div>
			</div>					
		</div>													
	</div>
    <script src="/public_source/www/js/common.js" type="text/javascript"></script>
    <script src="/public_source/www/js/form_check.js" type="text/javascript"></script>
<?php include_once('footer.php');?>		