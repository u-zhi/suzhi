<?php
/*开通城市*/
class City extends PC_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('open_city_model');
        $this->load->model('base_region_model');
        $this->load->helper('hash_helper');
        $this->go_url = $this->data['admin_path']."/city/city_list";
        $this->data['authority'] = $this->authority;
    }

    /**
     * @brief	分类列表
     * @param 	Null
     * @author	Allen
     * @since	2016/12/21 Ver 1.0
     */
    public function city_list() {
        if($this->uri->segment(4)) {
            $this->assign('message', $this->uri->segment(4));
        }
        $this->data['parent_id'] =  empty($_POST) ? '' : $_POST['parent_id'];
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $where['parent_id']=1;
        //获取所有一级分类
        $parent_list=$this->base_region_model->getRegionAll($where);
        $this->data['parent_list']=$parent_list;
        $this->display('city_list');
    }

    //专业列表
    public function ajax_city_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(id) like'] = '%'.trim($search).'%';
        if($data['search_field']) {
            $where['concat(sz_open_city.city_name) like'] = '%'.trim($data['search_field']).'%';;
        }
        if($data['parent_id']) {
            $where['sz_open_city.province_id'] = $data['parent_id'];
        }
        // var_dump($where);exit;
        $this->data['count'] = $this->open_city_model->getCount($where);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $occupation_list = $this->open_city_model->getOpenList($where,$length,$start,$order_by);
            foreach($occupation_list as $key => &$value) {
                $first=$this->base_region_model->checkRegion(array('region_id'=>$value['city_id']));
                $value['region_name_city']=$first['region_name'];
                $edit_url = $this->edit_url('city','edit_page',$value['id']);  
                $del_url = $this->delete_url('/city/delete',$value['id'],'删除','btn-purple');  
                $value['operate'] = $edit_url.$del_url;

            }
            $aaData = $occupation_list;
        }
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
        $output['iTotalRecords'] = $this->data['count']; //总共有几条数据
        echo json_encode($output); //最后把数据以json格式返回
    }

    /**
     * @brief   删除管理员数据
     * @param   Null
     * @author  Allen
     * @since   2016/07/12 Ver 1.0
     */
    public function delete() {
        $data = $_POST;
        $id = $_POST['id'];
        $where['id'] = $id; 
        $del_result = $this->open_city_model->deleteOpen($where);
        echo json_encode($del_result);
    }   
    
    /**
     * @brief   添加管理员
     * @param   Null
     * @author  Allen
     * @since   2016/07/12 Ver 1.0
     */
    public function add_page() {
        $where['parent_id']=1;
        //一级分类
        $parent_list=$this->base_region_model->getRegionAll($where);
        //二级分类
        $city_list = $this->base_region_model->getRegionAll();
        $city_arr=array();
        foreach($city_list as $value){
            $city_arr["province_".$value["parent_id"]][]=$value;
        }
        $this->data['parent_list'] = $parent_list;
        $this->data['city_list'] = $city_arr['province_'.$parent_list[0]['region_id']];
        $this->data['city_json'] = json_encode($city_arr);
        $this->display('city_add');
    }
    
    /**
     * @brief   保存添加管理员
     * @param   Null
     * @author  Allen
     * @since   2016/07/12 Ver 1.0
     */
    public function add() {
        $data = $_POST;
        // 获取城市名称
        $where['region_id']=$_POST["city_id"];
        $city_name=$this->base_region_model->getRegionAll($where);
        $data['city_name']=$city_name[0]['region_name'];
        $add_result = $this->open_city_model->addOpen($data);
        if($add_result) {
            $this->location_href($this->go_url."/4");
        }else {
            $this->location_href($this->go_url."/5");
        }
    }   
    /**
     * @brief   编辑页面
     */
    public function edit_page() {
        $id = $this->uri->segment(4);
        $whereone['id'] = $id;
        $occupation_info = $this->open_city_model->checkOpen($whereone);
        //获取所有一级分类
        $where['parent_id']=1;
        //一级分类
        $parent_list=$this->base_region_model->getRegionAll($where);
        //二级分类
        $city_list = $this->base_region_model->getRegionAll();
        $city_arr=array();
        foreach($city_list as $value){
            $city_arr["province_".$value["parent_id"]][]=$value;
        }
        $this->data['parent_list'] = $parent_list;
        //城市列表改为选中的省
        $this->data['city_list'] = $city_arr['province_'.$occupation_info['province_id']];
        $this->data['city_json'] = json_encode($city_arr);
        $this->data['parent_list']=$parent_list;
        $this->assign('data', $occupation_info);
        $this->display('city_edit');
    }
    /**
     * @brief   保存编辑信息
     */
    public function edit() {
        $data = $_POST;
        $where['id'] = $data['id'];
        $where_city['region_id']=$_POST["city_id"];
        $city_name=$this->base_region_model->getRegionAll($where_city);
        $data['city_name']=$city_name[0]['region_name'];
        $edit_result = $this->open_city_model->editOpen($where,$data);
        if($edit_result) {
            $this->location_href($this->go_url."/2");
        }else {
            $this->location_href($this->go_url."/3");
        }
    }
}
?>