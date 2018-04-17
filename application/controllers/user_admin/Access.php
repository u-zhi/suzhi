<?php
class Access extends PC_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('access_model');
		$this->go_url = $this->data['admin_path']."/role/role_list";
		
	}
	
	/**
	 * @brief	权限编辑
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function edit_page() {
		$role_id = $this->uri->segment(4);
		$this->load->model('node_model');
		$nodes = $this->node_model->getNodeAll(); //通过model获取所有节点,返回二维数组
		$nodes = $this->merge_node($nodes); //递归数组成为父子数组（多维数组）eg:array(1=>array('name'=>3,'child'=>array()))
		$this->data['nodes'] = $nodes;
		$node_info = $this->access_model->getAccessAll(array('role_id'=>$role_id));		
		$this->data['data'] = $node_info;
		$this->data['role_id'] = $role_id;
		$this->load->view('/admin/access_edit',$this->data);
	}

	/**
	 * @brief	保存权限编辑数据
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function edit() {
		$data = $_POST;
		$where['role_id'] = $data['role_id'];
		$node_list = $this->access_model->getAccessAll($where);
		$node_array = array();
		if($node_list) {			
			foreach($node_list as $k => $v) {
				$node_array[] = $v['node_id'];
			}
		}
		$this->access_model->deleteAccess($where);
		if(isset( $data['node_id'])) {
		$access = $data['node_id'];
			foreach ($access as $key => $value) {
				$add_data = array('role_id'=>$data['role_id'],'node_id'=>$value);
				$edit_result = $this->access_model->addAccess($add_data);
			}
		}else {
			$edit_result = true;
		}
		if($edit_result) {
			$this->location_href($this->go_url."/4");
		}else{
			$this->location_href($this->go_url."/5");
		}
	}
}