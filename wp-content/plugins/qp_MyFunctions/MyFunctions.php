<?php 
    /*
    Plugin Name: My Functions.php
    Plugin URI: 
    Description: This is helps to add filters, hooks without going into theme's functions.php file
    Author: IB
    Version: 1.0
    Author URI: ib
    */



//add_action('check_admin_referer', 'logout_without_confirm', 10, 2);
/*function logout_without_confirm($action, $result)
{
    
   //Allow logout without confirmation
     
    if ($action == "log-out" && !isset($_GET['_wpnonce'])) {
        $redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : '';
        $location = str_replace('&amp;', '&', wp_logout_url($redirect_to));
        header("Location: $location");
        die;
    }
}*/

add_filter( 'logout_url', 'my_logout_page', 10, 2 );
function my_logout_page( $logout_url, $redirect ) {

$args = array( 'action' => 'logout' );
    if ( !empty($redirect) ) {
        $args['redirect_to'] = urlencode( $redirect );
    }
 
    $logout_url = add_query_arg($args, site_url('wp-login.php', 'login'));
    $logout_url = wp_nonce_url( $logout_url, 'log-out' );

    //return home_url( '/login/?action=logout&redirect_to=' . $redirect );
$logout_url=str_replace('&amp;', '&', $logout_url);
return $logout_url;
}

//add_action( 'wpmem_register_redirect', 'my_redirect' );




function my_redirect() 
{
    
// NOTE: this is an action hook that uses wp_redirect.
get_header();
echo "<style>body{background: rgba(0, 0, 0, 0.54) !important;}</style>";
echo "<script>swal({   title: 'Registration Success',   text: 'Please check your mail for password',   type: 'warning',   showCancelButton: false,   confirmButtonColor: '#DD6B55',   confirmButtonText: 'Ok',   cancelButtonText: 'Go HOME',   closeOnConfirm: false,   closeOnCancel: false }, function(isConfirm){   if (isConfirm) {window.location='".home_url()."/';   } else {   window.location='".home_url()."';     } });
</script>";

get_footer();
 
    	//wp_redirect(home_url());
    exit();
	}

add_shortcode('rel_chan','rel_channels');
function rel_channels()
{
$orig_post = $post;
global $post;
$categories = get_the_category($post->ID);
if ($categories) {
$category_ids = array();
foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
$args=array(
'category__in' => $category_ids,
'post__not_in' => array($post->ID),
'posts_per_page'=> 12, // Number of related posts that will be displayed.
'caller_get_posts'=>1,
'orderby'=>'rand' // Randomize the posts
);
$my_query = new wp_query( $args );
if( $my_query->have_posts() ) {
echo '<div id="related_main" class="clear"><h2 class="widget-title maincolor2">RELATED CHANNELS</h2><div class="regular slider">';
while( $my_query->have_posts() ) {
$my_query->the_post(); ?>
<div>
<?php 
$width="300px";
$height="169px";
$crop="true";
set_post_thumbnail_size( $width, $height, $crop ); ?> 
<a class="rel-chan" href="<?php the_permalink(); ?>"> <?php echo the_post_thumbnail(); ?></a>
 </a>
</div>
<? }
echo '</div></div>';
} 
}
$post = $orig_post;
wp_reset_query(); 
}

//add_action('sub_sup','sub_sup_fn');

