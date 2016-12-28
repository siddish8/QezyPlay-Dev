<?php
/******************************************************************
 * Ideabytes Software India Pvt Ltd.                              *
 * 50 Jayabheri Enclave, Gachibowli, HYD                          *
 * Created Date : 25/07/2014                                      *
 * Created By : Pradeep                                           *
 * Vision : Project  - QezyTv                                     *  
 * Modified by : Pradeep     Date : 25/07/2014     Version : V1   *
 * Description : API Service model(data) page                     *
				                                                  *
 *****************************************************************/
 
Class ServiceData extends DBCon{
		
	function validatekAccessToken($user_id, $access_token){
		try {
			$info = validateAuthToken($access_token);

			if(is_array($info) && count($info) > 2){

				if($info['uid'] == $user_id)
					return $info;
				else
					return "Invalid token for this user"; 

			}else{
				return $info;
			}

		}catch (PDOException $e){
			return $e->getMessage();
		}
	}
		
}
