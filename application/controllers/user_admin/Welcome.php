<?php

class Welcome extends PC_Controller {
	public function __construct(){
		parent::__construct();
	}
		
	/**
	 * @brief	首页中心
	 * @param 	Null
	 * @author	Allen
	 * @since	2016/12/20 Ver 1.0
	 */
	public function index() {
		$this->display('welcome');
	}
}