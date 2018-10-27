<?php
/**
 * author: jeremy
 * DateTime: 2017/3/27 14:21
 * Description:
 */
/*
 * 配置需要自动加载的目录
 */
$autoloadConf= include ROOT_DIR . '/Config/SysConfig/AutoloadConfig.php';
$include_dir = $autoloadConf['common'];

//设置自动加载目录
set_include_path(get_include_path() . PATH_SEPARATOR .implode(PATH_SEPARATOR, $include_dir));
/**
 * 自动加载类库
 * @param string $class 类名
 */
function auto_load_class($class = '')
{
    $path = $class . '.php';
    /*
     * 当$path不是文件时，可能是命名空间与文件目录不一致
     * 尝试通过映射查找引入
     */
    if(is_file($path)){
        include_once($path);
    }else{
        $autoloadConf= include '../Config/SysConfig/AutoloadConfig.php';
        $map=$autoloadConf['map'];
        if($map){
            //最后一个\处截取
            $k=substr($path, 0,stripos($path, '\\'));
            $name=substr($path, stripos($path, '\\'));
            $v=array_key_exists($k,$map)?$map[$k]:'';
            if($v){
                $realName=$v.$name;
                include_once($realName);
            }
        }

    }
}
//spl注册自动加载
spl_autoload_register('auto_load_class');
