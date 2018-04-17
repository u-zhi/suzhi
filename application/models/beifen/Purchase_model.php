<?php
/**
 * User: Allen
 * Date: 16-11-29
 * 采购单模型
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
class Purchase_model extends HS_Model {
		
	//获取结果集
	public function getPurchaseList($where=array(), $limit='20', $offset='0', $order_by='purchase.id desc',$fields="*")
	{
		return $this->select($fields)
		->join('user','user.id = purchase.user_id','left')
		->join('user as temp','temp.id = purchase.parent_id','left')
		->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
	}
	//获取所有结果集
	public function getPurchaseAll($where=array(),$fields="*",$order_by='purchase.id desc')
	{
		return $this->select($fields)
		->join('user','user.id = purchase.user_id','left')
		->join('user as temp','temp.id = purchase.parent_id','left')
		->where($where)->order_by($order_by)->find_all();
	}
	
	//统计数据
	public function getChartList($where=array(),$fields="*",$group_by='id',$field="user_level")
	{
		$sql = "SELECT
					$fields
				FROM
					(SELECT 1 $field
						UNION ALL
					SELECT 2
						UNION ALL
					SELECT 3
						UNION ALL
					SELECT 4
					) a
				LEFT JOIN
					(SELECT
						mu.$field, SUM(total_price) total_price
					FROM
						moyoo_purchase mu
					WHERE 
						$where
					GROUP BY 
						$group_by
					) b ON a.$field = b.$field ORDER BY a.$field asc;";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	
	//计算行数
	public function getCount($where=array())
	{
		return $this->where($where)->count();
	}
	
	//获取单个
	public function checkPurchase($where=array(),$fields="*")
	{
		return $this->select($fields)->where($where)->find();
	}	
	
	//编辑
	public function editPurchase($where,$data)
	{
		return $this->where($where)->edit($data);
	}
	
	/*
	 *
	 * 以下为手机端调用
	 *
	 * */
	//获取结果集
	public function getWapPurchaseList($where=array(), $limit='20', $offset='0', $order_by='id desc',$fields="*")
	{
		return $this->select($fields)->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
	}
	//获取所有结果集
	public function getWapPurchaseAll($where=array(),$fields="*",$order_by='id desc')
	{
		return $this->select($fields)->where($where)->order_by($order_by)->find_all();
	}

	//获取单个
	public function checkWapPurchase($where=array(),$fields="*")
	{
		return $this->select($fields)->where($where)->find();
	}
	//添加
	public function addWapPurchase($data)
	{
		if ($this->add($data))
		{
			return $this->db->insert_id();
		}
	}
	//编辑
	public function editWapPurchase($where,$data)
	{
		return $this->where($where)->edit($data);
	}
//	//求和
//	public function sumWapPurchase($where,$fields='total_price'){
//		return $this->select_sum($fields)->where($where);
//	}
}
?>
