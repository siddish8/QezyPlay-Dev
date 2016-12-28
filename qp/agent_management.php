<?php 

include("db-config.php");
include("admin-include.php");
include("function_common.php");

if($_SESSION['adminlevel']>0)
{
//header("Location:".SITE_URL."/qp/admin-main.php");
//exit;
}

$msg = ""; $action = "Add";

if(isset($_POST['Agent_Back'])){
header("Location:".SITE_URL."/qp/agent_management.php");
exit;
}

if(isset($_REQUEST['delAgent'])){

	$id = $_REQUEST['delAgentid'];


//echo $id;
//exit;
	$stmt11 = $dbcon->prepare("DELETE FROM agent_info WHERE id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt11->execute();

	$stmt22 = $dbcon->prepare("DELETE FROM agent_location_info WHERE agent_id = ".$id."", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt22->execute();

	$msg = "<span id='msg' style='color:green'>Agent deleted Successfully</span>";
	
}


if(isset($_POST['Add_Submit'])){
	
	$agentname=trim($_POST['agentname']);
	$phone=trim($_POST['phone']);
	$email=trim($_POST['email']);
	//$authkey=trim($_POST['add_authkey']);

	$city=trim($_POST['city']);
	//$cityArray = explode(',', $city); //uncomment
		//echo $city;
	//exit;
	$authkey = md5($email.rand());
	$authkey = substr($authkey, 0, 10);	
	//$authkey="";
	$date = gmdate("Y-m-d H:i:s");
	
	$stmt21 = $dbcon->prepare('SELECT * FROM agent_info WHERE mobile = "'.$phone.'" OR agentname = "'.$agentname.'" OR email = "'.$email.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
	$stmt21->execute();
	$result21 = $stmt21->fetchAll(PDO::FETCH_ASSOC);
	$userexit = count($result21);	
//echo $userexit;	
//exit;
	if((int)$userexit > 0){

		$msg1="";
		

		$stmt212 = $dbcon->prepare('SELECT * FROM agent_info WHERE agentname = "'.$agentname.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt212->execute();
		$result212 = $stmt212->fetchAll(PDO::FETCH_ASSOC);
		$agentnameexist = count($result212);

		if((int)$agentnameexist > 0){	
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Agent Name already exist <br>";}

		$stmt211 = $dbcon->prepare('SELECT * FROM agent_info WHERE mobile = "'.$phone.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt211->execute();
		$result211 = $stmt211->fetchAll(PDO::FETCH_ASSOC);
		$phoneexist = count($result211);	
//echo $userexit;	
//exit;
		if((int)$phoneexist > 0){	
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Agent Phone already exist <br>";}

		
		$stmt213 = $dbcon->prepare('SELECT * FROM agent_info WHERE email = "'.$email.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt213->execute();
		$result213 = $stmt213->fetchAll(PDO::FETCH_ASSOC);
		$emailexist = count($result213);

		if((int)$emailexist > 0){		
		$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Agent Email already exist";}

		$msg = "<span id='msg' style='color:red'>".$msg1."</span>";
		
	}else{			
		
		//insert query
		$stmt22 = $dbcon->prepare('INSERT INTO agent_info(agentname,mobile,email,authkey,created_datetime) VALUES("'.$agentname.'","'.$phone.'","'.$email.'","'.$authkey.'","'.$date.'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt22->execute();
		$agent_id = $dbcon->lastInsertId();

		$agentloginlink = SITE_URL."/qp/agent-login.php";
		
		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		// More headers
		$headers .= 'From: <'.ADMIN_EMAIL.'>' . "\r\n";
		
		$subjectAgent = "Your account info on Qezyplay.com as Agent";
		$regards = "<p>Regards, <br>Admin - QezyPlay</p>";
		$bodyAgent = "<p>Hi ".$agentname.",</p>
		<p>Your account has been created in Qezyplay.com as Agent</p>
		<p>Find below credentials to login with agent portal</p>
		<p>
		<b>Email:</b> $email<br>
		<b>Auth Key:</b> $authkey<br>
		</p>
		<p>Log in to your agent portal here: $agentloginlink</p>".$regards;	
		
		//mail Agent
		mail($email, $subjectAgent, print_r($bodyAgent,true), $headers);

		$locationsArray =explode(',',$city); //added
		foreach($locationsArray as $locAr)
		{
			$cityAr=explode('-',$locAr);
			$stmt23 = $dbcon->prepare('INSERT INTO agent_location_info(agent_id,location,region,country) VALUES("'.$agent_id.'","'.$cityAr[0].'","'.$cityAr[1].'","'.$cityAr[2].'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt23->execute();	
		}

		$msg = "<span style='color:green'>Agent added Successfully</span>";
					
	}
}


if(isset($_POST['Edit_Submit'])){

	$agentid=trim($_POST['agentid']);


	//$agentid = $_GET['id'];
	$agentname=trim($_POST['agentname']);
	$phone=trim($_POST['phone']);
	$email=trim($_POST['email']);
	$city=trim($_POST['city']);

	$cityArray = explode(',', $city);
	$date = gmdate("Y-m-d H:i:s");
	$stmt21 = $dbcon->prepare('SELECT * FROM agent_info WHERE id != '.$agentid.' AND (mobile = "'.$phone.'" OR agentname = "'.$agentname.'" OR email = "'.$email.'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
	$stmt21->execute();
	$result21 = $stmt21->fetchAll(PDO::FETCH_ASSOC);	
	$userexit = count($result21);


	if((int)$userexit > 0){

		$msg1="";
		

		$stmt212 = $dbcon->prepare('SELECT * FROM agent_info WHERE id != '.$agentid.' AND agentname = "'.$agentname.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt212->execute();
		$result212 = $stmt212->fetchAll(PDO::FETCH_ASSOC);
		$agentnameexist = count($result212);

		if((int)$agentnameexist > 0){	
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Agent Name already exist <br>";}

		$stmt211 = $dbcon->prepare('SELECT * FROM agent_info WHERE id != '.$agentid.' AND mobile = "'.$phone.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt211->execute();
		$result211 = $stmt211->fetchAll(PDO::FETCH_ASSOC);
		$phoneexist = count($result211);	
//echo $userexit;	
//exit;
		if((int)$phoneexist > 0){	
			$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Agent Phone already exist <br>";}

		
		$stmt213 = $dbcon->prepare('SELECT * FROM agent_info WHERE id != '.$agentid.' AND email = "'.$email.'"', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt213->execute();
		$result213 = $stmt213->fetchAll(PDO::FETCH_ASSOC);
		$emailexist = count($result213);

		if((int)$emailexist > 0){		
		$msg1.= "<i class='fa fa-arrow-right' aria-hidden='true'></i> Agent Email already exist";}

		$msg = "<span id='msg' style='color:red'>".$msg1."</span>";
		
	}
	
	/* if((int)$userexit > 0){
		
		$msg = "<span style='color:red'>Agent already exist</span>";
		
	}*/else{
		
		//update query
		$stmt22 = $dbcon->prepare('UPDATE agent_info SET agentname = "'.$agentname.'", mobile = "'.$phone.'", email = "'.$email.'",created_datetime = "'.$date.'" WHERE id = '.$agentid, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	 
		$stmt22->execute();
		
		$stmt24 = $dbcon->prepare('DELETE FROM agent_location_info WHERE agent_id = '.$agentid.'', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
		$stmt24->execute();
		
		$locationsArray =explode(',',$city); //added
		//echo "<pre>";
		//print_r($locationsArray);
		foreach($locationsArray as $locAr)
		{
			$cityAr=explode('-',$locAr);
			$stmt23 = $dbcon->prepare('INSERT INTO agent_location_info(agent_id,location,region,country) VALUES("'.$agentid.'","'.$cityAr[0].'","'.$cityAr[1].'","'.$cityAr[2].'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt23->execute();	
		}	
		$msg = "<span style='color:green'>Agent updated Successfully</span>";
								
	}		
	//$_REQUEST['editAgent']=true;
}	


if(isset($_REQUEST['editAgent'])){

	$id = $_REQUEST['editAgentid'];		

//echo $id;
//exit;
	$stmt1 = $dbcon->prepare("SELECT * FROM agent_info where id='".$id."'", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt1->execute();
	$result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
	foreach($result1 as $row1){
		$agentid1=$id;
		$agentname1=$row1['agentname'];
		$mobile1=$row1['mobile'];
		$email1=$row1['email'];
	}

	$stmt2 = $dbcon->prepare("SELECT group_concat(concat(location,'-',region,'-',country)) as location FROM agent_location_info where agent_id='".$id."'", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
	$stmt2->execute();
	$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
	$location1 = $result2['location'];		
	$action = "Edit";

}	

include("header-admin.php");
?>

<style>
.xoouserultra-field-value, .xoouserultra-field-type{
	width: unset !important;
	padding: 5px;
}

form[name="f_timezone"] {
    display: none;
}

#search_agent > input[type="text"] {
    max-width: 250px;
}

.link_btn:hover{
    background: #000;
    color: #fff;
    border: solid 1px #000;
}

.link_btn:visited{color:white !important}
.link_btn{
    border-radius: 3px;
    line-height: 2.75;
    text-align: center;
    margin: 5px;
    padding: 6.5px 25px;
    outline: none;
    background: #4141a0;
    border: solid 1px #4141a0;
    color: #fff;
    transition: all .2s ease;
    text-decoration:none;
	border-radius:5px;
}
</style>

<div id="content" role="main" style="min-height:500px;margin: 2% 5%;">
	<!-- <h4 style="float:right;"><a alt="click to dashboard" href="admin-main.php">Dashborad</a></h4> -->
	<div class="pmpro_box" id="pmpro_account-invoices">
		<h2 style="text-align:center">Agent Management</h2>
	<?php		if($_SESSION['adminlevel']<1)
{ ?>	
		<div class="xoouserultra-wrap" style="max-width:100% !important;">
				
			<!-- <div class="xoouserultra-inner xoouserultra-login-wrapper"> -->
			<!--	<div class="xoouserultra-main"> -->
					<!-- <h4><b><u><?php echo $action;?> Agent</u></b></h4> -->
					<div class="panel panel-default">
   					 <div align="center" style="font-size: 16px;
    color: #4141a0;margin:0 auto" class="panel-heading"><span style="float:left"><?php echo $action;?> Agent</span><div class="msg" align="center" style="display:inline-block"><h4><?php echo $msg;?></h4></div></div>
   					 <div class="panel-body">
					<form id="xoouserultra-login-form-1" method='post' onsubmit="return callsubmit();">
						<input type="hidden" id="agentid" name="agentid" value="<?php echo $id; ?>"/>
						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
							<label class="xoouserultra-field-type">	
								<span>Agent Name:</span>
							</label>
							<div class="xoouserultra-field-value">
								<input onclick="clearError()" type="text" value="<?php echo @$agentname1;?>" id="agentname" name="agentname" />	
							</div>
							&nbsp;&nbsp;&nbsp;
							<label class="xoouserultra-field-type">	
								<span>Mobile:</span>
							</label>
							<div class="xoouserultra-field-value">
								<input onclick="clearError()" type="text" placeholder="Ex:+919000000000" value="<?php echo @$mobile1;?>" id="phone" name="phone"/>
							</div>
							&nbsp;&nbsp;&nbsp;
							<label class="xoouserultra-field-type">	
								<span>Email:</span>
							</label>
							<div class="xoouserultra-field-value">
								<input onclick="clearError()" type="text" value="<?php echo @$email1;?>" id="email" name="email" />
							</div>
						</div>		
						<div class="xoouserultra-clear"></div>
						<div class="xoouserultra-field xoouserultra-edit xoouserultra-edit-show">
						
							<label class="xoouserultra-field-type">	
								<span>Location:</span>
							</label>
							<div class="xoouserultra-field-value">
								<textarea onclick="clearError()" rows="3" cols="35" name="city" id="city" placeholder="Ex: city1-state1-country1,city2-state2-country2" ><?php echo @$location1;?></textarea>
							</div>
							&nbsp;&nbsp;&nbsp;
							
							<div class="xoouserultra-field-value" style="padding-left: 10px;">
								<?php if($action=="Edit"){?>
								<input type="hidden" name="editAgent"  value="true" />
								<input type="hidden" name="editAgentid"  value="<?php echo @$agentid1;?>" />
								<?php } ?>
								<input type="submit" name="<?php echo $action;?>_Submit"  value="Submit" />
								<?php if($action=="Edit"){?>
								<!--form id="" method="post"--><!--input type="submit" name="Agent_Back"  value="Back" /><!--/form-->					<a class="link_btn" href="<?php echo SITE_URL.'/qp/agent_management.php'?>">Back</a>
   								<?php } ?>
							</div>
							&nbsp;&nbsp;&nbsp;
							<div class="xoouserultra-field-value" style="padding-left: 10px;">
								<div id='error' style='color:red;padding-left:10px;'";></div>
							</div>
							
						</div>						
						
					</form>
					<div class="xoouserultra-clear"></div></div>
    					
  					<!-- </div> -->
				<!--  /div> -->
			</div>

		</div>
<?php }
?>

<div id="search_agent" align="center" style="margin:15px auto;">
		Search using any criteria: <br /> <input placeholder="Enter agent-name" id="searchAN" type="text" name="searchAN" /> <span class="_or"> </span>   
		<input placeholder="Enter agent-phone" id="searchPH" type="text" name="searchPH" /> <span class="_or"> </span>
		<input placeholder="Enter agent-email" id="searchEM" type="text" name="searchEM" /> 
				
		</div>


		<!-- <div class="table-responsive"	style="max-height: 400px; overflow-y: scroll;"> -->
		<form id="frm2" method="post">
		<table style="overflow-x:auto;min-width:50%;max-width:100% !important;" width="100%" border="0" cellspacing="0" cellpadding="0">
			<thead>
				<tr>					
					<th>Agent Name</th>
					<th>Mobile</th>
					<th>Location</th>
					<th>E-mail</th>
					
					<?php		if($_SESSION['adminlevel']<1)
{ ?> <th>Auth Key</th><th>Action</th> <?php } ?>
				</tr>
			</thead>
			<tbody id="agent_list" class="ui-sortable">
			<?php

			$stmt4 = $dbcon->prepare("SELECT * FROM agent_info", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt4->execute();
			$result4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);
	
			foreach($result4 as $row){

				$id=$row['id'];
				$agentname=$row['agentname'];
				$mobile=$row['mobile'];
				$email=$row['email'];
				$authkey=$row['authkey'];

				$stmt5 = $dbcon->prepare("SELECT group_concat(concat(location,'-',region,'-',country)) as location FROM agent_location_info where agent_id='".$id."'");
				$stmt5->execute();
				$result5 = $stmt5->fetch(PDO::FETCH_ASSOC);
				$location = $result5['location'];
			?>
				<tr style="" class="ui-sortable-handle">							
				<td style="width: 342px;color:blue;" class="level_name"><a target="_blank" href="agent_subscriber_list.php?agent_id=<?php echo $id ?>"><?php echo $agentname;?></a></td>
				<td style="width: 184px;"><?php echo $mobile;?></td>
				<td style="width: 192px;"><?php echo $location?></td>
				<td style="width: 192px;"><?php echo $email;?></td>
				
<?php		if($_SESSION['adminlevel']<1)
{ ?>
				<td style="width: 192px;"><?php echo $authkey;?></td>
				<td style="width: 332px;">

<!-- <a style="cursor: pointer;color:blue;" id="editAgent" href="agent_management.php?edit=true&id=<?php echo $id;?>" title="edit" name="Edit-<?php echo $id ?>" class="button-primary">Edit</a> -->

<!-- <input type="button" id="editAgent" name="editAgent" value="Edit" onclick="return confirmEditAgent(<?php echo $id;?>)" --> 
<a style="cursor:pointer;color:blue;" id="editAgent" name="editAgent" onclick="return confirmEditAgent(<?php echo $id;?>)">Edit</a>
&nbsp;
<a style="cursor:pointer;color:blue;" id="removeAgent" name="removeAgent" onclick="return confirmDelAgent(<?php echo $id;?>)">Remove</a>				
<!-- input type="button" id="removeAgent" name="removeAgent" value="Remove" onclick="return confirmDelAgent(<?php echo $id;?>)" -->
<!-- <a style="cursor: pointer;color:blue;" title="delete" name="removeAgent-<?php echo $id;?>" id="removeAgent-<?php echo $id;?>" onclick="callConfirmation('agent_management.php?del=true&id=<?php echo $id;?>');" class="button-secondary">Delete</a>-->
</td>
<?php } ?>				
</tr>
			<?php } ?>
			</tbody>
			<tr id="no_res_div" align="center" style="margin: 5px auto;
    border-bottom: 1px solid #999;
    max-width: 80%;
    font-size: 15px;"><td colspan="6" id="no_res" style="margin:0 auto;text-align:center"></td></tr>
		</table></form>
		<!-- </div> -->
	</div>
</div>


<script>

function validateEmail(email) {
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}

function validatePhone(phone) {
	var re = /^([0|\+[0-9]{1,5})?([1-9][0-9]{9})$/;
	return re.test(phone);
}

function callsubmit(){	
	
	var email = jQuery("#email").val();
	var agentname = jQuery("#agentname").val();
	var phone = jQuery("#phone").val();
	var city = jQuery("#city").val();


	if(agentname == ""){
		jQuery("#error").html("Please enter agent name");
		return false;		
	}
			
	if(phone == ""){
		jQuery("#error").html("Please enter phone no");
		return false;		
	}else if (!validatePhone(phone.trim())) {

		jQuery("#error").html("Please enter valid phone no");
		return false;
			
			}


	 if(email == ""){
		jQuery("#error").html("Please enter email address");	
		return false;	
	}else if (!validateEmail(email.trim())) {
		jQuery("#error").html("Please enter valid emaild address");
		return false;	
	}

	if(city == ""){
		jQuery("#error").html("Please enter location information");	
		return false;		
	}

	if(agentname != "" && phone != "" && email != "" && city != ""){
		if (validateEmail(email.trim())) {
			if (validatePhone(phone.trim())) {
				return true;
			
			} else {
				jQuery("#error").html("Please enter valid phone no");
			}
			
		} else {
			jQuery("#error").html("Please enter valid emaild address");
		}
	}
	return false;
}


function callConfirmation(url){

	var ans = confirm("Sure, do you want to delete this agent?");
	if(ans){
		window.location.href = url;
	}
}

function clearError()
{

		jQuery("#error").html("");
		jQuery(".msg h4").html("");		
	
}


function findRows(table,searchText1,col1,searchText2,col2,searchText3,col3) {

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
	
  var found1=false,found2=false,found3=false;
  
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
          
            
            found=found1 && found2 && found3;
        anyFound = anyFound || found;
//alert(found);
			//if(row.style.display=="none")
      	//	found=false;
        row.style.display = found ? "table-row" : "none";
       }
        
	   
	if(col1==99 && col2==99 && col3==99)
	{
		for (; r < rows.length; r += 1) {
			row = rows.item(r);
			 row.style.display = "table-row" ;}
	}
    //document.getElementById('no_res').style.display = anyFound ? "none" : "block";
var x = document.getElementById("agent_list").rows.length;
//alert("x"+x);
var cnt=0;

for(i=0;i<x;i=i+1)
{
 var y=document.getElementById("agent_list").rows[i].style.display;

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
    var searchText1 = document.getElementById('searchAN').value,
        searchText3 = document.getElementById('searchEM').value,
        searchText2= document.getElementById('searchPH').value,
        targetTable = document.getElementById('agent_list');
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
	col3=3;
	}
  else{col3=99;
}

  	

	//alert(searchText);
    findRows(targetTable,searchText1,col1,searchText2,col2,searchText3,col3);

}

//document.getElementById("search").onclick = performSearch;
document.getElementById("searchAN").onkeyup = performSearch;
document.getElementById("searchEM").onkeyup = performSearch;
document.getElementById("searchPH").onkeyup = performSearch;

/* function confirmDelAgent(Delid)
{

 swal({
 		title:' ', 
  text: 'Do you really want to remove this Agent?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Remove",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input1 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "delAgentid").val(Delid);
var input2 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "delAgent").val(true);
jQuery('#frm2').append(jQuery(input1));
jQuery('#frm2').append(jQuery(input2));
 jQuery("#frm2").submit();

}); 

}*/

function confirmDelAgent(Delid)
{

 swal({
 		title:' ', 
  text: 'Do you really want to remove this Agent?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Remove",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input1 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "delAgentid").val(Delid);
var input2 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "delAgent").val(true);
jQuery('#frm2').append(jQuery(input1));
jQuery('#frm2').append(jQuery(input2));
 jQuery("#frm2").submit();

}); 

}

function confirmEditAgent(Editid)
{

 swal({
 		title:' ', 
  text: 'Do you want to edit this Agent?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Edit",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input1 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "editAgentid").val(Editid);
var input2 = jQuery("<input>")
               .attr("type", "hidden")
               .attr("name", "editAgent").val(true);
jQuery('#frm2').append(jQuery(input1));
jQuery('#frm2').append(jQuery(input2));
 jQuery("#frm2").submit();

}); 

}

if(jQuery("tr#no_res_div td").html()=="")
	jQuery("tr#no_res_div").hide();
</script>
<?php include("footer-admin.php"); ?>

