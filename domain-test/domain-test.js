$(function() {
   
    $('#submitButton').click(function() {
       
        var host = $('#host').val();
        var user = $('#user').val();
        var pass = $('#pass').val();
        
        var jsonData = '{ "host":"' + host + '", "user":"' + user + '", "pass":"' + pass + '" }';
        
        $.ajax({type: "POST", url: "domainTester.php", data: JSON.parse(jsonData), dataType: "json", async: true, success: function(data) {
            
            console.log("success data", data);
            $('#result').html("Success: " + JSON.stringify(data));
                
        }, error: function(data, textStatus, errorThrown) {
            
            console.log("error data", data);
            $('#result').html("Error: " + data.responseText);
            
        }});
        console.log("jsonData", JSON.parse(jsonData));
        
    });
    
    
    
});