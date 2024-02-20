<?php

class productController extends mainController {

	public function __construct(){
		parent::__construct();
		$this->module = $this->request->getController();
		$this->model = Helper::loadModel($this->module);
		Session::authenticated();
		$this->viewer->assign('module', $this->request->getController());
		$this->viewer->assign('tpl_class', 'template-product');
	}

	public function index(){
		$this->viewer->view(__FUNCTION__);
	}

	// Dynamic methods
	public function __call($product, $arguments) {

		$p_id = $this->model->Get_Product_Id($product);
		$product = $this->model->Get_Record($p_id['product_id']);

		/*debug([
			'product' => $product,
			'args' => $arguments,
		], true);*/
		//do a get
		/*if (preg_match('/^get_(.+)/', $name, $matches)) {
			$var_name = $matches[1];
			return $this->$var_name ? $this->$var_name : $arguments[0];
		}
		//do a set
		if (preg_match('/^set_(.+)/', $name, $matches)) {
			$var_name = $matches[1];
			$this->$var_name = $arguments[0];
		}*/

		$this->viewer->assign('product', $product);
		$this->viewer->view('index');
	}

	public function quick_view(){
		$post = $this->request->post;
		$product = $this->model->Get_Record($post['product_id']);

		$this->viewer->assign('quick_product', $product);
		$this->viewer->view('partials/Quick_View', isAjax());
	}

	public function wishlist(){

		$post = $this->request->post;
		$product = $this->model->Get_Record($post['product_id']);

		try{

			if($product['is_wishlist']):
				$label = 'REMOVED_WISHLIST';
				$this->model->Remove_From_Wishlist($post['product_id']);
			else:
				$label = 'ADDED_WISHLIST';
				$this->model->Add_To_Wishlist($post['product_id']);
			endif;

			$this->viewer->jsonHttpResponse([
				'title' => translate('WISHLIST', 'main'),
				'msg' => sprintf(translate($label, 'main'), $product['product_name']),
			]);
		}catch(\Throwable $ex){
			Log::getInstance('error_log')->write($ex->getMessage());
			$this->viewer->jsonHttpResponse([
				'title' => translate('CART_TITLE', 'main'),
				'err' => sprintf(translate('ERROR_WISHLIST', 'main'), $product['is_wishlist']? 'Eliminar': 'Agregar'),
			], false);
		}

	}

}
