<?php
/**
 * User: zjz
 * File: Middleware.php
 * Date: 2018/12/21
 * Time: 9:18
 */
namespace src\tiny\Middleware;
use app\Middleware\Api;
use app\Middleware\Web;

final class Middleware{
    public static function load_middleware($url){
        if (!defined('API')){
            require_once App.'app/Middleware/Web.php';
            $web = new Web();
            $web->middleware();
        }else{
            require_once App.'app/Middleware/Api.php';
            $api = new Api();
            $api->middleware();
        }
        $url = explode('/',$url);
        $url = isset($url[1]) ? $url[1] : '';
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        if (isset($GLOBALS['middleware'][$method])&&key_exists($url,$GLOBALS['middleware'][$method])){
            $middleware = $GLOBALS['middleware'][$method][$url];
            $middlewares = explode(',',$middleware);
            foreach ($middlewares as $middleware){
                $file = App.'app/Middleware/'.$middleware.'.php';
                if (file_exists($file)){
                    require_once $file;
                    $class_name = 'app\\Middleware\\'.$middleware;
                    $midle = new $class_name;
                    $midle->middleware();
                }
            }

        }
    }
}
