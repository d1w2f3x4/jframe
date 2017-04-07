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
        Paramters::init();
        $controller= '\App\Controllers\\'.CONTROLLER_NAME;
        $action=ACTION_NAME;
        /*
         * 判断是否有需要执行的中间件
         */
        $commonMap=Config::get('Middleware.commonMap');
        $map=Config::get('Middleware.map');
        $key=CONTROLLER_NAME.'/'.$action;
        $middleware=Config::get('Middleware.middleware');
        /*
         * 执行前置拦截方法
         */
        foreach ($commonMap as $k){
            $currentMiddleware=$middleware[$k];
            $currentObj=ObjPool::getObj($currentMiddleware);
            if(method_exists($currentObj,'j_before')){
                $currentObj->j_before();
            }
        }
        if(array_key_exists($key,$map)){
            $currentMap=$map[$key];
            foreach ($currentMap as $k){
                $currentMiddleware=$middleware[$k];
                $currentObj=ObjPool::getObj($currentMiddleware);
                if(method_exists($currentObj,'j_before')){
                    $currentObj->j_before();
                }
            }
        }

        //执行请求方法
        $controller=new $controller();
        $controller->$action();

        /*
        * 执行后置拦截方法
        */
        foreach ($commonMap as $k){
            $currentMiddleware=$middleware[$k];
            $currentObj=ObjPool::getObj($currentMiddleware);
            if(method_exists($currentObj,'j_after')){
                $currentObj->j_after();
            }
        }
        if(array_key_exists($key,$map)){
            $currentMap=$map[$key];
            foreach ($currentMap as $k){
                $currentMiddleware=$middleware[$k];
                $currentObj=ObjPool::getObj($currentMiddleware);
                if(method_exists($currentObj,'j_after')){
                    $currentObj->j_after();
                }
            }
        }

    }

}
