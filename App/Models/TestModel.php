<?php
/**
 * author: jeremy
 * DateTime: 2017/3/27 17:13
 * Description:
 */
namespace App\Models;
use JframeCore\BaseModel;

class TestModel extends BaseModel {
    public function test(){
        $sql='select  * from user ';
        $return=$this->prepareExecute($sql,[]);
        return $return;
    }
}
