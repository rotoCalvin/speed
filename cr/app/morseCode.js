// constants
var NUM_OF_PLAYERS = 4, PINK = 0, BLUE = 1, ORANGE = 2, GREEN = 3;

// Initialize varibles
var $window = $(window);
var hostUri = "http://mserver/speed/";
var gameId = "";
var firstGameStarted = false;

var players = [{color: "PINK", inTimestamp: null, playerIn: false, message: '', messageTimestamp: null, time:0},
			   {color: "BLUE", inTimestamp: null, playerIn: false, message: '', messageTimestamp: null, time:0},
			   {color: "ORANGE", inTimestamp: null, playerIn: false, message: '', messageTimestamp: null, time:0},
			   {color: "GREEN", inTimestamp: null, playerIn: false, message: '', messageTimestamp: null, time:0}];

var gameTimerStarted = false;


$(function() {
  
	var xml = loadXMLDoc("../../config.xml");
	var environment = xml.getElementsByTagName("environment")[0].childNodes[0].nodeValue;
	console.log("environment: ", environment);
	
	hostUri = "http://mserver/speed/";
	if (environment == "local")
	{
		hostUri = "http://mserver/speed/";
	}
	else if (environment == "test")
	{
		hostUri = "http://www.rotosandbox.com/speed/";
	}
	else if (environment == "production")
	{
		hostUri = "http://roto.smv.org/speed/";
	}
	console.log("host URI: ", hostUri);
  
});





function ConfigLoaded() {
	
	gameTimerStarted = false;
	players[PINK].playerIn = false; players[PINK].message = ''; players[PINK].time = 0;
	players[BLUE].playerIn = false; players[BLUE].message = ''; players[BLUE].time = 0;
	players[ORANGE].playerIn = false; players[ORANGE].message = ''; players[ORANGE].time = 0;
	players[GREEN].playerIn = false; players[GREEN].message = ''; players[GREEN].time = 0;
	
	console.log('requesting a gameId');
	var jsonData = '{ "application" : "communicationsrace", ' +
					 '"action" : "startNewGame"}';
	//console.log(JSON.parse(jsonData));
	$.ajax({type: "POST", url: hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', data: JSON.parse(jsonData), dataType: "json", async: true, cache: false, success: function(data, textStatus, jqXHR) {
		console.dir(data);
		var response = data;
		//var response = JSON.parse(data);
		//console.log(response);
		if (response.error == 'NO_ERROR')
		{
			gameId = response.game_id;
			console.log("sending game id " + gameId);
			document.getElementById("flashFile").Setup(gameId);
			
			if (!firstGameStarted)
			{
				CheckForNewPinkPlayerInfo();
				CheckForNewBluePlayerInfo();
				CheckForNewOrangePlayerInfo();
				CheckForNewGreenPlayerInfo();
				firstGameStarted = true;
			}
		}
		
	},error: function(data, textStatus, errorThrown) {
			console.dir(data);
			console.warn("Error with InteractionBegin API call: " + data.responseText);
			console.warn("Chrome Error: " + errorThrown.Message);
		}});
	//});
}



function SetTimeoutTime(timeoutData) {
	
	console.log("setting timeout to " + timeoutData.timeoutSeconds);
	var jsonData = '{ "application" : "communicationsrace", ' +
					   '"action" : "setTimeout", ' +
					   '"game_id" : "' + timeoutData.gameId + '", ' + 
					   '"timeout_seconds" : "' + timeoutData.timeoutSeconds + '"}';
					   
	$.ajax({type: "POST", url: hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', data: JSON.parse(jsonData), dataType: "json", async: true, cache: false, success: function(data, textStatus, jqXHR) {
		//console.log(data);
		if (data.error == 'NO_ERROR')
		{
			console.log('set timeout to ', data.timeout_seconds);
		}
		
	},error: function(data, textStatus, errorThrown) {
			console.dir(data);
			console.warn("Error with SetTimeoutTime API call: " + data.responseText);
			console.warn("Chrome Error: " + errorThrown.Message);
		}});
}



function SetGame() {
	
	console.log("setting game");
	var jsonData = '{ "application" : "communicationsrace", ' +
					   '"action" : "setGame", ' +
					   '"game_id" : "' + gameId + '"}';
	jQuery.post(hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', JSON.parse(jsonData), function (data, textStatus, jqXHR) {
		var response = JSON.parse(data);
		if (response.error == 'NO_ERROR')
		{
			console.log('game set. no new entries allowed.');
		}
		
		jqXHR.abort();
	});
	
}




