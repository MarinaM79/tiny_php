<?php
/**
 * User: zjz
 * File: File_Upload.php
 * Date: 2018/12/18
 * Time: 11:38
 */


namespace app\utils;


use src\tiny\Logs\Error_log;

class File_Upload
{
    //  return is uploaded file, true or false
    public static function is_upload($name){  // 判断文件是否已上传
        if (!isset($_FILES[$name])){
            return false;
        }
        if (!isset($_FILES[$name]['tmp_name'])||empty($_FILES[$name]['tmp_name'])){
            return false;
        }
        if (is_uploaded_file($_FILES[$name]['tmp_name'])){
            return true;
        }else{
            return false;
        }
    }

    //  upload file to server
    public static function upload($name,$path,$size){  //  上传文件
       if (File_Upload::is_upload($name)==false){ // 是否已上传
           return false;
       }
       if (File_Upload::verify($name,$size)==false){ // 文件是否合法
           return false;
       }
       try{
           $file_name = $_FILES[$name]['name'];
           $file_type = explode('.',$file_name);
           $file_type = $file_type[count($file_type)-1];
           $file_name = sha1(date('Y-m-d H:i:s',time()).rand(1000,9999)).'.'.$file_type;
           if (!is_dir($path)){
               exec('mkdir -p '.$_SERVER['DOCUMENT_ROOT'].$path);
           }
           move_uploaded_file($_FILES[$name]['tmp_name'],$_SERVER['DOCUMENT_ROOT'].$path.'/'.$file_name);
           return File_Upload::url($path.'/'.$file_name);  // return the file url
       }catch (\Exception $e){
           new Error_log($e);
           return false;
       }
    }

    //  return the file is verify
    public static function verify($name,$size){  // 验证文件是否合法
        $type = ['png','jpg','jpeg','gif','html','htm','php'];
        $file_name = $_FILES[$name]['name'];
        $file_type = explode('.',$file_name);
        $file_type = $file_type[count($file_type)-1];
        if (in_array($file_type,$type)){
            if ($_FILES[$name]['size']<=$size*1024*1024){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    //  return the file of url
    public static function url($path){
        $http = $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
        return $http.$_SERVER['HTTP_HOST'].$path;
    }

    // delete old image file
    public static function del($url){
        $http = $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
        $domain = $http.$_SERVER['HTTP_HOST'];
        $urls = substr($url,strlen($domain));
        $urls = $_SERVER['DOCUMENT_ROOT'].$urls;
        is_file($urls)&&unlink($urls);
        return $urls;
    }
}