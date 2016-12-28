<?php

//echo "<pre>";
//print_r($_SERVER);

$a = session_id();
echo isset($_SESSION['sid']);
echo $_SESSION['sid'];
//if(empty($a))
if($_SESSION['sid']!=$a) {
 session_start();
echo "hello";

$_SESSION['sid']=$a;}
else
{
echo "SID: ".SID."<br>session_id(): ".session_id()."<br>COOKIE: ".$_COOKIE["PHPSESSID"];
}

?>
