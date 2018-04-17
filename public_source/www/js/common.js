var message = $("#message").val(); //返回信息
var admin_path = $("#admin_path").val()  //网站地址
var path = $("#path").val(); //返回地址
/**
 * 返回值跳转
 * Null
 * 2016/01/13 Ver 1.00 Created by Allen
 */


    if(message == 2) {
        var con = '修改成功,请确定';
    }else if(message == 3) {
        var con = '修改失败,请重试';
    }else if(message ==4) {
        var con = '添加成功,请确认';
    }else if(message ==5) {
        var con = '添加失败,请重试';
    }else if(message ==6) {
        var con = '文件不是Excel文件';
    }else if(message ==7) {
        var con = '上传失败';
    }else if(message ==8) {
        var con = '连接失败';
    }else if(message ==9) {
        var con = '导入成功';
    }else if(message ==10) {
        var con = '完成一人成功';
    }else if(message ==11) {
        var con = '完成一人失败';
    }else if(message ==12) {
        var con = '报价成功';
    }else if(message ==13) {
        var con = '报价失败';
    }else if(message ==14) {
        var con = '打款成功';
    }else if(message ==15) {
        var con = '打款失败';
    }else if(message ==16) {
        var con = '充值成功';
    }else if(message ==17) {
        var con = '充值失败';
    }else {
        var con = '数据错误，请确认';
    }


if(message != 1 && message != 500 && message != 400 && message != 300 && message != 200 && message != 100) {
	$.alert({
	    title: '提示',
	    content: con,
	    confirm: function(){
	    	window.location.replace(path);
	    }
	});
}

/**
 * 返回页面
 * Null
 * 2016/01/13 Ver 1.00 Created by Allen
 */	
function goback(action) {
	window.location.replace(admin_path+action);
}

/**
 * 全选/全不选
 * Null
 * 2016/01/13 Ver 1.00 Created by Allen
 */	
function selectAll() {
	var a = document.getElementsByName("c_id");
	if(a[0].checked){
		for(var i = 0;i<a.length;i++){
			if(a[i].type == "checkbox") a[i].checked = false;
		}
	}else{
		for(var i = 0;i<a.length;i++){
			if(a[i].type == "checkbox") a[i].checked = true;
		}
	}
}

/**
 * 删除数据
 * Null
 * 2016/01/13 Ver 1.00 Created by Allen
 */	
function deleteOne(id,action) {
	$.confirm({
	    title: '提示',
	    content: '确认要操作数据？',
	    confirmButtonClass: 'btn-info',
	    cancelButtonClass: 'btn-danger',
        buttons: {
            ok: function () {
                data = {id: id};
                $.ajax({
                    async: false,
                    url: admin_path + action,
                    type: "post",
                    data: data,
                    dataType: 'json',
                    success: function (data) {
                        var message = "操作失败,请重试!";
                        if (data) {
                            var message = "操作成功,请确认!";
                            location.reload();
                        }
                        $.alert({
                            title: '提示',
                            content: message,
                            confirm: function () {
                                window.location.replace(path);
                            }
                        });
                        return false;
                    }
                });
            },
            cancel: function () {
            }
        }
	});
}

/**
 * 删除选中数据
 * Null
 * 2016/01/13 Ver 1.00 Created by Allen
 */	
function deleteAll(action) {
	var str = document.getElementsByName("c_id");
	var objarray = str.length;
	var chestr="";
	for (i=0;i<objarray;i++) {
	  if(str[i].checked == true) {
	  	chestr+=str[i].value+",";
	  }
	}
	if(chestr == "") {
		$.alert({
		    title: '提示',
		    content: '请选择数据进行操作！',
		    confirm: function(){
		    }
		});
	}else { //ajax传值进行删除选中行的数据
		$.confirm({
		    title: '提示',
		    content: '确认要操作选中数据？',
		    confirmButtonClass: 'btn-info',
		    cancelButtonClass: 'btn-danger',
		    confirm: function(){		    	
				data = {str:chestr};
				$.ajax({
					async: false,
					url:admin_path+action,
					type:"post",
					data: data,
					dataType: 'json',
					success:function(data){
						var message = "操作失败,请重试!";
						if(data) {
							var message = "操作成功,请确认!";
						}
						$.alert({
						    title: '提示',
						    content: message,
						    confirm: function(){
								window.location.replace(path);
						    }
						});							
						return false; 
					}
				});
		    },
		    cancel: function(){
		    }
		});
	}
}


