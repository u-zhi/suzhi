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
class Pickup_model extends HS_Model {
	
	//获取结果集
	public function getPickupList($where=array(), $limit='20', $offset='0', $order_by='id desc',$fields="*")
	{
		return $this->select($fields)->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
	}
	//获取所有结果集
	public function getPickupAll($where=array(),$fields="*",$order_by='id desc')
	{
		return $this->select($fields)->where($where)->order_by($order_by)->find_all();
	}
	
	//获取单个
	public function checkPickup($where=array(),$fields="pickup.*")
	{
		return $this->select($fields)
		->join('user','user.id = pickup.user_id','left')
		->where($where)->find();
	}
	//添加
	public function addPickup($data)
	{
		if ($this->add($data))
		{
			return $this->db->insert_id();
		}
	}
	//编辑
	public function editPickup($where,$data)
	{
		return $this->where($where)->edit($data);
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
						mu.$field, SUM(total_price+total_dc_price) total_price
					FROM
						moyoo_pickup mu
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
	public function getCount($where = array(),$fields = '')
	{
		return $this->where($where)->count();
	}
	
	/*
	 *
	 * 以下为手机端调用
	 *
	 * */
	//获取结果集
	public function getWapPickupList($where=array(), $limit='20', $offset='0', $order_by='id desc',$fields="*")
	{
		return $this->select($fields)->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
	}
	//获取所有结果集
	public function getWapPickupAll($where=array(),$fields="*",$order_by='id desc')
	{
		return $this->select($fields)->where($where)->order_by($order_by)->find_all();
	}

	//获取单个
	public function checkWapPickup($where=array(),$fields="*")
	{
		return $this->select($fields)->where($where)->find();
	}
	//添加
	public function addWapPickup($data)
	{
		if ($this->add($data))
		{
			return $this->db->insert_id();
		}
	}
	//编辑
	public function editWapPickup($where,$data)
	{
		return $this->where($where)->edit($data);
	}
}
?>
