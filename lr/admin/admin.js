// Initialize varibles
var $window = $(window);
var hostUri = "http://mserver/speed/";

var racers;

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
	
	$('#success').hide();
	$('#errorMessage').hide();
	GetAllRacers();
});




function CreateRacerButtons()
{
	var racerButtonHTML = '<select id="allRacers">';
	
	for (i = 0; i < racers.length; i++)
	{
		racerButtonHTML += '<option value="' + racers[i].id + '">' + racers[i].name + '</option>';
	}
	racerButtonHTML += '</select>';
	racerButtonHTML += '<br><button type="button" class="formButton" id="editRacer">Edit Selected Racer</button>'
	
	racerButtonHTML += '<br><br><button type="button" class="formButton" id="addNewRacer">Add New Racer</button>';
	
	$('#racerButtons').html(racerButtonHTML);
	
	$('#addNewRacer').click(function() {
		CreateNewRacerForm();
	});
	
	$('#editRacer').click(function() {
		CreateRacerForm($( "#allRacers option:selected" ).val());
	});
	
	$('#racerButtons').show();
}


function CreateRacerForm(racerId)
{
	$('#success').hide();
	
	var racer;
	for (i = 0; i < racers.length; i++)
	{
		if (racers[i].id == racerId)
		{
			racer = racers[i];
			break;
		}
	}
	
	$('#racerButtons').hide();
	
	var racerEditHTML = '<h1>' + racer.name + '</h1>';
	racerEditHTML += '<input type="hidden" id="racerId" value="' + racer.id + '">'
	racerEditHTML += '<span class="formLabel">Name:</span><br><textarea id="racerName" rows="1" cols="100">' +  racer.name + '</textarea>';
	racerEditHTML += '<br><br><span class="formLabel">Image Filename:</span><br><textarea id="racerImage" rows="1" cols="100">' +  racer.image + '</textarea>';
	racerEditHTML += '<form id="upload_form" action="save_image.php" method="post" enctype="multipart/form-data" target="iframe_id">' +
					 '<input type="file" class="formButton" name="fileToUpload" id="fileToUpload" value=imageName>'+
					 '<input type="button" class="formButton" value="Upload Image" name="submit" onClick="UploadImage()"></form>';
	racerEditHTML += '<br><br><span class="formLabel">Speed (mph):</span><br><textarea id="racerSpeed" rows="1" cols="100">' +  racer.speed + '</textarea>';
	racerEditHTML += '<br><br><span class="formLabel">Fun Fact:</span><br><textarea id="racerFunFact" rows="20" cols="100">' +  racer.funFact + '</textarea>';
	racerEditHTML += '<br><button type="button" class="formButton" id="saveRacer">Save Edited Racer</button>';
	racerEditHTML += '<button type="button" class="formButton" id="deleteRacer">Delete Racer</button>';
	racerEditHTML += '<button type="button" class="formButton" id="backToAll">Back</button>';
	
	$('#racerEdit').html(racerEditHTML);
	$('#racerEdit').show();
	
	$('#saveRacer').click(function() {
		SaveRacer();
	});
	
	$('#deleteRacer').click(function() {
		DeleteRacer();
	});
	
	$('#backToAll').click(function() {
		$('#racerEdit').hide();
		$('#success').hide();
		GetAllRacers();
	});
}



function CreateNewRacerForm()
{
	$('#success').hide();
	
	$('#racerButtons').hide();
	
	var racerEditHTML = '<h1>Create New Racer</h1>';
	racerEditHTML += '<span class="formLabel">Name:</span><br><textarea id="racerName" rows="1" cols="100"></textarea>';
	racerEditHTML += '<br><br><span class="formLabel">Image Filename:</span><br><textarea id="racerImage" rows="1" cols="100"></textarea>';
	racerEditHTML += '<form id="upload_form" action="save_image.php" method="post" enctype="multipart/form-data" target="iframe_id">' +
					 '<input class="formButton" type="file" name="fileToUpload" id="fileToUpload" value=imageName>'+
					 '<input class="formButton" type="button" value="Upload Image" name="submit" onClick="UploadImage()"></form>';
	racerEditHTML += '<br><br><span class="formLabel">Speed (mph):</span><br><textarea id="racerSpeed" rows="1" cols="100"></textarea>';
	racerEditHTML += '<br><br><span class="formLabel">Fun Fact:</span><br><textarea id="racerFunFact" rows="20" cols="100"></textarea>';
	racerEditHTML += '<br><button type="button" class="formButton" id="saveRacer">Save New Racer</button>';
	racerEditHTML += '<button type="button" class="formButton" id="backToAll">Back</button>';
	$('#racerEdit').html(racerEditHTML);
	$('#racerEdit').show();
	
	$('#saveRacer').click(function() {
		AddNewRacer();
	});
	
	$('#backToAll').click(function() {
		$('#racerEdit').hide();
		$('#success').hide();
		GetAllRacers();
	});
}


