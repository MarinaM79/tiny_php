<?php
 //  框架加载文件, 控制框架加载
namespace src\tiny;
use src\tiny\config\env_load;
use src\tiny\config\load;

final class auto_load
{
    public function __construct()  // load env
    {
        require_once App.'src/tiny/Helper/auto_load.php';
        require_once App.'src/tiny/config/env_load.php';
        $env = new env_load();
        $env->Load();
        $this->config_load();
    }

    private function config_load(){  // load config
        require_once App.'src/tiny/config/load.php';
        new load();
        require_once App.'src/tiny/Main.php';  // this load file time is over, include Main.php to goon the load system service
    }
}
new auto_load();
