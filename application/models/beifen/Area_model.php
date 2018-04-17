<?php

/**
 * Created by Allen
 * Date: 16-03-04
 * 地区管理模型
 */
class Area_model extends HS_Model {

    //获取结果集
    public function getAreaList($where = array(), $limit = '20', $offset = '0', $order_by = 'areano desc', $fields = "*") {
        return $this->select($fields)->where($where)->limit($limit, $offset)->order_by($order_by)->find_all();
    }

    //获取所有结果集
    public function getAreaAll($where = array(), $fields = "*", $order_by = 'areano desc') {
        return $this->select($fields)->where($where)->order_by($order_by)->find_all();
    }

    //计算行数
    public function getCount($where = array()) {
        return $this->where($where)->count();
    }

    //获取单个
    public function checkArea($where, $fields = "*") {
        return $this->select($fields)->where($where)->find();
    }

    //删除
    public function deleteArea($where) {
        return $this->where($where)->delete();
    }

    //删除多行
    public function deleteAll($where) {
        return $this->where_in('areano', $where)->delete();
    }

    //添加
    public function addArea($data) {
        if ($this->add($data)) {
            return $this->db->insert_id();
        }
    }

    //编辑
    public function editArea($where, $data) {
        return $this->where($where)->edit($data);
    }

    //编辑多行
    public function editAll($where, $data) {
        return $this->where_in('id', $where)->edit($data);
    }

}

?>
