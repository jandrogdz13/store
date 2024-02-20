<?php

class categoryController extends mainController{

	public function __construct(){
		parent::__construct();
		$this->module = $this->request->getController();
		$this->model = Helper::loadModel($this->module);
		Session::authenticated();
		$this->viewer->assign('tpl_class', 'template-collection');
		$this->viewer->assign('module', $this->request->getController());
	}

	public function index(){
		// template store on index
		$this->viewer->view(__FUNCTION__);
	}

	// Dynamic methods
	public function __call($category, $arguments) {

		// Parent
		$parent = $this->model->Get_Category_Id($category);

		// Subcategory
		$sub_cat = !empty($arguments)? reset($arguments): '';
		$subcategory = $this->model->Get_Category_Id($sub_cat);

		// Get Products
		$arg = empty($arguments)
			? $parent['category_id']
			: $subcategory['category_id'];
		$product_model = Helper::loadModel('product');
		$products = $product_model->Get_Products_By_Category($arg);

		/*debug([
			'category' => $category,
			'category_id' => $parent['category_id'],
			'subcategory' => $sub_cat,
			'subcategory_id' => $subcategory['category_id'],
			'products' => $products
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

		$this->viewer->assign('category', $category);
		$this->viewer->assign('sub_category', $sub_cat);
		$this->viewer->assign('products', $products);
		$this->viewer->view('index');
	}

}
