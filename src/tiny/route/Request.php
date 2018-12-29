<?php
/**
 * User: zjz
 * File: Request.php
 * Date: 2018/12/12
 * Time: 10:25
 * Description: global Request helper
 */
namespace src\tiny\route;
final class Request
{
    public $request = [];
    public $post = [];
    public $get = [];

    public function __construct()
    {
        foreach ($_POST as $k => $v){
            $this->post[$k] = $v;
        }
        foreach ($_GET as $k => $v){
            $this->get[$k] = $v;
        }
        foreach ($_REQUEST as $k => $v){
            $this->request[$k] = $v;
        }
    }
    // 通用的请求数据返回
    public function input($key){
        if (key_exists($key,$this->get)){
            return $this->get[$key];
        }
        if (key_exists($key,$this->post)){
            return $this->post[$key];
        }
        if (key_exists($key,$this->request)){
            return $this->request[$key];
        }
        return null;
    }
    // get请求数据返回
    public function get($key){
        if (key_exists($key,$this->get)){
            return $this->get[$key];
        }
        return null;
    }
    // post请求数据返回
    public function post($key){
        if (key_exists($key,$this->post)){
            return $this->post[$key];
        }
        return null;
    }
    // request 聚合请求数据返回
    public function request($key){
        if (key_exists($key,$this->request)){
            return $this->request[$key];
        }
        return null;
    }
}