function sub_sup_fn()
{
ob_start();
//echo "<script>alert('begin')</script>";

global $current_user;
global $wpdb;

get_currentuserinfo();
$user_id = get_current_user_id();

$last_updatedate=$wpdb->get_var("select last_update_date from sub_support where user_id=".$user_id." order by id desc");
$last_updatedate1=new DateTime($last_updatedate);
$today=new DateTime("now");
$today=$today->format("Y-m-d");
$today=new DateTime($today);

$update=$today>$last_updatedate1;

if(!$last_updatedate)
{
$update=true;
}

$startdate=$wpdb->get_var("select startdate from wp_pmpro_memberships_users where user_id=".$user_id." and status='active'");
$support=$wpdb->get_var("select start_date from sub_support where user_id=".$user_id." order by id desc limit 1 ");
if($startdate!=$support)
{
$update=true;
}
$plan=$wpdb->get_var("select membership_id from wp_pmpro_memberships_users where user_id=".$user_id." and status='active'");
$exp=$wpdb->get_var("SELECT expiration_number FROM wp_pmpro_membership_levels where id=".$plan."");
$end=$wpdb->get_var("select enddate from wp_pmpro_memberships_users where user_id=".$user_id." and status='active'");

if($exp>0)
{
$update=false;
$wpdb->replace("sub_support", array(
   
   "user_id" => $user_id,
   "start_date" => $startdate, 
   "plan_start_date" => $startdate,
   "next_pay_date" => $end,
   "last_update_date"=>$last_updatedate)); 
}
echo "<script>console.log('exp'+'".$exp."')</script>";
echo "<script>console.log('end:'+'".$end."')</script>";

echo "<script>console.log('last_upd:'+'".$last_updatedate."')</script>";
echo "<script>console.log('upd:'+'".$update."')</script>";
if($update)
{
				//update trial left daily
				$today=new DateTime("now");
				$curr_plan_enddate=$wpdb->get_var("select next_pay_date from sub_support where user_id=".$user_id." order by id desc limit 1 ");
				$curr_plan_enddate=new DateTime($curr_plan_enddate);
				$temp = date_diff($today,$curr_plan_enddate);
				$delay=$temp->format('%R%a');

				if($delay < 0)
 					{$delay=0;}
				else
				{$delay=$temp->format('%a');}

					echo "<script>console.log('del:'+'".$delay."')</script>";
    				//$subscription_delay=floor($delay/(60*60*24));
					
					$subscription_delay=$delay;
				//echo "<script>('sub_delay'+'".$subscription_delay."')</script>";
				update_option("pmpro_sub_support_delay_" . $user_id, $subscription_delay); //save new delay for this user on change of plan
					$chk=get_option('pmpro_sub_support_delay_' . $user_id);
				echo "<script>console.log('upd_del:'+'".$chk."')</script>";
				//end

		$startdate=$wpdb->get_var("select startdate from wp_pmpro_memberships_users where user_id=".$user_id." and status='active'");
		$startdate1=$startdate;
		$plan=$wpdb->get_var("select membership_id from wp_pmpro_memberships_users where user_id=".$user_id." and status='active'");


		if($startdate!="")
		{

			$trail=$wpdb->get_var("select count(user_id) from wp_pmpro_memberships_users where user_id=".$user_id." ");

			$date=new DateTime($startdate); 
			if($trail>1) //no-trial
				{
					$plan_startdate=$startdate;
			
					$delay=get_option("pmpro_sub_support_delay_".$user_id);
					if($delay!=0)
							{
						$plan_startdate=$date->add(new DateInterval('P'.$delay.'D'));
						$plan_startdate=$plan_startdate->format('Y-m-d');
						}
				}
			else
				{		
				
					$traildays=get_option("pmpro_subscription_delay_" . $plan);
			
					$plan_startdate=$date->add(new DateInterval('P'.$traildays.'D'));
					$plan_startdate=$plan_startdate->format('Y-m-d');

				}

			if($trail>1) //no-trail
				{
					//echo "<script>alert('no-trail')</script>";

					$date=new DateTime($startdate); 
					$now=new DateTime("now");

					if($plan==1)
						{
						$next_paydate=$date->add(new DateInterval('P1Y'));
						if($now>$next_paydate)
							{
							$next_paydate=$next_paydate->add(new DateInterval('P1Y'));
							}
						}
					else if($plan==2)
						{
						$next_paydate=$date->add(new DateInterval('P6M'));
						if($now>$next_paydate)
							{
							$next_paydate=$next_paydate->add(new DateInterval('P6M'));
							}
						}
					else if($plan==3)
						{
						$next_paydate=$date->add(new DateInterval('P3M'));
						if($now>$next_paydate)
							{
							$next_paydate=$next_paydate->add(new DateInterval('P3M'));
							}
						}
					else if($plan==4)
						{
						$next_paydate=$date->add(new DateInterval('P3D'));
						if($now>$next_paydate)
							{
							$next_paydate=$next_paydate->add(new DateInterval('P3D'));
							}
						}
					else if($plan==5)
						{
						$next_paydate=$date->add(new DateInterval('P2D'));
						if($now>$next_paydate)
							{
							$next_paydate=$next_paydate->add(new DateInterval('P2D'));
							}
						}
					else
						{
						}
		
					//additional check for carried sub delay
					$delay=get_option("pmpro_sub_support_delay_".$user_id);
					if($delay!=0)
						{
						//echo "<script>alert('but prev delay')</script>";
						$date2=new DateTime($plan_startdate);
						$now=new DateTime("now");
								if($plan==1)
								{
								$next_paydate=$date2->add(new DateInterval('P1Y'));
								if($now>$next_paydate)
								{
								$next_paydate=$next_paydate->add(new DateInterval('P1Y'));
								}
							}
							else if($plan==2)
								{
								$next_paydate=$date2->add(new DateInterval('P6M'));
								if($now>$next_paydate)
								{
								$next_paydate=$next_paydate->add(new DateInterval('P6M'));
								}
							}
						else if($plan==3)
							{
							//echo "<script>alert('".$plan."')</script>";
							//echo "<script>alert('".$date2->format('Y-m-d')."')</script>";
							$next_paydate=$date2->add(new DateInterval('P3M'));
							//echo "<script>alert('".$next_paydate->format('Y-m-d')."')</script>";
							if($now>$next_paydate)
							{
							$next_paydate=$next_paydate->add(new DateInterval('P3M'));
							}
							//echo "<script>alert('".$next_paydate->format('Y-m-d')."')</script>";
							}
						else if($plan==4)
							{
							$next_paydate=$date2->add(new DateInterval('P3D'));
							if($now>$next_paydate)
							{
							$next_paydate=$next_paydate->add(new DateInterval('P3D'));
							}
							}
						else if($plan==5)
						{
						$next_paydate=$date2->add(new DateInterval('P2D'));
						if($now>$next_paydate)
							{
							$next_paydate=$next_paydate->add(new DateInterval('P2D'));
							}
						}
						else
						{
						}
			
					}

				$next_paydate=$next_paydate->format('Y-m-d');
				}
			//initial trial period
			else
				{
			$next_paydate=new DateTime($plan_startdate);
			$now=new DateTime("now");
			if($now>$next_paydate)
			{
				if($plan==1)
				{
				$next_paydate=$date->add(new DateInterval('P1Y'));
					if($now>$next_paydate)
					{
					$next_paydate=$next_paydate->add(new DateInterval('P1Y'));
					}
				}
				else if($plan==2)
				{
				$next_paydate=$date->add(new DateInterval('P6M'));
					if($now>$next_paydate)
					{
					$next_paydate=$next_paydate->add(new DateInterval('P6M'));
					}
				}else if($plan==3)
				{
				$next_paydate=$date->add(new DateInterval('P3M'));
					if($now>$next_paydate)
					{
					$next_paydate=$next_paydate->add(new DateInterval('P3M'));
					}
				}
				else if($plan==4)
				{
				$next_paydate=$date->add(new DateInterval('P3D'));
					if($now>$next_paydate)
					{
					$next_paydate=$next_paydate->add(new DateInterval('P3D'));
					}
				}
				else if($plan==5)
				{
				$next_paydate=$date->add(new DateInterval('P2D'));
					if($now>$next_paydate)
					{
					$next_paydate=$next_paydate->add(new DateInterval('P2D'));
					}
				}
				else
				{
				}
			}
		$next_paydate=$next_paydate->format('Y-m-d');
		}



	$last_updatedate=new DateTime("now");
	$last_updatedate=$last_updatedate->format("Y-m-d");
	



//echo "<script>alert('start')</script>";
echo "<script>console.log('user:'+".$user_id.")</script>";
echo "<script>console.log('plan:'+".$plan.")</script>";
echo "<script>console.log('trail:'+".$trail.")</script>";
echo "<script>console.log('traildays:'+'".$traildays."')</script>";
echo "<script>console.log('delay:'+".$delay.")</script>";
echo "<script>console.log('startdate:'+'".$startdate."');</script>";
echo "<script>console.log('plan_startdate:'+'".$plan_startdate."');</script>";
echo "<script>console.log('next_paydate:'+'".$next_paydate."');</script>";
echo "<script>console.log('last_updatedate:'+'".$last_updatedate."');</script>";
//echo "<script>alert('end')</script>";


 $wpdb->replace("sub_support", array(
   
   "user_id" => $user_id,
   "start_date" => $startdate, 
   "plan_start_date" => $plan_startdate,
   "next_pay_date" => $next_paydate,
   "last_update_date"=>$last_updatedate)); 

 $wpdb->update("sub_support", array(
   "next_pay_date" => $next_paydate,
   "last_update_date"=>$last_updatedate),""); 

//$wpdb->query( $wpdb->prepare("UPDATE sub_support SET next_pay_date = %s and last_update_date = %s ", $next_paydate, $last_updatedate ) );


}//insertion depends on active plan

}//update time

}//end of fn

add_action('update_delay','update_pmpro_subsupt_delay');
function update_pmpro_subsupt_delay()
{

global $wpdb, $current_user;
$user_id=$current_user->ID;
//update trial left daily
				$today=new DateTime("now");
				$today=$today->format("Y-m-d");
				echo "<script>console.log('today'+'".$today."')</script>";
				$today=new DateTime($today);

				$curr_plan_startdate=$wpdb->get_var("select plan_start_date from sub_support where user_id=".$user_id." order by id desc limit 1 ");
				if($curr_plan_startdate!="")
				{
				$curr_plan_startdate=new DateTime($curr_plan_startdate);
				$curr_plan_startdate=$curr_plan_startdate->format("Y-m-d");
				echo "<script>console.log('curr_plan_startdate'+'".$curr_plan_startdate."')</script>";
				$curr_plan_startdate=new DateTime($curr_plan_startdate);
				}
		
				$curr_plan_enddate=$wpdb->get_var("select next_pay_date from sub_support where user_id=".$user_id." order by id desc limit 1 ");			
				if($curr_plan_enddate!="")
				{
				$curr_plan_enddate=new DateTime($curr_plan_enddate);
				$curr_plan_enddate=$curr_plan_enddate->format("Y-m-d");
				echo "<script>console.log('curr_plan_enddate'+'".$curr_plan_enddate."')</script>";
				$curr_plan_enddate=new DateTime($curr_plan_enddate);

				}
				
				
			if($curr_plan_startdate!="" && $curr_plan_enddate != "")
				{
					if($today > $curr_plan_startdate)
						{
							$temp = date_diff($today,$curr_plan_enddate);
				
						}
					else
						{
							$temp = date_diff($today,$curr_plan_startdate);
						}
					$delay=$temp->format('%R%a');

					echo "<script>console.log('delay'+'".$delay."')</script>";

					if($delay < 0)
 						{$delay=0;}
					else
					{$delay=$temp->format('%a');}
				}
			
				echo "<script>console.log('delay-converted'+'".$delay."')</script>";

					echo "<script>console.log('del:'+'".$delay."')</script>";
    				//$subscription_delay=floor($delay/(60*60*24));
					
					$subscription_delay=$delay;
				//echo "<script>('sub_delay'+'".$subscription_delay."')</script>";
				update_option("pmpro_sub_support_delay_" . $user_id, $subscription_delay); //save new delay for this user on change of plan
					$chk=get_option('pmpro_sub_support_delay_' . $user_id);
				echo "<script>console.log('upd_del:'+'".$chk."')</script>";
				//end

}

