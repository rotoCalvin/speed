<?php
class Communicationsrace
{
	private $host;
	private $username;
	private $password;
	private $dbName;
	
	public function __construct()
	{
		//$this->host = "mserver";
		//$this->username = "speedApp";
		//$this->password = "sp33d";
		//$this->dbName = "speed";
		
		$xml = simplexml_load_file("config.xml");
		if ($xml === false) {
			echo "FAILED LOADING config.xml";
			foreach(libxml_get_errors() as $error) {
				echo "<br>", $error->message;
			}
		}
		else
		{
			$this->host = $xml->host;
			$this->username = $xml->username;
		    $this->password = $xml->password;
		    $this->dbName = $xml->dbName;
		}
	}
	
	
	
	
	
	/*********************************************************************************************************************************************************************************/
	/****************************************************************************** MAIN APPLICATION**********************************************************************************/
	/*********************************************************************************************************************************************************************************/
	public function startNewGameAction()
	{
		$response = array('error' => 'NO_ERROR');
		
		// create random gameId
		$characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$gameId = '';
		for ($i = 0; $i < 4; $i++)
		{
			$gameId .= $characters[rand(0, $charactersLength - 1)];
		}
		
		// add a new game to the games db
		$sql = "INSERT INTO communicationracegames(game_id, game_started, phrase_used, winner, morse_code_time, player_a_time, player_b_time, player_c_time, player_d_time) VALUES ('".$gameId."', 'false', '', '', '', '', '', '', '')";
		
		// Create Connection
		$dbCon = mysqli_connect($this->host, $this->username, $this->password, $this->dbName);
		
		// Check Connection
		if (mysqli_connect_errno()) {
			// failed to connect
			$response['error'] = 'ERROR_DATABSE_CONNECTION';
		}
		else
		{
			// successfully connected, insert new game item
			if (!mysqli_query($dbCon, $sql)) {
				// error
				$response['error'] = 'ERROR_MALFORMED_SQL';
			}
		}
		
		// close connetion
		mysqli_close($dbCon);
		
		file_put_contents(DATA_PATH."/PINK.txt", "OUT");
		file_put_contents(DATA_PATH."/BLUE.txt", "OUT");
		file_put_contents(DATA_PATH."/ORANGE.txt", "OUT");
		file_put_contents(DATA_PATH."/GREEN.txt", "OUT");
		
		$response['game_id'] = $gameId;
		$response['test'] = "TESTING!";
		
		return $response;
	}
	
	
	
	
	
	
	
	public function setTimeoutAction($data)
	{
		if (!isset($data['game_id']) || !isset($data['timeout_seconds']))
		{
			$response['error'] = 'API_CRITERIA_INCORRECT';
		}
		else
		{
			$gameId = $data['game_id'];
			$timeoutSeconds = $data['timeout_seconds'];
			
			$response = array('game_id' => $gameId,
							  'timeout_seconds' => floatval($timeoutSeconds),
							  'error' => 'NO_ERROR');
			
			// add a new game to the games db
			$sql = "UPDATE communicationracegames SET timeout='" . $timeoutSeconds ."' WHERE game_id='".$gameId."'";
			
			// Create Connection
			$dbCon = mysqli_connect($this->host, $this->username, $this->password, $this->dbName);
			
			// Check Connection
			if (mysqli_connect_errno()) {
				// failed to connect
				$response['error'] = 'ERROR_DATABSE_CONNECTION';
			}
			else
			{
				// successfully connected, insert new game item
				if (!mysqli_query($dbCon, $sql)) {
					// error
					$response['error'] = 'ERROR_MALFORMED_SQL';
					$response['sql'] = $sql;
				}
			}
			
			// close connetion
			mysqli_close($dbCon);
		}
		
		return $response;
	}
	
	
	
	
	
	
	
	
	
