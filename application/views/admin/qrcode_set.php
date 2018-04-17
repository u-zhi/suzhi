<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li><a href="<?=$this->go_url;?>">推荐设置</a></li>
				<li><a href="<?=$this->go_url;?>">公司二维码</a></li>
				<li>编辑信息</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">
		<div class="page-content" style="margin-top:18px;">							
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 查看信息</h1>
			</div>	
			<div class="row">
				<div class="col-xs-12">			
					<div class="form-horizontal">				
						<div class="form-group">
							<label  class="col-sm-1 control-label">当前二维码</label>
							<div class="col-sm-4">
								<img id="bigic" src="<?php echo $data['qrcode'];?>" />
							</div>
						</div>
					</div>	
				</div>
			</div>					
		</div>													
	</div>
    <script src="/public_source/www/js/common.js" type="text/javascript"></script>
    <script src="/public_source/www/js/form_check.js" type="text/javascript"></script>
    <script>
		//图片放大插件
		$('#bigic').bigic();
    </script>
<?php include_once('footer.php');?>		