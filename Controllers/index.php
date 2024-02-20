<?php
class indexController extends mainController {

	public function __construct(){
		parent::__construct();
		Session::authenticated();
		$this->viewer->assign('tpl_class', 'template-index');
		$this->viewer->assign('module', $this->request->getController());
	}

	public function index(){
		//debug(Session::get_all(), true);
		$product_model = Helper::loadModel('product');
		$products = $product_model->Get_Records();

		$this->viewer->assign('products', $products);
		$this->viewer->view(__FUNCTION__);
	}

}
