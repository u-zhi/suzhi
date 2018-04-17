<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li>人力外包中心</li>
				<li>企业外包需求待反馈</li>
				<li>报价</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="/user_admin/enterprise/enterprise_outsourcing_edit">
		<div class="page-content" style="margin-top:18px;">							
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 企业外包需求待反馈详情(报价)</h1>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="/user_admin/enterprise/enterprise_outsourcing_edit"  >
						<input type="hidden" class="width-100" name="id"  value="<?=$data['id']?>" readonly="readonly">
						<div class="form-group">
							<label class="col-sm-1 control-label">职位名称</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['occupation_two_id']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">职位类别</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['occupation_id']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">工作地点</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['work_address']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">学历要求</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['education_type']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">工作年限要求</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['work_year']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">薪资范围</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['salary_range']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">职位描述</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['job_description']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">任职要求</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['skill_need']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">联系人</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['link_name']?>" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">联系方式</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['link_mobile']?>" readonly="readonly">
							</div>
						</div>						
						<div class="form-group">
							<label class="col-sm-1 control-label">招聘需求</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['stop_reason']?>" readonly="readonly">
							</div>
						</div>						
						<div class="form-group">
							<label class="col-sm-1 control-label">需求人数</label>
							<div class="col-sm-2">
								<input type="text" class="width-100"  value="<?=$data['person_demand']?>" readonly="readonly">
							</div>
						</div>						
						<div class="form-group">
							<label class="col-sm-1 control-label">报价</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" name="offer_money"  value="<?=$data['offer_money']?>" >
							</div>
						</div>
					 	<div class="form-group">
					   		<div class="col-sm-offset-1 col-sm-10">
								<button type="submit" id="submit" class="btn btn-sm btn-primary">确认保存</button>
								<a href="javascript:history.go(-1)" class="btn btn-sm btn-warning">返回上页</a>
							</div>
					  	</div>
					</form>	
				</div>
			</div>					
		</div>													
	</div>
	<script src="/public_source/www/js/common.js" type="text/javascript"></script>
	<script src="/public_source/www/js/form_check.js" type="text/javascript"></script>
<?php include_once('footer.php');?>		
