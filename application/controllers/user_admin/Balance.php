<?php
//猎头余额列表

class Balance extends PC_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('user_profile_model');
        $this->load->model('balance_statements_model');
        $this->load->model('balance_withdraw_order_model');
        $this->load->model('balance_withdraw_profile_model');
        $this->load->model('user_balance_model');
        $this->go_url = $this->data['admin_path']."/balance/balance_list";
        $this->data['authority'] = $this->authority;
    }

    //猎头余额列表
    public function balance_list() {
        if($this->uri->segment(4)) {
            $this->data['message'] = $this->uri->segment(4);
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->display('balance_list');
    }
    //ajax获取管理员数据加载到列表

    /**
     *
     */
    public function ajax_balance_list() {
        $base_url=$this->config->item('oss_path');
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_user_balance.id) like'] = '%'.trim($search).'%';
        $where['user_profile.is_deleted'] = 0;
        if(isset($data['search_field']) && $data['search_field']){
            $where['sz_id_verfication.real_name like'] = '%'.trim($data['search_field']).'%';
        }
        $this->data['count'] = $this->user_balance_model->getCount($where,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = 'user_profile.id desc';
            $admin_list = $this->user_balance_model->getBalanceList($where,$length,$start,$order_by,'user_balance.*,id_verfication.real_name,user_profile.*',true);
            foreach($admin_list as $key => &$value) {
                $value['avatar_url']=$this->default_img($base_url.$value['avatar_url']);
                if($value['gender'] == 0){
                    $value['gender_name']='女';
                }else{
                    $value['gender_name']='男';
                }
                $admin_list[$key]['balance'] =(int)$value['balance']/100;
                $admin_list[$key]['frozen_amount'] =(int)$value['frozen_amount']/100;
               //余额
                $value['yue']=(int)$value['frozen_amount'] + (int)$value['balance'];
                $edit_url = $this->edit_url('balance','income_details',$value['user_id'],'收入明细','btn-primary');
                $show_url = $this->edit_url('balance','presentation_record',$value['user_id'],'提现记录','btn-yellow');
                $look_url = $this->edit_url('balance','cash_account',$value['user_id'],'提现账户','btn-purple');
                $value['check'] = $this->get_check($value['id']);
                $value['operate'] = $edit_url.$show_url.$look_url;
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
    //收入明细
    public function income_details(){
        $user_id = $this->uri->segment(4);
        $this->assign('user_id',$user_id);
        $this->display('income_list');

    }
    //ajax获取管理员数据加载到列表
    public function ajax_income_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(id) like'] = '%'.trim($search).'%';
        $where['user_id'] = $data['user_id'];
        $where['direction']=0;
        $this->data['count'] = $this->balance_statements_model->getCount($where);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = 'id desc';
            $admin_list = $this->balance_statements_model->getBalanceList($where,$length,$start,$order_by,'*');
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






    //提现账户
    public function cash_account(){
        $user_id = $this->uri->segment(4);
        $this->assign('user_id',$user_id);
        $this->display('account_list');
    }
    //ajax获取管理员数据加载到列表
    public function ajax_account_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(id) like'] = '%'.trim($search).'%';
        $where['user_id'] = $data['user_id'];
        $this->data['count'] = $this->balance_withdraw_profile_model->getCount($where);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = 'id desc';
            $admin_list = $this->balance_withdraw_profile_model->getBalanceList($where,$length,$start,$order_by,'*');
            foreach($admin_list as $key => &$value) {
                if($value['issuer'] == 0){
                    $value['issuer']='支付宝';
                }elseif ($value['issuer'] == 1){
                    $value['issuer']='微信';
                }else{
                    $value['issuer']='银行卡';
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

}