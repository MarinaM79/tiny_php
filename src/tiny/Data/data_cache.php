<?php
/**
 * User: zjz
 * File: data_cache.php
 * Date: 2018/12/15
 * Time: 9:25
 * Description: File Data Cache System.
 */
namespace src\tiny\Data;
use src\tiny\Logs\Error_log;

class data_cache{

    public static function cache_data($name,$data){  //  cache a data to file
        $cache_file = fopen(App.'storage/cache/data_cache/'.$name.'.json','w+');
        flock($cache_file,LOCK_EX);
        $data = json_encode($data);
        fwrite($cache_file,$data);
        flock($cache_file,LOCK_UN);
        fclose($cache_file);
        return true;
    }

    public static function is_cache($name){  //  return cache exits or not
        if (is_file(App.'storage/cache/data_cache/'.$name.'.json')){
            return true;
        }else{
            return false;
        }
    }

    public static function cache_get($name){  //  return cache data
        try{
            $data = file_get_contents(App.'storage/cache/data_cache/'.$name.'.json');
            $data = json_decode($data,true);
            return $data;
        }catch (\Exception $e){
            new Error_log($e);
            exit('File Not Found : '.App.'storage/cache/data_cache/'.$name.'.json');
        }
    }

    public static function cache_del($name){  //  drop data cache
        if (is_file(App.'storage/cache/data_cache/'.$name.'.json')){
            unlink(App.'storage/cache/data_cache/'.$name.'.json');
            return true;
        }else{
            return false;
        }
    }

    public static function cache_clean(){  //  drop all data cache
        chdir(App.'storage/cache/data_cache/');
        $cache_list = glob('*.json');
        foreach ($cache_list as $cache){
            unlink(App.'storage/cache/data_cache/'.$cache);
        }
    }

    public static function cache_limit($name, $start, $len){  // slice the cache data
        if (is_file(App.'storage/cache/data_cache/'.$name.'.json')){
            $data = json_decode(file_get_contents(App.'storage/cache/data_cache/'.$name.'.json'),true);
            if (!is_array($data)) return null;
            $res = [];
            for($i = $start; $i < ($start+$len); ++$i){
                $res[] = $data[$i];
            }
            return $res;
        }else{
            return null;
        }
    }

    public static function cache_paginate($name, $num){  // pagination cache data
        if (is_file(App.'storage/cache/data_cache/'.$name.'.json')){
            $start = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
            $start = ($start-1) * $num;
            $data = json_decode(file_get_contents(App.'storage/cache/data_cache/'.$name.'.json'),true);
            if (!is_array($data)) return null;
            $res = [];
            for($i = $start; $i < ($start+$num); ++$i){
                $res[] = $data[$i];
            }
            return $res;
        }else{
            return null;
        }
    }

    public static function cache_push($name, $data){  // if the cache data is an array , that can push the data to cache file
        if (is_file(App.'storage/cache/data_cache/'.$name.'.json')){
            $res = json_decode(file_get_contents(App.'storage/cache/data_cache/'.$name.'.json'),true);
            if (is_array($res)){
                array_push($res, $data);
                $f = fopen(App.'storage/cache/data_cache/'.$name.'.json','w+');
                flock($f,LOCK_EX);
                fwrite($f,json_encode($res));
                flock($f,LOCK_UN);
                fclose($f);
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public static function cache_splice($name, $offset, $len = 1){  // if the cache data is an array , that can splice a index`s child
        if (is_file(App.'storage/cache/data_cache/'.$name.'.json')){
            $res = json_decode(file_get_contents(App.'storage/cache/data_cache/'.$name.'.json'),true);
            if (is_array($res)){
                $res = array_slice($res, $offset, $len);
                $f = fopen(App.'storage/cache/data_cache/'.$name.'.json','w+');
                flock($f,LOCK_EX);
                fwrite($f,json_encode($res));
                flock($f,LOCK_UN);
                fclose($f);
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