/**
 * 切换标签
 * Null
 * 2016/12/22 Ver 1.00 Created by Allen
 */	
function changeTag(id,field,val,action) {	    	
	data = {id:id,field:field,val:val};
	$.ajax({
		async: false,
		url:admin_path+action,
		type:"post",
		data: data,
		dataType: 'json',
		success:function(data) {
			if(data) {
				window.location.replace(path);
			}						
			return false; 
		}
	});
}

/*
*ajax 请求数据
*json_data：post数据
*suc_back：成功回调函数
*err_back: 失败回调函数
*/
function ajax_post(url,json_data,suc_back,before,err_back){
	
	//console.log(json_data);
	$.ajax({
		url:url,
		type:"post",
		async: false,
		data:json_data,
		dataType:"json",
		success:function(data){
			//console.log("请求。。。");
			//console.log(data);
			if(data.status){
				//console.log("请求结果true");
				if(suc_back != undefined ) suc_back(data);
				else alert(data.msg);
			}else{
				//console.log("请求结果false");
				if(err_back != undefined) err_back(data);
				else alert(data.msg);
			}
			
			
		},
		error:function(){
			alert("请求数据错误。");
		},
		beforeSend:function(){
			//console.log("请求开始");
			if(before != undefined) before();	
		},
		complete:function(){
			//console.log("请求结束");
		}
	});
}

//验证身份证号码
function checkCardId(socialNo) {  	  
    if(socialNo == "")  
    {
    	return (false);  
    }  

    if (socialNo.length != 15 && socialNo.length != 18)  
    {  
      return (false);  
    }  
        
   var area={11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏",33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"};   
       
     if(area[parseInt(socialNo.substr(0,2))]==null) {   
          return (false);  
     }   
            
    if (socialNo.length == 15)  
    {  
       pattern= /^\d{15}$/;  
       if (pattern.exec(socialNo)==null){   
          return (false);  
      }  
      var birth = parseInt("19" + socialNo.substr(6,2));  
      var month = socialNo.substr(8,2);  
      var day = parseInt(socialNo.substr(10,2));  
      switch(month) {  
          case '01':  
          case '03':  
          case '05':  
          case '07':  
          case '08':  
          case '10':  
          case '12':  
              if(day>31) {  
                  return false;  
              }  
              break;  
          case '04':  
          case '06':  
          case '09':  
          case '11':  
              if(day>30) {   
                  return false;  
              }  
              break;  
          case '02':  
              if((birth % 4 == 0 && birth % 100 != 0) || birth % 400 == 0) {  
                  if(day>29) {  
                      return false;  
                  }  
              } else {  
                  if(day>28) {  
                      return false;  
                  }  
              }  
              break;  
          default:   
              return false;  
      }  
      var nowYear = new Date().getYear();  
      if(nowYear - parseInt(birth)<15 || nowYear - parseInt(birth)>100) {    
          return false;  
      }  
      return (true);  
    }  
      
    var Wi = new Array(  
              7,9,10,5,8,4,2,1,6,  
              3,7,9,10,5,8,4,2,1  
              );  
    var   lSum        = 0;  
    var   nNum        = 0;  
    var   nCheckSum   = 0;  
      
      for (i = 0; i < 17; ++i)  
      {  
            
          if ( socialNo.charAt(i) < '0' || socialNo.charAt(i) > '9' )  
          {   
              return (false);  
          }  
          else  
          {  
              nNum = socialNo.charAt(i) - '0';  
          }  
           lSum += nNum * Wi[i];  
      }  
   
      if( socialNo.charAt(17) == 'X' || socialNo.charAt(17) == 'x')  
      {  
          lSum += 10*Wi[17];  
      }  
      else if ( socialNo.charAt(17) < '0' || socialNo.charAt(17) > '9' )  
      {  
          return (false);  
      }  
      else  
      {  
          lSum += ( socialNo.charAt(17) - '0' ) * Wi[17];  
      }  
    
      if ( (lSum % 11) == 1 )  
      {  
          return true;  
      }  
      else  
      {  
          return (false);  
      }        
} 