	public function setGameAction($data)
	{
		if (!isset($data['game_id']))
		{
			$response['error'] = 'API_CRITERIA_INCORRECT';
		}
		else
		{
			$gameId = $data['game_id'];
			
			$response = array('game_id' => $gameId,
							  'error' => 'NO_ERROR');
			
			// add a new game to the games db
			$sql = "UPDATE communicationracegames SET game_started='1' WHERE game_id='".$gameId."'";
			
			// Create Connection
			$dbCon = mysqli_connect($this->host, $this->username, $this->password, $this->dbName);
			
			// Check Connection
			if (mysqli_connect_errno()) {
				// failed to connect
				$response['error'] = 'ERROR_DATABSE_CONNECTION';
			}
			else
			{
				// successfully connected, insert new game item
				if (!mysqli_query($dbCon, $sql)) {
					// error
					$response['error'] = 'ERROR_MALFORMED_SQL';
					$response['sql'] = $sql;
				}
			}
			
			// close connetion
			mysqli_close($dbCon);
		}
		
		return $response;
	}
	
	
	
	
	
	public function startTimerAction($data)
	{
		if (!isset($data['game_id']) || !isset($data['phrase']))
		{
			$response['error'] = 'API_CRITERIA_INCORRECT';
		}
		else
		{
			$gameId = $data['game_id'];
			$phrase = $data['phrase'];
			
			$micro = microtime();
			$millis = explode(" ", $micro)[0];
			$secs = explode(" ", $micro)[1];
			$timestamp = $millis + $secs;
			
			$response = array('game_id' => $gameId,
							  'phrase' => $phrase,
							  'timestamp' => $timestamp,
							  'error' => 'NO_ERROR');
			
			// add a new game to the games db
			$sql = "UPDATE communicationracegames SET game_started='1', game_start_time='".$timestamp."', phrase_used='".$phrase."' WHERE game_id='".$gameId."'";
			
			// Create Connection
			$dbCon = mysqli_connect($this->host, $this->username, $this->password, $this->dbName);
			
			// Check Connection
			if (mysqli_connect_errno()) {
				// failed to connect
				$response['error'] = 'ERROR_DATABSE_CONNECTION';
			}
			else
			{
				// successfully connected, insert new game item
				if (!mysqli_query($dbCon, $sql)) {
					// error
					$response['error'] = 'ERROR_MALFORMED_SQL';
					$response['sql'] = $sql;
				}
			}
			
			// close connetion
			mysqli_close($dbCon);
		}
		
		return $response;
	}
	
	
	
	
	
	
	
	
	public function getPlayerInfoAction($data)
	{
		if (!isset($data['color']) || !isset($data['timestamp']))
		{
			$response['error'] = 'API_CRITERIA_INCORRECT';
			$response['timestamp'] = null;
		}
		else
		{
			$playerColor = $data['color'];
		
			$response = array('color' => $playerColor,
							  'update' => true,
							  'error' => 'NO_ERROR');
			
			/////////////////////////////////////////////////////////////////////////
			// test for timestamp
			if (isset($data['timestamp'])) {
				$filename = DATA_PATH."/".$playerColor.".txt";
				$lastmodif = $data['timestamp'];
				$currentmodif = filemtime($filename);
				
				if ($lastmodif == null) { $lastmodif = 0; }
				
				$startTime = time();
				while ($currentmodif <= $lastmodif)
				{
					usleep(10000);
					clearstatcache();
					$currentmodif = filemtime($filename);
					
					if (time() >= $startTime + 110)
					{
						$response['update'] = false;
						
						$response['time'] = time();
						//$response['startTime'] = $startTime;
						//$response['startTime110'] = $startTime + 110;
						
						break;
					}
				}
				
				$response['player_info'] = file_get_contents($filename);
				$response['timestamp'] = $currentmodif;
				
				
			}
			/////////////////////////////////////////////////////////////////////////
		}
		
		return $response;
	}
	
	
	public function getPlayerTimeAction($data)
	{
		if (!isset($data['game_id']) || !isset($data['color']))
		{
			$response['error'] = 'API_CRITERIA_INCORRECT';
		}
		else
		{
			$gameId = $data['game_id'];
			$playerColor = $data['color'];
			$response = array('game_id' => $gameId,
							  'color' => $playerColor,
							  'error' => 'NO_ERROR');
			
			$sql = "SELECT * FROM communicationracegames ORDER BY id DESC LIMIT 1";
			
			// Create Connection
			$dbCon = mysqli_connect($this->host, $this->username, $this->password, $this->dbName);
			
			// Check Connection
			if (mysqli_connect_errno()) {
				// failed to connect
				$response['error'] = 'ERROR_DATABSE_CONNECTION';
			}
			else
			{
				$sqlResult = mysqli_query($dbCon, $sql);
				if($sqlResult === FALSE) {
					$response['error'] = 'ERROR_MALFORMED_SQL';
				}
				else
				{
					$row = mysqli_fetch_array($sqlResult);
					if ($gameId != $row['game_id'])
					{
						$response['error'] = 'ERROR_INCORRECT_GAME_ID';
					}
					else
					{
						$playerTime = 0;
						$gameStartTime = floatval($row['game_start_time']);
						if ($playerColor == 'PINK')
						{
							$playerInTime = floatval($row['player_a_time']);
							$response['player_time_in'] = $playerInTime;
							$response['player_time_start'] = $gameStartTime;
							$response['message'] = $row['player_a_message'];
							$playerTime = number_format($playerInTime - $gameStartTime, 2);
						}
						else if ($playerColor == 'BLUE')
						{
							$playerInTime = floatval($row['player_b_time']);
							$response['player_time_in'] = $playerInTime;
							$response['player_time_start'] = $gameStartTime;
							$response['message'] = $row['player_b_message'];
							$playerTime = number_format($playerInTime - $gameStartTime, 2);
						}
						else if ($playerColor == 'ORANGE')
						{
							$playerInTime = floatval($row['player_c_time']);
							$response['player_time_in'] = $playerInTime;
							$response['player_time_start'] = $gameStartTime;
							$response['message'] = $row['player_c_message'];
							$playerTime = number_format($playerInTime - $gameStartTime, 2);
						}
						else if ($playerColor == 'GREEN')
						{
							$playerInTime = floatval($row['player_d_time']);
							$response['player_time_in'] = $playerInTime;
							$response['player_time_start'] = $gameStartTime;
							$response['message'] = $row['player_d_message'];
							$playerTime = number_format($playerInTime - $gameStartTime, 2);
						}
						
						$response['player_time'] = $playerTime;
					}
				}
			}
			
			// close connetion
			mysqli_close($dbCon);
		}
		
		return $response;
	}
	
	
	
	
	
	
	/*********************************************************************************************************************************************************************************/
	/********************************************************************* TESTING MAIN APPLICATION **********************************************************************************/
	/*********************************************************************************************************************************************************************************/
	
