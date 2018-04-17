<?php

class Order extends PC_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('headhunter_order_profile_model');
        $this->load->model('jobhunter_order_profile_model');
        $this->load->model('jobhunter_order_statements_model');
        $this->load->model('jobhunter_interview_model');
         $this->load->model('jobhunter_work_model');
        $this->load->model('base_county_model');
        $this->load->model('firm_profile_model');
        $this->load->model('base_occupation_model');
        $this->load->model('user_profile_model');
        $this->load->model('user_balance_model');
        $this->load->model('balance_statements_model');
        $this->load->model('talent_pool_model');

        /*订单列表*/
        $this->load->model('task_profile_model');

        $this->go_url = $this->data['admin_path']."/order/receive_list";
        $this->gos_url = $this->data['admin_path']."/order/enroll_list";
        $this->going_url = $this->data['admin_path']."/order/parttime_list";
        $this->wait_url = $this->data['admin_path']."/order/wait_list";
        $this->work_url = $this->data['admin_path']."/order/wait_work_list";
        $this->recruit_url = $this->data['admin_path']."/order/recruit_list";
        $this->complete_url = $this->data['admin_path']."/order/complete_list";
        $this->problem_url = $this->data['admin_path']."/order/problem_list";
        $this->data['authority'] = $this->authority;
    }
    /*------------------------------------------猎头任务开始-----------------------------------------------------------*/
    /**
     * @brief	已领取列表
     */
    public function receive_list() {
        if($this->uri->segment(4)) {
            $this->assign('message', $this->uri->segment(4));
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->display('receive_list');
    }

    //列表
    public function ajax_receive_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_headhunter_order_profile.id) like'] = '%'.trim($search).'%';
        if($data['search_field']) {
            $where['sz_user_profile.phone_number like '. '\'%'.trim($data['search_field']).'%\''.' or sz_task_profile.name like  '] = '%'.trim($data['search_field']).'%';
        }
        // $where['task_profile.is_deleted']=0;
        $where['sz_headhunter_order_profile.is_cancled']=1;
        // $where['user_profile.is_hunter']=2;
        $this->data['count'] = $this->headhunter_order_profile_model->getCount($where,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = "sz_headhunter_order_profile.id desc";
            $order_list = $this->headhunter_order_profile_model->getOrderList($where,$length,$start,$order_by,'headhunter_order_profile.id,headhunter_order_profile.user_id,headhunter_order_profile.is_cancled,task_profile.name,task_profile.task_type,task_profile.work_time,task_profile.salary,task_profile.commission,task_profile.is_off_shelved,user_profile.nickname,user_profile.phone_number',true);
            foreach($order_list as $key => &$value) {
                    if($value['is_cancled'] == 0){
                        $order_list[$key]['is_cancled']='已领取';
                    }else{
                        $order_list[$key]['is_cancled']='已取消';
                    }

                    if($value['task_type'] == 1){
                        $order_list[$key]['task_type']='简历';
                    }elseif ($value['task_type'] == 2){
                        $order_list[$key]['task_type']='到岗';
                    }else{
                        $order_list[$key]['task_type']='入职';
                    }

                    if($value['is_off_shelved'] == 0){
                        $order_list[$key]['is_off_shelved']='上架';
                    }else{
                        $order_list[$key]['is_off_shelved']='下架';
                    }

                    // 求职进程
                    // jobhunter_order_profile_model
                    $wherejoborder['user_id']=$value['user_id'];
                    $jobhunter_one=$this->jobhunter_order_profile_model->getOrderOne($wherejoborder);
                    $current_status=$this->config->item("current_status");
                    $value['current_status']=$current_status[$jobhunter_one['current_status']];
                    $value['commission']=($value['commission']/100)."元";

                $edit_url = $this->edit_url('order','edit_page',$value['id'],'查看','btn-success');
                $value['check'] = $this->get_check($value['id']);
                $value['operate'] = $edit_url;
                if(!$value['operate']) {
                    $value['operate'] = '无操作';
                }
            }
            $aaData = $order_list;
        }
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
        $output['iTotalRecords'] = $this->data['count']; //总共有几条数据
        echo json_encode($output); //最后把数据以json格式返回
    }

    /**
     * @brief	猎头任务的查看
     */
    public function edit_page() {
        $base_url=$this->config->item('oss_path');
        $id = $this->uri->segment(4);
        $where['headhunter_order_profile.id'] = $id;
        $order_info = $this->headhunter_order_profile_model->checkOrder($where,'*',true);
        //获取区域信息
        $quyu=$this->base_county_model->checkBase(array('id'=>$order_info['county_id']));
        $order_info['county_name']=$quyu['county_name'];
        //获取公司信息
        $company=$this->firm_profile_model->checkFirm(array('id'=>$order_info['firm_id']));
        $order_info['firm_name']=$company['name'];
        //获取职位信息
        $oppucation=$this->base_occupation_model->checkBase(array('id'=>$order_info['firm_id']));
        $order_info['occupation_name']=$oppucation['item'];
        $order_info['view_times']=(int)$order_info['view_times'] + (int)$order_info['recv_cv_times'];
        $order_info['fake_view_times']=(int)$order_info['fake_view_times'] + (int)$order_info['fake_recv_cv_times'];
        $order_info['image_url']=$base_url.$order_info['image_url'];
        $this->assign('data', $order_info);
        $this->display('order_edit');
    }

    /*------------------------------------------猎头任务结束-----------------------------------------------------------*/
    /*------------------------------------------求职者任务开始---------------------------------------------------------*/
    /**
     * @brief	已报名列表(全职和实习)
     */
    public function enroll_list() {
        if($this->uri->segment(4)) {
            $this->assign('message', $this->uri->segment(4));
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->data['hunter_user_id'] =  empty($_POST) ? '' : $_POST['hunter_user_id'];
        $this->data['job_user_id'] =  empty($_POST) ? '' : $_POST['job_user_id'];
        $this->display('enroll_list');
    }
    //列表
    public function ajax_enroll_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_jobhunter_order_profile.id) like'] = '%'.trim($search).'%';
