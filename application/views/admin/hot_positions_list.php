<?php include_once('header.php');?>
<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="<?=$admin_path;?>/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li><a href="<?=$this->go_url;?>">用户管理</a></li>
				<li><a href="<?=$this->go_url;?>">企业中心</a></li>
				<li>发布的职位</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message;?>">
		<input type="hidden" name="message" id="firm_id" value="<?=$firm_id;?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path;?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">
		<div class="page-content" style="margin-top:8px;">
			<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
<!-- 				<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
					<div style="float:left;margin-right:8px;">
						<a href="<?=$admin_path;?>/plurality/add_page"><span class="btn btn-xs btn-pink">添加兼职</span></a>
					</div>
					<div style="float:left;margin-right:8px;">
						<a href="<?=$admin_path;?>/fulltime/add_page"><span class="btn btn-xs btn-pink">添加全职</span></a>
					</div>
					<div style="float:left;margin-right:8px;">
						<a href="<?=$admin_path;?>/practice/add_page"><span class="btn btn-xs btn-pink">添加实习</span></a>
					</div>
				</div> -->
				<div class="horizontal">
				<div class="form-group">
					<label  class="col-sm-1 control-label">当前企业头像</label>
					<div class="col-sm-2">
						<img id="bigic" src="<?php echo $data['icon_url'];?>" style="max-height:160px;max-width:835px;" />
					</div>

				</div>
				<div class="form-group " >
					<label class="col-sm-1 control-label">企业名称:</label>
					<div class="col-sm-2">
						<input type="text" class="width-100" id="name" name="name" value="<?php echo $data['name']?>">
					</div>							
					<label class="col-sm-1 control-label">余额:</label>
					<div class="col-sm-2">
						<input type="text" class="width-100" id="money" name="money" value="<?php echo  ($data['money']/100)?>">
					</div>
					<label class="col-sm-1 control-label"></label>
					<a href="javascript:add_recharge();"><span class="btn btn-xs btn-pink">充值</span></a>

				</div>
				<div class="visible-md visible-lg hidden-sm hidden-xs btn-group" style='margin: 124px 0 40px -5%;'>

					<div style="float:left;margin-right:30px;">
						<a href="<?=$admin_path;?>/firm/edit_page/<?=$data['id']?>" class='btn-pink3'><span  class="btn2 btn-xs bottom-ky ">企业资料</span></a>
					</div>					
					<div style="float:left;margin-right:30px;">
						<a href="<?=$admin_path;?>/firm/hot_positions/<?=$data['id']?>" class='btn-pink2'><span class="btn2 btn-xs  bottom-ky ">发布的职位</span></a>
					</div>						
					<div style="float:left;margin-right:30px;">
						<a href="<?=$admin_path;?>/firm/company_buy/<?=$data['id']?>" class='btn-pink4'><span class="btn2 btn-xs  bottom-ky ">购买的服务</span></a>
					</div>							
					<div style="float:left;margin-right:30px;">
						<a href="<?=$admin_path;?>/firm/company_staff/<?=$data['id']?>" class='btn-pink4'><span class="btn2 btn-xs  bottom-ky ">企业成员</span></a>
					</div>
                    <div style="float:left;margin-right:30px;">
                        <a href="<?=$admin_path;?>/firm/company_suzhi/<?=$data['id']?>" class='btn-pink4'><span class="btn2 btn-xs  bottom-ky ">速职币</span></a>
                    </div>
                </div>
				<div class="page-header" style="height:120px;"></div>
				</div>
				<div style="width:100%;">
					<div style="float:right;">
						<form action="/user_admin/firm/hot_positions/<?php echo $data["id"]?>" method="post">
							<div style="float:left;margin-right:10px;">
								<input type="text" id="search_field" name="search_field" placeholder="输入职位名称搜索"  value="<?=$search_field;?>">
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
						企业发布职位列表
					</div>
					<div class="table-responsive">
						<table id="sample-table-2" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>序号</th>
									<th>时间</th>
									<th>职位</th>
									<th>面试联系人</th>
									<th>模式</th>
									<th>类型</th>
									<th>佣金</th>
									<th>人数</th>
									<th>收到简历</th>
									<th>面试人数</th>
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
			var search_field = $("#search_field").val();
			var firm_id=$('#firm_id').val();
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
	            "sAjaxSource": admin_path+"/firm/ajax_positions_list?search_field="+search_field+"&firm_id="+firm_id, //给服务器发请求的url
	            "aoColumns": [ //这个属性下的设置会应用到所有列，按顺序没有是空
		       	    {"mData": 'id'},
	                {"mData": 'create_time'},
	                {"mData": 'name'}, //mData 表示发请求时候本列的列明，返回的数据中相同下标名字的数据会填充到这一列
					{"mData": 'link_name'},
					{"mData": 'mt_type'},//模式
					{"mData": 'task_type'},
					{"mData": 'commission'},//佣金
					{"mData": 'person_demand'},
					{"mData": 'recv_cv_times'},
					{"mData": 'recv_cv_yes_times'},
					{"mData": 'recv_type'},//状态
	                {"mData": 'operate'},
	            ],
	            "aoColumnDefs": [//和aoColums类似，但他可以给指定列附近爱属性
	               {"bSortable": false, "aTargets": [1,2,3,5,8]},//（从0开始算） 不能排序
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
	<link rel="stylesheet" href="/public_source/www/js/layer/skin/default/layer.css"/>
	<script src="/public_source/www/js/layer/layer.js"></script>
	<script type="text/html" id="add_recharge_form">
		<form class="col-sm-12" action="/user_admin//firm/pay_company" method="post" role="form" enctype="multipart/form-data" class="form-horizontal" id="recharge_form">
			<div class="form-group">
				<label class="control-label">充值金额：</label>
				<div class="">
					<input type="text" value="" name="money" id="money" class="width-100">
				</div>
				<input name="id" type="hidden" value="<?php echo $data['id'];?>"/>
			</div>
			<div class="form-group">
				<div class="">
					<button class="btn btn-sm btn-primary" id="submit" type="submit" style="float: right">确认充值</button>
				</div>
			</div>
		</form>
	</script>
	<script>
	//充值
	function add_recharge(){
		layer.open({
			type: 1,
			area: ['300px', '150px'],
			shadeClose: false, //点击遮罩关闭
			content:$("#add_recharge_form").html()
		});
	}
	//确认充值
	$(document).on("submit","#recharge_form",function () {
		var money=$("#money").val();
		if(money=="" || !/^\d+(\.\d{1,2})?$/.test(money) || parseFloat(money)<=0){
			alert("请填写正确的充值金额");return false;
		}
	});
	</script>

<?php include_once('footer.php');?>