	public function testStartNewGameAction()
	{
		$response = array('error' => 'NO_ERROR');
		
		// create random gameId
		$characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
		$charactersLength = strlen($characters);
		$gameId = '';
		for ($i = 0; $i < 4; $i++)
		{
			$gameId .= $characters[rand(0, $charactersLength - 1)];
		}
		
		// add a new game to the games db
		$sql = "INSERT INTO communicationracegames(game_id, game_started, phrase_used, winner, morse_code_time, player_a_time, player_b_time, player_c_time, player_d_time, test) VALUES ('".$gameId."', 'false', '', '', '', '', '', '', '', ".true.")";
		
		// Create Connection
		$dbCon = mysqli_connect($this->host, $this->username, $this->password, $this->dbName);
		
		// Check Connection
		if (mysqli_connect_errno()) {
			// failed to connect
			$response['error'] = 'ERROR_DATABSE_CONNECTION';
		}
		else
		{
			// successfully connected, insert new game item
			if (!mysqli_query($dbCon, $sql)) {
				// error
				$response['error'] = 'ERROR_MALFORMED_SQL';
			}
		}
		
		// close connetion
		mysqli_close($dbCon);
		
		$response['game_id'] = $gameId;
		$response['test'] = true;
		
		return $response;
	}
	
	
	public function testStartTimerAction($data)
	{
		if (!isset($data['game_id']) || !isset($data['phrase']) || !isset($data['test']))
		{
			$response['error'] = 'API_CRITERIA_INCORRECT';
		}
		else
		{
			$gameId = $data['game_id'];
			$phrase = $data['phrase'];
			
			$micro = microtime();
			$millis = explode(" ", $micro)[0];
			$secs = explode(" ", $micro)[1];
			$timestamp = $millis + $secs;
			
			$response = array('game_id' => $gameId,
							  'phrase' => $phrase,
							  'timestamp' => $timestamp,
							  'test' => true,
							  'error' => 'NO_ERROR');
			
			// add a new game to the games db
			$sql = "UPDATE communicationracegames SET game_started='1', game_start_time='".$timestamp."', phrase_used='".$phrase."' WHERE game_id='".$gameId."'";
			
			// Create Connection
			$dbCon = mysqli_connect($this->host, $this->username, $this->password, $this->dbName);
			
			// Check Connection
			if (mysqli_connect_errno()) {
				// failed to connect
				$response['error'] = 'ERROR_DATABSE_CONNECTION';
			}
			else
			{
				// successfully connected, insert new game item
				if (!mysqli_query($dbCon, $sql)) {
					// error
					$response['error'] = 'ERROR_MALFORMED_SQL';
				}
			}
			
			// close connetion
			mysqli_close($dbCon);
		}
		
		return $response;
	}
	
	
	
