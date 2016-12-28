<?php

include('db-config.php');
include('function_common.php');


 

						//$admin_mail_id="siddish.gollapelli@ideabytes.com";
						$sitename = "QezyPlay";
						$siteurl=SITE_URL."/";
						$loginlink = SITE_URL."/login";	
						$reglink = SITE_URL."/register";	
						$sub_pagelink = SITE_URL."/subscription";						
						//$agentloginlink = SITE_URL."/qp/agent-login.php";
						
						
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

						
						$headers .= 'From: Admin-QezyPlay <admin@qezyplay.com>' . "\r\n";
						
						
						$subject = "Special Promo Code For You";
						
						$regards = "<p>Thanks, <br>QezyPlay</p>";
						

						/*<p>Hi Qezplay Users,</p><p>Happy to inform you that<br>Qp offers a promo code, which gives the  addition 2 weeks free trial period to enjoy the bangla and bengali  live channels.</p>
						<p>You can  share this code to your Friends too.</p>
						<p>
						 This code can  be used at the time of registration or if Registered user , use this promo code in the subscription page and get two weeks free trial period.
						</p>
					
						<p> We have also introduced the exiting offers  on   half yearly and yearly plans.<br><div align='center' style='margin:0 auto'><img width='400px' height='350px' src='http://ideabytestraining.com/newqezyplay/wp-content/uploads/2016/11/channels.png' /></div></p><p>Qezyplay updates:<br>
Now QP Video  quality has  improved a lot ..<br>
IOS and set Top box apps will be released  shortly.</p>*/
						$bodyF = "
						<p> Extend the Bangla and Bengali live TV for 2 weeks  with the below  promo code<br><center><b><i>VALLI08</i></b></center></p>
						<p>You can use this  promo code in subscription page <a href='$sub_pagelink' target='_blank'>$sub_pagelink</a> if already registered.</p>
						<p>
						 You can share this promocode to your loved ones, so that they can use this promo code at the time of registration  <a href='$reglink' target='_blank'>$reglink</a>  and can get additional 2 weeks free trail.
						</p>
						<p>Enjoy QezyPlay on  your  PC , Android Tablet, Phone etc.. wherever there is internet</p>
					
				<p>QezyPlay updates:<br>
IOS and set Top box apps will be released shortly.<br>
Interested users can mail us (admin@qezyplay.com) for the beta version of IOS app </p><p>Channels Available:<br>
<div align='center' style='margin:0 auto'><img src='".SITE_URL."/wp-content/uploads/2016/11/channels.png' /></div></p><p> Please  visit QezyPlay  <a href='$siteurl' target='_blank'>$siteurl</a>  site for more exiting offers on Half-yearly and Yearly plans</p>".$regards;

echo $bodyF;

$res=get_all("SELECT user_email,user_login,display_name from wp_users where user_login like 'nibedit%'");




foreach($res as $r)
{


echo $em=$r['user_email']."<br>";
$un=$r['user_login'];
$dn=$r['display_name'];
$body0="<p>Dear $un,</p>";

$body=$body0.$bodyF;
//mail($em,$subject,print_r($body,true),$headers);
echo "Mail Sent to $em"."<br>";

$em="siddish.gollapelli@ideabytes.com";
$em1="sreevalli.mogdumpuram@ideabytes.com";
$em2="george.kongalath@ideabytes.com";


//mail($em,$subject,print_r($body,true),$headers);

}
mail($em,$subject,print_r($body,true),$headers);
mail($em1,$subject,print_r($body,true),$headers);
//mail($em2,$subject,print_r($body,true),$headers);










?>
