<?php

class accountModel extends Model{

	public function __construct(){
		parent::__construct();
	}

	public function Get_Record($record_id){
		$db = dbPDO::get_instance();
		$sql = "
				SELECT
				    store_customer.customer_id,
				    CONCAT(store_customer.customer_name, ' ', store_customer.customer_surname) AS name,
				    store_customer.customer_name,
				    store_customer.customer_surname,
				    store_customer.email,
				    store_customer.phone,
				    store_customer.status
				FROM store_customer
				WHERE store_customer.customer_id = :customer_id
			";
		$db->prepare($sql, [
			'customer_id' => $record_id
		]);
		return $db->fetch();
	}

	public function Validate_Account_Exists(String $email): bool{
		$db = dbPDO::get_instance();
		$sql = '
				SELECT store_customer.customer_id
				FROM store_customer
				WHERE store_customer.email = :email
			';
		$db->prepare($sql, [
			'email' => $email
		]);
		$record = $db->fetch();
		return !empty($record['customer_id']);
	}

	public function Validate_Account_Is_Active(String $email): bool{
		$db = dbPDO::get_instance();
		$sql = '
			SELECT store_customer.customer_id
			FROM store_customer
			WHERE store_customer.email = :email AND store_customer.status = 1
		';
		$db->prepare($sql, [
			'email' => $email
		]);
		$record = $db->fetch();
		return !empty($record['customer_id']);
	}

	public function Do_Login(String $email, String $pass): array{
		$db = dbPDO::get_instance();
		$sql = '
				SELECT
				    store_customer.customer_id,
				    store_customer.customer_name,
				    store_customer.customer_surname,
				    store_customer.phone,
				    store_customer.email
				FROM store_customer
				WHERE store_customer.email = :email AND store_customer.pass = :pass
			';
		$db->prepare($sql, [
			'email' => $email,
			'pass' => $pass,
		]);
		return $db->fetch();
	}

	public function Do_Register(array $data): array{
		$db = dbPDO::get_instance();
		$data['password'] = encrypt_password($data['email'], $data['password']);
		$sql = '
				INSERT INTO store_customer
				    SET
						store_customer.customer_name = :customer_name,
						store_customer.customer_surname = :customer_surname,
						store_customer.email = :email,
						store_customer.pass = :pass,
						store_customer.phone = :phone
			';
		$db->query($sql, [
			'customer_name' => $data['first_name'],
			'customer_surname' => $data['last_name'],
			'email' => $data['email'],
			'pass' => $data['password'],
			'phone' => $data['phone'],
		]);

		// Retrive last insert ID
		$customer_id = $db->lastInsertId();
		return $this->Get_Record($customer_id);
	}

	public function Get_Last_Code_Forgot(string $email): array{
		$db = dbPDO::get_instance();

		try{
			// Get Customer ID by email
			$sql = '
				SELECT
					store_customer.customer_id
				FROM store_customer
				WHERE store_customer.email = :email
			';
			$db->prepare($sql, [
				'email' => $email
			]);
			$c_id = $db->fetch();
			$customer = $this->Get_Record($c_id['customer_id']);
			$customer_id = $customer['customer_id'];

			$sql = '
				SELECT
					forgot_customer.code,
					forgot_customer.timestamp_code
				FROM forgot_customer
				WHERE forgot_customer.customer_id = :customer_id
			';
			$db->prepare($sql, [
				'customer_id' => $customer_id,
			]);
			$record = $db->fetch();

			if($record):
				$created_date = date('Y-m-d H:i:s', $record['timestamp_code']);
				$d1 = new DateTime($created_date);
				$d2 = new DateTime(getCurrentDate('Y-m-d H:i:s'));
				$interval = $d1->diff($d2);
				$hours = ($interval->days * 24) + $interval->h;
				if($hours <= 2):
					$customer['code'] = $record['code'];
					return $customer;
				endif;
			endif;

			$code = uniqid();
			$sql = '
				INSERT INTO forgot_customer
				SET
					forgot_customer.code = :code,
					forgot_customer.timestamp_code = :timestamp,
					forgot_customer.customer_id = :customer_id
			';
			$db->query($sql, [
				'code' => $code,
				'timestamp' => time(),
				'customer_id' => $customer_id
			]);

			$customer['code'] = $code;
			return $customer;
		}catch(Exception $e){
			throw new Exception($e->getMessage());
		}

	}

	public function Get_Data_Reset(string $code): array{

		$db = dbPDO::get_instance();
		$sql = '
				SELECT
					forgot_customer.customer_id
				FROM forgot_customer
				WHERE forgot_customer.code = :code
			';
		$db->prepare($sql, [
			'code' => $code,
		]);
		$record = $db->fetch();
		return $this->Get_Record($record['customer_id']);
	}

	public function Validate_Time_Code(string $code): int{

		$db = dbPDO::get_instance();
		try{
			$sql = '
				SELECT
					forgot_customer.code,
					forgot_customer.timestamp_code
				FROM forgot_customer
				WHERE forgot_customer.code = :code
			';
			$db->prepare($sql, [
				'code' => $code,
			]);
			$record = $db->fetch();

			if($record):
				$created_date = date('Y-m-d H:i:s', $record['timestamp_code']);
				$d1 = new DateTime($created_date);
				$d2 = new DateTime(getCurrentDate('Y-m-d H:i:s'));
				$interval = $d1->diff($d2);
				$hours = ($interval->days * 24) + $interval->h;
				return $hours;
			endif;

			return 5;
		}catch(Exception $e){
			throw new Exception($e->getMessage());
		}

	}

