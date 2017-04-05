<?php
/**
 * author: jeremy
 * DateTime: 2017/3/27 14:14
 * Description:
 */
//定义项目根目录路径常量
define('ROOT_DIR',realpath(dirname(__FILE__) . '/../'.DIRECTORY_SEPARATOR));
//定义框架内核路径常量
define('JFRAME_DIR',ROOT_DIR.'/Vendor/JframeCore'.DIRECTORY_SEPARATOR);
//定义项目根应用路径常量
define('APP_DIR',ROOT_DIR.'/App'.DIRECTORY_SEPARATOR);
//定义项目配置文件录路径常量
define('CONFIG_DIR',ROOT_DIR.'/Config'.DIRECTORY_SEPARATOR);
//定义项目Vendor录路径常量
define('VENDOR_DIR',ROOT_DIR.'/Vendor'.DIRECTORY_SEPARATOR);
//定义项目Runtime录路径常量
define('RUNTIME_DIR',ROOT_DIR.'/Runtime'.DIRECTORY_SEPARATOR);
//定义项目Log录路径常量
define('LOG_DIR',ROOT_DIR.'/Runtime/Logs'.DIRECTORY_SEPARATOR);
//定义项目控制器controllers录路径常量
define('CONTROLLERS_DIR',ROOT_DIR.'/App/Controllers'.DIRECTORY_SEPARATOR);
//定义项目模型models录路径常量
define('MODELS_DIR',ROOT_DIR.'/App/Models'.DIRECTORY_SEPARATOR);
//定义项目资源文件路径常量
define('RESOURCES_DIR',ROOT_DIR.'/Resources'.DIRECTORY_SEPARATOR);
//定义项目视图文件路径常量
define('VIEWS_DIR',ROOT_DIR.'/Resources/Views'.DIRECTORY_SEPARATOR);


include  ROOT_DIR.'/Vendor/Autoload.php';
$app=new \JframeCore\App();
$app->run();
