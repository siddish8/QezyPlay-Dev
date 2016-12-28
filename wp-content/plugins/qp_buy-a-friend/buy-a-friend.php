<?php 
    /*
    Plugin Name: Buy A Friend
    Plugin URI: 
    Description: This avails a registered user to buy a subscription to his/her friend(s)
    Author: IB
    Version: 1.0
    Author URI: ib
    */

add_shortcode('buy-a-friend','buy_a_friend_fn');

function buy_a_friend_fn(){

global $current_user, $wpdb;



if ((strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile/') !== false) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari/') == false)) {
	//ios_app_user_agent_name
    	echo "<style>header, #bottom-nav_1, footer, .blog-heading {display:none;}</style>";}

if($_SERVER['HTTP_X_REQUESTED_WITH'] == "com.ideabytes.qezyplay.qezyplay_new"){ //android_app_package_name
	echo "<style>header, #bottom-nav_1, footer, .blog-heading {display:none;}</style>";}




if(!is_user_logged_in())
{
header('Location:../');
}
	


//destroy session's href
$_SESSION['log_href']="";

$plan_id=$_REQUEST['plan_id'];
$boq_id=$_REQUEST['boq_id'];

	get_currentuserinfo();
	$user_id = get_current_user_id();
	$user=$wpdb->get_results("SELECT * from wp_users where ID=".$user_id." ");


	foreach($user as $usr)
	{		
	$user_email=$usr->user_email;
	$user_name=$usr->user_login;
	$user_dispname=$usr->display_name;
	}
	
echo "<style>.sweet-alert button.cancel {
    background-color: #607D8B !important;
}</style>";
//$agent_id=11;
$msg  = "";
$msgR = "";

$url=do_shortcode('[get_after_login_url]');

if(isset($_GET['pay'])) {

if($_GET['pay']=="success")
{
echo "<script>

swal({
         title:' ', 
  text: 'Your Payment Successful. Coupon Code is sent to your Friend',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: '#DD6B55', cancelButtonColor: '#607D8B !important',  confirmButtonText: 'Like to View player',   cancelButtonText: 'Buy Another gift '
},function(isConfirm){

if(isConfirm) {

window.location.href='".$url."';

}

else{
window.location.href='buy-a-friend';
}

}); 

</script>";
}
else {

echo  "<script>
swal({
         title:' ', 
  text: '',
  type: 'warning',
  
  confirmButtonColor: '#DD6B55',   confirmButtonText: 'OK'
},function(){ 

window.location.href='buy-a-friend';

}); 



swal({
         title:' ', 
  text: 'Your Payment Failed.',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: '#DD6B55', cancelButtonColor: '#607D8B !important',  confirmButtonText: 'Come back Later',   cancelButtonText: 'Continue to Pay Again'
},function(isConfirm){

if(isConfirm) {

window.location.href='".$url."';

}

else{
window.location.href='buy-a-friend';
}

}); 

</script>";
}

/*echo "<script>swal('Your Payment Failed')
setTimeout(function(){
window.location.href='buy-a-friend'
},3000);</script>";*/
}


