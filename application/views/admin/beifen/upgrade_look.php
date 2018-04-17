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
				<li><a href="<?=$this->go_url;?>">代理管理</a></li>
				<li><a href="<?=$this->go_url;?>">升级审核列表</a></li>
				<li>查看信息</li>
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
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?=$admin_path;?>/upgrade/edit">	           																										
						<input type="hidden" class="form-control" name="id" id="id" value="<?=$data['id'];?>">
						<input type="hidden" class="form-control" name="purchase_id" id="purchase_id" value="<?=$data['purchase_id'];?>">
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">头像</label>
							<div class="col-sm-2">
								<img id="bigic" src="<?php echo $data['avatar'];?>" style="max-height:160px;max-width:835px;" />
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">申请等级</label>
							<div class="col-sm-2">
								<div class="control"><?=$data['level'];?></div>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">姓名</label>
							<div class="col-sm-2">
								<div class="control"><?=$data['user_name'];?></div>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">手机号</label>
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
							<label for="title" class="col-sm-1 control-label">打款截图</label>
							<div class="col-sm-2">
								<img src="<?php echo $data['pay_image'];?>" style="max-height:160px;max-width:835px;" />
							</div>
						</div>							
						<div class="page-header">
							<h4 class="blue">   商品信息</h4>
						</div>	
						<div class="form-group">
							<label class="col-sm-1 control-label"></label>
							<table class="col-sm-5 price-td" style='border:1px solid #ddd;margin-bottom:20px;' width='50%'>
						 		<tr><td><b>商品id</b></td><td><b>商品名称</b></td><td><b>商品单价</b></td><td><b>采购数量</b></td><td><b>小计</b></td></tr> 			
								<?php
									$num = 0; 
									$total_price = 0;
									foreach($goods_list as $key => $value) {
										$num += $value['num'];
										$total_price += $value['num']*$value['now_price'];
									?>
								<tr><td><?=$value['goods_id'];?></td><td><b><?=$value['goods_name'];?></b></td><td><b><?=$value['now_price'];?></b></td><td><b><?=$value['num'];?></b></td><td><b><?=$value['num']*$value['now_price'];?></b></td></tr>				
						 		<?php }?>
						 	</table>					 	
						</div>			
							<b style="padding-left:37%;">总计：<?=$num;?>件商品 &nbsp;&nbsp;合计￥<?=$total_price;?></b>					
						<div class="form-group">
							<label class="col-sm-1 control-label">审核</label>
							<div class="col-sm-3">
								<label style="margin-right:30px;"><input class="ace" type="radio" name="above_status" value="2" checked="checked"/><span class="lbl"> 暂定</span></label>														
								<label style="margin-right:30px;"><input class="ace" type="radio" name="above_status" value="3" /><span class="lbl"> 通过</span></label>
								<label><input class="ace" type="radio" name="above_status" value="4" /><span class="lbl"> 拒绝</span></label>							
							</div>					
						</div>	
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
    <script src="/public_source/www/js/common.js" type="text/javascript"></script>    
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
	<script type="text/javascript">
		//图片放大插件
		$('.form-group img').bigic();
	</script>
<?php include_once('footer.php');?>