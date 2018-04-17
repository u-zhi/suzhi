<?php

class Login extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('admin_model');
		$this->load->library('visitor');
		$this->load->helper('hash_helper');
	}
	
	/**
	 * @brief	登录页面
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/10/22 Ver 1.0
	 */
	public function index() {
		$this->data['admin_path'] = '/user_admin';
		$this->load->view('/admin/login.php', $this->data);
	}
	
	/**
	 * @brief	登录操作（检测帐号、存session）
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0 
	 */
	public function login() {
		$data =$_POST;
		$username = $data['username'];
		$password = $data['password'];
		$admin = '';
		$back_result = array("message" => false,"login_error" => 0);
		if(isset($_SESSION['login_error']) && $_SESSION['login_error'] > 5) {
			$back_result['login_error'] = $_SESSION['login_error'];
		}else {
			if ($username && $password) {
				$admin = $this->admin_model->checkAdmin(array('username' => $username));
			}
			if ($admin && passwd_verify($password, $admin['password'])) {
				$user_data = $this->session->userdata('ALL_WANG');
				//获取当前帐号的权限名称
				$this->load->model('role_model');
				$role_info = $this->role_model->checkRole(array('role_id' => $admin['rid']));
				$admin['role_name'] = $role_info['role_name'];
				$user_data['admin'] = $admin;
				$this->visitor->assign($user_data);
				$this->session->unset_userdata('login_error');
				/**获取登录数据并存入数据库**/
				$update_data['last_ip'] = $this->get_ip();
				$update_address = array();
				$update_address = $this->GetIpLookup($update_data['last_ip']);
				if($update_address) {
					$update_data['last_address'] = $update_address['country'].$update_address['province'].$update_address['city'];
				}else {
					$update_data['last_address'] = '未分配或者内网IP';
				}			
				$update_data['last_time'] = time();
				$update_data['login_num'] = $admin['login_num'] + 1;
				$this->admin_model->editAdmin(array('id' => $admin['id']),$update_data);
				$back_result['message'] = true;
			} else {
				$back_result['message'] = false;
				if(!empty($_SESSION['login_error'])) {
					$_SESSION['login_error'] = $_SESSION['login_error'] + 1;
				}else {
					$_SESSION['login_error'] = 1;
				}
				$back_result['login_error'] = $_SESSION['login_error'];
			}
		}
		echo json_encode($back_result);
	}

	/**
	 * @brief	登出操作（清空session）
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function logout() {
		$user_data = $this->session->userdata('HS_cartoon');
		unset($user_data['admin']);
		$this->visitor->assign($user_data);
		$this->data['source'] = 2;
		$this->data['admin_path'] = '/user_admin';
		$this->load->view('/admin/login.php', $this->data);
	}
	
	/**
	 * @brief	获取客户端IP
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/07/12 Ver 1.0
	 */
	public function get_ip() {
		global $ip;
		if (getenv("HTTP_CLIENT_IP")) {
			$ip = getenv("HTTP_CLIENT_IP");
		}else if(getenv("HTTP_X_FORWARDED_FOR")) {
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		}else if(getenv("REMOTE_ADDR")) {
			$ip = getenv("REMOTE_ADDR");
		}else {
			$ip = "";
		}
		return $ip;
	}	
	
	/**
	 * @brief	获取地址
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/23 Ver 1.0
	 */
	public function GetIpLookup($ip = '') {
		$res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
		if(empty($res)){ return false; }
		$jsonMatches = array();
		preg_match('#\{.+?\}#', $res, $jsonMatches);
		if(!isset($jsonMatches[0])){ return false; }
		$json = json_decode($jsonMatches[0], true);
		if(isset($json['ret']) && $json['ret'] == 1){
			$json['ip'] = $ip;
			unset($json['ret']);
		}else{
			return false;
		}
		return $json;
	}
}
?>