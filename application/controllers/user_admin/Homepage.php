<?php
//全职任务
class Homepage extends PC_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('carousel_figure_model');
        $this->go_url = $this->data['admin_path']."/homepage/carousel_list";
        $this->data['authority'] = $this->authority;
    }

    //全职任务列表
    public function carousel_list() {
        if($this->uri->segment(4)) {
            $this->data['message'] = $this->uri->segment(4);
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->display('carousel_list');
    }
    //ajax获取管理员数据加载到列表
    public function ajax_carousel_list() {
        $base_url=$this->config->item('oss_path');
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(id) like'] = '%'.trim($search).'%';
        $where['is_deleted'] = 0;
        $this->data['count'] = $this->carousel_figure_model->getCount($where);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by='sort desc';
            $admin_list = $this->carousel_figure_model->getCarouselList($where,$length,$start,$order_by);
            foreach($admin_list as $key => &$value) {
                $value['img_url']=$this->default_img($base_url.$value['img_url']);
                $edit_url = $this->edit_url('homepage','edit_page',$value['id']);
                $del_url = $this->delete_url('/homepage/delete',$value['id'],'删除','btn-purple');
                $value['check'] = $this->get_check($value['id']);
                $value['operate'] = $edit_url.$del_url;
                if(!$value['operate']) {
                    $value['operate'] = '无操作';
                }
            }
            $aaData = $admin_list;
        }
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count'];
        $output['iTotalRecords'] = $this->data['count'];
        echo json_encode($output);
    }
    //删除
    public function delete() {
        $data = $_POST;
        $where['id'] = $data['id'];
        $data['is_deleted'] = 1; //删除
        $data['delete_time']=$this->time_retuen();
        $del_result = $this->carousel_figure_model->editCarousel($where,$data);
        echo json_encode($del_result);
    }
    //编辑展示
    public function edit_page() {
        $id = $this->uri->segment(4);
        $where['id'] = $id;
        $base_url=$this->config->item('oss_path');
        $info = $this->carousel_figure_model->checkCarousel($where);
        $info['img_url']=$base_url.$info['img_url'];
        $this->assign('data', $info);
        $this->display('carousel_edit');
    }
    //保存
    public function edit()
    {
        $data = $_POST;
        $where['id'] = $data['id'];
        /**上传图片**/
        if($_FILES['jietu']['tmp_name']) {
            $upload_image = $this->upload($_FILES,false,WEB_URL);
            $data['img_url'] = $upload_image;
        }else{
            $res=$this->carousel_figure_model->checkCarousel($where,'img_url');
            $data['img_url']=$res['img_url'];
        }
        $data['update_time']=$this->time_retuen();
        $info=$this->carousel_figure_model->editCarousel($where,$data);
        if($info){
            $this->location_href($this->go_url."/2");
        }else{
            $this->location_href($this->go_url."/3");
        }
    }
    //添加页面展示
    public function add_page() {
        $this->display('carousel_add');
    }
    //保存添加
    public function add(){
        $data = $_POST;
        /**上传图片**/
        if($_FILES['jietu']['tmp_name']) {
            $upload_image = $this->upload($_FILES,false,WEB_URL);
            $data['img_url'] = $upload_image;
        }
        //任务类型
        $data['create_time']=$this->time_retuen();
        $info=$this->carousel_figure_model->addCarousel($data);
        if($info){
            $this->location_href($this->go_url."/4");
        }else{
            $this->location_href($this->go_url."/5");
        }
    }

}