<?php include_once('header.php');?>
<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li>用户管理</li>
				<li>企业中心</li>
			</ul>
		</div>	
		<input type="hidden" name="message" id="message" value="<?=$message;?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path;?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">	
		<div class="page-content" style="margin-top:8px;">
			<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
<!--				<div >-->
<!--					<label for="title" style="float:left;height:40px;">上下架</label >-->
<!--					<div class="col-sm-1">-->
<!--						<select class="width-100" >-->
<!--							<option >请选择</option>-->
<!--							<option value="0">上架</option>-->
<!--							<option value="1">下架</option>-->
<!--						</select>-->
<!--					</div>-->
<!--				</div>-->
<!--				<div class="page-header" style="height:40px;"></div>-->
				<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
<!-- 					<div style="float:left;margin-right:8px;">
						<a href="add_page"><span class="btn btn-xs btn-pink">添加企业</span></a>
					</div>
					<div class="page-header" style="height:40px;"></div> -->
				</div>
				<div style="width:100%;">
					<div style="float:right;">	
						<form action="<?=$this->go_url;?>" method="post">
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
							<div style="float:left;margin-right:10px;">
								<input type="text" id="search_field" name="search_field" placeholder="输入企业名称搜索"  value="<?=$search_field;?>">
							</div>								
							<a style="padding-left:10px;float:left;margin-top:1px;" id="search">
								<button type="submit" class="btn btn-xs btn-success">
									<i class="icon-search nav-search-icon "></i>查询
								</button>
							</a>					
						</form>
					</div>
				</div>
				<div class="page-header" style="height:40px;"></div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="table-header">
						企业列表
					</div>
					<div class="table-responsive">
						<table id="sample-table-2" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>序号</th>
									<th>企业名称</th>
									<th>公司头像</th>
									<th>联系人</th>
									<th>手机号</th>
									<th>融资情况</th>
									<th>公司规模</th>
									<th>热招职位个数</th>
									<th>内推系统</th>
									<th>邀面次数</th>
									<th>外包项目</th>
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
	            "sAjaxSource": admin_path+"/firm/ajax_firm_list?search_field="+search_field+"&province_id="+province_id+"&city_id="+city_id, //给服务器发请求的url
	            "aoColumns": [ //这个属性下的设置会应用到所有列，按顺序没有是空
		       	    {"mData": 'id'},
	                {"mData": 'name'}, //mData 表示发请求时候本列的列明，返回的数据中相同下标名字的数据会填充到这一列
	                {"mData": 'logo_url'},
					{"mData": 'contact'},
					{"mData": 'phone_number'},
					{"mData": 'financing'},
					{"mData": 'scale_type'},
					{"mData": 'counts'},
					{"mData": 'innerpush'},//内推
					{"mData": 'interview'},//邀请
					{"mData": 'company_task'},//外包
	                {"mData": 'operate'},
	            ],
	            "aoColumnDefs": [//和aoColums类似，但他可以给指定列附近爱属性
	               {"bSortable": false, "aTargets": [1,2,3,5,9]},//（从0开始算） 不能排序
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