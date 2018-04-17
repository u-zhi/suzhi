<?php
//实习任务
class Jobhunter extends PC_Controller {
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

        $this->go_url = $this->data['admin_path']."/jobhunter/jobhunter_list";
        $this->gos_url = $this->data['admin_path']."/jobhunter/enroll_list";
        $this->data['authority'] = $this->authority;
    }

    //公司列表
    public function jobhunter_list() {
        if($this->uri->segment(4)) {
            $this->data['message'] = $this->uri->segment(4);
        }

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

        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->data['user_jobhunter_extra_info_highest_degree'] =  empty($_POST) ? '' : $_POST['user_jobhunter_extra_info_highest_degree'];
        $this->display('jobhunter_list');
    }
    //ajax获取管理员数据加载到列表
    public function ajax_jobhunter_list() {
        $base_url=$this->config->item('oss_path');
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_user_profile.id) like'] = '%'.trim($search).'%';

        if($data['province_id']) {
            $where['sz_user_jobhunter_extra_info.city_id like'] = "#".$data['province_id']."#%";
        }
        if($data['city_id']) {
            $where['sz_user_jobhunter_extra_info.city_id like '] = "#".$data['city_id']."#%";
        }
        $where['user_profile.is_deleted'] = 0;
        $where['user_profile.is_seeker'] = 2;
        if(isset($data['search_field']) && $data['search_field']){
            $where['sz_user_profile.nickname like'] = '%'.trim($data['search_field']).'%';
        }
        if(isset($data['highest_degree']) && $data['highest_degree']){
            $where['sz_user_jobhunter_extra_info.highest_degree'] = $data['highest_degree'];
        }
        $this->data['count'] = $this->user_profile_model->getCount($where,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = 'user_profile.id desc';
            $admin_list = $this->user_profile_model->getProfileList($where,$length,$start,$order_by,'user_profile.*,user_message.user_type,user_jobhunter_extra_info.*',true);
            foreach($admin_list as $key => &$value) {
                $value['avatar_url']=$this->default_img($base_url.$value['avatar_url']);
                //获取城市名称
                $county=$this->base_region_model->checkRegion(array('region_id'=>$value['city_id']));
                $value['city_name']=$county['region_name'];
                if($value['gender'] == 0){
                    $value['gender_name']='女';
                }else{
                    $value['gender_name']='男';
                }
                if($value['highest_degree'] == 0){
                    $value['highest_degree_name']='小学';
                }elseif ($value['highest_degree'] == 1){
                    $value['highest_degree_name']='初中';
                }elseif ($value['highest_degree'] == 2){
                    $value['highest_degree_name']='高中';
                }elseif ($value['highest_degree'] == 3){
                    $value['highest_degree_name']='大专';
                }elseif ($value['highest_degree'] == 4){
                    $value['highest_degree_name']='本科学士';
                }elseif ($value['highest_degree'] == 5){
                    $value['highest_degree_name']='硕士';
                }elseif ($value['highest_degree'] == 6){
                    $value['highest_degree_name']='博士';
                }else{
                    $value['highest_degree_name']='博士后';
                }
                $edit_url = $this->edit_url('jobhunter','edit_page',$value['user_id'],'用户详情','btn-primary');
                $show_url = $this->edit_url('jobhunter','show_resume',$value['user_id'],'查看简历','btn-yellow');
                // $del_url = $this->delete_url('/jobhunter/delete',$value['user_id'],'封号','btn-purple');
                $value['check'] = $this->get_check($value['id']);
                $value['operate'] = $edit_url.$show_url/*.$del_url*/;
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
        //添加封号表
        $crr['user_id']=$data['id'];
        $crr['phone_number']=$a['phone_number'];
        $crr['block_type']= 1;
        $crr['is_deleted']= 1;
        $crr['create_time']= $this->time_retuen();
        //添加封号表
        $ress=$this->block_list_model->addBlock($crr);
        //更新个人信息求职者补充信息
        $result=$this->user_jobhunter_extra_info_model->editJobhunter($arr,$brr);
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
        $user_info=$this->user_profile_model->checkProfile($where,"user_profile.*,user_message.user_type,user_jobhunter_extra_info.*",true);
        $user_info['avatar_url']=$base_url.$user_info['avatar_url'];
        //获取所有的城市
        $city=$this->base_city_model->getBaseAll();
        $this->assign('city',$city);
        $this->assign('data', $user_info);
        $this->display('jobhunter_edit');
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
        $crr['update_time']=$drr['update_time']=$this->time_retuen();
        $drr['city_id']=$data['city_id'];
        $drr['highest_degree']=$data['highest_degree'];
        $drr['work_email']=$data['work_email'];
        $drr['work_year']=$data['work_year'];
        $res=$this->user_profile_model->editProfile($brr,$crr);
        $result=$this->user_jobhunter_extra_info_model->editJobhunter($arr,$drr);
        if($res && $result) {
            $this->location_href($this->go_url."/2");
        }else {
            $this->location_href($this->go_url."/3");
        }
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
        $this->display('jobhunter_resume');
    }
    /**
     * @brief	导出功能操作
     */
    public function export_excel() {
        $this->user_id = $this->uri->segment(4);
        //获取用户基本信息
        $base_url=$this->config->item('oss_path');
        $arr1=$this->user_profile_model->checkProfile(array('id'=>$this->user_id),'nickname,avatar_url,gender,birthday,phone_number');
        $arr3=$this->user_jobhunter_extra_info_model->checkJobhunter(array('user_id'=>$this->user_id),'user_jobhunter_extra_info.*,base_city.city_name',true);
        $user_info['avatar_url']=$base_url.$arr1['avatar_url'];
        $user_info['avatar_url']=$this->default_img($user_info['avatar_url'],130);
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
        header("Content-type: application/vnd.ms-excel; charset=utf8");
        header("Content-Disposition: attachment; filename=". $user_info['nickname']."简历.xls");
        $data = "<style>
					.price-td td {height:40px;border:1px solid #999;text-align: center;}
				</style>
				<table class='price-td' style='border:1px solid #ddd;margin-bottom:20px;' width='60%'>";
        $data .="<tr>
                    <td colspan='3'><b>简历信息</b></td>
                </tr>
                <tr>
                    <td>姓名：".$user_info['nickname']."</td>
                    <td>联系电话：".$user_info['phone_number']."</td>
                    <td rowspan='3' colspan='1'>".$user_info['avatar_url']."</td>
               </tr>
                <tr >
                    <td rowspan='2' colspan='2' style='text-align: left'>跟进：</td>
                </tr>
                <tr >
                </tr>
                <tr>
                    <td colspan='3'><b>基本信息</b></td>
                </tr>
                <tr>
                    <td >所在城市：".$user_info['city_name']."</td>
                    <td >性别：".$user_info['gender']."</td>
                    <td >生日：".$user_info['birthday']."</td>
               </tr>
                <tr>
                    <td >最高学历：".$user_info['highest_degree_name']."</td>
                    <td >工作年限：".$user_info['work_year']."</td>
                    <td >联系邮箱：".$user_info['work_email']."</td>
                </tr>
                <tr>
                    <td colspan='3'><b>工作经历</b></td>
                </tr>";

          foreach ($work_experience as $key =>$value){
            $data .="<tr>
                <td colspan='1'>".$value['start_time']."--".$value['end_time']."</td>
                <td colspan='2'>".$value['firm_name']."/".$value['occupation']."</td>
            </tr>
            <tr>
                <td colspan='3' style='text-align: left'>".$value['content']."</td>
            </tr>";
            }
           $data .= "<tr>
                <td colspan='3'><b>教育经历</b></td>
            </tr>";
          foreach ($edu_background as $key =>$value){
          $data .="<tr>
                <td >".$value['start_time']."--".$value['end_time']."</td>
                <td >".$value['school_name']."</td>
                <td >".$value['degree'].".".$value['major']."</td>
            </tr>";
             }
        $data .="<tr>
                <td colspan='3'><b>期望工作</b></td>
            </tr>
            <tr>
                <td colspan='1'>".$job_intention[0]['occupation']."</td>
                <td colspan='2'>".$job_intention[0]['job_type']."/".$job_intention[0]['city']."/".$job_intention[0]['wage_lower']."--".$job_intention[0]['wage_upper']."</td>
            </tr>
            <tr>
                <td colspan='3'><b>项目经历</b></td>
            </tr>";
         foreach ($project_experience as $key =>$value){
         $data .="<tr>
                <td colspan='1'>".$value['start_time']."--".$value['end_time']."</td>
                <td colspan='2'>".$value['project_name']."/".$value['responsibility']."</td>
            </tr>
            <tr>
                <td colspan='3' style='text-align: left'>".$value['description'].".".$value['project_url']."</td>
            </tr>";
         }
        $data .="<tr>
                <td colspan='3'><b>技能标签</b></td>
            </tr>";
         $data .="<tr>
                <td colspan='3' style='text-align: left'>";
                foreach ($skill_tag as $key =>$value){
                   $data .= $value['tag'] ." ";
                }
        $data .="</td>
            </tr>";
            $data .="<tr>
                <td colspan='3'><b>自我描述</b></td>
            </tr>
            <tr>
                <td colspan='3' style='text-align: left'>".$self_intro[0]['content']."</td>
            </tr>";
        $data .= "</table>";
        echo $data. "\t";
        exit;
    }

        /*------------------------------------------求职者任务开始---------------------------------------------------------*/
    /**
     * @brief   已报名列表(全职和实习)
     */
    public function enroll_list() {
        // if($this->uri->segment(4)) {
        //     var_dump(asdasdad);exit;
        //     $this->assign('message', $this->uri->segment(4));
        // }
        $base_url=$this->config->item('oss_path');
        $user_id = $this->uri->segment(4);
        $where['sz_user_profile.id'] = $user_id;
        $user_info=$this->user_profile_model->checkProfile($where,"user_profile.*,user_message.user_type,user_jobhunter_extra_info.*",true);
        $user_info['avatar_url']=$base_url.$user_info['avatar_url'];
        //获取所有的城市
        $city=$this->base_city_model->getBaseAll();
        $this->assign('city',$city);
        $this->assign('user_id',$user_id);
        $this->assign('data', $user_info);
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->data['user_id'] =  $user_id;

        $this->display('jobhunter_enroll_list');
    }
    //列表
    public function ajax_enroll_list() {
        $data = $_GET;
        $user_id = $this->uri->segment(4);
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(sz_jobhunter_order_profile.id) like'] = '%'.trim($search).'%';
        $where['sz_jobhunter_order_profile.user_id'] = $data['user_id'];
//        $where['task_profile.is_deleted']=0;
//        $where['user_profile.is_deleted']=0;
//        $where['task_profile.task_type = 1 or task_profile.task_type = ']=2;
//        $where['jobhunter_order_profile.is_problem']=0;
        $user_idsql="jobhunter_order_profile.user_id = ".$data['user_id'];
        $where  =" $user_idsql";
        $where .=" and task_profile.is_deleted = 0";
        $where .=" and user_profile.is_deleted = 0";
        $where .=" and (task_profile.task_type = 1 or task_profile.task_type = 2)";
        $where .=" and jobhunter_order_profile.is_problem = 0";
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
                $edit_url = $this->edit_url('jobhunter','edit_pages',$value['id'],'查看','btn-success');
                // $show_url = $this->edit_url('jobhunter','show_resume',$value['user_id'],'查看简历','btn-yellow');
//                $modify_url = $this->edit_url('order','modify_status',$value['id'],'修改订单状态','btn-purple');
                $value['check'] = $this->get_check($value['id']);
//                $value['operate'] = $edit_url.$show_url.$modify_url;
                $value['operate'] = $edit_url/*.$show_url*/;
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
     * @brief   求职者任务的查看
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
        $this->display('jobhunter_order_edits');
    }



}