<?php
/**
 * author: jeremy
 * DateTime: 2017/4/10 20:41
 * Description:
 */

namespace App\Middleware;


use JframeCore\IMiddleware;

class BenchmarkMiddleware implements IMiddleware
{

    /**
     * 后置拦截器 在请求方法执行后执行
     * @return mixed
     */
    public function j_after()
    {
        benchmark_timer_stop();
    }

    /**
     * 前置拦截器 在请求方法之前执行
     * @return mixed
     */
    public function j_before()
    {
        benchmark_timer_start();
    }
}