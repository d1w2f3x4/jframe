<?php
/**
 * author: jeremy
 * DateTime: 2017/4/6 10:58
 * Description:中间件配置
 */
return [
    /*
     * 配置所有方法均要使用的中间件
     * 执行优先级commonMap高于map
     */
    'commonMap'=>[1],
    /*
     * 配置方法需要使用的中间件
     */

    'map'=>[
        'Index/test'=>[2],
    ],
    /*
     * 中间件配置
     * 格式：数字 => 中间件完全限定名
     * 注意key从1开始
     */
    'middleware'=>[
        //过滤请求参数中间件
        1=>'App\Middleware\ParamMiddleware',
        //测试中间件
        2=>'App\Middleware\TestMiddleware',
    ],
];
