<?php
/**
 * User: Allen
 * Date: 16-08-02
 * 用户模型
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

class User_model extends HS_Model {
    //获取结果集
    public function getUserList($where=array(), $limit='20', $offset='0', $order_by='id desc',$fields="*")
    {
    	return $this->select($fields)->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }  

    //获取联表集
    public function getJoinList($where=array(), $limit='20', $offset='0', $order_by='id desc',$fields="*")
    {    
    	return $this->select($fields)
    	->join('user as temp','temp.id = user.parent_id','left')
    	->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }
    
    //获取联表集
    public function getAuditList($where=array(), $limit='20', $offset='0', $order_by='id desc',$fields="*")
    {   
    	return $this->select($fields)
    	->join('purchase','purchase.user_id = user.id','left')
    	->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }
    
    //获取所有结果集
    public function getUserAll($where=array(),$fields="*",$order_by='id desc')
    {
    	return $this->select($fields)->where($where)->order_by($order_by)->find_all();
    }   
    
    //获取导出表格信息
    public function getExportAll($where=array(),$fields="user.*")
    {
    	return $this->select($fields)
    	->join('user as one','one.id = user.one_id','left')
    	->join('user as two','two.id = user.two_id','left')
    	->join('user as three','three.id = user.three_id','left')
    	->join('user as four','four.id = user.four_id','left')
    	->where($where)->find_all();
    }
    
	//统计数据
	public function getChartList($where=array(),$fields="*",$group_by='id') 
	{
		$sql = "SELECT
  					$fields
				FROM
  					(SELECT 1 agent_level
 						UNION ALL
  					SELECT 2
  						UNION ALL
  					SELECT 3
  						UNION ALL
  					SELECT 4
  						UNION ALL
  					SELECT 5
 					) a
				LEFT JOIN
					(SELECT
					  mu.agent_level, COUNT(1) level_num
					FROM
					  moyoo_user mu
					WHERE $where
					GROUP BY $group_by
					) b ON a.agent_level = b.agent_level ORDER BY a.agent_level asc;";
		$query = $this->db->query($sql);	
    	$result = $query->result_array();
    	return $result;
	}
    
    //计算行数
    public function getCount($where = array())
    {
    	return $this->where($where)->count();
    }  
     
    //获取单个
    public function checkUser($where=array(),$fields="*")
    {
    	return $this->select($fields)->where($where)->find();
    }
    
    //获取单个及上级信息
    public function checkJoinUser($where=array(),$fields="user.*")
    {
    	return $this->select($fields)
    	->join('user as one','one.id = user.one_id','left')
    	->join('user as two','two.id = user.two_id','left')
    	->join('user as three','three.id = user.three_id','left')
    	->join('user as four','four.id = user.four_id','left')
    	->where($where)->find();
    }
    
    //删除
    public function deleteUser($where) 
    {
    	return $this->where($where)->delete();
    }
    
    //删除多行
    public function deleteAll($where)
    {
    	return $this->where_in('id',$where)->delete();
    }
    
    //添加
    public function addUser($data)
    {
    	if ($this->add($data))
    	{
    		return $this->db->insert_id();
    	}
    }

    //编辑
    public function editUser($where,$data)
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