if(isset($_POST['share_to_friend']))
{
	$f_name        = $_POST['frnd-name1'];
   	$f_email        = $_POST['frnd-email1'];
	$f_msg        = $_POST['frnd-msg1'];
	$boq_id =4;
	$plan_id =4;
	$boq="Bangla Bouquet";
	$plan="Free Trial";
	$amt=0;
	
	
	//$amt=$wpdb->get_var("SELECT billing_amount from wp_pmpro_membership_levels where id=".$plan_id." ");

	$added_datetime = new DateTime("now");
 	$added_datetime = $added_datetime->format("Y-m-d H:i:s");

	$coupon=generate_coupon();
						
						
//update buy_a_friend status and coupon code on SUCCESS
$wpdb->insert( 
	'buy_a_friend', 
	array( 
		'user_id' =>  $user_id, 
		'friend_name' =>  $f_name  ,
		'friend_email' =>  $f_email  ,
		'friend_message' =>  $f_msg,
		'bouquet_id' => $boq_id,
		'plan_id' => $plan_id,
		'pay_amount' => $amt,
		'added_datetime' => $added_datetime,
		'status' => 'Paid',
		'coupon_code' => $coupon
	));

/**mailing **/

//$admin_mail_id = ADMIN_EMAIL;
						$admin_mail_id="siddish.gollapelli@ideabytes.com";
						$sitename = "QezyPlay";
						
						$reglink = site_url()."/register";						
						$couponpagelink = site_url()."/subscription";
						
						// Always set content-type when sending HTML email
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

						
						$headers .= 'From: Admin-QezyPlay <admin@qezyplay.com>' . "\r\n";
						
						$subjectAdmin = "Gift-to-Friend 'Free Trial' at $sitename";
						$subjectUser = "Your Gift for ($f_name) at $sitename";
						$subjectFriend = "Your Gift from ($user_dispname) at $sitename";
						
						$regards = "<p>Regards, <br>QezyPlay</p>";
						
						$bodyAdmin = "<p>Hi,</p>
						<p>There was a gift-to-friend at $sitename</p>
						<p>Below are details about the gift <br>
						<p>
						User: $user_name ($user_email) <br><br>
						Friend: $f_name ($f_email)<br><br>
						Subscription Bouquet: $boq<br><br>
						Subscription Plan: $plan<br><br>
						Coupon: $coupon
						</p>
						".$regards;
						
						

						$bodyUser = "<p>Hi $user_dispname,</p>
						<p>Your Gift has been sent to your Friend from $sitename. </p>
						<p>Below are the details of your Gift</p>
						<p>
						Name: $f_name <br><br>
						Email: $f_email <br><br>
						Subscription Bouquet: $boq<br><br>
						Subscription Plan: $plan<br><br>
						Coupon: $coupon<br><br>
						Tell your friend to use the coupon code.
						<br><br>
						Note:An Email is sent to your friend. 
						</p>
						".$regards;
						
						

						$bodyFriend = "
						<p>Hi $f_name,</p>
						
						<p>$user_dispname has sent you Qezy<sup>®</sup>Play subscription to shonar bangla for $plan </p>
					
						<blockquote style='font-family: Georgia, serif;
						font-size: 18px;
						font-style: italic;
						width: 500px;
						margin: 0.25em 0;
						padding: 0.35em 40px;
						line-height: 1.45;
						position: relative;
						color: #383838;'><sup><span style='font-size:38px'>&ldquo;</span></sup>
						$f_msg
						<cite style='color: #999999;
						font-size: 14px;
						display: block;
						margin-top: 5px;'>- $user_dispname</cite>
						</blockquote>
						<p>Below are details about your gift</p>
						Subscription Bouquet: $boq<br><br>
						Subscription Plan: $plan<br><br>
						Coupon: $coupon<br><br>
						</p>
						<p>Please use the above coupon to view the LIVE Channels</p>
						<p>If already registered at our site ($sitename): use your coupon code at $couponpagelink</p><p>If not registered at our site ($sitename): use your coupon code at $reglink</p>".$regards;				
						
						//mail Admin
						mail($admin_mail_id,$subjectAdmin,print_r($bodyAdmin,true),$headers);
						//mail Agent
						mail($user_email,$subjectUser,print_r($bodyUser,true),$headers);
						//mail User
						mail($f_email,$subjectFriend,print_r($bodyFriend,true),$headers);





/** mailing_end **/						
		echo "<script>

swal({
         title:' ', 
  text: 'Successfully Shared to your Friend',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: '#DD6B55', cancelButtonColor: '#607D8B !important',  confirmButtonText: 'Like to View player',   cancelButtonText: 'Share to Another Friend '
},function(isConfirm){

if(isConfirm) {

window.location.href='".$url."';

}

else{
window.location.href='buy-a-friend?plan_id=4';
}

}); 

</script>";				

$plan_id=4;
}


