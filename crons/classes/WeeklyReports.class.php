<?php

class WeeklyReports
{
	function citycodeHits($countryCode){
		global $dbcon;
		global $channel_id;
		global $friendlyIpsCond;
		global $friendlyWebsites;
		global $date1;
		global $date2;
		global $stmt3;			
		global $clicksInfo;		
		   			
		$sql = "SELECT vi.city,vi.state,count(vi.id) AS clicks FROM visitors_info as vi WHERE vi.country_code!='' AND date(vi.start_datetime) BETWEEN '".$date1."' AND '".$date2."'".$friendlyIpsCond.$friendlyWebsites." AND vi.country_code = '".$countryCode."' AND page_id = ".$channel_id." GROUP BY vi.city ORDER BY clicks DESC";		
		
		try {
			$stmt3 = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt3->execute();			
		}
		catch (PDOException $e)
		{
			print $e->getMessage();
		}
		
		$country_city_code_display_array = array();
		if($stmt3->rowCount() > 0)
		{
			$analyticsData = $stmt3->fetchALL(PDO::FETCH_ASSOC);
			
			$clicksInfo = array();					
			foreach($analyticsData as $aData)
			{
				$info_1 = array();				
				$info_1['city'] = $aData['city'];				
				$info_1['clicks'] = $aData['clicks'];
				$country_code_city_display_array[] = $info_1;
				
				
			}
		}		
		return $country_code_city_display_array;
	}

	function citycodeDuration($countryCode){
		global $dbcon;
		global $channel_id;
		global $friendlyIpsCond;
		global $friendlyWebsites;
		global $date1;
		global $date2;
		global $stmt4;
		
		global $durationInfo;		
		   			
		$sql = "SELECT vi.city,vi.state,sum(vi.duration) AS duration FROM visitors_info as vi WHERE vi.country_code!='' AND vi.play = 1 AND date(vi.start_datetime) BETWEEN '".$date1."' AND '".$date2."'".$friendlyIpsCond.$friendlyWebsites." AND vi.country_code = '".$countryCode."' AND page_id = ".$channel_id." GROUP BY vi.city ORDER BY duration DESC";		
		
		try {
			$stmt4 = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt4->execute();			
		}
		catch (PDOException $e)
		{
			print $e->getMessage();
		}
		
		$country_city_code_display_array = array();
		if($stmt4->rowCount() > 0)
		{
			$analyticsData = $stmt4->fetchALL(PDO::FETCH_ASSOC);
			
			$clicksInfo = array();					
			foreach($analyticsData as $aData)
			{
				$info_1 = array();				
				$info_1['city'] = $aData['city'];				
				$info_1['duration'] = ($aData['duration'] / 60);
				$info_1['duration']=round($info_1['duration'], 2, PHP_ROUND_HALF_DOWN);
				$country_code_city_display_array[] = $info_1;
				
				
			}
		}
		
		return $country_code_city_display_array;
	}
		
	
	function countrycityCodeHits()
	{
		global $dbcon;
		global $date1;
		global $date2;
		global $friendlyIpsCond;
		global $friendlyWebsites;
		global $stmt;
		global $analyticsDataHits;
		global $country_code_city_display_array_hits;
		global $countrycityInfoHits;
		global $countrycityclicksInfo;
		global $channel_id;
		
		 $sql = "SELECT vi.country_code,vi.country,count(vi.id) AS clicks FROM visitors_info as vi WHERE vi.country_code!='' AND date(vi.start_datetime) BETWEEN '".$date1."' AND '".$date2."'".$friendlyIpsCond.$friendlyWebsites." AND page_id = ".$channel_id." GROUP BY vi.country ORDER BY clicks DESC";
		try {
			$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt->execute();	
		}catch (PDOException $e){
			print $e->getMessage();
		}

		$country_code_city_display_array_hits = array();
		if($stmt->rowCount() > 0)
		{
			$analyticsDataHits = $stmt->fetchALL(PDO::FETCH_ASSOC);
			$countryInfo = array();
			$clicksInfo = array();			
			foreach($analyticsDataHits as $aData)
			{
				$info_1 = array();
				$info_1['country_code'] = $aData['country_code'];
				$info_1['country'] = $aData['country'];
				$cityInfo = self::citycodeHits($aData['country_code']);				
				$info_1['cityinfo'] = $cityInfo;	
				$info_1['clicks'] = $aData['clicks'];
				$country_code_city_display_array_hits[] = $info_1;				
				foreach($cityInfo as $city){								
					$countrycityInfoHits[] = $aData['country_code']."-".$city['city']."(".$city['clicks'].")";
					$countrycityclicksInfo[] = $city['clicks'];			
				
				}	
				
			}			
			
		}
				
	}



	function countrycityCodeDuration()
	{
		global $dbcon;
		global $date1;
		global $date2;
		global $friendlyIpsCond;
		global $friendlyWebsites;
		global $stmt1;
		global $analyticsDataDuration;
		global $country_code_city_display_array_duration;
		global $countrycityInfoDuration;
		global $countrycitydurationInfo;
		global $channel_id;
		
		$sql = "SELECT vi.country_code,vi.country,sum(vi.duration) AS duration FROM visitors_info as vi WHERE vi.country_code!='' AND vi.play = 1 AND date(vi.start_datetime) BETWEEN '".$date1."' AND '".$date2."'".$friendlyIpsCond.$friendlyWebsites." AND page_id = ".$channel_id." GROUP BY vi.country ORDER BY duration DESC";
		try {
			$stmt1 = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt1->execute();	
		}catch (PDOException $e){
			print $e->getMessage();
		}

		$country_code_city_display_array_duraton = array();
		if($stmt1->rowCount() > 0)
		{
			$analyticsDataDuration = $stmt1->fetchALL(PDO::FETCH_ASSOC);
			$countryInfo = array();
			$durationInfo = array();			
			foreach($analyticsDataDuration as $aData)
			{
				$info_1 = array();
				$info_1['country_code'] = $aData['country_code'];
				$info_1['country'] = $aData['country'];
				$cityInfo = self::citycodeDuration($aData['country_code']);				
				$info_1['cityinfo'] = $cityInfo;	
				$info_1['duration'] = ($aData['duration'] / 60);
				$info_1['duration']=round($info_1['duration'], 2, PHP_ROUND_HALF_DOWN);
				$country_code_city_display_array_duration[] = $info_1;				
				
				foreach($cityInfo as $city){								
					$countrycityInfoDuration[] = $aData['country_code']."-".$city['city']."(".$city['duration'].")";
					$countrycitydurationInfo[] = $city['duration'];			
				
				}	
				
			}			
			
		}
		
	}
}
?>
