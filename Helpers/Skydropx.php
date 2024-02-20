<?php

Helper::load_helper('Http');


final class Skydropx_Helper
{
	private $db;
	private $log;
	private $sandbox;
	private $environment;
	private $environmentRadar;
	private $token;

	private $address_from;

	public function __construct(){
		$this->db = dbPDO::get_instance();
		$this->log = Log::getInstance(gmdate('Ymd').'-skydropx');
		$this->sandbox = true;

		$this->address_from = [
			'province'	=> 'Jalisco',
			'city'		=> 'Zapopan',
			'name'		=> 'José Luis Godínez Sahagún',
			'zip'		=> '45190',
			'country'	=> 'MX',
			'address1'	=> 'Calle las Flores 115A',
			'company'	=> 'Mobel Inn',
			'address2'	=> 'Victor Hugo',
			'phone'		=> '1122334455',
			'email'		=> 'ventas@mobelinn.com'
		];

		if ($this->sandbox) {
			$this->token = SKYDROPX_API_KEY_SANDBOX;
			$this->environment = 'https://api-demo.skydropx.com/v1/';
			$this->environmentRadar = '';
		} else {
			$this->token = SKYDROPX_API_KEY_PRODUCTION;
			$this->environment = 'https://api.skydropx.com/v1/';
			$this->environmentRadar = 'https://radar-api.skydropx.com/v1/';
		}
	}

	/**
	 * quotations
	 * https://docs.skydropx.com/#quotations
	 *
	 * @param  string $cp_origen Zip code of origin.
	 * @param  string $cp_destino Zip code of destination
	 * @param  float $peso_kg Weight of Parcel, must be in KG.
	 * @param  float $alto_cm Height of Parcel, must be in CM.
	 * @param  float $ancho_cm Width of Parcel, must be in CM.
	 * @param  float $largo_cm Length of Parcel, must be in CM.
	 * @return array
	 *
	 * The field amount_local is used to indicate the price of the service. Depending on the zip code, they may have extra charges.
	 * Out of area means that zone is not covered for normal delivery and generates extra charges.
	 * out_of_area_service is used to indicate if the service is out of the area for normal delivery and if it's true.
	 * out_of_area_pricing have the pricing for this extra service.
	 * total_pricing have the sum of amount_local and out_of_area_pricing.
	 * days Estimated time of arrival.
	 * insurable means that the shipment could be insured declaring a cost. Not implemented yet in this API version.
	 * is_ocurre is used to indicate if the shipment is to a home address or to a branch office.
	 */
	public function getQuotations($cp_origen, $cp_destino, $peso_kg, $alto_cm, $ancho_cm, $largo_cm){
		$url = $this->environment.'quotations';
		$body = [
			'zip_from' => $cp_origen,
			'zip_to' =>	$cp_destino,
			'parcel' =>	[
				'weight' =>	$peso_kg,
				'height' =>	$alto_cm,
				'width'	 =>	$ancho_cm,
				'length' =>	$largo_cm
			],
		];

		$response = Http_Helper::post(($url), function($http) use ($body){
			$http->header('Content-Type', 'application/json');
			$http->header('Authorization', 'Token '.$this->token);
			$http->body(json_encode($body));
		});
		//$this->log->write(__FUNCTION__." Response:\n".json_encode($response));
		$bodyResponse = json_decode($response->body,true);
		return [
			'code' => $response->code,
			'body' => $bodyResponse
		];

	}

	/**
	 * getShipments
	 * https://docs.skydropx.com/#shipments
	 * https://docs.skydropx.com/#get-a-specific-shipment
	 *
	 * @param  int $page, default is 1. Number of page.
	 * @param  int $per_page,  default is 20. Quantity of records per page.
	 * @param  int $shipmentId,The ID of the Shipment to retrieve.
	 * @return array
	 *
	 * This endpoint retrieves all Shipments or retrieves a specific Shipment.
	 *
	 */
	public function getShipments($page = 1, $per_page = 20, $shipmentId = ''){
		$urlQuery = $shipmentId != '' ? '/' . $shipmentId: '?page=' . $page . '&per_page=' . $per_page;
		//$urlQuery=$shipmentId!=''?'/'.$shipmentId:'?page%5Bnumber%5D='.$page.'&page%5Bsize%5D='.$per_page;
		$url = $this->environment . 'shipments' . $urlQuery;
		$response = Http_Helper::get(($url), function($http){
			$http->header('Content-Type', 'application/json');
			$http->header('Authorization', 'Token '.$this->token);
		});
		//$this->log->write(__FUNCTION__." Response:\n".json_encode($response,JSON_UNESCAPED_SLASHES));
		$bodyResponse = $response->body;
		return [
			'code' => $response->code,
			'body' => $bodyResponse
		];
	}

