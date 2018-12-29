<?php
/**
 * User: zjz
 * File: web.php
 * Date: 2018/12/7
 * Time: 9:54
 * Description: web端路由表 , ajax 接口采用 / 来分割请求 , web 页请使用 _ 来分割 , 否则可能导致静态资源访问异常
 */
namespace routes;

use src\tiny\route\Request;
use src\tiny\route\Route;

Route::get('index','IndexController/index');
