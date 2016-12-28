<?php

include("header-broadcaster.php");
?>
<article>


<style>
.navbar{margin:0 !important;}
.row{padding:0 !important}
	#logo { padding-left: 30px;}
.menudiv{
	background: lightgrey;
	border: 2px solid black;
	text-align:center;
	width: 300px;
	margin: 1%;
	color: hsl(0, 0%, 0%);
	float: left;
	height: 100px;
	padding: 2% 3% 3% 3%;
}
form[name="f_timezone"] {
    display: none;
}

footer{width:100%;position:relative;bottom:0}

@media (min-width:768px) and (max-width:768px) 
{
footer{position:fixed !important}

} 

@media (max-width:600px) 
{
footer{position:fixed !important}

} 
@media (min-width:768px) and (max-width:1200px) and (orientation:landscape)
{
footer{position:fixed !important}
}
</style>
<div id="content" role="main" style="min-height:500px;margin: 0% 0%;">
<?php
echo "<script>
var t = [];
        var x = [];
        var y = [];
        var h = [];
</script>";

$datenow=new DateTime("now");
$datenow=$datenow->format("Y-m-d H:i:s");
//$datehrbck=$date = date('Y-m-d H:i:s', strtotime('-1 hour')); 
$datehrbck=$date = date('Y-m-d H:i:s', strtotime('-1 minutes')); 
//$start_or_end="start_datetime";
$start_or_end="end_datetime"; 

$sql="select * from visitors_info where (page_id=".$channelID.") and (".$start_or_end." between '".$datehrbck."' and '".$datenow."') group by user_id,ip_address ";

$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
$res=$stmt->fetchAll(PDO::FETCH_ASSOC);


$active_users=get_var("SELECT count(*) as count FROM (select * from visitors_info where (page_id=".$channelID.") and (".$start_or_end." between '".$datehrbck."' and '".$datenow."') group by user_id,ip_address ) a");
echo "<h3 style='float:'>ACTIVE USERS: $active_users";

if($active_users>0){
echo "
<span style='visibility:'><a id='pm_btn' style='color: #8e2c09;
    border: 1px solid #ece4e4;
    padding: 0px 4px;
    text-decoration: none;
    background-color: #e3eaea;
    font-size: 15px;
    font-weight: bolder;
' href='javascript:void(0)' class='btn btn-info btn-lg' onclick='return other_user_btn()' data-toggle='modal' data-target='#myModal'>+</a>&nbsp;Details &nbsp;</span> ";
}
echo "</h3>";

echo '

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" style="width:75%;height:50%;overflow-y:scroll">
    
      <!-- Modal content-->
      <div class="modal-content" style="height:100%">
        <div class="modal-header">
          <button type="button" id="close_pop" onclick="return other_user_btn()" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">ACTIVE USERS:'.$active_users.'</h4>
        </div>
        <div class="modal-body" style="height:70%">';

echo "<div align='center' id='active_users' style='max-width:500px;margin:30px auto;display:none'>";
$ips=array();
$citys=array();
echo "<style>
td:nth-child(odd) {
     background: unset; 
     color: black; }
