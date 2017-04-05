<?php
/**
 * author: jeremy
 * DateTime: 2017/3/28 11:54
 * Description:
 */
namespace JframeCore;
class Dispatch extends Base {
    public function run(){
        //设置请求参数
        Paramters::setParam();
        $controller= '\App\Controllers\\'.CONTROLLER_NAME;
        $action=ACTION_NAME;

        $controller=new $controller();
        //判断方法是否有前置方法，如果有则先执行前置方法
        $before_action='before_'.$action;
        if(method_exists($controller,$before_action)){
            $controller->$before_action();
        }
        //执行请求方法
        $controller->$action();

        //判断方法是否有后置方法，如果有则先执行后置方法
        $after_action='after_'.$action;
        if(method_exists($controller,$after_action)){
            $controller->$after_action();
        }
    }

}
