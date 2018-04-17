<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li><a href="<?=$this->company_url;?>">通用设置</a></li>
				<li><a href="<?=$this->company_url;?>">公司设置</a></li>
				<li>编辑信息</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$this->company_url;?>">
		<div class="page-content" style="margin-top:18px;">							
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 编辑信息</h1>
			</div>	
			<div class="row">
				<div class="col-xs-12">			
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?=$admin_path?>/setting/save_company_edit"  onsubmit="return company_edit_check()">
						<input type="hidden" class="width-100" id="id" name="id" value="<?=$data['id'];?>">	
						<div class="form-group"><div class="col-sm-6" style="padding-left:50px;"><b>公司设置</b></div></div>		
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">公司名称</label>
							<div class="col-sm-3">
								<span class="block input-icon input-icon-right">
									<input type="text" class="width-100" id="company_name" name="company_name" value="<?=$data['company_name'];?>">
								</span>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">公司联系人</label>
							<div class="col-sm-3">
								<input type="text" class="width-100" id="contact" name="contact" value="<?=$data['contact'];?>">
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">公司电话</label>
							<div class="col-sm-3">
								<span class="block input-icon input-icon-right">
									<input type="text" class="width-100" id="contact_num" name="contact_num" value="<?=$data['contact_num'];?>">
								</span>
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