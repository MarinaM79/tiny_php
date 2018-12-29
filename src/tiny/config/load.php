<?php
/**
 * User: zjz
 * File: load.php
 * Date: 2018/12/7
 * Time: 11:14
 */


namespace src\tiny\config;


use config\app;

final class load
{
    private $file_list;
    public function __construct()  // load config file , and define the database options
    {
        chdir(App.'config');
        $this->file_list = glob('*.json');
        require_once App.'config/app.php';
        $app_config = new app();
        $app_config = $app_config->config;
        date_default_timezone_set($app_config['date_time_zone']);
        define('MYSQL_HOST',$app_config['mysql']['dbhost']);
        define('MYSQL_PORT',$app_config['mysql']['dbport']);
        define('MYSQL_USER',$app_config['mysql']['dbuser']);
        define('MYSQL_DATABASE',$app_config['mysql']['database']);
        define('MYSQL_PASSWORD',$app_config['mysql']['dbpassword']);
        define('REDIS_HOST',$app_config['redis']['host']);
        define('REDIS_PORT',$app_config['redis']['port']);
        $this->load();
    }

    private function load(){
        foreach ($this->file_list as $v){
            $config = file_get_contents(App.'config/'.$v);
            $config = json_decode($config,true);
            define('CONFIG',$config);   //  define the global config
        }
    }
}
