<?php
/**
 * author: jeremy
 * DateTime: 2017/4/6 10:57
 * Description:
 */ 
return [
    /*
    * 数据库配置
    *主从分离则db_dsn配置以#分隔，默认第一个为主，其他为从
    * 默认用户名、密码均相同db_username、db_password配置不支持#分隔
    */
    'db_dsn'=>'mysql:host=127.0.0.1;port=3306;dbname=test;#mysql:host=127.0.0.1;port=3306;dbname=test;',
    'db_username'=>'root',
    'db_password'=>'root',
];