	public function testGetGameResultsAction($data)
	{
		if (!isset($data['game_id']))
		{
			$response['error'] = 'API_CRITERIA_INCORRECT';
		}
		else
		{
			$gameId = $data['game_id'];
			$response = array('game_id' => $gameId,
							  'test' => true,
							  'error' => 'NO_ERROR');
			
			$sql = "SELECT * FROM communicationracegames WHERE game_id='".$gameId."'";
			
			// Create Connection
			$dbCon = mysqli_connect($this->host, $this->username, $this->password, $this->dbName);
			
			// Check Connection
			if (mysqli_connect_errno()) {
				// failed to connect
				$response['error'] = 'ERROR_DATABSE_CONNECTION';
			}
			else
			{
				$sqlResult = mysqli_query($dbCon, $sql);
				if($sqlResult === FALSE) {
					$response['error'] = 'ERROR_MALFORMED_SQL';
				}
				else
				{
					$row = mysqli_fetch_array($sqlResult);
					if ($gameId != $row['game_id'] || !$row['test'])
					{
						$response['error'] = 'ERROR_INCORRECT_GAME_ID';
					}
					else
					{
						$response['game_start_time'] = floatval($row['game_start_time']);
						$response['player_a_time'] = floatval($row['player_a_time']);
						$response['player_b_time'] = floatval($row['player_b_time']);
						$response['player_c_time'] = floatval($row['player_c_time']);
						$response['player_d_time'] = floatval($row['player_d_time']);
					}
				}
			}
			
			// close connetion
			mysqli_close($dbCon);
		}
		
		return $response;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	/*********************************************************************************************************************************************************************************/
	/****************************************************************************** MOBILE *******************************************************************************************/
	/*********************************************************************************************************************************************************************************/
	
	public function joingameAction($data)
	{
		if (!isset($data['game_id']))
		{
			$response['error'] = 'API_CRITERIA_INCORRECT';
		}
		else
		{
			$gameId = strtoupper($data['game_id']);
			$response = array('game_id' => $gameId,
							  'error' => 'NO_ERROR');
			
			$sql = "SELECT * FROM communicationracegames ORDER BY id DESC LIMIT 1";
			
			// Create Connection
			$dbCon = mysqli_connect($this->host, $this->username, $this->password, $this->dbName);
			
			// Check Connection
			if (mysqli_connect_errno()) {
				// failed to connect
				$response['error'] = 'ERROR_DATABSE_CONNECTION';
			}
			else
			{
				$sqlResult = mysqli_query($dbCon, $sql);
				if($sqlResult === FALSE) {
					$response['error'] = 'ERROR_MALFORMED_SQL';
				}
				else
				{
					$row = mysqli_fetch_array($sqlResult);
					if ($gameId != $row['game_id'])
					{
						$response['error'] = 'ERROR_INCORRECT_GAME_ID';
					}
					else
					{
						if ($row['game_started'] == '1')
						{
							$response['error'] = 'ERROR_GAME_ALREADY_STARTED';
						}
						else
						{
							if ($row['player_a_in'] == '0')
							{
								mysqli_query($dbCon, "UPDATE communicationracegames SET player_a_in='1' WHERE game_id='".$gameId."'");
								$response['color'] = 'PINK';
								$response['color_code'] = '#A60063';
								$response['player_number'] = 1;
							}
							else if ($row['player_b_in'] == '0')
							{
								mysqli_query($dbCon, "UPDATE communicationracegames SET player_b_in='1' WHERE game_id='".$gameId."'");
								$response['color'] = 'BLUE';
								$response['color_code'] = '#039CB3';
								$response['player_number'] = 2;
							}
							else if ($row['player_c_in'] == '0')
							{
								mysqli_query($dbCon, "UPDATE communicationracegames SET player_c_in='1' WHERE game_id='".$gameId."'");
								$response['color'] = 'ORANGE';
								$response['color_code'] = '#FF6B35';
								$response['player_number'] = 3;
							}
							else if ($row['player_d_in'] == '0')
							{
								mysqli_query($dbCon, "UPDATE communicationracegames SET player_d_in='1' WHERE game_id='".$gameId."'");
								$response['color'] = 'GREEN';
								$response['color_code'] = '#368F3F';
								$response['player_number'] = 4;
							}
							else
							{
								$response['error'] = 'ERROR_NO_SLOTS_AVAILABLE';
							}
							
							// add timeout to response
							$response['timeout_seconds'] = floatval($row['timeout']);
							
							///////////////////////////////////////////////////////
							if (isset($response['color']))
							{
								$success = file_put_contents(DATA_PATH."/".$response['color'].".txt", "IN");
							}
							///////////////////////////////////////////////////////
						}
					}
				}
			}
			
			// close connetion
			mysqli_close($dbCon);
		}
		
		return $response;
	}
	
	
	
	
	
	
	
	
	public function submitMessageAction($data)
	{
		if (!isset($data['game_id']) || !isset($data['color']) || !isset($data['message']))
		{
			$response['error'] = 'API_CRITERIA_INCORRECT';
		}
		else
		{
			$gameId = strtoupper($data['game_id']);
			$playerColor = $data['color'];
			$playerMessage = $data['message'];
			
			$response = array('game_id' => $gameId,
						  'color' => $playerColor,
						  'message' => $playerMessage,
						  'error' => 'NO_ERROR');
			
			$sql = "SELECT * FROM communicationracegames ORDER BY id DESC LIMIT 1";
			
			// Create Connection
			$dbCon = mysqli_connect($this->host, $this->username, $this->password, $this->dbName);
			
			// Check Connection
			if (mysqli_connect_errno()) {
				// failed to connect
				$response['error'] = 'ERROR_DATABSE_CONNECTION';
			}
			else
			{
				$sqlResult = mysqli_query($dbCon, $sql);
				if($sqlResult === FALSE) {
					$response['error'] = 'ERROR_MALFORMED_SQL';
				}
				else
				{
					$row = mysqli_fetch_array($sqlResult);
					if ($gameId != $row['game_id'])
					{
						$response['error'] = 'ERROR_INCORRECT_GAME_ID';
					}
					else
					{
						$micro = microtime();
						$millis = explode(" ", $micro)[0];
						$secs = explode(" ", $micro)[1];
						$playerTime = $millis + $secs;
						
						$response['playerTime'] = $playerTime;
						
						if ($row['game_started'] == '1')
						{
							if ($playerColor == 'PINK')
							{
								mysqli_query($dbCon, "UPDATE communicationracegames SET player_a_time='".$playerTime."', player_a_message='".$playerMessage."' WHERE game_id='".$gameId."'");
							}
							else if ($playerColor == 'BLUE')
							{
								mysqli_query($dbCon, "UPDATE communicationracegames SET player_b_time='".$playerTime."', player_b_message='".$playerMessage."' WHERE game_id='".$gameId."'");
							}
							else if ($playerColor == 'ORANGE')
							{
								mysqli_query($dbCon, "UPDATE communicationracegames SET player_c_time='".$playerTime."', player_c_message='".$playerMessage."' WHERE game_id='".$gameId."'");
							}
							else if ($playerColor == 'GREEN')
							{
								mysqli_query($dbCon, "UPDATE communicationracegames SET player_d_time='".$playerTime."', player_d_message='".$playerMessage."' WHERE game_id='".$gameId."'");
							}
							else
							{
								$response['error'] = 'ERROR_INCORRECT_COLOR';
							}
							
							///////////////////////////////////////////////////////
							if (isset($response['color']))
							{
								$success = file_put_contents(DATA_PATH."/".$response['color'].".txt", $playerMessage);
							}
							///////////////////////////////////////////////////////
						}
						else
						{
							$response['error'] = 'ERROR_GAME_NOT_STARTED';
						}
					}
				}
			}
			
			// close connetion
			mysqli_close($dbCon);
		}
		
		return $response;
	}
	
	
	
	
	
	
	
	public function leaveGameAction($data)
	{
		if (!isset($data['game_id']) || !isset($data['color']))
		{
			$response['error'] = 'API_CRITERIA_INCORRECT';
		}
		else
		{
			$gameId = strtoupper($data['game_id']);
			$color = $data['color'];
			$response = array('game_id' => $gameId,
							  'color' => $color,
							  'error' => 'NO_ERROR');
			
			$sql = "SELECT * FROM communicationracegames ORDER BY id DESC LIMIT 1";
			
			// Create Connection
			$dbCon = mysqli_connect($this->host, $this->username, $this->password, $this->dbName);
			
			// Check Connection
			if (mysqli_connect_errno()) {
				// failed to connect
				$response['error'] = 'ERROR_DATABSE_CONNECTION';
			}
			else
			{
				$sqlResult = mysqli_query($dbCon, $sql);
				if($sqlResult === FALSE) {
					$response['error'] = 'ERROR_MALFORMED_SQL';
				}
				else
				{
					$row = mysqli_fetch_array($sqlResult);
					if ($gameId != $row['game_id'])
					{
						$response['error'] = 'ERROR_INCORRECT_GAME_ID';
					}
					else
					{
						if ($color == 'PINK')
						{
							mysqli_query($dbCon, "UPDATE communicationracegames SET player_a_in='0' WHERE game_id='".$gameId."'");
						}
						else if ($color == 'BLUE')
						{
							mysqli_query($dbCon, "UPDATE communicationracegames SET player_b_in='0' WHERE game_id='".$gameId."'");
						}
						else if ($color == 'ORANGE')
						{
							mysqli_query($dbCon, "UPDATE communicationracegames SET player_c_in='0' WHERE game_id='".$gameId."'");
						}
						else if ($color == 'GREEN')
						{
							mysqli_query($dbCon, "UPDATE communicationracegames SET player_d_in='0' WHERE game_id='".$gameId."'");
						}
						else
						{
							$response['error'] = 'ERROR_INCORRECT_COLOR';
						}
						
						///////////////////////////////////////////////////////
						if (isset($response['color']))
						{
							$success = file_put_contents(DATA_PATH."/".$response['color'].".txt", "OUT");
						}
						///////////////////////////////////////////////////////
					}
				}
			}
			
			// close connetion
			mysqli_close($dbCon);
		}
		
		return $response;
	}
	
	
	
	
	
	
	
}

?>