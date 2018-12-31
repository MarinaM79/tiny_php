<?php
/**
 * User: zjz
 * File: access.php
 * Date: 2018/12/10
 * Time: 13:10
 */
namespace src\tiny\Logs;
$last_day = date('Y-m-d',strtotime('-1 day'));
if(!is_dir(App.'storage/logs/old/access/'))mkdir(App.'storage/logs/old/access/');
if(!is_file(App.'storage/logs/old/access/tiny_access_'.$last_day.'.log')){
  $content = file_get_contents(App.'storage/logs/tiny_access.log');
  $old_access = fopen(App.'storage/logs/old/access/tiny_access_'.$last_day.'.log','w+');
  flock($old_access,LOCK_EX);
  fwrite($old_access,$content);
  flock($old_access,LOCK_UN);
  fclose($old_access);
  unlink(App.'storage/logs/tiny_access.log');
}
$client_ip = $_SERVER['REMOTE_ADDR'];
$log_time = date('Y-m-d H:i:s',time());
$query_string = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
$request_url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$query_string;
$log_text = '[ '.$log_time.' ip : '.$client_ip.' ] : '.$_SERVER['REQUEST_METHOD'] .'  '.$request_url;
$log = fopen(App.'storage/logs/tiny_access.log','a+');
fwrite($log,$log_text."\n");
fclose($log);
