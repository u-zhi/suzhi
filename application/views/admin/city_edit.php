<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li>产品管理</li>
				<li>城市区域</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$admin_path?>/occupation/occupation_list">
		<div class="page-content" style="margin-top:18px;">							
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 修改开发城市信息</h1>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="/user_admin/city/edit"  >
						<input type="hidden" class="width-100" id="id" name="id" value="<?php echo $data['id']?>">
						<div class="form-group">
							<label for="province_id" class="col-sm-1 control-label">选择省份</label>
							<div class="col-sm-2">
								<select class="width-100" id="province_id" name="province_id">
									<?php foreach($parent_list as $k => $v) {?>
										<option value="<?=$v['region_id'];?>" <?php if($v['region_id'] == $data['province_id']){echo "selected='selected'";}?> ><?=$v['region_name'];?></option>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="city_id" class="col-sm-1 control-label">选择城市</label>
							<div class="col-sm-2">
								<select class="width-100" id="city_id" name="city_id">
									<?php foreach($city_list as $v):?>
										<option value="<?=$v['region_id'];?>" <?php if($v['region_id'] == $data['city_id']){echo "selected='selected'";}?> ><?=$v['region_name'];?></option>
									<?php endforeach;?>
								</select>
							</div>
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
