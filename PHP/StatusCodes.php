<?php
class StatusCodes
{

	public $status_fail_zero;
	public $status_success_one;
	public $status_fail_unauthorized_two;
	public $status_fail_params_three;
	public $status_fail_forbidden_four;
	public $status_fail_orgin_access_five;
	public $status_success;
	public $status_require_vars;
	public $status_unauthorized;
	public $status_forbidden;
	public $status_already_login;
	public $status_already_logout;
	public $status_insert_exist;
	public $status_sql_fails;
	public $status_data_none;
	public $status_post_value_none;
	public $status_message_unauthorized;
	public $status_message_orgin_access;
	public $status_message_device_id;
	public $admin;
	public $status_messages_arr = array();
	function __construct()
	{
		$this->status_fail_zero = 0;
		$this->status_success_one = 1;
		$this->status_fail_unauthorized_two = 2;
		$this->status_fail_params_three = 3;
		$this->status_fail_forbidden_four = 4;
		$this->status_fail_orgin_access_five = 5;
		$this->status_fail_method_six = 6;
		$this->status_success = 200;
		$this->status_require_vars = 600; // bad syntax or was inherently impossible to be satisfied.
		$this->status_unauthorized = 601; //UNAUTHORIZED
		$this->status_forbidden = 603; // FORBIDDEN
		$this->status_already_login = 610; // ALREADY LOGIN
		$this->status_already_logout = 611; // ALREADY LOGOUT
		$this->status_insert_exist = 612; // 210 // SQL ERRORS
		$this->status_sql_fails = 613; // 211 //SQL ERRORS
		$this->status_data_none = 614; // NO DATA
		$this->status_post_value_none = 615; // POSTED VALUE EMPTY
		$this->admin = 'admin';
		$this->status_message_unauthorized = "TOKEN AUTHENTICATON FAILED! Please contact IT support";
		$this->status_message_orgin_access = "UNAUTHORIZED! Please contact IT support";
		$this->status_message_method = "FORBIDDEN! Please use `POST` method";
		$this->status_message_device_id = "DEVICE ID! Your device is not registered, Please login again or please contact IT support";
		$this->status_messages_arr = array(
			$this->status_success => array('SUCCESS!', 'Successfully performed operation'),
			$this->status_require_vars => array('FAILED!', 'Please call the API with required paramater'),
			$this->status_unauthorized => array('UNAUTHORIZED!', 'Please verify the tokens or please contact IT Support'),
			$this->status_forbidden => array('UNAUTHORIZED!', 'You are not allowed to perform this operation'),
			$this->status_already_login => array('UNAUTHORIZED!', 'User have been already login'),
			$this->status_insert_exist => array('UNAUTHORIZED!', 'User have been already logout'),
			$this->status_sql_fails => array('FAILED!', 'There was an error occured while exectuting your query, Please contact IT Support'),
			$this->status_data_none => array("FAILED!", "NO DATA AVAILABLE"),
			$this->status_post_value_none => array("FAILED!", "Value for the parameter {} cannot be empty"),
		);
	}

	public function getStatusSuccess($status, $error_code, $message = '', $parameters = array())
	{
		$return_array = array();
		$return_array['status'] = $status;
		if ($status == 'success') {
			$status_code = $this->status_success;
		} else {
			$status_code = $error_code;
		}
		$return_array['status_code'] = $status_code;
		$message = $this->getStatusMessage($status_code, $message, $parameters);
		$return_array['message'] = $message;
		return $return_array;
	}

	public function getStatus($status_code, $message = '', $parameters = array())
	{
		$return_array = array();
		if ($status_code == $this->status_success) {
			$return_array['status'] = 'SUCCESS';
		} else {
			$return_array['status'] = 'FAILS';
		}
		$message = $this->getStatusMessage($status_code, $message, $parameters);
		$return_array['message'] = $message;
		return $return_array;
	}

	public function getStatusMessage($status_code, $message = '', $parameters = array(), $cred = false)
	{
		if ($message == 'STATUS CODE CANNOT BE EMPTY') {
			$message_mode = "EMPTY STATUS CODE!";
		} else {
			$message_mode = $this->status_messages_arr[$status_code][0];
		}

		if ($message == '') {
			$message = $this->status_messages_arr[$status_code][1];
		} else {
			$message = $message_mode . ' ' . $message;
		}
		$message = strtoupper($message);
		if (!is_array($parameters)) {
			if (strpos($parameters, "__json") !== false) {
				$params_req_append = " IN THE JSON CREDENTIALS";
			} else {
				$params_req_append = "";
			}
			$parameters = str_replace("__json", "", $parameters);
			$params_req = ' NAMED AS `' . str_replace("credentials", "credentials (JSON STRING)", $parameters) . '`';
			if (strpos($params_req, 'credentials') === false) {
				$params_req .= 	$params_req_append;
			}
		} else if (count($parameters) > 0) {
			$params_req = 'S SUCH AS `' . implode(", ", $parameters) . '`';
		}

		if ($cred == true) {
			$message = str_replace("PARAMATER", "KEY", $message);
			$params_req .= ' IN THE `credentials` (JSON FORMATTED STRING) PARAMATER';
		}

		if ((is_array($parameters) && count($parameters) > 0) || (is_string($parameters) && strlen(trim($parameters)) > 0)) {
			if ($status_code == $this->status_post_value_none) {
				$message = str_replace("{}", $params_req, $message); //$this->status_post_value_none==>check this variable
			} else {
				$message = $message . $params_req;
			}
		}
		return $message;
	}
}
