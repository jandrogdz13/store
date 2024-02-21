<?php
Helper::load_helper('Http');

$months = [
    'January' => 'Enero',
    'February' => 'Febrero',
    'March' => 'Marzo',
    'April' => 'Abril',
    'May' => 'Mayo',
    'June' => 'Junio',
    'July' => 'Julio',
    'August' => 'Agosto',
    'September' => 'Septiembre',
    'October' => 'Octubre',
    'November' => 'Noviembre',
    'December' => 'Diciembre'
];

$days = [
	'sunday' => 'Domingo',
	'saturday' => 'Sábado',
	'monday' => 'Lunes',
	'tuesday' => 'Martes',
	'wednesday' => 'Miércoles',
	'thursday' => 'Jueves',
	'friday' => 'Viernes',
];

function custom_error_log($err){
	$err_log = [
		'Line' => $err->getLine(),
		'Code' => $err->getCode(),
		'File' => $err->getFile(),
		'Message' => $err->getMessage(),
		'Trace' => $err->getTraceAsString()
	];
	Log::getInstance('error_log')->write($err_log);
}

function debug($str, $break = false){
    echo "<pre>";
    print_r($str);
    echo "</pre>";
    if($break) exit;
}

function redirect($path = ''){
	if($path)
		header('location:' . BASE_URL . $path);
	else
		header('location:' . BASE_URL);
	exit;
}

function translate($key, $module){
    $registry = Registry::getInstance();
    $lang = $registry->get('lang');
    if(array_key_exists($module, $lang)):
        return isset($lang[$module][$key])? $lang[$module][$key]: $key;
    else:
		/*if($_COOKIE['currency'] === 'USD'):
			include PATH_LANG . 'en-us' . DS . $module . '.php';
		else:*/
			include PATH_LANG . LANG . DS . $module . '.php';
		//endif;
        $lang[$module] = @$languageStrings;
        $registry->set('lang', $lang);
        return isset($lang[$module][$key])? $lang[$module][$key]: $key;
    endif;
}

function get_conversion_rate(): string{

	$db = dbPDO::get_instance();
	$conversion_rate = '17.087';
	$current_date = getCurrentDate();

	$sql = "
			SELECT 
				conversion_rate.id,
				conversion_rate.conversion,
				DATE_FORMAT(FROM_UNIXTIME(conversion_rate.timestamp_api), '%Y-%m-%d') AS timestamp_api
			FROM conversion_rate
			WHERE DATE_FORMAT(FROM_UNIXTIME(conversion_rate.timestamp_api), '%Y-%m-%d') = :current_date
		";
	$db->prepare($sql, [
		'current_date' => $current_date
	]);
	$record = $db->fetch();

	//debug([$current_date, date('Y-m-d', strtotime($current_date))], true);

	if(!$record):
		try{
			$app_id = CURRENCY_CONVERT_ID;
			$response = Http_Helper::get(
				"https://openexchangerates.org/api/latest.json?app_id={$app_id}",
				function ($http){
					$http->header('accept', 'application/json');
				}
			);
			$body = json_decode($response->body, true);

			//Log::getInstance('error_log')->write($body);

			$conversion_rate = $body['rates']['MXN'];

			$sql = "
				INSERT INTO conversion_rate
				SET
					conversion_rate.conversion = :conversion_rate,
					conversion_rate.timestamp_api = :timestamp
			";
			$db->query($sql, [
				'conversion_rate' => $conversion_rate,
				'timestamp' => strtotime($current_date),
			]);
		}catch(Exception $ex){
			Log::getInstance('error_log')->write($ex->getMessage());
		}
	else:
		$conversion_rate = $record['conversion'];
	endif;

	return $conversion_rate;
}

function encrypt_password($email, $string, $action = 'e'){

	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		return false;

	list($secret_key, $secret_iv) = explode('@', $email);

	$output = false;
	$encrypt_method = "AES-256-CBC";
	$key = hash( 'sha256', $secret_key );
	$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

	$output = $action == 'e'
		? base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv))
		: openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	return $output;
}

function get_customer(){
	return  Session::get('customer')? : [];
}

function _global($key){
	return isset($GLOBALS[$key])? $GLOBALS[$key]: '';
}

function changeArrayKeys(&$array, $new_key = null){
    foreach($array as $key => $values):
		if($new_key && is_array($values)):
			$array[$values[$new_key]] = $values;
		else:
			$array[$values] = $values;
		endif;
        unset($array[$key]);
    endforeach;
}

function isAjax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}

function isMobileDevice(){
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

// Dates
function getCurrentDate($format = 'Y-m-d'){
	$date = new DateTime(null, new DateTimeZone(date_default_timezone_get()));
	$date = $date->format($format);
	$horarioVerano = date('I', strtotime($date));
	if ($horarioVerano && strpos($format, 'H:') !== FALSE)
		$date = date($format, strtotime('-1 hour'));

	return $date;
}

function translateDate($date, $short = true, $showYears = false, $showHours = true){

	$exp = explode(' ', $date);
	$month = $short? substr(Helper::$months[date('n', strtotime($date))-1], 0, 3): Helper::$months[date('n', strtotime($date))-1];
	$time = '';

	if(count($exp) > 1 && $showHours):
		$time = date('h:i', strtotime($date));
		$ampm = date('A', strtotime($date));
		$time = " {$time} {$ampm}";
	endif;

	$translateDate = !$showYears && date('Y') == date('Y', strtotime($date))
        ? date('d', strtotime($date))." ". $month
        : date('d', strtotime($date))." ". $month . ' de ' . date('Y', strtotime($date));

	return !$showYears
		? date('d', strtotime($date))." ". $month . $time
		: $translateDate . $time;
}

function cast(&$value, $type) {
    switch($type):
        case 'text':
		case 'text-numeric':
        case 'email':
        case 'currency':
        case 'time':
		case 'phone':
		case 'picklist':
		case 'multi':
		case 'multi_group':
		case 'default':
            $value = strtoupper($value? (string) "'{$value}'": "''");
            break;
		case 'date':
			$date = $value? date('Y-m-d', strtotime($value)): '';
			$value = $value? (string) "'{$date}'": "''";
			break;
		case 'datetime':
			$date = $value? date('Y-m-d H:i:s', strtotime($value)): '';
			$value = $value? (string) "'{$date}'": "''";
			break;
		case 'password':
			$registry = Registry::getInstance();
			$request = $registry->get('request');
			$pass = passwordEncrypt($request->post['email'], $value);
			$value = (string) "'{$pass}'";
		break;
        case 'status':
        case 'switch':
        case 'related':
        case 'hidden':
            $value = $value? (int) $value: 0;
            break;
    endswitch;

}

function generateUuidv4()
{
	return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		// 32 bits for "time_low"
		mt_rand(0, 0xffff), mt_rand(0, 0xffff),
		// 16 bits for "time_mid"
		mt_rand(0, 0xffff),
		// 16 bits for "time_hi_and_version",
		// four most significant bits holds version number 4
		mt_rand(0, 0x0fff) | 0x4000,
		// 16 bits, 8 bits for "clk_seq_hi_res",
		// 8 bits for "clk_seq_low",
		// two most significant bits holds zero and one for variant DCE1.1
		mt_rand(0, 0x3fff) | 0x8000,
		// 48 bits for "node"
		mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
	);
}