add_action('email_dates','dates_calc_email');

function dates_calc_email()
{
ob_start();
//echo "<script>alert('begin')</script>";

global $current_user;
global $wpdb;
get_currentuserinfo();
$user_id = get_current_user_id();


$today=new DateTime("now");
$today=$today->format("Y-m-d");
echo "<script>console.log('today:'+'".$today."')</script>";
$today=new DateTime($today);

$startdate=$wpdb->get_var("select startdate from wp_pmpro_memberships_users where user_id=".$user_id." and status='active'");
$support=$wpdb->get_var("select start_date from sub_support where user_id=".$user_id." order by id desc limit 1 ");
if($startdate!=$support)
{
$new_plan=true;
}

echo "<script>console.log('startdate:'+'".$startdate."')</script>";
echo "<script>console.log('Support_startdate:'+'".$support."')</script>";
echo "<script>console.log('new_plan:'+'".$new_plan."')</script>";


				
if($new_plan)
{
		
		$plan=$wpdb->get_var("select membership_id from wp_pmpro_memberships_users where user_id=".$user_id." and status='active'");

		if($startdate!="")
		{

			$trail=$wpdb->get_var("select count(user_id) from wp_pmpro_memberships_users where user_id=".$user_id." ");

			$date=new DateTime($startdate); 
			
		 
			if($trail>1) //no-trial
				{	echo "<script>console.log('no-trail')</script>";
					$plan_startdate=$startdate;
			
					$delay=get_option("pmpro_sub_support_delay_".$user_id);
					if($delay!=0)
							{echo "<script>console.log('but carried delay')</script>";
						$plan_startdate=$date->add(new DateInterval('P'.$delay.'D'));
						$plan_startdate=$plan_startdate->format('Y-m-d');
						}
				}
			else
				{		
					echo "<script>console.log('initial trail')</script>";
					$traildays=get_option("pmpro_subscription_delay_" . $plan);
					//$trail_days=$wpdb->get_var("SELECT option_value FROM wp_options where option_name='pmpro_subscription_delay_".$plan."' ");
					echo "<script>console.log('pmpro_subscription_delay_'+'".$plan."')</script>";
					echo "<script>console.log('traildays'+'".$traildays."')</script>";
					echo "<script>console.log('trail__days'+'".$trail_days."')</script>";
					$plan_startdate=$date->add(new DateInterval('P'.$traildays.'D'));
					$plan_startdate=$plan_startdate->format('Y-m-d');

				}
		
			if($trail>1) //no-trail
				{
					//echo "<script>alert('no-trail')</script>";

					$date=new DateTime($startdate); 
					$now=new DateTime("now");

					if($plan==1)
						{
						$next_paydate=$date->add(new DateInterval('P1Y'));
						if($now>$next_paydate)
							{
							$next_paydate=$next_paydate->add(new DateInterval('P1Y'));
							}
						}
					else if($plan==2)
						{
						$next_paydate=$date->add(new DateInterval('P6M'));
						if($now>$next_paydate)
							{
							$next_paydate=$next_paydate->add(new DateInterval('P6M'));
							}
						}
					else if($plan==3)
						{
						$next_paydate=$date->add(new DateInterval('P3M'));
						if($now>$next_paydate)
							{
							$next_paydate=$next_paydate->add(new DateInterval('P3M'));
							}
						}
					else if($plan==4)
						{
						$next_paydate=$date->add(new DateInterval('P3D'));
						if($now>$next_paydate)
							{
							$next_paydate=$next_paydate->add(new DateInterval('P3D'));
							}
						}
					else if($plan==5)
						{
						$next_paydate=$date->add(new DateInterval('P2D'));
						if($now>$next_paydate)
							{
							$next_paydate=$next_paydate->add(new DateInterval('P2D'));
							}
						}
					else
						{
						}
		
					//additional check for carried sub delay
					$delay=get_option("pmpro_sub_support_delay_".$user_id);
					if($delay!=0)
						{
						//echo "<script>alert('but prev delay')</script>";
						$date2=new DateTime($plan_startdate);
						$now=new DateTime("now");
								if($plan==1)
								{
								$next_paydate=$date2->add(new DateInterval('P1Y'));
								if($now>$next_paydate)
								{
								$next_paydate=$next_paydate->add(new DateInterval('P1Y'));
								}
							}
							else if($plan==2)
								{
								$next_paydate=$date2->add(new DateInterval('P6M'));
								if($now>$next_paydate)
								{
								$next_paydate=$next_paydate->add(new DateInterval('P6M'));
								}
							}
						else if($plan==3)
							{
							//echo "<script>alert('".$plan."')</script>";
							//echo "<script>alert('".$date2->format('Y-m-d')."')</script>";
							$next_paydate=$date2->add(new DateInterval('P3M'));
							//echo "<script>alert('".$next_paydate->format('Y-m-d')."')</script>";
							if($now>$next_paydate)
							{
							$next_paydate=$next_paydate->add(new DateInterval('P3M'));
							}
							//echo "<script>alert('".$next_paydate->format('Y-m-d')."')</script>";
							}
						else if($plan==4)
							{
							$next_paydate=$date2->add(new DateInterval('P3D'));
							if($now>$next_paydate)
							{
							$next_paydate=$next_paydate->add(new DateInterval('P3D'));
							}
							}
						else if($plan==5)
						{
						$next_paydate=$date2->add(new DateInterval('P2D'));
						if($now>$next_paydate)
							{
							$next_paydate=$next_paydate->add(new DateInterval('P2D'));
							}
						}
						else
						{
						}
			
					}

				$next_paydate=$next_paydate->format('Y-m-d');
				}
			//initial trial period
			else
				{
			$next_paydate=new DateTime($plan_startdate);
			$now=new DateTime("now");
			if($now>$next_paydate)
			{
				if($plan==1)
				{
				$next_paydate=$date->add(new DateInterval('P1Y'));
					if($now>$next_paydate)
					{
					$next_paydate=$next_paydate->add(new DateInterval('P1Y'));
					}
				}
				else if($plan==2)
				{
				$next_paydate=$date->add(new DateInterval('P6M'));
					if($now>$next_paydate)
					{
					$next_paydate=$next_paydate->add(new DateInterval('P6M'));
					}
				}else if($plan==3)
				{
				$next_paydate=$date->add(new DateInterval('P3M'));
					if($now>$next_paydate)
					{
					$next_paydate=$next_paydate->add(new DateInterval('P3M'));
					}
				}
				else if($plan==4)
				{
				$next_paydate=$date->add(new DateInterval('P3D'));
					if($now>$next_paydate)
					{
					$next_paydate=$next_paydate->add(new DateInterval('P3D'));
					}
				}
				else if($plan==5)
				{
				$next_paydate=$date->add(new DateInterval('P2D'));
					if($now>$next_paydate)
					{
					$next_paydate=$next_paydate->add(new DateInterval('P2D'));
					}
				}
				else
				{
				}
			}
		$next_paydate=$next_paydate->format('Y-m-d');
		}



	$last_updatedate=new DateTime("now");
	$last_updatedate=$last_updatedate->format("Y-m-d");
	



//echo "<script>alert('start')</script>";
echo "<script>console.log('user:'+".$user_id.")</script>";
echo "<script>console.log('plan:'+".$plan.")</script>";
echo "<script>console.log('trail:'+".$trail.")</script>";
echo "<script>console.log('traildays:'+'".$traildays."')</script>";
echo "<script>console.log('delay:'+".$delay.")</script>";
echo "<script>console.log('startdate:'+'".$startdate."');</script>";
echo "<script>console.log('plan_startdate:'+'".$plan_startdate."');</script>";
echo "<script>console.log('next_paydate:'+'".$next_paydate."');</script>";
echo "<script>console.log('last_updatedate:'+'".$last_updatedate."');</script>";
//echo "<script>alert('end')</script>";

}//calculation depends on active plan

}//new plan only

$result=array($plan_startdate, $next_paydate, $traildays, $delay);
return $result;
}//end of function


