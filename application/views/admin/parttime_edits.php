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
				<li><a href="<?=$this->going_url;?>">求职者兼职任务列表</a></li>
				<li>查看信息</li>
			</ul>
		</div>	
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$this->going_url;?>">
		<div class="page-content" style="margin-top:18px;">					
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 查看信息</h1>
			</div>							
			<div class="row">
				<div class="col-xs-12">
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="">
						<input type="hidden" id="order_id" value="<?=$data['order_id']?>">
						<div class="form-group">
							<label  class="col-sm-1 control-label">任务照片</label>
							<div class="col-sm-4">
								<img id="bigic" src="<?php echo $data['image_url'];?>" style="max-height:160px;max-width:835px;" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">任务名称</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['name']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">所属公司</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['firm_name']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">需求人数</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['person_demand']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">区域</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['county_name']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">佣金</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['commission']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">薪资</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['salary']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">工作类型</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['occupation_name']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">工作时间</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['work_time']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">工作时段</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['work_schedule']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">浏览次数</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['view_times']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">投递简历次数</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['fake_view_times']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label for="content" class="col-sm-1 control-label">工作描述</label>
							<div class="col-sm-10">
								<textarea id="editor" name="introduction" type="text/plain" style="width: 260px;height: 180px" readonly="readonly"><?=$data['job_description']?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="content" class="col-sm-1 control-label">任职要求</label>
							<div class="col-sm-10">
								<textarea id="editor" name="introduction" type="text/plain" style="width: 260px;height: 180px" readonly="readonly"><?=$data['duty_description']?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="content" class="col-sm-1 control-label">福利待遇</label>
							<div class="col-sm-10">
								<textarea id="editor" name="introduction" type="text/plain" style="width: 260px;height: 180px" readonly="readonly"><?=$data['benefits']?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">上下架</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?php if($data['is_off_shelved'] == 0){echo '上架';}else{echo '下架';}?>" readonly="readonly">
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-sm-1 control-label">订单编号</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['trade_no']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">猎头手机号</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['phone']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">猎头昵称</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['nicheng']?>" readonly="readonly">
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-sm-1 control-label">求职者手机号</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['phone_number']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">求职者姓名</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['nickname']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">订单状态</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['current_status']?>" readonly="readonly">
							</div>
						</div>
						<div class="page-header"></div>
					 	<div class="form-group">
					   		<div class="col-sm-offset-1 col-sm-10">
								<a href="/user_admin/jobhunter/show_resume/<?=$data['user_id']?>" class="btn btn-sm btn-yellow">查看简历</a>
								<button type="button" onclick="nopass()" class="btn btn-sm btn-primary">查看不通过</button>
								<a href="/user_admin/order/work/<?=$data['order_id']?>" class="btn btn-sm btn-purple">发送开工邀请</a>
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
	<script type="text/javascript" >
		function nopass() {
			var id=$('#order_id').val();
			var data={'id':id,'current_status':6};
			$.ajax({
				type: "post",
				url:"/user_admin/order/modify_edit",
				data: data,
				dataType: "json",
				success: function(data){
					if(data.status.succeed == 1){
						window.location.href="/user_admin/order/parttime_list";
					}else{
						alert(data.status.error_desc);
					}
				}
			});
		}







	</script>
<?php include_once('footer.php');?>