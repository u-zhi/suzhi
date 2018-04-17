<?php

class Open_city_model extends HS_Model {
    //获取结果集
    public function getOpenList($where=array(), $limit='20', $offset='0', $order_by='id desc',$fields="*")
    {
        return $this->select($fields)
                    ->join('base_region','base_region.region_id = open_city.province_id')
                    ->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }
    //获取所有结果集
    public function getOpenAll($where=array(),$fields="*",$order_by='id desc',$fields="*")
    {
        return $this->select($fields)->where($where)->order_by($order_by)->find_all();
    }   
    //获取一个结果集
    public function getOpenOne($where=array(),$fields="*",$order_by='id desc',$fields="*")
    {
        return $this->select($fields)->where($where)->order_by($order_by)->find();
    }
    //计算行数
    public function getCount($where = array())
    {
        return $this->where($where)->count();
    }  
     
    //获取单个
    public function checkOpen($where=array(),$fields="*")
    {
        return $this->select($fields)->where($where)->find();
    }
    
    
    //删除
    public function deleteOpen($where) 
    {
        return $this->where($where)->delete();
    }
    
    //删除多行
    public function deleteAll($where)
    {
        return $this->where_in('id',$where)->delete();
    }
    
    //添加
    public function addOpen($data)
    {
        if ($this->add($data))
        {
            return $this->db->insert_id();
        }
    }

    //编辑
    public function editOpen($where,$data)
    {
        return $this->where($where)->edit($data);
    }
    
    //编辑多行
    public function editAll($where,$data)
    {
        return $this->where_in('id',$where)->edit($data);
    }
    
    
}
?>
