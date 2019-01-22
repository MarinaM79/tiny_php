<?php
/**
 * User: zjz
 * File: route.php
 * Date: 2018/12/12
 * Time: 10:12
 * Description: load Route list , action start, 请求分发
 */
namespace src\tiny\route;
use src\tiny\Logs\Error_log;
use src\tiny\route\Request;

final class route_load
{
    public static function load_needs($action){
        $action = explode('/',$action);
        chdir(App.'app/Controller/');
        try{
            if (!is_dir($action[0])){
                require_once App.'app/Controller/'.$action[0].'.php';
                $func = $action[1];
                $class_name = "app\Controller\\".$action[0];
                $cont = new $class_name();
                // 反射获取方法所需要的参数
                $p = new \ReflectionMethod($class_name, $func);
                $param_list = $p->getParameters();

                // 无参数, 直接调用
                if (count($param_list) ==0){
                    $cont->$func();
                }

                // 将 Request 注入
                if (preg_match('/(r|R)equest.*/',$param_list[0]->name)){
                      $cont->$func(new Request);
                }else{
                      $cont->$func();
                }
            }else{
                require_once App.'app/Controller/'.$action[0].'/'.$action[1].'.php';
                $func = $action[2];
                $class_name = "app\Controller\\".$action[0].'\\'.$action[1];
                $cont = new $class_name();
                // 反射获取方法所需要的参数
                $p = new \ReflectionMethod($class_name, $func);
                $param_list = $p->getParameters();

                // 无参数, 直接调用
                if (count($param_list) ==0){
                    $cont->$func();
                }

                // 将 Request 注入
                if (preg_match('/(r|R)equest.*/',$param_list[0]->name)){
                    $cont->$func(new Request);
                }else{
                    $cont->$func();
                }
            }
        }catch (\Exception $e){
            new Error_log($e);
            tiny_bad('系统出错');
            exit();
        }

    }
}