	/**
	 * createShipments
	 * https://docs.skydropx.com/#create-a-shipment
	 * https://docs.skydropx.com/#create-an-international-shipment
	 *
	 * @param  array $dir_origen, arreglo de los datos del origen del que envia el paquete
	 * @param  array $paquetes, arreglo de arreglos con los datos de los paquetes a enviar
	 * @param  array $dir_destino, arreglo de los datos del destino del paquete
	 * @return array
	 *
	 * This endpoint creates a Shipment.
	 *
	 *
	 * $dir_origen	=	[
	 * 		"province"	=> "Ciudad de México",
	 * 		"city"		=> "Azcapotzalco",
	 * 		"name"		=> "Jose Fernando",
	 * 		"zip"		=> "02900",
	 * 		"country"	=> "MX",
	 * 		"address1"	=> "Av. Principal #234",
	 * 		"company"	=> "skydropx",
	 * 		"address2"	=> "Centro",
	 * 		"phone"		=> "5555555555",
	 * 		"email"		=> "skydropx@email.com"
	 * ]
	 * $paquetes	=	[
	 * 		[
	 * 			"weight"		=> 3,
	 * 			"distance_unit"	=> "CM",
	 * 			"mass_unit"		=> "KG",
	 * 			"height"		=> 10,
	 * 			"width"			=> 10,
	 * 			"length"		=> 10
	 * 		]
	 * ]
	 *	$dir_destino= [
	 *		"province"	=> "Jalisco",
	 *		"city"		=> "Guadalajara",
	 *		"name"		=> "Jorge Fernández",
	 *		"zip"		=> "44100",
	 *		"country"	=> "MX",
	 *		"address1"	=> " Av. Lázaro Cárdenas #234",
	 *		"company"	=> "-",
	 *		"address2"	=> "Americana",
	 *		"phone"		=> "5555555555",
	 *		"email"		=> "ejemplo@skydropx.com",
	 *		"reference"	=> "Frente a tienda de abarro",
	 *		"contents"	=> ""
	 *	]
	 */
	public function createShipments($parcels, $dir_destino) {

		$body = [
			"address_from" => $this->address_from,
			"parcels" => $parcels,
			"address_to" => $dir_destino,
			"consignment_note_class_code" => '56101500',
			"consignment_note_packaging_code" => 'Z01'

		];
		$url = $this->environment . 'shipments';

		$response = Http_Helper::post(($url), function($http) use ($body){
			$http->header('Content-Type', 'application/json');
			$http->header('Authorization', 'Token '.$this->token);
			$http->body(json_encode($body));
		});
		//$this->log->write(__FUNCTION__." Response:\n".json_encode($response,JSON_UNESCAPED_SLASHES));
		$bodyResponse = json_decode($response->body,true);
		return [
			'code' => $response->code,
			'body' => $bodyResponse
		];
	}

	/**
	 * getLabels
	 * https://docs.skydropx.com/#labels
	 * https://docs.skydropx.com/#get-a-specific-label
	 *
	 * @param  int $page, default is 1. Number of page.
	 * @param  int $per_page,  default is 20. Quantity of records per page.
	 * @param  int $labelId,The ID of the Label to retrieve.
	 * @return array
	 *
	 * This endpoint retrieves all Labels or retrieves a specific Label.
	 */
	public function getLabels($page=1,$per_page=20,$labelId=''){
		$urlQuery=$labelId!=''?'/'.$labelId:'?page='.$page.'&per_page='.$per_page;
		//$urlQuery=$shipmentId!=''?'/'.$shipmentId:'?page%5Bnumber%5D='.$page.'&page%5Bsize%5D='.$per_page;
		$url = $this->environment.'labels'.$urlQuery;
		$response = Vtiger_Treebes_Http_Helper::get(($url),function($http){
			$http->header('Content-Type', 'application/json');
			$http->header('Authorization', 'Token '.$this->token);
		});
		$this->log->write(__FUNCTION__." Response:\n".json_encode($response,JSON_UNESCAPED_SLASHES));
		$bodyResponse=(Vtiger_Treebes_Util_Helper::is_json($response->body)?json_decode($response->body,true):$response->body);
		return ['code'=>$response->code,'body'=>$bodyResponse];
	}

	/**
	 * createLabel
	 * https://docs.skydropx.com/#create-a-label
	 *
	 * This endpoint creates a Label.
	 *
	 * @param  int $rate_id
	 * @param  mixed $label_format
	 * @return array
	 */
	public function createLabel($rate_id, $label_format = 'pdf'){
		$body=[
			"rate_id" => (int)trim($rate_id),
			"label_format" => $label_format
		];

		$url = $this->environment.'labels';
		$response = Vtiger_Treebes_Http_Helper::post(($url),function($http) use ($body){
			$http->header('Content-Type', 'application/json');
			$http->header('Authorization', 'Token '.$this->token);
			$http->body(json_encode($body));
		});
		$this->log->write(__FUNCTION__." Response:\n".json_encode($response,JSON_UNESCAPED_SLASHES));
		$bodyResponse=(Vtiger_Treebes_Util_Helper::is_json($response->body)?json_decode($response->body,true):$response->body);
		return ['code'=>$response->code,'body'=>$bodyResponse];
	}

