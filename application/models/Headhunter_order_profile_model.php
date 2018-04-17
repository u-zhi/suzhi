<?php

class Headhunter_order_profile_model extends HS_Model {
    //获取结果集
    public function getOrderList($where=array(), $limit='20', $offset='0', $order_by='id desc',$fields="*",$join=false)
    {
        if($join){
            return $this->select($fields)
                ->join('task_profile','task_profile.id = headhunter_order_profile.task_id','left')
                ->join('user_profile','user_profile.id = headhunter_order_profile.user_id','left')
                ->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
        }
    	return $this->select($fields)->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }
    //获取所有结果集
    public function getOrderAll($where=array(),$fields="*",$order_by='id desc',$join=false)
    {
        if($join){
            return $this->select($fields)
                ->join('task_profile','task_profile.id = headhunter_order_profile.task_id','left')
                ->where($where)->order_by($order_by)->find_all();
        }


    	return $this->select($fields)->where($where)->order_by($order_by)->find_all();
    }   
    //获取一个结果集
    public function getOrderOne($where=array(),$fields="*",$order_by='id desc',$fields="*")
    {
        return $this->select($fields)->where($where)->order_by($order_by)->find();
    }
    //计算行数
    public function getCount($where = array(),$join=false)
    {
        if($join){
            return $this->where($where)
                ->join('task_profile','task_profile.id = headhunter_order_profile.task_id','left')
                ->join('user_profile','user_profile.id = headhunter_order_profile.user_id','left')
                ->count();
        }
    	return $this->where($where)->count();
    }  
     
    //获取单个
    public function checkOrder($where=array(),$fields="*",$join=false)
    {
        if($join){
            return $this->select($fields)
                ->join('task_profile','task_profile.id = headhunter_order_profile.task_id','left')
                ->join('user_profile','user_profile.id = headhunter_order_profile.user_id','left')
                ->where($where)->find();
        }
    	return $this->select($fields)->where($where)->find();
    }
	
    
    //删除
    public function deleteOrder($where) 
    {
    	return $this->where($where)->delete();
    }
    
    //删除多行
    public function deleteAll($where)
    {
    	return $this->where_in('id',$where)->delete();
    }
    
    //添加
    public function addOrder($data)
    {
    	if ($this->add($data))
    	{
    		return $this->db->insert_id();
    	}
    }

    //编辑
    public function editOrder($where,$data)
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
