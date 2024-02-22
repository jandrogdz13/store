<?php

Helper::loadLibrary('vendor/autoload');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

final class Mail_Helper{

	private $mailer;
	private $logo;
	private $footer;
	private $header;
	public $files = [];
	private $icons = [
		'fb' => '',
		'inst' => '',
		'pint' => '',
	];

	public function __construct(){
		$this->logo = BASE_URL . 'Views/layout/' . DEFAULT_LAYOUT . '/images/logo_mobel.png';
		$this->header = file_get_contents(PATH_VIEWS . 'email/partials/header.html');
		$this->footer = file_get_contents(PATH_VIEWS . 'email/partials/footer.html');

		$this->icons['fb'] = BASE_URL . 'Views/layout/' . DEFAULT_LAYOUT . '/images/socials/facebook-logo-white.png';
		$this->icons['inst'] = BASE_URL . 'Views/layout/' . DEFAULT_LAYOUT . '/images/socials/instagram-logo-white.png';
		$this->icons['pint'] = BASE_URL . 'Views/layout/' . DEFAULT_LAYOUT . '/images/socials/pinterest-logo-white.png';

	}

	public function set_files($files){
		$this->files = $files;
	}

	public function order_template($order_id, $update = false){
		$account_model = Helper::loadModel('account');
		$order = $account_model->Get_Order($order_id);
		$created_date = translateDate($order['created_date'], true, true, true);

		$amount = 0;
		$subtotal = 0;
		$taxes = 0; //$subtotal * (16 / 100);
		$order_detail = '';
		foreach($order['detail'] as $row):
			if(!$row['is_service']):
				$subtotal += ($row['quantity'] * $row['unit_price']) * ((100 - 16) / 100);
				$taxes += ($row['quantity'] * $row['unit_price']) * (16 / 100);
				$amount += $row['quantity'] * $row['unit_price'];

				$img = BASE_URL . 'Views/layout/' . DEFAULT_LAYOUT . '/images/product-images/img_demo.webp';

				$product = file_get_contents(PATH_VIEWS . 'email/partials/item.html');
				$product = str_replace('{{product_image}}', $img, $product);
				$product = str_replace('{{product_name}}', $row['product'], $product);
				$product = str_replace('{{quantity}}', $row['quantity'] . 'pz', $product);
				$product = str_replace('{{amount}}', number_format($row['quantity'] * $row['unit_price'], 2), $product);
				$order_detail .= $product . PHP_EOL;

			endif;
		endforeach;

		//debug($order, true);
		$html = file_get_contents(PATH_VIEWS . 'email/order.html');

		// Header - Footer
		$mail_img = BASE_URL . 'Views/layout/' . DEFAULT_LAYOUT . '/images/box_1136107.png';
		$html = str_replace('{{header_mail}}', $this->header, $html);
		$html = str_replace('{{logo_url}}', $this->logo, $html);
		$html = str_replace('{{mail_img}}', $mail_img, $html);
		$html = str_replace('{{footer_mail}}', $this->footer, $html);

		// Icons
		$html = str_replace('{{fb}}', $this->icons['fb'], $html);
		$html = str_replace('{{inst}}', $this->icons['inst'], $html);
		$html = str_replace('{{pint}}', $this->icons['pint'], $html);


		$html = str_replace('{{order_id}}', $order['orderid'], $html);
		$html = str_replace('{{created_date}}', $created_date, $html);


		$html = str_replace('{{store_url}}', BASE_URL, $html);
		$html = str_replace('{{detail_order}}', $order_detail, $html);
		$html = str_replace('{{subtotal}}', number_format($subtotal, 2), $html);
		$html = str_replace('{{shipping}}', number_format($order['shipping']['total_pricing'], 2), $html);
		$html = str_replace('{{taxes}}', number_format($taxes, 2), $html);
		$html = str_replace('{{total}}', number_format($amount + $order['shipping']['total_pricing'], 2), $html);

		// Customer
		$html = str_replace('{{customer_email}}', $order['customer_name'] . ' ' . $order['customer_surname'], $html);
		$html = str_replace('{{order_id}}', $order_id, $html);
		$html = str_replace('{{payment_method}}', $order['payment']['type'], $html);
		$html = str_replace('{{currency}}', 'MXN', $html);

		//Address
		$html = str_replace('{{carrier}}', $order['shipping']['provider'], $html);
		$html = str_replace('{{service}}', $order['shipping']['service_level_name'], $html);
		$html = str_replace('{{street}}', $order['address']['street'], $html);
		$html = str_replace('{{out_num}}', $order['address']['outdoor_num'], $html);
		$html = str_replace('{{int_num}}', $order['address']['interior_num'], $html);
		$html = str_replace('{{suburb}}', $order['address']['suburb'], $html);
		$html = str_replace('{{zipcode}}', $order['address']['postcode'], $html);
		$html = str_replace('{{city}}', $order['address']['city'], $html);
		$html = str_replace('{{state}}', $order['address']['state'], $html);

		return $html;
	}

