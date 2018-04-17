<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li>简历库管理</li>
				<li>编辑导入简历</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="/user_admin/jobhunter_all/jobhunter_list">
		<div class="page-content" style="margin-top:18px;">							
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 速职人才库简历导入编辑</h1>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="/user_admin/jobhunter_all/job_edit_row" onsubmit="return job_add_row()" >
						<input type="hidden" class="width-100" id="user_id" name="user_id" value="<?php echo $data['user_id']?>">
						<input type="text" class="width-100"  id="phone_number" name="phone_number" value="<?php echo $data['phone_number'];?>">
						<div class="form-group">
							<label class="col-sm-1 control-label">用户昵称</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="nickname" name="nickname" value="<?php echo $data['nickname'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">用户手机</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" disabled="disabled"  id="phone_number" name="phone_number" value="<?php echo $data['phone_number'];?>">
							</div>
						</div>
						<div class="form-group">
							<label for="province_id" class="col-sm-1 control-label">选择省份</label>
							<div class="col-sm-2">
								<select class="width-100" id="province_id" name="province_id">
									<?php foreach($parent_list as $k => $v) {?>
										<option value="<?=$v['region_id'];?>"><?=$v['region_name'];?></option>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="city_id" class="col-sm-1 control-label">选择城市</label>
							<div class="col-sm-2">
								<select class="width-100" id="city_id" name="city_id">
									<?php foreach($city_list as $v):?>
										<option value="<?=$v['region_id'];?>"><?=$v['region_name'];?></option>
									<?php endforeach;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">工作年限</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="work_year" name="work_year" value="<?php echo $data['work_year'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">期望薪资</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="wage_lower" name="wage_lower" value="<?php echo $data['wage_lower'];?>">
								<input type="text" class="width-100" id="wage_upper" name="wage_upper" value="<?php echo $data['wage_upper'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">期望职位</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="occupation" name="occupation" value="<?php echo $data['occupation'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">学历</label>
							<div class="col-sm-2">
								<select class="width-100" id="highest_degree" name="highest_degree">
										<option <?php if(0 == $data['highest_degree']){echo "selected='selected'";}?>  value="0">小学</option>
										<option <?php if(1 == $data['highest_degree']){echo "selected='selected'";}?>  value="1">初中</option>
										<option <?php if(2 == $data['highest_degree']){echo "selected='selected'";}?>  value="2">高中</option>
										<option <?php if(3 == $data['highest_degree']){echo "selected='selected'";}?>  value=3>大专</option>
										<option <?php if(4 == $data['highest_degree']){echo "selected='selected'";}?>  value="4">本科学士</option>
										<option <?php if(5 == $data['highest_degree']){echo "selected='selected'";}?>  value="5">硕士</option>
										<option <?php if(6 == $data['highest_degree']){echo "selected='selected'";}?>  value="6">博士</option>
										<option <?php if(7 == $data['highest_degree']){echo "selected='selected'";}?>  value="7">博士后</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">附件上传</label>
							<input id="fileupload" type="file" name="resume_file">
						</div>
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
	<script type="text/javascript">
		var city_arr=<?php echo $city_json;?>
	</script>
	<script type="text/javascript">
		$("#province_id").change(function(){
			var province_id=$(this).val();
			var html='';
			$.each(city_arr['province_'+province_id],function(item,temp){
				html+='<option value="'+temp.region_id+'">'+temp.region_name+'</option>';
			});
			$("#city_id").html(html);
		});
	</script>
<?php include_once('footer.php');?>		