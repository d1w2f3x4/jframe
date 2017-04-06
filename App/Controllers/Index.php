<?php
/**
 * author: jeremy
 * DateTime: 2017/3/27 15:55
 * Description:
 */
namespace App\Controllers;

use App\Lib\RedisClientHelper;
use App\Models\TestModel;
use App\Models\Tran;
use JframeCore\BaseController;
use JframeCore\Driver\PdoDriver;
use JframeCore\Driver\PRedisDriver;
use JframeCore\Driver\TestDriver;
use JframeCore\FileUpload;
use JframeCore\IMiddlewareAfter;
use JframeCore\Log;
use Predis\Autoloader;
use Predis\Client;
use Thrift\ClassLoader\ThriftClassLoader;

Class Index extends BaseController {

    public function test(){
        dd(I('b','string','222'));
        echo 'I am test action!';
    }

    public function fileUploadTest(){
        $up = new FileUpload();
        //设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
        $up -> set("path", RESOURCES_DIR.'/Uploads');
        $up -> set("maxsize", 2000000);
        $up -> set("allowtype", array("gif", "png", "jpg","jpeg"));
        $up -> set("israndname", false);

        //使用对象中的upload方法， 就可以上传文件， 方法需要传一个上传表单的名子 pic, 如果成功返回true, 失败返回false
        if($up -> upload("pic")) {
            dd($up->getFileName());
        } else {
            dd($up->getErrorMsg());
        }
    }
    public function redisTest(){
        $dsn='tcp://10.31.63.9:9007?database=2';
        $client=PRedisDriver::getClient($dsn);
        $info=$client->smembers('user:cluster:info');
        dd($info);
    }
    public function pdoTest(){

        try{
            $testModel=new TestModel();
            $tran=new Tran();
            $testModel->beginTransaction();

            $sql='insert into user (name,age) VALUES (?,?)';
            $param=['阿猫',40];
            $param1=['阿狗',50];
            $result1=$tran->prepareExecute($sql,$param1);
            $result=$testModel->prepareExecute($sql,$param);

            $testModel->commit();
        }catch (\Exception $e){
            Log::log_error($e->getMessage(),$e);
            $testModel->rollback();
        }




    }






}