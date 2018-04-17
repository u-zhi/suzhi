<?php
/**
 * User: Allen
 * Date: 17-02-09
 * 渠道销售统计控制器
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
class Chart_sale extends PC_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('purchase_model');
		$this->load->model('user_model');
		$this->go_url = $this->data['admin_path']."/chart_sale/channel_sale";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	统计渠道销售
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/02/09 Ver 1.0
	 */
	public function channel_sale() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		$this->load->model('pickup_model');
		$now_month = date("Y-m",time());
		$this->data['start_time'] =  empty($_POST) ? $now_month : $_POST['start_time'];
		/**获取各级代理的采购，出货，提货总额**/
		$chart_where = '1 = 1';
		$chart_where .= ' and mu.type = 2'; //采购数据
		$chart_where .= ' and mu.order_status = 3'; //状态采购完成
		$purchase_list = $this->purchase_model->getChartList($chart_where,'a.user_level, IFNULL(b.total_price, 0) AS total_price','mu.user_level');
		$clear_list = $this->purchase_model->getChartList($chart_where,'a.parent_level, IFNULL(b.total_price, 0) AS total_price','mu.parent_level','parent_level');
		$pick_where = '1 = 1';
		$pick_where .= ' and mu.order_status = 4'; //状态提货完成
		$pickup_list = $this->pickup_model->getChartList($pick_where,'a.user_level, IFNULL(b.total_price, 0) AS total_price','mu.user_level');
		$chart_where .= ' and mu.create_time >='.strtotime($this->data['start_time']);
		$chart_where .= ' and mu.create_time <'.mktime(23, 59, 59, date('m',strtotime($this->data['start_time']))+1, 00);//指定月份月末时间戳
		/**获取查询月份的各级代理的的采购，出货，提货总额**/
		$purchase_month_list = $this->purchase_model->getChartList($chart_where,'a.user_level, IFNULL(b.total_price, 0) AS total_price','mu.user_level');
		$clear_month_list = $this->purchase_model->getChartList($chart_where,'a.parent_level, IFNULL(b.total_price, 0) AS total_price','mu.parent_level','parent_level');
		$pick_where .= ' and mu.create_time >='.strtotime($this->data['start_time']);
		$pick_where .= ' and mu.create_time <'.mktime(23, 59, 59, date('m',strtotime($this->data['start_time']))+1, 00);//指定月份月末时间戳
		$pickup_month_list = $this->pickup_model->getChartList($pick_where,'a.user_level, IFNULL(b.total_price, 0) AS total_price','mu.user_level');
		$agent_list = array('','一级代理','二级代理','三级代理','四级代理','五级代理');
		$this->assign('agent_list',$agent_list);
		$this->assign('purchase_list',$purchase_list);
		$this->assign('clear_list',$clear_list);
		$this->assign('pickup_list',$pickup_list);
		$this->assign('purchase_month_list',$purchase_month_list);
		$this->assign('clear_month_list',$clear_month_list);
		$this->assign('pickup_month_list',$pickup_month_list);
		$this->display('channel_sale');
	}
	

	/**
	 * @brief	导出功能操作
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/02/09 Ver 1.0
	 */
	public function export_excel() {
		$start_time = $this->uri->segment(4);
		$this->load->model('pickup_model');
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
		$export_data['message'] = '渠道销售统计列表';
		$export_data['create_time'] = time();
		$this->load->model('operation_log_model');
		$this->operation_log_model->addOperation($export_data);	
		/**获取各级代理的采购，出货，提货总额**/
		$chart_where = '1 = 1';
		$chart_where .= ' and mu.type = 2'; //采购数据
		$chart_where .= ' and mu.order_status = 3'; //状态采购完成
		$purchase_list = $this->purchase_model->getChartList($chart_where,'a.user_level, IFNULL(b.total_price, 0) AS total_price','mu.user_level');
		$clear_list = $this->purchase_model->getChartList($chart_where,'a.parent_level, IFNULL(b.total_price, 0) AS total_price','mu.parent_level','parent_level');
		$pick_where = '1 = 1';
		$pick_where .= ' and mu.order_status = 4'; //状态提货完成
		$pickup_list = $this->pickup_model->getChartList($pick_where,'a.user_level, IFNULL(b.total_price, 0) AS total_price','mu.user_level');
		$chart_where .= ' and mu.create_time >='.strtotime($start_time);
		$chart_where .= ' and mu.create_time <'.mktime(23, 59, 59, date('m',strtotime($start_time))+1, 00);//指定月份月末时间戳
		/**获取查询月份的各级代理的的采购，出货，提货总额**/
		$purchase_month_list = $this->purchase_model->getChartList($chart_where,'a.user_level, IFNULL(b.total_price, 0) AS total_price','mu.user_level');
		$clear_month_list = $this->purchase_model->getChartList($chart_where,'a.parent_level, IFNULL(b.total_price, 0) AS total_price','mu.parent_level','parent_level');
		$pick_where .= ' and mu.create_time >='.strtotime($start_time);
		$pick_where .= ' and mu.create_time <'.mktime(23, 59, 59, date('m',strtotime($start_time))+1, 00);//指定月份月末时间戳
		$pickup_month_list = $this->pickup_model->getChartList($pick_where,'a.user_level, IFNULL(b.total_price, 0) AS total_price','mu.user_level');	
		$agent_list = array('','一级代理','二级代理','三级代理','四级代理','五级代理');		
		header("Content-type: application/vnd.ms-excel; charset=utf8");
		header("Content-Disposition: attachment; filename=渠道销售统计列表.xls");
		$data = "<style>
					.price-td td {height:40px;border:1px solid #999;text-align: center;}
				</style>
				<table class='price-td' style='border:1px solid #ddd;margin-bottom:20px;' width='80%'>";
		$data .= "<tr>
					<td colspan='9'><b>渠道销售统计</b></td>
				</tr>";
		$data .= "<tr>
					<td></td><td colspan='4'><b>累积</b></td>
					<td colspan='4'><b>当月($start_time)</b></td>
				</tr>";
		$data .= "<tr>
					<td></td>
					<td><b>采购额</b></td>
					<td><b>发货额</b></td>
					<td><b>提货额</b></td>
					<td><b>合计销售额</b></td>
					<td><b>当月采购额</b></td>
					<td><b>当月发货额</b></td>
					<td><b>当月提货额</b></td>
					<td><b>当月合计销售额</b></td>
				</tr>";
		$purchase_sum = 0;
		$clear_sum = 0;
		$pickup_sum = 0;
		$purchase_month_sum = 0;
		$clear_month_sum = 0;
		$pickup_month_sum = 0;
		foreach($purchase_list as $key => $value) {
			$purchase_sum += $value['total_price'];
			$clear_sum += $clear_list[$key]['total_price'];
			$pickup_sum += $pickup_list[$key]['total_price'];
			$purchase_month_sum += $purchase_month_list[$key]['total_price'];
			$clear_month_sum += $clear_month_list[$key]['total_price'];
			$pickup_month_sum += $pickup_month_list[$key]['total_price'];
			$total_num = $clear_list[$key]['total_price']+$pickup_list[$key]['total_price'];
			$total_month_num = $clear_month_list[$key]['total_price']+$pickup_month_list[$key]['total_price'];
			
			$data .= '<tr>
						<td>'.$agent_list[$value['user_level']].'</td>
						<td><b>'.$value['total_price'].'</b></td>
						<td><b>'.$clear_list[$key]['total_price'].'</b></td>
						<td><b>'.$pickup_list[$key]['total_price'].'</b></td>
						<td><b>'.$total_num.'</b></td>
						<td><b>'.$purchase_month_list[$key]['total_price'].'</b></td>
						<td><b>'.$clear_month_list[$key]['total_price'].'</b></td>
						<td><b>'.$pickup_month_list[$key]['total_price'].'</b></td>
						<td><b>'.$total_month_num.'</b></td>	
					</tr>';	
		}
		$total_sale = $clear_sum+$pickup_sum;
		$total_month_sale = $clear_month_sum+$pickup_month_sum;
		$data .= "<tr>
					<td>五级代理</td><td colspan='8'>无采购及发货，不统计</td>
				</tr>";
		$data .= "<tr>
					<td>总计</td>
					<td><b>$purchase_sum</b></td>
					<td><b>$clear_sum</b></td>
					<td><b>$pickup_sum</b></td>
					<td><b>$total_sale</b></td>
					<td><b>$purchase_month_sum</b></td>
					<td><b>$clear_month_sum</b></td>
					<td><b>$pickup_month_sum</b></td>
					<td><b>$total_month_sale</b></td>
				</tr>";
		$data .= "</table>";
		echo $data. "\t";
		exit;		
	}
}
?>