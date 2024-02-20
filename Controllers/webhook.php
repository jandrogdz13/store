<?php
Helper::load_helper('ApiResponse');
class webhookController extends Controller {
	use ApiResponse_Trait;

	private $token;
	private $log;

	private $mp_secret = '5e307651bd2a90b631c5b661d5f4b239ac0ffa8b118b5d4cce517c7c71d69753';
	public function __construct(){
		parent::__construct();
		$this->token = $this->mp_secret; //hash('sha256', vglobal('application_unique_key'));
		$this->log = Log::getInstance(gmdate('Ymd').'-mp');
	}

	public function index(){
		//debug('Entra', true);
	}

}
