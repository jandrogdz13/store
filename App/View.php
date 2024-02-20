<?php
require_once ROOT . 'Libs' . DS . 'smarty' . DS . 'libs' . DS . 'Smarty.class.php';

class View extends Smarty{
    private $controller;
    private $t_controller;
    private $request;

    public function __construct(Request $request) {
        parent::__construct();
		$this->request = $request;
        $this->controller = isset($request->post['module'])? $request->post['module']: $request->getController();
		$this->t_controller = $request->get('t_controller');
    }

    public function view($view, $content = false){

        $this->template_dir = ROOT . 'Views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS;
        $this->config_dir = ROOT . 'Views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'configs' . DS;
        $this->cache_dir = ROOT . 'tmp' . DS . 'cache' . DS;
        $this->compile_dir = ROOT . 'tmp' . DS . 'template' . DS;

        $title = 'Mobel Inn'; //$this->controller == 'index'? "Mobelinn": ucfirst($this->t_controller);
        $route = ROOT . 'Views' . DS . $this->controller . DS . $view . '.tpl';
		//$route =  ROOT . 'Views' . DS . 'main' . DS . $view . '.tpl';

        $vars = array(
            'css' => BASE_URL . 'Views/layout/' . DEFAULT_LAYOUT . '/css/',
            'images' => BASE_URL . 'Views/layout/' . DEFAULT_LAYOUT . '/images/',
            'js' => BASE_URL . 'Views/layout/' . DEFAULT_LAYOUT . '/js/',
            'fonts' => BASE_URL . 'Views/layout/' . DEFAULT_LAYOUT . '/fonts/',
            'storage' => BASE_URL . 'Storage/',
			'scripts' => BASE_URL . 'Views/' . $this->controller . '/scripts/',
            'title' => $title,
            'config' => [
                'base_url' => BASE_URL,
                'def_route' => DEFAULT_ROUTE,
                'path_views' => PATH_VIEWS,
				'app' => APP_NAME,
				'apis' => [
					'skydropx' => [
						'SKYDROPX_SANDBOX'=> SKYDROPX_SANDBOX,
						'SKYDROPX_API_KEY_SANDBOX'=> SKYDROPX_API_KEY_SANDBOX,
						'SKYDROPX_API_KEY_PRODUCTION'=> SKYDROPX_API_KEY_PRODUCTION,
					],
					'paypal' => [
						'PAYPAL_SANDBOX' => PAYPAL_SANDBOX,
						'PAYPAL_CLIENT_ID_SANDBOX' => PAYPAL_CLIENT_ID_SANDBOX,
						'PAYPAL_SECRET_SANDBOX' => PAYPAL_SECRET_SANDBOX,
						'PAYPAL_CLIENT_ID_PRODUCTION' => PAYPAL_CLIENT_ID_PRODUCTION,
						'PAYPAL_SECRET_PRODUCTION' => PAYPAL_SECRET_PRODUCTION,
					],
					'mercadopago' => [
						'MP_SANDBOX' => MP_SANDBOX,
						'MP_API_KEY_SANDBOX' => MP_API_KEY_SANDBOX,
						'MP_TOKEN_SANDBOX' => MP_TOKEN_SANDBOX,
						'MP_API_KEY_PRODUCTION' => MP_API_KEY_PRODUCTION,
						'MP_TOKEN_PRODUCTION' => MP_TOKEN_PRODUCTION,
					]
				]
            ],
        );

		$cart = Session::has('cart')? Session::get('cart'): [];
		$customer = Session::has('customer')? Session::get('customer'): [];

        $this->assign('vars', $vars);
        $this->assign('version', isMobileDevice()? 'mobile': 'desktop');
        $this->assign('method', $this->request->getMethod());
        $this->assign('timestamp', time());
        $this->assign('authenticated', Session::has('authenticated'));
        $this->assign('cart', $cart);
        $this->assign('customer', $customer);
        $this->assign('currency', $_COOKIE['currency'] ?? translate('CURRENCY', 'main'));

		// Menu
		$this->Get_Menu();

		if(is_readable($route)):
			if($content):
				$this->assign('sub_content', $route);
				$this->display('content.tpl');
			else:
				$this->assign('content', $route);
				$this->display('main.tpl');
			endif;
		else:
			throw new Exception("ERROR View not found: {$route}");
		endif;
    }

	private function Get_Menu(){
		$category_model = Helper::loadModel('category');
		$categories = $category_model->Get_Records();

		$this->assign('categories', $categories);
	}

    function jsonHttpResponse($data, $success = true){
        ob_clean();
        header_remove();
        header("Content-type: application/json; charset=utf-8");

        // Set your HTTP response code, 2xx = SUCCESS,
        // anything else will be error, refer to HTTP documentation
        if ($success) {
            http_response_code(200);
        } else {
            http_response_code(200);
        }

		$data['success'] = $success;
        echo json_encode($data);
        exit();
    }
}
?>