</style>
<table class='table table-striped' style='border-collapse:collapse !important'>
<thead><h4>USERS: </h4></thead>
<tbody >";
foreach($res as $r)
{
$rowid=$r['id'];
$geoinfo=$r['geo_info_status'];
$ip=$r['ip_address'];
array_push($ips,$ip);
$valIP = array_count_values($ips);
$duration = ($r["duration"] / 60);
$durationhr = ($duration / 60);
$duration=round($duration, 1, PHP_ROUND_HALF_DOWN); 
$durationhr=round($durationhr, 1, PHP_ROUND_HALF_DOWN);//added
$user=$r['user_name'];
$lat=$r['latitude'];
$lngt=$r['longitude'];
$city=$r['city'];
$state=$r['state'];
$country=$r['country'];

/*echo "<script>console.log('1:".$rowid ."')</script>";
echo "<script>console.log('2:".$geoinfo ."')</script>";
echo "<script>console.log('3:".$ip ."')</script>";
echo "<script>console.log('4:".$user."')</script>";
echo "<script>console.log('5:".$lat ."')</script>";
echo "<script>console.log('6:".$lngt ."')</script>";
echo "<script>console.log('7:".$city ."')</script>";
echo "<script>console.log('8:".$state ."')</script>";
echo "<script>console.log('9:".$country ."')</script>";*/
//echo "<script>console.log('1:".$country_name ."')</script>";

if( (($geoinfo==0) or ($city=="" and $lat=="")) and $ip!="")
{

//echo "<script>console.log('10:getting info from external')</script>";

$geoinfo = "http://api.ipinfodb.com/v3/ip-city/?key=13ebc6d8740ab89e93e615530a59dd0f22df559274089129135f83188578f84d&ip=$ip&format=json";

$ch_geoinfo = curl_init($geoinfo); 	

	curl_setopt($ch_geoinfo, CURLOPT_HEADER, 0);         	
	curl_setopt($ch_geoinfo, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch_geoinfo, CURLOPT_MAXREDIRS, 10);
	curl_setopt($ch_geoinfo, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch_geoinfo, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch_geoinfo,CURLOPT_CONNECTTIMEOUT,60);
	curl_setopt($ch_geoinfo, CURLOPT_FAILONERROR, 1);

	$execute_geoinfo = curl_exec($ch_geoinfo);

	if(!curl_errno($ch_geoinfo)){		
	
		$json_geoinfo = str_replace('\\', '\\\\', $execute_geoinfo);
		$json_decode_geoinfo = json_decode($json_geoinfo, true);   
		
		$country = $json_decode_geoinfo["countryName"]; //country
		$city = $json_decode_geoinfo["cityName"];			//city
		$country_code = $json_decode_geoinfo["countryCode"];	//country_code		
		$state = $json_decode_geoinfo["regionName"];		//state
		$lat=$json_decode_geoinfo["latitude"];
		$lngt=$json_decode_geoinfo["longitude"];
		}
$sql3="update visitors_info set city='".$city."', state='".$state."',country='".$country."',country_code='".$country_code."',geo_info_status='1',latitude='".$lat."',longitude='".$lngt."' where id=".$rowid." ";
$stmt3 = $dbcon->prepare($sql3, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$r3=$stmt3->execute();
if($r3)
	{
//echo "<script>console.log('10:updated info success')</script>";
	}
}


array_push($citys,$city);
$valCity = array_count_values($citys);

$dp_name=get_var("SELECT display_name from wp_users where user_login='".$user."' ");
if($dp_name=="")
{
	if($user=="")
		{
		$dp_name="Unknown User";
		}
	else
		{
		$dp_name=$user;
		}
}
?>
<tr><td style='float:right'><?php echo $dp_name ?></td><td>   from  </td><td><?php echo $city ?></td><td>( <?php echo $state ?>,<?php echo $country ?> )</td><td>
<?php if($duration>=60)
{ ?>
<a style="margin-left:-50px;color:#2a85e8" href='javascript:void(0)' class='tooltip'><?php echo $duration ?>(min)<span><?php echo $durationhr ?>(hr)</span></a>
<?php }
else
{
echo "<span style='margin-left:-50px'>".$duration."(min)</span>";
} ?>
</td></tr>

<script>
 t.push("<?php echo $city ?>");
        x.push("<?php echo $lat ?>");
        y.push("<?php echo $lngt ?>");
       h.push('<p id="cnt"><strong><?php echo $city ?> (<?php echo $valCity[$city]?> Users) </strong><br/><?php echo $state ?>,<?php echo $country ?></p>');

</script>
<?php
}//for-each
echo "</tbody>
</table></div>";

echo '       </div>
        <!-- div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div -->
      </div>
      
    </div>
  </div>


';


?>

<h1></h1>

<div id="map" style="width:100%;height:700px"></div>

<script>
function qzMap() {

var zoom;
var width =  screen.width;

if(width<=768){
zoom=2;
minZ=2;
}
else
{
zoom=3;
minZ=3;
}
 
  var myCenter = new google.maps.LatLng(51.508742,-0.120850);
  var mapCanvas = document.getElementById("map");
  var mapOptions = {center: myCenter, zoom: zoom, minZoom: minZ};
  var map = new google.maps.Map(mapCanvas, mapOptions);


  var marker = new google.maps.Marker({position:myCenter});
 // marker.setMap(map);

var geocoder = new google.maps.Geocoder;
var infowindow = new google.maps.InfoWindow;

var latitude=marker.getPosition().lat();               
var longitude=marker.getPosition().lng();
var latlng = {lat: parseFloat(latitude), lng: parseFloat(longitude)};

geocodeLatLng(geocoder, map, infowindow,latlng,t,x,y,h,zoom);

}

function geocodeLatLng(geocoder, map, infowindow,latlng,t,x,y,h,zoom) {
	var currentInfoWindow = null; 

	 geocoder.geocode({'location': latlng}, function(results, status) {
          if (status === 'OK') {
            if (results[1]) {
              map.setZoom(zoom);
		
		var icon = {
    url: "images/user5.png", // url
    scaledSize: new google.maps.Size(50, 50), // scaled size
   };	
		var i=0;
		var item;
		for ( item in t ) {
            var marker = new google.maps.Marker({
                map:       map,
                animation: google.maps.Animation.DROP,
                title:     t[i],
                position:  new google.maps.LatLng(x[i],y[i]),
		icon:icon,
                html:      h[i]
            });

            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent(this.html);
                infowindow.open(map, this);
            });
            i++;
        }

             
            } else {
              window.alert('No results found');
            }
          } else {
            window.alert('Geocoder failed due to: ' + status);
          }
        });

	      }


function other_user_btn()
{

var x=document.getElementById('active_users');
if(x.style.display=="none")
{
x.style.display='block';
document.getElementById("pm_btn").innerHTML="-";
}
else
{
x.style.display='none';
document.getElementById("pm_btn").innerHTML="+";

}

}


setTimeout(function(){
   window.location.reload(1);
}, 60000);
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCxWfgi-lSSC_SngJuMQkKEI3TnBf1ypTE&libraries=places&callback=qzMap"
  type="text/javascript"></script>

	
</div>
</article>
<?php	

include("footer-broadcaster.php");	

?>
