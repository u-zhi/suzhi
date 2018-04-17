<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li><a href="<?=$this->go_url;?>">通用设置</a></li>
				<li><a href="<?=$this->go_url;?>">基础设置</a></li>
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
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?=$admin_path?>/setting/save_basic_edit"  onsubmit="return basic_edit_check()">
						<input type="hidden" class="width-100" id="id" name="id" value="<?=$data['id'];?>">	
						<div class="form-group"><div class="col-sm-6" style="padding-left:50px;"><b>公众号绑定</b></div></div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">Appid</label>
							<div class="col-sm-4">
								<input type="text" class="width-100" id="appid" name="appid" value="<?=$data['appid']?>">
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">AppSecret</label>
							<div class="col-sm-4">
								<input type="text" class="width-100" id="appsecret" placeholder="appsecret" name="appsecret" value="<?=$data['appsecret']?>">
							</div>
						</div>
						<div class="form-group"><div class="col-sm-6" style="padding-left:50px;"><b>微信支付配置</b></div></div>															 	
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">商户密钥</label>
							<div class="col-sm-4">
								<input type="text" class="width-100" id="paysignkey" placeholder="paysignkey" name="paysignkey" value="<?=$data['paysignkey']?>">
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">商户号</label>
							<div class="col-sm-4">
								<input type="text" class="width-100" id="partnerkey" placeholder="partnerkey" name="partnerkey" value="<?=$data['partnerkey']?>" >
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