<?php
/**
 * User: zjz
 * File: url.php
 * Date: 2018/12/12
 * Time: 12:54
 */

//  dir and file helper function`s -------------------------------------------------------------------------------------/

function route($url){  //  return route_rule to url
    return $_SERVER['HTTP_HOST'].$url;
}

function static_path(){  //  return static path
    return $_SERVER['HTTP_HOST'];
}

// Template (view)`s function ------------------------------------------------------------------------------------------/

function view_path(){  // return view path
    $url = explode('/',App);
    if (preg_match('/.*\.*/',$url[0])){
        $url = explode('\\',App);
    }
    $path = '';
    foreach ($url as $k => $v){
        if ($k!=(count($url)-1))$path.=$v.'/';
    }
    if ($path=='')$path=$url[0];
    return $path.'view/';
}

function view_cache_path(){  //  return view cache path
    $url = explode('/',App);
    if (preg_match('/.*\.*/',$url[0])){
        $url = explode('\\',App);
    }
    $path = '';
    foreach ($url as $k => $v){
        if ($k!=(count($url)-1))$path.=$v.'/';
    }
    if ($path=='')$path = $url[0];
    return $path.'storage/cache/view_cache/';
}

//  template
function view($view,$data){  // return view to browser
    $load_tag = $view;
    $view = explode('.',$view);
    chdir(App.'view/');
    $GLOBALS['data'] = $data;
    require_once App.'src/tiny/view/view_compile.php';
    require_once App.'src/tiny/view/view_render.php';
    if (is_dir(view_path().$view[0])){
        $caches = view_cache([$view[0],$view[1]]);
        if ($caches!=false&&!DEBUG)render($caches.'.php');
        if (!$caches||DEBUG)compile(view_path().$view[0].'/'.$view[1].'.php','dir',$load_tag);
    }else{
        $caches = view_cache($view[0]);
        if ($caches!=false&&!DEBUG)render($caches.'.php');
        if (!$caches||DEBUG)compile(view_path().$view[0].'.php','single',$load_tag);
    }
}

function view_cache($view){  //  return view_cache exits(true | false)
    if (is_array($view)){
        if (is_file(view_cache_path().$view[0].'/'.sha1($view[1].'.php').'.php')){
            return view_cache_path().$view[0].'/'.sha1($view[1].'.php');
        }else{
            return false;
        }
    }else{
        if (is_file(view_cache_path().sha1($view.'.php').'.php')){
            return view_cache_path().sha1($view.'.php');
        }else{
            return false;
        }
    }
}

function view_clean($path = App.'storage/cache/view_cache/'){  //  clear the view cache
    chdir($path);
    $view_cache = glob('*');
    foreach ($view_cache as $v){
        if (is_file($path.$v)){
            unlink($path.$v);
        }else{
            view_clean($path.$v.'/');
        }
    }
    return true;
}

// Config and Php scripts Load function --------------------------------------------------------------------------------/

function auto_load($dir){  // auto_load the dir`s php file
    chdir(App.$dir.'/');
    $list = glob('*.php');
    foreach ($list as $v){
        require_once App.$dir.'/'.$v;
    }
}

function config($config,$default = ''){  // set the global config function
    $config = explode('.',$config);
    $len = count($config);
    $conf = CONFIG;
    for($i = 0; $i < $len;++$i){
        $conf = !is_string($conf) && key_exists($config[$i],$conf) ? $conf[$config[$i]] : $default;
        if ($conf == $default) return $default;
    }
    return $conf;
}

function env($config, $default = ''){  //  set the global env function
    $res = key_exists(strtolower($config),ENV) ? ENV[strtolower($config)] : $default;
    return $res;
}

// Navigate`s function -------------------------------------------------------------------------------------------------/

function redirect($to,$is_display = false,$msg=''){  //  redirect to a route
    if ($is_display){
        tiny_bad($msg);
        echo '<script>setTimeout(function(){window.location.href="/'.$to.'";},2000)</script>';
    }else{
        header('location:http://'.$_SERVER['HTTP_HOST'].'/'.$to);
    }
}

function tiny_bad($msg='Bad Request'){
    $log_text = "<h3 style='margin: 0 auto;text-align: center'>".$msg."</h3><hr/><p style='margin: 0 auto;text-align: center;font-size: 14px'>Tiny_PHP</p>";
    require_once App.'src/tiny/Logs/error.php';
    new src\tiny\Logs\Error_log($msg);
    echo $log_text;
}

function back($msg){
    exit('
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<title>提示</title>
</head>
<body>
<p>'.$msg.'</p>
<script>
   setTimeout(function() {
     window.history.go(-1);
   },2000);
</script>
</body>
</html>');
}

// Data cache function`s -----------------------------------------------------------------------------------------------/

require_once App.'src/tiny/Data/data_cache.php';
function cache($name,$data = []){
    if ($data!=[]){
        \src\tiny\Data\data_cache::cache_data($name,$data);
    }else{
        if (\src\tiny\Data\data_cache::is_cache($name)){
            return \src\tiny\Data\data_cache::cache_get($name);
        }else{
            return null;
        }
    }
    return null;
}

function cache_del($name){ // delete cache
    if (\src\tiny\Data\data_cache::is_cache($name)){
        \src\tiny\Data\data_cache::cache_del($name);
        return true;
    }else{
        return false;
    }
}

function cache_clean(){  // delete all cache
    \src\tiny\Data\data_cache::cache_clean();
}

function is_cache($name){
    return \src\tiny\Data\data_cache::is_cache($name);
}

// return the limited cache data
function cache_limit($name, $start, $len){
    return \src\tiny\Data\data_cache::cache_limit($name, $start, $len);
}

// return the pagination cache data
function cache_paginate($name, $num){
    return \src\tiny\Data\data_cache::cache_paginate($name, $num);
}

// push a data to cache
function cache_push($name, $data){
    return \src\tiny\Data\data_cache::cache_push($name, $data);
}

// splice a data , don`t return spliced cache data , return the splice result (true or false)
function cache_splice($name, $offset, $len = 1){
    return \src\tiny\Data\data_cache::cache_splice($name, $offset, $len);
}