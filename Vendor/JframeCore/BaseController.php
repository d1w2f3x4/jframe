<?php
/**
 * author: jeremy
 * DateTime: 2017/3/28 20:08
 * Description: 控制器基类
 */
namespace JframeCore;

class BaseController extends Base {
    /** @var null|\Smarty  */
	private $smarty = null;

	public function __construct() {
		
		parent::__construct();
		
		include JFRAME_DIR . 'Smarty/Smarty.class.php';
		$this->smarty = new \Smarty();
		self::initSet();
	}

	/**
	 * 初始化smarty设置
	 */
	private function initSet() {

		$this->smarty->caching = false;//是否使用缓存
   		$this->smarty->template_dir = Config::get('Smarty.TPL_VIEW');//设置模板目录
    	$this->smarty->compile_dir = Config::get('Smarty.TPL_COMPILE');//设置编译目录
    	$this->smarty->cache_dir = Config::get('Smarty.TPL_CACHE');//缓存文件夹
     	//修改左右边界符号
     	$this->smarty->left_delimiter = Config::get('Smarty.LEFT_DELIMITER');
     	$this->smarty->right_delimiter = Config::get('Smarty.RIGHT_DELIMITER');
	}

	/**
	 * 模板渲染
	 * @param $tpl_path string 模板路径：'Index/test'
	 * @param $tpl_data array 页面数据
	 */
	public function render($tpl_path,$tpl_data = array()) {
		
		$type = Config::get('Smarty.USE_SMARTY');
		
		if($type) {
			$this->smarty->display($tpl_path . '.html');
		} else {
			extract($tpl_data);
			include VIEWS_DIR . $tpl_path . '.html';
		}
	}

	/**
	 * 分配页面数据
	 * @param $var string 变量名称
	 * @param $value mixed 变量值
	 */
	public function assign($var,$value) {
		$this->smarty->assign($var,$value);
	}

	/**
	 * 加载第三方库
	 * @param $vendor_path string Vendor目录下的文件
	 */
	public function Vendor($vendor_path) {
		include VENDOR_DIR . $vendor_path . '.php';
	}

}
