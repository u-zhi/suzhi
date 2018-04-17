<?php
/**
 *
 * 购物车模型
 */
class Cart_model extends HS_Model {
    /*
     *
     * 以下是微信端调用model
     *
     *
     *
     * */
    //获取结果集带关联
    public function getWapCartList($where=array(), $limit='20', $offset='0', $order_by='id desc',$fields="*")
    {
        return $this->select($fields)
            ->join('goods','goods.id = Cart.gid and goods.company_id = Cart.gcompany_id','left')
            ->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }

    //获取所有结果集
    public function getWapCartAll($where=array(),$fields="*",$order_by='id desc',$join='',$join_condition='')
    {
        if($join && $join_condition){
            if(is_string($join)){
                return $this->select($fields)->join($join,$join_condition)->where($where)->order_by($order_by)->find_all();
            }
        }
        return $this->select($fields)->where($where)->order_by($order_by)->find_all();
    }

    //计算行数
    public function getWapCount($where = array())
    {
        return $this->where($where)->count();
    }

    //获取单个
    public function checkWapCart($where,$fields="*")
    {
        return $this->select($fields)->where($where)->find();
    }

    //删除
    public function deleteWapCart($where)
    {
        return $this->where($where)->delete();
    }

    //删除多行
    public function deleteWapAll($where)
    {
        return $this->where_in('id',$where)->delete();
    }

    //添加
    public function addWapCart($data)
    {
        if ($this->add($data))
        {
            return $this->db->insert_id();
        }
    }

    //编辑
    public function editWapCart($where,$data)
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
        return $this->db->update('cart');
    }
    //求某个字段的所有总和
    public function countFiled($where,$field){
        return $this->where($where)->sum($field);
    }
}
?>
