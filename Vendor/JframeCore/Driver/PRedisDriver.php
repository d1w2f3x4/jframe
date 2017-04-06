<?php
/**
 * author: jeremy
 * DateTime: 2017/4/6 16:11
 * Description: redis驱动类
 */

namespace JframeCore\Driver;


use JframeCore\Base;
use JframeCore\ObjPool;
use Predis\Autoloader;
use Predis\Client;

class PRedisDriver extends Base
{
    /**
     * 获取predis对象
     * @return Predis\Client|Client
     */
    public static function getClient($dsn){
        if(ObjPool::objExist('Predis\Client',$dsn)){
            return ObjPool::getObj('Predis\Client',$dsn);
        }else{
            include VENDOR_DIR.'/Predis/Autoloader.php';
            Autoloader::register();
            return ObjPool::getObj('Predis\Client',$dsn,new Client($dsn));
        }
    }

}