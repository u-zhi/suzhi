<?php
/**
 * User: Allen
 * Date: 16-12-21
 * 登陆日志控制器
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
class Loginlog extends PC_Controller {		
	public function __construct() {
		parent::__construct();
		$this->load->model('operation_log_model');
		$this->go_url = $this->data['admin_path']."/loginlog/loginlog_list";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	登陆日志列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function loginlog_list() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		$this->data['admin_name'] =  empty($_POST) ? '' : $_POST['admin_name'];
		$this->data['start_time'] =  empty($_POST) ? '' : $_POST['start_time'];
		$this->display('loginlog_list');
	}
	
	/**
	 * @brief	ajax获取登陆日志数据
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function ajax_loginlog_list() {
		$data = $_GET;
		/**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
		$start = $data['iDisplayStart'];
		$length = $data['iDisplayLength'];
		$sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
		$sort_type = $data['sSortDir_0'];
		$search = $data ['sSearch'];
		$where['concat(moyoo_operation_log.id) like'] = '%'.trim($search).'%';
		if($data['admin_name']) {
			$where['concat(moyoo_operation_log.admin_name) like'] = '%'.trim($data['admin_name']).'%';;
		}
		if($data['start_time']) {
			$where['create_time >=']  =  strtotime($data['start_time']);
			$where['create_time <']  =  strtotime($data['start_time']) + 86400;
		}
		$where['type'] = 1; //登陆操作
		$this->data['count'] = $this->operation_log_model->getCount($where);
		$aaData = array();
		if($this->data['count']) {			
			$order_by = $sort_th." ".$sort_type;
			$log_list = $this->operation_log_model->getOperationList($where,$length,$start,$order_by);
			foreach($log_list as $key => &$value) {	
				$value['create_time'] = date("Y-m-d H:i:s",$value['create_time']);
			}
			$aaData = $log_list;
		}
		$output['aaData'] = $aaData;
		$output['sEcho'] = $_GET['sEcho'];
		$output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
		$output['iTotalRecords'] = $this->data['count']; //总共有几条数据
		echo json_encode($output); //最后把数据以json格式返回
	}
}
?>