//        $where['task_profile.is_deleted']=0;
//        $where['user_profile.is_deleted']=0;
//        $where['task_profile.task_type = 1 or task_profile.task_type = ']=2;
//        $where['jobhunter_order_profile.is_problem']=0;
        // $where =" task_profile.is_deleted = 0";
        // $where .=" and user_profile.is_seeker = 1";
        // $where .=" and user_profile.is_deleted = 0";
        // $where .=" and (task_profile.task_type = 1 or task_profile.task_type = 2)";
        // $where .=" and jobhunter_order_profile.is_problem = 0";
        // $where .=" and jobhunter_order_profile.current_status = 0";
        if($data['search_field']) {
            $where .=' and (sz_jobhunter_order_profile.trade_no like '. '\'%'.trim($data['search_field']).'%\''.' or sz_user_profile.phone_number like '. '\'%'.trim($data['search_field']).'%\''.' or sz_task_profile.name like  '.'\'%'.trim($data['search_field']).'%\''.')';
        }
        $this->data['count'] = $this->jobhunter_order_profile_model->getCount($where,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = "sz_jobhunter_order_profile.id desc";
            $order_list = $this->jobhunter_order_profile_model->getOrderList($where,$length,$start,$order_by,'jobhunter_order_profile.id,jobhunter_order_profile.trade_no,jobhunter_order_profile.headhunter_task_id,jobhunter_order_profile.add_type,jobhunter_order_profile.current_status,jobhunter_order_profile.user_id,task_profile.name,task_profile.task_type,task_profile.work_time,task_profile.salary,user_profile.nickname,user_profile.phone_number',true);
            foreach($order_list as $key => &$value) {
                if($value['headhunter_task_id']){
                    $renwu=$this->headhunter_order_profile_model->checkOrder(array('id'=>$value['headhunter_task_id']));
                    $user=$this->user_profile_model->checkProfile(array('id'=>$renwu['user_id']));
                    $order_list[$key]['phone']=$user['phone_number'];
                    $order_list[$key]['nicheng']=$user['nickname'];
                }else{
                    $order_list[$key]['phone']='87211611';
                    $order_list[$key]['nicheng']='杭州巨光网络科技';
                }
                if($value['task_type'] == 1){
                    $order_list[$key]['task_type']='简历';
                }elseif ($value['task_type'] == 2){
                    $order_list[$key]['task_type']='到岗';
                }else{
                    $order_list[$key]['task_type']='入职';
                }                

                if($value['add_type'] == 1){
                    $order_list[$key]['add_type']='内推';
                }elseif ($value['add_type'] == 2){
                    $order_list[$key]['add_type']='顾问';
                }else{
                    $order_list[$key]['add_type']='自主投递';
                }
                $current_status=$this->config->item("current_status");
                $value['current_status']=$current_status[$value['current_status']];

                $edit_url = $this->edit_url('order','edit_pages',$value['id'],'招聘信息','btn-success');
                $show_url = $this->edit_url('jobhunter','show_resume',$value['user_id'],'求职者简历','btn-yellow');
//                $modify_url = $this->edit_url('order','modify_status',$value['id'],'修改订单状态','btn-purple');
                $value['check'] = $this->get_check($value['id']);
//                $value['operate'] = $edit_url.$show_url.$modify_url;
                $value['operate'] = $edit_url.$show_url;
                if(!$value['operate']) {
                    $value['operate'] = '无操作';
                }
            }
            $aaData = $order_list;
        }
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
        $output['iTotalRecords'] = $this->data['count']; //总共有几条数据
        echo json_encode($output); //最后把数据以json格式返回
    }
    /**
     * @brief	求职者任务的查看
     */
    public function edit_pages() {
        $base_url=$this->config->item('oss_path');
        $id = $this->uri->segment(4);
        $where['jobhunter_order_profile.id'] = $id;
        $order_info = $this->jobhunter_order_profile_model->checkOrder($where,'jobhunter_order_profile.id as order_id,jobhunter_order_profile.*,task_profile.*,user_profile.*',true);
        //获取区域信息
        $quyu=$this->base_county_model->checkBase(array('id'=>$order_info['county_id']));
        $order_info['county_name']=$quyu['county_name'];
        //获取公司信息
        $company=$this->firm_profile_model->checkFirm(array('id'=>$order_info['firm_id']));
        $order_info['firm_name']=$company['name'];
        //获取职位信息
        $oppucation=$this->base_occupation_model->checkBase(array('id'=>$order_info['firm_id']));
        $order_info['occupation_name']=$oppucation['item'];
        $order_info['view_times']=(int)$order_info['view_times'] + (int)$order_info['recv_cv_times'];
        $order_info['fake_view_times']=(int)$order_info['fake_view_times'] + (int)$order_info['fake_recv_cv_times'];
        $order_info['image_url']=$base_url.$order_info['image_url'];
        if($order_info['headhunter_task_id']){
            $renwu=$this->headhunter_order_profile_model->checkOrder(array('id'=>$order_info['headhunter_task_id']));
            $user=$this->user_profile_model->checkProfile(array('id'=>$renwu['user_id']));
            $order_info['phone']=$user['phone_number'];
            $order_info['nicheng']=$user['nickname'];
        }else{
            $order_info['phone']='87211611';
            $order_info['nicheng']='杭州巨光网络科技';
        }

        if($order_info['current_status'] == 0){
            $order_info['current_status']='求职者报名';
        }elseif($order_info['current_status'] == 1){
            $order_info['current_status']='企业发送面试邀请';
        }elseif($order_info['current_status'] == 2){
            $order_info['current_status']='企业已录取';
        }elseif($order_info['current_status'] == 3){
            $order_info['current_status']='完成-佣金解冻';
        }elseif($order_info['current_status'] == 4){
            $order_info['current_status']='3-10天企业辞退75%的佣金';
        }elseif($order_info['current_status'] == 5){
            $order_info['current_status']='3-10天求职者辞职25%的佣金';
        }elseif($order_info['current_status'] == 6){
            $order_info['current_status']='企业查看不通过';
        }elseif($order_info['current_status'] == 7){
            $order_info['current_status']='企业面试不通过';
        }elseif($order_info['current_status'] == 8){
            $order_info['current_status']='已完工';
        }else{
            $order_info['current_status']='未面试';
        }
        $this->assign('data', $order_info);
        $this->display('order_edits');
    }
    /**
     * @brief	已报名列表(兼职)
     */
    public function parttime_list() {
        if($this->uri->segment(4)) {
            $this->assign('message', $this->uri->segment(4));
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->display('parttime_list');
    }
    //列表
    public function ajax_parttime_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_jobhunter_order_profile.id) like'] = '%'.trim($search).'%';
//        $where['task_profile.is_deleted']=0;
//        $where['user_profile.is_deleted']=0;
//        $where['task_profile.task_type']=0;
//        $where['jobhunter_order_profile.is_problem']=0;
        $where = " task_profile.is_deleted = 0";
        $where .= " and user_profile.is_deleted = 0";
        $where .= " and task_profile.task_type = 0";
        $where .= " and jobhunter_order_profile.is_problem = 0";
        $where .=" and jobhunter_order_profile.current_status = 0";
        if($data['search_field']) {
            $where .=' and (sz_jobhunter_order_profile.trade_no like '. '\'%'.trim($data['search_field']).'%\''.' or sz_user_profile.phone_number like '. '\'%'.trim($data['search_field']).'%\''.' or sz_task_profile.name like  '.'\'%'.trim($data['search_field']).'%\''.')';
        }
        $this->data['count'] = $this->jobhunter_order_profile_model->getCount($where,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = "sz_jobhunter_order_profile.id desc";
            $order_list = $this->jobhunter_order_profile_model->getOrderList($where,$length,$start,$order_by,'jobhunter_order_profile.id,jobhunter_order_profile.trade_no,jobhunter_order_profile.headhunter_task_id,jobhunter_order_profile.current_status,jobhunter_order_profile.user_id,task_profile.name,task_profile.task_type,task_profile.work_time,task_profile.salary,user_profile.nickname,user_profile.phone_number',true);
            foreach($order_list as $key => &$value) {
                if($value['headhunter_task_id']){
                    $renwu=$this->headhunter_order_profile_model->checkOrder(array('id'=>$value['headhunter_task_id']));
                    $user=$this->user_profile_model->checkProfile(array('id'=>$renwu['user_id']));
                    $order_list[$key]['phone']=$user['phone_number'];
                    $order_list[$key]['nicheng']=$user['nickname'];
                }else{
                    $order_list[$key]['phone']='87211611';
                    $order_list[$key]['nicheng']='杭州巨光网络科技';
                }
                if($value['task_type'] == 0){
                    $order_list[$key]['task_type']='兼职';
                }elseif ($value['task_type'] == 1){
                    $order_list[$key]['task_type']='全职';
                }else{
                    $order_list[$key]['task_type']='实习';
                }
                if($value['current_status'] == 0){
                    $order_list[$key]['current_status']='求职者报名';
                }elseif($value['current_status'] == 1){
                    $order_list[$key]['current_status']='企业发送面试邀请';
                }elseif($value['current_status'] == 2){
                    $order_list[$key]['current_status']='企业已录取';
                }elseif($value['current_status'] == 3){
                    $order_list[$key]['current_status']='完成-佣金解冻';
                }elseif($value['current_status'] == 4){
                    $order_list[$key]['current_status']='3-10天企业辞退75%的佣金';
                }elseif($value['current_status'] == 5){
                    $order_list[$key]['current_status']='3-10天求职者辞职25%的佣金';
                }elseif($value['current_status'] == 6){
                    $order_list[$key]['current_status']='企业查看不通过';
                }elseif($value['current_status'] == 7){
                    $order_list[$key]['current_status']='企业面试不通过';
                }elseif($value['current_status'] == 8){
                    $order_list[$key]['current_status']='已完工';
                }else{
                    $order_list[$key]['current_status']='未面试';
                }
                $edit_url = $this->edit_url('order','edit_pagess',$value['id'],'查看','btn-success');
                $show_url = $this->edit_url('jobhunter','show_resume',$value['user_id'],'查看简历','btn-yellow');
//                $modify_url = $this->edit_url('order','modify_status',$value['id'],'修改订单状态','btn-purple');
                $value['check'] = $this->get_check($value['id']);
//                $value['operate'] = $edit_url.$show_url.$modify_url;
                $value['operate'] = $edit_url.$show_url;
                if(!$value['operate']) {
                    $value['operate'] = '无操作';
                }
            }
            $aaData = $order_list;
        }
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
        $output['iTotalRecords'] = $this->data['count']; //总共有几条数据
        echo json_encode($output); //最后把数据以json格式返回
    }
    /**
     * @brief	求职者兼职任务的查看
     */
    public function edit_pagess() {
        $base_url=$this->config->item('oss_path');
        $id = $this->uri->segment(4);
        $where['jobhunter_order_profile.id'] = $id;
        $order_info = $this->jobhunter_order_profile_model->checkOrder($where,'jobhunter_order_profile.id as order_id,jobhunter_order_profile.*,task_profile.*,user_profile.*',true);
        //获取区域信息
        $quyu=$this->base_county_model->checkBase(array('id'=>$order_info['county_id']));
        $order_info['county_name']=$quyu['county_name'];
        //获取公司信息
        $company=$this->firm_profile_model->checkFirm(array('id'=>$order_info['firm_id']));
        $order_info['firm_name']=$company['name'];
        //获取职位信息
        $oppucation=$this->base_occupation_model->checkBase(array('id'=>$order_info['firm_id']));
        $order_info['occupation_name']=$oppucation['item'];
        $order_info['view_times']=(int)$order_info['view_times'] + (int)$order_info['recv_cv_times'];
        $order_info['fake_view_times']=(int)$order_info['fake_view_times'] + (int)$order_info['fake_recv_cv_times'];
        $order_info['image_url']=$base_url.$order_info['image_url'];
        if($order_info['headhunter_task_id']){
            $renwu=$this->headhunter_order_profile_model->checkOrder(array('id'=>$order_info['headhunter_task_id']));
            $user=$this->user_profile_model->checkProfile(array('id'=>$renwu['user_id']));
            $order_info['phone']=$user['phone_number'];
            $order_info['nicheng']=$user['nickname'];
        }else{
            $order_info['phone']='87211611';
            $order_info['nicheng']='杭州巨光网络科技';
        }

        if($order_info['current_status'] == 0){
            $order_info['current_status']='求职者报名';
        }elseif($order_info['current_status'] == 1){
            $order_info['current_status']='企业发送面试邀请';
        }elseif($order_info['current_status'] == 2){
            $order_info['current_status']='企业已录取';
        }elseif($order_info['current_status'] == 3){
            $order_info['current_status']='完成-佣金解冻';
        }elseif($order_info['current_status'] == 4){
            $order_info['current_status']='3-10天企业辞退75%的佣金';
        }elseif($order_info['current_status'] == 5){
            $order_info['current_status']='3-10天求职者辞职25%的佣金';
        }elseif($order_info['current_status'] == 6){
            $order_info['current_status']='企业查看不通过';
        }elseif($order_info['current_status'] == 7){
            $order_info['current_status']='企业面试不通过';
        }elseif($order_info['current_status'] == 8){
            $order_info['current_status']='已完工';
        }else{
            $order_info['current_status']='未面试';
        }
        $this->assign('data', $order_info);
        $this->display('parttime_edits');
    }
    //简历投递的求职者
    public function wait_list() {
        $id = $this->uri->segment(4);
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $data['order_id'] =  $id;
        //添加类的方式
        $this->assign('data', $data);
        $this->display('wait_list');
    }
    //简历投递的求职者  sz_jobhunter_interview
    public function ajax_wait_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_jobhunter_order_profile.id) like'] = '%'.trim($search).'%';
        $where['sz_jobhunter_order_profile.task_idsz_jobhunter_order_profile.task_id'] = $data['order_id'];
