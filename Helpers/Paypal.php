<?php

require_once 'libraries/treebes_using_composer/vendor/autoload.php';

use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\AccessTokenRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;

/**
 * Vtiger_Treebes_PayPal_Helper class
 */
final class Vtiger_Treebes_PayPal_Helper
{
	private $db;
	private $config;
	private $sandbox;
	private $client_id;
	private $client_secret;
	private $c_io_online_store_delivery_service_id;

	public function __construct()
	{
		$this->db = PearDatabase::getInstance();
		$this->config = Vtiger_Treebes_Config_Helper::getInstance();
		$this->sandbox = (int)$this->config->get('pg_paypal_sandbox_mode');
		$this->c_io_online_store_delivery_service_id = $this->config->get('c_io_online_store_delivery_service_id');
		if ($this->sandbox) {
			$this->client_id = $this->config->get('pg_paypal_sandbox_customer_id');
			$this->client_secret = $this->config->get('pg_paypal_sandbox_secret');
		} else {
			$this->client_id = $this->config->get('pg_paypal_live_customer_id');
			$this->client_secret = $this->config->get('pg_paypal_live_secret');
		}
	}

	public function validateCredentials($client_id, $client_secret, $sandbox)
	{
		if ($sandbox) {
			$environment = new SandboxEnvironment($client_id, $client_secret);
		} else {
			$environment = new ProductionEnvironment($client_id, $client_secret);
		}
		$client = new PayPalHttpClient($environment);
		$request = new AccessTokenRequest($environment);
		$client->execute($request);
	}

