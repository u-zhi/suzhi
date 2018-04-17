<?php include_once('header.php');?>
	<div class="main-content">
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
			</script>
			<ul class="breadcrumb">
				<li><a href="/user_admin/welcome"><i class="icon-home home-icon"></i>首页</a></li>
				<li>人力外包中心</li>
				<li>简历库管理</li>
				<li>查看信息</li>
			</ul>
		</div>
		<input type="hidden" name="message" id="message" value="<?=$message?>">
		<input type="hidden" name="admin_path" id="admin_path" value="<?=$admin_path?>">
		<input type="hidden" name="path" id="path" value="<?=$this->go_url;?>">
		<div class="page-content" style="margin-top:18px;">									
			<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
				<div style="width:100%;">
					<div style="float:left;margin-right:8px;">
					<a href="javascript:history.go(-1)" class="btn btn-sm btn-warning">返回上页</a>
						<?php if($authority['export_status'] == 2) {?>
						<a href="/user_admin/jobhunter/export_excel/<?=$user_id?>"><span class="btn btn-xs btn-primary">导出表格</span></a>
						<?php }?>
					</div>
				</div>
				<div class="page-header" style="height:40px;"></div>
			</div>
			<div class="row">
				<h4 class="blue">   简历信息</h4>
				<div class="col-xs-12">			
					<form class="form-horizontal" enctype="multipart/form-data">	
						<div class="form-group">
							<label class="col-sm-1 control-label"></label>
							<table class="price-td" style='border:1px solid #ddd;margin-bottom:20px;' width='60%'>
						 		<tr>
						 			<td colspan="3"><b>简历信息</b></td>
						 		</tr>
						 		<tr>
									<td>姓名：<?=$user_info['nickname']?></td>
									<td>联系电话：<?=$user_info['phone_number']?></td>
									<td rowspan="3" colspan="1"><?=$user_info['avatar_url']?></td>
						 		</tr>
								<tr >
									<td rowspan="2" colspan="2" style="text-align: left">跟进：</td>
								</tr>
								<tr >
								</tr>
								<tr>
						 			<td colspan="3"><b>基本信息</b></td>
						 		</tr>
								<tr>
									<td >所在城市：<?=$user_info['city_name']?></td>
									<td >性别：<?=$user_info['gender']?></td>
									<td >生日：<?=$user_info['birthday']?></td>
								</tr>
								<tr>
									<td >最高学历：<?=$user_info['highest_degree_name']?></td>
									<td >工作年限：<?=$user_info['work_year']?></td>
									<td >联系邮箱：<?=$user_info['work_email']?></td>
								</tr>
								<tr>
									<td colspan="3"><b>工作经历</b></td>
								</tr>
								<?php foreach ($work_experience as $key =>$value){?>
								<tr>
									<td colspan="1"><?=$value['start_time']?>--<?=$value['end_time']?></td>
									<td colspan="2"><?=$value['firm_name']?>\<?=$value['occupation']?></td>
								</tr>
								<tr>
									<td colspan="3" style="text-align: left"><?=$value['content']?></td>
								</tr>
								<?php }?>
								<tr>
									<td colspan="3"><b>教育经历</b></td>
								</tr>
								<?php foreach ($edu_background as $key =>$value){?>
								<tr>
									<td ><?=$value['start_time']?>--<?=$value['end_time']?></td>
									<td ><?=$value['school_name']?></td>
									<td ><?=$value['degree']?>.<?=$value['major']?></td>
								</tr>
								<?php }?>
								<tr>
									<td colspan="3"><b>期望工作</b></td>
								</tr>
								<tr>
									<td colspan="1"><?=$job_intention[0]['occupation']?></td>
									<td colspan="2"><?=$job_intention[0]['job_type']?>\<?=$job_intention[0]['city']?>\<?=$job_intention[0]['wage_lower']?>--<?=$job_intention[0]['wage_upper']?></td>
								</tr>
								<tr>
									<td colspan="3"><b>项目经历</b></td>
								</tr>
								<?php foreach ($project_experience as $key =>$value){?>
								<tr>
									<td colspan="1"><?=$value['start_time']?>--<?=$value['end_time']?></td>
									<td colspan="2"><?=$value['project_name']?>\<?=$value['responsibility']?></td>
								</tr>
								<tr>
									<td colspan="3" style="text-align: left"><?=$value['description']?><?=$value['project_url']?></td>
								</tr>
								<?php }?>
								<tr>
									<td colspan="3"><b>技能标签</b></td>
								</tr>
								<tr>
									<td colspan="3" style="text-align: left"><?php foreach ($skill_tag as $key =>$value){?><?php echo $value['tag']; echo "&nbsp";?><?php }?></td>
								</tr>
								<tr>
									<td colspan="3"><b>自我描述</b></td>
								</tr>
								<tr>
									<td colspan="3" style="text-align: left"><?=$self_intro[0]['content']?></td>
								</tr>
							</table>
						</div>		
						<div class="page-header"></div>
					</form>	
				</div>
			</div>					
		</div>													
	</div>
    <script src="/public_source/www/js/common.js" type="text/javascript"></script>
	<script src="/public_source/www/My97DatePicker/WdatePicker.js"></script>  
	<style>
		.price-td td {
			height:40px;
			border:1px solid #999;
			text-align: center;
		}
	</style>
<?php include_once('footer.php');?>		