<?php
Class ServiceData extends DBCon{
		
	function validatekAccessToken($user_id, $access_token){
		
			$info = validateAuthToken($access_token);
			
			if(is_array($info) && count($info) > 2){

				if($info['uid'] == $user_id)					
					return $info['channelinfo'];					
				else
					return "Invalid token for this user"; 

			}else{
				return $info;
			}
		
	}
		
}
