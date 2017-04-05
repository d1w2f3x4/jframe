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

set_time_limit(600);
Class Index extends BaseController {
    public function before_index(){
        //echo 'before_index<br/>';
    }
    public function test(){
        $fileDir=LOG_DIR .date('Ymd').'/debug';
        if(!is_dir($fileDir)){
            mkdir("$fileDir",0644,true);
        }
        $fileDir=LOG_DIR .date('Ymd').'/info';
        if(!is_dir($fileDir)){
            mkdir("$fileDir",0644,true);
        }
        $fileDir=LOG_DIR .date('Ymd').'/error';
        if(!is_dir($fileDir)){
            mkdir("$fileDir",0644,true);
        }
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
            dd($e);
            $testModel->rollback();
        }




    }
    public function after_index(){
        //echo 'after_index<br/>';
    }





}