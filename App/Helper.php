<?php
class Helper{

    private static $registry;

    public static $days = [
        'Domingo',
        'Lunes',
        'Martes',
        'Miercoles',
        'Jueves',
        'Viernes',
        'Sabado'
    ];
    public static $months = [
        'Enero',
        'Febrero',
        'Marzo',
        'Abril',
        'Mayo',
        'Junio',
        'Julio',
        'Agosto',
        'Septiembre',
        'Octubre',
        'Noviembre',
        'Diciembre'
    ];

    // Controller
	public static function loadController($module){
		self::$registry = Registry::getInstance();
		$className = "{$module}Controller";
		$controllers = self::$registry->get('controllers');

		if(array_key_exists($className, $controllers)):
			return $controllers[$className];
		else:
			$route = ROOT . "Controllers" . DS . $module . ".php";
			if(is_readable($route)):
				require_once $route;
				$controllers[$className] = new $className($module);
				self::$registry->set('models', $controllers);
				return $controllers[$className];
			else:
				throw new Exception("ERROR controller not found: {$route}");
			endif;
		endif;
	}

    // Models
    public static function loadModel($module){
        self::$registry = Registry::getInstance();
        $className = "{$module}Model";
        $models = self::$registry->get('models');

        if(array_key_exists($className, $models)):
            return $models[$className];
        else:
            $route = ROOT . "Models" . DS . $module . ".php";
            if(is_readable($route)):
                require_once $route;
                $models[$className] = new $className($module);
                self::$registry->set('models', $models);
                return $models[$className];
            else:
                throw new Exception("ERROR model not found: {$route}");
            endif;
        endif;
    }

    // Libraries
    public static function loadLibrary($name){
        $route = ROOT. "Libs" . DS . $name . ".php";
        if(is_readable($route)):
            require_once $route;
        else:
            throw new Exception("Library not found: " . $route);
        endif;
    }

	public static function load_helper($name){
		$route = ROOT. "Helpers" . DS . $name . ".php";
		if(is_readable($route)):
			require_once $route;
		else:
			throw new Exception("Helper not found: " . $route);
		endif;
	}

}