function StartGameTimer(phrase) {
	
	gameTimerStarted = true;
	console.log("starting game timer.");
	
	var jsonData = '{ "application" : "communicationsrace", ' +
					   '"action" : "startTimer", ' +
					   '"game_id" : "' + gameId + '", ' + 
					   '"phrase" : "' + btoa(phrase) +'"}'; // base64 encode: btoa(), decode: atob()
	jQuery.post(hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', JSON.parse(jsonData), function (data, textStatus, jqXHR) {
		var response = JSON.parse(data);
		console.log("started game timer response: ", response);
		if (response.error == 'NO_ERROR')
		{
			console.log('game timer started. new phrase is "' + phrase + '"');
			console.log('timestamp:', response.timestamp);
			/*console.log('micro:', response.micro);
			console.log('millis:', response.millis);
			console.log('secs:', response.secs);*/
		}
	});
	
}



function GetPlayerIndexFromColor(playerColor)
{
	var index = 0;
	if (playerColor == "PINK"){ index = 0; }
	else if (playerColor == "BLUE"){ index = 1; }
	else if (playerColor == "ORANGE"){ index = 2; }
	else if (playerColor == "GREEN"){ index = 3; }
	return index;
}




function GetPlayerTime(playerColor)
{
	var jsonData = '{ "application" : "communicationsrace", ' +
					 '"action" : "getPlayerTime", ' +
					 '"color" : "'+playerColor+'", ' +
					 '"game_id" : "' + gameId + '"}';
	$.ajax({type: "POST", url: hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', data: JSON.parse(jsonData), dataType: "json", async: true, cache: false, success: function(data, textStatus, jqXHR) {
		
			//console.log(data);
			var response = data;
			
			if (response.error == 'NO_ERROR')
			{
				var playerIndex = GetPlayerIndexFromColor(playerColor);
				players[playerIndex].time = response.player_time;
				var playerObject = { color: playerColor, time: players[playerIndex].time, message: decodeURI(players[playerIndex].message)};
				console.log(playerObject.color  + ' took ' + playerObject.time + ' to write "' + decodeURI(playerObject.message) + '"');
				document.getElementById("flashFile").MobileTimeIn(playerObject);
			}
		},
		error: function(data, textStatus, errorThrown) {
			console.log("ERROR AT GetPlayerTime");
			console.log(data.responseText);
		}
	});
}



function CheckForNewPinkPlayerInfo() {
	//console.log("checking for new pink players...");
	
	var jsonData = '{ "application" : "communicationsrace", ' +
					 '"action" : "getPlayerInfo", ' +
					 '"color" : "PINK", ' +
					 '"timestamp" : "' + players[PINK].inTimestamp + '", ' +
					 '"game_id" : "' + gameId + '"}';
	//jQuery.post(hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', JSON.parse(jsonData), function (data, textStatus, jqXHR) {
	$.ajax({type: "POST", url: hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', data: JSON.parse(jsonData), dataType: "json", async: true, cache: false, success: function(data, textStatus, jqXHR) {
			var response = data;
			//var response = JSON.parse(data);
			
			//console.log("pink player info returned", data);
			if (response.error == 'NO_ERROR' && response.update)
			{
				if (response.player_info == "IN" && !players[PINK].playerIn && !gameTimerStarted)
				{
					players[PINK].playerIn = true;
					document.getElementById("flashFile").NewPlayer('PINK');
					console.log("added PINK player");
				}
				else if (response.player_info == "OUT" && players[PINK].playerIn)
				{
					players[PINK].playerIn = false;
					document.getElementById("flashFile").RemovePlayer('PINK');
					console.log("lost PINK player");
				}
				else if (players[PINK].playerIn)
				{
					players[PINK].message = response.player_info;
					GetPlayerTime('PINK');
					//console.log("PINK message in: " + players[PINK].message);
				}
			}
			
			players[PINK].inTimestamp = response.timestamp;
			setTimeout('CheckForNewPinkPlayerInfo()', 1000);
			//jqXHR.abort();
		},
		error: function(data, textStatus, errorThrown) {
			setTimeout('CheckForNewPinkPlayerInfo()', 15000);
			console.log("ERROR ON AISLE PINK");
			console.log(data);
			console.log(data.responseText);
		}
	});
}


