<?php

class Balance_withdraw_profile_model extends HS_Model {
    //获取结果集
    public function getBalanceList($where=array(), $limit='20', $offset='0', $order_by='id desc',$fields="*",$join=false,$joins=false)
    {
        if($join){
            return $this->select($fields)
                ->join('balance_withdraw_order','balance_withdraw_order.method_id = balance_withdraw_profile.id','left')
                ->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
        }
        if($joins){
            return $this->select($fields)
                ->join('balance_withdraw_order','balance_withdraw_order.method_id = balance_withdraw_profile.id','left')
                ->join('user_profile','user_profile.id = balance_withdraw_profile.user_id','left')
                ->join('id_verfication','id_verfication.user_id = balance_withdraw_profile.user_id','left')
                ->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
        }
    	return $this->select($fields)->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }
    //获取所有结果集
    public function getBalanceAll($where=array(),$fields="*",$order_by='id desc',$join=false)
    {
        if($join){
            return $this->select($fields)
                ->join('balance_withdraw_order','balance_withdraw_order.method_id = balance_withdraw_profile.id','left')
                ->where($where)->order_by($order_by)->find_all();
        }
    	return $this->select($fields)->where($where)->order_by($order_by)->find_all();
    }   
    //获取一个结果集
    public function getBalanceOne($where=array(),$fields="*",$order_by='id desc',$fields="*")
    {
        return $this->select($fields)->where($where)->order_by($order_by)->find();
    }
    //计算行数
    public function getCount($where = array(),$join=false,$joins=false)
    {
        if($join){
            return $this->where($where)
                ->join('balance_withdraw_order','balance_withdraw_order.method_id = balance_withdraw_profile.id','left')
                ->count();
        }
        if($joins){
            return $this->where($where)
                ->join('balance_withdraw_order','balance_withdraw_order.method_id = balance_withdraw_profile.id','left')
                ->join('user_profile','user_profile.id = balance_withdraw_profile.user_id','left')
                ->join('id_verfication','id_verfication.user_id = balance_withdraw_profile.user_id','left')
                ->count();
        }
    	return $this->where($where)->count();
    }  
     
    //获取单个
    public function checkBalance($where=array(),$fields="*",$order_by='id desc')
    {
    	return $this->select($fields)->where($where)->order_by($order_by)->find();
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
