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
				<li><a href="<?=$this->go_url;?>">用户管理</a></li>
				<li><a href="<?=$this->go_url;?>">求职顾问</a></li>
				<li>详细信息</li>
			</ul>
		</div>	
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">
		<div class="page-content" style="margin-top:18px;">	
			<a href="javascript:history.go(-1)" class="btn btn-sm btn-warning">返回上页</a>
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 详细信息</h1>
			</div>							
			<div class="row">
				<div class="col-xs-12">
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?=$admin_path;?>/headhunter/edit"  onsubmit="return headhunter_edit_check()">
						<input type="hidden" class="width-100" id="user_id" name="user_id" value="<?=$data['user_id'];?>">
						<div class="form-group">
							<label  class="col-sm-1 control-label">当前头像</label>
							<div class="col-sm-1">
								<img id="bigic" src="<?php echo $data['avatar_url'];?>" style="max-height:160px;max-width:835px;" />
							</div>
							<label class="col-sm-1 control-label">姓名</label>

							<div class="col-sm-1">
								<input type="text" class="width-100" id="real_name" name="real_name" value="<?=$data['real_name']?>">
							</div>
							<label class="col-sm-1 control-label">联系电话</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="phone_number" name="phone_number" value="<?=$data['phone_number']?>">
							</div>
							<label class="col-sm-1 control-label">余额</label>
							<div class="col-sm-1">
								<input type="text" class="width-100" id="balance" name="balance" value="<?=($data['balance']/100)?>">
							</div>
						</div>
						<div class="visible-md visible-lg hidden-sm hidden-xs btn-group" style='margin: 20px 0 40px 20%;'>

							<div style="float:left;margin-right:30px;">
								<a href="<?=$admin_path;?>/headhunter/edit_page/<?=$data['user_id'];?>" class='btn-pink2'><span  class="btn2 btn-xs bottom-ky ">用戶資料</span></a>
							</div>					
							<div style="float:left;margin-right:30px;">
								<a href="<?=$admin_path;?>/headhunter/talent_pool/<?=$data['user_id'];?>" class='btn-pink3'><span class="btn2 btn-xs  bottom-ky ">人才库</span></a>
							</div>						
							<div style="float:left;margin-right:30px;">
								<a href="<?=$admin_path;?>/headhunter/receive_list/<?=$data['user_id'];?>" class='btn-pink4'><span class="btn2 btn-xs  bottom-ky ">接取的任务</span></a>
							</div>							
							<div style="float:left;margin-right:30px;">
								<a href="<?=$admin_path;?>/firm/company_staff/<?=$data['user_id'];?>" class='btn-pink4'><span class="btn2 btn-xs  bottom-ky ">推荐人才订单</span></a>
							</div>	
						</div>
						<div class="page-header" style="height:100px;"></div>
<!-- 						<div class="form-group">
							<label  class="col-sm-1 control-label">要更改的头像</label>
							<div class="col-sm-2">
								<input type="hidden" class="form-control" id="avatar_url" name="avatar_url" value="">
								<input id="file-0b" class="file" type="file" name="jietu">
							</div>
						</div> -->
						<div class="form-group">
							<label class="col-sm-1 control-label">昵称</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="nickname" name="nickname" value="<?=$data['nickname']?>">
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">性别</label>
							<div class="col-sm-2">
								<select class="width-100" id="gender" name="gender">
									<option value="0" <?php if($data['gender'] == 0){echo "selected='selected'";}?>>女</option>
									<option value="1" <?php if($data['gender'] == 1){echo "selected='selected'";}?>>男</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">身份证号</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="card_no" name="card_no" value="<?=$data['card_no']?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">生日</label>
							<div class="col-sm-2">
								<input style="cursor:pointer;" type="text"  class="Wdate" id="birthday" name="birthday" value="<?=$data['birthday'];?>" id="d412" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">职业</label>
							<div class="col-sm-2">
								<select class="width-100" id="occupation_id" name="occupation_id">
									<?php foreach($occupation as $k => $v) {?>
										<option value="<?=$v['id'];?>" <?php if($v['id'] == $data['occupation_id']){echo "selected='selected'";}?>><?=$v['item'];?></option>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">学历</label>
							<div class="col-sm-2">
								<select class="width-100" id="highest_degree" name="highest_degree">
									<option value="0" <?php if($data['highest_degree'] == 0){echo "selected='selected'";}?>>小学</option>
									<option value="1" <?php if($data['highest_degree'] == 1){echo "selected='selected'";}?>>初中</option>
									<option value="2" <?php if($data['highest_degree'] == 2){echo "selected='selected'";}?>>高中</option>
									<option value="3" <?php if($data['highest_degree'] == 3){echo "selected='selected'";}?>>大专</option>
									<option value="4" <?php if($data['highest_degree'] == 4){echo "selected='selected'";}?>>本科学士</option>
									<option value="5" <?php if($data['highest_degree'] == 5){echo "selected='selected'";}?>>硕士</option>
									<option value="6" <?php if($data['highest_degree'] == 6){echo "selected='selected'";}?>>博士</option>
									<option value="7" <?php if($data['highest_degree'] == 7){echo "selected='selected'";}?>>博士后</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">毕业学校</label>
							<div class="col-sm-2">
								<select class="width-100" id="school_id" name="school_id">
									<?php foreach($school as $k => $v) {?>
										<option value="<?=$v['id'];?>" <?php if($v['id'] == $data['school_id']){echo "selected='selected'";}?>><?=$v['school_name'];?></option>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">专业</label>
							<div class="col-sm-2">
								<select class="width-100" id="major_id" name="major_id">
									<?php foreach($major as $k => $v) {?>
										<option value="<?=$v['id'];?>" <?php if($v['id'] == $data['major_id']){echo "selected='selected'";}?>><?=$v['item'];?></option>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">工作年限</label>
							<div class="col-sm-2">
								<!-- <input type="text" class="width-100" id="card_no" name="card_no" value="<?=$data['work_year']?>"> -->
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">所在城市</label>
							<div class="col-sm-2">
								<!-- <input type="text" class="width-100" id="card_no" name="card_no" value="<?=$data['city_id']?>"> -->
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">电话</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="card_no" name="card_no" value="<?=$data['phone_number']?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">邮箱</label>
							<div class="col-sm-2">
								<!-- <input type="text" class="width-100" id="card_no" name="card_no" value="<?=$data['work_email']?>"> -->
							</div>
						</div>
						<div class="page-header"></div>
					 	<div class="form-group">
					   		<div class="col-sm-offset-1 col-sm-10">
								<!-- <button type="submit" id="submit" class="btn btn-sm btn-primary">确定保存</button>									 -->
								<a href="javascript:history.go(-1)" class="btn btn-sm btn-warning">返回上页</a>
							</div>
					  	</div>
					</form>	
				</div>
			</div>					
		</div>															
	</div>
	<link  rel="stylesheet" href="/public_source/www/css/insure.css" />
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