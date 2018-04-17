<?php
	$lang['mulu'] = array(			
		"0" => array(
			"controller"=>array('welcome'),
			"style"=>'icon-home',
			"name"=>"首页中心",
			"second"=>array(
				"welcome"=>array("link"=>"/welcome/index","name"=>"首页中心")
			) 
		),	
		"1" => array(
			"controller"=>array('admin','role','node','access'),
			"style"=>'icon-github-alt',
			"name"=>"管理员管理",
			"special"=>"3",
			"second"=>array(
				"admin"=>array("link"=>"/admin/admin_list","name"=>"管理员列表"),
				"role"=>array("link"=>"/role/role_list","name"=>"角色列表"),
				"node"=>array("link"=>"/node/node_list","name"=>"节点列表")
			)
		),
		"2" => array(
			"controller"=>array('homepage','census','city','pay'),
			"style"=>'glyphicon glyphicon-folder-close',
			"name"=>"产品管理",
			"special"=>"3",
			"second"=>array(
				"homepage"=>array("link"=>"/homepage/carousel_list","name"=>"轮播图列表"),
				"census"=>array("link"=>"/census/census_list","name"=>"统计管理"),
				"city"=>array("link"=>"/city/city_list","name"=>"城市区域"),
				"pay"=>array("link"=>"/pay/pay_list","name"=>"付费服务")

			)
		),
		"3" => array(
			"controller"=>array('jobhunter','firm','headhunter'),
			"style"=>'glyphicon glyphicon-user',
			"name"=>"用户管理",
			"special"=>"3",
			"second"=>array(
				"firm"=>array("link"=>"/firm/firm_list","name"=>"企业列表"),
				"headhunter"=>array("link"=>"/headhunter/headhunter_list","name"=>"求职顾问"),
				"jobhunter"=>array("link"=>"/jobhunter/jobhunter_list","name"=>"求职者列表")

			)
		),
		"4" => array(
			"controller"=>array('order','problem_list','enroll_list','receive_list'),
			"style"=>'glyphicon glyphicon-cloud-download',
			"name"=>"订单中心",
			"special"=>"3",
			"second"=>array(
				"orderenroll"=>array("link"=>"/order/enroll_list","name"=>"求职者订单列表"),
				"orderreceive"=>array("link"=>"/order/receive_list","name"=>"猎头订单列表"),
				"problem_list"=>array("link"=>"/order/problem_list","name"=>"招聘订单列表"),
				// "parttime"=>array("link"=>"/order/parttime_list","name"=>"求职者兼职订单列表"),
				// "wait"=>array("link"=>"/order/wait_list","name"=>"求职者待面试订单列表"),
				// "wait_work"=>array("link"=>"/order/wait_work_list","name"=>"求职者待开工订单列表"),
				// "recruit"=>array("link"=>"/order/recruit_list","name"=>"面试录取订单列表"),
				// "complete"=>array("link"=>"/order/complete_list","name"=>"已结束订单列表"),
				// "problem"=>array("link"=>"/order/problem_list","name"=>"问题单列表")
			)
		),
		"5" => array(
			"controller"=>array('withdraw','alipay_cash_audit','alipay_presentation_record','card_cash_audit'),
			"style"=>'glyphicon glyphicon-usd',
			"name"=>"提现管理",
			"special"=>"3",
			"second"=>array(
				"alipay_cash_audit"=>array("link"=>"/withdraw/alipay_cash_audit","name"=>"提现待打款"),
				"alipay_presentation_record"=>array("link"=>"/withdraw/alipay_presentation_record","name"=>"打款记录"),
				"card_cash_audit"=>array("link"=>"/withdraw/card_cash_audit","name"=>"企业充值记录"),	
				// "alipay_cash_audit"=>array("link"=>"/withdraw/alipay_cash_audit","name"=>"支付宝提现审核"),
				// "alipay_presentation_record"=>array("link"=>"/withdraw/alipay_presentation_record","name"=>"支付宝提现记录"),
				// "card_cash_audit"=>array("link"=>"/withdraw/card_cash_audit","name"=>"银行卡提现审核"),
				// "card_presentation_record"=>array("link"=>"/withdraw/card_presentation_record","name"=>"银行卡提现记录")
			)
		),
		"6" => array(
			"controller"=>array('jobhunter_all','job_fair','enterprise','cooperation',),
			"style"=>'glyphicon glyphicon-dashboard',
			"name"=>"人力外包中心",
			"special"=>"3",
			"second"=>array(
				"jobhunter_all"=>array("link"=>"/jobhunter_all/jobhunter_list","name"=>"简历库管理"),
				"job_fair"=>array("link"=>"/job_fair/job_fair_position","name"=>"线上招聘会"),
				"enterprise"=>array("link"=>"/enterprise/enterprise_outsourcing_change","name"=>"企业外包需求待反馈"),
				"cooperation"=>array("link"=>"/cooperation/cooperation","name"=>"合作项目"),
			)
		),
		"7" => array(
			"controller"=>array('major','occupation'),
			"style"=>'glyphicon glyphicon-tree-conifer',
			"name"=>"选项管理",
			"special"=>"3",
			"second"=>array(
				"major"=>array("link"=>"/major/major_list","name"=>"专业列表"),
				"occupation"=>array("link"=>"/occupation/occupation_list","name"=>"职位列表")
			)
		),
		// "8" => array(
		// 	"controller"=>array('headhunter','balance'),
		// 	"style"=>'glyphicon glyphicon-user',
		// 	"name"=>"猎头管理",
		// 	"special"=>"3",
		// 	"second"=>array(
		// 		"headhunter"=>array("link"=>"/headhunter/headhunter_list","name"=>"猎头列表"),
		// 		"balance"=>array("link"=>"/balance/balance_list","name"=>"猎头余额列表"),
		// 	)
		// ),
		// "10" => array(
		// 	"controller"=>array('plurality','practice','fulltime'),
		// 	"style"=>'glyphicon glyphicon-cloud-download',
		// 	"name"=>"任务管理",
		// 	"special"=>"3",
		// 	"second"=>array(
		// 		"plurality"=>array("link"=>"/plurality/plurality_list","name"=>"简历列表"),
		// 		"practice"=>array("link"=>"/practice/practice_list","name"=>"邀面列表"),
		// 		"fulltime"=>array("link"=>"/fulltime/fulltime_list","name"=>"到岗列表")
		// 	)
		// ),
		// "11" => array(
		// "controller"=>array('ssadas'),
		// "style"=>'icon-bar-chart',
		// "name"=>"统计管理",
		// "second"=>array(
		// 	)
		// ),
	);
?>