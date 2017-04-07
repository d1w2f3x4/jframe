<?php
/**
 * author: jeremy
 * DateTime: 2017/4/6 20:20
 * Description:参数过滤处理中间件
 */

namespace App\Middleware;


use JframeCore\IMiddlewareBefore;
use JframeCore\Paramters;

class ParamMiddleware implements IMiddlewareBefore
{

    /**
     * 对参数进程过滤处理
     */
    public function j_before()
    {
        //获取所有参数
        $allParams=Paramters::getParam();

        //如果$data的键值中含有or或者exp等字符，自动在后面加一个空格
        array_walk_recursive($allParams,function(&$value,$key){
        if(preg_match('/^(EXP|NEQ|GT|EGT|LT|ELT|OR|XOR|LIKE|NOTLIKE|NOT BETWEEN|NOTBETWEEN|BETWEEN|NOTIN|NOT IN|IN)$/i',$value)){
            $value .= ' ';
        }
        //对特殊字符进行处理
        $value=htmlspecialchars($value);
        });

        Paramters::updateAllParam($allParams);

    }


}