<?php
/**
 * author: jeremy
 * DateTime: 2017/4/6 10:30
 * Description:前置拦截器接口
 */
namespace JframeCore;


interface IMiddlewareBefore
{
    /**
     * 前置拦截器 在请求方法之前执行
     * @return mixed
     */
    public function j_before();


}