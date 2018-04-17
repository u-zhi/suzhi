<?php defined('BASEPATH') OR exit('No direct script access allowed');

//派单对象
function send_order($send_object,$template_id,$send_url,$data) {
	$this->load->library('template_msg');
	$success_num = 0;
	foreach($send_object as $key => $value) {
		$open_id = $value['open_id'];
		$res = $this->template_msg->do_send($open_id,$template_id,$send_url,$data);
		if($res) {
			$success_num++;
		}
		return $success_num;
	}
}
