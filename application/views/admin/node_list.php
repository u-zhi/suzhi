<?php include_once('header.php');?>
<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li>管理员管理</li>
				<li>节点列表</li>
			</ul>
		</div>			
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$admin_path?>/node/node_list<?php if($message == 4) echo '#btn-scroll-up';?>">		
		<div class="page-content" style="margin-top:8px;">								
			<div class="alert alert-block alert-success">
				<i class="icon-comment-alt"></i> 说明：敏感操作，如不熟悉请勿随意操作。
			</div>		
			<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
				<div style="float:left;margin-right:8px;">
					<?php if($authority['add_status'] == 2) {?>
					<a href="add_page"><span class="btn btn-xs btn-pink">添加节点</span></a>
					<?php }?>	
				</div>
				<div class="page-header" style="height:40px;"></div>
			</div>	
			<div class="row">
				<div class="col-xs-12">
					<div class="table-header">
						节点列表
					</div>
					<div>
						<table style='border:1px solid #ddd;margin-bottom:20px;margin-top:-1px;' width="100%">
						 <?php
							 foreach($data as $key => $value) {
							 	$add_node = '';
							 	$edit_node = '';
							 	$delete_node = '';
							 	if($authority['add_status'] == 2) {
							 		$add_node = '<a href="/user_admin/node/add_page/'.$value['node_id'].'"><span class="btn btn-xs btn-pink">添加子节点</span></a>';						 			
							 	}
							 	if($authority['edit_status'] == 2) {
							 		$edit_node = '<a href="/user_admin/node/edit_page/'.$value['node_id'].'"><span class="btn btn-xs btn-success">编辑节点</span></a>';						 			
							 	}
							 	if($authority['del_status'] == 2) {
							 		$delete_node = '<a onclick="deleteOne('.$value['node_id'].',\'/node/delete\')"  data_id = '.$value['node_id'].'><span class="btn btn-xs btn-warning">删除节点</span></a>';						 			
							 	}							 	
							 	echo '<tr id="parent">
							 			<td style="border:1px solid #ddd;">
											<b style="padding-left:10px;">'.$value['title'].'</b><b style="padding-left:10px;">'.$value['level_name'].'</b>
										</td>
										<td style="border:1px solid #ddd;" colspan="5">
											'.$add_node.''.$edit_node.''.$delete_node.'
										</td>
									</tr>';
								foreach ($value['child'] as $k => $v) {
									$node_edit = '';
									$node_delete = '';
									if($authority['edit_status'] == 2) {
										$node_edit = '<a href="/user_admin/node/edit_page/'.$v['node_id'].'"><i class="icon-edit"></i>&nbsp;编辑</a>';
									}
									if($authority['del_status'] == 2) {
										$node_delete = '<a onclick="deleteOne('.$v['node_id'].',\'/node/delete\')"  data_id = '.$v['node_id'].'><i class="icon-trash"></i>&nbsp;删除</a>';
									}
									if(($k+3)%3 == 0) {
										echo "<tr id='child'>";
									}
									if($k +1 < count($value['child'])) {								
										echo '<td style="border:1px solid #ddd;">
												&nbsp;&nbsp;&nbsp;&nbsp;'.$v['title'].'<b style="padding-left:10px;">'.$v['level_name'].'</b>
											</td>
											<td style="border:1px solid #ddd;height:40px;">
							 					'.$node_edit.''.$node_delete.'
											</td>';
										if(($k+1)%3 == 0) {
											echo "</tr>";
										}
									}else {
										$num = (3-($k+4)%3)*2 + 1;
										echo '<td style="border:1px solid #ddd;">
												&nbsp;&nbsp;&nbsp;&nbsp;'.$v['title'].'<b style="padding-left:10px;">'.$v['level_name'].'</b>
											</td>
											<td style="border:1px solid #ddd;height:40px;" colspan="'.$num.'">
												'.$node_edit.''.$node_delete.'
											</td></tr>';										
									}									
								}
							 }
						?>
						</table>		
					</div>				
				</div><!-- /span -->
			</div>
		</div>														
	</div>
	<script src="/public_source/www/assets/js/jquery.dataTables.min.js"></script>
	<script src="/public_source/www/assets/js/jquery.dataTables.bootstrap.js"></script>
	<script src="/public_source/www/js/common.js"></script>
<?php include_once('footer.php');?>
<style>
	#parent{
		font-weight: bold;
		padding:10px 0 10px 10px;
		background-image:url('/public_source/www/images/98.gif');background-position:center;
		height:40px;
	}
	#parent a{
		color: #307ecc;
		cursor: pointer;
		padding: 0 10px;
	}
	#child{
		font-weight: normal;
		color: #666;
		padding:15px 20px 5px;
	}
	#child a{
		cursor: pointer;
		padding: 0 10px;
	}
</style>