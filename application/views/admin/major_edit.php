<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li><a href="/user_admin/major/major_list">选项管理</a></li>
				<li><a href="/user_admin/major/major_list">专业列表</a></li>
				<li>查看信息</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$admin_path?>/major/major_list">
		<div class="page-content" style="margin-top:18px;">							
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i>查看信息</h1>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="/user_admin/major/edit"  onsubmit="return major_add_check()">
						<input type="hidden" class="width-100" id="id" name="id" value="<?=$data['id'];?>">
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">所属学科</label>
							<div class="col-sm-2">
								<select class="width-100" id="parent_id" name="parent_id">
									<?php foreach($parent_list as $k => $v) {?>
										<option value="<?=$v['id'];?>" <?php if($v['id'] == $data['parent_id']){echo "selected='selected'";}?>><?=$v['item'];?></option>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">专业名称</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="item" name="item" value="<?=$data['item']?>">
							</div>
						</div>
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