	public function Reset_Password(string $email, string $pass): int{
		$db = dbPDO::get_instance();
		$sql = '
			UPDATE store_customer
				SET
				    store_customer.pass = :pass
			WHERE store_customer.email = :email
		';
		$db->query($sql, [
			'pass' => $pass,
			'email' => $email,
		]);
		return $db->num_rows();
	}

	public function Get_Order(int $order_id): array{
		$db = dbPDO::get_instance();
		$sql = "
			SELECT
				orders.orderid,
				orders.created_date,
				orders.modified_date,
				IFNULL(orders.promise_date, '') AS promise_date,
				orders.customerid,
				orders.userid,
				orders.branchid,
				orders.stage,
				orders.priority,
				orders.origin,
				orders.amount,
				orders.aditionals,
				orders.invoice,
				store_customer.customer_name,
				store_customer.customer_surname,
				store_customer.email,
				store_customer.phone,
				store_cart_order_detail.address,
				store_cart_order_detail.shipping,
				store_cart_order_detail.payment
			FROM `order` AS orders
			INNER JOIN store_customer ON store_customer.customer_id = orders.customer_store_id
			INNER JOIN store_cart_order_detail ON store_cart_order_detail.order_id = orders.orderid
			WHERE orderid = :order_id
		";
		$db->prepare($sql, [
			'order_id' => $order_id
		]);
		$record = $db->fetch();

		$record['address'] = unserialize($record['address']);
		$record['shipping'] = unserialize($record['shipping']);
		$record['payment'] = unserialize($record['payment']);

		// Detail
		$detail = $this->Get_Detail_Record($order_id);
		$record['detail'] = $detail;

		$totals = [];
		foreach($detail as $prod):
			if(!$prod['is_service'])
				$totals['subtotal'] += $prod['amount'];
			else
				$totals['shipping'] += $prod['amount'];
		endforeach;
		$record['totals'] = $totals;

		return $record;
	}

	public function Get_Orders(): array{
		$db = dbPDO::get_instance();
		$customer = Session::get('customer');
		$sql = "
			SELECT
				orders.orderid,
				orders.created_date,
				orders.modified_date,
				IFNULL(orders.promise_date, '') AS promise_date,
				orders.customerid,
				orders.userid,
				orders.branchid,
				orders.stage,
				orders.priority,
				orders.origin,
				orders.amount,
				orders.aditionals,
				orders.invoice,
				store_customer.customer_name,
				store_customer.customer_surname,
				store_customer.email,
				store_customer.phone,
				store_cart_order_detail.address,
				store_cart_order_detail.shipping,
				store_cart_order_detail.payment
			FROM `order` AS orders
			INNER JOIN store_customer ON store_customer.customer_id = orders.customer_store_id
			INNER JOIN store_cart_order_detail ON store_cart_order_detail.order_id = orders.orderid
			WHERE orders.customer_store_id = :customer_id
			ORDER BY orders.orderid DESC
		";
		$db->prepare($sql, [
			'customer_id' => $customer['id']
		]);

		$records = $db->fetchAll();
		foreach($records as &$record):
			$record['address'] = unserialize($record['address']);
			$record['shipping'] = unserialize($record['shipping']);
			$record['payment'] = unserialize($record['payment']);

			// Detail
			$record['detail'] = $this->Get_Detail_Record($record['orderid']);
		endforeach;
		unset($record);


		return $records;
	}

	public function Get_Detail_Record(int $order_id): array{
		$db = dbPDO::get_instance();
		$sql = "
			SELECT
				`order`.orderid,
				`order`.created_date,
				`order`.invoice,
				`order`.stage AS order_stage,
			   	order_detail.detailid,
				order_detail.product,
				order_detail.quantity,
				order_detail.unit_price,
				order_detail.quantity * order_detail.unit_price AS amount,
				order_detail.notes,
				order_detail.d_type,
				order_detail.is_service,
                order_detail.stage AS detail_stage
			FROM order_detail
			INNER JOIN `order` ON `order`.orderid = order_detail.orderid
			WHERE `order`.orderid = :order_id
			ORDER BY `order`.orderid ASC
		";
		$db->prepare($sql, [
			'order_id' => $order_id,
		]);
		return $db->fetchAll();
	}

	public function Get_Directions(){

	}

	public function Get_Wishlist(){
		$customer = Session::get('customer');
		$db = dbPDO::get_instance();

		$sql = '
			SELECT store_product_wishlist.product_id
			FROM store_product_wishlist
			WHERE store_product_wishlist.customer_id = :customer_id
		';
		$db->prepare($sql, [
			'customer_id' => $customer['id'],
		]);

		$wishlist = $db->fetchAll();
		$ids = implode(',', array_column($wishlist, 'product_id'));

		if(!$ids)
			return [];

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
			WHERE store_product.status = 1 AND store_product.product_id IN ({$ids})
		";

		$db->prepare($sql);
		return $db->fetchAll();
	}
}
