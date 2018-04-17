<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance =& $this;
		date_default_timezone_set('Asia/Shanghai');

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
		log_message('info', 'Controller Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}
	/**
	 * Enter 输出结果，同时会判断返回的code
	 * tags
	 * @param unknowtype
	 * @return string
	 * @author zhyu
	 * @date 2016-6-20下午5:50:37
	 * @version v1.0.0
	 */
	public function returns($success=0, $error_msg='',$is_object=false,$result = array())
	{
		if($success ==0 ){
			$result['status']['succeed']='1';
		}else{
			$result['status']['succeed']	='0';
			$result['status']['error_code']	=$success;
			$result['status']['error_desc'] =$error_msg?$error_msg:$this->getErrorMsg($success);
		}
		//error_log(date('Y-m-d H:i:s')."数据开始发送：\n",3,'log.txt');
		header("Access-Control-Allow-Origin:*");
		header("Content-type: text/html; charset=utf-8");
		header('Content-type : application/json');
		if ($is_object) {
			///echo 1211111111111111;
			//error_log('json:'.json_encode($result, JSON_FORCE_OBJECT)."\n",3,'log.txt');
			echo json_encode($result, JSON_FORCE_OBJECT);
			//error_log(date('Y-m-d H:i:s')."数据发送结束：\n",3,'log.txt');
			exit;
		} else {
			echo json_encode($result);
			//error_log(date('Y-m-d H:i:s')."数据发送结束：\n",3,'log.txt');
			exit;
		}
	}
	/**
	 * Enter description here ...
	 * tags
	 * @param unknowtype
	 * @return return_type
	 * @author zhyu
	 * @date 2016-6-20下午6:24:07
	 * @version v1.0.0
	 */
	private function getErrorMsg($success=0){
		@include(APPPATH.'config/http_status.php');
		return isset($http_status[$success])?$http_status[$success]:'未知错误';

	}

}
