var player_id = "player"; 
var player_width = 50; 
var player_height = 10; 
 

var player_id = "player";

 //var player_clientList = "trayuse, swf"; 

        var player_width = 512;
        var player_height = 288;
 
        var player_streams = [
        {
        id: 'player',
stream: streamURL
        }
        ];
 
        //optional. starts the stream when the player is ready
        var player_stream = "player";

var params = {allowFullScreen: true, scale: 'noscale', allowScriptAccess: 'always', autostart: false};  
//        var params = {allowFullScreen: true, scale: 'noscale', allowScriptAccess: 'always'};
        var attributes = {id: player_id, name: player_id};
    swfobject.embedSWF(document.location.protocol+'//octoshape-a.akamaihd.net/eps/players/infinitehd4/player.swf', player_id, player_width, player_height, "10.2.0", null, null, params, attributes);



 
 

