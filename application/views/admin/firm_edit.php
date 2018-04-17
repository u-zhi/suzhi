<?php include_once('header.php');?>
<link rel="stylesheet" href="/public_source/www/assets/css/dropzone.css" />
<link rel="stylesheet" href="/public_source/www/assets/css/ace.min.css" />
<link rel="stylesheet" type="text/css" href="/public_source/www/css/diyUpload.css">
<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="<?=$admin_path;?>/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li><a href="<?=$this->go_url;?>">用户管理</a></li>
				<li><a href="<?=$this->go_url;?>">企业中心</a></li>
				<li>企业资料</li>
			</ul>
		</div>	
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">
		<div class="page-content" style="margin-top:18px;">					
<!-- 			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 编辑信息</h1>
			</div>	 -->						
			<div class="row">
				<div class="col-xs-12">
					<a href="javascript:history.go(-1)" class="btn btn-sm btn-warning">返回上页</a>
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?=$admin_path;?>/firm/edit"  onsubmit="return firm_edit_check()">
						<input type="hidden" class="width-100" id="id" name="id" value="<?=$data['id'];?>">
						<div class="form-group">
							<label  class="col-sm-1 control-label">当前企业头像</label>
							<div class="col-sm-4">
								<img id="bigic" src="<?php echo $data['icon_url'];?>" style="max-height:160px;max-width:835px;" />
							</div>
						</div>
<!-- 						<div class="form-group">
							<label  class="col-sm-1 control-label">要更改的企业头像</label>
							<div class="col-sm-2">
								<input type="hidden" class="form-control" id="icon_url" name="icon_url" value="">
								<input id="file-0b" class="file" type="file" name="jietu">
							</div>
						</div> -->
						<div class="form-group">
							<label class="col-sm-1 control-label">企业名称</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="name" name="name" value="<?=$data['name']?>">
							</div>							
							<label class="col-sm-1 control-label">余额</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="name" name="name" value="<?=($data['money']/100)?>">
							</div>
							<label class="col-sm-1 control-label"></label>
							<a href="javascript:add_recharge();"><span class="btn btn-xs btn-pink">充值</span></a>

						</div>
						<div class="visible-md visible-lg hidden-sm hidden-xs btn-group" style='margin: 20px 0 40px 20%;'>

							<div style="float:left;margin-right:30px;">
								<a href="<?=$admin_path;?>/firm/edit_page/<?=$data['id']?>" class='btn-pink2'><span  class="btn2 btn-xs bottom-ky ">企业资料</span></a>
							</div>					
							<div style="float:left;margin-right:30px;">
								<a href="<?=$admin_path;?>/firm/hot_positions/<?=$data['id']?>" class='btn-pink3'><span class="btn2 btn-xs  bottom-ky ">发布的职位</span></a>
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
						<div class="page-header" style="height:100px;"></div>
						<div class="form-group">
							<label  class="col-sm-1 control-label">营业执照或者名片</label>
							<div class="col-sm-4">
								<img id="bigic" src="<?php echo $data['license_url'];?>" style="max-height:160px;max-width:835px;" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">企业名称</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="name" name="name" value="<?=$data['name']?>">
							</div>	
						</div>	
						<div class="form-group">
							<label class="col-sm-1 control-label">企业组织机构代码</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="organization_code" name="organization_code" value="<?=$data['organization_code']?>">
							</div>
						</div>						
						<div class="form-group">
							<label class="col-sm-1 control-label">联系人</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="contact" name="contact" value="<?=$data['contact']?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">手机号</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="phone_number" name="phone_number" value="<?=$data['phone_number']?>">
							</div>
						</div>						
						<div class="form-group">
							<label class="col-sm-1 control-label">固定电话</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="telephone" name="telephone" value="<?=$data['telephone']?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">公司行业</label>
							<div class="col-sm-2">
							<!-- 这个还没做 -->
								<input type="text" class="width-100" id="type_classify" name="type_classify" value="<?=$data['type_classify']?>">
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">融资情况</label>
							<div class="col-sm-2">
								<select class="width-100" id="financing" name="financing">
									<option value="0" <?php if($data['financing'] == 0){echo "selected='selected'";}?>>未融资</option>
									<option value="1" <?php if($data['financing'] == 1){echo "selected='selected'";}?>>天使轮</option>
									<option value="2" <?php if($data['financing'] == 2){echo "selected='selected'";}?>>A轮</option>
									<option value="3" <?php if($data['financing'] == 3){echo "selected='selected'";}?>>B轮</option>
									<option value="4" <?php if($data['financing'] == 4){echo "selected='selected'";}?>>C轮</option>
									<option value="5" <?php if($data['financing'] == 5){echo "selected='selected'";}?>>D轮及以上</option>
									<option value="6" <?php if($data['financing'] == 6){echo "selected='selected'";}?>>上市公司</option>
									<option value="7" <?php if($data['financing'] == 7){echo "selected='selected'";}?>>不需要融资</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">公司人数</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="scale_type" name="scale_type" value="<?=$data['scale_type']?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">公司地址</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="address" name="address" value="<?=$data['address']?>">
							</div>
						</div>						
						<div class="form-group">
							<label class="col-sm-1 control-label">企业账号</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="user_name" name="user_name" value="<?=$data['user_name']?>">
							</div>
						</div>						
						<div class="form-group">
							<label class="col-sm-1 control-label">验证手机号</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="phone_number" name="phone_number" value="<?=$data['phone_number']?>">
							</div>
						</div>
<!-- 						<div class="form-group">
							<label for="content" class="col-sm-1 control-label">公司简介</label>
							<div class="col-sm-10">
								<textarea id="editor" name="introduction" type="text/plain" style="width: 260px;height: 180px"><?=$data['introduction']?></textarea>
							</div>
						</div> -->
						<div class="page-header"></div>
					 	<div class="form-group">
					   		<div class="col-sm-offset-1 col-sm-10">
								<!-- <button type="submit" id="submit" class="btn btn-sm btn-primary">确定保存</button>									 -->
								<a href="javascript:history.go(-1)" class="btn btn-sm btn-warning">返回上页</a>
							</div>
					  	</div>
					</form>	
				</div>
			</div>					
		</div>															
	</div>
	<link  rel="stylesheet" href="/public_source/www/css/insure.css" />
	<link href="/public_source/www/assets/css/fileinput.css" rel="stylesheet" type="text/css" />
    <script src="/public_source/www/assets/js/fileinput.js" type="text/javascript"></script> 
    <script src="/public_source/www/js/common.js" type="text/javascript"></script>    
    <script src="/public_source/www/js/form_check.js" type="text/javascript"></script>  
	<script src="/public_source/www/assets/ueditor/ueditor.config.js" type="text/javascript"></script>
    <script src="/public_source/www/assets/ueditor/ueditor.all.js" type="text/javascript"></script>  
    <script src="/public_source/www/assets/js/dropzone.min.js"></script>
	<link href="/public_source/www/css/style.css" rel="stylesheet" type="text/css"/>
	<script src="/public_source/www/js/jquey-bigic.js" type="text/javascript"></script>
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
		*{ margin:0; padding:0;}
		#box{margin:50px auto; width:540px; min-height:400px; background:#FF9}
		.fileBoxUl { margin: 0 0 0 0;}
		.diyCancel {
			background:url(/public_source/www/images/x_alt.png) no-repeat;
		}
	</style>
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