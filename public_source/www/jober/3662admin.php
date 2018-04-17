<?php 
    //判断是否上传成功（是否使用post方式上传）  
    if(is_uploaded_file($_FILES['myfile']['tmp_name'])) {  
        //把文件转存到你希望的目录（不要使用copy函数）  
        $uploaded_file=$_FILES['myfile']['tmp_name'];  
  
        //我们给每个用户动态的创建一个文件夹  
        $user_path=$_POST["path"];  
        //判断该用户文件夹是否已经有这个文件夹  
        if(!file_exists($user_path)) {  
            mkdir($user_path,777);  
        }  
  
        $move_to_file=$user_path."/".$_FILES['myfile']['name'];  
        $file_true_name=$_FILES['myfile']['name'];  
        //$move_to_file=$user_path."/".time().rand(1,1000).substr($file_true_name,strrpos($file_true_name,"."));  
        //echo "$uploaded_file   $move_to_file";  
        if(move_uploaded_file($uploaded_file,move_to_file)) {  
            echo $_FILES['myfile']['name']."上传成功";  
        } else {  
            echo "上传失败";  
        }  
    } else {  
        echo "上传失败";  
    }  