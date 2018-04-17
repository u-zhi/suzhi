<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('logged_user_id')) {
	function logged_user_id() {
		$CI =& get_instance();
		$user_id = $CI->session->userdata('user_id');
		return $user_id ? $user_id : FALSE;
	}
}

if ( ! function_exists('logged_username')) {
	function logged_username() {
		$CI =& get_instance();
		return $CI->session->userdata('username');
	}
}

if ( ! function_exists('logged_company')) {
	function logged_company() {
		$CI =& get_instance();
		return $CI->session->userdata('company');
	}
}

if ( ! function_exists('redirect_return')) {
	function redirect_return($redirect_url, $current_url = '') {
		$CI =& get_instance();
		if ($current_url == '') {
			$current_url = current_url();
		}
		$CI->session->set_userdata('redirect_url', $current_url);
		redirect($redirect_url);
	}
}

if ( ! function_exists('auto_redirect')) {
	function auto_redirect($default_redirect_url = '/', $only_return = FALSE) {
		$CI =& get_instance();
		$redirect_url = $CI->session->userdata('redirect_url');
		if ($redirect_url) {
			$CI->session->unset_userdata('redirect_url');
		} else {
			$redirect_url = $default_redirect_url;
		}
		if ($only_return) {
			return $redirect_url;
		} else {
			redirect($redirect_url);
		}
	}
}

if ( ! function_exists('new_id')) {
	function new_id() {
		return uniqid().sprintf("%03s", mt_rand(0, 100));
	}
}

if ( ! function_exists('cut')) {
	function cut($str, $len, $more = '...') {
		if (mb_strlen($str, 'UTF-8') > $len) {
			return mb_substr($str, 0, $len, 'UTF-8') . $more;
		} else {
			return $str;
		}
	}
}


if ( ! function_exists('full_url')) {
	function full_url($url, $domain) {
		if (stripos($url, 'http://') === FALSE) {
			$url = 'http://'.$domain.BASE_DOMAIN.'/'.ltrim($url, '/');
		}
		return $url;
	}
}


if ( ! function_exists('image_url')) {
	function image_url($image, $width = 0, $height = 0) {
		if ($image) {
			if ($width > 0) {
				$file_ext = strrchr(basename($image), '.');
				$size_marker = '_'.$width.($height ? ('_'.$height) : '');
				$image = substr($image, 0, -strlen($file_ext)).$size_marker.$file_ext;
			}
			if (stripos($image, 'http://') === FALSE) {
				$image = full_url($image, 'www');
			}
		}
		return $image;
	}
}

//根据相对路径读取图片服务器路径
if ( ! function_exists('image_full_url')) {
    function image_full_url($image, $width = 0, $height =0) {
        if ($width > 0) {
            $file_ext = strrchr(basename($image), '.');
            $size_marker = '_'.$width.($height ? ('_'.$height) : '');
            $image = substr($image, 0, -strlen($file_ext)).$size_marker.$file_ext;
        }
        if (stripos($image, 'http://') === FALSE) {
            $image = full_url('data/'.$image, 'image');
        }
        return $image;
    }
}

if ( ! function_exists('isMicroMessenger') ) {
	function isMicroMessenger() {
		if(stripos($_SERVER['HTTP_USER_AGENT'], "MicroMessenger")){
			return TRUE;
		}
		return FALSE;
	}
}

if ( ! function_exists('getJsonOutput')) {
    /**
     * 通过判断是否为jsonp请求，返回可用来输出的格式字符串
     * @param array $array
     * @return string 返回的可直接输出的字符串
     */
    function getJsonOutput($array = array()) {
        $CI =& get_instance();
        $callback = $CI->input->get('callback');

        if($callback){
            header('Content-type: application/javascript');
            return $callback.'('.json_encode($array).');';
        }else{
            header('Content-type: application/json');
            return json_encode($array);
        }
    }
}

if ( ! function_exists('w_url')) {
	function w_url($url) {
		if (stripos($url, 'http://') === FALSE) {
			$url = 'http://w.bama555.com/'.ltrim($url, '/');
		}
		return $url;
	}
}


