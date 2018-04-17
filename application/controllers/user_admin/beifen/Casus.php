<?php
/**
 * User: Allen
 * Date: 16-12-22
 * 文案控制器
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
class Casus extends PC_Controller {		
	public function __construct() {
		parent::__construct();
		$this->load->model('casus_model');
		$this->go_url = $this->data['admin_path']."/casus/casus_list";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	案例列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/22 Ver 1.0
	 */
	public function casus_list() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		if($this->uri->segment(5)) {
			$this->assign('goods_id', $this->uri->segment(5));
			$this->load->model('goods_model');
			$goods_info = $this->goods_model->checkGoods(array('id' => $this->uri->segment(5)));
			$this->assign('goods_name', $goods_info['name'].'的');
		}else {
			$this->assign('goods_id', '0');
			$this->assign('goods_name', '');
		}
		$this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
		$this->display('casus_list');
	}
	
	/**
	 * @brief	ajax获取案例列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/22 Ver 1.0
	 */
	public function ajax_casus_list() {
		$data = $_GET;
		/**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
		$start = $data['iDisplayStart'];
		$length = $data['iDisplayLength'];
		$sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
		$sort_type = $data['sSortDir_0'];
		$search = $data ['sSearch'];
		$where['concat(moyoo_casus.id) like'] = '%'.trim($search).'%';
		if($data['search_field']) {
			$where['concat(moyoo_casus.id,moyoo_casus.title) like'] = '%'.trim($data['search_field']).'%';;
		}
		if($data['goods_id']) {
			$where['moyoo_casus.goods_id'] = $data['goods_id'];
		}
		$this->data['count'] = $this->casus_model->getCount($where);
		$aaData = array();
		if($this->data['count']) {			
			$order_by = $sort_th." ".$sort_type;
			$casus_list = $this->casus_model->getCasusList($where,$length,$start,$order_by,'casus.*,goods.name');
			foreach($casus_list as $key => &$value) {
				$value['check'] = $this->get_check($value['id']);
				$edit_url = $this->edit_url('casus','edit_page',$value['id']);
				$del_url = $this->delete_url('/casus/delete',$value['id'],'删除','btn-purple');
				$value['operate'] = $edit_url.$del_url;
				if(!$value['operate']) {
					$value['operate'] = '无';
				}else {
					$value['operate'] = $edit_url." ".$del_url;
				}
			}
			$aaData = $casus_list;
		}
		$output['aaData'] = $aaData;
		$output['sEcho'] = $_GET['sEcho'];
		$output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
		$output['iTotalRecords'] = $this->data['count']; //总共有几条数据
		echo json_encode($output); //最后把数据以json格式返回
	}
	
	/**
	 * @brief	删除信息
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/22 Ver 1.0
	 */
	public function delete() {
		$data = $_POST;
		$id = $data['id'];
		$where['id'] = $id;
		$del_result = $this->casus_model->deleteCasus($where);
		echo json_encode($del_result);
	}
		
	
	/**
	 * @brief	编辑页面
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/22 Ver 1.0
	 */
	public function edit_page() {
		$id = $this->uri->segment(4);
		$where['moyoo_casus.id'] = $id;
		$casus_info = array();
		$casus = $this->casus_model->getCasusList($where,'1','0','moyoo_casus.id desc','casus.*,goods.name');
		if($casus) {
			$casus_info = $casus[0];
			$casus_info['now_image'] = explode(";",$casus_info['roll_image']);
			$casus_info['now_image'] = array_filter($casus_info['now_image']);
		}		
		$this->assign('data', $casus_info);
		$this->display('casus_edit');
	}
	
	/**
	 * @brief	保存编辑页面
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/22 Ver 1.0
	 */
	public function edit() {
		$data = $_POST;
		$where['id'] = $data['id'];
		$data['update_time'] = time();
		/**获取即将删除的图片**/
		$delete_image = $data['delete_image'];
		unset($data['delete_image']);
		$edit_result = $this->casus_model->editCasus($where,$data);
		if($edit_result) {
			/**删除图片**/
			$delete_image = explode(";",$delete_image);
			$delete_image = array_filter($delete_image);
			foreach($delete_image as $key => $value) {
				$value = str_replace(WEB_URL,"",$value);
				unlink(dirname(APPPATH).$value);			
			}
			$this->location_href($this->go_url."/2");
		}else {
			$this->location_href($this->go_url."/3");
		}
	}
}
?>