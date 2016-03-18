<?php

class Lightrace
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
	
	
	
	
	// application
	public function addRaceAction($data)
	{
		if (!isset($data['racerAName']) || !isset($data['racerASpeed']) || !isset($data['racerBName']) || !isset($data['racerBSpeed']))
		{
			$response['error'] = 'API_CRITERIA_INCORRECT';
		}
		else
		{
			$racerAName = $data['racerAName'];
			$racerASpeed = floatval($data['racerASpeed']);
			$racerBName = $data['racerBName'];
			$racerBSpeed = floatval($data['racerBSpeed']);
			$timestamp = time();
			$response = array('racerAName' => $racerAName,
							  'racerASpeed' => $racerASpeed,
							  'racerBName' => $racerBName,
							  'racerBSpeed' => $racerBSpeed,
							  'timestamp' => $timestamp,
							  'error' => 'NO_ERROR');
			
			$sql = "INSERT INTO lightraceresults(racerAName, racerASpeed, racerBName, racerBSpeed, timestamp) VALUES ('".$racerAName."', '".$racerASpeed."', '".$racerBName."', '".$racerBSpeed."', '".$timestamp."')";
			
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
	
	
	public function endRaceAction($data)
	{
		if (!isset($data['racerADistance']) || !isset($data['racerBDistance']) || !isset($data['duration']) || !isset($data['winner']))
		{
			$response['error'] = 'API_CRITERIA_INCORRECT';
		}
		else
		{
			$racerADistance = floatval($data['racerADistance']);
			$racerBDistance = floatval($data['racerBDistance']);
			$duration = floatval($data['duration']);
			$winner = $data['winner'];
			$response = array('racerADistance' => $racerADistance,
							  'racerBDistance' => $racerBDistance,
							  'duration' => $duration,
							  'winner' => $winner,
							  'error' => 'NO_ERROR');
			
			$sql = "UPDATE lightraceresults SET racerADistance='".$racerADistance."', racerBDistance='".$racerBDistance."', duration='".$duration."', winner='".$winner."' ORDER BY id DESC LIMIT 1";
			
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
	
	
	public function getAllRacersAction()
	{
		$response = array('error' => 'NO_ERROR');
		
		$sql = "SELECT * FROM lightraceracers";
			
		// Create Connection
		$dbCon = mysqli_connect($this->host, $this->username, $this->password, $this->dbName);
		
		// Check Connection
		if (mysqli_connect_errno()) {
			// failed to connect
			$response['error'] = 'FAILED TO CONNECT';
		}
		else
		{
			$sqlResult = mysqli_query($dbCon, $sql);
			if($sqlResult === FALSE) {
				$response['error'] = 'ERROR_MALFORMED_SQL';
			}
			else
			{
				$response['racers'] = array();
				while($row = mysqli_fetch_array($sqlResult))
				{
					array_push($response['racers'], array(
						"id" => $row['id'],
						"name" => $row['name'],
						"speed" => floatval($row['speed']),
						"image" => $row['image'],
						"funFact" => addslashes($row['fun_fact'])
					));
				}	
			}
		}
		
		// close connetion
		mysqli_close($dbCon);
		
		return $response;
	}
	
	
	
	
	
	
	
	
	//  mobile
	public function getCurrentRaceInfoAction($data)
	{
		$response = array('error' => 'NO_ERROR');
		
		$sql = "SELECT * FROM lightraceresults ORDER BY id DESC LIMIT 1";
		
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
				
				
				$response['timestamp'] = $row['timestamp'];
				$response['racerAName'] = $row['racerAName'];
				$response['racerASpeed'] = $row['racerASpeed'];
				$response['racerBName'] = $row['racerBName'];
				$response['racerBSpeed'] = $row['racerBSpeed'];	
			}
		}
		
		// close connetion
		mysqli_close($dbCon);
		
		return $response;
	}
	
	
	
	public function getResultsAction($data)
	{
		if (!isset($data['count']))
		{
			$response['error'] = 'API_CRITERIA_INCORRECT';
		}
		else
		{
			$count = $data['count'];
			$response = array('count' => $count,
							  'error' => 'NO_ERROR');
			
			$countFromSQL = $count+1;
			$sql = "SELECT * FROM lightraceresults ORDER BY id DESC LIMIT " . $countFromSQL;
			
			// Create Connection
			$dbCon = mysqli_connect($this->host, $this->username, $this->password, $this->dbName);
			
			// Check Connection
			if (mysqli_connect_errno()) {
				// failed to connect
				$response['error'] = 'FAILED TO CONNECT';
			}
			else
			{
				$sqlResult = mysqli_query($dbCon, $sql);
				if($sqlResult === FALSE) {
					$response['error'] = 'ERROR_MALFORMED_SQL';
				}
				else
				{
					$response['results'] = array();
					$currentGameSkipped = false;
					
					while($row = mysqli_fetch_array($sqlResult)) {
						
						if ($currentGameSkipped)
						{
							array_push($response['results'], array(
								"winner" => $row['winner'],
								"timestamp" => $row['timestamp'],
								"duration" => $row['duration'],
								"racerAName" => $row['racerAName'],
								"racerASpeed" => $row['racerASpeed'],
								"racerADistance" => $row['racerADistance'],
								"racerBName" => $row['racerBName'],
								"racerBSpeed" => $row['racerBSpeed'],
								"racerBDistance" => $row['racerBDistance']
							));
						}
						$currentGameSkipped = true;
					}	
				}
			}
			
			// close connetion
			mysqli_close($dbCon);
		}
		
		return $response;
	}
	
	
	
	
	public function addRacerAction($data)
	{
		if (!isset($data['name']) || !isset($data['speed']) || !isset($data['image']) || !isset($data['funFact']))
		{
			$response['error'] = 'API_CRITERIA_INCORRECT';
		}
		else
		{
			$response = array('name' => $data['name'],
							  'speed' => floatval($data['speed']),
							  'image' => $data['image'],
							  'funFact' => $data['funFact'],
							  'error' => 'NO_ERROR');
			
			$sql = "INSERT INTO lightraceracers(name, speed, image, fun_fact) VALUES ('".$data['name']."', '".$data['speed']."', '".$data['image']."', '".$data['funFact']."')";
			
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
	
	
	
	
	public function editRacerAction($data)
	{
		if (!isset($data['id']) || !isset($data['name']) || !isset($data['speed']) || !isset($data['image']) || !isset($data['funFact']))
		{
			$response['error'] = 'API_CRITERIA_INCORRECT';
		}
		else
		{
			$response = array('id' => $data['id'],
							  'name' => $data['name'],
							  'speed' => floatval($data['speed']),
							  'image' => $data['image'],
							  'funFact' => $data['funFact'],
							  'error' => 'NO_ERROR');
			
			$sql = "UPDATE lightraceracers SET name='".$data['name']."', speed='".$data['speed']."', image='".$data['image']."', fun_fact='".$data['funFact']."' WHERE id='".$data['id']."'";
			
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
	
	
	public function deleteRacerAction($data)
	{
		if (!isset($data['id']))
		{
			$response['error'] = 'API_CRITERIA_INCORRECT';
		}
		else
		{
			$response = array('id' => $data['id'],
							  'error' => 'NO_ERROR');
			
			$sql = "DELETE FROM lightraceracers WHERE id=".$data['id'];
			
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}

?>