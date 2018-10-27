<?php
/**
 * author: jeremy
 * DateTime: 2017/3/27 14:37
 * Description:
 */
namespace JframeCore;

class App extends Base {
    //系统运行时间
    private $start=0;
    private $end=0;

    public  function __construct(){
        //设置时区
        $timeZone=Config::get('App.timezone')?:'Asia/Shanghai';
        date_default_timezone_set($timeZone);
        //生成流水号
        $serial_number= uniqid('',true);
        define('SERIAL_NUMBER',$serial_number);
        //程序开始运行时间
        $this->start=microtime(true);
        //开启缓冲区
        ob_start();
        Log::log_system_info('程序启动');
    }

    public function run(){
        // 设定错误和异常处理
        register_shutdown_function([$this,'shutdownFunc']);
        set_error_handler(array($this,'errorHandler'));
        set_exception_handler(array($this,'exceptionHandler'));


        $dispatch=new Dispatch();
        $dispatch->run();

    }

    /**
     *程序结束、超时、出现异常时调用
     */
    public function shutdownFunc(){

        if(!$this->end){
            //程序结束运行时间
            $this->end=microtime(true);
            Log::log_system_info('程序结束','运行总时长：'.($this->end-$this->start).'s');
        }
        if ($e = error_get_last()) {
            switch($e['type']){
                case E_ERROR:
                case E_PARSE:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                    $str='ERROR:' . $e['message'] . ' in  ' . $e['file'] . ' on line  ' . $e['line'];
                Log::log_system_error('用户未捕获错误',$str);
                if(Config::get('App.debug')) {
                    echo $str. '<br/>';
                }
                $this->jump();
                break;
            }
        }
        //刷缓冲区
        ob_flush();
        Log::log_flush();

    }

    /**
     * 出现error时调用
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     */
    public function errorHandler($errno, $errstr, $errfile, $errline){
        $str= 'errno:'.$errno.'<br/>errstr:'.$errstr.'<br/>errfile:'.$errfile.'<br/>errline:'.$errline.'<br/>';
        if(Config::get('App.debug')){
            echo '<pre>'.$str.'</pre>';
        }
        Log::log_system_error('用户未捕获错误',$str);
        $this->jump();
    }

    /**
     * 出现异常时调用
     * @param $exception
     */
    public function exceptionHandler(\Exception $exception){
        if(Config::get('App.debug')){
            echo '<pre>';
            var_export($exception);
            echo '</pre>';
        }
        $str= 'code:'.$exception->getCode().' message:'.$exception->getMessage().' file:'.$exception->getFile().' line:'.$exception->getLine().' trace:'.$exception->getTraceAsString();
        Log::log_system_error('用户未捕获异常',$str);
        $this->jump();
    }

    /**
     * 程序中断返回信息
     * TODO
     */
    private function jump(){

        //程序结束运行时间
        if(!$this->end){
            $this->end=microtime(true);
            Log::log_system_info('程序结束','运行总时长：'.($this->end-$this->start).'s');
        }
        //刷缓冲区
        ob_flush();
        Log::log_flush();
        exit('出错了！');
    }

}
