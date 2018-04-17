<?php include_once('header.php');?>
<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li>订单中心</li>
				<li>简历投递的求职者</li>
			</ul>
		</div>	
		<input type="hidden" name="message" id="message" value="<?=$message;?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path;?>">
		<input type="hidden" name="path" id="path" value="<?=$this->wait_url;?>">
		<div class="page-content" style="margin-top:8px;">
			<a href="javascript:history.go(-1)" class="btn btn-sm btn-warning">返回上页</a>
			<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
					<div class="visible-md visible-lg hidden-sm hidden-xs btn-group" style='margin: 20px 0 40px 20%;'>
							<div style="float:left;margin-right:30px;">
								<a href="<?=$admin_path;?>/order/edit_pageseseses/<?php echo $data['order_id']?>" class='btn-pink3'><span  class="btn2 btn-xs bottom-ky ">招聘信息</span></a>
							</div>					
							<div style="float:left;margin-right:30px;">
								<a href="<?=$admin_path;?>/order/wait_list/<?php echo $data['order_id']?>" class='btn-pink3'><span class="btn2 btn-xs  bottom-ky ">简历投递的求职者</span></a>
							</div>							
							<div style="float:left;margin-right:30px;">
								<a href="<?=$admin_path;?>/order/people_list_tock/<?php echo $data['order_id']?>" class='btn-pink2'><span class="btn2 btn-xs  bottom-ky ">面试的求职者</span></a>
							</div>						
						</div>
					<div class="page-header" style="height:180px;"></div>

				<div style="float:right;">
						<input type="hidden" id="order_id" value="<?=$data['order_id']?>">
<!-- 					<form action="<?=$this->wait_url;?>" method="post">
						<div style="float:left;margin-right:10px;">
							<input type="text" id="search_field" name="search_field" placeholder="订单编号或求职者手机号或任务名称搜索"  value="<?=$search_field;?>">
						</div>
						<a style="padding-left:10px;float:left;margin-top:1px;" id="search">
							<button type="submit" class="btn btn-xs btn-success">
								<i class="icon-search nav-search-icon "></i>查询
							</button>
						</a>
					</form> -->
				</div>
				<!-- <div class="page-header" style="height:40px;"></div> -->
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="table-header">
						求职者待面试列表
					</div>
					<div class="table-responsive">
						<table id="sample-table-2" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>序号</th>
									<th>职位</th>
									<th>求职者</th>
									<th>联系方式</th>
									<th>来源</th>
									<th>求职顾问</th>
									<th>面试时间</th>
									<th>状态</th>
									<th>操作</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>														
	</div>
	<link  rel="stylesheet" href="/public_source/www/css/insure.css" />
	<script src="/public_source/www/assets/js/jquery.dataTables.min.js"></script>
	<script src="/public_source/www/assets/js/jquery.dataTables.bootstrap.js"></script>
	<script src="/public_source/www/js/common.js"></script>
	<link href="/public_source/www/css/style.css" rel="stylesheet" type="text/css"/>
	<script src="/public_source/www/js/jquey-bigic.js" type="text/javascript"></script>
	<script type="text/javascript">
		jQuery(function($) {
			var admin_path = $("#admin_path").val();
			var order_id = $("#order_id").val();
			var bStateSave = true;
			if(order_id) {
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
	            "sAjaxSource": admin_path+"/order/people_list_tock_list?order_id="+order_id, //给服务器发请求的url
	            "aoColumns": [ //这个属性下的设置会应用到所有列，按顺序没有是空
		       	    {"mData": 'id'},
					{"mData": 'job'},
					{"mData": 'jobname'},
					{"mData": 'phone'},
	                {"mData": 'add_type'}, //mData 表示发请求时候本列的列明，返回的数据中相同下标名字的数据会填充到这一列
	                {"mData": 'hunter'}, //
	                {"mData": 'jobhunter_interview_time'}, //
	                {"mData": 'current_status'}, //
	                {"mData": 'operate'}, //

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
<?php include_once('footer.php');?>