<?php
/**
 * User: zjz
 * File: Main.php
 * Date: 2018/12/7
 * Time: 15:54
 */
namespace src\tiny;
// load the tiny safe system
use src\tiny\DB\DB;
use src\tiny\Logs\Error_log;
use src\tiny\Middleware\Middleware;
use function src\tiny\route\get_action;
use src\tiny\route\route;
use function src\tiny\route\route_exits;
use src\tiny\route\Request;
use src\tiny\route\route_load;

// load the route list
require_once App.'src/tiny/route/auto_load.php';

// load the log system
require_once App.'src/tiny/Logs/log_auto.php';

// load the database helper
require_once App.'src/tiny/DB/db_auto.php';

// load user include module
require_once App.'src/utils/auto_load.php';

// load Request
require_once App.'src/tiny/route/Request.php';

//  get url action
$urls = get_action($_SERVER['REQUEST_URI']);

//  load route table register
require_once App . 'src/tiny/route/route_load.php';

//  load route middleware
require_once App.'src/tiny/Middleware/Middleware.php';
Middleware::load_middleware($urls[0]);

//  start load controller
$urls[1] && route_load::load_needs($urls[1]);

//  url not in route list
if (!$urls){
    tiny_bad('你访问的页面不存在');
}
