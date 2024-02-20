<?php

class mainController extends Controller {
	public function __construct() {
		parent::__construct();

		$this->module = $this->request->getController();
		$this->model = Helper::loadModel('main');

		$this->init_apis();
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

			$session_id = session_id();
			/*if(Session::has('SESSIONID')):
				$session_id = Session::get('SESSIONID');
			else:
				session_regenerate_id(true);
				$session_id = session_id();
			endif;*/

			$main_model = Helper::loadModel('main');

			// Assign when customer ID is 0
			$main_model->assign_cart_customer($record_id, $session_id);

			// Get Session ID
			$session = $main_model->get_session_id($record_id);
			if($session)
				$session_id = $session['session_id'];


			Session::set('SESSIONID', $session_id);

			$products = $main_model->get_last_cart($record_id, $session_id);
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
			endif;
		}catch(Exception $e){
			Throw new Exception($e->getMessage() . ' Trace:' . $e->getTraceAsString());
		}
	}

	public function totals($return_json = false){
		$cart = Session::has('cart')? Session::get('cart'): [];
		if(!empty($cart)):
			$totals = [];
			$totals['shipping'] = isset($cart['shipping'])
				? $cart['shipping']['total_pricing']
				: 0;
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

			$cart['apis'] = Session::get('apis');
			$cart['session_id'] = Session::get('SESSIONID');

			Session::set('cart', $cart);

			if($return_json)
				$this->viewer->jsonHttpResponse([
					'data' => Session::get('cart')
				]);
		endif;
	}

	public function init_apis(){
		$apis = [
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
		Session::set('apis', $apis);
	}

	/* Error */
	public function error($code){
		$this->viewer->view($code);
	}

}
