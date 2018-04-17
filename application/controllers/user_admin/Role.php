<?php
class Role extends PC_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('role_model');
		$this->go_url = $this->data['admin_path']."/role/role_list";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	角色列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function role_list() {
	if($this->uri->segment(4)) {
			$this->data['message'] = $this->uri->segment(4);
		}
		$this->display('role_list');
	}
	
	/**
	 * @brief	ajax获取角色数据加载到列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function ajax_role_list() {
		$data = $_GET;
		$start = $data['iDisplayStart']; //显示的起始索引
		$length = $data['iDisplayLength']; //显示的行数
		$sort_th = $data['mDataProp_'.$data['iSortCol_0'].'']; //被排序的列 
		$sort_type = $data['sSortDir_0']; //排序的方向 "desc" 或者 "asc".
		$search = $data ['sSearch']; //全局搜索字段
		$where['concat(role_id) like'] = '%'.trim($search).'%';
		$where['role_id !='] = 1;
		$this->data['count'] = $this->role_model->getCount($where);
		$aaData = array();
		if($this->data['count']) {
			$order_by = $sort_th." ".$sort_type;
			$role_list = $this->role_model->getRoleList($where,$length,$start,$order_by);
			$authority = $this->authority;
			foreach($role_list as $key => &$value) {
				$value['set_limit'] = '无权限';
				if($authority['set_status'] == 2) {
					$value['set_limit'] = '<a href="/user_admin/access/edit_page/'.$value['role_id'].'">配置权限</a>';
				}
				$value['check'] = $this->get_check($value['role_id']);
				$edit_url = $this->edit_url('role','edit_page',$value['role_id']);
				$del_url = $this->delete_url('/role/delete',$value['role_id'],'删除','btn-purple');
				$value['operate'] = $edit_url.$del_url;
				if(!$value['operate']) {
					$value['operate'] = '无操作';
				}
			}
			$aaData = $role_list;
		}
		$output['aaData'] = $aaData;
		$output['sEcho'] = $_GET['sEcho'];
		$output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
		$output['iTotalRecords'] = $this->data['count']; //总共有几条数据
		echo json_encode($output); //最后把数据以json格式返回
	}
	
	/**
	 * @brief	角色编辑
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function edit_page() {
		$id = $this->uri->segment(4);
		$where['role_id'] = $id;
		$role_info = $this->role_model->checkRole($where);
		$this->assign('data', $role_info);
		$this->display('role_edit');
	}

	/**
	 * @brief	保存编辑数据
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function edit() {
		$data = $_POST;
		$where['role_id'] = $data['role_id'];
		$edit_result = $this->role_model->editRole($where,$data);
		if($edit_result) {
			$this->location_href($this->go_url."/2");	
		}else {
			$this->location_href($this->go_url."/3");	
		}
	}

	/**
	 * @brief	检查角色名称是否重复
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function check_sole() {
		$data = $_POST;
		if(isset($data['role_id'])) {
			$where['role_id !='] = $data['role_id'];
		}
		$where['role_name'] = $data['role_name'];
		$where['company_id'] = $data['company_id'];
		$sole = $this->role_model->checkRole($where);
		if($sole) {
			$message = false;
		}else {
			$message = true;
		}
		echo json_encode($message);
	}
	
	/**
	 * @brief	删除角色
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function delete() {
		$data = $_POST;
		$id = $_POST['id'];
		$where['role_id'] = $id;	
		$del_result = $this->role_model->deleteRole($where);
		echo json_encode($del_result);
	}	
	
	/**
	 * @brief	删除多个角色
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function delete_all() {
		$data = $_POST;
		$id_list = array_filter(explode(',',$data['str']));
		//$id_list = array(3,4);
		$del_result = $this->role_model->deleteAll($id_list);
		echo json_encode($del_result);
	}
	
	/**
	 * @brief	添加角色
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function add_page() {
		$this->display('role_add');
	}
	
	/**
	 * @brief	保存添加角色
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function add() {
		$data = $_POST;	
		$data['update_time'] = $data['create_time'] = time();
		$add_result = $this->role_model->addRole($data);
		if($add_result) {
			$this->location_href($this->go_url."/4");
		}else {
			$this->location_href($this->go_url."/5");
		}
	}	
}
?>