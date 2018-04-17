<?php
/**
 * User: Allen
 * Date: 16-12-26
 * 二维码控制器
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
class Qrcode extends PC_Controller {		
	public function __construct() {
		parent::__construct();
		$this->go_url = $this->data['admin_path']."/qrcode/qrcode_set";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	二维码设置
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/26 Ver 1.0
	 */
	public function qrcode_set() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		$this->load->model('basic_config_model');
		//$where['id'] = 1;
		$basic_info = array();
		$basic_info = $this->basic_config_model->checkBasic();
		$this->assign('data',$basic_info);
		$this->display('qrcode_set');
	}
}
?>