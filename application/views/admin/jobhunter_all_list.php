<?php include_once('header.php');?>
<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li>人力外包中心</li>
				<li>简历库管理</li>
			</ul>
		</div>	
		<input type="hidden" name="message" id="message" value="<?=$message;?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path;?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">	
		<div class="page-content" style="margin-top:8px;">
			<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
				<div style="float:right;">
					<form action="<?=$this->go_url;?>" method="post">
						<div style="float:left;margin-right:10px;">
<!-- 						期望职位
						工作年限 -->
						<!-- 期望薪资 这些先待定 -->
								<div class="col-sm-2">
									<select class="width-100" id="province_id" name="province_id">
										<option value="">开通省份</option>
										<?php foreach($parent_list as $k => $v) {?>
												<option value="<?=$v['region_id'];?>" <?php if($province_id == $v['region_id']) echo 'selected="selected;"';?> ><?=$v['region_name'];?></option>
										<?php }?>
									</select>
								</div>
								<div class="col-sm-2">
									<select class="width-100" id="city_id" name="city_id">
										<option value="">开通城市</option>
										<?php foreach($city_list as $v):?>
											<option value="<?=$v['region_id'];?>" <?php if($city_id == $v['region_id']) echo 'selected="selected;"';?>><?=$v['region_name'];?></option>
										<?php endforeach;?>
									</select>
								</div>
							最高学历
							<select style="width:100px;" id="user_jobhunter_extra_info_highest_degree" name="user_jobhunter_extra_info_highest_degree">
								<option value="0" <?php if($user_jobhunter_extra_info_highest_degree == 0) echo 'selected="selected;"';?>>小学</option>
								<option value="1" <?php if($user_jobhunter_extra_info_highest_degree == 1) echo 'selected="selected;"';?>>初中</option>
								<option value="2" <?php if($user_jobhunter_extra_info_highest_degree == 2) echo 'selected="selected;"';?>>高中</option>
								<option value="3" <?php if($user_jobhunter_extra_info_highest_degree == 3) echo 'selected="selected;"';?>>大专</option>
								<option value="4" <?php if($user_jobhunter_extra_info_highest_degree == 4) echo 'selected="selected;"';?>>本科学士</option>
								<option value="5" <?php if($user_jobhunter_extra_info_highest_degree == 5) echo 'selected="selected;"';?>>硕士</option>
								<option value="6" <?php if($user_jobhunter_extra_info_highest_degree == 6) echo 'selected="selected;"';?>>博士</option>
								<option value="7" <?php if($user_jobhunter_extra_info_highest_degree == 7) echo 'selected="selected;"';?>>博士后</option>
							</select>
							请输入名称：
							<input type="text" id="search_field" name="search_field" placeholder="输入昵称搜索"  value="<?=$search_field;?>">

						<a style="padding-left:10px;float:right;margin-top:1px;" id="search">
							<button type="submit" class="btn btn-xs btn-success">
								<i class="icon-search nav-search-icon "></i>查询
							</button>
						</a>
						<a href="/user_admin/jobhunter_all/jober_excle_add" class="btn btn-xs btn-success" style="float: right;">导入简历</a>
						<!-- <a href="/user_admin/jobhunter_all/jober_add" class="btn btn-xs btn-success" style="float: right;"> 导入简历</a> -->
						</div>
					</form>
				</div>
				<div class="page-header" style="height:40px;"></div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="table-header">
						求职者列表
					</div>
					<div class="table-responsive">
						<table id="sample-table-2" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>序号</th>
									<th>意向职位</th>
									<th>期望薪资</th>
									<th>求职者</th>
									<th>工作年限</th>
									<th>所在城市</th>
									<th>类型</th>
									<th>操作</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>														
	</div>
	<script src="/public_source/www/assets/js/jquery.dataTables.min.js"></script>
	<script src="/public_source/www/assets/js/jquery.dataTables.bootstrap.js"></script>
	<script src="/public_source/www/js/common.js"></script>
	<link href="/public_source/www/css/style.css" rel="stylesheet" type="text/css"/>
	<script src="/public_source/www/js/jquey-bigic.js" type="text/javascript"></script>
	<script type="text/javascript">
		jQuery(function($) {
			var admin_path = $("#admin_path").val();
			var search_field = $("#search_field").val();
			var province_id = $("#province_id").val();
			var city_id = $("#city_id").val();
			var highest_degree = $("#user_jobhunter_extra_info_highest_degree").val();
			var bStateSave = true;
			if(search_field || province_id|| city_id) {
				bStateSave = false;
			}
			var oTable1 = $('#sample-table-2').dataTable({
		        "oLanguage": {
		                "sUrl": "/public_source/www/assets/language/zh_CN.json"
		         },
				"aaSorting": [[0, "desc" ]],
				"bAutoWidth": false,
				"bStateSave": bStateSave,//加载记忆页码
				"bProcessing": true, //开启读取服务器数据时显示正在加载中……特别是大数据量的时候，开启此功能比较好
	            "bServerSide": true, //开启服务器模式，使用服务器端处理配置datatable。注意：sAjaxSource参数也必须被给予为了给datatable源代码来获取所需的数据对于每个画。 这个翻译有点别扭。开启此模式后，你对datatables的每个操作 每页显示多少条记录、下一页、上一页、排序（表头）、搜索，这些都会传给服务器相应的值。
	            "sAjaxSource": admin_path+"/jobhunter_all/ajax_jobhunter_list?search_field="+search_field+"&highest_degree="+highest_degree+"&province_id="+province_id+"&city_id="+city_id, //给服务器发请求的url
	            "aoColumns": [ //这个属性下的设置会应用到所有列，按顺序没有是空
		       	    {"mData": 'user_id'},	//序号
	                {"mData": 'occupation'}, 	//意向职位
	                {"mData": 'lower'},	//期望薪资
					{"mData": 'nickname'},	//求职者
					{"mData": 'work_year'},	//工作年限
					{"mData": 'city_name'},	//所在城市
					{"mData": 'type_daor'},	//类型
					{"mData": 'operate'},	//操作
	            ],
	            "aoColumnDefs": [//和aoColums类似，但他可以给指定列附近爱属性
	               {"bSortable": false, "aTargets": [1,2,3,4,5]},//（从0开始算） 不能排序
	        	]
			});
		});
		$(document).ready(function() {
			mytime = setInterval(function(){bigic()}, 1000);
		});
		function bigic () {
			$('#sample-table-2 img').bigic();
		}
		function func() {
			var vs = $('select  option:selected').val();

		}
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
<?php include_once('footer.php');?>