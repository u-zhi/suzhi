<?php include_once('header.php');?>
<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li>订单中心</li>
				<li>招聘订单列表</li>
			</ul>
		</div>	
		<input type="hidden" name="message" id="message" value="<?=$message;?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path;?>">
		<input type="hidden" name="path" id="path" value="<?=$this->problem_url;?>">
		<div class="page-content" style="margin-top:8px;">
			<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
				<div style="float:right;width: 1200px;">
					<form action="<?=$this->problem_url;?>" method="post" >
						<div style="float:left;margin-right:10px;">
							企业名称：
							<input type="text" id="firm_id" name="firm_id" placeholder="企业名称"  value="<?=$firm_id;?>">
						</div>						
						<div style="float:left;margin-right:10px;">
							订单号:
							<input type="text" id="trade_no" name="trade_no" placeholder="订单编号"  value="<?=$trade_no;?>">
						</div>						
						<div style="float:left;margin-right:10px;">
							<select class="width-100" id="recruit_type" name="recruit_type">
									<option value="">请选择模式</option>
										<?php foreach($recruit_type_list as $k => $v) {?>
												<option value="<?=$v['recruit_type_id'];?>" <?php if($recruit_type == $v['recruit_type_id']) echo 'selected="selected;"';?> ><?=$v['recruit_type_name'];?></option>
										<?php }?>
							</select>
						</div>						
						<div style="float:left;margin-right:10px;">
							<select class="width-100" id="task_type" name="task_type">
										<option value="">请选择类型</option>
										<?php foreach($task_type_list as $k => $v) {?>
												<option value="<?=$v['task_type_id'];?>" <?php if($task_type == $v['task_type_id']) echo 'selected="selected;"';?> ><?=$v['task_type_name'];?></option>
										<?php }?>
							</select>
						</div>						
						<div style="float:left;margin-right:10px;">
							职位:
							<input type="text" id="search_field" name="search_field" placeholder="职位"  value="<?=$search_field;?>">
						</div>
						<a style="padding-left:10px;float:left;margin-top:1px;" id="search">
							<button type="submit" class="btn btn-xs btn-success">
								<i class="icon-search nav-search-icon "></i>查询
							</button>
						</a>
					</form>
				</div>
				<div class="page-header" style="height:40px;"></div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="table-header">
						招聘订单列表
					</div>
					<div class="table-responsive">
						<table id="sample-table-2" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>订单编号</th>
									<th>职位</th>
									<th>时间</th>
									<!-- <th>职位</th> -->
									<th>企业</th>
									<th>模式</th>
									<th>类型</th>
									<th>佣金</th>
									<th>人数</th>
									<th>收到简历</th>
									<!-- <th>面试人数</th> -->
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
			var firm_id = $("#firm_id").val();
			var trade_no = $("#trade_no").val();
			var recruit_type = $("#recruit_type").val();
			var task_type = $("#task_type").val();
			var bStateSave = true;
			if(search_field|| firm_id || trade_no || recruit_type || task_type ) {
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
	            "sAjaxSource": admin_path+"/order/ajax_problem_list?search_field="+search_field+"&firm_id="+firm_id+"&trade_no="+trade_no+"&recruit_type="+recruit_type+"&task_type="+task_type, //给服务器发请求的url
	            "aoColumns": [ //这个属性下的设置会应用到所有列，按顺序没有是空
		       	    {"mData": 'id'},//订单编号
					{"mData": 'name'},//任务名称
					{"mData": 'on_time'},//时间
					// {"mData": 'occupation_id'},//职位
					{"mData": 'firm_id'},//企业
	                {"mData": 'recruit_type'},//模式 
	                {"mData": 'task_type'},//类型
					{"mData": 'salary'},//佣金
					{"mData": 'person_demand'},//人数
					{"mData": 'recv_cv_times'},//收到简历
					// {"mData": 'alltime'},//面试人数  待定
	                {"mData": 'operate'},
	            ],
	            "aoColumnDefs": [//和aoColums类似，但他可以给指定列附近爱属性
	               {"bSortable": false, "aTargets": [1,2,3,4,5,6,7,8,]},//（从0开始算） 不能排序
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
<?php include_once('footer.php');?>