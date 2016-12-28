<?php
//include("db-config.php");
//include("admin-include.php");
require_once("function_common.php");

function string_split_sql()
{
 $sql="DELIMITER $$

CREATE FUNCTION SPLIT_STR(
  x VARCHAR(255),
  delim VARCHAR(12),
  pos INT
)
RETURNS VARCHAR(255) DETERMINISTIC
BEGIN 
    RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(x, delim, pos),
       LENGTH(SUBSTRING_INDEX(x, delim, pos -1)) + 1),
       delim, '');
END$$

";
get_all($sql);
return $sql;
}

function get_loginlogout($dbcon,$cond,$date)
{
	echo "<style>.session{font-size:14px;font-weight:bolder;}
		     .channel{font-size:12px;font-weight:bold;}</style>";
	echo "<div style='border:1px solid grey'><h2 >USER SESSIONS AND DURATIONS FOR ".$date." :</h2>";
	
	$sql98="SELECT user_id,user_name FROM visitors_info where user_id>0 ".$cond." group by user_id";
	
	$users=get_all($sql98);
	foreach($users as $user)
	{
		$sql99='SELECT * FROM visitors_info where user_id='.$user["user_id"].' '.$cond.' order by id desc';
		
		$sessions=get_all($sql99);
		$time="";
		$new=array();
		$time=array();

		foreach($sessions as $session)
		{
			$sess=$session['session_id'];
			$ar=explode("-",$sess);
	
			$new = array_filter($new);
			$time = array_filter($time);
			
			if (empty($new)) 
			{
				$new[$ar[0]]=$ar[1];
			}
			else
			{
				foreach($new as $key=>$value)
				{

					if($key==$ar[0])
					{		
						$time[$ar[0]]=$time[$ar[0]]+($value-$ar[1]);//print_r($time);
						$new[$ar[0]]=$ar[1];
					}
					else
					{
						$new[$ar[0]]=$ar[1];
					}
				}
			}

		}
		
		echo "<br>";
		echo "<div style='border:1px solid'>";
		echo "<h3>user:".$user['user_name'];echo "</h3><br>";
		$i=1;
		string_split_sql();
		foreach($time as $key => $value)
			{
				echo "<br>";echo "<span class='session''>SESSION ".$i." : </span>";
				$startdate=get_var('SELECT start_datetime from visitors_info where user_id='.$user["user_id"].' '.$cond.' and SPLIT_STR(session_id, "-", 1)="'.$key.'" order by id asc limit 1');

				$enddate=get_var('SELECT end_datetime from visitors_info where user_id='.$user["user_id"].' '.$cond.' and SPLIT_STR(session_id, "-", 1)="'.$key.'" order by id desc limit 1');
				
				$diff = strtotime($enddate) - strtotime($startdate);
				
				echo min_hr($diff)[0]. "(".min_hr($diff)[1].")";
				
				//".min_hr($value)[0]." (".min_hr($value)[1].")";
				
				
				
				$sql96=' SELECT b.post_title,sum(a.duration) as dur FROM visitors_info a inner join wp_posts b on a.page_id=b.id where SPLIT_STR(session_id, "-", 1)="'.$key.'" and b.post_type="post" and a.user_id='.$user["user_id"].' '.$cond.' group by a.page_id order by dur desc';
				
				
				$channel_durations=get_all($sql96);
				echo "<br>";
				foreach($channel_durations as $chan)
				{
					echo "<span class='channel'>".$chan['post_title']." : </span>".min_hr($chan['dur'])[0]." (".min_hr($chan['dur'])[1].")";echo "<br>";
				}
				$i=$i+1;
			}

		/*$sql97='SELECT b.post_title,sum(a.duration) as dur FROM visitors_info a inner join wp_posts b on a.page_id=b.id where b.post_type="post" and a.user_id='.$user["user_id"].' '.$cond.' group by a.page_id order by dur desc';
		
		$channel_durations=get_all($sql97);
		echo "<br>";
		foreach($channel_durations as $chan)
			{
				echo $chan['post_title']." : ".min_hr($chan['dur'])[0]." (".min_hr($chan['dur'])[1].")";echo "<br>";
			}*/
		echo "</div>";
	}
echo "</div>";
}

?>
