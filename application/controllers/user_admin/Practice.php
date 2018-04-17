<?php
//实习任务
class Practice extends PC_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('task_profile_model');
		$this->load->model('firm_profile_model');
		$this->load->model('base_county_model');
		$this->load->model('base_occupation_model');
		$this->go_url = $this->data['admin_path']."/practice/practice_list";
		$this->data['authority'] = $this->authority;
	}

	//全职任务列表
	public function practice_list() {
		if($this->uri->segment(4)) {
			$this->data['message'] = $this->uri->segment(4);
		}
		$this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
		$this->display('practice_list');
	}
	//ajax获取管理员数据加载到列表
	public function ajax_practice_list() {
		$base_url=$this->config->item('oss_path');
		$data = $_GET;
		/**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
		$start = $data['iDisplayStart'];
		$length = $data['iDisplayLength'];
		$sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
		$sort_type = $data['sSortDir_0'];
		$search = $data ['sSearch'];
		$where['concat(id) like'] = '%'.trim($search).'%';
		$where['task_type'] = 2;
		$where['is_deleted'] = 0;
		if(isset($data['search_field']) && $data['search_field']){
			$where['name like'] = '%'.trim($data['search_field']).'%';
		}
		$this->data['count'] = $this->task_profile_model->getCount($where);
		$aaData = array();
		if($this->data['count']) {
			$order_by = $sort_th." ".$sort_type;
			$admin_list = $this->task_profile_model->getTaskList($where,$length,$start,$order_by);
			foreach($admin_list as $key => &$value) {
				$a1=$this->firm_profile_model->checkFirm(array('id'=>$value['firm_id']),'name');
				$value['firm_name']=$a1['name'];
				$a2=$this->base_county_model->checkBase(array('id'=>$value['county_id']),'county_name');
				$value['county_name']=$a2['county_name'];
				$a3=$this->base_occupation_model->checkBase(array('id'=>$value['occupation_id']),'item');
				$value['occupation_name']=$a3['item'];
				$value['image_url']=$this->default_img($base_url.$value['image_url']);
				if($value['is_off_shelved'] == 0){
					$value['is_off_shelved'] = '上架';
					$up_url = $this->delete_url('/practice/fail_up',$value['id'],'下架','btn-light');
				}else{
					$value['is_off_shelved'] = '下架';
					$up_url = $this->delete_url('/practice/agree_up',$value['id'],'上架','btn-success');
				}
				$edit_url = $this->edit_url('practice','edit_page',$value['id']);
				$del_url = $this->delete_url('/practice/delete',$value['id'],'删除','btn-purple');
				$value['check'] = $this->get_check($value['id']);
				$value['operate'] = $edit_url.$del_url.$up_url;
				if(!$value['operate']) {
					$value['operate'] = '无操作';
				}
			}
			$aaData = $admin_list;
		}
		$output['aaData'] = $aaData;
		$output['sEcho'] = $_GET['sEcho'];
		$output['iTotalDisplayRecords'] =  $this->data['count'];
		$output['iTotalRecords'] = $this->data['count'];
		echo json_encode($output);
	}
	/**
	 * @brief	下架商品
	 */
	public function fail_up() {
		$data = $_POST;
		$where['id'] = $data['id'];
		$edit_data['is_off_shelved'] = 1;
		$edit_result = $this->task_profile_model->editTask($where,$edit_data);
		echo json_encode($edit_result);
	}

	/**
	 * @brief	上架
	 */
	public function agree_up() {
		$data = $_POST;
		$where['id'] = $data['id'];
		$edit_data['is_off_shelved'] = 0;
		$edit_result = $this->task_profile_model->editTask($where,$edit_data);
		echo json_encode($edit_result);
	}
	//删除
	public function delete() {
		$data = $_POST;
		$where['id'] = $data['id'];
		$data['is_deleted'] = 1; //删除
		$data['delete_time']=$this->time_retuen();
		$del_result = $this->task_profile_model->editTask($where,$data);
		echo json_encode($del_result);
	}
	//编辑展示
	public function edit_page() {
		$id = $this->uri->segment(4);
		$where['id'] = $id;
		$base_url=$this->config->item('oss_path');
		//获取任务详情
		$info = $this->task_profile_model->checkTask($where);
		$info['image_url']=$base_url.$info['image_url'];
		//获取公司信息
		$a1=$this->firm_profile_model->getFirmAll();
		//获取区域信息
		$a2=$this->base_county_model->getBaseAll();
		//获取工作类型(职位)
		$a3=$this->base_occupation_model->getBaseAll();
		$this->assign('data', $info);
		$this->assign('firm', $a1);
		$this->assign('county', $a2);
		$this->assign('occupation', $a3);
		$this->display('practice_edit');
	}
	//保存
	public function edit()
	{
		$data = $_POST;
		$where['id'] = $data['id'];
		/**上传图片**/
		if($_FILES['jietu']['tmp_name']) {
			$upload_image = $this->upload($_FILES,false,WEB_URL);
			$data['image_url'] = $upload_image;
		}else{
			$res=$this->task_profile_model->checkTask($where,'image_url');
			$data['image_url']=$res['image_url'];
		}
		$data['update_time']=$this->time_retuen();
		$info=$this->task_profile_model->editTask($where,$data);
		if($info){
			$this->location_href($this->go_url."/2");
		}else{
			$this->location_href($this->go_url."/3");
		}
	}
	//添加页面展示
	public function add_page() {
		//获取公司信息
		$a1=$this->firm_profile_model->getFirmAll();
		//获取区域信息
		$a2=$this->base_county_model->getBaseAll();
		//获取工作类型(职位)
		$where['parent_id !='] = 0;
		$a3=$this->base_occupation_model->getBaseAll($where);
		$this->assign('firm', $a1);
		$this->assign('county', $a2);
		$this->assign('occupation', $a3);
		$this->display('practice_add');
	}
	//保存添加
	public function add(){
		$data = $_POST;;
		/**上传图片**/
		if($_FILES['jietu']['tmp_name']) {
			$upload_image = $this->upload($_FILES,false,WEB_URL);
			$data['image_url'] = $upload_image;
		}
		//任务类型
		$data['task_type'] = 2;
		$data['create_time']=$this->time_retuen();
		$info=$this->task_profile_model->addTask($data);
		if($info){
			$this->location_href($this->go_url."/4");
		}else{
			$this->location_href($this->go_url."/5");
		}
	}
	

}