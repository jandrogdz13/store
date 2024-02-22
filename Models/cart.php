<?php

class cartModel extends Model{

	public function __construct(){
		parent::__construct();
	}

	public function Get_Record(int $record_id): array{
		$db = dbPDO::get_instance();
		$sql = '
			SELECT
				store_cart.id,
				store_cart.session_id,
				store_cart.product_id,
				store_cart.customer_id,
				store_cart.quantity
			FROM store_cart
			WHERE store_cart.id = :id
		';
		$db->prepare($sql, [
			'id' => $record_id
		]);
		return $db->fetch();
	}

	public function Get_Records(string $session_id, int $customer_id){
		$db = dbPDO::get_instance();
		$sql = '
			SELECT
				store_cart.id,
				store_cart.session_id,
				store_cart.product_id,
				store_cart.customer_id,
				store_cart.quantity
			FROM store_cart
			WHERE store_cart.session_id = :session_id
				AND store_cart.customer_id = :customer_id
				AND store_cart.related_to = 0
		';
		$db->prepare($sql, [
			'session_id' => $session_id,
			'customer_id' => $customer_id
		]);

		$records = $db->fetchAll();
		$product_model = Helper::loadModel('product');
		$products_cart = [];
		foreach($records as &$record):
			$product = $product_model->Get_Record($record['product_id']);
			$record = array_merge($product, $record);
			$products_cart[$record['product_id']] = $record;
		endforeach;
		unset($record);

		return $products_cart;
	}

	public function Add(array $data): array{
		$db = dbPDO::get_instance();
		$cart_id = false;
		$db->beginTransaction();

		try {
			$row = $this->Validate_Product_Exists($data);
			if($row):
				$cart_id = $row['id'];
				$sql = '
					UPDATE store_cart
				    SET
						store_cart.quantity = :quantity
					WHERE store_cart.session_id = :session_id
						AND store_cart.customer_id = :customer_id
						AND store_cart.product_id = :product_id
				';
				$quantity = $data['is_cart']
					? $data['quantity']
					: $row['quantity'] + $data['quantity'];
				$db->query($sql, [
					'quantity' => $quantity,
					'session_id' => $data['session_id'],
					'customer_id' => $data['customer_id'],
					'product_id' => $data['product_id'],
				]);
			else:
				$sql = '
				INSERT INTO store_cart
				    SET
						store_cart.session_id = :session_id,
						store_cart.customer_id = :customer_id,
						store_cart.product_id = :product_id,
						store_cart.quantity = :quantity,
						store_cart.date_added = :date
				';
				$db->query($sql, [
					'session_id' => $data['session_id'],
					'customer_id' => $data['customer_id'],
					'product_id' => $data['product_id'],
					'quantity' => $data['quantity'],
					'date' => getCurrentDate(),
				]);
				$cart_id = $db->lastInsertId();
			endif;
			$db->commit();
			return $this->Get_Record($cart_id);
		}catch(Exception $ex){
			$db->rollBack();
			Throw new Exception($ex->getMessage());
		}

	}

	public function Remove(array $data): int{
		$db = dbPDO::get_instance();
		$db->beginTransaction();
		try {
			$sql = '
				DELETE FROM store_cart
				WHERE store_cart.session_id = :session_id
					AND store_cart.customer_id = :customer_id
					AND store_cart.product_id = :product_id
			';
			$db->query($sql, [
				'session_id' => $data['session_id'],
				'customer_id' => $data['customer_id'],
				'product_id' => $data['product_id'],
			]);
			$db->commit();
			return $db->num_rows();
		}catch(\Exception $ex){
			$db->rollBack();
			Throw new Exception($ex->getMessage());
			return 0;
		}

	}

	public function Validate_Product_Exists(array $data): array{
		$db = dbPDO::get_instance();
		$sql = '
				SELECT
				    store_cart.id,
				    store_cart.session_id,
				    store_cart.product_id,
				    store_cart.customer_id,
				    store_cart.quantity
				FROM store_cart
				WHERE store_cart.session_id = :session_id
				  	AND store_cart.customer_id = :customer_id
					AND store_cart.product_id = :product_id
			';
		$db->prepare($sql, [
			'session_id' => $data['session_id'],
			'customer_id' => $data['customer_id'],
			'product_id' => $data['product_id'],
		]);
		return $db->fetch();
	}

}
