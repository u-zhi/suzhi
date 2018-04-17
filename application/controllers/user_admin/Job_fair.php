<?php
//实习任务
class Job_fair extends PC_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('user_profile_model');
        $this->load->model('base_city_model');
        $this->load->model('user_jobhunter_extra_info_model');
        $this->load->model('block_list_model');
        $this->load->model('cv_work_experience_model');
        $this->load->model('cv_edu_background_model');
        $this->load->model('cv_job_intention_model');
        $this->load->model('cv_project_experience_model');
        $this->load->model('cv_self_intro_model');
        $this->load->model('cv_skill_tag_list_model');
        $this->load->model('cv_skill_tag_rel_model');

        // 城市信息
        $this->load->model('base_region_model');

        // 求职者订单信息
        $this->load->model('jobhunter_order_profile_model');
        $this->load->model('headhunter_order_profile_model');
        $this->load->model('base_occupation_model');
        $this->load->model('base_county_model');
        $this->load->model('firm_profile_model');


        /*招聘会*/
        // 企业招聘会参加记录
        $this->load->model('company_job_fair_model');
        // 招聘会人才
        $this->load->model('job_fair_user_model');
        // 招聘会摊位
        $this->load->model('job_fair_position_model');
        // 线上招聘会
        $this->load->model('job_fair_model');
        // 职位
        $this->load->model('base_major_model');
        


        $this->go_url = $this->data['admin_path']."/job_fair/job_fair_position";
        $this->go_url_allpeopele_postion = $this->data['admin_path']."/job_fair/allpeopele_postion";
        $this->data['authority'] = $this->authority;
    }
    /*招聘会列表*/
    public function job_fair_position() {

        if($this->uri->segment(4)) {
            $this->data['message'] = $this->uri->segment(4);
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->display('job_fair_position_list');

    }    
    /*招聘会列表ajax*/
    public function ajax_job_fair_position_list() {
        $base_url=$this->config->item('oss_path');
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(id) like'] = '%'.trim($search).'%';
        if(isset($data['search_field']) && $data['search_field']){
            $where['name like'] = '%'.trim($data['search_field']).'%';
        }
        $this->data['count'] = $this->job_fair_model->getCount($where,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = 'id desc';
            $admin_list = $this->job_fair_model->getJob_fairList($where,$length,$start,$order_by);
            $status=array(
                '1'=>'未开始',
                '2'=>'进行中',
                '3'=>'已经结束'
                );
            foreach($admin_list as $key => &$value) {
                //招聘会时间
                    $value['time']=$value['begin_time']."-".$value['end_time'];
                //职位
                    $base_majorp=json_decode($value['tag'],true);
                    $base_majorp_new=implode("--",$base_majorp);
                    $value['occupation_parent_name']=$base_majorp_new;
                //参会求职者人数
                    $wherejob['job_fair_id']=$value["id"];
                    $job_fair_user_sum=$this->job_fair_user_model->getCount($wherejob);
                    $value['all_job_sum']=$job_fair_user_sum;
                //参会企业人数
                    $wherecompany['job_fair_id']=$value["id"];
                    $company_job_fair_sum=$this->company_job_fair_model->getCount($wherecompany);
                    $value['all_hunter_sum']=$job_fair_user_sum;
                //本期基础摊位报价
                    $whereposition['job_fair_id']=$value["id"];
                    $job_fair_positionfirst=$this->job_fair_position_model->checkJob_fair_position($whereposition,"min(money) as min_money");
                    $job_fair_interview_number=$this->job_fair_position_model->checkJob_fair_position($whereposition,"min(money) as interview_number");
                    $value['money']=$job_fair_positionfirst['min_money'];
                //回报
                    $value['huibao']="招聘会内".$job_fair_interview_number['interview_number']."次邀请面试招聘会职位发布";
                //实际参会企业
                    $value['yes_company']=$job_fair_user_sum;
                //实际参会求职者
                    $wherejobtype['job_fair_id']=$value["id"];
                    $wherejobtype['add_type']=1;
                    $job_fair_user_sum=$this->job_fair_user_model->getCount($wherejobtype);
                    $value['yes_job']=$job_fair_user_sum;
                //获取城市名称
                $county=$this->base_region_model->checkRegion(array('region_id'=>$value['city_id']));
                $value['city_id']=$county['region_name'];
                // 状态
                $value['status']=$status[$value['status']];

                $edit_url = $this->edit_url('job_fair','allpeopele_postion',$value['id'],'会场人才库','btn-yellow');
                $del_url = $this->edit_url('job_fair','close_position',$value['id'],'关闭','btn-red');
                $value['check'] = $this->get_check($value['id']);
                $value['operate'] = $edit_url;
                $value['del']=$del_url;

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

     /*招聘会会场人才库*/
    public function allpeopele_postion() {
        $job_fair_id= $this->uri->segment(4);
        /*搜索城市*/
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
        // var_dump($this->data['parent_list']);exit;
        $this->data['city_list'] = $city_arr['province_'.$parent_list[0]['region_id']];
        $this->data['city_json'] = json_encode($city_arr);
        $this->data['province_id'] =  empty($_POST) ? '' : $_POST['province_id'];
        $this->data['job_fair_id'] = $job_fair_id;

        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->display('allpeopele_postion_list');

    }    
    // 招聘会人才库列表
    public function ajax_allpeopele_postion_list() {
        $base_url=$this->config->item('oss_path');
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(id) like'] = '%'.trim($search).'%';
        $where['job_fair_id'] = $data['job_fair_id'];

        if($data['province_id']) {
            $where['city_id like'] = "#".$data['province_id']."#%";
        }
        if($data['city_id']) {
            $where['city_id like '] = "#".$data['city_id']."#%";
        }
        if(isset($data['search_field']) && $data['search_field']){
            $where['name like'] = '%'.trim($data['search_field']).'%';
        }
        $this->data['count'] = $this->job_fair_user_model->getCount($where,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = 'id desc';
            $admin_list = $this->job_fair_user_model->getJob_fair_userList($where,$length,$start,$order_by);
            foreach($admin_list as $key => &$value) {
                /*职位*/
                $base_id=explode('#',$value['position']);
                $wherebase_p['id']=$base_id[0];
                $wherebase_s['id']=$base_id[1];
                $base_majorp=$this->base_major_model->checkBase($wherebase_p);
                $base_majors=$this->base_major_model->checkBase($wherebase_s);
                $value['occupation']=$base_majorp['item']."--".$base_majors['item'];
                switch ($value['add_type']) {
                    case '1':
                            $value['type_daor']='自动参加';
                        break;                    
                    case '2':
                            $value['type_daor']='平台导入';
                        break;                    
                    case '3':
                            $value['type_daor']='纯简历';
                        break;
                    
                    default:
                        $value['type_daor']='还未开放哦，请耐心等待';
                        break;
                }
                //获取城市名称
                $county=$this->base_region_model->checkRegion(array('region_id'=>$value['city']));
                $value['city_name']=$county['region_name'];
                $show_url = $this->edit_url('job_fair','show_resume',$value['user_id'],'查看简历','btn-yellow');
                $value['check'] = $this->get_check($value['id']);
                $value['operate'] = /*$edit_url.*/$show_url/*.$del_url*/;
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


   
    /*招聘会添加页面*/
    public function job_fair_position_page() {
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
        $this->display('job_fair_position_page');
    }  

   /*招聘会添加*/
    public function job_fair_position_add() {
       $data = $_POST;
       $data_fair['name']=$data['job_fair_name'];
       $data_fair['begin_time']=$data['begin_time'];
       $data_fair['end_time']=$data['end_time'];
       $data_fair['city_id']=$data['city_id'];
       $tag=json_encode($data['tag']);
       $data_fair['tag']=$tag;
       // 线上招聘会
       $add_result=$this->job_fair_model->addJob_fair($data_fair);
       // 招聘会收费设置添加 job_fair_id
       $position_name=$data['position_name'];
       $interview_number=$data['interview_number'];
       $money=$data['money'];
       $is_top=$data['is_top'];
       $number=$data['number'];
       foreach ($position_name as $key => $value) {
            $data_position['job_fair_id']=$add_result;
            $data_position['name']=$value;
            $data_position['interview_number']=$interview_number[$key];
            $data_position['is_top']=$is_top[$key];
            $data_position['money']=$money[$key];
            $data_position['number']=$number[$key];
            $add_fair_position=$this->job_fair_position_model->addJob_fair_position($data_position);
       }
       if($add_result&&$add_fair_position) {
            $this->location_href($this->go_url."/4");
        }else {
            $this->location_href($this->go_url."/5");
        }

    }   

    /*招聘会参会求职者*/
    public function job_fair_position_jober() {

    }   
    
     /*招聘会收费设置*/
    public function pay_position_add() {

    }    

     //查看简历
    public function show_resume(){
        $this->user_id = $this->uri->segment(4);
        $this->data['user_id']=$this->uri->segment(4);
        //获取用户基本信息
        $base_url=$this->config->item('oss_path');
        $arr1=$this->user_profile_model->checkProfile(array('id'=>$this->user_id),'nickname,avatar_url,gender,birthday,phone_number');
        $arr3=$this->user_jobhunter_extra_info_model->checkJobhunter(array('user_id'=>$this->user_id),'user_jobhunter_extra_info.*,base_city.city_name',true);
        $user_info['avatar_url']=$base_url.$arr1['avatar_url'];
        $user_info['avatar_url']=$this->default_img($user_info['avatar_url'],130);
//        $uer_info['avatar_url']= '<img src="'.$user_info['avatar_url'].'" style="max-height:230px;"/>';
        $user_info['nickname']=$arr1['nickname'];
        if($arr1['gender'] == 0){
            $user_info['gender']='女';
        }else{
            $user_info['gender']='男';
        }
        $user_info['birthday']=$arr1['birthday'];
        if($arr3['highest_degree'] == 0){
            $user_info['highest_degree_name']='小学';
        }elseif ($arr3['highest_degree'] == 1){
            $user_info['highest_degree_name']='初中';
        }elseif ($arr3['highest_degree'] == 2){
            $user_info['highest_degree_name']='高中';
        }elseif ($arr3['highest_degree'] == 3){
            $user_info['highest_degree_name']='大专';
        }elseif ($arr3['highest_degree'] == 4){
            $user_info['highest_degree_name']='本科学士';
        }elseif ($arr3['highest_degree'] == 5){
            $user_info['highest_degree_name']='硕士';
        }elseif ($arr3['highest_degree'] == 6){
            $user_info['highest_degree_name']='博士';
        }else{
            $user_info['highest_degree_name']='博士后';
        }
//        $user_info['highest_degree']=$arr3['highest_degree'];
        $user_info['work_year']=$arr3['work_year'];
        $user_info['city_name']=$arr3['city_name'];
        $user_info['phone_number']=$arr1['phone_number'];
        $user_info['work_email']=$arr3['work_email'];
        //获取工作经历
        $work_experience=$this->cv_work_experience_model->getWorkAll(array('user_id'=>$this->user_id,'is_deleted'=>0),'firm_name,occupation,start_time,end_time,content','id desc');
        //获取教育经历
        $edu_background=$this->cv_edu_background_model->getEduAll(array('user_id'=>$this->user_id,'is_deleted'=>0),'school_name,major,degree,start_time,end_time','id desc');
        foreach ($edu_background as $k => $v){
            if($v['degree'] == 0){
                $edu_background[$k]['degree']='小学';
            }elseif ($v['degree'] == 1){
                $edu_background[$k]['degree']='初中';
            }elseif ($v['degree'] == 2){
                $edu_background[$k]['degree']='高中';
            }elseif ($v['degree'] == 3){
                $edu_background[$k]['degree']='大专';
            }elseif ($v['degree'] == 4){
                $edu_background[$k]['degree']='本科学士';
            }elseif ($v['degree'] == 5){
                $edu_background[$k]['degree']='硕士';
            }elseif ($v['degree'] == 6){
                $edu_background[$k]['degree']='博士';
            }else{
                $edu_background[$k]['degree']='博士后';
            }
        }
        //获取期望工作
        $job_intention=$this->cv_job_intention_model->getJobAll(array('user_id'=>$this->user_id,'is_deleted'=>0),'city,job_type,occupation,wage_lower,wage_upper','id desc');
        foreach ($job_intention as $ke =>$va){
            if($va['job_type'] == 0){
                $job_intention[$ke]['job_type']='兼职';
            }elseif ($va['job_type'] == 1){
                $job_intention[$ke]['job_type']='全职';
            }else{
                $job_intention[$ke]['job_type']='实习';
            }
        }
        //获取项目经历
        $project_experience=$this->cv_project_experience_model->getProjectAll(array('user_id'=>$this->user_id,'is_deleted'=>0),'project_name,start_time,end_time,responsibility,description,project_url','id desc');
        //获取技能标签
        $arr4=$this->cv_skill_tag_list_model->getSkillAll(array('cv_skill_tag_list.is_deleted'=>0),'cv_skill_tag_list.id,cv_skill_tag_list.tag,cv_skill_tag_rel.user_id','cv_skill_tag_list.id desc',true);
        $ress=array();
        foreach ($arr4 as $key => $value){
            if($value['user_id'] == $this->user_id){
                $ress[$key]['tag']=$value['tag'];
            }
        }
        $skill_tag=$ress;
        //获取自我描述
        $self_intro=$this->cv_self_intro_model->getSelfAll(array('user_id'=>$this->user_id,'is_deleted'=>0),'content');
        $this->assign('user_info',$user_info);
        $this->assign('work_experience',$work_experience);
        $this->assign('edu_background',$edu_background);
        $this->assign('job_intention',$job_intention);
        $this->assign('project_experience',$project_experience);
        $this->assign('skill_tag',$skill_tag);
        $this->assign('self_intro',$self_intro);
        $this->display('jobhunter_all_resume');
    }


    public function close_position()
    {

        if($this->uri->segment(4))
            $this->job_fair_model->editJob_fair(array("id"=>$this->uri->segment(4)),array("status"=>3));
        redirect("/user_admin/job_fair/job_fair_position");

    }



}