//        $where['task_profile.is_deleted']=0;
//        $where['user_profile.is_deleted']=0;
//        $where['jobhunter_order_profile.current_status']=1;
//        $where['jobhunter_order_profile.is_problem']=0;
        $where =" task_profile.is_deleted = 0";
        $where .=" and user_profile.is_deleted = 0";
        $where .=" and jobhunter_order_profile.current_status = 0";
        $where .=" and jobhunter_order_profile.is_problem = 0";
        $where .=" and sz_jobhunter_order_profile.task_id =".$data['order_id'];
        // if($data['search_field']) {
        //     $where .=' and (sz_jobhunter_order_profile.trade_no like '. '\'%'.trim($data['search_field']).'%\''.' or sz_user_profile.phone_number like '. '\'%'.trim($data['search_field']).'%\''.' or sz_task_profile.name like  '.'\'%'.trim($data['search_field']).'%\''.')';
        // }
        $this->data['count'] = $this->jobhunter_order_profile_model->getCount($where,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = "sz_jobhunter_order_profile.id desc";
            $order_list = $this->jobhunter_order_profile_model->getOrderList($where,$length,$start,$order_by,'jobhunter_order_profile.id,jobhunter_order_profile.task_id,jobhunter_order_profile.add_type,jobhunter_order_profile.inner_user_id,jobhunter_order_profile.trade_no,jobhunter_order_profile.headhunter_task_id,jobhunter_order_profile.current_status,jobhunter_order_profile.user_id,task_profile.name,task_profile.task_type,task_profile.work_time,task_profile.salary,user_profile.nickname,user_profile.phone_number',true);
            foreach($order_list as $key => &$value) {
                /*获取发布职位信息表*/
                $task_profile=$this->task_profile_model->checkTask(array('id'=>$value['task_id']));
                //职位
                $oppucation=$this->base_occupation_model->checkBase(array('id'=>$task_profile['occupation_id']));
                $value['job']=$oppucation['item'];
                //求职者
                $user=$this->user_profile_model->checkProfile(array('id'=>$value['user_id']));
                $value['jobname']=$user['nickname'];
                //联系方式
                $value['phone']=$user['phone_number'];
                //来源内推，猎头，普通
                switch ($value['add_type']) {
                    case '1':
                        $value['add_type']='内推';
                        break;                    
                    case '2':
                        $value['add_type']='猎头';
                        break;                    
                    case '3':
                        $value['add_type']='普通';
                        break;
                    default:
                        $value['add_type']='其他数据还未整合';
                        break;
                }
                //求职顾问--也就是猎头
                if($value['inner_user_id']!=0){
                $user=$this->user_profile_model->checkProfile(array('id'=>$value['inner_user_id']));
                    $value['hunter']=$user['nickname'];
                }else{
                    $value['hunter']='无';
                }

                $value['check'] = $this->get_check($value['id']);
            }
            $aaData = $order_list;
        }
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
        $output['iTotalRecords'] = $this->data['count']; //总共有几条数据
        echo json_encode($output); //最后把数据以json格式返回
    }
    /**
     * @brief	求职者待面试查看
     */
    public function edit_pageses() {
        $base_url=$this->config->item('oss_path');
        $id = $this->uri->segment(4);
        $where['jobhunter_order_profile.id'] = $id;
        $order_info = $this->jobhunter_order_profile_model->checkOrder($where,'jobhunter_order_profile.id as order_id,jobhunter_order_profile.*,task_profile.*,user_profile.*',true);
        //获取区域信息
        $quyu=$this->base_county_model->checkBase(array('id'=>$order_info['county_id']));
        $order_info['county_name']=$quyu['county_name'];
        //获取公司信息
        $company=$this->firm_profile_model->checkFirm(array('id'=>$order_info['firm_id']));
        $order_info['firm_name']=$company['name'];
        //获取职位信息
        $oppucation=$this->base_occupation_model->checkBase(array('id'=>$order_info['firm_id']));
        $order_info['occupation_name']=$oppucation['item'];
        $order_info['view_times']=(int)$order_info['view_times'] + (int)$order_info['recv_cv_times'];
        $order_info['fake_view_times']=(int)$order_info['fake_view_times'] + (int)$order_info['fake_recv_cv_times'];
        $order_info['image_url']=$base_url.$order_info['image_url'];
        if($order_info['headhunter_task_id']){
            $renwu=$this->headhunter_order_profile_model->checkOrder(array('id'=>$order_info['headhunter_task_id']));
            $user=$this->user_profile_model->checkProfile(array('id'=>$renwu['user_id']));
            $order_info['phone']=$user['phone_number'];
            $order_info['nicheng']=$user['nickname'];
        }else{
            $order_info['phone']='87211611';
            $order_info['nicheng']='杭州巨光网络科技';
        }

        if($order_info['current_status'] == 0){
            $order_info['current_status']='求职者报名';
        }elseif($order_info['current_status'] == 1){
            $order_info['current_status']='企业发送面试邀请';
        }elseif($order_info['current_status'] == 2){
            $order_info['current_status']='企业已录取';
        }elseif($order_info['current_status'] == 3){
            $order_info['current_status']='完成-佣金解冻';
        }elseif($order_info['current_status'] == 4){
            $order_info['current_status']='3-10天企业辞退75%的佣金';
        }elseif($order_info['current_status'] == 5){
            $order_info['current_status']='3-10天求职者辞职25%的佣金';
        }elseif($order_info['current_status'] == 6){
            $order_info['current_status']='企业查看不通过';
        }elseif($order_info['current_status'] == 7){
            $order_info['current_status']='企业面试不通过';
        }elseif($order_info['current_status'] == 8){
            $order_info['current_status']='已完工';
        }else{
            $order_info['current_status']='未面试';
        }
        //获取面试信息
        $info=$this->jobhunter_interview_model->checkInterview(array('jobhunter_order_id'=>$order_info['order_id']));
        $order_info['contact']=$info['contact'];
        $order_info['contact_tel']=$info['contact_tel'];
        $order_info['interview_time']=$info['interview_time'];
        $order_info['interview_address']=$info['interview_address'];
        $this->assign('data', $order_info);
        $this->display('wait_edits');
    }
    //待开工
    public function wait_work_list() {
        if($this->uri->segment(4)) {
            $this->assign('message', $this->uri->segment(4));
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->display('wait_work_list');
    }
    //待开工列表
    public function ajax_wait_work_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_jobhunter_order_profile.id) like'] = '%'.trim($search).'%';
        $where =" task_profile.is_deleted = 0";
        $where .=" and user_profile.is_deleted = 0";
        $where .=" and jobhunter_order_profile.current_status = 2";
        $where .=" and task_profile.task_type = 0";
        $where .=" and jobhunter_order_profile.is_problem = 0";
