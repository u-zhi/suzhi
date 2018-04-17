<?php
/**
 * User: Allen
 * Date: 16-12-20
 * 
 * ━━━━━━神兽出没━━━━━━
 * 　　　┏┓　　　┏┓
 * 　　┏┛┻━━━┛┻┓
 * 　　┃　　　　　　　┃
 * 　　┃　　　━　　　┃
 * 　　┃　┳┛　┗┳　┃
 * 　　┃　　　　　　　┃
 * 　　┃　　　┻　　　┃
 * 　　┃　　　　　　　┃
 * 　　┗━┓　　　┏━┛Code is far away from bug with the animal protecting
 * 　　　　┃　　　┃    神兽保佑,代码无bug
 * 　　　　┃　　　┃
 * 　　　　┃　　　┗━━━┓
 * 　　　　┃　　　　　　　┣┓
 * 　　　　┃　　　　　　　┏┛
 * 　　　　┗┓┓┏━┳┓┏┛
 * 　　　　　┃┫┫　┃┫┫
 * 　　　　　┗┻┛　┗┻┛
 *
 * ━━━━━━感觉萌萌哒━━━━━━
 */

class PC_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->admin = array();
        $this->data['message'] = 1; //默认message
        $this->data['admin_path'] = '/user_admin'; //后台路径
        $this->data['method'] = $this->router->method; //当前方法
       	$this->data['controller'] = $this->router->class; //当前控制器
        $this->info = $this->session->userdata('ALLEN_WANG'); //缓存
        $this->data['info'] = $this->session->userdata('ALLEN_WANG'); //缓存
        $this->_init_visitor(); //初始化访问者
        $this->lang->load('catalog');
        $this->data['access'] = $this->Access();
        $this->parent_access();
        $this->authority = $this->Isauthority($this->data['controller']);
        if($this->data['controller'] != "welcome") {     
        	$this->RBAC();      
        }
    }

    protected function _init_visitor() {
        $this->load->library('visitor');
        if (!$this->visitor->is_signed()) {
            if ($this->input->is_ajax_request()) {
                exit(getJsonOutput(array('status'=>false, 'data'=>'请先登录！')));
            } else {
                redirect('http://'.$_SERVER['HTTP_HOST'].''.$this->data['admin_path'].'/login');
            }
        }
        $this->admin = $this->visitor->get_info('admin');
    }

    /**
     * @brief	跳转链接
     * @param 	Null
     * @author	Allen
     * @since	2016/10/24 Ver 1.0
     */
    public function location_href($go_url) {
    	echo "<script>window.location.replace('$go_url');</script>";
    	exit;
    }
    
    /**
     * @brief	拼接地区
     * @param 	Null
     * @author	Allen
     * @since	2016/10/24 Ver 1.0
     */
    public function get_post($data,$field) {
    	$province = $data['province'];
    	$city = $data['city'];
    	$county = $data['county'];
    	$province = explode(",", $province);
    	$city = explode(",", $city);
    	$county = explode(",", $county);
    	$areas['area_no']= $province[0].','.$city[0].','.$county[0];
    	$areas['detail']= $province[1].$city[1].$county[1];
    	$data[$field] = serialize($areas);
    	unset($data['province']);unset($data['city']);unset($data['county']);
    	return $data;
    }

    /**
     * @brief	上传多图
     * @param 	Null
     * @author	Allen
     * @since	2016/10/24 Ver 1.0
     */
    public function group_upload($files,$num="group_jietu") {
    	$upfile=$files[$num];
    	$destination = "";
    	$name_array = $upfile["name"]; //上传文件的文件名
    	foreach($name_array as $key => $value) {
			if($value) {
				$name = $upfile["name"][$key]; //上传文件的文件名
				$type = $upfile["type"][$key]; //上传文件的类型
				$size = $upfile["size"][$key]; //上传文件的大小
				$tmp_name = $upfile["tmp_name"][$key]; //上传文件的临时存放路径
				//取扩展名
				$file_ext = explode(".",$name);
				$file_ext = $file_ext[count($file_ext)-1];
				$file_ext = strtolower($file_ext);
				$newname = (time() + $key).'.'.$file_ext;
				$error = $upfile["error"][$key];
				move_uploaded_file($tmp_name,TEMP_PATH.$newname);
				$destination .= UPLOAD_PATH.$newname.";";
			}
    	}
    	return $destination;
 	 }

 	 /**
 	  * @brief	上传图片
 	  * @param 	Null
 	  * @author	Allen
 	  * @since	2016/10/24 Ver 1.0
 	  */
 	 public function upload($files,$change_name=false,$url='',$num="jietu") {
 	 	$upfile = $files[$num];
 	 	$rand_num = rand(1000,9999); //随机数
 	 	$name = $upfile["name"]; //上传文件的文件名
 	 	$type = $upfile["type"]; //上传文件的类型
 	 	$size = $upfile["size"]; //上传文件的大小
 	 	$tmp_name = $upfile["tmp_name"]; //上传文件的临时存放路径
 	 	$file_ext = explode(".",$name); //取扩展名
 	 	$file_ext = $file_ext[count($file_ext)-1];
 	 	$file_ext = strtolower($file_ext);
 	 	if($change_name) {
 	 		$newname = $name;
 	 	}else {
 	 		$newname = (time()-$rand_num).'.'.$file_ext;
 	 	}
 	 	$error = $upfile["error"];
 	 	move_uploaded_file($tmp_name,TEMP_PATH.$newname);
 	 	$destination = UPLOAD_PATH.$newname;
		 if($destination){
			 $this->load->library('aliyunoss');
			 //查看账户信息是否正确
			 $res=$this->aliyunoss->checkOss();
			 if($res){
				 //上传文件
				 $bucket='suzhi-app';
				 $object=$newname;
				 $file= '/opt/app/erp-web/public_source/www/upload/'.$newname;
				 $acl='public-read';
				 $result = $res->multiuploadFile($bucket, $object, $file);
				 $results = $res->putObjectAcl($bucket, $object, $acl);
				 if($result && $results){
					 $jieguo=@unlink ($file);
					 if(!$jieguo){
						 return false;
					 }else{
						 return $newname;
					 }
				 }else{
					 return false;
				 }
			 }else{
				 return false;
			 }
		 }else{
			 return false;
		 }
 	 }
	/**
	 * 上传图片
	 */
	public function uploadPic($img){
		$expData = explode(';',$img);
		$postfix   = explode(':',$expData[0]);
		$type = $postfix[1];
		switch($type){
			case 'image/png':
				$ext='.png';
				break;
			case 'image/jpeg';
				$ext='.jpeg';
				break;
			case 'image/jpeg':
				$ext='.jpg';
				break;
			case 'image/bmp':
				$ext='.bmp';
				break;
			default:
				$ext='.jpg';
		}
		$r = rand(10,1000);
		$pre=time().'_'.$r;

		$file_path=$this->config->item('upload_wap_path').'/'.$pre.$ext;
//		$file_path='/'.$pre.$ext;
		$img_content = str_replace('data:'.$type.';base64,','',$img);
		$img_content = base64_decode($img_content);
		$ress = file_put_contents($file_path,$img_content);
		if($ress){
			$this->load->library('aliyunoss');
			//查看账户信息是否正确
			$res=$this->aliyunoss->checkOss();
			if($res){
				//上传文件
				$bucket='suzhi-app';
				$object=$pre;
				$file= '/opt/app/erp-web/public_source/www/upload/'.$pre.$ext;
				$acl='public-read';
				$result = $res->multiuploadFile($bucket, $object, $file);
				$results = $res->putObjectAcl($bucket, $object, $acl);
				if($result && $results){
					$jieguo=@unlink ($file);
					if(!$jieguo){
						return false;
					}else{
						return $object;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

 	 /**
 	  * @brief	获取图片信息
 	  * @param 	Null
 	  * @author	Allen
 	  * @since	2016/10/24 Ver 1.0
 	  */
 	 public function default_img($image,$height="30") {
 	 	if(!$image) {
 	 		$image = DEFAULT_IMAGE;
 	 	}
 	 	$image = '<img src="'.$image.'" style="max-height:'.$height.'px;"/>';
 	 	return $image;
 	 }
	/**
	 * 封装一个时间返回值
	 * tags
	 * @param unknowtype
	 * @return return_type
	 * @version v1.0.0
	 */
	public function time_retuen(){
		$time=time();
		$res=date('Y-m-d H:i:s',$time);
		return $res;
	}
 	 /**
 	  * @brief	获取编辑信息链接
 	  * @param 	Null
 	  * @author	Allen
 	  * @since	2016/10/24 Ver 1.0
 	  */
 	 public function edit_url($c,$m,$id,$name="编辑",$color="btn-primary") {
		$edit_url = '';
		$authority = $this->authority;
		$admin_path = $this->data['admin_path'];
		if($name == '编辑' || $name == '设置'||$name=='关闭') {
			if($authority['edit_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="'.$admin_path.'/'.$c.'/'.$m.'/'.$id.'"><span class="btn btn-xs '.$color.'">'.$name.'</span></a>';
			}
		}else if($name == '用户详情') {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="'.$admin_path.'/'.$c.'/'.$m.'/'.$id.'"><span class="btn btn-xs '.$color.'">'.$name.'</span></a>';
			}
		}else if($name == '查看') {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="'.$admin_path.'/'.$c.'/'.$m.'/'.$id.'"><span class="btn btn-xs '.$color.'">'.$name.'</span></a>';
			}
		}else if($name == '招聘信息') {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="'.$admin_path.'/'.$c.'/'.$m.'/'.$id.'"><span class="btn btn-xs '.$color.'">'.$name.'</span></a>';
			}
		}else if($name == '详情') {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="'.$admin_path.'/'.$c.'/'.$m.'/'.$id.'"><span class="btn btn-xs '.$color.'">'.$name.'</span></a>';
			}
		}else if($name == '报价(详情)') {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="'.$admin_path.'/'.$c.'/'.$m.'/'.$id.'"><span class="btn btn-xs '.$color.'">'.$name.'</span></a>';
			}
		}else if($name == '完成一人') {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="'.$admin_path.'/'.$c.'/'.$m.'/'.$id.'"><span class="btn btn-xs '.$color.'">'.$name.'</span></a>';
			}
		}else if($name == '已经打款') {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="#"><span class="btn btn-xs '.$color.'">'.$name.'</span></a>';
			}
		}else if($name == '求职者简历') {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="' . $admin_path . '/' . $c . '/' . $m . '/' . $id . '"><span class="btn btn-xs ' . $color . '">' . $name . '</span></a>';
			}
		}else if($name == '设为已打款') {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="' . $admin_path . '/' . $c . '/' . $m . '/' . $id . '"><span class="btn btn-xs ' . $color . '">' . $name . '</span></a>';
			}
		}else if($name == '添加文案') {
			if($authority['add_casus_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="'.$admin_path.'/'.$c.'/'.$m.'/'.$id.'"><span class="btn btn-xs '.$color.'">'.$name.'</span></a>';
			}
		}else if($name == '查看文案') {
			if($authority['add_casus_status'] == 2) {
				$edit_url = '<a target="_blank" style="margin-right:5px;" href="'.$admin_path.'/'.$c.'/'.$m.'/'.$id.'"><span class="btn btn-xs '.$color.'">'.$name.'</span></a>';
			}
		}else if($name == '企业信息') {
			if ($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="' . $admin_path . '/' . $c . '/' . $m . '/' . $id . '"><span class="btn btn-xs ' . $color . '">' . $name . '</span></a>';
			}
		}else if($name == "热招职位") {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="' . $admin_path . '/' . $c . '/' . $m . '/' . $id . '"><span class="btn btn-xs ' . $color . '">' . $name . '</span></a>';
			}
		}else if($name == "查看简历") {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="' . $admin_path . '/' . $c . '/' . $m . '/' . $id . '"><span class="btn btn-xs ' . $color . '">' . $name . '</span></a>';
			}
		}else if($name == "人才库") {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="' . $admin_path . '/' . $c . '/' . $m . '/' . $id . '"><span class="btn btn-xs ' . $color . '">' . $name . '</span></a>';
			}
		}else if($name == "会场人才库") {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="' . $admin_path . '/' . $c . '/' . $m . '/' . $id . '"><span class="btn btn-xs ' . $color . '">' . $name . '</span></a>';
			}
		}else if($name == "保证金退还") {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="' . $admin_path . '/' . $c . '/' . $m . '/' . $id . '"><span class="btn btn-xs ' . $color . '">' . $name . '</span></a>';
			}
		}else if($name == "收入明细") {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="' . $admin_path . '/' . $c . '/' . $m . '/' . $id . '"><span class="btn btn-xs ' . $color . '">' . $name . '</span></a>';
			}
		}else if($name == "提现记录") {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="' . $admin_path . '/' . $c . '/' . $m . '/' . $id . '"><span class="btn btn-xs ' . $color . '">' . $name . '</span></a>';
			}
		}else if($name == "提现账户") {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="' . $admin_path . '/' . $c . '/' . $m . '/' . $id . '"><span class="btn btn-xs ' . $color . '">' . $name . '</span></a>';
			}
		}else if($name == "设为已打款") {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="' . $admin_path . '/' . $c . '/' . $m . '/' . $id . '"><span class="btn btn-xs ' . $color . '">' . $name . '</span></a>';
			}
		}else if($name == "修改订单状态") {
			if($authority['look_status'] == 2) {
				$edit_url = '<a style="margin-right:5px;" href="' . $admin_path . '/' . $c . '/' . $m . '/' . $id . '"><span class="btn btn-xs ' . $color . '">' . $name . '</span></a>';
			}
		}

		return $edit_url;
 	 }

 	 /**
 	  * @brief	获取onclick信息链接
 	  * @param 	Null
 	  * @author	Allen
 	  * @since	2016/10/24 Ver 1.0
 	  */
 	 public function delete_url($delete_url,$id,$name="删除",$color,$parameter='') {
 	 	$del_url = '';
		$authority = $this->authority;
 	 	$admin_path = $this->data['admin_path'];
 	 	if($name == "删除") {
	 	 	if($authority['del_status'] == 2) {
	 	 		$del_url = '<a style="margin-right:5px;" onclick="deleteOne('.$id.',\''.$delete_url.'\')"  data_id = '.$id.'><span class="btn btn-xs '.$color.'">'.$name.'</span></a>';	 			 	
	 	 	}
 	 	}else if($name == "冻结" || $name == "解冻") {
 	 		if($this->router->class == 'one_level') {
 	 			$is_status = $authority['one_level_status'];
 	 		}
 	 		if($this->router->class == 'two_level') {
 	 			$is_status = $authority['two_level_status'];
 	 		}
 	 		if($this->router->class == 'three_level') {
 	 			$is_status = $authority['three_level_status'];
 	 		}
 	 		if($this->router->class == 'four_level') {
 	 			$is_status = $authority['four_level_status'];
 	 		}
 	 		if($this->router->class == 'five_level') {
 	 			$is_status = $authority['five_level_status'];
 	 		}
	 	 	if($is_status == 2) {
	 	 		$del_url = '<a style="margin-right:5px;" onclick="deleteOne('.$id.',\''.$delete_url.'\')"  data_id = '.$id.'><span class="btn btn-xs '.$color.'">'.$name.'</span></a>';	 			 	
	 	 	}
 	 	}else if($name == "上架" || $name == "下架") {
			if($authority['del_status'] == 2) {
				$del_url = '<a style="margin-right:5px;" onclick="deleteOne('.$id.',\''.$delete_url.'\')"  data_id = '.$id.'><span class="btn btn-xs '.$color.'">'.$name.'</span></a>';
			}
		}else if($name == "设为已打款") {
			if($authority['del_status'] == 2) {
				$del_url = '<a style="margin-right:5px;" onclick="deleteOne('.$id.',\''.$delete_url.'\')"  data_id = '.$id.'><span class="btn btn-xs '.$color.'">'.$name.'</span></a>';
			}
		}else if($name == "封号") {
			if($authority['del_status'] == 2) {
				$del_url = '<a style="margin-right:5px;" onclick="deleteOne('.$id.',\''.$delete_url.'\')"  data_id = '.$id.'><span class="btn btn-xs '.$color.'">'.$name.'</span></a>';
			}
		}
 	 	return $del_url;
 	 }

 	 /**
 	  * @brief	获取选择框
 	  * @param 	Null
 	  * @author	Allen
 	  * @since	2016/10/24 Ver 1.0
 	  */
 	 public function get_check($id) {
 	 	$check_url = '<label><input name="c_id" type="checkbox" value="'.$id.'" /></label>';
 	 	return $check_url;
 	 }	

 	 /**
 	  * @brief	视图地址
 	  * @param 	Null
 	  * @author	Allen
 	  * @since	2016/10/24 Ver 1.0
 	  */
 	 public function display($view,$view_path='/admin/') {
 	 	$url_view = $this->load->view($view_path.$view,$this->data);
 	 	return $url_view;
 	 }
 	 
 	 /**
 	  * @brief	赋值
 	  * @param 	Null
 	  * @author	Allen
 	  * @since	2016/12/20 Ver 1.0
 	  */
 	 public function assign($field,$field_data) {
 	 	return $this->data[$field] = $field_data;
 	 }
 	 
 	 /**
 	  * @brief	跳转链接
 	  * @param 	Null
 	  * @author	Allen
 	  * @since	2016/10/24 Ver 1.0
 	  */
 	 public function get_link($link) {
 	 	$link ='<a href = "'.$link.'" target="_black">'.$link.'</a>';
 	 	return $link;
 	 }

 	 /**
 	  * @brief	递归数组
 	  * @param 	Null
 	  * @author	Allen
 	  * @since	2016/10/24 Ver 1.0
 	  */
 	 public function merge_node($node,$pid = 0) {
 	 	$arr = array();
 	 	foreach ($node as $k => $val) {
 	 		if($val['pid'] == $pid ) {
 	 			$val['child'] = $this->merge_node($node,$val['node_id']);
 	 			$arr[] = $val;
 	 		}
 	 	}
 	 	return $arr;
 	 }

 	 /**
 	  * @brief	获取父权限
 	  * @param 	Null
 	  * @author	Allen
 	  * @since	2016/10/24 Ver 1.0
 	  */
 	 public function parent_access() {
 	 	$admin_id = $this->admin['id'];
 	 	$role_id = $this->admin['rid'];
 	 	$this->load->model('access_model');
 	 	$access = $this->access_model->getAccessAll($where=array('role_id'=>$role_id));
 	 	$this->load->model('node_model');
 	 	$nodes = array();
 	 	foreach ($access as $key => $value) {
 	 		$tmp =$this->node_model->checkNode(array('node_id'=>$value['node_id'],'pid'=>0));
 	 		if($tmp) {
 	 			$nodes[] = $tmp['node_name'];
 	 		}
 	 	}
 	 	$this->data['parent_access'] = $nodes;//管理员权限 	 		
 	 	//所有父节点 权限
 	 	$parent_nodes = $this->node_model->getNodeAll(array("pid"=>0));
 	 	foreach ($parent_nodes as $k => $v){
 	 		$parent_nodes[$k] = $v['node_name'];
 	 	}
 	 	$this->data['all_node'] = $parent_nodes;
 	 }

 	 /**
 	  * @brief	获取权限
 	  * @param 	Null
 	  * @author	Allen
 	  * @since	2016/10/24 Ver 1.0
 	  */
 	 public function Access() {
 	 	$admin_id = $this->admin['id'];
 	 	$role_id = $this->admin['rid'];
 	 	$this->load->model('access_model');
 	 	if($role_id == 1) {
 	 		$access = $this->access_model->getAccessAll(); //超级管理员
 	 	}else {
 	 		$access = $this->access_model->getAccessAll($where=array('role_id'=>$role_id));
 	 	}
 	 	$nodes = array();
 	 	foreach ($access as $key => $value) {
 	 		$this->load->model('node_model');
 	 		$nodes[] = $this->node_model->checkNode(array('node_id'=>$value['node_id']));
 	 	}
 	 	$nodes = $this->merge_node($nodes);
 	 	return $nodes;
 	 }

 	 /**
 	  * @brief	权限控制
 	  * @param 	Null
 	  * @author	Allen
 	  * @since	2016/10/24 Ver 1.0
 	  */
 	 protected function RBAC() {
 	 	$admin_id = $this->admin['id'];//username=admin  123456
 	 	$role_id = $this->admin['rid'];
 	 	//去数据中读取权限
 	 	$this->load->model('access_model');
 	 	$access = $this->access_model->getAccessAll($where=array('role_id'=>$role_id));
 	 	$controller = $this->data['controller'];//获取当前控制器名称
 	 	$method = $this->data['method'] ;//获取当前方法名称
 	 	$this->load->model('node_model');
 	 	$controller_nodes = $this->node_model->checkNode(array('node_name'=>$controller,'pid'=>0));
 	 	$nodes = $this->node_model->checkNode(array('node_name'=>$method,'pid'=>$controller_nodes['node_id']));
 	 	if($role_id != 1) {
 	 		if($controller_nodes && $nodes) {
 	 			$is_auth = false;
 	 			foreach ($access as $key => $value) {
 	 				if($value['node_id'] == $nodes['node_id']) {
 	 					$is_auth = true;
 	 				}
 	 			}
 	 			if(!$is_auth) {
 	 				show_error("您没有权限",404,"权限错误");
 	 				exit;
 	 			}
 	 		}
 	 	}
 	 }
 	 
 	 /**
 	  * @brief	判断权限
 	  * @param 	Null
 	  * @author	Allen
 	  * @since	2016/10/24 Ver 1.0
 	  */
 	 protected function Isallow($c,$m) {
 	 	$admin_id = $this->admin['id'];
 	 	$role_id = $this->admin['rid'];
 	 	//去数据中读取权限
 	 	$this->load->model('access_model');
 	 	$access = $this->access_model->getAccessAll($where=array('role_id'=>$role_id));
 	 	$this->load->model('node_model');
 	 	$controller_nodes = $this->node_model->checkNode(array('node_name'=>$c,'pid'=>0));
 	 	$nodes = $this->node_model->checkNode(array('node_name'=>$m,'pid'=>$controller_nodes['node_id']));
 	 	if($role_id != 1) {
 	 		if($controller_nodes && $nodes) {
 	 			$is_auth = false;
 	 			foreach ($access as $key => $value) {
 	 				if($value['node_id'] == $nodes['node_id']) {
 	 					$is_auth = true;
 	 				}
 	 			}
 	 			return $is_auth;
 	 		}
 	 	}else {
 	 		return true;
 	 	}
 	 }
 	 	
 	 /**
 	  * @brief	获取权限
 	  * @param 	Null
 	  * @author	Allen
 	  * @since	2016/10/24 Ver 1.0
 	  */
 	 public function Isauthority($c) {
 	 	/**添加、修改、多行删除及单行删除、查看、导出权限 为2则开通此操作**/
 	 	$this_data['add_status'] = 1;
 	 	$this_data['edit_status'] = 1;
 	 	$this_data['delete_status'] = 1;
 	 	$this_data['del_status'] = 1;
 	 	$this_data['look_status'] = 1;
 	 	$this_data['export_status'] = 1;
 	 	if($c == 'role') {
 	 		$this_data['set_status'] = 1;
 	 		if($this->Isallow('access', 'edit_page')) $this_data['set_status'] = 2;
 	 	} 
 	 	if($c == 'goods') {
 	 		$this_data['up_status'] = 1;
 	 		$this_data['add_casus_status'] = 1;
 	 		$this_data['casus_status'] = 1;
 	 		if($this->Isallow($c,'agree_up')) $this_data['up_status'] = 2;
 	 		if($this->Isallow($c,'add_case')) $this_data['add_casus_status'] = 2;
 	 		if($this->Isallow($c,'casus_list')) $this_data['casus_status'] = 2;
 	 	} 
 	 	if($c == 'one_level') {
 	 		$this_data['one_level_status'] = 1;
 	 		if($this->Isallow($c,'agree_one_level')) $this_data['one_level_status'] = 2;
 	 	} 
 	 	if($c == 'two_level') {
 	 		$this_data['two_level_status'] = 1;
 	 		if($this->Isallow($c,'agree_two_level')) $this_data['two_level_status'] = 2;
 	 	} 
 	 	if($c == 'three_level') {
 	 		$this_data['three_level_status'] = 1;
 	 		if($this->Isallow($c,'agree_three_level')) $this_data['three_level_status'] = 2;
 	 	}
 	 	if($c == 'four_level') {
 	 		$this_data['four_level_status'] = 1;
 	 		if($this->Isallow($c,'agree_four_level')) $this_data['four_level_status'] = 2;
 	 	}	
 	 	if($c == 'five_level') {
 	 		$this_data['five_level_status'] = 1;
 	 		if($this->Isallow($c,'agree_five_level')) $this_data['five_level_status'] = 2;
 	 	}	 	
 	 	if($c == 'pay') {
 	 		$this_data['pay_status'] = 1;
 	 		if($this->Isallow($c,'pay_coin')) $this_data['pay_status'] = 2;
 	 	}
 	 	if($this->Isallow($c, 'add_page')) $this_data['add_status'] = 2;
 	 	if($this->Isallow($c, 'edit_page')) $this_data['edit_status'] = 2;
 	 	if($this->Isallow($c, 'delete')) $this_data['del_status'] = 2;
 	 	if($this->Isallow($c, 'delete_all')) $this_data['delete_status'] = 2;
 	 	if($this->Isallow($c, 'look_page')) $this_data['look_status'] = 2;
 	 	if($this->Isallow($c, 'export_excel')) $this_data['export_status'] = 2;
 	 	return $this_data;
 	 }
 	 
 	 /**
 	  * @brief	获取图案
 	  * @param 	Null
 	  * @author	Allen
 	  * @since	2016/12/22 Ver 1.0
 	  */
 	 public function tag_img($id,$field,$value="1",$delete_url="/goods/agree_up",$height="20") {
 	 	$authority = $this->authority; //获取是否有编辑权限
 	 	if($value == 1) {
 	 		$avatar = '/public_source/www/images/no.png';
 	 	}else {
 	 		$avatar = '/public_source/www/images/yes.png';
 	 	}
 	 	if($value == 1) { //给标签现在相反的值
 	 		$value = 2;
 	 	}else {
 	 		$value = 1;
 	 	}
 	 	if($authority['up_status'] == 2) {
 	 		$avatar = '<a onclick="changeTag('.$id.',\''.$field.'\','.$value.',\''.$delete_url.'\')" style="cursor: pointer;"><img src="'.$avatar.'" style="max-height:'.$height.'px;"/></a>';
 	 	}else {
 	 		$avatar = '<img src="'.$avatar.'" style="max-height:'.$height.'px;"/>';
 	 	}
 	 	return $avatar;
 	 }
 	 
 	 /**
 	  * @brief	获取客户端IP
 	  * @param 	Null
 	  * @author	Allen
 	  * @since	2016/07/12 Ver 1.0
 	  */
 	 public function get_ip() {
 	 	global $ip;
 	 	if (getenv("HTTP_CLIENT_IP")) {
 	 		$ip = getenv("HTTP_CLIENT_IP");
 	 	}else if(getenv("HTTP_X_FORWARDED_FOR")) {
 	 		$ip = getenv("HTTP_X_FORWARDED_FOR");
 	 	}else if(getenv("REMOTE_ADDR")) {
 	 		$ip = getenv("REMOTE_ADDR");
 	 	}else {
 	 		$ip = "";
 	 	}
 	 	return $ip;
 	 }
 	 
	/**
	 * @brief	获取地址
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/23 Ver 1.0
	 */
	public function GetIpLookup($ip = '') {
		if(empty($ip)) {
			$ip = $this->get_ip();
		}
		$res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
		if(empty($res)){ return false; }
		$jsonMatches = array();
		preg_match('#\{.+?\}#', $res, $jsonMatches);
		if(!isset($jsonMatches[0])){ return false; }
		$json = json_decode($jsonMatches[0], true);
		if(isset($json['ret']) && $json['ret'] == 1){
			$json['ip'] = $ip;
			unset($json['ret']);
		}else{
			return false;
		}
		return $json;
	}
	
	/**
	 * @brief	判断数据是否合理
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/28 Ver 1.0
	 */
	public function check_rational($rational_array) {
		if(!$rational_array) {
			$this->data['message'] = 10;
		}
	}
	
	/**
	 * @brief	生成证书
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/30 Ver 1.0
	 */
	public function create_diploma($QR,$logo,$data) {
		$font = './public_source/www/diploma/simhei.ttf';//字体路径
		if ($logo !== FALSE) {
			$QR = imagecreatefromstring(file_get_contents($QR));
			$logo = imagecreatefromstring(file_get_contents($logo));
			$QR_width = imagesx($QR);//图片宽度
			$QR_height = imagesy($QR);//图片高度
			$logo_width = imagesx($logo);//logo图片宽度
			$logo_height = imagesy($logo);//logo图片高度
			$logo_qr_width = $QR_width / 5;
			$scale = $logo_width/$logo_qr_width;
			$logo_qr_height = $logo_height/$scale;
			$from_width = ($QR_width - $logo_qr_width) / 6;
			//重新组合图片并调整大小
			$black = imagecolorallocate($QR, 2, 2, 2);//字体颜色
			imagefttext($QR, 18, 0, 285, 400, $black, $font, $data['user_name']);
			imagefttext($QR, 18, 0, 285, 448, $black, $font, $data['wechat_num']);
			imagefttext($QR, 18, 0, 285, 495, $black, $font, $data['phone']);
			imagecopyresampled($QR, $logo, 25, 360, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
		}
		//输出图片
		$qrcode_url = "./public_source/www/diploma/diploma_".$data['id'].".png";
		imagepng($QR,$qrcode_url);
		//$qrcode_url = WEB_URL."/public_source/www/diploma/diploma_".$data['id'].".png";
		//return $qrcode_url;
	}
	/**
	 * Enter 输出结果，同时会判断返回的code
	 */
	public function response($success=0, $error_msg='',$is_object=false,$result = array())
	{

		if($success ==0 ){
			$result['status']['succeed']='1';
		}else{
			$result['status']['succeed']	='0';
			$result['status']['error_code']	=$success;
			$result['status']['error_desc'] =$error_msg?$error_msg:$this->getErrorMsg($success);
		}
		//error_log(date('Y-m-d H:i:s')."数据开始发送：\n",3,'log.txt');
		header("Access-Control-Allow-Origin:*");
		header("Content-type: text/html; charset=utf-8");
		header('Content-type : application/json');
		if ($is_object) {
			///echo 1211111111111111;
			//error_log('json:'.json_encode($result, JSON_FORCE_OBJECT)."\n",3,'log.txt');
			echo json_encode($result, JSON_FORCE_OBJECT);
			//error_log(date('Y-m-d H:i:s')."数据发送结束：\n",3,'log.txt');
			exit;
		} else {
			echo json_encode($result);
			//error_log(date('Y-m-d H:i:s')."数据发送结束：\n",3,'log.txt');
			exit;
		}
	}
	/**
	 * Enter description here ...
	 * tags
	 */
	private function getErrorMsg($success=0){
		@include(APPPATH.'config/http_status.php');
		return isset($http_status[$success])?$http_status[$success]:'未知错误';

	}
}