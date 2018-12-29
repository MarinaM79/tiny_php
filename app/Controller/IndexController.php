<?php
/**
 * User: zjz
 * File: AdminController.php
 * Date: 2018/12/12
 * Time: 10:36
 */


namespace app\Controller;


use src\tiny\DB\DB;
use src\tiny\route\Request;

class IndexController
{
    public function index(Request $request){
        view('index.index',[]);
    }
}