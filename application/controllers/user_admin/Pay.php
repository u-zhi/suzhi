<?php
/*付费服务*/
class Pay extends PC_Controller {
    public function __construct() {
        parent::__construct();
        // 内推
        $this->load->model('server_innerpush_model');
        //邀请
        $this->load->model('server_interview_model');
        //套餐
        $this->load->model('server_package_model');
        //速职币
        $this->load->model('server_suzhicoin_model');

        $this->load->helper('hash_helper');
        $this->go_url = $this->data['admin_path']."/pay/pay_list";
        $this->go_url2 = $this->data['admin_path']."/pay/interview_list";
        $this->go_url3 = $this->data['admin_path']."/pay/package_list";
        $this->go_url4 = $this->data['admin_path']."/pay/suzhi_list";
        $this->data['authority'] = $this->authority;
    }

    /**
     * @brief   内推
     * @param   Null
     * @author  Allen
     * @since   2016/12/21 Ver 1.0
     */
    public function pay_list() {
        if($this->uri->segment(4)) {
            $this->assign('message', $this->uri->segment(4));
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->display('pay_list');
    }

    //专业列表
    public function ajax_pay_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(id) like'] = '%'.trim($search).'%';
        if($data['search_field']) {
            $where['concat(money) like'] = '%'.trim($data['search_field']).'%';
        }
        $where['is_delete']=1;
        $this->data['count'] = $this->server_innerpush_model->getCount($where);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $occupation_list = $this->server_innerpush_model->getInnerpushList($where,$length,$start,$order_by);
            foreach($occupation_list as $key => &$value) {
                $value['number']=$value['number'].'（人）';
                $value['expire_year']=$value['expire_year'].'（年）';
                $value['money']=($value['money']/100).'（元）';
                $del_url = $this->delete_url('/pay/delete',$value['id'],'删除','btn-purple');  
                $value['operate'] = $del_url;

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
     * @brief   邀请
     * @param   Null
     * @author  Allen
     * @since   2016/12/21 Ver 1.0
     */
    public function interview_list() {
        if($this->uri->segment(4)) {
            $this->assign('message', $this->uri->segment(4));
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->display('interview_list');
    }

    //专业列表
    public function ajax_interview_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(id) like'] = '%'.trim($search).'%';
        if($data['search_field']) {
            $where['concat(money) like'] = '%'.trim($data['search_field']).'%';
        }
        $where['is_delete']=1;
        $this->data['count'] = $this->server_interview_model->getCount($where);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $occupation_list = $this->server_interview_model->getInterviewList($where,$length,$start,$order_by);
            foreach($occupation_list as $key => &$value) {
                $value['number']=$value['number'].'（次）';
                $value['expire_year']=$value['expire_year'].'（年）';
                $value['money']=($value['money']/100).'（元）';
                $del_url = $this->delete_url('/pay/interview_delete',$value['id'],'删除','btn-purple');  
                $value['operate'] = $del_url;

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
     * @brief   套餐
     * @param   Null
     * @author  Allen
     * @since   2016/12/21 Ver 1.0
     */
    public function package_list() {
        if($this->uri->segment(4)) {
            $this->assign('message', $this->uri->segment(4));
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];
        $this->display('package_list');
    }

    //专业列表
    public function ajax_package_list() {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(id) like'] = '%'.trim($search).'%';
        if($data['search_field']) {
            $where['concat(money) like'] = '%'.trim($data['search_field']).'%';
        }
        $where['is_delete']=1;
        $this->data['count'] = $this->server_package_model->getCount($where);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $occupation_list = $this->server_package_model->getPackageList($where,$length,$start,$order_by);
            foreach($occupation_list as $key => &$value) {
                $whereinnerpush['id']=$value['innerpush_id'];
                $whereInterview['id']=$value['interview_id'];
                $wheresuzhi['id']=$value['suzhicoin_id'];
                $innerpush=$this->server_innerpush_model->getInnerpushAll($whereinnerpush);
                $Interview=$this->server_interview_model->getInterviewAll($whereInterview);
                $suzhicoin=$this->server_suzhicoin_model->getSuzhicoinAll($wheresuzhi);
                $value['interview_id']=$innerpush[0]['number']."（人）";
                $value['innerpush_id']=$Interview[0]['number']."（次）";
                $value['suzhicoin_id']=$suzhicoin[0]['number']."(速职币)";
                $value['money']=($value['money']/100)."（元）";
                $value['expire_year']=$value['expire_year']."（年）";
                $del_url = $this->delete_url('/pay/package_delete',$value['id'],'删除','btn-purple');  
                $value['operate'] = $del_url;

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
     * @brief   内推
     * @param   Null
     * @author  Allen
     * @since   2016/07/12 Ver 1.0
     */
    public function delete() {
        $data = $_POST;
        $id = $_POST['id'];
        $where['id'] = $id; 
        $innerpush=$this->server_innerpush_model->getInnerpushAll($where);
        // 判断是否有关联到套餐,关联到的都删除
        $wherehave['innerpush_id']=$id;
        $Package=$this->server_package_model->getPackageAll($wherehave);
        if($Package!=NULL){
            foreach ($Package as $key => $value) {
                    $datapack["is_delete"]=2;
                    $wherepack['id']=$value['id'];
                    $del_result = $this->server_package_model->editPackage($wherepack,$datapack);
                    }            
        }
        $data["is_delete"]=2;
        $del_result = $this->server_innerpush_model->editInnerpush($where,$data);
        echo json_encode($del_result);
    }       
    /**
     * @brief   邀请
     * @param   Null
     * @author  Allen
     * @since   2016/07/12 Ver 1.0
     */
    public function interview_delete() {
        $data = $_POST;
        $id = $_POST['id'];
        $where['id'] = $id; 
        $Interview=$this->server_interview_model->getInterviewAll($where);
        // 判断是否有关联到套餐,关联到的都删除
        $wherehave['interview_id']=$id;
        $Package=$this->server_package_model->getPackageAll($wherehave);
        if($Package!=NULL){
            foreach ($Package as $key => $value) {
                    $datapack["is_delete"]=2;
                    $wherepack['id']=$value['id'];
                    $del_result = $this->server_package_model->editPackage($wherepack,$datapack);
                    }            
        }
        $data["is_delete"]=2;
        $del_result = $this->server_interview_model->editInterview($where,$data);
        echo json_encode($del_result);
    }       
    /**
     * @brief   套餐
     * @param   Null
     * @author  Allen
     * @since   2016/07/12 Ver 1.0
     */
    public function package_delete() {
        $data = $_POST;
        $id = $_POST['id'];
        $where['id'] = $id; 
        $Package=$this->server_package_model->getPackageAll($where);
        $data["is_delete"]=2;
        $del_result = $this->server_package_model->editPackage($where,$data);
        echo json_encode($del_result);
    }   
    
    /**
     * @brief   添加内推页面
     * @param   Null
     * @author  Allen
     * @since   2016/07/12 Ver 1.0
     */
    public function add_page() {
        $this->display('pay_add');
    }
    
    /**
     * @brief   添加内推
     * @param   Null
     * @author  Allen
     * @since   2016/07/12 Ver 1.0
     */
    public function add() {
        $data = $_POST;
        $data['expire_year']=1;
        $data['money']=$data['money']*100;
        $add_result = $this->server_innerpush_model->addInnerpush($data);
        if($add_result) {
            $this->location_href($this->go_url."/4");
        }else {
            $this->location_href($this->go_url."/5");
        }
    }     

    /**
     * @brief   添加内推邀请
     * @param   Null
     * @author  Allen
     * @since   2016/07/12 Ver 1.0
     */
    public function interview_add_page() {
        $this->display('interview_add');
    }
    
    /**
     * @brief   添加邀请
     * @param   Null
     * @author  Allen
     * @since   2016/07/12 Ver 1.0
     */
    public function interview_add() {
        $data = $_POST;
        $data['money']=$data['money']*100;
        $add_result = $this->server_interview_model->addInterview($data);
        if($add_result) {
            $this->location_href($this->go_url2."/4");
        }else {
            $this->location_href($this->go_url2."/5");
        }
    }      

    /**
     * @brief   添加套餐
     * @param   Null
     * @author  Allen
     * @since   2016/07/12 Ver 1.0
     */
    public function package_add_page() {
        $where['is_delete']=1;
        $interview_list=$this->server_interview_model->getInterviewAll($where);
        $innerpush_list=$this->server_innerpush_model->getInnerpushAll($where);
        $suzhicoin_list = $this->server_suzhicoin_model->getSuzhicoinAll($where);
        $this->data['interview_list']=$interview_list;
        $this->data['innerpush_list']=$innerpush_list;
        $this->data['suzhicoin_list']=$suzhicoin_list;
        $this->display('package_add');
    }
    
    /**
     * @brief   添加套餐
     * @param   Null
     * @author  Allen
     * @since   2016/07/12 Ver 1.0
     */
    public function package_add() {
        $data = $_POST;
        $data['money']=$data['money']*100;
        $add_result = $this->server_package_model->addPackage($data);
        if($add_result) {
            //返回的页面go_url3在前面设置好，并且 在add也要设置好
            $this->location_href($this->go_url3."/4");
        }else {
            $this->location_href($this->go_url3."/5");
        }
    }

    /**
    *author:liangbo
    *功能:速职配置列表
    *时间:2017/11/13 下午11:19
    */
    public function suzhi_list()
    {

        if($this->uri->segment(4)) {
            $this->assign('message', $this->uri->segment(4));
        }
        $this->data['search_field'] =  empty($_POST) ? '' : $_POST['search_field'];

        $this->display('suzhi_list');
    }
    /**
    *author:liangbo
    *功能:速职列表
    *时间:2017/11/13 下午11:26
    */
    public function ajax_suzhi_list()
    {
        $data = $_GET;
        /**起始行、显示个数、第几列排序、排序方向、全局搜索字段**/
        $start = $data['iDisplayStart'];
        $length = $data['iDisplayLength'];
        $sort_th = $data['mDataProp_'.$data['iSortCol_0'].''];
        $sort_type = $data['sSortDir_0'];
        $search = $data ['sSearch'];
        $where['concat(id) like'] = '%'.trim($search).'%';
        if($data['search_field']) {
            $where['concat(money) like'] = '%'.trim($data['search_field']).'%';
        }
        $where['is_delete']=1;
        $this->data['count'] = $this->server_suzhicoin_model->getCount($where);
        $aaData = array();
        if($this->data['count']) {
            $order_by = $sort_th." ".$sort_type;
            $occupation_list = $this->server_suzhicoin_model->getSuzhicoinList($where,$length,$start,$order_by);
            foreach($occupation_list as $key => &$value) {
                $value['money']=($value['money']/100)."（元）";
                $value['expire_year']=$value['expire_year']."（年）";
                $del_url = $this->delete_url('/pay/suzhi_delete',$value['id'],'删除','btn-purple');
                $value['operate'] = $del_url;

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
    *功能:速职业币删除
    *时间:2017/11/13 下午11:54
    */
    public function suzhi_delete()
    {
        $data = $_POST;
        $id = $_POST['id'];
        $where['id'] = $id;
        $Suzhicoin=$this->server_suzhicoin_model->getSuzhicoinAll($where);
        $data["is_delete"]=2;
        $del_result = $this->server_suzhicoin_model->editSuzhicoin($where,$data);
        echo json_encode($del_result);
    }
    /**
    *author:liangbo
    *功能:速职币增加
    *时间:2017/11/13 下午11:54
    */
    public function suzhi_add() {
        $data = $_POST;
        $data['money']=$data['money']*100;
        $add_result = $this->server_suzhicoin_model->addSuzhicoin($data);
        if($add_result) {
            //返回的页面go_url3在前面设置好，并且 在add也要设置好
            $this->location_href($this->go_url4."/4");
        }else {
            $this->location_href($this->go_url4."/5");
        }
    }
   /**
   *author:liangbo
   *功能:速职配置添加
   *时间:2017/11/14 上午12:20
   */
    public function suzhi_add_page() {
        $this->display('suzhi_add');
    }

}
?>