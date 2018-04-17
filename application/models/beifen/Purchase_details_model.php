<?php
/**
 * User: Allen
 * Date: 16-11-12
 * 商品模型
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

class Purchase_details_model extends HS_Model {
	
	//获取所有结果集
	public function getPurchaseAll($where=array(),$fields="*",$order_by='id desc')
	{
		return $this->select($fields)->where($where)->order_by($order_by)->find_all();
	}
	
	
    /*
     *
     * 以下为微信端调用
     *
     *
     *
     * */
    //获取结果集
    public function getWapPurchase_detailsList($where=array(), $limit='20', $offset='0', $order_by='id desc',$fields="*",$user_join=false)
    {
        if($user_join) {
            return $this->select($fields)
                ->join('goods','goods.id = purchase_details.goods_id','left')
                ->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
        }else {
            return $this->select($fields)->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
        }

    }

    //获取所有结果集
    public function getWapPurchase_detailsAll($where=array(),$fields="*",$order_by='id desc')
    {
        return $this->select($fields)->where($where)->order_by($order_by)->find_all();
    }

    //计算行数
    public function getWapCount($where = array())
    {
        return $this->where($where)->count();
    }

    //获取单个
    public function checkWapPurchase_details($where,$fields="*",$user_join=false)
    {
        if($user_join) {
            return $this->select($fields)
                ->join('goods','goods.id = Purchase_details.goods_id','left')
                ->where($where)->find();
        }else {
            return $this->select($fields)->where($where)->find();
        }

    }

    //删除
    public function deleteWapPurchase_details($where)
    {
        return $this->where($where)->delete();
    }

    //删除多行
    public function deleteWapAll($where)
    {
        return $this->where_in('id',$where)->delete();
    }

    //添加
    public function addWapPurchase_details($data)
    {
        if ($this->add($data))
        {
            return $this->db->insert_id();
        }
    }

    //编辑
    public function editWapPurchase_details($where,$data)
    {
        return $this->where($where)->edit($data);
    }

    //编辑多行
    public function editWapAll($where,$data)
    {
        return $this->where_in('id',$where)->edit($data);
    }

    //对数据库字段进行自增修改
    public function addWapId($where,$col,$value){
        $this->db->where($where);
        if($value >= 0) {
            $this->db->set($col,$col.' + '.$value,FALSE);
        }else {
            $this->db->set($col,$col.' - '.abs($value),FALSE);
        }
        $this->db->set('update_time',time(),FALSE);
        return $this->db->update('Purchase_details');
    }
}
?>