function UploadImage()
{
	var fakeName = $('#fileToUpload').val();
	if (fakeName != "")
	{
		var uploadFile = $('#fileToUpload').get(0).files[0];
		console.log("uploading this file: ",  uploadFile);
		
		var formData = new FormData();
		formData.append('file', uploadFile);
		
		$.ajax({url: 'save_image.php',  type: 'POST', data: formData, processData: false, contentType: false, dataType: "json", success: function(data, textStatus, jqXHR) {
			
				console.log("save_image response: ", data);
				if (data.error == 'NO_ERROR')
				{
					console.log("uploaded image to: " + $('#racerName').val());
					var realName = fakeName.split("\\").pop();
					realName = realName.split("\\").pop();
					if(realName != ""){
						$('#racerImage').val(realName);
					}
				}
				
				$('#errorMessage').show();
				$('#errorMessage').html(data.error_message);
			
				
			},error: function(data, textStatus, errorThrown) {
				console.warn("error response", data);
				console.warn("Error with UploadImage API call: " + data.responseText);
				console.warn("Chrome Error: " + errorThrown.Message);
		}});
	}
	
}


function AddNewRacer()
{
	var escapedName = escape(encodeURIComponent($('#racerName').val()));
	var escapedFunFact = escape(encodeURIComponent($('#racerFunFact').val()));
	
	var jsonData = '{ "application" : "lightrace", "action" : "addRacer", "name" : "' + escapedName + '", "speed" : "' + $('#racerSpeed').val() + '", "image" : "' + $('#racerImage').val() + '", "funFact" : "' + escapedFunFact + '"}';
	//console.log("adding racer", JSON.parse(jsonData));
	$.ajax({type: "POST", url: hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', data: JSON.parse(jsonData), dataType: "json", async: true, cache: false, success: function(data, textStatus, jqXHR) {
		//console.log("adding racer response: ", data);
		if (data.error == 'NO_ERROR')
		{
			console.log("added new racer: " + $('#racerName').val());
			$('#success').show();
			$('#racerEdit').hide();
			GetAllRacers();
		}
	},error: function(data, textStatus, errorThrown) {
		console.dir(data);
		console.warn("Error with editRacer API call: " + data.responseText);
		console.warn("Chrome Error: " + errorThrown.Message);
	}});
}


function SaveRacer()
{
	var racerName = $('#racerName').val();
	var racerImage = $('#racerImage').val();
	var racerSpeed = $('#racerSpeed').val();
	
	if (racerName != "" && racerImage != "" && racerSpeed != "")
	{
		console.log("saving racer: " + $('#racerName').val());
		var escapedName = escape(encodeURIComponent($('#racerName').val()));
		var escapedFunFact = escape(encodeURIComponent($('#racerFunFact').val()));
		
		var jsonData = '{ "application" : "lightrace", "action" : "editRacer", "id" : "' + $('#racerId').val() + '", "name" : "' + escapedName + '", "speed" : "' + $('#racerSpeed').val() + '", "image" : "' + $('#racerImage').val() + '", "funFact" : "' + escapedFunFact + '"}';
		//console.log("editing racer", JSON.parse(jsonData));
		$.ajax({type: "POST", url: hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', data: JSON.parse(jsonData), dataType: "json", async: true, cache: false, success: function(data, textStatus, jqXHR) {
			console.log("edited racer response: ", data);
			if (data.error == 'NO_ERROR')
			{
				console.log("saved racer: " + $('#racerName').val());
				$('#success').show();
			}
		},error: function(data, textStatus, errorThrown) {
			console.dir(data);
			console.warn("Error with editRacer API call: " + data.responseText);
			console.warn("Chrome Error: " + errorThrown.Message);
		}});
	}
	else
	{
		$('#errorMessage').show();
		$('#errorMessage').html("Save Failed.  Missing Name, Image Filename, and/or Speed.");
	}
}


function DeleteRacer()
{
	var jsonData = '{ "application" : "lightrace", "action" : "deleteRacer", "id" : "' + $('#racerId').val() + '"}';
	//console.log("deleting racer", JSON.parse(jsonData));
	$.ajax({type: "POST", url: hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', data: JSON.parse(jsonData), dataType: "json", async: true, cache: false, success: function(data, textStatus, jqXHR) {
		//console.log("deleting racer response: ", data);
		if (data.error == 'NO_ERROR')
		{
			console.log("deleted racer: " + $('#racerName').val());
			$('#success').show();
			$('#racerEdit').hide();
			GetAllRacers();
		}
	},error: function(data, textStatus, errorThrown) {
		console.dir(data);
		console.warn("Error with editRacer API call: " + data.responseText);
		console.warn("Chrome Error: " + errorThrown.Message);
	}});
}




function GetAllRacers()
{
	var jsonData = '{ "application" : "lightrace", "action" : "getAllRacers"}';
	$.ajax({type: "POST", url: hostUri + '?api_username=ROTO&api_key=2EA729C74E4D36B8', data: JSON.parse(jsonData), dataType: "json", async: true, cache: false, success: function(data, textStatus, jqXHR) {
		//console.log("got all racers: ", data);
		if (data.error == 'NO_ERROR')
		{
			for (i = 0; i < data.racers.length; i++)
			{
				//console.log(data.racers[i].funFact + " -> " + unescape(decodeURIComponent(data.racers[i].funFact)))
				data.racers[i].name = decodeURIComponent(unescape(data.racers[i].name));
				data.racers[i].funFact = decodeURIComponent(unescape(data.racers[i].funFact));
			}
			
			racers = data.racers;
			CreateRacerButtons();
		}
	},error: function(data, textStatus, errorThrown) {
		console.dir(data);
		console.warn("Error with GetAllRacers API call: " + data.responseText);
		console.warn("Chrome Error: " + errorThrown.Message);
	}});
	
	$('#errorMessage').hide();
}