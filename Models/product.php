<?php

class productModel extends Model{

	public function __construct(){
		parent::__construct();
	}

	public function Get_Product_Id(String $keyword): array{
		$db = dbPDO::get_instance();
		$sql = '
			SELECT product_id
			FROM store_product
			WHERE store_product.keyword = :keyword
		';
		$db->prepare($sql, [
			'keyword' => $keyword
		]);
		return $db->fetch();
	}

	public function Get_Records(): array{
		$customer = Session::get('customer');
		$db = dbPDO::get_instance();
		$sql = "
			WITH
			inventory AS (
				SELECT
					store_inventory.product_id,
					IFNULL(SUM(IF(store_inventory.action = 'INGRESO', store_inventory.quantity, 0)), 0) AS entries,
					IFNULL(SUM(IF(store_inventory.action = 'SALIDA', store_inventory.quantity, 0)), 0) AS sales
				FROM store_inventory
			)
			SELECT
				store_product.product_id,
				store_product.product_name,
				store_product.sku,
				store_product.min_stock,
				store_product.discount,
				IF(store_product.discount > 0, store_product.unit_price * (store_product.discount / 100), 0) AS discount_amount,
				store_product.unit_price,
				IF(store_product.discount > 0, store_product.unit_price * (1 - (store_product.discount / 100)), 0) AS unit_price_inc_discount,
				store_product.description,
				store_product.keyword,
				(inventory.entries - inventory.sales) AS stock,
				0 AS is_wishlist
			FROM store_product
			INNER JOIN inventory
				ON inventory.product_id = store_product.product_id
			WHERE store_product.status = 1
		";

		$db->prepare($sql);
		$records = $db->fetchAll();

		$conversion_rate = get_conversion_rate();

		// Wishlist
		$wishlist = [];
		if(!empty($customer)):
			$sql = '
				SELECT store_product_wishlist.product_id
				FROM store_product_wishlist
				WHERE store_product_wishlist.customer_id = :customer_id
			';
			$db->prepare($sql, [
				'customer_id' => $customer['id'],
			]);

			$wishlist = $db->fetchAll();
		endif;

		foreach($records as &$record):
			$record['is_wishlist'] = in_array($record['product_id'], array_column($wishlist, 'product_id'));

			// Currency
			if($_COOKIE['currency'] === 'USD'):
				$record['discount_amount'] = $record['discount_amount'] / $conversion_rate;
				$record['unit_price'] = $record['unit_price'] / $conversion_rate;
				$record['unit_price_inc_discount'] = $record['unit_price_inc_discount'] / $conversion_rate;
			endif;

		endforeach;
		unset($record);


		return $records;
	}


	// $amount * ($product['productTaxes']['IVA'] / 100
	public function Get_Record(int $record_id): array{
		$customer = Session::get('customer');
		$db = dbPDO::get_instance();
		$sql = "
			WITH
			inventory AS (
				SELECT
					store_inventory.product_id,
					IFNULL(SUM(IF(store_inventory.action = 'INGRESO', store_inventory.quantity, 0)), 0) AS entries,
					IFNULL(SUM(IF(store_inventory.action = 'SALIDA', store_inventory.quantity, 0)), 0) AS sales
				FROM store_inventory
				WHERE store_inventory.product_id = :product_id
			)
			SELECT
				store_product.product_id,
				store_product.product_name,
				store_product.sku,
				store_product.min_stock,
				store_product.discount,
				IF(store_product.discount > 0, store_product.unit_price * (store_product.discount / 100), 0) AS discount_amount,
				store_product.unit_price,
				IF(store_product.discount > 0, store_product.unit_price * (1 - (store_product.discount / 100)), store_product.unit_price) AS unit_price_inc_discount,
				store_product.description,
				store_product.keyword,
				(inventory.entries - inventory.sales) AS stock,
				0 AS is_wishlist
			FROM store_product
			INNER JOIN inventory
				ON inventory.product_id = store_product.product_id
			WHERE store_product.product_id = :record_id
		";
		$db->prepare($sql, [
			'product_id' => $record_id,
			'record_id' => $record_id,
		]);
		$record = $db->fetch();

		$conversion_rate = get_conversion_rate();

		// Wishlist
		if(!empty($customer)):
			$sql = '
				SELECT store_product_wishlist.product_id
				FROM store_product_wishlist
				WHERE store_product_wishlist.customer_id = :customer_id
			';
			$db->prepare($sql, [
				'customer_id' => $customer['id'],
			]);

			$wishlist = $db->fetchAll();
			$record['is_wishlist'] = in_array($record_id, array_column($wishlist, 'product_id'));
		endif;

		// Currency
		if($_COOKIE['currency'] === 'USD'):
			$record['discount_amount'] = $record['discount_amount'] / $conversion_rate;
			$record['unit_price'] = $record['unit_price'] / $conversion_rate;
			$record['unit_price_inc_discount'] = $record['unit_price_inc_discount'] / $conversion_rate;
		endif;

		return $record;
	}

	public function Get_Products_By_Category(int $category_id): array{
		$db = dbPDO::get_instance();
		$sql = '
			SELECT store_product.*
			FROM store_product
			INNER JOIN store_product_category
				ON store_product_category.product_id = store_product.product_id
			WHERE store_product_category.category_id = :category_id
		';
		$db->prepare($sql, [
			'category_id' => $category_id
		]);
		$records = $db->fetchAll();

		$conversion_rate = get_conversion_rate();

		foreach($records as &$record):
			// Currency
			if($_COOKIE['currency'] === 'USD'):
				$record['discount_amount'] = $record['discount_amount'] / $conversion_rate;
				$record['unit_price'] = $record['unit_price'] / $conversion_rate;
				$record['unit_price_inc_discount'] = $record['unit_price_inc_discount'] / $conversion_rate;
			endif;
		endforeach;
		unset($record);

		return $records;
	}

	public function Remove_From_Wishlist(int $record_id): int{
		$db = dbPDO::get_instance();
		$customer = Session::get('customer');

		// Delete
		$sql = '
			DELETE FROM store_product_wishlist
			WHERE store_product_wishlist.customer_id = :customer_id
				AND store_product_wishlist.product_id = :product_id
		';
		$db->query($sql, [
			'customer_id' => $customer['id'],
			'product_id' => $record_id
		]);

		return $db->num_rows();
	}

	public function Add_To_Wishlist(int $record_id): int{
		$db = dbPDO::get_instance();
		$customer = Session::get('customer');

		// Add
		$sql = '
			INSERT INTO store_product_wishlist
			SET store_product_wishlist.customer_id = :customer_id,
				store_product_wishlist.product_id = :product_id
		';
		$db->query($sql, [
			'customer_id' => $customer['id'],
			'product_id' => $record_id
		]);

		return $db->num_rows();
	}


}
