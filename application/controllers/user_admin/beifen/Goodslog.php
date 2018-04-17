<?php
/**
 * User: Allen
 * Date: 16-12-23
 * 商品日志控制器
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
class Goodslog extends PC_Controller {		
	public function __construct() {
		parent::__construct();
		$this->load->model('goods_log_model');
		$this->go_url = $this->data['admin_path']."/goodslog/goodslog_list";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	 商品日志列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function goodslog_list() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		$this->data['admin_name'] =  empty($_POST) ? '' : $_POST['admin_name'];
		$this->data['start_time'] =  empty($_POST) ? '' : $_POST['start_time'];
		$this->display('goodslog_list');
	}
	
	/**
	 * @brief	ajax获取登陆日志数据
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function ajax_goodslog_list() {
		$data = $_GET;
		/**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
		$start = $data['iDisplayStart'];
		$length = $data['iDisplayLength'];
		$sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
		$sort_type = $data['sSortDir_0'];
		$search = $data ['sSearch'];
		$where['concat(moyoo_goods_log.id) like'] = '%'.trim($search).'%';
		if($data['admin_name']) {
			$where['concat(moyoo_goods_log.admin_name,moyoo_goods_log.goods_name) like'] = '%'.trim($data['admin_name']).'%';;
		}
		if($data['start_time']) {
			$where['create_time >='] = strtotime($data['start_time']);
			$where['create_time <'] = strtotime($data['start_time']) + 86400;
		}
		$this->data['count'] = $this->goods_log_model->getCount($where);
		$aaData = array();
		if($this->data['count']) {			
			$order_by = $sort_th." ".$sort_type;
			$log_list = $this->goods_log_model->getGoodsList($where,$length,$start,$order_by);
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
	
	/**
	 * @brief	获取价格详细数据
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/23 Ver 1.0
	 */
	public function ajax_goodslog_info() {
		$id = $this->uri->segment(4);
		$where['id'] = $id;
		$goods_info = $this->goods_log_model->checkGoods($where);
		$str = '';
		$str .='<tr>
					<td>一级代理价格</td><td><b>'.$goods_info['one_price'].'</b></td>
					<td>二级代理价格</td><td><b>'.$goods_info['two_price'].'</b></td>
					<td>三级代理价格</td><td><b>'.$goods_info['three_price'].'</b></td>
					<td>四级代理价格</td><td><b>'.$goods_info['four_price'].'</b></td>
					<td>五级代理价格</td><td><b>'.$goods_info['five_price'].'</b></td>
				</tr>';
		$str = "<table  class='ajax_table' style='border:1px solid #ddd;margin-bottom:20px;' width='95%'>".$str."</table>";
		$json_data['str'] = $str;
		echo json_encode($json_data);
	}
}
?>