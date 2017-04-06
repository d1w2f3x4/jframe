<?php
/**
 * author: jeremy
 * DateTime: 2017/4/6 10:30
 * Description:后置拦截器接口
 */
namespace JframeCore;


interface IMiddlewareAfter
{


    /**
     * 后置拦截器 在请求方法执行后执行
     * @return mixed
     */
    public function j_after();

}