	public function confirm_template(){
		$html = file_get_contents(PATH_VIEWS . 'email/confirm.html');

		// Header - Footer
		$html = str_replace('{{header_mail}}', $this->header, $html);
		$html = str_replace('{{logo_url}}', $this->logo, $html);
		$html = str_replace('{{footer_mail}}', $this->footer, $html);

		$mail_img = BASE_URL . 'Views/layout/' . DEFAULT_LAYOUT . '/images/letter_11055015.png';
		$html = str_replace('{{mail_img}}', $mail_img, $html);

		// Icons
		$html = str_replace('{{fb}}', $this->icons['fb'], $html);
		$html = str_replace('{{inst}}', $this->icons['inst'], $html);
		$html = str_replace('{{pint}}', $this->icons['pint'], $html);

		return $html;
	}

	public function register_template($customer_name){
		$html = file_get_contents(PATH_VIEWS . 'email/register.html');

		// Header - Footer
		$html = str_replace('{{header_mail}}', $this->header, $html);
		$html = str_replace('{{logo_url}}', $this->logo, $html);
		$html = str_replace('{{footer_mail}}', $this->footer, $html);

		// Icons
		$html = str_replace('{{fb}}', $this->icons['fb'], $html);
		$html = str_replace('{{inst}}', $this->icons['inst'], $html);
		$html = str_replace('{{pint}}', $this->icons['pint'], $html);

		$html = str_replace('{{customer_name}}', $customer_name, $html);
		return $html;
	}

	public function forgot_password($args){
		$html = file_get_contents(PATH_VIEWS . 'email/forgot.html');

		// Header - Footer
		$html = str_replace('{{header_mail}}', $this->header, $html);
		$html = str_replace('{{logo_url}}', $this->logo, $html);
		$html = str_replace('{{footer_mail}}', $this->footer, $html);
		$html = str_replace('{{url_reset}}', $args['url'], $html);

		$mail_img = BASE_URL . 'Views/layout/' . DEFAULT_LAYOUT . '/images/security.png';
		$html = str_replace('{{mail_img}}', $mail_img, $html);

		// Icons
		$html = str_replace('{{fb}}', $this->icons['fb'], $html);
		$html = str_replace('{{inst}}', $this->icons['inst'], $html);
		$html = str_replace('{{pint}}', $this->icons['pint'], $html);

		return $html;
	}

	public function reset_password($args){
		$html = file_get_contents(PATH_VIEWS . 'email/reset.html');

		// Header - Footer
		$html = str_replace('{{header_mail}}', $this->header, $html);
		$html = str_replace('{{logo_url}}', $this->logo, $html);
		$html = str_replace('{{footer_mail}}', $this->footer, $html);
		$html = str_replace('{{url_login}}', $args['url'], $html);

		// Icons
		$html = str_replace('{{fb}}', $this->icons['fb'], $html);
		$html = str_replace('{{inst}}', $this->icons['inst'], $html);
		$html = str_replace('{{pint}}', $this->icons['pint'], $html);

		return $html;
	}

	public function send($subject, $body, $customer, $type = 'soporte'){
		// Mailer
		$this->mailer = new PHPMailer(true);

		$this->mailer->isSMTP();
		//$this->mailer->SMTPDebug = 2;
		$this->mailer->Host       = 'smtp.hostinger.com';
		$this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		$this->mailer->Port       = 465;
		$this->mailer->SMTPAuth   = true;
		$this->mailer->Subject 	  = utf8_decode($subject);

		if($type === 'soporte'):
			$this->mailer->Username   = 'soporte@mobelinn.com';
			$this->mailer->Password   = 'Soporte.010203';
			//Recipients
			$this->mailer->setFrom('soporte@mobelinn.com', 'Soporte Mobel Inn');
		else:
			$this->mailer->Username   = 'ventas@mobelinn.com';
			$this->mailer->Password   = 'Ventas.010203';
			//Recipients
			$this->mailer->setFrom('ventas@mobelinn.com', 'Tienda Mobel Inn');
		endif;

		if(defined('SANDBOX') && SANDBOX):
			$this->mailer->addAddress('soporte@mobelinn.com', utf8_decode('Alejandro Godínez'));
			$this->mailer->addBCC('ventas@mobelinn.com', utf8_decode('Alejandro Godínez'));
		else:
			$this->mailer->addAddress($customer['email'], utf8_decode($customer['name']));
			$this->mailer->addBCC('admin@mobelinn.com', utf8_decode('Jose Luis Godínez'));
			$this->mailer->addBCC('ventas@mobelinn.com', utf8_decode('Alejandro Godínez'));
		endif;

		//Attachments
		if(!empty($this->files)):
			foreach($this->files as $file):
				$this->mailer->addAttachment($file);
			endforeach;
		endif;

		//Content
		$this->mailer->ContentType = 'text/plain';
		$this->mailer->IsHTML(true); //Set email format to HTML
		$this->mailer->Body = utf8_decode($body);
		$this->mailer->AltBody = strip_tags($body);

		if(!$this->mailer->send())
			Log::getInstance('error_log')->write($this->mailer->ErrorInfo);

	}

}
