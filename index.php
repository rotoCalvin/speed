<?php
	
	// define path to data folder
	define('DATA_PATH', realpath(dirname(__FILE__).'/data'));
	
	// define our id-key pairs
	$users = array(
		'ROTO' => '2EA729C74E4D36B8',
		'SMV' => 'DCF4777EA188374B'
	);
	
	// include our models
	include_once 'models/ErrorMessage.php';
	
	
	// wrap the whole thing in a try-catch block to catch any wayward exceptions
	try {
		// get all of the parameters in the POST/GET request
		$params = $_REQUEST;
		
		// check first if the user id exists in the list of users
		if (!isset($params['api_username']) || !isset($params['api_key'])) {
			throw new Exception('ERROR_NO_API_USERNAME_OR_KEY');
		}
		$username = strtoupper($params['api_username']);
		$key = $params['api_key'];
		
		// make sure api key matches for api username
		$apiKeyCorrect = false;
		for ($i = 0; $i < count($users); $i++)
		{
			if ($users[$username] == $key)
			{
				$apiKeyCorrect = true;
				break;
			}
		}
		if (!$apiKeyCorrect)
		{
			throw new Exception('ERROR_INCORRECT_API_USERNAME_OR_KEY');
		}
		
		// get the application name and format it correctly so the first letter is always capitalized
		$application = ucfirst(strtolower($_POST['application']));
		
		// get the action and format it correctly so all the letters are not capitalized, and append 'Action'
		$action = strtolower($params['action']).'Action';
		
		// check if the application exists.  if not, throw exception
		if (file_exists("controllers/{$application}.php")) {
			include_once "controllers/{$application}.php";
		} else {
			throw new Exception('ERROR_NO_APPLICATION');
		}
		
		// create a new instance of the controller, and pass it the parameters from the request
		$application = new $application();
		
		//echo('Looking for ' . $action . ' on ' . $application);
		// check if the action exists in the controller.  if not, throw an exception
		if (method_exists($application, $action) === false) {
			throw new Exception('ERROR_NO_ACTION');
		}
		
		// execute the action
		$result = $application->$action($_POST);
		$result['error_message'] = ErrorMessage::getErrorMessage($result['error']);
		
	} catch (Exception $e) {
		
		// catch any exceptions and report the problem
		$result = array();
		$result['error'] = $e->getMessage();
		$result['error_message'] = ErrorMessage::getErrorMessage($e->getMessage());
	}
	
	// echo the result of the API call
	echo json_encode($result);
	exit();
	
	
	
?>