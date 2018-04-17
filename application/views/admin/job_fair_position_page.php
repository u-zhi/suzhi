<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li>人力外包中心</li>
				<li>线上招聘会</li>
				<li>添加线上招聘会</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$admin_path?>/job_fair/job_fair_position_page">
		<div class="page-content" style="margin-top:18px;">
			<a href="javascript:history.go(-1)" class="btn btn-sm btn-warning">返回上页</a>
			<div class="row">
				<div class="col-xs-12">
					<form class="form-horizontal" id="create_position_form" enctype="multipart/form-data" role="form" method="post" action="/user_admin/job_fair/job_fair_position_add"  >
						<div class="form-group">
							<label for="province_id" class="col-sm-3 control-label">选择省份</label>
							<div class="col-sm-2">
								<select class="width-100" id="province_id" name="province_id">
									<?php foreach($parent_list as $k => $v) {?>
										<option value="<?=$v['region_id'];?>"><?=$v['region_name'];?></option>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="city_id" class="col-sm-3 control-label">选择城市</label>
							<div class="col-sm-2">
								<select class="width-100" id="city_id" name="city_id">
									<?php foreach($city_list as $v):?>
										<option value="<?=$v['region_id'];?>"><?=$v['region_name'];?></option>
									<?php endforeach;?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label  class="col-sm-3 control-label">招聘会主题:</label>
							<div class="col-sm-4">
								<input type="text" class="width-100" id="job_fair_name" name="job_fair_name" value="">
							</div>
						</div>						
						<div class="form-group">
							<label  class="col-sm-3 control-label">招聘会时间:</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="begin_time" name="begin_time" value="">
							</div>
							<div style="width: 15px;float: left">至</div>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="end_time" name="end_time" value="">
							</div>
						</div>						

						<div class="form-group">
							<label  class="col-sm-3 control-label">人才类型:</label>
							<div class="col-sm-9" id="tag_div">

								<button class="btn btn-sm" type="button" id="add_tag">添加</button>
							</div>
						</div>						

<!-- 						<div class="form-group">
							<label  class="col-sm-3 control-label">导入参会求职者:</label>
							<div class="col-sm-4">
									导入参会求职者<a href="">+</a>
							</div>
						</div>						

						<div class="form-group">
							<label  class="col-sm-3 control-label">导入参会职位:</label>
							<div class="col-sm-4">
									导入参会职位<a href="">+</a>
							</div>
						</div> -->						
						<div class="form-group">
							<label  class="col-sm-3 control-label">收费设置:</label>
							<div class="col-sm-9">
								<button class="btn btn-sm" type="button" id="add_position">添加</button>
							</div>
						</div>

					 	<div class="form-group">
							<label  class="col-sm-3 control-label"></label>
					   		<div class="col-sm-offset-1 col-sm-6">
								<button type="submit" id="submit" class="btn btn-sm btn-primary">发布</button>
							</div>
					  	</div>
					</form>	
				</div>
			</div>					
		</div>													
	</div>
	<script src="/public_source/www/js/common.js" type="text/javascript"></script>
	<script src="/public_source/www/js/form_check.js" type="text/javascript"></script>
<!--datetimepicker-->
<link rel="stylesheet" type="text/css" href="/public_source/www/js/datetimepicker/jquery.datetimepicker.css"/ >
<script src="/public_source/www/js/datetimepicker/jquery.datetimepicker.full.js"></script>
<link rel="stylesheet" href="/public_source/www/js/layer/skin/default/layer.css"/>
<script src="/public_source/www/js/layer/layer.js"></script>
<script type="text/html" id="add_position_div">
	<form action="" method="post" id="add_position_form" role="form" enctype="multipart/form-data" class="form-horizontal" style="margin-top: 20px;width: 400px;">
		<div class="form-group">
			<label class="col-sm-4 control-label" for="position_name">名称</label>
			<div class="col-sm-8">
				<input type="text" value="" name="position_name" id="position_name" class="width-100">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label" for="interview_number">邀面名额</label>
			<div class="col-sm-8">
				<input type="text" value="" name="interview_number" id="interview_number" class="width-100">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label" for="is_top">职位发布</label>
			<div class="col-sm-8">
				<select name="is_top" id="is_top" class="width-100">
					<option value="1">无置顶</option>
					<option value="2">置顶</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label" for="money">金额</label>
			<div class="col-sm-8">
				<input type="text" value="" name="money" id="money" class="width-100">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label" for="number">摊位数量</label>
			<div class="col-sm-8">
				<input type="text" value="" name="number" id="number" class="width-100">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-1 col-sm-11">
				<button class="btn btn-sm btn-primary" id="submit" type="submit" style="float: right;">确定</button>
			</div>
		</div>
	</form>
</script>
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
<script>
	//添加人才类型
	$("#add_tag").click(function () {
		var html='<span class="tag_span"><input type="text" value="" name="tag[]"/><i>X</i></span>';
		$(this).before(html);
	});
</script>
<script>
	$.datetimepicker.setLocale('ch');
	$('#begin_time').datetimepicker({
		lang:"ch", //语言选择中文 注：旧版本 新版方法：$.datetimepicker.setLocale('ch');
		format:"Y-m-d H:i:s",      //格式化日期
		timepicker:true,   //关闭时间选项
		todayButton:true    //关闭选择今天按钮
	});
	$('#end_time').datetimepicker({
		lang:"ch", //语言选择中文 注：旧版本 新版方法：$.datetimepicker.setLocale('ch');
		format:"Y-m-d H:i:s",      //格式化日期
		timepicker:true,   //关闭时间选项
		todayButton:true    //关闭选择今天按钮
	});
	//添加摊位
	$("#add_position").click(function () {
		layer.open({
			type: 1,
			area: ['500px', '340px'],
			shadeClose: false, //点击遮罩关闭
			content:$("#add_position_div").html()
		});
	});
	$(document).on("submit","#add_position_form",function(){
		var position_name=$(this).find("input[name='position_name']").val();
		var interview_number=$(this).find("input[name='interview_number']").val();
		var money=$(this).find("input[name='money']").val();
		var is_top=$(this).find("select[name='is_top']").val();
		var number=$(this).find("input[name='number']").val();
		if(position_name==""){
			alert("请填写摊位名称");return false;
		}
		if(interview_number=="" || !/^\d+$/.test(interview_number)){
			alert("请填写正确邀面名额");return false;
		}
		if(money=="" || !/^\d+(\.\d{1,2})?$/.test(money)){
			alert("请填写正确金额");return false;
		}
		if(number=="" || !/^\d+$/.test(number)){
			alert("请填写正确摊位数量");return false;
		}
		if(is_top==1){
			var top_str="无置顶";
		}else{
			var top_str="置顶";
		}
		var html='<div class="position_div">'+position_name+'<br/>';
		html+='<input type="hidden" name="position_name[]" value="'+position_name+'">';
		html+='<input type="hidden" name="interview_number[]" value="'+interview_number+'">';
		html+='<input type="hidden" name="money[]" value="'+money+'">';
		html+='<input type="hidden" name="is_top[]" value="'+is_top+'">';
		html+='<input type="hidden" name="number[]" value="'+number+'">';
		html+='<span class="close">X</span>';
		html+='邀面名额：'+interview_number+'个<br/>职位发布：'+top_str+'<br/>金额：'+money+'元 <br>摊位数量：'+number+'</div>';
		$("#add_position").before(html);
		$(".layui-layer-close").click();
		return false;
	});


	$(document).on("hover",".tag_span",function(){
		$(this).find("i").toggle();
	});
	$(document).on("click",".tag_span i",function(){
		$(this).closest(".tag_span").remove();
	});
	$(document).on("click",".position_div .close",function(){
		$(this).closest(".position_div").remove();
	});
</script>
<script>
	//提交表单
	$("#create_position_form").on("submit",function(){
		var name=$("#job_fair_name").val();
		var begin_time=$("#begin_time").val();
		var end_time=$("#end_time").val();
		if(name==""){
			alert("招聘会主题");return false;
		}
		if(begin_time=="" || end_time==""){
			alert("请填写完整招聘会时间");return false;
		}
		if($("input[name='tag[]']").length<=0){
			alert("请填写人才类型");return false;
		}
		var flag=true;
		$("input[name='tag[]']").each(function(){
			if($(this).val()==""){
				flag=false;
			}
		});
		if(flag==false){
			alert("所有人才类型不能为空");return false;
		}
		if($("input[name='position_name[]']").length<=0){
			alert("请填写收费设置");return false;
		}

	});
</script>
<style>
	.tag_span{
		display: inline-block;
		text-align: center;
		padding: 10px;
		margin-right: 10px;
		border:1px solid #d5d5d5;
		position: relative;
	}
	.tag_span input{
		width: 80px;
		padding: 0!important;
		border:none;
	}
	.tag_span i{
		position: absolute;
		width: 8px;
		height: 8px;
		color:red;
		right:-4px;
		top:-9px;
		cursor: pointer;
		font-style: normal;
		display: none;
	}
	.position_div{
		text-align: center;
		padding: 10px;
		background-color: #F2F2F2;
		line-height: 22px;
		display: inline-block;
		margin-right: 10px;
		width: 150px;
		position: relative;
	}
	.position_div .close{
		position: absolute;
		right: -4px;
		top:-6px;
		color: red;
	}
</style>
<?php include_once('footer.php');?>		