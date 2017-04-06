<?php
/**
 * author: jeremy
 * DateTime: 2017/3/27 15:55
 * Description:
 */
namespace App\Controllers;

use App\Models\TestModel;
use App\Models\Tran;
use JframeCore\BaseController;
use JframeCore\Driver\PdoDriver;
use JframeCore\Driver\TestDriver;
use JframeCore\Log;
use Thrift\ClassLoader\ThriftClassLoader;

Class Index extends BaseController {

    public function test(){
        dd('我是test方法');
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
            throw new \RuntimeException('xxxxxxxxxx');
            $result=$testModel->prepareExecute($sql,$param);

            $testModel->commit();
        }catch (\Exception $e){
            Log::log_error($e->getMessage(),$e);
            $testModel->rollback();
        }




    }






}