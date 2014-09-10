<?php
define("APP_PATH",dirname(__FILE__));
define("SP_PATH",dirname(__FILE__).'/SpeedPHP');
define("SEUL_CONFIG",dirname(__FILE__).'/config');
header("Content-Type: text/html; charset=UTF-8");
date_default_timezone_set('Asia/Shanghai');
$spConfig = array(
	// smarty配置
	'view' => array(
		'enabled' => TRUE, // 开启Smarty
		'config' =>array(
			'template_dir' => APP_PATH.'/tpl', // 模板存放的目录
			'compile_dir' => APP_PATH.'/tmp', // 编译的临时目录
			'cache_dir' => APP_PATH.'/tmp', // 缓存的临时目录
			'left_delimiter' => '{',  // smarty左限定符
			'right_delimiter' => '}', // smarty右限定符
		)
	),
	// 伪静态配置
	'launch' => array( // 加入挂靠点，以便开始使用Url_ReWrite的功能
		'router_prefilter' => array(
			array('spUrlRewrite', 'setReWrite'),  // 对路由进行挂靠，处理转向地址
		),
		'function_url' => array(
			array("spUrlRewrite", "getReWrite"),  // 对spUrl进行挂靠，让spUrl可以进行Url_ReWrite地址的生成
		)
	),
	// Url重写配置
	'ext' => array(
		'spUrlRewrite' => array(
			'suffix' => '.html', // 生成地址的结尾符，网址后缀，可自由设置，如果“.do”或“.myphp”，该参数可为空，默认是.html。
			'sep' => '/', // 网址参数分隔符，建议是“-_/”之一
			'map' => array(	// 网址映射
				'search' => 'main@search', // 将使得 http://www.example.com/search.html 转向控制器main/动作serach执行
				'@' => 'main@no' // 1.在map中无法找到其他映射，2. 网址第一个参数并非控制器名称。
			),
			'args' => array( // 网址映射附加的隐藏参数，如果针对某个网址映射设置了隐藏参数，则在网址中仅会存在参数值，而参数名称被隐藏。
				// 生成的网址将会是：http://www.example.com/search-thekey-2.html
				// 这个网址将会执行 控制器main/动作serach，而参数q将等于thekey，参数page将等于2
				'search' => array('q','page'), 
											   
			)
		)
	),
	// 用户程序扩展类载入路径
	'include_path' => array(
		APP_PATH.'/include'
	)

);
require(SEUL_CONFIG."/dbconfig.php");
$spConfig['db'] = $dbconfig;
require(SP_PATH."/SpeedPHP.php");
require(SEUL_CONFIG."/webinfo.php");
import("func.php");
spRun();
?>