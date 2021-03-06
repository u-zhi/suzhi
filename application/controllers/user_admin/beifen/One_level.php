<?php
/**
 * User: Allen
 * Date: 16-12-27
 * 一级代理控制器
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
class One_level extends PC_Controller {		
	public function __construct() {
		parent::__construct();
		$this->load->model('user_model');
		$this->go_url = $this->data['admin_path']."/one_level/one_list";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	一级代理列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/27 Ver 1.0
	 */
	public function one_list() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		$this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
		$this->display('one_list');
	}
	
	/**
	 * @brief	ajax获取一级代理列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/27 Ver 1.0
	 */
	public function ajax_one_list() {
		$data = $_GET;
		/**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
		$start = $data['iDisplayStart'];
		$length = $data['iDisplayLength'];
		$sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
		$sort_type = $data['sSortDir_0'];
		$search = $data ['sSearch'];
		$where['concat(moyoo_user.id) like'] = '%'.trim($search).'%';
		$where['parent_status'] = 3; //上级审核通过
		$where['invite_status'] = 3; //推荐人审核通过
		$where['agent_level'] = 1; //一级代理
		if($data['search_field']) {
			$where['concat(moyoo_user.user_name) like'] = '%'.trim($data['search_field']).'%';;
		}
		$this->data['count'] = $this->user_model->getCount($where);
		$aaData = array();
		if($this->data['count']) {			
			$order_by = $sort_th." ".$sort_type;
			$user_list = $this->user_model->getUserList($where,$length,$start,$order_by,'user.*');
			$status_array = array("1" => '正常',"2" => '冻结');
			foreach($user_list as $key => &$value) {
				$edit_url = $this->edit_url('one_level','edit_page',$value['id']);	
				$look_url = $this->edit_url('one_level','look_page',$value['id'],'查看','btn-pink');
				if($value['is_status'] == 1) {
					$up_url = $this->delete_url('/one_level/agree_one_level',$value['id'],'冻结','btn-success');				
				}else {
					$up_url = $this->delete_url('/one_level/fail_one_level',$value['id'],'解冻','btn-light');
				}
				$value['is_status'] = $status_array[$value['is_status']];
				$value['operate'] = $look_url.$edit_url.$up_url;
				if(!$value['operate']) {
					$value['operate'] = '无';
				}
			}
			$aaData = $user_list;
		}
		$output['aaData'] = $aaData;
		$output['sEcho'] = $_GET['sEcho'];
		$output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
		$output['iTotalRecords'] = $this->data['count']; //总共有几条数据
		echo json_encode($output); //最后把数据以json格式返回
	}
	
	/**
	 * @brief	冻结帐号
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/27 Ver 1.0
	 */
	public function agree_one_level() {
		$data = $_POST;
		$where['id'] = $data['id'];
		$edit_data['is_status'] = 2;
		$edit_result = $this->user_model->editUser($where,$edit_data);
		echo json_encode($edit_result);
	}
	
	/**
	 * @brief	解冻帐号
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/27 Ver 1.0
	 */
	public function fail_one_level() {
		$data = $_POST;
		$where['id'] = $data['id'];
		$edit_data['is_status'] = 1;
		$edit_result = $this->user_model->editUser($where,$edit_data);
		echo json_encode($edit_result);
	}
	

	
	/**
	 * @brief	检查字段的唯一性
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/28 Ver 1.0
	 */
	public function check_sole() {
		$data = $_POST;
		if(isset($data['id'])) {
			$where['id !='] = $data['id'];
		}
		if(isset($data['user_name'])) {
			$where['user_name'] = $data['user_name'];
		}
		if(isset($data['phone'])) {
			$where['phone'] = $data['phone'];
		}
		$sole = $this->user_model->checkUser($where);
		if($sole) {
			$message = false;
		}else {
			$message = true;
		}
		echo json_encode($message);
	}

	
	/**
	 * @brief	编辑页面
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/28 Ver 1.0
	 */
	public function edit_page() {
		$id = $this->uri->segment(4);
		$where['id'] = $id;
		$user_info = null;
		if(is_numeric($id)) {		
			$user_info = $this->user_model->checkUser($where);
			if($user_info && $user_info['agent_level'] != 1) {
				$user_info = null;
			}else {
				$user_info['level'] = '一级代理';
			}
		}
		$this->check_rational($user_info);
		$this->assign('data', $user_info);
		$this->display('one_edit');
	}
	
	/**
	 * @brief	保存编辑信息
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function edit() {
		$data = $_POST;
		$is_diploma = 0;
		if($data['old_name'] != $data['user_name'] || $data['old_phone'] != $data['old_phone'] || $data['old_wechat'] != $data['wechat_num']) {
			$is_diploma = 1;
		}
		unset($data['old_name']);unset($data['old_phone']);unset($data['old_wechat']);
		$where['id'] = $data['id'];
		$edit_result = $this->user_model->editUser($where,$data);
		if($edit_result) {		
			if($is_diploma == 1) {
				$user_info = $this->user_model->checkUser($where);
				/**成功后生成新的证书**/
				$this->create_diploma(DIPLOMA_IMAGE,'./public_source/www/diploma/qrcode.png', $user_info);				
			}
			$this->location_href($this->go_url."/2");
		}else {
			$this->location_href($this->go_url."/3");
		}
	}

	/**
	 * @brief	查看信息
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/28 Ver 1.0
	 */
	public function look_page() {
		$id = $this->uri->segment(4);
		$where['user.id'] = $id;
		$user_info = null;
		if(is_numeric($id)) {
			$fields = 'user.*,one.user_name as one_name,one.phone as one_phone,one.wechat_num as one_num,one.email as one_email,two.user_name as two_name,two.phone as two_phone,two.wechat_num as two_num,two.email as two_email,three.user_name as three_name,three.phone as three_phone,three.wechat_num as three_num,three.email as three_email,four.user_name as four_name,four.phone as four_phone,four.wechat_num as four_num,four.email as four_email';		
			$user_info =  $this->user_model->checkJoinUser($where,$fields);
			if($user_info && $user_info['agent_level'] != 1) {
				$user_info = null;
			}else {
				$user_info['level'] = '一级代理';
			}
		}
		$this->check_rational($user_info);
		/**获取公司信息**/
		$this->load->model('admin_model');
		$admin_where['id'] = 1;
		$amdin_info = $this->admin_model->checkAdmin($admin_where);
		/**获取二级代理个数**/
		$two_count = $this->user_model->getCount(array('user.one_id' => $id,'user.agent_level' => '2','user.parent_status' => '3','user.invite_status' => '3'));
		/**获取三级代理个数**/
		$three_count = $this->user_model->getCount(array('user.one_id' => $id,'user.agent_level' => '3','user.parent_status' => '3','user.invite_status' => '3'));
		/**获取四级代理个数**/
		$four_count = $this->user_model->getCount(array('user.one_id' => $id,'user.agent_level' => '4','user.parent_status' => '3','user.invite_status' => '3'));	
		/**获取五级代理个数**/
		$five_count = $this->user_model->getCount(array('user.one_id' => $id,'user.agent_level' => '5','user.parent_status' => '3','user.invite_status' => '3'));
		$this->data['data'] = $user_info;
		$this->data['amdin_info'] = $amdin_info;
		$this->data['two_count'] = $two_count;
		$this->data['three_count'] = $three_count;
		$this->data['four_count'] = $four_count;
		$this->data['five_count'] = $five_count;
		$this->display('one_look');
	}

	/**
	 * @brief	导出功能操作
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/29 Ver 1.0
	 */
	public function export_excel() {
		error_reporting(E_ALL ^ E_NOTICE);
		$this->load->helper('array_to_excel');
		$where['user.parent_status'] = 3; //上级审核通过
		$where['user.invite_status'] = 3; //推荐人审核通过
		$where['user.agent_level'] = 1; //一级代理
		$fields = 'user.*,one.user_name as one_name,one.phone as one_phone,one.wechat_num as one_num,one.email as one_email,two.user_name as two_name,two.phone as two_phone,two.wechat_num as two_num,two.email as two_email,three.user_name as three_name,three.phone as three_phone,three.wechat_num as three_num,three.email as three_email,four.user_name as four_name,four.phone as four_phone,four.wechat_num as four_num,four.email as four_email';
		$exprot_list = array();
		$exprot_list = $this->user_model->getExportAll($where,$fields);	
		/**获取公司信息**/
		$this->load->model('admin_model');
		$admin_where['id'] = 1;
		$amdin_info = $this->admin_model->checkAdmin($admin_where);
		/**将导入信息存入到记录表**/	
		$login_info = $this->info;
		$admin = $login_info['admin'];
		$export_data['admin_id'] = $admin['id'];
		$export_data['admin_name'] = $admin['username'];
		$export_data['admin_ip'] = $this->get_ip();
		$export_data['action_type'] = '导出';
		$export_data['type'] = 2;
		$export_data['method'] = $this->router->method; //当前方法
		$export_data['controller'] = $this->router->class; //当前控制器
		$address = array();
		$address = $this->GetIpLookup();
		if($address) {
			$export_data['admin_address'] = $address['country'].$address['province'].$address['city'];
		}else {
			$export_data['admin_address'] = '未分配或者内网IP';
		}
		$export_data['admin_level'] = $admin['role_name'];
		$export_data['message'] = '一级代理列表';
		$export_data['create_time'] = time();
		$this->load->model('operation_log_model');
		$this->operation_log_model->addOperation($export_data);	
		foreach ($exprot_list as $key => &$value) {			
			$value['level'] = '一级代理';
			$value['create_time'] = date("Y-m-d H:i:s");
			if($value['is_status'] == 1) {
				$value['is_status'] = '正常';
			}else {
				$value['is_status'] = '冻结';
			}
			$order_export[] = array(
					$value['id'],$value['user_name'],$value['level'],$value['phone'],$value['wechat_num'],$value['email'],
					$value['create_time'],$value['is_status'],$amdin_info['contact'],$amdin_info['contact_num'],
					$value['one_name'],$value['one_phone'],$value['one_num'],$value['one_email'],
					$value['two_name'],$value['two_phone'],$value['two_num'],$value['two_email'],
					$value['three_name'],$value['three_phone'],$value['three_num'],$value['three_email'],
					$value['four_name'],$value['four_phone'],$value['four_num'],$value['four_email'],
					);
		}
 		$arr_name = array('编号id','姓名','等级','手机号','微信号','邮箱','申请时间','状态','公司联系人','公司电话','一级代理姓名','一级代理电话','一级代理微信','一级代理邮箱','二级代理姓名','二级代理电话','二级代理微信','二级代理邮箱','三级代理姓名','三级代理电话','三级代理微信','三级代理邮箱','四级代理姓名','四级代理电话','四级代理微信','四级代理邮箱');
 		export_data_excel($arr_name, $order_export, '一级代理列表');
	} 
}
?>