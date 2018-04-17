<?php
/**
 * User: Allen
 * Date: 16-12-21
 * 分类控制器
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
class Classify extends PC_Controller {		
	public function __construct() {
		parent::__construct();
		$this->load->model('classify_model');
		$this->go_url = $this->data['admin_path']."/classify/classify_list";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	分类列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function classify_list() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		$this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
		$this->display('classify_list');
	}
	
	/**
	 * @brief	ajax获取分类列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function ajax_classify_list() {
		$data = $_GET;
		/**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
		$start = $data['iDisplayStart'];
		$length = $data['iDisplayLength'];
		$sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
		$sort_type = $data['sSortDir_0'];
		$search = $data ['sSearch'];
		$where['concat(moyoo_classify.id) like'] = '%'.trim($search).'%';
		if($data['search_field']) {
			$where['concat(moyoo_classify.id,moyoo_classify.name) like'] = '%'.trim($data['search_field']).'%';;
		}
		$this->data['count'] = $this->classify_model->getCount($where);
		$aaData = array();
		if($this->data['count']) {			
			$order_by = $sort_th." ".$sort_type;
			$classify_list = $this->classify_model->getClassifyList($where,$length,$start,$group_by="classify.id",$order_by,'classify.*,count(moyoo_goods.classify_id) as goods_num');
			foreach($classify_list as $key => &$value) {
				$value['check'] = $this->get_check($value['id']);
				$edit_url = $this->edit_url('classify','edit_page',$value['id']);
				$del_url = '';
				if($value['goods_num'] == 0) {
					$del_url = $this->delete_url('/classify/delete',$value['id'],'删除','btn-purple');
				}
				$value['operate'] = $edit_url.$del_url;
				if(!$value['operate']) {
					$value['operate'] = '无';
				}else {
					$value['operate'] = $edit_url." ".$del_url;
				}
			}
			$aaData = $classify_list;
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
	 * @since	2016/12/21 Ver 1.0
	 */
	public function delete() {
		$data = $_POST;
		$id = $_POST['id'];
		$where['id'] = $id;
		$del_result = $this->classify_model->deleteClassify($where);
		echo json_encode($del_result);
	}
	
	/**
	 * @brief	进入添加页面
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function add_page() {
		$this->display('classify_add');
	}
	
	/**
	 * @brief	检查分类姓名唯一性
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function check_sole() {
		$data = $_POST;
		if(isset($data['id'])) {
			$where['id !='] = $data['id'];
		}
		$where['name'] = $data['name'];
		$sole = $this->classify_model->checkClassify($where);
		if($sole) {
			$message = false;
		}else {
			$message = true;
		}
		echo json_encode($message);
	}
	
	/**
	 * @brief	保存添加信息
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function add() {
		$data = $_POST;
		$data['create_time'] = $data['update_time'] = time();
		$add_result = $this->classify_model->addClassify($data);
		if($add_result) {
			$this->location_href($this->go_url."/4");
		}else {
			$this->location_href($this->go_url."/5");
		}
	}
	
	/**
	 * @brief	编辑页面
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function edit_page() {
		$id = $this->uri->segment(4);
		$where['id'] = $id;
		$classify_info = $this->classify_model->checkClassify($where);
		$this->assign('data', $classify_info);
		$this->display('classify_edit');
	}
	
	/**
	 * @brief	保存编辑信息
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/21 Ver 1.0
	 */
	public function edit() {
		$data = $_POST;
		$data['update_time'] = time();
		$where['id'] = $data['id'];
		$edit_result = $this->classify_model->editClassify($where,$data);
		if($edit_result) {
			$this->location_href($this->go_url."/2");
		}else {
			$this->location_href($this->go_url."/3");
		}
	}
}
?>