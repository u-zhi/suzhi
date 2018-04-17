<?php
/**
 * Created by PhpStorm.
 * User: liangbo
 * Date: 2017/11/8
 * Time: 下午10:57
 */

class CI_Service
{
    public function __construct()
    {
       $this->load->database();
    }

    function __get($key)
    {
        $CI = & get_instance();
        return $CI->$key;
    }

}