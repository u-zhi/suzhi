<?php
/**
 * User: Allen
 * Date: 16-12-21
 * 商品控制器
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
class Goods extends PC_Controller {	
	public function __construct() {
		parent::__construct();
		$this->load->model('goods_model');
		$this->go_url = $this->data['admin_path']."/goods/goods_list";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	商品列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function goods_list() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		$this->data['classify_id'] =  empty($_POST) ? '' : $_POST['classify_id'];
		$this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
		/**获取商品分类**/
		$this->load->model('classify_model');
		$classify_list = array();
		$classify_list = $this->classify_model->getClassifyAll();
		$this->data['classify_list'] = $classify_list;
		$this->display('goods_list');
	}
	
	/**
	 * @brief	ajax获取商品列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function ajax_goods_list() {
		$data = $_GET;
		/**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
		$start = $data['iDisplayStart'];
		$length = $data['iDisplayLength'];
		$sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
		$sort_type = $data['sSortDir_0'];
		$search = $data ['sSearch'];
		$where['concat(moyoo_goods.id) like'] = '%'.trim($search).'%';
		if($data['classify_id']) {
			$where['classify_id'] = $data['classify_id'];
		}	
		if($data['search_field']) {
				$where['concat(moyoo_goods.name) like'] = '%'.trim($data['search_field']).'%';
		}
		$where['status'] = 1; //正常数据
		$this->data['count'] = $this->goods_model->getCount($where);
		$aaData = array();
		if($this->data['count']) {
			$order_by = $sort_th." ".$sort_type;
			$goods_list = $this->goods_model->getGoodsList($where,$length,$start,$order_by,'goods.*,classify.name as classify_name');
			foreach($goods_list as $key => &$value) {
				$edit_url = $this->edit_url('goods','edit_page',$value['id']);
				$del_url = $this->delete_url('/goods/delete',$value['id'],'删除','btn-purple');
				$case_url = $this->edit_url('goods','add_case',$value['id'],'添加文案','btn-pink');
				$look_url = $this->edit_url('casus','casus_list/1',$value['id'],'查看文案','btn-success');
				$value['is_show'] = $this->tag_img($value['id'],'is_show',$value['is_show']);
				$value['operate'] = $edit_url.$del_url.$case_url.$look_url;
				if(!$value['operate']) {
					$value['operate'] = '无操作';
				}
			}
			$aaData = $goods_list;
		}
		$output['aaData'] = $aaData;
		$output['sEcho'] = $_GET['sEcho'];
		$output['iTotalDisplayRecords'] = $this->data['count']; //总共有几条数据
		$output['iTotalRecords'] = $this->data['count']; //总共有几条数据
		echo json_encode($output); //最后把数据以json格式返回
	}

	/**
	 * @brief	改变商品状态
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/22 Ver 1.0
	 */
	public function agree_up() {
		$data = $_POST;
		$where['id'] = $data['id'];
		$edit_data[$data['field']] = $data['val'];
		$edit_data['update_time'] = time();
		$edit_result = $this->goods_model->editGoods($where,$edit_data);
		echo json_encode($edit_result);
	}

	/**
	 * @brief	删除商品
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/22 Ver 1.0
	 */
	public function delete() {
		$data = $_POST;
		$where['id'] = $data['id'];
		$data['status'] = 2; //删除
		$del_result = $this->goods_model->editGoods($where,$data);
		echo json_encode($del_result);
	}

	/**
	 * @brief	编辑商品
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/22 Ver 1.0
	 */
	public function edit_page() {
		$id = $this->uri->segment(4);
		$where['id'] = $id;
		$classify_list = array();
		$goods_info = $this->goods_model->checkGoods($where);
		if($goods_info) {
			$goods_info['now_image'] = explode(";",$goods_info['roll_image']);
			$goods_info['now_image'] = array_filter($goods_info['now_image']);
			$goods_info['images'] = $goods_info['good_logo'];
			if(!$goods_info['good_logo']) {
				$goods_info['images'] = DEFAULT_IMAGE;
			}
		}
		$this->load->model('classify_model');
		$classify_list = $this->classify_model->getClassifyAll();
		$this->assign('classify_list', $classify_list);
		$this->assign('data', $goods_info);
		$this->display('goods_edit');
	}

	/**
	 * @brief	保存编辑页面
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/22 Ver 1.0
	 */
	public function edit() {
		$data = $_POST;
		/**上传图片**/
		if($_FILES['jietu']['tmp_name']) {
			$upload_image = $this->upload($_FILES,false,WEB_URL);
			$data['good_logo'] = $upload_image;
		}
		$where['id'] = $data['id'];
		$data['update_time'] = time();
		/**获取即将删除的图片**/
		$delete_image = $data['delete_image'];
		unset($data['delete_image']);
		$edit_result = $this->goods_model->editGoods($where,$data);
		if($edit_result) {
			/**删除图片**/
			$delete_image = explode(";",$delete_image);
			$delete_image = array_filter($delete_image);
			foreach($delete_image as $key => $value) {
				$value = str_replace(WEB_URL,"",$value);
				unlink(dirname(APPPATH).$value);
			}
			/**获取商品的信息**/
			$goods_info = $this->goods_model->checkGoods($where,'id as goods_id,name as goods_name,one_price,two_price,three_price,four_price,five_price,stock_num');
			$goods_data = $goods_info;
			/**获取信息存入到商品日志表中**/
			$login_info = $this->info;
			$admin = $login_info['admin'];
			$goods_data['admin_id'] = $admin['id'];
			$goods_data['admin_name'] = $admin['username'];
			$goods_data['admin_ip'] = $this->get_ip();
			$goods_data['action_type'] = '修改';
			$goods_data['method'] = $this->router->method; //当前方法
			$goods_data['controller'] = $this->router->class; //当前控制器
			$address = array();
			$address = $this->GetIpLookup();			
			if($address) {
				$goods_data['admin_address'] = $address['country'].$address['province'].$address['city'];
			}else {
				$goods_data['admin_address'] = '未分配或者内网IP';
			}			
			$goods_data['admin_level'] = $admin['role_name'];
		
			$goods_data['create_time'] = time();
			$this->load->model('goods_log_model');
			$this->goods_log_model->addGoods($goods_data);
			$this->location_href($this->go_url."/2");
		}else {
			$this->location_href($this->go_url."/3");
		}
	}

	/**
	 * @brief	生成唯一货号单
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/22 Ver 1.0
	 */
	public function orderSn() {
		$yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
		$orderSn = $yCode[intval(date('Y')) - 2011] .mt_rand(100000,999999);
		$check_order = $this->goods_model->checkReserve(array('order_num' => $orderSn));
		if($check_order) {
			$this->orderSn();
		}
		return $orderSn;
	}
	
	/**
	 * @brief	添加商品
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/22 Ver 1.0
	 */
	public function add_page() {
		$this->load->model('classify_model');	
		$classify_list = array();
		$classify_list = $this->classify_model->getClassifyAll();
		$this->assign('classify_list', $classify_list);
		$this->display('goods_add');
	}
	
	/**
	 * @brief 保存添加页面
	 * @param Null
	 * @par 2016/12/22 Ver 1.00 Created by Allen
	 */
	public function add() {
		$data = $_POST;
		//判断是否存在货号，无则自动生成
		if(empty($data['order_num'])) {
			$data['order_num'] = $this->orderSn();
		}
		/**上传图片**/
		if($_FILES['jietu']['tmp_name']) {
			$upload_image = $this->upload($_FILES,false,WEB_URL);
			$data['good_logo'] = $upload_image;
		}
		$data['create_time'] = $data['update_time'] = time();
		$add_result = $this->goods_model->addGoods($data);
		if($add_result) {
			/**获取商品的信息**/
			$where['id'] = $add_result;
			$goods_info = $this->goods_model->checkGoods($where,'id as goods_id,name as goods_name,one_price,two_price,three_price,four_price,five_price,stock_num');
			$goods_data = $goods_info;
			/**获取信息存入到商品日志表中**/
			$login_info = $this->info;
			$admin = $login_info['admin'];
			$goods_data['admin_id'] = $admin['id'];
			$goods_data['admin_name'] = $admin['username'];
			$goods_data['admin_ip'] = $this->get_ip();
			$goods_data['action_type'] = '新增';
			$goods_data['method'] = $this->router->method; //当前方法
			$goods_data['controller'] = $this->router->class; //当前控制器
			$address = array();
			$address = $this->GetIpLookup();	
			
			if($address) {
				$goods_data['admin_address'] = $address['country'].$address['province'].$address['city'];
			}else {
				$goods_data['admin_address'] = '未分配或者内网IP';
			}
			$goods_data['admin_level'] = $admin['role_name'];
			
			$goods_data['create_time'] = time();
			$this->load->model('goods_log_model');
			$this->goods_log_model->addGoods($goods_data);	
			$this->location_href($this->go_url."/4");
		}else {
			$this->location_href($this->go_url."/5");
		}
	}
	
	/**
	 * @brief 检查商品货号的唯一性
	 * @param Null
	 * @par 2016/06/14 Ver 1.00 Created by Allen
	 */
	public function check_sole() {
		$data = $_POST;
		if(isset($data['id'])) {
			$where['id !='] = $data['id'];
		}
		if(isset($data['order_num'])) {
			$where['order_num'] = $data['order_num'];
		}
		$sole = $this->goods_model->checkGoods($where);
		if($sole) {
			$message = false;
		}else {
			$message = true;
		}
		echo json_encode($message);
	}	

	/**
	 * @brief	上传图片
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/11/14 Ver 1.0
	 */
	public function fileupload() {
		$image = '';
		if($_FILES['jietu']['tmp_name']) {
			$image= $this->upload($_FILES,false,WEB_URL);
		}
		echo $image;
	}
	
	/**
	 * @brief	删除图片
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/11/14 Ver 1.0
	 */
	public function unlink() {
		$data = $_POST;	
		$data['delete_image'] = str_replace(WEB_URL,"",$data['delete_image']);		
		unlink(dirname(APPPATH).$data['delete_image']);

	}

	/**
	 * @brief	获取网站详细数据
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/23 Ver 1.0
	 */
	public function ajax_goods_info() {
		$id = $this->uri->segment(4);
		$where['goods.id'] = $id;
		$goods = $this->goods_model->getGoodsAll($where,'goods.*,classify.name as classify_name');
		$goods_info = $goods[0];
		if($goods_info['is_show'] == 1) {
			$goods_info['is_show'] = '下架';
		}else {
			$goods_info['is_show'] = '上架';
		}
		if(!$goods_info['good_logo']) {
			$goods_info['good_logo'] = DEFAULT_IMAGE;
		}
		$goods_info['now_image'] = explode(";",$goods_info['roll_image']);
		$roll_list = array();
		$roll_list = array_filter($goods_info['now_image']);
		$roll_str = '';
		foreach($roll_list as $key => $value) {
			if($key < 3) {
				$roll_str .= '<img src="'.$value.'" style="max-height:160px;padding-right:10px;">';
			}			
		}
		if(!$roll_str) {
			$roll_str = DEFAULT_IMAGE;
		}
		$str = '';
		$str .='<tr>
					<td>商品名称</td><td><b>'.$goods_info['name'].'</b></td>
					<td>商品货号</td><td><b>'.$goods_info['order_num'].'</b></td>
					<td>商品分类</td><td><b>'.$goods_info['classify_name'].'</b></td>
					<td>库存数量</td><td><b>'.$goods_info['stock_num'].'</b></td>
					<td>销售数量</td><td><b>'.$goods_info['sell_num'].'</b></td>
				</tr>
				<tr>			
					<td>采购基数</td><td><b>'.$goods_info['purchase_num'].'</b></td>
					<td>提货基数</td><td><b>'.$goods_info['pick_num'].'</b></td>				
					<td>每件运费</td><td><b>'.$goods_info['dc_price'].'</b></td>
					<td>上架状态</td><td><b>'.$goods_info['is_show'].'</b></td>
					<td>商品排序</td><td><b>'.$goods_info['goods_sort'].'</b></td>	
				</tr>
				<tr>			
					<td>一级代理价格</td><td><b>'.$goods_info['one_price'].'</b></td>
					<td>二级代理价格</td><td><b>'.$goods_info['two_price'].'</b></td>				
					<td>三级代理价格</td><td><b>'.$goods_info['three_price'].'</b></td>
					<td>四级代理价格</td><td><b>'.$goods_info['four_price'].'</b></td>
					<td>五级代理价格</td><td><b>'.$goods_info['five_price'].'</b></td>
				</tr>
				<tr>
					<td>封面图</td><td colspan="2"><img src="'.$goods_info['good_logo'].'" style="max-height:160px;"></td>
					<td>图册</td><td colspan="6">'.$roll_str.'</td>
				</tr>';
		$str = "<table  class='ajax_table' style='border:1px solid #ddd;margin-bottom:20px;' width='95%'>".$str."</table>";
		$json_data['str'] = $str;
		echo json_encode($json_data);
	}
	
	/**
	 * @brief	添加文案
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/22 Ver 1.0
	 */
	public function add_case() {
		$id = $this->uri->segment(4);
		$where['id'] = $id;
		$classify_list = array();
		$goods_info = $this->goods_model->checkGoods($where);
		$this->assign('data', $goods_info);
		$this->display('casus_add');
	}
	
	/**
	 * @brief	保存文案
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/22 Ver 1.0
	 */
	public function save_case() {
		$data = $_POST;
		$data['create_time'] = $data['update_time'] = time();
		$this->load->model('casus_model');
		$add_result = $this->casus_model->addCasus($data);
		if($add_result) {
			$this->location_href($this->go_url."/4");
		}else {
			$this->location_href($this->go_url."/5");
		}
	}
}
?>