	/**
	 * cancelLabel
	 * https://docs.skydropx.com/#cancel-label-request
	 * This endpoint creates a Cancel Label Request.
	 *
	 * @param  mixed $tracking_number
	 * @param  mixed $reason
	 * @return void
	 */
	public function createCancelLabel($tracking_number,$reason) {

		$body=[
			"tracking_number"	=>$tracking_number,
			"reason"	=>	$reason
		];
		$url = $this->environment.'cancel_label_requests';
		$response = Vtiger_Treebes_Http_Helper::post(($url),function($http) use ($body){
			$http->header('Content-Type', 'application/json');
			$http->header('Authorization', 'Token '.$this->token);
			$http->body(json_encode($body));
		});
		$this->log->write(__FUNCTION__." Response:\n".json_encode($response,JSON_UNESCAPED_SLASHES));
		$bodyResponse=(Vtiger_Treebes_Util_Helper::is_json($response->body)?json_decode($response->body,true):$response->body);
		return ['code'=>$response->code,'body'=>$bodyResponse];
	}

	/**
	 * getCancelLabel
	 * https://docs.skydropx.com/#get-all-cancel-label-request
	 * https://docs.skydropx.com/#cancel-label-request
	 *
	 * @param  string $status, could be reviewing, rejected, approved. This accept more than one option separated by |
	 * @param  date $date, specific date to find canceled Labels
	 * @return void
	 */
	public function getCancelLabel($status="reviewing|rejected|approved",$date=''){
		$dateToFilter=!empty($date)?'&date='.$date:'';
		$urlQuery='?status='.$status.$dateToFilter;
		$url = $this->environment.'cancel_request'.$urlQuery;

		$response = Vtiger_Treebes_Http_Helper::get(($url),function($http){
			$http->header('Content-Type', 'application/json');
			$http->header('Authorization', 'Token '.$this->token);
		});
		$this->log->write(__FUNCTION__." Response:\n".json_encode($response,JSON_UNESCAPED_SLASHES));
		$bodyResponse=(Vtiger_Treebes_Util_Helper::is_json($response->body)?json_decode($response->body,true):$response->body);
		return ['code'=>$response->code,'body'=>$bodyResponse];
	}

	/**
	 * Radar
	 */


	/**
	 * getRadarShipments
	 * https://docs.skydropx.com/#radar-shipments
	 *
	 * @param  int $page, default is 1. Number of page.
	 * @param  int $per_page, default is 25. Quantity of records per page.
	 * @param  string $status, could be CREATED, PICKED_UP, IN_TRANSIT, LAST_MILE, DELIVEREDor EXCEPTION', This accept more than one option separated by |
	 * @param  string $order, could be ascending or descending
	 * @return array
	 *
	 * This endpoint retrieves all Shipments.
	 */
	public function getRadarShipments($page=1,$per_page=25,$status='CREATED|PICKED_UP|IN_TRANSIT|LAST_MILE|DELIVERED|EXCEPTION',$order='ascending',$shipmentId=''){
		$urlQuery=$shipmentId!=''?'/'.$shipmentId:'?page='.$page.'&per_page='.$per_page.'&status='.$status.'&order='.$order;
		$url = $this->environmentRadar.'shipments'.$urlQuery;
		$response = Vtiger_Treebes_Http_Helper::get(($url),function($http){
			$http->header('Content-Type', 'application/json');
			$http->header('Authorization', 'Token '.$this->token);
		});
		$this->log->write(__FUNCTION__." Response:\n".json_encode($response,JSON_UNESCAPED_SLASHES));
		$bodyResponse=(Vtiger_Treebes_Util_Helper::is_json($response->body)?json_decode($response->body,true):$response->body);
		return ['code'=>$response->code,'body'=>$bodyResponse];
	}


	/**
	 * getRadarTrakingSipments
	 * https://docs.skydropx.com/#radar-tracking-shipments
	 *
	 * This endpoint returns an tracking shipments.
	 * @param  array $tracking_numbers
	 * @return array
	 *
	 * [
	 * 		[
	 * 			"carrier"=>"DHL",
	 * 			"tracking_number"=>"11111111"
	 *		]
	 * ]
	 */
	public function getRadarTrakingSipments($tracking_numbers){
		$body=$tracking_numbers;
		$url = $this->environment.'tracking';
		$response = Vtiger_Treebes_Http_Helper::post(($url),function($http) use ($body){
			$http->header('Content-Type', 'application/json');
			$http->header('Authorization', 'Token '.$this->token);
			$http->body(json_encode($body));
		});
		$this->log->write(__FUNCTION__." Response:\n".json_encode($response,JSON_UNESCAPED_SLASHES));
		$bodyResponse=(Vtiger_Treebes_Util_Helper::is_json($response->body)?json_decode($response->body,true):$response->body);
		return ['code'=>$response->code,'body'=>$bodyResponse];
	}
}
