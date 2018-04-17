<?php
//猎头余额列表
class Withdraw extends PC_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('balance_withdraw_order_model');
        $this->load->model('balance_withdraw_profile_model');
        // 企业提现列表
        $this->load->model('balance_recharge_model');
        $this->load->model('user_balance_model');
        $this->load->model('firm_profile_model');
        $this->load->model('user_profile_model');
        $this->load->model('user_message_model');
        $this->load->model('sms_log_model');
        $this->load->model('Admin_model');
        $this->go_url = $this->data['admin_path']."/withdraw/alipay_cash_audit";
        $this->data['authority'] = $this->authority;
    }

    //提现审核列表
    public function alipay_cash_audit() {
        if($this->uri->segment(4)) {
            $this->data['message'] = $this->uri->segment(4);
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->display('alipay_list');
    }
    //ajax获取管理员数据加载到列表
    public function ajax_alipay_list() {
        $base_url=$this->config->item('oss_path');
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_balance_withdraw_order.id) like'] = '%'.trim($search).'%';
/*        //必须是支付宝方式提现
        $where['sz_balance_withdraw_profile.issuer']=0;*/

        //必须为未打款
        $where['sz_balance_withdraw_order.is_approved']=0;

        if(isset($data['search_field']) && $data['search_field']){
            $where['sz_balance_withdraw_profile.name like'] = '%'.trim($data['search_field']).'%';
        }
        $this->data['count'] = $this->balance_withdraw_profile_model->getCount($where,false,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = 'balance_withdraw_order.id desc';
            $admin_list = $this->balance_withdraw_profile_model->getBalanceList($where,$length,$start,$order_by,'user_profile.phone_number,user_profile.avatar_url,user_profile.nickname,id_verfication.real_name,balance_withdraw_profile.account,balance_withdraw_profile.name,balance_withdraw_profile.user_type,balance_withdraw_profile.user_id,balance_withdraw_profile.id,balance_withdraw_profile.issuer,balance_withdraw_order.id as ban_id,balance_withdraw_order.amount,balance_withdraw_order.create_time,balance_withdraw_order.approved_time',false,true);
             foreach($admin_list as $key => &$value) {
                if($value['user_type']==1){
                    $value['user_type']='企业';
                    //发起人
                    $company=$this->firm_profile_model->checkFirm(array('id'=>$value['user_id']));
                    $value['user_name']=$company['name'];
                }else{
                    $value['user_type']='求职顾问';
                    //发起人
                    $user=$this->user_profile_model->checkProfile(array('id'=>$value['user_id']));
                    $value['user_name']=$user['nickname'];
                }
                //提现账户
                switch ($value['issuer']) {
                    case '1':
                        $value['issuer']='支付宝';
                        break;                    
                    case '2':   
                        $value['issuer']='微信';
                        break;                    
                    case '1':
                        $value['issuer']='对公打款';
                        break;
                    
                    default:
                        $value['issuer']='未完待续';
                        break;
                }
                $admin_list[$key]['amounts'] =$value['amount']/100;
                // $edit_url = $this->edit_url('withdraw','edit',$value['ban_id'],'设为已打款','btn-primary');
                /*弹窗的设置*/
                $edit_url = $this->edit_url('withdraw','edit',$value['ban_id'],'设为已打款','btn-primary');
                if($value['user_type']==1){
                    /*企业用户详情*/ 
                    $show_url = $this->edit_url('firm','edit_page',$value['user_id'],'用户详情','btn-yellow');
                }else{
                    /*顾问-猎头用户详情*/
                    $show_url = $this->edit_url('headhunter','edit_page',$value['user_id'],'用户详情','btn-yellow');

                }
                $value['check'] = $this->get_check($value['ban_id']);
                $value['operate'] = $edit_url.$show_url;
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
    //支付宝提现设为已打款   要加一条日志哦~告诉用户已经打款了-扣除用户的冻结金额
    public function edit(){
        $where['id'] =$user_message_id=$this->uri->segment(4);
        $data['is_approved']=1;
        $data['approved_time']=$this->time_retuen();
        $res=$this->balance_withdraw_order_model->editBalance($where,$data);
        $res_row=$this->balance_withdraw_order_model->checkBalance($where);
        // 扣除用户的冻结余额
        $user_order=$this->balance_withdraw_order_model->getBalanceOne($where);
        // 判断企业还是用户  1企业 2用户
        if($user_order['user_type']==2){
            $user_balace=$this->user_balance_model->checkBalance(array("user_id"=>$user_order['user_id']),"frozen_amount,balance");
            $balance_change_down=$user_balace['balance']+$user_balace['frozen_amount']-$user_order['amount'];
            $user_editBalance=$this->balance_withdraw_order_model->editBalance($where,array(
                    "user_money"=>$balance_change_down,
                ));
            $reduce_frozen_amount=$user_balace['frozen_amount']-$user_order['amount'];
            $user_balace=$this->user_balance_model->editBalance(array("user_id"=>$user_order['user_id']),array(
                "frozen_amount"=>$reduce_frozen_amount,
                ));
            // 用户提现短信及通知
            //加入队列等待发送
            // $sms_data=$this->config->item("sms_data");
            $user_row=$this->user_profile_model->checkProfile(array('id'=>$res_row['user_id']));
            $sms_data=array($res_row['create_time'],($res_row['amount']/100));
            $sms_add=$this->sms_log_model->add(array(
                    "mobile"=>$user_row['phone_number'],
                    "template_id"=>205025,
                    "params"=>json_encode($sms_data),
                    "create_time"=>date("Y-m-d H:i:s")
                ));
            if($user_balace){
                $messge_add=$this->user_message_model->addMessage(array(
                    'user_id'=>$res_row['user_id'],
                    'user_type'=>0, //顾问
                    'create_time'=>$this->time_retuen(),
                    'content'=>"您于".$res_row['create_time']."申请的".($res_row['amount']/100)."提现,已打入制定账号",
                    ));
            }
            
        }elseif ($user_order['user_type']==1) {
            // 企业提现
            $company_money=$this->firm_profile_model->checkFirm(array('id'=>$user_order['user_id']));
            $reduce_frozen_amount=$company_money['forzen_money']-$user_order['amount'];
            $company_money=$this->firm_profile_model->editFirm(array('id'=>$user_order['user_id']),array('forzen_money'=>$reduce_frozen_amount));
            // 企业提现短信及通知
            $company=$this->firm_profile_model->checkFirm(array('id'=>$res_row['user_id']));
            $sms_data=array($res_row['create_time'],($res_row['amount']/100));
            $sms_add=$this->sms_log_model->add(array(
                    "mobile"=>$company['phone_number'],
                    "template_id"=>205025,
                    "params"=>json_encode($sms_data),
                    "create_time"=>date("Y-m-d H:i:s")
                ));

            if($company_money){
                $messge_add=$this->user_message_model->addMessage(array(
                    'user_id'=>$res_row['user_id'],
                    'user_type'=>1,
                    'create_time'=>$this->time_retuen(),
                    'content'=>"您于".$res_row['create_time']."申请的".($res_row['amount']/100)."提现,已打入制定账号",
                    ));
            }
        }
        if($res){
            // $this->location_href('/user_admin/withdraw/alipay_cash_audit');
            $this->location_href($this->go_url."/14");
        }else{
            $this->location_href($this->go_url."/15");
        }
    }
    //打款记录
    public function alipay_presentation_record() {
        if($this->uri->segment(4)) {
            $this->data['message'] = $this->uri->segment(4);
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->display('alipay_presentation_list');
    }
    //ajax获取管理员数据加载到列表
    public function ajax_record_list() {
        $base_url=$this->config->item('oss_path');
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_balance_withdraw_order.id) like'] = '%'.trim($search).'%';
/*        //必须是支付宝方式提现
        $where['sz_balance_withdraw_profile.issuer']=0;*/
        if(isset($data['search_field']) && $data['search_field']){
            $where['sz_balance_withdraw_profile.name like'] = '%'.trim($data['search_field']).'%';
        }
        //必须为已打款
        // 用户类型 1.表示企业用户 2.表示个人
        $where['sz_balance_withdraw_order.is_approved']=1;
        $this->data['count'] = $this->balance_withdraw_profile_model->getCount($where,false,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = 'balance_withdraw_order.id desc';
            $admin_list = $this->balance_withdraw_profile_model->getBalanceList($where,$length,$start,$order_by,'user_profile.phone_number,user_profile.avatar_url,user_profile.nickname,id_verfication.real_name,balance_withdraw_profile.account,balance_withdraw_profile.name,balance_withdraw_profile.user_type,balance_withdraw_profile.user_id,balance_withdraw_profile.id,balance_withdraw_profile.issuer,balance_withdraw_order.id as ban_id,balance_withdraw_order.amount,balance_withdraw_order.create_time,balance_withdraw_order.approved_time',false,true);
            foreach($admin_list as $key => &$value) {
                if($value['user_type']==1){
                    $value['user_type']='企业';
                    //发起人
                    $company=$this->firm_profile_model->checkFirm(array('id'=>$value['user_id']));
                    $value['user_name']=$company['name'];
                }else{
                    $value['user_type']='求职顾问';
                    //发起人
                    $user=$this->user_profile_model->checkProfile(array('id'=>$value['user_id']));
                    $value['user_name']=$user['nickname'];
                }
                //提现账户
                switch ($value['issuer']) {
                    case '1':
                        $value['issuer']='支付宝';
                        break;                    
                    case '2':
                        $value['issuer']='微信';
                        break;                    
                    case '1':
                        $value['issuer']='对公打款';
                        break;
                    
                    default:
                        $value['issuer']='未完待续';
                        break;
                }
                $value['avatar_url']=$this->default_img($base_url.$value['avatar_url']);
                $admin_list[$key]['amount'] =(int)$value['amount']/100;
                $value['check'] = $this->get_check($value['ban_id']);


                $edit_url = $this->edit_url('#','#',$value['id'],'已经打款','btn-success');
                if($value['user_type']==1){
                    /*企业用户详情*/ 
                    $show_url = $this->edit_url('firm','edit_page',$value['user_id'],'用户详情','btn-yellow');
                }else{
                    /*顾问-猎头用户详情*/
                    $show_url = $this->edit_url('headhunter','edit_page',$value['user_id'],'用户详情','btn-yellow');

                }
                $value['check'] = $this->get_check($value['id']);
                $value['operate'] = $edit_url.$show_url;
                if(!$value['operate']) {
                    $value['operate'] = '无操作';
                }

            }
            $aaData = $admin_list;
        }
        // var_dump($aaData);exit;
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count'];
        $output['iTotalRecords'] = $this->data['count'];
        echo json_encode($output);
    }
    //企业充值记录
    public function card_cash_audit() {
        if($this->uri->segment(4)) {
            $this->data['message'] = $this->uri->segment(4);
        }
        $this->display('cash_list');
    }
    //ajax获取管理员数据加载到列表
    public function ajax_cash_list() {
        $base_url=$this->config->item('oss_path');
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(id) like'] = '%'.trim($search).'%';
        $where['user_type'] = 1;
        $where['status'] = 2;
        /*sz_firm_profile 企业的提现记录 user_type=1 2位用户*/
        $this->data['count'] = $this->balance_recharge_model->getCount($where,false,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = 'id desc';
            $balance_recharge_list = $this->balance_recharge_model->getBalance_rechargeList($where,$length,$start,$order_by);
            foreach($balance_recharge_list as $key => &$value) {
                //获取公司信息
                $company=$this->firm_profile_model->checkFirm(array('id'=>$value['user_id']));
                $value['company_name']=$company['name'];
                // 支付方式
                $pay_type=$this->config->item("pay_type");
                $value['pay_way']=$pay_type[$value['pay_type']];
                $value['money']=$value['money']/100;
                //操作员
                if($value['admin_id']==NULL){
                    $value['caozuoyuan']=$value['company_name'];
                }else{
                    $admin_one=$this->Admin_model->checkAdmin(array("id"=>$value["admin_id"]));
                    $value['caozuoyuan']=$admin_one['realname'];
                }
                //创建时间
                // $value['create_time']=date("Y-m-d H:i:s",$value['create_time']);
                // 状态
                $pay_status=$this->config->item("pay_status");
                $value['status']=$pay_status[$value['status']];



            }
            $aaData = $balance_recharge_list;
        }
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count'];
        $output['iTotalRecords'] = $this->data['count'];
        echo json_encode($output);
    }
    //银行卡提现设为已打款
    public function edits(){
        $where['id'] = $this->uri->segment(4);
        $data['is_approved']=1;
        $data['approved_time']=$this->time_retuen();
        $res=$this->balance_withdraw_order_model->editBalance($where,$data);
        if($res){
            $this->location_href('/user_damin/withdraw/card_presentation_record');
        }else{
            $this->location_href($this->go_url."/3");
        }
    }
    //银行卡提现记录列表
    public function card_presentation_record() {
        if($this->uri->segment(4)) {
            $this->data['message'] = $this->uri->segment(4);
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->display('cash_presentation_list');
    }
    //ajax获取管理员数据加载到列表
    public function ajax_presentation_list() {
        $base_url=$this->config->item('oss_path');
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_balance_withdraw_order.id) like'] = '%'.trim($search).'%';
        //必须是银行卡方式提现
        $where['sz_balance_withdraw_profile.issuer']=2;
        if(isset($data['search_field']) && $data['search_field']){
            $where['sz_user_profile.phone_number like'] = '%'.trim($data['search_field']).'%';
        }
        //必须为已打款
        $where['sz_balance_withdraw_order.is_approved']=1;
        $this->data['count'] = $this->balance_withdraw_profile_model->getCount($where,false,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = 'balance_withdraw_order.id desc';
            $admin_list = $this->balance_withdraw_profile_model->getBalanceList($where,$length,$start,$order_by,'user_profile.phone_number,user_profile.avatar_url,user_profile.nickname,id_verfication.real_name,balance_withdraw_profile.account,balance_withdraw_profile.name,balance_withdraw_profile.extra_info,balance_withdraw_profile.issuer,balance_withdraw_order.id as ban_id,balance_withdraw_order.amount,balance_withdraw_order.create_time,balance_withdraw_order.approved_time',false,true);
            foreach($admin_list as $key => &$value) {
                $value['avatar_url']=$this->default_img($base_url.$value['avatar_url']);
                $admin_list[$key]['amount'] =(int)$value['amount']/100;
                if($value['issuer'] == 2){
                    $admin_list[$key]['issuer']="中国银行";
                }
                $value['check'] = $this->get_check($value['ban_id']);

            }
            $aaData = $admin_list;
        }
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count'];
        $output['iTotalRecords'] = $this->data['count'];
        echo json_encode($output);
    }
}