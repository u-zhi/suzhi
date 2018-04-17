<?php include_once('header.php');?>
<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li>用户管理</li>
				<li>求职顾问</li>
			</ul>
		</div>	
		<input type="hidden" name="message" id="message" value="<?=$message;?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path;?>">
		<input type="hidden" name="headhunter_user_id" id="headhunter_user_id" value="<?=$headhunter_user_id;?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">	
		<div class="page-content" style="margin-top:8px;">
			<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
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
						<div class="visible-md visible-lg hidden-sm hidden-xs btn-group" style='margin: 50px 0 54px 3%;'>
							<div style="float:left;margin-right:30px;">
								<a href="<?=$admin_path;?>/headhunter/edit_page/<?=$data['user_id'];?>" class='btn-pink3'><span  class="btn2 btn-xs bottom-ky ">用戶資料</span></a>
							</div>					
							<div style="float:left;margin-right:30px;">
								<a href="<?=$admin_path;?>/headhunter/talent_pool/<?=$data['user_id'];?>" class='btn-pink2'><span class="btn2 btn-xs  bottom-ky ">人才库</span></a>
							</div>						
							<div style="float:left;margin-right:30px;">
								<a href="<?=$admin_path;?>/headhunter/receive_list/<?=$data['user_id'];?>" class='btn-pink4'><span class="btn2 btn-xs  bottom-ky ">接取的任务</span></a>
							</div>							
							<div style="float:left;margin-right:30px;">
								<a href="<?=$admin_path;?>/firm/company_staff/<?=$data['user_id'];?>" class='btn-pink4'><span class="btn2 btn-xs  bottom-ky ">推荐人才订单（先放着后面做）</span></a>
							</div>	
						</div>
				<div class="page-header" style="height:195px;"></div>
				<div style="float:right;">
					<form action="/user_admin/headhunter/talent_pool/<?=$headhunter_user_id;?>" method="post">
						<div style="float:left;margin-right:10px;">
							<input type="text" id="search_field" name="search_field" placeholder="输入昵称搜索"  value="<?=$search_field;?>">
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
						人才列表
					</div>
					<div class="table-responsive">
						<table id="sample-table-2" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>序号</th>
									<th>手机号</th>
									<th>头像</th>
									<th>昵称</th>
									<th>性别</th>
									<th>期望职位</th>
									<th>工作类型</th>
									<th>期望城市</th>
									<th>月薪</th>
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
			var headhunter_user_id = $("#headhunter_user_id").val();
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
	            "sAjaxSource": admin_path+"/headhunter/ajax_talent_list?search_field="+search_field+"&headhunter_user_id="+headhunter_user_id, //给服务器发请求的url
	            "aoColumns": [ //这个属性下的设置会应用到所有列，按顺序没有是空
		       	    {"mData": 'user_id'},
	                {"mData": 'phone_number'}, //mData 表示发请求时候本列的列明，返回的数据中相同下标名字的数据会填充到这一列
	                {"mData": 'avatar_url'},
					{"mData": 'nickname'},
					{"mData": 'gender_name'},
					{"mData": 'occupation'},
					{"mData": 'job_type'},
					{"mData": 'city'},
					{"mData": 'salary'},
	                {"mData": 'operate'},
	            ],
	            "aoColumnDefs": [//和aoColums类似，但他可以给指定列附近爱属性
	               {"bSortable": false, "aTargets": [2,3,4,5,7,8,9]},//（从0开始算） 不能排序
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