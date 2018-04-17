<?php
/**
 * User: Allen
 * Date: 16-12-21
 * 商品分类模型
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

class Goods_model extends HS_Model {

    //获取结果集
    public function getGoodsList($where=array(), $limit='20', $offset='0', $order_by='id desc',$fields="*")
    {

    	return $this->select($fields)
    	->join('classify','goods.classify_id = classify.id','left')
    	->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }
    
    //获取所有结果集
    public function getGoodsAll($where=array(),$fields)
    {
    	return $this->select($fields)
    	->join('classify','goods.classify_id = classify.id','left')
    	->where($where)->find_all();
    }
     
    //计算行数
    public function getCount($where = array())
    {
    	return $this->join('classify','goods.classify_id = classify.id','left')->where($where)->count();
    }  
     
    //统计数据
    public function getChartList($where,$order_by,$offset,$limit,$start_time,$end_time)
    {
    	$sql = "SELECT 
					fy.name AS classify_name,ds.id,ds.name,IFNULL(b.pickup_sum,0) pickup_sum,IFNULL(a.total_sum,0) total_sum,IFNULL(b.pickup_time_sum,0) pickup_time_sum,IFNULL(a.total_time_sum,0) total_time_sum 
				FROM
					moyoo_goods ds
				LEFT JOIN
					(SELECT 
						de.goods_id,SUM(de.now_price*de.cartons*de.num) total_sum,SUM(IF(pu.create_time >= '$start_time' AND pu.create_time < '$end_time',de.now_price*de.cartons*de.num,0))total_time_sum
					FROM
						moyoo_purchase pu 
					LEFT JOIN 
						moyoo_purchase_details de 
					ON 
						de.appoint_id = pu.id 
					WHERE 
						pu.order_status = 3 AND pu.type = 2
					GROUP BY
						de.goods_id
					) a
				ON
					a.goods_id = ds.id
				LEFT JOIN
					(SELECT 
						dl.goods_id,SUM(dl.now_price*dl.cartons*dl.num) pickup_sum,SUM(IF(up.create_time >= '$start_time' AND up.create_time < '$end_time',dl.now_price*dl.cartons*dl.num,0))pickup_time_sum
					FROM
						moyoo_pickup up
					LEFT JOIN 
						moyoo_pickup_details dl
					ON 
						dl.appoint_id = up.id 
					WHERE 
						up.order_status = 4
					GROUP BY
						dl.goods_id
					) b
				ON
					b.goods_id = ds.id
				LEFT JOIN
					moyoo_classify fy
				ON
					fy.id = ds.classify_id
				WHERE
					$where
				ORDER BY 
    				$order_by
				LIMIT 
    				$offset,$limit";
    	$query = $this->db->query($sql);
    	$result = $query->result_array();
    	return $result;
    }
    
    //获取单个
    public function checkGoods($where=array(),$fields="*")
    {
    	return $this->select($fields)->where($where)->find();
    }
    
    //删除
    public function deleteGoods($where)
    {
    	return $this->where($where)->delete();
    }
    
    //编辑
    public function editGoods($where,$data)
    {
    	return $this->where($where)->edit($data);
    }
    
    //添加
    public function addGoods($data)
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
    	return $this->db->update('goods');
    }
    /*
     *
     *
     * 以下微信端调用
     *
     *
     *
     * */
    //获取结果集
    public function getWapGoodsList($where=array(), $limit='20', $offset='0', $order_by='id desc',$fields="*",$user_join=false)
    {
        if($user_join) {
            return $this->select($fields)
                ->join('stock','stock.goods_id = goods.id ','left')
                ->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
        }else {
            return $this->select($fields)->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
        }


    }













}
?>
