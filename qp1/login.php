<?php

include("db-config.php");
$username = $password = $emailError = $authError = '';

if($_SESSION['adminid'] != ""){	
	//Redirect the user to the next page
	//header('LOCATION: admin-home.php'); 
	header('Location:home.php'); 
	exit;
}


if(isset($_POST['login'])){
	
 	$email = trim($_POST['username']); $authkey = trim($_POST['password']);
	
	$sql1 = "SELECT name FROM admin_portal_users";		
	try {
		$stmt1 = $dbcon->prepare($sql1, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt1->execute();
		$result1 = $stmt1->fetchall(PDO::FETCH_ASSOC);

		$stmt1 = null;

		$match=0;
		//echo $result1;
		//print_r($result1);
		foreach($result1 as $em){
			if($em['name'] == $email){
				$match=1;
			}
		}

	}catch (PDOException $e){
		print $e->getMessage();
	}

	if($match==0){		
		$emailError = 'Entered Username does not exists';
	}else{
		$sql2 = "SELECT id,password,name,admin_level FROM admin_portal_users WHERE name = ?";		
		try {
			$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt2->execute(array($email));
			$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);

			$stmt2 = null;

			$customer_id = $result2['id'];
			$authkey_db = $result2['password'];
			$customername = $result2['name'];
			$adminlevel=$result2['admin_level'];



		}catch (PDOException $e){
			print $e->getMessage();
		}

		if($email === $email && $authkey === $authkey_db){
		
			//Dump your POST variables
			$_SESSION['adminid'] = $customer_id;
			$_SESSION['adminname'] = $customername;
			$_SESSION['adminlevel'] = $adminlevel;

			//Redirect the user to the next page
//			header('LOCATION: admin-home.php');	
			header('Location: home.php'); exit;

		}


		if($authkey !== $authkey_db)  {
			$authError = 'Please enter correct password'; 
		}

		
	}


}
?>


<!doctype html>
<html class="no-js" lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title> QEZYPLAY - AdminPortal | Login </title>
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
        <div class="auth">
            <div class="auth-container">
                <div class="card">
                    <header class="auth-header">
                        <h1 class="auth-title">
			<img class="logo1" alt="" src='../qp1/assets/images/qp_logo.png' style="width: 100px; height: 50px">
        <!--<div class="logo">
        	<span class="l l1"></span>
        	<span class="l l2"></span>
        	<span class="l l3"></span>
        	<span class="l l4"></span>
        	<span class="l l5"></span>
		     </div>-->        <center>AdminPortal</center>
      </h1> </header>
                    <div class="auth-content">
                        <p class="text-xs-center">LOGIN TO CONTINUE</p>
                        <form id="login-form" action="" method="POST" novalidate="">
                            <div class="form-group"> <label for="username">Username</label> <input type="text" class="form-control underlined" name="username" id="username" placeholder="Your UserName" required> </div>
                            <div class="form-group"> <label for="password">Password</label> <input type="password" class="form-control underlined" name="password" id="password" placeholder="Your password" required> </div>
                          <!--  <div class="form-group"> <label for="remember">
            <input class="checkbox" id="remember" type="checkbox"> 
            <span>Remember me</span>
          </label> <a href="reset.html" class="forgot-btn pull-right">Forgot password?</a> </div>-->
                            <div class="form-group"> <button type="submit" class="btn btn-block btn-primary" name='login'>Login</button> </div>
                            <!--div class="form-group">
                                <p class="text-muted text-xs-center">Do not have an account? <a href="signup.html">Sign Up!</a></p>
                            </div-->
                        </form>
                    </div>
                </div>
                <!--div class="text-xs-center">
                    <a href="index.html" class="btn btn-secondary rounded btn-sm"> <i class="fa fa-arrow-left"></i> Back to dashboard </a>
                </div-->
            </div>
        </div>
        <!-- Reference block for JS -->
        <div class="ref" id="ref">
            <div class="color-primary"></div>
            <div class="chart">
                <div class="color-primary"></div>
                <div class="color-secondary"></div>
            </div>
        </div>
        <script src="js/vendor.js"></script>
        <script src="js/app.js"></script>
    </body>

</html>
<?php 

function server_validation($error,$field)
{
$bool=true;
/*global $i;

if($i==0)
{
$bool=true;

}
else
{
$bool=false;
}

echo $i;
echo $bool;
*/
echo '

<script>

$("#'.$field.'").attr("aria-invalid","true");
$("#'.$field.'").attr("aria-describedby","'.$field.'-error");
var input = jQuery("<span>")
               .attr("id", "'.$field.'-error")
               .attr("class", "has-error").html("'.$error.'");
$("#'.$field.'").closest("div").attr("class","form-group has-error");
$("#'.$field.'").closest("div").append(input);

function chk()
{
var loginValidationSetting = {
	    rules: {
	        '.$field.': {
	            server: '.$bool.',
	            
	        }
	    },
	    messages: {
	       '.$field.': {
	            server: "'.$error.'",
	           
	        }
	    },
	    invalidHandler: function() {
			animate({
				name: "shake",
				selector: ".auth-container > .card"
			});
		}
	}
jQuery.validator.addMethod("server", function(value, element) {
  return this.optional(element) || /^http:\/\/mycorporatedomain.com/.test(value);
}, "Invalid");
//$.extend($.validator, {prototype { showLabel:function("username","no");} });
$.extend(loginValidationSetting, config.validations);

$("#login-form").load(loginValidationSetting);
// $("#login-form").validate(loginValidationSetting); 
}
</script>

';

}

if($emailError!="")
{
server_validation($emailError,"username");
$emailError=="";
}
if($authError!="")
{
server_validation($authError,"password");
$authError=="";
}

?>
