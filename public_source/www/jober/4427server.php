<?php
error_reporting(E_ALL | E_STRICT);

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_bind($socket, '127.0.0.1', 9998);

// 存入redis队列
$redis = new Redis();
$redis->connect('localhost', 6379);
//$redis->auth("password");
do{
    $from = '';
    $port = 0;
    socket_recvfrom($socket, $buf, 2048, 0, $from, $port);
    // 获取info
    $interface_info = $buf;

    // 加上时间戳存入队列
    $now_time = date("Y-m-d H:i:s");
    $redis->rPush("call_log", $interface_info . "%" . $now_time);
}while($buf!==false);
$redis->close();