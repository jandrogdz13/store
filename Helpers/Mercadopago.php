<?php

Helper::load_helper('Http');
Helper::load_helper('ApiResponse');
/**
 * Vtiger_Treebes_MercadoPago_Helper class
 */
final class MercadoPago_Helper
{
	use ApiResponse_Trait;
	private static $endpoint = 'https://api.mercadopago.com/';

	private $version = 'v1';
	private $db;
	private $config;
	private $public_key;
	private $access_token;

	private $pg_mp_sandbox_mode;
	public function __construct()
	{
		$this->db = dbPDO::get_instance();
		$this->endpoint = $this->endpoint . $this->version . '/';
		//$this->config = Vtiger_Treebes_Config_Helper::getInstance();
		$this->pg_mp_sandbox_mode = MP_SANDBOX; //(int)$this->config->get('pg_mp_sandbox_mode');
		//$this->c_io_online_store_delivery_service_id = $this->config->get('c_io_online_store_delivery_service_id');
		if ($this->pg_mp_sandbox_mode) {
			$this->public_key = MP_API_KEY_SANDBOX;
			$this->access_token = MP_TOKEN_SANDBOX;
		} else {
			$this->public_key = MP_API_KEY_PRODUCTION;
			$this->access_token = MP_TOKEN_PRODUCTION;
		}
	}

	/* public function validateCredentials($public_key, $access_token)
	{
		$public_key_result = HTTP::get(self::$endpoint . 'v1/payment_methods?public_key=' . $public_key);
		$this->validateHTTPStatusCode($public_key_result);
		$access_token_result = HTTP::get(
			self::$endpoint . 'v1/payment_methods',
			function ($http) use ($access_token) {
				$http->header('Authorization', 'Bearer ' . $access_token);
			}
		);
		$this->validateHTTPStatusCode($access_token_result);
		return true;
	} */

	/**
	 * undocumented function
	 *
	 * @return array
	 * @throws \Exception
	 **/
	public function createOrder($body){
		$response = Http_Helper::post(
			"{$this->endpoint}payments",
			function ($http) use ($body) {
				$access_token = MP_TOKEN_SANDBOX;
				$http->header('Authorization', 'Bearer ' . $access_token);
				$http->header('Content-Type', 'application/json');
				$http->header('X-Idempotency-Key', Session::get('SESSIONID'));
				$http->body(json_encode($body));
			}
		);
		$response->body = json_decode($response->body, true);
		return $response->body;
	}

	public function getPayment($id){
		$id = (int)$id;
		$access_token = $this->access_token;
		$response = Http_Helper::get(
			"{$this->endpoint}payments/{$id}",
			function ($http) use ($access_token) {
				$http->header('Authorization', 'Bearer ' . $access_token);
			}
		);
		//$this->validateHTTPStatusCode($response);
		$response->body = json_decode($response->body, true);
		return $response->body;
	}

	public function getOrder($id){
		$id = (int)$id;
		$access_token = $this->access_token;
		$response = Http_Helper::get(
			self::$endpoint . 'merchant_orders/' . $id,
			function ($http) use ($access_token) {
				$http->header('Authorization', 'Bearer ' . $access_token);
			}
		);
		$this->validateHTTPStatusCode($response);
		$response->body = json_decode($response->body, true);
		return $response->body;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function registerPayment($id, $user_id = 1)
	{
		$id = (int)$id;
		try {
			$payment = $this->getPayment($id);
		} catch (Exception $e) {
			return; // fail silently
		}
		if ($payment['status'] != 'approved')
			return; // not paid yet

		if (!($payment['transaction_details']['net_received_amount'] > 0))
			return; // not paid yet

		$crmid = (int)$payment['external_reference'];
		if (Vtiger_Treebes_Util_Helper::getCRMEntityType($crmid) != 'SalesOrder')
			return; // fail silently

		$id = 'MP-' . $id;
		if ($this->getTreebesFdEByTransaction($id))
			return; // already registered

		$pg_data = [
			'treebescybid' => $this->config->get('pg_mp_treebescybid'),
			'transaction' => $id,
			'response' => (array) $payment,
			'method' => 'Tarjeta de Credito',
		];
		TreebesFdE_Module_Model::process_treebesFdE($crmid, $pg_data, $user_id);
	}

}
