<?php 
    /*
    Plugin Name: Channel EPG Highlights
    Plugin URI: 
    Description: This provides EPG highlights of all channels
    Author: IB
    Version: 1.0
    Author URI: ib
    */

add_shortcode('epg','epg_fn');

function epg_fn($atts){

	global $wpdb, $current_user;
	$user_id = $current_user->ID;

	$channel_atts = shortcode_atts( array(
		'display' => 'table',
	
	       ), $atts );

	$args = array( 'display' => $channel_atts['display']);
	$disp=$channel_atts['display'];


	$sql='SELECT b.programname as program,c.post_title as channel,b.program_logo_url as logo,b.genre,DAYNAME(CURDATE()) as today,a.time FROM epg_timing_info a inner join epg_info b on a.epg_id=b.id inner join wp_posts c on c.id=b.channel where (a.day=DAYOFWEEK(CURDATE()) or a.day=99);';

	$res=$wpdb->get_results($sql);

	//$upload_dir = wp_upload_dir();
	//echo $user_dirname = $upload_dir['basedir']."/".date('Y')."/".date('m');


	if($disp=='table'){

		echo '<style>
			.today_highlights {
    border: 3px solid #CCCCCC;
	height:auto;
    max-height: 400px;
    margin-bottom: 30px;
    overflow-x: hidden;
    position: relative;
    width: auto;
    padding: 10px 0px;
	display:inline-block;
}

@media (max-width:1130px){
.today_highlights {
    max-height: 350px;
}
}
@media (max-width:950px){
.today_highlights {
    max-height: 300px;
}
}



#epg_tab td{border:none;padding: 2px 10px;text-align:center}
#epg_tab tr{text-align: center;}
.prog_info{border-bottom: 1px solid #E7E7E7}
.img_time{border:none}

@media (max-width:480px){

#epg_tab td{
    display: inline-block;
}
.img_time{border-bottom: 1px solid #E7E7E7}
.prog_info{border:none}
}
			</style>

	';

		if(count($res)<1)
		{
			
		//echo "No Highlights Today";

		}
		else
		{

		echo '<style>#epg_tab img{max-width: 180px;max-height: 100px;} .clr_black{color:black;} .clr_lblack{color: rgba(35, 23, 23, 0.92);}</style>';
		echo '<div id="popular-widget-high" class="1 widget widget-border popular-widget">
<h2 style="text-align:left" class="widget-title maincolor2">TODAYS HIGHLIGHTS</h2>';
		echo '<div align="center" class="today_highlights">';
		echo '<table id="epg_tab" style="padding: 10px 4px !important;width:100%;border:none">';
		//echo '<caption>Highlights Today</caption>';
		echo '<tbody>';
			
		

		foreach($res as $r){

			$program=$r->program;
			$channel=$r->channel;
			$genre=$r->genre;
			$today=$r->today;
			$time=$r->time;
			$logo=$r->logo;
			
			
			//$times=explode(',',$time);
			//$img=site_url().'/qp1/customer_logo/'.$logo;

			date_default_timezone_set('Asia/Kolkata');

			$d=$time;
			$datetime = new DateTime($d);
			$ist=$datetime->format('D h:i A T') . "\n"."<br>"; //INDIA

			$gmt_time = new DateTimeZone('Europe/London');
			$datetime->setTimezone($gmt_time);
			$gmt=$datetime->format('D h:i A T') . "\n"."<br>"; //London


			//$est_time = new DateTimeZone('America/New_York');
			//$datetime->setTimezone($est_time);
			//$est=$datetime->format('D jS F d-m-y h:i A T') . "\n"."<br>"; //Newyork EST

			$cst_time = new DateTimeZone('America/Costa_Rica');
			$datetime->setTimezone($cst_time);
			$cst=$datetime->format('D h:i A T') . "\n"."<br>"; //America Central CST


			$aus_time = new DateTimeZone('Australia/Sydney');
			$datetime->setTimezone($aus_time);
			$aedt=$datetime->format('D h:i A T') . "\n"."<br>"; //Australia

			if(is_user_logged_in()){
			$tz=$wpdb->get_var("SELECT timezone from visitors_info where user_id=$user_id order by id desc limit 1");
			if($tz!=""){
			$user_time = new DateTimeZone($tz);
			$datetime->setTimezone($user_time);
			$user_time=$datetime->format('D h:i A ') ;}
			}
			

			/*echo '<tr style="border-bottom: 1px solid #E7E7E7;
    text-align: center;">';
			echo '<td colspan="2" align="center"><img src="'.$logo.'" width="150"></td><td>
			<span><b>'.$program.'</b></span> on <span><b>'.$channel.'</b></span><br><span>('.$genre.')</span><br></td>';
			echo '</tr>';*/


			echo '<tr class="img_time">
				<td><img src="'.$logo.'" width="150"></td>
				<td rowspan="2"><span class="clr_lblack">';
				echo "<p>".$ist."</p>";
				echo "<p>".$gmt."</p>";
				echo "<p>".$cst."</p>";
				echo "<p>".$aedt."</p>";
				if(is_user_logged_in() && $tz!=""){echo "<p style='color: #3F51B5;'><b>Your time: ".$user_time."</b></p>";}
			echo '</span> </td>
				
				</tr>
				<tr class="prog_info">
				<td><span><b class="clr_black">'.$program.'</b></span> on <span><b class="clr_black">'.$channel.'</b></span><br><span class="clr_black">('.$genre.')</span></td>
				<td><input type="hidden" value="move up" class="move up" /></td>				
				</tr>';
		}
	
		
		echo "</tbody>";
		echo "</table></div></div>";

		

		echo "<script>
			jQuery('#popular-widget-high').attr('align','center');
			
			if(screen.width<=480){
				jQuery(function(){
   					jQuery('#epg_tab input.move').click();
				});
			}
			

			$('#epg_tab input.move').click(function() {
							
    var row = $(this).closest('tr');
    if ($(this).hasClass('up'))
        row.prev().before(row);
    else
        row.next().after(row);});
			</script>";
	}

	}

	if($disp=='list'){

		echo '<style>.headline-content {display:block !important;} .headline span{color:white} div#text-13 {    display: none;}</style>';
		
		if(count($res)<1)
		{
			
		//echo "No Highlights Today";

		}
		else
		{


	//style1
	/*
		echo '<style>#ticker {
    width:100%; height:20px; overflow:hidden; 
}
#ticker dt {
    font:normal 14px Georgia; padding:0 10px 5px 10px;
    background-color:black; padding-top:10px; 
    border-bottom:none; border-right:none;color:white;
}
#ticker dd {
    margin-left:0; font:normal 11px Verdana; padding:0 10px 10px 10px;
    border-bottom:1px solid #aaaaaa; background-color:black;
    
}
#ticker dd.last { border-bottom:1px solid #ffffff;
}</style>

';


	
		echo '<div class="headline" id="head284791804" style="display:block !important">
				<div class="row-fluid">
					<div class="htitle">
					<span class="hicon"><i class="fa fa-bullhorn"></i><span class="first-tex">Todays Highlights</span></span>';

		echo '    <div id="tickerContainer" >

      <dl id="ticker">';
		foreach($res as $r)
					{
					
						$program=$r->program;
						$channel=$r->channel;
						$genre=$r->genre;
						$today=$r->today;
						$time=$r->time;
						$logo=$r->logo;
						$img=site_url().'qp/customer_logo/'.$logo;
						echo '<dt class="heading">';
						echo	"<span>".$program." </span> | <span>". $channel."</span> | <span>".$time."</span>";
						echo '</dt>';
					}

		echo '    
              </dl>
    </div>';

echo '</div>
				</div>
			</div>';

		echo '<script>$(function() {


	//if(screen.width>480)
	//{

    //cache the ticker
    var ticker = $("#ticker");

    //wrap dt:dd pairs in divs
    ticker.children().filter("dt").each(function() {

        var dt = $(this),
            container = $("<div>");

        dt.next().appendTo(container);
        dt.prependTo(container);

        container.appendTo(ticker);
    });

    //hide the scrollbar
    ticker.css("overflow", "hidden");

    //animator function


    function animator(currentItem) {

        //work out new anim duration
        var distance = currentItem.height();
        duration = (distance + parseInt(currentItem.css("marginTop"))) / 0.025;

        //animate the first child of the ticker
        currentItem.animate({
            marginTop: -distance
        }, duration, "linear", function() {

            //move current item to the bottom
            currentItem.appendTo(currentItem.parent()).css("marginTop", 0);

            //recurse
            animator(currentItem.parent().children(":first"));
        });
    };

    //start the ticker
    animator(ticker.children(":first"));

    //set mouseenter
    ticker.mouseenter(function() {

        //stop current animation
        ticker.children().stop();

    });

    //set mouseleave
    ticker.mouseleave(function() {

        //resume animation
        animator(ticker.children(":first"));

    });

	//}
});</script>';
*/
//end_style1

//style2
		
		echo '<div class="headline" id="head284791804">
				<div class="row-fluid">
					<div class="htitle">
					<span class="hicon"><i class="fa fa-bullhorn"></i><span class="first-tex">Todays Highlights</span></span>
					
					<div id="tm-284791804" class="scroll-text">
						<ul>';

					foreach($res as $r)
					{
					
						$program=$r->program;
						$channel=$r->channel;
						$genre=$r->genre;
						$today=$r->today;
						$time=$r->time;
						$logo=$r->logo;
						$img=site_url().'qp/customer_logo/'.$logo;
						echo "<li>";
						echo	"<span>".$program." </span> | <span>". $channel."</span> | <span>".$time."</span>";
						echo "</li>";
					}

						echo '</ul>
						</div>
					</div>
				</div>
			</div>';

		

		echo '<script>
			jQuery(document).ready(function(e) {
				jQuery(function () {
				  jQuery("#tm-284791804").scrollbox({
					
					 linear: true,
					  step: 1,
					  delay: 0,
					  speed: 75,
					 infiniteLoop: true
				  });
				});
			});
		</script>';

//end_style2

	  }
	}

	if($disp=='promo_scroll'){
		echo '<style>.headline span{color:white} div#text-13 {    display: none;}</style>';
		echo '<div class="headline" id="head284791803">
				<div class="row-fluid">
					<div class="htitle">
					<span class="hicon"></span>
					<div id="tm-284791803" class="scroll-text">
						<ul>';

					
						echo "<li> <span>Promo Codes availbale at Charminar</span> </li>";
						echo "<li> <span>Promo Codes availbale at Gachibowli</span> </li>";
						echo "<li> <span>Promo Codes availbale at Golconda</span> </li>";
					
						echo '</ul>
						</div>
					</div>
				</div>
			</div>';

		

		echo '<script>
			jQuery(document).ready(function(e) {
				jQuery(function () {
				  $("#tm-284791803").scrollbox({
					speed: 50
				  });
				});
			});
		</script>';

	}


}
?>

<?php	

