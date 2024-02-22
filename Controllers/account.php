<?php
Helper::load_helper('Mail');

class accountController extends mainController {

	public function __construct(){
		parent::__construct();
		$this->module = $this->request->getController();
		$this->model = Helper::loadModel($this->module);

		if(!in_array($this->request->getMethod(), ['login', 'register', 'recovery', 'logout']))
			Session::authenticated();

		$this->viewer->assign('module', $this->request->getController());
		$this->viewer->assign('scripts', $this->getScripts());
		$this->viewer->assign('tpl_class', 'template-account');
	}

	public function index(){
		$this->viewer->view(__FUNCTION__);
	}

	public function login(){
		if(Session::has('authenticated'))
			redirect();

		if(isAjax()):

			try{
				$post = $this->request->post;
				$email = $post['email'];
				$pass = encrypt_password($email, $post['password']);

				if(!$pass)
					$this->viewer->jsonHttpResponse([
						'title' => translate('ERR_TITLE', $this->module),
						'err' => translate('ERR_DEFAULT', $this->module),
					], false);

				if(!$this->model->Validate_Account_Exists($email))
					$this->viewer->jsonHttpResponse([
						'title' => translate('ERR_TITLE', $this->module),
						'err' => translate('ERR_ACCOUNT_EXISTS', $this->module),
					], false);


				if(!$this->model->Validate_Account_Is_Active($email))
					$this->viewer->jsonHttpResponse([
						'title' => translate('ERR_TITLE', $this->module),
						'err' => translate('ERR_IS_ACTIVE', $this->module),
					], false);

				$customer = $this->model->Do_Login($email, $pass);
				if(!$customer)
					$this->viewer->jsonHttpResponse([
						'title' => translate('ERR_TITLE', $this->module),
						'err' => translate('ERR_DEFAULT', $this->module),
					], false);

				// Create session
				Session::set('authenticated', true);
				Session::set("timeout", time());
				Session::set('customer', [
					'authenticated' => true,
					'id' => $customer['customer_id'],
					'name' => "{$customer['customer_name']} {$customer['customer_surname']}",
					'first_name' => $customer['customer_name'],
					'last_name' => $customer['customer_surname'],
					'phone' => $customer['phone'],
					'email' => $customer['email'],
				]);

				// Retrive last cart
				$this->create_cart($customer['customer_id']);

				$this->viewer->jsonHttpResponse([
					'title' => translate('LOGIN_TITLE', $this->module),
					'msg' => translate('LOGIN_SUCCESS', $this->module),
				]);
				// Catch cookie current page to redirect after login
				//redirect(); // Redirect Home

			}catch(\Throwable $ex){
				Log::getInstance('error_log')->write($ex->getMessage());
				$this->viewer->jsonHttpResponse([
					'title' => translate('ERR_TITLE', $this->module),
					'err' => translate('ERR_LOGIN', $this->module),
				], false);
			}

		else:
			$this->viewer->view(__FUNCTION__);
		endif;

	}

	public function register(){

		if(Session::has('authenticated'))
			redirect();

		if(isAjax()):
			$post = $this->request->post;
			$first_name = $post['first_name'];
			$last_name = $post['last_name'];
			$phone = $post['phone'];
			$email = $post['email'];
			$pass = encrypt_password($email, $post['password']);

			if(empty($first_name) || empty($last_name) || empty($email) || empty($pass) || empty($phone))
				$this->viewer->jsonHttpResponse([
					'title' => translate('ERR_TITLE', $this->module),
					'err' => translate('ERR_EMPTY_ENTRY', $this->module),
				], false);

			if(!$pass)
				$this->viewer->jsonHttpResponse([
					'title' => translate('ERR_TITLE', $this->module),
					'err' => translate('ERR_DEFAULT_REG', $this->module),
				], false);

			if($this->model->Validate_Account_Exists($email))
				$this->viewer->jsonHttpResponse([
					'title' => translate('ERR_TITLE', $this->module),
					'err' => translate('ERR_ACCOUNT_EXISTS_REG', $this->module),
				], false);

			$customer = $this->model->Do_Register($post);
			if(!$customer)
				$this->viewer->jsonHttpResponse([
					'title' => translate('ERR_TITLE', $this->module),
					'err' => translate('ERR_DEFAULT_REG', $this->module),
				], false);

			// Create session
			Session::set('authenticated', true);
			Session::set("timeout", time());
			Session::set('customer', [
				'id' => $customer['customer_id'],
				'name' => "{$customer['customer_name']} {$customer['customer_surname']}",
				'first_name' => $customer['customer_name'],
				'last_name' => $customer['customer_surname'],
				'phone' => $customer['phone'],
				'email' => $customer['email'],
			]);

			// Retrive last cart
			$this->create_cart($customer['customer_id']);

			$mail = new Mail_Helper();
			$template = $mail->register_template($customer['customer_name']);
			$mail->send('Gracías por registrate en Mobel Inn', $template, Session::get('customer'));

			$this->viewer->jsonHttpResponse([
				'title' => translate('TITLE_SUCCESS', $this->module),
				'msg' => translate('MSG_SUCCESS', $this->module),
			]);

		// Catch cookie current page to redirect after login
		//redirect(); // Redirect Home
		else:
			$this->viewer->view(__FUNCTION__);
		endif;
	}

