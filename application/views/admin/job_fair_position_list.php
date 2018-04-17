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
			</ul>
		</div>	
		<input type="hidden" name="message" id="message" value="<?=$message;?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path;?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">	
		<div class="page-content" style="margin-top:8px;">
			<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
					<div style="float:left;margin-right:8px;">
						<a href="job_fair_position_page"><span class="btn btn-xs btn-pink">添加招聘会</span></a>
					</div>
				<div style="float:right;">
					<form action="<?=$this->go_url;?>" method="post">
						<div style="float:left;margin-right:10px;">
							请输入活动名称：
							<input type="text" id="search_field" name="search_field" placeholder="请输入活动名称"  value="<?=$search_field;?>">
						</div>
						<a style="padding-left:10px;float:right;margin-top:1px;" id="search">
							<button type="submit" class="btn btn-xs btn-success">
								<i class="icon-search nav-search-icon "></i>查询
							</button>
						</a>
						<a href="/user_admin/jobhunter_all/jober_excle_add" class="btn btn-xs btn-success" style="float: right;margin-top:1px;">导入简历</a>
					</form>
				</div>
				<div class="page-header" style="height:40px;"></div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="table-header">
						招聘会列表
					</div>
					<div class="table-responsive">
						<table id="sample-table-2" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>id</th>
									<th>招聘会名称</th>
									<th>招聘会时间</th>
									<th>招聘职位</th>
									<th>参会求职者总数</th>
									<th>参会企业总数</th>
									<th>本期基础摊位价(元)</th>
<!--									<th>回报</th>-->
									<th>实际参会企业总数</th>
									<th>实际参会总数</th>
									<th>招聘会城市</th>
                                    <th>招聘会状态</th>
									<th>操作</th>
                                    <th>关闭</th>
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
			var bStateSave = true;
			if(search_field) {
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
	            "sAjaxSource": admin_path+"/Job_fair/ajax_job_fair_position_list?search_field="+search_field, //给服务器发请求的url
	            "aoColumns": [ //这个属性下的设置会应用到所有列，按顺序没有是空
		       	    {"mData": 'id'},	//id
		       	    {"mData": 'name'},	//招聘会名称
		       	    {"mData": 'time'},	//招聘会时间
		       	    {"mData": 'occupation_parent_name'},	//招聘职位
		       	    {"mData": 'all_job_sum'},	//参会求职者总数
		       	    {"mData": 'all_hunter_sum'},	//参会企业总数
		       	    {"mData": 'money'},	//本期基础摊位价
//		       	    {"mData": 'huibao'},	//回报
		       	    {"mData": 'yes_company'},	//实际参会企业总数
		       	    {"mData": 'yes_job'},	//实际参会总数
		       	    {"mData": 'city_id'},	//城市
		       	    {"mData": 'status'},	//状态
					{"mData": 'operate'},
                    {"mData": 'del'}//操作

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