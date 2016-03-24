<?php


$host = $_POST['host'];
$user = $_POST['user'];
$pass = $_POST['pass'];

$result['host'] = $host;
$result['user'] = $user;
$result['pass'] = $pass;

// create random gameId
$characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$gameId = '';
for ($i = 0; $i < 4; $i++)
{
    $gameId .= $characters[rand(0, $charactersLength - 1)];
}
//$gameId = "TEST";

// Create Connection
$dbCon = mysqli_connect($host, $user, $pass, "speed");

// Check Connection
if (mysqli_connect_errno()) {
    // failed to connect
    $result['error'] = 'ERROR_DATABSE_CONNECTION';
}
else
{
    // successfully connected, delete all previous rows with game_id and insert new game item
    // delete old games to the games db
    $sql = "DELETE FROM communicationracegames WHERE game_id='".$gameId."'";
    if (!mysqli_query($dbCon, $sql)) {
        // error
        $result['error'] = 'ERROR_MALFORMED_SQL';
    }
    else {
        // add a new game to the games db
        $sql = "INSERT INTO communicationracegames(game_id, game_started, phrase_used, winner, morse_code_time, player_a_time, player_b_time, player_c_time, player_d_time) VALUES ('".$gameId."', 'false', '', '', '', '', '', '', '')";
        if (!mysqli_query($dbCon, $sql)) {
            // error
            $result['error'] = 'ERROR_MALFORMED_SQL';
        }
        else {
            $result['error'] = 'NO_ERROR';
        }
    }


}

// close connetion
mysqli_close($dbCon);

$result['game_id'] = $gameId;

echo json_encode($result);

?>