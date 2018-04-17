<?php
/**
 * User: Allen
 * Date: 16-12-26
 * 基本设置模型
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

class Basic_config_model extends HS_Model {  
    
	//获取单个
    public function checkBasic($where=array(),$fields="*")
    {
    	return $this->select($fields)->where($where)->find();
    }

    //编辑
    public function editBasic($where,$data)
    {
    	return $this->where($where)->edit($data);
    }
    
    //添加
    public function addBasic($data)
    {
    	if ($this->add($data))
    	{
    		return $this->db->insert_id();
    	}
    }
}
?>
