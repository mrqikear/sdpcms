<?php
/**
 * Created by PhpStorm.
 * User: mrqi
 * Date: 2020/12/18
 * Time: 16:54
 */

namespace common\utils;

class FileUtil{


    public static function scanFile($dir){

        //1、首先先读取文件夹
        $temp=scandir($dir);
        static  $result =[];
        //遍历文件夹
        foreach($temp as $v){
            $path=$dir.'/'.$v;

            if(is_dir($path)){//如果是文件夹则执行
                if($v=='.' || $v=='..'){//判断是否为系统隐藏的文件.和..  如果是则跳过否则就继续往下走，防止无限循环再这里。
                    continue;
                }
                self::scanFile($path);//因为是文件夹所以再次调用自己这个函数，把这个文件夹下的文件遍历出来
            }else{
                 $res = substr($path,strripos($path,"controllers")+strlen("controllers"));
                 #$res=ltrim($res,'/');
                 #$res=ltrim($res,'/');
                 $result[] =  $name = str_replace('.php', '', $res);;
            }

        }

        return $result;
    }


}