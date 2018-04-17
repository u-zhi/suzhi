<?php
/**
 * User: Allen
 * Date: 17-02-05
 * 升级审核控制器
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
class Upgrade extends PC_Controller {		
	public function __construct() {
		parent::__construct();
		$this->load->model('user_model');
		$this->go_url = $this->data['admin_path']."/upgrade/upgrade_list";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	升级审核列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/02/05 Ver 1.0
	 */
	public function upgrade_list() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		$this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
		$this->display('upgrade_list');
	}
	
	/**
	 * @brief	ajax获取升级审核列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2017/02/05 Ver 1.0
	 */
	public function ajax_upgrade_list() {
		$data = $_GET;
		/**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
		$start = $data['iDisplayStart'];
		$length = $data['iDisplayLength'];
		$sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
		$sort_type = $data['sSortDir_0'];
		$search = $data ['sSearch'];
		$where['concat(moyoo_user.id) like'] = '%'.trim($search).'%';
		$where['user.is_upgrade'] = 2; //升级中
		$where['user.above_status'] = 2; //待审核
		$where['user.upgrade_level'] = 1; //申请升级等级
		if($data['search_field']) {
			$where['concat(moyoo_user.user_name,moyoo_user.phone) like'] = '%'.trim($data['search_field']).'%';;
		}
		$this->data['count'] = $this->user_model->getCount($where);
		$aaData = array();
		if($this->data['count']) {	
			$where['purchase.type'] = 3; //升级
			$where['purchase.order_status'] = 2; //待审核
			$order_by = $sort_th." ".$sort_type;
			$upgrade_list = $this->user_model->getAuditList($where,$length,$start,$order_by,'user.*,purchase.total_price');			
			foreach($upgrade_list as $key => &$value) {
				$value['upgrade_level'] = '一级代理';
				$look_url = $this->edit_url('upgrade','look_page',$value['id'],'查看','btn-pink');
				$value['operate'] = $look_url;
				if(!$value['operate']) {
					$value['operate'] = '无';
				}
			}
			$aaData = $upgrade_list;
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
	 * @since	2017/02/06 Ver 1.0
	 */
	public function edit() {
		$data = $_POST;
		$this->db->trans_begin();
		$where['id'] = $data['id'];
		$purchase_id = $data['purchase_id'];
		unset($data['purchase_id']);
		$edit_result = $this->user_model->editUser($where,$data);
		if($edit_result) {
			$user_info = $this->user_model->checkUser($where);
			if($user_info['above_status'] == 3 && $user_info['equal_status'] == 3) {
				/**更改采集订单状态**/
				$purchase_where['id'] = $purchase_id;
				$purchase_data['order_status'] = 3;
				$purchase_data['update_time'] = time();
				$this->load->model('purchase_model');
				$result = $this->purchase_model->editPurchase($purchase_where,$purchase_data);
				if($result) {
					/**获取parent_id的数据**/
					$parent_info = $this->user_model->checkUser(array('id' => $user_info['parent_id']));
					/**将用户状态更改为升级成功并升级成新的等级**/
					$new_parent_id = $parent_info['parent_id']; //新的上级id
					$old_level = $user_info['agent_level']; //当前等级
					$new_level = $user_info['upgrade_level']; //新等级
					/**新的数据组合**/
					$new_data['parent_id'] = $new_parent_id;
					$new_data['is_upgrade'] = 3;
					$new_data['agent_level'] = $new_level;
					$new_data['upgrade_level'] = 0;
					$new_data['one_id'] = $parent_info['one_id'];
					$new_data['two_id'] = $parent_info['two_id'];
					$new_data['three_id'] = $parent_info['three_id'];
					$new_data['four_id'] = $parent_info['four_id'];
					$up_result = $this->user_model->editUser($where,$new_data); //更改为升级成功后的数据
					if(!$up_result) {
						$this->db->trans_rollback();
					}else {
						/**将和此id有关的数据的上级关系更新**/
						$number_list = array('','one_id','two_id','three_id','four_id');
						$exchange_where[$number_list[$old_level]] =  $data['id'];
						$exchage_data[$number_list[$old_level]] = 0;
						$exchage_data[$number_list[$new_level]] =  $data['id'];
						$exchange_result = $this->user_model->editUser($exchange_where,$exchage_data);
						if($exchange_result) {
							/**成功后生成新的证书**/
							$new_info = $this->user_model->checkUser($where);
							$this->create_diploma(DIPLOMA_IMAGE,'./public_source/www/diploma/qrcode.png',$new_info);
							$this->db->trans_commit();							
						}else {
							$this->db->trans_rollback();
						}
					}		
				}else {
					$this->db->trans_rollback();
				}			
			}else if($user_info['above_status'] == 4 || $user_info['equal_status'] == 4) {
				/**更改采集订单状态**/
				$purchase_where['id'] = $purchase_id;
				$purchase_data['order_status'] = 4;
				$purchase_data['update_time'] = time();
				$this->load->model('purchase_model');
				$result = $this->purchase_model->editPurchase($purchase_where,$purchase_data);
				if($result) {
					/**将升级状态改为失败**/
					$new_data['is_upgrade'] = 4;
					$new_data['upgrade_level'] = 0;
					$up_result = $this->user_model->editUser($where,$new_data); //更改为升级成功后的数据
					if($up_result) {
						$this->db->trans_commit();
					}else {
						$this->db->trans_rollback();
					}
				}else {
					$this->db->trans_rollback();
				}		
			}else {
				$this->db->trans_commit();
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
	 * @since	2017/02/06 Ver 1.0
	 */
	public function look_page() {
		$id = $this->uri->segment(4);
		$where['user.id'] = $id;
		$where['user.above_status'] = 2; //升级上级审核
		$where['user.upgrade_level'] = 1; //升级一级代理
		$where['purchase.type'] = 3; //升级
		$where['purchase.order_status'] = 2; //升级待审核
		$goods_list = array();
		$upgrade_info = null;
		if(is_numeric($id)) {
			$upgrade_list = $this->user_model->getAuditList($where,1,0,'user.id desc','user.*,purchase.id as purchase_id,purchase.total_price,purchase.pay_image');
			if($upgrade_list) {
				$upgrade_info = $upgrade_list['0'];
			}
			/**获取订单的商品列表**/
			$this->load->model('purchase_details_model');
			$goods_where['appoint_id'] = $upgrade_info['purchase_id'];
			$goods_list = $this->purchase_details_model->getPurchaseAll($goods_where);
		}
		$this->check_rational($upgrade_info);
		$upgrade_info['level'] = '一级代理';
		$this->data['goods_list'] = $goods_list;
		$this->data['data'] = $upgrade_info;
		$this->display('upgrade_look');
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