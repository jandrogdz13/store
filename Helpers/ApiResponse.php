<?php

/**
 * ApiResponse_Trait
 */
trait ApiResponse_Trait {
	protected $json = [
		'success' => true,
		'message' => [
			'success' => null,
			'warning' => null,
			'error' => null,
		],
		'result' => [],
		'empty_result_message' => null,
	];
	protected $args = ['is_custom' => false, 'module' => false, 'status' => 'ok'];
	protected $status_code = 200;

	protected function sendResponse()
	{
		$statusMessage = $this->getHttpStatusMessage($this->status_code);
		if (!($this->status_code >= 200 && $this->status_code < 300)) {
			if (empty(implode('', $this->json['message']))) {
				$this->json['message']['error'] = $statusMessage;
			}
			$this->json['success'] = false;
		}

		header($_SERVER["SERVER_PROTOCOL"] . " " . $this->status_code . " " . $statusMessage);
		header('Content-Type: application/json; charset=utf-8');

		echo json_encode($this->json, JSON_UNESCAPED_UNICODE);
		/*echo $this->args['is_custom']
			? json_encode(['status' => $this->args['status']], JSON_UNESCAPED_UNICODE)
			: json_encode($this->json, JSON_UNESCAPED_UNICODE);*/
		die;
	}

	protected function getHttpStatusMessage($status_code)
	{
		$httpStatus = array(
			200 => 'OK',
			201 => 'Created',
			204 => 'No Content',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			409 => 'Conflict',
			410 => 'Gone',
			429 => 'Too Many Requests',
			500 => 'Internal Server Error',
		);

		return ($httpStatus[$status_code]) ? $httpStatus[$status_code] : $httpStatus[500];
	}

	protected function getPost()
	{
		$input = file_get_contents('php://input');
		$post = json_decode($input, true);

		if (!is_array($post) || empty($post)) {
			$this->status_code = 400;
			$this->json['message']['error'] = 'Invalid request body, please validate the JSON object';
			return $this->sendResponse();
		}

		return $post;
	}
}