add_action('update_daily','upd_daily_chk');

/* 
New User Record insertion sub_support table

check passed for new user: userCount>=1 from mem_users table
userCount=$wpdb->get_var("select count(user_id) from wp_pmpro_memberships_users where user_id=".$user_id." ");


args: startdate from mem_users, trail, plan_duration,curr plan, cycle_number,cycle_period


*/
function upd_daily_chk()
{
echo "<script>console.log('Daily Update check')</script>";
global $current_user;
global $wpdb;

get_currentuserinfo();
$user_id = get_current_user_id();

$last_updatedate=$wpdb->get_var("select record_updateddate from pmpro_dates_chk1 where user_id=".$user_id." order by id desc");
echo "<script>console.log('last upd:'+'".$last_updatedate."')</script>";
$last_updatedate1=new DateTime($last_updatedate);
$today=new DateTime("now");
$today=$today->format("Y-m-d");
echo "<script>console.log('today:'+'".$today."')</script>";
$today=new DateTime($today);

$update=$today>$last_updatedate1;
echo "<script>console.log('upd chckkkk:'+'".$update."')</script>";

	if(!$last_updatedate)
	{
	$update=true;
	}

$plan=$wpdb->get_var("select plan_id from pmpro_dates_chk1 where user_id=".$user_id." order by id desc"); 
$user=$wpdb->get_var("select user_id from pmpro_dates_chk1 where user_id=".$user_id." order by id desc"); 
if($plan=="" and $user=="")
	{$update=false;
//echo "<script>console.log('upd:".$update."')</script>";
}


	if($update)
	{
	echo "<script>console.log('Update In')</script>";
	//$plan=$wpdb->get_var("select membership_id from wp_pmpro_memberships_users where user_id=".$user_id." and status='active' "); 
	//$plan_period_no=$wpdb->get_var("select cycle_number from wp_pmpro_memberships_users where user_id=".$user_id." and status='active' ");
	//$plan_period=$wpdb->get_var("select cycle_period from wp_pmpro_memberships_users where user_id=".$user_id." and status='active' ");
	//echo "<script>console.log('plan details'+'".$plan.$plan_period_no.$plan_period."')</script>";

	//dbl_chk
	$plan=$wpdb->get_var("select plan_id from pmpro_dates_chk1 where user_id=".$user_id." order by id desc"); 
	$plan_period_no=$wpdb->get_var("select cycle_number from wp_pmpro_membership_levels where id=".$plan." ");
	$plan_period=$wpdb->get_var("select cycle_period from wp_pmpro_membership_levels where id=".$plan." ");
	echo "<script>console.log('plan details'+'".$plan.$plan_period_no.$plan_period."')</script>";

	$agent=$wpdb->get_var("select agent from pmpro_dates_chk1 where user_id=".$user_id." and plan_id=".$plan." order by id desc");

	if($plan_period_no=="")
	{
	echo "<script>console.log('free-plan: no change in next_paydate')</script>";
	}

	$next_paydate=$wpdb->get_var("select next_paydate from pmpro_dates_chk1 where user_id=".$user_id." order by id desc");
	$enddate_chk=$wpdb->get_var("select plan_enddate from pmpro_dates_chk1 where user_id=".$user_id." order by id desc");

	if($next_paydate=="0000-00-00 00:00:00")
	{
	echo "<script>console.log('plan cancelled: no change in next_paydate')</script>";
	}
	else
	{
		if($agent=="no")
		{
			if(($plan_period_no != "") && ($plan_period != ""))
			{

			$next_paydate=new DateTime($next_paydate);
			while($today>=$next_paydate)
			{
			$next_paydate=$next_paydate->add(new DateInterval('P'.$plan_period_no.$plan_period[0]));
			}
			$next_paydate=$next_paydate->format("Y-m-d");
			echo "<script>console.log('next_paydate'+'".$next_paydate."')</script>";
			}

		}
		else
		{
		echo "<script>console.log('agent paid: no change in next_paydate')</script>";
		}
	}

	$this_id=$wpdb->get_var("select id from pmpro_dates_chk1 where user_id=".$user_id." order by id desc");
	$plan_startdate=$wpdb->get_var("select plan_startdate from pmpro_dates_chk1 where user_id=".$user_id." order by id desc");
	echo "<script>console.log('plan_startdate'+'".$plan_startdate."')</script>";
	
	if($agent=="no")
	{
		if( (new DateTime($plan_startdate)) > $today )
				{
					$delayeddate=$plan_startdate;
				}
		else
				{
					if($next_paydate=="0000-00-00 00:00:00")
					{$delayeddate=$enddate_chk;}
					else $delayeddate=$next_paydate;
				
				}
	}
	elseif($agent=="free")
	{
		if($next_paydate=="0000-00-00 00:00:00")
					{$delayeddate=$enddate_chk;}
					else $delayeddate=$next_paydate;
	}
	else
	{
		if($next_paydate=="0000-00-00 00:00:00")
					{$delayeddate=$enddate_chk;}
					else $delayeddate=$next_paydate;
	}
				
	echo "<script>console.log('delayeddate'+'".$delayeddate."')</script>";

	$delayeddate=new DateTime($delayeddate);
	$temp = date_diff($today,$delayeddate);
	$delayUpd=$temp->format('%R%a');

	if($delayUpd < 0)
		{$delayUpd=0;}
	else
		{$delayUpd=$temp->format('%a');}

	echo "<script>console.log('delay'+'".$delayUpd."')</script>";


	//db update

	$last_updatedate=new DateTime("now");
	$last_updatedate=$last_updatedate->format("Y-m-d");


	//$wpdb->prepare("update pmpro_dates_chk1 set next_paydate='".$next_paydate."',delay=".$delayUpd.",record_updateddate='".$last_updatedate."' where id=".$this_id." ");
	$wpdb->update("pmpro_dates_chk1", array(
   "next_paydate" => $next_paydate,
	"delay"=> $delayUpd,
   "record_updateddate"=>$last_updatedate),array("id"=>$this_id)); 

	echo "<script>console.log('last upd date'+'".$last_updatedate."')</script>";
	echo "<script>console.log('DB Updated')</script>";

	update_option("pmpro_sub_support_delay_" . $user_id, $delayUpd); //save new delay for this user on change of plan

	}


echo "<script>console.log('Update Completed')</script>";
echo "<script>console.log('next_paydate'+'".$next_paydate."')</script>";
echo "<script>console.log('plan duration passed'+'".$plan_duration."')</script>";

}

add_shortcode('after_paypal_cancel','paypal_cancel_fn');

