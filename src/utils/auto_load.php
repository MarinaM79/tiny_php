<?php
/**
 * User: zjz
 * File: auto_load.php
 * Date: 2018/12/12
 * Time: 9:28
 */
$utils_list = [];
chdir(App.'src/utils/');
$utils_list = glob('*.php');
foreach ($utils_list as $util){
    require_once App.'src/utils/'.$util;
}
