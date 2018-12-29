<?php
/**
 * User: zjz
 * File: Web.php
 * Date: 2018/12/21
 * Time: 9:47
 */


namespace app\Middleware;


use function src\tiny\route\route_exits;
final class Web
{
    public function middleware(){
        $url = $_SERVER['REQUEST_URI'];
        if (preg_match('/.*(script|insert).*/',$url)){
            tiny_bad();
        }
        define('REQUEST_METHOD',$_SERVER['REQUEST_METHOD']);
        if(REQUEST_METHOD == 'GET') $method = &$_GET;
        if (REQUEST_METHOD == 'POST') $method = &$_POST;
        foreach ($method as $k => $v){
            if (is_string($v)&&preg_match('/.*(script|insert).*/',$v)){
                unset($method[$k]);
            }
        }
        if(!preg_match('/.*(Mozilla|Chrome|WebKit|Safari).*/',$_SERVER['HTTP_USER_AGENT'])&&!defined('APIROUTE')){
            if(!route_exits($_SERVER['REQUEST_URI']))tiny_bad();
        }


        if ($_SERVER['REQUEST_METHOD']=='GET'){
            $app_safe_key = password_hash(env('APPSAFE_KEY').$_SERVER['REMOTE_ADDR'],PASSWORD_DEFAULT);
            setcookie('tiny_php_xsrf',$app_safe_key,time()+3600,'/');
        }
        if ((!isset($_COOKIE['tiny_php_xsrf']))&&$_SERVER['REQUEST_METHOD'] == 'POST'&&!defined('APIROUTE')){
            tiny_bad('登录已过期');
        }
        if(!defined('APIROUTE')&&$_SERVER['REQUEST_METHOD'] == 'POST' && !password_verify(env('APPSAFE_KEY').$_SERVER['REMOTE_ADDR'],$_COOKIE['tiny_php_xsrf'])){
            tiny_bad(); // TODO: needs to complate the except url setting
        }
    }
}