/**
 * @param $encryptedData
 * @param $key
 * @param $iv
 * @return string
 * 前端加密，PHP端揭秘
 */
if ( ! function_exists('decrypt_aes')) {
    function decrypt_aes($encryptedData, $key='weibaxinxiniubia', $iv='niubiaweibaxinxi') {
        $encryptedData = base64_decode($encryptedData);
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encryptedData, MCRYPT_MODE_CBC, $iv);
        return trim($decrypted);
    }
}

/**
 *
 * @author Qianc
 * @date 2014-7-15
 * @description 打印变量
 */
if ( ! function_exists('dump')) {
    function dump($var, $output = null) {
        if($output == null){
            echo "<pre>";
            print_r($var);
            echo "</pre>";
        }elseif($output == 'firephp'){
            FB::info($var);
        }
    }
}

/**
 * 根据site_id和member_id生成唯一字符串ID
 */
if ( ! function_exists('create_public_id') ) {
    function create_public_id() {

    }
}
if ( ! function_exists('ajax_return') ) {
    function ajax_return($data, $type = 'json') {
        if (strtoupper($type) == 'JSON') {
            $CI =& get_instance();
            echo $CI->output->set_content_type('application/json')->set_output(json_encode($data))->get_output();
        } else {
            // TODO 增加其它格式
        }
        exit;
    }
}

/**
 * 生成salt
 * @return string
 */
if (! function_exists('generateSalt')) {
    function generateSalt() {
        $slat = '';
        $word = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
                      'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'
                      , 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
                      'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
                      'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y');
        $count = count($word) - 1;
        for ($i = 0; $i < 5; $i++) {
            $slat .= $word[rand(0, $count)];
        }
        return $slat;
    }
}

/**
 * 向手机APP推送消息
 */
if(!function_exists('push_msg'))
{
	function push_msg($msg){
		$CI = & get_instance ();
		$CI->load->library('curl');
		$result = $CI->curl->simple_get("http://queue.bama555.com/job/add/weiba_im_sendmsg?data=".$msg);
		return $result;
	}
}

/**
 * 上传图片
 */
if(!function_exists('images_upload'))
{
	function images_upload(){
		
	}
}

/**
 * 测试调试用
 */
if(!function_exists('rr'))
{
    function rr($data){
        print_r($data);exit;

    }
}


/**
 * 创建目录
 * @param $path
 * @return unknown_type
 */
if(!function_exists('mkpath')) {
    function mkpath($path)
    {
        !is_dir($path) && mkdir($path, 0777, TRUE);
    }
}


/**
 * 提示信息框,适应手机端页面
 * @param str $msg 提示信息
 * @param str $url 跳转链接
 * @param int $outtime 提示页面停留时间
 *
 */
if( ! function_exists('error_msg'))
{
    function error_msg($msg = FALSE , $url = '-1' , $skip_time = '1000')
    {
        if($msg !== FALSE)
        {
            if($url == '-1' || $url == '')
            {
                $url_str = "history.back(-1)";
            }
            else
            {
                $url_str = "window.location.href='$url'";
            }
            //url等于stop时不允许跳转
            if($url!='stop')
            {
                $skip_url = '<script language="javascript">setTimeout("'.$url_str.'",'.$skip_time.');</script>';
            }
            else
            {
                $skip_url = '';
            }
            echo <<<Eof
                <html>
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=uft-8"/>
						<title>提示信息</title>
						<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
					</head>
					<body>
						<script language="javascript" src="/static/js/jquery-1.11.0.js"></script>
						<script language="javascript" src="/static/js/layer/layer.js"></script>
						<script language="javascript">layer.msg("$msg");</script>
                        $skip_url
					</body>
                </html>
Eof;
            exit;
        }
        exit;
    }
}
//curl封装
if(!function_exists("curl_ci"))
{
    function curl_ci($method,$url,$headers,$bodys) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$url, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);
        return json_decode(curl_exec($curl),1);
    }
}

function get_extension($file)
{     
    return substr($file, strrpos($file, '.')+1);
}



