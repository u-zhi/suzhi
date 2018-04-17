<?php

class Admin extends PC_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('admin_model');
		$this->load->model('role_model');
		$this->load->helper('hash_helper');
		$this->go_url = $this->data['admin_path']."/admin/admin_list";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	管理员列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function admin_list() {
		if($this->uri->segment(4)) {
			$this->data['message'] = $this->uri->segment(4);
		}
        $this->data['role_id'] =  empty($_POST) ? '' : $_POST['role_id'];
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $where['status']=1;
        //获取所有一级分类
        $rid_list=$this->role_model->getRoleAll($where);
        $this->data['rid_list']=$rid_list;
		$this->display('admin_list');
	}
	
	/**
	 * @brief	ajax获取管理员数据加载到列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function ajax_admin_list() {
		$data = $_GET;
		/**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
		$start = $data['iDisplayStart'];
		$length = $data['iDisplayLength'];
		$sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
		$sort_type = $data['sSortDir_0'];
		$search = $data ['sSearch'];
		$where['concat(sz_admin.id) like'] = '%'.trim($search).'%';
    	if(isset($data['search_field']) && $data['search_field']){
            $where['sz_admin.realname like'] = '%'.trim($data['search_field']).'%';
        }
        if($data['role_id']) {
            $where['sz_admin.rid'] = $data['role_id'];
        }
		$this->data['count'] = $this->admin_model->getCount($where);
		$aaData = array();
		if($this->data['count']) {
			$order_by = $sort_th." ".$sort_type;
            $order_by = 'sz_admin.id desc';
			$admin_list = $this->admin_model->getAdminList($where,$length,$start,$order_by);
			foreach($admin_list as $key => &$value) {
				$edit_url = $this->edit_url('admin','edit_page',$value['id']);	
				$del_url = $this->delete_url('/admin/delete',$value['id'],'删除','btn-purple');				
				$value['check'] = $this->get_check($value['id']);
				if($value['rid'] == 1) {
					$value['check'] = '<img src="/public_source/www/images/guding.png" title="不可选取" />';
					$del_url = '';
				}			
				$value['operate'] = $edit_url.$del_url;
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
	 * @brief	管理员编辑
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function edit_page() {
		$id = $this->uri->segment(4);
		$where['id'] = $id;
		$role_list = $this->role_model->getRoleAll();
		$admin_info = $this->admin_model->checkAdmin($where);	
		$this->assign('data', $admin_info);
		$this->assign('role_list', $role_list);
		$this->display('admin_edit');
	}
			
	/**
	 * @brief	保存编辑数据
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function edit() {
		$data = $_POST;
		$where['id'] = $data['id'];
		if($data['n_password']) {
			$data['password'] = passwd_hash($data['n_password']); //加密密码
		}
		unset($data['n_password']);
		$edit_result = $this->admin_model->editAdmin($where,$data);
		if($edit_result) {		
			/**获取信息存入到日志表中**/
			$login_info = $this->info;
			$admin = $login_info['admin'];
			$power_data['login_id'] = $admin['id'];
			$power_data['login_name'] = $admin['username'];
			$power_data['login_ip'] = $this->get_ip();
			$power_data['action_type'] = '修改';
			$power_data['method'] = $this->router->method; //当前方法
			$power_data['controller'] = $this->router->class; //当前控制器
			$address = array();
			$address = $this->GetIpLookup();
			if($address) {
				$power_data['login_address'] = $address['country'].$address['province'].$address['city'];
			}else {
				$power_data['login_address'] = '未分配或者内网IP';
			}
			
			$power_data['login_level'] = $admin['role_name'];
			/**获取修改的帐号数据**/
			$object_info = $this->admin_model->checkAdmin($where);
			$power_data['object_id'] = $object_info['id'];
			$power_data['object_name'] = $object_info['username'];
			$this->load->model('role_model');
			$role_info = $this->role_model->checkRole(array('role_id' => $object_info['rid']));
			$power_data['object_level'] = $role_info['role_name'];
			$power_data['create_time'] = time();
//			$this->load->model('power_log_model');
//			$this->power_log_model->addPower($power_data);
			$this->location_href($this->go_url."/2");	
		}else {
			$this->location_href($this->go_url."/3");	
		}
	}
	
	/**
	 * @brief	检查管理员名称是否重复
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function check_sole() {
		$data = $_POST;
		if(isset($data['id'])) {
			$where['id !='] = $data['id'];
		}
		$where['username'] = $data['username'];
		$sole = $this->admin_model->checkAdmin($where,"*",false);
		if($sole) {
			$message = false;
		}else {
			$message = true;
		}
		echo json_encode($message);
	}
	
	/**
	 * @brief	删除管理员数据
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function delete() {
		$data = $_POST;
		$id = $_POST['id'];
		$where['id'] = $id;	
		$del_result = $this->admin_model->deleteAdmin($where);
		echo json_encode($del_result);
	}	
	
	/**
	 * @brief	删除多个管理员数据
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function delete_all() {
		$data = $_POST;
		$id_list = array_filter(explode(',',$data['str']));
		$key = array_search(1, $id_list); //将管理员排除在外
		if ($key !== false)
			array_splice($id_list, $key, 1);
		$del_result = $this->admin_model->deleteAll($id_list);
		echo json_encode($del_result);
	}
	
	/**
	 * @brief	添加管理员
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function add_page() {
		$role_list = $this->role_model->getRoleAll();
		$this->data['role_list'] = $role_list;
		$this->display('admin_add');
	}
	
	/**
	 * @brief	保存添加管理员
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function add() {
		$data = $_POST;	
		$data['password'] = passwd_hash($data['n_password']); //加密密码
		unset($data['n_password']); //清空原始密码
		$data['create_time'] = $data['update_time'] = time();
		$add_result = $this->admin_model->addAdmin($data);
		if($add_result) {
//			/**获取信息存入到日志表中**/
//			$login_info = $this->info;
//			$admin = $login_info['admin'];
//			$power_data['login_id'] = $admin['id'];
//			$power_data['login_name'] = $admin['username'];
//			$power_data['login_ip'] = $this->get_ip();
//			$power_data['action_type'] = '新建';
//			$power_data['method'] = $this->router->method; //当前方法
//			$power_data['controller'] = $this->router->class; //当前控制器
//			$address = array();
//			$address = $this->getCity();
//			if($address) {
//				$power_data['login_address'] = $address['province'].$address['city'];
//			}
//			$power_data['login_level'] = $admin['role_name'];
//			/**获取新建的帐号数据**/
//			$where['id'] = $add_result;
//			$object_info = $this->admin_model->checkAdmin($where);
//			$power_data['object_id'] = $object_info['id'];
//			$power_data['object_name'] = $object_info['username'];
//			$this->load->model('role_model');
//			$role_info = $this->role_model->checkRole(array('role_id' => $object_info['rid']));
//			$power_data['object_level'] = $role_info['role_name'];
//			$power_data['create_time'] = time();
//			$this->load->model('power_log_model');
//			$this->power_log_model->addPower($power_data);
			$this->location_href($this->go_url."/4");
		}else {
			$this->location_href($this->go_url."/5");
		}
	}	
}
?>