<?php
/**
 * User: Allen
 * Date: 16-12-23
 * 文章控制器
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
class News extends PC_Controller {		
	public function __construct() {
		parent::__construct();
		$this->load->model('news_model');
		$this->go_url = $this->data['admin_path']."/news/news_list";
		$this->data['authority'] = $this->authority;
	}
	
	/**
	 * @brief	文章列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/24 Ver 1.0
	 */
	public function news_list() {
		if($this->uri->segment(4)) {
			$this->assign('message', $this->uri->segment(4));
		}
		$this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
		$this->display('news_list');
	}
	
	/**
	 * @brief	ajax获取文章列表
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/24 Ver 1.0
	 */
	public function ajax_news_list() {
		$data = $_GET;
		/**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
		$start = $data['iDisplayStart'];
		$length = $data['iDisplayLength'];
		$sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
		$sort_type = $data['sSortDir_0'];
		$search = $data ['sSearch'];
		$where['concat(moyoo_news.id) like'] = '%'.trim($search).'%';
		if($data['search_field']) {
			$where['concat(moyoo_news.id,moyoo_news.title) like'] = '%'.trim($data['search_field']).'%';;
		}
		$this->data['count'] = $this->news_model->getCount($where);
		$aaData = array();
		if($this->data['count']) {			
			$order_by = $sort_th." ".$sort_type;
			$news_list = $this->news_model->getNewsList($where,$length,$start,$order_by);
			foreach($news_list as $key => &$value) {
				$value['check'] = $this->get_check($value['id']);
				$value['update_time'] = date("Y-m-d H:i:s",$value['update_time']);
				$edit_url = $this->edit_url('news','edit_page',$value['id']);
				$del_url = $this->delete_url('/news/delete',$value['id'],'删除','btn-purple');
				$value['operate'] = $edit_url.$del_url;
				if(!$value['operate']) {
					$value['operate'] = '无';
				}else {
					$value['operate'] = $edit_url." ".$del_url;
				}
			}
			$aaData = $news_list;
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
	 * @since	2016/12/24 Ver 1.0
	 */
	public function delete() {
		$data = $_POST;
		$id = $data['id'];
		$where['id'] = $id;
		$del_result = $this->news_model->deleteNews($where);
		echo json_encode($del_result);
	}
	
	/**
	 * @brief	进入添加页面
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/24 Ver 1.0
	 */
	public function add_page() {
		$this->display('news_add');
	}
	
	/**
	 * @brief	保存添加信息
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/24 Ver 1.0
	 */
	public function add() {
		$data = $_POST;
		$data['create_time'] = $data['update_time'] = time();
		$add_result = $this->news_model->addNews($data);
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
	 * @since	2016/12/24 Ver 1.0
	 */
	public function edit_page() {
		$id = $this->uri->segment(4);
		$where['id'] = $id;
		$news_info = $this->news_model->checkNews($where);
		$this->assign('data', $news_info);
		$this->display('news_edit');
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
		$edit_result = $this->news_model->editNews($where,$data);
		if($edit_result) {
			$this->location_href($this->go_url."/2");
		}else {
			$this->location_href($this->go_url."/3");
		}
	}
}
?>