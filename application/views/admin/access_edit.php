<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li><a href="/user_admin/role/role_list">管理员管理</a></li>
				<li><a href="/user_admin/role/role_list">角色列表</a></li>
				<li>编辑权限</li>
			</ul>
		</div>
		<div class="page-content">	
			<div class="page-header">
				<h1 class="blue"><i class="icon-edit"></i> 编辑信息</h1>
			</div>	
			<div class="row">
				<div class="col-xs-12">									
					<form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="/user_admin/access/edit">
						<input type="hidden" id="role_id" name="role_id" value="<?=$role_id?>" />						
						<table style='border:1px solid #ddd;margin-bottom:20px;'id="nodes" width="100%">
							<?php foreach ($nodes as $k => $row) {
								$ck = "";
								foreach ($data as $key => $value) {
									if($row['node_id'] == $value['node_id']){ 
										$ck = "checked";
									}
								}
								echo '<div><div class="parent"><input class="control-input" type="checkbox" '.$ck.' name="node_id[]" value="'.$row['node_id'].'" /><b>'.$row['title'].'</b></div>';							
								if($row['child']){
									echo '<div class="child" style="padding:5px 30px;width:100%;height:35px;">';
										foreach ($row['child'] as $key => $value) {
											$ck = "";
											foreach ($data as $key => $val) {
												if($value['node_id'] == $val['node_id']){ 
													$ck = "checked";
											}
										}
											echo '<span style="padding-right:20px;float:left;"><input style="float:left;margin-top:8px;" type="checkbox" '.$ck.' name="node_id[]" value="'.$value['node_id'].'" /><span  style="float:left;padding-left:10px;line-height:30px;">'.$value['title'].'</span></span>';
									}
									echo '</div>';
								}
								echo '</div>';
							}?>						
						</table>						
					 	<div class="form-group">
					   		<div class="col-sm-10">
								<button type="submit" id="submit" class="btn btn-sm btn-primary">确定保存</button>
							</div>
					  	</div>														
					</form>	
				</div>
			</div>					
		</div>													
	</div>
	<link href="/public_source/www/assets/css/ace.min.css" rel="stylesheet" />	
	<link href="/public_source/www/assets/css/jquery.tag-editor.css" rel="stylesheet"/>
    <script src="/public_source/www/assets/js/bootbox.min.js"></script>  
	<script src="/public_source/www/assets/js/jquery.tag-editor.min.js"></script>
	<script type="text/javascript">
	   	$(function(){
	   		$(".control-input").click(function() {
	   			if($(this).attr("checked")=="checked") {
	   				$(this).parents(".parent").siblings(".child").find("input").attr("checked","checked");
	   			}
	   			if($(this).attr("checked")==undefined){
	   				$(this).parents(".parent").siblings(".child").find("input").attr("checked",false);
	   			}
	   		});
	   		$(".child").find("input").click(function() {
	   			if($(this).attr("checked")=="checked") {
	   				$(this).parents(".child").siblings(".parent").find("input").attr("checked","checked");
	   			}
	   			if($(this).attr("checked")==undefined) {
	   				var status = false;	
	   	   			var test = $(this).parents(".child").find("input");   		
	      	   		 for (var i = 0; i < test.length; i++) {
	      	   	   	    if (test[i].checked == true) {
	      	   	   	    	status = true;
	      	   	   	    	break;
	      	   	   	    }
	      	   	   	  }
	   	   	   		if(!status) {
	   	   	   	   		$(this).parents(".child").siblings(".parent").find("input").attr("checked",false);
	   	   	   	   	}
	   			}
	   		});
	   	});
	</script>	
	<style>
		#nodes{
			border: 1px solid #ccc;
		}
		.parent{
			font-weight: bold;
			padding:10px 0 10px 10px;
			background-image:url('/public_source/www/images/node_bg3.jpg');background-position:center;	
			height:40px;				
		}
		.parent b{
		  padding-left: 20px;
		  display: block;
		  margin-top: -21px;						
		}	
		.child{
			font-weight: normal;
			color: #666;
			padding:15px 20px 5px;	
		}
	</style>	
<?php include_once('footer.php');?>	