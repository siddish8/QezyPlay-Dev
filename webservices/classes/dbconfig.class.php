<?php

/******************************************************************
 * Ideabytes Software India Pvt Ltd.                              *
 * 50 Jayabheri Enclave, Gachibowli, HYD                          *
 * Created Date : 22/01/2014                                      *
 * Created By : Pradeep G                                         *
 * Vision : Project Sparkle                                       *  
 * Modified by : Pradeep G     Date : 22/01/2014    Version : V1  *
 * Description : Make DB Connnection                              *
 *****************************************************************/


 Class DBCon{
			
	//Open database connection
	public function dbConnect(){
		/* create connection */
		
		try{
			$this->db = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
		}
		catch(PDOException $e){
			echo $e->getMessage();    
		}
	}
	
	//Close database connection
	public function dbClose(){
		/* close connection */
		unset($this->db);
		$this->db = null;
	}
	
}
?>
