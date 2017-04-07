<?php
/**
 * author: jeremy
 * DateTime: 2017/3/27 14:24
 * Description:
 */
return [
    //命名空间与目录结构一致时的自动加载配置
    //例如：App/Controllers/TestController.php 使用的是App/Controllers命名空间
    'common'=>[
        ROOT_DIR.'/App/Controllers',
        ROOT_DIR.'/App/Models',
        ROOT_DIR.'/Lib',
    ],
    //命名空间与目录结构不一致时的自动加载映射配置
    //例如：App/Controllers/TestController.php 使用的是App命名空间
    'map'=>[
        //因为入口文件在Public目录下所以注册命名空间时目录名称以index.php所在目录为基准即以Public目录为基准
        //注册App命名空间
        'App'=>ROOT_DIR.'/App',
        'Vendor'=>ROOT_DIR.'/Vendor',
        //注册JframeCore命名空间
        'JframeCore'=>ROOT_DIR.'/Vendor/JframeCore',
    ],
];
