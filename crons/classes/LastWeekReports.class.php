<?php

class LastWeekReports
{
	function lastWeekByChannelHits(){
		global $dbcon;
		global $channel_id;
		global $friendlyIpsCond;
		global $friendlyWebsites;
		
		global $date;
		global $date1;
		global $date2;
		global $stmt3;			
		//global $clicksInfo;	

		$cond="";
		$cond .= " AND DATE(start_datetime) >= '".$date1."'";
		$cond .= " AND DATE(end_datetime) <= '".$date2."'";	

		//echo $cond;
		   			
		//$sql = "SELECT vi.city,vi.state,count(vi.id) AS clicks FROM visitors_info as vi WHERE vi.country_code!='' AND date(vi.start_datetime) BETWEEN '".$date1."' AND '".$date2."'".$friendlyIpsCond.$friendlyWebsites." AND vi.country_code = '".$countryCode."' AND page_id = ".$channel_id." GROUP BY vi.city ORDER BY clicks DESC";		
		
		$sql = "SELECT count(*) as hits,a.page_id,b.post_title as channel FROM visitors_info a inner join wp_posts b on b.ID=a.page_id WHERE 1 and b.post_status='publish' and b.post_type='post'".$cond." GROUP BY a.page_id";	
	
		//$hits = $stmt1->fetchAll(PDO::FETCH_ASSOC);

		try {
			$stmt3 = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt3->execute();			
		}
		catch (PDOException $e)
		{
			print $e->getMessage();
		}
		
		$last_week_byChannel_hits = array();
		$info_channel=array();
		$info_hits=array();
		$info_channel_hits=array();

		
		if($stmt3->rowCount() > 0)
		{
			$analyticsData = $stmt3->fetchALL(PDO::FETCH_ASSOC);
			
			//$clicksInfo = array();					
			foreach($analyticsData as $aData)
			{
				//$info_1 = array();				
				 //$info_1['channel'] = $aData['channel'];				
				//$info_1['hits'] = $aData['hits'];
				array_push($info_channel,$aData['channel']);
				array_push($info_hits,$aData['hits']);	
				//array_push($info_channel_hits,$aData['channel']=>$aData['hits']);			
					$info_channel_hits[$aData['channel']]=	$aData['hits'];		
				
			}
			$last_week_byChannel_hits[0] = $info_channel;
			$last_week_byChannel_hits[1] = $info_hits;
			$last_week_byChannel_hits[2] = $info_channel_hits;
			
			
						
		}	
			
		return $last_week_byChannel_hits;
	}

