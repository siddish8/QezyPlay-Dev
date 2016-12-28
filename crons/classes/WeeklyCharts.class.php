<?php
/*************************************************************************
 * Ideabytes Software India Pvt Ltd.                        		     *
 * 50 Jayabheri Enclave, Gachibowli, HYD                          		 *
 * Created Date : 25/08/2014                                      		 *
 * Created By : Sri Ravi Teja                                            *
 * Vision : Project Visitortracking MVC                              	 *  
 * Modified by : Sri Ravi Teja    Date : 25/08/2014  Version : 2.0   	 *
 * Description : this page contains all sql statements to display    	 *
				  WeeklyChart's information	                             *
 ************************************************************************/

class WeeklyCharts
{
	function citycode($countryCode)
	{
		
		global $dbcon;
		global $get_category;
		global $friendlyIpsCond;
		global $friendlyWebsites;
		global $date1;
		global $date2;
		global $searhCond;
		global $stmt;
		global $country_city_code_display_array;
		global $country_cityInfo;
		global $clicksInfo;
		global $pageInfo;
		global $page_code_display_array;
		global $sql;
		global $pageShortName;
		global $deviceCond;
		
		   			
		 $sql = "SELECT vi.city,count(vi.id) AS clicks FROM visitors_info as vi WHERE vi.geo_info_status = 1 AND date(datetime) BETWEEN '".$date1."' AND '".$date2."'".$friendlyIpsCond.$friendlyWebsites." AND country_code = '".$countryCode."' GROUP BY vi.city  ORDER BY clicks DESC";
		
		
		try {
			$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt->execute();			
		}
		catch (PDOException $e)
		{
			print $e->getMessage();
		}
		
		$country_city_code_display_array = array();
		if($stmt->rowCount() > 0)
		{
			$analyticsData = $stmt->fetchALL(PDO::FETCH_ASSOC);
			$country_cityInfo = array();
			$clicksInfo = array();		
			/*echo "<pre>";
				print_r($analyticsData);*/
			foreach($analyticsData as $aData)
			{
				$info_1 = array();				
				$info_1['city'] = $aData['city'];				
				$info_1['clicks'] = $aData['clicks'];
				$country_city_code_display_array[] = $info_1;
				$country_cityInfo[] = $aData['city']."(".$aData['clicks'].")";
				$clicksInfo[] = $aData['clicks'];
				
			}
		}
		
		return $country_city_code_display_array;
		
	}	
	
	function chartsweekly()
	{
		global $dbcon;
		global $get_category;
		global $friendlyIpsCond;
		global $friendlyWebsites;
		global $date1;
		global $date2;
		global $searhCond;
		global $stmt;
		global $country_code_display_array;
		global $countryInfo;
		global $clicksInfo;
		global $pageInfo;
		global $page_code_display_array;
		global $sql;
		global $pageShortName;
		global $deviceCond;
		global $country_code_city_display_array;
		global $countrycityclicksInfo;
		global $countrycityInfo;
		
		if($get_category == "Country"){
			$sql = "SELECT vi.country_code,vi.country,count(vi.id) AS clicks FROM visitors_info as vi WHERE vi.geo_info_status = 1 AND date(datetime) BETWEEN '".$date1."' AND '".$date2."'".$friendlyIpsCond.$friendlyWebsites." GROUP BY vi.country ORDER BY clicks DESC";
		}else if($get_category == "Page"){
			$sql = "SELECT p.short_name,p.page_name, count(vi.id) AS clicks FROM visitors_info as vi  LEFT JOIN page as p ON p.id = vi.page_id WHERE vi.geo_info_status = 1 AND date(datetime) BETWEEN '".$date1."' AND '".$date2."'".$friendlyIpsCond.$friendlyWebsites." GROUP BY p.page_name ORDER BY clicks DESC";
		}else if($get_category == "Country-City"){
			$sql = "SELECT vi.country_code,vi.country,count(vi.id) AS clicks FROM visitors_info as vi WHERE vi.geo_info_status = 1 AND date(datetime) BETWEEN '".$date1."' AND '".$date2."'".$friendlyIpsCond.$friendlyWebsites." GROUP BY vi.country ORDER BY clicks DESC";
		}
		
		try {
			$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt->execute();	
		}catch (PDOException $e){
			print $e->getMessage();
		}

		if($get_category == "Country"){
			$country_code_display_array = array();
			if($stmt->rowCount() > 0){
				$analyticsData = $stmt->fetchALL(PDO::FETCH_ASSOC);
				$countryInfo = array();
				$clicksInfo = array();
				foreach($analyticsData as $aData){
					$info_1 = array();
					$info_1['short_name'] = $aData['country_code'];
					$info_1['full_name'] = $aData['country'];
					$cityInfo =array();
					$info_1['cityinfo'] = $cityInfo;	
					$info_1['clicks'] = $aData['clicks'];
					$country_code_display_array[] = $info_1;					
					$countryInfo[] = $aData['country_code']."(".$aData['clicks'].")";
					$clicksInfo[] = $aData['clicks'];
					
				}
			}
		}else if($get_category == "Page"){
			if($stmt->rowCount() > 0){
				$analyticsData = $stmt->fetchALL(PDO::FETCH_ASSOC);
				$pageInfo = array();
				$clicksInfo = array();
				$page_code_display_array = array();
				foreach($analyticsData as $aData){
					$info_1 = array();
					$pageShortName = $aData['short_name'];
					$info_1['short_name'] = $pageShortName;
					$info_1['full_name'] = $aData['page_name'];
					$cityInfo =array();
					$info_1['cityinfo'] = $cityInfo;	
					$info_1['clicks'] = $aData['clicks'];
					$page_code_display_array[] = $info_1;
					
					$pageInfo[] = $pageShortName."(".$aData['clicks'].")";
					
					$clicksInfo[] = $aData['clicks'];
					
				}
			
			}
		}
		else if($get_category == "Country-City"){			
			
			$country_code_city_display_array = array();
			if($stmt->rowCount() > 0)
			{
				$analyticsData = $stmt->fetchALL(PDO::FETCH_ASSOC);
				$countrycityInfo = array();
				$countrycityclicksInfo = array();			
				foreach($analyticsData as $aData)
				{
					$info_1 = array();
					$info_1['short_name'] = $aData['country_code'];
					$info_1['full_name'] = $aData['country'];
					$cityInfo = self::citycode($aData['country_code']);				
					$info_1['cityinfo'] = $cityInfo;	
					$info_1['clicks'] = $aData['clicks'];
					$country_code_city_display_array[] = $info_1;				
					
					foreach($cityInfo as $city){								
						$countrycityInfo[] = $aData['country_code']."-".$city['city']."(".$city['clicks'].")";
						$countrycityclicksInfo[] = $city['clicks'];							
					}	
				}	
			}
		}
	}
}
?>