function paypal_cancel_fn()
{

$x=$_GET['id'];
//echo "<script>alert('insertion_col_id from chk:'+'".$x."')</script>";

//added
			//echo "<script>alert('::::Note down the data from alerts:::::');</script>";
			$txt="";
			 global $wpdb;
//global $current_user;

$current_user=$x;
//echo "<script>alert('insertion_col_id from chk:'+'".$current_user."')</script>";

			//$planstartdate=$wpdb->get_var("select plan_start_date from sub_support where user_id=".$current_user." order by id desc limit 1 ");
			//$nextpaydate=$wpdb->get_var("select next_pay_date from sub_support where user_id=".$current_user." order by id desc limit 1 ");

$planstartdate=$wpdb->get_var("select plan_startdate from pmpro_dates_chk1 where user_id=".$current_user." order by id desc limit 1 ");
$nextpaydate=$wpdb->get_var("select next_paydate from pmpro_dates_chk1 where user_id=".$current_user." order by id desc limit 1 ");
				$today=new DateTime("now");
				$today=$today->format("Y-m-d");
				$today=new DateTime($today);
				$planstartdate=new DateTime($planstartdate);
			
				if($planstartdate >= $today )
				{
					$enddate=$planstartdate->format("Y-m-d");
				}
				else
				{
					$enddate=$nextpaydate;
				}

			$txt.=$enddate;
			
			$enddateUpd=$enddate;
			
			$enddateUpd=date('Y-m-d', strtotime($enddateUpd. ' -1 days'));
			
			$this_id=$wpdb->get_var("SELECT id FROM wp_pmpro_memberships_users where user_id=".$current_user." order by id desc limit 1");
			$txt.=$this_id;
			//$updated=$wpdb->update("wp_pmpro_memberships_users",array(   "status" => "active",   "enddate"=>$enddateUpd), array("id"=>$this_id)); 
			
			$updated=$wpdb->query($wpdb->prepare("UPDATE wp_pmpro_memberships_users SET status=%s, enddate=%s WHERE id=%d",'active',$enddateUpd,$this_id));

			//added for change of plan
				
				$today=new DateTime("now");
				$today=$today->format("Y-m-d");
				$today=new DateTime($today);

				$enddate=new DateTime($enddate);
				$enddate=$enddate->format("Y-m-d");
				$enddate=new DateTime($enddate);


				$temp = date_diff($today,$enddate);
				$delay=$temp->format('%R%a');

				if($delay < 0)
 					{$delay=0;}
				else
				{$delay=$temp->format('%a');}

				$subscription_delay=$delay;
				//echo "<script>alert('sub_delay'+'".$subscription_delay."')</script>";
				update_option("pmpro_sub_support_delay_" . $current_user->ID, $subscription_delay); //save new delay for this user on change of plan
					$chk=get_option('pmpro_sub_support_delay_' . $current_user);
				//echo "<script>alert('upd_del:'+'".$chk."')</script>";


$last_updatedate=new DateTime("now");
$last_updatedate=$last_updatedate->format("Y-m-d");
$next_paydate="0000-00-00 00:00:00";

$this_chk_id=$wpdb->get_var("select id from pmpro_dates_chk1 where user_id=".$current_user." order by id desc limit 1 ");
//echo "<script>alert('nxt pay:'+'".$next_paydate."')</script>";
//echo "<script>alert('insertion_col_id from chk:'+'".$current_user."')</script>";
//echo "<script>alert('insertion_col_id from chk:'+'".$this_chk_id."')</script>";

		

$wpdb->update("pmpro_dates_chk1", array(
   "next_paydate" => $next_paydate,
	"delay"=> $subscription_delay,
   "record_updateddate"=>$last_updatedate),array("id"=>$this_chk_id)); 


 						
			//ended	
}		
	


add_filter('pre_get_posts','SearchFilter'); //search only posts

function SearchFilter($query) {

	if ($query->is_search) 
		{
		$query->set('post_type', 'post');
		}
	
	return $query;
	}
	
add_action('subscribed','subscribed_or_not_fn');
function subscribed_or_not_fn()
{
global $current_user;
global $wpdb;

get_currentuserinfo();
$user_id = get_current_user_id();

$subAgent = $wpdb->get_var("SELECT plan_id FROM agent_vs_subscription_credit_info WHERE subscriber_id =  ".$user_id. " ORDER BY id DESC LIMIT 1");
$enddateAgent = $wpdb->get_var("SELECT subscription_end_on FROM agent_vs_subscription_credit_info WHERE subscriber_id =  ".$user_id. " ORDER BY id DESC LIMIT 1");
echo "<script>console.log('endDateAgent:".$enddateAgent."')</script>";
$dateNow = new DateTime("now");
$enddateAgent=new DateTime($enddateAgent);

	echo '<style>#player{display:block !important;}</style>';
	
//mem_id from wp pmpro users

$sub_status = $wpdb->get_var("SELECT status FROM wp_pmpro_memberships_users WHERE user_id =  ".$user_id. " order by id desc limit 1");

$validity=($enddateAgent >= $dateNow)?1:0;
//echo "<script>console.log('val1:".$validity1."')</script>";
echo "<script>console.log('val:".$validity."')</script>";
echo "<script>console.log('Direct Sub-status:".$sub_status."')</script>";

if( (int)$subAgent>0 and $validity==1)
{
echo "<script>console.log('Payment through Agent Portal')</script>";
}

//if(  ( (int)$sub>0 and $validity1==1) or ( (int)$subAgent>0 and $validity2==1)  or ($user_id==4) ) 
if (($sub_status=="active") or ( (int)$subAgent>0 and $validity==1)){

echo "<script>console.log('Subscribed.. Can watch')</script>";
update_option("subscribed_".$user_id,"true");
echo "<script>console.log('subscribed_".$user_id.":".get_option("subscribed_".$user_id,"")."')</script>";
}
else
{
//not subscribed
		update_option("subscribed_".$user_id,"false");
		//echo "<script>console.log('subscribed_".$user_id.":".get_option('subscribed'.$user_id)."')</script>";
		echo "<script>console.log('subscribed_".$user_id.":".get_option("subscribed_".$user_id,"")."')</script>";
}


}

add_action('channel_dropdown','channel_dd_fn');
function channel_dd_fn()
{
$defaults = array(
			'selected'              => FALSE,
			'pagination'            => FALSE,
			'posts_per_page'        => - 1,
			'post_status'           => 'publish',
			'cache_results'         => TRUE,
			'cache_post_meta_cache' => TRUE,
			'echo'                  => 1,
			'select_name'           => 'post_id',
			'id'                    => '',
			'class'                 => '',
			'show'                  => 'post_title',
			'show_callback'         => NULL,
			'show_option_all'       => NULL,
			'show_option_none'      => NULL,
			'option_none_value'     => '',
			'multi'                 => FALSE,
			'value_field'           => 'ID',
			'order'                 => 'ASC',
			'orderby'               => 'post_title',
		);
		$r = wp_parse_args( $args, $defaults );
		$posts  = get_posts( $r );
		$output = '';
		$show = $r['show'];
		if( ! empty($posts) ) {
			$name = esc_attr( $r['select_name'] );
			if( $r['multi'] && ! $r['id'] ) {
				$id = '';
			} else {
				$id = $r['id'] ? " id='" . esc_attr( $r['id'] ) . "'" : " id='$name'";
			}
			$output = "<select name='{$name}'{$id} class='" . esc_attr( $r['class'] ) . "'>\n";
			if( $r['show_option_all'] ) {
				$output .= "\t<option value='0'>{$r['show_option_all']}</option>\n";
			}
			if( $r['show_option_none'] ) {
				$_selected = selected( $r['show_option_none'], $r['selected'], FALSE );
				$output .= "\t<option value='" . esc_attr( $r['option_none_value'] ) . "'$_selected>{$r['show_option_none']}</option>\n";
			}
			foreach( (array) $posts as $post ) {
				$value   = ! isset($r['value_field']) || ! isset($post->{$r['value_field']}) ? $post->ID : $post->{$r['value_field']};
				$_selected = selected( $value, $r['selected'], FALSE );
				$display = ! empty($post->$show) ? $post->$show : sprintf( __( '#%d (no title)' ), $post->ID );
				if( $r['show_callback'] ) $display = call_user_func( $r['show_callback'], $display, $post->ID );
				$output .= "\t<option value='{$value}'{$_selected}>" . esc_html( $display ) . "</option>\n";
			}
			$output .= "</select>";
		}
echo $output;

}

add_shortcode('login_check','login_check_redirect');
function login_check_redirect(){

global $current_user, $wpdb;
	
	get_currentuserinfo();
	$user_id = get_current_user_id();

	if((int)$user_id<1)
	{
	header('Location:../');
	}


}

