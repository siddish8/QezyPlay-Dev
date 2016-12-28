<?php 
include("db-config.php");
include("function_common.php");
?>

<body>

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
$datehrbck=$date = date('Y-m-d H:i:s', strtotime('-1 hour')); 

$sql="select * from visitors_info where end_datetime between '".$datehrbck."' and '".$datenow."' group by user_id,ip_address ";
$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->execute();
$res=$stmt->fetchAll(PDO::FETCH_ASSOC);


$active_users=get_var("SELECT count(*) as count FROM (select * from visitors_info where end_datetime between '".$datehrbck."' and '".$datenow."' group by user_id,ip_address ) a");

echo "<h3>ACTIVE USERS: $active_users </h3>";
echo "
<p style='visibility:'><a id='pm_btn' style='color: #8e2c09;
    border: 1px solid #ece4e4;
    padding: 0px 4px;
    text-decoration: none;
    background-color: #e3eaea;
    font-size: 15px;
    font-weight: bolder;
' href='#' onclick='return other_user_btn()' >+</a>&nbsp;Details &nbsp;</p>
<div id='active_users' style='display:none'>";
$ips=array();
$citys=array();
echo "<table>
<tbody>";
foreach($res as $r)
{

$ip=$r['ip_address'];
array_push($ips,$ip);
$valIP = array_count_values($ips);

$user=$r['user_name'];
$lat=$r['latitude'];
$lngt=$r['longitude'];
$city=$r['city'];
array_push($citys,$city);
$valCity = array_count_values($citys);
$state=$r['state'];
$country=$r['country'];
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
</table></div>";



?>




<!-- h1>User Info</h1 -->

<div id="map" style="width:100%;height:700px"></div>

<script>
function myMap() {

     
  var myCenter = new google.maps.LatLng(51.508742,-0.120850);
  var mapCanvas = document.getElementById("map");
  var mapOptions = {center: myCenter, zoom: 3};
  var map = new google.maps.Map(mapCanvas, mapOptions);


  var marker = new google.maps.Marker({position:myCenter});
 // marker.setMap(map);

var geocoder = new google.maps.Geocoder;
var infowindow = new google.maps.InfoWindow;

var latitude=marker.getPosition().lat();               
var longitude=marker.getPosition().lng();
var latlng = {lat: parseFloat(latitude), lng: parseFloat(longitude)};

geocodeLatLng(geocoder, map, infowindow,latlng,t,x,y,h);


}
function geocodeLatLng(geocoder, map, infowindow,latlng,t,x,y,h) {
	var currentInfoWindow = null; 

	       

        geocoder.geocode({'location': latlng}, function(results, status) {
          if (status === 'OK') {
            if (results[1]) {
              map.setZoom(3);
		
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
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCxWfgi-lSSC_SngJuMQkKEI3TnBf1ypTE&libraries=places&callback=myMap"
  type="text/javascript"></script>


</body>
</html>
