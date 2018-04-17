<?php
/**
 * 
 * @author lunzi
 * @uses 发送模板消息
 * @date 2015-07-21
 *
 */
class Template_msg
{
	protected $appid;
	protected $secrect;
	protected $accessToken;
	protected $send_template_id = 'RNBjaLvyI6NAoFtMIhoZPLebG3yaLbApAlmJ4FMdPcs';
	protected $receive_template_id = 'zzqXGBgA4OnMP564-enLA4CFxPkURP_kiZu2ViI3Hc8';
	
	function  __construct()
	{
		$CI =& get_instance();
		$CI->load->config('wx');
		$wx = $CI->config->item('wx');
		$this->appid = $wx['appid'];
		$this->secrect = $wx['appsecret'];
		$this->accessToken = $this->getToken($this->appid, $this->secrect);
	}
	
	/**
	 * 发送post请求
	 * @param string $url
	 * @param string $param
	 * @return bool|mixed
	 */
	function request_post($url = '', $param = '')
	{
		if (empty($url) || empty($param)) {
			return false;
		}
		$postUrl = $url;
		$curlPost = $param;
		$ch = curl_init(); //初始化curl
		curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页
		curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		$data = curl_exec($ch); //运行curl
		curl_close($ch);
		return $data;
	}
	
	
	/**
	 * 发送get请求
	 * @param string $url
	 * @return bool|mixed
	 */
	function request_get($url = '')
	{
		if (empty($url)) {
			return false;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
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
	
	
	/**
	 * 发送自定义的模板消息
	 * @param $touser
	 * @param $template_id
	 * @param $url
	 * @param $data
	 * @param string $topcolor
	 * @return bool
	 */
	public function do_send($touser, $template_id, $url, $data, $topcolor = '#7B68EE')
	{
		if($template_id == 'send'){
			$template_id = $this->send_template_id;
		}
		else{
			$template_id = $this->receive_template_id;
		}
	
		/*
		 * data=>array(
		 		'first'=>array('value'=>urlencode("您好,您已购买成功"),'color'=>"#743A3A"),
		 		'name'=>array('value'=>urlencode("商品信息:微时代电影票"),'color'=>'#EEEEEE'),
		 		'remark'=>array('value'=>urlencode('永久有效!密码为:1231313'),'color'=>'#FFFFFF'),
		 )
		*/
		$template = array(
				'touser' => $touser,
				'template_id' => $template_id,
				'url' => $url,
				'topcolor' => $topcolor,
				'data' => $data
		);
		$json_template = json_encode($template);
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $this->accessToken;
		$dataRes = json_decode($this->request_post($url, urldecode($json_template)),true);
		if ($dataRes['errcode'] == 0) {
			return true;
		} else {
			return false;
		}
	}
	
	//派单对象
	public function send_order($send_object,$template_id,$send_url,$data) {
		$success_num = 0;
		foreach($send_object as $key => $value) {
			$open_id = $value['open_id'];
			$data['first'] = array('value'=>'您好'.$value['name'].'小鸽，有一笔预约可以抢，点击详情抢单','color'=>'#173177');
			$res = $this->do_send($open_id,$template_id,$send_url,$data);
			if($res) {
				$success_num++;
			}
			return $success_num;
		}
	}	
}