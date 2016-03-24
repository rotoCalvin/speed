// Initialize varibles
var $window = $(window);
var hostUri = "http://mserver/speed/";
var gameId = '', color = 'none', colorName = 'none';

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
	
	
	
	
	
	
	
	
	
	$('#doneButton').mousedown(function() {
		
		
		
		if ($('#textInput').val() != "")
		{
			if (gameId == '')
			{
				gameId = $('#textInput').val();
				$('#textInput').hide();
				$('#textInput').val('');
				$('#textInput').attr('rows', '7');
				
				var jsonData = '{"application" : "communicationsrace", ' +
								'"action" : "joingame", ' +
								'"game_id" : "' + gameId + '"}';
				jQuery.post(hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', JSON.parse(jsonData), function (data, textStatus, jqXHR) {
					//console.dir(data);
					//var response = data;
					var response = JSON.parse(data);
					console.log(response);
					if (response.error == 'NO_ERROR')
					{
						// check if we don't already have a color
						if (colorName == 'none')
						{
							// new player!
							colorName = response.color;
							color = response.color_code;
							document.getElementById('mobileInstructions').style.color = "white";
							$('#mobileInstructions').html("You will be shown a line of text that you'll have to type back.  The fastest one wins!  Can you beat the morse coder?");
							document.body.style.backgroundColor = color;
							console.log('mobile - got color: ' + colorName);
							$('#textInput').show();
						}
					}
					else
					{
						
						$('#mobileInstructions').html(response.error_message);
						$('#textInput').val('');
						$('#textInput').hide();
						console.log(response.error + ': ' + response.error_message);
					}
				});
			}
			else
			{
				//var message = escape($('#textInput').val());
				var message = $('#textInput').val();
				$('#textInput').val('');
				$('#textInput').hide();
				$('#mobileInstructions').html("Thanks for playing!  Watch the big screen for your results!");
				
				var jsonData = '{ "application" : "communicationsrace", ' +
								   '"action" : "submitMessage", ' +
								   '"game_id" : "' + gameId + '", ' +
								   '"color" : "' + colorName + '", ' +									   
								   '"message" : "' + message +'"}';
				jQuery.post(hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', JSON.parse(jsonData), function (data, textStatus, jqXHR) {
					var response = JSON.parse(data);
					console.log(response);
					if (response.error == 'NO_ERROR')
					{
						console.log('message submitted');
					}
					else
					{
						$('#mobileInstructions').html(response.error_message);
						console.log(response.error + ': ' + response.error_message);
					}
				});
				
			}
		}
		
	});
	
	
	
	window.onbeforeunload = function (e) {
		
		if (colorName != 'none')
		{
			var jsonData = '{ "application" : "communicationsrace", ' +
											   '"action" : "leaveGame", ' +
											   '"game_id" : "' + gameId + '", ' + 
											   '"color" : "' + colorName +'"}';
			jQuery.post(hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', JSON.parse(jsonData), function (data, textStatus, jqXHR) {
				var response = JSON.parse(data);
				console.log(response);
			});
			console.log('attempted to send remove player to the server');
		}
	  
  };
	
	
});