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
				<li>企业套餐</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">
		<div class="page-content" style="margin-top:18px;">							
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 添加企业套餐信息</h1>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="/user_admin/pay/package_add" onsubmit="return packge_add_check()"  >
						<div class="form-group">
							<label  class="col-sm-1 control-label">选择内推套餐</label>
							<div class="col-sm-2">
								<select class="width-100" id="innerpush_id" name="innerpush_id">
									<?php foreach($innerpush_list as $k => $v) {?>
										<option value="<?=$v['id'];?>"><?=$v['number'];?>(人)</option>
									<?php }?>
								</select>
							</div>
						</div>						
						<div class="form-group">
							<label  class="col-sm-1 control-label">选择邀请套餐</label>
							<div class="col-sm-2">
								<select class="width-100" id="interview_id" name="interview_id">
									<?php foreach($interview_list as $k => $v) {?>
										<option value="<?=$v['id'];?>"><?=$v['number'];?>(次)</option>
									<?php }?>
								</select>
							</div>
						</div>
                        <div class="form-group">
                            <label  class="col-sm-1 control-label">选择速职币套餐</label>
                            <div class="col-sm-2">
                                <select class="width-100" id="suzhicoin_id" name="suzhicoin_id">
                                    <?php foreach($suzhicoin_list as $k => $v) {?>
                                        <option value="<?=$v['id'];?>"><?=$v['number'];?>(速职币)</option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
						<div class="form-group">
							<label  class="col-sm-1 control-label">年限：</label>
							<div class="col-sm-2">
								<input type="text" readonly="readonly" class="width-100" id="year" name="year" value="1">
								
							</div>
						</div>
						<div class="form-group">
							<label  class="col-sm-1 control-label">所需金额：</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="money" name="money" value="">
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