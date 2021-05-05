<?php

class RestApiController extends StatusCodes
{
	protected $http_origin;
	protected $allowed_orgins;
	function __construct()
	{
		parent::__construct();
		$this->http_origin = $_SERVER['REMOTE_ADDR'];
		$this->allowed_orgins = array(
		);
		if (!in_array($this->http_origin, $this->allowed_orgins)) {
			if (isset($_SERVER['HTTP_ORIGIN'])) {
				$this->http_origin = rtrim($_SERVER['HTTP_ORIGIN'], "/");
			} else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
				$this->http_origin = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$this->http_origin = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$this->http_origin = $_SERVER['REMOTE_ADDR'];
			}
		}
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		if (!in_array($this->http_origin, $this->allowed_orgins)) {
			$this->create_response(array(), 5);
		}
		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			$this->create_response(array(), 6);
		}
		$this->restmodal = new RestModal();
	}
	// START **** CHECK OUT THESE FUNCTIONS ****  //
	// $this->security_check();
	// $this->required_vars_check();
	// $this->verify_isset_middlewaretoken();
	// $token = $this->verify_middle_ware_token();
	// $this->create_response();
	// END **** CHECK OUT THESE FUNCTIONS **** //
	public function index()
	{
		$message['status'] = "UNAUTHORIZED";
		$message['message'] = "YOU ARE NOT ALLOWED TO ACCESS THIS PAGE";
		$message['result'] = (object) array();
		echo json_encode($message);
	}


	public function apiUpdateGoldrate()
	{
		$this->security_check($_POST);
	}

	public function security_check($post_data)
	{
		$this->required_vars_check(
			array(
				'credentials',
			),
			$post_data
		);

		$json_decode = json_decode($post_data['credentials'], true);
		$this->required_vars_check(
			array(
				'middlewaretoken__json',
				'auth_id__json',
			),
			$json_decode
		);
		$middlewaretoken = $json_decode['middlewaretoken'];
		$auth_id = $json_decode['auth_id'];
	}

	public function required_vars_check($posted_keys, $posted_data, $cred = false)
	{
		$return_array = array();
		foreach ($posted_keys as $key => $posted_key) {
			$posted_key_check = str_replace("__json", "", $posted_key);
			if (!isset($posted_data[$posted_key_check])) {
				$return_array['message'] = $this->getStatusMessage($this->status_require_vars, "", $posted_key, $cred);
				$this->create_response($return_array, $this->status_fail_params_three);
				exit;
			} else if (strlen(trim($posted_data[$posted_key_check])) == 0) {
				$return_array['message'] = $this->getStatusMessage($this->status_post_value_none, "", $posted_key, $cred);
				$this->create_response($return_array, $this->status_fail_params_three);
				exit;
			}
		}
	}
  
  public function create_response($array, $status = 1, $print_test = false)
	{
		$return_array = array();
		$this->print_response($print_test, $array);
		$return_array['data'] = $array;
		if ($status == $this->status_fail_zero) {
			$result['status'] = 'FAILED';
			$result['message'] = $array['message'];
			if (isset($array['data'])) {
				$result['result'] = $array['data'];
			}
		} else if ($status == $this->status_fail_unauthorized_two) {
			$result['status'] = 'UNAUTHORIZED';
			$result['message'] = $this->status_message_unauthorized;
		} else if ($status == $this->status_fail_params_three) {

			if (!isset($array['status'])) {
				$result['status'] = 'FAILED';
			} else {
				$result['status'] = $array['status'];
			}
			$result['message'] = $array['message'];
		} else if ($status == $this->status_fail_forbidden_four) {
			$result['status'] = 'FAILED';
			if (isset($array['message'])) {
				$message = $array['message'];
			} else {
				$message = $this->getStatusMessage($this->status_forbidden);
			}
			$result['message'] = 'FORBIDDEN!' . $message;
		} else if ($status == $this->status_fail_orgin_access_five) {
			$result['status'] = 'FAILED';
			$result['message'] = $this->status_message_orgin_access;
		} else if ($status == $this->status_fail_method_six) {
			$result['status'] = 'FAILED';
			$result['message'] = $this->status_message_method;
		} else {
			if (count($array) > 0) {
				if (!isset($array['message']) || (isset($array['message']) && $array['message'] == '')) {
					$message = '';
				} else {
					$message = $array['message'];
				}
				$result = $this->getStatus($this->status_success, $message);
				if (isset($array['data'])) {
					if (is_string($array['data']) && strlen(trim($array['data'])) != 0) {
						$result['result'] = $array['data'];
					} else {
						$result['result'] = $array['data'];
					}
				} else {
					$result['result'] = $array;
				}
			} else {
				$result = $this->getStatus($this->status_data_none);
			}
		}
		if (!isset($result['result']) || isset($result['result']) && count($result['result']) == 0) {
			$result['result'] = (object) array();
		}
		echo json_encode($result, true);
		exit;
	}
}