add_action('process_cc','process_cc_fn',10,1);
function process_cc_fn($cc)
{
	global $current_user, $wpdb;
	get_currentuserinfo();
	$user_id = get_current_user_id();

	$coupon_code=$cc;
	if($cc=="")
	return;
						$status="active";
						$d=new DateTime("now");
						$startdate=$d->format("Y-m-d H:i:s");
						$cpn_details=$wpdb->get_results("SELECT * from buy_a_friend where coupon_code='".$coupon_code."' and status='Paid'");				

						$promo_cpn_details=$wpdb->get_results("SELECT * from promo_codes where coupon_code='".$coupon_code."' and usage_count<max_count");	


						if($promo_cpn_details!="")
						{		
							foreach($promo_cpn_details as $cpn)
							{
								$plan_id=$cpn->plan_id;
							}
						}
						else
						{
							foreach($cpn_details as $cpn)
							{
								$plan_id=$cpn->plan_id;
							}
						}
						//$e=$d->add(new DateInterval('P7D'));


						$plan_details=$wpdb->get_results("SELECT * from wp_pmpro_membership_levels where id=".$plan_id." ");					

						foreach($plan_details as $plan)
						{
							//$plan_id=$plan->plan_id;
							$i_amt=$plan->initial_payment;
							$b_amt=$plan->billing_amount;
							$c_no=$plan->cycle_number;
							$c_prd=$plan->cycle_period;
							$b_limit=$plan->billing_limit;
							$t_amt=$plan->trial_amount;
							$t_limit=$plan->trial_limit;
							$exp_no=$plan->expiration_number;
							$exp_prd=$plan->expiration_period;
						}
						if($plan_id==4)
						{
						$e=$d->add(new DateInterval('P'.$exp_no.$exp_prd[0]));
							if($promo_cpn_details!="")
							{$e=$e->add(new DateInterval('P1W'));}//extra 1 week due to promo code 
												
						}
						else
						$e=$d->add(new DateInterval('P'.$c_no.$c_prd[0]));

						$enddate=$e->format("Y-m-d H:i:s");
						

						$wpdb->insert("wp_pmpro_memberships_users",array('user_id'=>$user_id,'membership_id'=>$plan_id,'code_id'=>$coupon_code,'initial_payment'=>$i_amt,'billing_amount'=>$b_amt,'cycle_number'=>$c_no, 'cycle_period'=>$c_prd,'billing_limit'=>$b_limit,'trial_amount'=>$t_amt,'trial_limit'=>$t_limit,'status'=>'active','startdate'=>$startdate,'enddate'=>$enddate));

if($promo_cpn_details!=""){

$count=$wpdb->get_var("SELECT usage_count from promo_codes where coupon_code='".$coupon_code."' ");
$wpdb->update("promo_codes", array("usage_count"=>$count+1), array("coupon_code"=>$coupon_code));			
update_option('promo_used_'.$user_id,'true');	
$c='promo';

}
else
{
$wpdb->update("buy_a_friend", array("status"=>"Coupon Used"), array("coupon_code"=>$coupon_code));
$c='coupon';
}
after_process_coupon($user_id,$plan_id,$startdate,$enddate,$c,$coupon_code);


//insert into pmpro_dates_chk1


}