	public function createOrder($crmid, $return_url, $cancel_url)
	{
		if (Vtiger_Treebes_Util_Helper::getCRMEntityType($crmid) != 'SalesOrder') {
			throw new Exception(vtranslate('LBL_IS_NOT') . ' ' . vtranslate('SINGLE_SalesOrder', 'SalesOrder'));
		}

		global $doconvert;
		$_doconvert = $doconvert; // save orginal
		$doconvert = false; // SINO A VTIGER LE DA POR to_html()
		// get record model
		$sales_order_record_model = Vtiger_Record_Model::getInstanceById($crmid);
		$tfde_balance = $sales_order_record_model->get('tfde_balance');
		$currency_info = $sales_order_record_model->getCurrencyInfo();

		// account model
		$account_id = $sales_order_record_model->get('account_id');
		$account_record_model = Vtiger_Record_Model::getInstanceById($account_id, 'Accounts');
		// products
		$products = $sales_order_record_model->getProducts();
		$final_details = $products[1]['final_details'];
		// company model
		$vtiger_companydetails_model = Vtiger_CompanyDetails_Model::getInstanceById();

		$doconvert = $_doconvert; // revert $doconvert

		$ship_num_int = $sales_order_record_model->get('ship_num_int');
		if ($ship_num_int) {
			$ship_num_int = "Interior: {$ship_num_int},";
		}

		$body = [
			'intent' => 'CAPTURE',
			'application_context' => [
				'return_url' => $return_url,
				'cancel_url' => $cancel_url,
				'brand_name' => $vtiger_companydetails_model->get('organizationname'),
				'locale' => 'es-MX',
				'landing_page' => 'LOGIN',
				'shipping_preference' => 'SET_PROVIDED_ADDRESS',
				'user_action' => 'PAY_NOW',
			],
			'purchase_units' => [
				[
					// 'reference_id' => 'PUHF',
					// 'description' => 'Sporting Goods',
					'custom_id' => $crmid,
					// 'soft_descriptor' => 'HighFashions',
					'amount' => [
						'currency_code' => $currency_info['currency_code'],
						'value' => $this->round($tfde_balance),
						'breakdown' => [
							'item_total' => [
								'currency_code' => $currency_info['currency_code'],
								'value' => 0,
							],
							'tax_total' => [
								'currency_code' => $currency_info['currency_code'],
								'value' => 0,
							],
							'shipping' => [
								'currency_code' => $currency_info['currency_code'],
								'value' => 0,
							],
							'handling' => [
								'currency_code' => $currency_info['currency_code'],
								'value' => 0,
							],
							'insurance' => [
								'currency_code' => $currency_info['currency_code'],
								'value' => 0,
							],
							'shipping_discount' => [
								'currency_code' => $currency_info['currency_code'],
								'value' => 0,
							],
							'discount' => [
								'currency_code' => $currency_info['currency_code'],
								'value' => 0,
							],
						],
					],
					'items' => [],
					'shipping' => [
						'name' => [
							'full_name' => Vtiger_Treebes_Util_Helper::normalize("{$account_record_model->get('firstname')} {$account_record_model->get('lastname')}"),
						],
						'address' => [
							'address_line_1' => Vtiger_Treebes_Util_Helper::normalize("{$sales_order_record_model->get('ship_street')} {$sales_order_record_model->get('ship_num_ext')}"),
							'address_line_2' => Vtiger_Treebes_Util_Helper::normalize("{$ship_num_int} {$sales_order_record_model->get('ship_pobox')}"),
							'admin_area_2' => $sales_order_record_model->get('ship_city'),
							'admin_area_1' => $sales_order_record_model->get('ship_state'),
							'postal_code' => $sales_order_record_model->get('ship_code'),
							'country_code' => 'MX', // $sales_order_record_model->get('ship_country'),
						],
					],
				],
			],
		];

		// Backorders
		$delivery_service = $this->c_io_online_store_delivery_service_id;
		$backorder_flow = false;
		if($sales_order_record_model->get('tfde_pagado') > 0 && $sales_order_record_model->get('backorder_salesorderid'))
			$backorder_flow = true;

		// loop $products
		$item_total = 0;
		$shipping_key = 0;
		foreach ($products as $key => $value) {
			$unit_price = $this->round($value['netPrice' . $key] / $value['qty' . $key]);
			$quantity = (float)$value['qty' . $key];
			if(!$backorder_flow){
				if ($value['hdnProductId' . $key] == $delivery_service) {
					$shipping_key = $key;
					continue;
				}
				$item_total += ($unit_price * $quantity);
				$body['purchase_units'][0]['items'][] = [
					'name' => $value['productName' . $key],
					// 'description' => 'Green XL',
					'sku' => $value['hdnProductcode' . $key],
					'unit_amount' => [
						'currency_code' => $currency_info['currency_code'],
						'value' => $unit_price,
					],
					'quantity' => $quantity,
					'category' => 'PHYSICAL_GOODS',
				];
			}else{
				if ($value['hdnProductId' . $key] !== $delivery_service)
					continue;

				$p_title = $value['productName' . $key] . " Backorder: {$sales_order_record_model->get('salesorder_no')}";
				$item_total += ($unit_price * $quantity);
				$body['purchase_units'][0]['items'][] = [
					'name' => $p_title,
					// 'description' => 'Green XL',
					'sku' => $value['hdnProductcode' . $key],
					'unit_amount' => [
						'currency_code' => $currency_info['currency_code'],
						'value' => $unit_price,
					],
					'quantity' => $quantity,
					'category' => 'PHYSICAL_GOODS',
				];
			}

		}
		$body['purchase_units'][0]['amount']['breakdown']['item_total'] = [
			'currency_code' => $currency_info['currency_code'],
			'value' => $this->round($item_total),
		];

		// Reward Points
		$model = Vtiger_Treebes_StoreApi_Model::getInstance();
		$reward_points = $model->getFdEPuntosRecompensa($crmid);

		if ($final_details['discountTotal_final'] != '0.00') {
			$amount_discount = $reward_points['abonado'] > 0? $final_details['discountTotal_final'] + $reward_points['abonado']: $final_details['discountTotal_final'];
			$body['purchase_units'][0]['amount']['breakdown']['discount'] = [
				'currency_code' => $currency_info['currency_code'],
				'value' => $this->round($amount_discount),
			];
		}else{
			if($reward_points['abonado'] > 0){
				$body['purchase_units'][0]['amount']['breakdown']['discount'] = [
					'currency_code' => $currency_info['currency_code'],
					'value' => $this->round($reward_points['abonado']),
				];
			}
		}

		// Shipment
		if ($shipping_key) {
			$body['purchase_units'][0]['amount']['breakdown']['shipping'] = [
				'currency_code' => $currency_info['currency_code'],
				'value' => $this->round($products[$shipping_key]['netPrice' . $shipping_key]),
			];
		}
		// adjust total, {"name":"UNPROCESSABLE_ENTITY","details":[{"field":"/purchase_units/0/amount/value","value":"597.31","issue":"AMOUNT_MISMATCH","description":"Should equal item_total + tax_total + shipping + handling + insurance - shipping_discount - discount."}],"message":"The requested action could not be performed, semantically incorrect, or failed business validation.","debug_id":"dc50e75200db6","links":[{"href":"https://developer.paypal.com/docs/api/orders/v2/#error-AMOUNT_MISMATCH","rel":"information_link","method":"GET"}]}
		$total = 0;
		$subtract = [
			'shipping_discount',
			'discount',
		];
		foreach ($body['purchase_units'][0]['amount']['breakdown'] as $key => $value) {
			if (in_array($key, $subtract)) {
				$total -= $value['value'];
			} else {
				$total += $value['value'];
			}
		}
		$diff = $this->round($this->round($tfde_balance) - $total);
		if ($diff > 0) { // add to handling
			$body['purchase_units'][0]['amount']['breakdown']['handling']['value'] += $diff;
		} elseif ($diff < 0) { // subtract to discount
			$body['purchase_units'][0]['amount']['breakdown']['discount']['value'] -= $diff;
		}
		// end adjust total
		// return $body;
		$client = $this->getClient();
		$request = new OrdersCreateRequest();
		$request->headers['prefer'] = 'return=representation';
		$request->body = $body;
		$response = $client->execute($request);
		return $response;
	}