function CheckForNewBluePlayerInfo() {
	//console.log("checking for new blue players...");
	
	var jsonData = '{ "application" : "communicationsrace", ' +
					 '"action" : "getPlayerInfo", ' +
					 '"color" : "BLUE", ' +
					 '"timestamp" : "' + players[BLUE].inTimestamp + '", ' +
					 '"game_id" : "' + gameId + '"}';
	//jQuery.post(hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', JSON.parse(jsonData), function (data, textStatus, jqXHR) {
	$.ajax({type: "POST", url: hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', data: JSON.parse(jsonData), dataType: "json", async: true, cache: false, success: function(data, textStatus, jqXHR) {
		
			//console.dir(data);
			var response = data;
			//var response = JSON.parse(data);
			
			if (response.error == 'NO_ERROR' && response.update)
			{
				if (response.player_info == "IN" && !players[BLUE].playerIn && !gameTimerStarted)
				{
					players[BLUE].playerIn = true;
					document.getElementById("flashFile").NewPlayer('BLUE');
					console.log("added BLUE player");
				}
				else if (response.player_info == "OUT" && players[BLUE].playerIn)
				{
					players[BLUE].playerIn = false;
					document.getElementById("flashFile").RemovePlayer('BLUE');
					console.log("lost BLUE player");
				}
				else if (players[BLUE].playerIn)
				{
					players[BLUE].message = response.player_info;
					GetPlayerTime('BLUE');
					//console.log("BLUE message in: " + players[BLUE].message);
				}
			}
			
			players[BLUE].inTimestamp = response.timestamp;
			setTimeout('CheckForNewBluePlayerInfo()', 1000);
			//jqXHR.abort();
		},
		error: function(data, textStatus, errorThrown) {
			setTimeout('CheckForNewBluePlayerInfo()', 15000);
		}
	});
}

function CheckForNewOrangePlayerInfo() {
	//console.log("checking for new orange players...");
	
	var jsonData = '{ "application" : "communicationsrace", ' +
					 '"action" : "getPlayerInfo", ' +
					 '"color" : "ORANGE", ' +
					 '"timestamp" : "' + players[ORANGE].inTimestamp + '", ' +
					 '"game_id" : "' + gameId + '"}';
	//jQuery.post(hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', JSON.parse(jsonData), function (data, textStatus, jqXHR) {
	$.ajax({type: "POST", url: hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', data: JSON.parse(jsonData), dataType: "json", async: true, cache: false, success: function(data, textStatus, jqXHR) {
		
			//console.dir(data);
			var response = data;
			//var response = JSON.parse(data);
			
			if (response.error == 'NO_ERROR' && response.update)
			{
				if (response.player_info == "IN" && !players[ORANGE].playerIn && !gameTimerStarted)
				{
					players[ORANGE].playerIn = true;
					document.getElementById("flashFile").NewPlayer('ORANGE');
					console.log("added ORANGE player");
				}
				else if (response.player_info == "OUT" && players[ORANGE].playerIn)
				{
					players[ORANGE].playerIn = false;
					document.getElementById("flashFile").RemovePlayer('ORANGE');
					console.log("lost ORANGE player");
				}
				else if (players[ORANGE].playerIn)
				{
					players[ORANGE].message = response.player_info;
					GetPlayerTime('ORANGE');
					//console.log("ORANGE message in: " + players[ORANGE].message);
				}
			}
			
			players[ORANGE].inTimestamp = response.timestamp;
			setTimeout('CheckForNewOrangePlayerInfo()', 1000);
			//jqXHR.abort();
		},
		error: function(data, textStatus, errorThrown) {
			setTimeout('CheckForNewOrangePlayerInfo()', 15000);
		}
	});
}

function CheckForNewGreenPlayerInfo() {
	//console.log("checking for new green players...");
	
	var jsonData = '{ "application" : "communicationsrace", ' +
					 '"action" : "getPlayerInfo", ' +
					 '"color" : "GREEN", ' +
					 '"timestamp" : "' + players[GREEN].inTimestamp + '", ' +
					 '"game_id" : "' + gameId + '"}';
	//jQuery.post(hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', JSON.parse(jsonData), function (data, textStatus, jqXHR) {
	$.ajax({type: "POST", url: hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', data: JSON.parse(jsonData), dataType: "json", async: true, cache: false, success: function(data, textStatus, jqXHR) {
		
			//console.dir(data);
			var response = data;
			//var response = JSON.parse(data);
			
			if (response.error == 'NO_ERROR' && response.update)
			{
				if (response.player_info == "IN" && !players[GREEN].playerIn && !gameTimerStarted)
				{
					players[GREEN].playerIn = true;
					document.getElementById("flashFile").NewPlayer('GREEN');
					console.log("added GREEN player");
				}
				else if (response.player_info == "OUT" && players[GREEN].playerIn)
				{
					players[GREEN].playerIn = false;
					document.getElementById("flashFile").RemovePlayer('GREEN');
					console.log("lost GREEN player");
				}
				else if (players[GREEN].playerIn)
				{
					players[GREEN].message = response.player_info;
					GetPlayerTime('GREEN');
					//console.log("GREEN message in: " + players[GREEN].message);
				}
			}
			
			players[GREEN].inTimestamp = response.timestamp;
			setTimeout('CheckForNewGreenPlayerInfo()', 1000);
			//jqXHR.abort();
		},
		error: function(data, textStatus, errorThrown) {
			setTimeout('CheckForNewGreenPlayerInfo()', 15000);
		}
	});
}



function Reset()
{
	ConfigLoaded();
}







function DebugText(debugText) {
	console.log("APPLICATION: " + debugText);
}