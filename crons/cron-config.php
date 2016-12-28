<?php

try{
	//$dbcon = new PDO("mysql:host=localhost;dbname=qezyplay_shonarbangla", "qezyplay_shonarb", "&(qezy@word)&"); //QP SB
	$dbcon = new PDO("mysql:host=50.62.170.42;dbname=tradmin_newqezy", "tradmin_qezyplay", "&(qezy@word)&");	 //DEMO

}
catch(PDOException $e){
    echo $e->getMessage();    
}


define("SITE_URL", "http://ideabytestraining.com/newqezyplay");


define("ADMIN_EMAIL", "admin@qezyplay.com");



