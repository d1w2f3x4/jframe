<?php
/**
 * author: jeremy
 * DateTime: 2017/3/29 14:58
 * Description:请求参数处理类
 */

namespace JframeCore;


class Paramters
{
    private static $jParam;
    /**
     * 设置请求参数
     * @throws \Exception
     */
    public static function init(){
        $post=$_POST?:[];
        $get=$_GET?:[];
        self::$jParam=array_merge($get,$post);
        $pathinfo=$_SERVER['REQUEST_URI'];
        //如果有？则去掉问号后面的
        $pos=strpos($pathinfo,'?');
        $pos===false?:$pathinfo=substr($pathinfo,0,$pos);
        if(array_key_exists($pathinfo,self::$jParam)){
            unset(self::$jParam[$pathinfo]);
        }
        //解析活的controller、action及其他参数
        $pathinfoArr=explode('/',$pathinfo);
        if(count($pathinfoArr)<3 || empty($pathinfoArr[1]) || empty($pathinfoArr[2])){
            throw new \Exception('路由错误!');
        }
        //将控制器名 方法名设为常量
        //控制器首字母大写
        define('CONTROLLER_NAME',ucfirst($pathinfoArr[1]));
        define('ACTION_NAME',$pathinfoArr[2]);

    }

    /**
     * 获取请求参数
     * @param 若不传默认获取所有请求参数|string $key 若不传默认获取所有请求参数
     * @param mixed $default 默认值 如果没有获取到key对应的值则使用default所设置的默认值
     * @return mixed
     */
    public static function getParam($key='',$default=null){
        if($key){
            return array_key_exists($key,self::$jParam)?self::$jParam[$key]:$default;
        }else{
            return self::$jParam;
        }
    }

    /**
     * 修改参数
     * @param $key
     * @param $value
     */
    public static function updateParam($key,$value){
        self::$jParam[$key]=$value;
    }

    /**
     * 修改所有参数
     * @param $arr
     */
    public static function updateAllParam($arr){
        self::$jParam=$arr;
    }

}