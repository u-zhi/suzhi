<?php

class Company_task_outsourcing_model extends HS_Model {
    //获取结果集
    public function getCompany_task_outsourcingList($where=array(), $limit='20', $offset='0', $order_by='id desc',$fields="*")
    {
        return $this->select($fields)
                ->join('firm_profile','firm_profile.id = company_task_outsourcing.company_id','left')
                ->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }
    //获取所有结果集
    public function getCompany_task_outsourcingAll($where=array(),$fields="*",$order_by='id desc',$fields="*")
    {
        return $this->select($fields)->where($where)->order_by($order_by)->find_all();
    }   
    //获取一个结果集
    public function getCompany_task_outsourcingOne($where=array(),$fields="*",$order_by='id desc',$fields="*")
    {
        return $this->select($fields)->where($where)->order_by($order_by)->find();
    }
    //计算行数
    public function getCount($where = array(),$where_in=array())
    {
        if($where_in && is_array($where_in)){
            foreach($where_in as $key=>$value){
                $this->where_in($key,$value);
            }
        }
        return $this->where($where)
                ->join('firm_profile','firm_profile.id = company_task_outsourcing.company_id','left')
                ->count();

    }  
     
    //获取单个
    public function checkCompany_task_outsourcing($where=array(),$fields="*")
    {
        return $this->select($fields)
                ->join('firm_profile','firm_profile.id = company_task_outsourcing.company_id','left')
                ->where($where)->find();
    }
    
    
    //删除
    public function deleteCompany_task_outsourcing($where) 
    {
        return $this->where($where)->delete();
    }
    
    //删除多行
    public function deleteAll($where)
    {
        return $this->where_in('id',$where)->delete();
    }
    
    //添加
    public function addCompany_task_outsourcing($data)
    {
        if ($this->add($data))
        {
            return $this->db->insert_id();
        }
    }

    //编辑
    public function editCompany_task_outsourcing($where,$data)
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
