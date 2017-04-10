<?php
/**
 * author: jeremy
 * DateTime: 2017/3/29 14:28
 * Description:全局函数
 */

if ( ! function_exists('str_starts_with')) {
    /**
     * 判断字符串是否以某个片段开头
     * @param string $str 必传参数 需要判断的字符串
     * @param string $part 必传参数 需要比较的字符串片段
     * @return bool 是则返回true，不是则返回false
     */
    function str_starts_with($str, $part)
    {
        if ($part != '' && strpos($str, $part) === 0) {
            return true;
        } else {
            return false;
        }
    }
}
if ( ! function_exists('str_ends_with')) {
    /**
     * 判断字符串是否以某个片段结尾
     * @param string $str 必传参数 需要判断字符串
     * @param string $part 必传参数 需要比较的字符串片段
     * @return bool 是则返回true，不是则返回false
     */
    function str_ends_with($str, $part)
    {
        if ((string)$part === substr($str, -strlen($part))) {
            return true;
        } else {
            return false;
        }
    }
}
if ( ! function_exists('str_contain_str')) {
    /**
     * 判断字符串是否包含另一字符串片段
     * @param string $str 必传参数 需要判断字符串
     * @param string $part 必传参数 需要比较的字符串片段
     * @return bool 是则返回true，不是则返回false
     */
    function str_contain_str($str, $part)
    {
        if ($part != '' && strpos($str, $part) !== false) {
            return true;
        } else {
            return false;
        }
    }
}
if ( ! function_exists('array_depth')) {
    /**
     * TODO 判断数组维数
     * @param array $arr
     * @return int
     */
    function array_depth($arr)
    {
        if (!is_array($arr)) {
            return 0;
        }
    }
}
if ( ! function_exists('dd')) {
    /**
     * 调试模式下输出调试信息，自动美化<br/>
     * 非调试模式不会输出即debug配置为false则不输出，防止调试信息删除不干净导致线上环境问题
     * @param $data
     * @param bool $isExit 执行完输出后是否中断程序 默认false：不中断
     */
    function dd($data,$isExit=false)
    {
        if (\JframeCore\Config::get('App.debug')) {
            if(is_array($data)||is_object($data)){
                echo '<pre>';
                print_r($data);
                echo '</pre>';
            }else{
                echo $data.'<br/>';
            }
            if($isExit){
                exit();
            }
        }
    }
}
if ( ! function_exists('I')) {
    /**
     * 获取请求参数
     * @param string|若不传默认获取所有请求参数 $key 若不传默认获取所有请求参数
     * @param mixed $default 默认值 如果没有获取到key对应的值则使用default所设置的默认值
     * @return mixed 返回值会根据$type的值进行强转后返回
     */
    function I($key='',$default=null){
        $value=\JframeCore\Paramters::getParam($key,$default);
        return $value;
    }
}
if ( ! function_exists('benchmark_timer_start')) {
    /**
     * benchmark 时间记录开始<br/>
     * 只针对debug模式有效
     */
    function benchmark_timer_start()
    {
        if (\JframeCore\Config::get('App.debug')) {
            require JFRAME_DIR . '/Benchmark/Benchmark_Timer_Class.php';
            $timer = \JframeCore\ObjPool::getObj('benchmark_timer', [], new \Benchmark_Timer_Class());
            $timer->start();
        }
    }
}
if ( ! function_exists('benchmark_timer_stop')) {
    /**
     * benchmark 时间记录结束并输出到屏幕<br/>
     * 只针对debug模式
     */
    function benchmark_timer_stop()
    {
        if (\JframeCore\Config::get('App.debug') && \JframeCore\ObjPool::objExist('benchmark_timer', [])) {
            $timer = \JframeCore\ObjPool::getObj('benchmark_timer', []);
            $timer->stop();
            $timer->display();
        }
    }
}
if ( ! function_exists('benchmark_timer_mark')) {
    /**
     * benchmark 时间记录锚点注入<br/>
     * 只针对debug模式
     * @param $markName 锚点名称
     */
    function benchmark_timer_mark($markName)
    {
        if (\JframeCore\Config::get('App.debug') && \JframeCore\ObjPool::objExist('benchmark_timer', [])) {
            $timer = \JframeCore\ObjPool::getObj('benchmark_timer', []);
            $timer->setMarker($markName);
        }
    }
}
if ( ! function_exists('benchmark_iterate')) {
    /**
     * benchmark 对单一闭包函数执行指定次数计算每次运行时间
     * @param Closure $func 闭包函数
     * @param $count 需要执行的次数
     * @internal param 需要执行的函数 $func
     */
    function benchmark_iterate(Closure $func,$count){
        if(\JframeCore\Config::get('App.debug')){
            require_once JFRAME_DIR."/Benchmark/Benchmark_Iterate.php";
            $bench = new \Benchmark_Iterate();
            $bench->run($count,$func);
            dd($bench->get());
        }
    }
}
