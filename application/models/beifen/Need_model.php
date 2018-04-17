<?php
/**
 * User: Allen
 * Date: 17-01-06
 * 意向代理模型
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

class Need_model extends HS_Model {

    //获取结果集
    public function getNeedList($where=array(), $limit='20', $offset='0',$order_by='id desc',$fields="*")
    {

    	return $this->select($fields)
    	->join('user','user.id = need.agency_id','left')
    	->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }
    
    //获取所有结果集
    public function getNeedAll($where=array(),$fields="*")
    {
    	return $this->select($fields)
    	->join('user','user.id = need.agency_id','left')
    	->where($where)->find_all();
    }
     
    //计算行数
    public function getCount($where = array())
    {
    	return $this->where($where)->count();
    }  
     
    //获取单个
    public function checkNeed($where=array(),$fields="*")
    {
    	return $this->select($fields)->where($where)->find();
    }
    
    //删除
    public function deleteNeed($where)
    {
    	return $this->where($where)->delete();
    }
    
    //编辑
    public function editNeed($where,$data)
    {
    	return $this->where($where)->edit($data);
    }
    
    //添加
    public function addNeed($data)
    {
    	if ($this->add($data))
    	{
    		return $this->db->insert_id();
    	}
    }
    
    //获取单个
    public function check($where=array(),$fields="*")
    {
    	return $this->select($fields)->where($where)->find();
    }

    //对数据库字段进行自增修改
    public function addId($where,$col,$value) {
    	$this->db->where($where);
    	if($value >= 0) {
    		$this->db->set($col,$col.' + '.$value,FALSE);
    	}else {
    		$this->db->set($col,$col.' - '.abs($value),FALSE);
    	}
    	//$this->db->set('update_time',time(),FALSE);
    	return $this->db->update('need');
    }
}
?>
