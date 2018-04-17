<?php
/**
 * 
 * @author chenqi
 * @uses 生成微信支付js
 * @date 2015-10-24
 *
 */
class Wx_pay
{
	protected $appid;
	protected $secrect;
	protected $mch_id;//商户id
	protected $mch_key;//商户支付key
	protected $earnest = 0.01;//定金
	
	function  __construct()
	{
		$CI =& get_instance();
                $CI->load->model('wechat_config_model');
                $wx=$CI->wechat_config_model->checkWechat();
                
//		$CI->load->config('wx');
//		$wx = $CI->config->item('wx');
		$this->appid = $wx['appid'];
		$this->secrect = $wx['appsecret'];
		$this->mch_id = $wx['partnerkey'];
		$this->mch_key = $wx['paysignkey'];
	}
	
	//获取支付代码
    function get_code($order)
    {
//        $this->appid = $wx['appid'];
//        $this->secrect = $wx['appsecret'];
//        $this->mch_id = $wx['paysignkey'];
//        $this->mch_key = $wx['partnerkey'];
    	$jsApiObj["appId"] = $this->appid;
		$timeStamp = time();
		$jsApiObj["timeStamp"] = "$timeStamp";
		$jsApiObj["nonceStr"] = $this->createNoncestr();
		
		$jsApiObj["package"] = "prepay_id=".$this->pre_payment($order);
		$jsApiObj["signType"] = "MD5";
		$jsApiObj["paySign"] = $this->getSign($jsApiObj);
		//echo "<>";
		//print_r($jsApiObj);exit;
		$jsapi = json_encode($jsApiObj);
		//wxjsbridge
		$js = '<script language="javascript">
			function callpay(){
				WeixinJSBridge.invoke(
					"getBrandWCPayRequest",
					'.$jsapi.',
					function(res){
						//alert(res.err_code+res.err_desc+res.err_msg);
						//WeixinJSBridge.log(res.err_msg);
                        if(res.err_msg == "get_brand_wcpay_request:ok"){
							location.href="/order/tips?status=1&order_id='.$order['order_no'].'"
    					}else{
							location.href="/order/tips?status=0&order_id='.$order['order_no'].'";
    					}
					}
				);
			}
			</script>';

		$button = '<a onclick="callpay()" style="display:block;width:90%;margin:10px auto;text-align:center;line-height:35px;background:#95420b;border-radius:5px;color:#fff;font-size:1.4em;">微信支付</a>'.$js;

		return $button;        
    }

	
	/**
	 * 	作用：产生随机字符串，不长于32位
	 */
	public function createNoncestr( $length = 32 )
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
		}
		return $str;
	}
	
	//统一下单，获取预支付ID值prepay_id
	public function pre_payment($order)
	{
		
		$out_trade_no = $order['order_no'].'tel'.time(); 		
		//统一下单参数
		$parameters = array(
				'appid' => $this->appid,
				'mch_id' => $this->mch_id,
				'nonce_str' => $this->createNoncestr(),
				'body' => $order['order_body'],
				'out_trade_no' => $out_trade_no,
				'total_fee' => 100*$this->earnest,
                                //'total_fee' => 100*$order['order_amount'],
				'spbill_create_ip' => $this->real_ip(),
				'notify_url' =>$order['notify_url'],
				'trade_type' => 'JSAPI',
				'openid' => $order['open_id']
		);
                error_log('$parameters:'.  var_export($parameters,1)."\n",3,'paylog.txt');
		$parameters['sign'] = $this->getSign($parameters);
		error_log('sign:'.  var_export($parameters,1)."\n",3,'paylog.txt');		
		$xml = $this->arrayToXml($parameters);
		$xml_res = $this->postXmlCurl($xml,'https://api.mch.weixin.qq.com/pay/unifiedorder','30');
		$res = $this->xmlToArray($xml_res);
                error_log('$res:'.  var_export($res,1)."\n",3,'paylog.txt');
		if($res['return_code'] == "FAIL"){
			echo $res['return_msg'];exit;
		}
		
		
		$prepay_id = $res["prepay_id"];
		if($prepay_id){
			return $prepay_id;
		}
	}
        /**
	 * 微信支付服务端回调
	 */
	public function serverCallback()
	{
            error_log('$GLOBALS:'.  var_export($GLOBALS,1)."\n",3,'paylog.txt');
		$callbackData = $this->xmlToArray($GLOBALS['HTTP_RAW_POST_DATA']);
                error_log('$callbackData:'.  var_export($callbackData,1)."\n",3,'paylog.txt');
		if($callbackData['result_code'] == 'SUCCESS')
		{
			//除去待签名参数数组中的空值和签名参数
			$para_filter = $this->paraFilter($callbackData);

			
			unset($para_sort['sign']);
			//生成签名结果
			$mysign = $this->getSign($para_sort);
                        error_log('$mysign:'.  var_export($mysign,1)."\n",3,'paylog.txt');
			//验证签名
			if($mysign == $callbackData['sign'])
			{
				
                                $orderNoa=  explode('tel', $callbackData['out_trade_no']);
                                $orderNo =$orderNoa[0];
				$CI = &get_instance();

				//订单支付
				$CI->load->model('appoint_model');
				$res = $CI->appoint_model->pay($orderNo);//支付成功修改订单状态

				$result = json_decode($res,true);
				echo $result['return_code'];//支付成功返回给微信
			}
			else
			{
				$message = '签名不匹配';
				return false;
			}
		}
		else
		{
			$message = $callbackData['return_msg'];
			return false;
		}
		return false;
	}
        /**
	 * 除去数组中的空值和签名参数
	 * @param $para 签名参数组
	 * return 去掉空值与签名参数后的新签名参数组
	 */
	private function paraFilter($para)
	{
		$para_filter = array();
		foreach($para as $key => $val)
		{
			if($key == "sign" || $key == "sign_type" || $val == "")
			{
				continue;
			}
			else
			{
				$para_filter[$key] = $para[$key];
			}
		}
		return $para_filter;
	}
	/**
	 * 	作用：array转xml
	 */
	function arrayToXml($arr)
	{
		$xml = "<xml>";
		foreach ($arr as $key=>$val)
		{
			if (is_numeric($val))
			{
				$xml.="<".$key.">".$val."</".$key.">";
	
			}
			else
				$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
		}
		$xml.="</xml>";
		return $xml;
	}
	/**
	 * 	作用：以post方式提交xml到对应的接口url
	 */
	public function postXmlCurl($xml,$url,$second=30)
	{
		//初始化curl
		$ch = curl_init();
		//设置超时
		//curl_setopt($ch, CURLOP_TIMEOUT, $second);
		//这里设置代理，如果有的话
		//curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
		//curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		//设置header
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//post提交方式
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		//运行curl
		$data = curl_exec($ch);
		curl_close($ch);
		//返回结果
		if($data)
		{
			//curl_close($ch);
			return $data;
		}
		else
		{
			$error = curl_errno($ch);
			echo "curl出错，错误码:$error"."<br>";
			echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-0errors.html'>错误原因查询</a></br>";
			curl_close($ch);
			return false;
		}
	}
	
	/**
	 * 	作用：将xml转为array
	 */
	public function xmlToArray($xml)
	{
		//将XML转为array
		$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		return $array_data;
	}
	/**
	 * 	作用：生成签名
	 */
	public function getSign($Obj)
	{
            error_log('$this->mch_key:'.  var_export($this->mch_key,1)."\n",3,'paylog.txt');
		foreach ($Obj as $k => $v)
		{
			$Parameters[$k] = $v;
		}
		//签名步骤一：按字典序排序参数
		ksort($Parameters);
		
		$String = $this->formatBizQueryParaMap($Parameters, false);
		//echo '【string1】'.$String.'</br>';
		//签名步骤二：在string后加入KEY
		$String = $String."&key=".$this->mch_key;
		//echo "【string2】".$String."</br>";
		//签名步骤三：MD5加密
		$String = md5($String);
		//echo "【string3】 ".$String."</br>";
		//签名步骤四：所有字符转为大写
		$result_ = strtoupper($String);
		//echo "【result】 ".$result_."</br>";
		return $result_;
	}

	/**
	 * 	作用：格式化参数，签名过程需要使用
	 */
	function formatBizQueryParaMap($paraMap, $urlencode)
	{
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
			if($urlencode)
			{
				$v = urlencode($v);
			}
			//$buff .= strtolower($k) . "=" . $v . "&";
			$buff .= $k . "=" . $v . "&";
			
		}
		$reqPar;
		if (strlen($buff) > 0)
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}

	/**
	 * 获得用户的真实IP地址
	 *
	 * @access  public
	 * @return  string
	 */
	function real_ip()
	{
	    if (isset($_SERVER))
	    {
	        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	        {
	            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

	            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
	            foreach ($arr AS $ip)
	            {
	                $ip = trim($ip);

	                if ($ip != 'unknown')
	                {
	                    $realip = $ip;

	                    break;
	                }
	            }
	        }
	        elseif (isset($_SERVER['HTTP_CLIENT_IP']))
	        {
	            $realip = $_SERVER['HTTP_CLIENT_IP'];
	        }
	        else
	        {
	            if (isset($_SERVER['REMOTE_ADDR']))
	            {
	                $realip = $_SERVER['REMOTE_ADDR'];
	            }
	            else
	            {
	                $realip = '0.0.0.0';
	            }
	        }
	    }
	    else
	    {
	        if (getenv('HTTP_X_FORWARDED_FOR'))
	        {
	            $realip = getenv('HTTP_X_FORWARDED_FOR');
	        }
	        elseif (getenv('HTTP_CLIENT_IP'))
	        {
	            $realip = getenv('HTTP_CLIENT_IP');
	        }
	        else
	        {
	            $realip = getenv('REMOTE_ADDR');
	        }
	    }

	    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
	    $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

	    return $realip;
	}

	function __get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}
}