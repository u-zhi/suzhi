<?php
/**
 * User: Allen
 * Date: 16-12-19
 *
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

class Wx_visitor {

    // 访问者信息
    private $_info = NULL;
    // 是否登录
    private $_signed = FALSE;
    //秘钥
    private $_session_key = 'ALLEN_WANG';

    public function __construct()
    {
    	$info = $this->session->userdata($this->_session_key);
        if($info)
        {
            if($info['id'])
            {
                $this->_info = $info;
                $this->_signed = true;
            }
            else
            {
                $this->signout();
            }
        }
    }
    
    public function is_signed()
    {
    	return $this->_signed;
    }

    public function get_info($key = '')
    {
        if ($key) {
            return isset($this->_info[$key]) ? $this->_info[$key] : NULL;
        } else {
            return $this->_info;
        }
    }

    public function assign($info)
    {
        $this->_info = $info;
        $this->session->set_userdata($this->_session_key, $info);
    }

    public function signout()
    {
        $this->session->set_userdata($this->_session_key, NULL);
    }

    function __get($key)
    {
        $CI =& get_instance();
        return $CI->$key;
    }
} 