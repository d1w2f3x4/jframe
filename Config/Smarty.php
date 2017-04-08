<?php
/**
 * author: cooper
 * DateTime: 2017/4/7 20:19
 * Description:smarty模板引擎配置
 */
return [
    /**
     * smarty配置定义
     */
	'USE_SMARTY' =>	TRUE,			//使用smarty还是原生
	 
    'TPL_VIEW' => VIEWS_DIR,                                //模板目录
    'TPL_CACHE' => RUNTIME_DIR  . 'Tpl_cache',              //模板缓存目录
    'TPL_COMPILE' => RUNTIME_DIR  . 'Tpl_compile',          //模板变异目录


    'DEBUGGING' => TRUE,             //开启debug
    'CACHING' => TRUE,              //开启缓存
    'CACHE_LIFETIME' => 120,         //缓存时间

    'LEFT_DELIMITER' => '<{',
    'RIGHT_DELIMITER' => '}>'

];
