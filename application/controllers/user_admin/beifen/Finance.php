<?php
/**
 * User: Allen
 * Date: 16-12-26
 * 代理商申请门槛控制器
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
class Finance extends PC_Controller {		
	public function __construct() {
		parent::__construct();
		$this->go_url = $this->data['admin_path']."/finance/finance_set";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	代理商申请门槛设置
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/26 Ver 1.0
	 */
	public function finance_set() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		$this->load->model('basic_config_model');
		//$where['id'] = 1;
		$basic_info = array();
		$basic_info = $this->basic_config_model->checkBasic();
		$this->assign('data',$basic_info);
		$this->display('finance_set');
	}
	
	/**
	 * @brief	保存编辑信息
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/26 Ver 1.0
	 */
	public function save_finance_edit() {
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
}
?>