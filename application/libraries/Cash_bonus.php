<?php
/*
 * 商家发送现金红包
 * 1、下载证书、并上传到根目录下的cert文件夹下
 * 2、发放现金红包
 * create by chenqi 2015-12-14
 * */
class Cash_bonus{
	
	protected $appid;//appid
	protected $secrect;//appscerct
	protected $accessToken;//
	protected $mch_id;//商户id
	protected $key;//商户key
	protected $wxname;//
	protected $cash_url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";//支付api
	protected $post_data = array();
	
	function  __construct()
	{
		//获取配置
// 		$CI =& get_instance();
// 		$CI->load->config('wx');
// 		$wx = $CI->config->item('wx');
// 		$this->appid = $wx['appid'];
// 		$this->secrect = $wx['appsecret'];
// 		$this->key = $wx['key'];
// 		$this->mch_id = $wx['mch_id'];
// 		$this->wxname = $wx['wx_name'];
		
		//$this->accessToken = $this->getToken($this->appid, $this->secrect);
	}
	function set($wx){
		$this->appid = $wx['appid'];
		$this->secrect = $wx['appsecret'];
		$this->key = $wx['key'];
		$this->mch_id = $wx['mch_id'];
		$this->wxname = $wx['wx_name'];
	}
	function get(){
		
	}

	/**
	 * 发送post请求
	 * @param string $url
	 * @param string $param
	 * @return bool|mixed
	 */
	function postXmlCurl($xml,$url,$second=30,$aHeader=array())
	{
		$ch = curl_init();
		//超时时间
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		//这里设置代理，如果有的话
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
		
		//以下两种方式需选择一种		
		//第一种方法，cert 与 key 分别属于两个.pem文件
		//默认格式为PEM，可以注释
		curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
		curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/cert/apiclient_cert.pem');
		//默认格式为PEM，可以注释
		curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
		curl_setopt($ch,CURLOPT_SSLKEY,getcwd().'/cert/apiclient_key.pem');		
		//第二种方式，两个文件合成一个.pem文件
		//curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/cert/all.pem');
		
		if( count($aHeader) >= 1 ){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
		}
		
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
		$data = curl_exec($ch);
		if($data){
			curl_close($ch);
			return $data;
		}
		else {
			$error = curl_errno($ch);
			//echo "call faild, errorCode:$error\n";
			curl_close($ch);
			return false;
		}
	}
	
	/**
	 * @param $appid
	 * @param $appsecret
	 * @return mixed
	 * 获取token
	 */
	protected function getToken($appid, $appsecret)
	{
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $appsecret;
		$token = $this->request_get($url);
		$token = json_decode(stripslashes($token));
		$arr = json_decode(json_encode($token), true);
		$access_token = $arr['access_token'];
		return $access_token;
	}
	
	public function set_data($data){
		
		//请求数据
		$this->post_data['nonce_str'] = $this->create_noncestr();//随机字符串
		$this->post_data['mch_billno'] = $this->mch_id.date('Ymd',time()).time();//商户订单号 mch_id+yyyymmdd+10位一天内不能重复的数字。
		$this->post_data['mch_id'] = $this->mch_id;//商户号
		$this->post_data['wxappid'] = $this->appid;//公众账号appid
		$this->post_data['send_name'] = $this->wxname;//商户名称		
		$this->post_data['total_amount'] =$data['price']*100;//付款金额
		$this->post_data['total_num'] = 1;//红包发放总人数
		$this->post_data['wishing'] = $data['wishing'];//红包祝福语
		$this->post_data['client_ip'] = $this->get_client_ip();//获取本机ip
		$this->post_data['act_name'] = empty($data['act_name']) ? "活动名称":$data['act_name'];//活动名称
		$this->post_data['remark'] = empty($data['remark']) ? "备注" : $data['remark'];//备注
		
		
	} 
	/**
	 * 给一个用户发放现金红包
	 * @param $openid  用户openid
	 * @param $price  金额
	 */
	public function grant($openid,$data){
		
		$this->post_data['re_openid'] = $openid;//用户openid		
		$this->set_data($data);//设置参数		
		$this->post_data['sign'] = $this->getSign($this->post_data);//签名
		
		$xml = $this->arrayToXml($this->post_data);
		
		$cash_res = $this->postXmlCurl($xml,$this->cash_url);
				
		$res = $this->xmlToArray($cash_res);
		if($res['return_code'] == "FAIL"){
			//echo $res['return_msg'];
		}
		
		return $res;
		
	}
	
	
	//数组转化为xml
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
	
	//xml转化为数组
	function xmlToArray($xml)
	{
		//将XML转为array
		$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		return $array_data;
	}
	
	//生成随机数
	protected function create_noncestr( $length = 32 ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
		}
		return $str;
	}
	
	//	作用：生成签名
	public function getSign($Obj)
	{
		foreach ($Obj as $k => $v)
		{
			$Parameters[$k] = $v;
		}
		//签名步骤一：按字典序排序参数
		ksort($Parameters);
		$String = $this->formatBizQueryParaMap($Parameters, false);
		//echo '【string1】'.$String.'</br>';
		//签名步骤二：在string后加入KEY
		$String = $String."&key=".$this->key;
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
	 * 	格式化参数，签名过程需要使用
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
		$reqPar="";
		if (strlen($buff) > 0)
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	
	function get_client_ip()
	{
		if ($_SERVER['REMOTE_ADDR']) {
			$cip = $_SERVER['REMOTE_ADDR'];
		} elseif (getenv("REMOTE_ADDR")) {
			$cip = getenv("REMOTE_ADDR");
		} elseif (getenv("HTTP_CLIENT_IP")) {
			$cip = getenv("HTTP_CLIENT_IP");
		} else {
			$cip = "unknown";
		}
		return $cip;
	}
	

	function curl_post_ssl($url, $vars, $second=30,$aHeader=array())
	{
		$ch = curl_init();
		//超时时间
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		//这里设置代理，如果有的话
		//curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
		//curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
	
		//以下两种方式需选择一种
	
		//第一种方法，cert 与 key 分别属于两个.pem文件
		//默认格式为PEM，可以注释
		//curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
		//curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/cert.pem');
		//默认格式为PEM，可以注释
		//curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
		//curl_setopt($ch,CURLOPT_SSLKEY,getcwd().'/private.pem');
	
		//第二种方式，两个文件合成一个.pem文件
		curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');
	
		if( count($aHeader) >= 1 ){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
		}
	
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
		$data = curl_exec($ch);
		if($data){
			curl_close($ch);
			return $data;
		}
		else {
			$error = curl_errno($ch);
			//echo "call faild, errorCode:$error\n";
			curl_close($ch);
			return false;
		}
	}
	
	function __get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}	
	
}