<?php
date_default_timezone_set('America/Mexico_City');


define('STRICT', false);
if(defined(STRICT) && STRICT){
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}else{
	//error_reporting(E_ALL & ~E_NOTICE);
	error_reporting(E_ERROR | E_PARSE);
	ini_set('display_errors', 1);
	/* ini_set('display_startup_errors', 1);
	error_reporting(E_ALL); */
	ini_set("session.gc_maxlifetime","864000");
	ini_set('session.cookie_lifetime',"864000");
}

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
define("DS", DIRECTORY_SEPARATOR);
define("ROOT", realpath(dirname(__FILE__)) . DS);
define("APP_PATH", ROOT . "App" . DS);

/*set_error_handler(
	function($errno, $errstr, $errfile, $errline) {
	Log::getInstance('error_log')->write([$errstr, $errno, $errfile, $errline]);
	//throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
});*/


/*echo 'Versión actual de PHP: ' . phpinfo();
exit;*/

//echo "<pre>";print_r(get_required_files());
/*
$r = new Request();
echo $r->getControlador() . "<br>";
echo $r->getMetodo() . "<pre>";
print_r($r->getArgs());
 *
 */

spl_autoload_register(function($class) {
    switch($class):
        case strpos($class, 'Smarty'):
            if($class == 'Smarty_Autoloader'):
                $class = 'Autoloader';
                require_once ROOT . DS . 'Libs' . DS . 'smarty' . DS . 'libs' .DS . $class . '.php';
                Smarty_Autoloader::register();
            endif;
        break;
        default:
            require_once APP_PATH . ucfirst($class) . '.php';
        break;
    endswitch;
});

try{
    require_once APP_PATH . 'Config.php';
    require_once APP_PATH . 'Functions.php';
    require_once APP_PATH . 'Log.php';
    require_once APP_PATH . 'Cache.php';
    Session::init();

    $registry = Registry::getInstance();
    $registry->set('request', new Request());

    // Session
	$_controller = $registry->get('request')->getController();
	$method = $registry->get('request')->getMethod();

	/*if($_controller == 'account' && !in_array($method, ['login', 'register', 'recovery', 'logout']))
		Session::authenticated();*/

	// Database
	require_once ROOT . 'Databases' . DS . DB_TYPE . '.php';
	$dbClass = 'db' . DB_TYPE;
	$registry->set('db', new $dbClass());

	Bootstrap::run($registry->get('request'));

}
 catch (Exception $e){
     echo $e->getMessage();
 }
?>