add_action('process_cc_ext_sub','sub_ext_with_coupon',10,3);
function sub_ext_with_coupon($cpn,$curr_enddate,$param)
{

//$file=fopen("cpn_sub_ext.txt","a");
//$info="";
global $current_user, $wpdb;
	get_currentuserinfo();
	$user_id = get_current_user_id();
	//$info=$user_id=167;	//fwrite($file,$info);
	$info=$coupon_code=$cpn;
	//fwrite($file,$info);

	$today=new DateTime("now");
	$start=$today=$today->format("Y-m-d");

	if($cpn=="")
	return;
			//$info=$param;
			//fwrite($file,$info);
			$info=$d=$curr_enddate;
			//fwrite($file,$info);
			$cpn_details=$wpdb->get_results("SELECT * from buy_a_friend where coupon_code='".$coupon_code."' and status='Paid'");	

			$promo_cpn_details=$wpdb->get_results("SELECT * from promo_codes where coupon_code='".$coupon_code."' and usage_count<max_count");					

						if(count($cpn_details)>=1)
						{
							foreach($cpn_details as $cpn)
							{
								$plan_id=$cpn->plan_id;
							}
						}
						if(count($promo_cpn_details)>=1)
						{
							foreach($promo_cpn_details as $cpn)
							{
								$plan_id=$cpn->plan_id;
							}
						}
						//$e=$d->add(new DateInterval('P7D'));


						$plan_details=$wpdb->get_results("SELECT * from wp_pmpro_membership_levels where id=".$plan_id." ");					

						foreach($plan_details as $plan)
						{
							//$plan_id=$plan->plan_id;
							$plan_name=$plan->name;
							$i_amt=$plan->initial_payment;
							$b_amt=$plan->billing_amount;
							$c_no=$plan->cycle_number;
							$c_prd=$plan->cycle_period;
							$b_limit=$plan->billing_limit;
							$t_amt=$plan->trial_amount;
							$t_limit=$plan->trial_limit;
							$exp_no=$plan->expiration_number;
							$exp_prd=$plan->expiration_period;
						}

						$d=new DateTime($d);
						if($c_no==0){
							//$plan_id==4 free trial
						
							$e=$d->add(new DateInterval('P'.$exp_no.$exp_prd[0]));
							if(count($promo_cpn_details)>=1){
									$e=$e->add(new DateInterval('P1W')); //extra 1weeks as for promo_code
							}						
						}
						else
						{
						$e=$d->add(new DateInterval('P'.$c_no.$c_prd[0]));
						}
						$enddate=$e->format("Y-m-d H:i:s");

						$info=$ext_enddate=$e->format("Y-m-d H:i:s");
						$end=$e->format("Y-m-d");
						//fwrite($file,$info);

				$today1=new DateTime($today);
				$delayeddate=new DateTime($ext_enddate);
				$temp = date_diff($today1,$delayeddate);
				$delayUpd=$temp->format('%R%a');

				if($delayUpd < 0)
 					{$delayUpd=0;}
				else
				{$delayUpd=$temp->format('%a');}

				
		//check if agent / pmpro update it; also update dates_chk1

		if($param=="A")
		{

		//- update_old - insert_new

		//echo "<script>alert('".$param."')</script>";
		
		$a_id=$wpdb->get_var("SELECT id from agent_vs_subscription_credit_info where subscriber_id=".$user_id." and CURDATE()<date(subscription_end_on) order by id desc limit 1");	
		$agent_id=$wpdb->get_var("SELECT agent_id from agent_vs_subscription_credit_info where subscriber_id=".$user_id." and CURDATE()<date(subscription_end_on) order by id desc limit 1");

		$cpn="Agent:".$agent_id.",".$coupon_code;

		$a_dates_id=$wpdb->get_var("SELECT id from pmpro_dates_chk1 where user_id=".$user_id." order by id desc limit 1");
		$o_ag=$wpdb->get_var("SELECT agent from pmpro_dates_chk1 where user_id=".$user_id." order by id desc limit 1");
		$o_plan_id=$wpdb->get_var("SELECT plan_id from pmpro_dates_chk1 where user_id=".$user_id." order by id desc limit 1");
		$o_plan_name=$wpdb->get_var("SELECT plan_name from pmpro_dates_chk1 where user_id=".$user_id." order by id desc limit 1");
		$o_delay=$wpdb->get_var("SELECT delay from pmpro_dates_chk1 where user_id=".$user_id." order by id desc limit 1");

		$ag=$o_ag.", ".$coupon_code;

		//echo "<script>alert('".$ag."')</script>";

		$wpdb->update("agent_vs_subscription_credit_info",array("subscription_end_on"=>$today),array("id"=>$a_id)); //update_old
					
$wpdb->insert("wp_pmpro_memberships_users",array("user_id"=>$user_id,"membership_id"=>$plan_id,"code_id"=>$cpn,"initial_payment"=>$i_amt,"billing_amount"=>$b_amt,"cycle_number"=>$c_no,"cycle_period"=>$c_prd,"billing_limit"=>$b_limit,"trial_amount"=>$t_amt,"trial_limit"=>$t_limit,"status"=>"active","startdate"=>$today,"enddate"=>$ext_enddate)); //insert_new

				
		$wpdb->update("pmpro_dates_chk1",array("next_paydate"=>$today),array("id"=>$a_dates_id));//update-old

		//echo "<script>alert('updated')</script>";

$wpdb->insert("pmpro_dates_chk1",array("user_id"=>$user_id,"old_plan_id"=>$o_plan_id,"old_plan_name"=>$o_plan_name,"plan_id"=>$plan_id,"plan_name"=>$plan_name,"startdate"=>$today,"plan_startdate"=>$today,"next_paydate"=>$ext_enddate,"delay"=>$delayUpd,"agent"=>$ag,"record_updateddate"=>"0000-00-00"));//insert-new

		//echo "<script>alert('inserted?')</script>";

		if(count($promo_cpn_details)>=1){
							$count=$wpdb->get_var("SELECT usage_count from promo_codes where coupon_code='".$coupon_code."' ");
							$wpdb->update("promo_codes", array("usage_count"=>$count+1), array("coupon_code"=>$coupon_code));			

							update_option('promo_used_'.$user_id,'true');					
			}
						
						else{
							$wpdb->update("buy_a_friend", array("status"=>"Coupon Used"), array("coupon_code"=>$coupon_code));					}

		update_option("pmpro_sub_support_prev_delay_" . $user_id,$o_delay);
		update_option("pmpro_sub_support_delay_" . $user_id,$delayUpd);

		
		
		}elseif($param=="U")
		{

		//echo "<script>alert('".$param."')</script>";

		//get already existing cpn
		$u_id=$wpdb->get_var("SELECT id from wp_pmpro_memberships_users where user_id=".$user_id." and status='active' order by id desc limit 1");
		$u_cpn=$wpdb->get_var("SELECT code_id from wp_pmpro_memberships_users where user_id=".$user_id." and status='active' order by id desc limit 1");

		if($u_cpn=="")
			$cpn=$coupon_code;
		else
			$cpn=$u_cpn.", ".$coupon_code;
		
		$u_cpn=$u_cpn."*";

		$u_dates_id=$wpdb->get_var("SELECT id from pmpro_dates_chk1 where user_id=".$user_id." order by id desc limit 1");
		$o_plan_id=$wpdb->get_var("SELECT plan_id from pmpro_dates_chk1 where user_id=".$user_id." order by id desc limit 1");
		$o_plan_name=$wpdb->get_var("SELECT plan_name from pmpro_dates_chk1 where user_id=".$user_id." order by id desc limit 1");
		$o_ag=$wpdb->get_var("SELECT agent from pmpro_dates_chk1 where user_id=".$user_id." order by id desc limit 1");
		$o_delay=$wpdb->get_var("SELECT delay from pmpro_dates_chk1 where user_id=".$user_id." order by id desc limit 1");
			
		$ag=$o_ag.", ".$coupon_code;

				
		//update_old - insert_new

		//echo "<script>alert('".$ag."')</script>";
		$wpdb->update("wp_pmpro_memberships_users",array("code_id"=>$u_cpn,"status"=>"changed","enddate"=>$today),array("id"=>$u_id)); //update-old

$wpdb->insert("wp_pmpro_memberships_users",array("user_id"=>$user_id,"membership_id"=>$plan_id,"code_id"=>$cpn,"initial_payment"=>$i_amt,"billing_amount"=>$b_amt,"cycle_number"=>$c_no,"cycle_period"=>$c_prd,"billing_limit"=>$b_limit,"trial_amount"=>$t_amt,"trial_limit"=>$t_limit,"status"=>"active","startdate"=>$today,"enddate"=>$ext_enddate)); //insert_new

				
		$wpdb->update("pmpro_dates_chk1",array("next_paydate"=>$today),array("id"=>$u_dates_id));//update-old

		//echo "<script>alert('updated')</script>";

$wpdb->insert("pmpro_dates_chk1",array("user_id"=>$user_id,"old_plan_id"=>$o_plan_id,"old_plan_name"=>$o_plan_name,"plan_id"=>$plan_id,"plan_name"=>$plan_name,"startdate"=>$today,"plan_startdate"=>$today,"next_paydate"=>$ext_enddate,"delay"=>$delayUpd,"agent"=>$ag,"record_updateddate"=>"0000-00-00"));//insert-new

		//echo "<script>alert('inserted?')</script>";

		if(count($promo_cpn_details)>=1){
							$count=$wpdb->get_var("SELECT usage_count from promo_codes where coupon_code='".$coupon_code."' ");
							$wpdb->update("promo_codes", array("usage_count"=>$count+1), array("coupon_code"=>$coupon_code));			

							$wpdb->insert("promocodes_vs_users",array("promocode"=>$coupon_code,"user_id"=>$user_id,"start_datetime"=>$start,"end_datetime"=>$end));

							update_option('promo_used_'.$user_id,'true');					
			}
						
						else{
							$wpdb->update("buy_a_friend", array("status"=>"Coupon Used"), array("coupon_code"=>$coupon_code));					}

		update_option("pmpro_sub_support_prev_delay_" . $user_id,$o_delay);
		update_option("pmpro_sub_support_delay_" . $user_id,$delayUpd);
		
		
		
		}
		else
		{
		//$info= "Update nothing";
		//fwrite($file,$info);
		}

		//set recd_upd_time to empty and perfoem update_daily and set sub_delay
	

	do_action('update_daily');
	get_option("pmpro_sub_support_delay_" . $user_id); 
	
	update_option("subscribed_".$user_id,"true");

	//fwrite($file,$info);
		//exit;
}

