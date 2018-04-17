<?php

class Base_region_model extends HS_Model {
    //获取结果集
    public function getRegionList($where=array(), $limit='20', $offset='0', $order_by='region_id desc',$fields="*")
    {
        return $this->select($fields)->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }
    //获取所有结果集
    public function getRegionAll($where=array(),$fields="*",$order_by='region_id desc',$fields="*")
    {
        return $this->select($fields)->where($where)->order_by($order_by)->find_all();
    }   
    //获取一个结果集
    public function getRegionOne($where=array(),$fields="*",$order_by='region_id desc',$fields="*")
    {
        return $this->select($fields)->where($where)->order_by($order_by)->find();
    }
    //计算行数
    public function getCount($where = array())
    {
        return $this->where($where)->count();
    }  
     
    //获取单个
    public function checkRegion($where=array(),$fields="*")
    {
        return $this->select($fields)->where($where)->find();
    }
    
    
    //删除
    public function deleteRegion($where) 
    {
        return $this->where($where)->delete();
    }
    
    //删除多行
    public function deleteAll($where)
    {
        return $this->where_in('region_id',$where)->delete();
    }
    
    //添加
    public function addRegion($data)
    {
        if ($this->add($data))
        {
            return $this->db->insert_region_id();
        }
    }

    //编辑
    public function editRegion($where,$data)
    {
        return $this->where($where)->edit($data);
    }
    
    //编辑多行
    public function editAll($where,$data)
    {
        return $this->where_in('region_id',$where)->edit($data);
    }
    
    
}
?>
