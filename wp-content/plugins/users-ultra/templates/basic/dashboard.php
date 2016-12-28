<?php
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


if(isset($_POST["xoouserultra-update"])){
echo'
<script>document.getElementById("GP").setAttribute("class","tablinks active");
document.getElementById("General").style.display="block";</script>';
}

$current_user = $xoouserultra->userpanel->get_user_info();

$user_id = $current_user->ID;
$user_email = $current_user->user_email;

//$login_user=$xoouserultra->login->
$user_pwd=$current_user->user_pass;
$user_mail=$current_user->user_email;
$pass="";

$howmany = 5;

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
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}
</style>
<!-- <div class="usersultra-dahsboard-cont"> -->
<style>textarea {
    resize: none;
}
.xoouserultra-field-type i {
    display: none !important;
}</style>
 <ul class="tab mx">
  <li><a id="GP" href="#" class="tablinks active" onclick="editProfile(event, 'General')">General Profile</a></li>
 
 </ul>
  <!-- </div> setTimeout('hidde_noti(\"update_msg\")', 5000)	;-->
<div id="General" class="tabcontent mx" style="display:block">
 
<!-- <div class="commons-panel xoousersultra-shadow-borers" > -->
                      <div class="commons-panel-heading">
                          <div id="update_msg" class="xoouserultra-success xoouserultra-signin-noti-block">
			<?php if($_SESSION['update_msg']!="") {
				echo "<style>#update_msg{display:block;}</style>
					<script>jQuery('.xoouserultra-signin-noti-block').slideDown();
						</script>";
				echo $_SESSION['update_msg'];
				unset($_SESSION['update_msg']);unset($_SESSION['pic']);} 
				
				else echo "<style>#update_msg{display:none !important;}</style>";
			?>
			</div>
			 <div id="error_msg" class="xoouserultra-errors xoouserultra-signin-noti-block">
			<?php if($_SESSION['errMsg']!=""){ 
				echo "<style>#update_msg{display:block;}</style>
					<script>jQuery('.xoouserultra-signin-noti-block').slideDown();
						</script>";
				echo $_SESSION['errMsg'];
				unset($_SESSION['errMsg']);} 
				
				else echo "<style>#error_msg{display:none !important;}</style>";
			?>
			</div>
                   
	 </div>
                     
                      <div class="commons-panel-content">
                        

                       <?php echo $xoouserultra->userpanel->edit_profile_form();?>
		                                  
                      </div>
                     
                     
          </div>
<!-- </div> -->

<?php //Privacy Settings ?>
<style>
.f15{font-size:15px;}
</style>

<!-- </div>  -->

<div id="editprof_btn" style="margin: 0 auto; width: 25%;"><button onclick="location.href='<?php echo home_url() ?>/myprofile'">Back to Profile</button></div>
<script>
function editProfile(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the link that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
</script>
      


 
<script>



function styleback(id)
{

hidde_noti("update_msg");
hidde_noti("error_msg")	;

if(id=="phone")
{
document.getElementById("phone_err_div").style.display="none";
document.getElementById("phone").style.border="1px solid black";
document.getElementById("phone").style.boxShadow="0px 0px 0px black";

}
}





function validateEmail(email) {
   var re=/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9](?:\.[a-zA-Z0-9-]+)*$/;
    return re.test(email);
}

function validatePass(pass) {
var re=/^(?=.*\d)(?=.*[A-Z])(?=.*[!@#$%^&*()?])[0-9A-Za-z!@#$%^&*()?]{8,50}$/;
return re.test(pass);
}



if($.trim($("#update_msg").html())=='')
{
$('#update_msg').hide();
}
if($.trim($("#error_msg").html())=='')
{
$('#error_msg').hide();
}


document.getElementById("phone").setAttribute("placeholder","Ex:+919876543210 (incl. CountryCode)");
document.getElementById("facebook").setAttribute("placeholder","www.facebook.com/your-profile-page");
document.getElementById("twitter").setAttribute("placeholder","www.twitter.com/your-account-page");
document.getElementById("googleplus").setAttribute("placeholder","plus.google.com/your-account-page");

  $('.xoouserultra-datepicker').prop('disabled', false);
$(function() {
    $('.xoouserultra-datepicker').datepicker();
    $('.xoouserultra-datepicker').keyup(function()  { this.value = this.value.substring(0,this.value.length -1); });
var date = $('.xoouserultra-datepicker').datepicker({ dateFormat: 'MM dd, yyyy' }).val();
});

</script>
