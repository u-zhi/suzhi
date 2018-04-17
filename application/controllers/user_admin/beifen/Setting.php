<?php
/**
 * User: Allen
 * Date: 16-12-26
 * 通用设置控制器
 * ━━━━━━神兽出没━━━━━━
 * 　　　┏┓　　　┏┓
 * 　　┏┛┻━━━┛┻┓
 * 　　┃　　　　　　　┃
 * 　　┃　　　━　　　┃
 * 　　┃　┳┛　┗┳　┃
 * 　　┃　　　　　　　┃
 * 　　┃　　　┻　　　┃
 * 　　┃　　　　　　　┃
 * 　　┗━┓　　　┏━┛Code is far away from bug with the animal protecting
 * 　　　　┃　　　┃    神兽保佑,代码无bug
 * 　　　　┃　　　┃
 * 　　　　┃　　　┗━━━┓
 * 　　　　┃　　　　　　　┣┓
 * 　　　　┃　　　　　　　┏┛
 * 　　　　┗┓┓┏━┳┓┏┛
 * 　　　　　┃┫┫　┃┫┫
 * 　　　　　┗┻┛　┗┻┛
 *
 * ━━━━━━感觉萌萌哒━━━━━━
 */
class Setting extends PC_Controller {		
	public function __construct() {
		parent::__construct();
		$this->go_url = $this->data['admin_path']."/setting/basic_set";
		$this->company_url = $this->data['admin_path']."/setting/company_set";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	基础设置
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/26 Ver 1.0
	 */
	public function basic_set() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		$this->load->model('basic_config_model');
		//$where['id'] = 1;
		$basic_info = array();
		$basic_info = $this->basic_config_model->checkBasic();
		$this->assign('data',$basic_info);
		$this->display('basic_set');
	}
	
	/**
	 * @brief	保存编辑信息
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/26 Ver 1.0
	 */
	public function save_basic_edit() {
		$this->load->model('basic_config_model');
		$data = $_POST;
		$data['update_time'] = time();
		$where['id'] = $data['id'];
		$edit_result = $this->basic_config_model->editBasic($where,$data);
		if($edit_result) {
			$this->location_href($this->go_url."/2");
		}else {
			$this->location_href($this->go_url."/3");
		}
	}
	
	/**
	 * @brief	公司设置
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/26 Ver 1.0
	 */
	public function company_set() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		$this->load->model('admin_model');
		$where['id'] = 1;
		$admin_info = array();
		$admin_info = $this->admin_model->checkAdmin($where);
		$this->assign('data',$admin_info);
		$this->display('company_set');
	}
	
	/**
	 * @brief	保存编辑信息
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/26 Ver 1.0
	 */
	public function save_company_edit() {
		$this->load->model('admin_model');
		$data = $_POST;
		//$data['update_time'] = time();
		$where['id'] = $data['id'];
		$edit_result = $this->admin_model->editAdmin($where,$data);
		if($edit_result) {
			$this->location_href($this->company_url."/2");
		}else {
			$this->location_href($this->company_url."/3");
		}
	}
}
?>