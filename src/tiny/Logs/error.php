<?php
/**
 * User: zjz
 * File: error.php
 * Date: 2018/12/10
 * Time: 13:10
 */
namespace src\tiny\Logs;
final class Error_log{
    private $error_text;
    private $times;
    private $client_ip;
    private $error;
    public function __construct($error)
    {
        $this->times = date('Y-m-d H:i:s',time());
        $this->client_ip = $_SERVER['REMOTE_ADDR'];
        $this->error = $error;
        $this->write();
    }
    private function write(){
        $error_log = fopen(App.'storage/logs/tiny_error.log','a+');
        $log = '[ '.$this->times.' ip : '.$this->client_ip .' ] : '.$_SERVER['REQUEST_METHOD'] .' '.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].' [ error : '.$this->error.' ]';
        $this->error_text = $log;
        fwrite($error_log,$log."\n");
        fclose($error_log);
        return true;
    }

    public function get_log(){
        return $this->error_text;
    }
}