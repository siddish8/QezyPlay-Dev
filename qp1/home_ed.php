<?php
include("db-config.php");
include("admin-include.php");
include("function_common.php");

//Access your POST variables
$customer_id = $_SESSION['adminid'];
$admin_level = $_SESSION['adminlevel'];
$admin=$_SESSION['adminname'];
//echo "id:".$agent_id;
//Unset the useless session variable

?>

<!doctype html>
<html class="no-js" lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title> QEZYPLAY - AdminPortal | HOME </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->
        <link rel="stylesheet" href="css/vendor.css">
        <!-- Theme initialization -->
        <script>
            var themeSettings = (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) :
            {};
            var themeName = themeSettings.themeName || '';
            if (themeName)
            {
                document.write('<link rel="stylesheet" id="theme-style" href="css/app-' + themeName + '.css">');
            }
            else
            {
                document.write('<link rel="stylesheet" id="theme-style" href="css/app.css">');
            }
        </script>
    </head>

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




/*echo "<div style='margin:0% 5%'>
<h3 style='float:'>ACTIVE USERS: $active_users";*/

if($active_users>0){
/*echo "
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
<tbody >";*/
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

<!--<tr><td style='float:right'><?php echo $dp_name ?></td><td>   from  </td><td><?php echo $city ?></td><td>( <?php echo $state ?>,<?php echo $country ?> )</td></tr-->


<script>
 t.push("<?php echo $city ?>");

//t.forEach(function(c) { counts[c] = (counts[c] || 0)+1; });

        x.push("<?php echo $lat ?>");
        y.push("<?php echo $lngt ?>");
        h.push('<p id="cnt"><strong><?php echo $city ?> (<?php echo $valCity[$city]?> Users) </strong><br/><?php echo $state ?>,<?php echo $country ?></p>');

</script>
<?php
}//for-each
/*echo "</tbody>
</table></div></div>";*/


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
//echo "<style>#active_users{float:left;}</style>";
/*echo "<div id='live_chan' style='margin-top: 70px;
    width: 200px;max-width:30%;
    margin-left: 5%;float:;display:none'><h4 style='text-align:left'>LIVE CHANNELS: ".count($res1)."</h4>";*/

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

