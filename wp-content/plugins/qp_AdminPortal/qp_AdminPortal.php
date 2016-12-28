<?php 

/*
Plugin Name: Admin Portal 
Plugin URI: 
Description: Access to all admin-based functionalities
Author: IB
Version: 1.0
Author URI: ib
*/

add_shortcode('admin_portal','admin_fn');
 
function admin_fn()
{
global $wpdb;

$username = $password = $emailError = $authError = '';

if(isset($_POST['login'])){
	
 	$email = trim($_POST['email']); 
	$pass = trim($_POST['pwd']);
	
	/* $count=$wpdb->get_var("SELECT count(email) from admin_access where email=".$email." ");
	
	if($count>0){
	$match=1;
	} */
		
	
	if($email=="siddishg@gmail.com")
	{
	$match=1;
	
	}
		$pwd="sid";
	if($match==0){		
		$emailError = 'Entered Email-id does not exists';
	}else{
		//$results=$wpdb->get_results("SELECT password FROM admin_access WHERE email=".$email." ");
		
		//$results['password']="sid";

		//echo "matched";
		//$pwd="sid";
		if($pass == "sid"){
					
							//success
					echo '<style>	#xoouserultra-login-form-1{display:none;}
							.xoouserultra-inner xoouserultra-login-wrapper{visibilty:hidden}
					
							.mx{max-width:20%;margin:0 auto !important;}
							 /* Style the list */
							ul.tabM {
							    list-style-type: none;
							    margin: 0;
							    padding: 0;
							    overflow: hidden;
							    border: 1px solid #ccc;
							    background-color: #f1f1f1;
							}

							/* Float the list items side by side */
							ul.tabM li {float: left;}

							/* Style the links inside the list items */
							ul.tabM li a {
							    display:block;
							    color: black;
							    text-align: center;
							    padding: 14px 16px;
							    text-decoration: none;
							    transition: 0.3s;
							    font-size: 17px;
							}

							/* Change background color of links on hover */
							ul.tabM li a:hover {background-color: #ddd;}

							/* Create an active/current tablink class */
							ul.tabM li a:focus, .active {background-color: #ccc;}

							/* Style the tab content */
							.tabcontentM {
							    display: none;
							       border-top: none;
							}
						</style>';

					echo '<script>swal("Welcome...");</script>';

					echo '<ul class="tabM mxM">
							  <li><a id="Agent" href="#" class="tablinksM" onclick="adminPortal(event, \'AgentDiv\')">Agent Management</a></li>
							  <li><a id="Customer" href="#" class="tablinksM" onclick="adminPortal(event, \'CustomerDiv\')">Customer Management</a></li>
							<li><a id="ExtraParam" href="#" class="tablinksM" onclick="adminPortal(event, \'ExtraParamDiv\')">Extra Parameters Settings</a></li>
							  <li><a id="UserForms" href="#" class="tablinksM" onclick="adminPortal(event, \'UserFormsDiv\')">User Forms Display</a></li>
							<li><a id="UserStats" href="#" class="tablinksM" onclick="adminPortal(event, \'UserStatsDiv\')">User Statistics Display</a></li>
							   </ul>';

					echo '<div id="AgentDiv" class="tabcontentM mxM">'; echo do_shortcode('[AgentAdmin]'); echo '</div>';
					echo '<div id="CustomerDiv" class="tabcontentM mxM">'; echo do_shortcode('[CustomerAdmin]'); echo '</div>';
					echo '<div id="ExtraParamDiv" class="tabcontentM mxM">'; echo do_shortcode('[ExtraParamAdmin]'); echo '</div>';
					echo '<div id="UserFormsDiv" class="tabcontentM mxM">'; echo do_shortcode('[UserFormsAdmin]'); echo '</div>';
					echo '<div id="UserStatsDiv" class="tabcontentM mxM">'; echo do_shortcode('[UserStatsAdmin]'); echo '</div>';
		
					echo '<script>
					function adminPortal(evt, cityName) {
						
						
					    // Declare all variables
					    var i, tabcontent, tablinks;

					    // Get all elements with class="tabcontentM" and hide them
					    tabcontent = document.getElementsByClassName("tabcontentM");
					    for (i = 0; i < tabcontent.length; i++) {
						tabcontent[i].style.display = "none";
					    }

					    // Get all elements with class="tablinksM" and remove the class "active"
					    tablinks = document.getElementsByClassName("tablinksM");
					    for (i = 0; i < tablinks.length; i++) {
						tablinks[i].className = tablinks[i].className.replace(" active", "");
					    }

					    // Show the current tab, and add an "active" class to the link that opened the tab
					    document.getElementById(cityName).style.display = "block";
					    evt.currentTarget.className += " active";
					}
					</script>';

			}


					/*if($pass != $pwd)  {
							$authError = 'Invalid Password'; 
					}*/
		}
	}

?>
<style>
@media (min-width: 1200px){
#content{
    margin-bottom: 80px;
    margin-top: 80px;
}
</style>
<div id="content" role="main">
	<div class="xoouserultra-wrap xoouserultra-login">
		<div class="xoouserultra-inner xoouserultra-login-wrapper">
			<div class="xoouserultra-main">
				<form id="xoouserultra-login-form-1" method='post'>
					<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
						<label for="user_login" class="xoouserultra-field-type">
							<i class="fa fa-user"></i>
							<span>Email </span>
						</label>
						<div class="xoouserultra-field-value">
							<input class="xoouserultra-input" required="required" onclick='document.getElementById(\"emailerror\").innerHTML=\"\"' type='email' value="<?php echo $email; ?>" id='email' name='email'>
							<div id='emailerror' <?php if($emailError != ""){ echo "style='color:red;padding-left:10px;'"; }?>><?php echo $emailError; ?></div>
						</div>
					</div>		
					<div class="xoouserultra-clear"></div>
					<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
						<label for="login_user_pass" class="xoouserultra-field-type">
							<i class="fa fa-lock"></i>
							<span>Password</span>
						</label>
	
						<div class="xoouserultra-field-value">
							<input type="password" onclick='document.getElementById(\"keyerror\").innerHTML=\"\";' type='password' value="<?php echo $authkey; ?>" id='pwd' name='pwd'>					
							<div id='keyerror' <?php if($authError != ""){ echo "style='color:red;padding-left:10px;'"; } ?>><?php echo $authError; ?></div>
						</div>
					</div>
					<div class="xoouserultra-clear"></div>
					<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
						<label class="xoouserultra-field-type">&nbsp;</label>
						<div class="xoouserultra-field-value" style="padding-left: 10px;">
							<input type="submit" name='login' value="Log In" class="xoouserultra-button xoouserultra-login" name="xoouserultra-login" id="Login">
						</div>
					</div>
					<div class="xoouserultra-clear"></div>
				</form>
			</div>
		</div>
	</div>
</div>



<?php
}
?>
<?php

