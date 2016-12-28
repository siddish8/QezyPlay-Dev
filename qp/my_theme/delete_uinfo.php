<?php
include("theme-header.php");
//include("customer-include.php");
//include("function_common.php");


if(($_POST['get_uname'])!=""){
$usrid=$_POST['uid'];
$usname=get_var("SELECT user_login from wp_users WHERE ID='".$usrid."'");
}

if(($_POST['get_uid'])!=""){	

$uname=$_POST['uname'];	
$usid=get_var("SELECT ID from wp_users WHERE user_login='".$uname."'");
}

if(isset($_POST['reg'])){

//Registrations Info Delete
echo "user_id:".$id=$_POST['userid'];
$msg="<br>";

$stmt1 = $dbcon->prepare("DELETE FROM wp_users WHERE ID = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$r1=$stmt1->execute();
if($r1)
{
$msg.="Deleted from wp_users <br>";
}

$stmt2 = $dbcon->prepare("DELETE FROM wp_usermeta WHERE user_id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$r2=$stmt2->execute();
if($r2)
{
$msg.="Deleted from wp_usermeta <br>";
}

$stmt3 = $dbcon->prepare("DELETE FROM wp_options WHERE option_name = 'activation_".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$r3=$stmt3->execute();
if($r3)
{
$msg.="Deleted from wp_options -activation <br>";
}

}

if(isset($_POST['sub'])){

//Subscription Info Delete
echo "user_id:".$id=$_POST['userid'];
$msg="<br>";

$stmt1 = $dbcon->prepare("DELETE FROM wp_pmpro_memberships_users WHERE user_id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$r1=$stmt1->execute();
if($r1)
{
$msg.="Deleted from wp_pmpro_memberships_users <br>";
}

$stmt2 = $dbcon->prepare("DELETE FROM agent_vs_subscription_credit_info WHERE subscriber_id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$r2=$stmt2->execute();
if($r2)
{
$msg.="Deleted from agent_vs_subscription_credit_info <br>";
}


$stmt3 = $dbcon->prepare("DELETE FROM pmpro_dates_chk1 WHERE user_id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$r3=$stmt3->execute();
if($r3)
{
$msg.="Deleted from pmpro_dates_chk <br>";
}

$stmt4 = $dbcon->prepare("DELETE FROM wp_options WHERE option_name = 'subscribed_".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$r4=$stmt4->execute();
if($r4)
{
$msg.="Deleted from wp_options - subscribed <br>";
}

$stmt5 = $dbcon->prepare("DELETE FROM wp_options WHERE option_name = 'pmpro_sub_support_delay_".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$r5=$stmt5->execute();
if($r5)
{
$msg.="Deleted from wp_options - sub_supp_delay <br>";
}

$stmt6 = $dbcon->prepare("DELETE FROM wp_options WHERE option_name = 'pmpro_sub_support_prev_delay_".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$r6=$stmt6->execute();
if($r6)
{
$msg.="Deleted from wp_options - sub_supp_prev_delay <br>";
}



}

if(isset($_POST['subX'])){

//Agent Sub-req, Penidng Remittance Info Delete
echo "user_id:".$id=$_POST['userid'];
echo "user_name:".$name=$_POST['username'];

$msg="<br>";

$stmt1 = $dbcon->prepare("DELETE FROM agent_credit_requests WHERE user_name = ".$name."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$r1=$stmt1->execute();
if($r1)
{
$msg.="Deleted from agent_credit_requests <br>";
}

$stmt2 = $dbcon->prepare("DELETE FROM agent_remittence_pending WHERE subscriber_id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$r2=$stmt2->execute();
if($r2)
{
$msg.="Deleted from agent_remittence_pending <br>";
}


}

if(isset($_POST['comp'])){

//Complete Info Delete
echo "user_id:".$id=$_POST['userid'];
echo "user_name:".$name=$_POST['username'];



$stmt2 = $dbcon->prepare("DELETE FROM wp_usermeta WHERE user_id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt2->execute();

$stmt3 = $dbcon->prepare("DELETE FROM wp_options WHERE option_name = 'activation_".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt3->execute();

$stmt4 = $dbcon->prepare("DELETE FROM wp_pmpro_memberships_users WHERE user_id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt4->execute();

$stmt5 = $dbcon->prepare("DELETE FROM agent_vs_subscription_credit_info WHERE subscriber_id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt5->execute();

$stmt6 = $dbcon->prepare("DELETE FROM pmpro_dates_chk1 WHERE user_id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt6->execute();

$stmt7 = $dbcon->prepare("DELETE FROM wp_options WHERE option_name = 'subscribed_".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt7->execute();

$stmt8 = $dbcon->prepare("DELETE FROM wp_options WHERE option_name = 'pmpro_sub_support_delay_".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt8->execute();

$stmt9 = $dbcon->prepare("DELETE FROM wp_options WHERE option_name = 'pmpro_sub_support_prev_delay_".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt9->execute();

$stmt10 = $dbcon->prepare("DELETE FROM agent_credit_requests WHERE user_name = ".$name."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt10->execute();

$stmt11 = $dbcon->prepare("DELETE FROM agent_remittence_pending WHERE subscriber_id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt11->execute();

$stmt1 = $dbcon->prepare("DELETE FROM wp_users WHERE ID = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt1->execute();

$msg="Deleted All info";
}


if($usid!=""){$f=$usid;}else{$f=$usrid;}

if($usname!=""){$g=$usname;}else{$g=$uname;}

echo '<div class="alert alert-info fade in"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>'.$msg.'</strong> 
</div>';

echo "<style>.noclick{opacity: 0.65; 
  cursor: not-allowed;pointer-events:none}</style>";
echo '
<!-- Main -->
				<div id="main-wrapper">
					<div class="container">
						<div class="row 200%">
							
							<div class="8u 12u(medium) important(medium)">

								<!-- Content -->
									<div id="content">
										<section class="last">
											<h2>First Get User Info </h2>

											<p>
											<form method="post">
Enter Username : 
<input type="text" name="uname" id="uname" /> 
<input type="submit" name="get_uid" value="Get UserId" />  
<span >'.$usid.'</span><br /><br />

or Enter UserId: <input type="text" name="uid" id="uid" /> 
<input type="submit" name="get_uname" value="Get Username" />  
<span >'.$usname.'</span><br /><br />

 <input type="hidden" name="userid" id="userid" value="'.$f.'" />
<input type="hidden" name="username" id="username" value="'.$g.'" />

 										</p>

											<!-- a href="#" class="button icon fa-arrow-circle-right">Continue Reading</a -->
										</section>
									</div>

							</div>

							<div class="4u 12u(medium)">

								<!-- Sidebar -->
									<div id="sidebar">
										<section class="widget thumbnails">
											<h3>DELETE ACTIONS</h3>
											<div class="grid">
												<div class="row 50%">
													<div class="6u"><input id="delb1"  class="noclick" type="submit" name="sub" value="Subscription Delete" /></div>
													<div class="6u"><input id="delb2"  class="noclick" type="submit" name="subX" value="Agent Portal Req&Rem Delete" /></div>
													<div class="6u"><input id="delb3"  class="noclick" type="submit" name="reg" value="Registration Delete" /></div>
													<div class="6u"><input id="delb4"  class="noclick" type="submit" name="comp" value="Complete info Delete" /></div>
												</div>
											</div>
											<!-- a href="#" class="button icon fa-file-text-o">More</a -->
										</section>
									</div>

							</div>
						</form>						
						</div>
					</div>
				</div>




';

echo '
<script>
var a=document.getElementById("username").value;
var b=document.getElementById("userid").value;

var b1=document.getElementById("delb1");
var b2=document.getElementById("delb2");
var b3=document.getElementById("delb3");
var b4=document.getElementById("delb4");

if(a!="" && b!="")
{
b1.setAttribute("class","");
b2.setAttribute("class","");
b3.setAttribute("class","");
b4.setAttribute("class","");
}
else
{
b1.setAttribute("class","noclick");
b2.setAttribute("class","noclick");
b3.setAttribute("class","noclick");
b4.setAttribute("class","noclick");

}


</script>
';

?>
<?php
include("theme-footer.php");
?>


?>
