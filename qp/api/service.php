<?php

class Service extends ServiceData{
	

	public function handleGetChannelInfo() {

		try {
			if(isset($_POST["user_id"]) && isset($_POST["access_token"]) && isset($_POST["channel_id"])){			
				
				$info = $this->validatekAccessToken($_POST["user_id"],$_POST["access_token"]); 
				$this->response($info);
				
			}else{
				$this->response('Insufficeient inputs');
			}
	
		}catch (Exception $e){
		    $this->response("Request failed"); 
		}
		
	}

}
?>
