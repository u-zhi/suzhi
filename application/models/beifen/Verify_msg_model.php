<?php
/**
 * Created by Allen.
 * Date: 16-06-14
 * 会员模型
 */
class Verify_msg_model extends HS_Model {
    //获取结果集
    public function getVerifyList($where=array(), $limit='20', $offset='0', $order_by='id desc',$fields="*")
    {
    	return $this->select($fields)->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }
    
    //获取所有结果集
    public function getVerifyAll($where=array(),$fields="*",$order_by='id desc')
    {
    	return $this->select($fields)->where($where)->order_by($order_by)->find_all();
    }   
    
    //计算行数
    public function getCount($where = array())
    {
    	return $this->where($where)->count();
    }  
     
    //获取单个
    public function checkVerify($where=array(),$fields="*")
    {
    	return $this->select($fields)->where($where)->find();
    }
    
    //删除
    public function deleteVerify($where) 
    {
    	return $this->where($where)->delete();
    }
    
    //删除多行
    public function deleteAll($where)
    {
    	return $this->where_in('id',$where)->delete();
    }
    
    //添加
    public function addVerify($data)
    {
    	if ($this->add($data))
    	{
    		return $this->db->insert_id();
    	}
    }

    //编辑
    public function editVerify($where,$data)
    {
    	return $this->where($where)->edit($data);
    }
    
    //编辑多行
    public function editAll($where,$data)
    {
    	return $this->where_in('id',$where)->edit($data);
    }
    
    //对数据库字段进行自增修改
    public function addId($where,$col,$value){
    	$this->db->where($where);
    	if($value >= 0) {
    		$this->db->set($col,$col.' + '.$value,FALSE);
    	}else {
    		$this->db->set($col,$col.' - '.abs($value),FALSE);
    	}
    	$this->db->set('update_time',time(),FALSE);
    	return $this->db->update('user'); 
    }
}
?>