//        $where['task_profile.is_deleted']=0;
//        $where['user_profile.is_deleted']=0;
//        $where['jobhunter_order_profile.current_status']=2;
//        $where['task_profile.task_type']=0;
//        $where['jobhunter_order_profile.is_problem']=0;
        if($data['search_field']) {
            $where .=' and (sz_jobhunter_order_profile.trade_no like '. '\'%'.trim($data['search_field']).'%\''.' or sz_user_profile.phone_number like '. '\'%'.trim($data['search_field']).'%\''.' or sz_task_profile.name like  '.'\'%'.trim($data['search_field']).'%\''.')';
        }
        $this->data['count'] = $this->jobhunter_order_profile_model->getCount($where,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = "sz_jobhunter_order_profile.id desc";
            $order_list = $this->jobhunter_order_profile_model->getOrderList($where,$length,$start,$order_by,'jobhunter_order_profile.id,jobhunter_order_profile.trade_no,jobhunter_order_profile.headhunter_task_id,jobhunter_order_profile.current_status,jobhunter_order_profile.user_id,task_profile.name,task_profile.task_type,task_profile.work_time,task_profile.salary,user_profile.nickname,user_profile.phone_number',true);
            foreach($order_list as $key => &$value) {
                if($value['headhunter_task_id']){
                    $renwu=$this->headhunter_order_profile_model->checkOrder(array('id'=>$value['headhunter_task_id']));
                    $user=$this->user_profile_model->checkProfile(array('id'=>$renwu['user_id']));
                    $order_list[$key]['phone']=$user['phone_number'];
                    $order_list[$key]['nicheng']=$user['nickname'];
                }else{
                    $order_list[$key]['phone']='87211611';
                    $order_list[$key]['nicheng']='杭州巨光网络科技';
                }
                if($value['task_type'] == 0){
                    $order_list[$key]['task_type']='兼职';
                }elseif ($value['task_type'] == 1){
                    $order_list[$key]['task_type']='全职';
                }else{
                    $order_list[$key]['task_type']='实习';
                }
                if($value['current_status'] == 0){
                    $order_list[$key]['current_status']='求职者报名';
                }elseif($value['current_status'] == 1){
                    $order_list[$key]['current_status']='企业发送面试邀请';
                }elseif($value['current_status'] == 2){
                    $order_list[$key]['current_status']='企业已录取';
                }elseif($value['current_status'] == 3){
                    $order_list[$key]['current_status']='完成-佣金解冻';
                }elseif($value['current_status'] == 4){
                    $order_list[$key]['current_status']='3-10天企业辞退75%的佣金';
                }elseif($value['current_status'] == 5){
                    $order_list[$key]['current_status']='3-10天求职者辞职25%的佣金';
                }elseif($value['current_status'] == 6){
                    $order_list[$key]['current_status']='企业查看不通过';
                }elseif($value['current_status'] == 7){
                    $order_list[$key]['current_status']='企业面试不通过';
                }elseif($value['current_status'] == 8){
                    $order_list[$key]['current_status']='已完工';
                }else{
                    $order_list[$key]['current_status']='未面试';
                }
                $edit_url = $this->edit_url('order','edit_pagesess',$value['id'],'查看','btn-success');
                $show_url = $this->edit_url('jobhunter','show_resume',$value['user_id'],'查看简历','btn-yellow');
//                $modify_url = $this->edit_url('order','modify_status',$value['id'],'修改订单状态','btn-purple');
                $value['check'] = $this->get_check($value['id']);
//                $value['operate'] = $edit_url.$show_url.$modify_url;
                $value['operate'] = $edit_url.$show_url;
                if(!$value['operate']) {
                    $value['operate'] = '无操作';
                }
            }
            $aaData = $order_list;
        }
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
        $output['iTotalRecords'] = $this->data['count']; //总共有几条数据
        echo json_encode($output); //最后把数据以json格式返回
    }
    /**
     * @brief	求职者待开工查看
     */
    public function edit_pagesess() {
        $base_url=$this->config->item('oss_path');
        $id = $this->uri->segment(4);
        $where['jobhunter_order_profile.id'] = $id;
        $order_info = $this->jobhunter_order_profile_model->checkOrder($where,'jobhunter_order_profile.id as order_id,jobhunter_order_profile.*,task_profile.*,user_profile.*',true);
        //获取区域信息
        $quyu=$this->base_county_model->checkBase(array('id'=>$order_info['county_id']));
        $order_info['county_name']=$quyu['county_name'];
        //获取公司信息
        $company=$this->firm_profile_model->checkFirm(array('id'=>$order_info['firm_id']));
        $order_info['firm_name']=$company['name'];
        //获取职位信息
        $oppucation=$this->base_occupation_model->checkBase(array('id'=>$order_info['firm_id']));
        $order_info['occupation_name']=$oppucation['item'];
        $order_info['view_times']=(int)$order_info['view_times'] + (int)$order_info['recv_cv_times'];
        $order_info['fake_view_times']=(int)$order_info['fake_view_times'] + (int)$order_info['fake_recv_cv_times'];
        $order_info['image_url']=$base_url.$order_info['image_url'];
        if($order_info['headhunter_task_id']){
            $renwu=$this->headhunter_order_profile_model->checkOrder(array('id'=>$order_info['headhunter_task_id']));
            $user=$this->user_profile_model->checkProfile(array('id'=>$renwu['user_id']));
            $order_info['phone']=$user['phone_number'];
            $order_info['nicheng']=$user['nickname'];
        }else{
            $order_info['phone']='87211611';
            $order_info['nicheng']='杭州巨光网络科技';
        }

        if($order_info['current_status'] == 0){
            $order_info['current_status']='求职者报名';
        }elseif($order_info['current_status'] == 1){
            $order_info['current_status']='企业发送面试邀请';
        }elseif($order_info['current_status'] == 2){
            $order_info['current_status']='企业已录取';
        }elseif($order_info['current_status'] == 3){
            $order_info['current_status']='完成-佣金解冻';
        }elseif($order_info['current_status'] == 4){
            $order_info['current_status']='3-10天企业辞退75%的佣金';
        }elseif($order_info['current_status'] == 5){
            $order_info['current_status']='3-10天求职者辞职25%的佣金';
        }elseif($order_info['current_status'] == 6){
            $order_info['current_status']='企业查看不通过';
        }elseif($order_info['current_status'] == 7){
            $order_info['current_status']='企业面试不通过';
        }elseif($order_info['current_status'] == 8){
            $order_info['current_status']='已完工';
        }else{
            $order_info['current_status']='未面试';
        }
        //获取开工信息
        $info=$this->jobhunter_work_model->checkWork(array('jobhunter_order_id'=>$order_info['order_id']));
        $order_info['contact']=$info['contact'];
        $order_info['contact_tel']=$info['contact_tel'];
        $order_info['work_time']=$info['work_time'];
        $order_info['work_address']=$info['work_address'];
        $this->assign('data', $order_info);
        $this->display('wait_work_edits');
    }
    //面试录取(实习和全职)
    public function recruit_list() {
        if($this->uri->segment(4)) {
            $this->assign('message', $this->uri->segment(4));
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->display('recruit_list');
    }
    //面试录取列表
    public function ajax_recruit_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_jobhunter_order_profile.id) like'] = '%'.trim($search).'%';
//        $where['task_profile.is_deleted']=0;
//        $where['user_profile.is_deleted']=0;
//        $where['jobhunter_order_profile.current_status']=2;
        $where =" task_profile.is_deleted = 0 ";
        $where .=" and user_profile.is_deleted = 0 ";
        $where .=" and jobhunter_order_profile.current_status = 2 ";
        $where .= " and (task_profile.task_type = 1 or task_profile.task_type =2)";
        $where .=" and jobhunter_order_profile.is_problem = 0 ";
        if($data['search_field']) {
        $where .=' and (sz_jobhunter_order_profile.trade_no like '. '\'%'.trim($data['search_field']).'%\''.' or sz_user_profile.phone_number like '. '\'%'.trim($data['search_field']).'%\''.' or sz_task_profile.name like  '.'\'%'.trim($data['search_field']).'%\''.')';
        }
