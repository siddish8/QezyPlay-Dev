<?php

/* * ****************************************************************
 * Ideabytes Software India Pvt Ltd.                              *
 * 50 Jayabheri Enclave, Gachibowli, HYD                          *
 * Created Date : 22/11/2016                                      *
 * Created By : Siddish G                                        *
 * Vision :   Channel Streaming Status                              *
 * Modified by : Gayathri D    Date : 22/07/2014    Version : V1  *
 * Description : Manage Channels Streaming page                  *
 * *************************************************************** */

/* Includes header file and class file */

include('header.php');
?>
<article class="content items-list-page">

<?php



			$stmt4 = $dbcon->prepare("SELECT id,name,url FROM channels where status=1 or id=911", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt4->execute();
			$result4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);
			
?>

<section class="section">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-block">
                                        <div class="card-title-block">
                                            <h3 class="title">
							Channels and Streaming Status
						</h3> </div>
                                        <section class="example">		
		<form id="frm2" method="post">
		 <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover">
		
			<thead>
                                    <th width="5%">Channel</th>                                  
                                    <th width="15%">URl</th>
					<th width="15%">PlayerStream</th>
                                </tr>
			</thead>
			<tbody id="channel_status_list" class="ui-sortable">
			 
                                <?php
                               
                               foreach($result4 as $row){

				//$id=$row['id'];
				$channel_id = $row['id'];
				$channel_name=$row['name'];
				$url=$row['url'];
				//$date=$row['updated_datetime'];
				
				                 ?>
                               <tr>
					<td style="display:none" id="rid"><?php echo $id ?></td>
                                                                
                                    <td><?php echo $channel_name;?></td>
					<td><?php echo $url ?></td>
									
									
                                   <td><button id="<?php echo $channel_id ?>" name="<?php echo $url ?>" onclick="return playStream(this.id,this.name)">Check</button></td>
                                  
                              
                                </tr>
                                <?php
                                }
                                ?>
				
                           </tbody>
			<tr id="no_res_div" align="center" style="margin: 5px auto;
    border-bottom: 1px solid #999;
    max-width: 80%;
    font-size: 15px;"><td colspan="6" id="no_res" style="margin:0 auto;text-align:center"></td></tr>
		</table></div></form>
 </section>
                                    </div>
                                </div>
</div>

 <div class="col-md-4">
<script src="<?php echo SITE_URL ?>/players/octoshape/swfobject.js" type="text/javascript"></script>
				<script src="<?php echo SITE_URL ?>/players/octoshape/jquery-1.6.1.js" type="text/javascript"></script>

<div id="player">
				
			</div>


 <script>
		
var player_id = "player";

 //var player_clientList = "trayuse, swf"; 

        var player_width = 400;
        var player_height = 250;		
var player_streams = [					
					
					
					                      
<?php

foreach($result4 as $row){

echo "{id: ".$row['id'].", stream: '".$row['url']."'},";

}
?>
];

var player_clientList = "trayuse, swf";
			
		var params = {allowFullScreen: true, scale: 'noscale', allowScriptAccess: 'always'};
		var attributes = {id: player_id, name: player_id};


	 	swfobject.embedSWF(document.location.protocol+'//octoshape-a.akamaihd.net/eps/players/infinitehd4/player.swf', player_id, player_width, player_height, "10.2.0", null, null, params, attributes);

var err;
window.err=0;

var player_jsbridge = {

            playerevents: {

                onPlayerReady: 'funcOnPlayerReady',
                onStop: 'funcOnStop',
                onPause: 'funcOnPause',
                onPlay: 'funcOnPlay',
                onError: 'funcOnError',

            },

            cuepoints: {
                onMetaData: "funcOnMetaData"
            }

        };

function funcOnError(msg, code) {
        console.log(msg + code);

	window.err=1;
	alert(window.err);

        jQuery.ajax({
            type: "POST",
            url: "http://ideabytestraining.com/newqezyplay/qp1/uservalidation_check",

            data: {
                "action": "setStreamStatus",
                "channel_id": window.id,
                "status": 0
            },

            success: function(response) {

                alert(response);

                //$("#msgInHeader").empty().html(response);
            }
        });

    }
    

    

	</script>  

<script>

function playStream(id, url) {

	
    if (url != "") {

	
	document.getElementById("player").os_load(id);

	window.id=id;
	window.url=url;
	


	setTimeout(function(){
		if(window.err==0)
		{
		jQuery.ajax({
			    type: "POST",
			    url: "http://ideabytestraining.com/newqezyplay/qp1/uservalidation_check",

			    data: {
				"action": "setStreamStatus",
				"channel_id": window.id,
				"status": 1
			    },

			    success: function(response) {

				alert(response);

				//$("#msgInHeader").empty().html(response);
			    }
			});
		}

		},20000);


	function countDown(i, callback) {
    callback = callback || function(){};
    var int = setInterval(function() {
        document.getElementById("displayDiv").innerHTML = "Before checking others, Wait: " + i;
        i-- || (clearInterval(int), callback());
    }, 2000);
}

	countDown(15, function(){
        alert("you can check others");
    });



        }

    return false;
}




</script>
<span id="displayDiv"></span>
</div>
</div>
</section>


</article>
<?php
include('footer.php');
?>
