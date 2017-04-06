<?php
/**
 * author: jeremy
 * DateTime: 2017/3/28 10:11
 * Description:
 */
namespace JframeCore;
use JframeCore\Base;
use JframeCore\Config;

class Log extends Base{
    private static $buffer=[];
    /**
     * info级别日志记录<br/>
     * 仅在log_info配置为true时记录
     * @param $message 日志简述
     * @param $data 日志内容
     * @param string $filename 日志文件名 不传则默认以日期命名
     */
    public static function log_info($message,$data,$filename=''){
        if(Config::get('App.log_info')){
            self::log_write($message,$data, 'info',$filename);
        }
    }
    /**
     * sql级别日志记录<br/>
     * 仅在log_sql配置为true时记录
     * @param $message 日志简述
     * @param $data 日志内容
     * @param string $filename 日志文件名 不传则默认以日期命名
     */
    public static function log_sql($message,$data,$filename=''){
        if(Config::get('App.log_sql')){
            self::log_write($message,$data, 'sql',$filename);
        }
    }
    /**
     * debug级别日志记录<br/>
     * 仅在debug模式下记录 即配置debug为true
     * @param $message 日志简述
     * @param $data 日志内容
     * @param string $filename 日志文件名 不传则默认以日期命名
     */
    public static function log_debug($message,$data,$filename=''){
        if(Config::get('App.debug')){
            self::log_write($message,$data, 'debug',$filename);
        }
    }
    /**
     * error级别日志记录<br/>
     * 仅在log_error配置为true时记录
     * @param $message 日志简述
     * @param $data 日志内容
     * @param string $filename 日志文件名 不传则默认以日期命名
     */
    public static function log_error($message,$data,$filename=''){
        if(Config::get('App.log_error')){
            self::log_write($message,$data, 'error',$filename);
        }
    }
    /**
     * system info级别日志记录<br/>
     * 仅供框架调用
     * @param $message 日志简述
     * @param $data 日志内容
     * @param string $filename 日志文件名 不传则默认以日期命名
     */
    public static function log_system_info($message,$data='',$filename='Jframe'){
        self::log_write('【info】'.$message,$data, 'system',$filename);
    }
 /**
     * system error级别日志记录<br/>
     * 仅供框架调用
     * @param $message 日志简述
     * @param $data 日志内容
     * @param string $filename 日志文件名 不传则默认以日期命名
     */
    public static function log_system_error($message,$data='',$filename='Jframe'){
        self::log_write('【error】'.$message,$data, 'system',$filename);
    }
 /**
     * system warning级别日志记录<br/>
     * 仅供框架调用
     * @param $message 日志简述
     * @param $data 日志内容
     * @param string $filename 日志文件名 不传则默认以日期命名
     */
    public static function log_system_warning($message,$data='',$filename='Jframe'){
        self::log_write('【warning】'.$message,$data, 'system',$filename);
    }

    /**
     * 记录日志信息
     * @param string $message 提示信息
     * @param mixed $data 日志内容
     * @param string $level 日志级别 可选 info,error,debug,sql
     * @param $filename 文件名
     */
    private  static function log_write($message,$data,$level,$filename){
        try {
            $filePath = LOG_DIR . '/';
            switch ($level) {
                case 'sql':
                    $filePath = $filePath . 'sql/';
                    break;
                case 'info':
                    $filePath = $filePath . 'info/';
                    break;
                case 'error':
                    $filePath = $filePath . 'error/';
                    break;
                case 'debug':
                    $filePath = $filePath . 'debug/';
                    break;
                case 'system':
                    $filePath = $filePath . '/';
                    break;
            }
            if (!is_dir($filePath)) {
                mkdir("$filePath", 0644, true);
            }
            $fileName = $filename . date('Y_m_d') . '.log';
            $fullFileName = $filePath . $fileName;

            //检测日志文件大小，超过配置大小则备份日志文件重新生成
            if (is_file($fullFileName) && floor(Config::get('App.log_file_size')) <= filesize($fullFileName)) {
                rename($fullFileName, dirname($fullFileName) . '/' . time() . '-' . basename($fullFileName));
            }

            $reqDateTime = date('Y-m-d H:i:s', time());

            if (is_array($data)) {
                $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
            } elseif ($data instanceof \Exception){
                $jsonData=$data->getTraceAsString();
            }else{
                $jsonData=$data;
            }
            $url=$_SERVER["REQUEST_SCHEME"].'://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];

            $str='request time:'.$reqDateTime.
                ' request serialNumber:'.SERIAL_NUMBER.
                ' request url:'.$url.
                ' record message:'.$message.
                ' record data:';
            $str.=$jsonData;

            self::log_buffer($fullFileName,$str);

        }catch (\Exception $e){
            //出现异常不做处理
        }

    }

