<?php
/**
 * User: Allen
 * Date: 17-01-06
 * 意向代理控制器
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
class Need extends PC_Controller {		
	public function __construct() {
		parent::__construct();
		$this->load->model('need_model');
		$this->go_url = $this->data['admin_path']."/need/need_list";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	意向代理列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/06 Ver 1.0
	 */
	public function need_list() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		$this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
		$this->display('need_list');
	}
	
	/**
	 * @brief	ajax获取意向代理列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/06 Ver 1.0
	 */
	public function ajax_need_list() {
		$data = $_GET;
		/**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
		$start = $data['iDisplayStart'];
		$length = $data['iDisplayLength'];
		$sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
		$sort_type = $data['sSortDir_0'];
		$search = $data ['sSearch'];
		$where['concat(moyoo_need.id) like'] = '%'.trim($search).'%';
		if($data['search_field']) {
			$where['concat(moyoo_need.phone) like'] = '%'.trim($data['search_field']).'%';;
		}

		$this->data['count'] = $this->need_model->getCount($where);
		$aaData = array();
		if($this->data['count']) {			
			$order_by = $sort_th." ".$sort_type;
			$need_list = $this->need_model->getNeedList($where,$length,$start,$order_by,'need.*,user.user_name as temp_name,user.agent_level as temp_level');
			$status_array = array("1" => '待处理',"2" => '已处理');
			$level_array = array('','一级代理','二级代理','三级代理','四级代理','五级代理');
			foreach($need_list as $key => &$value) {
				$edit_url = $this->delete_url('/need/delete',$value['id'],'删除','btn-purple');
				if($value['is_allot'] == 1) {
					$edit_url = $this->edit_url('need','edit_page',$value['id'],'设置');				
				}
				$value['create_time'] = date("Y-m-d H:i",$value['create_time']);
				$value['agent_level'] = $level_array[$value['agent_level']];
				$value['is_allot'] = $status_array[$value['is_allot']]; //后台是否分配
				$value['is_status'] = $status_array[$value['is_status']]; //前端是否处理
				if($value['temp_level']) {
					$value['temp_level'] = $level_array[$value['temp_level']];
				}else {
					$value['temp_level'] = '暂无';
					$value['temp_name'] = '暂无';
				}			
				$value['operate'] = $edit_url;
				if(!$value['operate']) {
					$value['operate'] = '无';
				}
			}
			$aaData = $need_list;
		}
		$output['aaData'] = $aaData;
		$output['sEcho'] = $_GET['sEcho'];
		$output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
		$output['iTotalRecords'] = $this->data['count']; //总共有几条数据
		echo json_encode($output); //最后把数据以json格式返回
	}
	
	/**
	 * @brief	编辑页面
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/06 Ver 1.0
	 */
	public function edit_page() {
		$id = $this->uri->segment(4);
		$where['id'] = $id;
		$need_info = null;
		if(is_numeric($id)) {		
			$need_info = $this->need_model->checkNeed($where);
			if($need_info) {
				$level_array = array('','一级代理','二级代理','三级代理','四级代理','五级代理');
				$need_info['level'] = $level_array[$need_info['agent_level']];
			}
		}
		$this->check_rational($need_info);
		$this->assign('data', $need_info);
		$this->display('need_edit');
	}
	
	/**
	 * @brief	编辑页面
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/06 Ver 1.0
	 */
	public function check_need() {
		$data = $_POST;
		$this->load->model('user_model');
		$user_where['phone'] = $data['phone'];
		$user_where['is_delete'] = 1;
		$user_where['is_status'] = 1;
		$user_info = $this->user_model->checkUser($user_where);
		if($user_info) {
			$message = false;
			if($data['agent_level'] >= $user_info['agent_level']) {
				$message = true;
			}
		}else {
			$message = false;
		}
		echo json_encode($message);
	}
	
	/**
	 * @brief	保存编辑信息
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/06 Ver 1.0
	 */
	public function edit() {
		$data = $_POST;
		/**获得代理的信息**/
		$this->load->model('user_model');
		$user_where['phone'] = $data['phone'];
		$user_where['is_delete'] = 1;
		$user_where['is_status'] = 1;
		$user_info = $this->user_model->checkUser($user_where);
		if(!$user_info) {
			$this->location_href($this->go_url."/3");
		}else {
			$need_data['agency_id'] = $user_info['id'];
			$need_data['update_time'] = time();
			$need_data['is_allot'] = 2; //已分配
			$where['id'] = $data['id'];
			$edit_result = $this->need_model->editNeed($where,$need_data);
			if($edit_result) {
				$this->location_href($this->go_url."/2");
			}else {
				$this->location_href($this->go_url."/3");
			}	
		}
	}
	
	/**
	 * @brief	重新分配
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/07 Ver 1.0
	 */
	public function delete() {
		$data = $_POST;
		$id = $data['id'];
		$where['id'] = $id;
		$need_data['agency_id'] = 0;
		$need_data['update_time'] = time();
		$need_data['is_allot'] = 1; //未分配
		$need_data['is_status'] = 1; //未处理
		$del_result = $this->need_model->editNeed($where,$need_data);
		echo json_encode($del_result);
	}

	/**
	 * @brief	导出功能操作
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/06 Ver 1.0
	 */
	public function export_excel() {
		error_reporting(E_ALL ^ E_NOTICE);
		$this->load->helper('array_to_excel');
		$exprot_list = array();
		$exprot_list = $this->need_model->getNeedAll(array(),'need.*,user.user_name as temp_name,user.agent_level as temp_level');
		$status_array = array("1" => '待处理',"2" => '已处理');
		$level_array = array('','一级代理','二级代理','三级代理','四级代理','五级代理');
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
		$export_data['message'] = '意向代理列表';
		$export_data['create_time'] = time();
		$this->load->model('operation_log_model');
		$this->operation_log_model->addOperation($export_data);
		foreach ($exprot_list as $key => &$value) {
			$value['create_time'] = date("Y-m-d H:i",$value['create_time']);
			$value['agent_level'] = $level_array[$value['agent_level']];
			$value['is_allot'] = $status_array[$value['is_allot']];
			$value['is_status'] = $status_array[$value['is_status']]; //前端是否处理
			if($value['temp_level']) {
				$value['temp_level'] = $level_array[$value['temp_level']];
			}else {
				$value['temp_level'] = '暂无';
				$value['temp_name'] = '暂无';
			}
			$order_export[] = array(
					$value['id'],$value['user_name'],$value['phone'],$value['wechat_num'],$value['email'],
					$value['agent_level'],$value['is_allot'],$value['create_time'],$value['temp_name'],$value['temp_level'],$value['is_status']
			);
		}
		$arr_name = array('编号id','姓名','手机号','微信号','邮箱','意向等级','分配状态','申请时间','分配代理','代理等级','前端状态');
		export_data_excel($arr_name, $order_export, '意向代理列表');
	}
}
?>