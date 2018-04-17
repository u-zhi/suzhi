<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 public function index()
	{
//		if($_SERVER['QUERY_STRING']){
//			redirect('http://'.$_SERVER['HTTP_HOST'].'/wap/home?'.$_SERVER['QUERY_STRING']);
//		}else{
			redirect('http://'.$_SERVER['HTTP_HOST'].'/user_admin/welcome');
//		}

		//this->load->view('welcome_message');
	}
	
	public function test(){
// 		$this->load->library('template_msg');
// 		$data = array(
// 				'first' => array('value'=>'ceshi','color'=>'#173177'),
// 				'keyword1' => array('value'=>'ceshi','color'=>'#173177'),
// 				'keyword2' => array('value'=>'ceshi','color'=>'#173177'),
// 				'keyword3' => array('value'=>'ceshi','color'=>'#173177'),
// 				'keyword4' => array('value'=>'ceshi','color'=>'#173177'),
// 				'keyword5' => array('value'=>'ceshi','color'=>'#173177'),
// 				'remark' => array('value'=>'ceshi','color'=>'#173177'),
// 			);
// 		$res = $this->template_msg->do_send('owRuZuE4yBOtUiR0MkHlC3_byZ0g','send','http://www.baodu.com',$      data);
// 		if($res){
// 			echo '111';
// 		}
// 		else{
// 			echo '222';
// 		}
 		$this->load->library('message');
 		$this->message->send_message('15557198112','1234','1');
		
// 		$this->load->library('template_msg');
// 		$data = array(
// 				'first' => array('value'=>'ceshi','color'=>'#173177'),
// 				'keyword1' => array('value'=>'ceshi','color'=>'#173177'),
// 				'keyword2' => array('value'=>'ceshi','color'=>'#173177'),
// 				'keyword3' => array('value'=>'ceshi','color'=>'#173177'),
// 				'keyword4' => array('value'=>'ceshi','color'=>'#173177'),
// 				'keyword5' => array('value'=>'ceshi','color'=>'#173177'),
// 				'remark' => array('value'=>'ceshi','color'=>'#173177'),
// 			);
// 		//send 派单
// 		//receive 预约成功
// 		$res = $this->template_msg->do_send('owRuZuE4yBOtUiR0MkHlC3_byZ0g','send','http://www.baidu.com',$data);
// 		if($res){
// 			echo '111';
// 		}
// 		else{
// 			echo '222';
// 		}
	}
}
