##### *Tiny_PHP*
###### *目前随线上实际项目开发进行功能测试和修复, 有兴趣的可以等项目完善后使用稳定版*  
##### *Global helper function :* 
```php
echo env('APPNAME','default value'); // 返回.env文件中定义的选项, 忽略大小写  
echo config('cdn_path','default value');  // 返回config/user.json 中定义的选项, 不忽略大小写
echo config('path.cdn','default value');  // 存在层级关系, 用点'.'来表示, 这个参数取出的就是, path 下的 cdn 参数  
```   
##### *Route*  
```php
Route::start_group('Session');  // start route group, after this, will auto apply this middleware

Route::get('index','IndexController/index');  // get method
// (new IndexController)->index(new Request);
Route::post('login','IndexController/login'); // post method
Route::post('admin_passwd_update','AdminController/passwd_update)->Middleware('Admin');  // apply middleware Admin (File:app/Middleware/Admin.php)

Route::end_group();  // end the route group
Route::get('member','MemberController/member_list'); // this will not apply `Session` Middleware

// in app/Middleware/Admin.php : 

namespace app\Middleware;
final class Session{
    public function middleware(){  // this function is must exits
        if (!isset($_SESSION['admin_info'])){
            redirect('admin');
        }
    }
}
```  
##### *Controller*  
```php
<?php

namespace app\Controller;

use src\tiny\DB\db;
use src\tiny\route\Request;

class IndexController
{
    public function index(Request $request){
        echo $request->input('username');
        echo config('path.cdn');
        echo env('APPNAME');
        view('index',['res'=>['ss','yy'],'name'=>'test']); // view/index.php
    }
}
```  
##### *Template*  
```html
@foreach($res as $v)  --> <?php foreach ($res as $v){ ?>
<p>{{$v}}</p>         --> <p><?php echo $v; ?></p>
@endforeach           --> <?php }; ?>
<h1>{{$name}}</h1>    --> <h1><?php echo $name; ?></h1>
@json($_SERVER)       --> <?php echo json_encode($_SERVER); ?>
@{{data.name}}        --> {{data.name}}

@raw{                 --> <?php
  var_dump($_SERVER); --> var_dump($_SERVER);
}                     --> ?>
```  
##### *DataCache*
```php
cache($name,$data); // cache a data to cache
cache($name);  // get the cache data
is_cache($name); // cache exits or not
cache_del($name);  // del a cache 
cache_clean();  // clear all cache
cache_limit($name, $start, $len);  // slice the cache data
cache_paginate($name, $num);  // pagination cache data
cache_push($name, $data);  // if the cache data is an array , that can push the data to cache file
cache_splice($name, $offset, $len);  // if the cache data is an array , that can splice a index`s child
```  
##### *AutoLoad Css/Js File*
> in static/auto_load.json file :   
```json5
{
  "admin": {  // dir view/admin can be auto load this config`s file
    "js": [
      "public/js/public.js", // the file is static/public/js/public.js
      "vue_element/vue.js",
      "vue_element/element.js",
      "admi/js/admin.js"
    ],
    "css": [
      "admi/css/admin.css",
      "public/css/reset.css",
      "public/css/public.css",
      "vue_element/element.css"
    ]
  }
}
```  
> in view/admin/index.php file :  
```html
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>管理中心</title>
    <!-- tiny_auto -->
    <link rel="stylesheet" type="text/css" href="admi/css/index.css">
    <script type="text/javascript" src="admi/js/index.js"></script>
</head>
<body>

</body>
</html>
```  
> will replace to :  
```html
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>管理中心</title>
    <link rel="stylesheet" type="text/css" href="admi/css/admin.css">
    <link rel="stylesheet" type="text/css" href="public/css/reset.css">
    <link rel="stylesheet" type="text/css" href="public/css/public.css">
    <link rel="stylesheet" type="text/css" href="vue_element/element.css">
    <script type="text/javascript" src="public/js/public.js"></script>
    <script type="text/javascript" src="vue_element/vue.js"></script>
    <script type="text/javascript" src="vue_element/element.js"></script>
    <script type="text/javascript" src="admi/js/admin.js"></script>
    <link rel="stylesheet" type="text/css" href="admi/css/index.css">
    <script type="text/javascript" src="admi/js/index.js"></script>
</head>
<body>
    
</body>
</html>
```  
> tiny_auto must add to where your want to import :  
```html
<!-- tiny_auto -->
```  
##### *2018-12-31  -- 增加日志归档, 防止单日志文件过大, 问题排查不便, 增加基于Python的命令行工具, 目前支持创建控制器 --*
##### *2018-12-29  -- 增加数据库和缓存的数据分页功能, 待进一步完善 --*
##### *2018-12-21  -- 增加路由中间件, 接口访问安全度提升, 将原有url检测等迁移到了中间件 --*  
##### *2018-12-19  -- 更改了导致重定向和跳转错误的$_SERVER['SERVER_NAME'] --*  
##### *2018-12-15  -- 增加文件数据缓存系统 --*
##### *2018-12-14  -- 增加静态资源自动导入 --*
##### *2018-12-13  -- 修复若干错误, DB类重写 --*
##### *2018-12-12  -- 建立模板引擎, 模板渲染 --*  
##### *2018-12-07  -- 建立基本架构, 添加配置文件助手函数, 增加安全检测, 请求过滤 --*  
