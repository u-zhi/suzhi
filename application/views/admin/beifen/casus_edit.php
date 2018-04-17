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
				<li><a href="<?=$this->go_url;?>">商城管理</a></li>
				<li><a href="<?=$this->go_url;?>">文案列表</a></li>
				<li>编辑信息</li>
			</ul>
		</div>	
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">
		<div class="page-content" style="margin-top:18px;">					
			<div class="alert alert-info">
				<b><i class="icon-comment-alt"></i> 说明：文案图册最多上传9张图！</b>
			</div>
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 编辑信息</h1>
			</div>							
			<div class="row">
				<div class="col-xs-12">
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?=$admin_path;?>/casus/edit"  onsubmit="return case_edit_check()">	           																										
						<input type="hidden" id="id" name="id" class="width-100" value="<?=$data['id'];?>">
						<div class="form-group">
							<label class="col-sm-1 control-label">商品名称</label>
							<div class="col-sm-3">							
								<div class="control"><b><?=$data['name'];?></b></div>								
							</div>					
						</div>
						<div class="form-group">
							<label class="col-sm-1 control-label">文案标题</label>
							<div class="col-sm-3">							
								<input type="text" class="width-100" id="title" name="title" value="<?=$data['title'];?>">								
							</div>					
						</div>		
						<div class="form-group has-error">
							<label for="title" class="col-sm-1 control-label">排序</label>
							<div class="col-sm-3">
								<span class="block input-icon input-icon-right">
									<input type="text" class="width-100" id="case_sort" name="case_sort" value="<?=$data['case_sort'];?>">
									<i class="icon-info-sign"></i>
								</span>
							</div>									
							<div class="help-block col-xs-12 col-sm-reset inline">
								数字越小越靠前，只能为正整数
							</div>
						</div>		            	
			            <?php if(isset($data['now_image'])) {?>	
						<div class="form-group">
			                <label  class="col-sm-1 control-label">商品图册</label>
			                <div class="parentFileBox">
			               		<ul class="fileBoxUl">
				                	<?php foreach($data['now_image'] as $key => $value) {?>
				                		<li class="diyUploadHover" id="remove_<?=$key?>">
				                			<div class="viewThumb">
				                				<img src="<?=$value;?>" />
				                			</div>
				                			<div class="diyCancel" onclick= cancle('<?=$value;?>','remove_<?=$key;?>')></div>
				                		</li>	 
				                	<?php }?> 			                
			                	</ul>              
			                </div>				          
			             </div>		
			             <?php }?>
						<div class="form-group">
							<label class="col-sm-1 control-label">文案图册</label>		
							<div id="my-awesome-dropzone" class="dropzone col-sm-7">								
							</div>							
						</div>
						<input type="hidden" id="z_image" name="roll_image" value="<?=$data['roll_image'];?>">
						<input type="hidden" id="delete_image" name="delete_image" value="">															
						<div class="form-group">
							<label class="col-sm-1 control-label">商品文案</label>
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
	<script type="text/javascript">
		var editor = new baidu.editor.ui.Editor();
		editor.render('editor');  //editor为编辑器容器的id 
		Dropzone.options.myAwesomeDropzone = {
		  // The configuration we've talked about above
			autoProcessQueue: true,
			url: '/user_admin/goods/fileupload',
			paramName: "jietu", // The name that will be used to transfer the file
			maxFilesize: 5, // MB
			addRemoveLinks : true,
			//acceptedFiles: 'image/*',
			uploadMultiple: false,
			dictDefaultMessage :
			'<span class="bigger-150 bolder"><i class="icon-caret-right red"></i> 拖拽</span> 上传 \
			<span class="smaller-80 grey">(或 点击)</span> <br /> \
			<i class="upload-icon icon-cloud-upload blue icon-3x"></i>',
			dictResponseError: '上传文件时发生错误!',
	
		  // The setting up of the dropzone
		  init: function() {
			  this.on("success", function(file,response) {
				 var z_image = $("#z_image").val();
				  $("#z_image").val(z_image+response+';');
				  
	          });
	          this.on("removedfile",function(file,response) {
	              var delete_image = file.xhr.response;
	              var link_data = {delete_image:delete_image};
	              $.ajax({
	    				url:"/user_admin/goods/unlink/",
	    				type:"post",
	    				dataType: 'json',
	    				data:link_data,
	    				success:function(data) {
	
	    				}	
	    		  });
	              delete_image = file.xhr.response+";";
	        	  var z_image = $("#z_image").val();
	              z_image = z_image.replace(delete_image,"");
	              $("#z_image").val(z_image);
	          });
			var myDropzone = this;
			// Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
			// of the sending event because uploadMultiple is set to true.
			this.on("sendingmultiple", function() {
			  // Gets triggered when the form is actually being sent.
			  // Hide the success button or the complete form.
			});
			this.on("successmultiple", function(files, response) {
			  // Gets triggered when the files have successfully been sent.
			  // Redirect user or notify of success.
			});
			this.on("errormultiple", function(files, response) {
			  // Gets triggered when there was an error sending the files.
			  // Maybe show form again, and notify user of error
			});
		  }
		}	
		//点击删除图片并将删除数据存在临时数据里
		function cancle(cancle_image,remove_class) {		
	          cancle_image = cancle_image+";";
	    	  var z_image = $("#z_image").val();
	          z_image = z_image.replace(cancle_image,"");
	          $("#z_image").val(z_image);
	          $("#"+remove_class+"").remove();
	          var delete_image = $("#delete_image").val();
			  $("#delete_image").val(delete_image+cancle_image);
		}
		//图片放大插件
		$('.parentFileBox img').bigic();
		$('#bigic').bigic();
	</script>
<?php include_once('footer.php');?>