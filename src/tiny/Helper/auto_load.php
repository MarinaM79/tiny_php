<?php
/**
 * User: zjz
 * File: auto_load.php
 * Date: 2018/12/12
 * Time: 12:53
 */
$helper_list = [];
chdir(App.'src/tiny/Helper/');
$helper_list = glob('*.php');
foreach ($helper_list as $v){
    require_once App.'src/tiny/Helper/'.$v;
}
