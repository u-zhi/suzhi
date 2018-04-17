<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li><a href="<?=$this->go_url;?>">财务管理</a></li>
				<li><a href="<?=$this->go_url;?>">门槛设置</a></li>
				<li>编辑信息</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">
		<div class="page-content" style="margin-top:18px;">	
			<div class="alert alert-info">
				<b><i class="icon-comment-alt"></i> 提示：这里设置的是申请加盟和升级成为各级代理的门槛金额。在选择代理级别的时候提示。用户申请加盟或是升级时采购的商品必须满足这个门槛金额。
				</b>
			</div>						
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 编辑信息</h1>
			</div>	
			<div class="row">
				<div class="col-xs-12">			
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?=$admin_path?>/finance/save_finance_edit"  onsubmit="return finance_edit_check()">
						<input type="hidden" class="width-100" id="id" name="id" value="<?=$data['id'];?>">	
						<div class="form-group"><div class="col-sm-6" style="padding-left:50px;"><b>门槛设置</b></div></div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">一级代理</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="one_class" name="one_class" value="<?=$data['one_class']?>">
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">二级代理</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="two_class" name="two_class" value="<?=$data['two_class']?>">
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">三级代理</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="three_class" name="three_class" value="<?=$data['three_class']?>">
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">四级代理</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="four_class" name="four_class" value="<?=$data['four_class']?>">
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">五级代理</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="five_class" name="five_class" value="<?=$data['five_class']?>">
							</div>
						</div>						
						<div class="page-header"></div>
					 	<div class="form-group">
					   		<div class="col-sm-offset-1 col-sm-10">
								<button type="submit" id="submit" class="btn btn-sm btn-primary">确认保存</button>
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