<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li><a href="/user_admin/node/node_list">管理员管理</a></li>
				<li><a href="/user_admin/node/node_list">节点列表</a></li>
				<li>编辑信息</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$admin_path?>/node/node_list">
		<div class="page-content" style="margin-top:18px;">		
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 编辑信息</h1>
			</div>	
			<div class="row">		
				<div class="col-xs-12">
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="/user_admin/node/edit"  onsubmit="return node_edit_check()">
						<input type="hidden" id="node_id" name="node_id" value="<?=$data['node_id'];?>" />						
						<div class="form-group has-error">
							<label for="title" class="col-sm-1 control-label">节点名称</label>
							<div class="col-sm-2">
								<span class="block input-icon input-icon-right">
									<input type="text" class="width-100" id="node_name" name="node_name" value="<?=$data['node_name'];?>">
									<i class="icon-info-sign"></i>
								</span>
							</div>									
							<div class="help-block col-xs-12 col-sm-reset inline">
								不熟悉人员请勿随意填写
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">节点描述</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="title" name="title" value="<?=$data['title'];?>">
							</div>
						</div>						
						<div class="form-group has-error">
							<label for="title" class="col-sm-1 control-label">节点权限</label>
							<div class="col-sm-2">
								<select name="level" class="width-100">
									<option value="1" <?php if($data['level'] == 1) echo 'selected="selected"';?>>仅限总公司</option>
									<option value="2" <?php if($data['level'] == 2) echo 'selected="selected"';?>>分公司可用</option>						
								</select>
							</div>									
							<div class="help-block col-xs-12 col-sm-reset inline">
								分公司可用指分公司帐号进入后台也可以对此功能进行管理
							</div>
						</div>
						<?php if($data['pid'] == 0) {?>
						<div class="form-group has-error">
							<label for="title" class="col-sm-1 control-label">节点排序</label>
							<div class="col-sm-2">
								<span class="block input-icon input-icon-right">
									<input type="text" class="width-100" id="node_sort" name="node_sort" value="<?=$data['node_sort'];?>">
									<i class="icon-info-sign"></i>
								</span>
							</div>									
							<div class="help-block col-xs-12 col-sm-reset inline">
								节点排序绝对节点列表显示的先后顺序
							</div>
						</div>
						<?php }else {?>
							<input type="hidden" class="width-100" id="node_sort" name="node_sort" value="<?=$data['node_sort'];?>">
						<?php }?>
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