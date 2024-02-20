<?php

class mainModel extends Model{

	public function __construct(){
		parent::__construct();
	}

	public function get_last_cart(int $customer_id, string $session_id): array{
		$db = dbPDO::get_instance();
		$sql = '
			SELECT
			    store_cart.id,
				store_cart.session_id,
				store_cart.product_id,
				store_cart.customer_id,
				store_cart.quantity
			FROM store_cart
			WHERE store_cart.customer_id = :customer_id
		  		AND store_cart.session_id = :session_id
		  		AND store_cart.related_to = 0
		';
		$db->prepare($sql, [
			'customer_id' => $customer_id,
			'session_id' => $session_id
		]);
		return $db->fetchAll();
	}

	public function assign_cart_customer(int $customer_id, string $session_id): int{
		$db = dbPDO::get_instance();
		$sql = '
			UPDATE store_cart
				SET
				    store_cart.customer_id = :customer_id
			WHERE store_cart.related_to = 0
		  		AND store_cart.session_id = :session_id
		  		AND store_cart.customer_id = 0
		';
		$db->query($sql, [
			'customer_id' => $customer_id,
			'session_id' => $session_id
		]);
		return $db->num_rows();
	}

	public function get_session_id(int $customer_id): array{
		$db = dbPDO::get_instance();
		$sql = '
			SELECT DISTINCT store_cart.session_id
			FROM store_cart
			WHERE store_cart.customer_id = :customer_id
				AND store_cart.related_to = 0
			ORDER BY store_cart.date_added DESC
		';
		$db->prepare($sql, [
			'customer_id' => $customer_id
		]);
		return $db->fetch();
	}

	public function getSliders(){

	}

	public function getCategories(){

	}

	public function getProducts(){

	}

	public function getProduct($recordid){

	}

	public function getCollections(){

	}

	public function getSocialNetworks(){

	}



}
