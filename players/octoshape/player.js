var player_id = "player"; 
var player_width = 50; 
var player_height = 10; 
 

var player_id = "player";

 //var player_clientList = "trayuse, swf"; 

        var player_width = 512;
        var player_height = 288;

var player_streams = [					
					{id: 'Qezy-TV-LIVE', stream: streamVID},
					{id: 'Qezy-TV-VOD', stream: streamVOD},
					];

		//var player_stream = "Qezy-TV-LIVE";

		
		var player_clientList = "trayuse, swf";
			
		var params = {allowFullScreen: true, scale: 'noscale', allowScriptAccess: 'always'};
		var attributes = {id: player_id, name: player_id};


	 	swfobject.embedSWF(document.location.protocol+'//octoshape-a.akamaihd.net/eps/players/infinitehd4/player.swf', player_id, player_width, player_height, "10.2.0", null, null, params, attributes);


var player_jsbridge = {

     playerevents: {

          onPlayerReady: 'funcOnPlayerReady',
          onStop: 'funcOnStop',
          onPause: 'funcOnPause',
          onPlay: 'funcOnPlay',	
	onError:'funcOnError',
	
     },

     cuepoints: {
          onMetaData: "funcOnMetaData"
     }

};

	function funcOnStop()
	{
		
		//console.log(window.x+" Can I re-load VOD?");
	console.log("stopped");
	//swal({   title: " ",   text: "Live Stream is still not up. Re-playing VOD",   timer: 4000,   showConfirmButton: false });
	//document.getElementById('player').os_load('Qezy-TV-VOD');
	 
	}

	function funcOnPause()
	{
	//console.log("paused");
	}

	function funcOnPlay(player_id)
	{
//console.log(document.getElementById('player').os_get());
	console.log("playing"+id);
	}


	function funcOnPlayerReady()
	{
	     console.log("The player is ready to begin playback.");
		 console.log(window.id);
		 console.log(window.x+"x");
		 jQuery.ajax({
			    type: "POST",
			    url: "http://ideabytestraining.com/newqezyplay/qp1/uservalidation_check",
		
			    data: { "action" : "getStreamStatus","channel_id" : window.id },
		
			    success: function(resp)
			    {
					var response=JSON.parse(resp);
					response=response.status;
					window.x=response;
					console.log(window.x+"-"+response);

					if(response==1)
					{

						swal({   title: " ",   text: "Playing LIVE",   timer: 4000,   showConfirmButton: false });
						document.getElementById("player").os_load("Qezy-TV-LIVE");

						//document.getElementById("ostat").innerHTML="1";
						window.x=1;
						
					}

					else if(response==0)
					{
						swal({   title: " ",   text: "Channel will be LIVE shortly. Till then Enjoy this VOD",   timer: 4000,   showConfirmButton: false });
						
						document.getElementById("player").os_load("Qezy-TV-VOD");

						//document.getElementById("ostat").innerHTML="0";
						window.x=0;
						
					

					}
				//$("#msgInHeader").empty().html(response);
			    }
			});

	}

	function funcOnError(msg, code) {
        console.log(msg + code);
	window.err=1;
	 jQuery.ajax({
            type: "POST",
            url: "http://ideabytestraining.com/newqezyplay/qp1/uservalidation_check",

            data: {
                "action": "setStreamStatus",
                "channel_id": window.id,
                "status": 0
            },

            success: function(response) {

               // alert(response);
		swal({
      title: "",
      text: "<p class='saving'>Wait a moment  <span class='dt'>.</span><span class='dt'>.</span><span class='dt'>.</span></p>",
      type: "warning",
      html: "true",
      showCancelButton: false,
      showConfirmButton: false,
      confirmButtonColor: "#F9F4F3",
      cancelButtonColor: "#607D8B !important",
      confirmButtonText: "",
    });

		swal({   title: " ",   text: "Channel will be LIVE shortly. Till then Enjoy this VOD",   timer: 4000,   showConfirmButton: false });
					
					document.getElementById("player").os_load("Qezy-TV-VOD");
	window.x=0;

                //$("#msgInHeader").empty().html(response);
            }
        });

	
	//window.goforStatus();
	}
