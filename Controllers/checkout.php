<?php

Helper::load_helper('Mercadopago');
Helper::load_helper('Mail');

class checkoutController extends mainController {

	public function __construct(){
		parent::__construct();
		$this->module = $this->request->getController();
		$this->model = Helper::loadModel($this->module);
		Session::authenticated();
		$this->viewer->assign('module', $this->request->getController());
		$this->viewer->assign('scripts', $this->getScripts());
	}

	public function index(){
		if(!Session::has('authenticated'))
			redirect('account/login');

		$cart = Session::has('cart')? Session::get('cart'): [];
		if(empty($cart) || empty($cart['products']))
			redirect();

		//debug($cart, true);
		$this->viewer->view(__FUNCTION__);
	}

	public function address(){
		try{
			$post = $this->request->post;
			$cart = Session::has('cart')? Session::get('cart'): [];

			$record = $this->model->Get_Address($post['address_id']);

			if($record):
				$cart['address'] = $record;
				Session::set('cart', $cart);

				$this->viewer->jsonHttpResponse([
					'data' => $record
				]);
			else:
				$this->viewer->jsonHttpResponse([
					'title' => translate('TITLE_ADDR', $this->module),
					'err' => translate('ERR_GET_ADDRESS', $this->module),
				], false);
			endif;

		}catch(Exception $ex){
			Log::getInstance('error_log')->write($ex->getMessage());
			$this->viewer->jsonHttpResponse([
				'title' => translate('TITLE_ADDR', $this->module),
				'err' => translate('ERR_GET_ADDRESS', $this->module),
			], false);
		}

	}

	public function addresses(){
		$addresses = $this->model->Get_Addresses_By_Customer();
		$cart = Session::has('cart')? Session::get('cart'): [];

		$this->viewer->assign('address_cart', $cart['address'] ?? []);
		$this->viewer->assign('addresses', $addresses);
		$this->viewer->view('partials/Address', isAjax());
	}

	public function address_form($edit = false){
		$address = [];
		$post = $this->request->post;

		if($edit)
			$address = $this->model->Get_Address($post['address_id']);

		$this->viewer->assign('edit', $edit);
		$this->viewer->assign('address', $address);
		$this->viewer->view('partials/Address_Form', isAjax());
	}

	public function save_address($record_id = 0){

		$post = $this->request->post;
		$customer = get_customer();
		$cart = Session::has('cart')? Session::get('cart'): [];

		try{

			$address = $this->model->Save_Address($post, $record_id);
			$cart['address'] = $address;

			Session::set('cart', $cart);

			$this->viewer->jsonHttpResponse([
				'title' => translate('TITLE_ADDR', $this->module),
				'msg' => sprintf(translate('ADDRESS_SUCCESS', $this->module), $post['product_name']),
				'data' => $address
			]);

		}catch(\Throwable $ex){
			Log::getInstance('error_log')->write($ex->getMessage());
			$this->viewer->jsonHttpResponse([
				'title' => translate('TITLE_ADDR', $this->module),
				'err' => translate('ERR_SAVE_ADDRESS', $this->module),
			], false);
		}
	}

	// Skydropx
	public function get_rates(){
		$cart = Session::has('cart')? Session::get('cart'): [];
		$customer = Session::get('customer');
		Helper::load_helper('Skydropx');

		$address = $cart['address'];
		$parcels = [
			[
				"weight"		=> 10,
				"distance_unit"	=> "CM",
				"mass_unit"		=> "KG",
				"height"		=> 70,
				"width"			=> 210,
				"length"		=> 50
			]
		];

		$address_to = [
			'province'	=> $address['state'],
			'city'		=> $address['city'],
			'name'		=> $customer['name'],
			'zip'		=> $address['postcode'],
			'country'	=> 'MX',
			'address1'	=> "{$address['street']} {$address['outdoor_num']} {$address['interior_num']}",
			'company'	=> $customer['name'],
			'address2'	=> $address['suburb'],
			'phone'		=> $customer['phone'],
			'email'		=> $customer['email']
		];

		try{
			$skydropx = new Skydropx_Helper();
			$rates = $skydropx->createShipments($parcels, $address_to);

			if($rates)
				$rates = array_filter($rates['body']['included'], function($item){
					return $item['type'] === 'rates';
				});

			if($rates && !isset($rates['body']['errors']))
				$this->viewer->jsonHttpResponse([
					'data' => $rates
				]);
			else
				Log::getInstance('error_log')->write($rates['body']);
				$this->viewer->jsonHttpResponse([
					'title' => translate('TITLE_RATES', $this->module),
					'err' => translate('ERR_RATES', $this->module),
				], false);

		}catch(\Throwable $ex){
			Log::getInstance('error_log')->write($ex->getMessage());
			$this->viewer->jsonHttpResponse([
				'title' => translate('TITLE_RATES', $this->module),
				'err' => translate('ERR_RATES', $this->module),
			], false);
		}
	}

