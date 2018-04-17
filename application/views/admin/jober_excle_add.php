<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li>简历库管理</li>
				<li>添加导入简历</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="/user_admin/jobhunter_all/jobhunter_list">
		<div class="page-content" style="margin-top:18px;">							
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 速职人才库简历导入</h1>
			</div>
			<div class="row">
				<div class="col-xs-12">
				<a href="javascript:history.go(-1)" class="btn btn-sm btn-warning">返回上页</a>
					<div id="demo">
						<div id="as" ></div>
					</div>
					
				</div>
			</div>					
		</div>													
	</div>
<script type="text/javascript">
$('#as').diyUpload({
	url:'/user_admin/jobhunter_all/job_excle_add_row/<?php echo $this->data['position_id']; ?>',
	success:function( data ) {
		console.info( data );
	},
	error:function( err ) {
		console.info( err );	
	},
	buttonText : '选择文件',
	chunked:true,
	// 分片大小
	chunkSize:512 * 1024,
	//最大上传的文件数量, 总文件大小,单个文件大小(单位字节);
	fileNumLimit:50,
	fileSizeLimit:500000 * 1024,
	fileSingleSizeLimit:50000 * 1024,
	accept: {}
});
</script>
	<script src="/public_source/www/js/common.js" type="text/javascript"></script>
	<script src="/public_source/www/js/form_check.js" type="text/javascript"></script>
<?php include_once('footer.php');?>		