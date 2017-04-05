<?php
/**
 * author: jeremy
 * DateTime: 2017/3/27 16:36
 * Description:
 */
return [
    //运行模式 true表示调试模式 上线请设置为false
    'debug' => true,
    //时区 默认为上海
    'timezone' => 'Asia/Shanghai',
    //info级别日志记录开关
    'log_info'=>true,
    //error级别日志记录开关
    'log_error'=>true,
    //sql级别日志记录开关
    'log_sql'=>true,
    //日志文件大小限制单位byte
    'log_file_size'=>102400000,
    //缓存的日志条数 0 表示不缓冲直接写入文件
    'log_buffer'=>100,

    /*
     * 数据库配置
     */
    'db_dsn'=>'mysql:host=127.0.0.1;port=3306;dbname=test;',
    'db_username'=>'root',
    'db_password'=>'root',
];
