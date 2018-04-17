<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li><a href="<?=$this->go_url;?>">数据统计</a></li>
				<li><a href="<?=$this->go_url;?>">渠道销售统计</a></li>
				<li>查看信息</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">
		<div class="page-content" style="margin-top:18px;">									
			<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
				<div style="width:100%;">
					<div style="float:left;margin-right:8px;">		
						<?php if($authority['export_status'] == 2) {?>
						<a href="export_excel/<?=$start_time;?>"><span class="btn btn-xs btn-primary">导出表格</span></a>
						<?php }?>
					</div>
					<div style="float:right;">		
						<form action="<?=$this->go_url;?>" method="post">
							<div style="float:left;margin-right:10px;">
								<input style="cursor:pointer;" type="text" placeholder="请选择时间" class="Wdate" id="start_time" name="start_time" value="<?=$start_time;?>" id="d412" onfocus="WdatePicker({dateFmt:'yyyy-MM'})" />								 	
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
				<h4 class="blue">   统计信息</h4>
				<div class="col-xs-12">			
					<form class="form-horizontal" enctype="multipart/form-data">	
						<div class="form-group">
							<label class="col-sm-1 control-label"></label>
							<table class="price-td" style='border:1px solid #ddd;margin-bottom:20px;' width='80%'>		 		
						 		<tr>
						 			<td colspan="10"><b>渠道销售统计</b></td>
						 		</tr>
						 		<tr>
						 			<td></td>
						 			<td colspan="4"><b>累积</b></td>
						 			<td colspan="4"><b>当月（<?=$start_time;?>）</b></td>
						 		</tr>
						 		 <tr>
						 			<td></td>
						 			<td><b>采购额</b></td>
						 			<td><b>发货额</b></td>
						 			<td><b>提货额</b></td>
						 			<td><b>合计销售额</b></td>
						 			<td><b>当月采购额</b></td>
						 			<td><b>当月发货额</b></td>
						 			<td><b>当月提货额</b></td>
						 			<td><b>当月合计销售额</b></td>
						 		</tr>		
								<?php
									$purchase_sum = 0;
									$clear_sum = 0;
									$pickup_sum = 0;
									$purchase_month_sum = 0;
									$clear_month_sum = 0;
									$pickup_month_sum = 0;
									foreach($purchase_list as $key => $value) {
										$purchase_sum += $value['total_price'];
										$clear_sum += $clear_list[$key]['total_price'];
										$pickup_sum += $pickup_list[$key]['total_price'];
										$purchase_month_sum += $purchase_month_list[$key]['total_price'];
										$clear_month_sum += $clear_month_list[$key]['total_price'];
										$pickup_month_sum += $pickup_month_list[$key]['total_price'];
								?>
								<tr>
									<td><?=$agent_list[$value['user_level']]?></td>
									<td><b><?=$value['total_price']?></b></td>
									<td><b><?=$clear_list[$key]['total_price']?></b></td>
									<td><b><?=$pickup_list[$key]['total_price']?></b></td>
									<td><b><?=$clear_list[$key]['total_price']+$pickup_list[$key]['total_price']?></b></td>
									<td><b><?=$purchase_month_list[$key]['total_price']?></b></td>
									<td><b><?=$clear_month_list[$key]['total_price']?></b></td>
									<td><b><?=$pickup_month_list[$key]['total_price']?></b></td>
									<td><b><?=$clear_month_list[$key]['total_price']+$pickup_month_list[$key]['total_price']?></b></td>	
								</tr>		
								<?php }?>
								<tr>
									<td>五级代理</td>
									<td colspan="9">无采购及发货，不统计</td>
								</tr>	
								<tr>
									<td>总计</td>
									<td><b><?=$purchase_sum?></b></td>
									<td><b><?=$clear_sum?></b></td>
									<td><b><?=$pickup_sum?></b></td>
									<td><b><?=$clear_sum+$pickup_sum?></b></td>
									<td><b><?=$purchase_month_sum?></b></td>
									<td><b><?=$clear_month_sum?></b></td>
									<td><b><?=$pickup_month_sum?></b></td>
									<td><b><?=$clear_month_sum+$pickup_month_sum?></b></td>				
								</tr>
							</table>
						</div>		
						<div class="page-header"></div>
					</form>	
				</div>
			</div>					
		</div>													
	</div>
    <script src="/public_source/www/js/common.js" type="text/javascript"></script>
	<script src="/public_source/www/My97DatePicker/WdatePicker.js"></script>  
	<style>
		.price-td td {
			height:40px;
			border:1px solid #999;
			text-align: center;
		}
	</style>
<?php include_once('footer.php');?>		