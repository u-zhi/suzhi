<?php
class Headhunter extends PC_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('user_profile_model');
        $this->load->model('base_major_model');
        $this->load->model('base_city_model');
        $this->load->model('base_occupation_model');
        $this->load->model('base_school_model');
        $this->load->model('user_headhunter_extra_info_model');
        $this->load->model('block_list_model');
        $this->load->model('wechat_payment_order_model');
        $this->load->model('registry_promotion_model');
        $this->load->model('id_verfication_model');
        $this->load->model('talent_pool_model');
        $this->load->model('user_balance_model');
        $this->load->model('balance_statements_model');
        $this->load->model('balance_withdraw_profile_model');
        /*接取的任务*/
        $this->load->model('jobhunter_order_profile_model');
        $this->load->model('headhunter_order_profile_model');
        $this->load->model('firm_profile_model');
        $this->load->model('base_county_model');
        $this->load->model('base_region_model');

        $this->go_url = $this->data['admin_path']."/headhunter/headhunter_list";
        $this->gos_url = $this->data['admin_path']."/headhunter/receive_list";
        $this->goedit_url = $this->data['admin_path']."/headhunter/receive_edit_page";
        $this->data['authority'] = $this->authority;
    }

    //公司列表
    public function headhunter_list() {
        if($this->uri->segment(4)) {
            $this->data['message'] = $this->uri->segment(4);
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->display('headhunter_list');
    }
    //ajax获取管理员数据加载到列表
    public function ajax_headhunter_list() {
        $base_url=$this->config->item('oss_path');
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_user_profile.id) like'] = '%'.trim($search).'%';
        $where['user_profile.is_deleted'] = 0;
        $where['user_profile.is_hunter'] = 2;
        //$where['user_message.user_type'] = 0;
        if(isset($data['search_field']) && $data['search_field']){
            $where['sz_id_verfication.real_name like'] = '%'.trim($data['search_field']).'%';
        }
        if(isset($data['is_return_deposit']) && $data['is_return_deposit']){
            $where['sz_user_headhunter_extra_info.is_return_deposit'] = $data['is_return_deposit'];
        }
        $this->data['count'] = $this->user_profile_model->getCount($where,false,true);
//        echo $this->db->last_query();
//        exit;
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = 'user_profile.id desc';
            $admin_list = $this->user_profile_model->getProfileLists($where,$length,$start,$order_by,'user_profile.*,user_message.user_type,id_verfication.real_name,id_verfication.card_no,user_headhunter_extra_info.*',true);
            foreach($admin_list as $key => &$value) {
                $value['avatar_url']=$this->default_img($base_url.$value['avatar_url']);
                if($value['gender'] == 0){
                    $value['gender_name']='女';
                }else{
                    $value['gender_name']='男';
                }
                //是邀请码,免费猎头，还是付费
                $ress1=$this->wechat_payment_order_model->getPaymentAll(array('headhunter_id'=>$value['user_id'], 'status'=>1 ));
                $ress2=$this->registry_promotion_model->getPromotionAll(array('user_id'=>$value['user_id'],'is_used'=>1));
                if(empty($ress1) && !empty($ress2)){
                    $value['pay_type']='邀请码';
                }elseif (!empty($ress1) && empty($ress2)){
                    $value['pay_type']='保证金';
                }else{
                    $value['pay_type']='免费猎头';
                }
                // 接取的订单
                $admin_list[$key]["jqorder"]= $this->headhunter_order_profile_model->getCount(array('user_id'=>$value['user_id'],'is_cancled'=>0));
                // 推荐人才
                $admin_list[$key]["getOrder_sum"]=$this->jobhunter_order_profile_model->getCount(array('inner_user_id'=>$value['user_id']));
                // 用户余额
                $balance=$this->user_balance_model->checkBalance(array('user_id'=>$value['user_id']),'balance');
                $admin_list[$key]["balance"]=($balance['balance']/100)."(元)";

                $edit_url = $this->edit_url('headhunter','edit_page',$value['user_id'],'用户详情','btn-primary');
                $show_url = $this->edit_url('headhunter','presentation_record',$value['user_id'],'提现记录','btn-yellow');
                // $del_url = $this->delete_url('/headhunter/delete',$value['user_id'],'封号','btn-purple');
                $value['check'] = $this->get_check($value['id']);
                //是否退还保证金
                if($value['is_return_deposit'] == 0){
                    $value['is_return_deposit_name']='否';
                    if($value['identity_level'] == 4){
                        $bao_url = $this->edit_url('headhunter','edit_pages',$value['user_id'],'保证金退还','btn-warning');
                        $value['operate'] = $edit_url.$show_url.$bao_url.$del_url;
                    }else{
                        $value['operate'] = $edit_url.$show_url/*.$del_url*/;
                    }

                }else{
                    $value['is_return_deposit_name']='是';
                    $value['operate'] = $edit_url.$show_url/*.$del_url*/;
                }
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
    //封号
    public function delete() {
        $data = $_POST;
        $where['id'] = $data['id'];
        //获取用户信息
        $a=$this->user_profile_model->checkProfile($where);

        $data['is_deleted'] = 1; //删除
        $data['delete_time']=$this->time_retuen();
        $arr['user_id']=$data['id'];
        $brr['is_deleted'] = 1;
        $brr['delete_time']=$this->time_retuen();
        //添加封号表
        $crr['user_id']=$data['id'];
        $crr['phone_number']=$a['phone_number'];
        $crr['block_type']= 1;
        $crr['is_deleted']= 1;
        $crr['create_time']= $this->time_retuen();
        //添加封号表
        $ress=$this->block_list_model->addBlock($crr);
        //更新个人猎头补充信息
        $result=$this->user_headhunter_extra_info_model->editHeadhunter($arr,$brr);
        //更新用户基础信息表
        $del_result = $this->user_profile_model->editProfile($where,$data);
        if($ress && $result && $del_result){
            $de_result=true;
        }else{
            $de_result=false;
        }
        echo json_encode($de_result);
    }
    /**
     * @brief	编辑页面
     */
    public function edit_page() {
        $base_url=$this->config->item('oss_path');
        $id = $this->uri->segment(4);
        $where['sz_user_profile.id'] = $id;
        $user_info=$this->user_profile_model->checkProfile($where,"user_profile.*,user_message.user_type,id_verfication.real_name,id_verfication.card_no,user_headhunter_extra_info.*",false,true);
        $user_info['avatar_url']=$base_url.$user_info['avatar_url'];
        $arr['parent_id !=']=0;
        // 用户余额
        $whereuser['user_id'] = $id;
        $balance=$this->user_balance_model->checkBalance($whereuser,'balance');
        $user_info['balance']=$balance['balance'];
        //获取所有的学校
        $school=$this->base_school_model->getBaseAll();
        //获取所有的职位
        $occupation=$this->base_occupation_model->getBaseAll($arr);
        //获取所有的专业
        $major=$this->base_major_model->getBaseAll($arr);
        // 获取城市名称
        // $user_info['city_id']=$this->base_region_model->getRegionOne(array("region_id"=>$user_info['city_id']));
        $this->assign('school',$school);
        $this->assign('occupation',$occupation);
        $this->assign('major',$major);
        $this->assign('data', $user_info);
        $this->display('headhunter_edit');
    }
    /**
     * @brief	保存编辑信息
     */
    public function edit() {
        $data = $_POST;
        $brr['id'] = $data['user_id'];
        $arr['user_id']=$data['user_id'];
        //上传头像
        if($_FILES['jietu']['tmp_name']){
            $upload_image = $this->upload($_FILES,false,WEB_URL);
            $crr['avatar_url'] = $upload_image;
        }else{
            $res=$this->user_profile_model->checkProfile($brr,'avatar_url');
            $data['avatar_url']=$res['avatar_url'];
        }
        $crr['nickname']=$data['nickname'];
        $crr['phone_number']=$data['phone_number'];
        $crr['gender']=$data['gender'];
        $crr['birthday']=$data['birthday'];
        $crr['update_time']=$drr['update_time']=$err['update_time']=$this->time_retuen();

        $drr['school_id']=$data['school_id'];
        $drr['occupation_id']=$data['occupation_id'];
        $drr['major_id']=$data['major_id'];
        $drr['enroll_year']=$data['enroll_year'];
        $drr['highest_degree']=$data['highest_degree'];

        $err['real_name']=$data['real_name'];
        $err['card_no']=$data['card_no'];

        $res=$this->user_profile_model->editProfile($brr,$crr);
        $result=$this->user_headhunter_extra_info_model->editHeadhunter($arr,$drr);
        $results=$this->id_verfication_model->editVerification($arr,$err);
        if($res && $result && $results) {
            $this->location_href($this->go_url."/2");
        }else {
            $this->location_href($this->go_url."/3");
        }
    }
    //保证金退还操作
    public function edit_pages() {
        $id = $this->uri->segment(4);
        $where['sz_user_profile.id'] = $id;
        $user_info=$this->user_profile_model->checkProfile($where,"user_balance.*,id_verfication.real_name",false,true);
        $this->assign('data', $user_info);
        $this->display('headhunter_edit');
    }
    /**
     * @brief	退还保证金操作
     */
    public function edits() {
        $data = $_POST;
        $where['user_id'] = $data['user_id'];
        //获取该账户的余额信息
        $arr=$this->user_balance_model->checkBalance($where);
        $brr['frozen_amount']=(int)$arr['frozen_amount'] - 50000;
        $brr['balance']=(int)$arr['balance'] + 50000;
        $brr['update_time']=$crr['create_time']=$drr['update_time']=$this->time_retuen();

        $crr['user_id']=$where['user_id'];
        $crr['direction']=0;
        $crr['amount']=50000;
        $crr['comment']='保证金退还';

        $drr['is_return_deposit']=1;
       //更新余额表的余额信息
        $res=$this->user_balance_model->editBalance($where,$brr);
        //添加余额变动表信息
        $result=$this->balance_statements_model->addBalance($crr);
        //更改猎头信息为已退还保证金状态
        $results=$this->user_headhunter_extra_info_model->editHeadhunter($where,$drr);
        if($res && $result && $results) {
            $this->location_href($this->go_url."/2");
        }else {
            $this->location_href($this->go_url."/3");
        }
    }
    //人才库
    public function talent_pool(){
        $headhunter_user_id= $this->uri->segment(4);
        $base_url=$this->config->item('oss_path');
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->data['headhunter_user_id']=$headhunter_user_id;

        $where['sz_user_profile.id'] = $headhunter_user_id;
        $user_info=$this->user_profile_model->checkProfile($where,"user_profile.*,user_message.user_type,id_verfication.real_name,id_verfication.card_no,user_headhunter_extra_info.*",false,true);
        $user_info['avatar_url']=$base_url.$user_info['avatar_url'];
        $arr['parent_id !=']=0;
        // 用户余额
        $whereuser['user_id'] = $headhunter_user_id;
        $balance=$this->user_balance_model->checkBalance($whereuser,'balance');
        $user_info['balance']=$balance['balance'];
        //获取所有的学校
        $school=$this->base_school_model->getBaseAll();
        //获取所有的职位
        $occupation=$this->base_occupation_model->getBaseAll($arr);
        //获取所有的专业
        $major=$this->base_major_model->getBaseAll($arr);
        $this->assign('school',$school);
        $this->assign('occupation',$occupation);
        $this->assign('major',$major);
        $this->assign('data', $user_info);
        $this->display('talent_pool_list');
    }
    //ajax获取管理员数据加载到列表
    public function ajax_talent_list() {
        $base_url=$this->config->item('oss_path');
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_talent_pool.id) like'] = '%'.trim($search).'%';
        $where['headhunter_user_id'] = $data['headhunter_user_id'];
        $where['cv_job_intention.is_deleted']=0;
        $where['user_profile.is_deleted']=0;
        if(isset($data['search_field']) && $data['search_field']){
            $where['sz_user_profile.nickname like'] = '%'.trim($data['search_field']).'%';
        }
        $this->data['count'] = $this->talent_pool_model->getCount($where,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = 'talent_pool.id desc';
            $admin_list = $this->talent_pool_model->getTalentList($where,$length,$start,$order_by,'talent_pool.jobhunter_user_id,user_profile.*,cv_job_intention.*',true);
            foreach($admin_list as $key => &$value) {
                $value['avatar_url']=$this->default_img($base_url.$value['avatar_url']);
                if($value['gender'] == 0){
                    $value['gender_name']='女';
                }else{
                    $value['gender_name']='男';
                }
                if($value['job_type'] == 0){
                    $value['job_type']='兼职';
                }elseif ($value['job_type'] == 1){
                    $value['job_type']='全职';
                }else{
                    $value['job_type']='实习';
                }
                $value['salary']=$value['wage_lower'].'-'.$value['wage_upper'];
                $edit_url = $this->edit_url('jobhunter','show_resume',$value['jobhunter_user_id'],'查看简历','btn-primary');
                $value['check'] = $this->get_check($value['id']);
                $value['operate'] = $edit_url;
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


    //提现记录
    public function presentation_record(){
        $user_id = $this->uri->segment(4);
        $this->assign('user_id',$user_id);
        $this->display('record_list');

    }
    //ajax获取管理员数据加载到列表
    public function ajax_record_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_balance_withdraw_order.id) like'] = '%'.trim($search).'%';
        $where['user_id'] = $data['user_id'];
        $this->data['count'] = $this->balance_withdraw_profile_model->getCount($where,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = 'balance_withdraw_order.id desc';
            $admin_list = $this->balance_withdraw_profile_model->getBalanceList($where,$length,$start,$order_by,'balance_withdraw_order.*',true);
            foreach ($admin_list as $key =>$value){
                $admin_list[$key]['amount'] =(int)$value['amount']/100;
            }
            $aaData = $admin_list;
        }
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count'];
        $output['iTotalRecords'] = $this->data['count'];
        echo json_encode($output);
    }


        /**
     * @brief   已领取列表
     */
    public function receive_list() {
        $headhunter_user_id= $this->uri->segment(4);
        $this->data['headhunter_user_id']=$headhunter_user_id;
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];

        $base_url=$this->config->item('oss_path');
        $headhunter_user_id= $this->uri->segment(4);
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $where['sz_user_profile.id'] = $headhunter_user_id;
        $user_info=$this->user_profile_model->checkProfile($where,"user_profile.*,user_message.user_type,id_verfication.real_name,id_verfication.card_no,user_headhunter_extra_info.*",false,true);
        $user_info['avatar_url']=$base_url.$user_info['avatar_url'];
        $arr['parent_id !=']=0;
        // 用户余额
        $whereuser['user_id'] = $headhunter_user_id;
        $balance=$this->user_balance_model->checkBalance($whereuser,'balance');
        $user_info['balance']=$balance['balance'];
        //获取所有的学校
        $school=$this->base_school_model->getBaseAll();
        //获取所有的职位
        $occupation=$this->base_occupation_model->getBaseAll($arr);
        //获取所有的专业
        $major=$this->base_major_model->getBaseAll($arr);
        $this->assign('school',$school);
        $this->assign('occupation',$occupation);
        $this->assign('major',$major);
        $this->assign('data', $user_info);
        $this->display('headhunter_receive_list');
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
        $where['sz_headhunter_order_profile.user_id'] =$data['headhunter_user_id'];
        if($data['search_field']) {
            $where['sz_user_profile.phone_number like '. '\'%'.trim($data['search_field']).'%\''.' or sz_task_profile.name like  '] = '%'.trim($data['search_field']).'%';
        }
        /*注意*/
        // $where['task_profile.is_deleted']=0;
        $where['user_profile.is_deleted']=0;
        $this->data['count'] = $this->headhunter_order_profile_model->getCount($where,true);
        // var_dump($this->data['count']);exit;
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = "sz_headhunter_order_profile.id desc";
            $order_list = $this->headhunter_order_profile_model->getOrderList($where,$length,$start,$order_by,'headhunter_order_profile.id,headhunter_order_profile.is_cancled,task_profile.name,task_profile.task_type,task_profile.firm_id,task_profile.person_demand,task_profile.work_time,task_profile.salary,task_profile.commission,task_profile.is_off_shelved,user_profile.nickname,user_profile.phone_number',true);
            foreach($order_list as $key => &$value) {
                    if($value['is_cancled'] == 0){
                        $order_list[$key]['is_cancled']='已领取';
                    }else{
                        $order_list[$key]['is_cancled']='已取消';
                    }
                    if($value['task_type'] == 0){
                        $order_list[$key]['task_type']='兼职';
                    }elseif ($value['task_type'] == 1){
                        $order_list[$key]['task_type']='全职';
                    }else{
                        $order_list[$key]['task_type']='实习';
                    }
                    if($value['is_off_shelved'] == 0){
                        $order_list[$key]['is_off_shelved']='上架';
                    }else{
                        $order_list[$key]['is_off_shelved']='下架';
                    }
                //获取企业名称
                $company=$this->firm_profile_model->checkFirm(array('id'=>$value['firm_id']));
                $value['firm_id']=$company['name'];


                $edit_url = $this->edit_url('headhunter','receive_edit_page',$value['id'],'查看','btn-success');
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
     * @brief   猎头任务的查看
     */
    public function receive_edit_page() {
        $base_url=$this->config->item('oss_path');
        $id = $this->uri->segment(4);
        $where['headhunter_order_profile.id'] = $id;
        $this->data['headhunter_user_id']=$id;
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
        $this->display('receive_edit_page');
    }



}