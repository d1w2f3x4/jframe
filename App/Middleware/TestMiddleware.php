<?php
/**
 * author: jeremy
 * DateTime: 2017/4/6 10:41
 * Description:
 */

namespace App\Middleware;


use JframeCore\IMiddleware;

class TestMiddleware implements IMiddleware
{


    public function j_before()
    {
       dd('我是前置拦截器');
    }

    public function j_after()
    {
        dd('我是后置拦截器');
    }
}