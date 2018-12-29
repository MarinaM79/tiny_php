<?php
/**
 * User: zjz
 * File: index.php
 * Date: 2018/12/7
 * Time: 9:47
 */
namespace boot;
 // define the App Path
define('App',__DIR__.'/../');
 // define the Config Path
define('Config',__DIR__.'/../config/');
 // define the View Path
define('View',__DIR__.'/../view/');
 // start the session
session_start();
 // start load config and tiny
require_once __DIR__.'/../src/tiny/auto_load.php';
