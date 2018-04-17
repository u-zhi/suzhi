<?php
//实习任务
class Firm extends PC_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('task_profile_model');
        $this->load->model('firm_profile_model');
        $this->load->model('base_region_model');
        $this->load->model('base_occupation_model');
        $this->load->model('jobhunter_order_profile_model');
        $this->load->model('balance_statements_model');
        //城市
        $this->load->model('base_county_model');
        // 内推
        $this->load->model('server_innerpush_model');
        //邀请
        $this->load->model('server_interview_model');
        //套餐
        $this->load->model('server_package_model');
        //企业外包项目
        $this->load->model('company_task_outsourcing_model');
        //企业拥有的服务
        $this->load->model('company_server_model');
        //企业购买的服务
        $this->load->model('company_buy_server_model');
        // 企业员工（跟企业内推人数限制有关）
        $this->load->model('company_staff_model');
        // 充值
        $this->load->service('AccountService');
        $this->load->model('balance_recharge_model');
        $this->go_url = $this->data['admin_path']."/firm/firm_list";
        $this->go_url_edit = $this->data['admin_path']."/firm/edit_page";
        $this->data['authority'] = $this->authority;
    }

    //公司列表
    public function firm_list() {
        if($this->uri->segment(4)) {
            $this->data['message'] = $this->uri->segment(4);
        }
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
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->data['province_id'] =  empty($_POST) ? '' : $_POST['province_id'];
        $this->data['city_id'] =  empty($_POST) ? '' : $_POST['city_id'];
        $this->display('firm_list');
    }
    
    //ajax获取管理员数据加载到列表
    public function ajax_firm_list() {
        $base_url=$this->config->item('image_path');
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(id) like'] = '%'.trim($search).'%';
        $where['is_deleted'] = 0;
        if(isset($data['search_field']) && $data['search_field']){
            $where['name like'] = '%'.trim($data['search_field']).'%';
        }
        if($data['province_id']) {
            $where['address_pro_city_coun like'] = "#".$data['province_id']."#%";
        }
        if($data['city_id']) {
            $where['address_pro_city_coun like '] = "#".$data['city_id']."#%";
        }
        $this->data['count'] = $this->firm_profile_model->getCount($where);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $admin_list = $this->firm_profile_model->getFirmList($where,$length,$start,$order_by);
            foreach($admin_list as $key => &$value) {
                //内推次数
                $innerpush_row=$this->company_server_model->checkCompany_server(array(
                    "company_id"=>$value['id'],
                    "status"=>1,
                    "type"=>1,
                ));
                $value['innerpush']=$innerpush_row ? $innerpush_row["number"].'人/年' : "未开通";
                //邀面次数
                $interview_row=$this->company_server_model->checkCompany_server(array(
                    "company_id"=>$value['id'],
                    "status"=>1,
                    "type"=>2,
                ),"sum(number) as total");
                $value['interview'] =$interview_row['total'] ? $interview_row['total'].'次' : "未开通";
                // //外包服务
                $wherecompany_task['company_id']=$value['id'];
                $where_in["status"]=array(4,6);
                $company_task=$this->company_task_outsourcing_model->getCount($wherecompany_task,$where_in);
                if($company_task==0){
                    $value['company_task']='未开通';
                }else{
                    $value['company_task']=$company_task;
                }
                // 规模
                $scale_type=$this->config->item('scale_type');
                $value['scale_type']=$scale_type[$value['scale_type']];
                $value['logo_url']=$this->default_img($base_url.$value['logo_url']);
                $value['img_url']=$this->default_img($base_url.$value['img_url']);
                // 职位总数
                $value['counts']=$this->task_profile_model->getCount(array('firm_id'=>$value['id']));

                if($value['financing'] == 0){
                    $value['financing']='未融资';
                }elseif($value['financing'] == 1){
                    $value['financing']='天使轮';
                }elseif($value['financing'] == 2){
                    $value['financing']='A轮';
                }elseif($value['financing'] == 3){
                    $value['financing']='B轮';
                }elseif($value['financing'] == 4){
                    $value['financing']='C轮';
                }elseif($value['financing'] == 5){
                    $value['financing']='D轮及以上';
                }elseif($value['financing'] == 6){
                    $value['financing']='上市公司';
                }else{
                    $value['financing']='不需要融资';
                }
                $edit_url = $this->edit_url('firm','edit_page',$value['id'],'企业信息','btn-primary');
                // $show_url = $this->edit_url('firm','hot_positions',$value['id'],'热招职位','btn-yellow');
                // $del_url = $this->delete_url('/firm/positions_delete',$value['id'],'删除','btn-purple');
                $value['check'] = $this->get_check($value['id']);
                $value['operate'] = $edit_url/*.$show_url.$del_url*/;
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
    //删除
    public function delete() {
        $data = $_POST;
        $where['id'] = $data['id'];
        $data['is_deleted'] = 1; //删除
        $data['delete_time']=$this->time_retuen();
        $del_result = $this->firm_profile_model->editFirm($where,$data);
        echo json_encode($del_result);
    }
    //编辑展示
    public function edit_page() {
        $id = $this->uri->segment(4);
        $where['id'] = $id;
        $base_url=$this->config->item('image_path');
        //获取任务详情
        $info = $this->firm_profile_model->checkFirm($where);
//        if(!empty($info)){
            $info['icon_url']=$base_url.$info['logo_url'];
            $info['license_url']=$base_url.$info['license_url'];
            // 公司行业
/*            $oppucation=explode(",",$info['type_classify']);
            foreach ($oppucation as $key => $value) {
                $a1[$key]=$this->base_occupation_model->checkBase(array("id"=>$value));
            }
            $info['type_classify']=$a1[0]['item']."--".$a2[1]['item'];*/
            $this->assign('data', $info);
            $this->display('firm_edit');
//        }else{
//            $this->check_rational($info);
//            $this->display('firm_edit');
//        }

    }
    //保存
    public function edit()
    {
        $data = $_POST;
        $where['id'] = $data['id'];
        /**上传图片**/
        if($_FILES['jietu']['tmp_name']) {
            $upload_image = $this->upload($_FILES,false,WEB_URL);
            $data['icon_url'] =$data['img_url'] = $upload_image;
        }else{
            $res=$this->firm_profile_model->checkFirm($where,'icon_url');
            $data['icon_url']=$res['icon_url'];
        }
        $data['update_time']=$this->time_retuen();
        $info=$this->firm_profile_model->editFirm($where,$data);
        if($info){
            $this->location_href($this->go_url."/2");
        }else{
            $this->location_href($this->go_url."/3");
        }
    }   
    //添加页面展示
    public function add_page() {
        $this->display('firm_add');
    }

    //erp充值
    public function pay_company(){
        $data = $_POST;
        $where['id']=$data['id'];
        $res=$this->firm_profile_model->checkFirm($where,'money');
        $datamoney['money']=$res['money']+($data['money']*100);
        // 充值记录
        $pay_log=$this->balance_recharge_model->addBalance_recharge(array(
                            "user_id"=>$data['id'],
                            "admin_id"=>$_SESSION["ALLEN_WANG"]['admin']["id"],
                            "money"=>($data['money']*100),
                            "user_type"=>1,
                            "pay_type"=>2,
                            "status"=>2,
                            "create_time"=>date("Y-m-d H:i:s"),
                            "order_number"=>"R".time().rand(100,999),
                        ));
        $del_result = $this->firm_profile_model->editFirm($where,$datamoney); 
        if($del_result&&$pay_log){
            $this->location_href($this->go_url_edit.'/'.$data['id']);
        }else{
            $this->location_href($this->go_url_edit.'/'.$data['id']);
        }

    } 

    //保存添加
    public function add(){
        $data = $_POST;
        /**上传图片**/
        if($_FILES['jietu']['tmp_name']) {
            $upload_image = $this->upload($_FILES,false,WEB_URL);
            $data['icon_url'] = $data['img_url']=$upload_image;
        }
        $data['create_time']=$this->time_retuen();
        $info=$this->firm_profile_model->addFirm($data);
        if($info){
            $this->location_href($this->go_url."/4");
        }else{
            $this->location_href($this->go_url."/5");
        }
    }
    //热招职位
    public function hot_positions (){
        $firm_id= $this->uri->segment(4);
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->assign('firm_id',$firm_id);
        $where['id'] = $firm_id;
         $base_url=$this->config->item('image_path');
        //获取任务详情
        $info = $this->firm_profile_model->checkFirm($where);
//        if(!empty($info)){
        $info['icon_url']=$base_url.$info['logo_url'];
        $this->data['data'] = $info;
        $this->display('hot_positions_list');

    }
    //ajax获取管理员数据加载到列表
    public function ajax_positions_list() {
        $base_url=$this->config->item('image_path');
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(id) like'] = '%'.trim($search).'%';
        $where['firm_id'] = $data['firm_id'];
        $where['is_deleted'] = 0;
        if(isset($data['search_field']) && $data['search_field']){
            $where['name like'] = '%'.trim($data['search_field']).'%';
        }
        $this->data['count'] = $this->task_profile_model->getCount($where);
        // var_export($this->data['count']);exit;

        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $admin_list = $this->task_profile_model->getTaskList($where,$length,$start,$order_by);
            foreach($admin_list as $key => &$value) {
                $a1=$this->firm_profile_model->checkFirm(array('id'=>$value['firm_id']),'name');
                $value['firm_name']=$a1['name'];
                $a2=$this->base_county_model->checkBase(array('id'=>$value['county_id']),'county_name');
                $value['county_name']=$a2['county_name'];
                $a3=$this->base_occupation_model->checkBase(array('id'=>$value['occupation_id']),'item');
                $value['occupation_name']=$a3['item'];
                // $value['image_url']=$this->default_img($base_url.$value['logo_url']);
                if($value['recruit_type_platform'] != 1){
                    $value['mt_type'] = '平台展示';
                }elseif($value['recruit_type_inner'] != 1){
                    $value['mt_type'] = '内推';
                }elseif($value['recruit_type_hunter'] != 1){
                    $value['mt_type'] = '顾问展示';
                }else{
                    $value['mt_type'] = '为填写';
                }
                if($value['task_type'] == 0){
                    $value['task_type']='兼职';
                }elseif($value['task_type'] == 1){
                    $value['task_type']='全职';
                }else{
                    $value['task_type']='实习';
                }
                //面试人数recv_cv_yes_times
                $whererecv['trade_no']=$value['id'];
                $recv_cv_yes_times=$this->jobhunter_order_profile_model->getCount($whererecv);
                
                $value['recv_cv_yes_times']=$recv_cv_yes_times;
                $value['commission']= $value['commission']."（元）";
                $recv_type=$this->config->item("recv_type");
                $value['recv_type']=$recv_type[$value['status']];
                // var_dump($value['recv_cv_yes_times']);exit;
                $edit_url = $this->edit_url('firm','edit_pages',$value['id'],'查看','btn-success');
                // $del_url = $this->delete_url('/practice/delete',$value['id'],'删除','btn-purple');
                $value['check'] = $this->get_check($value['id']);
                $value['operate'] = $edit_url/*.$del_url*/;
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
    //编辑展示
    public function edit_pages() {
        $id = $this->uri->segment(4);
        $where['id'] = $id;
        $base_url=$this->config->item('image_path');
        //获取任务详情
        $info = $this->task_profile_model->checkTask($where);
//        if(!empty($info)){
            if($info['task_type'] == 0){
                $info['task_type']='兼职';
            }elseif($info['task_type'] == 1){
                $info['task_type']='全职';
            }else{
                $info['task_type']='实习';
            }
            // $info['image_url']=$base_url.$info['logo_url'];
            //获取公司信息
            $a1=$this->firm_profile_model->checkFirm();
            $info['firm_name']=$a1['name'];
            //获取区域信息
            $a2=$this->base_county_model->checkBase();
            $info['county_name']=$a2['county_name'];
            //获取工作类型(职位)
            $a3=$this->base_occupation_model->checkBase();
            $info['occupation_name']=$a3['item'];
            $this->assign('data', $info);
            $this->display('positions_edit');
//        }else{
//            $this->check_rational($info);
//        }

    }
    //删除
    public function positions_delete() {
        $data = $_POST;
        $where['id'] = $data['id'];
        $data['is_deleted'] = 1; //删除
        $data['delete_time']=$this->time_retuen();
        $del_result = $this->task_profile_model->editTask($where,$data);
        echo json_encode($del_result);
    }

    /*企业员工模块*/
    public function company_staff(){
        $firm_id= $this->uri->segment(4);
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->assign('firm_id',$firm_id);
        $where['id'] = $firm_id;
         $base_url=$this->config->item('image_path');
        //获取任务详情
        $info = $this->firm_profile_model->checkFirm($where);
//        if(!empty($info)){
        $info['icon_url']=$base_url.$info['logo_url'];
        $this->data['data'] = $info;
        $this->display('company_staff_list');

    }
    public function ajax_company_staff_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(id) like'] = '%'.trim($search).'%';
        $where['company_id'] =$data["firm_id"];
        if($data['search_field']) {
            $where['concat(name) like'] = '%'.trim($data['search_field']).'%';;
        }
        $this->data['count'] = $this->company_staff_model->getCount($where);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $occupation_list = $this->company_staff_model->getCompany_staffList($where,$length,$start,$order_by);
            foreach($occupation_list as $key => &$value) {
                $first=$this->firm_profile_model->checkfirm_profil(array('id'=>$value['company_id']));
                $value['region_name_city']=$first['name'];
                //内推的人数
                $jobhunter_order_sum=$this->jobhunter_order_profile_model->getCount(array("inner_user_id"=>$value['user_id']));
                $occupation_list[$key]['companion']=$jobhunter_order_sum;
                //计获取的奖金(内推)
                $jobhunter_order_list=$this->jobhunter_order_profile_model->getOrderList(array("inner_user_id"=>$value['user_id'],"current_status"=>10));
                $task_id=array();
                foreach($jobhunter_order_list as $value){
                    $task_id[]=$value["task_id"];
                }
                if($task_id){
                    $task_id=array_unique($task_id);
                    $commission_row=$task->_task_model->field("sum(commission) as total")->where(array("id"=>array("in",$task_id)))->find();
                    $occupation_list[$key]['jackpot']=$commission_row["total"]?$commission_row["total"]:0;
                }else{
                    $occupation_list[$key]['jackpot']=0;
                }




                // $edit_url = $this->edit_url('city','edit_page',$value['id']);  
                // $del_url = $this->delete_url('/city/delete',$value['id'],'删除','btn-purple');  
                // $value['operate'] = $edit_url.$del_url;

            }
            $aaData = $occupation_list;
        }
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
        $output['iTotalRecords'] = $this->data['count']; //总共有几条数据
        echo json_encode($output); //最后把数据以json格式返回
    }


     /*企业购买的服务*/
    public function company_buy(){
        $firm_id= $this->uri->segment(4);
        $suzhi_coin=$this->accountservice->get_suzhi_coin($firm_id);
        $this->assign('firm_id',$firm_id);
        $this->assign('suzhi_coin', $suzhi_coin);
        $where['id'] = $firm_id;
         $base_url=$this->config->item('image_path');
        //获取任务详情
        $info = $this->firm_profile_model->checkFirm($where);
        $info['icon_url']=$base_url.$info['logo_url'];
        //内推次数
        $innerpush_row=$this->company_server_model->checkCompany_server(array(
            "company_id"=>$firm_id,
            "status"=>1,
            "type"=>1,
        ));
        $info['server_innerpush_number']=$innerpush_row ? $innerpush_row["number"].'人/年' : "未开通";
        $info['innerpush_over_time']=$innerpush_row ? ceil((strtotime($innerpush_row["end_time"])-time())/86400) : "未开通";
        //邀面次数
        $interview_row=$this->company_server_model->checkCompany_server(array(
            "company_id"=>$firm_id,
            "status"=>1,
            "type"=>2,
        ),"sum(number) as total");
        $info['server_interview_number'] =$interview_row['total'] ? $interview_row['total'].'次' : "未开通";

        $this->data['data'] = $info;
        $this->display('company_buy_server_list');

    }
    public function ajax_company_buy_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(id) like'] = '%'.trim($search).'%';
        $where['company_id'] =$data['firm_id'];
        $this->data['count'] = $this->company_buy_server_model->getCount($where);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $occupation_list = $this->company_buy_server_model->getCompany_buy_serverList($where,$length,$start,$order_by);
            foreach($occupation_list as $key => &$value) {
                // $value['xianmu']="功能还未做";
                switch ($value['server_type']) {
                    case '1':
                    $value['xianmu']="企业内推";
                        break; 
                    case '2':
                    $value['xianmu']="邀请面试";
                        break; 
                    case '3':
                    $value['xianmu']="套餐";
                        break;
                    
                    default:
                    $value['xianmu']="";
                        break;
                }
                // $value['amount']=$value['amount']."员";
                $value['pay_way']="支付宝";
                $value['money']=($value['money']/100)."元";
                $value['work_man']="企业";
            }
            $aaData = $occupation_list;
        }
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
        $output['iTotalRecords'] = $this->data['count']; //总共有几条数据
        echo json_encode($output); //最后把数据以json格式返回
    }
     /**
     *author:liangbo
     *功能:速职币列表
     *时间:2017/11/13 下午2:49
     */
    public function company_suzhi()
    {
        $firm_id= $this->uri->segment(4);
        $suzhi_coin=$this->accountservice->get_suzhi_coin($firm_id);
        $this->assign('firm_id',$firm_id);
        $this->assign('suzhi_coin', $suzhi_coin);
        $where['id'] = $firm_id;
        $base_url=$this->config->item('image_path');
        //获取任务详情
        $info = $this->firm_profile_model->checkFirm($where);
        $info['icon_url']=$base_url.$info['logo_url'];
        //内推次数
        $innerpush_row=$this->company_server_model->checkCompany_server(array(
            "company_id"=>$firm_id,
            "status"=>1,
            "type"=>1,
        ));
        $info['server_innerpush_number']=$innerpush_row ? $innerpush_row["number"].'人/年' : "未开通";
        $info['innerpush_over_time']=$innerpush_row ? ceil((strtotime($innerpush_row["end_time"])-time())/86400) : "未开通";
        //邀面次数
        $interview_row=$this->company_server_model->checkCompany_server(array(
            "company_id"=>$firm_id,
            "status"=>1,
            "type"=>2,
        ),"sum(number) as total");
        $info['server_interview_number'] =$interview_row['total'] ? $interview_row['total'].'次' : "未开通";

        $this->data['data'] = $info;
        $this->display('company_buy_suzhi_list');

    }
    /**
    *author:liangbo
    *功能:速职币ajax请求
    *时间:2017/11/13 下午2:49
    */
    public function ajax_company_suzhi_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(id) like'] = '%'.trim($search).'%';
        $where['type'] =4;
        $where['company_id'] =$data['firm_id'];
        $this->data['count'] = $this->company_server_model->getCount($where);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $occupation_list = $this->company_server_model->getCompany_serverList($where,$length,$start,$order_by);
            foreach($occupation_list as $key => &$value) {
                $value['pay_way']="支付宝";
                $value['money']=($value['money']/100)."元";
                $value['work_man']="企业";
            }
            $aaData = $occupation_list;
        }
        $output['aaData'] = $aaData;
        $output['sEcho'] = $_GET['sEcho'];
        $output['iTotalDisplayRecords'] =  $this->data['count']; //总共有几条数据
        $output['iTotalRecords'] = $this->data['count']; //总共有几条数据
        echo json_encode($output); //最后把数据以json格式返回
    }
    /**
    *author:liangbo
    *功能:内推次数修改
    *时间:2017/11/13 下午8:29
    */
    public function innertui_add()
    {
        $data = $_POST;
        $where['id']=$data['id'];
        $innerpush_row=$this->company_server_model->checkCompany_server(array(
            "company_id"=>$where['id'],
            "status"=>1,
            "type"=>1,
        ));
       if(empty($innerpush_row))
       {
           $res=$this->company_server_model->addCompany_server(array(
               "company_id"=>$where['id'],
               "status"=>1,
               "type"=>1,
               "number"=>$data["number"],
               "begin_time"=>date("Y-m-d H:i:s"),
               "end_time"=>date("Y-m-d H:i:s",strtotime("+1 year")),
               "add_time"=>date("Y-m-d H:i:s"),
           ));
       }else{
          $res= $this->company_server_model->editCompany_server(array("company_id"=>$where['id'],
               "status"=>1,
               "type"=>1,),
               array("number"=>$data["number"])
           );
       }
        if($res){
            $this->location_href($this->go_url_edit.'/'.$data['id']);
        }else{
            $this->location_href($this->go_url_edit.'/'.$data['id']);
        }



    }
    /**
    *author:liangbo
    *功能:面试次数修改
    *时间:2017/11/13 下午8:29
    */
    public function interview_add()
    {
        $data = $_POST;
        $where['id']=$data['id'];
        $innerpush_row=$this->company_server_model->checkCompany_server(array(
            "company_id"=>$where['id'],
            "status"=>1,
            "type"=>2,
        ));
        if(empty($innerpush_row))
        {
            $res=$this->company_server_model->addCompany_server(array(
                "company_id"=>$where['id'],
                "status"=>1,
                "type"=>2,
                "number"=>$data["number"],
                "begin_time"=>date("Y-m-d H:i:s"),
                "end_time"=>date("Y-m-d H:i:s",strtotime("+1 year")),
                "add_time"=>date("Y-m-d H:i:s"),
            ));
        }else{
            $res= $this->company_server_model->editCompany_server(array("company_id"=>$where['id'],
                "status"=>1,
                "type"=>2,),
                array("number"=>$data["number"])
            );
        }
        if($res){
            $this->location_href($this->go_url_edit.'/'.$data['id']);
        }else{
            $this->location_href($this->go_url_edit.'/'.$data['id']);
        }


    }

    /**
    *author:liangbo
    *功能:速职币修改
    *时间:2017/11/13 下午8:30
    */
    public function suzhi_add()
    {
        $data = $_POST;
        $where['id']=$data['id'];
        $innerpush_row=$this->company_server_model->checkCompany_server(array(
            "company_id"=>$where['id'],
            "status"=>1,
            "type"=>4,
        ));
        if(empty($innerpush_row))
        {
            $res=$this->company_server_model->addCompany_server(array(
                "company_id"=>$where['id'],
                "status"=>1,
                "type"=>4,
                "number"=>$data["number"],
                "begin_time"=>date("Y-m-d H:i:s"),
                "end_time"=>date("Y-m-d H:i:s",strtotime("+1 year")),
                "add_time"=>date("Y-m-d H:i:s"),
            ));
        }else{
            $res= $this->company_server_model->editCompany_server(array("company_id"=>$where['id'],
                "status"=>1,
                "type"=>2,),
                array("number"=>$data["number"])
            );
        }
        if($res){
            $this->location_href($this->go_url_edit.'/'.$data['id']);
        }else{
            $this->location_href($this->go_url_edit.'/'.$data['id']);
        }
    }

}