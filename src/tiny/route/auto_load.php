<?php
/**
 * User: zjz
 * File: auto_load.php
 * Date: 2018/12/12
 * Time: 9:32
 * Description: load route list
 */
namespace src\tiny\route;
$GLOBALS['route_list'] = ['post'=>[],'get'=>[]];
if(!preg_match('/.*(Mozilla|Chrome|WebKit|Safari).*/',$_SERVER['HTTP_USER_AGENT'])){
    define('APIROUTE',true);  // disable the api xsrf check
}
class Route
{
    protected $is_get;
    protected $now_query_string;
    public static function post($query_string,$action){
        $route = new Route();
        $GLOBALS['route_list']['post'][$query_string] = $action;
        $route->now_query_string = $query_string;
        $route->is_get = false;
        if (isset($GLOBALS['route_group'])){
            if ($route->is_get == true){
                $GLOBALS['middleware']['get'][$route->now_query_string] = $GLOBALS['route_group'];
            }else{
                $GLOBALS['middleware']['post'][$route->now_query_string] = $GLOBALS['route_group'];
            }
        }
        return $route;
    }

    public static function get($query_string,$action){
        $route = new Route();
        $GLOBALS['route_list']['get'][$query_string] = $action;
        $route->now_query_string = $query_string;
        $route->is_get = true;
        if (isset($GLOBALS['route_group'])){
            if ($route->is_get == true){
                $GLOBALS['middleware']['get'][$route->now_query_string] = $GLOBALS['route_group'];
            }else{
                $GLOBALS['middleware']['post'][$route->now_query_string] = $GLOBALS['route_group'];
            }
        }
        return $route;
    }

    public function Middleware($middle_ware){
        if (isset($GLOBALS['route_group'])){
            $middle_ware = $GLOBALS['route_group'].','.$middle_ware;
        }
        if ($this->is_get == true){
            $GLOBALS['middleware']['get'][$this->now_query_string] = $middle_ware;
        }else{
            $GLOBALS['middleware']['post'][$this->now_query_string] = $middle_ware;
        }
    }

    public static function start_group($middleware){
        $GLOBALS['route_group'] = $middleware;
    }

    public static function end_group(){
        unset($GLOBALS['route_group']);
    }
}

if (defined('APIROUTE')){
    require_once App.'routes/api.php';
}else{
    require_once App.'routes/web.php';
}
define('ROUTE_LIST',$GLOBALS['route_list']);
unset($GLOBALS['route_list']);

function route_exits($url){
    if (strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
        foreach (ROUTE_LIST['post'] as $k => $v){
            if (strpos($url,$k)){
                return true;
            }
        }
    }else{
        foreach (ROUTE_LIST['get'] as $k => $v){
            if (strpos($url,$k)){
                return true;
            }
        }
    }
    return false;
}

function get_action($url){
    $url = $url=='/' ? '/index' : $url;
    $script_name = $_SERVER['SCRIPT_NAME'];
    $tiny_index = explode('/',$script_name);
    $tiny_path = '/';
    foreach ($tiny_index as $k => $v){
        if ($k!=(count($tiny_index)-1))$tiny_path.=$v;
    }
    if (!$_SERVER['SCRIPT_NAME'] == '/index.php'){
        $url = substr($url,strlen($tiny_path)+1);
    }
    $url = explode('?',$url);
    $url = $url[0];
    if (strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
        foreach (ROUTE_LIST['post'] as $k => $v){
            if ($url=='/'.$k||$url==$k){
                return [$url,$v];
            }
        }
    }else{
        foreach (ROUTE_LIST['get'] as $k => $v){
            if ($url== '/'.$k||$url==$k){
                return [$url,$v];
            }
        }
    }
    return false;
}
