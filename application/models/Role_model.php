<?php
/**
 * User: Allen
 * Date: 16-07-12
 * 角色模型
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

class Role_model extends HS_Model {


    //获取结果集
    public function getRoleList($where=array(), $limit='20', $offset='0', $order_by='role_id desc')
    {
    	return $this->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }
    
    //获取所有结果集
    public function getRoleAll($where=array())
    {
    	return $this->where($where)->find_all();
    }
    
    
    //计算行数
    public function getCount($where = array())
    {
    	return $this->where($where)->count();
    }  
     
    //获取单个
    public function checkRole($where)
    {
    	return $this->where($where)->find();
    }
    
    //删除
    public function deleteRole($where)
    {
    	return $this->where($where)->delete();
    }
    
    //删除多行
    public function deleteAll($where)
    {
    	return $this->where_in('role_id',$where)->delete();
    }
    
    //编辑
    public function editRole($where,$data)
    {
    	return $this->where($where)->edit($data);
    }
    
    //添加
    public function addRole($data)
    {
    	if ($this->add($data))
    	{
    		return $this->db->insert_id();
    	}
    }
}
?>
