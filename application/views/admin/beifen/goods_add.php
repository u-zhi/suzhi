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
				<li><a href="<?=$this->go_url;?>">商品列表</a></li>
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
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="<?=$admin_path;?>/goods/add"  onsubmit="return goods_add_check()">	           																										
						<div class="form-group">
							<label class="col-sm-1 control-label">商品名</label>
							<div class="col-sm-3">							
								<input type="text" class="width-100" id="name" name="name" value="">								
							</div>					
						</div>
						<div class="form-group">
							<label for="title" class="col-sm-1 control-label">商品分类</label>	
							<div class="col-sm-2">
								<select class="width-100" id="classify_id" name="classify_id">
								<?php foreach($classify_list as $k => $v) {?>
									<option value="<?=$v['id'];?>"><?=$v['name'];?></option>											
								<?php }?>
								</select>
							</div>
						</div>	
						<div class="form-group">
			                <label  class="col-sm-1 control-label">封面图</label>
			                <div class="col-sm-3">
			                	<input type="hidden" class="form-control" id="good_logo" name="good_logo" value="">
			                    <input id="file-0b" class="file" type="file" name="jietu">                    
			                </div>
			            </div>					
						<div class="form-group">
							<label class="col-sm-1 control-label">商品图册</label>		
							<div id="my-awesome-dropzone" class="dropzone col-sm-7">								
							</div>
						</div>	
						<input type="hidden" id="z_image" name="roll_image" value="">				 	
					 	<div class="form-group">
						 	<label class="col-sm-1 control-label">各级价格</label>
						 	<table class="col-sm-5 price-td" style='border:1px solid #ddd;margin-bottom:20px;' width='50%'>
						 		<tr><td><b>级别</b></td><td><b>价格</b></td></tr>
								<tr><td>一级代理</td><td><input type="text" class="width-80" id="one_price" name="one_price" value=""><b>&nbsp;&nbsp;元</b></td></tr>
								<tr><td>二级代理</td><td><input type="text" class="width-80" id="two_price" name="two_price" value=""><b>&nbsp;&nbsp;元</b></td></tr>	
								<tr><td>三级代理</td><td><input type="text" class="width-80" id="three_price" name="three_price" value=""><b>&nbsp;&nbsp;元</b></td></tr>
								<tr><td>四级代理</td><td><input type="text" class="width-80" id="four_price" name="four_price" value=""><b>&nbsp;&nbsp;元</b></td></tr>
								<tr><td>五级代理</td><td><input type="text" class="width-80" id="five_price" name="five_price" value=""><b>&nbsp;&nbsp;元</b></td></tr>						
						 	</table>
					 	</div> 	
						<div class="form-group">
							<label class="col-sm-1 control-label">库存</label>
							<div class="col-sm-3">							
								<input type="text" class="width-100" id="stock_num" name="stock_num" value="9999">								
							</div>					
						</div>						 	
						<div class="form-group has-error">
							<label class="col-sm-1 control-label">采购基数</label>
							<div class="col-sm-3">							
								<span class="block input-icon input-icon-right">
									<input type="text" class="width-100" id="purchase_num" name="purchase_num" value="1">								
									<i class="icon-info-sign"></i>
								</span>
							</div>																
							<div class="help-block col-xs-12 col-sm-reset inline">
								采购基数是代理购买的基数，采购数量只能是该基数的整数倍数
							</div>				
						</div>					 	
						<div class="form-group has-error">
							<label class="col-sm-1 control-label">提货基数</label>
							<div class="col-sm-3">														
								<span class="block input-icon input-icon-right">
									<input type="text" class="width-100" id="pick_num" name="pick_num" value="1">								
									<i class="icon-info-sign"></i>
								</span>
							</div>																
							<div class="help-block col-xs-12 col-sm-reset inline">
								提货基数是代理提货的基数，提货数量只能是该基数的整数倍数
							</div>					
						</div>						 	
						<div class="form-group has-error">
							<label class="col-sm-1 control-label">每件运费</label>
							<div class="col-sm-3">														
								<span class="block input-icon input-icon-right">
									<input type="text" class="width-100" id="dc_price" name="dc_price" value="">								
									<i class="icon-info-sign"></i>
								</span>
							</div>																
							<div class="help-block col-xs-12 col-sm-reset inline">
								每件运费为计费计算的基础数据。运费计算为按件累计
							</div>					
						</div>			
						<div class="form-group has-error">
							<label for="title" class="col-sm-1 control-label">排序</label>
							<div class="col-sm-3">
								<span class="block input-icon input-icon-right">
									<input type="text" class="width-100" id="goods_sort" name="goods_sort" value="99">
									<i class="icon-info-sign"></i>
								</span>
							</div>									
							<div class="help-block col-xs-12 col-sm-reset inline">
								数字越小越靠前（结合其他信息），只能为正整数
							</div>
						</div>		
						<div class="form-group">
							<label class="col-sm-1 control-label">状态</label>
							<div class="col-sm-3">														
								<label style="margin-right:30px;"><input class="ace" type="radio" name="is_show" value="2" checked="checked" /><span class="lbl"> 上架</span></label>
								<label><input class="ace" type="radio" name="is_show" value="1" /><span class="lbl"> 下架</span></label>							
							</div>					
						</div>													             
						<div class="form-group">
							<label for="content" class="col-sm-1 control-label">商品描述</label>
							<div class="col-sm-10">
								<textarea id="editor" name="intro" type="text/plain" style="height:360px;"></textarea>
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
		//图片放大插件
		$('.parentFileBox img').bigic();
		$('#bigic').bigic();
	</script>
<?php include_once('footer.php');?>