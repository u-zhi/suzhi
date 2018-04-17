<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li><a href="<?=$this->go_url;?>">订单管理</a></li>
				<li><a href="<?=$this->go_url;?>">渠道采购单列表</a></li>
				<li>编辑信息</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">
		<div class="page-content" style="margin-top:18px;">							
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 编辑信息</h1>
			</div>	
			<div class="row">
				<div class="col-xs-12">			
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?=$admin_path?>/channel/edit">
						<input type="hidden" name="id" id="id" value="<?=$data['id'];?>">
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">采购人名字</label>
							<div class="col-sm-2">
								<div class="control"><?=$data['user_name'];?></div>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">采购人等级</label>
							<div class="col-sm-2">
								<div class="control"><?=$data['agent_level'];?></div>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">采购人手机号</label>
							<div class="col-sm-2">
								<div class="control"><?=$data['phone'];?></div>
							</div>	
						</div>	
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">上级名字</label>
							<div class="col-sm-2">
								<div class="control"><?=$data['parent_name'];?></div>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">上级等级</label>
							<div class="col-sm-2">
								<div class="control"><?=$data['temp_level'];?></div>
							</div>
						</div>	
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">发货人手机号</label>
							<div class="col-sm-2">
								<div class="control"><?=$data['parent_phone'];?></div>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">订单号</label>
							<div class="col-sm-2">
								<div class="control"><?=$data['order_id'];?></div>
							</div>
						</div>	
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">采购时间</label>
							<div class="col-sm-2">
								<div class="control"><?=date("Y-m-d H:i:s",$data['parent_phone']);?></div>
							</div>
						</div>	
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">订单状态</label>
							<div class="col-sm-2">
								<select class="width-100" id="order_status" name="order_status">
									<option value="2">待审核</option>
									<option value="3">已完成</option>
									<option value="4">已取消</option>
								</select>
							</div>
						</div>
						<div class="page-header">
							<h4 class="blue">   商品信息</h4>
						</div>	
						<div class="form-group">
							<label class="col-sm-1 control-label"></label>
							<table class="col-sm-5 price-td" style='border:1px solid #ddd;margin-bottom:20px;' width='50%'>
						 		<tr><td><b>商品id</b></td><td><b>商品名称</b></td><td><b>商品单价</b></td><td><b>采购数量</b></td><td><b>小计</b></td></tr> 			
								<?php
									$num = 0; 
									$total_price = 0;
									foreach($goods_list as $key => $value) {
										$num += $value['num'];
										$total_price += $value['num']*$value['now_price'];
									?>
								<tr><td><?=$value['goods_id'];?></td><td><b><?=$value['goods_name'];?></b></td><td><b><?=$value['now_price'];?></b></td><td><b><?=$value['num'];?></b></td><td><b><?=$value['num']*$value['now_price'];?></b></td></tr>				
						 		<?php }?>
						 	</table>					 	
						</div>			
							<b style="padding-left:37%;">总计：<?=$num;?>件商品 &nbsp;&nbsp;合计￥<?=$total_price;?></b>
						
						<div class="page-header"></div>
					 	<div class="form-group">
					   		<div class="col-sm-offset-1 col-sm-10">
					   			<button type="submit" id="submit" class="btn btn-sm btn-primary">确定保存</button>																
								<a href="javascript:history.go(-1)" class="btn btn-sm btn-warning">返回上页</a>
							</div>	
					  	</div>
					</form>	
				</div>
			</div>					
		</div>													
	</div>
    <script src="/public_source/www/js/common.js" type="text/javascript"></script>
	<style>
		.control {
		    padding: 4px 0px;
		    color: #858585;
		    font-size: 14px;
		}
		.price-td td {
			height:40px;
			border:1px solid #999;
			text-align: center;
		}
	</style>
<?php include_once('footer.php');?>		