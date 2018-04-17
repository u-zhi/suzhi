<?php
/**
 * User: Allen
 * Date: 16-12-20
 * 管理员模型
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

class Admin_model extends HS_Model {


    //获取结果集
    public function getAdminList($where=array(), $limit='20', $offset='0', $order_by='id desc',$fields="*")
    {
            return $this->select($fields)
                ->join('role','role.role_id = admin.rid','left')
                ->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }    
    //获取结果集先
/*    public function getAdminList($where=array(), $limit='20', $offset='0', $order_by='id desc')
    {
    	return $this->select('admin.*,role.role_name')
    	->join('role','role.role_id = admin.rid')
    	->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }*/
/*    //获取所有结果集
    public function getAdminAll($where=array(),$fields="*",$order_by='id desc',$join=false)
    {
        if($join){
            return $this->select($fields)
                ->join('role','role.role_id = admin.rid','left')
                ->where($where)->order_by($order_by)->find_all();
        }
        return $this->select($fields)->where($where)->order_by($order_by)->find_all();
    } */
    
    //获取所有结果集
    public function getAdminAll($where=array())
    {
    	return $this->select('admin.*,role.role_name')
    	->join('role','role.role_id = admin.rid')
    	->where($where)->find_all();
    }
    
    
    //计算行数
    public function getCount($where=array())
    {
    	return $this->where($where)
                    ->join('role','role.role_id = admin.rid')
                    ->count();
    }  
     
    //获取单个
    public function checkAdmin($where=array(),$fields="*")
    {
    	return $this->select($fields)->where($where)->find();
    }
    
    //删除
    public function deleteAdmin($where)
    {
    	return $this->where($where)->delete();
    }
    
    //删除多行
    public function deleteAll($where)
    {
    	return $this->where_in('id',$where)->delete();
    }
    
    //编辑
    public function editAdmin($where,$data)
    {
    	return $this->where($where)->edit($data);
    }
    
    //添加
    public function addAdmin($data)
    {
    	if ($this->add($data))
    	{
    		return $this->db->insert_id();
    	}
    }
}
?>