    /**
     * 将日志写入缓存
     * @param $fileName 要写入的文件名
     * @param $logStr 要写入的日志信息
     */
    public static function log_buffer($fileName,$logStr){
        self::$buffer[$fileName][]=$logStr;
        //判断日志量是否达到缓存上限，达到则进行flush
        if(array_key_exists($fileName,self::$buffer) && count(self::$buffer[$fileName])>=Config::get('App.log_buffer')){
            self::log_flush($fileName);
        }
    }

    /**
     * 将buffer落地到文件
     * @param string $fileName 要写入的文件名，不传默认将buffer中所有的数据写入各自文件
     */
    public static function log_flush($fileName=''){
        if($fileName){
            $logStr='';
            $fileNameBuffer=self::$buffer[$fileName]?:[];
            foreach ($fileNameBuffer as $key=>$str){
                $logStr.=$str.PHP_EOL;
            }
            file_put_contents($fileName,$logStr,FILE_APPEND);
            unset(self::$buffer[$fileName]);
        }else{
            foreach (self::$buffer as $fileName=>$buf){
                $logStr='';
                foreach ($buf as $str){
                    $logStr.=$str.PHP_EOL;
                }
                file_put_contents($fileName,$logStr,FILE_APPEND|LOCK_EX);
            }
            self::$buffer=[];

        }
    }
    /*
     * 日志自动删除
     * 在规定的时间段内访问则触发删除规定天数(暂定为3天)前的日志
     */
    private static  function log_delLog($count=3){
        $time=time();
        $start=strtotime(date('Y-m-d').' 23:00:00');
        $end=strtotime(date('Y-m-d').' 23:59:59');

        /*
         * 如果访问时间在当天的23点之后则触发删除日志
         */
        if($time>$start && $time<$end){
            /*
             * 删除白名单
             */
            $whiteArr[]=date('Ymd').'.log';
            $whiteArr[]=date('y_m_d').'.log';
            for ($i=0;$i<$count;$i++){
                $whiteArr[]=date('Ymd',strtotime('-'.$i.' day')).'.log';
                $whiteArr[]=date('y_m_d',strtotime('-'.$i.' day')).'.log';
            }
            self::log_deldir(LOG_DIR.'/Logs/',$whiteArr);
        }
    }
    /**
     * 递归删除目录下的文件
     * @param string $dir 文件夹路径
     * @param array $whiteArr 文件白名单(文件名以白名单结尾的文件)
     */
    private static function log_deldir($dir,$whiteArr=[])
    {
        try {
            //删除目录下的文件：
            $dh=opendir($dir);

            while ($file=readdir($dh))
            {
                if($file!="." && $file!=".." && !in_array(substr($file, -12), $whiteArr))
                {
                    $fullpath=$dir."/".$file;
                    if(!is_dir($fullpath))
                    {
                        unlink($fullpath);
                    }
                    else
                    {
                        self::log_deldir($fullpath,$whiteArr);
                    }
                }
            }

        }catch (\Exception $e){
            //不做处理
        }finally {
            closedir($dh);
        }
    }
}
