<?php
//实习任务
class Enterprise extends PC_Controller {
    public function __construct() {
        parent::__construct();
        /*企业外包*/
        $this->load->model('company_task_outsourcing_model');
        /*职业信息*/
        $this->load->model('base_major_model');
        /*通知*/
        $this->load->model('user_message_model');
        // 公司名称
        $this->load->model('firm_profile_model');
        // 短信发送
        $this->load->model('sms_log_model');
        $this->go_url = $this->data['admin_path']."/enterprise/enterprise_outsourcing_change";
        // $this->go_url_cooperation = $this->data['admin_path']."/enterprise/cooperation";
        $this->data['authority'] = $this->authority;
    }
    
     /*企业外包需求待反馈*/
    public function enterprise_outsourcing_change() {
        if($this->uri->segment(4)) {
            $this->data['message'] = $this->uri->segment(4);
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->display('enterprise_outsourcing_change_list');

    }    
    /*企业外包需求待反馈*/
    public function ajax_enterprise_outsourcing_change_list() {
        $base_url=$this->config->item('oss_path');
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_company_task_outsourcing.id) like'] = '%'.trim($search).'%';
        /*状态为1表示准备报价还未合作*/
        $where['sz_company_task_outsourcing.status'] = 1;
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
                // $edit_url = $this->edit_url('enterprise','enterprise_outsourcing_edit',$value['id'],'详情','btn-blue');
                $show_url = $this->edit_url('enterprise','enterprise_outsourcing_pay',$value['id'],'报价(详情)','btn-yellow');
                $value['check'] = $this->get_check($value['id']);
                $value['operate']=/*$edit_url.*/$show_url;
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

    } /*企业外包需求合作项目*/
    public function ajax_enterprise_outsourcing_change_list2() {
        $base_url=$this->config->item('oss_path');
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_company_task_outsourcing.id) like'] = '%'.trim($search).'%';
        /*状态为1表示准备报价还未合作*/
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
                // $edit_url = $this->edit_url('enterprise','enterprise_outsourcing_edit',$value['id'],'详情','btn-blue');
                $show_url = $this->edit_url('enterprise','enterprise_outsourcing_pay',$value['id'],'报价(详情)','btn-yellow');
                $value['check'] = $this->get_check($value['id']);
                $value['operate']=/*$edit_url.*/$show_url;
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
    /*企业外包需求待反馈详情*/
    public function enterprise_outsourcing_edit() {
        $data = $_POST;
        $where['id'] = $data['id'];
        $enterpriseedit_one = $this->company_task_outsourcing_model->getCompany_task_outsourcingOne($where);
        if($enterpriseedit_one['status']!=1){
            $this->location_href($this->go_url."/13");
        }
        $data2['status']=3;
        $data2['offer_money']=($data['offer_money']*100);
        $enterpriseedit = $this->company_task_outsourcing_model->editCompany_task_outsourcing($where,$data2);
        if($enterpriseedit) {
            $this->location_href($this->go_url."/12");
        }else {
            $this->location_href($this->go_url."/13");
        }
    }     

    /*企业外包需求报价*/
    public function enterprise_outsourcing_pay() {
        $id = $this->uri->segment(4);
        $where['id'] = $id;
        $enterprise_outsourcing_one = $this->company_task_outsourcing_model->getCompany_task_outsourcingOne($where);
        $this->assign('data', $enterprise_outsourcing_one);
        // 短信发送及通知

        //加入队列等待发送
        $company=$this->firm_profile_model->checkFirm(array('id'=>$enterprise_outsourcing_one['company_id']));
        // $sms_data=$this->config->item("sms_data");
        $sms_data=array($enterprise_outsourcing_one['name']);
        $sms_add=$this->sms_log_model->add(array(
                "mobile"=>$company['phone_number'],
                "template_id"=>205029,
                "params"=>json_encode($sms_data),
                "create_time"=>date("Y-m-d H:i:s")
            ));
        $messge_add=$this->user_message_model->addMessage(array(
                    'user_id'=>$id,
                    'user_type'=>1,
                    'create_time'=>$this->time_retuen(),
                    'content'=>"您发起的项目".$enterprise_outsourcing_one['name'].",速职已经报价反馈,请注意查看",
                    ));
        if($sms_add&&$sms_add){
            $this->display('enterprise_outsourcing_edit');
        }

    }    



}