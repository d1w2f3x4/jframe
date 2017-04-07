<?php
/**
 * author: jeremy
 * DateTime: 2017/3/28 20:09
 * Description:模型基类
 */
namespace JframeCore;
use JframeCore\Driver\PdoDriver;

class BaseModel extends PdoDriver {
    public function __construct($dsn='',$username='',$password='',$options=[])
    {
        parent::__construct($dsn?:Config::get('Db.db_dsn'),$username?:Config::get('Db.db_username'),$password?:Config::get('Db.db_password'),$options);
    }

    function __destruct()
    {
        parent::close();
    }

}
