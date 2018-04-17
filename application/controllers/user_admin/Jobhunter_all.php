<?php
//实习任务
class Jobhunter_all extends PC_Controller {
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


        $this->go_url = $this->data['admin_path']."/jobhunter_all/jobhunter_list";
        $this->gos_url = $this->data['admin_path']."/jobhunter/enroll_list";
        $this->go_urlposition = $this->data['admin_path']."/jobhunter/job_fair_position";
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
        $this->display('jobhunter_all_list');
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
        $where['user_profile.is_seeker'] = 2;
        $where['user_jobhunter_extra_info.is_deleted'] = 0;
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
            $order_by = 'sz_user_jobhunter_extra_info.id desc';
            $admin_list = $this->user_profile_model->getProfileList($where,$length,$start,$order_by,'user_profile.*,user_message.user_type,user_jobhunter_extra_info.*',true);
            foreach($admin_list as $key => &$value) {
                /*还未做待确认*/
                        //获取期望工作
                // $job_intention=$this->cv_job_intention_model->checkJob(array('user_id'=>$value['user_id'],'is_deleted'=>0));
                // if (empty($job_intention)) {
                //     $value['occupation']='无';
                //     $value['lower']='无';
                // }else{
                    $value['lower']=$value['wage_lower']."-".$value['wage_upper'];               
                // }
                $value['type_daor']='求职者';
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

                $edit_url = $this->edit_url('jobhunter_all','job_edit',$value['user_id'],'编辑','btn-primary');
                $show_url = $this->edit_url('jobhunter_all','show_resume',$value['user_id'],'查看简历','btn-yellow');
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
        $where['concat(sz_user_profile.id) like'] = '%'.trim($search).'%';
        if(isset($data['search_field']) && $data['search_field']){
            $where['sz_user_profile.nickname like'] = '%'.trim($data['search_field']).'%';
        }
        $this->data['count'] = $this->user_profile_model->getCount($where,true);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $order_by = 'user_profile.id desc';
            $admin_list = $this->user_profile_model->getProfileList($where,$length,$start,$order_by,'user_profile.*,user_message.user_type,user_jobhunter_extra_info.*',true);
            foreach($admin_list as $key => &$value) {
                //获取期望工作
                $job_intention=$this->cv_job_intention_model->checkJob(array('user_id'=>$value['user_id'],'is_deleted'=>0));




                $show_url = $this->edit_url('jobhunter_all','show_resume',$value['user_id'],'查看简历','btn-yellow');
                $value['check'] = $this->get_check($value['id']);
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

    /*导入简历--显示*/

    public function jober_add(){
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
        $this->display('jober_add');
    }     


    /*导入简历添加*/
    public function job_add_row(){
        // 文件上传
        $rand_num = rand(1000,9999);
        $temp = explode(".", $_FILES["resume_file"]["name"]);
        $extension = end($temp);     // 获取文件后缀名
        if($_FILES["resume_file"]["size"] > 204800){$this->location_href($this->go_url."/3");}   // 小于 2000kb)

        if ($_FILES["resume_file"]["error"] > 0)
        {
            echo "错误：: " . $_FILES["resume_file"]["error"] . "<br>";
        }else{
            // 判断当期目录下的 upload 目录是否存在该文件
            // 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
            $jober_file=$this->config->item("jober_file");
            if (file_exists("$jober_file/public_source/www/jober/" .$rand_num.$_FILES["resume_file"]["name"]))
            {
                echo $_FILES["resume_file"]["name"] . " 文件已经存在。 ";
            }
            else
            {
                // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
                move_uploaded_file($_FILES["resume_file"]["tmp_name"], "$jober_file/public_source/www/jober/" .$rand_num.$_FILES["resume_file"]["name"]);
                $data['resume_file']="/public_source/www/jober/" .$rand_num.$_FILES["resume_file"]["name"];
            }
         }
        // sz_user_profile 基本信息
        // sz_user_jobhunter_extra_info 细信息
        $data = $_POST;
        // 查找用户是否存在，存在返回失败，不存在继续
        $find_row = $this->user_profile_model->checkProfile(array("phone_number"=>$data['phone_number']));
        if($find_row){$this->location_href($this->go_url."/5");}
        $add_result = $this->user_profile_model->addProfile(array(
            'nickname'=>$data['nickname'],
            'phone_number'=>$data['phone_number'],
            'create_time'=>date("Y-m-d H:i:s"),
            'resume_file'=>$data['resume_file'],
            'is_seeker'=>2,
            ));
        $add_extra_result = $this->user_jobhunter_extra_info_model->addJobhunter(array(
            'city_id'=>$data['city_id'],
            'user_id'=>$add_result,
            'highest_degree'=>$data['highest_degree'],
            'work_year'=>$data['work_year'],
            'occupation'=>$data['occupation'],
            'wage_lower'=>$data['wage_lower'],
            'wage_upper'=>$data['wage_upper'],
            'is_deleted'=>0,
            'create_time'=>date("Y-m-d H:i:s"),
            ));
        if($add_result&&$add_extra_result) {
            $this->location_href($this->go_url."/4");
        }else {
            $this->location_href($this->go_url."/5");
        }

    }
    /*导入简历编辑显示*/
    public function job_edit(){
        $id = $this->uri->segment(4);
        $whereone['id'] = $id;
        $find_row = $this->user_profile_model->checkProfile(array("id"=>$id));
        $add_extra_result = $this->user_jobhunter_extra_info_model->checkJobhunter(array("user_id"=>$id));
        $data['nickname']=$find_row['nickname'];
        $data['phone_number']=$find_row['phone_number'];
        $data['highest_degree']=$add_extra_result['highest_degree'];
        $data['work_year']=$add_extra_result['work_year'];
        $data['user_id']=$add_extra_result['user_id'];
        $data['occupation']=$add_extra_result['occupation'];
        $data['wage_lower']=$add_extra_result['wage_lower'];
        $data['wage_upper']=$add_extra_result['wage_upper'];
        // $data['city_name']=$city_name[0]['region_name'];
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
        $this->assign('data', $data);
        $this->display('jober_edit');


    }
    /*导入简历-编辑*/
    public function job_edit_row(){
        $data = $_POST;
        $rand_num = rand(1000,9999);
        // 文件上传
        $temp = explode(".", $_FILES["resume_file"]["name"]);
        $extension = end($temp);     // 获取文件后缀名
        if($_FILES["resume_file"]["size"] > 204800){$this->location_href($this->go_url."/3");}   // 小于 2000kb)

        if ($_FILES["resume_file"]["error"] > 0)
        {
            echo "未更新上传文件哦~若文件不更新可忽略";
        }else{
            // 判断当期目录下的 upload 目录是否存在该文件
            // 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
            $jober_file=$this->config->item("jober_file");
            if (file_exists("$jober_file/public_source/www/jober/" .$rand_num.$_FILES["resume_file"]["name"]))
            {
                echo $_FILES["resume_file"]["name"] . " 文件已经存在。 ";
            }
            else
            {
                // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
                move_uploaded_file($_FILES["resume_file"]["tmp_name"], "$jober_file/public_source/www/jober/" .$rand_num.$_FILES["resume_file"]["name"]);
                $data['resume_file']="/public_source/www/jober/" .$rand_num.$_FILES["resume_file"]["name"];
            }
         }
        // 查找用户简历
        $find_row = $this->user_profile_model->checkProfile(array("id"=>$data['user_id']));
        if($_FILES["resume_file"]["name"]==NULL){$data['resume_file']=$find_row['resume_file'];}
        $add_result = $this->user_profile_model->editProfile(array("id"=>$data['user_id']),array(
            'nickname'=>$data['nickname'],
            'phone_number'=>$data['phone_number'],
            'create_time'=>date("Y-m-d H:i:s"),
            'resume_file'=>$data['resume_file'],
            ));
        $add_extra_result = $this->user_jobhunter_extra_info_model->editJobhunter(array("user_id"=>$data['user_id']),array(
            'city_id'=>$data['city_id'],
            'highest_degree'=>$data['highest_degree'],
            'work_year'=>$data['work_year'],
            'occupation'=>$data['occupation'],
            'wage_lower'=>$data['wage_lower'],
            'wage_upper'=>$data['wage_upper'],
            'update_time'=>date("Y-m-d H:i:s"),
            ));
        if($add_result && $add_extra_result) {
            $this->location_href($this->go_url."/2");
        }else {
            $this->location_href($this->go_url."/3");
        }
    }


    /*excle导入简历--显示*/

    public function jober_excle_add(){
        $postion_id = $this->uri->segment(4);
        $this->data['position_id']=$postion_id;
        $this->display('jober_excle_add');
    } 
     /*
      * 导入简历添加
      * 梁波
      * qq:173120209
      * 2017-11-06
      * */
    public function job_excle_add_row(){
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $postion_id = $this->uri->segment(4);
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $tmp_file = $_FILES ['file'] ['tmp_name']; //临时文件
            $tmp_name = $_FILES ['file'] ['name']; //临时文件
            $ext=get_extension($tmp_name);
            $myfile = fopen($tmp_file, "rb") or die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');;
            $content= fread($myfile,filesize($tmp_file));
            fclose($myfile);
            $host = "http://youyun.market.alicloudapi.com";
            $path = "/";
            $method = "POST";
            $appcode = $this->config->item('appcode');;
            $headers = array();
            array_push($headers, "Authorization:APPCODE " . $appcode);
            //根据API的要求，定义相对应的Content-Type
            array_push($headers, "Content-Type".":"."application/x-www-form-urlencoded; charset=UTF-8");
            $querys = "";
            // $content="";
            $bodys = "content=".urlencode(base64_encode($content))."&ext=".$ext;
            $url = $host . $path;
            //TODO阿里云简历解析接口
            $call_back=curl_ci("POST",$url,$headers,$bodys);
//            var_dump($call_back);exit();
            // 回调成功则解析入库
            if(isset($call_back) && !$call_back['error_code']){

                $data=$call_back['data']['cv_parse'];
                unset($call_back);
                unset($data['cv_text']);
                // $row=$this->db->query("select id from sz_user_profile where phone_number='{$data['contact']['mobile']}' limit 1")->row_array();
                // if($row){
                //     echo "<script>alert('因存在相同手机号码的简历，导入失败');window.history.go(-1);</script>";
                //     exit;
                // }
                //所在城市
                if(!$postion_id)
                {
                    $city_id=$data['basic_info']['location']['city']?$data['basic_info']['location']['city']:$data['basic_info']['location']['province'];
                    $row=$this->db->query("select region_id from sz_base_region where region_name like '%{$city_id}%' limit 1")->row_array();
                    if($row){
                        $city_id=$row["region_id"];
                    }
                    $highest_degree=4;
                    if(isset($data['educations']) && isset($data['educations'][0])) {
                        if($data['educations'][0]['major']=="本科" || $data['educations'][0]['major']=="学士在读"){
                            $highest_degree=4;
                        }elseif($data['educations'][0]['major']=="专科" || $data['educations'][0]['major']=="大专" || $data['educations'][0]['major']=="专 科"){
                            $highest_degree=3;
                        }elseif($data['educations'][0]['major']=="高中" || $data['educations'][0]['major']=="中专"){
                            $highest_degree=2;
                        }elseif($data['educations'][0]['major']=="硕士" || $data['educations'][0]['major']=="硕士研究生"){
                            $highest_degree=5;
                        }elseif($data['educations'][0]['major']=="博士" || $data['educations'][0]['major']=="MBA" || $data['educations'][0]['major']=="EMBA"){
                            $highest_degree=6;
                        }
                    } elseif(isset($data['basic_info']) && isset($data['basic_info']['highest_degree'])) {
                        $degree_name = $data['basic_info']['highest_degree'];
                        if($degree_name=="本科" || $degree_name=="学士在读"){
                            $highest_degree=4;
                        }elseif($degree_name=="专科" || $degree_name=="大专" || $degree_name=="专 科"){
                            $highest_degree=3;
                        }elseif($degree_name=="高中" || $degree_name=="中专"){
                            $highest_degree=2;
                        }elseif($degree_name=="硕士" || $degree_name=="硕士研究生"){
                            $highest_degree=5;
                        }elseif($degree_name=="博士" || $degree_name=="MBA" || $degree_name=="EMBA"){
                            $highest_degree=6;
                        }
                    }
                    $user_info=array(
                        "nickname"=>$data['basic_info']['name'],
                        "phone_number"=>$data['contact']['mobile'],
                        "gender"=>$data['basic_info']['gender']=="男"?1:0,
                        "is_seeker"=>2,
                        "city_id"=>$city_id,//城市
                        "highest_degree"=>$highest_degree,//最高学历
                        "work_year"=>intval($data['basic_info']['work_years']),
                        "work_email"=>isset($data['contact']['email'])?$data['contact']['email']:'',
                        "college"=>$data['basic_info']['graduate_school'],
                        "major"=>$data['educations'][0]['major'],
                        "occupation"=>$data['job_objective']['expect_titles'],
                        "birthday"=>date("Y-m-d H:i:s",strtotime($data['basic_info']['birthday'])),
                        "img_info"=>isset($data['img_info']['img_url'])?$data['img_info']['img_url']:'',
                        "educations"=>json_encode($data['educations']),
                        "certificates"=>json_encode($data['certificates']),
                        "languages"=>json_encode($data['languages']),
                        "skills"=>json_encode($data['skills']),
                        "create_time"=>date("Y-m-d H:i:s"),
                        "qq"=>isset($data['contact']['qq'])?$data['contact']['qq']:'',
                        "wage_lower"=>$data['job_objective']['expect_salary_upper'],
                        "wage_upper"=>$data['job_objective']['expect_salary_floor'],
                        "hunter_status"=>json_encode($data['job_objective']['status']),
                        "industries"=>$data['job_objective']['industries'],
                    );
                    //添加用户
                    $this->db->query("insert into sz_user_profile set phone_number='{$user_info["phone_number"]}',birthday='{$user_info["birthday"]}',nickname='{$user_info["nickname"]}',gender='{$user_info["gender"]}',is_seeker='{$user_info["is_seeker"]}',is_register='2'");
                    $insert_id=$this->db->insert_id();
                    //用户附加信息
                    $datenew=date("Y-m-d H:i:s");
                    $this->db->query("insert into sz_user_jobhunter_extra_info set city_id='{$user_info["city_id"]}',
                            highest_degree='{$user_info["highest_degree"]}',user_id='{$insert_id}',
                            work_year='{$user_info["work_year"]}',
                            work_email='{$user_info["work_email"]}',
                            college='{$user_info["college"]}',
                            major='{$user_info["major"]}',
                            update_time='{$datenew}',
                            occupation='{$user_info["occupation"]}',
                            img_info='{$user_info["img_info"]}',
                            educations='{$user_info["educations"]}',
                            create_time='{$datenew}',
                            certificates='{$user_info["certificates"]}',
                            languages='{$user_info["languages"]}',
                            skills='{$user_info["skills"]}',
                            qq='{$user_info["qq"]}',
                            wage_lower='{$user_info["wage_lower"]}',
                            wage_upper='{$user_info["wage_upper"]}',
                            hunter_status='{$user_info["hunter_status"]}',
                            industries='{$user_info["industries"]}'
                          ");
                    //工作经历和项目经历等
                    $work_list=array();
                    foreach ($data['projects'] as $key => $value) {
                        if(isset($value['not_ended'])){
                            $prj_time=$value['start_year'].'.'.$value['start_month'].'-'.$value['end_year'].'.'.$value['end_month'];
                        }else{
                            $prj_time=$value['start_year'].'.'.$value['start_month'].'至今';
                        }
                        $work_list[]=array(
                            "name"=>$value['name'],
                            "time"=>$prj_time,
                            "post"=>$value['post'],
                            "desc"=>$value['desc'],
                        );
                    }
                    $arr=array(
                        "self_desc"=>$data['self_evaluate'],
                        "user_id"=>$insert_id,
                        "work_list"=>json_encode($work_list)
                    );
                    $this->db->insert("sz_user_resume",$arr);
                    // 工作经历
                    $date=date("Y-m-d H:i:s");
                    foreach ($data['occupations'] as $key => $value) {
                        $start_time=date('Y-m-d',strtotime($value['start_time'].'/01'));
                        $end_time=date('Y-m-d',strtotime($value['end_time'].'/01'));
                        $this->db->query("insert into sz_cv_work_experience set user_id='{$insert_id}',
                            firm_name='{$value["company"]}',occupation='{$value["title"]}',
                            start_time='{$start_time}',
                            end_time='{$end_time}',
                            content='{$value["desc"]}',
                            create_time='{$date}',
                            update_time='{$date}',
                            is_deleted=0
                        ");
                    }
                    // 教育背景
                    foreach ($data['educations'] as $key => $value) {
                        if($value['degree']=='小学'){
                            $degree=0;
                        }else if($value['degree']=='初中'){
                            $degree=1;
                        }else if($value['degree']=='高中'){
                            $degree=2;
                        }else if($value['degree']=='大专'){
                            $degree=3;
                        }else if($value['degree']=='本科'){
                            $degree=4;
                        }else if($value['degree']=='硕士'){
                            $degree=5;
                        }else if($value['degree']=='博士'){
                            $degree=6;
                        }else{
                            $degree=7;
                        }
                        $start_time=date('Y-m-d',strtotime($value['start_time'].'/01'));
                        $end_time=date('Y-m-d',strtotime($value['end_time'].'/01'));
                        $this->db->query("insert into sz_cv_edu_background set user_id='{$insert_id}',
                            school_name='{$value["school"]}',major='{$value["major"]}',
                            start_time='{$start_time}',
                            end_time='{$end_time}',
                            degree='{$degree}',
                            create_time='{$date}',
                            update_time='{$date}',
                            is_deleted=0
                        ");
                    }
                    // 期望工作
                    $job_objective=$data['job_objective'];
                    $city=$job_objective["expect_locations"][0]['city']?$job_objective["expect_locations"][0]['city']:$job_objective["expect_locations"][0]['province'];
                    $row=$this->db->query("select region_id from sz_base_region where region_name like '%{$city}%' limit 1")->row_array();
                    if($row){
                        $city_id=$row["region_id"];
                    }else{
                        $city_id=0;
                    }
                    $this->db->query("insert into sz_cv_job_intention set user_id='{$insert_id}',
                            city='{$city}',job_type=1,
                            occupation='{$job_objective['expect_titles']}',
                            wage_lower='{$job_objective["expect_salary_floor"]}',
                            wage_upper='{$job_objective["expect_salary_upper"]}',
                            create_time='{$date}',
                            update_time='{$date}',
                            is_deleted=0,
                            city_id='{$city_id}'
                        ");
                    // 项目经验
                    foreach ($data['projects'] as $key => $value) {
                        $start_time=date('Y-m-d',strtotime($value['start_year'].'-'.$value["start_month"]));
                        $end_time=date('Y-m-d',strtotime($value['end_year'].'-'.$value['end_month']));
                        $this->db->query("insert into sz_cv_project_experience set user_id='{$insert_id}',
                            project_name='{$value["name"]}',
                            start_time='{$start_time}',
                            end_time='{$end_time}',
                            responsibility='{$value["post"]}',
                            description='{$value["desc"]}',
                            create_time='{$date}',
                            update_time='{$date}',
                            is_deleted=0
                        ");
                    }
                    // 自我描述


                    $this->db->query("insert into sz_cv_self_intro set user_id='{$insert_id}',
                            content='{$data["self_evaluate"]}',
                            create_time='{$date}',
                            update_time='{$date}',
                            is_deleted=0
                        ");
                    // 技能标签
                    foreach ($data['skills']['extract'] as $key => $value) {
                        $row=$this->db->query("select id from sz_cv_skill_tag_list where tag like '%{$value}%' limit 1")->row_array();
                        if($row){
                            $skill_id=$row["id"];
                        }else{
                            $this->db->query("insert into sz_cv_skill_tag_list set
                                    tag='{$value}',
                                    create_time='{$date}',
                                    update_time='{$date}',
                                    is_deleted=0
                                ");
                            $skill_id=$this->db->insert_id();
                        }
                        // 若不存在改技能标签则新建
                        $this->db->query("insert into sz_cv_skill_tag_rel set 
                        user_id='{$insert_id}',
                        tag_id='{$skill_id}',
                        create_time='{$date}',
                        is_deleted=0
                    ");
                    }
                }else{

                     $this->add_position($postion_id,$data);
                }

            }
            echo "<script>alert('导入完成');window.history.go(-1);</script>";exit;
        }
    }


    private function add_position($positionid,$data)
    {

        $city_id=$data['basic_info']['location']['city']?$data['basic_info']['location']['city']:$data['basic_info']['location']['province'];
        $city=$data['basic_info']['location']['city']?$data['basic_info']['location']['city']:$data['basic_info']['location']['province'];
        $row=$this->db->query("select region_id from sz_base_region where region_name like '%{$city_id}%' limit 1")->row_array();
        if($row){
            $city_id=$row["region_id"];
        }
        $highest_degree=4;
        if(isset($data['educations']) && isset($data['educations'][0])) {
            if($data['educations'][0]['major']=="本科" || $data['educations'][0]['major']=="学士在读"){
                $highest_degree=4;
            }elseif($data['educations'][0]['major']=="专科" || $data['educations'][0]['major']=="大专" || $data['educations'][0]['major']=="专 科"){
                $highest_degree=3;
            }elseif($data['educations'][0]['major']=="高中" || $data['educations'][0]['major']=="中专"){
                $highest_degree=2;
            }elseif($data['educations'][0]['major']=="硕士" || $data['educations'][0]['major']=="硕士研究生"){
                $highest_degree=5;
            }elseif($data['educations'][0]['major']=="博士" || $data['educations'][0]['major']=="MBA" || $data['educations'][0]['major']=="EMBA"){
                $highest_degree=6;
            }
        } elseif(isset($data['basic_info']) && isset($data['basic_info']['highest_degree'])) {
            $degree_name = $data['basic_info']['highest_degree'];
            if($degree_name=="本科" || $degree_name=="学士在读"){
                $highest_degree=4;
            }elseif($degree_name=="专科" || $degree_name=="大专" || $degree_name=="专 科"){
                $highest_degree=3;
            }elseif($degree_name=="高中" || $degree_name=="中专"){
                $highest_degree=2;
            }elseif($degree_name=="硕士" || $degree_name=="硕士研究生"){
                $highest_degree=5;
            }elseif($degree_name=="博士" || $degree_name=="MBA" || $degree_name=="EMBA"){
                $highest_degree=6;
            }
        }


        $user_info=array(
            "nickname"=>$data['basic_info']['name'],
            "phone_number"=>$data['contact']['mobile'],
            "gender"=>$data['basic_info']['gender']=="男"?1:0,
            "is_seeker"=>2,
            "city_id"=>$city_id,//城市
            "highest_degree"=>$highest_degree,//最高学历
            "work_year"=>intval($data['basic_info']['work_years']),
            "work_email"=>isset($data['contact']['email'])?$data['contact']['email']:'',
            "college"=>$data['basic_info']['graduate_school'],
            "major"=>$data['educations'][0]['major'],
            "occupation"=>$data['job_objective']['expect_titles'],
            "birthday"=>date("Y-m-d H:i:s",strtotime($data['basic_info']['birthday'])),
            "img_info"=>isset($data['img_info']['img_url'])?$data['img_info']['img_url']:'',
            "educations"=>json_encode($data['educations']),
            "certificates"=>json_encode($data['certificates']),
            "languages"=>json_encode($data['languages']),
            "skills"=>json_encode($data['skills']),
            "qq"=>isset($data['contact']['qq'])?$data['contact']['qq']:'',
            "wage_lower"=>$data['job_objective']['expect_salary_upper'],
            "wage_upper"=>$data['job_objective']['expect_salary_floor'],
            "hunter_status"=>json_encode($data['job_objective']['status']),
            "industries"=>$data['job_objective']['industries'],
        );
        //添加用户
        $this->db->query("insert into sz_user_profile set phone_number='{$user_info["phone_number"]}',birthday='{$user_info["birthday"]}',nickname='{$user_info["nickname"]}',gender='{$user_info["gender"]}',is_seeker='{$user_info["is_seeker"]}',is_register='2'");
        $insert_id=$this->db->insert_id();
        //用户附加信息
        $datenew=date("Y-m-d H:i:s");
        $this->db->query("insert into sz_job_fair_user set job_fair_id='{$positionid}',
                            add_type=2,user_id='{$insert_id}',
                            add_time='{$datenew}',
                            `name`='{$user_info["nickname"]}',
                            city='{$city}',
                            `year`='{$user_info["work_year"]}',
                            `position`='{$user_info["occupation"]}',
                            salary='".$data['job_objective']['expect_salary_upper'].'-'.$data['job_objective']['expect_salary_floor']."'
                          ");
        //工作经历和项目经历等
        $work_list=array();
        foreach ($data['projects'] as $key => $value) {
            if(isset($value['not_ended'])){
                $prj_time=$value['start_year'].'.'.$value['start_month'].'-'.$value['end_year'].'.'.$value['end_month'];
            }else{
                $prj_time=$value['start_year'].'.'.$value['start_month'].'至今';
            }
            $work_list[]=array(
                "name"=>$value['name'],
                "time"=>$prj_time,
                "post"=>$value['post'],
                "desc"=>$value['desc'],
            );
        }
        $arr=array(
            "self_desc"=>$data['self_evaluate'],
            "user_id"=>$insert_id,
            "work_list"=>json_encode($work_list)
        );
        $this->db->insert("sz_user_resume",$arr);
        // 工作经历
        $date=date("Y-m-d H:i:s");
        foreach ($data['occupations'] as $key => $value) {
            $start_time=date('Y-m-d',strtotime($value['start_time'].'/01'));
            $end_time=date('Y-m-d',strtotime($value['end_time'].'/01'));
            $this->db->query("insert into sz_cv_work_experience set user_id='{$insert_id}',
                            firm_name='{$value["company"]}',occupation='{$value["title"]}',
                            start_time='{$start_time}',
                            end_time='{$end_time}',
                            content='{$value["desc"]}',
                            create_time='{$date}',
                            update_time='{$date}',
                            is_deleted=0
                        ");
        }
        // 教育背景
        foreach ($data['educations'] as $key => $value) {
            if($value['degree']=='小学'){
                $degree=0;
            }else if($value['degree']=='初中'){
                $degree=1;
            }else if($value['degree']=='高中'){
                $degree=2;
            }else if($value['degree']=='大专'){
                $degree=3;
            }else if($value['degree']=='本科'){
                $degree=4;
            }else if($value['degree']=='硕士'){
                $degree=5;
            }else if($value['degree']=='博士'){
                $degree=6;
            }else{
                $degree=7;
            }
            $start_time=date('Y-m-d',strtotime($value['start_time'].'/01'));
            $end_time=date('Y-m-d',strtotime($value['end_time'].'/01'));
            $this->db->query("insert into sz_cv_edu_background set user_id='{$insert_id}',
                            school_name='{$value["school"]}',major='{$value["major"]}',
                            start_time='{$start_time}',
                            end_time='{$end_time}',
                            degree='{$degree}',
                            create_time='{$date}',
                            update_time='{$date}',
                            is_deleted=0
                        ");
        }
        // 期望工作
        $job_objective=$data['job_objective'];
        $city=$job_objective["expect_locations"][0]['city']?$job_objective["expect_locations"][0]['city']:$job_objective["expect_locations"][0]['province'];
        $row=$this->db->query("select region_id from sz_base_region where region_name like '%{$city}%' limit 1")->row_array();
        if($row){
            $city_id=$row["region_id"];
        }else{
            $city_id=0;
        }
        $this->db->query("insert into sz_cv_job_intention set user_id='{$insert_id}',
                            city='{$city}',job_type=1,
                            occupation='{$job_objective['expect_titles']}',
                            wage_lower='{$job_objective["expect_salary_floor"]}',
                            wage_upper='{$job_objective["expect_salary_upper"]}',
                            create_time='{$date}',
                            update_time='{$date}',
                            is_deleted=0,
                            city_id='{$city_id}'
                        ");
        // 项目经验
        foreach ($data['projects'] as $key => $value) {
            $start_time=date('Y-m-d',strtotime($value['start_year'].'-'.$value["start_month"]));
            $end_time=date('Y-m-d',strtotime($value['end_year'].'-'.$value['end_month']));
            $this->db->query("insert into sz_cv_project_experience set user_id='{$insert_id}',
                            project_name='{$value["name"]}',
                            start_time='{$start_time}',
                            end_time='{$end_time}',
                            responsibility='{$value["post"]}',
                            description='{$value["desc"]}',
                            create_time='{$date}',
                            update_time='{$date}',
                            is_deleted=0
                        ");
        }
        // 自我描述


        $this->db->query("insert into sz_cv_self_intro set user_id='{$insert_id}',
                            content='{$data["self_evaluate"]}',
                            create_time='{$date}',
                            update_time='{$date}',
                            is_deleted=0
                        ");
        // 技能标签
        foreach ($data['skills']['extract'] as $key => $value) {
            $row=$this->db->query("select id from sz_cv_skill_tag_list where tag like '%{$value}%' limit 1")->row_array();
            if($row){
                $skill_id=$row["id"];
            }else{
                $this->db->query("insert into sz_cv_skill_tag_list set
                                    tag='{$value}',
                                    create_time='{$date}',
                                    update_time='{$date}',
                                    is_deleted=0
                                ");
                $skill_id=$this->db->insert_id();
            }
            // 若不存在改技能标签则新建
            $this->db->query("insert into sz_cv_skill_tag_rel set 
                        user_id='{$insert_id}',
                        tag_id='{$skill_id}',
                        create_time='{$date}',
                        is_deleted=0
                    ");
        }



    }




}
