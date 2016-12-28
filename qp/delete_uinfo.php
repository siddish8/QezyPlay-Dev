<?php
include("db-config.php");
include("admin-include.php");
include("function_common.php");


$r1=get_all("SELECT ID from wp_users");

$i=1;
foreach($r1 as $r)
{

echo $i;
if($i>3)
exit;
echo $r['ID'];
//SELECT meta_value FROM tradmin_newqezy.wp_usermeta where user_id=3 and meta_key='uultra_user_registered_ip";
$ip= get_var("SELECT meta_value from wp_usermeta where meta_key='uultra_user_registered_ip' and user_id=".$r['ID']." ");
echo "ip:".$ip;
$sql="INSERT INTO wp_users(user_registered_ip) VALUES('".$ip."') where ID=".$r['ID']." ";
$stmt1 = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt1->execute();
$i=$i+1;

}

echo "mm";



exit;

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

echo "<div><form method='post'>
Enter Username : <input type='text' name='uname' id='uname' /> <input type='submit' name='get_uid' value='Get UserId' />  <span >".$usid."</span><br /><br />
or Enter UserId: <input type='text' name='uid' id='uid' /> <input type='submit' name='get_uname' value='Get Username' />  <span >".$usname."</span><br /><br />
 <input type='hidden' name='userid' id='userid' value='".$f."' />
<input type='hidden' name='username' id='username' value='".$g."' />

<input type='submit' name='sub' value='Subscription Delete' /> <br /><br />
<input type='submit' name='subX' value='Agent Portal Req&Rem Delete' /> <br /><br />
<input type='submit' name='reg' value='Registration Delete' /><br /><br />
<input type='submit' name='comp' value='Complete info Delete' />
</form>";

echo"<span>".$msg."</span></div>";


if(isset($_POST['execQ']))
{

$sql=$_POST['query'];
$qt=$_POST['query_type'];

$msg="<br>";
//echo $qt;
switch($qt)
{

case 1:
	//echo "insert";
	$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->execute();
	$res=$stmt->fetchAll(PDO::FETCH_ASSOC);
	if($res)
	{
	$msg.="Success...<br> Results: <br>";
	foreach($res as $r)
	{
		foreach($r as $key=>$value)
		$msg.=$key." : " .$r[$key]."<br>";
	}
	//print_r($res);
	}
	
	$msg.="<br> Executed Query: ".$sql."<br>";

	break;
case 2:
	$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$res=$stmt->execute();
	if($res)
	{
	$msg.="Success...<br>";
	$msg.="Inserted:".$dbcon->lastInsertId();
		
	
	}


	$msg.="<br> Executed Query: ".$sql."<br>";
	break;
case 3:
	$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$res=$stmt->execute();
	$res1=$stmt->fetchAll();
	
	if($res)
	{
	$msg.="Success...<br>";
	
	$msg.="U.p.d.a.t.e.d.";
	}
	
	$msg.="<br> Executed Query: ".$sql."<br>";
	break;
case 4:	
	
	$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$res=$stmt->execute();
	if($res)
	{
	$msg.="Success...<br>";
	$msg.="D.e.l.t.e.d.";
	}


	$msg.="<br> Executed Query: ".$sql."<br>";
	break;
default:

	break;


}

}
echo "<div>
<form method='post'>
Enter Query: <input required type='textarea' name='query' id='query' /><br> <br>
Select Query Type: <select name='query_type' required><option id='0' value=''>CHOOSE ANY</option><option id='1' value='1'>SELECT</option><option id='2' value='2'>INSERT</option><option id='3' value='3'>UPDATE</option><option id='4' value='4'>DELETE</option></select><br><br>
<input type='submit' name='execQ' value='Execute Query' /> <br /><br />
</form>
<div>".$msg."</div>
</div>";



?>
