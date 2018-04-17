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
				<li>邀请面试</li>
			</ul>
		</div>	
		<input type="hidden" name="message" id="message" value="<?=$message;?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path;?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url2;?>">	
		<div class="page-content" style="margin-top:8px;">
<!--			<div class="alert alert-info">-->
<!--				<b><i class="icon-comment-alt"></i> 说明：点击id栏左侧图标可查看商品其他信息，图册最多展示3张！</b>-->
<!--			</div>-->
			<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
				<div style="width:100%;">				
					<div style="float:left;margin-right:30px;">
						<a href="/user_admin/pay/pay_list" class='btn-pink4'><span  class="btn2 btn-xs bottom-ky ">企业内推</span></a>
					</div>					
					<div style="float:left;margin-right:30px;">
						<a href="/user_admin/pay/interview_list" class='btn-pink2'><span class="btn2 btn-xs  bottom-ky ">邀请面试</span></a>
					</div>						
					<div style="float:left;margin-right:30px;">
						<a href="/user_admin/pay/package_list" class='btn-pink4'><span class="btn2 btn-xs  bottom-ky ">套餐</span></a>
					</div>
                    <div style="float:left;margin-right:30px;">
                        <a href="/user_admin/pay/suzhi_list" class='btn-pink4'><span class="btn2 btn-xs  bottom-ky ">速职币</span></a>
                    </div>
                    <div class="page-header" style="height:100px;"></div>
					<div style="float:left;margin-right:8px;">
						<?php if($authority['add_status'] == 2) {?>
						<a href="/user_admin/pay/interview_add_page"><span class="btn btn-xs btn-pink">添加邀请面试服务</span></a>
						<?php }?>
					</div>
					<div style="float:right;">		
						<form action="<?=$this->go_url2;?>" method="post">	
							<div style="float:left;margin-right:10px;">
								<input type="text" id="search_field" name="search_field" placeholder="输入开通金额"  value="<?=$search_field;?>">
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
						邀请面试
					</div>
					<div class="table-responsive">
						<table id="sample-table-2" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>id</th>
									<th>人数限制</th>
									<th>过期时间</th>
									<th>所需金额</th>
									<th>操作</th>
								</tr>
							</thead>
						</table>
					</div><!-- /.table-responsive -->
				</div><!-- /span -->
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
		var oTable1 = $('#sample-table-2').dataTable({ 
	        "oLanguage": {
	                "sUrl": "/public_source/www/assets/language/zh_CN.json"
	         },			     	
			"aaSorting": [[ 0, "desc" ]],
			"bAutoWidth": false, 
			"bStateSave": true,//加载记忆页码
			"bProcessing": true, //开启读取服务器数据时显示正在加载中……特别是大数据量的时候，开启此功能比较好
            "bServerSide": true, //开启服务器模式，使用服务器端处理配置datatable。注意：sAjaxSource参数也必须被给予为了给datatable源代码来获取所需的数据对于每个画。 这个翻译有点别扭。开启此模式后，你对datatables的每个操作 每页显示多少条记录、下一页、上一页、排序（表头）、搜索，这些都会传给服务器相应的值。 
            "sAjaxSource": admin_path+"/pay/ajax_interview_list?search_field="+search_field, //给服务器发请求的url
            "aoColumns": [ //这个属性下的设置会应用到所有列，按顺序没有是空
	       	    {"mData": 'id'},
                {"mData": 'number'}, //mData 表示发请求时候本列的列明，返回的数据中相同下标名字的数据会填充到这一列
                {"mData": 'expire_year'}, //mData 表示发请求时候本列的列明，返回的数据中相同下标名字的数据会填充到这一列
                {"mData": 'money'}, //mData 表示发请求时候本列的列明，返回的数据中相同下标名字的数据会填充到这一列
                {"mData": 'operate'},
            ],
            "aoColumnDefs": [//和aoColums类似，但他可以给指定列附近爱属性
               {"bSortable": false, "aTargets": [4]},//（从0开始算） 不能排序
        	]
		});
	});

</script>
<?php include_once('footer.php');?>




