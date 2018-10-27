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
     * @param object $obj 对象 针对无法通过完全限定名创建的对象（引入第三方类库时）$fullyQualifiedName可以不传入完全限定名，但是需要自己创建对象并传入,使用时结合objExist方法使用<br/>
     * <b color=yellow>注意：$fullyQualifiedName不能使用完全限定名时,传入的值一定要制定统一的规则全局唯一，防止出现未预期的对象覆盖导致的错误<b>
     * @return mixed|object
     */
    public static function getObj( $fullyQualifiedName,$paramArr=[],$obj=null){
        //根据完全限定名和参数生成key
        $key=self::generateKey($fullyQualifiedName,$paramArr);
        //对象池中如果已经有则直接取出返回
        if(array_key_exists($key,self::$objPool)){
            return self::$objPool[$key];
        }elseif ($obj){
            self::$objPool[$key]=$obj;
            return $obj;
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
     * 判断对象池中是该对象是否已经存在
     * @param string $fullyQualifiedName 类的完全限定名
     * @param array $paramArr 创建对象时需要传的参数组成的数组
     * @return bool
     */
    public static function objExist($fullyQualifiedName,$paramArr=[]){
        $key=self::generateKey($fullyQualifiedName,$paramArr);
        if(array_key_exists($key,self::$objPool)){
            return true;
        }else{
            return false;
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

    /**
     * 创建对象并执行对象方法(执行方法所需要的参数直接跟在最后面传入，暂时最多只支持5个)返回执行结果<br/>
     * 本方法自动进行异常捕获及重试
     * @param string $fullyQualifiedName 类的完全限定名，包含完整的命名空间 示例：\Task\TaskUserRuleModule
     * @param array $paramArr 创建对象时需要传的参数组成的数组
     * @param string $method 要执行的方法
     * @param int $retryCount 重试次数 负数-1表示无限次重试 默认重试三次
     * @param int $retryFrequency 重试间隔单位秒 默认为0：无间隔执行
     * @return  mixed 返回方法执行结果
     * @throws \Exception 当进行$retryCount次重试后如果仍然有异常则将异常抛出以便业务捕获进行针对性业务处理
     */
    public static function getResult($fullyQualifiedName,$paramArr=[],$method='',$retryCount=3,$retryFrequency=0){
        //重试标记
        $retryFlag=false;
        do{
            //如果需要重试则sleep一段时间再重试
            if($retryFlag){
                sleep($retryFrequency);
            }
            try{
                $obj=self::getObj($fullyQualifiedName,$paramArr);
                /*
                 * 既要用对象池又要支持多个参数暂时只能用这么笨的办法，如果不用对象池则可以直接用反射传入数组即可
                 */
                $methodParamCount=func_num_args()-5;
                $methodParamArr=array_slice(func_get_args(),5);

                switch ($methodParamCount){
                    case 0:
                        $result=$obj->$method();
                        break;
                    case 1:
                        $result=$obj->$method($methodParamArr[0]);
                        break;
                    case 2:
                        $result=$obj->$method($methodParamArr[0],$methodParamArr[1]);
                        break;
                    case 3:
                        $result=$obj->$method($methodParamArr[0],$methodParamArr[1],$methodParamArr[2]);
                        break;
                    case 4:
                        $result=$obj->$method($methodParamArr[0],$methodParamArr[1],$methodParamArr[2],$methodParamArr[3]);
                        break;
                    case 5:
                        $result=$obj->$method($methodParamArr[0],$methodParamArr[1],$methodParamArr[2],$methodParamArr[3],$methodParamArr[4]);
                        break;
                }

                //重置重试标记
                $retryFlag=false;
                return $result;
            }catch (\Exception $e){
                echo $retryCount;
                /*
                 * 如果捕获到异常可能是对象的连接中断了，需要将对象池中原有的对象销毁重新生成并重试本次操作
                 */
                if($retryCount==0){
                    //重试次数为0表示不再重试
                    $retryFlag=false;
                    //将异常抛出以便业务捕获做后续个性化业务处理
                    /** @var \Exception $e */
                    throw $e;
                }else{
                    //重试次数为正则按照该数值进行$retryCount次重试
                    $retryFlag=true;
                    $retryCount--;
                }

                //异常记录
                Log::log_error($e->getMessage(),$e);
                //销毁对象
                ObjPool::destoryObj($fullyQualifiedName,$paramArr);

            }
        }while($retryFlag);

    }
}