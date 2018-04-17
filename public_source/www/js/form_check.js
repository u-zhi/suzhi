/**
 * 添加admin
 * Null
 * 2016/12/21 Ver 1.00 Created by Allen
 */
var r = /^[0-9]*[1-9][0-9]*$/;　//正整数
var z = /^[1-9]\d*|0$/;　//正整数+0 
var phoneReg=/^0?1[3584][0-9][0-9]{8}$/; //手机号验证
function validate(num) { //金额
	  var reg = /^\d+(?=\.{0,1}\d+$|$)/
	  if(reg.test(num)) return true;
	  return false ;  
} 
function admin_add_check() {
	var username = $("#username").val();
	var password = $("#n_password").val();
   	var data = {username:username};
   	var bool = true;
   	if(username ==  null ||username == '') {
	   $.alert({
		    title: '提示',
		    content:'名称不能为空',
		    confirm: function(){
		    }
		});
        return false;
   	}
   	if(password ==  null ||password == '') {
	   $.alert({
		    title: '提示',
		    content:'密码不能为空',
		    confirm: function(){
		    }
		});
        return false;
   }
   $.ajax({
		url:"/user_admin/admin/check_sole",
		async: false,
		type:"post",
		data: data,
		dataType: 'json',
		success:function(data) {
			bool = data;
		}	
	});
	if(!bool) {
	   	$.alert({
		    title: '提示',
		    content:'名称已存在',
		    confirm: function(){
		    }
		});
		 return false;	
	}
   return true;
}

/**
 * 编辑admin
 * Null
 * 2016/12/21 Ver 1.00 Created by Allen
 */
