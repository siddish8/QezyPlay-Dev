<?php

include("db-config.php");
include("admin-include.php");
include("function_common.php");

//Access your POST variables
$customer_id = $_SESSION['adminid'];
$admin_level = $_SESSION['adminlevel'];
//echo "id:".$agent_id;
//Unset the useless session variable

	
include("header-admin.php");
?>
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
		/*float: left;*/
		display: inline-block;
		height: 100px;
		padding: 2% 3% 3% 3%;
	}
	form[name="f_timezone"] {
    display: none;
}

	svg{width:100% !important;}
	</style>
	<link rel="stylesheet" href="css/jquery-ui.css">
	<link rel="stylesheet" href="css/morris.css">
	<script src="js/jquery-ui.js"></script>
	
	<script src="js/raphael-min.js"></script>
	<script src="js/morris.min.js"></script>
	<div id="content" role="main" style="min-height:500px;margin: 0% 0%;">
		
	
<?php
echo "<script>
var t = [];
        var x = [];
        var y = [];
        var h = [];
	var counts = {};
</script>";


$datenow=new DateTime("now");
$datenow=$datenow->format("Y-m-d H:i:s");
//$datehrbck=$date = date('Y-m-d H:i:s', strtotime('-1 hour')); 
$datehrbck=$date = date('Y-m-d H:i:s', strtotime('-1 minutes'));
//echo "<script>console.log('1:".$datehrbck ."')</script>";
$datehrbckcal=$date = date('Y-m-d H:i:s', strtotime('-1 minutes 30 seconds'));
//echo "<script>console.log('1:".$datehrbckcal ."')</script>";
//$start_or_end="start_datetime";
$start_or_end="end_datetime"; 
$cond = " AND start_datetime >= '".$datehrbckcal."'";
$startdate = $datewkbck;		
$cond .= " AND end_datetime <= '".$datenow."'";
$enddate = $datenow;		


$sql="select * from visitors_info where ".$start_or_end." between '".$datehrbck."' and '".$datenow."' group by user_id,ip_address";
//$sql="select * from visitors_info where end_datetime between '2016-09-21 05:04:16' and '2016-09-21 06:04:16' group by user_id,ip_address ";
echo "<script>console.log(\"0:".$sql."\")</script>";
$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
$res=$stmt->fetchAll(PDO::FETCH_ASSOC);


$active_users=get_var("SELECT count(*) as count FROM (select * from visitors_info where ".$start_or_end." between '".$datehrbck."' and '".$datenow."' group by user_id,ip_address ) a");

$sql1="select a.page_id,b.post_title from visitors_info a inner join wp_posts b on b.ID=a.page_id where (a.".$start_or_end." between '".$datehrbck."' and '".$datenow."') and b.post_status='publish' and b.post_type='post' group by a.page_id ";
$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt1->execute();
;
$res1=$stmt1->fetchAll(PDO::FETCH_ASSOC);



