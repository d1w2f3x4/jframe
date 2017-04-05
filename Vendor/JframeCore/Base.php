<?php
/**
 * author: jeremy
 * DateTime: 2017/3/27 16:53
 * Description:所有类的基类
 */
namespace JframeCore;
class Base{
    //请求参数
    protected $jparam;


    public function __construct()
    {
        //引入全局函数
        include JFRAME_DIR.'/Helper.php';
    }

}