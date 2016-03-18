<?php

class ErrorMessage
{
	// error code constants
	
	private static $allErrors = array(
		array('NO_ERROR', 'No error.'),
		array('ERROR_NO_API_USERNAME_OR_KEY', 'No API username or key present in url.'),
		array('ERROR_INCORRECT_API_USERNAME_OR_KEY', 'Incorrect API username or key.'),
		array('ERROR_NO_APPLICATION', 'Application does not exist.'),
		array('ERROR_NO_ACTION', 'Action does not exist.'),
		array('API_CRITERIA_INCORRECT', 'There is specific criteria needed for this API call.  Check documentation.'),
		array('ERROR_NO_SLOTS_AVAILABLE', 'All available players slots are filled.'),
		array('ERROR_INCORRECT_GAME_ID', 'Game id does not match the game id for the current session.'),
		array('ERROR_GAME_ALREADY_STARTED', 'The game is not accepting new players at this time.'),
		array('ERROR_GAME_NOT_STARTED', 'The game is not accepting new messages at this time.  Wait until the game has started.'),
		array('ERROR_INCORRECT_COLOR', 'Player color does not exist.'),
		array('ERROR_DATABSE_CONNECTION', 'Failed to connect to database.'),
		array('ERROR_MALFORMED_SQL', 'Improper SQL Statement.  Contact Roto if problem persists.')
	);
	
	
	public static function getErrorMessage($error)
	{
		$errorMsg = '';
		for ($i = 0; $i < count(self::$allErrors); $i++)
		{
			if ($error == self::$allErrors[$i][0])
			{
				$errorMsg = self::$allErrors[$i][1];
				break;
			}
		}
		
		return $errorMsg;
	}
	
	
	
}

?>