<?php
/**
 * author: jeremy
 * DateTime: 2017/3/24 13:41
 * Description: 对象池 负责对象的创建、缓存、提取
 */
namespace JframeCore;
Class ObjPool{
    private static $objPool=[];

    /**
     * 从对象池中获取对象
     * @param string $fullyQualifiedName 类的完全限定名，包含完整的命名空间 示例：\Task\TaskUserRuleModule
     * @param array $paramArr 创建对象时需要传的参数组成的数组
     * @return mixed|object
     */
    public static function getObj( $fullyQualifiedName,$paramArr=[]){
        //根据完全限定名和参数生成key
        $key=self::generateKey($fullyQualifiedName,$paramArr);
        //对象池中如果已经有则直接取出返回
        if(array_key_exists($key,self::$objPool)){
            return self::$objPool[$key];
        }else{
            //生成反射对象
            $class = new \ReflectionClass($fullyQualifiedName);
            //创建对象
            $newObj=$class->newInstanceArgs($paramArr);
            self::$objPool[$key]=$newObj;
            return $newObj;
        }
    }

    /**
     * 获取对象池中所有对象
     * @return array
     */
    public static function getAllObj(){
            return self::$objPool;
    }
    /**
     * 将对象池中将某个对象销毁掉
     * @param string $fullyQualifiedName 类的完全限定名
     * @param array $paramArr 创建对象时需要传的参数组成的数组
     */
        public static function destoryObj($fullyQualifiedName,$paramArr=[]){
            $key=self::generateKey($fullyQualifiedName,$paramArr);
            if(array_key_exists($key,self::$objPool)){
                unset(self::$objPool[$key]);
            }
        }

    /**
     * 将对象池中所有对象销毁掉
     */
        public static function destoryAllObj(){
            self::$objPool=[];
        }

    /**
     * 生成key
     * @param string $fullyQualifiedName 类的完全限定名
     * @param array $paramArr 创建对象时需要传的参数组成的数组
     * @return string
     */
        private static function generateKey($fullyQualifiedName,$paramArr=[]){
            $key=$fullyQualifiedName.json_encode($paramArr);
            return $key;
        }
}