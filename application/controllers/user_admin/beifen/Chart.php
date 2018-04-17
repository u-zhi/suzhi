<?php
/**
 * User: Allen
 * Date: 17-02-07
 * 统计控制器
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
class Chart extends PC_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('purchase_model');
		$this->load->model('user_model');
		$this->go_url = $this->data['admin_path']."/chart/channel_member";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	统计渠道数量
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/02/07 Ver 1.0
	 */
	public function channel_member() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		$now_month = date("Y-m",time());
		$this->data['start_time'] =  empty($_POST) ? $now_month : $_POST['start_time'];
		/**获取各级代理的总数**/
		$chart_where = '1 = 1';
		$chart_where .= ' and mu.parent_status = 3'; //上级审核通过
		$chart_where .= ' and mu.invite_status = 3'; //推荐审核通过
		$total_list = $this->user_model->getChartList($chart_where,'a.agent_level, IFNULL(b.level_num, 0) as level_num','mu.agent_level');
		$chart_where .= ' and mu.create_time >='.strtotime($this->data['start_time']);
		$chart_where .= ' and mu.create_time <'.mktime(23, 59, 59, date('m',strtotime($this->data['start_time']))+1, 00);//指定月份月末时间戳		
		/**获取查询月份的各级代理的总数**/
		$month_list = $this->user_model->getChartList($chart_where,'a.agent_level, IFNULL(b.level_num, 0) as month_num','mu.agent_level');
		$agent_list = array('','一级代理','二级代理','三级代理','四级代理','五级代理');
		$this->assign('agent_list',$agent_list);
		$this->assign('total_list',$total_list);
		$this->assign('month_list',$month_list);
		$this->display('channel_member');
	}

	/**
	 * @brief	导出功能操作
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/13 Ver 1.0
	 */
	public function export_excel() {
		error_reporting(E_ALL ^ E_NOTICE);
		$this->load->helper('array_to_excel');
		$export_list = array();
		/**获取各级代理的总数**/
		$chart_where = '1 = 1';
		$chart_where .= ' and mu.parent_status = 3'; //上级审核通过
		$chart_where .= ' and mu.invite_status = 3'; //推荐审核通过
		$export_list = $this->user_model->getChartList($chart_where,'a.agent_level, IFNULL(b.level_num, 0) as level_num','mu.agent_level');
		$start_time = $this->uri->segment(4);
		$chart_where .= ' and mu.create_time >='.strtotime($start_time);
		$chart_where .= ' and mu.create_time <'.mktime(23, 59, 59, date('m',strtotime($start_time))+1, 00);//指定月份月末时间戳		
		/**获取查询月份的各级代理的总数**/
		$month_list = $this->user_model->getChartList($chart_where,'a.agent_level, IFNULL(b.level_num, 0) as month_num','mu.agent_level');
		$agent_list = array('','一级代理','二级代理','三级代理','四级代理','五级代理');
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
		$export_data['message'] = '渠道数量统计列表';
		$export_data['create_time'] = time();
		$this->load->model('operation_log_model');
		$this->operation_log_model->addOperation($export_data);
		$total_sum = 0;
		$month_sum = 0;
		foreach ($export_list as $key => &$value) {
			$total_sum += $value['level_num'];
			$month_sum += $month_list[$key]['month_num'];
			$order_export[] = array(
					$agent_list[$value['agent_level']],$value['level_num'].'个',$month_list[$key]['month_num'].'个'
			);
		}
		$order_export[] = array('总计',$total_sum.'个',$month_sum.'个');
		$arr_name = array('等级','累积',$start_time);
		export_data_excel($arr_name, $order_export, '渠道数量统计列表');
	}
}
?>