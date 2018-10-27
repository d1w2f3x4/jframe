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
        self::$jParam=$post;
        $pathinfo=$_SERVER['REQUEST_URI'];

        //解析获得controller、action及其他参数
        $pathinfoArr=explode('/',$pathinfo);
        if(empty($pathinfoArr[1])){
            $pathinfoArr[1]=Config::get('App.default_controller')?:'Index';
        }
        if (empty($pathinfoArr[2])){
            $pathinfoArr[2]=Config::get('App.default_action')?:'index';
        }
        //将控制器名 方法名设为常量
        //控制器首字母大写
        define('CONTROLLER_NAME',ucfirst($pathinfoArr[1]));
        define('ACTION_NAME',$pathinfoArr[2]);
        $pathinfoArr=array_slice($pathinfoArr,3);
        $count=count($pathinfoArr);
        for ($i = 0; $i <= $count; $i += 2) {
            if (empty($pathinfoArr[$i]) || empty($pathinfoArr[$i + 1])) {
                continue;
            }
            self::$jParam[$pathinfoArr[$i]]=$pathinfoArr[$i+1];

        }

    }

    /**
     * 获取请求参数
     * @param string $key 若不传默认获取所有请求参数
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