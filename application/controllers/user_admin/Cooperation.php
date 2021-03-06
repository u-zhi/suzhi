<?php
//实习任务
class Cooperation extends PC_Controller {
    public function __construct() {
        parent::__construct();
        /*企业外包*/
        $this->load->model('company_task_outsourcing_model');
        /*职业信息*/
        $this->load->model('base_major_model');
        // 公司名称
        $this->load->model('firm_profile_model');
        
        $this->go_url = $this->data['admin_path']."/enterprise/enterprise_outsourcing_change";
        $this->go_url_cooperation = $this->data['admin_path']."/cooperation/cooperation";
        $this->data['authority'] = $this->authority;
    }

    /*合作项目*/
    public function cooperation() {
        if($this->uri->segment(4)) {
            $this->data['message'] = $this->uri->segment(4);
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->display('cooperation_list');

    }        

    /*合作项目*/
    public function ajax_cooperation_list() {
        $base_url=$this->config->item('oss_path');
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_company_task_outsourcing.id) like'] = '%'.trim($search).'%';
        /*状态为2表示报价完且在合作*/
        $where['sz_company_task_outsourcing.status'] = 4;
        if(isset($data['search_field']) && $data['search_field']){
            $where['sz_firm_profile.name like'] = '%'.trim($data['search_field']).'%';
        }
        $this->data['count'] = $this->company_task_outsourcing_model->getCount($where,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = 'company_task_outsourcing.id desc';
            $admin_list = $this->company_task_outsourcing_model->getCompany_task_outsourcingList($where,$length,$start,$order_by,'company_task_outsourcing.*,firm_profile.name',true);
            foreach($admin_list as $key => &$value) {
                //获取企业名称
                $company=$this->firm_profile_model->checkFirm(array('id'=>$value['company_id']));
                $value['company_name']=$company['name'];
                /*模式*/
                    //获取教育经历
                if($value['education_type'] == 1){
                    $value['education_type']='小学';
                }elseif ($value['education_type'] == 2){
                    $value['education_type']='初中';
                }elseif ($value['education_type'] == 3){
                    $value['education_type']='高中';
                }elseif ($value['education_type'] == 4){
                    $value['education_type']='大专';
                }elseif ($value['education_type'] == 5){
                    $value['education_type']='本科学士';
                }elseif ($value['education_type'] == 6){
                    $value['education_type']='硕士';
                }elseif ($value['education_type'] == 7){
                    $value['education_type']='博士';
                }else{
                    $value['education_type']='博士后';
                }
                $value['model']='面试---选项待定';
                if($value["has_number"]<$value['person_demand']){
                    $show_url = $this->edit_url('cooperation','cooperation_one',$value['id'],'完成一人','btn-yellow');
                }else{
                    $show_url = $this->edit_url('#','#',$value['id'],'亲已经结束','btn-yellow');
                }

                $value['check'] = $this->get_check($value['id']);
                $value['operate']=$show_url;
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

    /*合作项目完成一人*/
    public function cooperation_one() {
        $id = $this->uri->segment(4);
        $where['id'] = $id;
        $outsourcing=$this->company_task_outsourcing_model->getCompany_task_outsourcingOne($where);
        if($outsourcing['status']!=4){
            $this->location_href($this->go_url_cooperation."/11");
        }
        $data['has_number']=$outsourcing['has_number']+1;
        $edit_result = $this->company_task_outsourcing_model->editCompany_task_outsourcing($where,$data);
        if($edit_result) {
            $this->location_href($this->go_url_cooperation."/10");
        }else {
            $this->location_href($this->go_url_cooperation."/11");
        }



    }    

    /*合作项目人才面试*/
    // public function cooperation_jober() {

    // }





}