	function lastWeekByChannelDuration(){
		global $dbcon;
		global $channel_id;
		global $friendlyIpsCond;
		global $friendlyWebsites;
		
		global $date;
		global $date1;
		global $date2;
		global $stmt4;
		
		global $durationInfo;		

		$cond="";
		$cond .= " AND DATE(start_datetime) >= '".$date1."'";
		$cond .= " AND DATE(end_datetime) <= '".$date2."'";
		   			
		//$sql = "SELECT vi.city,vi.state,sum(vi.duration) AS duration FROM visitors_info as vi WHERE vi.country_code!='' AND vi.play = 1 AND date(vi.start_datetime) BETWEEN '".$date1."' AND '".$date2."'".$friendlyIpsCond.$friendlyWebsites." AND vi.country_code = '".$countryCode."' AND page_id = ".$channel_id." GROUP BY vi.city ORDER BY duration DESC";		
		
		
		$sql = "SELECT sum(duration) as duration,a.page_id,b.post_title as channel FROM visitors_info a inner join wp_posts b on b.ID=a.page_id WHERE 1 AND duration > 0 AND play = 1 and b.post_status='publish' and b.post_type='post'".$cond." GROUP BY page_id";

		try {
			$stmt4 = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt4->execute();			
		}
		catch (PDOException $e)
		{
			print $e->getMessage();
		}
		
		$country_city_code_display_array = array();
		$info_channel=array();
		$info_durations=array();
		$info_channels_durations=array();

		if($stmt4->rowCount() > 0)
		{
			$analyticsData = $stmt4->fetchALL(PDO::FETCH_ASSOC);
			
			$clicksInfo = array();	
							
			foreach($analyticsData as $aData)
			{
							
				//$info_1['channel'] = $aData['channel'];				
				//$info_1['duration'] = ($aData['duration'] / 60);
				//$info_1['duration']=round($info_1['duration'], 2, PHP_ROUND_HALF_DOWN);
				//$country_code_city_display_array[] = $info_1;

				//$info_1 = array();				
				 //$info_1['channel'] = $aData['channel'];				
				//$info_1['hits'] = $aData['hits'];

				$duration=($aData['duration'] / 60);
				$duration=round($duration, 1, PHP_ROUND_HALF_DOWN);
				array_push($info_channel,$aData['channel']);
				array_push($info_durations,$duration);	
					$info_channels_durations[$aData['channel']]=$duration;
				//$last_week_byChannel_duration[]=$info_1;
							
			}
			$last_week_byChannel_duration[0] = $info_channel;
			$last_week_byChannel_duration[1] = $info_durations;
			$last_week_byChannel_duration[2] = $info_channels_durations;
		}
		
		return $last_week_byChannel_duration;
	}
		
	
	function lastWeekHits()
	{
		global $dbcon;
		global $date;
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
		
		$cond="";
		$cond .= " AND DATE(start_datetime) >= '".$date1."'";
		$cond .= " AND DATE(end_datetime) <= '".$date2."'";

		// $sql = "SELECT vi.country_code,vi.country,count(vi.id) AS clicks FROM visitors_info as vi WHERE vi.country_code!='' AND date(vi.start_datetime) BETWEEN '".$date1."' AND '".$date2."'".$friendlyIpsCond.$friendlyWebsites." AND page_id = ".$channel_id." GROUP BY vi.country ORDER BY clicks DESC";

		$sql = "SELECT count(*) as hits,start_datetime as date FROM visitors_info WHERE 1".$cond." GROUP BY start_datetime";	

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
				//$info_1['country_code'] = $aData['country_code'];
				//$info_1['country'] = $aData['country'];
				//$cityInfo = self::citycodeHits($aData['country_code']);				
				//$info_1['cityinfo'] = $cityInfo;	
				//$info_1['clicks'] = $aData['clicks'];
					$info_1['date']=$aData['date'];
					$info_1['hits']=$aData['hits'];
				//$country_code_city_display_array_hits[] = $info_1;
				$last_week_hits[]=$info_1;				
				/*foreach($cityInfo as $city){								
					$countrycityInfoHits[] = $aData['country_code']."-".$city['city']."(".$city['clicks'].")";
					$countrycityclicksInfo[] = $city['clicks'];			
				
				}*/	
				
			}			
			
		}
				
	}



	function lastWeekDuration()
	{
		global $dbcon;
		global $date;
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
		

		$cond="";
		$cond .= " AND DATE(start_datetime) >= '".$date1."'";
		$cond .= " AND DATE(end_datetime) <= '".$date2."'";

		//$sql = "SELECT vi.country_code,vi.country,sum(vi.duration) AS duration FROM visitors_info as vi WHERE vi.country_code!='' AND vi.play = 1 AND date(vi.start_datetime) BETWEEN '".$date1."' AND '".$date2."'".$friendlyIpsCond.$friendlyWebsites." AND page_id = ".$channel_id." GROUP BY vi.country ORDER BY duration DESC";

		$sql = "SELECT sum(duration) as duration,date(start_datetime) as date FROM visitors_info WHERE 1 AND duration > 0 AND play = 1".$cond." GROUP BY date(start_datetime)";	
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
				//$info_1['country_code'] = $aData['country_code'];
				//$info_1['country'] = $aData['country'];
				//$cityInfo = self::citycodeHits($aData['country_code']);				
				//$info_1['cityinfo'] = $cityInfo;	
				//$info_1['clicks'] = $aData['clicks'];
					$info_1['date']=$aData['date'];
					//$info_1['count']=$aData['count'];	
				$info_1['duration'] = ($aData['duration'] / 60);
				$info_1['duration']=round($info_1['duration'], 1, PHP_ROUND_HALF_DOWN);
				//$country_code_city_display_array_duration[] = $info_1;				
				$last_week_duration[]=$info_1;
				/*foreach($cityInfo as $city){								
					$countrycityInfoDuration[] = $aData['country_code']."-".$city['city']."(".$city['duration'].")";
					$countrycitydurationInfo[] = $city['duration'];			
				
				}*/	
				
			}			
			
		}
		
	}
}
?>