if (isset($_POST['add_to_pay'])) {
    
   	$f_name        = $_POST['frnd-name'];
   	$f_email        = $_POST['frnd-email'];
	$f_msg        = $_POST['frnd-msg'];
 	$boq_id         = $_POST['bouquet'];
    //$plan_id=$_POST['plan'];
     	$plan_id        = $_POST['item_number'];
  	  $added_datetime = new DateTime("now");
 	$added_datetime = $added_datetime->format("Y-m-d H:i:s");

	$amt=$wpdb->get_var("SELECT billing_amount from wp_pmpro_membership_levels where id=".$plan_id." ");
 
    //$uname = get_var("select user_login from wp_users where ID=" . $sub_id . " ");
    
    //$sql01  = "SELECT * FROM agent_remittence_pending where agent_id=" . $agentId . " and subscriber_id=" . $sub_id . " and bouquet_id=" . $boq_id . " and plan_id=" . $plan_id . " and status='penidng' ";
    
   // $result01 = $wpdb->get_results($sql01);
    
   // if (count($result01) == 0) {
        //$sql1 = "INSERT INTO agent_remittence_pending(agent_id,subscriber_id,bouquet_id,plan_id,added_datetime) VALUES(?,?,?,?,?)";
       
            
	$wpdb->insert( 
	'buy_a_friend', 
	array( 
		'user_id' =>  $user_id, 
		'friend_name' =>  $f_name  ,
		'friend_email' =>  $f_email  ,
		'friend_message' =>  $f_msg,
		'bouquet_id' => $boq_id,
		'plan_id' => $plan_id,
		'pay_amount' => $amt,
		'added_datetime' => $added_datetime,
	)
	
);

	           
      
        $msgR = "<span style='color:green'><script>
swal({   title: '',   text: 'Gift Successfully added  to the list, You can go for the payment',   type: 'warning',   showCancelButton: false,   confirmButtonColor: '#DD6B55',   confirmButtonText: 'OK',   closeOnConfirm: true }, function(){   
window.location.href='#cart';

 });
</script></span>";
       // $sq = "DELETE FROM agent_credit_requests where agent_id=" . $agent_id . " and user_name='" . $uname . "' ";

	//$wpdb->delete( 'agent_credit_requests', array( 'agent_id' => $agent_id, 'user_name' => $uname ), array( '%d','%s' ) );
        
        
   // } else {
        //echo "<script>swal('Already in Payment Pending List ')</script>";
   // }
    //$added_hide = 1;
    //$userPR     = 1;
}

if (isset($_POST['PRids'])) {
    // print_r($_POST);
    $id     = $_POST['PRids'];

	$wpdb->delete( 'buy_a_friend', array( 'id' => $id ), array( '%d') );
        
    $msgR = "<script>swal('Item deleted from Gift Cart Successfully')</script><script>window.location.href='#cart'</script>";
    
    $userPR = 1;
   
}
if (isset($_REQUEST['payCRs'])) {
    
    $id       = $_REQUEST['pCRids'];
    $email_CR = $_REQUEST['CRemails'];
    //echo "id:".$id."   email:".$email_CR;
    
    $userPR   = 1;
    $CR_to_PR = 1;
    
}

?>
<style>
h2{font-family: georgia !important;}
label{color: #00008B !important;
    font-weight: bolder !important;
    font-family: georgia !important;}
