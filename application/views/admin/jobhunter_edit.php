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
				<li>用户管理</li>
				<li>求职者列表</li>
				<li>求职者信息</li>
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
					<a href="javascript:history.go(-1)" class="btn btn-sm btn-warning">返回上页</a>
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?=$admin_path;?>/jobhunter/edit"  onsubmit="return jobhunter_edit_check()">
						<input type="hidden" class="width-100" id="user_id" name="user_id" value="<?=$data['user_id'];?>">
						<div class="form-group">
							<label  class="col-sm-1 control-label">当前头像</label>
							<div class="col-sm-4">
								<img id="bigic" src="<?php echo $data['avatar_url'];?>" style="max-height:160px;max-width:835px;" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">姓名</label>
							<div class="col-sm-2">
								<input type="text" class="width-100" id="nickname" name="nickname" value="<?=$data['nickname']?>">
							</div>
						</div>
						<div class="visible-md visible-lg hidden-sm hidden-xs btn-group" style='margin: 20px 0 40px 20%;'>
							<div style="float:left;margin-right:30px;">
								<a href="<?=$admin_path;?>/jobhunter/edit_page/<?=$data['user_id'];?>" class='btn-pink2'><span  class="btn2 btn-xs bottom-ky ">用戶資料</span></a>
							</div>					
							<div style="float:left;margin-right:30px;">
								<a href="<?=$admin_path;?>/jobhunter/enroll_list/<?=$data['user_id'];?>" class='btn-pink3'><span class="btn2 btn-xs  bottom-ky ">投递职位</span></a>
							</div>						
						</div>
						<div class="page-header" style="height:100px;"></div>
<!-- 
						省略很多。。。

						<a href="">下载</a> -->


						<div class="page-header"></div>
					 	<div class="form-group">
					   		<div class="col-sm-offset-1 col-sm-10">
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
	<script src="/public_source/www/My97DatePicker/WdatePicker.js"></script>
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