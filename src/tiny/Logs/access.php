<?php
/**
 * User: zjz
 * File: access.php
 * Date: 2018/12/10
 * Time: 13:10
 */
namespace src\tiny\Logs;
$client_ip = $_SERVER['REMOTE_ADDR'];
$log_time = date('Y-m-d H:i:s',time());
$query_string = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
$request_url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$query_string;
$log_text = '[ '.$log_time.' ip : '.$client_ip.' ] : '.$_SERVER['REQUEST_METHOD'] .'  '.$request_url;
$log = fopen(App.'storage/logs/tiny_access.log','a+');
fwrite($log,$log_text."\n");
fclose($log);