	// Shipping
	public function shipping(){
		$post = $this->request->post;
		$cart = Session::has('cart')? Session::get('cart'): [];
		$cart['shipping'] = $post;

		$this->totals();

		Session::set('cart', $cart);

		$this->viewer->jsonHttpResponse([
			'data' => Session::get('cart')
		]);
	}

	// Payment
	public function payment(){
		$post = $this->request->post;
		$cart = Session::has('cart')? Session::get('cart'): [];
		$cart['payment'] = $post;
		Session::set('cart', $cart);

		$this->totals();

		$this->viewer->jsonHttpResponse([
			'data' => Session::get('cart')
		]);
	}

	public function intent_ML(){
		$customer = Session::get('customer');
		$cart = Session::get('cart');
		$contents = json_decode(file_get_contents('php://input'), true);
		//Log::getInstance(__METHOD__)->write($contents);
		$body = [
			"transaction_amount" => (float) $cart['totals']['subtotal_inc_disc'] + $cart['totals']['shipping'], // $contents['transaction_amount'],
			"token" => $contents['token'],
			"description" => 'Muebles',
			"installments" => $contents['installments'],
			"payment_method_id" => $contents['payment_method_id'],
			"issuer_id" => $contents['issuer_id'],
			"payer" => [
				"email" => $contents['payer']['email'],
				"type" => "customer",
				"first_name" => $customer['first_name'],
				"last_name" => $customer['last_name'],
				"identification" => [
					"type" => 'RFC',
					"number" => 'XEXX010101000'
				]
			]
		];

		$mercado_pago = new MercadoPago_Helper();
		$response = $mercado_pago->createOrder($body);

		if ($response['status'] == 'approved'):
			$cart['payment']['detail'] = $response;
			Session::set('cart', $cart);
		endif;

		$this->viewer->jsonHttpResponse([
			'data'  => $response
		]);
	}

	// Order
	public function create_order(){
		try {
			//Log::getInstance(__FUNCTION__)->write($this->request->post);

			$post = $this->request->post;
			$order_id = $this->model->Create_Order($post);

			if ($order_id):
				$customer = Session::get('customer');
				Session::destroy('cart');
				Session::destroy('SESSIONID');

				Session::set('create_order', true);

				$mail = new Mail_Helper();
				$template = $mail->order_template($order_id);
				$mail->send('Hemos recibido tú pedido', $template, $customer, 'ventas');

				$this->viewer->jsonHttpResponse([
					'title' => translate('CHECKOUT_TITLE', $this->module),
					'msg' => translate('SUCCESS_ORDER', $this->module),
					'data' => ['order_id' => $order_id]
				]);
			else:
				Log::getInstance('error_log')->write('Error al crear la orden');
				$this->viewer->jsonHttpResponse([
					'title' => translate('CHECKOUT_TITLE', $this->module),
					'err' => translate('ERR_CREATE_ORDER', $this->module),
				], false);
			endif;
		}catch(\Throwable $ex){
			Log::getInstance('error_log')->write($ex->getMessage());
			$this->viewer->jsonHttpResponse([
				'title' => translate('CHECKOUT_TITLE', $this->module),
				'err' => translate('ERR_CREATE_ORDER', $this->module),
			], false);
		}
	}

	public function summary($order_id){
		$account_model = Helper::loadModel('account');
		$order = $account_model->Get_Order($order_id);

		if(Session::has('create_order')):
			session_regenerate_id(true);
			Session::set('SESSIONID',  session_id());
			Session::destroy('create_order');
		endif;

		//debug($order, true);

		$this->viewer->assign('order', $order);
		$this->viewer->view('summary');
	}

	protected function getScripts(){
		$p_scripts = parent::getScripts();
		$scripts = [
			'index.js'
		];
		return array_merge($p_scripts, $scripts);
	}

}
