<?php

include("agent-config.php");

//Access your POST variables
$agent_id = $_SESSION['agentid'];

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
	
	include("header.php");
		
?>

<div align="center" style="margin:0 auto;max-width:250px">Search:<input placeholder="Enter user-name or user-email" id="search" type="text" name="search" /></div>
		<div class="pmpro_box" id="pmpro_account-invoices">
			<h2 style="text-align:center">List of Subscriptions</h2>
			<table id="sub_list" style="overflow-x:auto;min-width:50%;max-width:80% !important;" width="80%" border="0" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th onclick="sort_table(tbody, 0, asc1); asc1 *= -1; asc2 = 1; asc3 = 1;">Subscriber</th>
						<th>Bouquet</th>
						<th>Plan</th>
						<th onclick="sort_table(tbody, 1, asc1); asc1 *= -1; asc2 = 1; asc3 = 1;">Amount</th>
						<th onclick="sort_table(tbody, 2, asc1); asc1 *= -1; asc2 = 1; asc3 = 1;">Start date</th>
						<th onclick="sort_table(tbody, 3, asc1); asc1 *= -1; asc2 = 1; asc3 = 1;">End date</th>
						<th onclick="sort_table(tbody, 4, asc1); asc1 *= -1; asc2 = 1; asc3 = 1;">Paid date</th>
					</tr>
				</thead>
				<tbody id="tbody">
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

		
							$sql4 = "SELECT user_login,user_email FROM wp_users where ID=?";				
							$stmt4 = $dbcon->prepare($sql4, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
							$stmt4->execute(array($subid));
							$result4 = $stmt4->fetch(PDO::FETCH_ASSOC);
							$user_email=$result4['user_email'];
							$user_login=$result4['user_login'];

							

							$date1=new DateTime("now");
							$date2=new DateTime($end);

							if($date1<$date2)
							{
								$plan="active";
								echo "<style>#credit-$ID{background-color:#D5F288;}</style>";
							}
							if(count($result4) > 1){
								echo "<tr id='credit-".$ID."' class='ui-sortable-handle'>			
										<td>".$user_login." (".$user_email.")</td>
										<td>Bangla Bouquet</td>
										<td>".$planName."</td>
										<td>".$amount."</td>
										<td>".date("Y-m-d",strtotime($start))."</td>
										<td>".date("Y-m-d",strtotime($end))."</td>
										<td>".date("Y-m-d",strtotime($paid))."</td>
									</tr>";
							}

						}
					}else{
						echo "<tr style='' class='ui-sortable-handle'><td colspan='7'><center>No subscription found</center></td></tr>";
					}	
		
				}catch (PDOException $e){
					print $e->getMessage();
				}
				?>					
				</tbody>
			</table>
			<div id="no_res_div" align="center" style="margin: 5px auto;
    border-bottom: 1px solid #999;
    max-width: 80%;
    font-size: 15px;"><p id="no_res" style="margin:0 auto;text-align:center"></p></div>
		</div>	

<script>
var people, asc1 = 1,
    asc2 = 1,
    asc3 = 1;

function sort_table(tbody, col, asc) {
    var rows = tbody.rows,
        rlen = rows.length,
        arr = new Array(),
        i, j, cells, clen;
    // fill the array with values from the table
    for (i = 0; i < rlen; i++) {
        cells = rows[i].cells;
        clen = cells.length;
        arr[i] = new Array();
        for (j = 0; j < clen; j++) {
            arr[i][j] = cells[j].innerHTML;
        }
    }
    // sort the array by the specified column number (col) and order (asc)
    arr.sort(function (a, b) {
        return (a[col] == b[col]) ? 0 : ((a[col] > b[col]) ? asc : -1 * asc);
    });
    // replace existing rows with new rows created from the sorted array
    for (i = 0; i < rlen; i++) {
        rows[i].innerHTML = "<td>" + arr[i].join("</td><td>") + "</td>";
    }
}


var rows = jQuery('#sub_list tr').not('thead tr');
jQuery('#search').keyup(function() {
    var val = jQuery.trim(jQuery(this).val()).replace(/ +/g, ' ').toLowerCase();

    rows.show().filter(function() {
        var text = jQuery(this).text().replace(/\s+/g, ' ').toLowerCase();
        return !~text.indexOf(val);
    }).hide();

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
if(x==(cnt+1))
{
//document.getElementById("no_res").innerHTML="NO SEARCH RESULTS";
jQuery("#no_res").html("NO SEARCH RESULTS");
jQuery("#no_res_div").show();
}
else
{
jQuery("#no_res").html("");
jQuery("#no_res_div").hide();
}

});
</script>
<?php	

	include("footer.php");
	
}
?>
