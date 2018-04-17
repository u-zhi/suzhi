<?php include_once('header.php');?>
<link rel="stylesheet" href="/public_source/www/assets/css/dropzone.css" />
<link rel="stylesheet" href="/public_source/www/assets/css/ace.min.css" />
<link rel="stylesheet" type="text/css" href="/public_source/www/css/diyUpload.css">
<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="<?=$admin_path;?>/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li><a href="<?=$this->gos_url;?>">订单中心</a></li>
				<li><a href="<?=$this->gos_url;?>">求职者任务列表</a></li>
				<li>编辑信息</li>
			</ul>
		</div>
	<!--暂时还没仔细写                                   待完善-->











		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$this->gos_url;?>">
		<div class="page-content" style="margin-top:18px;">					
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 编辑信息</h1>
			</div>							
			<div class="row">
				<div class="col-xs-12">
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?=$admin_path;?>/order/edit">
						<input type="hidden" class="width-100" id="id" name="id" value="<?=$data['id'];?>">
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">订单状态</label>
							<div class="col-sm-2">
								<select class="width-100" id="current_status" name="current_status">
									<?php if($data['current_status'] >0){}else{?>
									<option value="0" <?php if($data['current_status'] == 0){echo "selected='selected'";}?>>求职者报名</option>
									<?php }?>
									<?php if($data['current_status'] >1){}else{?>
									<option value="1" <?php if($data['current_status'] == 1){echo "selected='selected'";}?>>企业发送面试邀请</option>
									<?php }?>
									<?php if($data['current_status'] >2){}else{?>
									<option value="2" <?php if($data['current_status'] == 2){echo "selected='selected'";}?>>企业已录取</option>
									<?php }?>
									<?php if($data['current_status'] >3){}else{?>
									<option value="3" <?php if($data['current_status'] == 3){echo "selected='selected'";}?>>完成-佣金解冻</option>
									<?php }?>
									<?php if($data['current_status'] >4){}else{?>
									<option value="4" <?php if($data['current_status'] == 4){echo "selected='selected'";}?>>3-10天企业辞退75%的佣金</option>
									<?php }?>
									<?php if($data['current_status'] >5){}else{?>
									<option value="5" <?php if($data['current_status'] == 5){echo "selected='selected'";}?>>3-10天求职者辞职25%的佣金</option>
									<?php }?>
									<?php if($data['current_status'] >6){}else{?>
									<option value="6" <?php if($data['current_status'] == 6){echo "selected='selected'";}?>>企业查看不通过</option>
									<?php }?>
									<?php if($data['current_status'] >7){}else{?>
									<option value="7" <?php if($data['current_status'] == 7){echo "selected='selected'";}?>>企业面试不通过</option>
									<?php }?>
									<?php if($data['current_status'] >8){}else{?>
									<option value="8" <?php if($data['current_status'] == 8){echo "selected='selected'";}?>>已完工</option>
									<?php }?>
									<?php if($data['current_status'] >9){}else{?>
									<option value="9" <?php if($data['current_status'] == 9){echo "selected='selected'";}?>>未面试</option>
									<?php }?>
								</select>
							</div>
						</div>
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
	<link href="/public_source/www/assets/css/fileinput.css" rel="stylesheet" type="text/css" />
    <script src="/public_source/www/assets/js/fileinput.js" type="text/javascript"></script> 
    <script src="/public_source/www/js/common.js" type="text/javascript"></script>    
    <script src="/public_source/www/js/form_check.js" type="text/javascript"></script>  
	<script src="/public_source/www/assets/ueditor/ueditor.config.js" type="text/javascript"></script>
    <script src="/public_source/www/assets/ueditor/ueditor.all.js" type="text/javascript"></script>  
    <script src="/public_source/www/assets/js/dropzone.min.js"></script>
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
<?php include_once('footer.php');?>