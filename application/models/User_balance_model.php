<?php

class User_balance_model extends HS_Model {
    //获取结果集
    public function getBalanceList($where=array(), $limit='20', $offset='0', $order_by='id desc',$fields="*",$join=false)
    {
        if($join){
            return $this->select($fields)
                ->join('id_verfication','id_verfication.user_id = user_balance.user_id','left')
                ->join('user_profile','user_profile.id = user_balance.user_id','left')
                ->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
        }
    	return $this->select($fields)->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }
    //获取所有结果集
    public function getBalanceAll($where=array(),$fields="*",$order_by='id desc',$fields="*")
    {
    	return $this->select($fields)->where($where)->order_by($order_by)->find_all();
    }   
    //获取一个结果集
    public function getBalanceOne($where=array(),$fields="*",$order_by='id desc',$fields="*")
    {
        return $this->select($fields)->where($where)->order_by($order_by)->find();
    }
    //计算行数
    public function getCount($where = array(),$join)
    {
        if($join){
            return $this->where($where)
                ->join('id_verfication','id_verfication.user_id = user_balance.user_id','left')
                ->join('user_profile','user_profile.id = user_balance.user_id','left')
                ->count();
        }
    	return $this->where($where)->count();
    }  
     
    //获取单个
    public function checkBalance($where=array(),$fields="*")
    {
    	return $this->select($fields)->where($where)->find();
    }
	
    
    //删除
    public function deleteBalance($where) 
    {
    	return $this->where($where)->delete();
    }
    
    //删除多行
    public function deleteAll($where)
    {
    	return $this->where_in('id',$where)->delete();
    }
    
    //添加
    public function addBalance($data)
    {
    	if ($this->add($data))
    	{
    		return $this->db->insert_id();
    	}
    }

    //编辑
    public function editBalance($where,$data)
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
