<?php
class Census extends PC_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('user_message_model');
        $this->load->model('user_profile_model');
        $this->load->model('firm_profile_model');
        $this->go_url = $this->data['admin_path']."/census/census_list";
        $this->data['authority'] = $this->authority;
    }

    //统计页面
    public function census_list() {
        if($this->uri->segment(4)) {
            $this->assign('message', $this->uri->segment(4));
        }
        $now_month = date("Y-m",time());
        $now_day=date("Y-m-d",time());
        //按月搜索
        $this->data['start_time'] =  empty($_POST) ? $now_month : $_POST['start_time'];
        //按日搜索
        $this->data['starts_time'] = empty($_POST) ? $now_day : $_POST['starts_time'];
        //猎头的限制条件
        $arr['is_hunter']=2;
        $arr['is_deleted']=0;
        // $arr['user_message.user_type']=0;
        // $arr1['user_headhunter_extra_info.is_activated']=1;
        // $arr1['user_headhunter_extra_info.is_deleted']=0;
        // $arr1['user_message.user_type']=0;
        //求职者的限制条件
        $brr['is_seeker']=2;
        $brr['is_deleted']=0;
        // $brr1['user_jobhunter_extra_info.is_deleted ']=0;
        // $brr1['user_message.user_type']=1;
        //公司的限制条件
        $crr['is_deleted'] = 0;
        $crr1['is_deleted'] = 0;
        /**统计总的猎头数，求职者数，企业数**/
        $count1=$this->user_profile_model->getCount($arr);
        $count2=$this->user_profile_model->getCount($brr);
        $count3=$this->firm_profile_model->getCount($crr);
        /**统计当月的猎头数，求职者数，企业数**/
        $arr['create_time >=']= $this->data['start_time'];
        $arr['create_time <'] = date('Y-m',mktime(23, 59, 59, date('m',strtotime($this->data['start_time']))+2, 00));//指定月份月末时间戳
        $brr['create_time >='] = $this->data['start_time'];
        $brr['create_time <'] =date('Y-m',mktime(23, 59, 59, date('m',strtotime($this->data['start_time']))+2, 00));//指定月份月末时间戳
        $crr['create_time >=']=$this->data['start_time'];
        $crr['create_time <'] =date('Y-m',mktime(23, 59, 59, date('m',strtotime($this->data['start_time']))+2, 00));//指定月份月末时间戳
        $count4=$this->user_profile_model->getCount($arr);
        $count5=$this->user_profile_model->getCount($brr);
        $count6=$this->firm_profile_model->getCount($crr);
        /**统计当天的猎头数，求职者数，企业数**/
        $arr1['create_time >='] = ($this->data['starts_time'].' '.'00:00:00');
        $arr1['create_time <'] = ($this->data['starts_time'].' '.'23:59:59');//指定日末时间戳
        $brr1['create_time >='] = ($this->data['starts_time'].' '.'00:00:00');
        $brr1['create_time <'] = ($this->data['starts_time'].' '.'23:59:59');//指定日末时间戳
        $crr1['create_time >=']= ($this->data['starts_time'].' '.'00:00:00');
        $crr1['create_time <']= ($this->data['starts_time'].' '.'23:59:59');//指定日末时间戳
        $count7=$this->user_profile_model->getCount($arr1);
        $count8=$this->user_profile_model->getCount($brr1);
        $count9=$this->firm_profile_model->getCount($crr1);
        $this->assign('count1',$count1);
        $this->assign('count2',$count2);
        $this->assign('count3',$count3);
        $this->assign('count4',$count4);
        $this->assign('count5',$count5);
        $this->assign('count6',$count6);
        $this->assign('count7',$count7);
        $this->assign('count8',$count8);
        $this->assign('count9',$count9);
        $this->display('census_list');
    }

}
?>