function after_process_coupon($user_id,$plan_id,$startdate,$enddate,$c,$coupon_code)
{

global $wpdb;
		//added

$txt="---------------- /n ";

//dbl_chk start
$old_planid_user=$wpdb->get_var("SELECT membership_id FROM wp_pmpro_memberships_users where user_id=".$user_id." and status<>'active' order by id desc limit 1"); //dbl_chk
$old_startdate_user=$wpdb->get_var("SELECT startdate FROM wp_pmpro_memberships_users where user_id=".$user_id." and status<>'active' order by id desc limit 1"); //dbl_chk

$old_planid_Agentuser=$wpdb->get_var("SELECT plan_id FROM agent_vs_subscription_credit_info where subscriber_id=".$user_id." order by id desc limit 1"); //dbl_chk
$old_startdate_Agentuser=$wpdb->get_var("SELECT credited_datetime FROM agent_vs_subscription_credit_info where subscriber_id=".$user_id." order by id desc limit 1"); //dbl_chk

					if($old_startdate_user!=""){
						if($old_startdate_Agentuser=="")
								{$old_startdate_Agentuser="1970-01-01 01:01:01";} //unix epoch date as it not exists
						
							if( (new DateTime($old_startdate_user)) > (new DateTime($old_startdate_Agentuser)) ){
								$old_planid=$old_planid_user;
							}else{
								$old_planid=$old_planid_Agentuser;
							}
						}else{		
							if($old_startdate_Agentuser!=""){
								$old_planid=$old_planid_Agentuser;
							}					
						}
$txt.="oldstartdate user:".$old_startdate_user;
$txt.="old_startdate_Agentuser:".$old_startdate_Agentuser;

$txt.="oldplanid user:".$old_planid_user;
$txt.="oldplanid Agentuser:".$old_planid_Agentuser;
$txt.="oldplanid final:".$old_planid;
//dbl_chk end
if($old_planid == 0)
{
$old_planname="---";
}
else
{
$old_planname=$wpdb->get_var("SELECT name FROM wp_pmpro_membership_levels where id=".$old_planid." ");
}
$txt.="oldplanname:".$old_planname;
$planid=$plan_id;
$planname=$wpdb->get_var("SELECT name FROM wp_pmpro_membership_levels where id=".$planid." ");

$txt.="planid:".$planid;
$txt.="planname:".$planname;
if($planid == 0)
{
$cancelled=true;
$txt.="cancelled:true";
}

$startdate=$startdate;
$plan_period_no=$wpdb->get_var("SELECT cycle_number FROM wp_pmpro_membership_levels where id=".$planid." ");
$plan_period=$wpdb->get_var("SELECT cycle_period FROM wp_pmpro_membership_levels where id=".$planid." ");

$trail_period_no=$wpdb->get_var("SELECT expiration_number FROM wp_pmpro_membership_levels where id=".$planid." ");
$trail_period=$wpdb->get_var("SELECT expiration_period FROM wp_pmpro_membership_levels where id=".$planid." ");
	
$txt.="1.startdate:".$startdate;
$txt.="plan_period_no:".$plan_period_no;
$txt.="plan_period:".$plan_period;

$txt.="trail_period_no:".$trail_period_no;
$txt.="trail_period:".$trail_period;

$userCount=$wpdb->get_var("select count(user_id) from wp_pmpro_memberships_users where user_id=".$user_id." ");
$userCountAgent=$wpdb->get_var("select count(subscriber_id) from agent_vs_subscription_credit_info where subscriber_id=".$user_id." ");

$allCount=(int)$userCount + (int)$userCountAgent;

$txt.="userCount:".$userCount;
$txt.="userCountAgent:".$userCountAgent;
$txt.="allCount:".$allCount;

if($allCount<2)
{
$delay= get_option("pmpro_subscription_delay_" . $plan_id, "");
$txt.="delay:".$delay;
if($delay=="")
$free="free";
$abc="new user-".$free;
}
else
{
$delay=$trail=get_option("pmpro_sub_support_delay_".$user_id);
//$delay=$wpdb->get_var("select delay from pmpro_dates_chk1 where user_id=".$user_id." order by id desc limit 1");
$abc="not new user";
}

//if delay is null pass 0
 if($delay=="")
$delay=0; 

$txt.="delay:".$delay;
$txt.="abc:".$abc;


update_option("pmpro_sub_support_prev_delay_".$user_id,$delay); //added
$plan_startdate=date('Y-m-d', strtotime($startdate. ' +'.(int)$delay.' days'));

$txt.="plan_startdate:".$plan_startdate;

if($planid == 4)
{
	if( ($trail_period_no != "") && ($trail_period != ""))
	{
		$next_paydate=new DateTime($plan_startdate);
		$next_paydate=$next_paydate->add(new DateInterval('P'.$trail_period_no.$trail_period[0]));

		if($c=='promo')
			$next_paydate=$next_paydate->add(new DateInterval('P1W')); //add extra 1week for promo code

		$next_paydate=$next_paydate->format("Y-m-d");
	}
	$agent="free-trial/$coupon_code";
}
else
{
	if($plan_period != "" && $plan_period_no!="")
	{
	$next_paydate=new DateTime($plan_startdate);
	$next_paydate=$next_paydate->add(new DateInterval('P'.$plan_period_no.$plan_period[0]));
	$next_paydate=$next_paydate->format("Y-m-d");
	}
	$agent=$coupon_code;
}
		$txt.="next_paydate:".$next_paydate;	
		$txt.="agent:".$agent;	
		//delay update after the new/change plan
				$today=new DateTime("now");
				$today=$today->format("Y-m-d");
				$today=new DateTime($today);

				if( (new DateTime($plan_startdate)) > $today )
				{
					$delayeddate=$plan_startdate;
				}
				else
				{
					$delayeddate=$next_paydate;
				}
				
				$txt.="delayeddate:".$delayeddate;	

				$delayeddate=new DateTime($delayeddate);
				$temp = date_diff($today,$delayeddate);
				$delayUpd=$temp->format('%R%a');

				if($delayUpd < 0)
 					{$delayUpd=0;}
				else
				{$delayUpd=$temp->format('%a');}


				$txt.="delayUpd:".$delayUpd;			
		
//insert or update only if the plan is not cancelled ie. plan id exists >0
if($planid > 0)
{

$wpdb->replace("pmpro_dates_chk1", array(
   "user_id" => $user_id,
"old_plan_id" => $old_planid,
"old_plan_name" => $old_planname,
"plan_id" => $planid,
"plan_name" => $planname,
    "startdate" => $startdate, 
   "plan_startdate" => $plan_startdate,
	"next_paydate"=>$next_paydate,
	"delay"=>$delayUpd,
	"agent"=>$agent
  )); 		

//copy delayUpd to wp_options
update_option("pmpro_sub_support_delay_".$user_id, $delayUpd);
//update_option("pmpro_sub_support_delay_" . $current_user->ID, $subscription_delay);

$fromdb=get_option("pmpro_sub_support_delay_".$user_id);

$txt.="delay_from_db:".$fromdb;
}

$txt.="startdate:".$startdate."-or-".$startdate."-usercount-".$userCount."-delay-".$delay."-nextpay-".$next_paydate;

mail("siddish.gollapelli@ideabytes.com","After Process Coupon",print_r($txt,true));
$file = fopen("test.txt","w");
fwrite($file,$txt);
fclose($file);

//ended
}
	//end after_process_coupon



add_shortcode('get_after_login_url','get_login_redirect_url');
function get_login_redirect_url()
{
	global $current_user, $wpdb;
	
	get_currentuserinfo();
	$user_id = get_current_user_id();
	//echo "user:".$user_id;
	$sub=get_option("subscribed_".$user_id);

	$sub_active=$wpdb->get_var("SELECT status from wp_pmpro_memberships_users where user_id=".$user_id." order by id desc limit 1");
	if($sub_active=="active")
		$sub="true";
	else
		$sub="false";

	//echo "sess:".$_SESSION['log_href'];
	//echo "cook:".$_COOKIE['log_href'];
	//exit;

	if($_SESSION['log_href']!="")
	//if($_COOKIE['log_href']!="")
	{
		return site_url().$_SESSION['log_href'];
		//return site_url().$_COOKIE['log_href'];
	}
	
	if($sub=="true")
	{
		//echo "subscribed";
		$last_viewed=$wpdb->get_var("SELECT b.post_name FROM visitors_info a inner join wp_posts b on a.page_id=b.id where a.page_id<>911 and a.user_id=".$user_id." and b.post_type='post' order by a.id desc limit 1;");
		if($last_viewed!="")
		{
			//echo "last_vwd:".$last_viewed;
			return site_url()."/".$last_viewed;
		}
		else
		{

			$channel=$wpdb->get_var("SELECT b.post_name FROM user_favourite_channels a inner join wp_posts b on a.channel_id=b.ID where a.user_id=".$user_id." and a.favourite=1 and b.post_status='publish' order by a.id desc");
			if($channel!="")
			{
			//echo "fav:".$channel;
			return site_url()."/".$channel;
			}
			else
				{
				return site_url()."/tara-tv"; 
				//echo "popular";
				}
		}
	}
	else
	{
	//echo "not subscribed";
	return site_url()."/tara-tv";
	}
	exit;
}

function php_tags_code( $atts, $content = null ) {
	return '<?php' . $content . '?>';
}
add_shortcode( 'php', 'php_tags_code' );

?>
<?php
