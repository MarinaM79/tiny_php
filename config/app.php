<?php
/**
 * User: zjz
 * File: app.php
 * Date: 2018/12/7
 * Time: 11:20
 * Notice: Do not modify this class return array's exits index,
 *        if you want add your's config item,
 *        please add to /config/user.json file and use the global config function to get it ,
 *        or add to /.env file and use the global env function to get it .
 */


namespace config;
final class app
{
   public $config;
   public function __construct(){
       $this->config = [
               'mysql' =>
                  [
                   'datatype' => env('DBTYPE','mysql'),
                   'dbuser'=> env('DBUSER','root'),
                   'dbpassword' => env('DBPASSWORD','root'),
                   'dbhost' => env('DBHOST','localhost'),
                   'dbport' => env('DBPORT','3306'),
                   'database' => env('DATABASE','tiny')
                  ],
               'date_time_zone' => 'PRC',
               'redis'=>
                    [
                       'host' => env('REDIS_HOST','localhost'),
                       'port' => env('REDIS_PORT','6379'),
                    ]
           ];
   }
}