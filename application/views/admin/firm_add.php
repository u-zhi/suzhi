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
				<li><a href="<?=$this->go_url;?>">企业管理</a></li>
				<li><a href="<?=$this->go_url;?>">企业列表</a></li>
				<li>新增信息</li>
			</ul>
		</div>	
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">
		<div class="page-content" style="margin-top:18px;">					
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 添加信息</h1>
			</div>							
			<div class="row">
				<div class="col-xs-12">
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?=$admin_path;?>/firm/add"  onsubmit="return firm_add_check()">
						<div class="form-group">
							<label  class="col-sm-1 control-label">企业头像</label>
							<div class="col-sm-2">
								<input type="hidden" class="form-control" id="icon_url" name="icon_url" value="">
								<input id="file-0b" class="file" type="file" name="jietu">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">企业名称</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="name" name="name" value="">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">联系人</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="contact" name="contact" value="">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">手机号</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="phone_number" name="phone_number" value="">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">公司行业</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="type" name="type" value="">
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">融资情况</label>
							<div class="col-sm-2">
								<select class="width-100" id="financing" name="financing">
									<option value="0">未融资</option>
									<option value="1">天使轮</option>
									<option value="2">A轮</option>
									<option value="3">B轮</option>
									<option value="4">C轮</option>
									<option value="5">D轮及以上</option>
									<option value="6">上市公司</option>
									<option value="7">不需要融资</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">公司人数</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="scale" name="scale" value="">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">公司地址</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="address" name="address" value="">
							</div>
						</div>
						<div class="form-group">
							<label for="content" class="col-sm-1 control-label">公司简介</label>
							<div class="col-sm-10">
								<textarea id="editor" name="introduction" type="text/plain" style="width: 260px;height: 180px"></textarea>
							</div>
						</div>
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
<?php include_once('footer.php');?>