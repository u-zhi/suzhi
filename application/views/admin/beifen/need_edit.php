<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li><a href="<?=$this->go_url;?>">代理管理</a></li>
				<li><a href="<?=$this->go_url;?>">意向代理列表</a></li>
				<li>编辑信息</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">
		<div class="page-content" style="margin-top:18px;">							
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 编辑信息</h1>
			</div>	
			<div class="row">
				<div class="col-xs-12">			
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?=$admin_path?>/need/edit"  onsubmit="return need_edit_check()">
						<input type="hidden" class="width-100" id="agent_level" name="agent_level" value="<?=$data['agent_level'];?>">																
						<input type="hidden" class="width-100" id="id" name="id" value="<?=$data['id'];?>">																
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">姓名</label>
							<div class="col-sm-2">
								<div class="control"><?=$data['user_name'];?></div>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">电话</label>
							<div class="col-sm-2">
								<div class="control"><?=$data['phone'];?></div>
							</div>
						</div>	
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">微信号</label>
							<div class="col-sm-2">
								<div class="control"><?=$data['wechat_num'];?></div>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">邮箱</label>
							<div class="col-sm-2">
								<div class="control"><?=$data['email'];?></div>
							</div>
						</div>	
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">意向等级</label>
							<div class="col-sm-2">
								<div class="control"><?=$data['level'];?></div>
							</div>
						</div>	
						<div class="form-group has-error">
							<label for="title" class="col-sm-1 control-label">添加代理</label>
							<div class="col-sm-2">
								<span class="block input-icon input-icon-right">
									<input type="text" class="width-100" id="phone" name="phone" value="">
									<i class="icon-info-sign"></i>
								</span>
							</div>															
							<div class="help-block col-xs-12 col-sm-reset inline">
								请添加大于或等于意向代理等级的代理手机号(如3级意向代理只能推给1，2，3级代理)
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
	<style>
		.control {
		    padding: 4px 0px;
		    color: #858585;
		    font-size: 14px;
		}
		.price-td td {
			height:40px;
			border:1px solid #999;
			text-align: center;
		}
	</style>
<?php include_once('footer.php');?>		