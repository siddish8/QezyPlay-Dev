<?php 
include('header.php');
?>
<article class="content items-list-page">

<?php
$msg = ""; $action = "Add";

if(isset($_REQUEST['delAgent'])){

	$id = $_REQUEST['delAgentid'];


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

	//$city=trim($_POST['city']);
	//$cityArray = explode(',', $city); //uncomment
		//echo $city;
	
	$number=trim($_POST['number']);

	//echo $number;
	$city="";
	for($i=1;$i<=$number;$i++)
		{
			$city.=$_POST['city-'.$i]."-".$_POST['state-'.$i]."-".$_POST['country-'.$i].",";
		}
	$city=rtrim($city,',');
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
			/*if($cityAr[0]!="" and $cityAr[1]!="" and $cityAr[2]!="")
			{$stmt23 = $dbcon->prepare('INSERT INTO agent_location_info(agent_id,location,region,country) VALUES("'.$agent_id.'","'.$cityAr[0].'","'.$cityAr[1].'","'.$cityAr[2].'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt23->execute();	}*/
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
	//$city=trim($_POST['city']);

	$number=trim($_POST['number']);

	$city="";
	for($i=1;$i<=$number;$i++)
		{
			$city.=$_POST['city-'.$i]."-".$_POST['state-'.$i]."-".$_POST['country-'.$i].",";
		}
	$city=rtrim($city,',');
	//$cityArray = explode(',', $city);
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
		
		//print_r($locationsArray);
		foreach($locationsArray as $locAr)
		{
			$cityAr=explode('-',$locAr);
			/*if($cityAr[0]!="" and $cityAr[1]!="" and $cityAr[2]!="")
			{$stmt23 = $dbcon->prepare('INSERT INTO agent_location_info(agent_id,location,region,country) VALUES("'.$agent_id.'","'.$cityAr[0].'","'.$cityAr[1].'","'.$cityAr[2].'")', array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));	
			$stmt23->execute();	}*/
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
	$locs=explode(',', $location1);
	$no_locs=sizeof($locs);
	//$location1 = "AA-BB-CC,DD-EE-FF,";
	//echo $no_locs=sizeof($locs);

		
			
	$action = "Edit";

}	

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
<h2 style="text-align:center">Agent Management</h2>
<?php		if($_SESSION['adminlevel']<1)
{ ?>	
<section class="section">
<span style="float:"></span><div class="msg" align="center" style="display:inline-block"><h4><?php echo $msg;?></h4></div>
                        <div class="row sameheight-container">
				 <div class="col-md-3">
                             
                            </div>
                            <div class="col-md-6">
                                <div id="agent-form-section" class="card card-block sameheight-item" style="height:auto;/*height: 721px;*/">
                                    <!--<div class="title-block">
                                        <h2 class="title">
						<?php echo $action;?> Agent
					</h2> </div>-->
					<div class="card card-primary">
					<div class="card-header">
                                        <div class="header-block">
                                            <p class="title"> <?php echo $action;?> Agent </p>
                                        </div>
                                    </div>
				     <div class="xoouserultra-field-value" style="">
						<div id='error' style='color:red;'";></div>
					</div>
					<div class="card-block">
                                    <form role="form" method='post'>
					<input type="hidden" id="agentid" name="agentid" value="<?php echo $id; ?>"/>
                                        <div class="form-group"> <label class="control-label">Agent Name</label> <input onclick="clearError()" type="text" value="<?php echo @$agentname1;?>" id="agentname" name="agentname" class="form-control underlined"> </div>
                                        <div class="form-group"> <label class="control-label">Mobile</label> <input onclick="clearError()" type="text" placeholder="Ex:+919000000000" value="<?php echo @$mobile1;?>" id="phone" name="phone" class="form-control underlined"> </div>
                                        <div class="form-group"> <label class="control-label">Email</label> <input onclick="clearError()" type="text" value="<?php echo @$email1;?>" id="email" name="email" class="form-control underlined" > </div>
                                      <!--  <div class="form-group"> <label class="control-label">Disabled Input</label> <input type="text" disabled="disabled" class="form-control underlined" placeholder="Disabled input text"> </div>
                                        <div class="form-group"> <label class="control-label">Static control</label>
                                            <p class="form-control-static underlined">email@example.com</p>
                                        </div>
                                        <div class="form-group"> <label class="control-label">Readonly Input</label> <input type="text" readonly="readonly" class="form-control underlined" value="Readonly input text"> </div>-->
                                        <div id="loc" class="form-group"> <label class="control-label">Location</label> <input class="btn btn-primary btn-sm" type="button" onclick="return add_location_field()" id="add_location" value="Add Another Location" /><br>
						<?php if($action=="Edit"){?>
						<input type="hidden" id="number" name="number" value="<?php echo @(int)$no_locs;?>"/>
						<?php if((int)$no_locs>0)  {
							$i=1;
							foreach($locs as $locAr)
							{
							$cityAr=explode('-',$locAr);?>
							<p>Location <?php echo $i; ?>:</p>
							<label class="control-label">City</label> <input onclick="clearError()" class="form-control underlined" type="text" id='city-<?php echo $i?>' name='city-<?php echo $i?>' value="<?php echo @$cityAr[0];?>">
						<label class="control-label">State</label> <input onclick="clearError()" class="form-control underlined" type="text" id='state-<?php echo $i?>' name='state-<?php echo $i?>' value="<?php echo @$cityAr[1];?>">
						<label class="control-label">Country</label> <input onclick="clearError()" class="form-control underlined" type="text" id='country-<?php echo $i?>' name='country-<?php echo $i?>' value="<?php echo @$cityAr[2];?>"> 
							
							<?
							$i=$i+1;
							}
						?>
							

						<?php }
							}
							if($action=="Add") {?>
						 <input type="hidden" id="number" name="number" value="1"/>
						<p>Location 1:</p>
						<label class="control-label">City</label> <input onclick="clearError()" class="form-control underlined" type="text" id='city-1' name='city-1'>
						<label class="control-label">State</label> <input onclick="clearError()" class="form-control underlined" type="text" id='state-1' name='state-1'>
						<label class="control-label">Country</label> <input onclick="clearError()" class="form-control underlined" type="text" id='country-1' name='country-1'> 	
						<?php } ?>				
						<!--textarea rows="3" onclick="clearError()" rows="3" cols="35" name="city" id="city" placeholder="Ex: city1-state1-country1,city2-state2-country2" class="form-control underlined"><?php echo @$location1;?></textarea--> </div>
						<span id="location"></span>
					<div class="xoouserultra-field-value" style="padding-left: 10px;">
								<?php if($action=="Edit"){?>
								<input type="hidden" name="editAgent"  value="true" />
								<input type="hidden" name="editAgentid"  value="<?php echo @$agentid1;?>" />
								<?php } ?>
								<input class="btn btn-primary" type="submit" onclick="return callsubmit();" name="<?php echo $action;?>_Submit"  value="Submit" />
								<?php if($action=="Edit"){?>
								<!--form id="" method="post"--><!--input type="submit" name="Agent_Back"  value="Back" /><!--/form-->					<a class="btn btn-primary" href="<?php echo SITE_URL.'/qp1/agent-management.php'?>">Back</a>
   								<?php } ?>
							</div>
							
	
                                    </form>
				</div>
				</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                             
                            </div>
                        </div>
                    </section>
<?php }

?>

<section class="section">
 <div class="col-md-3">
                             
                            </div>
<div id="search_agent" align="center" style="margin:15px auto;" class="col-md-6">
		Search Agents List using any criteria: <br /> 
		<div class="form-group"> <!--<label class="control-label">Input Text</label> -->
		<input placeholder="Enter agent-name" id="searchAN" type="text" name="searchAN" class="form-control boxed"><span class="_or"> </span> 
	</div>
	<div class="form-group">   
		<input placeholder="Enter agent-phone" id="searchPH" type="text" name="searchPH" class="form-control boxed" /> <span class="_or"> </span>
	</div>
	<div class="form-group"> 
		<input placeholder="Enter agent-email" id="searchEM" type="text" name="searchEM" class="form-control boxed" /> 
			</div>	
		</div>
 <div class="col-md-3">
                             
                            </div>
   </section>
<section class="section">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-block">
                                        <div class="card-title-block">
                                            <h3 class="title">
							Agents List
						</h3> </div>
                                        <section class="example">		<!-- <div class="table-responsive"	style="max-height: 400px; overflow-y: scroll;"> -->

		<form id="frm2" method="post">
		 <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover">
		<!--<table style="overflow-x:auto;min-width:50%;max-width:100% !important;" width="100%" border="0" cellspacing="0" cellpadding="0">-->
			<thead>
				<tr>					
					<th>Agent Name</th>
					<th>Mobile</th>
					<th>Location</th>
					<th>E-mail</th>
					
					<?php		if($_SESSION['adminlevel']<1)
{ ?> <th>Auth Key</th><th> Action  <i class="fa fa-cog"></i></th> <?php } ?>
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
				<td style="width: 200px;color:blue;" class="level_name"><a target="_blank" href="agent-subscribers-list.php?agentid=<?php echo $id ?>"><?php echo $agentname;?></a></td>
				<td style="width: 175px;"><?php echo $mobile;?></td>
				<td style="width: 300px;"><?php echo $location?></td>
				<td style="width: 185px;"><?php echo $email;?></td>
				
<?php		if($_SESSION['adminlevel']<1)
{ ?>
				<td style="width: 150px;"><?php echo $authkey;?></td>
				<td style="width: 100px;">

<a style="cursor:pointer;color:blue;" id="editAgent" name="editAgent" onclick="return confirmEditAgent(<?php echo $id;?>)"><em title="Edit" class="fa fa-pencil"></em></a>
&nbsp;
<a style="cursor:pointer;color:blue;" id="removeAgent" name="removeAgent" onclick="return confirmDelAgent(<?php echo $id;?>)"><i title="Remove" class="fa fa-trash-o "></i></a>				

</td>
<?php } ?>				
</tr>
			<?php } ?>
			</tbody>
			<tr id="no_res_div" align="center" style="margin: 5px auto;
    border-bottom: 1px solid #999;
    max-width: 80%;
    font-size: 15px;"><td colspan="6" id="no_res" style="margin:0 auto;text-align:center"></td></tr>
		</table></div></form>
 </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
		


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
	
	$("#agent-form-section").css("height","auto");
	
	var email = $("#email").val();
	var agentname = $("#agentname").val();
	var phone = $("#phone").val();
	var city = $("#city").val();


	if(agentname == ""){
		$("#error").html("Please enter agent name");
		return false;		
	}
			
	if(phone == ""){
		$("#error").html("Please enter phone no");
		return false;		
	}else if (!validatePhone(phone.trim())) {

		$("#error").html("Please enter valid phone no");
		return false;
			
			}


	 if(email == ""){
		$("#error").html("Please enter email address");	
		return false;	
	}else if (!validateEmail(email.trim())) {
		$("#error").html("Please enter valid emaild address");
		return false;	
	}

	if(city == ""){
		$("#error").html("Please enter location information");	
		return false;		
	}

	if(agentname != "" && phone != "" && email != "" && city != ""){
		if (validateEmail(email.trim())) {
			if (validatePhone(phone.trim())) {
				return true;
			
			} else {
				$("#error").html("Please enter valid phone no");
			}
			
		} else {
			$("#error").html("Please enter valid emaild address");
		}
	}
	return false;

}



