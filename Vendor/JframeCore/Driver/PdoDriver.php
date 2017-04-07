<?php
/**
 * author: jeremy
 * DateTime: 2017/3/28 16:05
 * Description:数据库连接驱动类
 */ 
namespace JframeCore\Driver;
use JframeCore\Base;
use JframeCore\Log;
use JframeCore\ObjPool;

class PdoDriver extends Base {
    protected $master_dsn;
    protected $slave_dsn;
    protected $username;
    protected $password;
    protected $options;
    /** @var  \PDO $pdo */
    protected $pdo;
    //带参数绑定的sql语句
    protected $sql='';
    //参数绑定列表
    protected $bind=[];


    /**
     * pdo驱动构造函数
     * @param $dsn 主从读写分离则使用#分隔，默认第一个为主库，其他为从库
     * @param string $username
     * @param string $password
     * @param array $options
     */
    public function __construct($dsn,$username='',$password='',$options=[]) {
        parent::__construct();
        /*
         * 主从读写分离处理
         */
        $dsnArr=explode('#',$dsn);
        $this->master_dsn=$dsnArr[0];
        unset($dsnArr[0]);
        $this->slave_dsn=$dsnArr;

        $this->username=$username;
        $this->password=$password;
        $this->options =$options;
        //默认为写连接 否则事务会出问题
        $this->pdo_connect();
    }

    /**
     * 创建pdo连接
     * 根据是操作语句进行读写分离数据库连接
     * @param bool $isQuery 是否是查询语句
     */
    private function pdo_connect($isQuery=false) {
        $dsn=$this->master_dsn;
        if($isQuery && $this->slave_dsn){
            //打乱顺序随机获取一个
            shuffle($this->slave_dsn);
            $dsn=$this->slave_dsn[0];
        }
        $this->pdo = ObjPool::getObj('\PDO',[$dsn,$this->username,$this->password,$this->options]);
        // 设置 PDO 错误模式为异常
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * 采用预处理的方式执行sql语句
     * 注意：当$paramArr二维数组时返回结果执行的是array_merge操作，查询操作慎用
     * @param $sql 问号形式的预处理语句
     * @param $paramArr 预处理参数值组成的数组，顺序与问号顺序一一对应，若为二维数组则表示执行多组值不同的操作
     * @return mixed 如果为查询语句则返回结果集数组，否则将返回影响条数
     */
    public function prepareExecute($sql,$paramArr=[]){

        //判断是否为查询语句
        $isQuery=false;
        if(strtoupper(substr(trim($sql),0,6))=='SELECT' ){
            $isQuery=true;
        }
        $this->pdo_connect($isQuery);
        $this->sql=$sql;
        $this->bind=$paramArr;

        $returnResult=[];
        $pdoStatement=$this->pdo->prepare($sql);
        if($pdoStatement){
            if(array_key_exists(0,$paramArr) && is_array($paramArr[0])){
                foreach ($paramArr as $arr){
                    $boolResult=$pdoStatement->execute($arr);
                    if($boolResult){
                        if($isQuery){
                            $result=$pdoStatement->fetchAll(\PDO::FETCH_ASSOC);
                            $returnResult=array_merge($returnResult,$result);
                        }else{
                            $returnResult=$boolResult;
                        }
                    }else{
                        return $boolResult;
                    }
                }
            }else{
                $boolResult=$pdoStatement->execute($paramArr);
                if($boolResult){
                    if($isQuery){
                        $returnResult=$pdoStatement->fetchAll(\PDO::FETCH_ASSOC);
                    }else{
                        $returnResult=$boolResult;
                    }
                }else{
                    return $boolResult;
                }
            }

        }

        return $returnResult;
    }

    /**
     * 开启事务
     */
    public function beginTransaction(){
        $this->pdo->beginTransaction();
    }

    /**
     * 提交事务
     */
    public function commit(){
        $this->pdo->commit();
    }

    /**
     * 回滚事务
     */
    public function rollback(){
        $this->pdo->rollBack();
    }

    /**
     * 获取最后一条查询语句
     * @return mixed
     */
    public function getLastSql(){
        return $this->getRealSql($this->sql,$this->bind);
    }

    /**
     * 获取最后一次插入id
     * @return string
     */
    public function getLastInsertId(){
        return $this->pdo->lastInsertId();
    }

    /**
     * 数据库调试 记录当前SQL及分析性能 只针对select 语句
     * @access protected
     * @param string $sql 执行的SQL语句 留空自动获取
     * @param int $outputLocation 结果输出位置 默认1：sql日志 2：echo到浏览器
     * @return void
     * @internal param bool $start 调试开始标记 true 开始 false 结束
     */
    public function debug($sql = '',$outputLocation=1)
    {
        $startTime=microtime(true);
        $sql=$sql?:$this->getLastSql();
        dd('sqlDebugSql: '.$sql);
        if(0 === stripos(trim($sql), 'select')){
            $result=$this->getExplain($sql,$outputLocation);
        }
        $endTime=microtime(true);
        switch ($outputLocation){
            case 1:
                Log::log_sql('sqlDebugRunTime',($endTime-$startTime).'s');
                Log::log_sql('sqlDebugResult',$result);
                break;
            case 2:
                dd('sqlDebugRunTime: '.($endTime-$startTime).'s');
                dd('sqlDebugResult: ');
                dd($result);
                break;
        }

    }

    /**
     * SQL性能分析
     * @access protected
     * @param string $sql
     * @param $outputLocation
     * @return array
     */
    protected function getExplain($sql,$outputLocation)
    {
        $pdo    = $this->pdo->query("EXPLAIN " . $sql);
        $result = $pdo->fetch(\PDO::FETCH_ASSOC);
        $result = array_change_key_case($result);
        if (isset($result['extra'])) {
            if (strpos($result['extra'], 'filesort') || strpos($result['extra'], 'temporary')) {
                switch ($outputLocation){
                    case 1:
                        Log::log_sql('sqlDebugExplain','SQL:' . $this->queryStr . '[' . $result['extra'] . ']');
                        break;
                    case 2:
                        dd('sqlDebugExplain: ','SQL:' . $this->queryStr . '[' . $result['extra'] . ']', 'warn');
                        break;
                }
            }
        }
        return $result;
    }

    /**
     * 根据参数绑定组装最终的SQL语句 便于调试
     * @param string    $sql 带参数绑定的sql语句
     * @param array     $bind 参数绑定列表
     * @return string
     */
    private function getRealSql($sql, array $bind = [])
    {
        $lastBind='';
        if(is_array($bind[0])){
            $size=count($bind);
            $lastBind=$bind[$size-1];
        }else{
            $lastBind=$bind;
        }
        foreach ($lastBind as $key => $value) {
            $type  =  is_numeric($value)? \PDO::PARAM_INT:\PDO::PARAM_STR;
            if (\PDO::PARAM_STR == $type) {
                //sql指令安全过滤
                $value=$this->pdo->quote($value);
            } elseif (\PDO::PARAM_INT == $type) {
                $value = (float) $value;
            }
            // 判断占位符
            $sql = is_numeric($key) ?
                substr_replace($sql, $value, strpos($sql, '?'), 1) :
                str_replace(
                    [':' . $key . ')', ':' . $key . ',', ':' . $key . ' '],
                    [$value . ')', $value . ',', $value . ' '],
                    $sql . ' ');
        }
        return rtrim($sql);
    }

    /**
     * 销毁连接
     */
    public function close(){
        $this->pdo=null;
    }



}