	public function captureOrder($order_id, $userId = false)
	{
		$client = $this->getClient();
		$orders_get_request = new OrdersGetRequest($order_id);
		$response = $client->execute($orders_get_request);
		if ($response->result->status == 'APPROVED') {
			$orders_capture_request = new OrdersCaptureRequest($order_id);
			$client->execute($orders_capture_request);
			$response = $client->execute($orders_get_request);
		}

		// register TreebesFdE
		$this->registerFullPayment($response, $userId);

		return $response;
	}

	/**
	 * @throws Exception
	 */
	protected function registerFullPayment(PayPalHttp\HttpResponse $response, $user_id = 1)
	{
		if ($response->result->status != 'COMPLETED')
			return; // there is nothing to capture

		$transaction = $response->result->id;
		if ($this->getTreebesFdEByTransaction($transaction))
			return; // already captured

		$purchase_unit = reset($response->result->purchase_units);
		$crmid = (int)$purchase_unit->custom_id;
		if (Vtiger_Treebes_Util_Helper::getCRMEntityType($crmid) != 'SalesOrder')
			return; // fail silently

		$pg_data = [
			'treebescybid' => $this->config->get('pg_paypal_treebescybid'),
			'transaction' => $transaction,
			'response' => (array) $response,
			'method' => 'Tarjeta de Credito',
		];
		TreebesFdE_Module_Model::process_treebesFdE($crmid, $pg_data, $user_id);
	}

	public function getTreebesFdEByTransaction($transaction)
	{
		$crmid = 0;

		$currentUser = Users_Record_Model::getCurrentUserModel();
		$queryGenerator = new EnhancedQueryGenerator('TreebesFdE', $currentUser);
		$queryGenerator->setFields([
			'id'
		]);
		$queryGenerator->addCondition('transaction', $transaction, 'e');
		$query = $queryGenerator->getQuery();

		$result = $this->db->pquery($query);

		$crmid = $this->db->query_result($result, 0);

		if ($crmid) {
			return $crmid;
		}
		return false;
	}
	private function getClient()
	{
		if ($this->sandbox) {
			$environment = new SandboxEnvironment($this->client_id, $this->client_secret);
		} else {
			$environment = new ProductionEnvironment($this->client_id, $this->client_secret);
		}
		$client = new PayPalHttpClient($environment);
		return $client;
	}
	private function round($value)
	{
		return round($value, 2);
	}
}
