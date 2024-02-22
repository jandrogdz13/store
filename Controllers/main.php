<?php

class mainController extends Controller {
	public function __construct() {
		parent::__construct();

		$this->module = $this->request->getController();
		$this->model = Helper::loadModel('main');

		// Update data Cart
		$this->totals();

		$this->viewer->assign('module', $this->module);
		$this->viewer->assign('scripts', $this->getScripts());
	}

	public function index(){
		$current_date = translateDate(getCurrentDate(), false, true, false);
		$this->viewer->assign('cur_date', $current_date);
		$this->viewer->view(__FUNCTION__, isAjax());
	}

	public function create_cart($record_id){

		try{

			$session_id = Session::has('SESSIONID')
				? Session::get('SESSIONID')
				: session_id();

			$main_model = Helper::loadModel('main');

			// Assign when customer ID is 0
			$main_model->assign_cart_customer($record_id, $session_id);

			// Get Session ID
			$session = $main_model->get_session_id($record_id);
			if($session)
				$session_id = $session['session_id'];


			Session::set('SESSIONID', $session_id);

			$this->totals();

			/*$products = $main_model->get_last_cart($record_id, $session_id);
			if($products):
				$cart = [];
				$product_model = Helper::loadModel('product');
				foreach($products as $product):
					$product_id = $product['product_id'];
					$rec_prod = $product_model->Get_Record($product_id);
					$product_merge = array_merge($rec_prod, $product);
					$cart['products'][$product_id] = $product_merge;
				endforeach;

				Session::set('cart', $cart);

				$this->totals();
			endif;*/
		}catch(Exception $e){
			Throw new Exception($e->getMessage() . ' Trace:' . $e->getTraceAsString());
		}
	}

	public function totals($return_json = false){

		// Retrive Data
		$cart = Session::has('cart')? Session::get('cart'): [];
		$customer = get_customer();
		$customer_id = !empty($customer)? $customer['id']: 0;

		// Session ID
		$session_id = session_id();
		if(!Session::has('SESSIONID'))
			Session::set('SESSIONID', $session_id);
		else
			$session_id = Session::get('SESSIONID');

		// Get Products Cart
		$cart_model = Helper::loadModel('cart');
		$products = $cart_model->Get_Records($session_id, $customer_id);
		$cart['products'] = $products;

		// Cost shipping
		$totals = [];
		$totals['shipping'] = isset($cart['shipping'])
			? $cart['shipping']['total_pricing']
			: 0;

		// Calculate Totals
		$totals['subtotal'] = 0;
		$totals['discounts'] = 0;
		$totals['subtotal_inc_disc'] = 0;
		$count = 0;
		if(!empty($cart['products'])):
			foreach($cart['products'] as $product):
				$count++;
				$totals['subtotal'] += $product['quantity'] * $product['unit_price'];
				$totals['discounts'] += $product['quantity'] * $product['discount_amount'];
				$totals['subtotal_inc_disc'] += $product['quantity'] * $product['unit_price_inc_discount'];
			endforeach;
		endif;

		$cart['totals'] = $totals;
		$cart['count'] = $count;

		$cart['apis'] = $this->init_apis();
		$cart['session_id'] = Session::get('SESSIONID');

		Session::set('cart', $cart);

		if($return_json)
			$this->viewer->jsonHttpResponse([
				'data' => Session::get('cart')
			]);

	}

	public function init_apis(): array{
		return $apis = [
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
		];
	}

	/* Error */
	public function error($code){
		$this->viewer->view($code);
	}

}
