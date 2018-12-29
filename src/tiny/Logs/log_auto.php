<?php
/**
 * User: zjz
 * File: log_auto.php
 * Date: 2018/12/7
 * Time: 17:31
 * Notice : AutoLoad The Log System
 */
namespace src\tiny\Logs;
chdir(App.'src/tiny/Logs/');
$log_system = glob('*.php');
foreach ($log_system as $v){
    require_once App.'src/tiny/Logs/'.$v;
    // TODO: the Logs folder needs complate the log system
}
