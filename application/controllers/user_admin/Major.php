<?php

class Major extends PC_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('base_major_model');
        $this->go_url = $this->data['admin_path']."/major/major_list";
        $this->data['authority'] = $this->authority;
    }

    /**
     * @brief	分类列表
     * @param 	Null
     * @author	Allen
     * @since	2016/12/21 Ver 1.0
     */
    public function major_list() {
        if($this->uri->segment(4)) {
            $this->assign('message', $this->uri->segment(4));
        }
        $this->data['parent_id'] =  empty($_POST) ? '' : $_POST['parent_id'];
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $where['parent_id']=0;
        //获取所有一级分类
        $parent_list=$this->base_major_model->getBaseAll($where);
        $this->data['parent_list']=$parent_list;
        $this->display('major_list');
    }

    //专业列表
    public function ajax_major_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(id) like'] = '%'.trim($search).'%';
        if($data['search_field']) {
            $where['concat(item) like'] = '%'.trim($data['search_field']).'%';;
        }
        if($data['parent_id']) {
            $where['parent_id'] = $data['parent_id'];
        }
        $where['parent_id !=']=0;
        $this->data['count'] = $this->base_major_model->getCount($where);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $major_list = $this->base_major_model->getBaseList($where,$length,$start,$order_by);
            foreach($major_list as $key => &$value) {
                $first=$this->base_major_model->checkBase(array('id'=>$value['parent_id']));
                $value['first_name']=$first['item'];
                $edit_url = $this->edit_url('major','edit_page',$value['id'],'查看','btn-success');
                $value['check'] = $this->get_check($value['id']);
                $value['operate'] = $edit_url;
                if(!$value['operate']) {
                    $value['operate'] = '无操作';
                }
            }
            $aaData = $major_list;
        }
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
        $output['iTotalRecords'] = $this->data['count']; //总共有几条数据
        echo json_encode($output); //最后把数据以json格式返回
    }
    /**
     * @brief	进入一级添加页面
     */
    public function add_pages() {

        $this->display('major_adds');
    }


    /**
     * @brief	保存一级添加信息
     */
    public function adds() {
        $data = $_POST;
        $data['parent_id']=0;
        $add_result = $this->base_major_model->addBase($data);
        if($add_result) {
            $this->location_href($this->go_url."/4");
        }else {
            $this->location_href($this->go_url."/5");
        }
    }


    /**
     * @brief	进入二级添加页面
     */
    public function add_page() {
        $where['parent_id']=0;
        //获取所有一级分类
        $parent_list=$this->base_major_model->getBaseAll($where);
        $this->data['parent_list']=$parent_list;
        $this->display('major_add');
    }


    /**
     * @brief	保存二级添加信息
     */
    public function add() {
        $data = $_POST;
        $add_result = $this->base_major_model->addBase($data);
        if($add_result) {
            $this->location_href($this->go_url."/4");
        }else {
            $this->location_href($this->go_url."/5");
        }
    }

    /**
     * @brief	编辑页面
     */
    public function edit_page() {
        $id = $this->uri->segment(4);
        $where['id'] = $id;
        $major_info = $this->base_major_model->checkBase($where);
        //获取所有一级分类
        $parent_list=$this->base_major_model->getBaseAll(array('parent_id'=>0));
        $this->data['parent_list']=$parent_list;
        $this->assign('data', $major_info);
        $this->display('major_edit');
    }

    /**
     * @brief	保存编辑信息
     */
    public function edit() {
        $data = $_POST;
        $where['id'] = $data['id'];
        $edit_result = $this->base_major_model->editBase($where,$data);
        if($edit_result) {
            $this->location_href($this->go_url."/2");
        }else {
            $this->location_href($this->go_url."/3");
        }
    }
}
?>