<?php
/**
 * Created by PhpStorm.
 * User: liangbo
 * 账户服务
 * Date: 2017/11/8
 * Time: 下午11:00
 */

class AccountService extends CI_Service
{

    /**
     * 获取速职币
     * */
    public function get_suzhi_coin($company_id)
    {
        $res=$list=$this->db
            ->select("sum(number) as total,sum(has_number) as use_total")
            ->where(
                array("company_id"=>$company_id,
                "type"=>4,
                "status"=>1,
                "end_time > "=>date("Y-m-d H:i:s")))
            ->get('company_server')
            ->row();
        if(!$res){return 0;}
        return $res->total-$res->use_total;
    }
    /**
     * 速职币修改
     * */

//    public function g

}