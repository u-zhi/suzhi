<?php
/**
 * User: Allen
 * Date: 16-07-12
 * 管理员权限模型
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

class Access_model extends HS_Model {

	//添加
    public function addAccess($data)
    {
        if ($this->add($data))
        {
            return $this->db->insert_id();
        }
    }
    
    //获取单个
    public function checkAccess($where)
    {
    	return $this->where($where)->find();
    }

    //编辑
    public function editAccess($where=array(),$data)
    {
        return $this->where($where)->edit($data);
    }
    
    //获取结果集
    public function getAccessList($where=array(), $limit='20', $offset='0', $order_by='node_id desc')
    {
    	return $this->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }
    
    //获取全部结果集
    public function getAccessAll($where=array(), $order_by='node_id desc')
    {
    	return $this->where($where)->order_by($order_by)->find_all();
    }
    
    //计算行数
    public function getCount($where = array())
    {
    	return $this->where($where)->count();
    }
    
    //删除
    public function deleteAccess($where = array())
    {
    	return $this->where($where)->delete();
    }
} 