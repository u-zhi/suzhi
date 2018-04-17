<?php

class Jobhunter_work_model extends HS_Model {
    //获取结果集
    public function getWorkList($where=array(), $limit='20', $offset='0', $order_by='id desc',$fields="*",$join=false)
    {
        if($join){
            return $this->select($fields)
                ->join('task_profile','task_profile.id = headhunter_order_profile.task_id','left')
                ->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
        }
    	return $this->select($fields)->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }
    //获取所有结果集
    public function getWorkAll($where=array(),$fields="*",$order_by='id desc',$join=false)
    {
        if($join){
            return $this->select($fields)
                ->join('task_profile','task_profile.id = headhunter_order_profile.task_id','left')
                ->where($where)->order_by($order_by)->find_all();
        }


    	return $this->select($fields)->where($where)->order_by($order_by)->find_all();
    }   
    //获取一个结果集
    public function getWorkOne($where=array(),$fields="*",$order_by='id desc',$fields="*")
    {
        return $this->select($fields)->where($where)->order_by($order_by)->find();
    }
    //计算行数
    public function getCount($where = array())
    {
    	return $this->where($where)->count();
    }  
     
    //获取单个
    public function checkWork($where=array(),$fields="*",$join=false)
    {
        if($join){
            return $this->select($fields)
                ->join('jobhunter_order_profile','jobhunter_order_profile.id = jobhunter_work.jobhunter_order_id','left')
                ->where($where)->find();
        }
    	return $this->select($fields)->where($where)->find();
    }
	
    
    //删除
    public function deleteWork($where)
    {
    	return $this->where($where)->delete();
    }
    
    //删除多行
    public function deleteAll($where)
    {
    	return $this->where_in('id',$where)->delete();
    }
    
    //添加
    public function addWork($data)
    {
    	if ($this->add($data))
    	{
    		return $this->db->insert_id();
    	}
    }

    //编辑
    public function editWork($where,$data)
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