function admin_edit_check() {
	var username = $("#username").val();
	var password = $("#n_password").val();
	var id = $("#id").val();
	var data = {id:id,username:username};
	var bool = true;
	if(username ==  null ||username == '') {
		$.alert({
			title: '提示',
			content:'名称不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	$.ajax({
		url:"/user_admin/admin/check_sole",
		async: false,
		type:"post",
		data: data,
		dataType: 'json',
		success:function(data) {
			bool = data;
		}	
	});
	if(!bool) {
		$.alert({
			title: '提示',
			content:'名称已存在',
			confirm: function(){
			}
		});
		return false;	
	}
	return true;
} 	

/**
 * 添加role
 * Null
 * 2016/12/21 Ver 1.00 Created by Allen
 */
function role_add_check() {
	var role_name = $("#role_name").val();
	var company_id = $("#company_id").val();
	var data = {company_id:company_id,role_name:role_name};
   	var bool = true;
	if(role_name ==  null ||role_name == '') {
		$.alert({
			title: '提示',
			content:'角色名称不能为空',
			confirm: function(){
			}
		});
		return false;	
	}
	$.ajax({
		url:"/user_admin/role/check_sole",
		async: false,
		type:"post",
		data: data,
		dataType: 'json',
		success:function(data) {
			bool = data;
		}	
	});
	if(!bool) {
	   	$.alert({
		    title: '提示',
		    content:'角色名称已存在',
		    confirm: function(){
		    }
		});
		 return false;	
	}
   	return true;
} 

/**
 * 编辑role
 * Null
 * 2016/12/21 Ver 1.00 Created by Allen
 */	
function role_edit_check() {
	var role_name = $("#role_name").val();
	var role_id = $("#role_id").val();
	var data = {role_id:role_id,role_name:role_name};
	var bool = true;
	if(role_name ==  null ||role_name == '') {
		$.alert({
			title: '提示',
			content:'角色名称不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	$.ajax({
		url:"/user_admin/role/check_sole",
		async: false,
		type:"post",
		data: data,
		dataType: 'json',
		success:function(data) {
			bool = data;
		}	
	});
	if(!bool) {
		$.alert({
			title: '提示',
			content:'角色名称已存在',
			confirm: function(){
			}
		});
		return false;	
	}
	return true;
}
/**
 * 添加major
 * Null
 * 2016/12/21 Ver 1.00 Created by Allen
 */
function major_add_check() {
	var item = $("#item").val();
	if(item ==  null ||item == '') {
		$.alert({
			title: '专业学科姓名不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	return true;
}
/**
 * 编辑occupation
 * Null
 * 2016/12/21 Ver 1.00 Created by Allen
 */
function major_edit_check() {
	var item = $("#item").val();
	if(item ==  null ||item == '') {
		$.alert({
			title: '专业学科姓名不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	return true;
}
/**
 * 添加major
 * Null
 * 2016/12/21 Ver 1.00 Created by Allen
 */
function occupation_add_check() {
	var item = $("#item").val();
	if(item ==  null ||item == '') {
		$.alert({
			title: '职位名称不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	return true;
}
/**
 * 添加pay_add_check
 * Null
 * 2016/12/21 Ver 1.00 Created by Allen
 */
function pay_add_check() {
	var number = $("#number").val();
	var money = $("#money").val();
	if(number ==  null || number == '' || !/^\d+$/.test(number)) {
		$.alert({
			title: '提示',
			content:'人数不能为空且必须为整数',
			confirm: function(){
			}
		});
		return false;
	}	
	if(money ==  null || money == '' || !/^\d+(\.\d{0,2})?$/.test(money)) {
		$.alert({
			title: '提示',
			content:'所需金额不能为空且为数字小数点后2位',
			confirm: function(){
			}
		});
		return false;
	}
	return true;
}

/**
 * 添加pay_add_check
 * Null
 * 2016/12/21 Ver 1.00 Created by Allen
 */
function packge_add_check() {
	var money = $("#money").val();
	if(money ==  null || money == '' || !/^\d+(\.\d{0,2})?$/.test(money)) {
		$.alert({
			title: '提示',
			content:'所需金额不能为空且为数字小数点后2位',
			confirm: function(){
			}
		});
		return false;
	}
	return true;
}
/**
 * 添加major
 * Null
 * 2016/12/21 Ver 1.00 Created by Allen
 */
function open_add_check() {
	var item = $("#item").val();
	if(item ==  null ||item == '') {
		$.alert({
			province_id: '请选择省份',
			city_id: '请选择城市',
			confirm: function(){
			}
		});
		return false;
	}
	return true;
}
/**
 * 编辑occupation
 * Null
 * 2016/12/21 Ver 1.00 Created by Allen
 */
function occupation_edit_check() {
	var item = $("#item").val();
	if(item ==  null ||item == '') {
		$.alert({
			title: '职位名称不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	return true;
}
/**
 * 编辑jobhunter
 * Null
 * 2016/12/21 Ver 1.00 Created by Allen
 */
function jobhunter_edit_check() {
	var nickname = $("#nickname").val();
	var phone_number = $("#phone_number").val();
	var birthday = $("#birthday").val();
	var work_year = $("#work_year").val();
	var work_email = $("#work_email").val();
	if(nickname ==  null ||nickname == '') {
		$.alert({
			title: '姓名不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(phone_number ==  null ||phone_number == '') {
		$.alert({
			title: '联系电话不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(!phoneReg.test(phone_number)) {
		$.alert({
			title: '提示',
			content: '联系电话有误',
			confirm: function(){
			}
		});
		return false;
	}
	if(birthday ==  null ||birthday == '') {
		$.alert({
			title: '生日不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(work_year ==  null ||work_year == '') {
		$.alert({
			title: '工作年限不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(work_email ==  null ||work_email == '') {
		$.alert({
			title: '工作邮箱不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	return true;
}

/**
 * 编辑求职者
 * Null
 * 2016/12/21 Ver 1.00 Created by Allen
 */
function job_add_row() {
	var nickname = $("#nickname").val();
	var phone_number = $("#phone_number").val();
	var work_year = $("#work_year").val();
	var wage_lower = $("#wage_lower").val();
	var wage_upper = $("#wage_upper").val();
	var highest_degree = $("#highest_degree").val();
	var occupation = $("#occupation").val();
	if(nickname ==  null ||nickname == '') {
		$.alert({
			title: '姓名不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(phone_number ==  null ||phone_number == '') {
		$.alert({
			title: '联系电话不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(!phoneReg.test(phone_number)) {
		$.alert({
			title: '提示',
			content: '联系电话有误',
			confirm: function(){
			}
		});
		return false;
	}
	if(work_year ==  null ||work_year == ''|| !/^\d+$/.test(work_year)) {
		$.alert({
			title: '工作年限不能为空,且为数字',
			confirm: function(){
			}
		});
		return false;
	}
	if(wage_lower ==  null ||wage_lower == '' || !/^\d+$/.test(wage_lower) ) {
		$.alert({
			title: '期望薪资期望薪资不能为空,且为数字',
			confirm: function(){
			}
		});
		return false;
	}	
	if(wage_upper ==  null ||wage_upper == '' || !/^\d+$/.test(wage_upper) ) {
		$.alert({
			title: '期望薪资期望薪资不能为空,且为数字',
			confirm: function(){
			}
		});
		return false;
	}
	if(highest_degree ==  null ||highest_degree == '') {
		$.alert({
			title: '学历不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(occupation ==  null ||occupation == '') {
		$.alert({
			title: '期望职位',
			confirm: function(){
			}
		});
		return false;
	}
	return true;
}
/**
 * 编辑headhunter
 * Null
 * 2016/12/21 Ver 1.00 Created by Allen
 */
function headhunter_edit_check() {
	var nickname = $("#nickname").val();
	var phone_number = $("#phone_number").val();
	var real_name = $("#real_name").val();
	var card_no = $("#card_no").val();
	var birthday = $("#birthday").val();
	var enroll_year = $("#enroll_year").val();
	if(nickname ==  null ||nickname == '') {
		$.alert({
			title: '昵称不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(phone_number ==  null ||phone_number == '') {
		$.alert({
			title: '联系电话不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(!phoneReg.test(phone_number)) {
		$.alert({
			title: '提示',
			content: '联系电话有误',
			confirm: function(){
			}
		});
		return false;
	}
	if(real_name ==  null ||real_name == '') {
		$.alert({
			title: '姓名不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(card_no ==  null ||card_no == '') {
		$.alert({
			title: '身份证号不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(birthday ==  null ||birthday == '') {
		$.alert({
			title: '生日不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(enroll_year ==  null ||enroll_year == '') {
		$.alert({
			title: '入学年份不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	return true;
}
/**
 * 添加node
 * Null
 * 2016/12/21 Ver 1.00 Created by Allen
 */	
function node_add_check() {
   	var node_name = $("#node_name").val();
   	var node_sort = $("#node_sort").val();
   	var title = $("#title").val();
   	if(node_name ==  null ||node_name == '') {
	   	$.alert({
		    title: '提示',
		    content:'节点名称不能为空',
		    confirm: function(){
		    }
		});
		return false;
   	}
   	if(title ==  null ||title == '') {
	   	$.alert({
		    title: '提示',
		    content:'节点描述不能为空',
		    confirm: function(){
		    }
		});
		return false;
   	}
   	if(node_sort ==  null ||node_sort == '') {
	   	$.alert({
		    title: '提示',
		    content:'节点排序不能为空',
		    confirm: function(){
		    }
		});
		return false;
   	}
	if(!r.test(node_sort)) {
		$.alert({
		    title: '提示',
		    content: '节点排序有误',
		    confirm: function(){
		    }
		});
        return false;
	}
	return true;
}

/**
 * 编辑node
 * Null
 * 2016/12/21 Ver 1.00 Created by Allen
 */
function node_edit_check() {
   	var node_name = $("#node_name").val();
   	var node_sort = $("#node_sort").val();
   	var title = $("#title").val();
   	if(node_name ==  null ||node_name == '') {
	   	$.alert({
		    title: '提示',
		    content:'节点名称不能为空',
		    confirm: function(){
		    }
		});
		return false;
   	}
   	if(title ==  null ||title == '') {
	   	$.alert({
		    title: '提示',
		    content:'节点描述不能为空',
		    confirm: function(){
		    }
		});
		return false;
   	}
   	if(node_sort ==  null ||node_sort == '') {
	   	$.alert({
		    title: '提示',
		    content:'节点排序不能为空',
		    confirm: function(){
		    }
		});
		return false;
   	}
	if(!r.test(node_sort)) {
		$.alert({
		    title: '提示',
		    content: '节点排序有误',
		    confirm: function(){
		    }
		});
        return false;
	}
	return true;
}

/**
 * 添加classify
 * Null
 * 2016/12/21 Ver 1.00 Created by Allen
 */
function classify_add_check() {
	var name = $("#name").val();
	var classify_sort = $("#classify_sort").val();
   	var data = {name:name};
   	var bool = true;
	if(name ==  null || name == '') {
	   $.alert({
		    title: '提示',
		    content:'分类名称不能为空',
		    confirm: function(){
		    }
		});
        return false;
	 }
   	if(classify_sort ==  null || classify_sort == '') {
	   $.alert({
		    title: '提示',
		    content:'分类排序不能为空',
		    confirm: function(){
		    }
		});
        return false;
   	}
	if(!r.test(classify_sort)) {
		$.alert({
		    title: '提示',
		    content: '分类排序有误',
		    confirm: function(){
		    }
		});
        return false;
	} 
   	$.ajax({
		url:"/user_admin/classify/check_sole",
		async: false,
		type:"post",
		data: data,
		dataType: 'json',
		success:function(data) {
			bool = data;
		}	
	});
	if(!bool) {
	   	$.alert({
		    title: '提示',
		    content:'分类名称已存在',
		    confirm: function(){
		    }
		});
		 return false;	
	}
	return true;
}

/**
 * 编辑classify
 * Null
 * 2016/12/21 Ver 1.00 Created by Allen
 */
function classify_edit_check() {
	var id = $("#id").val();
	var name = $("#name").val();
	var classify_sort = $("#classify_sort").val();
   	var data = {id:id,name:name};
   	var bool = true;
	if(name ==  null || name == '') {
	   $.alert({
		    title: '提示',
		    content:'分类名称不能为空',
		    confirm: function(){
		    }
		});
        return false;
	 }
   	if(classify_sort ==  null || classify_sort == '') {
	   $.alert({
		    title: '提示',
		    content:'分类排序不能为空',
		    confirm: function(){
		    }
		});
        return false;
   	}
	if(!r.test(classify_sort)) {
		$.alert({
		    title: '提示',
		    content: '分类排序有误',
		    confirm: function(){
		    }
		});
        return false;
	} 
   	$.ajax({
		url:"/user_admin/classify/check_sole",
		async: false,
		type:"post",
		data: data,
		dataType: 'json',
		success:function(data) {
			bool = data;
		}	
	});
	if(!bool) {
	   	$.alert({
		    title: '提示',
		    content:'分类名称已存在',
		    confirm: function(){
		    }
		});
		 return false;	
	}
	return true;
}
/**
 * 添加firm
 * Null
 * 2016/12/22 Ver 1.00 Created by Allen
 */
function firm_add_check() {

	var icon_url =  $("#file-0b").val();
	var name = $("#name").val();
	var contact =  $("#contact").val();
	var phone_number =  $("#phone_number").val();
	var type =  $("#type").val();
	var scale =  $("#scale").val();
	var address =  $("#address").val();
	var bool = true;
	if(icon_url ==  null || icon_url == '') {
		$.alert({
			title: '提示',
			content:'请上传企业LOGO',
			confirm: function(){
			}
		});
		return false;
	}
	if(name ==  null || name == '') {
		$.alert({
			title: '提示',
			content:'企业名称不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(contact ==  null || contact == '') {
		$.alert({
			title: '提示',
			content:'联系人不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(phone_number ==  null || phone_number == '') {
		$.alert({
			title: '提示',
			content:'联系人手机号不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(!phoneReg.test(phone_number)) {
		$.alert({
			title: '提示',
			content: '联系人手机号有误',
			confirm: function(){
			}
		});
		return false;
	}
	if(type ==  null || type == '') {
		$.alert({
			title: '提示',
			content:'公司行业不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(scale ==  null || scale == '') {
		$.alert({
			title: '提示',
			content:'公司人数不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(address ==  null || address == '') {
		$.alert({
			title: '提示',
			content:'公司地址不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	return true;
}
/**
 * 编辑firm
 * Null
 * 2016/12/22 Ver 1.00 Created by Allen
 */
function firm_edit_check() {

	// var icon_url =  $("#file-0b").val();
	var name = $("#name").val();
	var contact =  $("#contact").val();
	var phone_number =  $("#phone_number").val();
	var type =  $("#type").val();
	var scale =  $("#scale").val();
	var address =  $("#address").val();
	var bool = true;
	if(name ==  null || name == '') {
		$.alert({
			title: '提示',
			content:'企业名称不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(contact ==  null || contact == '') {
		$.alert({
			title: '提示',
			content:'联系人不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(phone_number ==  null || phone_number == '') {
		$.alert({
			title: '提示',
			content:'联系人手机号不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(!phoneReg.test(phone_number)) {
		$.alert({
			title: '提示',
			content: '联系人手机号有误',
			confirm: function(){
			}
		});
		return false;
	}
	if(type ==  null || type == '') {
		$.alert({
			title: '提示',
			content:'公司行业不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(scale ==  null || scale == '') {
		$.alert({
			title: '提示',
			content:'公司人数不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(address ==  null || address == '') {
		$.alert({
			title: '提示',
			content:'公司地址不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	return true;
}
/**
 * 添加goods
 * Null
 * 2016/12/22 Ver 1.00 Created by Allen
 */
function goods_add_check() {
	var name = $("#name").val();
	// var good_logo =  $("#work_image").attr('src');
	var good_logo =  $("#file-0b").val();
	var work_address =  $("#work_address").val();
	var person_demand =  $("#person_demand").val();
	var commission =  $("#commission").val();
	var salary =  $("#salary").val();
	var work_time =  $("#work_time").val();
	var work_schedule =  $("#work_schedule").val();
   	var bool = true;
	if(good_logo ==  null || good_logo == '') {
		$.alert({
			title: '提示',
			content:'请上传任务LOGO',
			confirm: function(){
			}
		});
		return false;
	}
	if(name ==  null || name == '') {
	   $.alert({
		    title: '提示',
		    content:'任务名称不能为空',
		    confirm: function(){
		    }
		});
        return false;
	 }
	if(work_address ==  null || work_address == '') {
		$.alert({
			title: '提示',
			content:'工作地点不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(person_demand ==  null || person_demand == '') {
		$.alert({
			title: '提示',
			content:'需求人数不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(commission ==  null || commission == '') {
		$.alert({
			title: '提示',
			content:'佣金不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(salary ==  null || salary == '') {
		$.alert({
			title: '提示',
			content:'薪资不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(work_time ==  null || work_time == '') {
		$.alert({
			title: '提示',
			content:'工作时间不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(work_schedule ==  null || work_schedule == '') {
		$.alert({
			title: '提示',
			content:'上班时段不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	return true;
}

/**
 * 编辑goods
 * Null
 * 2016/12/22 Ver 1.00 Created by Allen
 */
function goods_edit_check() {
	var name = $("#name").val();
	// var good_logo =  $("#work_image").attr('src');
	// var good_logo =  $("#file-0b").val();
	var work_address =  $("#work_address").val();
	var person_demand =  $("#person_demand").val();
	var commission =  $("#commission").val();
	var salary =  $("#salary").val();
	var work_time =  $("#work_time").val();
	var work_schedule =  $("#work_schedule").val();
	var bool = true;
	// if(good_logo ==  null || good_logo == '') {
	// 	$.alert({
	// 		title: '提示',
	// 		content:'请上传任务LOGO',
	// 		confirm: function(){
	// 		}
	// 	});
	// 	return false;
	// }
	if(name ==  null || name == '') {
		$.alert({
			title: '提示',
			content:'任务名称不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(work_address ==  null || work_address == '') {
		$.alert({
			title: '提示',
			content:'工作地点不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(person_demand ==  null || person_demand == '') {
		$.alert({
			title: '提示',
			content:'需求人数不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(commission ==  null || commission == '') {
		$.alert({
			title: '提示',
			content:'佣金不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(salary ==  null || salary == '') {
		$.alert({
			title: '提示',
			content:'薪资不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(work_time ==  null || work_time == '') {
		$.alert({
			title: '提示',
			content:'工作时间不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(work_schedule ==  null || work_schedule == '') {
		$.alert({
			title: '提示',
			content:'上班时段不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	return true;
}
/**
 * 添加interview
 * Null
 * 2016/12/22 Ver 1.00 Created by Allen
 */
function interview_add_check() {
	var interview_time =  $("#interview_time").val();
	var contact =  $("#contact").val();
	var contact_tel =  $("#contact_tel").val();
	var interview_address =  $("#interview_address").val();
	var bool = true;
	if(interview_time ==  null || interview_time == '') {
		$.alert({
			title: '提示',
			content:'面试时间不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(contact ==  null || contact == '') {
		$.alert({
			title: '提示',
			content:'联系人不能为空',
			confirm: function(){
			}
		});
		return false;
	}

	if(contact_tel ==  null || contact_tel == '') {
		$.alert({
			title: '提示',
			content:'联系电话不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	// if(!phoneReg.test(contact_tel)) {
	// 	$.alert({
	// 		title: '提示',
	// 		content: '联系电话有误',
	// 		confirm: function(){
	// 		}
	// 	});
	// 	return false;
	// }
	if(interview_address ==  null || interview_address == '') {
		$.alert({
			title: '提示',
			content:'面试地点不能为空',
			confirm: function(){
			}
		});
		return false;
	}

	return true;
}
/**
 * 添加work
 * Null
 * 2016/12/22 Ver 1.00 Created by Allen
 */
function work_add_check() {
	var work_time =  $("#work_time").val();
	var contact =  $("#contact").val();
	var contact_tel =  $("#contact_tel").val();
	var work_address =  $("#work_address").val();
	var bool = true;
	if(work_time ==  null || work_time == '') {
		$.alert({
			title: '提示',
			content:'开工时间不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(contact ==  null || contact == '') {
		$.alert({
			title: '提示',
			content:'联系人不能为空',
			confirm: function(){
			}
		});
		return false;
	}

	if(contact_tel ==  null || contact_tel == '') {
		$.alert({
			title: '提示',
			content:'联系电话不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	// if(!phoneReg.test(contact_tel)) {
	// 	$.alert({
	// 		title: '提示',
	// 		content: '联系电话有误',
	// 		confirm: function(){
	// 		}
	// 	});
	// 	return false;
	// }
	if(work_address ==  null || work_address == '') {
		$.alert({
			title: '提示',
			content:'开工地点不能为空',
			confirm: function(){
			}
		});
		return false;
	}

	return true;
}
/**
 * 添加carousel
 * Null
 * 2016/12/22 Ver 1.00 Created by Allen
 */
function carousel_add_check() {
	var good_logo =  $("#file-0b").val();
	var sort =  $("#sort").val();
	// var link =  $("#link").val();
	var bool = true;
	if(good_logo ==  null || good_logo == '') {
		$.alert({
			title: '提示',
			content:'请上传轮播LOGO',
			confirm: function(){
			}
		});
		return false;
	}
	if(sort ==  null || sort == '') {
		$.alert({
			title: '提示',
			content:'排序不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	// if(link ==  null || link == '') {
	// 	$.alert({
	// 		title: '提示',
	// 		content:'链接不能为空',
	// 		confirm: function(){
	// 		}
	// 	});
	// 	return false;
	// }

	return true;
}

/**
 * 编辑carousel
 * Null
 * 2016/12/22 Ver 1.00 Created by Allen
 */
function carousel_edit_check() {
	var sort =  $("#sort").val();
	var link =  $("#link").val();
	var bool = true;
	if(sort ==  null || sort == '') {
		$.alert({
			title: '提示',
			content:'排序不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(link ==  null || link == '') {
		$.alert({
			title: '提示',
			content:'链接不能为空',
			confirm: function(){
			}
		});
		return false;
	}

	return true;
}
/**
 * 添加case
 * Null
 * 2016/12/22 Ver 1.00 Created by Allen
 */
function case_add_check() {
	var title = $("#title").val();
	var case_sort = $("#case_sort").val();
	if(title ==  null || title == '') {
	   $.alert({
		    title: '提示',
		    content:'文案标题不能为空',
		    confirm: function(){
		    }
		});
        return false;
	 }
   	if(case_sort ==  null || case_sort == '') {
	   $.alert({
		    title: '提示',
		    content:'排序不能为空',
		    confirm: function(){
		    }
		});
        return false;
   	}
	if(!r.test(case_sort)) {
		$.alert({
		    title: '提示',
		    content: '排序有误',
		    confirm: function(){
		    }
		});
        return false;
	}
	return true;
}

/**
 * 编辑case
 * Null
 * 2016/12/22 Ver 1.00 Created by Allen
 */
function case_edit_check() {
	var title = $("#title").val();
	var case_sort = $("#case_sort").val();
	if(title ==  null || title == '') {
	   $.alert({
		    title: '提示',
		    content:'文案标题不能为空',
		    confirm: function(){
		    }
		});
        return false;
	 }
   	if(case_sort ==  null || case_sort == '') {
	   $.alert({
		    title: '提示',
		    content:'排序不能为空',
		    confirm: function(){
		    }
		});
        return false;
   	}
	if(!r.test(case_sort)) {
		$.alert({
		    title: '提示',
		    content: '排序有误',
		    confirm: function(){
		    }
		});
        return false;
	}
	return true;
}

/**
 * 添加news
 * Null
 * 2016/12/24 Ver 1.00 Created by Allen
 */
function news_add_check() {
	var title = $("#title").val();
	var news_sort =  $("#news_sort").val();
	if(title ==  null || title == '') {
	   $.alert({
		    title: '提示',
		    content:'文章名称不能为空',
		    confirm: function(){
		    }
		});
        return false;
	 }
   	if(news_sort ==  null || news_sort == '') {
	   $.alert({
		    title: '提示',
		    content:'排序不能为空',
		    confirm: function(){
		    }
		});
        return false;
   	}
	if(!r.test(news_sort)) {
		$.alert({
		    title: '提示',
		    content: '排序有误',
		    confirm: function(){
		    }
		});
        return false;
	}
	return true;
}


/**
 * 通用设置
 * Null
 * 2016/12/26 Ver 1.00 Created by Allen
 */
function company_edit_check() {
	var contact_num = $("#contact_num").val();
	var contact =  $("#contact").val();
	if(contact ==  null || contact == '') {
	   $.alert({
		    title: '提示',
		    content:'公司联系人不能为空',
		    confirm: function(){
		    }
		});
        return false;
	}
   	if(contact_num ==  null || contact_num == '') {
	   $.alert({
		    title: '提示',
		    content:'公司电话不能为空',
		    confirm: function(){
		    }
		});
        return false;
   	}
	if(!phoneReg.test(contact_num)) {
		$.alert({
		    title: '提示',
		    content: '公司电话有误',
		    confirm: function(){
		    }
		});
        return false;
	}
	return true;
}

/**
 * 编辑finance
 * Null
 * 2016/12/26 Ver 1.00 Created by Allen
 */
function finance_edit_check() {
	var one_class = $("#one_class").val();
	var two_class = $("#two_class").val();
	var three_class = $("#three_class").val();
	var four_class = $("#four_class").val();
	var five_class = $("#five_class").val();
	if(one_class == "" || one_class == 0 || !validate(one_class)) {
		$.alert({
		    title: '提示',
		    content: '一级代理金额错误',
		    confirm: function(){
		    }
		});
        return false;
	}
	if(two_class == "" || two_class == 0 || !validate(two_class)) {
		$.alert({
		    title: '提示',
		    content: '二级代理金额错误',
		    confirm: function(){
		    }
		});
        return false;
	}
	if(three_class == "" || three_class == 0 || !validate(three_class)) {
		$.alert({
		    title: '提示',
		    content: '三级代理金额错误',
		    confirm: function(){
		    }
		});
        return false;
	}
	if(four_class == "" || four_class == 0 || !validate(four_class)) {
		$.alert({
		    title: '提示',
		    content: '四级代理金额错误',
		    confirm: function(){
		    }
		});
        return false;
	}
	if(five_class == "" || five_class == 0 || !validate(five_class)) {
		$.alert({
		    title: '提示',
		    content: '五级代理金额错误',
		    confirm: function(){
		    }
		});
        return false;
	}
	return true;
}


/**
 * 编辑一级代理
 * Null
 * 2016/12/28 Ver 1.00 Created by Allen
 */
function one_edit_check() {
	var user_name = $("#user_name").val();
	var phone = $("#phone").val();
	var id = $("#id").val();
	var data = {id:id,user_name:user_name};
	var phone_data = {id:id,phone:phone};
	var bool = true;
	var phone_bool = true;
	if(user_name ==  null || user_name == '') {
		$.alert({
			title: '提示',
			content:'姓名不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(phone ==  null || phone == '') {
		$.alert({
			title: '提示',
			content:'手机号不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	if(!phoneReg.test(phone)) {
		$.alert({
		    title: '提示',
		    content: '手机号有误',
		    confirm: function(){
		    }
		});
        return false;
	}
	$.ajax({
		url:"/user_admin/one_level/check_sole",
		async: false,
		type:"post",
		data: data,
		dataType: 'json',
		success:function(data) {
			bool = data;
		}	
	});
	if(!bool) {
		$.alert({
			title: '提示',
			content:'姓名已存在',
			confirm: function(){
			}
		});
		return false;	
	}
	$.ajax({
		url:"/user_admin/one_level/check_sole",
		async: false,
		type:"post",
		data: phone_data,
		dataType: 'json',
		success:function(data) {
			phone_bool = data;
		}	
	});
	if(!phone_bool) {
		$.alert({
			title: '提示',
			content:'手机号已存在',
			confirm: function(){
			}
		});
		return false;	
	}
	return true;
} 

/**
 * 编辑一级代理
 * Null
 * 2017/01/06 Ver 1.00 Created by Allen
 */
function need_edit_check() {
	var agent_level = $("#agent_level").val();
	var phone = $("#phone").val();
	var data = {agent_level:agent_level,phone:phone};
	var bool = true;
	if(phone ==  null || phone == '') {
		$.alert({
			title: '提示',
			content:'添加代理不能为空',
			confirm: function(){
			}
		});
		return false;
	}
	$.ajax({
		url:"/user_admin/need/check_need",
		async: false,
		type:"post",
		data: data,
		dataType: 'json',
		success:function(data) {
			bool = data;
		}	
	});
	if(!bool) {
		$.alert({
			title: '提示',
			content:'添加代理不符合要求',
			confirm: function(){
			}
		});
		return false;	
	}
	return true;
} 