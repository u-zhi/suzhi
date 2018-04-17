<?php
/**
 * User: Allen
 * Date: 17-01-13
 * 采购单控制器
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
class Purchase extends PC_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('purchase_model');
		$this->go_url = $this->data['admin_path']."/purchase/purchase_list";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	采购单列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/13 Ver 1.0
	 */
	public function purchase_list() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		$this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];	
		$this->data['order_status'] =  empty($_POST) ? '0' : $_POST['order_status'];
		$order_list = array('全部状态','待付款','待审核','已完成','已取消');
		$this->assign('order_list',$order_list);
		$this->display('purchase_list');
	}
	
	/**
	 * @brief	ajax获取采购单列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/13 Ver 1.0
	 */
	public function ajax_purchase_list() {
		$data = $_GET;
		/**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
		$start = $data['iDisplayStart'];
		$length = $data['iDisplayLength'];
		$sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
		$sort_type = $data['sSortDir_0'];
		$search = $data ['sSearch'];
		$where['concat(moyoo_purchase.id) like'] = '%'.trim($search).'%';	
		if($data['search_field']) {
			$where['concat(moyoo_purchase.order_id) like'] = '%'.trim($data['search_field']).'%';
		}
		if($data['order_status']) {
			$where['purchase.order_status'] = $data['order_status'];
		}
		$where['moyoo_purchase.parent_id'] = 0; //上级id为公司
		$where['moyoo_purchase.type'] = 2; //类型为采购
		$this->data['count'] = $this->purchase_model->getCount($where);
		$aaData = array();
		if($this->data['count']) {
			$order_by = $sort_th." ".$sort_type;
			$purchase_list = $this->purchase_model->getPurchaseList($where,$length,$start,$order_by,'purchase.*,user.user_name,user.phone,temp.user_name as parent_name,temp.phone as parent_phone');
			$order_list = array('','待付款','待审核','已完成','已取消');
			$this->load->model('admin_model');
			$company_where['id'] = 1;
			$admin_info = array();
			$admin_info = $this->admin_model->checkAdmin($company_where);
			foreach($purchase_list as $key => &$value) {
				$look_url = $this->edit_url('purchase','look_page',$value['id'],'查看','btn-success');	
				$edit_url = '';
				if($value['order_status'] == 2) {
					$edit_url = $this->edit_url('purchase','edit_page',$value['id']);
				}							
				$value['order_status'] = $order_list[$value['order_status']];
				if($value['parent_id'] == 0) {
					$value['parent_name'] = $admin_info['company_name'];
					$value['parent_phone'] = $admin_info['contact_num'];
				}
				$value['operate'] = $look_url.$edit_url;				
				if(!$value['operate']) {
					$value['operate'] = '无操作';
				}
			}
			$aaData = $purchase_list;
		}
		$output['aaData'] = $aaData;
		$output['sEcho'] = $_GET['sEcho'];
		$output['iTotalDisplayRecords'] = $this->data['count']; //总共有几条数据
		$output['iTotalRecords'] = $this->data['count']; //总共有几条数据
		echo json_encode($output); //最后把数据以json格式返回
	}

	/**
	 * @brief	查看信息
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/13 Ver 1.0
	 */
	public function look_page() {
		$id = $this->uri->segment(4);
		$purchase_info = null;
		$goods_list = array();
		if(is_numeric($id)) {
			$where['purchase.id'] = $id;
			$purchase_list = $this->purchase_model->getPurchaseList($where,1,0,'purchase.id desc','purchase.*,user.agent_level,user.user_name,user.phone,temp.user_name as parent_name,temp.phone as parent_phone,temp.agent_level as temp_level');				
			if($purchase_list) {
				$purchase_info =  $purchase_list['0'];
				$level_list = array('','一级代理','二级代理','三级代理','四级代理','五级代理');
				$order_list = array('','待付款','待审核','已完成','已取消');
				$purchase_info['agent_level'] = $level_list[$purchase_info['agent_level']];
				$purchase_info['order_status'] = $order_list[$purchase_info['order_status']];
				/**如果parent_id为0 获取公司信息**/
				$this->load->model('admin_model');
				$company_where['id'] = 1;
				$admin_info = array();
				$admin_info = $this->admin_model->checkAdmin($company_where);
				if($purchase_info['parent_id'] == 0) {
					$purchase_info['parent_name'] = $admin_info['company_name'];
					$purchase_info['parent_phone'] = $admin_info['contact_num'];
					$purchase_info['temp_level'] = '公司';
				}else {
					$purchase_info['temp_level'] = $level_list[$purchase_info['temp_level']];
				}
				/**获取订单的商品列表**/
				$this->load->model('purchase_details_model');
				$goods_where['appoint_id'] = $purchase_info['id'];
				$goods_list = $this->purchase_details_model->getPurchaseAll($goods_where);				
			}		
		}
		$this->check_rational($purchase_info);
		$this->assign('data',$purchase_info);
		$this->assign('goods_list',$goods_list);
		$this->display('purchase_look');
	}
	
	/**
	 * @brief	编辑信息
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/13 Ver 1.0
	 */
	public function edit_page() {
		$id = $this->uri->segment(4);
		$purchase_info = null;
		$goods_list = array();
		if(is_numeric($id)) {
			$where['purchase.id'] = $id;
			$where['purchase.order_status'] = 2;
			$purchase_list = $this->purchase_model->getPurchaseList($where,1,0,'purchase.id desc','purchase.*,user.agent_level,user.user_name,user.phone,temp.user_name as parent_name,temp.phone as parent_phone,temp.agent_level as temp_level');				
			if($purchase_list) {
				$purchase_info =  $purchase_list['0'];
				$level_list = array('','一级代理','二级代理','三级代理','四级代理','五级代理');
				$purchase_info['agent_level'] = $level_list[$purchase_info['agent_level']];
				/**如果parent_id为0 获取公司信息**/
				$this->load->model('admin_model');
				$company_where['id'] = 1;
				$admin_info = array();
				$admin_info = $this->admin_model->checkAdmin($company_where);
				if($purchase_info['parent_id'] == 0) {
					$purchase_info['parent_name'] = $admin_info['company_name'];
					$purchase_info['parent_phone'] = $admin_info['contact_num'];
					$purchase_info['temp_level'] = '公司';
				}else {
					$purchase_info['temp_level'] = $level_list[$purchase_info['temp_level']];
				}
				/**获取订单的商品列表**/
				$this->load->model('purchase_details_model');
				$goods_where['appoint_id'] = $purchase_info['id'];
				$goods_list = $this->purchase_details_model->getPurchaseAll($goods_where);			
			}		
		}
		$this->check_rational($purchase_info);
		$this->assign('data',$purchase_info);
		$this->assign('goods_list',$goods_list);
		$this->display('purchase_edit');
	}

	/**
	 * @brief	保存编辑页面
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/01/13 Ver 1.0
	 */
	public function edit() {
		$data = $_POST;
		$this->db->trans_begin(); //开启事务
		/**更改采购订单的状态**/
		$purchase_where['id'] = $data['id'];
		$purchase_data['order_status'] = $data['order_status'];
		$edit_result = $this->purchase_model->editPurchase($purchase_where,$purchase_data);
		if($edit_result) {
			$purchase_info = $this->purchase_model->checkPurchase(array('id' => $data['id']));
			$goods_list = array();
			$this->load->model('purchase_details_model');
			$goods_where['appoint_id'] = $purchase_info['id'];
			$goods_list = $this->purchase_details_model->getPurchaseAll($goods_where);
			/**如果状态改为已完成给采购人加库存商品**/
			if($data['order_status'] == 3) {
				/**查看库存表中是否有采购人此商品的数据，没有创建，有则将库存加上**/
				$this->load->model('stock_model');
				foreach($goods_list as $key => $value) {
					$stock_info = $this->stock_model->checkStock(array('user_id' => $purchase_info['user_id'],'goods_id' => $value['goods_id']));
					if($stock_info) {
						$stock_where['user_id'] = $purchase_info['user_id'];
						$stock_where['goods_id'] = $value['goods_id'];
						$this->stock_model->addId($stock_where,'goods_stock',$value['num']);
					}else {
						$stock_data['user_id'] = $purchase_info['user_id'];
						$stock_data['goods_id'] = $value['goods_id'];
						$stock_data['goods_stock'] = $value['num'];
						$stock_data['create_time'] = $stock_data['update_time'] = time();
						$this->stock_model->addStock($stock_data);
					}
				}
				$this->db->trans_commit();
				$this->location_href($this->go_url."/2");
			}else if($data['order_status'] == 4) {
				/**取消采购单，将预支扣除的库存原路返回。如果上级为公司则返回到goods表，否则则返回到stock表**/
				if($purchase_info['parent_id'] == 0) {
					$this->load->model('goods_model');
					foreach($goods_list as $key => $value) {
						$temp_where['id'] = $value['goods_id'];
						$this->goods_model->addId($temp_where,'stock_num',$value['num']);	
					}
				}else {
					$this->load->model('stock_model');
					foreach($goods_list as $key => $value) {
						$stock_info = $this->stock_model->checkStock(array('user_id' => $purchase_info['parent_id'],'goods_id' => $value['goods_id']));
						if($stock_info) {
							$where['user_id'] = $purchase_info['parent_id'];
							$where['goods_id'] = $value['goods_id'];
							$this->stock_model->addId($where,'goods_stock',$value['num']);
						}else {
							$stock_data['user_id'] = $purchase_info['parent_id'];
							$stock_data['goods_id'] = $value['goods_id'];
							$stock_data['goods_stock'] = $value['num'];
							$stock_data['create_time'] = $stock_data['update_time'] = time();
							$this->stock_model->addStock($stock_data);
						}
					}
				}
				$this->db->trans_commit();
				$this->location_href($this->go_url."/2");			
			}else {
				$this->db->trans_rollback();
				$this->location_href($this->go_url."/3");			
			}
		}else {
			$this->db->trans_rollback();
			$this->location_href($this->go_url."/3");
		}
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
		$export_list = $this->purchase_model->getPurchaseAll(array('purchase.parent_id' => '0','purchase.type' => '2'),'purchase.*,user.user_name,user.phone,temp.user_name as parent_name,temp.phone as parent_phone');
		$order_list = array('','待付款','待审核','已完成','已取消');
		$this->load->model('admin_model');
		$company_where['id'] = 1;
		$admin_info = array();
		$admin_info = $this->admin_model->checkAdmin($company_where);
		foreach($export_list as $key => &$value) {
			$value['order_status'] = $order_list[$value['order_status']];
			if($value['parent_id'] == 0) {
				$value['parent_name'] = $admin_info['company_name'];
				$value['parent_phone'] = $admin_info['contact_num'];
			}
		}
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
		$export_data['message'] = '采购单列表';
		$export_data['create_time'] = time();
		$this->load->model('operation_log_model');
		$this->operation_log_model->addOperation($export_data);
		foreach ($export_list as $key => &$value) {
			$order_export[] = array(
					$value['id'],$value['order_id'],$value['user_name'],$value['phone'],$value['parent_name'],$value['parent_phone'],
					$value['total_price'],$value['order_status']
			);
		}
		$arr_name = array('id','订单号','采购人','采购人电话','上级姓名','上级手机号','订单总价','订单状态');
		export_data_excel($arr_name, $order_export, '采购单列表');
	}
}
?>