//        $where['jobhunter_order_profile.is_problem']=0;
        $this->data['count'] = $this->jobhunter_order_profile_model->getCount($where,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = "sz_jobhunter_order_profile.id desc";
            $order_list = $this->jobhunter_order_profile_model->getOrderList($where,$length,$start,$order_by,'jobhunter_order_profile.id,jobhunter_order_profile.trade_no,jobhunter_order_profile.headhunter_task_id,jobhunter_order_profile.current_status,jobhunter_order_profile.user_id,task_profile.name,task_profile.task_type,task_profile.work_time,task_profile.salary,user_profile.nickname,user_profile.phone_number',true);
            foreach($order_list as $key => &$value) {
                if($value['headhunter_task_id']){
                    $renwu=$this->headhunter_order_profile_model->checkOrder(array('id'=>$value['headhunter_task_id']));
                    $user=$this->user_profile_model->checkProfile(array('id'=>$renwu['user_id']));
                    $order_list[$key]['phone']=$user['phone_number'];
                    $order_list[$key]['nicheng']=$user['nickname'];
                }else{
                    $order_list[$key]['phone']='87211611';
                    $order_list[$key]['nicheng']='杭州巨光网络科技';
                }
                if($value['task_type'] == 0){
                    $order_list[$key]['task_type']='兼职';
                }elseif ($value['task_type'] == 1){
                    $order_list[$key]['task_type']='全职';
                }else{
                    $order_list[$key]['task_type']='实习';
                }
                if($value['current_status'] == 0){
                    $order_list[$key]['current_status']='求职者报名';
                }elseif($value['current_status'] == 1){
                    $order_list[$key]['current_status']='企业发送面试邀请';
                }elseif($value['current_status'] == 2){
                    $order_list[$key]['current_status']='企业已录取';
                }elseif($value['current_status'] == 3){
                    $order_list[$key]['current_status']='完成-佣金解冻';
                }elseif($value['current_status'] == 4){
                    $order_list[$key]['current_status']='3-10天企业辞退75%的佣金';
                }elseif($value['current_status'] == 5){
                    $order_list[$key]['current_status']='3-10天求职者辞职25%的佣金';
                }elseif($value['current_status'] == 6){
                    $order_list[$key]['current_status']='企业查看不通过';
                }elseif($value['current_status'] == 7){
                    $order_list[$key]['current_status']='企业面试不通过';
                }elseif($value['current_status'] == 8){
                    $order_list[$key]['current_status']='已完工';
                }else{
                    $order_list[$key]['current_status']='未面试';
                }
                $edit_url = $this->edit_url('order','edit_pageseses',$value['id'],'查看','btn-success');
                $show_url = $this->edit_url('jobhunter','show_resume',$value['user_id'],'查看简历','btn-yellow');
//                $modify_url = $this->edit_url('order','modify_status',$value['id'],'修改订单状态','btn-purple');
                $value['check'] = $this->get_check($value['id']);
//                $value['operate'] = $edit_url.$show_url.$modify_url;
                $value['operate'] = $edit_url.$show_url;
                if(!$value['operate']) {
                    $value['operate'] = '无操作';
                }
            }
            $aaData = $order_list;
        }
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
        $output['iTotalRecords'] = $this->data['count']; //总共有几条数据
        echo json_encode($output); //最后把数据以json格式返回
    }
    /**
     * @brief	求职者面试录取查看
     */
    public function edit_pageseses() {
        $base_url=$this->config->item('oss_path');
        $id = $this->uri->segment(4);
        $where['jobhunter_order_profile.id'] = $id;
        $order_info = $this->jobhunter_order_profile_model->checkOrder($where,'jobhunter_order_profile.id as order_id,jobhunter_order_profile.update_time as times,jobhunter_order_profile.*,task_profile.*,user_profile.*',true);
        //获取区域信息
        $quyu=$this->base_county_model->checkBase(array('id'=>$order_info['county_id']));
        $order_info['county_name']=$quyu['county_name'];
        //获取公司信息
        $company=$this->firm_profile_model->checkFirm(array('id'=>$order_info['firm_id']));
        $order_info['firm_name']=$company['name'];
        //获取职位信息
        $oppucation=$this->base_occupation_model->checkBase(array('id'=>$order_info['firm_id']));
        $order_info['occupation_name']=$oppucation['item'];
        $order_info['view_times']=(int)$order_info['view_times'] + (int)$order_info['recv_cv_times'];
        $order_info['fake_view_times']=(int)$order_info['fake_view_times'] + (int)$order_info['fake_recv_cv_times'];
        $order_info['image_url']=$base_url.$order_info['image_url'];
        if($order_info['headhunter_task_id']){
            $renwu=$this->headhunter_order_profile_model->checkOrder(array('id'=>$order_info['headhunter_task_id']));
            $user=$this->user_profile_model->checkProfile(array('id'=>$renwu['user_id']));
            $order_info['phone']=$user['phone_number'];
            $order_info['nicheng']=$user['nickname'];
        }else{
            $order_info['phone']='87211611';
            $order_info['nicheng']='杭州巨光网络科技';
        }

        if($order_info['current_status'] == 0){
            $order_info['current_status']='求职者报名';
        }elseif($order_info['current_status'] == 1){
            $order_info['current_status']='企业发送面试邀请';
        }elseif($order_info['current_status'] == 2){
            $order_info['current_status']='企业已录取';
        }elseif($order_info['current_status'] == 3){
            $order_info['current_status']='完成-佣金解冻';
        }elseif($order_info['current_status'] == 4){
            $order_info['current_status']='3-10天企业辞退75%的佣金';
        }elseif($order_info['current_status'] == 5){
            $order_info['current_status']='3-10天求职者辞职25%的佣金';
        }elseif($order_info['current_status'] == 6){
            $order_info['current_status']='企业查看不通过';
        }elseif($order_info['current_status'] == 7){
            $order_info['current_status']='企业面试不通过';
        }elseif($order_info['current_status'] == 8){
            $order_info['current_status']='已完工';
        }else{
            $order_info['current_status']='未面试';
        }
//        $time=date('Y-m-d H:i:s',time());
        $time=time();
        $order_info['time_diff']=$time -strtotime($order_info['times']) ;
        $order_info['time_diff']=date('d',$order_info['time_diff']);
        $this->assign('data', $order_info);
        $this->display('recruit_edits');
    }

    //已结束订单列表
    public function complete_list() {
        if($this->uri->segment(4)) {
            $this->assign('message', $this->uri->segment(4));
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->display('complete_list');
    }
    //已结束订单列表
    public function ajax_complete_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_jobhunter_order_profile.id) like'] = '%'.trim($search).'%';
//        $where['task_profile.is_deleted']=0;
//        $where['user_profile.is_deleted']=0;
//        $where['jobhunter_order_profile.current_status']=2;
        $where =" task_profile.is_deleted = 0 ";
        $where .=" and user_profile.is_deleted = 0 ";
        $where .=" and jobhunter_order_profile.current_status != 0 and jobhunter_order_profile.current_status != 1 and jobhunter_order_profile.current_status != 2 and jobhunter_order_profile.current_status != 3";
        $where .=" and jobhunter_order_profile.is_problem = 0 ";
        if($data['search_field']) {
            $where .=' and (sz_jobhunter_order_profile.trade_no like '. '\'%'.trim($data['search_field']).'%\''.' or sz_user_profile.phone_number like '. '\'%'.trim($data['search_field']).'%\''.' or sz_task_profile.name like  '.'\'%'.trim($data['search_field']).'%\''.')';
        }
