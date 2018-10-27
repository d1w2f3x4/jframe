<?php

/**
 * author: jeremy
 * DateTime: 2017/3/27 16:35
 * Description: 配置文件操作工具类
 */
namespace JframeCore;
Class Config  {
    //缓存配置文件
    private static $cacheConfig=[];

    /**
     * 获取配置信息
     * @param string $param 需要获取的配置，格式“文件名.key” 示例：App.host 表示获取Config目录下App.php配置文件中key为host的值<br/>
     * 如果只传文件名表示获取所有配置
     * @param bool $refresh 是否刷新获取，默认false：从缓冲区获取（如果配置文件使用过默认会缓存起来） true:重新加载配置并获取<br/>
     *   ====notice $refresh 设置为true会导致使用set设置的值丢失====
     * @return mixed
     */
    public static function get($param,$refresh=false){
        $arr=explode('.',$param);
        $configFileName=$arr[0];
        $key=count($arr)==2?$arr[1]:'';
        if($refresh){
            $cacheArr='';
        }else{
            $cacheArr= array_key_exists($configFileName,self::$cacheConfig)?self::$cacheConfig[$configFileName]:'';
        }

        if($cacheArr){
            return $key?array_key_exists($key,$cacheArr)? $cacheArr[$key]:'':$cacheArr;
        }else{
            $config=include CONFIG_DIR.$configFileName.'.php';
            self::$cacheConfig[$configFileName]=$config;
            return $key?array_key_exists($key,$config)?$config[$key]:'':$config;
        }

    }
    /**
     * 设置配置信息
     * @param string $param 需要设置的配置，格式“文件名.key” 示例：App.host 表示设置Config目录下App.php配置文件中key为host的值
     * @param mixed $value 需要设置的值
     */
    public static function set($param,$value){
        $arr=explode('.',$param);
        $configFileName=$arr[0];
        $key=$arr[1];
        $cacheArr=array_key_exists($configFileName,self::$cacheConfig)?self::$cacheConfig[$configFileName]:'';
        if($cacheArr){
            self::$cacheConfig[$configFileName][$key]=$value;
        }else{
            $config=include CONFIG_DIR.$configFileName.'.php';
            self::$cacheConfig[$configFileName]=$config;
            self::$cacheConfig[$configFileName][$key]=$value;
        }
    }
}