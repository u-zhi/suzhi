<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li><a href="<?=$this->go_url;?>">代理管理</a></li>
				<li><a href="<?=$this->go_url;?>">四级代理列表</a></li>
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
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?=$admin_path?>/four_level/edit"  onsubmit="return one_edit_check()">
						<input type="hidden" class="width-100" id="id" name="id" value="<?=$data['id'];?>">					
						<input type="hidden" class="width-100" id="old_wechat" name="old_wechat" value="<?=$data['wechat_num'];?>">					
						<input type="hidden" class="width-100" id="old_phone" name="old_phone" value="<?=$data['phone'];?>">											
						<input type="hidden" class="width-100" id="old_name" name="old_name" value="<?=$data['user_name'];?>">												
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">头像</label>
							<div class="col-sm-2">
								<img id="bigic" src="<?php echo $data['avatar'];?>" style="max-height:160px;max-width:835px;" />
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">等级</label>
							<div class="col-sm-2">
								<div class="control"><?=$data['level'];?></div>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">姓名</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="user_name" name="user_name" value="<?=$data['user_name'];?>">
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">手机号</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="phone" name="phone" value="<?=$data['phone'];?>">								
							</div>
						</div>	
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">微信号</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="wechat_num" name="wechat_num" value="<?=$data['wechat_num'];?>">
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">邮箱</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="email" name="email" value="<?=$data['email'];?>">
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
    <link href="/public_source/www/css/style.css" rel="stylesheet" type="text/css"/>
	<script src="/public_source/www/js/jquey-bigic.js" type="text/javascript"></script>
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
		*{ margin:0; padding:0;}
		#box{margin:50px auto; width:540px; min-height:400px; background:#FF9}
		.fileBoxUl { margin: 0 0 0 0;}
		.diyCancel {
			background:url(/public_source/www/images/x_alt.png) no-repeat;
		}
	</style>
	<script>
		$('#bigic').bigic();
	</script>
<?php include_once('footer.php');?>		