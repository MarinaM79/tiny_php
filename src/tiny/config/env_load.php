<?php
/**
 * User: zjz
 * File: config_load.php
 * Date: 2018/12/7
 * Time: 10:02
 */

namespace src\tiny\config;
final class env_load
{
    public $env;
    public function __construct()  //  read the env file
    {
        $this->env = file_get_contents(App.'.env');
        preg_match_all('/[\w]*[=]{1}[\.\w\/\d]*/m',$this->env,$this->env);
        $this->env = $this->env[0];
    }

    public function Load(){  //  decode the env file
        $str_arr = $this->env;
        $arr = array();
        foreach ($str_arr as $v){
            preg_match('/[\w]*[=]{1}/',$v,$item_index);
            $item_index = strtolower(substr($item_index[0],0,strlen($item_index[0])-1));
            preg_match('/[=]{1}[\.\w\/\d]*/',$v,$item_val);
            $item_val = substr($item_val[0],1);
            $arr[$item_index] = $item_val;
        }
        $this->env = $arr;
        $this->error_level();
    }

    private function error_level(){ // set error_report level
        if ($this->env['debug']=='true'){
            define('DEBUG',true);
        }else{
            define('DEBUG',false);
        }
        $this->config();
    }

    private function config(){  //  set the global config item
      define('ENV',$this->env);
    }
}