//        $where['jobhunter_order_profile.is_problem']=0;
        $this->data['count'] = $this->jobhunter_order_profile_model->getCount($where,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = "sz_jobhunter_order_profile.id desc";
            $order_list = $this->jobhunter_order_profile_model->getOrderList($where,$length,$start,$order_by,'jobhunter_order_profile.id,jobhunter_order_profile.trade_no,jobhunter_order_profile.headhunter_task_id,jobhunter_order_profile.current_status,jobhunter_order_profile.user_id,task_profile.name,task_profile.task_type,task_profile.work_time,task_profile.salary,user_profile.nickname,user_profile.phone_number',true);
            foreach($order_list as $key => &$value) {
                if($value['headhunter_task_id']){
                    $renwu=$this->headhunter_order_profile_model->checkOrder(array('id'=>$value['headhunter_task_id']));
                    $user=$this->user_profile_model->checkProfile(array('id'=>$renwu['user_id']));
                    $order_list[$key]['phone']=$user['phone_number'];
                    $order_list[$key]['nicheng']=$user['nickname'];
                }else{
                    $order_list[$key]['phone']='87211611';
                    $order_list[$key]['nicheng']='杭州巨光网络科技';
                }
                if($value['task_type'] == 0){
                    $order_list[$key]['task_type']='兼职';
                }elseif ($value['task_type'] == 1){
                    $order_list[$key]['task_type']='全职';
                }else{
                    $order_list[$key]['task_type']='实习';
                }
                if($value['current_status'] == 0){
                    $order_list[$key]['current_status']='求职者报名';
                }elseif($value['current_status'] == 1){
                    $order_list[$key]['current_status']='企业发送面试邀请';
                }elseif($value['current_status'] == 2){
                    $order_list[$key]['current_status']='企业已录取';
                }elseif($value['current_status'] == 3){
                    $order_list[$key]['current_status']='完成-佣金解冻';
                }elseif($value['current_status'] == 4){
                    $order_list[$key]['current_status']='3-10天企业辞退75%的佣金';
                }elseif($value['current_status'] == 5){
                    $order_list[$key]['current_status']='3-10天求职者辞职25%的佣金';
                }elseif($value['current_status'] == 6){
                    $order_list[$key]['current_status']='企业查看不通过';
                }elseif($value['current_status'] == 7){
                    $order_list[$key]['current_status']='企业面试不通过';
                }elseif($value['current_status'] == 8){
                    $order_list[$key]['current_status']='已完工';
                }else{
                    $order_list[$key]['current_status']='未面试';
                }
                $edit_url = $this->edit_url('order','edit_pagesesess',$value['id'],'查看','btn-success');
                $show_url = $this->edit_url('jobhunter','show_resume',$value['user_id'],'查看简历','btn-yellow');
//                $modify_url = $this->edit_url('order','modify_status',$value['id'],'修改订单状态','btn-purple');
                $value['check'] = $this->get_check($value['id']);
//                $value['operate'] = $edit_url.$show_url.$modify_url;
                $value['operate'] = $edit_url.$show_url;
                if(!$value['operate']) {
                    $value['operate'] = '无操作';
                }
            }
            $aaData = $order_list;
        }
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
        $output['iTotalRecords'] = $this->data['count']; //总共有几条数据
        echo json_encode($output); //最后把数据以json格式返回
    }
    /**
     * @brief	已结束订单查看
     */
    public function edit_pagesesess() {
        $base_url=$this->config->item('oss_path');
        $id = $this->uri->segment(4);
        $where['jobhunter_order_profile.id'] = $id;
        $order_info = $this->jobhunter_order_profile_model->checkOrder($where,'jobhunter_order_profile.id as order_id,jobhunter_order_profile.update_time as times,jobhunter_order_profile.*,task_profile.*,user_profile.*',true);
        //获取区域信息
        $quyu=$this->base_county_model->checkBase(array('id'=>$order_info['county_id']));
        $order_info['county_name']=$quyu['county_name'];
        //获取公司信息
        $company=$this->firm_profile_model->checkFirm(array('id'=>$order_info['firm_id']));
        $order_info['firm_name']=$company['name'];
        //获取职位信息
        $oppucation=$this->base_occupation_model->checkBase(array('id'=>$order_info['firm_id']));
        $order_info['occupation_name']=$oppucation['item'];
        $order_info['view_times']=(int)$order_info['view_times'] + (int)$order_info['recv_cv_times'];
        $order_info['fake_view_times']=(int)$order_info['fake_view_times'] + (int)$order_info['fake_recv_cv_times'];
        $order_info['image_url']=$base_url.$order_info['image_url'];
        if($order_info['headhunter_task_id']){
            $renwu=$this->headhunter_order_profile_model->checkOrder(array('id'=>$order_info['headhunter_task_id']));
            $user=$this->user_profile_model->checkProfile(array('id'=>$renwu['user_id']));
            $order_info['phone']=$user['phone_number'];
            $order_info['nicheng']=$user['nickname'];
        }else{
            $order_info['phone']='87211611';
            $order_info['nicheng']='杭州巨光网络科技';
        }

        if($order_info['current_status'] == 0){
            $order_info['current_status']='求职者报名';
        }elseif($order_info['current_status'] == 1){
            $order_info['current_status']='企业发送面试邀请';
        }elseif($order_info['current_status'] == 2){
            $order_info['current_status']='企业已录取';
        }elseif($order_info['current_status'] == 3){
            $order_info['current_status']='完成-佣金解冻';
        }elseif($order_info['current_status'] == 4){
            $order_info['current_status']='3-10天企业辞退75%的佣金';
        }elseif($order_info['current_status'] == 5){
            $order_info['current_status']='3-10天求职者辞职25%的佣金';
        }elseif($order_info['current_status'] == 6){
            $order_info['current_status']='企业查看不通过';
        }elseif($order_info['current_status'] == 7){
            $order_info['current_status']='企业面试不通过';
        }elseif($order_info['current_status'] == 8){
            $order_info['current_status']='已完工';
        }else{
            $order_info['current_status']='未面试';
        }
        $this->assign('data', $order_info);
        $this->display('complete_edits');
    }

    //订单列表
    public function problem_list() {
        if($this->uri->segment(4)) {
            $this->assign('message', $this->uri->segment(4));
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->data['firm_id'] =  empty($_POST) ? '' : $_POST['firm_id'];
        $this->data['trade_no'] =  empty($_POST) ? '' : $_POST['trade_no'];
        $this->data['recruit_type'] =  empty($_POST) ? '' : $_POST['recruit_type'];
        $this->data['task_type'] =  empty($_POST) ? '' : $_POST['task_type'];
        $recruit_type_list= array(
                '1'=>array('recruit_type_id' => 1, 
                    'recruit_type_name'=>'平台展示'),                
                '2'=>array('recruit_type_id' => 2, 
                    'recruit_type_name'=>'内推'),                
                '3'=>array('recruit_type_id' => 3, 
                    'recruit_type_name'=>'顾问展示'),
                    
            );
        $task_type_list= array(
                '1'=>array('task_type_id' => 1, 
                    'task_type_name'=>'兼职'),                
                '2'=>array('task_type_id' => 2, 
                    'task_type_name'=>'全职'),                
                '3'=>array('task_type_id' => 3, 
                    'task_type_name'=>'实习'),
            );
        $this->data['recruit_type_list'] = $recruit_type_list;
        $this->data['task_type_list'] = $task_type_list;
        $this->display('problem_list');
    }
    //已结束订单列表
    public function ajax_problem_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_task_profile.id) like'] = '%'.trim($search).'%';
        if(isset($data['search_field']) && $data['search_field']){
            $where['sz_task_profile.occupation_id like'] = '%'.trim($data['search_field']).'%';
        }        
        if(isset($data['firm_id']) && $data['firm_id']){
            $where['sz_task_profile.firm_id like'] = '%'.trim($data['firm_id']).'%';
        }          
        if(isset($data['task_type']) && $data['task_type']){
            switch ($data['task_type']) {
                case '1':
                    $where['sz_task_profile.task_type like'] = 0;
                    break;               
                case '2':
                    $where['sz_task_profile.task_type like'] = 1;
                    break;                
                case '3':
                    $where['sz_task_profile.task_type like'] = 2;
                    break;
                
                default:
                    break;
            }
        }  
        //订单号      
