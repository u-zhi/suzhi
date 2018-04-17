<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li><a href="<?=$this->go_url;?>">管理员管理</a></li>
				<li><a href="<?=$this->go_url;?>">管理员列表</a></li>
				<li>编辑信息</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">
		<div class="page-content">		
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 编辑信息</h1>
			</div>	
			<div class="row">
				<div class="col-xs-12">				
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="/user_admin/admin/edit"  onsubmit="return admin_edit_check()">
						<input type="hidden" class="width-100" id="id" name="id" value="<?=$data['id']?>">
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">账号</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="username" name="username" value="<?=$data['username']?>">
							</div>
						</div>						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">姓名</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="realname" name="realname" value="<?=$data['realname']?>">
							</div>
						</div>							
						<div class="form-group has-error">
							<label for="title" class="col-sm-1 control-label">密码</label>
							<div class="col-sm-2">
								<span class="block input-icon input-icon-right">
									<input type="text" class="width-100" id="n_password" name="n_password" value="">
									<i class="icon-info-sign"></i>
								</span>
							</div>									
							<div class="help-block col-xs-12 col-sm-reset inline">
								可不填，填写则为更改密码
							</div>
						</div>		
						<?php if($data['rid'] != 1) {?>			
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">角色</label>
							<div class="col-sm-2">
								<select class="width-100" id="rid" name="rid">
								<?php
									foreach($role_list as $key => $value) {					
								?>													
									<option value="<?=$value['role_id'];?>" <?php if($value['role_id'] == $data['rid']) {echo 'selected="selected"';}?>><?=$value['role_name'];?></option>							
								<?php }?>											
	
								</select>
							</div>
						</div>
						<?php }?>				
						<div class="page-header"></div>
					 	<div class="form-group">
					   		<div class="col-sm-offset-1 col-sm-10">
								<button type="submit" id="submit" class="btn btn-sm btn-primary">确定保存</button>									
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