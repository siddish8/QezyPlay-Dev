<?php

/* Includes config,db and table definitions and common functions files */	


define("DB_SERVER","50.62.170.42");	
define("DB_USER","tradmin_qezyplay");
define("DB_PASSWORD","&(qezy@word)&");
define("DB_NAME","tradmin_newqezy");

define("TOKEN_SECRET_KEY", "QezyplayIB");

define("ADMIN_EMAIL1", "pradeep.ganapathy@ideabytes.com");
define("ADMIN_EMAIL", "siddish.gollapelli@ideabytes.com");

define("SITE_URL", "http://ideabytestraining.com/newqezyplay");
define("SITE_NAME", "http://ideabytestraining.com/newqezyplay");


/* Include db & data files */
require_once("common_functions.php");
require_once("classes/dbconfig.class.php");  //DB connection
require_once("classes/servicedata.class.php");


?>
