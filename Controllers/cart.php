<?php

class cartController extends mainController {

	public function __construct(){
		parent::__construct();
		$this->module = $this->request->getController();
		$this->model = Helper::loadModel($this->module);
		Session::authenticated();
		$this->viewer->assign('tpl_class', 'template-cart');
		$this->viewer->assign('module', $this->request->getController());
	}

	public function index(){
		$this->viewer->view(__FUNCTION__);
	}

	public function add(){

		//session_regenerate_id(true);
		$session_id = session_id();

		$post = $this->request->post;
		$customer = get_customer();
		$cart = Session::has('cart')? Session::get('cart'): [];
		try{

			$row = $this->model->Add([
				'quantity' => $post['quantity'],
				'customer_id' => !empty($customer)? $customer['id']: 0,
				'product_id' => $post['product_id'],
				'session_id' => $session_id,
				'is_cart' => $post['is_cart'],
			]);

			$product_model = Helper::loadModel('product');
			$product = $product_model->Get_Record($post['product_id']);
			$product = array_merge($product, $row);
			$cart['products'][$post['product_id']] = $product;
			Session::set('cart', $cart);

			$this->totals();

			$this->viewer->jsonHttpResponse([
				'title' => translate('CART_TITLE', $this->module),
				'msg' => sprintf(translate('ADD_DEFAULT', $this->module), $post['product_name']),
				'data' => Session::get('cart')
			]);

		}catch(\Throwable $ex){
			Log::getInstance('error_log')->write($ex->getMessage());
			$this->viewer->jsonHttpResponse([
				'title' => translate('CART_TITLE', $this->module),
				'err' => translate('ERROR_ADD_DEFAULT', $this->module),
			], false);
		}
	}

	public function remove(){
		$session_id = Session::get('SESSIONID'); //session_id();
		$post = $this->request->post;
		$customer = get_customer();
		$cart = Session::has('cart')? Session::get('cart'): [];

		try{
			$num_rows = $this->model->Remove([
				'customer_id' => !empty($customer)? $customer['id']: 0,
				'product_id' => $post['product_id'],
				'session_id' => $session_id,
			]);

			if($num_rows > 0):
				unset($cart['products'][$post['product_id']]);
				Session::set('cart', $cart);

				$this->totals();

				$this->viewer->jsonHttpResponse([
					'title' => translate('CART_TITLE', $this->module),
					'msg' => sprintf(translate('REMOVE_DEFAULT', $this->module), $post['product_name']),
					'data' => Session::get('cart')
				]);
			else:
				Throw new Exception(translate('ERROR_ADD_DEFAULT', $this->module));
			endif;

		}catch(\Throwable $ex) {
			Log::getInstance('error_log')->write($ex->getMessage());
			$this->viewer->jsonHttpResponse([
				'title' => translate('CART_TITLE', $this->module),
				'err' => translate('ERROR_ADD_DEFAULT', $this->module),
			], false);
		}
	}

	public function cart(){
		$cart = Session::has('cart')? Session::get('cart'): [];

		$this->viewer->assign('cart', $cart);
		$this->viewer->view('partials/Cart', isAjax());
	}

	public function shipping(){
		try{
			$cart = Session::has('cart')? Session::get('cart'): [];
			$post = $this->request->post;

			// Set Shipping
			$cart['shipping'] = $post;

			Session::set('cart', $cart);

			$this->totals();

			$this->viewer->jsonHttpResponse([
				'title' => translate('CART_TITLE', $this->module),
				'msg' => sprintf(translate('ADD_SHIPPING', $this->module), $post['carrier']),
				'data' => Session::get('cart')
			]);

		}catch(\Throwable $ex){
			Log::getInstance('error_log')->write($ex->getMessage());
			$this->viewer->jsonHttpResponse([
				'title' => translate('CART_TITLE', $this->module),
				'err' => translate('ERROR_ADD_SHIPPING', $this->module),
			], false);
		}
	}

}
