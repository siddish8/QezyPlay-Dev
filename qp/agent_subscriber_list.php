<?php

include("db-config.php");

//Access your POST variables
$agent_id = $_REQUEST['agent_id'];
$_SESSION['agentid']=$agent_id;
//echo "id:".$agent_id;
//Unset the useless session variable


$msg = ""; 
$msgR = ""; 

if(isset($_GET['logout'])){

	unset($_SESSION['agentid']);

	header('Location: agent-login.php');
	exit;
}


function getPlanName($planid){

	global $dbcon;

	$sql2 = "SELECT name FROM wp_pmpro_membership_levels where id = ".$planid;		
	try {
		$stmt2 = $dbcon->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt2->execute();
		$result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
 		$stmt2 = null;
		foreach($result2 as $plan){
			return $plan['name'];
		
		}
	}catch (PDOException $e){
		print $e->getMessage();
	}

}

if((int)$_SESSION['agentid'] <= 0){
	
	header('Location: agent-login.php');
	exit;
	
}else{
	
	$sql = "SELECT agentname FROM agent_info WHERE id = ?";		
	try {
		$stmt = $dbcon->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute(array($agent_id));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
 
    	$stmt = null;

		$agent_name = $result['agentname'];
		
	}catch (PDOException $e){
		print $e->getMessage();
   	}
	
	include("header-agent.php");
	?>
		
<style>

.mx{max-width:80%;margin:0 auto !important;}
 /* Style the list */
ul.tab {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Float the list items side by side */
ul.tab li {float: left;}

/* Style the links inside the list items */
ul.tab li a {
    display: inline-block;
    color: black;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of links on hover */
ul.tab li a:hover {background-color: #ddd;}

/* Create an active/current tablink class */
ul.tab li a:focus, .active {background-color: #ccc;}

/* Style the tab content */
.tabcontent {
    display: none;
       border-top: none;
	 text-decoration:none;
}

.show{display:block !important;}
.hide{display:none !important;}

.link_btn:hover{
    background: #000;
    color: #fff;
    border: solid 1px #000;
}

.link_btn{
    border-radius: 3px;
    line-height: 1.5;
    text-align: center;
    margin: 5px;
    padding: 7px 18px;
    outline: none;
    background: #4141a0;
    border: solid 1px #4141a0;
    color: #fff;
    transition: all .2s ease;
    text-decoration:none;
}

td:nth-child(even) {background: #FFF}
td:nth-child(odd) {background: #DDD}
th{color:whitesmoke !important;text-align: center;border: 1px solid rgba(255,255,255,.15) !important;}
thead{background-color: #4141a0}
table td {
    color: #444;
border-bottom: 1px solid #777 !important;
	text-align: center;
}


div#SubscriberList > div > input[type="text"]{max-width:250px}
._or{margin:0 15px}

footer{position: relative;
    bottom: 0px;
    width: 100%;}
#bottom-nav_1{position: relative;
    bottom: 0px;
    width: 100%;}
#logout{display:none}
</style>

<div id="SubscriberList" class="">
	<div align="center" style="margin:15px auto;">
		Search using any criteria: <br /> <input placeholder="Enter user-name" id="searchUN" type="text" name="searchUN" /> <span class="_or"> </span>   
		<input placeholder="Enter user-email" id="searchEM" type="text" name="searchEM" /> <span class="_or"> </span>
		<input placeholder="Enter user-firstname" id="searchFN" type="text" name="searchFN" />  <span class="_or"> </span>
		<input placeholder="Enter user-lastname" id="searchLN" type="text" name="searchLN" /> <span class="_or"> </span>  
		<input placeholder="Enter user-phone" id="searchPH" type="text" name="searchPH" />
		</div>
		<div class="pmpro_box" id="pmpro_account-invoices">
			<h2 style="text-align:center">List of Subscriptions</h2>
		<div class="table-responsive"	style="max-height: 400px; overflow-y: scroll;">
			<table id="sub_list1" style="overflow-x:auto;min-width:50%;max-width:80% !important;" width="80%" border="0" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th>Subscriber Name</th>
						<th>Subscriber Email</th>
						<th>Subscriber FirstName</th>
						<th>Subscriber LastName</th>
						<th>Subscriber Phone</th>
						<th>Bouquet</th>
						<th>Plan</th>
						<th>Amount</th>
						<th>Start date</th>
						<th>End date</th>
						<th>Paid date</th>
						<th>Plan Status</th>
					</tr>
				</thead>
				<tbody id="sub_list">
				<?php

				$sql3 = "SELECT * FROM agent_vs_subscription_credit_info where  agent_id=? order by credited_datetime desc";		
				try {
					$stmt3 = $dbcon->prepare($sql3, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
					$stmt3->execute(array($agent_id));
					$result3 = $stmt3->fetchall(PDO::FETCH_ASSOC);
			 		$stmt3 = null;
					if(count($result3) > 0){
						foreach($result3 as $credit){
							$ID=$credit["id"];
							$subid=$credit["subscriber_id"];
							$boqid=$credit["bouquet_id"];
							$planid=$credit["plan_id"];

							$planName = getPlanName($planid);

							$amount=$credit["amount"];
							$start=$credit["subscription_start_from"];
							$end=$credit["subscription_end_on"];
							$paid=$credit["credited_datetime"];

		
							$sql4 = "SELECT * FROM wp_users where ID=?";				
							$stmt4 = $dbcon->prepare($sql4, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmt4->execute(array($subid));
							$result4 = $stmt4->fetch(PDO::FETCH_ASSOC);
							$user_email=$result4['user_email'];
							$user_login=$result4['user_login'];
							$user_phone=$result4['phone'];
							
							$sql41 = "select meta_value from wp_usermeta where user_id=? and (meta_key='first_name' or meta_key='last_name')";
							$stmt41 = $dbcon->prepare($sql41, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmt41->execute(array($subid));
							$result41 = $stmt41->fetchAll(PDO::FETCH_ASSOC);
							
							//echo var_dump($result41);
							$user_fn=$result41[0]['meta_value'];
							$user_ln=$result41[1]['meta_value'];
							

							$date1=new DateTime("now");
							$date2=new DateTime($end);

							if($date1<$date2)
							{
								$plan_status="active";
								//echo "<style>#credit-$ID{background-color:#D5F288;}</style>";
								echo "<style>#status-$ID{background-color:#D5F288;}</style>";
							}
							else
							{
								$plan_status="expired";
							}

							if(count($result4) > 1){
								echo "<tr class='ui-sortable-handle'>			
										<td>".$user_login."</td>
										<td>".$user_email."</td>
										<td>".$user_fn."</td>
										<td>".$user_ln."</td>
										<td>".$user_phone."</td>
										<td>Bangla Bouquet</td>
										<td>".$planName."</td>
										<td>".$amount."</td>
										<td>".date("Y-m-d",strtotime($start))."</td>
										<td>".date("Y-m-d",strtotime($end))."</td>
										<td>".date("Y-m-d",strtotime($paid))."</td>
										<td id='status-".$ID."'>".$plan_status."</td>
									</tr>";
							}

						}
					}else{
						echo "<tr style='' class='ui-sortable-handle'><td colspan='12'><center>No subscription found</center></td></tr>";
					}	
		
				}catch (PDOException $e){
					print $e->getMessage();
				}
				?>					
				</tbody>
			<tr id="no_res_div" align="center" style="margin: 5px auto;
    border-bottom: 1px solid #999;
    max-width: 80%;
    font-size: 15px;"><td colspan="12" id="no_res" style="margin:0 auto;text-align:center"></td></tr>
			</table></div>
			
		</div>		
	</div>	
<br><br><br><br>
<script>

function findRows(table,searchText1,col1,searchText2,col2,searchText3,col3,searchText4,col4,searchText5,col5) {

//var table=document.querySelector("#sub_list > tr:visible");
//var table=$('#sub_list tr:visible');
    var rows = table.rows,
        r = 0,
        found = false,
        anyFound = false;
       // var found1,found2,found3;
        //alert(rows.length);
        //alert(searchText1+"in"+col1);
       // alert(searchText2+"in"+col2);
       // alert(searchText3+"in"+col3);
       //  alert(searchText4+"in"+col4);
       // alert(searchText5+"in"+col5);
        //alert(searchText1+"in"+col1);

//alert($('#sub_list tr:visible').length);
	
  var found1=false,found2=false,found3=false,found4=false,found5=false;
  
    for (; r < rows.length; r += 1) {
        row = rows.item(r);
          
     // var i=column;
       // alert(searchText1+"in col:"+col1+"  row::"+r);
        
        if(col1!=99)
        found1 = (row.cells.item(col1).textContent.toLowerCase().indexOf(searchText1.toLowerCase().trim()) !== -1);
        else
        found1=true;
         if(col2!=99)
          found2 = (row.cells.item(col2).textContent.toLowerCase().indexOf(searchText2.toLowerCase().trim()) !== -1);else
        found2=true;
          if(col3!=99)
            found3 = (row.cells.item(col3).textContent.toLowerCase().indexOf(searchText3.toLowerCase().trim()) !== -1);else
        found3=true;
          if(col4!=99)
          found4 = (row.cells.item(col4).textContent.toLowerCase().indexOf(searchText4.toLowerCase().trim()) !== -1);else
        found4=true;
          if(col5!=99)
            found5 = (row.cells.item(col5).textContent.toLowerCase().indexOf(searchText5.toLowerCase().trim()) !== -1);else
        found5=true;
            
            found=found1 && found2 && found3 && found4 && found5;
        anyFound = anyFound || found;
//alert(found);
			//if(row.style.display=="none")
      	//	found=false;
        row.style.display = found ? "table-row" : "none";
       }
        
	   
	if(col1==99 && col2==99 && col3==99 && col4==99 && col5==99)
	{
		for (; r < rows.length; r += 1) {
			row = rows.item(r);
			 row.style.display = "table-row" ;}
	}
    //document.getElementById('no_res').style.display = anyFound ? "none" : "block";
var x = document.getElementById("sub_list").rows.length;
//alert("x"+x);
var cnt=0;

for(i=0;i<x;i=i+1)
{
 var y=document.getElementById("sub_list").rows[i].style.display;

if(y=="none")
cnt=cnt+1;
}

//alert("cnt:"+cnt);
if(x==(cnt))
{
document.getElementById("no_res").innerHTML="NO SEARCH RESULTS";
jQuery("#no_res").html("NO SEARCH RESULTS");
jQuery("#no_res_div").show();
}
else
{
jQuery("#no_res").html("");
jQuery("#no_res_div").hide();
}

}

function performSearch() {
    var searchText1 = document.getElementById('searchUN').value,
        searchText2 = document.getElementById('searchEM').value,
        searchText3 = document.getElementById('searchFN').value,
        searchText4 = document.getElementById('searchLN').value,
        searchText5 = document.getElementById('searchPH').value,
        targetTable = document.getElementById('sub_list');
	var searchText=[],col;
	
	if(searchText1!="")
	{
	searchText[0]=searchText1;
	col1=0;
	}
  else{col1=99;}
  
  
	if(searchText2!="")
	{
	searchText[1]=searchText2;
	col2=1;
	}
  else{col2=99;}
  
  
 if(searchText3!="")
	{
	searchText[2]=searchText3;
	col3=2;
	}
  else{col3=99;
}

if(searchText4!="")
	{
	searchText[3]=searchText4;
	col4=3;
	}
  else{col4=99;}
  
  
 if(searchText5!="")
	{
	searchText[4]=searchText5;
	col5=4;
	}
  else{col5=99;
}
  	

	//alert(searchText);
    findRows(targetTable,searchText1,col1,searchText2,col2,searchText3,col3,searchText4,col4,searchText5,col5);

}

//document.getElementById("search").onclick = performSearch;
document.getElementById("searchUN").onkeyup = performSearch;
document.getElementById("searchEM").onkeyup = performSearch;
document.getElementById("searchFN").onkeyup = performSearch;
document.getElementById("searchLN").onkeyup = performSearch;
document.getElementById("searchPH").onkeyup = performSearch;

if(jQuery("tr#no_res_div td").html()=="")
	jQuery("tr#no_res_div").hide();
</script>
<?php	

	include("footer-agent.php");
	
}
?>
