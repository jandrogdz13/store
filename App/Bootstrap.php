<?php

class Bootstrap{

    public static function run(Request $request){
        $controllername = $request->getController();
        $route = ROOT . "Controllers" . DS . $controllername . ".php";

		/*if(!is_readable($route)):
			$controllername = 'main';
			$route = ROOT . "Controllers" . DS . "main.php";
		endif;*/

        $controllerClass = "{$controllername}Controller";
        $method = $request->getMethod();
        $args = $request->getArgs();

        if(is_readable($route)):

			if(!in_array($controllername, ['main', 'error'])):
				require_once ROOT . "Controllers" . DS . "main.php";
				$main_controller = new mainController();
			endif;

            require_once $route;
            $controller = new $controllerClass();

			/*if(!method_exists($controller, $method)):
				unset($controller);
				$controller = $main_controller;
			endif;*/

			$method = is_callable([$controller, $method])
				? $request->getMethod()
				: 'index';

			isset($args)
				? call_user_func_array([$controller, $method], $args)
				: call_user_func([$controller, $method]);

        else:
			throw new Exception("Pagina no encontrada...");
        endif;
    }
}
?>