	/*public function confirm(){
		$customer = Session::has('customer')? Session::get('customer'): [];

		if($customer):
			$mail = new Mail_Helper();
			$template = $mail->register_template($customer['name']);
			$mail->send('Gracías por registrate', $template, $customer);
		endif;

	}*/

	public function reset(){

		if(Session::has('authenticated'))
			redirect();

		if(isAjax()):

			$post = $this->request->post;
			$code = $post['code'];
			$customer = $this->model->Get_Data_Reset($code);

			$pass = encrypt_password($customer['email'], $post['password']);
			$password_confirm = encrypt_password($customer['email'], $post['password']);

			try{
				if($pass !== $password_confirm)
					$this->viewer->jsonHttpResponse([
						'title' => translate('ERR_TITLE', $this->module),
						'err' => translate('ERR_RESET', $this->module),
					], false);

				$changed = $this->model->Reset_Password($customer['email'], $pass);

				if(!$changed)
					$this->viewer->jsonHttpResponse([
						'title' => translate('ERR_TITLE', $this->module),
						'err' => translate('ERR_RESET', $this->module),
					], false);

				$url = BASE_URL . "account/login";

				$mail = new Mail_Helper();
				$template = $mail->reset_password([
					'url' => $url,
				]);
				$mail->send(
					'Contraseña restablecida',
					$template,
					$customer
				);

				$this->viewer->jsonHttpResponse([
					'title' => translate('RECOVERY_TITLE', $this->module),
					'msg' => translate('RESET_SUCCESS', $this->module),
					'data' => $changed,
				]);
			}catch(\Throwable $ex){
				custom_error_log($ex);
				$this->viewer->jsonHttpResponse([
					'title' => translate('ERR_TITLE', $this->module),
					'err' => translate('ERR_RESET_DEF', $this->module),
				], false);
			}
		else:

			$err = '';
			$args = $this->request->getArgs();
			$code = $args[0];
			if(empty($args))
				redirect();

			// Validate time
			$time = $this->model->Validate_Time_Code($code);
			if($time > 2)
				$err = translate('RESET_CODE', $this->module);


			$this->viewer->assign('code', $code);
			$this->viewer->assign('err', $err);
			$this->viewer->view(__FUNCTION__);
		endif;
	}

	public function forgot(){

		if(Session::has('authenticated'))
			redirect();

		if(isAjax()):
			$post = $this->request->post;
			$email = $post['email'];

			try{
				if(!$this->model->Validate_Account_Exists($email))
					$this->viewer->jsonHttpResponse([
						'title' => translate('ERR_TITLE', $this->module),
						'err' => translate('ERR_ACCOUNT_EXISTS', $this->module),
					], false);
				else

				$customer = $this->model->Get_Last_Code_Forgot($email);
				$code = $customer['code'];
				$url = BASE_URL . "account/reset/{$code}";

				$mail = new Mail_Helper();
				$template = $mail->forgot_password([
					'url' => $url,
				]);
				$mail->send(
					'Restablecer contraseña',
					$template,
					$customer
				);

				$this->viewer->jsonHttpResponse([
					'title' => translate('RECOVERY_TITLE', $this->module),
					'msg' => translate('RESET_MSG', $this->module),
					'data' => $code,
				]);
			}catch(\Throwable $ex){
				custom_error_log($ex);
				$this->viewer->jsonHttpResponse([
					'title' => translate('ERR_TITLE', $this->module),
					'err' => translate('ERR_RESET_DEF', $this->module),
				], false);
			}
		else:
			$this->viewer->view(__FUNCTION__);
		endif;
	}

	public function logout(){
		Session::destroy();
	}

	public function account(){
		$orders = $this->model->Get_Orders();

		//debug($orders, true);

		$checkout_model = Helper::loadModel('checkout');
		$addresses = $checkout_model->Get_Addresses_By_Customer();
		$wishlist = $this->model->Get_Wishlist();

		$this->viewer->assign('wishlist', $wishlist);
		$this->viewer->assign('addresses', $addresses);
		$this->viewer->assign('customer', Session::get('customer'));
		$this->viewer->assign('orders', $orders);
		$this->viewer->view('account', isAjax());
	}

	protected function getScripts(){
		$p_scripts = parent::getScripts();
		$scripts = [
			'index.js'
		];
		return array_merge($p_scripts, $scripts);
	}

}
