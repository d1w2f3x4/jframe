<?php
/**
 * author: jeremy
 * DateTime: 2017/4/6 10:30
 * Description:拦截器接口（包含前置拦截器、后置拦截器）
 */
namespace JframeCore;


interface IMiddleware extends IMiddlewareBefore , IMiddlewareAfter
{

}