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
				<li><a href="<?=$this->going_url;?>">订单中心</a></li>
				<li><a href="<?=$this->going_url;?>">求职者兼职订单列表</a></li>
				<li>编辑信息</li>
			</ul>
		</div>	
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$this->going_url;?>">
		<div class="page-content" style="margin-top:18px;">					
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 编辑信息</h1>
			</div>							
			<div class="row">
				<div class="col-xs-12">
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?=$admin_path;?>/order/adds"  onsubmit="return work_add_check()">
						<input type="hidden" class="width-100" id="id" name="id" value="<?=$data['id'];?>">
						<div class="form-group">
							<label class="col-sm-1 control-label">开工时间</label>
							<div class="col-sm-2">
								<input style="cursor:pointer;" type="text" placeholder="请选择时间" class="Wdate" id="work_time" name="work_time"  id="d412" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">联系人</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="contact" name="contact" value="">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">联系电话</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="contact_tel" name="contact_tel" value="">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">开工地点</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="work_address" name="work_address" value="">
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
	<script src="/public_source/www/My97DatePicker/WdatePicker.js"></script>
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