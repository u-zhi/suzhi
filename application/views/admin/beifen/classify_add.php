<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li><a href="<?=$this->go_url;?>">商城管理</a></li>
				<li><a href="<?=$this->go_url;?>">分类管理</a></li>
				<li>增加信息</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">
		<div class="page-content" style="margin-top:18px;">							
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 添加信息</h1>
			</div>	
			<div class="row">
				<div class="col-xs-12">			
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?=$admin_path?>/classify/add"  onsubmit="return classify_add_check()">
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">分类名称</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="name" name="name" value="">
							</div>
						</div>
						<div class="form-group has-error">
							<label for="title" class="col-sm-1 control-label">分类排序</label>
							<div class="col-sm-2">
								<span class="block input-icon input-icon-right">
									<input type="text" class="width-100" id="classify_sort" name="classify_sort" value="99">
								</span>
							</div>													
							<div class="help-block col-xs-12 col-sm-reset inline">
								分类排序只能为正整数
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