/*        if(isset($data['trade_no']) && $data['trade_no']){
            $where['sz_task_profile.id like'] = '%'.trim($data['trade_no']).'%';
        }  */      
        if(isset($data['recruit_type']) && $data['recruit_type']){
            switch ($data['recruit_type']) {
                case '1':
                    $where['sz_task_profile.recruit_type_platform'] = 2;
                    break;               
                case '2':
                    $where['sz_task_profile.recruit_type_inner'] = 2;
                    break;                
                case '3':
                    $where['sz_task_profile.recruit_type_hunter'] = 2;
                    break;
                
                default:
                    break;
            }
        }     
        /*id之后会去掉*/
        if(isset($data['search_field']) && $data['search_field']){
            $where['sz_task_profile.id like'] = '%'.trim($data['search_field']).'%';
        }
        $where['sz_task_profile.is_deleted'] = 0;//目前为0
        $this->data['count'] = $this->task_profile_model->getCount($where,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = "sz_task_profile.id desc";
            $order_list = $this->task_profile_model->getTaskList($where,$length,$start,$order_by);
            foreach($order_list as $key => &$value) {
                    $value['on_time']=$value['on_time'];
                    //获取职位信息
                    $oppucation=$this->base_occupation_model->checkBase(array('id'=>$value['occupation_id']));
                    $value['occupation_id']=$oppucation['item'];
                    //获取公司信息
                    $company=$this->firm_profile_model->checkFirm(array('id'=>$value['firm_id']));
                    $value['firm_id']=$company['name'];
                    //模式 recruit_type
                    if($value['recruit_type_platform']==2){
                        $value['recruit_type']='平台展示';
                    }elseif($value['recruit_type_inner']==2){
                        $value['recruit_type']='内推';
                    }elseif($value['recruit_type_hunter']==2){
                        $value['recruit_type']='顾问展示';
                    }else{
                        $value['recruit_type']='未写明来源，为原本数据';
                    }
                    //类型 task_type
                    switch ($value['task_type']) {
                        case '0':
                            $value['task_type'] = "兼职";
                            break;               
                        case '1':
                            $value['task_type'] = "全职";
                            break;                
                        case '2':
                            $value['task_type'] = "实习";
                            break;
                        
                        default:
                            break;
                    }
                    $value['alltime']='待商榷';

/*                    $renwu=$this->headhunter_order_profile_model->checkOrder(array('id'=>$value['headhunter_task_id']));
                    $user=$this->user_profile_model->checkProfile(array('id'=>$renwu['user_id']));
                    $order_list[$key]['phone']=$user['phone_number'];
                    $order_list[$key]['nicheng']=$user['nickname'];*/
                $del_url = $this->delete_url('/order/problem_list_delete',$value['id'],'删除','btn-purple');               
                $edit_url = $this->edit_url('order','edit_pageseseses',$value['id'],'详情','btn-success');
                $value['check'] = $this->get_check($value['id']);
                $value['operate'] = $edit_url.$del_url;
                if(!$value['operate']) {
                    $value['operate'] = '无操作';
                }
            }
            $aaData = $order_list;
        }
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
        $output['iTotalRecords'] = $this->data['count']; //总共有几条数据
        echo json_encode($output); //最后把数据以json格式返回
    }
    //删除
    public function problem_list_delete() {
        $data = $_POST;
        $where['id'] = $data['id'];
        $data['is_deleted'] = 1; //删除
        $data['delete_time']=$this->time_retuen();
        $del_result = $this->task_profile_model->editTask($where,$data);
        echo json_encode($del_result);
    }
    /**
     * @brief	已结束订单查看
     */
    public function edit_pageseseses() {
        $base_url=$this->config->item('oss_path');
        $id = $this->uri->segment(4);
        $where['sz_task_profile.id'] = $id;
        $where['sz_task_profile.is_deleted'] = 0;//目前为0
        $this->data['count'] = $this->task_profile_model->getCount($where,true);
        $order_by = "sz_task_profile.id desc";
        $order_info = $this->task_profile_model->checkTask($where);
        // $order_info = $this->jobhunter_order_profile_model->checkOrder($where,'jobhunter_order_profile.id as order_id,jobhunter_order_profile.update_time as times,jobhunter_order_profile.*,task_profile.*,user_profile.*',true);
        //获取区域信息
        $quyu=$this->base_county_model->checkBase(array('id'=>$order_info['county_id']));
        $order_info['county_name']=$quyu['county_name'];
        //获取公司信息
        $company=$this->firm_profile_model->checkFirm(array('id'=>$order_info['firm_id']));
        $order_info['firm_name']=$company['name'];
        //获取职位信息
        $oppucation=$this->base_occupation_model->checkBase(array('id'=>$order_info['firm_id']));
        $order_info['occupation_name']=$oppucation['item'];
        $order_info['view_times']=(int)$order_info['view_times'] + (int)$order_info['recv_cv_times'];
        $order_info['fake_view_times']=(int)$order_info['fake_view_times'] + (int)$order_info['fake_recv_cv_times'];
        $order_info['image_url']=$base_url.$order_info['image_url'];

        $order_info['order_type']="问题单";
        $this->assign('data', $order_info);
        $this->display('problem_edits');
    }





    /*------------------------------------------求职者任务结束---------------------------------------------------------*/
    //修改订单状态
//    public function modify_status(){
//        $id = $this->uri->segment(4);
//        $where['id'] = $id;
//        //获取订单状态
//        $res=$this->jobhunter_order_profile_model->checkOrder($where);
//        if(!empty($res)){
//            $this->assign('data',$res);
//            $this->display('modify_edit');
//        }else{
//            echo "<script>window.history.go(-1);</script>";
//            exit;
//        }
//    }
    //保存修改的订单的状态
    public function modify_edit(){
        $data=$_POST;
        $where['id']=$data['id'];
        //获取订单信息
        $res=$this->jobhunter_order_profile_model->checkOrder($where);
        $arr['current_status']=$data['current_status'];
        $arr['update_time']=$this->time_retuen();

        $brr['jobhunter_order_id']=$res['id'];
        $brr['status']=$data['current_status'];
        $brr['create_time']=$this->time_retuen();

        //判断订单状态是否为 2：企业已录取 或 7：企业面试不通过 人才库里的人才产生一次面试，到账5元／次
        if($data['current_status'] == 2 || $data['current_status'] == 7){
            //人才库所有猎头账户余额+5
            $headhunters=$this->talent_pool_model->getTalentAll(array('jobhunter_user_id'=>$res['user_id']),'headhunter_user_id');
            foreach ($headhunters as $key => $value){
                //获取猎头账户的余额
                $yue=$this->user_balance_model->checkBalance(array('user_id'=>$value['headhunter_user_id']),'balance');
                $arr['balance'] = (int)$yue['balance'] + 500;
                $arr['update_time']= $this->time_retuen();

                $brr['user_id'] = $value['headhunter_user_id'];
                $brr['direction'] = 0;
                $brr['amount'] = 500;
                $brr['comment'] = '人才库的人才产生一次面试，猎头奖励5元';
                $brr['create_time'] = $this->time_retuen();;
                //更新用户余额
                $this->user_balance_model->editBalance(array('user_id'=>$value['headhunter_user_id']),$arr);
                //用户余额变动表信息添加
                $this->balance_statements_model->addBalance($brr);
            }
        }
        //更改求职者订单基础信息表订单状态
        $a=$this->jobhunter_order_profile_model->editOrder($where,$arr);
        //增加求职者订单流水表
        $b=$this->jobhunter_order_statements_model->addStatements($brr);
        if($a && $b){
            $this->response('0','',true);
        }else{
            $this->response('1','更新失败',true);
        }


    }
    //发送面试邀请跳转
    public function interview(){
        $where['id'] = $this->uri->segment(4);
        //查询该订单是否存在
        $res=$this->jobhunter_order_profile_model->checkOrder($where);
        if(!empty($res)){
            $this->assign('data',$res);
            $this->display('interview_edit');
        }else{
            echo "<script>window.history.go(-1);</script>";
            exit;
        }
    }
    //面试邀请的添加
    public function add(){
        $data=$_POST;
        $where['id']=$data['id'];

        $arr['current_status']=1;
        $arr['update_time']=$this->time_retuen();

        $brr['jobhunter_order_id']=$data['id'];
        $brr['status']=1;
        $brr['contact']=$data['contact'];
        $brr['contact_tel']=$data['contact_tel'];
        $brr['interview_time']=$data['interview_time'];
        $brr['interview_address']=$data['interview_address'];
        $brr['create_time']=$this->time_retuen();
        //修改订单状态为1
        $a=$this->jobhunter_order_profile_model->editOrder($where,$arr);
        //添加面试邀请信息
        $b=$this->jobhunter_interview_model->addInterview($brr);
        if($a && $b){
            $this->location_href($this->gos_url."/4");
        }else{
            $this->location_href($this->gos_url."/5");
        }
    }
    //发送开工邀请跳转
    public function work(){
        $where['id'] = $this->uri->segment(4);
        //查询该订单是否存在
        $res=$this->jobhunter_order_profile_model->checkOrder($where);
        if(!empty($res)){
            $this->assign('data',$res);
            $this->display('work_edit');
        }else{
            echo "<script>window.history.go(-1);</script>";
            exit;
        }
    }
    //开工邀请的添加
    public function adds(){
        $data=$_POST;
        $where['id']=$data['id'];

        $arr['current_status']=2;
        $arr['update_time']=$this->time_retuen();

        $brr['jobhunter_order_id']=$data['id'];
        $brr['status']=2;
        $brr['contact']=$data['contact'];
        $brr['contact_tel']=$data['contact_tel'];
        $brr['work_time']=$data['work_time'];
        $brr['work_address']=$data['work_address'];
        $brr['create_time']=$this->time_retuen();
        //修改订单状态为2
        $a=$this->jobhunter_order_profile_model->editOrder($where,$arr);
        //添加开工邀请信息
        $b=$this->jobhunter_work_model->addWork($brr);
        if($a && $b){
            $this->location_href($this->going_url."/4");
        }else{
            $this->location_href($this->going_url."/5");
        }
    }
    //企业辞退和自己辞职
    public function discharge(){
        $data=$_POST;
        $where['jobhunter_order_profile.id']=$data['id'];
        $type=$data['type'];
        //获取订单信息
        $res=$this->jobhunter_order_profile_model->checkOrder($where,'jobhunter_order_profile.id,jobhunter_order_profile.headhunter_task_id,task_profile.commission',true);
        //如果是猎头领取的任务
        if($res['headhunter_task_id']){
            $result=$this->headhunter_order_profile_model->checkOrder(array('id'=>$res['headhunter_task_id']),'user_id');
            //获取佣金
            $commission=$this->user_balance_model->checkBalance(array('user_id'=>$result['user_id']));
        }
        if(isset($result['user_id']) && $result['user_id']){
            $arr['update_time']=$this->time_retuen();
            $brr['update_time']=$this->time_retuen();
            $crr['jobhunter_order_id']=$res['id'];
            $crr['create_time']=$this->time_retuen();
            if($type == 1){
                $arr['balance']=(int)$commission['balance'] + (int)($res['commission']/4)*100;
                $brr['current_status']=5;
                $crr['status']=5;
                //增加猎头的余额
                $a=$this->user_balance_model->editBalance(array('id'=>$commission['id']),$arr);
                //更改订单的状态
                $b=$this->jobhunter_order_profile_model->editOrder(array('id'=>$res['id']),$brr);
                //增加订单流水
                $c=$this->jobhunter_order_statements_model->addStatements($crr);
                if($a && $b && $c){
                    $this->response('0','',true);
                }else{
                    $this->response('1','更新失败',true);
                }
            }
            if($type == 2) {
                $brr['current_status']=4;
                $crr['status']=4;
                $arr['balance']=(int)$commission['balance'] + (int)($res['commission']/4)*300;
                //增加猎头的余额
                $a=$this->user_balance_model->editBalance(array('id'=>$commission['id']),$arr);
                //更改订单的状态
                $b=$this->jobhunter_order_profile_model->editOrder(array('id'=>$res['id']),$brr);
                //增加订单流水
                $c=$this->jobhunter_order_statements_model->addStatements($crr);
                if($a && $b && $c){
                    $this->response('0','',true);
                }else{
                    $this->response('1','更新失败',true);
                }
            }
        }else{
            $brr['update_time']=$this->time_retuen();
            $crr['jobhunter_order_id']=$res['id'];
            $crr['create_time']=$this->time_retuen();
            if($type == 1){
                $brr['current_status']=5;
                $crr['status']=5;
                //更改订单的状态
                $b=$this->jobhunter_order_profile_model->editOrder(array('id'=>$res['id']),$brr);
                //增加订单流水
                $c=$this->jobhunter_order_statements_model->addStatements($crr);
                if($b && $c){
                    $this->response('0','',true);
                }else{
                    $this->response('1','更新失败',true);
                }
            }
            if($type == 2) {
                $brr['current_status']=4;
                $crr['status']=4;
                //更改订单的状态
                $b=$this->jobhunter_order_profile_model->editOrder(array('id'=>$res['id']),$brr);
                //增加订单流水
                $c=$this->jobhunter_order_statements_model->addStatements($crr);
                if($b && $c){
                    $this->response('0','',true);
                }else{
                    $this->response('1','更新失败',true);
                }
            }
        }
    }
    //改为正常单
    public function problem_edit(){
        $data=$_POST;
        $where['id']=$data['id'];

        $arr['is_problem']=0;
        $arr['update_time']=$this->time_retuen();

        //修改为正常订单
        $a=$this->jobhunter_order_profile_model->editOrder($where,$arr);
        if($a){
            $this->response('0','',true);
        }else{
            $this->response('1','更新失败',true);
        }
    }


    /*面试的求职者*/
    public function people_list_tock(){
        $id = $this->uri->segment(4);
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $data['order_id'] =  $id;
        //添加类的方式
        $this->assign('data', $data);
        $this->display('people_list_tock');
    }
    //
    public function people_list_tock_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_jobhunter_order_profile.id) like'] = '%'.trim($search).'%';
        // $where['concat(sz_jobhunter_order_profile.task_id) like'] = '%'.trim($data['order_id']).'%';
