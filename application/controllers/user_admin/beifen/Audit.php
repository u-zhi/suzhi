<?php
/**
 * User: Allen
 * Date: 17-01-03
 * 一级代理审核控制器
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
class Audit extends PC_Controller {		
	public function __construct() {
		parent::__construct();
		$this->load->model('user_model');
		$this->go_url = $this->data['admin_path']."/audit/audit_list";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	代理审核列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/03 Ver 1.0
	 */
	public function audit_list() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		$this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
		$this->display('audit_list');
	}
	
	/**
	 * @brief	ajax获取代理审核列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/03 Ver 1.0
	 */
	public function ajax_audit_list() {
		$data = $_GET;
		/**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
		$start = $data['iDisplayStart'];
		$length = $data['iDisplayLength'];
		$sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
		$sort_type = $data['sSortDir_0'];
		$search = $data ['sSearch'];
		$where['concat(moyoo_user.id) like'] = '%'.trim($search).'%';
		$where['user.parent_status'] = 2; //上级审核
		$where['user.agent_level'] = 1; //一级代理
		$where['user.is_status'] = 1; //正常数据
		if($data['search_field']) {
			$where['concat(moyoo_user.user_name,moyoo_user.phone) like'] = '%'.trim($data['search_field']).'%';;
		}
		$this->data['count'] = $this->user_model->getCount($where);
		$aaData = array();
		if($this->data['count']) {	
			$where['purchase.type'] = 1; //开户
			$where['purchase.order_status'] = 2; //待审核
			$order_by = $sort_th." ".$sort_type;
			$audit_list = $this->user_model->getAuditList($where,$length,$start,$order_by,'user.*,purchase.total_price');
			foreach($audit_list as $key => &$value) {
				$value['agent_level'] = '一级代理';
				$look_url = $this->edit_url('audit','look_page',$value['id'],'查看','btn-pink');
				$value['operate'] = $look_url;
				if(!$value['operate']) {
					$value['operate'] = '无';
				}
			}
			$aaData = $audit_list;
		}
		$output['aaData'] = $aaData;
		$output['sEcho'] = $_GET['sEcho'];
		$output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
		$output['iTotalRecords'] = $this->data['count']; //总共有几条数据
		echo json_encode($output); //最后把数据以json格式返回
	}
	
	/**
	 * @brief	保存编辑信息
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/03 Ver 1.0
	 */
	public function edit() {
		$data = $_POST;
		$this->db->trans_begin();
		$where['id'] = $data['id'];
		$purchase_id = $data['purchase_id'];
		unset($data['purchase_id']);
		$edit_result = $this->user_model->editUser($where,$data);
		if($edit_result) {
			/**获取订单的商品信息**/
			$this->load->model('purchase_details_model');
			$goods_where['appoint_id'] = $purchase_id;
			$goods_list = $this->purchase_details_model->getPurchaseAll($goods_where);
			$user_info = $this->user_model->checkUser($where);
			if($user_info['parent_status'] == 3 && $user_info['invite_status'] == 3) {
				/**更改采集订单状态**/
				$purchase_where['id'] = $purchase_id;
				$purchase_data['order_status'] = 3;
				$purchase_data['update_time'] = time();
				$this->load->model('purchase_model');
				$result = $this->purchase_model->editPurchase($purchase_where,$purchase_data);
				if($result) {
					/**查看库存表中是否有采购人此商品的数据，没有创建，有则将库存加上**/
					$this->load->model('stock_model');
					foreach($goods_list as $key => $value) {
						$stock_info = $this->stock_model->checkStock(array('user_id' => $user_info['id'],'goods_id' => $value['goods_id']));
						if($stock_info) {
							$stock_where['user_id'] = $user_info['id'];
							$stock_where['goods_id'] = $value['goods_id'];
							$this->stock_model->addId($stock_where,'goods_stock',$value['num']);
						}else {
							$stock_data['user_id'] = $user_info['id'];
							$stock_data['goods_id'] = $value['goods_id'];
							$stock_data['goods_stock'] = $value['num'];
							$stock_data['create_time'] = $stock_data['update_time'] = time();
							$this->stock_model->addStock($stock_data);
						}
					}
					/**成功后生成新的证书**/
					$this->create_diploma(DIPLOMA_IMAGE,'./public_source/www/diploma/qrcode.png', $user_info);				
					$this->db->trans_commit();
				}else {
					$this->db->trans_rollback();
				}			
			}else if($user_info['parent_status'] == 4 || $user_info['invite_status'] == 4) {
				/**更改采集订单状态**/
				$purchase_where['id'] = $purchase_id;
				$purchase_data['order_status'] = 4;
				$purchase_data['update_time'] = time();
				$this->load->model('purchase_model');
				$result = $this->purchase_model->editPurchase($purchase_where,$purchase_data);
				if($result) {
					/**取消采购单，将预支扣除的库存原路返回。如果上级为公司则返回到goods表，否则则返回到stock表**/
					if($user_info['parent_id'] == 0) {
						$this->load->model('goods_model');
						foreach($goods_list as $key => $value) {
							$temp_where['id'] = $value['goods_id'];
							$this->goods_model->addId($temp_where,'stock_num',$value['num']);
						}
					}else {
						$this->load->model('stock_model');
						foreach($goods_list as $key => $value) {
							$stock_info = $this->stock_model->checkStock(array('user_id' => $user_info['parent_id'],'goods_id' => $value['goods_id']));
							if($stock_info) {
								$where['user_id'] = $user_info['parent_id'];
								$where['goods_id'] = $value['goods_id'];
								$this->stock_model->addId($where,'goods_stock',$value['num']);
							}else {
								$stock_data['user_id'] = $user_info['parent_id'];
								$stock_data['goods_id'] = $value['goods_id'];
								$stock_data['goods_stock'] = $value['num'];
								$stock_data['create_time'] = $stock_data['update_time'] = time();
								$this->stock_model->addStock($stock_data);
							}
						}
					}
					$this->db->trans_commit();
				}else {
					$this->db->trans_rollback();
				}		
			}
			$this->location_href($this->go_url."/2");
		}else {
			$this->db->trans_rollback();
			$this->location_href($this->go_url."/3");
		}
	}

	/**
	 * @brief	查看信息
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/03 Ver 1.0
	 */
	public function look_page() {
		$id = $this->uri->segment(4);
		$where['user.id'] = $id;
		$where['user.parent_status'] = 2; //上级审核
		$where['user.agent_level'] = 1; //一级代理
		$where['purchase.type'] = 1; //开户
		$where['purchase.order_status'] = 2; //开户待审核
		$goods_list = array();
		$audit_info = null;
		if(is_numeric($id)) {
			$audit_list = $this->user_model->getAuditList($where,1,0,'user.id desc','user.*,purchase.id as purchase_id,purchase.total_price,purchase.pay_image');
			if($audit_list) {
				$audit_info = $audit_list['0'];
			/**获取订单的商品列表**/
			$this->load->model('purchase_details_model');
			$goods_where['appoint_id'] = $audit_info['purchase_id'];
			$goods_list = $this->purchase_details_model->getPurchaseAll($goods_where);
			}
		}
		$this->check_rational($audit_info);
		$audit_info['level'] = '一级代理';
		$this->data['goods_list'] = $goods_list;
		$this->data['data'] = $audit_info;
		$this->display('audit_look');
	}
	
	/**
	 * @brief	代理审核失败列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/03 Ver 1.0
	 */
	public function fail_list() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		$this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
		$this->display('fail_list');
	}

	/**
	 * @brief	ajax获取代理审核失败列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/03 Ver 1.0
	 */
	public function ajax_fail_list() {
		$data = $_GET;
		/**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
		$start = $data['iDisplayStart'];
		$length = $data['iDisplayLength'];
		$sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
		$sort_type = $data['sSortDir_0'];
		$search = $data ['sSearch'];
		$where['concat(moyoo_user.id) like'] = '%'.trim($search).'%';
		$where['user.parent_status'] = 4; //上级审核失败
		$where['user.agent_level'] = 1; //一级代理
		if($data['search_field']) {
			$where['concat(moyoo_user.user_name,moyoo_user.phone) like'] = '%'.trim($data['search_field']).'%';;
		}
		$this->data['count'] = $this->user_model->getCount($where);
		$aaData = array();
		if($this->data['count']) {
			$where['purchase.type !='] = 2; //开户,升级
			$where['purchase.order_status'] = 4; //取消订单
			$order_by = $sort_th." ".$sort_type;
			$fail_list = $this->user_model->getAuditList($where,$length,$start,$order_by,'user.*,purchase.total_price');
			foreach($fail_list as $key => &$value) {
				$value['agent_level'] = '一级代理';
				$value['user_status'] = '未通过';
			}
			$aaData = $fail_list;
		}
		$output['aaData'] = $aaData;
		$output['sEcho'] = $_GET['sEcho'];
		$output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
		$output['iTotalRecords'] = $this->data['count']; //总共有几条数据
		echo json_encode($output); //最后把数据以json格式返回
	}
	
}
?>