button, input[type='submit']{color:#fff !important}
textarea{color: #4141a0 !important;}
button:hover, input[type='submit']:hover, .dark-div .light-div button:hover, .dark-div .light-div input[type='submit']:hover{color:#fff !important}
</style>


<?php



if($plan_id!=4)
{
?>
<style>

/*td:nth-child(even) {background: #FFF}
td:nth-child(odd) {background: #DDD}*/
th{color:whitesmoke !important;text-align: center;border: 1px solid rgba(255,255,255,.15) !important;}
thead{background-color: #4141a0}
table td {
    color: #444;
border-bottom: 1px solid #777 !important;
	text-align: center;
}

</style>
	<div style="float:right;">
        
<span style="display:inline-block;">&nbsp; &nbsp;</span>
      <!--  <a style="color:#2a85e8;border: 2px solid black;padding: 0px 6px;display:inline-block;" href="javascript:void(0)" onclick="clearF()">Buy for Another Friend</a> --></div>
<br>
<br>

        <div id="emailchkdiv">
            
                                  
		<div id="selection" style=""> 
			
				 
                        <form class='paypal' method='post' id='paypal_form' target='_self' style='display:;border 1px solid grey;'>			
                            <div style="margin: 10px auto;
    padding: 12px;background-color: #f1f2f2 !important;
    border: #b71f37 4px solid !important;">

			 <h2><center><span>Friend's Details</span></center></h2> 
                       
                        <div class=""> <label class="control-label">Name</label> <input onblur="validateF(this.id)" onclick="clearF()"  onkeypress="" type="text" name='frnd-name' id='frnd-name' class="form-control"> <div id="frnd-name-error" style="color:red"></div></div>                
                                   <div class="form-group"> <label class="control-label">E-mail</label> <input onblur="validateF(this.id)" onclick="clearF()"  onkeypress="return event.charCode !=32" type="text" name='frnd-email' id='frnd-email' class="form-control"><div id="frnd-email-error" style="color:red"></div> </div>                

			 <div class=""> <label class="control-label">Message</label> <textarea class="" onblur="validateF(this.id)" onclick="clearF()" col="20" row="3" name='frnd-msg' id='frnd-msg' class="form-control"></textarea><div id="frnd-msg-error" style="color:red"></div> </div>                         				
    			<div class=""> <label class="control-label">Bouquet</label> <select onblur="validateF(this.id)" onclick="clearF()" style="color:black;" id="bouquet" name='bouquet' value="<?php echo $boq_id?>" ><option value=''>Select Bouquet</option>

	<?php
    $sql3 = "SELECT id, name FROM bouquets where status=1 and is_free=0";
    
        
        $result3 = $wpdb->get_results($sql3);
       
        foreach ($result3 as $boq) {
            $boqid   = $boq->id;
            $boqname = $boq->name;
            	$boqselected = ($boqid == $_REQUEST['boq_id']) ? 'selected="selected" ' : "";
                echo "<option value='" . $boqid . "' $boqselected >" . $boqname . "</option>";
           
        }
    
    
?>
</select> <div id="bouquet-error" style="color:red"></div></div>			<div class=""> <label class="control-label">Plan</label> <select onblur="validateF(this.id)" onclick="clearF()" id="plan" style="color:black;" name='item_number' value="<?php echo $plan_id?>" ><option value=''> --Select Plan-- </option>                                
                                <?php
    $sql2 = "SELECT id, name, billing_amount FROM wp_pmpro_membership_levels where allow_signups=1";
    
        
        $result2 = $wpdb->get_results($sql2);
       
        foreach ($result2 as $plan) {
            $planid   = $plan->id;
            $planname = $plan->name;
            if ($plan->billing_amount > 0) {
		$planselected = ($planid == $_REQUEST['plan_id']) ? 'selected="selected" ' : "";
                echo "<option value='" . $planid . "' $planselected>" . $planname . "</option>";
            }
        }
    
    
?>
                               </select> <div id="plan-error" style="color:red"></div></div>
                          <div > </label><input onclick="return validateBuy()" type='submit' name='add_to_pay' value='Add to List' id='add_to_pay' /></div>
                           
                        
		</div>
			
                    </form>
	</div>
                <!-- </div> -->

            </div>
       

        <!--<div class="xoouserultra-wrap xoouserultra-login" style="float:left;width:50%;padding:0px 10px">
            <div class="xoouserultra-inner xoouserultra-login-wrapper">
                <div class="xoouserultra-main"> -->

<br>
<div class="msg" align="center" style="display:block"><h4><?php
    echo $msgR;
?></h4></div>     
    <br />    
			<a href="javascript:void(0)" name="cart"></a>
			<div id="PayReady" style="max-width: 100%;">
                        
                                        <div class="card-title-block">
                                            <h3 class="title">
							<center>Gift Cart <i class="fa fa-shopping-cart" aria-hidden="true"></i></center>
						</h3> </div>
                                        		<!-- <div class="table-responsive"	style="max-height: 400px; overflow-y: scroll;"> -->

		<form id="frm1" method="post">
		 <div class="table-responsive">
                                                <table style="max-width:700px" id="pending_list" class="table table-striped table-bordered table-hover">
<?php
    echo '
        
            <thead>
                <tr>                    
                    <th>Friend\'s Name</th>
			<th>Friend\'s Email</th>
                    <th>Bouquet</th>
                    <th>PlanName</th>
                    <th>Amount (USD)</th>
                    <th>Status</th>
                    <th>Action</th>
                    
                </tr>
            </thead>
            <tbody class="ui-sortable">';
    
    $sql99  = "SELECT a.id,a.user_id,a.friend_name,a.friend_email,a.bouquet_id,c.name as bouquet,a.plan_id,b.name as plan,b.billing_amount,a.added_datetime,a.status FROM buy_a_friend a inner join wp_pmpro_membership_levels b on b.id=a.plan_id inner join bouquets c on c.id=a.bouquet_id where a.status='Pending' and user_id=$user_id ";
    
    $result99 = $wpdb->get_results($sql99);
    
    if (count($result99) == 0) {
        echo "<style>.no_pending{display:none}</style>";
        echo "<tr style='text-align:center'><td colspan='7'>No Pending Gifts To Show</td></tr>";
    }
    
    foreach ($result99 as $row99) {
        
        
        $id = $row99->id;
        //$aid=$row99['agent_id'];
        
        //if($aid!=$agent_id)
        //    continue;
        $userid   = $row99->user_id;
        //$username = $row99->user_login;
	$f_name= $row99->friend_name;
		$f_email= $row99->friend_email;
		//$f_msg= $row99->friend_msg; 
        
       // $email = $row99->user_email;
        $boq_name   = $row99->bouquet;
       // if ($boq == 1) {
          //  $boq_name = "Bangla Bouquet";
        //}
        
        $plan_name   = $row99->plan;
        $plan_amount = $row99->billing_amount;
        $status      = $row99->status;
        
        $time = $row99->added_datetime;
        
           
        echo '<tr id="PR-' . $id . '" style="" class="ui-sortable-handle">                            
                <td style="width: 192px;" class="level_name">' . $f_name .'</td>
		<td style="width: 192px;" class="level_name">' . $f_email .'</td>
                <td style="width: 192px;">' . $boq_name . '</td>
                <td style="width: 184px;">' . $plan_name . '</td>
                <td style="width: 184px;">' . $plan_amount . '</td>
               <!-- <td style="width: 184px;">' . $time . '</td> -->
                <td style="width: 184px;">' . $status . '</td>
                <td style="width: 100px;">
                <input type="hidden" id="PRid" name="PRid" value="' . $id . '">
                <input type="hidden" id="delPR" name="delPR" value="false">
                <a style="padding: 1px 4px !important;
    background: none !important;
    border: 0px transparent !important;
    box-shadow: none !important;
    color: #2a85e8 !important;
    font-size: 15px;
    font-weight: bold;
    margin: 0px 5px !important;" href="#" id="removePR" name="removePR" onclick="return confirmDelPR(' . $id . ')"><!--<span title="Remove" class="fa fa-trash-o"></span>-->Delete</a>
                
                
                        <input type="hidden" name="cmd" value="_xclick"/>
                        <input type="hidden" name="no_note" value="1"/>
                        <input type="hidden" name="lc" value="IND"/>
                        <input type="hidden" name="currency_code" value="USD"/>
                        <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest"/>
                        <input type="hidden" name="first_name" value=""/>
                        <input type="hidden" name="last_name" value=""/>
                        <input type="hidden" name="item_name1" value="Paying: '.$f_name. '"/>
                        <!-- <input type="hidden" name="amount1" id="amount1" value="' . $plan_amount . '"/> -->
                       <!-- <input type="hidden" name="agent_id" value="' . $agent_id. '"> -->
                        <input type="hidden" name="user_id" value="'.$userid.'">
                        <input type="hidden" name="bulk1" value="false">                        
                        <button style="padding: 1px 4px !important;
    background: none !important;
    border: 0px transparent !important;
    box-shadow: none !important;
    color: #2a85e8 !important;
    font-size: 15px;
    font-weight: bold;
    margin: 0px 5px !important;" type="submit" name="payPR" onclick="this.form.action=\''.site_url().'/wp-content/plugins/qp_buy-a-friend/paypal-processing.php?Rid=' . $id . '&amount=' .$plan_amount. '&item_name=Paying: ' .$f_name. '&bulk=false\'"><!--<em title="Subscribe" class="fa fa-dollar"></em><em title="Subscribe" class="fa fa-money"></em><em title="Subscribe" class="fa fa-dollar"></em>-->Pay</button>
                        <input type="hidden" name="payer_email" value="">                            
              
</td>
                
                </tr>';
    }
    
    echo '</tbody>
            ';
    
    
?>
   </table></div></form>
 
                                    
                         <!--</div> -->
        <!-- </div></div>-->
        <!-- <div> -->
        <form class='paypal' action='<?php echo site_url() ?>/wp-content/plugins/qp_buy-a-friend/paypal-processing.php' method='post' id='paypal_formAll' style=''>
                        <input type='hidden' name='cmd' value='_xclick'/>
                        <input type='hidden' name='no_note' value='1'/>
                        <input type='hidden' name='lc' value='IND'/>
                        <input type='hidden' name='currency_code' value='USD'/>
                        <input type='hidden' name='bn' value='PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest'/>
                        <input type='hidden' name='first_name' value=''/>
                        <input type='hidden' name='last_name' value=''/>
                        <input type='hidden' name='item_name' value='Bulk Pay'/>
                        <input type='hidden' name='total_amount' id="total_amount" value=''/>
                        
                        
                            
                        <input class="no_pending" type='submit' name='submit' value='Pay All' id='Paypal' style="float:right;margin:15px" />
                        <label class="no_pending" style="float: right;
    margin: 25px;
    font-size: 18px;">Total Amount :  $ <span style="color:black" id="total_amount1"></span>  USD</label>                        
                                                                            
                        <div class="xoouserultra-clear"></div>
                        <input type='hidden' name='payer_email' value=''  />    
                        <input type='hidden' name='user_id' value="<?php echo $userid ?>" / >
                        
        
        </form>
        <!-- </div> -->
        </div>
<?php 
}
else
{

?>

<div style="float:right;">
        
<span style="display:inline-block;">&nbsp; &nbsp;</span>
      <!--  <a style="color:#2a85e8;border: 2px solid black;padding: 0px 6px;display:inline-block;" href="subscription">Buy Another Subscription</a>--></div>
<br>
<br>

<div id="emailchkdiv">
            
                                  
		<div id="selection" style=""> 
			
				 
                        <form method='post' style='display:;border 1px solid grey;'>			
                            <div style="margin: 10px auto;
    padding: 12px;background-color: #f1f2f2 !important;
    border: #b71f37 4px solid !important;">

			<h2><center><span>Friend's Details</span></center></h2> 
                       
                       <div class=""> <label class="control-label">Name</label> <input onblur="validateF1(this.id)" onclick="clearF1()"  onkeypress="" type="text" name='frnd-name1' id='frnd-name1' class="form-control"> <div id="frnd-name1-error" style="color:red"></div></div>                
                                   <div class="form-group"> <label class="control-label">E-mail</label> <input onblur="validateF1(this.id)" onclick="clearF1()"  onkeypress="return event.charCode !=32" type="text" name='frnd-email1' id='frnd-email1' class="form-control"><div id="frnd-email1-error" style="color:red"></div> </div>                

			 <div class=""> <label class="control-label">Message</label> <textarea class="" onblur="validateF1(this.id)" onclick="clearF1()" col="20" row="3" name='frnd-msg1' id='frnd-msg1' class="form-control"></textarea><div id="frnd-msg1-error" style="color:red"></div> </div>   
                                   
			         
                          <div > </label><input onclick="return validateBuy1()" type='submit' name='share_to_friend' value='Share to Friend' id='share_to_friend' /></div>
                           
                        
		</div>
			
                    </form>
	</div>
                <!-- </div> -->

            </div>
</div>

<?php
}
?>
<script>

setTimeout(function(){
//jQuery(".msg").slideUp();
},3000);



function confirmDelPR(PRid)
{

swal({
         title:' ', 
  text: 'Do you really want to remove this from the list?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Delete",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "PRids").val(PRid);
jQuery('#frm1').append(jQuery(input));
 jQuery("#frm1").submit();

}); 
   
}
var sum=colSum("pending_list",4);

jQuery("#total_amount1").html(sum);
jQuery("#total_amount").val(sum);
//alert(s);
function colSum(tableId, colNumber)
{

  // find the table with id attribute tableId
  // return the total of the numerical elements in column colNumber
  // skip the top row (headers) and bottom row (where the total will go)
    var debugScript=false;    
  var result = 0;
        
  try
  {
    var tableElem = window.document.getElementById(tableId);     
       
    var tableBody = tableElem.getElementsByTagName("tbody").item(0);
    var i;
    var howManyRows = tableBody.rows.length;
    //alert(howManyRows);    
    for (i=0; i<howManyRows; i++) // skip first and last row (hence i=1, and howManyRows-1)
    {
       var thisTrElem = tableBody.rows[i];
    //alert(thisTrElem);
       var thisTdElem = thisTrElem.cells[colNumber];            
       var thisTextNode = thisTdElem.childNodes.item(0);
       if (debugScript)
       {
          window.alert("text is " + thisTextNode.data);
       } // end if

       // try to convert text to numeric
       var thisNumber = parseFloat(thisTextNode.data);
       // if you didn't get back the value NaN (i.e. not a number), add into result
       if (!isNaN(thisNumber))
         result += thisNumber;
     } // end for
         
  } // end try
  catch (ex)
  {
     //window.alert("Exception in function computeTableColumnTotal()\n" + ex);
     result = 0;
  }
  finally
  {
     return Math.round(result * 100) / 100;
  }
    
}


    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

   
function validateF(id)
{

//if(id=="frnd-name")
//{			
			if(id!="frnd-msg" && id!="frnd-email")
			{
				var val=document.getElementById(id);
			
				if(val.value == ""){
					val.style.border="1px solid rgb(210, 8, 8)";
					val.style.boxShadow="0px 0px 10px rgb(210, 8, 8)";
					val.style.outline="medium none";
					return false;}
				else
				{
						val.style.border="1px solid black";
						val.style.boxShadow="0px 0px 0px black";
					return true;
				}
			}

			if(id=="frnd-email")
			{
				
				var val=document.getElementById(id);
				var chk=validateEmail(val.value);
				
				if(val.value == ""){
					val.style.border="1px solid rgb(210, 8, 8)";
					val.style.boxShadow="0px 0px 10px rgb(210, 8, 8)";
					val.style.outline="medium none";
					return false;}
				
				else if(!chk && val.value != "")
				{
					jQuery("#frnd-email-error").html("Please enter a valid Email");
					val.style.border="1px solid rgb(210, 8, 8)";
					val.style.boxShadow="0px 0px 10px rgb(210, 8, 8)";
					val.style.outline="medium none";
					return false;
				}
				
				else
				{		jQuery("#frnd-email-error").html("");
						val.style.border="1px solid black";
						val.style.boxShadow="0px 0px 0px black";
					return true;
				}

			}
//}


}

function validateF1(id)
{

if(id!="frnd-msg1" && id!="frnd-email1")
			{
				var val=document.getElementById(id);
			
				if(val.value == ""){
					val.style.border="1px solid rgb(210, 8, 8)";
					val.style.boxShadow="0px 0px 10px rgb(210, 8, 8)";
					val.style.outline="medium none";
					return false;}
				else
				{
						val.style.border="1px solid black";
						val.style.boxShadow="0px 0px 0px black";
					return true;
				}
			}

			if(id=="frnd-email1")
			{
				
				var val=document.getElementById(id);
				var chk=validateEmail(val.value);
				
				if(val.value == ""){
					val.style.border="1px solid rgb(210, 8, 8)";
					val.style.boxShadow="0px 0px 10px rgb(210, 8, 8)";
					val.style.outline="medium none";
					return false;}
				
				else if(!chk && val.value != "")
				{
					jQuery("#frnd-email1-error").html("Please enter a valid Email");
					val.style.border="1px solid rgb(210, 8, 8)";
					val.style.boxShadow="0px 0px 10px rgb(210, 8, 8)";
					val.style.outline="medium none";
					return false;
				}
				
				else
				{		jQuery("#frnd-email1-error").html("");
						val.style.border="1px solid black";
						val.style.boxShadow="0px 0px 0px black";
					return true;
				}

			}

}



 
function clearF()
{
jQuery(".msg").slideUp();
//jQuery("#frnd-name").val("");
//jQuery("#frnd-email").val("");
//jQuery("#frnd-msg").val("");
//jQuery("option:selected").removeAttr("selected");
//jQuery("#bouquet").val("");
//jQuery("#plan").val("");


jQuery("#frnd-name-error").html("");
//jQuery("#frnd-email-error").html("");
jQuery("#frnd-msg-error").html("");
jQuery("#bouquet-error").html("");
jQuery("#plan-error").html("");


/*document.getElementById("frnd-name").style.border="1px solid black";
document.getElementById("frnd-name").style.boxShadow="0px 0px 0px black";

document.getElementById("frnd-email").style.border="1px solid black";
document.getElementById("frnd-email").style.boxShadow="0px 0px 0px black";

document.getElementById("frnd-msg").style.border="1px solid black";
document.getElementById("frnd-msg").style.boxShadow="0px 0px 0px black";

document.getElementById("bouquet").style.border="1px solid black";
document.getElementById("bouquet").style.boxShadow="0px 0px 0px black";

document.getElementById("plan").style.border="1px solid black";
document.getElementById("plan").style.boxShadow="0px 0px 0px black";
*/



}

function clearF1()
{
jQuery(".msg").slideUp();
//jQuery("#frnd-name").val("");
//jQuery("#frnd-email").val("");
//jQuery("#frnd-msg").val("");
//jQuery("option:selected").removeAttr("selected");
//jQuery("#bouquet").val("");
//jQuery("#plan").val("");


jQuery("#frnd-name1-error").html("");
//jQuery("#frnd-email1-error").html("");
jQuery("#frnd-msg1-error").html("");

}

function validateBuy()
{
//onblur="validate(this.id)"  onclick="styleback(this.id)"
var v=jQuery("#frnd-name");

if(v.val()=="")
{
jQuery("#frnd-name-error").html("Please enter your Friend name");
return false;
}

v=jQuery("#frnd-email");
if(v.val()=="")
{
jQuery("#frnd-email-error").html("Please enter your Friend's email");
return false;
}
var chk=validateEmail(v.val());
if(!chk)
{
jQuery("#frnd-email-error").html("Please enter a valid Email");
return false;
}

/*jQuery("#frnd-msg")
if(v.val=="")
{
jQuery("#frnd-msg-error").html("Please enter some message");
return false;
}*/
v=jQuery("#bouquet");

if(v.val()=="")
{
jQuery("#bouquet-error").html("Please select a bouquet");
return false;
}

v=jQuery("#plan");
if(v.val()=="")
{
jQuery("#plan-error").html("Please select a plan");
return false;
}


return true;

}
function validateBuy1()
{
//onblur="validate(this.id)"  onclick="styleback(this.id)"
var v=jQuery("#frnd-name1");

if(v.val()=="")
{
jQuery("#frnd-name1-error").html("Please enter your Friend name");
return false;
}

v=jQuery("#frnd-email1");
if(v.val()=="")
{
jQuery("#frnd-email1-error").html("Please enter your Friend's email");
return false;
}
var chk=validateEmail(v.val());
if(!chk)
{
jQuery("#frnd-email1-error").html("Please enter a valid Email");
return false;
}

/*jQuery("#frnd-msg")
if(v.val=="")
{
jQuery("#frnd-msg-error").html("Please enter some message");
return false;
}*/



return true;

}

</script>

<?php
if ($CR_to_PR == 1) {
            echo "<script>jQuery('#emailchecking').show();
		jQuery('#user-emailUN').val('" . $email_CR . "');
                jQuery('#validate').click();</script>";
        }

}

function generate_coupon()
{
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
	$length=6;
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $cpn="QZ".$randomString;
if(confirm_coupon($cpn))
{
return $cpn;
}
else
{
generate_coupon();
}
}

function confirm_coupon($cpnI)
{
global $dbcon,$wpdb;

$sql = "SELECT * FROM buy_a_friend WHERE coupon_code = '$cpnI'";		
	try {
		$result = $wpdb->get_results($sql);
		if(count($result) > 0){
			return false;
		}		

	}catch (PDOException $e){
		return $e->getMessage();
	}

return true;
}