//        $where['task_profile.is_deleted']=0;
//        $where['user_profile.is_deleted']=0;
//        $where['jobhunter_order_profile.current_status']=1;
//        $where['jobhunter_order_profile.is_problem']=0;
        $where =" task_profile.is_deleted = 0";
        $where .=" and user_profile.is_deleted = 0";
        $where .=" and jobhunter_order_profile.current_status != 0 and jobhunter_order_profile.current_status !=9";
        $where .=" and jobhunter_order_profile.is_problem = 0";
        $where .=" and sz_jobhunter_order_profile.task_id = ".$data['order_id'];
        // if($data['search_field']) {
        //     $where .=' and (sz_jobhunter_order_profile.trade_no like '. '\'%'.trim($data['search_field']).'%\''.' or sz_user_profile.phone_number like '. '\'%'.trim($data['search_field']).'%\''.' or sz_task_profile.name like  '.'\'%'.trim($data['search_field']).'%\''.')';
        // }
        $this->data['count'] = $this->jobhunter_order_profile_model->getCount($where,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = "sz_jobhunter_order_profile.id desc";
            $order_list = $this->jobhunter_order_profile_model->getOrderList($where,$length,$start,$order_by,'jobhunter_order_profile.id,jobhunter_order_profile.task_id,jobhunter_order_profile.add_type,jobhunter_order_profile.inner_user_id,jobhunter_order_profile.trade_no,jobhunter_order_profile.headhunter_task_id,jobhunter_order_profile.current_status,jobhunter_order_profile.user_id,task_profile.name,task_profile.task_type,task_profile.work_time,task_profile.salary,user_profile.nickname,user_profile.phone_number',true);
            foreach($order_list as $key => &$value) {
                /*获取发布职位信息表*/
                $task_profile=$this->task_profile_model->checkTask(array('id'=>$value['task_id']));
                //职位
                $oppucation=$this->base_occupation_model->checkBase(array('id'=>$task_profile['occupation_id']));
                $value['job']=$oppucation['item'];
                //求职者
                $user=$this->user_profile_model->checkProfile(array('id'=>$value['user_id']));
                $value['jobname']=$user['nickname'];
                //联系方式
                $value['phone']=$user['phone_number'];
                //来源内推，猎头，普通

                switch ($value['add_type']) {
                    case '1':
                        $value['add_type']='内推';
                        break;                    
                    case '2':
                        $value['add_type']='猎头';
                        break;                    
                    case '3':
                        $value['add_type']='普通';
                        break;
                    default:
                        $value['add_type']='其他数据还未整合';
                        break;
                }
                //求职顾问--也就是猎头
                if($value['inner_user_id']!=0){
                $user=$this->user_profile_model->checkProfile(array('id'=>$value['inner_user_id']));
                    $value['hunter']=$user['nickname'];
                }else{
                    $value['hunter']='无';
                }
                /*获取发布面试时间*/
                $jobhunter_interview_list=$this->jobhunter_interview_model->checkInterview(array('jobhunter_order_id'=>$value['user_id']));
                $value['jobhunter_interview_time']=date("Y-m-d H:i:s",$jobhunter_interview_list['interview_time']);
                if($value['current_status'] == 0){
                    $value['current_status']='求职者报名';
                }elseif($value['current_status'] == 1){
                    $value['current_status']='企业发送面试邀请';
                }elseif($value['current_status'] == 2){
                    $value['current_status']='企业已录取';
                }elseif($value['current_status'] == 3){
                    $value['current_status']='完成-佣金解冻';
                }elseif($value['current_status'] == 4){
                    $value['current_status']='3-10天企业辞退75%的佣金';
                }elseif($value['current_status'] == 5){
                    $value['current_status']='3-10天求职者辞职25%的佣金';
                }elseif($value['current_status'] == 6){
                    $value['current_status']='企业查看不通过';
                }elseif($value['current_status'] == 7){
                    $value['current_status']='企业面试不通过';
                }elseif($value['current_status'] == 8){
                    $value['current_status']='已完工';
                }else{
                    $value['current_status']='未面试';
                }
                $edit_url = $this->edit_url('order','edit_pageseses',$value['id'],'查看','btn-success');
                $show_url = $this->edit_url('jobhunter','show_resume',$value['user_id'],'查看简历','btn-yellow');
//                $modify_url = $this->edit_url('order','modify_status',$value['id'],'修改订单状态','btn-purple');
                $value['check'] = $this->get_check($value['id']);
//                $value['operate'] = $edit_url.$show_url.$modify_url;
                $value['operate'] = $edit_url.$show_url;
                if(!$value['operate']) {
                    $value['operate'] = '无操作';
                }
            }
            $aaData = $order_list;
        }
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
        $output['iTotalRecords'] = $this->data['count']; //总共有几条数据
        echo json_encode($output); //最后把数据以json格式返回
    }


}
?>