//$sql3= "SELECT count(*) as count,start_datetime as date FROM visitors_info WHERE 1".$cond." GROUP BY start_datetime";	
	$sql3= "select b.post_title,count(a.user_name) as count from visitors_info a inner join wp_posts b on b.ID=a.page_id where (a.".$start_or_end." between '".$datehrbck."' and '".$datenow."') and b.post_status='publish' and b.post_type='post' group by a.page_id ";	
	$stmt3 = $dbcon->prepare($sql3, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt3->execute();
	$hits = $stmt3->fetchAll(PDO::FETCH_ASSOC);
	//foreach($hits as $hit)
		//echo print_r($hit);
	

	$sql2 = "SELECT sum(duration) as duration,start_datetime as date FROM visitors_info WHERE 1 AND duration > 0 AND play = 1".$cond." GROUP BY start_datetime";	
	$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt2->execute();
	$durations = $stmt2->fetchAll(PDO::FETCH_ASSOC);




echo "<div style='margin:0% 5%'>
<h3 style='float:'>ACTIVE USERS: $active_users";

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
echo "</h3></div>";

echo '

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" style="width:75%;height:100%;">
    
      <!-- Modal content-->
      <div class="modal-content" style="height:100%">
        <div class="modal-header">
          <button type="button" id="close_pop" onclick="return other_user_btn()" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">ACTIVE USERS:'.$active_users.'</h4>
        </div>
        <div class="modal-body" style="height:70%">';
 
echo "<div align='' id='active_users' style='max-width:70%;width:500px;margin:30px auto;display:none'>";

$ips=array();
$citys=array();
echo "<style>
td:nth-child(odd) {
     background: unset; 
     color: black; }
</style>
<div class='table-responsive'	style='max-height: 45%; overflow-y: scroll;'>
<table class='table table-striped' style='border-collapse:collapse !important'>
<caption style='text-align: center;
    font-size: 14px;'>USERS</caption><thead></thead>
<tbody >";
foreach($res as $r)
{
$rowid=$r['id'];
$geoinfo=$r['geo_info_status'];
$ip=$r['ip_address'];
array_push($ips,$ip);
$valIP = array_count_values($ips);

$user=$r['user_name'];
$lat=$r['latitude'];
$lngt=$r['longitude'];
$city=$r['city'];
$state=$r['state'];
$country=$r['country'];


if( (($geoinfo==0) or ($city=="" and $lat=="")) and $ip!="")
{

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

<tr><td style='float:right'><?php echo $dp_name ?></td><td>   from  </td><td><?php echo $city ?></td><td>( <?php echo $state ?>,<?php echo $country ?> )</td></tr>


<script>
 t.push("<?php echo $city ?>");

//t.forEach(function(c) { counts[c] = (counts[c] || 0)+1; });

        x.push("<?php echo $lat ?>");
        y.push("<?php echo $lngt ?>");
        h.push('<p id="cnt"><strong><?php echo $city ?> (<?php echo $valCity[$city]?> Users) </strong><br/><?php echo $state ?>,<?php echo $country ?></p>');

</script>
<?php
}//for-each
echo "</tbody>
</table></div></div>";


/*if(count($hits) > 0){ 
			echo '<center><h3>Channels vs Users</h3><center><div id="chanVuserschart" style="display:none;max-height: 300px;
    max-width: 30%;
    margin: auto 20px;
    float: right;
    width: 350px;"></div>';
			$hitsdata = "";
			foreach($hits as $hit){
				$hitsdata .= '{channel:"'.$hit["post_title"].'",value:"'.$hit["count"].'"},';		
			}
			$hitsdata = substr($hitsdata,0,(strlen($hitsdata) - 1));
			?><script>	
			new Morris.Bar({
				element:'chanVuserschart',
				data:[<?php echo $hitsdata; ?>],
				eventStrokeWidth: 0,
   				 resize: true,
				    
				xkey:'channel',
				ykeys:['value'],
				labels:['Users'],
				
			});

			</script><?php
		}
*/


if(count($res1)>0)
{
echo "<style>#active_users{float:left;}</style>";
echo "<div id='live_chan' style='margin-top: 70px;
    width: 200px;max-width:30%;
    margin-left: 5%;float:;display:none'><h4 style='text-align:left'>LIVE CHANNELS: ".count($res1)."</h4>";

foreach($res1 as $r1)
{
 //echo $r1["post_title"]."<br>";
$sql4="select a.user_name,b.post_title from visitors_info a inner join wp_posts b on b.ID=a.page_id where (a.".$start_or_end." between '".$datehrbck."' and '".$datenow."') and b.post_status='publish' and b.post_type='post' and a.page_id=".$r1['page_id']." group by a.user_name ";
$stmt4 = $dbcon->prepare($sql4, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt4->execute();
$res4=$stmt4->fetchAll(PDO::FETCH_ASSOC);
$userInfo="";
foreach($res4 as $r4)
{$dp_name=get_var("SELECT display_name from wp_users where user_login='".$r4['user_name']."' ");
//$userInfo.=" {".$dp_name."} ";}
$userInfo.=$dp_name."<br>";}

echo "<a style='color:#2a85e8;' href='javascript:void(0)' class='tooltip'>".$r1["post_title"]."<span>".$userInfo."</span></a>"."<br>";
 //echo '<a href="#" data-toggle="popover" title="Users viewing" data-content="'.$userInfo.'">'.$r1["post_title"].'</a>';

}
echo "</div>";
}


         
 echo '       </div>
        <!-- div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div -->
      </div>
      
    </div>
  </div>


';


/*echo "<div style='margin:2% 5%;display:inline-flex;'>";



/*if(count($hits)>0)
{
echo "<div id='hits' style='float:right;margin-top:70px;display:none'><h4>HITS: ".count($hits)."</h4>";

foreach($hits as $hit)
 echo $hit["date"].$hit["count"]."<br>";
echo "</div>";
}

if(count($durations)>0)
{
echo "<div id='duration' style='float:right;margin-top:70px;display:none'><h4>HITS: ".count($durations)."</h4>";

foreach($durations as $duration)
 echo $duration["date"].$durations["duration"]."<br>";
echo "</div>";
}







if(count($hits) > 0){ 
			echo '<center><h3>Channels vs Users</h3><center><div id="chanVuserschart" style="display:none;max-height: 300px;
    max-width: 400px;
    margin: auto 20px;
    float: right;
    width: 350px;"></div>';
			$hitsdata = "";
			foreach($hits as $hit){
				$hitsdata .= '{channel:"'.$hit["post_title"].'",value:"'.$hit["count"].'"},';		
			}
			$hitsdata = substr($hitsdata,0,(strlen($hitsdata) - 1));
			?><script>	
			new Morris.Bar({
				element:'chanVuserschart',
				data:[<?php echo $hitsdata; ?>],
				eventStrokeWidth: 0,
   				 resize: true,
				xkey:'channel',
				ykeys:['value'],
				labels:['Users'],
				
			});

			</script><?php
		}

echo "</div>";*/
?>




<!-- h1>User Info</h1 -->

<div id="map" style="width:100%;height:700px;margin-top:0px"></div>

<script>
function qzMap() {

var zoom;
var width =  screen.width;

if(width<=768)
{
zoom=2;
minZ=2;
}
else
{
zoom=3;
minZ=3;
}
     document.getElementById("map").style.height=256*zoom;
     document.getElementById("map").style.width=256*zoom;
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
var width =  screen.width;
var x=document.getElementById('active_users');
var y=document.getElementById('live_chan');
//var z=document.getElementById('chanVuserschart');

if(x.style.display=="none")
{
x.style.display='block';

document.getElementById("pm_btn").innerHTML="-";


if(width<=1024){
y.style.display='block';
x.style.display='block';
x.style.float='unset';
//y.style.float='unset';
y.style.margin='30px auto';
}
else
{
y.style.display='inline-block';
x.style.display='inline-block';
x.style.float='left';
//y.style.float='right';
y.style.margin='5%';
}
//z.style.display='inline-block';
//alert(document.getElementById("pm_btn").innerHTML);
}
else
{
x.style.display='none';

document.getElementById("pm_btn").innerHTML="+";
y.style.display='none';
//z.style.display='none';
//alert(document.getElementById("pm_btn").innerHTML);
}

}

function back()
{
document.getElementById("pm_btn").innerHTML="+";
}
//jQuery("#close_pop").click(function(){
//jQuery("#pm_btn").html("+");

//});
setTimeout(function(){
   window.location.reload(1);
}, 60000);


jQuery(document).ready(function(){
   jQuery('[data-toggle="popover"]').popover();
});


</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCxWfgi-lSSC_SngJuMQkKEI3TnBf1ypTE&libraries=places&callback=qzMap"
  type="text/javascript"></script>


		
	</div>
	
<?php	

include("footer-admin.php");
	
?>
