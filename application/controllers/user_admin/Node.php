<?php

class Node extends PC_Controller {		
	public function __construct() {
		parent::__construct();
		$this->load->model('node_model');
		$this->go_url = $this->data['admin_path']."/node/node_list";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	节点列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function node_list() {
	 	$this->data['count'] = $this->node_model->getCount();
		if($this->data['count']) {
			$nodes = $this->node_model->getNodeAll();
			foreach($nodes as &$value) {
				if($value['level'] == 1) {
					$value['level_name'] = $this->default_img('/public_source/www/images/all.png',$height='20');
				}else {
					$value['level_name'] = $this->default_img('/public_source/www/images/fen.png',$height='20');
				}
			}
			$nodes = $this->merge_node($nodes);
			$this->data['data'] = $nodes;
		}else {
			$this->data['data'] = array(); 
		}
		if($this->uri->segment(4)) {
			$this->data['message'] = $this->uri->segment(4);
		}
		$this->display('node_list');
	}
	
	/**
	 * @brief	节点编辑
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function edit_page() {
		$id = $this->uri->segment(4);
		$where['node_id'] = $id;
		$node_info = $this->node_model->checknode($where);
		$this->data['data'] = $node_info;
		$this->display('node_edit');
	}

	/**
	 * @brief	保存编辑节点数据
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function edit() {
		$data = $_POST;
		$where['node_id'] = $data['node_id'];
		$add_result = $this->node_model->editNode($where,$data);
		$go_url = 'node_list';
		if($add_result) {
			$this->location_href($this->go_url."/2");
		}else{
			$this->location_href($this->go_url."/3");
		}
	}
	
	/**
	 * @brief	添加节点
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function add_page() {
		$id = $this->uri->segment(4);
		$id = $id ? $id:0;
		$this->data['pid'] = $id;
		$this->display('node_add');
	}
	
	/**
	 * @brief	保存添加节点数据
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function add() {
		$data = $_POST;
		$add_result = $this->node_model->addNode($data);
		if($add_result){
			$this->location_href($this->go_url."/4");
		}else{
			$this->location_href($this->go_url."/5");
		}
	}

	/**
	 * @brief	删除节点数据
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function delete() {
		$data = $_POST;
		$id = $data['id'];	
		$node_info = $this->node_model->checkNode(array("node_id"=>$id)); //获取节点信息
		$this->load->model('access_model'); //加载access模型
		if($node_info['pid'] == 0) {
			//父节点
			$child_nodes = $this->node_model->getNodeAll(array("pid"=>$node_info['node_id'])); //获取子节点
			foreach ($child_nodes as $k=>$node) {
				//删除子节点
				if($this->access_model->deleteAccess(array("node_id"=>$node['node_id'])) ){ //删除该节点对应角色权限
					$this->node_model->deleteNode(array("node_id"=>$node['node_id']));
				}
			}
			//删除父节点
			if($this->access_model->deleteAccess(array("node_id"=>$node_info['node_id'])) ) { //删除角色权限
				if( $this->node_model->deleteNode(array("node_id"=>$node_info['node_id'])) ) { //删除父节点
					echo json_encode(true);
				}else {
					echo json_encode(false);
				}
			}			
		}else {			
			//子节点
			if($this->access_model->deleteAccess(array("node_id"=>$id)) ) { //删除角色权限
				if($this->node_model->deleteNode(array("node_id"=>$id)) ) { //删除节点
					echo json_encode(true);
				}else {
					echo json_encode(false);
				}
			}
		}		

	}
}