//echo "<a style='color:#2a85e8;' href='javascript:void(0)' class='tooltip'>".$r1["post_title"]."<span>".$userInfo."</span></a>"."<br>";
 //echo '<a href="#" data-toggle="popover" title="Users viewing" data-content="'.$userInfo.'">'.$r1["post_title"].'</a>';

}
//echo "</div>";
}


         
 /*echo '       </div>
        <!-- div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div -->
      </div>
      
    </div>
  </div>


';*/
?>

        <div class="main-wrapper">
            <div class="app" id="app">
                <header class="header">
                    <div class="header-block header-block-collapse hidden-lg-up"> <button class="collapse-btn" id="sidebar-collapse-btn">
    			<i class="fa fa-bars"></i>
    		</button> </div>
                    <div class="header-block header-block-search hidden-sm-down">
                        <!--form role="search">
                            <div class="input-container"> <i class="fa fa-search"></i> <input type="search" placeholder="Search">
                                <div class="underline"></div>
                            </div>
                        </form-->
                    </div>
                    <div class="header-block header-block-buttons">
                        <!--a href="https://github.com/modularcode/modular-admin-html" class="btn btn-oval btn-sm rounded-s header-btn"> <i class="fa fa-github-alt"></i> View on GitHub </a>
                        <a href="https://github.com/modularcode/modular-admin-html/releases/download/v1.0.1/modular-admin-html-1.0.1.zip" class="btn btn-oval btn-sm rounded-s header-btn"> <i class="fa fa-cloud-download"></i> Download .zip </a -->
                    </div>
                    <div class="header-block header-block-nav">
                        <ul class="nav-profile">
                            <!-- li class="notifications new">
                                <a href="" data-toggle="dropdown"> <i class="fa fa-bell-o"></i> <sup>
    			      <span class="counter">8</span>
    			    </sup> </a>
                                <div class="dropdown-menu notifications-dropdown-menu">
                                    <ul class="notifications-container">
                                        <li>
                                            <a href="" class="notification-item">
                                                <div class="img-col">
                                                    <div class="img" style="background-image: url('assets/faces/3.jpg')"></div>
                                                </div>
                                                <div class="body-col">
                                                    <p> <span class="accent">Zack Alien</span> pushed new commit: <span class="accent">Fix page load performance issue</span>. </p>
                                                </div>
                                            </a>
                                        </li 
                                        <li>
                                            <a href="" class="notification-item">
                                                <div class="img-col">
                                                    <div class="img" style="background-image: url('assets/faces/5.jpg')"></div>
                                                </div>
                                                <div class="body-col">
                                                    <p> <span class="accent">Amaya Hatsumi</span> started new task: <span class="accent">Dashboard UI design.</span>. </p>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="" class="notification-item">
                                                <div class="img-col">
                                                    <div class="img" style="background-image: url('assets/faces/8.jpg')"></div>
                                                </div>
                                                <div class="body-col">
                                                    <p> <span class="accent">Andy Nouman</span> deployed new version of <span class="accent">NodeJS REST Api V3</span> </p>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                    <footer>
                                        <ul>
                                            <li> <a href="">
    			            View All
    			          </a> </li>
                                        </ul>
                                    </footer>
                                </div>
                            </li-->
                            <li class="profile dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    <div class="img" style="background-image: url('../qp1/assets/images/admin2.png')"> </div>  <span class="name"> 
    			      <?php echo $admin ?>
    			    </span> </a>
                                <div class="dropdown-menu profile-dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <a class="dropdown-item" href="#"> <i class="fa fa-user icon"></i> Profile </a>
                                    <a class="dropdown-item" href="#"> <i class="fa fa-bell icon"></i> Notifications </a>
                                    <a class="dropdown-item" href="#"> <i class="fa fa-gear icon"></i> Settings </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="login.html"> <i class="fa fa-power-off icon"></i> Logout </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </header>
                <aside class="sidebar">
                    <div class="sidebar-container">
                        <div class="sidebar-header">
                            <div class="brand">
				<img class="logo1" alt="" src='../qp1/assets/images/qp_logo.png' style="width: 100px; height: 50px">
                                <!--<div class="logo"> <span class="l l1"></span> <span class="l l2"></span> <span class="l l3"></span> <span class="l l4"></span> <span class="l l5"></span> </div--> AdminPortal </div>
                        </div>
                        <nav class="menu">
                            <ul class="nav metismenu" id="sidebar-menu">
                                <li class="active">
                                    <a href="home.php"> <i class="fa fa-home"></i> Dashboard </a>
                                </li>
				 <li>
                                    <a href="admin-main.php"> <i class="fa fa-th-large"></i>LIVE Geo-Map <i class="fa arrow"></i> </a>
                                    
                                </li>
				 <li>
                                    <a href=""> <i class="fa fa-th-large"></i> Statistics <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a href="admin_channel_analytics.php">
    								All Statistics
    							</a> </li>
                                        <li> <a href="admin_statistics_by_channel.php">
    								Statistics By Channel
    							</a> </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href=""> <i class="fa fa-th-large"></i> Items Manager <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a href="items-list.php">
    								Items List
    							</a> </li>
                                        <li> <a href="item-editor.html">
    								Item Editor
    							</a> </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href=""> <i class="fa fa-bar-chart"></i> Charts <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a href="charts-flot.html">
    								Flot Charts
    							</a> </li>
                                        <li> <a href="charts-morris.html">
    								Morris Charts
    							</a> </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href=""> <i class="fa fa-table"></i> Tables <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a href="static-tables.html">
    								Static Tables
    							</a> </li>
                                        <li> <a href="responsive-tables.html">
    								Responsive Tables
    							</a> </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="forms.html"> <i class="fa fa-pencil-square-o"></i> Forms </a>
                                </li>
                                <li>
                                    <a href=""> <i class="fa fa-desktop"></i> UI Elements <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a href="buttons.html">
    								Buttons
    							</a> </li>
                                        <li> <a href="cards.html">
    								Cards
    							</a> </li>
                                        <li> <a href="typography.html">
    								Typography
    							</a> </li>
                                        <li> <a href="icons.html">
    								Icons
    							</a> </li>
                                        <li> <a href="grid.html">
    								Grid
    							</a> </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href=""> <i class="fa fa-file-text-o"></i> Pages <i class="fa arrow"></i> </a>
                                    <ul>
                                        <li> <a href="login.html">
    								Login
    							</a> </li>
                                        <li> <a href="signup.html">
    								Sign Up
    							</a> </li>
                                        <li> <a href="reset.html">
    								Reset
    							</a> </li>
                                        <li> <a href="error-404.html">
    								Error 404 App
    							</a> </li>
                                        <li> <a href="error-404-alt.html">
    								Error 404 Global
    							</a> </li>
                                        <li> <a href="error-500.html">
    								Error 500 App
    							</a> </li>
                                        <li> <a href="error-500-alt.html">
    								Error 500 Global
    							</a> </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="https://github.com/modularcode/modular-admin-html"> <i class="fa fa-github-alt"></i> Theme Docs </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <footer class="sidebar-footer">
                        <ul class="nav metismenu" id="customize-menu">
                            <li>
                                <ul>
                                    <li class="customize">
                                        <div class="customize-item">
                                            <div class="row customize-header">
                                                <div class="col-xs-4"> </div>
                                                <div class="col-xs-4"> <label class="title">fixed</label> </div>
                                                <div class="col-xs-4"> <label class="title">static</label> </div>
                                            </div>
                                            <div class="row hidden-md-down">
                                                <div class="col-xs-4"> <label class="title">Sidebar:</label> </div>
                                                <div class="col-xs-4"> <label>
    				                        <input class="radio" type="radio" name="sidebarPosition" value="sidebar-fixed" >
    				                        <span></span>
    				                    </label> </div>
                                                <div class="col-xs-4"> <label>
    				                        <input class="radio" type="radio" name="sidebarPosition" value="">
    				                        <span></span>
    				                    </label> </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-4"> <label class="title">Header:</label> </div>
                                                <div class="col-xs-4"> <label>
    				                        <input class="radio" type="radio" name="headerPosition" value="header-fixed">
    				                        <span></span>
    				                    </label> </div>
                                                <div class="col-xs-4"> <label>
    				                        <input class="radio" type="radio" name="headerPosition" value="">
    				                        <span></span>
    				                    </label> </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-4"> <label class="title">Footer:</label> </div>
                                                <div class="col-xs-4"> <label>
    				                        <input class="radio" type="radio" name="footerPosition" value="footer-fixed">
    				                        <span></span>
    				                    </label> </div>
                                                <div class="col-xs-4"> <label>
    				                        <input class="radio" type="radio" name="footerPosition" value="">
    				                        <span></span>
    				                    </label> </div>
                                            </div>
                                        </div>
                                        <div class="customize-item">
                                            <ul class="customize-colors">
                                                <li> <span class="color-item color-red" data-theme="red"></span> </li>
                                                <li> <span class="color-item color-orange" data-theme="orange"></span> </li>
                                                <li> <span class="color-item color-green" data-theme="green"></span> </li>
                                                <li> <span class="color-item color-seagreen" data-theme="seagreen"></span> </li>
                                                <li> <span class="color-item color-blue active" data-theme=""></span> </li>
                                                <li> <span class="color-item color-purple" data-theme="purple"></span> </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                                <a href=""> <i class="fa fa-cog"></i> Customize </a>
                            </li>
                        </ul>
                    </footer>
                </aside>
                <div class="sidebar-overlay" id="sidebar-overlay"></div>
                <article class="content dashboard-page">
                    <section class="section">
                        <div class="row sameheight-container">
                            <div class="col col-xs-12 col-sm-12 col-md-6 col-xl-5 stats-col">
                                <div class="card sameheight-item stats" data-exclude="xs">
                                    <div class="card-block">
                                        <div class="title-block">
                                            <h4 class="title">
            				Stats
            			</h4>
                                            <p class="title-description"> Website metrics for <a href="http://modularteam.github.io/modularity-free-admin-dashboard-theme-html/">
            					your awesome project
            				</a> </p>
                                        </div>
                                        <div class="row row-sm stats-container">
                                            <div class="col-xs-12 col-sm-6 stat-col">
                                                <div class="stat-icon"> <i class="fa fa-rocket"></i> </div>
                                                <div class="stat">
                                                    <div class="value"> 5407 </div>
                                                    <div class="name"> Active items </div>
                                                </div> <progress class="progress stat-progress" value="75" max="100">
            					<div class="progress">
            						<span class="progress-bar" style="width: 75%;"></span>
            					</div>
            				</progress> </div>
                                            <div class="col-xs-12 col-sm-6 stat-col">
                                                <div class="stat-icon"> <i class="fa fa-shopping-cart"></i> </div>
                                                <div class="stat">
                                                    <div class="value"> 78464 </div>
                                                    <div class="name"> Items sold </div>
                                                </div> <progress class="progress stat-progress" value="25" max="100">
            					<div class="progress">
            						<span class="progress-bar" style="width: 25%;"></span>
            					</div>
            				</progress> </div>
                                            <div class="col-xs-12 col-sm-6  stat-col">
                                                <div class="stat-icon"> <i class="fa fa-line-chart"></i> </div>
                                                <div class="stat">
                                                    <div class="value"> $80.560 </div>
                                                    <div class="name"> Monthly income </div>
                                                </div> <progress class="progress stat-progress" value="60" max="100">
            					<div class="progress">
            						<span class="progress-bar" style="width: 60%;"></span>
            					</div>
            				</progress> </div>
                                            <div class="col-xs-12 col-sm-6  stat-col">
                                                <div class="stat-icon"> <i class="fa fa-users"></i> </div>
                                                <div class="stat">
                                                    <div class="value"> 359 </div>
                                                    <div class="name"> Total users </div>
                                                </div> <progress class="progress stat-progress" value="34" max="100">
            					<div class="progress">
            						<span class="progress-bar" style="width: 34%;"></span>
            					</div>
            				</progress> </div>
                                            <div class="col-xs-12 col-sm-6  stat-col">
                                                <div class="stat-icon"> <i class="fa fa-list-alt"></i> </div>
                                                <div class="stat">
                                                    <div class="value"> 59 </div>
                                                    <div class="name"> Tickets closed </div>
                                                </div> <progress class="progress stat-progress" value="49" max="100">
            					<div class="progress">
            						<span class="progress-bar" style="width: 49%;"></span>
            					</div>
            				</progress> </div>
                                            <div class="col-xs-12 col-sm-6 stat-col">
                                                <div class="stat-icon"> <i class="fa fa-dollar"></i> </div>
                                                <div class="stat">
                                                    <div class="value"> $780.064 </div>
                                                    <div class="name"> Total income </div>
                                                </div> <progress class="progress stat-progress" value="15" max="100">
            					<div class="progress">
            						<span class="progress-bar" style="width: 15%;"></span>
            					</div>
            				</progress> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-xs-12 col-sm-12 col-md-6 col-xl-7 history-col">
                                <div class="card sameheight-item" data-exclude="xs">
                                    <div class="card-header card-header-sm bordered">
                                        <div class="header-block">
                                            <h3 class="title">History</h3> </div>
                                        <ul class="nav nav-tabs pull-right" role="tablist">
                                            <li class="nav-item"> <a class="nav-link active" href="#visits" role="tab" data-toggle="tab">Visits</a> </li>
                                            <li class="nav-item"> <a class="nav-link" href="#downloads" role="tab" data-toggle="tab">Downloads</a> </li>
                                        </ul>
                                    </div>
                                    <div class="card-block">
                                        <div class="tab-content">
                                            <div role="tabpanel" class="tab-pane active fade in" id="visits">
                                                <p class="title-description"> Number of unique visits last 30 days </p>
                                                <div id="dashboard-visits-chart"></div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="downloads">
                                                <p class="title-description"> Number of downloads last 30 days </p>
                                                <div id="dashboard-downloads-chart"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="section">
                        <div class="row sameheight-container">
                            <div class="col-xl-8">
                                <div class="card sameheight-item items" data-exclude="xs,sm,lg">
                                    <div class="card-header bordered">
                                        <div class="header-block">
                                            <h3 class="title">
            				Items
            			</h3> <a href="item-editor.html" class="btn btn-primary btn-sm rounded">
            				Add new
            			</a> </div>
                                        <div class="header-block pull-right"> <label class="search">
            				<input class="search-input" placeholder="search...">
            				<i class="fa fa-search search-icon"></i>
            			</label>
                                            <div class="pagination">
                                                <a href="" class="btn btn-primary btn-sm rounded"> <i class="fa fa-angle-up"></i> </a>
                                                <a href="" class="btn btn-primary btn-sm rounded"> <i class="fa fa-angle-down"></i> </a>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="item-list striped">
                                        <li class="item item-list-header hidden-sm-down">
                                            <div class="item-row">
                                                <div class="item-col item-col-header fixed item-col-img xs"></div>
                                                <div class="item-col item-col-header item-col-title">
                                                    <div> <span>Name</span> </div>
                                                </div>
                                                <div class="item-col item-col-header item-col-sales">
                                                    <div> <span>Sales</span> </div>
                                                </div>
                                                <div class="item-col item-col-header item-col-stats">
                                                    <div class="no-overflow"> <span>Stats</span> </div>
                                                </div>
                                                <div class="item-col item-col-header item-col-date">
                                                    <div> <span>Published</span> </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="item">
                                            <div class="item-row">
                                                <div class="item-col fixed item-col-img xs">
                                                    <a href="">
                                                        <div class="item-img xs rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/brad_frost/128.jpg)"></div>
                                                    </a>
                                                </div>
                                                <div class="item-col item-col-title no-overflow">
                                                    <div>
                                                        <a href="" class="">
                                                            <h4 class="item-title no-wrap">
            									12 Myths Uncovered About IT &amp; Software
            								</h4> </a>
                                                    </div>
                                                </div>
                                                <div class="item-col item-col-sales">
                                                    <div class="item-heading">Sales</div>
                                                    <div> 4958 </div>
                                                </div>
                                                <div class="item-col item-col-stats">
                                                    <div class="item-heading">Stats</div>
                                                    <div class="no-overflow">
                                                        <div class="item-stats sparkline" data-type="bar"></div>
                                                    </div>
                                                </div>
                                                <div class="item-col item-col-date">
                                                    <div class="item-heading">Published</div>
                                                    <div> 21 SEP 10:45 </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="item">
                                            <div class="item-row">
                                                <div class="item-col fixed item-col-img xs">
                                                    <a href="">
                                                        <div class="item-img xs rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/_everaldo/128.jpg)"></div>
                                                    </a>
                                                </div>
                                                <div class="item-col item-col-title no-overflow">
                                                    <div>
                                                        <a href="" class="">
                                                            <h4 class="item-title no-wrap">
            									50% of things doesn&#x27;t really belongs to you
            								</h4> </a>
                                                    </div>
                                                </div>
                                                <div class="item-col item-col-sales">
                                                    <div class="item-heading">Sales</div>
                                                    <div> 192 </div>
                                                </div>
                                                <div class="item-col item-col-stats">
                                                    <div class="item-heading">Stats</div>
                                                    <div class="no-overflow">
                                                        <div class="item-stats sparkline" data-type="bar"></div>
                                                    </div>
                                                </div>
                                                <div class="item-col item-col-date">
                                                    <div class="item-heading">Published</div>
                                                    <div> 21 SEP 10:45 </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="item">
                                            <div class="item-row">
                                                <div class="item-col fixed item-col-img xs">
                                                    <a href="">
                                                        <div class="item-img xs rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/eduardo_olv/128.jpg)"></div>
                                                    </a>
                                                </div>
                                                <div class="item-col item-col-title no-overflow">
                                                    <div>
                                                        <a href="" class="">
                                                            <h4 class="item-title no-wrap">
            									Vestibulum tincidunt amet laoreet mauris sit sem aliquam cras maecenas vel aliquam.
            								</h4> </a>
                                                    </div>
                                                </div>
                                                <div class="item-col item-col-sales">
                                                    <div class="item-heading">Sales</div>
                                                    <div> 2143 </div>
                                                </div>
                                                <div class="item-col item-col-stats">
                                                    <div class="item-heading">Stats</div>
                                                    <div class="no-overflow">
                                                        <div class="item-stats sparkline" data-type="bar"></div>
                                                    </div>
                                                </div>
                                                <div class="item-col item-col-date">
                                                    <div class="item-heading">Published</div>
                                                    <div> 21 SEP 10:45 </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="item">
                                            <div class="item-row">
                                                <div class="item-col fixed item-col-img xs">
                                                    <a href="">
                                                        <div class="item-img xs rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/why_this/128.jpg)"></div>
                                                    </a>
                                                </div>
                                                <div class="item-col item-col-title no-overflow">
                                                    <div>
                                                        <a href="" class="">
                                                            <h4 class="item-title no-wrap">
            									10 tips of Object Oriented Design
            								</h4> </a>
                                                    </div>
                                                </div>
                                                <div class="item-col item-col-sales">
                                                    <div class="item-heading">Sales</div>
                                                    <div> 124 </div>
                                                </div>
                                                <div class="item-col item-col-stats">
                                                    <div class="item-heading">Stats</div>
                                                    <div class="no-overflow">
                                                        <div class="item-stats sparkline" data-type="bar"></div>
                                                    </div>
                                                </div>
                                                <div class="item-col item-col-date">
                                                    <div class="item-heading">Published</div>
                                                    <div> 21 SEP 10:45 </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="item">
                                            <div class="item-row">
                                                <div class="item-col fixed item-col-img xs">
                                                    <a href="">
                                                        <div class="item-img xs rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/w7download/128.jpg)"></div>
                                                    </a>
                                                </div>
                                                <div class="item-col item-col-title no-overflow">
                                                    <div>
                                                        <a href="" class="">
                                                            <h4 class="item-title no-wrap">
            									Sometimes friend tells it is cold
            								</h4> </a>
                                                    </div>
                                                </div>
                                                <div class="item-col item-col-sales">
                                                    <div class="item-heading">Sales</div>
                                                    <div> 10214 </div>
                                                </div>
                                                <div class="item-col item-col-stats">
                                                    <div class="item-heading">Stats</div>
                                                    <div class="no-overflow">
                                                        <div class="item-stats sparkline" data-type="bar"></div>
                                                    </div>
                                                </div>
                                                <div class="item-col item-col-date">
                                                    <div class="item-heading">Published</div>
                                                    <div> 21 SEP 10:45 </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="item">
                                            <div class="item-row">
                                                <div class="item-col fixed item-col-img xs">
                                                    <a href="">
                                                        <div class="item-img xs rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/pankogut/128.jpg)"></div>
                                                    </a>
                                                </div>
                                                <div class="item-col item-col-title no-overflow">
                                                    <div>
                                                        <a href="" class="">
                                                            <h4 class="item-title no-wrap">
            									New ways of conceptual thinking
            								</h4> </a>
                                                    </div>
                                                </div>
                                                <div class="item-col item-col-sales">
                                                    <div class="item-heading">Sales</div>
                                                    <div> 3217 </div>
                                                </div>
                                                <div class="item-col item-col-stats">
                                                    <div class="item-heading">Stats</div>
                                                    <div class="no-overflow">
                                                        <div class="item-stats sparkline" data-type="bar"></div>
                                                    </div>
                                                </div>
                                                <div class="item-col item-col-date">
                                                    <div class="item-heading">Published</div>
                                                    <div> 21 SEP 10:45 </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card sameheight-item sales-breakdown" data-exclude="xs,sm,lg">
                                    <div class="card-header">
                                        <div class="header-block">
                                            <h3 class="title">
             				Sales breakdown
             			</h3> </div>
                                    </div>
                                    <div class="card-block">
                                        <div class="dashboard-sales-breakdown-chart" id="dashboard-sales-breakdown-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="section map-tasks">
                        <div class="row sameheight-container">
                            <div class="col-md-8">
                                <div class="card sameheight-item" data-exclude="xs,sm">
                                    <div class="card-header">
                                        <div class="header-block">
                                            <h3 class="title">
            	            Sales by countries
            	        </h3> </div>
                                    </div>
                                    <div class="card-block">
                                        <!--div id="dashboard-sales-map" style="width: 100%; height: 400px;"></div-->
					<div id="map" style="width:100%;height:700px;margin-top:0px"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card tasks sameheight-item" data-exclude="xs,sm">
                                    <div class="card-header bordered">
                                        <div class="header-block">
                                            <h3 class="title">
                           Tasks
                        </h3> </div>
                                        <div class="header-block pull-right"> <a href="" class="btn btn-primary btn-sm rounded pull-right">
                            Add new
                        </a> </div>
                                    </div>
                                    <div class="card-block">
                                        <div class="tasks-block">
                                            <ul class="item-list">
                                                <li class="item">
                                                    <div class="item-row">
                                                        <div class="item-col item-col-title"> <label>
                                                <input class="checkbox" type="checkbox"
                                                checked="checked"> 
                                                <span>Meeting with embassador</span>
                                            </label> </div>
                                                        <div class="item-col fixed item-col-actions-dropdown">
                                                            <div class="item-actions-dropdown">
                                                                <a class="item-actions-toggle-btn"> <span class="inactive">
                                                        <i class="fa fa-cog"></i>
                                                    </span> <span class="active">
                                                    <i class="fa fa-chevron-circle-right"></i>
                                                    </span> </a>
                                                                <div class="item-actions-block">
                                                                    <ul class="item-actions-list">
                                                                        <li>
                                                                            <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal"> <i class="fa fa-trash-o "></i> </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="check" href="#"> <i class="fa fa-check"></i> </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="edit" href="item-editor.html"> <i class="fa fa-pencil"></i> </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="item-row">
                                                        <div class="item-col item-col-title"> <label>
                                                <input class="checkbox" type="checkbox"
                                                checked="checked"> 
                                                <span>Confession</span>
                                            </label> </div>
                                                        <div class="item-col fixed item-col-actions-dropdown">
                                                            <div class="item-actions-dropdown">
                                                                <a class="item-actions-toggle-btn"> <span class="inactive">
                                                        <i class="fa fa-cog"></i>
                                                    </span> <span class="active">
                                                    <i class="fa fa-chevron-circle-right"></i>
                                                    </span> </a>
                                                                <div class="item-actions-block">
                                                                    <ul class="item-actions-list">
                                                                        <li>
                                                                            <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal"> <i class="fa fa-trash-o "></i> </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="check" href="#"> <i class="fa fa-check"></i> </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="edit" href="item-editor.html"> <i class="fa fa-pencil"></i> </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="item-row">
                                                        <div class="item-col item-col-title"> <label>
                                                <input class="checkbox" type="checkbox"
                                                > 
                                                <span>Time to start building an ark</span>
                                            </label> </div>
                                                        <div class="item-col fixed item-col-actions-dropdown">
                                                            <div class="item-actions-dropdown">
                                                                <a class="item-actions-toggle-btn"> <span class="inactive">
                                                        <i class="fa fa-cog"></i>
                                                    </span> <span class="active">
                                                    <i class="fa fa-chevron-circle-right"></i>
                                                    </span> </a>
                                                                <div class="item-actions-block">
                                                                    <ul class="item-actions-list">
                                                                        <li>
                                                                            <a class="remove" href="#" data-toggle="modal" data-target="#confirm-modal"> <i class="fa fa-trash-o "></i> </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="check" href="#"> <i class="fa fa-check"></i> </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="edit" href="item-editor.html"> <i class="fa fa-pencil"></i> </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="item-row">
                                                        <div class="item-col item-col-title"> <label>
                                                <input class="checkbox" type="checkbox"
                                                > 
                                                <span>Beer time with dudes</span>
                                            </label> </div>
                                                        <div class="item-col fixed item-col-actions-dropdown">
                                                            <div class="item-actions-dropdown">
                                                                <a class="item-actions-toggle-btn"> <span class="inactive">
                                                        <i class="fa fa-cog"></i>
                                                    </span> <span class="active">
         
