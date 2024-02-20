<?php

class policiesController extends Controller{

	public function __construct(){
		parent::__construct();
		Session::authenticated();
		$this->viewer->assign('tpl_class', 'template-policies');
		$this->viewer->assign('module', $this->request->getController());
	}

	public function index(){}

	// Dynamic methods
	public function __call($seo, $arguments) {
		debug([$seo, $arguments], true);

		switch($seo):
			case 'terms-and-conditions':
				$this->terms_and_conditions();
			break;
			case 'privacy-policy':
				$this->privacy_policy();
			break;
			case 'shipping-policy':
				$this->shipping_policy();
			break;
			case 'refound-policy':
				$this->refund_policy();
			break;
		endswitch;
	}

	public function terms_and_conditions(){
		$this->viewer->view('page');
	}

	public function privacy_policy(){
		$this->viewer->view('page');
	}

	public function shipping_policy(){
		$this->viewer->view('page');
	}

	public function refund_policy(){
		$this->viewer->view('page');
	}
}
