<?php
/**
 * User: Allen
 * Date: 17-02-10
 * 公司销售统计控制器
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
class Chart_goods extends PC_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('goods_model');
		$this->go_url = $this->data['admin_path']."/chart_goods/goods_sale";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	公司销售统计列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/02/10 Ver 1.0
	 */
	public function goods_sale() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		$now_month = date("Y-m",time());
		$this->data['classify_id'] =  empty($_POST) ? '' : $_POST['classify_id'];
		$this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
		$this->data['start_time'] =  empty($_POST) ? $now_month : $_POST['start_time'];
		/**获取商品分类**/
		$this->load->model('classify_model');
		$classify_list = array();
		$classify_list = $this->classify_model->getClassifyAll();
		$this->data['classify_list'] = $classify_list;
		$this->display('sale_list');
	}
	
	/**
	 * @brief	ajax获取公司销售统计列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function ajax_sale_list() {
		$data = $_GET;
		/**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
		$start = $data['iDisplayStart'];
		$length = $data['iDisplayLength'];
		$sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
		$sort_type = $data['sSortDir_0'];
		$search = $data ['sSearch'];
		$where = '1 = 1';
		if($data['classify_id']) {
			$where .= ' and fy.id = '.$data['classify_id'];
		}	
		if($data['search_field']) {
			$where .= ' and ds.name like \'%'.trim($data['search_field']).'%\'';
		}
		$aaData = array();
		$order_by = $sort_th." ".$sort_type;
		$start_time = strtotime($data['start_time']);
		$end_time = mktime(23, 59, 59, date('m',strtotime($data['start_time']))+1, 00);//指定月份月末时间戳
		$sale_list = $this->goods_model->getChartList($where,$order_by,$start,$length,$start_time,$end_time);
		if($sale_list) {
			$aaData = $sale_list;
		}
		$this->data['count'] = count($aaData);
		$output['aaData'] = $aaData;
		$output['sEcho'] = $_GET['sEcho'];
		$output['iTotalDisplayRecords'] = $this->data['count']; //总共有几条数据
		$output['iTotalRecords'] = $this->data['count']; //总共有几条数据
		echo json_encode($output); //最后把数据以json格式返回
	}

}
?>