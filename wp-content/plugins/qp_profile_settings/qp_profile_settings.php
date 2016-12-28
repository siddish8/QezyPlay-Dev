<?php 
    /*
    Plugin Name: User Profile Settings
    Plugin URI: 
    Description: Settings for user to change password and email from EditProfile
    Author: IB
    Version: 1.0
    Author URI: ib
    */

add_shortcode('prof-settings','profile_settings');

function profile_settings(){

add_filter('send_email_change_email', '__return_false');
add_filter('send_password_change_email', '__return_false');

if(!is_user_logged_in())
{
header('Location:../login');
}
global $xoouserultra;

$module = "";
$act= "";
$gal_id= "";
$page_id= "";
$view= "";
$reply= "";
$post_id ="";

if(isset($_GET["module"])){	$module = $_GET["module"];	}
if(isset($_GET["act"])){$act = $_GET["act"];	}
if(isset($_GET["gal_id"])){	$gal_id = $_GET["gal_id"];}
if(isset($_GET["page_id"])){	$page_id = $_GET["page_id"];}
if(isset($_GET["view"])){	$view = $_GET["view"];}
if(isset($_GET["reply"])){	$reply = $_GET["reply"];}
if(isset($_GET["post_id"])){	$post_id = $_GET["post_id"];}

$current_user = $xoouserultra->userpanel->get_user_info();

$user_id = $current_user->ID;
$user_email = $current_user->user_email;

//$login_user=$xoouserultra->login->
$user_pwd=$current_user->user_pass;
$user_mail=$current_user->user_email;
$pass="";
?>
<style>
.mx{max-width:80%;margin:0 auto !important;}
 /* Style the list */
ul.tab {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Float the list items side by side */
ul.tab li {float: left;}

/* Style the links inside the list items */
ul.tab li a {
    display: inline-block;
    color: black;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of links on hover */
ul.tab li a:hover {background-color: #ddd;}

/* Create an active/current tablink class */
ul.tab li a:focus, .active {background-color: #ccc;}

/* Style the tab content */
.tabcontent {
    
    padding: 6px 12px;
    border: 1px solid #ccc;
   
}
</style>

<div id="Privacy" class="tabcontent mx">
	<!-- <div class="commons-panel xoousersultra-shadow-borers" > -->
  	  <!-- <div id="settings" class="commons-panel-heading"> -->
	 <div id="settings" class="commons-panel-headin" style="margin:30px">
                   <form method="post" name="uultra-check-pwd" > 
			<p class="pwd f15">To enable settings to change your password/email,</p>                     
                   	 <p class="pwd f15"> Type your present Password</p>
                 	 <p><input class="pwd" onclick="stylebackE(this.id)" onkeypress="return event.charCode !=32" type="password" name="p0" id="p0" onblur="return validateP(this.id)" /><span style="color:red;" id="p0Err"></span></p>
<div id="p0_err_div" class="xoouserultra-field" style="display:none">
    <div id="p0_err" class="alert alert-danger xoouserultra-field-value"></div>
   </div>	
			<p><input type="submit"  name="check-password" id="check-password" onclick="return validateP(this.id)" class="xoouserultra-button pwd" value="Submit Password" /></p>

<?php 
if(isset($_POST['check-password']))
{
$pass=$_POST['p0'];
if($pass!='')
{
	if ( $current_user && wp_check_password( $pass, $user_pwd, $user_id) )   
		{//echo "<p style='text-align:center; font-size:15px;'><strong>You can change settings now</strong></p>";
		echo "<style>#view{display:block !important;}.pwd{display:none !important;}</style>"; 
			//echo "<script>	 swal('Settings Unlocked', 'You can change Settings Now!', 'success'); 	</script>";
			echo '<script></script>';		
			}
	else
   		{
echo '<script></script>';		
echo "<script>document.getElementById('p0_err_div').style.display='block';document.getElementById('p0_err').innerHTML='  Incorrect Password!';</script>";

echo '<script>// Get all elements with class="tablinks" and remove the class "active"
    
//document.getElementById("PS").setAttribute("class","tablinks active");
//document.getElementById("Privacy").style.display="block";


</script>';
		//echo "<script>		 swal('Invalid Password!'); </script>";
			//echo "<script>window.loaction.href='#settings'</script>";
		}
}
}
?></form>
                     </div>
			<div id="pwd_success" style="display:none" class="xoouserultra-success xoouserultra-signin-noti-block">
								</div>
			<div id="pwd_error" style="display:none" class="xoouserultra-errors xoouserultra-signin-noti-block">
								</div>
			<div id="email_success" style="display:none" class="xoouserultra-success xoouserultra-signin-noti-block">
								</div>
			<div id="email_error" style="display:none" class="xoouserultra-errors xoouserultra-signin-noti-block">
								</div>			 
			<div id="view" class="commons-panel-content" style="display:none">
                       <h2> <?php  _e('Update Password','xoousers');?>  </h2>                     
                     
                       <form method="post" name="uultra-close-account" >
			
                       <p class="f15"><?php  _e('Type your New Password','xoousers');?></p>
                 			 <p><input onclick="stylebackE(this.id)" onkeypress="return event.charCode !=32" type="password" name="p1" id="p1" onblur="return validateP(this.id)" /></p><p id="pass_pattern" style="font-size:13px">    ( * Must contain 8-16 characters ) </p>
                            <div id="p1_err_div" class="xoouserultra-field" style="display:none">
    <div id="p1_err" class="alert alert-danger xoouserultra-field-value"></div>
   </div>
                             <p class="f15"><?php  _e('Re-type your New Password','xoousers');?></p>
                 			 <p><input onclick="stylebackE(this.id)" onkeypress="return event.charCode !=32" type="password"  name="p2" id="p2" onblur="return validateP(this.id)" /></p>
                            <div id="p2_err_div" class="xoouserultra-field" style="display:none">
    <div id="p2_err" class="alert alert-danger xoouserultra-field-value"></div>
   </div>
                         <p><input type="submit" name="xoouserultra-backenedb-eset-password" id="xoouserultra-backenedb-eset-password1" class="xoouserultra-button" onclick="return validateP(this.id)"  value="<?php  _e('UPDATE PASSWORD','xoousers');?>" /></p>

                         
                         <p id="uultra-p-reset-msg"></p>
               		  </form>
   <?php 
if(isset($_POST['xoouserultra-backenedb-eset-password']))
{
$password1=$_POST['p1'];
$password2=$_POST['p2'];
if($password1=="")
{
echo "<style>#pwd_error{display:block;}</style>";
echo "<style>#pwd_success{display:none;}</style>";
echo "<script>    

document.getElementById('pwd_error').innerHTML='Please Enter Password..';

jQuery('.xoouserultra-signin-noti-block').slideDown();
setTimeout('hidde_noti(\"pwd_error\")', 5000)	;</script>";
echo "<style>#view{display:block !important;}.pwd{display:none !important;}</style>"; 
}
elseif(!preg_match("/^.{8,16}$/", $password1))
{
echo "<style>#pwd_error{display:block;}</style>";
echo "<style>#pwd_success{display:none;}</style>";
echo "<script>    

document.getElementById('pwd_error').innerHTML='Password must contain 8-16 characters';

jQuery('.xoouserultra-signin-noti-block').slideDown();
setTimeout('hidde_noti(\"pwd_error\")', 5000)	;</script>";
echo "<style>#view{display:block !important;}.pwd{display:none !important;}</style>"; 
}
elseif($password2=="")
{
echo "<style>#pwd_error{display:block;}</style>";
echo "<style>#pwd_success{display:none;}</style>";
echo "<script>    

document.getElementById('pwd_error').innerHTML='Re-type password..';

jQuery('.xoouserultra-signin-noti-block').slideDown();
setTimeout('hidde_noti(\"pwd_error\")', 5000)	;</script>";
echo "<style>#view{display:block !important;}.pwd{display:none !important;}</style>"; 
}
elseif($password1!=$password2)
{
echo "<style>#pwd_error{display:block;}</style>";
echo "<style>#pwd_success{display:none;}</style>";
echo "<script>    

document.getElementById('pwd_error').innerHTML='Passwords did not match..';

jQuery('.xoouserultra-signin-noti-block').slideDown();
setTimeout('hidde_noti(\"pwd_error\")', 5000)	;</script>";
echo "<style>#view{display:block !important;}.pwd{display:none !important;}</style>"; 
}
else
{
$passok = wp_update_user( array( 'ID' => $user_id, 'user_pass' => $password1 ) );
if($passok>0)
{
//add_filter( 'send_password_change_email', '__return_false' );

$user_now=wp_get_current_user();


//function my_pwd_change_mail

$displayname=$user_now->display_name;
//$user_pass=$user_now->user_pass;
$user_pass=$password1;
$login_link=site_url()."/login/";
$user_mail=$user_now->user_email;

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: Admin - QezyPlay <admin@qezyplay.com>' . "\r\n";

$subjectUser="Password Changed at Qezyplay";
//$subjectAdmin="User Contacted at Qezyplay";

$bodyUser="
<p>Hi, $displayname</p>

<p>Your Password is changed at QezyPlay. <br />
Your New Password: $user_pass </p>
<p>You can login to your account here: <br />
$login_link</p>

<p>If you did not change this, please contact the Admin</p>

<p>Regards, <br />
QezyPlay</p>

";

//$admin_mail=get_option("admin_email");
mail($user_mail,$subjectUser,print_r($bodyUser,true),$headers);
//mail("siddish.gollapelli@ideabytes.com",$subjectUser,print_r($bodyUser,true),$headers);


echo "<style>#pwd_success{display:block;}</style>";
echo "<style>#pwd_error{display:none;}</style>";
echo "<script>

document.getElementById('pwd_success').innerHTML='Password Updated!';
jQuery('.xoouserultra-signin-noti-block').slideDown();
setTimeout('hidde_noti(\"pwd_success\")', 5000)	;/*swal('Password Updated!', 'success');*/</script>";
		
echo "<style>#view{display:block !important;}.pwd{display:none !important;}</style>"; 
}
else
{
echo "<style>#pwd_error{display:block;}</style>";
echo "<style>#pwd_success{display:none;}</style>";
echo "<script>    

document.getElementById('pwd_error').innerHTML='Password Update Failed! Try Again..';
/*swal('Password Update Failed!', 'error');*/
jQuery('.xoouserultra-signin-noti-block').slideDown();
setTimeout('hidde_noti(\"pwd_error\")', 5000)	;</script>";
echo "<style>#view{display:block !important;}.pwd{display:none !important;}</style>"; 
}


}

}
?>                   

                       <h2> <?php  _e('Update Email','xoousers');?>  </h2> 
                                           
                     
                       <form method="post" name="uultra-change-email" >
                       <p class="f15"><?php  _e('Type your New Email','xoousers');?></p>
                 			 <p><input onclick="stylebackE(this.id)" onkeypress="return event.charCode !=32" type="text" name="email" onblur="return validateP(this.id)" id="email" value="<?php echo $user_email?>" /></p>
                        <div id="email_err_div" class="xoouserultra-field" style="display:none">
    <div id="email_err" class="alert alert-danger xoouserultra-field-value"></div>
   </div>
                         <p><input type="submit" name="xoouserultra-backenedb-update-email" id="xoouserultra-backenedb-update-email1" class="xoouserultra-button" onclick="return validateP(this.id)" value="<?php  _e('UPDATE EMAIL','xoousers');?>" /></p>
                         
                         <p id="uultra-p-changeemail-msg"></p>
<?php 
if(isset($_POST['email']))
{
$mail=$_POST['email'];
//echo "<script>alert('form:".$mail."');</script>";
//echo "<script>alert('db:".$user_mail."');</script>";
//echo "<script>alert('id:".$user_id."');</script>";
if ( $mail == "" ) {
echo "<style>#email_error{display:block;}</style>";
echo "<style>#email_success{display:none;}</style>";
echo "<script>    

document.getElementById('email_error').innerHTML='Please Enter E-mail..';
jQuery('.xoouserultra-signin-noti-block').slideDown();
setTimeout('hidde_noti(\"email_error\")', 5000)	;</script>";
/*	echo "<script>
 swal('Settings Error', 'E-mail didnt change as you entered the same email..', 'error');
			</script>"; */

	echo "<style>#view{display:block !important;}.pwd{display:none !important;}</style>"; 

}
elseif(!preg_match("/^[a-zA-Z0-9.!#$%&Â’*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+[\.].[a-zA-Z0-9]+(?:\.[a-zA-Z0-9-]+)*$/", $mail)) {
echo "<style>#email_error{display:block;}</style>";
echo "<style>#email_success{display:none;}</style>";
echo "<script>    

document.getElementById('email_error').innerHTML='Please Enter Valid Email-id..';
jQuery('.xoouserultra-signin-noti-block').slideDown();
setTimeout('hidde_noti(\"email_error\")', 5000)	;</script>";
/*	echo "<script>
 swal('Settings Error', 'E-mail didnt change as you entered the same email..', 'error');
			</script>"; */

	echo "<style>#view{display:block !important;}.pwd{display:none !important;}</style>"; 

}
elseif ( $mail == $user_email ) {//echo "<script>alert('db:".$user_email."');</script>";
echo "<style>#email_error{display:block;}</style>";
echo "<style>#email_success{display:none;}</style>";
echo "<script>    

document.getElementById('email_error').innerHTML='E-mail didnt change as you entered the same(old) email..';
jQuery('.xoouserultra-signin-noti-block').slideDown();
setTimeout('hidde_noti(\"email_error\")', 5000)	;</script>";
/*	echo "<script>
 swal('Settings Error', 'E-mail didnt change as you entered the same email..', 'error');
			</script>"; */

	echo "<style>#view{display:block !important;}.pwd{display:none !important;}</style>"; 

}
elseif(email_exists($mail))
{
echo "<style>#email_error{display:block;}</style>";
echo "<style>#email_success{display:none;}</style>";
echo "<script>    

document.getElementById('email_error').innerHTML='E-mail already taken by someone. Choose a different email-id ';
jQuery('.xoouserultra-signin-noti-block').slideDown();
setTimeout('hidde_noti(\"email_error\")', 5000)	;</script>";

/* echo "<script>swal('Settings Error', 'E-mail already exists. Choose different one', 'error');
			</script>"; */
echo "<style>#view{display:block !important;}.pwd{display:none !important;}</style>"; 
}
else
{
//$updated = update_user_meta($user_id,'user_email',$mail);
$ok = wp_update_user( array( 'ID' => $user_id, 'user_email' => $mail ) );
if($ok>0)
{
//echo "<script>alert('ok:".$mail."');</script>";
}
	if($ok>0)
		{
//add_filter( 'send_email_change_email', '__return_false' );


$user_now=wp_get_current_user();



//mail change mail function

$displayname=$user_now->display_name;
$login_link=site_url()."/login/";
//$user_mail=$user_now->user_email;

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: Admin - QezyPlay <admin@qezyplay.com>' . "\r\n";

$subjectUser="Email Changed at Qezyplay";
//$subjectAdmin="User Contacted at Qezyplay";

$bodyUser="
<p>Hi, $displayname</p>

<p>Your Email is updated from $user_email to $mail at QezyPlay. </p>
<p>You can login to your account here:<br />
$login_link</p>

<p>If you did not change this, please contact the Admin</p>

<p>Regards,<br />
QezyPlay</p>

";

//$admin_mail=get_option("admin_email");
mail($user_mail,$subjectUser,print_r($bodyUser,true),$headers);//old  mail
//mail($mail,$subjectUser,print_r($bodyUser,true),$headers);//new mail
//mail("siddish.gollapelli@ideabytes.com",$subjectUser,print_r($bodyUser,true),$headers);


		echo "<style>#email_success{display:block;}</style>";
echo "<style>#email_error{display:none;}</style>";
/*echo "<script>    
document.getElementById('PS').setAttribute('class','tablinks active');
document.getElementById('Privacy').style.display='block';
document.getElementById('GP').setAttribute('class','tablinks');
document.getElementById('General').style.display='none';</script>";*/
echo "<script>
document.getElementById('email_success').innerHTML='E-mail Updated successfully.. ';
document.getElementById('email').value='".$mail."';
jQuery('.xoouserultra-signin-noti-block').slideDown();
setTimeout('hidde_noti(\"email_success\")', 5000)	;</script>";


		/* echo "<script>swal('Settings Update', 'E-mail Updated successfully..', 'success');
						</script>"; */
			
			echo "<style>#view{display:block !important;}.pwd{display:none !important;}</style>"; 
		}
	else{
		/* echo "<script>swal('Settings Update', 'E-mail Update Failed!', 'error');
					</script>"; */
		echo "<style>#email_error{display:block;}</style>";
echo "<style>#email_success{display:none;}</style>";
echo "<script>    

document.getElementById('email_error').innerHTML='E-mail Update Failed. Try Again.. ';
jQuery('.xoouserultra-signin-noti-block').slideDown();
setTimeout('hidde_noti(\"email_error\")', 5000)	;</script>";
	echo "<style>#view{display:block !important;}.pwd{display:none !important;}</style>"; 
		}
}
}
?>
               		  </form>
                                      
                     </div>
                                                               
                </div>

<script>



function stylebackE(id)
{
var id=id;
if(id=="p0")
{
document.getElementById("p0_err_div").style.display="none";
document.getElementById("p0").style.border="1px solid black";
document.getElementById("p0").style.boxShadow="0px 0px 0px black";
}
if(id=="p1" || id=="p2")
{
document.getElementById("p1_err_div").style.display="none";
document.getElementById("p1").style.border="1px solid black";
document.getElementById("p1").style.boxShadow="0px 0px 0px black";
document.getElementById("pass_pattern").setAttribute("class","");
document.getElementById("p2_err_div").style.display="none";
document.getElementById("p2").style.border="1px solid black";
document.getElementById("p2").style.boxShadow="0px 0px 0px black";
}
if(id=="email")
{
document.getElementById("email_err_div").style.display="none";
document.getElementById("email").style.border="1px solid black";
document.getElementById("email").style.boxShadow="0px 0px 0px black";
}

}

function validateP(id)
{
/*var target = event.target || event.srcElement;*/
var id = id;
/* alert(id); */


if(id=="check-password")
{
var val1=document.getElementById("p0");
if(val1.value==""){
/* document.getElementById("p0").setCustomValidity("Please enter password"); */
val1.style.border="1px solid rgb(210, 8, 8) ";
val1.style.boxShadow="0px 0px 10px rgb(210, 8, 8) ";
val1.style.outline="medium none"; 
document.getElementById("p0_err_div").style.display="block";
document.getElementById("p0_err").innerHTML='Please enter Password';
return false;}
else {
return true;}

}

if(id=="p0")
{
var val1=document.getElementById("p0");
if(val1.value==""){val1.style.border="1px solid rgb(210, 8, 8) ";
val1.style.boxShadow="0px 0px 10px rgb(210, 8, 8)";
val1.style.outline="medium none"; 
return false;}
else { 
return true;}
}

if(id=="xoouserultra-backenedb-eset-password1")
{

var val1=document.getElementById("p1");
var check=validatePass(val1.value);
if(val1.value==""){
/* document.getElementById("p1").setCustomValidity("Please enter password"); */
val1.style.border="1px solid rgb(210, 8, 8) ";
val1.style.boxShadow="0px 0px 10px rgb(210, 8, 8) ";
val1.style.outline="medium none"; 
document.getElementById("p1_err_div").style.display="block";
document.getElementById("p1_err").innerHTML='Please enter Password';
return false;}
if(!check && (val1.value!="")){
/* document.getElementById("p1").setCustomValidity("Must contain min of 8 characters with 1 uppercaseLetter,1 number,1 special char"); */
val1.style.border="1px solid rgb(210, 8, 8) ";
val1.style.boxShadow="0px 0px 10px rgb(210, 8, 8) ";
val1.style.outline="medium none"; 
document.getElementById("pass_pattern").setAttribute("class","xoouserultra-errors");
/*document.getElementById("p1_err_div").style.display="block";
document.getElementById("p1_err").innerHTML='Must contain min of 8 characters with 1 uppercaseLetter,1 number,1 special char';*/
 return false;}
else { }

var val2=document.getElementById("p2");
var check=(val1.value==val2.value);
if(val2.value==""){
/* document.getElementById("p2").setCustomValidity("Please re-type password"); */
val2.style.border="1px solid rgb(210, 8, 8) ";
val2.style.boxShadow="0px 0px 10px rgb(210, 8, 8) ";
val2.style.outline="medium none"; 
document.getElementById("p2_err_div").style.display="block";
document.getElementById("p2_err").innerHTML='Please re-type password';
return false;}
if(!check && (val2.value!="")){
/* document.getElementById("p2").setCustomValidity("Passwords did not match"); */
val2.style.border="1px solid rgb(210, 8, 8) ";
val2.style.boxShadow="0px 0px 10px rgb(210, 8, 8) ";
val2.style.outline="medium none"; 
document.getElementById("p2_err_div").style.display="block";
document.getElementById("p2_err").innerHTML='Passwords did not match';
return false;}
else {
 return true;}
}

if(id=="p1")
{
var val1=document.getElementById("p1");
var check=validatePass(val1.value);
if(val1.value==""){
/* document.getElementById("p1").setCustomValidity("Please enter password"); */
val1.style.border="1px solid rgb(210, 8, 8) ";
val1.style.boxShadow="0px 0px 10px rgb(210, 8, 8) ";
val1.style.outline="medium none"; 
return false;}
if(!check && (val1.value!="")){
/* document.getElementById("p1").setCustomValidity("Must contain min of 8 characters with 1 uppercaseLetter,1 number,1 special char"); */
val1.style.border="1px solid rgb(210, 8, 8) ";
val1.style.boxShadow="0px 0px 10px rgb(210, 8, 8) ";
val1.style.outline="medium none"; 
 return false;}
else { return true;}
}
if(id=="p2")
{
var val1=document.getElementById("p1");
var val2=document.getElementById("p2");
var check=(val1.value==val2.value);
if(val2.value==""){
/* document.getElementById("p2").setCustomValidity("Please re-type password"); */
val2.style.border="1px solid rgb(210, 8, 8) ";
val2.style.boxShadow="0px 0px 10px rgb(210, 8, 8) ";
val2.style.outline="medium none"; 
return false;}
if(!check && (val2.value!="")){
/* document.getElementById("p2").setCustomValidity("Passwords did not match"); */
val2.style.border="1px solid rgb(210, 8, 8) ";
val2.style.boxShadow="0px 0px 10px rgb(210, 8, 8) ";
val2.style.outline="medium none"; 
return false;}
else {
 return true;}
}

if(id=="email")
{
var val=document.getElementById("email");
var check=validateEmail(val.value);
if(val.value==""){
/* document.getElementById("email").setCustomValidity("Please enter email"); */
val.style.border="1px solid rgb(210, 8, 8) ";
val.style.boxShadow="0px 0px 10px rgb(210, 8, 8) ";
val.style.outline="medium none"; 
return false;}
if(!check && (val.value!="")){
/* document.getElementById("email").setCustomValidity("Please enter valid email"); */
val.style.border="1px solid rgb(210, 8, 8) ";
val.style.boxShadow="0px 0px 10px rgb(210, 8, 8) ";
val.style.outline="medium none"; 
return false;} 
else {return true;}
}

if(id=="xoouserultra-backenedb-update-email1")
{
var val=document.getElementById("email");
var check=validateEmail(val.value);
if(val.value==""){
/* document.getElementById("email").setCustomValidity("Please enter email"); */
val.style.border="1px solid rgb(210, 8, 8) ";
val.style.boxShadow="0px 0px 10px rgb(210, 8, 8) ";
val.style.outline="medium none"; 
document.getElementById("email_err_div").style.display="block";
document.getElementById("email_err").innerHTML='Please enter email';
return false;}
if(!check && (val.value!="")){
/* document.getElementById("email").setCustomValidity("Please enter valid email"); */
val.style.border="1px solid rgb(210, 8, 8) ";
val.style.boxShadow="0px 0px 10px rgb(210, 8, 8) ";
val.style.outline="medium none"; 
document.getElementById("email_err_div").style.display="block";
document.getElementById("email_err").innerHTML='Please enter valid email';
return false;} 
else {return true;}
}

}

function validateEmail(email) {
   var re=/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9](?:\.[a-zA-Z0-9-]+)*$/;
    return re.test(email);
}

function validatePass(pass) {
//var re=/^(?=.*\d)(?=.*[A-Z])(?=.*[!@#$%^&*()?])[0-9A-Za-z!@#$%^&*()?]{8,50}$/;
var re=/^.{8,16}$/;
return re.test(pass);
}


document.getElementById("p0").addEventListener("click", function(event) {
		 document.getElementById("p0Err").innerHTML=" ";
    });
document.getElementById("p1").addEventListener("click", function(event) {
		document.getElementById("uultra-p-reset-msg").style.display="none";
    });
document.getElementById("p2").addEventListener("click", function(event) {
		document.getElementById("uultra-p-reset-msg").style.display="none";
    });
document.getElementById("email").addEventListener("click", function(event) {
		document.getElementById("uultra-p-changeemail-msg").style.display="none";
    });

if($.trim($("#email_error").html())=='')
{
$('#email_error').hide();
}
if($.trim($("#email_success").html())=='')
{
$('#email_success').hide();
}
if($.trim($("#pwd_error").html())=='')
{
$('#pwd_error').hide();
}
if($.trim($("#pwd_success").html())=='')
{
$('#pwd_success').hide();
}

if($.trim($("#update_msg").html())=='')
{
$('#update_msg').hide();
}


</script>

<?php } ?>

<?php