function clearError()
{

		$("#error").html("");
		$(".msg h4").html("");		
	
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
$("#no_res").html("NO SEARCH RESULTS");
$("#no_res_div").show();
}
else
{
$("#no_res").html("");
$("#no_res_div").hide();
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


function confirmDelAgent(Delid)
{

 swal({
 		title:' ', 
  text: 'Do you really want to remove this Agent?',
  type: 'warning',
  
  showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Remove",   cancelButtonText: "Cancel",  closeOnCancel: true
},function(){ 

var input1 = $("<input>")
               .attr("type", "hidden")
               .attr("name", "delAgentid").val(Delid);
var input2 = $("<input>")
               .attr("type", "hidden")
               .attr("name", "delAgent").val(true);
$('#frm2').append($(input1));
$('#frm2').append($(input2));
 $("#frm2").submit();

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

var input1 = $("<input>")
               .attr("type", "hidden")
               .attr("name", "editAgentid").val(Editid);
var input2 = $("<input>")
               .attr("type", "hidden")
               .attr("name", "editAgent").val(true);
$('#frm2').append($(input1));
$('#frm2').append($(input2));
 $("#frm2").submit();

}); 

}




function add_location_field()
{

var i;
i=$("#number").val();
j=parseInt(i)+1;
$("#number").val(j);


var head=$("<p>")
		.html("Location "+j+":");		
var input01 =$("<label>")
		.attr("class","control-label").html("City");
var input1 = $("<input>")
		.attr("type", "text")
		.attr("id", "city-"+j)
               .attr("name", "city-"+j)
		.attr("onclick", "clearError()")
		.attr("class", "form-control underlined");

var input02 =$("<label>")
		.attr("class","control-label").html("State");
var input2 = $("<input>")
		.attr("type", "text")
               .attr("id", "state-"+j)
               .attr("name", "state-"+j)
		.attr("onclick", "clearError()")
		.attr("class", "form-control underlined");

var input03 =$("<label>")
		.attr("class","control-label").html("Country");
var input3 = $("<input>")
		.attr("type", "text")
               .attr("id", "country-"+j)
               .attr("name", "country-"+j)
		.attr("onclick", "clearError()")
		.attr("class", "form-control underlined");

$("#loc").append(head);
$("#loc").append(input01);
$("#loc").append(input1);
$("#loc").append(input02);
$("#loc").append(input2);
$("#loc").append(input03);
$("#loc").append(input3);
$("#agent-form-section").css("height","auto");

//$("#location").closest("div").append(input3);

}

$("#agent-form-section").css("height","auto");

if($("tr#no_res_div td").html()=="")
	$("tr#no_res_div").hide();
</script>
</article>
<?php
include('footer.php');
?>
