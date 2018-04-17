<?php include_once('header.php');?>
<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li>日志管理</li>
				<li>商品修改管理</li>
			</ul>
		</div>	
		<input type="hidden" name="message" id="message" value="<?=$message;?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path;?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">	
		<div class="page-content" style="margin-top:8px;">
			<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
				<div style="width:100%;">
					<div style="float:right;">		
						<form action="<?=$this->go_url;?>" method="post">
							<div style="float:left;margin-right:10px;">
								<input style="cursor:pointer;" type="text" placeholder="请选择时间" class="Wdate" id="start_time" name="start_time" value="<?=$start_time;?>" id="d412" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />								 	
								&nbsp;<input type="text" id="admin_name" name="admin_name" placeholder="输入操作帐号搜索"  value="<?=$admin_name;?>">
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
						商品修改日志列表
					</div>
					<div class="table-responsive">
						<table id="sample-table-2" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>id</th>
									<th>操作帐号</th>
									<th>帐号权限</th>
									<th>登陆地点</th>
									<th>登陆IP</th>
									<th>商品id</th>
									<th>商品名称</th>
									<th>设置价格</th>
									<th>设置库存</th>
									<th>操作类型</th>
									<th>操作时间</th>
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
	<script src="/public_source/www/My97DatePicker/WdatePicker.js"></script>  
	<script type="text/javascript">
		jQuery(function($) {
			var admin_path = $("#admin_path").val();
			var start_time = $("#start_time").val(); 
			var admin_name = $("#admin_name").val(); 
			var bStateSave = true;
			if(start_time || admin_name) {
				bStateSave = false;
			}
			var oTable1 = $('#sample-table-2').dataTable({ 
		        "oLanguage": {
		                "sUrl": "/public_source/www/assets/language/zh_CN.json"
		         },			     	
				"aaSorting": [[ 0, "desc" ]],
				"bAutoWidth": false, 
				"bStateSave": bStateSave,//加载记忆页码
				"bProcessing": true, //开启读取服务器数据时显示正在加载中……特别是大数据量的时候，开启此功能比较好
	            "bServerSide": true, //开启服务器模式，使用服务器端处理配置datatable。注意：sAjaxSource参数也必须被给予为了给datatable源代码来获取所需的数据对于每个画。 这个翻译有点别扭。开启此模式后，你对datatables的每个操作 每页显示多少条记录、下一页、上一页、排序（表头）、搜索，这些都会传给服务器相应的值。 
	            "sAjaxSource": admin_path+"/goodslog/ajax_goodslog_list?start_time="+start_time+"&admin_name="+admin_name, //给服务器发请求的url
	            "aoColumns": [ //这个属性下的设置会应用到所有列，按顺序没有是空
		       	    {"mData": 'id'},
			       	{"mData": 'admin_name'}, //mData 表示发请求时候本列的列明，返回的数据中相同下标名字的数据会填充到这一列
		            {"mData": 'admin_level'},
		            {"mData": 'admin_address'},
		            {"mData": 'admin_ip'},
		            {"mData": 'goods_id'},
		            {"mData": 'goods_name'},
		            {"mData": 'one_price'},
	                {"mData": 'stock_num'},
	                {"mData": 'action_type'},
	                {"mData": 'create_time'},
	            ],
	            "aoColumnDefs": [//和aoColums类似，但他可以给指定列附近爱属性
	               {"bSortable": false, "aTargets": []},//（从0开始算） 不能排序
	        	],
	            "fnCreatedRow": function(nRow, aData,iDataIndex) {
	                $('td:eq(7)',nRow).html("<span style='line-height:20px;' class='row-details row-details-close' data_id='" + aData.id + "'></span>&nbsp;<span>展开查看 </span>");
	            },
	         	"fnInitComplete": function(oSettings, json) { //回调函数
	           	} 
			});		
			$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
		    $('.table').on('click','tbody td .row-details',function() {
		    	var nTr = $(this).parents('tr')[0];
		       	if (oTable1.fnIsOpen(nTr)) //判断是否已打开
	            {
	                 /* This row is already open - close it */
	                 $(this).addClass("row-details-close").removeClass("row-details-open");
	                 oTable1.fnClose(nTr);
	             } else {
	                 /* Open this row */
	                 $(this).addClass("row-details-open").removeClass("row-details-close");
	                 //  alert($(this).attr("data_id"));
	                 //oTable.fnOpen( nTr,
	                 // 调用方法显示详细信息 data_id为自定义属性 存放配置ID
	                 fnFormatDetails(nTr, $(this).attr("data_id"));
	            }
		    });
		   function fnFormatDetails(nTr, pdataId) {
		       //根据配置Id 异步查询数据
		       $.get("/user_admin/goodslog/ajax_goodslog_info/"+ pdataId,function(json) {
			       	var json = eval("("+json+")");
					var sOut = json.str;
		             oTable1.fnOpen(nTr,sOut,'details');	          	
		      });	 
		   	}
			function tooltip_placement(context, source) {
				var $source = $(source);
				var $parent = $source.closest('table')
				var off1 = $parent.offset();
				var w1 = $parent.width();	
				var off2 = $source.offset();
				var w2 = $source.width();
				if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2)) return 'right';
				return 'left';
			}
		});
	</script>
<?php include_once('footer.php');?>