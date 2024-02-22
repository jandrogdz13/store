<?php

class checkoutModel extends Model{

	public function __construct(){
		parent::__construct();
	}

	public function Get_Addresses_By_Customer(): array{
		$customer = Session::get('customer');
		$db = dbPDO::get_instance();
		$sql = '
			SELECT address.*
			FROM address
			INNER JOIN store_customer_address
				ON store_customer_address.address_id = address.addressid
			WHERE store_customer_address.customer_id = :customer_id
		';
		$db->prepare($sql, [
			'customer_id' => $customer['id']
		]);
		return $db->fetchAll();
	}

	public function Get_Address(int $record_id): array{
		$db = dbPDO::get_instance();
		$sql = '
			SELECT *
			FROM address
			WHERE address.addressid = :address_id
		';
		$db->prepare($sql, [
			'address_id' => $record_id
		]);
		return $db->fetch();
	}

	public function Save_Address(array $data, int $record_id = 0): array{
		if($record_id)
			return $this->Update_Address($data, $record_id);
		else
			return $this->Insert_Address($data);
	}

	private function Insert_Address($data){
		$customer = Session::get('customer');
		$db = dbPDO::get_instance();
		$db->beginTransaction();
		try{
			$set = [];
			foreach($data as $key => $value):
				if(!empty(trim($value))):
					$value = $key == 'postcode'? $value: "'" . trim(strtoupper($value)) . "'";
					$set[] = "`{$key}` = {$value}";
				endif;
			endforeach;
			$set = implode(', ', $set);

			$sql = "
				INSERT INTO address
				SET
					{$set}
			";
			$db->query($sql);
			$address_id = $db->lastInsertId();

			$sql = '
				INSERT INTO store_customer_address
					SET
						customer_id = :customer_id,
						address_id = :address_id
			';
			$db->query($sql, [
				'customer_id' => $customer['id'],
				'address_id' => $address_id
			]);

			$record = $this->Get_Address($address_id);
			$db->commit();
			return $record;
		}catch(Exception $e){
			$db->rollBack();
			Throw new Exception($e->getMessage());
		}
	}

	private function Update_Address($data, $record_id){
		$db = dbPDO::get_instance();
		$db->beginTransaction();
		try {
			$set = [];
			foreach ($data as $key => $value):
				$value = $key == 'postcode' ? $value : "'" . trim(strtoupper($value)) . "'";
				$set[] = "`{$key}` = {$value}";
			endforeach;
			$set = implode(', ', $set);

			$sql = "
				UPDATE address
				SET
					{$set}
				WHERE address.addressid = :address_id
			";
			$db->query($sql, [
				'address_id' => $record_id
			]);

			$record = $this->Get_Address($record_id);
			$db->commit();
			return $record;
		} catch (Exception $e) {
			$db->rollBack();
			throw new Exception($e->getMessage());
		}
	}

	private function Get_User_Store(){
		$db = dbPDO::get_instance();
		$sql = "
			SELECT *
			FROM `user`
			WHERE LOWER(`user`.email) = 'tienda@mobelinn.com'
		";
		$db->prepare($sql);
		return $db->fetch();
	}

	private function Get_Customer_Store(){
		$db = dbPDO::get_instance();
		$sql = "
			SELECT *
			FROM customer
			WHERE LOWER(customer.customername) = 'tienda'
		";
		$db->prepare($sql);
		return $db->fetch();
	}

	public function Create_Order($data): int{
		$customer = Session::get('customer');
		$cart = Session::get('cart');
		$created_date = getCurrentDate('Y-m-d H:i:s');
		$user_store = $this->Get_User_Store();
		$customer_store = $this->Get_Customer_Store();
		$db = dbPDO::get_instance();
		$order_id = 0;

		$db->beginTransaction();
		try {
			// Create order
			$sql = "
				INSERT INTO `order`
					SET
					created_date = :created_date,
					amount = :amount,
					aditionals = :additionals,
					stage = 'Pedido',
					origin = 'TIENDA',
					userid = :userid,
					customerid = :customerid,
					branchid = :branchid,
					customer_store_id = :customer_store_id
			";
			$db->query($sql, [
				'created_date' => $created_date,
				'amount' => $cart['totals']['subtotal_inc_disc'] + $cart['totals']['shipping'],
				'additionals' => '', //$cart['comments'],
				'userid' => $user_store['userid'],
				'customerid' => $customer_store['customerid'],
				'branchid' => $customer_store['branchid'],
				'customer_store_id' => $customer['id'],
			]);
			$order_id = $db->lastInsertId();

			// Payment Detail
			$sql = '
				INSERT INTO store_cart_order_detail
				SET
					address = :address,
					shipping = :shipping,
					payment = :payment,
					order_id = :order_id
			';
			$db->query($sql, [
				'address' => serialize($cart['address']),
				'shipping' => serialize($cart['shipping']),
				'payment' => serialize($data),
				'order_id' => $order_id,
			]);

			// Create order detail
			foreach($cart['products'] as $product):
				$sql = "
					INSERT INTO order_detail
					SET
						orderid = :orderid,
						product = :product,
						quantity = :quantity,
						unit_price = :unit_price,
						stage = 'Sin asignar'
				";
				$db->query($sql, [
					'orderid' => $order_id,
					'product' => $product['product_name'],
					'quantity' => $product['quantity'],
					'unit_price' => $product['unit_price_inc_discount'],
				]);
			endforeach;

			// Add shipping item
			if($cart['shipping']):
				$shipping = $cart['shipping'];
				$product_name = "Servicio de envío {$shipping['provider']}({$shipping['service_level_name']})";
				$sql = "
					INSERT INTO order_detail
					SET
						orderid = :orderid,
						product = :product,
						quantity = 1,
						unit_price = :unit_price,
						stage = 'Sin asignar',
						is_service = 1
				";
				$db->query($sql, [
					'orderid' => $order_id,
					'product' => $product_name,
					'unit_price' => $shipping['total_pricing'],
				]);
			endif;


			// Update cart
			$sql = '
				UPDATE store_cart
				SET
					related_to = :order_id
				WHERE session_id = :session_id
					AND customer_id = :customer_id
					AND related_to = 0
			';
			$db->query($sql, [
				'order_id' => $order_id,
				'session_id' => Session::get('SESSIONID'),
				'customer_id' => $customer['id'],
			]);

			$db->commit();
			return $order_id;

		}catch(Exception $ex){
			$db->rollBack();
			throw new Exception($ex->getMessage());
		}

	}
}
