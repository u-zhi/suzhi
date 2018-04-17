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
				<li><a href="<?=$this->go_url;?>">文章管理</a></li>
				<li><a href="<?=$this->go_url;?>">文章列表</a></li>
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
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?=$admin_path;?>/news/edit"  onsubmit="return news_add_check()">	           																										
						<input type="hidden" class="width-100" id="id" name="id" value="<?=$data['id'];?>">	
						<div class="form-group">
							<label class="col-sm-1 control-label">文章名称</label>
							<div class="col-sm-3">							
								<input type="text" class="width-100" id="title" name="title" value="<?=$data['title'];?>">								
							</div>					
						</div>		
						<div class="form-group has-error">
							<label for="title" class="col-sm-1 control-label">排序</label>
							<div class="col-sm-3">
								<span class="block input-icon input-icon-right">
									<input type="text" class="width-100" id="news_sort" name="news_sort" value="<?=$data['news_sort'];?>">
									<i class="icon-info-sign"></i>
								</span>
							</div>									
							<div class="help-block col-xs-12 col-sm-reset inline">
								数字越小越靠前（结合其他信息），只能为正整数
							</div>
						</div>														             
						<div class="form-group">
							<label for="content" class="col-sm-1 control-label">文章描述</label>
							<div class="col-sm-10">
								<textarea id="editor" name="content" type="text/plain" style="height:360px;"><?=$data['content'];?></textarea>
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
    <script src="/public_source/www/js/common.js" type="text/javascript"></script>    
    <script src="/public_source/www/js/form_check.js" type="text/javascript"></script>  
	<script src="/public_source/www/assets/ueditor/ueditor.config.js" type="text/javascript"></script>
    <script src="/public_source/www/assets/ueditor/ueditor.all.js" type="text/javascript"></script>  
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
	<script type="text/javascript">
		var editor = new baidu.editor.ui.Editor();
		editor.render('editor');  //editor为编辑器容器的id 
	</script>
<?php include_once('footer.php');?>